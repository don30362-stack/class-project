# 樣式維護與交付說明

## 載入方式

`includes/headfile.php` 先載入 Bootstrap、Font Awesome，再依原始順序直接載入 `css/` 下的 17 個樣式檔，讓瀏覽器可以平行下載。所有頁面共用相同順序，避免拆檔改變 CSS 級聯結果。

`website_p01.css` 保留為舊連結相容入口，透過 `@import` 載入相同檔案。新頁面請共用 `includes/headfile.php`，不要同時載入兩種入口。若新增或調整載入順序，兩個入口必須同步更新。

## 檔案對照

| 檔案（位於 css/） | 負責範圍 |
| --- | --- |
| navbar.css | 導覽列、商品下拉、會員選單、購物車徽章 |
| carousel.css | 首頁輪播、文案、指示燈與箭頭 |
| breadcrumb.css | 麵包屑 |
| recommend.css | 首頁推薦商品 |
| product-filter.css | 商品分類篩選 |
| product-card.css | 商品卡片 |
| pagination.css | 商品分頁 |
| product-detail.css | 商品詳細內容 |
| categories.css | 首頁分類列表 |
| recommendation-section.css | 推薦區塊容器 |
| why-us.css | 品牌優勢區塊 |
| footer-cta.css | 頁尾行動呼籲 |
| footer.css | 頁尾與社群連結 |
| cart.css | 購物車 |
| checkout.css | 結帳 |
| login.css | 登入 |
| register.css | 註冊、驗證與上傳狀態 |

## 本次整理

- 原始規則按既有邊界拆檔，保留宣告、媒體查詢與載入順序。
- 輪播選擇器限定在 `#carouselExampleCaptions`，移除該元件的 75 處 `!important`，以元件範圍和既有桌機覆蓋順序控制樣式。若更改輪播 ID，必須同步修改 CSS。
- 全部文字中的 `!important` 出現次數由 143 降為 68（包含原本已註解的宣告）。其他區塊仍有 Bootstrap 工具類別或動態定位的覆蓋需求，應逐一驗證後再移除。
- 頁尾 CTA 和登入背景圖路徑改為 `../product_img/cta_01.png`，保持拆檔後指向同一張圖片。

## 後續修改原則

在對應元件檔案內修改，避免在入口末尾追加覆蓋規則。響應式規則放在同一元件檔內，並保留由基礎到斷點的順序。新增樣式優先使用元件類別；不要擴大使用全域 `.badge`、`.container h2` 等選擇器。

新增 `!important` 前，先確認是否能移除 HTML 中衝突的 Bootstrap 工具類別，或以元件選擇器解決。確實需要保留時，註明覆蓋對象與原因。不要直接批次刪除剩餘標記。

## 驗收方式

靜態檢查可確認檔案順序、拆分內容、輪播宣告及圖片路徑；不等同於瀏覽器畫面驗證。部署需包含整個 `css/` 目錄。

在可連線資料庫的 PHP 環境，使用 375、767、768、1199、1200 和 1440px 寬度檢查：

- 首頁：輪播切換、目前指示燈、箭頭 hover、手機文案與背景圖片。
- 導覽列：手機展開、多層分類、會員選單及購物車徽章。
- 商品頁：篩選狀態、卡片 hover、分頁、商品詳情。
- 購物車與結帳：數量欄位、金額區與付款選項。
- 登入與註冊：表單焦點、錯誤訊息、驗證碼和圖片預覽。
- 開發者工具 Network：所有 CSS 與背景圖片均成功載入。
