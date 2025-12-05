<?php
require_once __DIR__ . '/../models/PersonalGuideModel.php';
require_once __DIR__ . '/../models/BaoNghiModel.php'; 

class PersonalGuideController {
    private $model;
    private $uploadDir = '/uploads/avatars/guides/';
    private $baoNghiModel; 

    public function __construct() {
        $this->model = new PersonalGuideModel();
        $this->baoNghiModel = new BaoNghiModel(); 
    }
    
    private function checkLogin() {
        if (!isset($_SESSION['guide_id'])) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
        return $_SESSION['guide_id'];
    }
    
    private function ensureGuideProfileExists($guideId) {
        $profile = $this->model->getMyProfile($guideId);
        
        if (!$profile) {
            // Tạo profile trống nếu chưa có
            $data = [
                'ho_ten' => $_SESSION['guide_name'] ?? '',
                'email' => $_SESSION['guide_email'] ?? '',
                'so_dien_thoai' => '',
                'ngay_sinh' => null,
                'dia_chi' => '',
                'loai_huong_dan_vien' => 'nội địa',
                'ngon_ngu' => '[]',
                'kinh_nghiem' => '',
                'chuyen_mon' => '',
                'tinh_trang_suc_khoe' => '',
                'so_giay_phep_hanh_nghe' => '',
                'ngay_cap_giay_phep' => null,
                'noi_cap_giay_phep' => '',
                'trang_thai' => 'đang làm việc'
            ];
            
            return $this->model->createProfile($guideId, $data);
        }
        
        return true;
    }
    
