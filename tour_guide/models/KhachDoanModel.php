<?php
class KhachDoanModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getKhachDoanByLich($id_lich_khoi_hanh) {
        try {
            // Logic: Lấy từ bảng thanh_vien (người đi) JOIN sang khach_hang (để lấy sđt liên hệ của người đặt)
            $sql = "SELECT 
                        tv.id AS id,
                        tv.ho_ten AS ten_khach,
                        tv.gioi_tinh,
                        tv.ngay_sinh,
                        tv.yeu_cau_dac_biet,
                        
                        -- Thông tin người đại diện đặt tour (để gọi khi cần)
                        kh.ho_ten AS nguoi_dat,
                        kh.so_dien_thoai AS sdt_lien_he,
                        
                        -- Thông tin nhóm/đoàn
                        pdt.ma_dat_tour,
                        pdt.ten_doan,
                        pdt.loai_khach,
                        
                        t.ten_tour,
                        lkh.ngay_bat_dau

                    FROM thanh_vien_dat_tour tv
                    JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id
                    JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                    JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    
                    WHERE pdt.lich_khoi_hanh_id = :id 
                    AND pdt.trang_thai IN ('đã cọc', 'hoàn tất')
                    ORDER BY pdt.ma_dat_tour ASC, tv.ho_ten ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich_khoi_hanh);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    // THÊM HÀM NÀY: Lấy các tour mà HDV này được phân công
    public function getToursByGuide($guide_id) {
        try {
            $sql = "SELECT 
                        lkh.id,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        lkh.trang_thai
                    FROM phan_cong pc
                    JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE hdv.nguoi_dung_id = :guide_id 
                    ORDER BY lkh.ngay_bat_dau DESC"; // Tour mới nhất lên đầu

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guide_id);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    // Thêm vào class KhachDoanModel
public function getTourInfoById($id_lich) {
    try {
        $sql = "SELECT t.ten_tour, lkh.ngay_bat_dau 
                FROM lich_khoi_hanh lkh
                JOIN tour t ON lkh.tour_id = t.id
                WHERE lkh.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id_lich);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        return [];
    }
}
}
?>