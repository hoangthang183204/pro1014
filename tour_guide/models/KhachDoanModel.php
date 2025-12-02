<?php
class KhachDoanModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Tự động tạo trạm mẫu nếu tour chưa có
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

    // Lấy danh sách các trạm
    public function getTramByLich($id_lich) {
        $sql = "SELECT * FROM tram_dung_chan WHERE lich_khoi_hanh_id = :id ORDER BY thu_tu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_lich]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách khách và trạng thái check-in
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
                        
                        -- Lấy trạng thái check-in
                        COALESCE(ck.trang_thai, 'chưa đến') as trang_thai_checkin,

                        booker.ho_ten AS nguoi_dat,
                        t.ten_tour,
                        lkh.ngay_bat_dau

                    FROM khach_hang kh
                    JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                    LEFT JOIN khach_hang booker ON pdt.khach_hang_id = booker.id
                    JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    
                    -- [ĐÃ SỬA] Dùng khach_hang_id thay cho thanh_vien_dat_tour_id
                    LEFT JOIN checkin_khach_hang ck 
                        ON kh.id = ck.khach_hang_id 
                        AND ck.tram_id = :tram_id
                    
                    WHERE pdt.lich_khoi_hanh_id = :id 
                    ORDER BY pdt.ma_dat_tour ASC, kh.ho_ten ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich_khoi_hanh);
            
            $tram_val = $tram_id ? $tram_id : 0;
            $stmt->bindParam(':tram_id', $tram_val);
            
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }

    // Cập nhật check-in
    public function updateCheckIn($id_khach_hang, $status, $tram_id) {
        try {
            // Lấy ID lịch từ khách hàng (để lưu vào checkin nếu là insert mới)
            $sqlInfo = "SELECT pdt.lich_khoi_hanh_id FROM khach_hang kh 
                        JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id 
                        WHERE kh.id = :id";
            $stmtInfo = $this->conn->prepare($sqlInfo);
            $stmtInfo->execute([':id' => $id_khach_hang]);
            $info = $stmtInfo->fetch();
            if (!$info) return false;
            $id_lich = $info['lich_khoi_hanh_id'];

            // [ĐÃ SỬA] Kiểm tra tồn tại bằng khach_hang_id
            $sqlCheck = "SELECT id FROM checkin_khach_hang WHERE khach_hang_id = :id AND tram_id = :tram_id";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id_khach_hang, ':tram_id' => $tram_id]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                // UPDATE
                $sql = "UPDATE checkin_khach_hang SET trang_thai = :status, thoi_gian_checkin = NOW() 
                        WHERE khach_hang_id = :id AND tram_id = :tram_id";
            } else {
                // INSERT: [ĐÃ SỬA] Insert vào cột khach_hang_id
                $sql = "INSERT INTO checkin_khach_hang 
                        (lich_khoi_hanh_id, tram_id, khach_hang_id, trang_thai, thoi_gian_checkin, dia_diem_checkin) 
                        VALUES (:lich_id, :tram_id, :id, :status, NOW(), 'Check-in tại trạm')";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id_khach_hang);
            $stmt->bindParam(':tram_id', $tram_id);
            if (!$existing) $stmt->bindParam(':lich_id', $id_lich);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

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