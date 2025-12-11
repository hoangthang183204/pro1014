<?php
session_name('ADMIN_SESSION');
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require file Common
require_once '../commons/env.php';
require_once '../commons/function.php';

// Require Controllers
require_once './controllers/DashboardController.php';
require_once './controllers/AdminDanhMucTourController.php';
require_once './controllers/AdminTourController.php';
require_once './controllers/AdminLichTrinhKhoiHanhController.php';
require_once './controllers/AdminDatTourController.php';
require_once './controllers/AdminTaiKhoanController.php';
require_once './controllers/AdminKhachHangController.php';
require_once './controllers/AdminLichLamViecHDVController.php';
require_once './controllers/AdminPhanPhongController.php';
require_once './controllers/AdminChinhSachController.php';
require_once './controllers/AdminHuongDanVienController.php';

// Require Models
require_once './models/AdminDashboard.php';
require_once './models/AdminDanhMuc.php';
require_once './models/AdminTour.php';
require_once './models/AdminLichTrinhKhoiHanh.php';
require_once './models/AdminDatTour.php';
require_once './models/AdminTaiKhoan.php';
require_once './models/AdminKhachHang.php';
require_once './models/AdminLichLamViecHDV.php';
require_once './models/AdminPhanPhong.php';
require_once './models/AdminChinhSach.php';
require_once './models/AdminHuongDanVien.php';


require_once './middleware/check-login.php';
// Route
$act = $_GET['act'] ?? '/';

// 🚨 QUAN TRỌNG: Xử lý thông báo session TRƯỚC KHI checkLogin()
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    // KHÔNG unset ở đây, để checkLogin() xử lý redirect
}

if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    // KHÔNG unset ở đây
}

// 🚨 Gọi checkLogin() SAU KHI đã lấy thông báo session
checkLogin();

