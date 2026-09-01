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
    <style>
        .input-group>.form-control {
            width: 100%;
        }

        span.error-tips,
        span.error-tips::before {
            font-family: "Font Awesome 5 Free";
            color: red;
            font-weight: 900;
            content: "\f0c4";
        }

        span.valid-tips,
        span.valid-tips::before {
            font-family: "Font Awesome 5 Free";
            color: greenyellow;
            font-weight: 900;
            content: "\f00c";
        }
    </style>

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

    <section id="content" class="mt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>會員註冊頁面</h1>
                    <p>請輸入相關資料，*為必須輸入欄位</p>
                </div>
            </div>

            <div class="row">
                <div class="col-8 offset-2 text-left">
                    <form id="reg" name="reg" action="register.php" method="POST">
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="請輸入email帳號" autocomplete="off">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="pw1" id="pw1" class="form-control" placeholder="請輸入密碼">
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="pw2" id="pw2" class="form-control" placeholder="請再次確認密碼">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="cname" id="cname" class="form-control" placeholder="請輸入姓名">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="tssn" id="tssn" class="form-control" placeholder="請輸入身份證字號">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="birthday" id="birthday" onfocus="(this.type='date')" class="form-control" placeholder="*請選擇生日">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="請輸入手機號碼">
                        </div>
                        <div class="input-group mb-3">
                            <select name="myCity" id="myCity" class="form-control">
                                <option value="">請選擇市區</option>
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
                            <br>
                            <select name="myTown" id="myTown" class="form-control">
                                <option value="">請選擇地區</option>
                            </select>
                        </div>
                        <label for="address" class="form-control" id="zipcode" name="zipcode">郵遞區號：地址</label>
                        <div class="input-group mb-3">
                            <input type="hidden" name="myZip" id="myZip" value="">
                            <input type="text" name="address" id="address" class="form-control" placeholder="請輸入後續地址">
                        </div>

                        <label for="fileToUpload" class="form-control">上傳相片：</label>

                        <div class="input-group mb-3">
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" title="請上傳相片圖示" accept="image/x-png,image/jpeg,image/gif,image/jpg">

                            <p><button type="button" class="btn btn-danger" id="uploadForm" name="uploadForm">開始上傳</button></p>

                            <div id="progress-div01" class="progress" style="width: 100%; display: none;">
                                <div id="progress-bar01" class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>

                            <input type="hidden" name="uploadname" id="uploadname" value="">
                            <img id="showimg" name="showimg" src="" alt="photo" style="display: none;" class="img-fluid">
                        </div>

                        <div class="input-group mb-3">
                            <input type="hidden" name="captcha" id="captcha" value="">
                            <a href="javascript:void(0)" title="按我更新認證" onclick="getCaptcha()">
                                <canvas id="can"></canvas>
                            </a>
                            <input type="text" name="recaptcha" id="recaptcha" class="form-control" placeholder="請輸入認證碼">
                        </div>
                        <input type="hidden" name="formctl" id="formctl" value="reg">
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-success btn-lg">送出</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <hr>



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
                    required: 'email信箱不得為空白',
                    email: 'email信箱格式有誤',
                    remote: 'email信箱已經註冊'
                },
                pw1: {
                    required: '密碼不得為空白',
                    maxlength: '密碼最大長度為20位(4-20位英文字母與數字的組合)',
                    minlength: '密碼最小長度為4位(4-20位英文字母與數字的組合)'
                },
                pw2: {
                    required: '確認密碼不得為空白',
                    equalTo: '兩次輸入的密碼必須一致！'
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
                    required: '手機號碼不得為空白',
                    checkphone: '手機號碼格式有誤'
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
                    'src': 'uploads/' + data.fileName,
                    'style': 'display:block'
                });
                $('button.btn.btn-danger').attr({
                    'style': 'display:none;'
                });
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
                        $('myZip').val("");
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