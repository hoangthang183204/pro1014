<?php
class KhachDoanModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Tự động tạo trạm mẫu nếu tour chưa có (để HDV luôn có trạm để chọn)
    public function checkAndCreateTramMau($id_lich) {
        $sqlCheck = "SELECT COUNT(*) FROM tram_dung_chan WHERE lich_khoi_hanh_id = :id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $id_lich]);
        if ($stmtCheck->fetchColumn() == 0) {
            try {
                $sql1 = "INSERT INTO tram_dung_chan (lich_khoi_hanh_id, ten_tram, thu_tu) VALUES (:id, 'Trạm 1: Điểm tập trung/Đón khách', 1)";
                $this->conn->prepare($sql1)->execute([':id' => $id_lich]);

                $sql2 = "INSERT INTO tram_dung_chan (lich_khoi_hanh_id, ten_tram, thu_tu) VALUES (:id, 'Trạm 2: Điểm kết thúc tour', 2)";
                $this->conn->prepare($sql2)->execute([':id' => $id_lich]);
                return true;
            } catch (PDOException $e) { return false; }
        }
        return false;
    }

    // Lấy danh sách các trạm của 1 tour
    public function getTramByLich($id_lich) {
        $sql = "SELECT * FROM tram_dung_chan WHERE lich_khoi_hanh_id = :id ORDER BY thu_tu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_lich]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách khách và trạng thái check-in TẠI TRẠM ĐANG CHỌN
    public function getKhachDoanByLich($id_lich_khoi_hanh, $tram_id = null) {
        try {
            $sql = "SELECT 
                        kh.id AS id,
                        kh.ho_ten AS ten_khach,
                        kh.gioi_tinh,
                        kh.ngay_sinh,
                        kh.ghi_chu,
                        kh.so_dien_thoai AS sdt_lien_he,
                        pdt.ma_dat_tour,
                        
                        -- Lấy trạng thái check-in chỉ của trạm này
                        COALESCE(ck.trang_thai, 'chưa đến') as trang_thai_checkin,

                        booker.ho_ten AS nguoi_dat,
                        t.ten_tour,
                        lkh.ngay_bat_dau

                    FROM khach_hang kh
                    JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN khach_hang booker ON pdt.khach_hang_id = booker.id
                    JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    
                    -- JOIN bảng checkin với điều kiện tram_id
                    LEFT JOIN checkin_khach_hang ck 
                        ON kh.id = ck.thanh_vien_dat_tour_id 
                        AND ck.tram_id = :tram_id
                    
                    WHERE pdt.lich_khoi_hanh_id = :id 
                    ORDER BY pdt.ma_dat_tour ASC, kh.ho_ten ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich_khoi_hanh);
            
            // Nếu không có trạm nào được chọn, coi như tram_id = 0
            $tram_val = $tram_id ? $tram_id : 0;
            $stmt->bindParam(':tram_id', $tram_val);
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }

    // Cập nhật check-in (Lưu cả tram_id)
    public function updateCheckIn($id_thanh_vien, $status, $tram_id) {
        try {
            // Lấy ID lịch từ khách hàng
            $sqlInfo = "SELECT pdt.lich_khoi_hanh_id FROM khach_hang kh 
                        JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id 
                        WHERE kh.id = :id";
            $stmtInfo = $this->conn->prepare($sqlInfo);
            $stmtInfo->execute([':id' => $id_thanh_vien]);
            $info = $stmtInfo->fetch();
            if (!$info) return false;
            $id_lich = $info['lich_khoi_hanh_id'];

            // Kiểm tra đã có bản ghi cho khách này tại trạm này chưa
            $sqlCheck = "SELECT id FROM checkin_khach_hang WHERE thanh_vien_dat_tour_id = :id AND tram_id = :tram_id";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id_thanh_vien, ':tram_id' => $tram_id]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                // UPDATE
                $sql = "UPDATE checkin_khach_hang SET trang_thai = :status, thoi_gian_checkin = NOW() 
                        WHERE thanh_vien_dat_tour_id = :id AND tram_id = :tram_id";
            } else {
                // INSERT
                $sql = "INSERT INTO checkin_khach_hang 
                        (lich_khoi_hanh_id, tram_id, thanh_vien_dat_tour_id, trang_thai, thoi_gian_checkin, dia_diem_checkin) 
                        VALUES (:lich_id, :tram_id, :id, :status, NOW(), 'Check-in tại trạm')";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id_thanh_vien);
            $stmt->bindParam(':tram_id', $tram_id);
            if (!$existing) $stmt->bindParam(':lich_id', $id_lich);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Các hàm cũ giữ nguyên
    public function getToursByGuide($guide_id) {
        try {
            $sql = "SELECT lkh.id, t.ten_tour, lkh.ngay_bat_dau, lkh.trang_thai
                    FROM phan_cong pc
                    JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE hdv.nguoi_dung_id = :guide_id ORDER BY lkh.ngay_bat_dau DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guide_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) { return []; }
    }

    public function getTourInfoById($id_lich) {
        try {
            $sql = "SELECT t.ten_tour, lkh.ngay_bat_dau FROM lich_khoi_hanh lkh JOIN tour t ON lkh.tour_id = t.id WHERE lkh.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) { return []; }
    }
}
?>