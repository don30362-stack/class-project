<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once('./Connections/conn_db.php');
require_once('php_lib.php');
?>
<!doctype html>
<html lang="zh">

<head>
  <?php require_once('headfile.php'); ?>
</head>

<body>
  <section id="header">
    <?php require_once('navbar.php'); ?>
  </section>

  <section id="carousel">
    <?php require_once('carousel.php'); ?>
  </section>


  <section id="category-list" class="py-4 py-md-5">
    <?php require_once('category.php'); ?>
  </section>

  <section id="recommendation-section" class="py-4 py-md-5">
    <?php require_once('recommend.php'); ?>
  </section>

  <section id="why-choose-us" class="py-4 py-md-5">
    <?php require_once('why_us.php'); ?>
  </section>

  <section id="pre-footer-cta" class="py-4 py-md-5 position-relative d-flex align-items-center">
    <?php require_once('footer_cta.php'); ?>
  </section>

  <section id="footer" class="py-4 py-md-5 text-white">
    <?php require_once('footer.php'); ?>
  </section>





  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    document.querySelectorAll('.navbar .dropdown-toggle')
      .forEach(function(element) {

        element.addEventListener('click', function(e) {

          if (window.innerWidth >= 992) {
            e.preventDefault();
            e.stopPropagation();
            return;
          }

          if (this.parentElement.classList.contains('dropend')) {
            e.preventDefault();
            e.stopPropagation();

            const submenu = this.nextElementSibling;
            submenu.classList.toggle('show');
          }

        });

      });
  </script>
</body>

</html>