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

        try {
            $thong_tin_khach_hang = $this->khachHang->getChiTietKhachHang($id);

            if (!$thong_tin_khach_hang) {
                $_SESSION['error'] = "Không tìm thấy thông tin khách hàng";
                header('Location: ?act=khach-hang');
                exit();
            }

            // Lấy lịch sử đặt tour
            $lich_su_dat_tour = $this->khachHang->getLichSuDatTour($id);

            // Lấy danh sách khách hàng cùng booking (nếu có)
            $khach_hang_cung_booking = [];
            if ($thong_tin_khach_hang['phieu_dat_tour_id']) {
                $khach_hang_cung_booking = $this->khachHang->getKhachHangCungBooking($thong_tin_khach_hang['phieu_dat_tour_id']);
            }

            require_once './views/khachhang/chiTietKhachHang.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải thông tin khách hàng: " . $e->getMessage();
            header('Location: ?act=khach-hang');
            exit();
        }
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
        try {
            // Lấy thống kê chi tiết theo tháng
            $thong_ke_chi_tiet = $this->khachHang->getThongKeChiTiet();

            // Tính toán các chỉ số tổng quan
            $tong_so_khach_hang = 0;
            $khach_hang_co_tour = 0;
            $khach_moi_thang_nay = 0;
            $tang_truong_thang_nay = 0;

            if (!empty($thong_ke_chi_tiet)) {
                // Tổng số khách hàng
                $tong_so_khach_hang = array_sum(array_column($thong_ke_chi_tiet, 'so_luong'));

                // Khách hàng có tour
                $khach_hang_co_tour = array_sum(array_column($thong_ke_chi_tiet, 'so_booking'));

                // Khách mới tháng này (tháng hiện tại)
                $thang_hien_tai = date('n');
                $nam_hien_tai = date('Y');
                foreach ($thong_ke_chi_tiet as $thong_ke) {
                    if ($thong_ke['thang'] == $thang_hien_tai && $thong_ke['nam'] == $nam_hien_tai) {
                        $khach_moi_thang_nay = $thong_ke['so_luong'];
                        break;
                    }
                }

                // Tính tăng trưởng tháng này so với tháng trước
                $thang_truoc = $thang_hien_tai - 1;
                $nam_truoc = $nam_hien_tai;
                if ($thang_truoc == 0) {
                    $thang_truoc = 12;
                    $nam_truoc = $nam_hien_tai - 1;
                }

                $khach_thang_truoc = 0;
                foreach ($thong_ke_chi_tiet as $thong_ke) {
                    if ($thong_ke['thang'] == $thang_truoc && $thong_ke['nam'] == $nam_truoc) {
                        $khach_thang_truoc = $thong_ke['so_luong'];
                        break;
                    }
                }

                if ($khach_thang_truoc > 0) {
                    $tang_truong_thang_nay = (($khach_moi_thang_nay - $khach_thang_truoc) / $khach_thang_truoc) * 100;
                    $tang_truong_thang_nay = round($tang_truong_thang_nay, 1);
                }
            }

            // Tỷ lệ khách có tour
            $ty_le_co_tour = $tong_so_khach_hang > 0 ? round(($khach_hang_co_tour / $tong_so_khach_hang) * 100, 1) : 0;

            require_once './views/khachhang/thongKeKhachHang.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải thống kê: " . $e->getMessage();
            header('Location: ?act=khach-hang');
            exit();
        }
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
            'Ngày sinh',
            'Giới tính',
            'Địa chỉ',
            'Tour hiện tại',
            'Ngày khởi hành',
            'Hướng dẫn viên',
            'Trạng thái',
            'Mã booking',
            'Ngày tạo'
        ], ';');

        // Dữ liệu
        foreach ($du_lieu as $row) {
            fputcsv($output, [
                $row['id'],
                $row['ho_ten'],
                $row['so_dien_thoai'],
                $row['email'],
                $row['cccd'],
                $row['ngay_sinh'] ? date('d/m/Y', strtotime($row['ngay_sinh'])) : '',
                $row['gioi_tinh'],
                $row['dia_chi'],
                $row['tour_hien_tai'] ?? 'Không có',
                $row['ngay_bat_dau'] ? date('d/m/Y', strtotime($row['ngay_bat_dau'])) : '',
                $row['huong_dan_vien'] ?? 'Chưa phân công',
                $row['trang_thai'] ?? 'Không có',
                $row['ma_dat_tour'] ?? '',
                date('d/m/Y H:i', strtotime($row['created_at']))
            ], ';');
        }

        fclose($output);
        exit();
    }

    // Cập nhật thông tin khách hàng
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            try {
                $data = [
                    'ho_ten' => $_POST['ho_ten'],
                    'email' => $_POST['email'],
                    'so_dien_thoai' => $_POST['so_dien_thoai'],
                    'cccd' => $_POST['cccd'],
                    'ngay_sinh' => $_POST['ngay_sinh'],
                    'gioi_tinh' => $_POST['gioi_tinh'],
                    'dia_chi' => $_POST['dia_chi'],
                    'ghi_chu' => $_POST['ghi_chu']
                ];

                $result = $this->khachHang->capNhatKhachHang($id, $data);

                if ($result) {
                    $_SESSION['success'] = "Cập nhật thông tin khách hàng thành công";
                } else {
                    $_SESSION['error'] = "Cập nhật thông tin khách hàng thất bại";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Lỗi khi cập nhật: " . $e->getMessage();
            }

            header('Location: ?act=khach-hang-show&id=' . $id);
            exit();
        }
    }

    // Xóa khách hàng
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        try {
            $result = $this->khachHang->xoaKhachHang($id);

            if ($result) {
                $_SESSION['success'] = "Xóa khách hàng thành công";
            } else {
                $_SESSION['error'] = "Không thể xóa khách hàng đang có tour hoặc không tồn tại";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi xóa khách hàng: " . $e->getMessage();
        }

        header('Location: ?act=khach-hang');
        exit();
    }
}
