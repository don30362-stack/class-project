<?php
date_default_timezone_set('Asia/Taipei');

$envValues = array();
$envFile = dirname(__DIR__) . '/.env';

if (is_file($envFile)) {
    $parsedEnv = parse_ini_file($envFile, false, INI_SCANNER_RAW);

    if ($parsedEnv === false) {
        error_log('Unable to parse the local environment file.');
        http_response_code(500);
        exit('系統設定錯誤，請聯絡管理人員。');
    }

    $envValues = $parsedEnv;
}

// Read each value from the system environment first, then fall back to .env.
$getConfigValue = static function (string $name) use ($envValues): ?string {
    $systemValue = getenv($name);

    if ($systemValue !== false) {
        return $systemValue;
    }

    if (array_key_exists($name, $envValues)) {
        return (string)$envValues[$name];
    }

    return null;
};

$dbHost = $getConfigValue('DB_HOST');
$dbPort = $getConfigValue('DB_PORT');
$dbName = $getConfigValue('DB_DATABASE');
$dbCharset = $getConfigValue('DB_CHARSET');
$dbUsername = $getConfigValue('DB_USERNAME');
$dbPassword = $getConfigValue('DB_PASSWORD');

$requiredConfig = array($dbHost, $dbPort, $dbName, $dbCharset, $dbUsername, $dbPassword);
if (in_array(null, $requiredConfig, true)) {
    error_log('One or more required database environment variables are missing.');
    http_response_code(500);
    exit('系統設定不完整，請聯絡管理人員。');
}

if (!ctype_digit($dbPort) || (int)$dbPort < 1 || (int)$dbPort > 65535) {
    error_log('The configured database port is invalid.');
    http_response_code(500);
    exit('系統設定錯誤，請聯絡管理人員。');
}

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    $dbHost,
    $dbPort,
    $dbName,
    $dbCharset
);

try {
    $link = new PDO($dsn, $dbUsername, $dbPassword, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ));
} catch (PDOException $e) {
    error_log('Database connection failed.');
    http_response_code(500);
    exit('系統暫時無法連線，請稍後再試。');
}
?>
