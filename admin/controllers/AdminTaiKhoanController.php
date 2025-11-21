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

    public function login()
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function loginprocess()
    {
        $this->taiKhoan->loginprocess();
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL_ADMIN . '?act=login');
        exit();
    }
}
