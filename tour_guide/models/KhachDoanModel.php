<?php
class KhachDoanModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getKhachDoanByLich($id_lich_khoi_hanh) {
        try {
            // SỬA: JOIN thêm bảng checkin_khach_hang
            $sql = "SELECT 
                        tv.id AS id,
                        tv.ho_ten AS ten_khach,
                        tv.gioi_tinh,
                        tv.ngay_sinh,
                        tv.yeu_cau_dac_biet,
                        
                        -- Lấy trạng thái check-in (Nếu chưa có thì mặc định là 'chưa đến')
                        COALESCE(ck.trang_thai, 'chưa đến') as trang_thai_checkin,

                        kh.ho_ten AS nguoi_dat,
                        kh.so_dien_thoai AS sdt_lien_he,
                        
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
                    
                    -- KẾT HỢP BẢNG CHECK-IN
                    LEFT JOIN checkin_khach_hang ck ON tv.id = ck.thanh_vien_dat_tour_id
                    
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

    public function getToursByGuide($guide_id) {
        try {
            $sql = "SELECT lkh.id, t.ten_tour, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.trang_thai
                    FROM phan_cong pc
                    JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                    WHERE hdv.nguoi_dung_id = :guide_id 
                    ORDER BY lkh.ngay_bat_dau DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guide_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

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

    // THÊM: Hàm xử lý logic Check-in (Thông minh: Chưa có thì Thêm, Có rồi thì Sửa)
    public function updateCheckIn($id_thanh_vien, $status) {
        try {
            // B1: Lấy ID lịch khởi hành từ thành viên (cần thiết để Insert)
            $sqlInfo = "SELECT pdt.lich_khoi_hanh_id 
                        FROM thanh_vien_dat_tour tv 
                        JOIN phieu_dat_tour pdt ON tv.phieu_dat_tour_id = pdt.id 
                        WHERE tv.id = :id";
            $stmtInfo = $this->conn->prepare($sqlInfo);
            $stmtInfo->execute([':id' => $id_thanh_vien]);
            $info = $stmtInfo->fetch();
            
            if (!$info) return false;
            $id_lich = $info['lich_khoi_hanh_id'];

            // B2: Kiểm tra xem đã có bản ghi trong bảng checkin chưa
            $sqlCheck = "SELECT id FROM checkin_khach_hang WHERE thanh_vien_dat_tour_id = :id";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id_thanh_vien]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                // UPDATE nếu đã tồn tại
                $sql = "UPDATE checkin_khach_hang 
                        SET trang_thai = :status, 
                            thoi_gian_checkin = NOW() 
                        WHERE thanh_vien_dat_tour_id = :id";
            } else {
                // INSERT nếu chưa tồn tại
                $sql = "INSERT INTO checkin_khach_hang 
                        (lich_khoi_hanh_id, thanh_vien_dat_tour_id, trang_thai, thoi_gian_checkin, dia_diem_checkin) 
                        VALUES (:lich_id, :id, :status, NOW(), 'Check-in nhanh')";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id_thanh_vien);
            if (!$existing) {
                $stmt->bindParam(':lich_id', $id_lich);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>