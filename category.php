<div class="container">
  <?php
  global $link;
  $SQLstring = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
  $pyclass01 = $link->query($SQLstring);
  ?>
  <h2 class="text-center fw-bold">商品分類</h2>
  <div class="row row-cols-2 row-cols-md-4 g-4 text-center"> <!-- 手機版 g-3 改 g-4 釋放空間 -->
    <?php while ($pyclass01_rows = $pyclass01->fetch()) { ?>
      <div class="col">
        <a href="productList.php?classid=<?php echo $pyclass01_rows['classid']; ?>&level=<?php echo $pyclass01_rows['level']; ?>" class="text-decoration-none text-dark d-block">
          <!-- 💡 重點：拔除了 bg-light border 避免生硬框線 -->
          <div class="ratio ratio-1x1 mb-2">
            <img src="./product_img/category0<?= $pyclass01_rows['classid']; ?>.png" alt="<?= $pyclass01_rows['cname']; ?>" class="img-fluid object-fit-cover">
          </div>
          <!-- 💡 重點：加上 category-title 類別，並拿掉 fw-bold fs-6 -->
          <div class="category-title"><?= $pyclass01_rows['cname']; ?></div>
        </a>
      </div>
    <?php } ?>
  </div>
</div>