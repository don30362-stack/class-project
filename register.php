<?php
require_once __DIR__ . '/includes/session.php';
ob_start();
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
require_once(__DIR__ . '/includes/cart.php');
require_once(__DIR__ . '/includes/csrf.php');
require_once(__DIR__ . '/includes/register_validation.php');
$registrationAvatar = registrationUploadFilename();
$registrationErrors = array();
$registrationPageError = null;
$registrationValues = array(
    'email' => '', 'cname' => '', 'birthday' => '', 'mobile' => '',
    'myCity' => '', 'myTown' => '', 'myZip' => '', 'address' => '',
);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['formctl'] ?? null) === 'reg') {
    foreach (array_keys($registrationValues) as $field) {
        if (isset($_POST[$field]) && is_string($_POST[$field])) {
            $registrationValues[$field] = trim($_POST[$field]);
        }
    }

    if (!csrf_validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        $registrationPageError = '請求驗證失敗，請重新整理頁面後再試。';
    } else {
        [$registrationValid, $registrationResult] = validateRegistration($link, $_POST);
        if (!$registrationValid) {
            http_response_code(422);
            $registrationErrors = $registrationResult;
        }

        $password = isset($_POST['pw1']) && is_string($_POST['pw1']) ? $_POST['pw1'] : null;
        $passwordConfirmation = isset($_POST['pw2']) && is_string($_POST['pw2']) ? $_POST['pw2'] : null;
        $passwordLength = $password === null ? 0 : preg_match_all('/./us', $password, $passwordCharacters);

        if ($password === null || $password === '') {
            $registrationErrors['pw1'] = '請輸入密碼。';
        } elseif ($passwordLength === false || $passwordLength < 4 || $passwordLength > 20) {
            $registrationErrors['pw1'] = '密碼長度必須為 4～20 個字元。';
        }
        if ($passwordConfirmation === null || $passwordConfirmation === '') {
            $registrationErrors['pw2'] = '請再次輸入密碼。';
        } elseif ($password !== $passwordConfirmation) {
            $registrationErrors['pw2'] = '兩次輸入的密碼不一致。';
        }

        if ($registrationValid && empty($registrationErrors)) {
            try {
                $pw1 = password_hash($password, PASSWORD_DEFAULT);
            } catch (Throwable $exception) {
                $pw1 = false;
            }

            if (!is_string($pw1)) {
                error_log('Member registration failed: password_hash_failed');
                $registrationPageError = '註冊失敗，請稍後再試。';
            } else {
                extract($registrationResult, EXTR_SKIP);
                try {
                    $link->beginTransaction();
                    $statement = $link->prepare('INSERT INTO member (email,pw1,cname,birthday,imgname) VALUES (:email,:password,:cname,:birthday,:imgname)');
                    $statement->execute(array(':email'=>$email, ':password'=>$pw1, ':cname'=>$cname, ':birthday'=>$birthday, ':imgname'=>$imgname));
                    $emailid = (int)$link->lastInsertId();
                    $statement = $link->prepare("INSERT INTO addbook (emailid,setdefault,cname,mobile,myZip,city_id,town_id,address) VALUES (:emailid, '1', :cname, :mobile, :zip, :city_id, :town_id, :address)");
                    $statement->execute(array(':emailid'=>$emailid, ':cname'=>$cname, ':mobile'=>$mobile, ':zip'=>$zip, ':city_id'=>$city_id, ':town_id'=>$town_id, ':address'=>$address));
                    if (!mergeAnonymousCartIntoMember($link, $emailid, false)) throw new RuntimeException('cart_merge_failed');
                    $link->commit();
                    unset($_SESSION[CART_ANONYMOUS_TOKEN_SESSION_KEY]);
                    if ($imgname !== 'avatar.svg') releaseRegistrationUpload($imgname);
                } catch (Throwable $exception) {
                    if ($link->inTransaction()) $link->rollBack();
                    error_log('Member registration failed: transaction_failed');
                    http_response_code(500);
                    $registrationPageError = '註冊失敗，請稍後再試。';
                }

                if ($registrationPageError === null) {
                    if (session_regenerate_id(true)) {
                        $_SESSION['login'] = true; $_SESSION['emailid'] = $emailid; $_SESSION['email'] = $email;
                        $_SESSION['cname'] = $cname; $_SESSION['imgname'] = $imgname; csrf_rotate();
                        header('Location: index.php', true, 303);
                    } else {
                        error_log(sprintf('Member registration auto-login failed for member ID %d: session_regeneration_failed', $emailid));
                        $_SESSION = array();
                        header('Location: login.php', true, 303);
                    }
                    exit;
                }
            }
        }
    }
}

