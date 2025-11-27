<?php
class AdminThanhVienTourController
{
    public $thanhVienTour;

    public function __construct()
    {
        $this->thanhVienTour = new AdminThanhVienTour();
    }

    // Trang chủ thành viên tour
    public function index()
    {
        $thong_ke = $this->thanhVienTour->getThongKeThanhVien();
        $danh_sach_thanh_vien = $this->thanhVienTour->getDanhSachThanhVien();
        require_once './views/thanhvien/homeThanhVien.php';
    }

    // Xem chi tiết thành viên
    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $thong_tin_thanh_vien = $this->thanhVienTour->getChiTietThanhVien($id);
        
        if (!$thong_tin_thanh_vien) {
            header('Location: ?act=thanh-vien-tour&error=Không tìm thấy thành viên');
            exit();
        }
        
        require_once './views/thanhvien/chiTietThanhVien.php';
    }

    // Tìm kiếm thành viên
    public function search()
    {
        $tu_khoa = $_GET['tu_khoa'] ?? '';
        $danh_sach_thanh_vien = $this->thanhVienTour->timKiemThanhVien($tu_khoa);
        $thong_ke = $this->thanhVienTour->getThongKeThanhVien();
        
        require_once './views/thanhvien/homeThanhVien.php';
    }

    // Cập nhật thông tin thành viên
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $ho_ten = $_POST['ho_ten'] ?? '';
            $cccd = $_POST['cccd'] ?? '';
            $ngay_sinh = $_POST['ngay_sinh'] ?? '';
            $gioi_tinh = $_POST['gioi_tinh'] ?? '';
            $yeu_cau_dac_biet = $_POST['yeu_cau_dac_biet'] ?? '';

            $result = $this->thanhVienTour->capNhatThanhVien($id, [
                'ho_ten' => $ho_ten,
                'cccd' => $cccd,
                'ngay_sinh' => $ngay_sinh,
                'gioi_tinh' => $gioi_tinh,
                'yeu_cau_dac_biet' => $yeu_cau_dac_biet
            ]);

            if ($result) {
                header('Location: ?act=thanh-vien-tour-chi-tiet&id=' . $id . '&success=Cập nhật thành công');
            } else {
                header('Location: ?act=thanh-vien-tour-chi-tiet&id=' . $id . '&error=Cập nhật thất bại');
            }
            exit();
        }
    }

    // Xử lý yêu cầu đặc biệt
    public function xuLyYeuCau()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $ghi_chu_xu_ly = $_POST['ghi_chu_xu_ly'] ?? '';

            $result = $this->thanhVienTour->xuLyYeuCauDacBiet($id, $ghi_chu_xu_ly);

            if ($result) {
                header('Location: ?act=thanh-vien-tour-chi-tiet&id=' . $id . '&success=Đã xử lý yêu cầu');
            } else {
                header('Location: ?act=thanh-vien-tour-chi-tiet&id=' . $id . '&error=Xử lý thất bại');
            }
            exit();
        }
    }
}
?>