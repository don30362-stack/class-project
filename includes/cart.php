<?php

const CART_MAX_QUANTITY = 49;
const CART_ANONYMOUS_TOKEN_SESSION_KEY = 'anonymous_cart_token';

function cartParsePositiveInteger($value): ?int
{
    if (is_int($value)) {
        return $value > 0 ? $value : null;
    }

    if (!is_string($value) || preg_match('/\A[1-9][0-9]*\z/D', $value) !== 1) {
        return null;
    }

    $parsedValue = filter_var($value, FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1),
    ));

    return $parsedValue === false ? null : $parsedValue;
}

function cartParseQuantity($value): ?int
{
    $quantity = cartParsePositiveInteger($value);

    return $quantity !== null && $quantity <= CART_MAX_QUANTITY ? $quantity : null;
}

function cartIsAuthenticated(): bool
{
    return isset($_SESSION['login'], $_SESSION['emailid'])
        && $_SESSION['login'] === true
        && cartParsePositiveInteger($_SESSION['emailid']) !== null;
}

function cartGetAnonymousToken(bool $create = false): ?string
{
    $token = $_SESSION[CART_ANONYMOUS_TOKEN_SESSION_KEY] ?? null;

    if (is_string($token) && preg_match('/\A[0-9a-f]{64}\z/D', $token) === 1) {
        return $token;
    }

    unset($_SESSION[CART_ANONYMOUS_TOKEN_SESSION_KEY]);

    if (!$create) {
        return null;
    }

    $token = bin2hex(random_bytes(32));
    $_SESSION[CART_ANONYMOUS_TOKEN_SESSION_KEY] = $token;

    return $token;
}

/**
 * @return array{type: string, emailid?: int, anonymous_token_hash?: string}
 */
function cartCurrentOwner(bool $createAnonymous = false): array
{
    if (cartIsAuthenticated()) {
        return array(
            'type' => 'member',
            'emailid' => cartParsePositiveInteger($_SESSION['emailid']),
        );
    }

    $token = cartGetAnonymousToken($createAnonymous);
    if ($token === null) {
        return array('type' => 'none');
    }

    return array(
        'type' => 'anonymous',
        'anonymous_token_hash' => hash('sha256', $token),
    );
}

/**
 * @return array{condition: string, params: array<string, int|string>}
 */
function cartOwnerSql(array $owner, string $tableAlias = 'c'): array
{
    $prefix = $tableAlias === '' ? '' : $tableAlias . '.';

    if ($owner['type'] === 'member') {
        return array(
            'condition' => $prefix . 'emailid = :owner_emailid'
                . ' AND ' . $prefix . 'anonymous_token_hash IS NULL',
            'params' => array(':owner_emailid' => $owner['emailid']),
        );
    }

    if ($owner['type'] === 'anonymous') {
        return array(
            'condition' => $prefix . 'anonymous_token_hash = :owner_anonymous_token_hash'
                . ' AND ' . $prefix . 'emailid IS NULL',
            'params' => array(':owner_anonymous_token_hash' => $owner['anonymous_token_hash']),
        );
    }

    return array('condition' => '1 = 0', 'params' => array());
}

function cartProductIsPurchasable(PDO $link, int $productId, bool $forUpdate = false): bool
{
    $statement = $link->prepare(
        'SELECT p_id FROM product WHERE p_id = :p_id AND p_open = 1 LIMIT 1'
        . ($forUpdate ? ' FOR UPDATE' : '')
    );
    $statement->execute(array(':p_id' => $productId));

    return $statement->fetchColumn() !== false;
}

/**
 * Merge the current anonymous open cart into a member cart atomically.
 */
