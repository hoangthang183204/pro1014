<?php
require_once 'models/NhatKyModel.php';

class NhatKyController
{
    public $model;

    public function __construct()
    {
        $this->model = new NhatKyModel();
    }

    private function checkLogin() {
        if (!isset($_SESSION['guide_id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
    }

    // --- HÀM XỬ LÝ UPLOAD ĐÃ SỬA (Dùng đường dẫn tuyệt đối) ---
    private function processUpload($file) {
        // 1. Xác định đường dẫn gốc của dự án
        // dirname(__DIR__) sẽ trả về thư mục cha của thư mục controllers -> chính là thư mục gốc của project
        $projectRoot = dirname(__DIR__); 
        
        // 2. Đường dẫn thư mục upload (tương đối để lưu DB)
        $relativeDir = 'uploads/nhatky/';
        
        // 3. Đường dẫn vật lý tuyệt đối (để di chuyển file)
        $targetDir = $projectRoot . '/' . $relativeDir;

        // Tạo thư mục nếu chưa có
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // 4. Kiểm tra lỗi file upload từ phía server
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Ghi log lỗi để debug nếu cần
            error_log("Lỗi upload file: " . $file['error']);
            return false;
        }

        // 5. Đặt tên file (Thêm time() để tránh trùng)
        $fileName = time() . '_' . basename($file['name']);
        
        // Đường dẫn file vật lý đầy đủ
        $targetFilePath = $targetDir . $fileName;
        
        // Lấy đuôi file để kiểm tra
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp'); // Thêm webp nếu cần

        if (in_array($fileType, $allowTypes)) {
            // 6. Di chuyển file vào thư mục đích
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                // Trả về đường dẫn tương đối để lưu vào Database (VD: uploads/nhatky/abc.jpg)
                return $relativeDir . $fileName; 
            } else {
                error_log("Không thể di chuyển file tới: " . $targetFilePath);
            }
        }
        return false;
    }

    public function index() {
        $this->checkLogin();
        $user_id = $_SESSION['guide_id'];
        $hdv_id = $this->model->getGuideIdFromUser($user_id);

        if (!$hdv_id) {
             $_SESSION['error'] = "Tài khoản chưa liên kết HDV!";
             require_once 'views/nhatky/list.php';
             return;
        }
        $listNhatKy = $this->model->getAllNhatKy($hdv_id);
        require_once 'views/nhatky/list.php';
    }

    // --- Create: Đã sửa dùng processUpload ---
    public function store() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lich_id = $_POST['lich_khoi_hanh_id'];
            $user_id = $_SESSION['guide_id'];
            $hdv_id = $this->model->getGuideIdFromUser($user_id);
            
            // Insert Nhật ký
            $nhat_ky_id = $this->model->insertNhatKy($lich_id, $hdv_id, $_POST['ngay_nhat_ky'], $_POST['thoi_tiet'], $_POST['hoat_dong'], $_POST['diem_nhan'], $user_id);

            // Insert Sự cố
            if (isset($_POST['co_su_co']) && $_POST['co_su_co'] == '1') {
                $this->model->insertSuCo($lich_id, $hdv_id, $_POST['tieu_de_su_co'], $_POST['ngay_nhat_ky'], $_POST['mo_ta_su_co'], $_POST['cach_xu_ly'], $_POST['muc_do_nghiem_trong'], $user_id);
            }

            // [SỬA] Xử lý upload ảnh
            if (isset($_FILES['hinh_anh']) && !empty($_FILES['hinh_anh']['name'][0])) {
                $files = $_FILES['hinh_anh'];
                $count = count($files['name']); // Đếm số file upload

                for ($i = 0; $i < $count; $i++) {
                    // Gom dữ liệu từng file vào mảng chuẩn
                    $fileItem = [
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i]
                    ];
                    
                    // Gọi hàm upload nội bộ
                    $url_anh = $this->processUpload($fileItem); 
                    
                    if ($url_anh) {
                        $this->model->insertMedia($nhat_ky_id, $url_anh, $user_id);
                    }
                }
            }
            header("Location: ?act=nhat_ky");
        } else {
             // Hiển thị form thêm mới
             $this->create();
        }
    }
    
    // Hàm phụ trợ hiển thị form thêm mới
    public function create() {
        $this->checkLogin();
        $user_id = $_SESSION['guide_id'];
        $hdv_id = $this->model->getGuideIdFromUser($user_id);
        if ($hdv_id) {
            $tours = $this->model->getAssignedTours($hdv_id);
            require_once 'views/nhatky/add.php';
        }
    }

    public function edit() {
        $this->checkLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) return;

        $nhatKy = $this->model->getNhatKyById($id);
        $user_id = $_SESSION['guide_id'];
        $hdv_id_that = $this->model->getGuideIdFromUser($user_id);

        if ($nhatKy && $nhatKy['huong_dan_vien_id'] == $hdv_id_that) {
            $suCo = $this->model->getSuCoByDateAndTour($nhatKy['lich_khoi_hanh_id'], $nhatKy['ngay_nhat_ky']);
            $dsAnh = $this->model->getMediaByNhatKy($id); // Lấy ảnh
            require_once 'views/nhatky/edit.php';
        } else {
            $_SESSION['error'] = "Không có quyền truy cập!";
            header("Location: ?act=nhat_ky");
        }
    }

    // --- Update: Đã sửa dùng processUpload ---
    public function update() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // echo "<pre>"; print_r($_FILES); die();
            $id = $_POST['id'];
            $nhatKyCu = $this->model->getNhatKyById($id);
            
            // Update Text
            $this->model->updateNhatKy($id, $_POST['thoi_tiet'], $_POST['hoat_dong'], $_POST['diem_nhan']);

            // Update Sự cố
            if (isset($_POST['co_su_co']) && $_POST['co_su_co'] == '1') {
                $this->model->updateSuCo($nhatKyCu['lich_khoi_hanh_id'], $nhatKyCu['ngay_nhat_ky'], $_POST['tieu_de_su_co'], $_POST['mo_ta_su_co'], $_POST['cach_xu_ly'], $_POST['muc_do_nghiem_trong']);
            }

            // [SỬA] Upload thêm ảnh
            if (isset($_FILES['hinh_anh']) && !empty($_FILES['hinh_anh']['name'][0])) {
                $files = $_FILES['hinh_anh'];
                $count = count($files['name']);
                $user_id = $_SESSION['guide_id'];

                for ($i = 0; $i < $count; $i++) {
                    // Kiểm tra nếu có file được chọn thực sự
                    if(!empty($files['name'][$i])) {
                        $fileItem = [
                            'name'     => $files['name'][$i],
                            'type'     => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error'    => $files['error'][$i],
                            'size'     => $files['size'][$i]
                        ];
                        
                        // Gọi hàm upload nội bộ
                        $url_anh = $this->processUpload($fileItem);
                        
                        if ($url_anh) {
                            $this->model->insertMedia($id, $url_anh, $user_id);
                        }
                    }
                }
            }
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }
    
    public function delete() {
         $this->checkLogin();
         if (isset($_GET['id'])) {
             // Logic xóa giữ nguyên
             $this->model->deleteNhatKy($_GET['id']);
             header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
         }
    }
}
?>