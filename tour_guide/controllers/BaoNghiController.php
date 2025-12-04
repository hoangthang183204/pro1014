<?php

require_once __DIR__ . '/../models/BaoNghiModel.php';

class BaoNghiController {
    private $model;
    
    public function __construct() {
        $this->model = new BaoNghiModel();
    }
    
     private function checkLogin() {
        
        if (!isset($_SESSION['guide_id']) || empty($_SESSION['guide_id'])) {
            error_log("Session guide_id not found. Redirecting to login.");
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
        
        $guideId = $_SESSION['guide_id'];
        error_log("Returning guide_id from session: " . $guideId);
        return $guideId;
    }
    
    public function index() {
        $guideId = $this->checkLogin();
        
        // Lấy danh sách yêu cầu nghỉ
        $requests = $this->model->getAllByGuideId($guideId);
        
        // Debug: Log số lượng request
        error_log("BaoNghiController - Requests count: " . count($requests));
        
        // Lấy thống kê
        $stats = $this->model->getStats($guideId);
        
        // Tính số ngày nghỉ trong tháng
        $daysOffResult = $this->model->countDaysOffThisMonth($guideId);
        $daysOff = $daysOffResult['total_days'] ?? 0;
        
        // Gộp thống kê
        $stats['days_off'] = $daysOff;
        
        // Truyền dữ liệu
        $GLOBALS['baoNghiRequests'] = $requests;
        $GLOBALS['baoNghiStats'] = $stats;
        
        // Debug: Log để kiểm tra
        error_log("BaoNghiController - Stats: " . print_r($stats, true));
        
        // Hiển thị view
        include __DIR__ . '/../views/guide/my_profile.php';
    }
    
    public function create() {
        $guideId = $this->checkLogin();
        include __DIR__ . '/../views/guide/baonghi/create.php';
    }
    
    public function store() {
        $guideId = $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Tạo mã yêu cầu tự động
                $maYeuCau = 'BN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
                
                $data = [
                    'ma_yeu_cau' => $maYeuCau,
                    'huong_dan_vien_id' => $guideId,
                    'loai_nghi' => trim($_POST['loai_nghi'] ?? ''),
                    'ngay_bat_dau' => trim($_POST['ngay_bat_dau'] ?? ''),
                    'ngay_ket_thuc' => !empty($_POST['ngay_ket_thuc']) ? trim($_POST['ngay_ket_thuc']) : null,
                    'ly_do' => trim($_POST['ly_do'] ?? ''),
                    'file_dinh_kem' => null, // Xử lý sau
                    'trang_thai' => 'cho_duyet',
                    'nguoi_tao' => $guideId
                ];
                
                // Validate dữ liệu
                if (empty($data['loai_nghi']) || empty($data['ngay_bat_dau']) || empty($data['ly_do'])) {
                    throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
                }
                
                // Xử lý file đính kèm
                if (isset($_FILES['file_dinh_kem']) && $_FILES['file_dinh_kem']['error'] === UPLOAD_ERR_OK) {
                    $uploadedFile = $this->handleFileUpload($_FILES['file_dinh_kem']);
                    if ($uploadedFile) {
                        $data['file_dinh_kem'] = $uploadedFile;
                    }
                }
                
                // Lưu vào database
                if ($this->model->create($data)) {
                    $_SESSION['success'] = 'Gửi yêu cầu nghỉ thành công!';
                    header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
                    exit();
                } else {
                    throw new Exception('Không thể tạo yêu cầu nghỉ');
                }
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: " . BASE_URL_GUIDE . "?act=bao-nghi-create");
                exit();
            }
        }
        
        header("Location: " . BASE_URL_GUIDE . "?act=bao-nghi");
        exit();
    }
    
    public function detail() {
        $guideId = $this->checkLogin();
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy yêu cầu';
            header("Location: " . BASE_URL_GUIDE . "?act=bao-nghi");
            exit();
        }
        
        $request = $this->model->getById($id, $guideId);
        
        if (!$request) {
            $_SESSION['error'] = 'Yêu cầu không tồn tại hoặc bạn không có quyền xem';
            header("Location: " . BASE_URL_GUIDE . "?act=bao-nghi");
            exit();
        }
        
        $GLOBALS['request'] = $request;
        include __DIR__ . '/../views/guide/detail.php';
    }
    
    public function cancel() {
        $guideId = $this->checkLogin();
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy yêu cầu';
            header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
            exit();
        }
        
        // Kiểm tra quyền sở hữu
        $request = $this->model->getById($id, $guideId);
        if (!$request) {
            $_SESSION['error'] = 'Bạn không có quyền hủy yêu cầu này';
            header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
            exit();
        }
        
        // Chỉ được hủy khi đang chờ duyệt
        if ($request['trang_thai'] !== 'cho_duyet') {
            $_SESSION['error'] = 'Chỉ có thể hủy yêu cầu đang chờ duyệt';
            header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
            exit();
        }
        
        // Cập nhật trạng thái
        $data = [
            'trang_thai' => 'da_huy',
            'nguoi_cap_nhat' => $guideId
        ];
        
        if ($this->model->update($id, $data)) {
            $_SESSION['success'] = 'Hủy yêu cầu nghỉ thành công';
        } else {
            $_SESSION['error'] = 'Không thể hủy yêu cầu';
        }
        
        header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
        exit();
    }
    
    private function handleFileUpload($file) {
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/jpg'
        ];
        
        $maxSize = 5 * 1024 * 1024; // 5MB
        $uploadDir = '/uploads/bao_nghi/';
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $uploadDir;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Định dạng file không được hỗ trợ');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('Kích thước file tối đa 5MB');
        }
        
        // Tạo tên file an toàn
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'bao_nghi_' . time() . '_' . uniqid() . '.' . $fileExt;
        $targetFile = $fullPath . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $uploadDir . $fileName;
        }
        
        return null;
    }
}
?>