// Routing
match ($act) {
    // PUBLIC ROUTES - Không cần đăng nhập
    'login' => (new AdminTaiKhoanController())->login(),
    'login-process' => (new AdminTaiKhoanController())->loginprocess(),
    'register' => (new AdminTaiKhoanController())->register(),
    'register-process' => (new AdminTaiKhoanController())->registerprocess(),
    'logout-admin' => (new AdminTaiKhoanController())->logout(),

    '/' => (new DashboardController())->home(),

    // Quản lý Tour
    'tour' => (new AdminTourController())->index(),
    'tour-create' => (new AdminTourController())->create(),
    'tour-store' => (new AdminTourController())->store(),
    'tour-edit' => (new AdminTourController())->edit(),
    'tour-update' => (new AdminTourController())->update(),
    'tour-delete' => (new AdminTourController())->delete(),

    // THÊM MỚI: Quản lý Lịch trình theo Lịch khởi hành
    'lich-khoi-hanh-lich-trinh' => (new AdminLichKhoiHanhController())->lichTrinh(),
    'lich-khoi-hanh-lich-trinh-create' => (new AdminLichKhoiHanhController())->createLichTrinh(),
    'lich-khoi-hanh-lich-trinh-store' => (new AdminLichKhoiHanhController())->storeLichTrinh(),
    'lich-khoi-hanh-lich-trinh-edit' => (new AdminLichKhoiHanhController())->editLichTrinh(),
    'lich-khoi-hanh-lich-trinh-update' => (new AdminLichKhoiHanhController())->updateLichTrinh(),
    'lich-khoi-hanh-lich-trinh-delete' => (new AdminLichKhoiHanhController())->deleteLichTrinh(),

    // Quản lý Phiên bản Tour
    'tour-phien-ban' => (new AdminTourController())->phienBan(),
    'phien-ban-create' => (new AdminTourController())->createPhienBan(),
    'phien-ban-store' => (new AdminTourController())->storePhienBan(),
    'phien-ban-edit' => (new AdminTourController())->editPhienBan(),
    'phien-ban-update' => (new AdminTourController())->updatePhienBan(),
    'phien-ban-delete' => (new AdminTourController())->deletePhienBan(),
    'phien-ban-activate' => (new AdminTourController())->activatePhienBan(),
    'phien-ban-xem' => (new AdminTourController())->xemPhienBan(),

    // Media Tour
    'tour-media' => (new AdminTourController())->media(),
    'upload-media' => (new AdminTourController())->uploadMedia(),
    'delete-media' => (new AdminTourController())->deleteMedia(),
    'update-media-info' => (new AdminTourController())->updateMediaInfo(),

    'danh-muc' => (new AdminDanhMucTourController())->index(),
    'danh-muc-tour-create' => (new AdminDanhMucTourController())->createDanhMucTour(),
    'danh-muc-tour-store' => (new AdminDanhMucTourController())->storeDanhMucTour(),
    'danh-muc-tour-edit' => (new AdminDanhMucTourController())->editDanhMucTour(),
    'danh-muc-tour-update' => (new AdminDanhMucTourController())->updateDanhMucTour(),
    'danh-muc-tour-delete' => (new AdminDanhMucTourController())->deleteDanhMucTour(),
    'danh-muc-tours' => (new AdminDanhMucTourController())->toursByDanhMuc(),
    'danh-muc-filter' => (new AdminDanhMucTourController())->filterTours(),

    // Quản lý Trạm dừng chân
    'tram-dung-chan' => (new AdminLichKhoiHanhController())->tramDungChan(),
    'tram-dung-chan-them' => (new AdminLichKhoiHanhController())->themTramDungChan(),
    'xoa-tram' => (new AdminLichKhoiHanhController())->xoaTramDungChan(),
    'sua-tram-form' => (new AdminLichKhoiHanhController())->suaTramDungChanForm(),
    'sua-tram' => (new AdminLichKhoiHanhController())->suaTramDungChan(),


    'tour-clone' => (new AdminTourController())->clone(),
    'tour-store-clone' => (new AdminTourController())->storeClone(),
    'tour-clone-success' => (new AdminTourController())->cloneSuccess(),
    'tour-clone-history' => (new AdminTourController())->cloneHistory(),
    'tour-quick-clone' => (new AdminTourController())->quickClone(),

    'chinh-sach' => (new AdminChinhSachController())->index(),
    'chinh-sach-view' => (new AdminChinhSachController())->show(),

    // Quản lý Lịch Khởi Hành
    'lich-khoi-hanh' => (new AdminLichKhoiHanhController())->index(),
    'lich-khoi-hanh-create' => (new AdminLichKhoiHanhController())->create(),
    'lich-khoi-hanh-store' => (new AdminLichKhoiHanhController())->store(),
    'lich-khoi-hanh-edit' => (new AdminLichKhoiHanhController())->edit(),
    'lich-khoi-hanh-update' => (new AdminLichKhoiHanhController())->update(),
    'lich-khoi-hanh-delete' => (new AdminLichKhoiHanhController())->delete(),
    'phan-cong' => (new AdminLichKhoiHanhController())->phanCong(),
    'phan-cong-store' => (new AdminLichKhoiHanhController())->phanCongStore(),
    'huy-phan-cong' => (new AdminLichKhoiHanhController())->huyPhanCong(),
    'checklist-truoc-tour' => (new AdminLichKhoiHanhController())->checklistTruocTour(),
    'checklist-them' => (new AdminLichKhoiHanhController())->themChecklist(),
    'checklist-update' => (new AdminLichKhoiHanhController())->updateChecklist(),
    'checklist-xoa' => (new AdminLichKhoiHanhController())->xoaChecklist(),

    // Quản lý Khách hàng
    'khach-hang' => (new AdminKhachHangController())->index(),
    'khach-hang-chi-tiet' => (new AdminKhachHangController())->show(),
    'khach-hang-tim-kiem' => (new AdminKhachHangController())->search(),
    'khach-hang-thong-ke' => (new AdminKhachHangController())->thongKe(),
    'khach-hang-export' => (new AdminKhachHangController())->export(),

    'dat-tour' => (new AdminDatTourController())->index(),
    'dat-tour-create' => (new AdminDatTourController())->create(),
    'dat-tour-store' => (new AdminDatTourController())->store(),
    'dat-tour-show' => (new AdminDatTourController())->show(),
    'dat-tour-edit' => (new AdminDatTourController())->edit(),
    'dat-tour-update' => (new AdminDatTourController())->update(),
    'dat-tour-update-status' => (new AdminDatTourController())->updateStatus(),
    'dat-tour-delete' => (new AdminDatTourController())->delete(),
    'dat-tour-print' => (new AdminDatTourController())->print(),
    'dat-tour-get-lich-khoi-hanh' => (new AdminDatTourController())->getLichKhoiHanhInfo(),
    'dat-tour-thong-ke' => (new AdminDatTourController())->thongKe(),

    // Quản lý Lịch Làm Việc HDV
    'lich-lam-viec-hdv' => (new AdminLichLamViecHDVController())->index(),
    'lich-lam-viec-hdv-them' => (new AdminLichLamViecHDVController())->create(),
    'lich-lam-viec-hdv-cap-nhat' => (new AdminLichLamViecHDVController())->update(),
    'lich-lam-viec-hdv-xoa' => (new AdminLichLamViecHDVController())->delete(),
    'lich-lam-viec-hdv-loc' => (new AdminLichLamViecHDVController())->filter(),

    'huong-dan-vien' => (new AdminHuongDanVienController())->index(),
    'huong-dan-vien-chi-tiet' => (new AdminHuongDanVienController())->detail($_GET['id'] ?? 0),

    // Quản lý phân phòng khách sạn
    'phan-phong' => (new AdminPhanPhongController())->index(),
    'phan-phong-create' => (new AdminPhanPhongController())->create(),
    'phan-phong-update' => (new AdminPhanPhongController())->update(),
    'phan-phong-delete' => (new AdminPhanPhongController())->delete(),
    'phan-phong-hang-loat' => (new AdminPhanPhongController())->phanPhongHangLoat(),
    'phan-phong-api' => (new AdminPhanPhongController())->apiGetPhanPhong(),
    'phan-phong-api-phong' => (new AdminPhanPhongController())->apiGetPhong(),

    // Thống kê và tìm kiếm
    'dat-tour-thong-ke' => (new AdminDatTourController())->thongKe(),
    'dat-tour-print' => (new AdminDatTourController())->print()
};