function mergeAnonymousCartIntoMember(PDO $link, int $emailId, bool $manageTransaction = true): bool
{
    $token = cartGetAnonymousToken(false);
    if ($token === null) {
        return true;
    }

    $tokenHash = hash('sha256', $token);

    try {
        if ($manageTransaction) {
            $link->beginTransaction();
        } elseif (!$link->inTransaction()) {
            throw new RuntimeException('Registration cart merge requires an active transaction.');
        }

        $anonymousStatement = $link->prepare(
            'SELECT cartid, p_id, qty
             FROM cart
             WHERE anonymous_token_hash = :token_hash
               AND emailid IS NULL
               AND orderid IS NULL
             ORDER BY cartid
             FOR UPDATE'
        );
        $anonymousStatement->execute(array(':token_hash' => $tokenHash));
        $anonymousRows = $anonymousStatement->fetchAll(PDO::FETCH_ASSOC);

        $memberStatement = $link->prepare(
            'SELECT cartid, p_id, qty
             FROM cart
             WHERE emailid = :emailid
               AND anonymous_token_hash IS NULL
               AND orderid IS NULL
             ORDER BY cartid
             FOR UPDATE'
        );
        $memberStatement->execute(array(':emailid' => $emailId));
        $memberRows = $memberStatement->fetchAll(PDO::FETCH_ASSOC);

        $groupRows = static function (array $rows): array {
            $groups = array();
            foreach ($rows as $row) {
                $productId = (int)$row['p_id'];
                if (!isset($groups[$productId])) {
                    $groups[$productId] = array('ids' => array(), 'qty' => 0);
                }
                $groups[$productId]['ids'][] = (int)$row['cartid'];
                $groups[$productId]['qty'] = min(
                    CART_MAX_QUANTITY,
                    $groups[$productId]['qty'] + max(0, (int)$row['qty'])
                );
            }

            return $groups;
        };

        $anonymousGroups = $groupRows($anonymousRows);
        $memberGroups = $groupRows($memberRows);

        foreach ($memberGroups as $productId => $group) {
            $keepId = array_shift($group['ids']);
            $update = $link->prepare(
                'UPDATE cart SET qty = :qty
                 WHERE cartid = :cartid AND emailid = :emailid
                   AND anonymous_token_hash IS NULL AND orderid IS NULL'
            );
            $update->execute(array(':qty' => max(1, $group['qty']), ':cartid' => $keepId, ':emailid' => $emailId));
            foreach ($group['ids'] as $duplicateId) {
                $delete = $link->prepare(
                    'DELETE FROM cart WHERE cartid = :cartid AND emailid = :emailid
                     AND anonymous_token_hash IS NULL AND orderid IS NULL'
                );
                $delete->execute(array(':cartid' => $duplicateId, ':emailid' => $emailId));
            }
            $memberGroups[$productId]['ids'] = array($keepId);
        }

        foreach ($anonymousGroups as $productId => $group) {
            if (isset($memberGroups[$productId])) {
                $memberId = $memberGroups[$productId]['ids'][0];
                $mergedQuantity = min(CART_MAX_QUANTITY, $memberGroups[$productId]['qty'] + $group['qty']);
                $update = $link->prepare(
                    'UPDATE cart SET qty = :qty
                     WHERE cartid = :cartid AND emailid = :emailid
                       AND anonymous_token_hash IS NULL AND orderid IS NULL'
                );
                $update->execute(array(':qty' => max(1, $mergedQuantity), ':cartid' => $memberId, ':emailid' => $emailId));

                foreach ($group['ids'] as $anonymousId) {
                    $delete = $link->prepare(
                        'DELETE FROM cart WHERE cartid = :cartid
                         AND anonymous_token_hash = :token_hash
                         AND emailid IS NULL AND orderid IS NULL'
                    );
                    $delete->execute(array(':cartid' => $anonymousId, ':token_hash' => $tokenHash));
                }
                continue;
            }

            $keepId = array_shift($group['ids']);
            $claim = $link->prepare(
                'UPDATE cart
                 SET emailid = :emailid, anonymous_token_hash = NULL, qty = :qty
                 WHERE cartid = :cartid
                   AND anonymous_token_hash = :token_hash
                   AND emailid IS NULL AND orderid IS NULL'
            );
            $claim->execute(array(
                ':emailid' => $emailId,
                ':qty' => max(1, $group['qty']),
                ':cartid' => $keepId,
                ':token_hash' => $tokenHash,
            ));
            if ($claim->rowCount() !== 1) {
                throw new RuntimeException('Unable to claim anonymous cart row.');
            }

            foreach ($group['ids'] as $duplicateId) {
                $delete = $link->prepare(
                    'DELETE FROM cart WHERE cartid = :cartid
                     AND anonymous_token_hash = :token_hash
                     AND emailid IS NULL AND orderid IS NULL'
                );
                $delete->execute(array(':cartid' => $duplicateId, ':token_hash' => $tokenHash));
            }
        }

        if ($manageTransaction) {
            $link->commit();
            unset($_SESSION[CART_ANONYMOUS_TOKEN_SESSION_KEY]);
        }

        return true;
    } catch (Throwable $exception) {
        if ($manageTransaction && $link->inTransaction()) {
            $link->rollBack();
        }
        error_log(sprintf('Anonymous cart merge failed for member ID %d: transaction_failed', $emailId));

        return false;
    }
}
