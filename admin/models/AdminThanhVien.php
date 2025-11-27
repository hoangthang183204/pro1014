<?php
class AdminThanhVienTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy thống kê thành viên tour
    public function getThongKeThanhVien()
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM thanh_vien_dat_tour) as tong_thanh_vien,
                        (SELECT COUNT(*) FROM thanh_vien_dat_tour WHERE yeu_cau_dac_biet IS NOT NULL AND yeu_cau_dac_biet != '') as co_yeu_cau_dac_biet,
                        (SELECT COUNT(*) FROM thanh_vien_dat_tour WHERE da_xu_ly_yeu_cau = 1) as da_xu_ly_yeu_cau,
                        (SELECT COUNT(*) FROM thanh_vien_dat_tour WHERE gioi_tinh = 'nam') as thanh_vien_nam,
                        (SELECT COUNT(*) FROM thanh_vien_dat_tour WHERE gioi_tinh = 'nữ') as thanh_vien_nu,
                        (SELECT COUNT(DISTINCT phieu_dat_tour_id) FROM thanh_vien_dat_tour) as so_phieu_dat_tour";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy danh sách thành viên tour
    public function getDanhSachThanhVien()
    {
        try {
            $query = "SELECT 
                        tv.*,
                        pdt.ma_dat_tour,
                        kh.ho_ten as ten_khach_hang,
                        kh.so_dien_thoai,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc
                    FROM thanh_vien_dat_tour tv
                    INNER JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id
                    INNER JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                    INNER JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    INNER JOIN tour t ON lkh.tour_id = t.id
                    WHERE pdt.trang_thai != 'hủy'
                    ORDER BY tv.created_at DESC
                    LIMIT 100";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Tìm kiếm thành viên
    public function timKiemThanhVien($tu_khoa)
    {
        try {
            $query = "SELECT 
                        tv.*,
                        pdt.ma_dat_tour,
                        kh.ho_ten as ten_khach_hang,
                        kh.so_dien_thoai,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc
                    FROM thanh_vien_dat_tour tv
                    INNER JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id
                    INNER JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                    INNER JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    INNER JOIN tour t ON lkh.tour_id = t.id
                    WHERE (tv.ho_ten LIKE :tu_khoa 
                           OR tv.cccd LIKE :tu_khoa
                           OR kh.ho_ten LIKE :tu_khoa
                           OR t.ten_tour LIKE :tu_khoa)
                    AND pdt.trang_thai != 'hủy'
                    ORDER BY tv.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['tu_khoa' => "%$tu_khoa%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy chi tiết thành viên
    public function getChiTietThanhVien($id)
    {
        try {
            $query = "SELECT 
                        tv.*,
                        pdt.ma_dat_tour,
                        pdt.tong_tien,
                        pdt.trang_thai as trang_thai_dat_tour,
                        kh.ho_ten as ten_khach_hang,
                        kh.so_dien_thoai as sdt_khach_hang,
                        kh.email as email_khach_hang,
                        t.ten_tour,
                        t.ma_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        lkh.diem_tap_trung,
                        hdv.ho_ten as ten_huong_dan_vien
                    FROM thanh_vien_dat_tour tv
                    INNER JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id
                    INNER JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                    INNER JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    INNER JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                    LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE tv.id = :id
                    LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Cập nhật thông tin thành viên
    public function capNhatThanhVien($id, $data)
    {
        try {
            $query = "UPDATE thanh_vien_dat_tour 
                      SET ho_ten = :ho_ten, 
                          cccd = :cccd, 
                          ngay_sinh = :ngay_sinh, 
                          gioi_tinh = :gioi_tinh,
                          yeu_cau_dac_biet = :yeu_cau_dac_biet
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'ho_ten' => $data['ho_ten'],
                'cccd' => $data['cccd'],
                'ngay_sinh' => $data['ngay_sinh'],
                'gioi_tinh' => $data['gioi_tinh'],
                'yeu_cau_dac_biet' => $data['yeu_cau_dac_biet'],
                'id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Xử lý yêu cầu đặc biệt
    public function xuLyYeuCauDacBiet($id, $ghi_chu_xu_ly)
    {
        try {
            $query = "UPDATE thanh_vien_dat_tour 
                      SET da_xu_ly_yeu_cau = 1, 
                          ghi_chu_xu_ly = :ghi_chu_xu_ly
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'ghi_chu_xu_ly' => $ghi_chu_xu_ly,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy danh sách thành viên theo phiếu đặt tour
    public function getThanhVienTheoPhieuDatTour($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT * FROM thanh_vien_dat_tour 
                      WHERE phieu_dat_tour_id = :phieu_dat_tour_id
                      ORDER BY created_at";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>