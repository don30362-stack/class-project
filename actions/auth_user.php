<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/member_password.php';

if (
    isset($_POST['inputAccount'], $_POST['inputPassword'])
    && is_string($_POST['inputAccount'])
    && is_string($_POST['inputPassword'])
) {
    $inputAccount = $_POST['inputAccount'];
    $inputPassword = $_POST['inputPassword'];

    $query = "SELECT emailid, email, pw1, active, cname, imgname
              FROM member
              WHERE email = :email
              LIMIT 1";
    $result = $link->prepare($query);
    $querySucceeded = $result && $result->execute(array(':email' => $inputAccount));

    if ($querySucceeded) {
        $data = $result->fetch();
        if ($data) {
            if (!$data['active']) {
                $retcode = array("c" => "2", "m" => "會員帳號被鎖定！請聯絡管理人員。");
            } else {
                $passwordResult = verifyAndUpgradeMemberPassword(
                    $link,
                    (int)$data['emailid'],
                    $inputPassword,
                    $data['pw1']
                );

                if (!$passwordResult['verified']) {
                    $retcode = array("c" => "2", "m" => "帳號或密碼錯誤！需要重新輸入。");
                } elseif (!session_regenerate_id(true)) {
                    error_log(sprintf('Member login failed for member ID %d: session_regeneration_failed', (int)$data['emailid']));
                    $retcode = array("c" => "0", "m" => "抱歉！會員驗證失敗，請聯絡管理人員。");
                } else {
                    $_SESSION['login'] = true;
                    $_SESSION['emailid'] = $data['emailid'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['cname'] = $data['cname'];
                    $_SESSION['imgname'] = $data['imgname'];
                    $retcode = array("c" => "1", "m" => "會員驗證成功");
                }
            }
        } else {
            $retcode = array("c" => "2", "m" => "帳號或密碼錯誤！需要重新輸入。");
        }
    } else {
        $retcode = array("c" => "0", "m" => "抱歉！會員驗證失敗，請聯絡管理人員。");
    }
    echo json_encode($retcode,JSON_UNESCAPED_UNICODE);
}
return;
?>
