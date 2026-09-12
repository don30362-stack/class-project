# 本機啟動方式

## 需求

- PHP 8.2 或更新版本，並啟用 PDO MySQL、fileinfo 與 GD。
- MySQL 或 MariaDB。
- 可連線至 CDN，以載入 Bootstrap、Font Awesome、jQuery 與 PhotoSwipe。

## 建立資料庫

匯入 `database/expstore.sample.sql`。這份公開範例包含完整資料表結構、商品、分類、輪播與台灣縣市資料，不包含會員、地址、購物車、訂單或管理員資料。

```bash
mysql -u root -p < database/expstore.sample.sql
```

資料庫名稱預設為 `expstore`。如果本機帳號或密碼不同，請修改 `config/conn_db.php` 的 DSN、使用者名稱與密碼。正式部署時應從環境變數讀取密碼，不應把正式密碼提交到 Git。

## 啟動 PHP

在專案根目錄執行：

```bash
php -S 127.0.0.1:8000
```

然後開啟 `http://127.0.0.1:8000/`。

## Git 內容界線

- `product_img/` 是網站固定內容，會納入 Git。
- `database/expstore.sample.sql` 是去除個資的可公開範例資料庫，會納入 Git。
- `database/expstore.sql` 是本機完整備份，可能含會員及訂單資料，因此保持忽略。
- `uploads/.htaccess` 與 `uploads/avatar.svg` 會納入 Git；其他會員上傳檔案保持忽略。
- `assets/images/` 是目前沒有使用的舊素材，保持忽略。
