<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function uploadFailure($message, $status = 400)
{
    http_response_code($status);
    echo json_encode(array('success' => 'false', 'msg' => '', 'error' => $message, 'fileName' => ''), JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    uploadFailure('請使用 POST 上傳檔案', 405);
}

$file = $_FILES['file1'] ?? null;
if (!is_array($file) || !isset($file['error'], $file['tmp_name']) ||
    !is_int($file['error']) || !is_string($file['tmp_name'])) {
    uploadFailure('未收到有效的上傳檔案，或請求超過伺服器大小限制');
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    $messages = array(
        UPLOAD_ERR_INI_SIZE => '檔案超過伺服器大小限制',
        UPLOAD_ERR_FORM_SIZE => '檔案超過表單大小限制',
        UPLOAD_ERR_PARTIAL => '檔案未完整上傳，請重試',
        UPLOAD_ERR_NO_FILE => '請選擇要上傳的檔案',
        UPLOAD_ERR_NO_TMP_DIR => '伺服器無法建立上傳暫存檔',
        UPLOAD_ERR_CANT_WRITE => '伺服器無法寫入上傳檔案',
        UPLOAD_ERR_EXTENSION => '伺服器已停止此檔案上傳',
    );
    uploadFailure($messages[$file['error']] ?? '上傳發生錯誤');
}

$tempPath = $file['tmp_name'];
if (!is_uploaded_file($tempPath)) {
    uploadFailure('無效的上傳暫存檔');
}

// Read the actual temporary file; do not trust client metadata.
$size = filesize($tempPath);
if ($size === false || $size === 0 || $size > 5 * 1024 * 1024) {
    uploadFailure('檔案必須大於 0 且不得超過 5 MB', 413);
}
if (!class_exists('finfo')) {
    uploadFailure('伺服器尚未啟用檔案類型驗證', 500);
}
$allowedTypes = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif');
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($tempPath);
$image = @getimagesize($tempPath);
if (!isset($allowedTypes[$mime]) || $image === false ||
    ($image['mime'] ?? '') !== $mime || $image[0] <= 0 || $image[1] <= 0) {
    uploadFailure('只允許有效的 JPG、PNG 或 GIF 圖片', 415);
}
if ($image[0] > 10000 || $image[1] > 10000 || $image[0] * $image[1] > 25000000) {
    uploadFailure('圖片尺寸不得超過 10000 像素，總像素不得超過 2500 萬');
}

$uploadDirectory = dirname(__DIR__) . '/uploads';
if (!is_dir($uploadDirectory) || !is_writable($uploadDirectory)) {
    uploadFailure('上傳目錄無法寫入', 500);
}
try {
    $fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mime];
} catch (Throwable $error) {
    uploadFailure('無法建立安全的檔案名稱', 500);
}
if (!move_uploaded_file($tempPath, $uploadDirectory . '/' . $fileName)) {
    uploadFailure('無法完成檔案上傳', 500);
}
echo json_encode(array('success' => 'true', 'msg' => '完成檔案上傳', 'error' => '', 'fileName' => $fileName), JSON_UNESCAPED_UNICODE);
