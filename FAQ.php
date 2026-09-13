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
          <p>以下整理目前作品集網站可使用的功能，以及下一階段規劃的購物流程。</p>
        </header>

        <div class="accordion faq-accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true" aria-controls="faqOne"><span>01</span>目前要如何選購商品？</button></h2>
            <div id="faqOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">您可以瀏覽商品分類與詳細資訊，將商品加入購物車並調整數量。結帳頁目前是介面預覽，尚不會建立或送出真實訂單。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo"><span>02</span>使用購物車需要先登入嗎？</button></h2>
            <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">不需要。訪客可以先加入商品；之後登入或完成註冊時，系統會把同一瀏覽 Session 的匿名購物車合併到會員購物車。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree"><span>03</span>規劃中的付款方式有哪些？</button></h2>
            <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">本作品集只規劃貨到付款，不提供信用卡或 ATM 轉帳。正式下單與收款流程尚未實作。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour"><span>04</span>商品會如何配送？</button></h2>
            <div id="faqFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">介面目前以宅配情境呈現，但尚未串接物流商或物流追蹤。實際配送區域、時程與費用將在下單功能完成後再明確定義。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive"><span>05</span>購物車的商品數量有限制嗎？</button></h2>
            <div id="faqFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">有。單項商品的購物車數量上限為 49 件；實際可購買數量仍應以未來下單階段的庫存規則為準。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSix" aria-expanded="false" aria-controls="faqSix"><span>06</span>目前可以申請退換貨或退款嗎？</button></h2>
            <div id="faqSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">目前沒有真實訂單，因此也沒有線上退換貨或退款功能。相關規則會在正式訂單流程建置時一併設計，不會在此階段做功能宣稱。</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSeven" aria-expanded="false" aria-controls="faqSeven"><span>07</span>如何註冊會員與設定頭像？</button></h2>
            <div id="faqSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">在註冊頁填寫資料即可建立帳號，頭像為選填。完成註冊前，同一匿名 Session 只保留最近一次成功上傳的圖片；註冊成功後該圖片會成為會員頭像。</div></div>
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
