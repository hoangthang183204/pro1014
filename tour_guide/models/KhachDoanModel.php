<?php
class KhachDoanModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Tự động tạo trạm mẫu nếu tour chưa có
    public function checkAndCreateTramMau($id_lich)
    {
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
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

    // Lấy danh sách các trạm
    public function getTramByLich($id_lich)
    {
        $sql = "SELECT * FROM tram_dung_chan WHERE lich_khoi_hanh_id = :id ORDER BY thu_tu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_lich]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách khách và trạng thái check-in (ĐÃ CẬP NHẬT LOGIC KIỂM TRA VẮNG)
    public function getKhachDoanByLich($id_lich_khoi_hanh, $tram_id = null)
    {
        try {
            $tram_val = $tram_id ? $tram_id : 0;

            $sql = "SELECT 
                    kh.id AS id,
                    kh.ho_ten AS ten_khach,
                    kh.gioi_tinh,
                    kh.ngay_sinh,
                    kh.ghi_chu,
                    kh.so_dien_thoai AS sdt_lien_he,
                    pdt.ma_dat_tour,
                    
                    -- Lấy trạng thái check-in tại trạm hiện tại
                    COALESCE(ck.trang_thai, 'chưa đến') as trang_thai_checkin,

                    -- [SỬA] Kiểm tra đã xác nhận ở BẤT KỲ trạm nào chưa
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM checkin_khach_hang ck2 
                            WHERE ck2.khach_hang_id = kh.id 
                            AND ck2.lich_khoi_hanh_id = :id
                            AND ck2.yeu_cau_dac_biet_confirmed = 1
                        ) THEN 1
                        ELSE 0
                    END as yeu_cau_confirmed,

                    -- Kiểm tra xem khách đã vắng ở trạm trước chưa
                    (
                        SELECT COUNT(*)
                        FROM checkin_khach_hang ck_cu
                        JOIN tram_dung_chan tdc_cu ON ck_cu.tram_id = tdc_cu.id
                        WHERE ck_cu.khach_hang_id = kh.id 
                        AND ck_cu.trang_thai = 'vắng mặt'
                        AND tdc_cu.thu_tu < (SELECT thu_tu FROM tram_dung_chan WHERE id = :tram_id_sub LIMIT 1)
                    ) as da_huy_truoc_do,

                    booker.ho_ten AS nguoi_dat,
                    t.ten_tour,
                    lkh.ngay_bat_dau

                FROM khach_hang kh
                JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
                LEFT JOIN khach_hang booker ON pdt.khach_hang_id = booker.id
                JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                
                LEFT JOIN checkin_khach_hang ck 
                    ON kh.id = ck.khach_hang_id 
                    AND ck.tram_id = :tram_id
                
                WHERE pdt.lich_khoi_hanh_id = :id 
                ORDER BY pdt.ma_dat_tour ASC, kh.ho_ten ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich_khoi_hanh);
            $stmt->bindParam(':tram_id', $tram_val);
            $stmt->bindParam(':tram_id_sub', $tram_val);

            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Lỗi getKhachDoanByLich: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật check-in
    public function updateCheckIn($id_khach_hang, $status, $tram_id)
    {
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

    public function getToursByGuide($guide_id)
    {
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
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTourInfoById($id_lich)
    {
        try {
            $sql = "SELECT t.ten_tour, lkh.ngay_bat_dau FROM lich_khoi_hanh lkh JOIN tour t ON lkh.tour_id = t.id WHERE lkh.id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_lich);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return [];
        }
    }




    public function getYeuCauDacBiet($khach_hang_id, $tram_id)
    {
        try {
            $sql = "SELECT ghi_chu, yeu_cau_dac_biet_confirmed 
                    FROM checkin_khach_hang 
                    WHERE khach_hang_id = :khach_id AND tram_id = :tram_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':khach_id' => $khach_hang_id,
                ':tram_id' => $tram_id
            ]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    // === THÊM MỚI: Phương thức confirm yêu cầu đặc biệt ===
    public function confirmYeuCauDacBiet($khach_hang_id)
    {
        try {
            // Debug: log thông tin đầu vào
            error_log("DEBUG confirmYeuCauDacBiet: khach_hang_id = " . $khach_hang_id);

            // 1. Kiểm tra khách hàng có tồn tại không
            $sqlCheckKhach = "SELECT id FROM khach_hang WHERE id = :khach_id";
            $stmtCheck = $this->conn->prepare($sqlCheckKhach);
            $stmtCheck->execute([':khach_id' => $khach_hang_id]);
            $khachExists = $stmtCheck->fetch();

            if (!$khachExists) {
                error_log("ERROR: Khách hàng ID $khach_hang_id không tồn tại");
                return false;
            }

            // 2. Lấy lich_khoi_hanh_id từ khách hàng
            $sqlInfo = "SELECT pdt.lich_khoi_hanh_id 
                   FROM khach_hang kh 
                   JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id 
                   WHERE kh.id = :khach_id";
            $stmtInfo = $this->conn->prepare($sqlInfo);
            $stmtInfo->execute([':khach_id' => $khach_hang_id]);
            $info = $stmtInfo->fetch();

            if (!$info) {
                error_log("ERROR: Không tìm thấy thông tin tour của khách ID: " . $khach_hang_id);
                return false;
            }

            $lich_id = $info['lich_khoi_hanh_id'];
            error_log("DEBUG: lich_khoi_hanh_id = " . $lich_id);

            // 3. Kiểm tra xem đã có bản ghi checkin nào chưa
            $sqlCheckExist = "SELECT COUNT(*) as count 
                         FROM checkin_khach_hang 
                         WHERE khach_hang_id = :khach_id 
                         AND lich_khoi_hanh_id = :lich_id";
            $stmtCheckExist = $this->conn->prepare($sqlCheckExist);
            $stmtCheckExist->execute([
                ':khach_id' => $khach_hang_id,
                ':lich_id' => $lich_id
            ]);
            $countResult = $stmtCheckExist->fetch();
            $hasRecords = $countResult['count'] > 0;

            error_log("DEBUG: Đã có $countResult[count] bản ghi checkin");

            if ($hasRecords) {
                // 4A. Cập nhật TẤT CẢ các bản ghi checkin đã tồn tại
                $sql = "UPDATE checkin_khach_hang 
                    SET yeu_cau_dac_biet_confirmed = 1 
                    WHERE khach_hang_id = :khach_id 
                    AND lich_khoi_hanh_id = :lich_id";

                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    ':khach_id' => $khach_hang_id,
                    ':lich_id' => $lich_id
                ]);

                $rowCount = $stmt->rowCount();
                error_log("DEBUG: UPDATE thành công? " . ($result ? 'YES' : 'NO') . ", Số dòng ảnh hưởng: " . $rowCount);

                return $result;
            } else {
                // 4B. Tạo bản ghi checkin mới với confirmed = 1
                error_log("DEBUG: Chưa có bản ghi checkin, tạo mới...");

                // Lấy trạm đầu tiên
                $sqlTram = "SELECT id FROM tram_dung_chan 
                       WHERE lich_khoi_hanh_id = :lich_id 
                       ORDER BY thu_tu ASC LIMIT 1";
                $stmtTram = $this->conn->prepare($sqlTram);
                $stmtTram->execute([':lich_id' => $lich_id]);
                $tram = $stmtTram->fetch();

                if (!$tram) {
                    error_log("ERROR: Không tìm thấy trạm nào cho lịch ID: " . $lich_id);
                    return false;
                }

                error_log("DEBUG: Tạo bản ghi checkin tại trạm ID: " . $tram['id']);

                $sqlInsert = "INSERT INTO checkin_khach_hang 
                         (lich_khoi_hanh_id, tram_id, khach_hang_id, 
                          trang_thai, yeu_cau_dac_biet_confirmed, thoi_gian_checkin, dia_diem_checkin) 
                         VALUES (:lich_id, :tram_id, :khach_id, 
                                 'chưa đến', 1, NOW(), 'Check-in yêu cầu đặc biệt')";

                $stmtInsert = $this->conn->prepare($sqlInsert);
                $result = $stmtInsert->execute([
                    ':lich_id' => $lich_id,
                    ':tram_id' => $tram['id'],
                    ':khach_id' => $khach_hang_id
                ]);

                error_log("DEBUG: INSERT thành công? " . ($result ? 'YES' : 'NO'));
                return $result;
            }
        } catch (PDOException $e) {
            error_log("ERROR confirmYeuCauDacBiet: " . $e->getMessage());
            error_log("ERROR Code: " . $e->getCode());
            return false;
        }
    }
    // Hàm mới: Cập nhật hàng loạt
    public function updateCheckInBulk($list_ids, $status, $tram_id) {
        try {
            $this->conn->beginTransaction(); // Dùng transaction để đảm bảo an toàn dữ liệu
            $successCount = 0;
            
            foreach ($list_ids as $id_khach) {
                // Gọi lại hàm updateCheckIn có sẵn để tái sử dụng logic
                if ($this->updateCheckIn($id_khach, $status, $tram_id)) {
                    $successCount++;
                }
            }
            
            $this->conn->commit();
            return $successCount;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}


    

?>
