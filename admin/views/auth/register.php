<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
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

        /* Background animation */
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
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
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
            max-width: 450px;
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
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 600;
            font-size: 14px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px 2px;
            border: 3px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .btn-register {
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

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
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

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            font-weight: 500;
        }

        .input-group.error input,
        .input-group.error select {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .input-group.success input,
        .input-group.success select {
            border-color: #38a169;
            box-shadow: 0 0 0 3px rgba(56, 161, 105, 0.1);
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

        /* Loading animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .form-container {
                margin: 20px;
                padding: 25px 20px;
            }
            
            body {
                padding: 10px;
                height: auto;
                min-height: 100vh;
            }
            
            .form-container h2 {
                font-size: 24px;
            }
        }

        /* Scrollbar styling */
        .form-container {
            max-height: 90vh;
            overflow-y: auto;
             
        }

        .form-container::-webkit-scrollbar {
            width: 6px;
        }

        .form-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .form-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            
        }
    </style>
</head>

<body>
    <!-- Floating background elements -->
    <div class="floating"></div>
    <div class="floating"></div>
    <div class="floating"></div>
    
    <div class="form-container">
        <h2>Đăng ký</h2>

        <form id="registerForm" action="?act=register-process" method="post">
            <div class="input-group">
                <label for="ten_dang_nhap">Tên đăng nhập</label>
                <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" placeholder="Tên đăng nhập..." required>
                <div class="error-message" id="ten_dang_nhap_error"></div>
            </div>

            <div class="input-group">
                <label for="ho_ten">Họ tên</label>
                <input type="text" name="ho_ten" id="ho_ten" placeholder="Họ tên..." required>
                <div class="error-message" id="ho_ten_error"></div>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email..." required>
                <div class="error-message" id="email_error"></div>
            </div>

            <div class="input-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Số điện thoại...">
                <div class="error-message" id="so_dien_thoai_error"></div>
            </div>

            <div class="input-group">
                <label for="vai_tro">Vai trò</label>
                <select name="vai_tro" id="vai_tro" required>
                    <option value="admin">Admin</option>
                </select>
                <div class="error-message" id="vai_tro_error"></div>
            </div>

            <div class="input-group">
                <label for="trang_thai">Trạng thái hoạt động</label>
                <select name="trang_thai" id="trang_thai" required>
                    <option value="hoạt động">Hoạt động</option>
                    <option value="khóa">Khóa</option>
                </select>
                <div class="error-message" id="trang_thai_error"></div>
            </div>

            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="mat_khau" id="password" placeholder="Mật khẩu..." required>
                <div class="error-message" id="mat_khau_error"></div>
            </div>

            <div class="input-group">
                <label for="confirm">Nhập lại mật khẩu</label>
                <input type="password" name="confirm" id="confirm" placeholder="Nhập lại mật khẩu..." required>
                <div class="error-message" id="confirm_error"></div>
            </div>

            <button type="submit" class="btn-register" id="submitBtn">Đăng ký</button>
        </form>

        <div class="form-footer">
            <p>Đã có tài khoản? <a href="?act=login">Đăng nhập</a></p>
        </div>
    </div>

    <script>
        // JavaScript code giữ nguyên
        class RegisterValidator {
            constructor() {
                this.form = document.getElementById('registerForm');
                this.submitBtn = document.getElementById('submitBtn');
                this.init();
            }

            init() {
                console.log('✅ RegisterValidator initialized');

                this.form.addEventListener('submit', (e) => {
                    console.log('📝 Form submit event triggered');
                    this.validateForm(e);
                });

                // Real-time validation
                const inputs = this.form.querySelectorAll('input, select');
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
                e.preventDefault();

                let isValid = true;
                const inputs = this.form.querySelectorAll('input[required], select[required]');

                console.log(`📋 Found ${inputs.length} required fields`);

                // Reset all errors first
                this.clearAllErrors();

                inputs.forEach(input => {
                    console.log(`Validating: ${input.name} = "${input.value}"`);
                    if (!this.validateField(input)) {
                        console.log(`❌ Validation failed for: ${input.name}`);
                        isValid = false;
                    }
                });

                // Special validation for password match
                if (!this.validatePasswordMatch()) {
                    isValid = false;
                }

                console.log(`✅ Form validation result: ${isValid}`);

                if (isValid) {
                    console.log('🎉 Validation passed, submitting form...');
                    this.submitBtn.disabled = true;
                    this.submitBtn.textContent = 'Đang xử lý...';
                    this.submitBtn.innerHTML = '<div class="loading"></div> Đang xử lý...';

                    setTimeout(() => {
                        console.log('🚀 Submitting form now...');
                        this.form.submit();
                    }, 500);
                } else {
                    console.log('❌ Validation failed, please check errors');
                }
            }

            validateField(field) {
                const value = field.value.trim();
                const fieldName = field.name;
                let isValid = true;
                let errorMessage = '';

                console.log(`🔍 Validating ${fieldName}: "${value}"`);

                switch (fieldName) {
                    case 'ten_dang_nhap':
                        if (value.length < 3) {
                            errorMessage = 'Tên đăng nhập phải có ít nhất 3 ký tự';
                            isValid = false;
                        } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                            errorMessage = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
                            isValid = false;
                        }
                        break;

                    case 'ho_ten':
                        if (value.length < 2) {
                            errorMessage = 'Họ tên phải có ít nhất 2 ký tự';
                            isValid = false;
                        }
                        break;

                    case 'email':
                        if (!value) {
                            errorMessage = 'Email không được để trống';
                            isValid = false;
                        } else if (!this.isValidEmail(value)) {
                            errorMessage = 'Email không hợp lệ';
                            isValid = false;
                        }
                        break;

                    case 'so_dien_thoai':
                        if (value && !this.isValidPhone(value)) {
                            errorMessage = 'Số điện thoại không hợp lệ (ví dụ: 0912345678 hoặc +84912345678)';
                            isValid = false;
                        }
                        break;

                    case 'mat_khau':
                        if (value.length < 6) {
                            errorMessage = 'Mật khẩu phải có ít nhất 6 ký tự';
                            isValid = false;
                        }
                        break;

                    case 'confirm':
                        const password = document.getElementById('password').value;
                        if (value !== password) {
                            errorMessage = 'Mật khẩu nhập lại không khớp';
                            isValid = false;
                        }
                        break;
                }

                console.log(`📊 ${fieldName} validation: ${isValid} - ${errorMessage}`);
                this.setFieldValidation(field, isValid, errorMessage);
                return isValid;
            }

            validatePasswordMatch() {
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('confirm').value;

                console.log(`🔐 Password match check: "${password}" vs "${confirm}"`);

                if (password !== confirm) {
                    this.setFieldValidation(document.getElementById('confirm'), false, 'Mật khẩu nhập lại không khớp');
                    return false;
                }

                this.setFieldValidation(document.getElementById('confirm'), true, '');
                return true;
            }

            isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            isValidPhone(phone) {
                const phoneRegex = /^(0|\+84)(\d{9,10})$/;
                return phoneRegex.test(phone.replace(/\s/g, ''));
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
                    inputGroup.classList.add('success');
                    errorElement.style.display = 'none';
                    console.log(`✅ ${field.name}: Validation passed`);
                } else {
                    inputGroup.classList.remove('success');
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
                    inputGroup.classList.remove('error', 'success');
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
                    group.classList.remove('error', 'success');
                });
            }
        }

        // Initialize validator when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Page loaded, initializing RegisterValidator...');
            window.registerValidator = new RegisterValidator();
        });
    </script>
</body>

</html>