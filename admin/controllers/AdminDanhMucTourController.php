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
        require_once './views/danhmuctour/homeDanhMuc.php';
    }

    // ==================== DANH MỤC TOUR ====================
    public function danhMucTour()
    {
        $danh_muc_list = $this->danhMuc->getAllDanhMucTour();
        require_once './views/danhmuctour/listDanhMucTour.php';
    }

    public function createDanhMucTour()
    {
        require_once './views/danhmuctour/addDanhMucTour.php';
    }

    public function storeDanhMucTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_danh_muc' => $_POST['ten_danh_muc'],
                'loai_tour' => $_POST['loai_tour'],
                'mo_ta' => $_POST['mo_ta'],
                'trang_thai' => $_POST['trang_thai'] ?? 'hoạt động'
            ];

            $result = $this->danhMuc->createDanhMucTour($data);

            if ($result) {
                header('Location: ?act=danh-muc-tour&success=Thêm danh mục tour thành công');
            } else {
                header('Location: ?act=danh-muc-tour-create&error=Có lỗi xảy ra khi thêm danh mục tour');
            }
        }
    }

    public function editDanhMucTour()
    {
        $id = $_GET['id'] ?? 0;
        $danh_muc = $this->danhMuc->getDanhMucTourById($id);

        if (!$danh_muc) {
            header('Location: ?act=danh-muc-tour&error=Danh mục tour không tồn tại');
            return;
        }

        require_once './views/danhmuctour/editDanhMucTour.php';
    }

    public function updateDanhMucTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_danh_muc' => $_POST['ten_danh_muc'],
                'loai_tour' => $_POST['loai_tour'],
                'mo_ta' => $_POST['mo_ta'],
                'trang_thai' => $_POST['trang_thai']
            ];

            $result = $this->danhMuc->updateDanhMucTour($id, $data);

            if ($result) {
                header('Location: ?act=danh-muc-tour&success=Cập nhật danh mục tour thành công');
            } else {
                header('Location: ?act=danh-muc-tour-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteDanhMucTour()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteDanhMucTour($id);

        if ($result) {
            header('Location: ?act=danh-muc-tour&success=Xóa danh mục tour thành công');
        } else {
            header('Location: ?act=danh-muc-tour&error=Có lỗi xảy ra khi xóa danh mục tour hoặc danh mục đang được sử dụng');
        }
    }

    // ==================== ĐIỂM ĐẾN ====================
    public function diemDen()
    {
        $diem_den_list = $this->danhMuc->getAllDiemDen();
        require_once './views/danhmuctour/listDiemDen.php';
    }

    public function createDiemDen()
    {
        require_once './views/danhmuctour/addDiemDen.php';
    }

    public function storeDiemDen()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_diem_den' => $_POST['ten_diem_den'],
                'mo_ta' => $_POST['mo_ta']
            ];

            $result = $this->danhMuc->createDiemDen($data);

            if ($result) {
                header('Location: ?act=danh-muc-diem-den&success=Thêm điểm đến thành công');
            } else {
                header('Location: ?act=danh-muc-diem-den-create&error=Có lỗi xảy ra khi thêm điểm đến');
            }
        }
    }

    public function editDiemDen()
    {
        $id = $_GET['id'] ?? 0;
        $diem_den = $this->danhMuc->getDiemDenById($id);

        if (!$diem_den) {
            header('Location: ?act=danh-muc-diem-den&error=Điểm đến không tồn tại');
            return;
        }

        require_once './views/danhmuctour/editDiemDen.php';
    }

    public function updateDiemDen()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_diem_den' => $_POST['ten_diem_den'],
                'mo_ta' => $_POST['mo_ta']
            ];

            $result = $this->danhMuc->updateDiemDen($id, $data);

            if ($result) {
                header('Location: ?act=danh-muc-diem-den&success=Cập nhật điểm đến thành công');
            } else {
                header('Location: ?act=danh-muc-diem-den-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteDiemDen()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteDiemDen($id);

        if ($result) {
            header('Location: ?act=danh-muc-diem-den&success=Xóa điểm đến thành công');
        } else {
            header('Location: ?act=danh-muc-diem-den&error=Có lỗi xảy ra khi xóa điểm đến hoặc điểm đến đang được sử dụng');
        }
    }

    // ==================== TAG TOUR ====================
    public function tagTour()
    {
        $tag_tour_list = $this->danhMuc->getAllTagTour();
        require_once './views/danhmuctour/listTag.php';
    }

    public function createTagTour()
    {
        require_once './views/danhmuctour/addTagTour.php';
    }

    public function storeTagTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_tag' => $_POST['ten_tag']
            ];

            $result = $this->danhMuc->createTagTour($data);

            if ($result) {
                header('Location: ?act=danh-muc-tag-tour&success=Thêm tag tour thành công');
            } else {
                header('Location: ?act=danh-muc-tag-tour-create&error=Có lỗi xảy ra khi thêm tag tour');
            }
        }
    }

    public function editTagTour()
    {
        $id = $_GET['id'] ?? 0;
        $tag_tour = $this->danhMuc->getTagTourById($id);

        if (!$tag_tour) {
            header('Location: ?act=danh-muc-tag-tour&error=Tag tour không tồn tại');
            return;
        }

        require_once './views/danhmuctour/editTagTour.php';
    }

    public function updateTagTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_tag' => $_POST['ten_tag']
            ];

            $result = $this->danhMuc->updateTagTour($id, $data);

            if ($result) {
                header('Location: ?act=danh-muc-tag-tour&success=Cập nhật tag tour thành công');
            } else {
                header('Location: ?act=danh-muc-tag-tour-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteTagTour()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteTagTour($id);

        if ($result) {
            header('Location: ?act=danh-muc-tag-tour&success=Xóa tag tour thành công');
        } else {
            header('Location: ?act=danh-muc-tag-tour&error=Có lỗi xảy ra khi xóa tag tour hoặc tag đang được sử dụng');
        }
    }

    // ==================== CHÍNH SÁCH TOUR ====================
    public function chinhSach()
    {
        $chinh_sach_list = $this->danhMuc->getAllChinhSach();
        require_once './views/danhmuctour/listChinhSach.php';
    }

    public function createChinhSach()
    {
        require_once './views/danhmuctour/addChinhSach.php';
    }

    public function storeChinhSach()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_chinh_sach' => $_POST['ten_chinh_sach'],
                'quy_dinh_huy_doi' => $_POST['quy_dinh_huy_doi'],
                'luu_y_suc_khoe' => $_POST['luu_y_suc_khoe'],
                'luu_y_hanh_ly' => $_POST['luu_y_hanh_ly'],
                'luu_y_khac' => $_POST['luu_y_khac']
            ];

            $result = $this->danhMuc->createChinhSach($data);

            if ($result) {
                header('Location: ?act=danh-muc-chinh-sach&success=Thêm chính sách thành công');
            } else {
                header('Location: ?act=danh-muc-chinh-sach-create&error=Có lỗi xảy ra khi thêm chính sách');
            }
        }
    }

    public function editChinhSach()
    {
        $id = $_GET['id'] ?? 0;
        $chinh_sach = $this->danhMuc->getChinhSachById($id);

        if (!$chinh_sach) {
            header('Location: ?act=danh-muc-chinh-sach&error=Chính sách không tồn tại');
            return;
        }

        require_once './views/danhmuctour/editChinhSach.php';
    }

    public function updateChinhSach()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_chinh_sach' => $_POST['ten_chinh_sach'],
                'quy_dinh_huy_doi' => $_POST['quy_dinh_huy_doi'],
                'luu_y_suc_khoe' => $_POST['luu_y_suc_khoe'],
                'luu_y_hanh_ly' => $_POST['luu_y_hanh_ly'],
                'luu_y_khac' => $_POST['luu_y_khac']
            ];

            $result = $this->danhMuc->updateChinhSach($id, $data);

            if ($result) {
                header('Location: ?act=danh-muc-chinh-sach&success=Cập nhật chính sách thành công');
            } else {
                header('Location: ?act=danh-muc-chinh-sach-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteChinhSach()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteChinhSach($id);

        if ($result) {
            header('Location: ?act=danh-muc-chinh-sach&success=Xóa chính sách thành công');
        } else {
            header('Location: ?act=danh-muc-chinh-sach&error=Có lỗi xảy ra khi xóa chính sách hoặc chính sách đang được sử dụng');
        }
    }

    // ==================== ĐỐI TÁC ====================
    public function doiTac()
    {
        $doi_tac_list = $this->danhMuc->getAllDoiTac();
        require_once './views/danhmuctour/listDoiTac.php';
    }

    public function createDoiTac()
    {
        require_once './views/danhmuctour/addDoiTac.php';
    }

    public function storeDoiTac()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_doi_tac' => $_POST['ten_doi_tac'],
                'loai_dich_vu' => $_POST['loai_dich_vu'],
                'thong_tin_lien_he' => $_POST['thong_tin_lien_he']
            ];

            $result = $this->danhMuc->createDoiTac($data);

            if ($result) {
                header('Location: ?act=danh-muc-doi-tac&success=Thêm đối tác thành công');
            } else {
                header('Location: ?act=danh-muc-doi-tac-create&error=Có lỗi xảy ra khi thêm đối tác');
            }
        }
    }

    public function editDoiTac()
    {
        $id = $_GET['id'] ?? 0;
        $doi_tac = $this->danhMuc->getDoiTacById($id);

        if (!$doi_tac) {
            header('Location: ?act=danh-muc-doi-tac&error=Đối tác không tồn tại');
            return;
        }

        require_once './views/danhmuctour/editDoiTac.php';
    }

    public function updateDoiTac()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_doi_tac' => $_POST['ten_doi_tac'],
                'loai_dich_vu' => $_POST['loai_dich_vu'],
                'thong_tin_lien_he' => $_POST['thong_tin_lien_he']
            ];

            $result = $this->danhMuc->updateDoiTac($id, $data);

            if ($result) {
                header('Location: ?act=danh-muc-doi-tac&success=Cập nhật đối tác thành công');
            } else {
                header('Location: ?act=danh-muc-doi-tac-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteDoiTac()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteDoiTac($id);

        if ($result) {
            header('Location: ?act=danh-muc-doi-tac&success=Xóa đối tác thành công');
        } else {
            header('Location: ?act=danh-muc-doi-tac&error=Có lỗi xảy ra khi xóa đối tác hoặc đối tác đang được sử dụng');
        }
    }
    // ==================== HƯỚNG DẪN VIÊN ====================
    public function huongDanVien()
    {
        $huong_dan_vien_list = $this->danhMuc->getAllHDV();
        require_once './views/danhmuctour/listHuongDanVien.php';
    }

    // Xem chi tiết HDV - Trang riêng
    public function chiTietHuongDanVien()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?act=huong-dan-vien');
            return;
        }

        $hdv = $this->danhMuc->getHDVById($id);
        if (!$hdv) {
            $_SESSION['error'] = 'Không tìm thấy hướng dẫn viên';
            header('Location: ?act=huong-dan-vien');
            return;
        }

        require_once './views/danhmuctour/chiTietHuongDanVien.php';
    }

    // Sửa HDV - Hiển thị form
    public function suaHuongDanVien()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?act=huong-dan-vien');
            return;
        }

        $hdv = $this->danhMuc->getHDVById($id);
        if (!$hdv) {
            $_SESSION['error'] = 'Không tìm thấy hướng dẫn viên';
            header('Location: ?act=huong-dan-vien');
            return;
        }

        require_once './views/danhmuctour/editHuongDanVien.php';
    }

    // Cập nhật HDV - Xử lý form
    // Cập nhật HDV - Xử lý form
    public function updateHuongDanVien()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                if (!$id) {
                    $_SESSION['error'] = 'ID không hợp lệ';
                    header('Location: ?act=huong-dan-vien');
                    return;
                }

                // Debug dữ liệu nhận được
                error_log("POST data: " . print_r($_POST, true));

                $data = [
                    'ho_ten' => $_POST['ho_ten'] ?? '',
                    'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'dia_chi' => $_POST['dia_chi'] ?? '',
                    'so_giay_phep_hanh_nghe' => $_POST['so_giay_phep_hanh_nghe'] ?? '',
                    'loai_huong_dan_vien' => $_POST['loai_huong_dan_vien'] ?? 'nội địa',
                    'chuyen_mon' => $_POST['chuyen_mon'] ?? '',
                    'trang_thai' => $_POST['trang_thai'] ?? 'đang làm việc',
                    'ngon_ngu' => isset($_POST['ngon_ngu']) ? json_encode($_POST['ngon_ngu']) : '[]',
                    'ghi_chu' => $_POST['ghi_chu'] ?? ''
                ];

                // Debug dữ liệu trước khi update
                error_log("Update data: " . print_r($data, true));

                $result = $this->danhMuc->updateHDV($id, $data);

                if ($result) {
                    $_SESSION['success'] = 'Cập nhật hướng dẫn viên thành công!';
                } else {
                    $_SESSION['error'] = 'Cập nhật hướng dẫn viên thất bại!';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
                error_log("Lỗi updateHuongDanVien: " . $e->getMessage());
            }
        }

        header('Location: ?act=huong-dan-vien');
        exit;
    }

    // Xóa HDV
    public function xoaHuongDanVien()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?act=huong-dan-vien');
            return;
        }

        try {
            $result = $this->danhMuc->deleteHDV($id);

            if ($result) {
                $_SESSION['success'] = 'Xóa hướng dẫn viên thành công!';
            } else {
                $_SESSION['error'] = 'Xóa hướng dẫn viên thất bại hoặc hướng dẫn viên đang được sử dụng!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ?act=huong-dan-vien');
        exit;
    }
}
