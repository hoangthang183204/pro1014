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

        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #7c3aed;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --bg-color: #f9fafb;
            --error-color: #ef4444;
            --success-color: #10b981;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(-45deg,
                    #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Background pattern */
        .bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            background-image:
                radial-gradient(circle at 25% 25%, var(--primary-color) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, var(--secondary-color) 2px, transparent 2px);
            background-size: 60px 60px;
            z-index: 1;
        }

        .login-container {
            background: linear-gradient(-45deg,
                    #fcbba7ff, #4bffc3ff, #23a6d5, #4d9fecff);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 440px;
            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.08),
                0 2px 15px rgba(0, 0, 0, 0.03);
            position: relative;
            z-index: 2;
            border: 1px solid rgba(95, 77, 238, 0.9);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 16px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 16px;
            font-weight: 400;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 24px;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
        }

        .input-container {
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 16px 20px;
            padding-left: 48px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: white;
            color: var(--text-color);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-field::placeholder {
            color: #9ca3af;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 20px;
        }

        .input-field:focus+.input-icon {
            color: var(--primary-color);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            font-size: 18px;
        }

        .password-toggle:hover {
            color: var(--text-color);
        }

        /* Remember me & Forgot password */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--text-color);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 14px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Register link */
        .register-section {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .register-text {
            color: var(--text-light);
            font-size: 14px;
        }

        .register-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
            transition: color 0.3s;
        }

        .register-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Alert messages */
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: none;
            animation: slideIn 0.3s ease;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #16a34a;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
            }

            .login-title {
                font-size: 28px;
            }

            .input-field {
                padding: 14px 16px;
                padding-left: 44px;
            }
        }
    </style>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="bg-pattern"></div>

    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">HDV</div>
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
                <div class="error-message" id="email_error" style="color: var(--error-color); font-size: 14px; margin-top: 4px;"></div>
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
                <div class="error-message" id="mat_khau_error" style="color: var(--error-color); font-size: 14px; margin-top: 4px;"></div>
            </div>

            <div class="form-options">
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="checkbox-label">Ghi nhớ đăng nhập</label>
                </div>
                <a href="#" class="forgot-link">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span id="btnText">Đăng nhập</span>
                <span id="btnLoading" style="display: none;">Đang xử lý...</span>
            </button>
        </form>

        <div class="register-section">
            <span class="register-text">Chưa có tài khoản?</span>
            <a href="?act=register" class="register-link">Đăng ký ngay</a>
        </div>
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

                    // TẠM THỜI: Submit form thật (không dùng setTimeout)
                    // Xóa phần setTimeout demo và submit form thật
                    form.submit();

                    // Hoặc nếu muốn có thông báo thành công trước khi redirect:
                    // showSuccess('Đăng nhập thành công! Đang chuyển hướng...');
                    // setTimeout(() => {
                    //     form.submit(); // Submit form thật
                    // }, 1500);
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

                input.style.borderColor = 'var(--error-color)';
                input.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
            }

            function clearError(input) {
                const errorId = input.name + '_error';
                const errorElement = document.getElementById(errorId);

                if (errorElement) {
                    errorElement.textContent = '';
                }

                input.style.borderColor = '';
                input.style.boxShadow = '';
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

            function showSuccess(message) {
                showAlert(message, 'success');
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