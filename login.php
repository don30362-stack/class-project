<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
?>
<?php
$allowedLoginDestinations = array('cart' => 'cart.php', 'checkout' => 'checkout.php');
$requestedPath = isset($_GET['sPath']) && is_string($_GET['sPath']) ? $_GET['sPath'] : '';
$sPath = $allowedLoginDestinations[$requestedPath] ?? 'index.php';

if (isset($_SESSION['login'])) {
    header(sprintf("location: %s", $sPath));
}
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once(__DIR__ . '/includes/headfile.php'); ?>
</head>

<body class="login-body">
    <section id="header">
        <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="content" class="login-page">
        <div class="container-xl">
            <?php require_once(__DIR__ . '/components/login_content.php'); ?>
        </div>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once(__DIR__ . '/components/footer.php'); ?>
    </section>

    <div id="loading">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>

    <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>



</body>

<script>
    $(function() {
        let loginRequestPending = false;
        let loginRedirecting = false;

        function showLoginError(message) {
            $('#login-feedback').text(message).removeClass('d-none');
        }

        $("#form1").submit(function(e) {
            e.preventDefault();
            if (loginRequestPending) return;

            const inputAccount = $("#inputAccount").val();
            const inputPassword = $("#inputPassword").val();
            const csrfToken = $("#loginCsrfToken").val();
            const submitButton = $(this).find('.login-submit-btn');

            loginRequestPending = true;
            $('#login-feedback').addClass('d-none').text('');
            submitButton.prop('disabled', true).attr('aria-busy', 'true').text('登入中…');
            $("#loading").css("display", "flex");

            $.ajax({
                url: 'actions/auth_user.php',
                type: 'post',
                dataType: 'json',
                data: {
                    inputAccount: inputAccount,
                    inputPassword: inputPassword,
                    csrf_token: csrfToken
                },

                success: function(data) {
                    if (data.c == true) {
                        loginRedirecting = true;
                        window.location.href = <?= jsValue($sPath) ?>;
                    } else {
                        showLoginError(typeof data.m === 'string' ? data.m : 'Email 或密碼錯誤。');
                    }
                },

                error: function() {
                    showLoginError('系統目前無法連接，請稍後再試。');
                },

                complete: function() {
                    $("#loading").hide();
                    if (!loginRedirecting) {
                        loginRequestPending = false;
                        submitButton.prop('disabled', false).removeAttr('aria-busy').html('登入 <i class="fa-solid fa-arrow-right"></i>');
                    }
                }
            });
        });
    });
</script>

</html>
