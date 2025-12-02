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
require_once './controllers/AdminThanhToanController.php';

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
require_once './models/AdminThanhToan.php';

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

    // PROTECTED ROUTES - Cần đăng nhập và không phải HDV
    '/' => (new DashboardController())->home(),

    // Quản lý Tour
    'tour' => (new AdminTourController())->index(),
    'tour-create' => (new AdminTourController())->create(),
    'tour-store' => (new AdminTourController())->store(),
    'tour-edit' => (new AdminTourController())->edit(),
    'tour-update' => (new AdminTourController())->update(),
    'tour-delete' => (new AdminTourController())->delete(),

    // Nhà cung cấp
    'tour-nha-cung-cap' => (new AdminTourController())->nhaCungCap(),
    'tour-add-nha-cung-cap' => (new AdminTourController())->addNhaCungCapToTour(),
    'tour-update-nha-cung-cap' => (new AdminTourController())->updateNhaCungCapTour(),
    'tour-remove-nha-cung-cap' => (new AdminTourController())->removeNhaCungCapFromTour(),

    // Thêm các route mới
    'tour-nha-cung-cap-list' => (new AdminTourController())->listNhaCungCap(),
    'tour-nha-cung-cap-create' => (new AdminTourController())->createNhaCungCap(),
    'tour-nha-cung-cap-store' => (new AdminTourController())->storeNhaCungCap(),
    'tour-nha-cung-cap-edit' => (new AdminTourController())->editNhaCungCap(),
    'tour-nha-cung-cap-update' => (new AdminTourController())->updateNhaCungCap(),
    'tour-nha-cung-cap-delete' => (new AdminTourController())->deleteNhaCungCap(),


    // Quản lý Lịch trình Tour
    'tour-lich-trinh' => (new AdminTourController())->lichTrinh(),
    'lich-trinh-create' => (new AdminTourController())->createLichTrinh(),
    'lich-trinh-store' => (new AdminTourController())->storeLichTrinh(),
    'lich-trinh-edit' => (new AdminTourController())->editLichTrinh(),
    'lich-trinh-update' => (new AdminTourController())->updateLichTrinh(),
    'lich-trinh-delete' => (new AdminTourController())->deleteLichTrinh(),

    // Quản lý Phiên bản Tour
    'tour-phien-ban' => (new AdminTourController())->phienBan(),
    'phien-ban-create' => (new AdminTourController())->createPhienBan(),
    'phien-ban-store' => (new AdminTourController())->storePhienBan(),
    'phien-ban-edit' => (new AdminTourController())->editPhienBan(),
    'phien-ban-update' => (new AdminTourController())->updatePhienBan(),
    'phien-ban-delete' => (new AdminTourController())->deletePhienBan(),
    'phien-ban-ap-dung' => (new AdminTourController())->apDungPhienBan(),
    'phien-ban-xem' => (new AdminTourController())->xemPhienBan(),

    // Media Tour
    'tour-media' => (new AdminTourController())->media(),
    'upload-media' => (new AdminTourController())->uploadMedia(),
    'delete-media' => (new AdminTourController())->deleteMedia(),
    'update-media-info' => (new AdminTourController())->updateMediaInfo(),

    // Quản lý Danh Mục Tour - CHỈ BAO GỒM 3 LOẠI: trong nước, quốc tế, theo yêu cầu
    'danh-muc' => (new AdminDanhMucTourController())->index(),
    'danh-muc-tour-create' => (new AdminDanhMucTourController())->createDanhMucTour(),
    'danh-muc-tour-store' => (new AdminDanhMucTourController())->storeDanhMucTour(),
    'danh-muc-tour-edit' => (new AdminDanhMucTourController())->editDanhMucTour(),
    'danh-muc-tour-update' => (new AdminDanhMucTourController())->updateDanhMucTour(),
    'danh-muc-tour-delete' => (new AdminDanhMucTourController())->deleteDanhMucTour(),
    'danh-muc-tours' => (new AdminDanhMucTourController())->toursByDanhMuc(),
    'danh-muc-filter' => (new AdminDanhMucTourController())->filterTours(),

    // 'danh-muc-tour' => (new AdminDanhMucTourController())->danhMucTour(),
    // 'danh-muc-tour-create' => (new AdminDanhMucTourController())->createDanhMucTour(),
    // 'danh-muc-tour-store' => (new AdminDanhMucTourController())->storeDanhMucTour(),
    // 'danh-muc-tour-edit' => (new AdminDanhMucTourController())->editDanhMucTour(),
    // 'danh-muc-tour-update' => (new AdminDanhMucTourController())->updateDanhMucTour(),
    // 'danh-muc-tour-delete' => (new AdminDanhMucTourController())->deleteDanhMucTour(),

    // 'danh-muc-tag-tour' => (new AdminDanhMucTourController())->tagTour(),
    // 'danh-muc-tag-tour-create' => (new AdminDanhMucTourController())->createTagTour(),
    // 'danh-muc-tag-tour-store' => (new AdminDanhMucTourController())->storeTagTour(),
    // 'danh-muc-tag-tour-edit' => (new AdminDanhMucTourController())->editTagTour(),
    // 'danh-muc-tag-tour-update' => (new AdminDanhMucTourController())->updateTagTour(),
    // 'danh-muc-tag-tour-delete' => (new AdminDanhMucTourController())->deleteTagTour(),

    // 'danh-muc-chinh-sach' => (new AdminDanhMucTourController())->chinhSach(),
    // 'danh-muc-chinh-sach-create' => (new AdminDanhMucTourController())->createChinhSach(),
    // 'danh-muc-chinh-sach-store' => (new AdminDanhMucTourController())->storeChinhSach(),
    // 'danh-muc-chinh-sach-edit' => (new AdminDanhMucTourController())->editChinhSach(),
    // 'danh-muc-chinh-sach-update' => (new AdminDanhMucTourController())->updateChinhSach(),
    // 'danh-muc-chinh-sach-delete' => (new AdminDanhMucTourController())->deleteChinhSach(),

    // 'danh-muc-doi-tac' => (new AdminDanhMucTourController())->doiTac(),
    // 'danh-muc-doi-tac-create' => (new AdminDanhMucTourController())->createDoiTac(),
    // 'danh-muc-doi-tac-store' => (new AdminDanhMucTourController())->storeDoiTac(),
    // 'danh-muc-doi-tac-edit' => (new AdminDanhMucTourController())->editDoiTac(),
    // 'danh-muc-doi-tac-update' => (new AdminDanhMucTourController())->updateDoiTac(),
    // 'danh-muc-doi-tac-delete' => (new AdminDanhMucTourController())->deleteDoiTac(),

    // // HƯỚNG DẪN VIÊN
    // 'huong-dan-vien' => (new AdminDanhMucTourController())->huongDanVien(),
    // 'chi-tiet-huong-dan-vien' => (new AdminDanhMucTourController())->chiTietHuongDanVien(),
    // 'sua-huong-dan-vien' => (new AdminDanhMucTourController())->suaHuongDanVien(),
    // 'update-huong-dan-vien' => (new AdminDanhMucTourController())->updateHuongDanVien(),
    // 'xoa-huong-dan-vien' => (new AdminDanhMucTourController())->xoaHuongDanVien(),

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
    // 'dat-tour-search-khach-hang' => (new AdminDatTourController())->searchKhachHang(),
    // 'dat-tour-get-khach-hang' => (new AdminDatTourController())->getKhachHangById(),
    'dat-tour-thong-ke' => (new AdminDatTourController())->thongKe(),

    // Quản lý Lịch Làm Việc HDV
    'lich-lam-viec-hdv' => (new AdminLichLamViecHDVController())->index(),
    'lich-lam-viec-hdv-them' => (new AdminLichLamViecHDVController())->create(),
    'lich-lam-viec-hdv-cap-nhat' => (new AdminLichLamViecHDVController())->update(),
    'lich-lam-viec-hdv-xoa' => (new AdminLichLamViecHDVController())->delete(),
    'lich-lam-viec-hdv-loc' => (new AdminLichLamViecHDVController())->filter(),

    // Quản lý phân phòng khách sạn
    'phan-phong' => (new AdminPhanPhongController())->index(),
    'phan-phong-create' => (new AdminPhanPhongController())->create(),
    'phan-phong-update' => (new AdminPhanPhongController())->update(),
    'phan-phong-delete' => (new AdminPhanPhongController())->delete(),
    'phan-phong-hang-loat' => (new AdminPhanPhongController())->phanPhongHangLoat(),
    'phan-phong-api' => (new AdminPhanPhongController())->apiGetPhanPhong(),
    'phan-phong-api-phong' => (new AdminPhanPhongController())->apiGetPhong(),

    // Quản lý Thanh toán
    'thanh-toan-nhanh-modal' => (new AdminThanhToanController())->modalThanhToanNhanh(),
    'thanh-toan-nhanh-process' => (new AdminThanhToanController())->processThanhToanNhanh(),

    // Thống kê và tìm kiếm
    'dat-tour-thong-ke' => (new AdminDatTourController())->thongKe(),
    'dat-tour-print' => (new AdminDatTourController())->print()
    // 'dat-tour-tim-kiem' => (new AdminDatTourController())->timKiemBooking(),

};
