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

資料庫名稱預設為 `expstore`。建議另外建立僅能存取這個資料庫的應用程式帳號，不要在正式環境使用 MySQL root 帳號。

## 設定環境變數

將公開範例複製為本機設定檔：

```bash
cp .env.example .env
```

Windows PowerShell 可使用：

```powershell
Copy-Item .env.example .env
```

依本機環境修改 `.env`：

```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expstore
DB_CHARSET=utf8mb4
DB_USERNAME=expstore_app
DB_PASSWORD=your_local_password
```

`config/conn_db.php` 會逐項優先讀取系統環境變數；只有該變數未設定時，才使用專案根目錄 `.env` 的值。因此正式部署可直接由 Web Server、容器或部署平台注入環境變數，不必建立 `.env`。

`.env` 已被 Git 忽略，不得提交。`.env.example` 只包含範例欄位與非真實憑證，可以公開。

## 啟動 PHP

在專案根目錄執行：

```bash
php -S 127.0.0.1:8000
```

然後開啟 `http://127.0.0.1:8000/`。

上述 HTTP 網址僅供 localhost 本機開發。正式部署必須設定 HTTPS，讓會員註冊與登入密碼受到傳輸層保護；在瀏覽器先計算 MD5 並不能取代 HTTPS，MD5 值本身仍可能被重播並當作等效密碼使用。

## Git 內容界線

- `product_img/` 是網站固定內容，會納入 Git。
- `database/expstore.sample.sql` 是去除個資的可公開範例資料庫，會納入 Git。
- `database/expstore.sql` 及其他非 sample 的 SQL／dump／backup 是本機完整備份，可能含管理員、會員、地址、購物車及訂單資料，因此保持忽略。
- `uploads/.htaccess` 與 `uploads/avatar.svg` 會納入 Git；其他會員上傳檔案保持忽略。
- `assets/images/` 是目前沒有使用的舊素材，保持忽略。
- `.env` 保存本機連線資訊並保持忽略；只有 `.env.example` 會納入 Git。

在提交前可使用以下指令複核：

```bash
git check-ignore .env database/expstore.sql
git check-ignore database/expstore.sample.sql
git diff --check
```

最後一條 `git check-ignore` 預期不輸出任何內容，代表公開 sample SQL 沒有被忽略。
