<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
