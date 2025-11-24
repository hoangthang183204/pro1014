<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once '../commons/env.php'; // Khai báo biến môi trường
require_once '../commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/ProductController.php';
require_once './controllers/GuideTaiKhoanController.php';

// Require toàn bộ file Models
require_once './models/ProductModel.php';
require_once './models/GuideTaiKhoan.php';

// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Route
$act = $_GET['act'] ?? '/';

// Danh sách các route không cần đăng nhập (CHỈ các trang auth)
$public_routes = [
    'login',
    'login-process',
    'register',
    'register-process'
];

// Middleware kiểm tra đăng nhập
// CHỈ cho phép các route public và trang chủ (khi chưa login)
if ($act === '/' && !checkGuideLogin()) {
    // Nếu là trang chủ và chưa đăng nhập, chuyển hướng đến login
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}
elseif (!in_array($act, $public_routes) && $act !== '/' && !checkGuideLogin()) {
    // Nếu không phải public route, không phải trang chủ, và chưa đăng nhập
    $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục!";
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}

match ($act) {
    // Trang chủ guide - CHỈ hiển thị khi đã đăng nhập
    '/' => (new GuideTaiKhoanController())->home(),
    
    // Auth routes
    'login' => (new GuideTaiKhoanController())->login(),
    'login-process' => (new GuideTaiKhoanController())->loginprocess(),
    'register' => (new GuideTaiKhoanController())->register(),
    'register-process' => (new GuideTaiKhoanController())->registerprocess(),
    'logout' => (new GuideTaiKhoanController())->logout(),
    
    // Guide routes
    'guide-dashboard' => (new GuideTaiKhoanController())->guideDashboard(),
    
    default => (new GuideTaiKhoanController())->home(),
};