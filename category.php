<div class="container">
      <?php
      global $link;
      $SQLstring = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
      $pyclass01 = $link->query($SQLstring);
      ?>
      <h2 class="text-center fw-bold mb-4">商品分類</h2>
      <div class="row row-cols-2 row-cols-md-4 g-3 g-md-4  text-center">
        <?php while ($pyclass01_rows = $pyclass01->fetch()) { ?>
          <div class="col">
            <a href="#" class="text-decoration-none text-dark d-block">
              <div class="ratio ratio-1x1 mb-2 bg-light border">
                <img src="./product_img/category0<?= $pyclass01_rows['classid']; ?>.png" alt="<?= $pyclass01_rows['cname']; ?>" class="img-fluid object-fit-cover">
              </div>
              <div class="fw-bold fs-6"><?= $pyclass01_rows['cname']; ?></div>
            </a>
          </div>
        <?php } ?>
      </div>
    </div>