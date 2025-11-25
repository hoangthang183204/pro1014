<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập ADMIN</title>
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
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .btn-login:disabled {
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
        .input-group.error input {
            border-color: #dc3545;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: none;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Đăng nhập</h2>
        
        <!-- Thêm alert cho thông báo lỗi -->
        <div class="alert alert-error" id="errorAlert"></div>
        
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