<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('con_db.php');
require_once('php_lib.php');
?>
<!doctype html>
<html lang="zh">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home Fitness</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css">
  <link rel="stylesheet" href="website_p01.css">


</head>

<body>
  <section id="header">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="./images/logo-s.png" class="img-fluid" alt="logo" style="max-width: 120px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center fw-bold text-center fs-4">
            <li class="nav-item px-3">
              <a class="nav-link" aria-current="page" href="#">首頁</a>
            </li>
            <li class="nav-item dropdown px-3">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                商品專區
              </a>
              <ul class="dropdown-menu text-center text-lg-start">
                <li><a class="dropdown-item" href="#">可調式啞鈴</a></li>
                <li><a class="dropdown-item" href="#">可調式槓鈴</a></li>
                <li><a class="dropdown-item" href="#">健身椅</a></li>
                <li><a class="dropdown-item" href="#">健身配件</a></li>
              </ul>
            </li>
            <li class="nav-item px-3">
              <a class="nav-link" href="#">品牌介紹</a>
            </li>
            <li class="nav-item px-3">
              <a class="nav-link" href="#">常見問題</a>
            </li>
          </ul>
          <form class="d-flex justify-content-center" role="search">
            <div class="collapse collapse-horizontal" id="searchBar"><input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" style="width: 200px;" /></div>
            <button class="btn" type="submit" data-bs-toggle="collapse" data-bs-target="#searchBar"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
          <ul class="navbar-nav align-items-center me-lg-5">
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fa-solid fa-user"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fa-solid fa-cart-shopping"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </section>

  <section id="hero-section" class="py-5">
    <div class="container">
      <div class="row align-items-center g-4">

        <div class="col-12 col-md-4">
          <h1 class="fw-bold mb-3" style="letter-spacing: 1px;">打造屬於你的居家訓練空間</h1>
          <p class="text-secondary mb-4" style="font-size: 0.95rem;">專注家用重量訓練器材</p>
          <div class="d-flex gap-3">
            <a href="#" class="btn btn-secondary px-4 py-2 rounded-0">立即選購</a>
            <a href="#" class="btn btn-outline-dark px-4 py-2 rounded-0">了解更多</a>
          </div>
        </div>

        <div class="col-12 col-md-8">
          <img src="./images/hero.jpg" alt="居家訓練空間" class="img-fluid w-100">
        </div>
      </div>

    </div>
  </section>


  <section id="category-list" class="py-5">
    <div class="container">

      <h2 class="text-center fw-bold mb-4">商品分類</h2>

      <div class="row row-cols-2 row-cols-md-4 g-4 text-center">

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="ratio ratio-1x1 mb-2 bg-light border">
              <img src="./images/category01.webp" alt="可調式啞鈴" class="img-fluid object-fit-cover">
            </div>
            <div class="fw-bold fs-6">可調式啞鈴</div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="ratio ratio-1x1 mb-2 bg-light border">
              <img src="./images/category02.jpg" alt="可調式槓鈴" class="img-fluid object-fit-cover">
            </div>
            <div class="fw-bold fs-6">可調式槓鈴</div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="ratio ratio-1x1 mb-2 bg-light border">
              <img src="./images/category03.jpg" alt="健身椅" class="img-fluid object-fit-cover">
            </div>
            <div class="fw-bold fs-6">健身椅</div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="ratio ratio-1x1 mb-2 bg-light border">
              <img src="./images/category04.webp" alt="健身配件" class="img-fluid object-fit-cover">
            </div>
            <div class="fw-bold fs-6">健身配件</div>
          </a>
        </div>

      </div>
    </div>
  </section>

  <section id="recommendation-section" class="py-5">
    <div class="container">

      <h2 class="text-center fw-bold mb-4">推薦商品</h2>

      <div class="row row-cols-2 row-cols-md-4 g-4 text-center">

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="card h-100 border rounded-0 shadow-sm">
              <div class="ratio ratio-1x1 bg-light">
                <img src="./product_img/dumbbel-22.6kg.jpg" class="card-img-top object-fit-cover" alt="22.6kg(50LB) 5段重量 可調式啞鈴 (白)">
              </div>
              <div class="card-body p-3">
                <h5 class="card-title fw-bold fs-6 mb-1">22.6kg(50LB) 5段重量 可調式啞鈴 (白)</h5>
                <p class="card-text text-secondary small mb-0">$5180</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="card h-100 border rounded-0 shadow-sm">
              <div class="ratio ratio-1x1 bg-light">
                <img src="./product_img/dumbbel-46kg.webp" class="card-img-top object-fit-cover" alt="46kg 18段重量 抗摔可調式啞鈴(黑)">
              </div>
              <div class="card-body p-3">
                <h5 class="card-title fw-bold fs-6 mb-1">46kg 18段重量 抗摔可調式啞鈴(黑)</h5>
                <p class="card-text text-secondary small mb-0">$18800</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="card h-100 border rounded-0 shadow-sm">
              <div class="ratio ratio-1x1 bg-light">
                <img src="./product_img/dumbbel-stand-46kg.jpg" class="card-img-top object-fit-cover" alt="46kg 可調式啞鈴專用架(黑)">
              </div>
              <div class="card-body p-3">
                <h5 class="card-title fw-bold fs-6 mb-1">46kg 可調式啞鈴專用架(黑)</h5>
                <p class="card-text text-secondary small mb-0">$3980</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col">
          <a href="#" class="text-decoration-none text-dark d-block">
            <div class="card h-100 border rounded-0 shadow-sm">
              <div class="ratio ratio-1x1 bg-light">
                <img src="./product_img/bench-fold.webp" class="card-img-top object-fit-cover" alt="站立式收納5段調整健身椅(黑)">
              </div>
              <div class="card-body p-3">
                <h5 class="card-title fw-bold fs-6 mb-1">站立式收納5段調整健身椅(黑)</h5>
                <p class="card-text text-secondary small mb-0">$5980</p>
              </div>
            </div>
          </a>
        </div>

      </div>
    </div>
  </section>

  <section class="why-choose-us py-5">
    <div class="container text-center">

      <h2 class="fw-bold mb-5">為什麼選擇 Home Fitness？</h2>

      <div class="row row-cols-2 row-cols-md-4 g-4">

        <div class="col">
          <div class="why-us-item">
            <div class="why-us-icon mb-3">
              <i class="fa-regular fa-calendar-check fa-3x text-muted"></i>
            </div>
            <p class="text-secondary fs-6 mb-0">居家訓練更方便</p>
          </div>
        </div>

        <div class="col">
          <div class="why-us-item">
            <div class="why-us-icon mb-3">
              <i class="fa-solid fa-briefcase fa-3x text-muted"></i>
            </div>
            <p class="text-secondary fs-6 mb-0">商品規格清楚透明</p>
          </div>
        </div>

        <div class="col">
          <div class="why-us-item">
            <div class="why-us-icon mb-3">
              <i class="fa-solid fa-wave-square fa-3x text-muted"></i>
            </div>
            <p class="text-secondary fs-6 mb-0">器材選購更直覺</p>
          </div>
        </div>

        <div class="col">
          <div class="why-us-item">
            <div class="why-us-icon mb-3">
              <i class="fa-solid fa-truck fa-3x text-muted"></i>
            </div>
            <p class="text-secondary fs-6 mb-0">購物流程簡單安心</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="pre-footer-cta py-5 position-relative d-flex align-items-center">

    <div class="container position-relative h-100 d-flex flex-column justify-content-between" style="min-height: 350px;">

      <div class="row w-100 m-0 my-auto">
        <div class="col-12 col-md-6 ms-auto text-center text-md-start">
          <h2 class="fw-bold display-6 mb-4 text-white" style="letter-spacing: 1px;">
            開始打造居家健身空間
          </h2>
          <div class="d-inline-block">
            <a href="#" class="btn custom-premium-btn px-4 py-2-5 rounded-0 fs-6 text-dark text-decoration-none">
              瀏覽商品
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <footer class="footer py-5 text-white">
    <div class="container">

      <div class="row align-items-center g-4 mb-5 text-center text-lg-start">

        <div class="col-12 col-lg-3">
          <a class="navbar-brand d-inline-flex align-items-center text-white text-decoration-none fw-bold fs-5" href="#">
            <img src="./images/logo-s2.png" class="img-fluid" alt="logo" style="max-width: 120px;">Home Fitness
          </a>
        </div>

        <div class="col-12 col-lg-6 text-center text-lg-center">
          <ul class="nav flex-column flex-sm-row justify-content-center gap-4 p-0 m-0 d-inline-flex">
            <li class="nav-item">
              <a class="nav-link text-white-50 p-0 hover-white text-decoration-none" href="#">商品專區</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 p-0 hover-white text-decoration-none" href="#">品牌介紹</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 p-0 hover-white text-decoration-none" href="#">常見問題</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white-50 p-0 hover-white text-decoration-none" href="#">會員中心</a>
            </li>
          </ul>
        </div>

        <div class="col-12 col-lg-3 text-center text-lg-end">
          <div class="d-inline-flex gap-3 fs-5">
            <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-youtube"></i></a>
            <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-instagram"></i></a>
          </div>
        </div>

      </div>

    </div>



    <hr class="border-secondary my-4 opacity-25">

    <div class="text-center">
      <p class="text-white-50 mb-0" style="font-size: 0.85rem; letter-spacing: 0.5px;">
        DonaldWang @ 2026. All rights reserved.
      </p>
    </div>

    </div>
  </footer>









  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>