<?php
class AdminTaiKhoanController
{
    public $taiKhoan;

    public function __construct()
    {
        $this->taiKhoan = new AdminTaiKhoan();
    }

    // Hiển thị form đăng ký
    public function register()
    {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Xử lý đăng ký
    public function registerprocess()
    {
        $this->taiKhoan->registerprocess();
    }

    // Hiển thị form login và xử lý login
    public function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Vui lòng nhập email và mật khẩu.";
                header("Location: " . BASE_URL_ADMIN . "?act=login");
                exit();
            }

            $user = $this->taiKhoan->attemptLogin($email, $password);
            if ($user) {
                // Lưu session
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_name'] = $user['ten'] ?? ($user['ten_admin'] ?? '');
                $_SESSION['success'] = "Đăng nhập thành công.";
                header("Location: " . BASE_URL_ADMIN . "?act=/");
                exit();
            } else {
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng.";
                header("Location: " . BASE_URL_ADMIN . "?act=login");
                exit();
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Đăng xuất
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL_ADMIN . "?act=login");
        exit();
    }
}
