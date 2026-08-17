<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="product_img/logo-s.png" class="img-fluid" alt="logo" style="max-width: 120px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center fw-bold text-center fs-5">
                <li class="nav-item px-3">
                    <a class="nav-link" aria-current="page" href="index.php">首頁</a>
                </li>
                <?php multiList02(); ?>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">品牌介紹</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">常見問題</a>
                </li>
            </ul>
            <form class="d-flex justify-content-center" role="search" action="productList.php" method="get">
                <div>
                    <input name="search_name" class="form-control me-2" type="search" placeholder="Search" aria-label="Search" style="width: 200px;" value="<?php echo (isset($_GET['search_name'])) ? $_GET['search_name'] : ''; ?>" required />
                </div>
                <button class="btn" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <ul class="navbar-nav me-lg-5 flex-row justify-content-center gap-3">
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

<?php
function multiList02()
{
    global $link;
    $SQLstring = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
    $pyclass01 = $link->query($SQLstring);
?>
    <li class="nav-item dropdown product-dropdown">

        <div class="d-flex align-items-center justify-content-center">

            <a class="nav-link product-link" href="productList.php">
                商品專區
            </a>

            <button
                class="product-dropdown-toggle"
                type="button"
                aria-label="展開商品分類"
                aria-expanded="false">
                <i class="fa-solid fa-caret-down"></i>
            </button>

        </div>

        <ul class="dropdown-menu">
            <?php while ($pyclass01_rows = $pyclass01->fetch()) { ?>
                <li class="nav-item dropend">
                    <a class="dropdown-item dropdown-toggle" href="productList.php">
                        <i class="fas <?= $pyclass01_rows['fonticon']; ?> fa-lg fa-fw"></i>
                        <?= $pyclass01_rows['cname']; ?>
                    </a>
                    <?php
                    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_rows['classid']);
                    $pyclass02 = $link->query($SQLstring);
                    ?>
                    <ul class="dropdown-menu submenu">
                        <?php while ($pyclass02_rows = $pyclass02->fetch()) { ?>
                            <li>
                                <a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>" class="dropdown-item">
                                    <i class="fas <?= $pyclass02_rows['fonticon']; ?> fa-fw"></i>
                                    <?= $pyclass02_rows['cname']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>


        </ul>
    </li>
<?php } ?>