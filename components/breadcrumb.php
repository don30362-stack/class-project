<?php
// 1. 初始化，最外層固定有首頁
$breadcrumbItems = [
    ['name' => '首頁', 'url' => 'index.php', 'active' => false]
];

// 2. 取得當前執行的檔名
$current_page = basename($_SERVER['PHP_SELF']);

// -------------------------------------------------------------
// 情境 A：非商品專區的靜態頁面（維持獨立，不疊加商品專區）
// -------------------------------------------------------------
if ($current_page == 'brand.php') {
    $breadcrumbItems[] = ['name' => '品牌介紹', 'url' => '', 'active' => true];
} elseif ($current_page == 'FAQ.php') {
    $breadcrumbItems[] = ['name' => '常見問題', 'url' => '', 'active' => true];
} elseif ($current_page == 'cart.php') {
    $breadcrumbItems[] = ['name' => '購物車', 'url' => '', 'active' => true];
} elseif ($current_page == 'checkout.php') {
    $breadcrumbItems[] = ['name' => '結帳', 'url' => '', 'active' => true];
}


// -------------------------------------------------------------
// 情境 B：凡是 productList.php 或 productDetail.php 都屬於商品專區
// -------------------------------------------------------------
elseif ($current_page == 'productList.php' || $current_page == 'productDetail.php') {

    // 檢查目前網址有沒有任何參數 (不論是 classid、p_id 還是 search_name)
    $has_params = !empty($_GET);

    if (!$has_params) {
        // 🌟 狀況 1：完全沒有參數 -> 呈現 "首頁 > 商品專區"
        $breadcrumbItems[] = ['name' => '商品專區', 'url' => '', 'active' => true];
    } else {
        // 🌟 狀況 2：有參數 -> 先塞入可點擊的 "商品專區"，後面再繼續疊加分類
        $breadcrumbItems[] = ['name' => '商品專區', 'url' => 'productList.php', 'active' => false];

        // --- 以下開始根據參數動態疊加後續節點 ---

        if (isset($_GET['p_id']) && $_GET['p_id'] != "") {
            // 頁面：商品內頁 (呈現：商品專區 > 大類 > 小類 > 商品名稱)
            $SQLstring = "SELECT p.p_name, c2.cname as cname2, c2.classid as classid2, c1.cname as cname1, c1.classid as classid1, c1.level as level1
                          FROM product p
                          INNER JOIN pyclass c2 ON p.classid = c2.classid
                          INNER JOIN pyclass c1 ON c2.uplink = c1.classid
                          WHERE p.p_id = :p_id";

            $stmt = $link->prepare($SQLstring);
            $stmt->execute([':p_id' => (int)$_GET['p_id']]);
            $data = $stmt->fetch();

            if ($data) {
                $breadcrumbItems[] = ['name' => $data['cname1'], 'url' => 'productList.php?classid=' . $data['classid1'] . '&level=' . $data['level1'], 'active' => false];
                $breadcrumbItems[] = ['name' => $data['cname2'], 'url' => 'productList.php?classid=' . $data['classid2'], 'active' => false];
                $breadcrumbItems[] = ['name' => $data['p_name'], 'url' => '', 'active' => true];
            }
        } elseif (isset($_GET['search_name'])) {
            // 頁面：關鍵字搜尋結果
            $breadcrumbItems[] = ['name' => '關鍵字查詢：' . $_GET['search_name'], 'url' => '', 'active' => true];
        } elseif (isset($_GET['level']) && isset($_GET['classid']) && $_GET['level'] == 1) {
            // 頁面：第一個分類 (大類)
            $SQLstring = "SELECT cname FROM pyclass WHERE level = :level AND classid = :classid";
            $stmt = $link->prepare($SQLstring);
            $stmt->execute([':level' => (int)$_GET['level'], ':classid' => (int)$_GET['classid']]);
            $data = $stmt->fetch();
            if ($data) {
                $breadcrumbItems[] = ['name' => $data['cname'], 'url' => '', 'active' => true];
            }
        } elseif (isset($_GET['classid'])) {
            // 頁面：第二個分類 (小類)
            $SQLstring = "SELECT c2.cname as cname2, c1.cname as cname1, c1.classid as classid1, c1.level as level1 
                          FROM pyclass c2
                          INNER JOIN pyclass c1 ON c2.uplink = c1.classid
                          WHERE c2.level = 2 AND c2.classid = :classid";

            $stmt = $link->prepare($SQLstring);
            $stmt->execute([':classid' => (int)$_GET['classid']]);
            $data = $stmt->fetch();
            if ($data) {
                $breadcrumbItems[] = ['name' => $data['cname1'], 'url' => 'productList.php?classid=' . $data['classid1'] . '&level=' . $data['level1'], 'active' => false];
                $breadcrumbItems[] = ['name' => $data['cname2'], 'url' => '', 'active' => true];
            }
        }
    }
}
?>


<nav aria-label="breadcrumb" class="ms-3 mt-4">
    <ol class="breadcrumb">
        <?php foreach ($breadcrumbItems as $item) { ?>
            <?php if ($item['active']) { ?>
                <!-- 如果是最後一頁（當前頁面），不加超連結並加上 active -->
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($item['name']) ?></li>
            <?php } else { ?>
                <!-- 如果是上層目錄，加上對應的頁面超連結 -->
                <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= htmlspecialchars($item['name']) ?></a></li>
            <?php } ?>
        <?php } ?>
    </ol>
</nav>