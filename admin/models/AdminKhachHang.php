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
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'hoàn tất') as tour_da_hoan_thanh,
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'đã cọc') as tour_da_coc,
                        (SELECT COUNT(*) FROM phieu_dat_tour WHERE trang_thai = 'chờ xác nhận') as tour_cho_xac_nhan,
                        (SELECT COUNT(DISTINCT kh.id) 
                         FROM khach_hang kh 
                         INNER JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id 
                         WHERE pdt.trang_thai != 'hủy') as khach_hang_co_tour,
                        (SELECT COUNT(*) FROM khach_hang WHERE DATE(created_at) = CURDATE()) as khach_moi_hom_nay";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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
                        kh.created_at,
                        pdt.ma_dat_tour,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        hdv.ho_ten as ten_huong_dan_vien,
                        pdt.trang_thai as trang_thai_dat_tour
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id AND pdt.trang_thai != 'hủy'
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
                        pdt.ma_dat_tour,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        hdv.ho_ten as ten_huong_dan_vien,
                        pdt.trang_thai as trang_thai_dat_tour
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id AND pdt.trang_thai != 'hủy'
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE kh.ho_ten LIKE :tu_khoa 
                       OR kh.so_dien_thoai LIKE :tu_khoa 
                       OR kh.email LIKE :tu_khoa
                       OR t.ten_tour LIKE :tu_khoa
                    ORDER BY kh.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['tu_khoa' => "%$tu_khoa%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy chi tiết khách hàng
    public function getChiTietKhachHang($id)
    {
        try {
            $query = "SELECT 
                        kh.*,
                        pdt.ma_dat_tour,
                        pdt.so_luong_khach,
                        pdt.tong_tien,
                        pdt.trang_thai as trang_thai_dat,
                        pdt.loai_khach,
                        t.ten_tour,
                        t.ma_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        lkh.diem_tap_trung,
                        hdv.ho_ten as ten_huong_dan_vien,
                        hdv.so_dien_thoai as sdt_huong_dan_vien
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id AND pdt.trang_thai != 'hủy'
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE kh.id = :id
                    LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Lấy thống kê chi tiết
    public function getThongKeChiTiet()
    {
        try {
            $query = "SELECT 
                        MONTH(created_at) as thang,
                        YEAR(created_at) as nam,
                        COUNT(*) as so_luong,
                        COUNT(DISTINCT CASE WHEN id IN (
                            SELECT khach_hang_id FROM phieu_dat_tour WHERE trang_thai != 'hủy'
                        ) THEN id END) as khach_co_tour
                    FROM khach_hang 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                    GROUP BY YEAR(created_at), MONTH(created_at)
                    ORDER BY nam DESC, thang DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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
                        t.ten_tour as tour_dang_tham_gia,
                        lkh.ngay_bat_dau,
                        hdv.ho_ten as huong_dan_vien,
                        pdt.trang_thai
                    FROM khach_hang kh
                    LEFT JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id AND pdt.trang_thai != 'hủy'
                    LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    LEFT JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    ORDER BY kh.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getThanhVienTourHienTai($ma_dat_tour)
    {
        try {
            $query = "SELECT 
                    tv.*,
                    kh.ho_ten as ten_khach_hang_dat
                FROM thanh_vien_dat_tour tv
                INNER JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id
                INNER JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                WHERE pdt.ma_dat_tour = :ma_dat_tour
                ORDER BY tv.created_at";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['ma_dat_tour' => $ma_dat_tour]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Cập nhật method getLichSuDatTour để lấy số thành viên
    public function getLichSuDatTour($khach_hang_id)
    {
        try {
            $query = "SELECT 
                    pdt.ma_dat_tour,
                    t.ten_tour,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc,
                    pdt.tong_tien,
                    pdt.trang_thai,
                    pdt.created_at as ngay_dat,
                    (SELECT COUNT(*) FROM thanh_vien_dat_tour WHERE phieu_dat_tour_id = pdt.id) as so_thanh_vien
                FROM phieu_dat_tour pdt
                INNER JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                INNER JOIN tour t ON lkh.tour_id = t.id
                WHERE pdt.khach_hang_id = :khach_hang_id
                ORDER BY pdt.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['khach_hang_id' => $khach_hang_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
