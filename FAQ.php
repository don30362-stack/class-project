<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/config/conn_db.php');
?>
<!doctype html>
<html lang="zh">

<head>
  <?php require_once(__DIR__ . '/includes/headfile.php'); ?>
</head>

<body>
    <section id="header">
    <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="breadcrumb">
    <?php require_once(__DIR__ . '/components/breadcrumb.php'); ?>
    </section>

    <main class="faq-page py-5 py-lg-6">
      <div class="container">
        <header class="faq-header text-center">
          <span>SUPPORT / FAQ</span>
          <h1>常見問題</h1>
          <p>以下整理目前作品集網站可使用的購物、會員與訂單功能。</p>
        </header>

        <div class="accordion faq-accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true" aria-controls="faqOne"><span>01</span>如何購買商品？</button></h2>
            <div id="faqOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">您可以瀏覽商品分類與詳細資訊，先將商品加入購物車並調整數量；正式進入結帳頁時需要登入會員，確認本次收件資訊後即可使用貨到付款建立訂單。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo"><span>02</span>一定要先登入才能加入購物車嗎？</button></h2>
            <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">不需要。訪客可以先加入商品；之後登入或完成註冊時，系統會把同一瀏覽 Session 的匿名購物車合併到會員購物車。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree"><span>03</span>支援哪些付款方式？</button></h2>
            <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">目前作品只實作貨到付款，不提供信用卡或 ATM 轉帳。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour"><span>04</span>運費如何計算？</button></h2>
            <div id="faqFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">目前每張訂單固定收取 NT$100 運費，結帳頁會分別顯示商品小計、運費與訂單總額。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive"><span>05</span>商品數量有限制嗎？</button></h2>
            <div id="faqFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">有。每項商品在購物車中最多可加入 49 件。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSix" aria-expanded="false" aria-controls="faqSix"><span>06</span>如何查看訂單？</button></h2>
            <div id="faqSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">訂單建立成功後，可從登入會員選單進入「我的訂單」，查看訂單紀錄、付款方式、收件資訊與商品明細。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSeven" aria-expanded="false" aria-controls="faqSeven"><span>07</span>結帳時修改收件地址會更改會員資料嗎？</button></h2>
            <div id="faqSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">不會。結帳時填寫的收件人、手機與地址只會保存為該筆訂單的資料快照，不會回寫會員註冊時保存的地址。</div></div>
          </div>
        </div>

        <p class="faq-disclaimer"><i class="fa-regular fa-lightbulb"></i> Home Fitness 為作品集中的虛構品牌，頁面內容用來呈現產品設計與網站開發成果。</p>
      </div>
    </main>

    <section id="why-choose-us" class="py-4 py-md-5">
      <?php require_once(__DIR__ . '/components/why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-4 py-md-5 position-relative d-flex align-items-center">
    <?php require_once(__DIR__ . '/components/footer_cta.php'); ?>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
    <?php require_once(__DIR__ . '/components/footer.php'); ?>
    </section>



    <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>


</body>

</html>
