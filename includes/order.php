<?php

require_once __DIR__ . '/cart.php';

const ORDER_PAYMENT_CASH_ON_DELIVERY = 1;
const ORDER_STATUS_PENDING = 1;
const ORDER_SHIPPING_FEE = 100;
const ORDER_SUBMISSION_SESSION_KEY = 'checkout_submission_token_hash';

function orderPaymentLabel(int $payment): string
{
    return $payment === ORDER_PAYMENT_CASH_ON_DELIVERY ? '貨到付款' : '未知';
}

function orderStatusLabel(int $status): string
{
    return $status === ORDER_STATUS_PENDING ? '待處理' : '未知';
}

function orderDecimal(int $amount): string
{
    return number_format($amount, 2, '.', '');
}

function orderDisplayMoney($value): string
{
    $money = is_int($value) ? (string)$value : (is_string($value) ? $value : '0');
    if (!preg_match('/\A([0-9]+)(?:\.([0-9]{2}))?\z/', $money, $matches)) {
        return '0';
    }

    $integer = ltrim($matches[1], '0');
    $integer = $integer === '' ? '0' : $integer;
    $grouped = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $integer) ?? $integer;
    $cents = $matches[2] ?? '00';

    return $cents === '00' ? $grouped : $grouped . '.' . $cents;
}

function orderCreateSubmissionToken(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION[ORDER_SUBMISSION_SESSION_KEY] = hash('sha256', $token);

    return $token;
}

function orderSubmittedTokenHash($token): ?string
{
    if (!is_string($token) || preg_match('/\A[0-9a-f]{64}\z/D', $token) !== 1) {
        return null;
    }

    return hash('sha256', $token);
}

function orderSessionTokenMatches(string $submittedHash): bool
{
    $sessionHash = $_SESSION[ORDER_SUBMISSION_SESSION_KEY] ?? null;

    return is_string($sessionHash)
        && preg_match('/\A[0-9a-f]{64}\z/D', $sessionHash) === 1
        && hash_equals($sessionHash, $submittedHash);
}

function orderConsumeSubmissionToken(string $submittedHash): void
{
    if (orderSessionTokenMatches($submittedHash)) {
        unset($_SESSION[ORDER_SUBMISSION_SESSION_KEY]);
    }
}

function orderFindBySubmissionHash(PDO $link, int $emailId, string $tokenHash): ?string
{
    $statement = $link->prepare(
        'SELECT orderid FROM uorder
         WHERE submission_token_hash = :token_hash AND emailid = :emailid
         LIMIT 1'
    );
    $statement->execute(array(':token_hash' => $tokenHash, ':emailid' => $emailId));
    $orderId = $statement->fetchColumn();

    return is_string($orderId) ? $orderId : null;
}

function orderGetMemberAddress(PDO $link, int $emailId, bool $forUpdate = false): ?array
{
    $statement = $link->prepare(
        'SELECT addressid, cname, mobile, myZip, city_id, town_id, address
         FROM addbook
         WHERE emailid = :emailid
         ORDER BY setdefault DESC, create_date DESC, addressid DESC
         LIMIT 1' . ($forUpdate ? ' FOR UPDATE' : '')
    );
    $statement->execute(array(':emailid' => $emailId));
    $address = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$address) {
        return null;
    }

    $location = null;
    if ($address['city_id'] !== null && $address['town_id'] !== null) {
        $location = orderFindLocation(
            $link,
            (string)$address['city_id'],
            (string)$address['town_id'],
            (string)$address['myZip']
        );
    }

    if ($location === null && is_string($address['myZip']) && $address['myZip'] !== '') {
        $location = orderFindUniqueLocationByPostalCode($link, $address['myZip']);
    }

    $address['city_id'] = $location['city_id'] ?? null;
    $address['town_id'] = $location['town_id'] ?? null;
    $address['city_name'] = $location['city_name'] ?? null;
    $address['town_name'] = $location['town_name'] ?? null;

    return $address;
}

