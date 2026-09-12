# 上傳目錄部署設定

上傳端點只接受內容類型為 JPEG、PNG、GIF 的圖片，單檔上限為 5 MiB。
PHP 的 upload_max_filesize 若更低，會優先套用；post_max_size 應大於單檔上限，預留 multipart 表單開銷。必須啟用 fileinfo。

## Apache 2.4

請部署 uploads/.htaccess，並確認該目錄允許覆寫 FileInfo、AuthConfig、Options 設定。設定將所有檔案交由靜態處理器提供，禁止 CGI 與目錄列表，並拒絕非圖片副檔名。若禁用 AllowOverride，請將相同規則放入虛擬主機的 uploads Directory 區塊。

## Nginx

Nginx 不讀取 .htaccess。請在 server 區塊加入下列規則，並將 URL 前綴改成網站實際路徑（例如 /project01/uploads/）。^~ 必須保留，以避免請求落入通用 PHP FastCGI 規則。

```nginx
location ^~ /project01/uploads/ {
    autoindex off;
    add_header X-Content-Type-Options nosniff always;
    if ($uri !~* \.(jpg|jpeg|png|gif|svg)$) { return 403; }
    try_files $uri =404;
}
```

其他伺服器也必須將 uploads 設為只提供靜態檔案，移除 PHP/CGI 執行處理器。SVG 僅為相容既有 avatar.svg；上傳 API 不接受 SVG。

部署後確認正常圖片可讀取、uploads 下的 PHP 檔案回傳 403，且不會執行。本機 PHP 開發伺服器不套用 Apache/Nginx 規則，不能用來驗證這項部署保護。
