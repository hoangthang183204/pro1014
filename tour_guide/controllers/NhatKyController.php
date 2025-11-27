<?php
require_once 'models/NhatKyModel.php';

class NhatKyController
{
    public $model;

    public function __construct()
    {
        $this->model = new NhatKyModel();
    }

    // Hàm kiểm tra đăng nhập nội bộ (Private)
    private function checkLogin() {
        if (!isset($_SESSION['guide_id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để thực hiện chức năng này!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
    }

    // Hiển thị danh sách nhật ký
    public function index()
    {
        $this->checkLogin();

        // 1. Lấy ID tài khoản từ session (là số 3)
        $user_id = $_SESSION['guide_id'];

        // 2. Đổi sang ID hướng dẫn viên (sẽ ra số 2)
        $hdv_id = $this->model->getGuideIdFromUser($user_id);

        if (!$hdv_id) {
             // Trường hợp tài khoản này chưa được liên kết với hồ sơ HDV nào
             $_SESSION['error'] = "Tài khoản chưa được liên kết thông tin Hướng dẫn viên!";
             require_once 'views/nhatky/list.php'; // Load trang rỗng
             return;
        }

        // 3. Dùng ID thật để lấy nhật ký
        $listNhatKy = $this->model->getAllNhatKy($hdv_id);
        
        require_once 'views/nhatky/list.php';
    }

    public function create()
    {
        $this->checkLogin();
        $user_id = $_SESSION['guide_id'];
        
        // Đổi sang ID thật
        $hdv_id = $this->model->getGuideIdFromUser($user_id); 
        
        if ($hdv_id) {
            $tours = $this->model->getAssignedTours($hdv_id);
            require_once 'views/nhatky/add.php';
        } else {
             echo "Lỗi: Tài khoản chưa kích hoạt hồ sơ HDV.";
        }
    }

    public function store() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lich_id = $_POST['lich_khoi_hanh_id'];
            
            // XỬ LÝ QUAN TRỌNG: Lấy ID HDV thật
            $user_id_session = $_SESSION['guide_id'];
            $hdv_id = $this->model->getGuideIdFromUser($user_id_session);
            
            if (!$hdv_id) {
                die("Lỗi: Không tìm thấy thông tin hướng dẫn viên.");
            }

            $user_id_tao = $_SESSION['guide_id']; // Người tạo vẫn là User ID (để log ai làm)
            
            $ngay = $_POST['ngay_nhat_ky'];
            $thoi_tiet = $_POST['thoi_tiet'];
            $hoat_dong = $_POST['hoat_dong'];
            $diem_nhan = $_POST['diem_nhan'];

            // Gọi model insert với $hdv_id (là số 2)
            $nhat_ky_id = $this->model->insertNhatKy($lich_id, $hdv_id, $ngay, $thoi_tiet, $hoat_dong, $diem_nhan, $user_id_tao);

            // ... Giữ nguyên phần xử lý sự cố và upload ảnh bên dưới ...
            // (Copy lại đoạn code sự cố và ảnh từ bài trước vào đây)
             if (isset($_POST['co_su_co']) && $_POST['co_su_co'] == '1') {
                $tieu_de_su_co = $_POST['tieu_de_su_co'];
                $mo_ta_su_co = $_POST['mo_ta_su_co'];
                $xu_ly_su_co = $_POST['cach_xu_ly'];
                $muc_do = $_POST['muc_do_nghiem_trong'];

                $this->model->insertSuCo($lich_id, $hdv_id, $tieu_de_su_co, $ngay, $mo_ta_su_co, $xu_ly_su_co, $muc_do, $user_id_tao);
            }

            if (isset($_FILES['hinh_anh']) && !empty($_FILES['hinh_anh']['name'][0])) {
                $files = $_FILES['hinh_anh'];
                $count = count($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    $fileItem = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];
                    $url_anh = uploadFile($fileItem, 'uploads/nhatky/');
                    if ($url_anh) {
                        $this->model->insertMedia($nhat_ky_id, $url_anh, $user_id_tao);
                    }
                }
            }

            header("Location: ?act=nhat_ky");
        }
    }

    // Hiển thị form sửa
    public function edit()
    {
        $this->checkLogin();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $nhatKy = $this->model->getNhatKyById($id);

            // --- ĐOẠN SỬA: Lấy ID thật của HDV ---
            $user_id = $_SESSION['guide_id'];
            $hdv_id_that = $this->model->getGuideIdFromUser($user_id);
            // -------------------------------------

            // So sánh với ID thật ($hdv_id_that) thay vì session trực tiếp
            if ($nhatKy && $nhatKy['huong_dan_vien_id'] == $hdv_id_that) {
                $suCo = $this->model->getSuCoByDateAndTour($nhatKy['lich_khoi_hanh_id'], $nhatKy['ngay_nhat_ky']);
                require_once 'views/nhatky/edit.php';
            } else {
                $_SESSION['error'] = "Bạn không có quyền sửa nhật ký này!";
                header("Location: ?act=nhat_ky");
            }
        }
    }

    // Xử lý cập nhật
    // Xử lý cập nhật
    public function update()
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            // --- ĐOẠN SỬA: Lấy ID thật của HDV ---
            $user_id = $_SESSION['guide_id'];
            $hdv_id_that = $this->model->getGuideIdFromUser($user_id);
            // -------------------------------------

            $nhatKyCu = $this->model->getNhatKyById($id);
            
            // So sánh với ID thật
            if ($nhatKyCu['huong_dan_vien_id'] != $hdv_id_that) {
                 $_SESSION['error'] = "Không có quyền sửa!";
                 header("Location: ?act=nhat_ky");
                 exit();
            }

            $thoi_tiet = $_POST['thoi_tiet'];
            $hoat_dong = $_POST['hoat_dong'];
            $diem_nhan = $_POST['diem_nhan'];

            $this->model->updateNhatKy($id, $thoi_tiet, $hoat_dong, $diem_nhan);
            
            // Thêm thông báo thành công cho chỉn chu
            $_SESSION['success'] = "Cập nhật nhật ký thành công!";
            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }

    // Xóa
    // Xóa
    public function delete()
    {
        $this->checkLogin();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // --- ĐOẠN SỬA: Lấy ID thật của HDV ---
            $user_id = $_SESSION['guide_id'];
            $hdv_id_that = $this->model->getGuideIdFromUser($user_id);
            // -------------------------------------

            $nhatKy = $this->model->getNhatKyById($id);
            
            // So sánh với ID thật
            if ($nhatKy && $nhatKy['huong_dan_vien_id'] == $hdv_id_that) {
                $this->model->deleteNhatKy($id);
                $_SESSION['success'] = "Xóa nhật ký thành công!";
            } else {
                $_SESSION['error'] = "Bạn không có quyền xóa nhật ký này!";
            }
            
            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }
}