function orderFindUniqueLocationByPostalCode(PDO $link, string $postalCode): ?array
{
    $statement = $link->prepare(
        'SELECT c.AutoNo AS city_id, t.townNo AS town_id,
                c.Name AS city_name, t.Name AS town_name, t.Post AS postal_code
         FROM town AS t
         INNER JOIN city AS c ON c.AutoNo = t.AutoNo
         WHERE t.Post = :postal_code AND t.State = 0 AND c.State = 0
         ORDER BY t.townNo
         LIMIT 2'
    );
    $statement->execute(array(':postal_code' => $postalCode));
    $locations = $statement->fetchAll(PDO::FETCH_ASSOC);

    return count($locations) === 1 ? $locations[0] : null;
}

function orderFindLocation(PDO $link, string $cityId, string $townId, string $postalCode): ?array
{
    if (
        preg_match('/\A[1-9][0-9]*\z/D', $cityId) !== 1
        || preg_match('/\A[1-9][0-9]*\z/D', $townId) !== 1
        || preg_match('/\A[0-9]{3,10}\z/D', $postalCode) !== 1
    ) {
        return null;
    }

    $statement = $link->prepare(
        'SELECT c.AutoNo AS city_id, t.townNo AS town_id,
                c.Name AS city_name, t.Name AS town_name, t.Post AS postal_code
         FROM town AS t
         INNER JOIN city AS c ON c.AutoNo = t.AutoNo
         WHERE c.AutoNo = :city_id AND t.townNo = :town_id
           AND t.Post = :postal_code AND t.State = 0 AND c.State = 0
         LIMIT 1'
    );
    $statement->execute(array(
        ':city_id' => (int)$cityId,
        ':town_id' => (int)$townId,
        ':postal_code' => $postalCode,
    ));
    $location = $statement->fetch(PDO::FETCH_ASSOC);

    return $location ?: null;
}

function orderRequestString(array $source, string $key): ?string
{
    return isset($source[$key]) && is_string($source[$key]) ? trim($source[$key]) : null;
}

function orderStringLength(string $value): ?int
{
    $length = preg_match_all('/./us', $value, $matches);

    return $length === false ? null : $length;
}

function orderValidateCheckout(PDO $link, array $post): array
{
    $recipientName = orderRequestString($post, 'recipient_name');
    $recipientPhone = orderRequestString($post, 'recipient_phone');
    $cityId = orderRequestString($post, 'city_id');
    $townId = orderRequestString($post, 'town_id');
    $postalCode = orderRequestString($post, 'postal_code');
    $recipientAddress = orderRequestString($post, 'recipient_address');

    $nameLength = $recipientName === null ? null : orderStringLength($recipientName);
    if ($nameLength === null || $nameLength < 1 || $nameLength > 30) {
        return array(false, '收件人姓名必須為 1～30 個字元。');
    }
    if ($recipientPhone === null || preg_match('/\A09[0-9]{8}\z/D', $recipientPhone) !== 1) {
        return array(false, '請輸入正確的台灣手機號碼。');
    }
    $addressLength = $recipientAddress === null ? null : orderStringLength($recipientAddress);
    if ($addressLength === null || $addressLength < 1 || $addressLength > 200) {
        return array(false, '收件地址必須為 1～200 個字元。');
    }
    if ($cityId === null || $townId === null || $postalCode === null) {
        return array(false, '請選擇有效的縣市與行政區。');
    }

    $location = orderFindLocation($link, $cityId, $townId, $postalCode);
    if ($location === null) {
        return array(false, '縣市、行政區與郵遞區號不相符。');
    }

    return array(true, array(
        'recipient_name' => $recipientName,
        'recipient_phone' => $recipientPhone,
        'postal_code' => $location['postal_code'],
        'city_name' => $location['city_name'],
        'town_name' => $location['town_name'],
        'recipient_address' => $recipientAddress,
    ));
}

