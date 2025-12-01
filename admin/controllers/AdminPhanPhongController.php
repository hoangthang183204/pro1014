<?php
class AdminPhanPhongController
{
    private $phanPhongModel;

    public function __construct()
    {
        $this->phanPhongModel = new AdminPhanPhong();
    }

    public function index()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        // Nếu không có lich_khoi_hanh_id, hiển thị danh sách lịch để chọn
        if (!$lich_khoi_hanh_id) {
            $this->showLichKhoiHanhList();
            return;
        }

        try {
            // Lấy thông tin lịch khởi hành
            $lich_khoi_hanh = $this->getLichKhoiHanhById($lich_khoi_hanh_id);

            if (!$lich_khoi_hanh) {
                $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

            $danh_sach_phan_phong = $this->phanPhongModel->getDanhSachPhanPhong($lich_khoi_hanh_id);
            $khach_chua_phan_phong = $this->phanPhongModel->getKhachChuaPhanPhong($lich_khoi_hanh_id);
            $thong_ke = $this->phanPhongModel->getThongKePhanPhong($lich_khoi_hanh_id);
            $danh_sach_khach_san = $this->phanPhongModel->getDanhSachKhachSan($lich_khoi_hanh_id);

            // Tính tổng số khách hàng
            $tong_khach_hang = $this->phanPhongModel->getKhachHangByLichKhoiHanh($lich_khoi_hanh_id);
            $tong_so_khach = is_countable($tong_khach_hang) ? count($tong_khach_hang) : 0;

            require_once './views/phanphong/phanphong.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }
    }

    private function showLichKhoiHanhList()
    {
        try {
            $conn = connectDB();
            $query = "SELECT 
                lkh.id,
                lkh.ngay_bat_dau,
                lkh.ngay_ket_thuc,
                lkh.trang_thai,
                lkh.so_cho_con_lai,
                lkh.so_cho_toi_da,
                t.ma_tour,
                t.ten_tour,
                (SELECT COUNT(*) FROM phieu_dat_tour WHERE lich_khoi_hanh_id = lkh.id AND trang_thai IN ('đã cọc', 'hoàn tất')) as so_khach
            FROM lich_khoi_hanh lkh
            JOIN tour t ON lkh.tour_id = t.id
            WHERE lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra')
            ORDER BY lkh.ngay_bat_dau DESC";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $lich_khoi_hanh_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            require_once './views/phanphong/select_lich.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? 0,
                'khach_hang_id' => $_POST['khach_hang_id'] ?? 0,
                'ten_khach_san' => trim($_POST['ten_khach_san'] ?? ''),
                'so_phong' => trim($_POST['so_phong'] ?? ''),
                'loai_phong' => $_POST['loai_phong'] ?? 'đơn',
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? '',
                'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? '',
                'ghi_chu' => trim($_POST['ghi_chu'] ?? ''),
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ];

            // Kiểm tra dữ liệu
            if (empty($data['lich_khoi_hanh_id']) || empty($data['khach_hang_id'])) {
                $_SESSION['error'] = 'Thiếu thông tin bắt buộc';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                exit();
            }

            if (empty($data['ten_khach_san']) || empty($data['so_phong'])) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin khách sạn và phòng';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                exit();
            }

            // Kiểm tra phòng trùng
            if ($this->phanPhongModel->kiemTraPhongTrung(
                $data['lich_khoi_hanh_id'],
                $data['ten_khach_san'],
                $data['so_phong'],
                $data['loai_phong']
            )) {
                $_SESSION['error'] = 'Phòng này đã được phân cho khách khác';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                exit();
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

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? 0,
                'ten_khach_san' => trim($_POST['ten_khach_san'] ?? ''),
                'so_phong' => trim($_POST['so_phong'] ?? ''),
                'loai_phong' => $_POST['loai_phong'] ?? 'đơn',
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? '',
                'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? '',
                'ghi_chu' => trim($_POST['ghi_chu'] ?? '')
            ];

            if (empty($data['id'])) {
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

            // Lấy thông tin phân phòng cũ
            $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($data['id']);
            if (!$phan_phong) {
                $_SESSION['error'] = 'Không tìm thấy phân phòng';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

            // Kiểm tra phòng trùng
            if ($this->phanPhongModel->kiemTraPhongTrung(
                $phan_phong['lich_khoi_hanh_id'],
                $data['ten_khach_san'],
                $data['so_phong'],
                $data['loai_phong'],
                $data['id']
            )) {
                $_SESSION['error'] = 'Phòng này đã được phân cho khách khác';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $phan_phong['lich_khoi_hanh_id']);
                exit();
            }

            $result = $this->phanPhongModel->capNhatPhanPhong($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $phan_phong['lich_khoi_hanh_id']);
            exit();
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;

            if (!$id) {
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

            $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($id);

            if (!$phan_phong) {
                $_SESSION['error'] = 'Không tìm thấy phân phòng';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

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

    public function phanPhongHangLoat()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? 0,
                'danh_sach_khach' => $_POST['danh_sach_khach'] ?? [],
                'ten_khach_san' => trim($_POST['ten_khach_san'] ?? ''),
                'so_phong' => trim($_POST['so_phong'] ?? ''),
                'loai_phong' => $_POST['loai_phong'] ?? 'đôi',
                'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? '',
                'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? '',
                'ghi_chu' => trim($_POST['ghi_chu'] ?? ''),
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ];

            if (empty($data['lich_khoi_hanh_id'])) {
                $_SESSION['error'] = 'Thiếu thông tin lịch khởi hành';
                header('Location: ?act=lich-khoi-hanh');
                exit();
            }

            if (empty($data['ten_khach_san']) || empty($data['so_phong'])) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin khách sạn và phòng';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                exit();
            }

            if (empty($data['danh_sach_khach'])) {
                $_SESSION['error'] = 'Vui lòng chọn ít nhất một khách hàng';
                header('Location: ?act=phan-phong&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
                exit();
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

    public function apiGetPhanPhong()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? 0;
        $phan_phong = $this->phanPhongModel->getChiTietPhanPhong($id);

        if ($phan_phong) {
            echo json_encode(['success' => true, 'data' => $phan_phong]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phân phòng']);
        }
        exit();
    }

    public function apiGetPhong()
    {
        header('Content-Type: application/json');

        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $ten_khach_san = $_GET['ten_khach_san'] ?? '';

        if ($lich_khoi_hanh_id && $ten_khach_san) {
            $danh_sach_phong = $this->phanPhongModel->getDanhSachPhong($lich_khoi_hanh_id, $ten_khach_san);
            echo json_encode(['success' => true, 'data' => $danh_sach_phong]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
        }
        exit();
    }

    private function getLichKhoiHanhById($id)
    {
        try {
            $conn = connectDB();
            $query = "SELECT lkh.*, t.ten_tour, t.ma_tour 
                      FROM lich_khoi_hanh lkh
                      JOIN tour t ON lkh.tour_id = t.id
                      WHERE lkh.id = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhById: " . $e->getMessage());
            return null;
        }
    }
}
?>