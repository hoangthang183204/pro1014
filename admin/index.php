<?php
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once '../commons/env.php'; // Khai báo biến môi trường
require_once '../commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/DashboardController.php';
require_once './controllers/AdminDanhMucTourController.php';
require_once './controllers/AdminTourController.php';
require_once './controllers/AdminLichTrinhKhoiHanhController.php';
require_once './controllers/AdminTaiKhoanController.php';

// Require toàn bộ file Models
require_once './models/AdminDashboard.php';
require_once './models/AdminDanhMuc.php';
require_once './models/AdminTour.php';
require_once './models/AdminLichTrinhKhoiHanh.php';
require_once './models/AdminTaiKhoan.php';

// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/' => (new DashboardController())->home(),

    // Quản lý Tour
    'tour' => (new AdminTourController())->index(),
    'tour-create' => (new AdminTourController())->create(),
    'tour-store' => (new AdminTourController())->store(),
    'tour-edit' => (new AdminTourController())->edit(),
    'tour-update' => (new AdminTourController())->update(),
    'tour-delete' => (new AdminTourController())->delete(),
    'tour-lich-trinh' => (new AdminTourController())->lichTrinh(),
    'tour-phien-ban' => (new AdminTourController())->phienBan(),

    // Media Tour routes - THÊM MỚI
    'tour-media' => (new AdminTourController())->media(),
    'upload-media' => (new AdminTourController())->uploadMedia(),
    'delete-media' => (new AdminTourController())->deleteMedia(),
    'update-media-info' => (new AdminTourController())->updateMediaInfo(),

    // Quản lý Danh Mục Tour
    'danh-muc' => (new AdminDanhMucTourController())->index(),
    'danh-muc-diem-den' => (new AdminDanhMucTourController())->diemDen(),
    'danh-muc-diem-den-create' => (new AdminDanhMucTourController())->createDiemDen(),
    'danh-muc-diem-den-store' => (new AdminDanhMucTourController())->storeDiemDen(),
    'danh-muc-diem-den-edit' => (new AdminDanhMucTourController())->editDiemDen(),
    'danh-muc-diem-den-update' => (new AdminDanhMucTourController())->updateDiemDen(),
    'danh-muc-diem-den-delete' => (new AdminDanhMucTourController())->deleteDiemDen(),

    'danh-muc-loai-tour' => (new AdminDanhMucTourController())->loaiTour(),
    'danh-muc-loai-tour-create' => (new AdminDanhMucTourController())->createLoaiTour(),
    'danh-muc-loai-tour-store' => (new AdminDanhMucTourController())->storeLoaiTour(),
    'danh-muc-loai-tour-edit' => (new AdminDanhMucTourController())->editLoaiTour(),
    'danh-muc-loai-tour-update' => (new AdminDanhMucTourController())->updateLoaiTour(),
    'danh-muc-loai-tour-delete' => (new AdminDanhMucTourController())->deleteLoaiTour(),

    'danh-muc-tag-tour' => (new AdminDanhMucTourController())->tagTour(),
    'danh-muc-tag-tour-create' => (new AdminDanhMucTourController())->createTagTour(),
    'danh-muc-tag-tour-store' => (new AdminDanhMucTourController())->storeTagTour(),
    'danh-muc-tag-tour-edit' => (new AdminDanhMucTourController())->editTagTour(),
    'danh-muc-tag-tour-update' => (new AdminDanhMucTourController())->updateTagTour(),
    'danh-muc-tag-tour-delete' => (new AdminDanhMucTourController())->deleteTagTour(),

    'danh-muc-chinh-sach' => (new AdminDanhMucTourController())->chinhSach(),
    'danh-muc-chinh-sach-create' => (new AdminDanhMucTourController())->createChinhSach(),
    'danh-muc-chinh-sach-store' => (new AdminDanhMucTourController())->storeChinhSach(),
    'danh-muc-chinh-sach-edit' => (new AdminDanhMucTourController())->editChinhSach(),
    'danh-muc-chinh-sach-update' => (new AdminDanhMucTourController())->updateChinhSach(),
    'danh-muc-chinh-sach-delete' => (new AdminDanhMucTourController())->deleteChinhSach(),

    'danh-muc-doi-tac' => (new AdminDanhMucTourController())->doiTac(),
    'danh-muc-doi-tac-create' => (new AdminDanhMucTourController())->createDoiTac(),
    'danh-muc-doi-tac-store' => (new AdminDanhMucTourController())->storeDoiTac(),
    'danh-muc-doi-tac-edit' => (new AdminDanhMucTourController())->editDoiTac(),
    'danh-muc-doi-tac-update' => (new AdminDanhMucTourController())->updateDoiTac(),
    'danh-muc-doi-tac-delete' => (new AdminDanhMucTourController())->deleteDoiTac(),

    'danh-muc-hdv' => (new AdminDanhMucTourController())->huongDanVien(),
    'danh-muc-hdv-create' => (new AdminDanhMucTourController())->createHDV(),
    'danh-muc-hdv-store' => (new AdminDanhMucTourController())->storeHDV(),
    'danh-muc-hdv-edit' => (new AdminDanhMucTourController())->editHDV(),
    'danh-muc-hdv-update' => (new AdminDanhMucTourController())->updateHDV(),
    'danh-muc-hdv-delete' => (new AdminDanhMucTourController())->deleteHDV(),

    // Quản lý Lịch Khởi Hành
    'lich-khoi-hanh' => (new AdminLichKhoiHanhController())->index(),
    'lich-khoi-hanh-create' => (new AdminLichKhoiHanhController())->create(),
    'lich-khoi-hanh-store' => (new AdminLichKhoiHanhController())->store(),
    'lich-khoi-hanh-edit' => (new AdminLichKhoiHanhController())->edit(),
    'lich-khoi-hanh-update' => (new AdminLichKhoiHanhController())->update(),
    'lich-khoi-hanh-delete' => (new AdminLichKhoiHanhController())->delete(),
    'phan-cong-hdv' => (new AdminLichKhoiHanhController())->phanCongHDV(),
    'phan-cong-hdv-store' => (new AdminLichKhoiHanhController())->phanCongHDVStore(),
    'dich-vu-kem-theo' => (new AdminLichKhoiHanhController())->dichVuKemTheo(),
    'checklist-chuan-bi' => (new AdminLichKhoiHanhController())->checklistChuanBi(),

    // Quản lý đăng ký
    // Đăng ký Admin

    // Quản lý đăng ký
// Đăng ký Admin
'register' => (new AdminTaiKhoanController())->register(),
'register-process' => (new AdminTaiKhoanController())->registerprocess(),

};