function orderNextOrderId(PDO $link, DateTimeImmutable $now): string
{
    $sequenceDate = $now->format('Y-m-d');
    $insert = $link->prepare(
        'INSERT IGNORE INTO order_number_sequences (sequence_date, last_value)
         VALUES (:sequence_date, 0)'
    );
    $insert->execute(array(':sequence_date' => $sequenceDate));

    $select = $link->prepare(
        'SELECT last_value FROM order_number_sequences
         WHERE sequence_date = :sequence_date FOR UPDATE'
    );
    $select->execute(array(':sequence_date' => $sequenceDate));
    $lastValue = $select->fetchColumn();
    if ($lastValue === false || (int)$lastValue >= 9999) {
        throw new RuntimeException('daily_order_sequence_exhausted');
    }

    $nextValue = (int)$lastValue + 1;
    $update = $link->prepare(
        'UPDATE order_number_sequences SET last_value = :next_value
         WHERE sequence_date = :sequence_date'
    );
    $update->execute(array(':next_value' => $nextValue, ':sequence_date' => $sequenceDate));
    if ($update->rowCount() !== 1) {
        throw new RuntimeException('daily_order_sequence_update_failed');
    }

    return 'HF' . $now->format('Ymd') . str_pad((string)$nextValue, 4, '0', STR_PAD_LEFT);
}

