<?php 



require_once '../commons/env.php'; 
require_once '../commons/function.php'; 


require_once './controllers/ProductController.php';
require_once './controllers/GuideTaiKhoanController.php';
require_once './controllers/DashBoardHDVController.php';
require_once './controllers/NhatKyController.php';

// Require Models
require_once './models/ProductModel.php';
require_once './models/GuideTaiKhoan.php';
require_once './models/DashBoardHDVModel.php';
require_once './models/NhatKyModel.php';




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
    '/' => (new DashboardHDVController())->home(),
    
    // Auth routes
    'login' => (new GuideTaiKhoanController())->login(),
    'login-process' => (new GuideTaiKhoanController())->loginprocess(),
    'register' => (new GuideTaiKhoanController())->register(),
    'register-process' => (new GuideTaiKhoanController())->registerprocess(),
    'logout' => (new GuideTaiKhoanController())->logout(),
    
    // Nhật ký tour routes
    'nhat_ky' => (new NhatKyController())->index(),
    'nhat_ky_add' => (new NhatKyController())->create(),
    'nhat_ky_update' => (new NhatKyController())->update(),
    'nhat_ky_edit' => (new NhatKyController())->edit(),
    
    default => (new DashboardHDVController())->home(),
};