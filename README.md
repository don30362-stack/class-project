# Home Fitness

以 PHP、MariaDB、Bootstrap 製作的居家健身器材購物網站，包含商品分類、商品詳細頁、購物車、結帳及會員功能。

## 快速啟動

1. 安裝 PHP 8.2+ 與 MySQL／MariaDB。
2. 匯入 `database/expstore.sample.sql`。
3. 複製 `.env.example` 為 `.env`，填入本機資料庫連線設定。
4. 建議建立僅具本專案所需權限的資料庫帳號，不要在正式環境使用 MySQL root 帳號。
5. 在專案根目錄執行 `php -S 127.0.0.1:8000`。
6. 開啟 `http://127.0.0.1:8000/`。

`.env`、完整資料庫備份與會員上傳內容只留在本機，不會納入 Git。可公開的範例資料庫為 `database/expstore.sample.sql`。

完整環境需求、環境變數及資料內容界線請參考 [本機啟動方式](docs/setup.md)。專案目錄說明位於 [目錄結構](docs/structure.md)，前端套件版本位於 [前端依賴](docs/dependencies.md)。
