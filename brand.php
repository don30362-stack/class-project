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

    <main class="brand-page">
      <section class="brand-hero">
        <div class="container position-relative">
          <div class="brand-hero-content">
            <span class="brand-eyebrow">HOME FITNESS · PORTFOLIO BRAND</span>
            <h1>把訓練，帶回生活裡</h1>
            <p>Home Fitness 是為本作品集打造的虛構健身用品品牌，專注於讓器材、空間與日常節奏自然共存。</p>
          </div>
        </div>
      </section>

      <section class="brand-story py-5 py-lg-6">
        <div class="container">
          <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5">
              <span class="brand-section-index">01 / OUR IDEA</span>
              <h2>從「每天都做得到」開始</h2>
            </div>
            <div class="col-lg-7 brand-copy">
              <p class="brand-lead">我們相信，有效的訓練不必受限於特定場館，也不必等待完美時機。</p>
              <p>品牌以 Home Fitness／居家訓練為核心，將器材的尺寸、用途與操作情境說清楚，幫助使用者依照自己的空間、目標與生活方式，組合一套真正願意持續使用的訓練環境。</p>
            </div>
          </div>
        </div>
      </section>

      <section class="brand-categories py-5 py-lg-6">
        <div class="container">
          <div class="brand-section-heading text-center">
            <span class="brand-section-index">02 / WHAT WE CURATE</span>
            <h2>為居家場景整理的訓練選擇</h2>
            <p>從基礎建立到進階強度，以清楚分類縮短挑選器材的距離。</p>
          </div>
          <div class="row g-3 g-lg-4">
            <div class="col-6 col-lg-3">
              <article class="brand-category-card">
                <span>01</span><i class="fa-solid fa-dumbbell"></i>
                <h3>重量訓練</h3><p>啞鈴與可調式負重，支援循序漸進的肌力安排。</p>
              </article>
            </div>
            <div class="col-6 col-lg-3">
              <article class="brand-category-card">
                <span>02</span><i class="fa-solid fa-person-running"></i>
                <h3>有氧器材</h3><p>以居家動線為前提，兼顧運動強度與收納需求。</p>
              </article>
            </div>
            <div class="col-6 col-lg-3">
              <article class="brand-category-card">
                <span>03</span><i class="fa-solid fa-layer-group"></i>
                <h3>訓練設備</h3><p>訓練椅、地墊等基礎設備，穩定每一次動作。</p>
              </article>
            </div>
            <div class="col-6 col-lg-3">
              <article class="brand-category-card">
                <span>04</span><i class="fa-solid fa-bag-shopping"></i>
                <h3>輔助配件</h3><p>支撐、恢復與收納配件，補足完整訓練流程。</p>
              </article>
            </div>
          </div>
        </div>
      </section>

      <section class="brand-values py-5 py-lg-6">
        <div class="container">
          <div class="row g-4 g-lg-5">
            <div class="col-lg-4">
              <span class="brand-section-index">03 / CORE VALUES</span>
              <h2>我們重視的三件事</h2>
            </div>
            <div class="col-lg-8">
              <div class="brand-value-list">
                <article><strong>CLARITY</strong><div><h3>資訊清楚</h3><p>用易懂分類與商品資訊，讓選擇回到實際需求。</p></div></article>
                <article><strong>ADAPTABILITY</strong><div><h3>適應生活</h3><p>器材服務空間與習慣，而不是讓生活遷就器材。</p></div></article>
                <article><strong>CONSISTENCY</strong><div><h3>支持持續</h3><p>不追逐速成承諾，鼓勵穩定且可長久執行的訓練。</p></div></article>
              </div>
            </div>
          </div>
        </div>
      </section>
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
