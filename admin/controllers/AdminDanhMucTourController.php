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
            header('Location: ?act=danh-muc-diem-den&error=Có lỗi xảy ra khi xóa điểm đến');
        }
    }

    // ==================== LOẠI TOUR ====================
    public function loaiTour()
    {
        $loai_tour_list = $this->danhMuc->getAllLoaiTour();
        require_once './views/danhmuctour/listLoaiTour.php';
    }

    public function createLoaiTour()
    {
        require_once './views/danhmuctour/addLoaiTour.php';
    }

    public function storeLoaiTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_loai' => $_POST['ten_loai'],
                'mo_ta' => $_POST['mo_ta']
            ];
            
            $result = $this->danhMuc->createLoaiTour($data);
            
            if ($result) {
                header('Location: ?act=danh-muc-loai-tour&success=Thêm loại tour thành công');
            } else {
                header('Location: ?act=danh-muc-loai-tour-create&error=Có lỗi xảy ra khi thêm loại tour');
            }
        }
    }

    public function editLoaiTour()
    {
        $id = $_GET['id'] ?? 0;
        $loai_tour = $this->danhMuc->getLoaiTourById($id);
        
        if (!$loai_tour) {
            header('Location: ?act=danh-muc-loai-tour&error=Loại tour không tồn tại');
            return;
        }
        
        require_once './views/danhmuctour/editLoaiTour.php';
    }

    public function updateLoaiTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_loai' => $_POST['ten_loai'],
                'mo_ta' => $_POST['mo_ta']
            ];
            
            $result = $this->danhMuc->updateLoaiTour($id, $data);
            
            if ($result) {
                header('Location: ?act=danh-muc-loai-tour&success=Cập nhật loại tour thành công');
            } else {
                header('Location: ?act=danh-muc-loai-tour-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteLoaiTour()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteLoaiTour($id);
        
        if ($result) {
            header('Location: ?act=danh-muc-loai-tour&success=Xóa loại tour thành công');
        } else {
            header('Location: ?act=danh-muc-loai-tour&error=Có lỗi xảy ra khi xóa loại tour');
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
            header('Location: ?act=danh-muc-tag-tour&error=Có lỗi xảy ra khi xóa tag tour');
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
            header('Location: ?act=danh-muc-chinh-sach&error=Có lỗi xảy ra khi xóa chính sách');
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
            header('Location: ?act=danh-muc-doi-tac&error=Có lỗi xảy ra khi xóa đối tác');
        }
    }

    // ==================== HƯỚNG DẪN VIÊN ====================
    public function huongDanVien()
    {
        $hdv_list = $this->danhMuc->getAllHDV();
        require_once './views/danhmuctour/listHuongDanVien.php';
    }

    public function createHDV()
    {
        require_once './views/danhmuctour/addHuongDanVien.php';
    }

    public function storeHDV()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_hdv' => $_POST['ten_hdv'],
                'ky_nang_ngon_ngu' => json_encode($_POST['ky_nang_ngon_ngu'] ?? []),
                'chuyen_mon' => $_POST['chuyen_mon'],
                'thong_tin_lien_he' => $_POST['thong_tin_lien_he'],
                'trang_thai' => $_POST['trang_thai']
            ];
            
            $result = $this->danhMuc->createHDV($data);
            
            if ($result) {
                header('Location: ?act=danh-muc-hdv&success=Thêm HDV thành công');
            } else {
                header('Location: ?act=danh-muc-hdv-create&error=Có lỗi xảy ra khi thêm HDV');
            }
        }
    }

    public function editHDV()
    {
        $id = $_GET['id'] ?? 0;
        $hdv = $this->danhMuc->getHDVById($id);
        
        if (!$hdv) {
            header('Location: ?act=danh-muc-hdv&error=HDV không tồn tại');
            return;
        }
        
        require_once './views/danhmuctour/editHuongDanVien.php';
    }

    public function updateHDV()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ten_hdv' => $_POST['ten_hdv'],
                'ky_nang_ngon_ngu' => json_encode($_POST['ky_nang_ngon_ngu'] ?? []),
                'chuyen_mon' => $_POST['chuyen_mon'],
                'thong_tin_lien_he' => $_POST['thong_tin_lien_he'],
                'trang_thai' => $_POST['trang_thai']
            ];
            
            $result = $this->danhMuc->updateHDV($id, $data);
            
            if ($result) {
                header('Location: ?act=danh-muc-hdv&success=Cập nhật HDV thành công');
            } else {
                header('Location: ?act=danh-muc-hdv-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    public function deleteHDV()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->danhMuc->deleteHDV($id);
        
        if ($result) {
            header('Location: ?act=danh-muc-hdv&success=Xóa HDV thành công');
        } else {
            header('Location: ?act=danh-muc-hdv&error=Có lỗi xảy ra khi xóa HDV');
        }
    }
}
?>