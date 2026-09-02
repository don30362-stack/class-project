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

<body class="login-body">
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>

    <section id="content" class="login-page">
        <div class="container-xl">
            <?php require_once('login_content.php'); ?>
        </div>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once('footer.php'); ?>
    </section>

    <div id="loading">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>

    <?php require_once('jsfile.php'); ?>



</body>

<script src="commlib.js"></script>
<script>
    $(function() {
        $("#form1").submit(function(e) {
            e.preventDefault();

            const inputAccount = $("#inputAccount").val();
            const inputPassword = MD5($("#inputPassword").val());

            $("#loading").css("display", "flex");

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

                error: function() {
                    alert("系統目前無法連接到後台資料庫。");
                },

                complete: function() {
                    $("#loading").hide();
                }
            });
        });
    });
</script>

</html>