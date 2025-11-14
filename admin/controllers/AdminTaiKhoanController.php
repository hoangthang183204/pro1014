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
        // Gọi đến phương thức registerprocess() của model AdminTaiKhoan
        $this->taiKhoan->registerprocess();
    }
}
