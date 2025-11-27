<?php
class AdminPhanPhongController {
    private $phanPhongModel;
    private $lichKhoiHanhModel;

    public function __construct() {
        $this->phanPhongModel = new AdminPhanPhong();
        $this->lichKhoiHanhModel = new AdminLichKhoiHanh();
    }

    // Danh sách phân phòng
    public function index() {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        
        if (!$lich_khoi_hanh_id) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);
        $danh_sach_phan_phong = $this->phanPhongModel->getDanhSachPhanPhong($lich_khoi_hanh_id);
        $khach_chua_phan_phong = $this->phanPhongModel->getKhachChuaPhanPhong($lich_khoi_hanh_id);
        $thong_ke = $this->phanPhongModel->getThongKePhanPhong($lich_khoi_hanh_id);

        require_once './views/phanphong/phanphong.php';
    }

    // Thêm phân phòng
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                'thanh_vien_dat_tour_id' => $_POST['thanh_vien_dat_tour_id'],
                'ten_khach_san' => $_POST['ten_khach_san'],
                'so_phong' => $_POST['so_phong'],
                'loai_phong' => $_POST['loai_phong'],
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'],
                'ngay_tra_phong' => $_POST['ngay_tra_phong'],
                'ghi_chu' => $_POST['ghi_chu'],
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ];

            // Kiểm tra phòng trùng
            if ($this->phanPhongModel->kiemTraPhongTrung(
                $data['lich_khoi_hanh_id'], 
                $data['ten_khach_san'], 
                $data['so_phong'], 
                $data['loai_phong']
            )) {
                $_SESSION['error'] = 'Phòng đã được phân cho khách khác';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                return;
            }

            $result = $this->phanPhongModel->themPhanPhong($data);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
            exit();
        }
    }

    // Cập nhật phân phòng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'],
                'ten_khach_san' => $_POST['ten_khach_san'],
                'so_phong' => $_POST['so_phong'],
                'loai_phong' => $_POST['loai_phong'],
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'],
                'ngay_tra_phong' => $_POST['ngay_tra_phong'],
                'ghi_chu' => $_POST['ghi_chu']
            ];

            $result = $this->phanPhongModel->capNhatPhanPhong($data);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            // Lấy lich_khoi_hanh_id để redirect về
            $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($data['id']);
            header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $phan_phong['lich_khoi_hanh_id']);
            exit();
        }
    }

    // Xóa phân phòng
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            
            // Lấy thông tin trước khi xóa để redirect
            $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($id);
            
            $result = $this->phanPhongModel->xoaPhanPhong($id);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $phan_phong['lich_khoi_hanh_id']);
            exit();
        }
    }

    // Phân phòng hàng loạt
    public function phanPhongHangLoat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                'danh_sach_khach' => $_POST['danh_sach_khach'] ?? [],
                'ten_khach_san' => $_POST['ten_khach_san'],
                'so_phong' => $_POST['so_phong'],
                'loai_phong' => $_POST['loai_phong'],
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'],
                'ngay_tra_phong' => $_POST['ngay_tra_phong'],
                'ghi_chu' => $_POST['ghi_chu'],
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ];

            if (empty($data['danh_sach_khach'])) {
                $_SESSION['error'] = 'Vui lòng chọn ít nhất một khách hàng';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                return;
            }

            $result = $this->phanPhongModel->phanPhongHangLoat($data);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
            exit();
        }
    }

    // API lấy thông tin phân phòng
    public function apiGetPhanPhong() {
        $id = $_GET['id'] ?? 0;
        $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($id);
        
        if ($phan_phong) {
            echo json_encode(['success' => true, 'data' => $phan_phong]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phân phòng']);
        }
    }
}
?>