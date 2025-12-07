<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống Quản trị</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #213e68ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background-color: #4a6ee0;
            border-radius: 8px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 700;
        }

        .login-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .input-container {
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            padding-left: 40px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
            background-color: white;
            color: #333;
        }

        .input-field:focus {
            outline: none;
            border-color: #4a6ee0;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #666;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .forgot-link {
            color: #4a6ee0;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #4a6ee0;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #3a5ecf;
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .register-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }

        .register-link {
            color: #4a6ee0;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert-error {
            background-color: #ffeaea;
            border: 1px solid #ffc7c7;
            color: #d32f2f;
        }

        .alert-success {
            background-color: #edf7ed;
            border: 1px solid #c5e1c5;
            color: #2e7d32;
        }

        .error-message {
            font-size: 12px;
            color: #d32f2f;
            margin-top: 4px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 20px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">A</div>
            <h1 class="login-title">Đăng nhập</h1>
            <p class="login-subtitle">Vui lòng đăng nhập để tiếp tục</p>
        </div>

        <div id="errorAlert" class="alert alert-error"></div>
        <div id="successAlert" class="alert alert-success"></div>

        <form id="loginForm" action="?act=login-process" method="post">
            <div class="form-group">
                <label class="input-label">Email</label>
                <div class="input-container">
                    <input type="email"
                        name="email"
                        id="email"
                        class="input-field"
                        placeholder="example@company.com"
                        required>
                    <span class="input-icon material-icons">mail</span>
                </div>
                <div class="error-message" id="email_error"></div>
            </div>

            <div class="form-group">
                <label class="input-label">Mật khẩu</label>
                <div class="input-container">
                    <input type="password"
                        name="mat_khau"
                        id="password"
                        class="input-field"
                        placeholder="••••••••"
                        required>
                    <span class="input-icon material-icons">lock</span>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <span class="material-icons" id="toggleIcon">visibility_off</span>
                    </button>
                </div>
                <div class="error-message" id="mat_khau_error"></div>
            </div>

            <div class="form-options">
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <!-- <a href="#" class="forgot-link">Quên mật khẩu?</a> -->
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span id="btnText">Đăng nhập</span>
                <span id="btnLoading" style="display: none;">Đang xử lý...</span>
            </button>
        </form>

        <!-- <div class="register-section">
            <span>Chưa có tài khoản?</span>
            <a href="?act=register" class="register-link">Đăng ký ngay</a>
        </div> -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const errorAlert = document.getElementById('errorAlert');
            const successAlert = document.getElementById('successAlert');

            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });

            // Real-time validation
            emailInput.addEventListener('blur', validateEmail);
            passwordInput.addEventListener('blur', validatePassword);

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (validateForm()) {
                    showLoading(true);
                    
                    // Submit form
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }
            });

            function validateEmail() {
                const email = emailInput.value.trim();
                const errorElement = document.getElementById('email_error');

                if (!email) {
                    showError(emailInput, 'Vui lòng nhập email');
                    return false;
                }

                if (!isValidEmail(email)) {
                    showError(emailInput, 'Email không hợp lệ');
                    return false;
                }

                clearError(emailInput);
                return true;
            }

            function validatePassword() {
                const password = passwordInput.value.trim();
                const errorElement = document.getElementById('mat_khau_error');

                if (!password) {
                    showError(passwordInput, 'Vui lòng nhập mật khẩu');
                    return false;
                }

                if (password.length < 6) {
                    showError(passwordInput, 'Mật khẩu phải có ít nhất 6 ký tự');
                    return false;
                }

                clearError(passwordInput);
                return true;
            }

            function validateForm() {
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();

                if (!isEmailValid || !isPasswordValid) {
                    showAlert('Vui lòng kiểm tra lại thông tin đăng nhập', 'error');
                    return false;
                }

                return true;
            }

            function showError(input, message) {
                const errorId = input.name + '_error';
                const errorElement = document.getElementById(errorId);

                if (errorElement) {
                    errorElement.textContent = message;
                }

                input.style.borderColor = '#d32f2f';
            }

            function clearError(input) {
                const errorId = input.name + '_error';
                const errorElement = document.getElementById(errorId);

                if (errorElement) {
                    errorElement.textContent = '';
                }

                input.style.borderColor = '';
            }

            function showAlert(message, type = 'error') {
                if (type === 'error') {
                    errorAlert.textContent = message;
                    errorAlert.style.display = 'block';
                    successAlert.style.display = 'none';
                } else {
                    successAlert.textContent = message;
                    successAlert.style.display = 'block';
                    errorAlert.style.display = 'none';
                }
            }

            function showLoading(loading) {
                if (loading) {
                    submitBtn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'inline';
                } else {
                    submitBtn.disabled = false;
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                }
            }

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
</body>

</html>