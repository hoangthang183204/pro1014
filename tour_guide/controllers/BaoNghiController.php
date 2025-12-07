<?php
class BaoNghiController {
    private $model;
    
    public function __construct() {
        $this->model = new BaoNghiModel();
    }
    
    private function checkLogin() {
        if (!isset($_SESSION['guide_id']) || empty($_SESSION['guide_id'])) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
        return $_SESSION['guide_id'];
    }
    
    // Phương thức chính để xem profile
    public function myProfile() {
        $guideId = $this->checkLogin();
        
        // Lấy thông tin hướng dẫn viên từ database
        $db = new Database();
        $sql = "SELECT * FROM huong_dan_vien WHERE id = :id";
        $db->query($sql);
        $db->bind(':id', $guideId);
        $profile = $db->single();
        
        if (!$profile) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
        
        // Lấy số tour đã dẫn và đánh giá trung bình
        $statsSql = "SELECT 
                        COUNT(DISTINCT ct.tour_id) as so_tour_da_dan,
                        AVG(dg.diem_danh_gia) as danh_gia_trung_binh
                     FROM chi_tiet_tour ct
                     LEFT JOIN danh_gia dg ON ct.tour_id = dg.tour_id AND dg.huong_dan_vien_id = :id
                     WHERE ct.huong_dan_vien_id = :id";
        
        $db->query($statsSql);
        $db->bind(':id', $guideId);
        $stats = $db->single();
        
        // Truyền dữ liệu cho view
        $GLOBALS['profile'] = $profile;
        $GLOBALS['stats'] = $stats;
        
        // Hiển thị view
        include __DIR__ . '/../views/guide/my_profile.php';
    }
    
    // Các phương thức khác cho chức năng báo nghỉ (giữ nguyên nếu cần)
    public function index() {
        $guideId = $this->checkLogin();
        
        // Lấy danh sách yêu cầu nghỉ
        $requests = $this->model->getAllByGuideId($guideId);
        
        // Lấy thống kê
        $stats = $this->model->getStats($guideId);
        
        // Truyền dữ liệu
        $GLOBALS['baoNghiRequests'] = $requests;
        $GLOBALS['baoNghiStats'] = $stats;
        
        // Hiển thị view cho tab báo nghỉ
        include __DIR__ . '/../views/guide/index.php';
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
                    'file_dinh_kem' => null,
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
                    header("Location: " . BASE_URL_GUIDE . "?act=bao-nghi");
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