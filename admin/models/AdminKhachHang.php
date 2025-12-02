<?php
class AdminKhachHang
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy thống kê khách hàng
    public function getThongKeKhachHang()
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM khach_hang) as tong_khach_hang,
                        (SELECT COUNT(DISTINCT kh.id) 
                         FROM khach_hang kh 
                         INNER JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id 
                         WHERE pdt.trang_thai != 'hủy') as khach_co_tour,
                        (SELECT COUNT(*) FROM khach_hang WHERE DATE(created_at) = CURDATE()) as khach_moi_hom_nay,
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'hoàn tất') as tour_hoan_tat,
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'đã cọc') as tour_da_coc,
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'chờ xác nhận') as tour_cho_xac_nhan";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Get Thong Ke Khach Hang Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách khách hàng với thông tin tour
    public function getDanhSachKhachHang()
    {
        try {
            $query = "SELECT 
                        kh.id,
                        kh.ho_ten,
                        kh.so_dien_thoai,
                        kh.email,
                        kh.cccd,
                        kh.ngay_sinh,
                        kh.gioi_tinh,
                        kh.dia_chi,
                        kh.created_at,
                        pdt.id as phieu_dat_tour_id,
                        pdt.ma_dat_tour,
                        pdt.trang_thai as trang_thai_dat_tour,
                        t.ten_tour,
                        t.ma_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        hdv.ho_ten as ten_huong_dan_vien
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    ORDER BY kh.created_at DESC
                    LIMIT 100";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Danh Sach Khach Hang Error: " . $e->getMessage());
            return [];
        }
    }

    // Tìm kiếm khách hàng
    public function timKiemKhachHang($tu_khoa)
    {
        try {
            $query = "SELECT 
                        kh.id,
                        kh.ho_ten,
                        kh.so_dien_thoai,
                        kh.email,
                        kh.cccd,
                        kh.ngay_sinh,
                        kh.gioi_tinh,
                        kh.dia_chi,
                        pdt.ma_dat_tour,
                        pdt.trang_thai as trang_thai_dat_tour,
                        t.ten_tour,
                        t.ma_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        hdv.ho_ten as ten_huong_dan_vien
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE kh.ho_ten LIKE :tu_khoa 
                       OR kh.so_dien_thoai LIKE :tu_khoa 
                       OR kh.email LIKE :tu_khoa
                       OR kh.cccd LIKE :tu_khoa
                       OR t.ten_tour LIKE :tu_khoa
                    ORDER BY kh.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tu_khoa' => "%$tu_khoa%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Tim Kiem Khach Hang Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết khách hàng
    public function getChiTietKhachHang($id)
    {
        try {
            $query = "SELECT 
                        kh.*,
                        pdt.id as phieu_dat_tour_id,
                        pdt.ma_dat_tour,
                        pdt.so_luong_khach,
                        pdt.tong_tien,
                        pdt.trang_thai as trang_thai_dat_tour,
                        pdt.ghi_chu as ghi_chu_dat_tour,
                        pdt.created_at as ngay_dat_tour,
                        t.ten_tour,
                        t.ma_tour,
                        t.gia_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        lkh.gio_tap_trung,
                        lkh.diem_tap_trung,
                        hdv.ho_ten as ten_huong_dan_vien,
                        hdv.so_dien_thoai as sdt_huong_dan_vien,
                        hdv.email as email_huong_dan_vien
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE kh.id = :id
                    LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Get Chi Tiet Khach Hang Error: " . $e->getMessage());
            return null;
        }
    }

    // Lấy tất cả khách hàng trong cùng một booking
    public function getKhachHangCungBooking($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT 
                        kh.*,
                        CASE 
                            WHEN pdt.khach_hang_id = kh.id THEN 1 
                            ELSE 0 
                        END as la_khach_chinh
                    FROM khach_hang kh
                    WHERE kh.phieu_dat_tour_id = :phieu_dat_tour_id
                    ORDER BY la_khach_chinh DESC, kh.created_at ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Khach Hang Cung Booking Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch sử đặt tour của khách hàng - PHIÊN BẢN ĐƠN GIẢN
    public function getLichSuDatTour($khach_hang_id)
    {
        try {
            $query = "SELECT 
                    pdt.id,
                    pdt.ma_dat_tour,
                    pdt.khach_hang_id,
                    t.ten_tour,
                    t.ma_tour,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc,
                    pdt.tong_tien,
                    pdt.so_luong_khach,
                    pdt.trang_thai,
                    pdt.created_at as ngay_dat
                FROM phieu_dat_tour pdt
                LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                LEFT JOIN tour t ON lkh.tour_id = t.id
                WHERE pdt.khach_hang_id = :khach_hang_id
                ORDER BY pdt.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':khach_hang_id' => $khach_hang_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Lich Su Dat Tour Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy thống kê chi tiết
    public function getThongKeChiTiet()
    {
        try {
            $query = "SELECT 
                        MONTH(kh.created_at) as thang,
                        YEAR(kh.created_at) as nam,
                        COUNT(*) as so_luong,
                        COUNT(DISTINCT kh.phieu_dat_tour_id) as so_booking
                    FROM khach_hang kh
                    WHERE kh.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                    GROUP BY YEAR(kh.created_at), MONTH(kh.created_at)
                    ORDER BY nam DESC, thang DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Thong Ke Chi Tiet Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy dữ liệu export
    public function getDuLieuExport()
    {
        try {
            $query = "SELECT 
                        kh.id,
                        kh.ho_ten,
                        kh.so_dien_thoai,
                        kh.email,
                        kh.cccd,
                        kh.ngay_sinh,
                        kh.gioi_tinh,
                        kh.dia_chi,
                        t.ten_tour as tour_hien_tai,
                        lkh.ngay_bat_dau,
                        hdv.ho_ten as huong_dan_vien,
                        pdt.trang_thai,
                        pdt.ma_dat_tour,
                        kh.created_at
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    ORDER BY kh.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Du Lieu Export Error: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật thông tin khách hàng
    public function capNhatKhachHang($id, $data)
    {
        try {
            $query = "UPDATE khach_hang SET 
                        ho_ten = :ho_ten,
                        email = :email,
                        so_dien_thoai = :so_dien_thoai,
                        cccd = :cccd,
                        ngay_sinh = :ngay_sinh,
                        gioi_tinh = :gioi_tinh,
                        dia_chi = :dia_chi,
                        ghi_chu = :ghi_chu,
                        updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':ho_ten' => $data['ho_ten'],
                ':email' => $data['email'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':cccd' => $data['cccd'],
                ':ngay_sinh' => $data['ngay_sinh'],
                ':gioi_tinh' => $data['gioi_tinh'],
                ':dia_chi' => $data['dia_chi'],
                ':ghi_chu' => $data['ghi_chu'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Cap Nhat Khach Hang Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa khách hàng
    public function xoaKhachHang($id)
    {
        try {
            // Kiểm tra xem khách hàng có đang trong tour không
            $query_check = "SELECT phieu_dat_tour_id FROM khach_hang WHERE id = :id";
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->execute([':id' => $id]);
            $khach_hang = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($khach_hang && $khach_hang['phieu_dat_tour_id']) {
                // Không cho phép xóa khách hàng đang có tour
                return false;
            }

            $query = "DELETE FROM khach_hang WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Xoa Khach Hang Error: " . $e->getMessage());
            return false;
        }
    }
}
