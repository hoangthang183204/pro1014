<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        :root {
            --primary: #977ac7ff;
            --primary-dark: #2543ecff;
            --secondary: #4c2bf1ff;
            --light: #020202ff;
            --dark: #212529;
            --success: #4cc9f0;
            --error: #f72585;
            --gray: #6c757d;
            --gray-light: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #394d71ff 0%, #05337dff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: var(--dark);
        }

        
        .container {
            width: 100%;
            max-width: 900px;
            position: relative;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            padding: 30px 30px 20px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .card-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 30px;
        }

        /* Layout 2 cột */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .form-column {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 15px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-light);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-control.error {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(247, 37, 133, 0.1);
        }

        .form-control.success {
            border-color: var(--success);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.1);
        }

        .error-message {
            color: var(--error);
            font-size: 12px;
            margin-top: 6px;
            display: none;
            font-weight: 500;
        }

        .form-actions {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 15px;
            margin-top: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            background: var(--gray);
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .card-footer {
            text-align: center;
            padding: 20px 30px 30px;
            border-top: 1px solid var(--gray-light);
        }

        .card-footer p {
            color: var(--gray);
            margin-bottom: 10px;
        }

        .card-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .card-footer a:hover {
            color: var(--secondary);
            background: rgba(67, 97, 238, 0.1);
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
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        /* Floating elements */
        .floating {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
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
            animation-delay: 2s;
        }

        .floating:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 40%;
            left: 85%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-column {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .container {
                max-width: 480px;
            }
        }

        @media (max-width: 480px) {
            .card-header, .card-body, .card-footer {
                padding: 20px;
            }
            
            .card-header h1 {
                font-size: 24px;
            }
            
            .form-row {
                margin: 0 -10px;
            }
            
            .form-column {
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating"></div>
    <div class="floating"></div>
    <div class="floating"></div>
    
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Tạo tài khoản</h1>
                <p>Điền thông tin để đăng ký tài khoản mới</p>
            </div>
            
            <div class="card-body">
                <form id="registerForm" action="?act=register-process" method="post">
                    <div class="form-row">
                        <!-- Cột 1 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                                <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" class="form-control" placeholder="Nhập tên đăng nhập" required>
                                <div class="error-message" id="ten_dang_nhap_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="ho_ten" class="form-label">Họ tên</label>
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control" placeholder="Nhập họ tên" required>
                                <div class="error-message" id="ho_ten_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Nhập địa chỉ email" required>
                                <div class="error-message" id="email_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" placeholder="Nhập số điện thoại">
                                <div class="error-message" id="so_dien_thoai_error"></div>
                            </div>
                        </div>

                        <!-- Cột 2 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label for="vai_tro" class="form-label">Vai trò</label>
                                <select name="vai_tro" id="vai_tro" class="form-control" required >
                                    <option value="huong_dan_vien" >Hướng dẫn viên</option>
                                </select>
                                <div class="error-message" id="vai_tro_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="trang_thai" class="form-label">Trạng thái hoạt động</label>
                                <select name="trang_thai" id="trang_thai" class="form-control" required>
                                    <option value="hoạt động">Hoạt động</option>
                                    <option value="khóa">Khóa</option>
                                </select>
                                <div class="error-message" id="trang_thai_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="mat_khau" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                                <div class="error-message" id="mat_khau_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="confirm" class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" name="confirm" id="confirm" class="form-control" placeholder="Nhập lại mật khẩu" required>
                                <div class="error-message" id="confirm_error"></div>
                            </div>
                        </div>

                        <!-- Nút đăng ký - chiếm full width -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Đăng ký</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-footer">
                <p>Đã có tài khoản? <a href="?act=login">Đăng nhập ngay</a></p>
            </div>
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
                const inputGroup = field.closest('.form-group');

                if (!errorElement) {
                    console.error(`❌ Error element not found for: ${field.name}_error`);
                    return;
                }

                if (isValid) {
                    field.classList.remove('error');
                    field.classList.add('success');
                    errorElement.style.display = 'none';
                    console.log(`✅ ${field.name}: Validation passed`);
                } else {
                    field.classList.remove('success');
                    field.classList.add('error');
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                    console.log(`❌ ${field.name}: ${errorMessage}`);
                }
            }

            clearError(field) {
                const errorElement = document.getElementById(field.name + '_error');
                const inputGroup = field.closest('.form-group');

                if (errorElement && inputGroup) {
                    field.classList.remove('error', 'success');
                    errorElement.style.display = 'none';
                }
            }

            clearAllErrors() {
                const errorElements = this.form.querySelectorAll('.error-message');
                const inputs = this.form.querySelectorAll('.form-control');

                errorElements.forEach(element => {
                    element.style.display = 'none';
                });

                inputs.forEach(input => {
                    input.classList.remove('error', 'success');
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