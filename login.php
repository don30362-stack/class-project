<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('Connections/conn_db.php');
require_once('php_lib.php');
?>
<?php
if (isset($_GET['sPath'])) {
    $sPath = $_GET['sPath'] . ".php";
} else {
    $sPath = "index.php";
}

if (isset($_SESSION['login'])) {
    header(sprintf("location: %s", $sPath));
}
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once('headfile.php'); ?>
</head>

<body>
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>

    <section id="content" class="mt-2">
        <div class="container-fluid">
            <?php require_once('login_content.php'); ?>
        </div>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once('footer.php'); ?>
    </section>

    <?php require_once('jsfile.php'); ?>

    <div id="loading" name="loading" style="display: none; position: fixed; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255, 255, 255, .5); z-index: 9999;">
        <i class="fas fa-spinner fa-spin fa-5x fa-fw" style="position: absolute; top: 50%; left: 50%;"></i>
    </div>

</body>

<script src="commlib.js"></script>
<script>
    $(function() {
        $("#form1").submit(function() {
            const inputAccount = $("#inputAccount").val();
            const inputPassword = MD5($("#inputPassword").val());

            $("#loading").show();

            $.ajax({
                url: 'auth_user.php',
                type: 'post',
                dataType: 'json',
                data: {
                    inputAccount: inputAccount,
                    inputPassword: inputPassword
                },
                success: function(data) {
                    if (data.c == true) {
                        alert(data.m);
                        window.location.href = "<?= $sPath ?>";
                    } else {
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("系統目前無法連接到後台資料庫。");
                }
            })
        })
    })
</script>

</html>