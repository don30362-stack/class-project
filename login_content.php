<div class="login-wrapper">

    <!-- 左側品牌視覺 -->
    <div class="login-visual">

        <div class="login-visual-overlay"></div>

        <div class="login-brand-content">

            <span class="login-brand-eyebrow">
                MEMBER
            </span>

            <h1>
                歡迎回來
            </h1>

            <p>
                登入會員帳號，查看訂單紀錄、會員資料，
                並享受更完整的購物體驗。
            </p>

        </div>

    </div>


    <!-- 右側登入 -->
    <div class="login-form-area">

        <div class="login-form-container">

            <div class="login-heading">

                <span class="login-eyebrow">
                    WELCOME BACK
                </span>

                <h2>會員登入</h2>

                <p>
                    請輸入您的會員帳號與密碼
                </p>

            </div>


            <form
                action=""
                method="POST"
                id="form1"
                class="login-form">

                <!-- Email -->
                <div class="login-field">

                    <label for="inputAccount">
                        電子信箱
                    </label>

                    <div class="login-input-wrapper">

                        <i class="fa-regular fa-envelope"></i>

                        <input
                            type="email"
                            id="inputAccount"
                            name="inputAccount"
                            placeholder="example@email.com"
                            required
                            autofocus>

                    </div>

                </div>


                <!-- Password -->
                <div class="login-field">

                    <div class="login-label-row">

                        <label for="inputPassword">
                            密碼
                        </label>

                        <a href="#" class="forgot-password">
                            忘記密碼？
                        </a>

                    </div>


                    <div class="login-input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="inputPassword"
                            name="inputPassword"
                            placeholder="請輸入密碼"
                            required>

                    </div>

                </div>


                <!-- Login Button -->
                <button
                    type="submit"
                    class="login-submit-btn">
                    登入
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>


            <div class="login-register">

                <span>
                    還不是會員？
                </span>

                <a href="register.php">
                    立即註冊
                </a>

            </div>

        </div>

    </div>

</div>