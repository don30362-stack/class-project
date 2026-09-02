<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('Connections/conn_db.php');
require_once('php_lib.php');
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

    <?php
    if (isset($_POST['formctl']) && $_POST['formctl'] == 'reg') {
        $email = $_POST['email'];
        $pw1 = md5($_POST['pw1']);
        $cname = $_POST['cname'];
        $tssn = $_POST['tssn'];
        $birthday = $_POST['birthday'];
        $mobile = $_POST['mobile'];
        $myZip = $_POST['myZip'] == '' ? NULL : $_POST['myZip'];
        $address = $_POST['address'] == '' ? NULL : $_POST['address'];
        $imgname = $_POST['uploadname'] == '' ? 'avatar.svg' : $_POST['uploadname'];
        $insertsql = "INSERT INTO member (email,pw1,cname,tssn,birthday,imgname) VALUES ('" . $email . "','" . $pw1 . "','" . $cname . "','" . $tssn . "','" . $birthday . "','" . $imgname . "')";
        $Result = $link->query($insertsql);
        if ($Result) {
            $emailid = $link->lastInsertId();
            $insertsql = "INSERT INTO addbook (emailid,setdefault,cname,mobile,myZip,address) VALUES ('" . $emailid . "', '1', '" . $cname . "', '" . $mobile . "', '" . $myZip . "', '" . $address . "')";
            $Result = $link->query($insertsql);
            $_SESSION['login'] = true;
            $_SESSION['emailid'] = $emailid;
            $_SESSION['email'] = $email;
            $_SESSION['cname'] = $cname;
            $_SESSION['imgname'] = $imgname;
            echo "<script>alert('謝謝您!會員資料已完成註冊');location.href='index.php';</script>";
        } else {
            echo "<script>alert('註冊失敗，請重新註冊，並連絡管理員。');location.href='register.php';</script>";
        }
    }
    ?>

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
                            完成會員註冊後，可管理個人資料、查看訂單紀錄，
                            並享有更完整的購物體驗。
                        </p>

                        <div class="register-benefits">

                            <div class="register-benefit">
                                <span>01</span>
                                <p>快速管理會員資料</p>
                            </div>

                            <div class="register-benefit">
                                <span>02</span>
                                <p>查看歷史訂單紀錄</p>
                            </div>

                            <div class="register-benefit">
                                <span>03</span>
                                <p>簡化後續購物流程</p>
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
                                            autocomplete="off">
                                    </div>
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
                                            placeholder="請輸入 4～20 位密碼">
                                    </div>
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
                                            placeholder="請再次輸入密碼">
                                    </div>
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
                                        placeholder="請輸入姓名">
                                </div>


                                <div class="register-field">
                                    <label for="tssn">
                                        身分證字號
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="tssn"
                                        id="tssn"
                                        class="register-input"
                                        placeholder="請輸入身分證字號">
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
                                        class="register-input">
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
                                        placeholder="例：0912345678">
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
                                            <option value="<?= $city_rows['AutoNo'] ?>">
                                                <?= $city_rows['Name'] ?>
                                            </option>
                                        <?php } ?>

                                    </select>

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
                                    </select>

                                </div>


                                <div class="register-field register-field-full">

                                    <label>
                                        郵遞區號 / 區域
                                    </label>

                                    <div
                                        id="zipcode"
                                        class="register-zipcode">
                                        選擇縣市與地區後將自動顯示
                                    </div>

                                    <input
                                        type="hidden"
                                        name="myZip"
                                        id="myZip"
                                        value="">

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
                                        placeholder="請輸入路名、巷弄、門牌等詳細地址">

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
                                        src=""
                                        alt="會員照片預覽"
                                        class="register-preview-image"
                                        style="display: none;">

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
                            </div>


                            <input
                                type="hidden"
                                name="uploadname"
                                id="uploadname"
                                value="">

                        </section>


                        <!-- ==========================================
                         05 驗證
                         ========================================== -->
                        <section class="register-section">

                            <div class="register-section-heading">
                                <span>05</span>

                                <div>
                                    <small>VERIFICATION</small>
                                    <h3>安全驗證</h3>
                                </div>
                            </div>


                            <div class="register-captcha">

                                <div class="register-captcha-image">

                                    <a
                                        href="javascript:void(0)"
                                        title="點擊更新驗證碼"
                                        onclick="getCaptcha()">
                                        <canvas id="can"></canvas>
                                    </a>

                                    <span>
                                        點擊圖片可更新
                                    </span>

                                </div>


                                <div class="register-field">

                                    <label for="recaptcha">
                                        驗證碼
                                        <span class="required-mark">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="recaptcha"
                                        id="recaptcha"
                                        class="register-input"
                                        placeholder="請輸入圖片中的驗證碼"
                                        autocomplete="off">

                                </div>

                            </div>


                            <input
                                type="hidden"
                                name="captcha"
                                id="captcha"
                                value="">

                        </section>


                        <input
                            type="hidden"
                            name="formctl"
                            id="formctl"
                            value="reg">


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
        <?php require_once('footer.php'); ?>
    </section>

    <?php require_once('jsfile.php'); ?>
    <script src="commlib.js"></script>
    <script src="jquery.validate.js"></script>


    <script>
        jQuery.validator.addMethod("tssn", function(value, element, param) {
            var tssn = /^[a-zA-Z]{1}[1-2]{1}[0-9]{8}$/;
            return this.optional(element) || (tssn.test(value));
        });

        jQuery.validator.addMethod("checkphone", function(value, element, param) {
            var checkphone = /^[0]{1}[9]{1}[0-9]{8}$/;
            return this.optional(element) || (checkphone.test(value));
        });

        jQuery.validator.addMethod("checkMyTown", function(value, element, param) {
            return (value !== "");
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
                    remote: 'checkemail.php'
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
                tssn: {
                    required: true,
                    tssn: true
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
                myTown: {
                    checkMyTown: true,
                },
                recaptcha: {
                    required: true,
                    equalTo: '#captcha'
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
                tssn: {
                    required: '身份證ID不得為空白',
                    tssn: '身份證ID格式有誤'
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
                myTown: {
                    checkMyTown: '需選擇郵遞區號',
                },
                recaptcha: {
                    required: '驗證碼不得為空白！',
                    equalTo: '驗證碼需相同！'
                },
            }
        });

        function getId(el) {
            return document.getElementById(el);
        }

        $('#uploadForm').click(function(e) {
            var fileName = $('#fileToUpload').val();
            var idxDot = fileName.lastIndexOf(".") + 1;
            let extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile == "jpg" || extFile == "jpeg" || extFile == "png" || extFile == "gif") {
                $('#progress-div01').css("display", "flex");
                let file1 = getId("fileToUpload").files[0];
                let formdata = new FormData();
                formdata.append("file1", file1);
                let ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", progressHandler, false);
                ajax.addEventListener("load", complereHandler, false);
                ajax.addEventListener("error", errorHandler, false);
                ajax.addEventListener("abort", abortHandler, false);
                ajax.open("POST", "file_upload_parser.php");
                ajax.send(formdata);
                return false
            } else {
                alert("目前只支援jpg,jpeg,png,gif檔案格式上傳!");
            }
        });

        function progressHandler(event) {
            let percent = Math.round((event.loaded / event.total) * 100);
            $('#progress-bar01').css("width", percent + "%");
            $('#progress-bar01').html(percent + "%");
        }

        function complereHandler(event) {
            let data = JSON.parse(event.target.responseText);

            if (data.success == 'true') {

                $('#uploadname').val(data.fileName);

                $('#showimg').attr({
                    'src': 'uploads/' + data.fileName
                }).show();

                $('#uploadForm').hide();

            } else {
                alert(data.error);
            }
        }

        function errorHandler(event) {
            alert("Upload Failed:上傳發生錯誤");
        }

        function abortHandler(event) {
            alert("Upload Aborted:上傳作業取消");
        }

        function getCaptcha() {
            var inputText = document.getElementById("captcha");
            inputText.value = captchaCode("can", 150, 50, "blue", "white", "28px", 5);
        }

        $(function() {
            getCaptcha();
        })

        $('#myCity').change(function() {
            var CNo = $('#myCity').val();

            if (CNo == "") {
                return false
            }

            $.ajax({
                url: 'Town_ajax.php',
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
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("系統目前無法連接到後台資料庫");
                }
            });
        });

        $('#myTown').change(function() {
            var AutoNo = $('#myTown').val();

            if (AutoNo == "") {
                return false
            }

            $.ajax({
                url: 'Zip_ajax.php',
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
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("系統目前無法連接到後台資料庫");
                }
            });
        });
    </script>

</body>

</html>