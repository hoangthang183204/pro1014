<?php
function checkLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $publicRoutes = [
        'login',
        'login-process', 
        'register',
        'register-process',
        'logout-admin'
    ];

    $act = $_GET['act'] ?? '/';

    // Nếu không phải route public và chưa đăng nhập
    if (!in_array($act, $publicRoutes) && empty($_SESSION['admin_id'])) {
        $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục!";
        header('Location: ' . BASE_URL_ADMIN . '?act=login');
        exit();
    }

    // Nếu đã đăng nhập nhưng cố truy cập trang login/register
    if (in_array($act, ['login', 'register']) && !empty($_SESSION['admin_id'])) {
        // 🚨 QUAN TRỌNG: Giữ nguyên session success/error khi redirect
        header('Location: ' . BASE_URL_ADMIN);
        exit();
    }
}