    private function ensureUploadDirectoryExists() {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $this->uploadDir;
        
        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                $error = error_get_last();
                throw new Exception("Không thể tạo thư mục: " . ($error['message'] ?? 'Unknown error'));
            }
        }
        
        if (!is_writable($fullPath)) {
            throw new Exception("Thư mục không có quyền ghi: " . $fullPath);
        }
        
        return $fullPath;
    }

    public function showProfile() {
        $guideId = $this->checkLogin();
        
        // Đảm bảo profile tồn tại
        $this->ensureGuideProfileExists($guideId);
        
        // Lấy thông tin profile
        $profile = $this->model->getMyProfile($guideId);
        
        // Lấy số tour đang hoạt động
        $activeTours = $this->model->getActiveToursCount($guideId);
        
        // Lấy thông tin thống kê từ profile
        $stats = [
            'so_tour_da_dan' => $profile['so_tour_da_dan'] ?? 0,
            'danh_gia_trung_binh' => $profile['danh_gia_trung_binh'] ?? 0
        ];
        
        // Lấy dữ liệu báo nghỉ
        $baoNghiRequests = $this->baoNghiModel->getAllByGuideId($guideId);
        $baoNghiStats = $this->baoNghiModel->getStats($guideId);
        
        // Tính số ngày nghỉ trong tháng
        $daysOffResult = $this->baoNghiModel->countDaysOffThisMonth($guideId);
        $daysOff = $daysOffResult['total_days'] ?? 0;
        
        // Gộp thống kê
        $baoNghiStats = array_merge($baoNghiStats, [
            'days_off' => $daysOff
        ]);
        
        // Truyền dữ liệu qua $GLOBALS
        $GLOBALS['profile'] = $profile;
        $GLOBALS['activeTours'] = $activeTours;
        $GLOBALS['stats'] = $stats;
        $GLOBALS['baoNghiRequests'] = $baoNghiRequests;
        $GLOBALS['baoNghiStats'] = $baoNghiStats;
        
        // Hiển thị view
        include __DIR__ . '/../views/guide/my_profile.php';
    }

    public function showProfileSettings() {
        $guideId = $this->checkLogin();
        
        // Đảm bảo profile tồn tại
        $this->ensureGuideProfileExists($guideId);
        
        $profile = $this->model->getMyProfile($guideId);
        $GLOBALS['profile'] = $profile;
        
        include __DIR__ . '/../views/guide/profile_settings.php';
    }
    
    public function updateProfile() {
        $guideId = $this->checkLogin();
        
        // Đảm bảo profile tồn tại trước khi cập nhật
        $this->ensureGuideProfileExists($guideId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'ho_ten' => trim($_POST['ho_ten'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                    'ngay_sinh' => trim($_POST['ngay_sinh'] ?? ''),
                    'dia_chi' => trim($_POST['dia_chi'] ?? ''),
                    'loai_huong_dan_vien' => trim($_POST['loai_huong_dan_vien'] ?? ''),
                    'ngon_ngu' => !empty($_POST['ngon_ngu']) ? json_encode($_POST['ngon_ngu']) : '[]',
                    'kinh_nghiem' => trim($_POST['kinh_nghiem'] ?? ''),
                    'chuyen_mon' => trim($_POST['chuyen_mon'] ?? ''),
                    'tinh_trang_suc_khoe' => trim($_POST['tinh_trang_suc_khoe'] ?? ''),
                    'so_giay_phep_hanh_nghe' => trim($_POST['so_giay_phep_hanh_nghe'] ?? ''),
                    'ngay_cap_giay_phep' => trim($_POST['ngay_cap_giay_phep'] ?? ''),
                    'noi_cap_giay_phep' => trim($_POST['noi_cap_giay_phep'] ?? ''),
                    // Thêm thống kê
                    'so_tour_da_dan' => intval($_POST['so_tour_da_dan'] ?? 0),
                    'danh_gia_trung_binh' => floatval($_POST['danh_gia_trung_binh'] ?? 0)
                ];
                
                // Validate dữ liệu thống kê
                if ($data['so_tour_da_dan'] < 0) {
                    throw new Exception('Số tour đã dẫn không thể âm');
                }
                
                if ($data['so_tour_da_dan'] > 999) {
                    throw new Exception('Số tour đã dẫn tối đa là 999');
                }
                
                if ($data['danh_gia_trung_binh'] < 0 || $data['danh_gia_trung_binh'] > 5) {
                    throw new Exception('Đánh giá phải nằm trong khoảng 0.0 đến 5.0');
                }
                
                // Update thông tin cơ bản
                if (!$this->model->updateProfile($guideId, $data)) {
                    throw new Exception('Cập nhật thông tin thất bại.');
                }
                
                // Xử lý upload avatar
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $this->handleAvatarUpload($guideId, $_FILES['avatar']);
                }

                // Cập nhật session name nếu có thay đổi
                if (!empty($data['ho_ten'])) {
                    $_SESSION['guide_name'] = $data['ho_ten'];
                }
                
                $_SESSION['success'] = "Cập nhật hồ sơ thành công!";

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        header("Location: " . BASE_URL_GUIDE . "?act=my-profile");
        exit();
    }
    
    private function handleAvatarUpload($guideId, $file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Lỗi upload: " . $this->getUploadError($file['error']));
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Định dạng file không hợp lệ.");
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception("Kích thước file quá lớn. Tối đa 2MB.");
        }
        
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'guide_' . $guideId . '_' . time() . '.' . $fileExt;
        
        $fullPath = $this->ensureUploadDirectoryExists();
        $targetFile = $fullPath . $fileName;
        $avatarPath = $this->uploadDir . $fileName;
        
        // Di chuyển file
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            $error = error_get_last();
            throw new Exception("Không thể lưu file: " . ($error['message'] ?? 'Unknown error'));
        }
        
        // Xác nhận file đã được tạo
        if (!file_exists($targetFile)) {
            throw new Exception("File không được tạo thành công.");
        }
        
        // Xóa ảnh cũ
        $oldProfile = $this->model->getMyProfile($guideId);
        if ($oldProfile && !empty($oldProfile['hinh_anh'])) {
            $oldFile = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $oldProfile['hinh_anh'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // Cập nhật database
        if (!$this->model->updateAvatar($guideId, $avatarPath)) {
            unlink($targetFile);
            throw new Exception("Lỗi cập nhật database.");
        }
    }
    
    private function getUploadError($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File vượt quá kích thước cho phép',
            UPLOAD_ERR_FORM_SIZE => 'File vượt quá kích thước form',
            UPLOAD_ERR_PARTIAL => 'File chỉ upload được một phần',
            UPLOAD_ERR_NO_FILE => 'Không có file được upload',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file',
            UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi extension'
        ];
        return $errors[$errorCode] ?? 'Lỗi upload không xác định';
    }
}
?>