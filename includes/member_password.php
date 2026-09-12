<?php

/**
 * Determine whether a stored value is a password hash supported by this PHP runtime.
 */
function memberPasswordIsModernHash(string $storedHash): bool
{
    $hashInfo = password_get_info($storedHash);

    return $hashInfo['algoName'] !== 'unknown';
}

/**
 * Legacy member hashes are limited to the exact format historically produced here.
 */
function memberPasswordIsLegacyMd5(string $storedHash): bool
{
    return preg_match('/\A[0-9a-f]{32}\z/D', $storedHash) === 1;
}

/**
 * Replace a password hash only if another request has not already changed it.
 */
function memberPasswordConditionalUpdate(
    PDO $link,
    int $emailId,
    string $oldHash,
    string $newHash
): bool {
    $statement = $link->prepare(
        'UPDATE member
         SET pw1 = :new_hash
         WHERE emailid = :emailid
           AND pw1 = :old_hash'
    );

    return $statement->execute(array(
        ':new_hash' => $newHash,
        ':emailid' => $emailId,
        ':old_hash' => $oldHash,
    )) && $statement->rowCount() === 1;
}

/**
 * Verify a member password and transparently upgrade an accepted stored hash.
 *
 * A legacy migration failure denies this login. A modern rehash failure allows the
 * login because the already-verified original hash remains valid in the database.
 * No password or hash is written to the server log.
 *
 * @return array{verified: bool, format: string, upgraded: bool, rehash_deferred: bool}
 */
function verifyAndUpgradeMemberPassword(
    PDO $link,
    int $emailId,
    string $plainPassword,
    string $storedHash
): array {
    $result = array(
        'verified' => false,
        'format' => 'unknown',
        'upgraded' => false,
        'rehash_deferred' => false,
    );

    if (memberPasswordIsModernHash($storedHash)) {
        $result['format'] = 'modern';

        if (!password_verify($plainPassword, $storedHash)) {
            return $result;
        }

        $result['verified'] = true;

        if (!password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
            return $result;
        }

        try {
            $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
            if (!is_string($newHash) || !memberPasswordConditionalUpdate($link, $emailId, $storedHash, $newHash)) {
                $result['rehash_deferred'] = true;
                error_log(sprintf('Member password rehash deferred for member ID %d: update_failed', $emailId));

                return $result;
            }

            $result['upgraded'] = true;
        } catch (Throwable $exception) {
            $result['rehash_deferred'] = true;
            error_log(sprintf('Member password rehash deferred for member ID %d: processing_failed', $emailId));
        }

        return $result;
    }

    if (!memberPasswordIsLegacyMd5($storedHash)) {
        return $result;
    }

    $result['format'] = 'legacy_md5';

    if (!hash_equals($storedHash, md5($plainPassword))) {
        return $result;
    }

    try {
        $newHash = password_hash($plainPassword, PASSWORD_DEFAULT);
        if (!is_string($newHash)) {
            error_log(sprintf('Member password legacy migration failed for member ID %d: hash_generation_failed', $emailId));

            return $result;
        }

        if (!memberPasswordConditionalUpdate($link, $emailId, $storedHash, $newHash)) {
            error_log(sprintf('Member password legacy migration failed for member ID %d: update_failed', $emailId));

            return $result;
        }
    } catch (Throwable $exception) {
        error_log(sprintf('Member password legacy migration failed for member ID %d: processing_failed', $emailId));

        return $result;
    }

    $result['verified'] = true;
    $result['upgraded'] = true;

    return $result;
}
