# 專案目錄結構

主要瀏覽頁面保留在根目錄，因此既有的公開網址不變。內部檔案依用途分類：

| 目錄 | 用途 |
| --- | --- |
| `actions/` | 修改登入、購物車等伺服器狀態的處理程式 |
| `api/` | 表單驗證、地址查詢及上傳等資料端點 |
| `assets/images/` | 尚未被目前頁面引用的舊圖片素材；預設不納入 Git |
| `assets/js/` | 專案 JavaScript 與表單驗證程式 |
| `assets/vendor/` | 備用 jQuery 等本機第三方套件 |
| `components/` | 導覽列、頁尾、商品卡片等 PHP 畫面元件 |
| `config/` | 資料庫連線設定 |
| `css/` | 按元件拆分的 CSS |
| `database/` | 可公開的範例資料庫，以及被忽略的本機完整備份 |
| `docs/` | 維護、安全性及舊版參考文件 |
| `includes/` | 頁面載入入口與共用 PHP 函式 |
| `product_img/` | 商品與網站正式圖片；納入 Git 以便重建網站 |
| `uploads/` | 只追蹤安全設定與預設頭像，忽略會員上傳內容 |

## 引用原則

PHP 伺服器端引用使用 `__DIR__` 組成絕對檔案路徑，避免目前工作目錄影響 `require_once`。HTML、JavaScript 與重新導向網址則以瀏覽器所見的根目錄頁面為基準。

主要頁面新增共用區塊時，從 `components/` 引用；純函式或載入清單放在 `includes/`。會修改資料的請求放在 `actions/`，回傳表單或查詢資料的端點放在 `api/`。

## 舊版與第三方檔案

舊版麵包屑保存在 `docs/legacy/breadcrumb_old.php.txt`，副檔名改為文字以避免伺服器執行。`docs/legacy/bs53-jquery-cdn.html` 是舊 CDN 參考頁。未使用的本機 jQuery 3.6 保存在 `assets/vendor/jquery/`；目前頁面仍由 `includes/jsfile.php` 載入 CDN jQuery 3.7.1。

商品燈箱使用 MIT 授權的 PhotoSwipe，並由固定版本 CDN 載入。版本與更新方式記錄在 `docs/dependencies.md`。
