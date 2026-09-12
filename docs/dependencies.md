# 前端依賴

## PhotoSwipe

商品詳細頁使用 PhotoSwipe 5.4.4，CSS 與 ES module JavaScript 由 jsDelivr CDN 載入，版本固定在網址中：

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.css">
<script type="module">
    import PhotoSwipeLightbox from 'https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe-lightbox.esm.js';
</script>
```

初始化程式位於 `productDetail.php`。隱藏的商品圖片群組位於 `components/productDetailContent.php`，主圖會依目前縮圖索引呼叫 `loadAndOpen()`。PhotoSwipe 要求每張圖片提供寬高，因此 PHP 會讀取本機圖片尺寸並輸出 `data-pswp-width` 與 `data-pswp-height`。

選擇 CDN 是因為本專案沒有 Node.js 建置流程，而且 Bootstrap、jQuery 與 Font Awesome 已使用 CDN。固定完整版本號可避免上游更新在未驗證的情況下改變網站行為；升級時需同時更新 CSS 與 JavaScript 的版本，並測試主圖、縮圖切換、上一張／下一張、縮放、關閉及手機觸控操作。

PhotoSwipe 採用 MIT License，可免費用於個人與商業專案。保留本段依賴與版本資訊即可；若日後直接修改或重新散布套件原始碼，應一併保留其 MIT 授權聲明。

## 舊版移除

原專案的 Fancybox 2.1.7 與短暫評估的 Fancybox 6 均已停止載入。專案原始碼不再依賴 Fancybox。
