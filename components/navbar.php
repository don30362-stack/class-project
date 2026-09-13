<?php
require_once dirname(__DIR__) . '/includes/php_lib.php';
require_once dirname(__DIR__) . '/includes/cart.php';
?>
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
        $ownerSql = cartOwnerSql(cartCurrentOwner(false), 'c');
        $SQLstring = "SELECT COUNT(*) FROM cart AS c WHERE c.orderid IS NULL AND " . $ownerSql['condition'];
        $SQLstringParams = $ownerSql['params'];
        $cart_rs = $link->prepare($SQLstring);
        $cart_rs->execute($SQLstringParams);
        $cartItemCount = (int)$cart_rs->fetchColumn();
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
                    <input name="search_name" class="form-control me-2" type="search" placeholder="Search" aria-label="Search" style="width: 200px;" value="<?= e(isset($_GET['search_name']) && is_string($_GET['search_name']) ? $_GET['search_name'] : '') ?>" required />
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
                            <?php echo $cartItemCount; ?>
                        </span>
                    </a>
                </li>
            </ul>
            <?php if (isset($_SESSION['login'])) { ?>
                <ul class="navbar-nav account-nav flex-row justify-content-center mt-2 mt-lg-0">
                    <li class="nav-item dropdown account-dropdown">

                        <button
                            type="button"
                            class="nav-link dropdown-toggle account-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img
                                src="uploads/<?= e(safeImageFilename($_SESSION['imgname'] ?? null, 'avatar.svg')) ?>"
                                width="40"
                                height="40"
                                class="rounded-circle"
                                alt="會員頭像">
                        </button>

                        <div class="dropdown-menu dropdown-menu-end account-dropdown-menu">
                            <form method="POST" action="actions/logout.php" onsubmit="return confirm('請確定是否要登出');">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <button type="submit" class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                    登出
                                </button>
                            </form>
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

    $categoryTree = getCategoryTree($link);
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
            <?php foreach ($categoryTree['parents'] as $pyclass01_rows) {
                $isNavActive = ($current_level == 1 && $current_classid == $pyclass01_rows['classid']);
            ?>
                <!-- 💡 加上 d-flex 讓文字連結和手機版小按鈕可以並排 -->
                <li class="nav-item dropend position-relative">
                    <div class="d-flex align-items-center justify-content-center category-nav-row">

                        <!-- ⭐ 移除 dropdown-toggle 類別，回歸純 A 標籤，點擊文字保證 100% 跳轉 -->
                        <a class="dropdown-item <?php echo $isNavActive ? 'active-nav' : ''; ?>"
                            href="productList.php?classid=<?php echo $pyclass01_rows['classid']; ?>&level=<?php echo $pyclass01_rows['level']; ?>">
                            <i class="fas <?= e($pyclass01_rows['fonticon']); ?> fa-lg fa-fw"></i>
                            <?= e($pyclass01_rows['cname']); ?>
                        </a>

                        <!-- ⭐ 新增：專門給手機版點擊展開二層選單的小按鈕 (電腦版會自動隱藏) -->
                        <button type="button" class="submenu-toggle-btn d-xl-none" aria-label="展開子分類">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                    </div>

                    <ul class="dropdown-menu submenu">
                        <?php foreach ($categoryTree['children'][(int)$pyclass01_rows['classid']] ?? array() as $pyclass02_rows) { ?>
                            <li>
                                <a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>" class="dropdown-item">

                                    <?= e($pyclass02_rows['cname']); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>

    </li>
<?php } ?>
