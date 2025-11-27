<?php
class AdminKhachHangController
{
    public $khachHang;

    public function __construct()
    {
        $this->khachHang = new AdminKhachHang();
    }

    // Trang chủ khách hàng
    public function index()
    {
        $thong_ke = $this->khachHang->getThongKeKhachHang();
        $danh_sach_khach_hang = $this->khachHang->getDanhSachKhachHang();
        require_once './views/khachhang/homeKhachHang.php';
    }

    // Xem chi tiết khách hàng
    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $thong_tin_khach_hang = $this->khachHang->getChiTietKhachHang($id);
        $lich_su_dat_tour = $this->khachHang->getLichSuDatTour($id);

        // Bổ sung: Lấy thành viên trong tour hiện tại
        $thanh_vien_tour_hien_tai = [];
        if ($thong_tin_khach_hang && $thong_tin_khach_hang['ma_dat_tour']) {
            $thanh_vien_tour_hien_tai = $this->khachHang->getThanhVienTourHienTai($thong_tin_khach_hang['ma_dat_tour']);
        }

        if (!$thong_tin_khach_hang) {
            header('Location: ?act=khach-hang&error=Không tìm thấy khách hàng');
            exit();
        }

        require_once './views/khachhang/chiTietKhachHang.php';
    }

    // Tìm kiếm khách hàng
    public function search()
    {
        $tu_khoa = $_GET['tu_khoa'] ?? '';
        $danh_sach_khach_hang = $this->khachHang->timKiemKhachHang($tu_khoa);
        $thong_ke = $this->khachHang->getThongKeKhachHang();

        require_once './views/khachhang/homeKhachHang.php';
    }

    // Thống kê khách hàng
    public function thongKe()
    {
        $thong_ke_chi_tiet = $this->khachHang->getThongKeChiTiet();
        require_once './views/khachhang/thongKeKhachHang.php';
    }

    // Export dữ liệu
    public function export()
    {
        $du_lieu = $this->khachHang->getDuLieuExport();

        // Header cho file CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=danh_sach_khach_hang_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, [
            'ID',
            'Họ tên',
            'Số điện thoại',
            'Email',
            'CCCD',
            'Tour đang tham gia',
            'Ngày khởi hành',
            'Hướng dẫn viên',
            'Trạng thái'
        ]);

        // Dữ liệu
        foreach ($du_lieu as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
