<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img
                src="product_img/logo-s.png"
                class="site-logo"
                alt="Home Fit">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        $SQLstring = "SELECT * FROM cart WHERE orderid IS NULL AND ip='" . $_SERVER['REMOTE_ADDR'] . "'";
        $cart_rs = $link->query($SQLstring);
        ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center fw-bold text-center fs-5">
                <li class="nav-item px-xl-2 px-xxl-3">
                    <a class="nav-link" aria-current="page" href="index.php">首頁</a>
                </li>
                <?php multiList02(); ?>
                <li class="nav-item px-xl-2 px-xxl-3">
                    <a class="nav-link" href="brand.php">品牌介紹</a>
                </li>
                <li class="nav-item px-xl-2 px-xxl-3">
                    <a class="nav-link" href="FAQ.php">常見問題</a>
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
            <ul class="navbar-nav me-lg-5 flex-row justify-content-center gap-3 mt-2 mt-lg-0">
                <?php if (!isset($_SESSION['login'])) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fa-solid fa-user"></i>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link cart-link mt-2 mt-xl-0" href="cart.php">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="badge">
                            <?php echo ($cart_rs) ? $cart_rs->rowCount() : ''; ?>
                        </span>
                    </a>
                </li>
            </ul>
            <?php if (isset($_SESSION['login'])) { ?>
                <ul class="navbar-nav account-nav flex-row justify-content-center mt-2 mt-lg-0">
                    <li class="nav-item dropdown account-dropdown">

                        <a
                            class="nav-link dropdown-toggle account-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img
                                src="uploads/<?= ($_SESSION['imgname'] != '') ? $_SESSION['imgname'] : 'avatar.svg' ?>"
                                width="40"
                                height="40"
                                class="rounded-circle"
                                alt="會員頭像">
                        </a>

                        <div class="dropdown-menu dropdown-menu-end account-dropdown-menu">
                            <a class="dropdown-item" href="orderlist.php">
                                <i class="fa-solid fa-receipt me-2"></i>
                                訂單紀錄
                            </a>

                            <a class="dropdown-item" href="profile.php">
                                <i class="fa-solid fa-user-pen me-2"></i>
                                會員資料
                            </a>

                            <div class="dropdown-divider"></div>

                            <a
                                class="dropdown-item"
                                href="#"
                                onclick="btn_confirmLink('請確定是否要登出', 'logout.php')">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>
                                登出
                            </a>
                        </div>

                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>

<?php
function multiList02()
{
    global $link;
    $current_classid = isset($_GET['classid']) ? intval($_GET['classid']) : 0;
    $current_level = isset($_GET['level']) ? intval($_GET['level']) : 0;

    $navParentSQL = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
    $pyclass01 = $link->query($navParentSQL);
?>
    <li class="nav-item dropdown product-dropdown ">

        <div class="d-flex align-items-center justify-content-center">

            <a class="nav-link product-link" href="productList.php">
                商品專區
                <i class="fa-solid fa-caret-down d-none d-xl-inline ms-1"></i>
            </a>

            <button
                class="product-dropdown-toggle d-xl-none"
                type="button"
                aria-label="展開商品分類"
                aria-expanded="false">
                <i class="fa-solid fa-caret-down"></i>
            </button>

        </div>

        <ul class="dropdown-menu">
            <?php while ($pyclass01_rows = $pyclass01->fetch()) {
                $isNavActive = ($current_level == 1 && $current_classid == $pyclass01_rows['classid']);
            ?>
                <!-- 💡 加上 d-flex 讓文字連結和手機版小按鈕可以並排 -->
                <li class="nav-item dropend position-relative">
                    <div class="d-flex align-items-center justify-content-center category-nav-row">

                        <!-- ⭐ 移除 dropdown-toggle 類別，回歸純 A 標籤，點擊文字保證 100% 跳轉 -->
                        <a class="dropdown-item <?php echo $isNavActive ? 'active-nav' : ''; ?>"
                            href="productList.php?classid=<?php echo $pyclass01_rows['classid']; ?>&level=<?php echo $pyclass01_rows['level']; ?>">
                            <i class="fas <?= $pyclass01_rows['fonticon']; ?> fa-lg fa-fw"></i>
                            <?= $pyclass01_rows['cname']; ?>
                        </a>

                        <!-- ⭐ 新增：專門給手機版點擊展開二層選單的小按鈕 (電腦版會自動隱藏) -->
                        <button type="button" class="submenu-toggle-btn d-xl-none" aria-label="展開子分類">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                    </div>

                    <?php
                    $navChildSQL = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_rows['classid']);
                    $pyclass02 = $link->query($navChildSQL);
                    ?>
                    <ul class="dropdown-menu submenu">
                        <?php while ($pyclass02_rows = $pyclass02->fetch()) { ?>
                            <li>
                                <a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>" class="dropdown-item">

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