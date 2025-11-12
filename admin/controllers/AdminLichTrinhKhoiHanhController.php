<?php
class AdminLichKhoiHanhController
{
    public $lichKhoiHanhModel;
    
    public function __construct()
    {
        $this->lichKhoiHanhModel = new AdminLichKhoiHanh();
    }

    // Danh sách lịch khởi hành
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $trang_thai = $_GET['trang_thai'] ?? '';
        $thang = $_GET['thang'] ?? date('m');
        $nam = $_GET['nam'] ?? date('Y');
        
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getAllLichKhoiHanh($search, $trang_thai, $thang, $nam);
        
        require_once './views/lichtrinhkhoihanh/listKhoiHanh.php';
    }

    // Hiển thị form tạo lịch khởi hành mới
    public function create()
    {
        $tours = $this->lichKhoiHanhModel->getAllToursActive();
        
        require_once './views/lichtrinhkhoihanh/addLichTrinh.php';
    }

    // Xử lý tạo lịch khởi hành mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_bat_dau' => $_POST['ngay_bat_dau'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'gio_tap_trung' => $_POST['gio_tap_trung'],
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'so_cho_du_kien' => $_POST['so_cho_du_kien'],
                'ghi_chu_van_hanh' => $_POST['ghi_chu_van_hanh']
            ];
            
            $result = $this->lichKhoiHanhModel->createLichKhoiHanh($data);
            
            if ($result) {
                header('Location: ?act=lich-khoi-hanh&success=Thêm lịch khởi hành thành công');
            } else {
                header('Location: ?act=lich-khoi-hanh-create&error=Có lỗi xảy ra khi thêm lịch khởi hành');
            }
        }
    }

    // Hiển thị form sửa lịch khởi hành
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($id);
        
        if (!$lich_khoi_hanh) {
            header('Location: ?act=lich-khoi-hanh&error=Lịch khởi hành không tồn tại');
            return;
        }
        
        $tours = $this->lichKhoiHanhModel->getAllToursActive();
        
        require_once './views/lichtrinhkhoihanh/editLichTrinh.php';
    }

    // Xử lý cập nhật lịch khởi hành
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_bat_dau' => $_POST['ngay_bat_dau'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'gio_tap_trung' => $_POST['gio_tap_trung'],
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'so_cho_du_kien' => $_POST['so_cho_du_kien'],
                'ghi_chu_van_hanh' => $_POST['ghi_chu_van_hanh'],
                'trang_thai' => $_POST['trang_thai']
            ];
            
            $result = $this->lichKhoiHanhModel->updateLichKhoiHanh($id, $data);
            
            if ($result) {
                header('Location: ?act=lich-khoi-hanh&success=Cập nhật lịch khởi hành thành công');
            } else {
                header('Location: ?act=lich-khoi-hanh-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    // Xóa lịch khởi hành
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->lichKhoiHanhModel->deleteLichKhoiHanh($id);
        
        if ($result) {
            header('Location: ?act=lich-khoi-hanh&success=Xóa lịch khởi hành thành công');
        } else {
            header('Location: ?act=lich-khoi-hanh&error=Có lỗi xảy ra khi xóa lịch khởi hành');
        }
    }

    // Phân công HDV
    public function phanCongHDV()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);
        
        if (!$lich_khoi_hanh) {
            header('Location: ?act=lich-khoi-hanh&error=Lịch khởi hành không tồn tại');
            return;
        }
        
        $hdv_list = $this->lichKhoiHanhModel->getAllHDV();
        $hdv_trung_lich = $this->lichKhoiHanhModel->getHDVTrungLich($lich_khoi_hanh_id, $lich_khoi_hanh['ngay_bat_dau'], $lich_khoi_hanh['ngay_ket_thuc']);
        $phan_cong_hien_tai = $this->lichKhoiHanhModel->getPhanCongHDV($lich_khoi_hanh_id);
        
        require_once './views/lichtrinhkhoihanh/phanHuongDanVien.php';
    }

    // Xử lý phân công HDV - THÊM METHOD NÀY
    public function phanCongHDVStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;
            $hdv_id = $_POST['hdv_id'] ?? 0;
            $ghi_chu = $_POST['ghi_chu'] ?? '';
            
            if (!$lich_khoi_hanh_id || !$hdv_id) {
                header('Location: ?act=phan-cong-hdv&lich_khoi_hanh_id=' . $lich_khoi_hanh_id . '&error=Vui lòng chọn HDV');
                return;
            }
            
            $result = $this->lichKhoiHanhModel->phanCongHDV($lich_khoi_hanh_id, $hdv_id, $ghi_chu);
            
            if ($result) {
                header('Location: ?act=phan-cong-hdv&lich_khoi_hanh_id=' . $lich_khoi_hanh_id . '&success=Phân công HDV thành công');
            } else {
                header('Location: ?act=phan-cong-hdv&lich_khoi_hanh_id=' . $lich_khoi_hanh_id . '&error=Có lỗi xảy ra khi phân công HDV');
            }
        }
    }

    // Quản lý dịch vụ kèm theo
    public function dichVuKemTheo()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);
        
        if (!$lich_khoi_hanh) {
            header('Location: ?act=lich-khoi-hanh&error=Lịch khởi hành không tồn tại');
            return;
        }
        
        $doi_tac_list = $this->lichKhoiHanhModel->getAllDoiTac();
        $dich_vu_list = $this->lichKhoiHanhModel->getDichVuKemTheo($lich_khoi_hanh_id);
        
        require_once './views/lichtrinhkhoihanh/dichVuTheoKem.php';
    }

    // Checklist chuẩn bị
    public function checklistChuanBi()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);
        
        if (!$lich_khoi_hanh) {
            header('Location: ?act=lich-khoi-hanh&error=Lịch khởi hành không tồn tại');
            return;
        }
        
        $checklist = $this->lichKhoiHanhModel->getChecklistChuanBi($lich_khoi_hanh_id);
        
        require_once './views/lichtrinhkhoihanh/checklistChuanBi.php';
    }
}
?>

