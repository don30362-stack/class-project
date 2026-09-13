# 輸入驗證與輸出編碼

一般來自 request、Session 或資料庫的文字，在 HTML text 與 attribute context 輸出時必須使用 `e()`。圖片檔名必須先通過 `safeImageFilename()`；內嵌 JavaScript 值使用 `jsValue()`，不可自行拼接引號。

商品的 `p_content` 是唯一刻意直接輸出的 HTML 欄位。目前內容只由版本控制內的 sample data／受信任管理資料建立，一般會員沒有寫入介面。未來若提供後台編輯商品 HTML，必須在寫入時使用 HTML allowlist sanitizer，不可直接信任編輯器輸入。

註冊上傳成功後，伺服器產生的隨機檔名會登記在目前 Session。註冊只接受該 Session 實際上傳過的檔名；hidden input 不是信任來源。
