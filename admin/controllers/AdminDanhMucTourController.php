<?php
class AdminDanhMucTourController
{
    public $danhMuc;

    public function __construct()
    {
        $this->danhMuc = new AdminDanhMucTour();
    }

    // Trang chủ danh mục
    public function index()
    {
        $thong_ke = $this->danhMuc->getThongKeDanhMuc();
        $danh_muc_list = $this->danhMuc->getAllDanhMucTour();
        require_once './views/danhmuctour/listDanhMucTour.php';
    }

    // Tạo danh mục mới
    public function createDanhMucTour()
    {
        require_once './views/danhmuctour/addDanhMucTour.php';
    }

    public function storeDanhMucTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_danh_muc' => $_POST['ten_danh_muc'] ?? '',
                'loai_tour' => $_POST['loai_tour'] ?? 'trong nước',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'trang_thai' => $_POST['trang_thai'] ?? 'hoạt động'
            ];

            if (empty($data['ten_danh_muc'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ?act=danh-muc-tour-create');
                exit;
            }

            $result = $this->danhMuc->createDanhMucTour($data);

            if ($result) {
                $_SESSION['success'] = 'Thêm danh mục thành công';
                header('Location: ?act=danh-muc');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm danh mục';
                header('Location: ?act=danh-muc-tour-create');
            }
            exit;
        }
    }

    // Sửa danh mục
    public function editDanhMucTour()
    {
        $id = $_GET['id'] ?? 0;
        $danh_muc = $this->danhMuc->getDanhMucTourById($id);

        if (!$danh_muc) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ?act=danh-muc');
            exit;
        }

        require_once './views/danhmuctour/editDanhMucTour.php';
    }

    public function updateDanhMucTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'ten_danh_muc' => $_POST['ten_danh_muc'] ?? '',
                'loai_tour' => $_POST['loai_tour'] ?? 'trong nước',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'trang_thai' => $_POST['trang_thai'] ?? 'hoạt động'
            ];

            if (empty($data['ten_danh_muc'])) {
                $_SESSION['error'] = 'Tên danh mục không được để trống';
                header('Location: ?act=danh-muc-tour-edit&id=' . $id);
                exit;
            }

            $result = $this->danhMuc->updateDanhMucTour($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật danh mục thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
            }
            header('Location: ?act=danh-muc');
            exit;
        }
    }

    // Xóa danh mục
    public function deleteDanhMucTour()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteDanhMucTour($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa danh mục (đang có tour sử dụng)';
        }
        
        header('Location: ?act=danh-muc');
        exit;
    }

    // Xem tour theo danh mục
    public function toursByDanhMuc()
    {
        $danh_muc_id = $_GET['danh_muc_id'] ?? 0;
        $danh_muc = $this->danhMuc->getDanhMucTourById($danh_muc_id);
        $tours = $this->danhMuc->getToursByDanhMuc($danh_muc_id);
        
        require_once './views/danhmuctour/toursByDanhMuc.php';
    }

    // Lọc và tìm kiếm tour
    public function filterTours()
    {
        $danh_muc_id = $_GET['danh_muc_id'] ?? 0;
        $loai_tour = $_GET['loai_tour'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $tours = $this->danhMuc->filterTours($danh_muc_id, $loai_tour, $search);
        $danh_muc_list = $this->danhMuc->getAllDanhMucTour();
        
        require_once './views/danhmuctour/filterTours.php';
    }
}