function orderCreateFromMemberCart(PDO $link, int $emailId, array $post, string $tokenHash): string
{
    $timezone = new DateTimeZone('Asia/Taipei');
    $now = new DateTimeImmutable('now', $timezone);

    try {
        $link->beginTransaction();

        $address = orderGetMemberAddress($link, $emailId, true);
        [$checkoutValid, $checkoutData] = orderValidateCheckout($link, $post);
        if (!$checkoutValid) {
            throw new InvalidArgumentException($checkoutData);
        }

        $cartStatement = $link->prepare(
            'SELECT cartid, p_id, qty
             FROM cart
             WHERE emailid = :emailid AND anonymous_token_hash IS NULL AND orderid IS NULL
             ORDER BY cartid FOR UPDATE'
        );
        $cartStatement->execute(array(':emailid' => $emailId));
        $cartRows = $cartStatement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($cartRows)) {
            throw new DomainException('購物車目前沒有可結帳的商品。');
        }

        $productIds = array_values(array_unique(array_map(
            static fn(array $row): int => (int)$row['p_id'],
            $cartRows
        )));
        sort($productIds, SORT_NUMERIC);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $productStatement = $link->prepare(
            'SELECT p_id, p_name, p_price, p_open
             FROM product WHERE p_id IN (' . $placeholders . ')
             ORDER BY p_id FOR UPDATE'
        );
        $productStatement->execute($productIds);
        $products = array();
        foreach ($productStatement->fetchAll(PDO::FETCH_ASSOC) as $product) {
            $products[(int)$product['p_id']] = $product;
        }

        $items = array();
        $itemsSubtotal = 0;
        foreach ($cartRows as $cartRow) {
            $productId = (int)$cartRow['p_id'];
            $quantity = filter_var($cartRow['qty'], FILTER_VALIDATE_INT);
            $product = $products[$productId] ?? null;
            if (!$product || (int)$product['p_open'] !== 1) {
                throw new DomainException('購物車內含已下架或不存在的商品。');
            }
            if ($quantity === false || $quantity < 1 || $quantity > CART_MAX_QUANTITY) {
                throw new DomainException('購物車內含無效的商品數量。');
            }
            $priceValue = $product['p_price'];
            if (!is_int($priceValue) && (!is_string($priceValue) || preg_match('/\A[0-9]+\z/D', $priceValue) !== 1)) {
                throw new DomainException('購物車內含無效的商品價格。');
            }
            $unitPrice = (int)$priceValue;
            if ($unitPrice < 0 || $unitPrice > intdiv(PHP_INT_MAX, $quantity)) {
                throw new DomainException('購物車內含無效的商品價格。');
            }
            $subtotal = $unitPrice * $quantity;
            if ($itemsSubtotal > PHP_INT_MAX - $subtotal) {
                throw new DomainException('訂單金額超出可處理範圍。');
            }
            $itemsSubtotal += $subtotal;
            $items[] = array(
                'cartid' => (int)$cartRow['cartid'],
                'p_id' => $productId,
                'product_name' => (string)$product['p_name'],
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            );
        }

        $shippingFee = ORDER_SHIPPING_FEE;
        if ($itemsSubtotal > PHP_INT_MAX - $shippingFee) {
            throw new DomainException('訂單金額超出可處理範圍。');
        }
        $orderTotal = $itemsSubtotal + $shippingFee;
        if ($orderTotal > 9999999999) {
            throw new DomainException('訂單金額超出可處理範圍。');
        }

        $orderId = orderNextOrderId($link, $now);
        $orderStatement = $link->prepare(
            'INSERT INTO uorder
             (orderid, emailid, addressid, recipient_name, recipient_phone,
              postal_code, city_name, town_name, recipient_address,
              howpay, paystatus, status, remark, items_subtotal,
              shipping_fee, order_total, submission_token_hash, create_date)
             VALUES
             (:orderid, :emailid, :addressid, :recipient_name, :recipient_phone,
              :postal_code, :city_name, :town_name, :recipient_address,
              :howpay, NULL, :status, NULL, :items_subtotal,
              :shipping_fee, :order_total, :token_hash, :create_date)'
        );
        $orderStatement->execute(array(
            ':orderid' => $orderId,
            ':emailid' => $emailId,
            ':addressid' => $address === null ? null : (int)$address['addressid'],
            ':recipient_name' => $checkoutData['recipient_name'],
            ':recipient_phone' => $checkoutData['recipient_phone'],
            ':postal_code' => $checkoutData['postal_code'],
            ':city_name' => $checkoutData['city_name'],
            ':town_name' => $checkoutData['town_name'],
            ':recipient_address' => $checkoutData['recipient_address'],
            ':howpay' => ORDER_PAYMENT_CASH_ON_DELIVERY,
            ':status' => ORDER_STATUS_PENDING,
            ':items_subtotal' => orderDecimal($itemsSubtotal),
            ':shipping_fee' => orderDecimal($shippingFee),
            ':order_total' => orderDecimal($orderTotal),
            ':token_hash' => $tokenHash,
            ':create_date' => $now->format('Y-m-d H:i:s'),
        ));

        $itemStatement = $link->prepare(
            'INSERT INTO order_items
             (orderid, p_id, product_name, unit_price, quantity, subtotal)
             VALUES (:orderid, :p_id, :product_name, :unit_price, :quantity, :subtotal)'
        );
        foreach ($items as $item) {
            $itemStatement->execute(array(
                ':orderid' => $orderId,
                ':p_id' => $item['p_id'],
                ':product_name' => $item['product_name'],
                ':unit_price' => orderDecimal($item['unit_price']),
                ':quantity' => $item['quantity'],
                ':subtotal' => orderDecimal($item['subtotal']),
            ));
        }

        $cartIds = array_column($items, 'cartid');
        $deletePlaceholders = implode(',', array_fill(0, count($cartIds), '?'));
        $deleteStatement = $link->prepare(
            'DELETE FROM cart
             WHERE cartid IN (' . $deletePlaceholders . ')
               AND emailid = ? AND anonymous_token_hash IS NULL AND orderid IS NULL'
        );
        $deleteStatement->execute(array_merge($cartIds, array($emailId)));
        if ($deleteStatement->rowCount() !== count($cartIds)) {
            throw new RuntimeException('checked_out_cart_delete_incomplete');
        }

        $link->commit();

        return $orderId;
    } catch (Throwable $exception) {
        if ($link->inTransaction()) {
            $link->rollBack();
        }
        throw $exception;
    }
}
