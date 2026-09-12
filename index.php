<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
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

  <section id="carousel">
    <?php require_once(__DIR__ . '/components/carousel.php'); ?>
  </section>

  <section id="category-list" class="py-5">
    <?php require_once(__DIR__ . '/components/category.php'); ?>
  </section>

  <section id="recommendation-section" class="py-4 py-md-5">
    <?php require_once(__DIR__ . '/components/recommend.php'); ?>
  </section>

  <section id="why-choose-us" class="py-4 py-md-5">
    <?php require_once(__DIR__ . '/components/why_us.php'); ?>
  </section>

  <section id="pre-footer-cta" class="py-5 position-relative d-flex align-items-center">
    <?php require_once(__DIR__ . '/components/footer_cta.php'); ?>
  </section>

  <section id="footer" class="py-4 py-md-5 text-white">
    <?php require_once(__DIR__ . '/components/footer.php'); ?>
  </section>



  <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>



  

</body>

</html>
