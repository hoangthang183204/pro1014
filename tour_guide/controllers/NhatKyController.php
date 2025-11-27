<?php
require_once 'models/NhatKyModel.php';

class NhatKyController {
    public $model;

    public function __construct() {
        $this->model = new NhatKyModel();
    }

    // Hiển thị danh sách nhật ký
    public function index() {
        // Giả sử ID HDV lấy từ Session khi đăng nhập
        $hdv_id = $_SESSION['guide_id'] ?? 2; // Mặc định 2 nếu chưa login (để test)
        $listNhatKy = $this->model->getAllNhatKy($hdv_id);
        require_once 'views/nhatky/list.php';
    }

    // Hiển thị form thêm mới
    public function create() {
        $hdv_id = $_SESSION['guide_id'] ?? 2;
        $tours = $this->model->getAssignedTours($hdv_id);
        require_once 'views/nhatky/add.php';
    }

    // Xử lý lưu dữ liệu thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lich_id = $_POST['lich_khoi_hanh_id'];
            $hdv_id = $_SESSION['guide_id'] ?? 2;
            $user_id = $_SESSION['user_id'] ?? 2; // ID người dùng hệ thống
            $ngay = $_POST['ngay_nhat_ky'];
            $thoi_tiet = $_POST['thoi_tiet'];
            $hoat_dong = $_POST['hoat_dong'];
            $diem_nhan = $_POST['diem_nhan'];

            // 2. Thêm nhật ký
            $this->model->insertNhatKy($lich_id, $hdv_id, $ngay, $thoi_tiet, $hoat_dong, $diem_nhan, $user_id);

            // 2. Kiểm tra nếu có báo cáo sự cố
            if (isset($_POST['co_su_co']) && $_POST['co_su_co'] == '2') {
                $tieu_de_su_co = $_POST['tieu_de_su_co'];
                $mo_ta_su_co = $_POST['mo_ta_su_co'];
                $xu_ly_su_co = $_POST['cach_xu_ly'];
                $muc_do = $_POST['muc_do_nghiem_trong'];
                
                $this->model->insertSuCo($lich_id, $hdv_id, $tieu_de_su_co, $ngay, $mo_ta_su_co, $xu_ly_su_co, $muc_do, $user_id);
            }

            // 3. Xử lý upload ảnh (Demo logic)
            // if (!empty($_FILES['hinh_anh']['name'][0])) { ... }

            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }

    // Hiển thị form sửa
    public function edit() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $nhatKy = $this->model->getNhatKyById($id);
            // Lấy thông tin sự cố nếu có trong ngày đó
            $suCo = $this->model->getSuCoByDateAndTour($nhatKy['lich_khoi_hanh_id'], $nhatKy['ngay_nhat_ky']);
            
            require_once 'views/nhatky/edit.php';
        }
    }

    // Xử lý cập nhật
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $thoi_tiet = $_POST['thoi_tiet'];
            $hoat_dong = $_POST['hoat_dong'];
            $diem_nhan = $_POST['diem_nhan'];

            $this->model->updateNhatKy($id, $thoi_tiet, $hoat_dong, $diem_nhan);
            
            // Logic cập nhật sự cố (nếu cần mở rộng) có thể thêm ở đây

            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }

    // Xóa
    public function delete() {
        if (isset($_GET['id'])) {
            $this->model->deleteNhatKy($_GET['id']);
            header("Location: " . BASE_URL_GUIDE . '?act=nhat_ky');
        }
    }
}
?>