$registrationTownRows = array();
if (preg_match('/\A[1-9][0-9]*\z/D', $registrationValues['myCity']) === 1) {
    $townStatement = $link->prepare('SELECT townNo, Name FROM town WHERE AutoNo = :city_id AND State = 0 ORDER BY townNo');
    $townStatement->execute(array(':city_id' => (int)$registrationValues['myCity']));
    $registrationTownRows = $townStatement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once(__DIR__ . '/includes/headfile.php'); ?>

</head>

<body>
    <section id="header">
        <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="content" class="register-page">
        <div class="container-xl">

            <div class="register-wrapper">

                <!-- 左側品牌區 -->
                <aside class="register-intro">

                    <div class="register-intro-content">

                        <span class="register-intro-eyebrow">
                            HOME FIT MEMBER
                        </span>

                        <h1>
                            建立您的<br>
                            會員帳號
                        </h1>

                        <p>
                            建立會員帳號，讓購物車能在登入後接續使用，
                            並享有更流暢的選購體驗。
                        </p>

                        <div class="register-benefits">

                            <div class="register-benefit">
                                <span>01</span>
                                <p>建立專屬會員帳號</p>
                            </div>

                            <div class="register-benefit">
                                <span>02</span>
                                <p>延續匿名購物車內容</p>
                            </div>

                            <div class="register-benefit">
                                <span>03</span>
                                <p>保存會員頭像與資料</p>
                            </div>

                        </div>

                    </div>

                </aside>


                <!-- 右側表單 -->
                <main class="register-form-area">

                    <div class="register-heading">

                        <span class="register-eyebrow">
                            CREATE ACCOUNT
                        </span>

                        <h2>會員註冊</h2>

                        <p>
                            請填寫以下會員資料，標示
                            <span class="required-mark">*</span>
                            為必填欄位。
                        </p>

                        <?php if ($registrationPageError !== null) { ?>
                            <div class="alert alert-danger mt-3 mb-0" role="alert"><?= e($registrationPageError) ?></div>
                        <?php } ?>

                    </div>


                    <form
                        id="reg"
                        name="reg"
                        action="register.php"
                        method="POST"
                        class="register-form">

                        <!-- ==========================================
                         01 帳號資訊
                         ========================================== -->
                        <section class="register-section">

                            <div class="register-section-heading">
                                <span>01</span>

                                <div>
                                    <small>ACCOUNT</small>
                                    <h3>帳號資訊</h3>
                                </div>
                            </div>


                            <div class="register-grid">

                                <div class="register-field register-field-full">
                                    <label for="email">
                                        電子信箱
                                        <span class="required-mark">*</span>
                                    </label>

                                    <div class="register-input-wrapper">
                                        <i class="fa-regular fa-envelope"></i>

                                        <input
                                            type="email"
                                            name="email"
                                             id="email"
                                             class="register-input"
                                             placeholder="example@email.com"
                                             autocomplete="email"
                                             value="<?= e($registrationValues['email']) ?>">
                                     </div>
                                    <?php if (isset($registrationErrors['email'])) { ?><div class="register-error server-register-error" data-error-for="email"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['email']) ?></div><?php } ?>
                                </div>


                                <div class="register-field">
                                    <label for="pw1">
                                        密碼
                                        <span class="required-mark">*</span>
                                    </label>

                                    <div class="register-input-wrapper">
                                        <i class="fa-solid fa-lock"></i>

                                        <input
                                            type="password"
                                            name="pw1"
                                             id="pw1"
                                             class="register-input"
                                             autocomplete="new-password"
                                             placeholder="請輸入 4～20 位密碼">
                                     </div>
                                    <?php if (isset($registrationErrors['pw1'])) { ?><div class="register-error server-register-error" data-error-for="pw1"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['pw1']) ?></div><?php } ?>
                                </div>


                                <div class="register-field">
                                    <label for="pw2">
                                        確認密碼
                                        <span class="required-mark">*</span>
                                    </label>

                                    <div class="register-input-wrapper">
                                        <i class="fa-solid fa-lock"></i>

                                        <input
                                            type="password"
                                            name="pw2"
                                             id="pw2"
                                             class="register-input"
                                             autocomplete="new-password"
                                             placeholder="請再次輸入密碼">
                                     </div>
                                    <?php if (isset($registrationErrors['pw2'])) { ?><div class="register-error server-register-error" data-error-for="pw2"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['pw2']) ?></div><?php } ?>
                                </div>

                            </div>

                        </section>


                        <!-- ==========================================
                         02 個人資料
                         ========================================== -->
                        <section class="register-section">

                            <div class="register-section-heading">
                                <span>02</span>

                                <div>
                                    <small>PROFILE</small>
                                    <h3>個人資料</h3>
                                </div>
                            </div>


                            <div class="register-grid">

                                <div class="register-field">
                                    <label for="cname">
                                        姓名
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="cname"
                                             id="cname"
                                             class="register-input"
                                             autocomplete="name"
                                             placeholder="請輸入姓名"
                                             value="<?= e($registrationValues['cname']) ?>">
                                    <?php if (isset($registrationErrors['cname'])) { ?><div class="register-error server-register-error" data-error-for="cname"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['cname']) ?></div><?php } ?>
                                </div>


                                <div class="register-field">
                                    <label for="birthday">
                                        生日
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="date"
                                             name="birthday"
                                             id="birthday"
                                             class="register-input"
                                             max="<?= date('Y-m-d') ?>"
                                             value="<?= e($registrationValues['birthday']) ?>">
                                    <?php if (isset($registrationErrors['birthday'])) { ?><div class="register-error server-register-error" data-error-for="birthday"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['birthday']) ?></div><?php } ?>
                                </div>


                                <div class="register-field">
                                    <label for="mobile">
                                        手機號碼
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                             name="mobile"
                                             id="mobile"
                                             class="register-input"
                                             autocomplete="tel"
                                             inputmode="numeric"
                                             placeholder="例：0912345678"
                                             value="<?= e($registrationValues['mobile']) ?>">
                                    <?php if (isset($registrationErrors['mobile'])) { ?><div class="register-error server-register-error" data-error-for="mobile"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['mobile']) ?></div><?php } ?>
                                </div>

                            </div>

                        </section>


                        <!-- ==========================================
                         03 配送地址
                         ========================================== -->
                        <section class="register-section">

                            <div class="register-section-heading">
                                <span>03</span>

                                <div>
                                    <small>ADDRESS</small>
                                    <h3>配送地址</h3>
                                </div>
                            </div>


                            <div class="register-grid">

                                <div class="register-field">

                                    <label for="myCity">
                                        縣市
                                        <span class="required-mark">*</span>
                                    </label>

                                    <select
                                             name="myCity"
                                             id="myCity"
                                             class="register-input register-select">
                                        <option value="">請選擇縣市</option>

                                        <?php
                                        $city = "SELECT * FROM city WHERE State = 0";
                                        $city_rs = $link->query($city);

                                        while ($city_rows = $city_rs->fetch()) {
                                        ?>
                                            <option value="<?= $city_rows['AutoNo'] ?>" <?= (string)$city_rows['AutoNo'] === $registrationValues['myCity'] ? 'selected' : '' ?>>
                                                <?= e($city_rows['Name']) ?>
                                            </option>
                                        <?php } ?>

                                     </select>
                                    <?php if (isset($registrationErrors['myCity'])) { ?><div class="register-error server-register-error" data-error-for="myCity"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['myCity']) ?></div><?php } ?>

                                </div>


                                <div class="register-field">

                                    <label for="myTown">
                                        地區
                                        <span class="required-mark">*</span>
                                    </label>

                                    <select
                                        name="myTown"
                                        id="myTown"
                                         class="register-input register-select">
                                         <option value="">請選擇地區</option>
                                        <?php foreach ($registrationTownRows as $townRow) { ?>
                                            <option value="<?= (int)$townRow['townNo'] ?>" <?= (string)$townRow['townNo'] === $registrationValues['myTown'] ? 'selected' : '' ?>><?= e($townRow['Name']) ?></option>
                                        <?php } ?>
                                     </select>
                                    <?php if (isset($registrationErrors['myTown'])) { ?><div class="register-error server-register-error" data-error-for="myTown"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['myTown']) ?></div><?php } ?>

                                </div>


                                <div class="register-field register-field-full">

                                    <label>
                                        郵遞區號 / 區域
                                    </label>

                                    <div
                                        id="zipcode"
                                        class="register-zipcode">
                                        <?= $registrationValues['myZip'] === '' ? '選擇縣市與地區後將自動顯示' : e($registrationValues['myZip']) ?>
                                    </div>

                                    <div id="location-error" class="register-error" role="alert" aria-live="polite" style="display: none;"></div>

                                    <input
                                        type="hidden"
                                         name="myZip"
                                         id="myZip"
                                         value="<?= e($registrationValues['myZip']) ?>">

                                </div>


                                <div class="register-field register-field-full">

                                    <label for="address">
                                        詳細地址
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="address"
                                             id="address"
                                             class="register-input"
                                             autocomplete="street-address"
                                             placeholder="請輸入路名、巷弄、門牌等詳細地址"
                                             value="<?= e($registrationValues['address']) ?>">
                                    <?php if (isset($registrationErrors['address'])) { ?><div class="register-error server-register-error" data-error-for="address"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['address']) ?></div><?php } ?>

                                </div>

                            </div>

                        </section>


                        <!-- ==========================================
                         04 會員照片
                         ========================================== -->
                        <section class="register-section">

                            <div class="register-section-heading">
                                <span>04</span>

                                <div>
                                    <small>PROFILE PHOTO</small>
                                    <h3>會員照片</h3>
                                </div>
                            </div>


                            <div class="register-upload">

                                <div class="register-upload-control">

                                    <label for="fileToUpload">
                                        選擇會員照片
                                    </label>

                                    <input
                                        type="file"
                                        name="fileToUpload"
                                        id="fileToUpload"
                                        class="register-file-input"
                                        accept="image/x-png,image/jpeg,image/gif,image/jpg">

                                    <p>
                                        支援 JPG、JPEG、PNG、GIF 格式
                                    </p>


                                    <button
                                        type="button"
                                        class="register-upload-btn"
                                        id="uploadForm"
                                        name="uploadForm">
                                        <i class="fa-solid fa-arrow-up-from-bracket me-2"></i>
                                        上傳照片
                                    </button>

                                </div>


                                <div class="register-upload-preview">

                                    <div class="register-preview-placeholder">
                                        <i class="fa-regular fa-user"></i>
                                    </div>

                                    <img
                                        id="showimg"
                                        name="showimg"
                                        src="<?= $registrationAvatar === null ? '' : 'uploads/' . e($registrationAvatar) ?>"
                                        alt="會員照片預覽"
                                        class="register-preview-image"
                                        <?= $registrationAvatar === null ? 'style="display: none;"' : '' ?>>

                                </div>

                            </div>


                             <div
                                 id="progress-div01"
                                class="progress register-upload-progress"
                                style="display: none;">
                                <div
                                    id="progress-bar01"
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: 0%;"
                                    aria-valuenow="0"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    0%
                             </div>

                            <div id="avatar-error" class="register-error" role="alert" aria-live="polite" style="display: none;"></div>
                            <?php if (isset($registrationErrors['uploadname'])) { ?><div class="register-error server-register-error" data-error-for="uploadname"><i class="fa-solid fa-circle-exclamation"></i> <?= e($registrationErrors['uploadname']) ?></div><?php } ?>
                            </div>


                            <input
                                type="hidden"
                                name="uploadname"
                                id="uploadname"
                                value="<?= e($registrationAvatar ?? '') ?>">

                        </section>


                        <input
                            type="hidden"
                            name="formctl"
                            id="formctl"
                            value="reg">

                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= e(csrf_token()) ?>">


                        <!-- Submit -->
                        <div class="register-submit-area">

                            <button
                                type="submit"
                                class="register-submit-btn">
                                建立會員帳號

                                <i class="fa-solid fa-arrow-right"></i>
                            </button>


                            <p>
                                已經有會員帳號？

                                <a href="login.php">
                                    返回登入
                                </a>
                            </p>

                        </div>

                    </form>

                </main>

            </div>

        </div>
    </section>




    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once(__DIR__ . '/components/footer.php'); ?>
    </section>

    <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>
    <script src="assets/js/jquery.validate.js"></script>


    <script>
        // Replace the bundled validator's legacy email pattern with a practical
        // format check. The server still performs the authoritative validation.
        jQuery.validator.addMethod("email", function(value, element) {
            return this.optional(element) || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        });

        jQuery.validator.addMethod("checkphone", function(value, element, param) {
            var checkphone = /^[0]{1}[9]{1}[0-9]{8}$/;
            return this.optional(element) || (checkphone.test(value));
        });

        $('#reg').validate({
            errorElement: 'div',
            errorClass: 'register-error',

            errorPlacement: function(error, element) {

                const inputWrapper = element.closest('.register-input-wrapper');

                if (inputWrapper.length) {
                    // 有 icon 的 input：
                    // 錯誤訊息放在整個 wrapper 外面
                    error.insertAfter(inputWrapper);
                } else {
                    // 一般 input / select
                    error.insertAfter(element);
                }

            },

            highlight: function(element) {
                $(element).addClass('register-invalid');
            },

            unhighlight: function(element) {
                $(element).removeClass('register-invalid');
            },

            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: 'api/checkemail.php'
                },
                pw1: {
                    required: true,
                    maxlength: 20,
                    minlength: 4
                },
                pw2: {
                    required: true,
                    equalTo: '#pw1'
                },
                cname: {
                    required: true,
                },
                birthday: {
                    required: true,
                },
                mobile: {
                    required: true,
                    checkphone: true
                },
                address: {
                    required: true,
                },
                myCity: {
                    required: true,
                },
                myTown: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: '請輸入電子信箱',
                    email: '請輸入正確的電子信箱格式',
                    remote: '此電子信箱已註冊'
                },
                pw1: {
                    required: '請輸入密碼',
                    maxlength: '密碼最多 20 個字元',
                    minlength: '密碼至少需要 4 個字元'
                },
                pw2: {
                    required: '請再次輸入密碼',
                    equalTo: '兩次輸入的密碼不一致'
                },
                cname: {
                    required: '使用者名稱不得為空白',
                },
                birthday: {
                    required: '生日不得為空白',
                },
                mobile: {
                    required: '請輸入手機號碼',
                    checkphone: '請輸入正確的手機號碼格式'
                },
                address: {
                    required: '地址不得為空白',
                },
                myCity: {
                    required: '請選擇縣市',
                },
                myTown: {
                    required: '請選擇行政區',
                },
            }
        });

        $('#reg').on('input change', 'input, select', function() {
            const fieldName = this.name;
            if (fieldName) {
                $('.server-register-error[data-error-for="' + fieldName + '"]').remove();
            }
        });

        function getId(el) {
            return document.getElementById(el);
        }

        function showAvatarError(message) {
            $('#avatar-error').text(message).show();
        }

        function clearAvatarError() {
            $('#avatar-error').text('').hide();
            $('.server-register-error[data-error-for="uploadname"]').remove();
        }

        function resetAvatarProgress() {
            $('#progress-div01').hide();
            $('#progress-bar01').css('width', '0%').text('0%').attr('aria-valuenow', '0');
        }

        $('#fileToUpload').on('change', clearAvatarError);

        $('#uploadForm').click(function() {
            clearAvatarError();
            const fileInput = getId('fileToUpload');
            const file = fileInput.files[0];
            if (!file) {
                showAvatarError('請先選擇圖片。');
                resetAvatarProgress();
                return false;
            }

            const extension = file.name.includes('.') ? file.name.split('.').pop().toLowerCase() : '';
            if (!['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                showAvatarError('目前只支援 JPG、JPEG、PNG、GIF 圖片格式。');
                resetAvatarProgress();
                return false;
            }

            $('#progress-div01').css('display', 'flex');
            let formdata = new FormData();
            formdata.append('file1', file);
            formdata.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
            let ajax = new XMLHttpRequest();
            ajax.upload.addEventListener('progress', progressHandler, false);
            ajax.addEventListener('load', completeHandler, false);
            ajax.addEventListener('error', errorHandler, false);
            ajax.addEventListener('abort', abortHandler, false);
            ajax.open('POST', 'api/file_upload_parser.php');
            ajax.send(formdata);
            return false;
        });

        function progressHandler(event) {
            let percent = Math.round((event.loaded / event.total) * 100);
            $('#progress-bar01').css("width", percent + "%");
            $('#progress-bar01').html(percent + "%");
            $('#progress-bar01').attr('aria-valuenow', percent);
        }

        function completeHandler(event) {
            try {
                const data = JSON.parse(event.target.responseText);
                if (event.target.status >= 200 && event.target.status < 300 && data.success == 'true') {
                    $('#uploadname').val(data.fileName);
                    $('#showimg').attr({
                        'src': 'uploads/' + data.fileName
                    }).show();
                    clearAvatarError();
                } else {
                    showAvatarError(typeof data.error === 'string' ? data.error : '圖片上傳失敗，請稍後再試。');
                }
            } catch (error) {
                showAvatarError('圖片上傳回應格式錯誤，請稍後再試。');
            } finally {
                resetAvatarProgress();
            }
        }

        function errorHandler() {
            showAvatarError('圖片上傳失敗，請檢查網路後再試。');
            resetAvatarProgress();
        }

        function abortHandler() {
            showAvatarError('圖片上傳已取消。');
            resetAvatarProgress();
        }

        $('#myCity').change(function() {
            var CNo = $('#myCity').val();

            if (CNo == "") {
                $('#myTown').html('<option value="">請選擇地區</option>');
                $('#myZip').val('');
                $('#zipcode').text('選擇縣市與地區後將自動顯示');
                return false
            }

            $('#location-error').hide().text('');

            $.ajax({
                url: 'api/Town_ajax.php',
                type: 'post',
                dataType: 'json',
                data: {
                    CNo: CNo
                },
                success: function(data) {
                    if (data.c == true) {
                        $('#myTown').html(data.m);
                        $('#myZip').val("");
                    } else {
                        $('#location-error').text(data.m).show();
                    }
                },
                error: function() {
                    $('#myTown').html('<option value="">行政區載入失敗</option>');
                    $('#myZip').val('');
                    $('#location-error').text('行政區資料載入失敗，請稍後再試。').show();
                }
            });
        });

        $('#myTown').change(function() {
            var AutoNo = $('#myTown').val();

            if (AutoNo == "") {
                $('#myZip').val('');
                return false
            }

            $('#location-error').hide().text('');

            $.ajax({
                url: 'api/Zip_ajax.php',
                type: 'get',
                dataType: 'json',
                data: {
                    AutoNo: AutoNo
                },
                success: function(data) {
                    if (data.c == true) {
                        $('#myZip').val(data.Post);
                        $('#zipcode').html(data.Post + data.Cityname + data.Name);
                    } else {
                        $('#location-error').text(data.m).show();
                    }
                },
                error: function() {
                    $('#myZip').val('');
                    $('#zipcode').text('郵遞區號載入失敗');
                    $('#location-error').text('郵遞區號資料載入失敗，請稍後再試。').show();
                }
            });
        });
    </script>

</body>

</html>
