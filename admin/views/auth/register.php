<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .input-group select {
            background: #fff;
        }

        .btn-register {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-register:hover {
            background-color: #218838;
        }

        .btn-register:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .form-footer {
            text-align: center;
            margin-top: 15px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .input-group.error input,
        .input-group.error select {
            border-color: #dc3545;
        }

        .input-group.success input,
        .input-group.success select {
            border-color: #28a745;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Đăng ký</h2>

        <form id="registerForm" action="?act=register-process" method="post">
            <div class="input-group">
                <label for="ten_dang_nhap">Tên đăng nhập</label>
                <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" placeholder="Tên Đăng Nhập..." required>
                <div class="error-message" id="ten_dang_nhap_error"></div>
            </div>

            <div class="input-group">
                <label for="ho_ten">Họ tên</label>
                <input type="text" name="ho_ten" id="ho_ten" placeholder="Họ Tên..." required>
                <div class="error-message" id="ho_ten_error"></div>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email..." required>
                <div class="error-message" id="email_error"></div>
            </div>

            <div class="input-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Số Điện Thoại...">
                <div class="error-message" id="so_dien_thoai_error"></div>
            </div>

            <div class="input-group">
                <label for="vai_tro">Vai trò</label>
                <select name="vai_tro" id="vai_tro" required>
                    <option value="nhan_vien">Nhân viên</option>
                    <option value="admin">Admin</option>
                    <option value="huong_dan_vien">Hướng dẫn viên</option>
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
                <input type="password" name="mat_khau" id="password" placeholder="Mật Khẩu..." required>
                <div class="error-message" id="mat_khau_error"></div> <!-- SỬA TÊN ID Ở ĐÂY -->
            </div>

            <div class="input-group">
                <label for="confirm">Nhập lại mật khẩu</label>
                <input type="password" name="confirm" id="confirm" placeholder="Nhập Lại Mật Khẩu..." required>
                <div class="error-message" id="confirm_error"></div>
            </div>

            <button type="submit" class="btn-register" id="submitBtn">Đăng ký</button>
        </form>
    </div>

    <script>
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
                    
                    setTimeout(() => {
                        console.log('🚀 Submitting form now...');
                        this.form.submit();
                    }, 500);
                } else {
                    console.log('❌ Validation failed, please check errors');
                    // Không cần alert nữa vì đã hiển thị lỗi trên form
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

                    case 'mat_khau': // SỬA TÊN FIELD Ở ĐÂY
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
                // SỬA LỖI Ở ĐÂY - Kiểm tra element tồn tại trước
                const errorElement = document.getElementById(field.name + '_error');
                const inputGroup = field.closest('.input-group');

                if (!errorElement) {
                    console.error(`❌ Error element not found for: ${field.name}_error`);
                    return; // Thoát nếu không tìm thấy element
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

        // Test function để submit không cần validation
        function testSubmit() {
            console.log('🚀 TEST: Force submitting form...');
            document.getElementById('registerForm').submit();
        }

        // Initialize validator when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Page loaded, initializing RegisterValidator...');
            
            // Kiểm tra xem các element có tồn tại không
            console.log('Form element:', document.getElementById('registerForm'));
            console.log('Submit button:', document.getElementById('submitBtn'));
            console.log('Password error element:', document.getElementById('mat_khau_error'));
            
            window.registerValidator = new RegisterValidator();
        });
    </script>
</body>

</html>