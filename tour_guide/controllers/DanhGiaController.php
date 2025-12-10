<?php
require_once './models/DanhGiaModel.php';

class DanhGiaController {
    private $model;
    
    public function __construct() {
        $this->model = new DanhGiaModel();
    }

    // Hiển thị danh sách tour cần đánh giá
    public function index() {
        // SỬA: Kiểm tra session đúng cách
        if (!isset($_SESSION['guide_id'])) {
            header('Location: ?act=login');
            exit();
        }

        $huong_dan_vien_id = $_SESSION['guide_id']; // SỬA Ở ĐÂY
        $tours = $this->model->getToursCanDanhGia($huong_dan_vien_id);
        
        require_once './views/danhgia/list_tours.php';
    }

    // Hiển thị form tạo đánh giá
    public function create() {
        if (!isset($_SESSION['guide_id'])) { // SỬA
            header('Location: ?act=login');
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $huong_dan_vien_id = $_SESSION['guide_id']; // SỬA
        
        // Kiểm tra tour có tồn tại và HDV có được phân công không
        $tourInfo = $this->model->getTourInfoForReview($id, $huong_dan_vien_id);
        
        if (!$tourInfo) {
            $_SESSION['error'] = "Tour không tồn tại hoặc bạn không có quyền đánh giá tour này";
            header('Location: ?act=danh_gia');
            exit();
        }
        
        // Kiểm tra đã đánh giá chưa
        if ($this->model->isAlreadyReviewed($id, $huong_dan_vien_id)) {
            $_SESSION['error'] = "Bạn đã đánh giá tour này rồi";
            header('Location: ?act=danh_gia');
            exit();
        }
        
        require_once './views/danhgia/create.php';
    }

    // Xử lý lưu đánh giá
    public function store() {
    // Bật lỗi chi tiết
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    error_log("=== BẮT ĐẦU XỬ LÝ STORE ===");
    
    if (!isset($_SESSION['guide_id'])) {
        error_log("Lỗi: Chưa đăng nhập");
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log("Lỗi: Không phải POST request");
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
        exit();
    }

    $huong_dan_vien_id = $_SESSION['guide_id'];
    $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;

    // Debug POST data
    error_log("POST data: " . print_r($_POST, true));
    error_log("HDV ID: $huong_dan_vien_id, Lịch khởi hành ID: $lich_khoi_hanh_id");

    // Kiểm tra đã đánh giá chưa
    if ($this->model->isAlreadyReviewed($lich_khoi_hanh_id, $huong_dan_vien_id)) {
        error_log("Lỗi: Đã đánh giá tour này rồi");
        echo json_encode(['success' => false, 'message' => 'Bạn đã đánh giá tour này rồi']);
        exit();
    }

    // Lấy phieu_dat_tour_id đầu tiên
    $phieu_dat_tour_id = $this->model->getFirstPhieuDatTourId($lich_khoi_hanh_id);
    error_log("Phiếu đặt tour ID: " . $phieu_dat_tour_id);
    
    if (!$phieu_dat_tour_id) {
        error_log("Lỗi: Không tìm thấy phiếu đặt tour");
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin đặt tour']);
        exit();
    }

    // Chuẩn bị dữ liệu JSON
    $danhGiaDichVu = json_encode([
        'diem_tong_quan' => $_POST['diem_tong_quan'] ?? 5,
        'noi_dung_tong_quan' => $_POST['noi_dung_tong_quan'] ?? '',
        'diem_khach_san' => $_POST['diem_khach_san'] ?? 5,
        'nha_cung_cap_khach_san' => $_POST['nha_cung_cap_khach_san'] ?? '',
        'nhan_xet_khach_san' => $_POST['nhan_xet_khach_san'] ?? '',
        'diem_nha_hang' => $_POST['diem_nha_hang'] ?? 5,
        'nha_cung_cap_nha_hang' => $_POST['nha_cung_cap_nha_hang'] ?? '',
        'nhan_xet_nha_hang' => $_POST['nhan_xet_nha_hang'] ?? '',
        'diem_xe_van_chuyen' => $_POST['diem_xe_van_chuyen'] ?? 5,
        'nha_cung_cap_xe' => $_POST['nha_cung_cap_xe'] ?? '',
        'nhan_xet_xe_van_chuyen' => $_POST['nhan_xet_xe_van_chuyen'] ?? '',
        'diem_dich_vu_bo_sung' => $_POST['diem_dich_vu_bo_sung'] ?? 5,
        'nhan_xet_dich_vu_bo_sung' => $_POST['nhan_xet_dich_vu_bo_sung'] ?? '',
        'de_xuat_cai_thien' => $_POST['de_xuat_cai_thien'] ?? '',
        'de_xuat_tiep_tuc_su_dung' => $_POST['de_xuat_tiep_tuc_su_dung'] ?? 'co'
    ]);

    error_log("JSON data: " . $danhGiaDichVu);

    // Tính điểm trung bình
    $diemTrungBinh = (
        (int)($_POST['diem_tong_quan'] ?? 5) +
        (int)($_POST['diem_khach_san'] ?? 5) +
        (int)($_POST['diem_nha_hang'] ?? 5) +
        (int)($_POST['diem_xe_van_chuyen'] ?? 5) +
        (int)($_POST['diem_dich_vu_bo_sung'] ?? 5)
    ) / 5;

    error_log("Điểm trung bình: " . $diemTrungBinh);

    // Chuẩn bị dữ liệu
    $data = [
        ':phieu_dat_tour_id' => $phieu_dat_tour_id,
        ':diem_so' => round($diemTrungBinh, 1),
        ':noi_dung_danh_gia' => $_POST['noi_dung_tong_quan'] ?? '',
        ':danh_gia_dich_vu' => $danhGiaDichVu,
        ':huong_dan_vien_id' => $huong_dan_vien_id,
        ':lich_khoi_hanh_id' => $lich_khoi_hanh_id
    ];

    error_log("Data to insert: " . print_r($data, true));

    // Lưu đánh giá
    try {
        $result = $this->model->saveDanhGia($data);
        error_log("Kết quả lưu: " . ($result ? 'THÀNH CÔNG' : 'THẤT BẠI'));
        
        if ($result) {
            error_log("Đánh giá đã được gửi thành công!");
            echo json_encode(['success' => true, 'message' => 'Đánh giá đã được gửi thành công!']);
        } else {
            error_log("Lỗi khi gửi đánh giá");
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi đánh giá']);
        }
    } catch (Exception $e) {
        error_log("EXCEPTION: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
    }
    
    error_log("=== KẾT THÚC XỬ LÝ STORE ===");
}

    // Hiển thị danh sách đánh giá đã gửi
    public function list() {
        if (!isset($_SESSION['guide_id'])) { // SỬA
            header('Location: ?act=login');
            exit();
        }

        $huong_dan_vien_id = $_SESSION['guide_id']; // SỬA
        $danhGiaList = $this->model->getDanhGiaList($huong_dan_vien_id);
        
        require_once './views/danhgia/list_reviews.php';
    }

    // Hiển thị chi tiết đánh giá
    public function detail() {
        if (!isset($_SESSION['guide_id'])) { // SỬA
            header('Location: ?act=login');
            exit();
        }

        $id = $_GET['id'] ?? 0;
        $huong_dan_vien_id = $_SESSION['guide_id']; // SỬA
        
        $danhGia = $this->model->getDanhGiaDetail($id, $huong_dan_vien_id);
        
        if (!$danhGia) {
            $_SESSION['error'] = "Không tìm thấy đánh giá";
            header('Location: ?act=danh_gia_list');
            exit();
        }
        
        require_once './views/danhgia/list_detail.php';
    }
}   