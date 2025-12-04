<?php 
session_name('GUIDE_SESSION');
session_start();


require_once '../commons/env.php'; 
require_once '../commons/function.php'; 


require_once './controllers/ProductController.php';
require_once './controllers/GuideTaiKhoanController.php';
require_once './controllers/DashBoardHDVController.php';
require_once './controllers/PersonalGuideController.php';
require_once './controllers/NhatKyController.php';
require_once './controllers/KhachDoanController.php';
require_once './controllers/LichTrinhController.php';

// Require Models
require_once './models/ProductModel.php';
require_once './models/GuideTaiKhoan.php';
require_once './models/DashBoardHDVModel.php';
require_once './models/Database.php';
require_once './models/PersonalGuideModel.php';
require_once './models/NhatKyModel.php';
require_once './models/KhachDoanModel.php';
require_once './models/LichTrinhModel.php';




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
    
    // Guide routes
    // 'guide-dashboard' => (new GuideTaiKhoanController())->guideDashboard(),
  
    //Thông tin tài khoản
    'my-profile' => (new PersonalGuideController())->showProfile(),
    'profile-settings' => (new PersonalGuideController())->showProfileSettings(),
    'update-profile' => (new PersonalGuideController())->updateProfile(),
  
    // Nhật ký tour routes
    'nhat_ky' => (new NhatKyController())->index(),
    'nhat_ky_add' => (new NhatKyController())->create(),
    'nhat_ky_update' => (new NhatKyController())->update(),
    'nhat_ky_edit' => (new NhatKyController())->edit(),
    'nhat_ky_store' => (new NhatKyController())->store(),
    'nhat_ky_delete' => (new NhatKyController())->delete(),
    
    // Khách đoàn routes
    'xem_danh_sach_khach' => (new KhachDoanController())->index(),
    'check_in_khach' => (new KhachDoanController())->update_checkin_status(),

    // Lịch trình routes
    'lich-trinh' => (new LichTrinhController())->index(),
    'lich-trinh-detail' => (new LichTrinhController())->detail(),
    'lich-trinh-update-checklist' => (new LichTrinhController())->updateChecklist(),
    'lich-lam-viec' => (new LichTrinhController())->lichLamViec(),
    'update-checklist-guide' => (new LichTrinhController())->updateChecklistForGuide(),

    default => (new DashboardHDVController())->home(),
};
?>