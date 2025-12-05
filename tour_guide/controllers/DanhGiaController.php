<?php
require_once './models/DanhGiaModel.php';

class DanhGiaController {
    public $model;

    public function __construct() {
        $this->model = new DanhGiaModel();
    }

    // Danh sách tour đã hoàn thành (chưa đánh giá)
    public function index() {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $guide_id = $_SESSION['guide_id'];
        $tours = $this->model->getToursCompletedByGuide($guide_id);
        
        require_once './views/danhgia/list_tours.php';
    }

    // Form đánh giá
    public function create() {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $lich_id = $_GET['id'] ?? null;
        if (!$lich_id) {
            $_SESSION['error'] = "Không tìm thấy tour!";
            header("Location: " . BASE_URL_GUIDE . "?act=danh_gia");
            exit();
        }

        $guide_id = $_SESSION['guide_id'];
        
        // Kiểm tra đã đánh giá chưa
        $daDanhGia = $this->model->checkDaDanhGia($lich_id, $guide_id);
        if ($daDanhGia && $daDanhGia['trang_thai'] != 'draft') {
            $_SESSION['info'] = "Bạn đã đánh giá tour này rồi!";
            header("Location: " . BASE_URL_GUIDE . "?act=danh_gia_detail&id=" . $daDanhGia['id']);
            exit();
        }

        // Lấy thông tin tour
        $tourInfo = $this->model->getTourInfoForReview($lich_id);
        if (!$tourInfo) {
            $_SESSION['error'] = "Không tìm thấy thông tin tour!";
            header("Location: " . BASE_URL_GUIDE . "?act=danh_gia");
            exit();
        }

        require_once './views/danhgia/create.php';
    }

    // Lưu đánh giá
    public function store() {
        if (!checkGuideLogin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $guide_id = $_SESSION['guide_id'];
        $lich_id = $_POST['lich_khoi_hanh_id'] ?? null;

        if (!$lich_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin tour']);
            return;
        }

        // Chuẩn bị data
        $data = [
            ':lich_id' => $lich_id,
            ':guide_id' => $guide_id,
            ':diem_tong_quan' => $_POST['diem_tong_quan'] ?? 5,
            ':noi_dung_tong_quan' => $_POST['noi_dung_tong_quan'] ?? '',
            
            ':diem_khach_san' => $_POST['diem_khach_san'] ?? 5,
            ':nhan_xet_khach_san' => $_POST['nhan_xet_khach_san'] ?? '',
            
            ':diem_nha_hang' => $_POST['diem_nha_hang'] ?? 5,
            ':nhan_xet_nha_hang' => $_POST['nhan_xet_nha_hang'] ?? '',
            
            ':diem_xe_van_chuyen' => $_POST['diem_xe_van_chuyen'] ?? 5,
            ':nhan_xet_xe_van_chuyen' => $_POST['nhan_xet_xe_van_chuyen'] ?? '',
            
            ':diem_dich_vu_bo_sung' => $_POST['diem_dich_vu_bo_sung'] ?? 5,
            ':nhan_xet_dich_vu_bo_sung' => $_POST['nhan_xet_dich_vu_bo_sung'] ?? '',
            
            ':nha_cung_cap_khach_san' => $_POST['nha_cung_cap_khach_san'] ?? '',
            ':nha_cung_cap_nha_hang' => $_POST['nha_cung_cap_nha_hang'] ?? '',
            ':nha_cung_cap_xe' => $_POST['nha_cung_cap_xe'] ?? '',
            
            ':de_xuat_cai_thien' => $_POST['de_xuat_cai_thien'] ?? '',
            ':de_xuat_tiep_tuc_su_dung' => $_POST['de_xuat_tiep_tuc_su_dung'] ?? 'co'
        ];

        // Kiểm tra đã đánh giá chưa (update hoặc insert)
        $existing = $this->model->checkDaDanhGia($lich_id, $guide_id);
        
        if ($existing && $existing['trang_thai'] == 'draft') {
            // Update draft
            $result = $this->model->updateDanhGia($existing['id'], $data);
        } else {
            // Insert mới
            $result = $this->model->saveDanhGia($data);
        }

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã gửi đánh giá thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu đánh giá!']);
        }
    }

    // Xem danh sách đánh giá đã gửi
    public function list() {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $guide_id = $_SESSION['guide_id'];
        $danhGiaList = $this->model->getDanhGiaByGuide($guide_id);
        
        require_once './views/danhgia/list_reviews.php';
    }

    // Xem chi tiết đánh giá
    public function detail() {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        $guide_id = $_SESSION['guide_id'];

        if (!$id) {
            $_SESSION['error'] = "Không tìm thấy đánh giá!";
            header("Location: " . BASE_URL_GUIDE . "?act=danh_gia_list");
            exit();
        }

        $danhGia = $this->model->getDanhGiaDetail($id, $guide_id);
        if (!$danhGia) {
            $_SESSION['error'] = "Không tìm thấy đánh giá!";
            header("Location: " . BASE_URL_GUIDE . "?act=danh_gia_list");
            exit();
        }

        require_once './views/danhgia/detail.php';
    }

    
}
?>