<?php
class GuideTaiKhoanController
{
    public $taiKhoan;

    public function __construct()
    {
        $this->taiKhoan = new GuideTaiKhoan();
    }

    // Hiển thị form đăng ký
    public function register()
    {
        // Nếu đã đăng nhập thì redirect
        if (checkGuideLogin()) {
            header('Location: ' . BASE_URL_GUIDE);
            exit();
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Xử lý đăng ký
    public function registerprocess()
    {
        $this->taiKhoan->registerprocess();
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        // Nếu đã đăng nhập thì redirect
        if (checkGuideLogin()) {
            $currentUser = getCurrentGuide();
            if ($currentUser['vai_tro'] === 'huong_dan_vien') {
                header('Location: ' . BASE_URL_GUIDE);
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function loginprocess()
    {
        $this->taiKhoan->loginprocess();
    }

    // Đăng xuất
    // Đăng xuất
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // 1. Xóa sạch session
        session_unset();
        session_destroy();
        
        // 2. Chuyển hướng bằng JavaScript (An toàn tuyệt đối khỏi lỗi trang trắng)
        echo '<script>window.location.href = "index.php?act=login";</script>';
        exit();
    }

    // Trong class GuideTaiKhoanController

    public function home()
    {
        // Nếu đã đăng nhập, chuyển hướng đến dashboard
        if (checkGuideLogin()) {
            $currentUser = getCurrentGuide();
            if ($currentUser['vai_tro'] === 'huong_dan_vien') {
                header('Location: ' . BASE_URL_GUIDE);
            } else {
                header('Location: ' . BASE_URL_GUIDE . '?act=profile');
            }
            exit();
        }
    }

    public function guideDashboard()
    {
        if (!isset($_SESSION['guide_id'])) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Kiểm tra vai trò nếu cần
        $vai_tro = $_SESSION['guide_vai_tro'] ?? '';
        if (!in_array($vai_tro, ['huong_dan_vien', 'huong_dan_yien', 'admin'])) {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
            header("Location: " . BASE_URL_GUIDE);
            exit();
        }

        require_once './views/trangchu.php';
    }
}
