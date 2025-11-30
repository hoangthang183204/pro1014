<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập ADMIN</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Thêm hiệu ứng background động */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(1deg);
            }
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.1),
                0 3px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15),
                0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            /* Dùng màu solid thay vì gradient */
        }

        .input-group {
            margin-bottom: 20px;
            margin-left: 0;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 600;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 8px;
            margin-left: -12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: #a0aec0;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-footer p {
            color: #718096;
            margin-bottom: 10px;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .form-footer a:hover {
            color: #764ba2;
            background: rgba(102, 126, 234, 0.1);
            text-decoration: none;
        }

        /* Style cho alert */
        .h4-alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .alert-error {
            background: linear-gradient(135deg, #fed7d7, #feb2b2);
            color: #c53030;
            border-left: 4px solid #fc8181;
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
            color: #276749;
            border-left: 4px solid #68d391;
        }

        /* Floating elements */
        .floating {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 3s ease-in-out infinite;
        }

        .floating:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            left: 80%;
            animation-delay: 1s;
        }

        .floating:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 40%;
            left: 85%;
            animation-delay: 2s;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .form-container {
                margin: 20px;
                padding: 25px 20px;
            }

            body {
                padding: 10px;
            }

            .form-container h2 {
                font-size: 24px;
            }
        }

        /* Loading animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="floating"></div>
        <div class="floating"></div>
        <div class="floating"></div>
        <h2><span>Đăng Nhập</span></h2>
        <?php
        // 🚨 ĐẶT Ở ĐÂY - TRÊN TIÊU ĐỀ "Đăng nhập"
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error" style="display: block; margin-bottom: 15px;">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success" style="display: block; margin-bottom: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <!-- Thêm alert cho thông báo lỗi -->
         <h4 class="h4">
        <div class="alert alert-error" id="errorAlert"></div>
        </h4>

        <form id="loginForm" action="?act=login-process" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <div class="error-message" id="email_error"></div>
            </div>

            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="mat_khau" id="password" placeholder="Mật khẩu" required>
                <div class="error-message" id="mat_khau_error"></div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">Đăng nhập</button>
        </form>
        <div class="form-footer">
            <p>Chưa có tài khoản? <a href="?act=register">Đăng ký</a></p>
        </div>


        <!-- Thêm nút test -->

    </div>

    <script>
        class LoginValidator {
            constructor() {
                this.form = document.getElementById('loginForm');
                this.submitBtn = document.getElementById('submitBtn');
                this.errorAlert = document.getElementById('errorAlert');
                this.init();
            }

            init() {
                console.log('✅ LoginValidator initialized');

                this.form.addEventListener('submit', (e) => {
                    console.log('📝 Form submit event triggered');
                    this.validateForm(e);
                });

                // Real-time validation khi người dùng rời khỏi field
                const inputs = this.form.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        console.log(`🔍 Validating field: ${input.name}`);
                        this.validateField(input);
                    });
                    input.addEventListener('input', () => this.clearError(input));
                });
            }

            validateForm(e) {
                console.log('🔄 Starting form validation...');
                e.preventDefault(); // QUAN TRỌNG: Ngăn form submit mặc định

                let isValid = true;
                const inputs = this.form.querySelectorAll('input[required]');

                console.log(`📋 Found ${inputs.length} required fields`);

                // Reset all errors first
                this.clearAllErrors();
                this.hideAlert();

                inputs.forEach(input => {
                    console.log(`Validating: ${input.name} = "${input.value}"`);
                    if (!this.validateField(input)) {
                        console.log(`❌ Validation failed for: ${input.name}`);
                        isValid = false;
                    }
                });

                console.log(`✅ Form validation result: ${isValid}`);

                if (isValid) {
                    console.log('🎉 Validation passed, submitting form...');
                    this.submitBtn.disabled = true;
                    this.submitBtn.textContent = 'Đang đăng nhập...';

                    // Submit form sau 1 giây
                    setTimeout(() => {
                        console.log('🚀 Submitting form now...');
                        this.form.submit();
                    }, 1000);
                } else {
                    console.log('❌ Validation failed');
                    this.showAlert('Vui lòng kiểm tra lại thông tin đăng nhập');
                }
            }

            validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';

                console.log(`🔍 Validating ${fieldName}: "${value}"`);

                switch (fieldName) {
                    case 'email':
                        if (!value) {
                            errorMessage = 'Email không được để trống';
                            isValid = false;
                        } else if (!this.isValidEmail(value)) {
                            errorMessage = 'Email không hợp lệ';
                            isValid = false;
                        }
                        break;

                    case 'mat_khau':
                        if (!value) {
                            errorMessage = 'Mật khẩu không được để trống';
                            isValid = false;
                        } else if (value.length < 1) {
                            errorMessage = 'Vui lòng nhập mật khẩu';
                            isValid = false;
                        }
                        break;
                }

                console.log(`📊 ${fieldName} validation: ${isValid} - ${errorMessage}`);
                this.setFieldValidation(field, isValid, errorMessage);
                return isValid;
            }

            isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            setFieldValidation(field, isValid, errorMessage) {
                const errorElement = document.getElementById(field.name + '_error');
                const inputGroup = field.closest('.input-group');

                if (!errorElement) {
                    console.error(`❌ Error element not found for: ${field.name}_error`);
                    return;
                }

                if (isValid) {
                    inputGroup.classList.remove('error');
                    errorElement.style.display = 'none';
                    console.log(`✅ ${field.name}: Validation passed`);
                } else {
                    inputGroup.classList.add('error');
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                    console.log(`❌ ${field.name}: ${errorMessage}`);
                }
            }

            clearError(field) {
                const errorElement = document.getElementById(field.name + '_error');
                const inputGroup = field.closest('.input-group');

                if (errorElement && inputGroup) {
                    inputGroup.classList.remove('error');
                    errorElement.style.display = 'none';
                }
            }

            clearAllErrors() {
                const errorElements = this.form.querySelectorAll('.error-message');
                const inputGroups = this.form.querySelectorAll('.input-group');

                errorElements.forEach(element => {
                    element.style.display = 'none';
                });

                inputGroups.forEach(group => {
                    group.classList.remove('error');
                });
            }

            showAlert(message) {
                this.errorAlert.textContent = message;
                this.errorAlert.style.display = 'block';
            }

            hideAlert() {
                this.errorAlert.style.display = 'none';
            }
        }

        // Test function
        function testValidation() {
            console.log('🧪 Testing validation...');

            // Test case 1: Empty fields
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            window.loginValidator.validateForm(new Event('submit'));

            // Test case 2: Invalid email
            setTimeout(() => {
                document.getElementById('email').value = 'invalid-email';
                document.getElementById('password').value = '123';
                window.loginValidator.validateForm(new Event('submit'));
            }, 2000);

            // Test case 3: Valid data
            setTimeout(() => {
                document.getElementById('email').value = 'test@example.com';
                document.getElementById('password').value = '123456';
                window.loginValidator.validateForm(new Event('submit'));
            }, 4000);
        }

        // Initialize validator when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Page loaded, initializing LoginValidator...');

            // Kiểm tra xem các element có tồn tại không
            console.log('Form element:', document.getElementById('loginForm'));
            console.log('Submit button:', document.getElementById('submitBtn'));
            console.log('Error alert:', document.getElementById('errorAlert'));

            window.loginValidator = new LoginValidator();


        });
    </script>
</body>

</html>