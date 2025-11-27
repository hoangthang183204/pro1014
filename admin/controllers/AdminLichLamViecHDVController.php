<?php
class AdminLichLamViecHDVController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminLichLamViecHDV();
    }

    // Trang danh sách lịch làm việc
    public function index()
    {
        $filters = [];
        
        if (!empty($_GET['hdv'])) {
            $filters['huong_dan_vien_id'] = $_GET['hdv'];
        }
        
        if (!empty($_GET['thang'])) {
            $filters['thang'] = $_GET['thang'];
        }

        $hdv_list = $this->model->getHDVDangLamViec();
        $lich_lam_viec = $this->model->getAllLichLamViec($filters);

        // Truyền dữ liệu sang view
        $data = [
            'hdv_list' => $hdv_list,
            'lich_lam_viec' => $lich_lam_viec,
            'filters' => $filters
        ];

        // Load view và truyền dữ liệu
        extract($data);
        include_once './views/lichLamViecHDV/lichLamViec.php';
    }

    // Thêm lịch làm việc
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'huong_dan_vien_id' => $_POST['huong_dan_vien_id'],
                'ngay' => $_POST['ngay'],
                'loai_lich' => $_POST['loai_lich'],
                'ghi_chu' => $_POST['ghi_chu'],
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ];

            // Kiểm tra trùng lịch (trừ trường hợp sửa)
            if ($this->model->kiemTraTrungLich($data['huong_dan_vien_id'], $data['ngay'])) {
                $_SESSION['error'] = 'Hướng dẫn viên đã có lịch làm việc trong ngày này!';
                header('Location: index.php?act=lich-lam-viec-hdv');
                exit();
            }

            if ($this->model->create($data)) {
                $_SESSION['success'] = 'Thêm lịch làm việc thành công!';
            } else {
                $_SESSION['error'] = 'Thêm lịch làm việc thất bại!';
            }

            header('Location: index.php?act=lich-lam-viec-hdv');
            exit();
        }
    }

    // Cập nhật lịch làm việc - SỬA QUAN TRỌNG
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            // Kiểm tra ID có tồn tại không
            if (empty($id)) {
                $_SESSION['error'] = 'ID lịch làm việc không hợp lệ!';
                header('Location: index.php?act=lich-lam-viec-hdv');
                exit();
            }

            $data = [
                'huong_dan_vien_id' => $_POST['huong_dan_vien_id'],
                'ngay' => $_POST['ngay'],
                'loai_lich' => $_POST['loai_lich'],
                'ghi_chu' => $_POST['ghi_chu']
            ];

            // DEBUG: Kiểm tra dữ liệu nhận được
            error_log("UPDATE DATA: " . print_r($data, true));
            error_log("ID: " . $id);

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật lịch làm việc thành công!';
            } else {
                $_SESSION['error'] = 'Cập nhật lịch làm việc thất bại!';
            }

            header('Location: index.php?act=lich-lam-viec-hdv');
            exit();
        } else {
            $_SESSION['error'] = 'Phương thức không hợp lệ!';
            header('Location: index.php?act=lich-lam-viec-hdv');
            exit();
        }
    }

    // Xóa lịch làm việc
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id && $this->model->delete($id)) {
            $_SESSION['success'] = 'Xóa lịch làm việc thành công!';
        } else {
            $_SESSION['error'] = 'Xóa lịch làm việc thất bại!';
        }

        header('Location: index.php?act=lich-lam-viec-hdv');
        exit();
    }

    // Lọc lịch làm việc
    public function filter()
    {
        $params = [];
        if (!empty($_GET['hdv'])) $params[] = 'hdv=' . $_GET['hdv'];
        if (!empty($_GET['thang'])) $params[] = 'thang=' . $_GET['thang'];
        
        $query_string = !empty($params) ? '&' . implode('&', $params) : '';
        header('Location: index.php?act=lich-lam-viec-hdv' . $query_string);
        exit();
    }
}
?>