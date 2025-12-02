<?php
class AdminTour
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour với filter
    public function getAllTours($search = '', $trang_thai = '', $danh_muc_id = '')
    {
        try {
            // XÓA JOIN VÀO chinh_sach_tour
            $query = "SELECT t.*, dm.ten_danh_muc,
                             COUNT(DISTINCT lt.id) as so_lich_trinh,
                             COUNT(DISTINCT pb.id) as so_phien_ban,
                             COUNT(DISTINCT mt.id) as so_media
                      FROM tour t 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
                      LEFT JOIN lich_trinh_tour lt ON t.id = lt.tour_id
                      LEFT JOIN phien_ban_tour pb ON t.id = pb.tour_id
                      LEFT JOIN media_tour mt ON t.id = mt.tour_id
                      WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (t.ma_tour LIKE :search OR t.ten_tour LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($trang_thai)) {
                $query .= " AND t.trang_thai = :trang_thai";
                $params[':trang_thai'] = $trang_thai;
            }

            if (!empty($danh_muc_id)) {
                $query .= " AND t.danh_muc_id = :danh_muc_id";
                $params[':danh_muc_id'] = $danh_muc_id;
            }

            $query .= " GROUP BY t.id ORDER BY t.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllTours: " . $e->getMessage());
            return [];
        }
    }


    // Tạo tour mới
    public function createTour($data)
    {
        try {
            $this->conn->beginTransaction();

            // Insert tour (BỎ chinh_sach_id)
            $query = "INSERT INTO tour (ma_tour, ten_tour, danh_muc_id, mo_ta, gia_tour, hinh_anh, duong_dan_online, trang_thai) 
                      VALUES (:ma_tour, :ten_tour, :danh_muc_id, :mo_ta, :gia_tour, :hinh_anh, :duong_dan_online, 'đang hoạt động')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':danh_muc_id' => $data['danh_muc_id'],
                ':mo_ta' => $data['mo_ta'],
                ':gia_tour' => $data['gia_tour'],
                ':hinh_anh' => $data['hinh_anh'] ?? null,
                ':duong_dan_online' => $data['duong_dan_online']
            ]);

            $tour_id = $this->conn->lastInsertId();

            $this->conn->commit();
            return $tour_id;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi createTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật tour
    public function updateTour($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Build update query (BỎ chinh_sach_id)
            $query = "UPDATE tour 
                      SET ma_tour = :ma_tour, ten_tour = :ten_tour, danh_muc_id = :danh_muc_id, 
                          mo_ta = :mo_ta, gia_tour = :gia_tour, 
                          trang_thai = :trang_thai, duong_dan_online = :duong_dan_online, 
                          updated_at = NOW()";

            // Thêm hình ảnh nếu có
            if (isset($data['hinh_anh'])) {
                $query .= ", hinh_anh = :hinh_anh";
            }

            $query .= " WHERE id = :id";

            $params = [
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':danh_muc_id' => $data['danh_muc_id'],
                ':mo_ta' => $data['mo_ta'],
                ':gia_tour' => $data['gia_tour'],
                ':trang_thai' => $data['trang_thai'],
                ':duong_dan_online' => $data['duong_dan_online'],
                ':id' => $id
            ];

            if (isset($data['hinh_anh'])) {
                $params[':hinh_anh'] = $data['hinh_anh'];
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi updateTour: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tour
    public function deleteTour($id)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa media
            $this->deleteAllMediaByTour($id);

            // Xóa lịch trình
            $this->deleteAllLichTrinhByTour($id);

            // Xóa phiên bản
            $this->deleteAllPhienBanByTour($id);

            // Xóa tour
            $query = "DELETE FROM tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi deleteTour: " . $e->getMessage());
            return false;
        }
    }

    // Thêm các phương thức sau vào TourModel.php

    // Lấy lịch trình theo phiên bản (nếu có lưu riêng)
    public function getLichTrinhByPhienBan($phien_ban_id)
    {
        try {
            // Nếu bạn có bảng lưu lịch trình riêng cho phiên bản
            // Nếu không, lấy từ lịch trình tour hiện tại
            $query = "SELECT ltt.* FROM lich_trinh_tour ltt
                  JOIN phien_ban_tour pbt ON ltt.tour_id = pbt.tour_id
                  WHERE pbt.id = :phien_ban_id
                  ORDER BY ltt.thu_tu_sap_xep, ltt.so_ngay";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':phien_ban_id' => $phien_ban_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichTrinhByPhienBan: " . $e->getMessage());
            return [];
        }
    }

    // Xóa tất cả phiên bản theo tour (dùng khi xóa tour)
    public function deleteAllPhienBanByTour($tour_id)
    {
        try {
            $query = "DELETE FROM phien_ban_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllPhienBanByTour: " . $e->getMessage());
            return false;
        }
    }

    // Lấy phiên bản đang áp dụng cho một lịch khởi hành cụ thể
    public function getPhienBanChoNgay($tour_id, $ngay)
    {
        try {
            $query = "SELECT * FROM phien_ban_tour 
                  WHERE tour_id = :tour_id 
                  AND :ngay BETWEEN thoi_gian_bat_dau AND thoi_gian_ket_thuc
                  AND loai_phien_ban IN ('khuyen_mai', 'dac_biet')
                  ORDER BY 
                    CASE loai_phien_ban 
                        WHEN 'dac_biet' THEN 1
                        WHEN 'khuyen_mai' THEN 2
                        ELSE 3
                    END
                  LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id, ':ngay' => $ngay]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanChoNgay: " . $e->getMessage());
            return null;
        }
    }
    // Lấy lịch trình theo tour
    public function getLichTrinhByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour 
                  WHERE tour_id = :tour_id 
                  ORDER BY thu_tu_sap_xep, so_ngay";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichTrinhByTour: " . $e->getMessage());
            return [];
        }
    }



    // Sao chép lịch trình vào phiên bản (nếu bạn muốn lưu riêng)
    public function copyLichTrinhToPhienBan($phien_ban_id, $tour_id)
    {
        try {
            // Tạo bảng lưu lịch trình phiên bản nếu chưa có
            // Giả sử có bảng lich_trinh_phien_ban với cấu trúc tương tự lich_trinh_tour
            $check_table = "SHOW TABLES LIKE 'lich_trinh_phien_ban'";
            $stmt = $this->conn->query($check_table);

            if (!$stmt->fetch()) {
                // Tạo bảng mới
                $create_table = "CREATE TABLE lich_trinh_phien_ban (
                id INT AUTO_INCREMENT PRIMARY KEY,
                phien_ban_id INT NOT NULL,
                so_ngay INT NOT NULL,
                tieu_de VARCHAR(255),
                mo_ta_hoat_dong TEXT,
                cho_o TEXT,
                bua_an TEXT,
                phuong_tien TEXT,
                ghi_chu_hdv TEXT,
                thu_tu_sap_xep INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (phien_ban_id) REFERENCES phien_ban_tour(id) ON DELETE CASCADE
            )";

                $this->conn->exec($create_table);
            }

            // Sao chép dữ liệu từ lich_trinh_tour
            $copy_query = "INSERT INTO lich_trinh_phien_ban 
                       (phien_ban_id, so_ngay, tieu_de, mo_ta_hoat_dong, 
                        cho_o, bua_an, phuong_tien, ghi_chu_hdv, thu_tu_sap_xep)
                       SELECT :phien_ban_id, so_ngay, tieu_de, mo_ta_hoat_dong,
                              cho_o, bua_an, phuong_tien, ghi_chu_hdv, thu_tu_sap_xep
                       FROM lich_trinh_tour 
                       WHERE tour_id = :tour_id
                       ORDER BY thu_tu_sap_xep, so_ngay";

            $stmt = $this->conn->prepare($copy_query);
            $stmt->execute([
                ':phien_ban_id' => $phien_ban_id,
                ':tour_id' => $tour_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi copyLichTrinhToPhienBan: " . $e->getMessage());
            return false;
        }
    }

    // ==================== UTILITY METHODS ====================

    // Lấy tất cả danh mục
    public function getAllDanhMuc()
    {
        try {
            $query = "SELECT * FROM danh_muc_tour WHERE trang_thai = 'hoạt động' ORDER BY ten_danh_muc";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllDanhMuc: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tất cả điểm đến
    public function getAllDiemDen()
    {
        try {
            $query = "SELECT * FROM diem_den ORDER BY ten_diem_den";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllDiemDen: " . $e->getMessage());
            return [];
        }
    }

    // ==================== LỊCH TRÌNH METHODS ====================

    // Lấy lịch trình theo ID
    public function getLichTrinhById($id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichTrinhById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo lịch trình mới
    public function createLichTrinh($data)
    {
        try {
            $query = "INSERT INTO lich_trinh_tour (tour_id, so_ngay, tieu_de, mo_ta_hoat_dong, 
                      cho_o, bua_an, phuong_tien, ghi_chu_hdv, thu_tu_sap_xep) 
                      VALUES (:tour_id, :so_ngay, :tieu_de, :mo_ta_hoat_dong, 
                      :cho_o, :bua_an, :phuong_tien, :ghi_chu_hdv, :thu_tu_sap_xep)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':so_ngay' => $data['so_ngay'],
                ':tieu_de' => $data['tieu_de'],
                ':mo_ta_hoat_dong' => $data['mo_ta_hoat_dong'],
                ':cho_o' => $data['cho_o'],
                ':bua_an' => $data['bua_an'],
                ':phuong_tien' => $data['phuong_tien'],
                ':ghi_chu_hdv' => $data['ghi_chu_hdv'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật lịch trình
    public function updateLichTrinh($id, $data)
    {
        try {
            $query = "UPDATE lich_trinh_tour 
                      SET so_ngay = :so_ngay, tieu_de = :tieu_de, mo_ta_hoat_dong = :mo_ta_hoat_dong,
                          cho_o = :cho_o, bua_an = :bua_an, phuong_tien = :phuong_tien,
                          ghi_chu_hdv = :ghi_chu_hdv, thu_tu_sap_xep = :thu_tu_sap_xep,
                          updated_at = NOW()
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':so_ngay' => $data['so_ngay'],
                ':tieu_de' => $data['tieu_de'],
                ':mo_ta_hoat_dong' => $data['mo_ta_hoat_dong'],
                ':cho_o' => $data['cho_o'],
                ':bua_an' => $data['bua_an'],
                ':phuong_tien' => $data['phuong_tien'],
                ':ghi_chu_hdv' => $data['ghi_chu_hdv'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Xóa lịch trình
    public function deleteLichTrinh($id)
    {
        try {
            $query = "DELETE FROM lich_trinh_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả lịch trình của tour
    private function deleteAllLichTrinhByTour($tour_id)
    {
        try {
            $query = "DELETE FROM lich_trinh_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllLichTrinhByTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PHIÊN BẢN METHODS ====================

    // Lấy giá tour hiện tại (phương thức riêng)
    public function getGiaTourHienTai($tour_id)
    {
        try {
            $query = "SELECT gia_tour FROM tour WHERE id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? ($result['gia_tour'] ?? 0) : 0;
        } catch (PDOException $e) {
            error_log("Lỗi getGiaTourHienTai: " . $e->getMessage());
            return 0;
        }
    }
    // Lấy phiên bản theo tour
    public function getPhienBanByTour($tour_id)
    {
        try {
            $query = "SELECT pb.*, 
                  (SELECT COUNT(*) FROM phieu_dat_tour pdt 
                   JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id 
                   WHERE lkh.tour_id = pb.tour_id 
                   AND lkh.ngay_bat_dau BETWEEN pb.thoi_gian_bat_dau AND pb.thoi_gian_ket_thuc) as so_dat_tour
                  FROM phien_ban_tour pb 
                  WHERE pb.tour_id = :tour_id 
                  ORDER BY 
                    CASE pb.loai_phien_ban 
                        WHEN 'dac_biet' THEN 1
                        WHEN 'khuyen_mai' THEN 2
                        WHEN 'mua' THEN 3
                        ELSE 4
                    END,
                    pb.thoi_gian_bat_dau DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tour theo ID (đảm bảo có giá trị mặc định)
    public function getTourById($tour_id)
    {
        try {
            $query = "SELECT t.*, 
                  dm.ten_danh_muc,
                  cs.ten_chinh_sach,
                  cs.quy_dinh_huy_doi,
                  cs.luu_y_suc_khoe,
                  cs.luu_y_hanh_ly,
                  (SELECT COUNT(*) FROM lich_khoi_hanh WHERE tour_id = t.id AND trang_thai NOT IN ('đã hủy')) as so_lich_khoi_hanh
                  FROM tour t
                  LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                  LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id
                  WHERE t.id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Đảm bảo giá trị mặc định
            if ($result) {
                $result['gia_tour'] = $result['gia_tour'] ?? 0;
                $result['mo_ta'] = $result['mo_ta'] ?? '';
                $result['hinh_anh'] = $result['hinh_anh'] ?? '';
                $result['ten_danh_muc'] = $result['ten_danh_muc'] ?? 'Chưa phân loại';
                $result['so_lich_khoi_hanh'] = $result['so_lich_khoi_hanh'] ?? 0;
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getTourById: " . $e->getMessage());
            return null;
        }
    }

    // Lấy lịch khởi hành trong thời gian phiên bản
    public function getLichKhoiHanhTrongPhienBan($tour_id, $bat_dau, $ket_thuc)
    {
        try {
            $query = "SELECT lkh.*, 
                  COUNT(pdt.id) as so_dat_tour,
                  GROUP_CONCAT(DISTINCT CONCAT(kh.ho_ten, ' (', pdt.so_luong_khach, ' khách)') SEPARATOR '; ') as khach_hang_dat
                  FROM lich_khoi_hanh lkh
                  LEFT JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id AND pdt.trang_thai IN ('đã cọc', 'hoàn tất')
                  LEFT JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                  WHERE lkh.tour_id = :tour_id 
                  AND lkh.ngay_bat_dau BETWEEN :bat_dau AND :ket_thuc
                  GROUP BY lkh.id
                  ORDER BY lkh.ngay_bat_dau";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $tour_id,
                ':bat_dau' => $bat_dau,
                ':ket_thuc' => $ket_thuc
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhTrongPhienBan: " . $e->getMessage());
            return [];
        }
    }

    // Lấy duyệt toán của tour
    public function getDuToanByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM du_toan_tour 
                  WHERE tour_id = :tour_id 
                  ORDER BY created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDuToanByTour: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật giá tour
    public function updateGiaTour($tour_id, $gia_moi)
    {
        try {
            $query = "UPDATE tour 
                  SET gia_tour = :gia_tour, 
                      updated_at = NOW()
                  WHERE id = :tour_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':gia_tour' => $gia_moi,
                ':tour_id' => $tour_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateGiaTour: " . $e->getMessage());
            return false;
        }
    }

    // Xóa phiên bản
    public function deletePhienBan($id)
    {
        try {
            // Kiểm tra xem phiên bản có đang được áp dụng không
            $phien_ban = $this->getPhienBanById($id);
            if (!$phien_ban) {
                throw new Exception("Phiên bản không tồn tại!");
            }

            // Kiểm tra có lịch khởi hành nào đang sử dụng phiên bản này không
            $check_query = "SELECT COUNT(*) as count FROM lich_khoi_hanh 
                       WHERE tour_id = :tour_id 
                       AND ngay_bat_dau BETWEEN :bat_dau AND :ket_thuc";

            $stmt = $this->conn->prepare($check_query);
            $stmt->execute([
                ':tour_id' => $phien_ban['tour_id'],
                ':bat_dau' => $phien_ban['thoi_gian_bat_dau'],
                ':ket_thuc' => $phien_ban['thoi_gian_ket_thuc']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                throw new Exception("Không thể xóa phiên bản vì có lịch khởi hành đang sử dụng trong thời gian này!");
            }

            // Xóa phiên bản
            $delete_query = "DELETE FROM phien_ban_tour WHERE id = :id";
            $stmt = $this->conn->prepare($delete_query);
            $stmt->execute([':id' => $id]);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // Lấy thống kê chi tiết
    public function getThongKePhienBan($phien_ban_id)
    {
        try {
            $phien_ban = $this->getPhienBanById($phien_ban_id);
            if (!$phien_ban) {
                return [];
            }

            $query = "SELECT 
                    COUNT(pdt.id) as tong_dat,
                    SUM(pdt.so_luong_khach) as tong_khach,
                    SUM(pdt.tong_tien) as doanh_thu,
                    SUM(CASE WHEN pdt.trang_thai = 'hoàn tất' THEN pdt.tong_tien ELSE 0 END) as da_thanh_toan,
                    SUM(CASE WHEN pdt.trang_thai = 'đã cọc' THEN pdt.tong_tien * 0.3 ELSE 0 END) as coc,
                    COUNT(DISTINCT pdt.khach_hang_id) as so_khach_hang,
                    AVG(dtg.diem_so) as diem_danh_gia_tb
                  FROM phieu_dat_tour pdt
                  JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                  LEFT JOIN danh_gia_tour dtg ON pdt.id = dtg.phieu_dat_tour_id
                  WHERE lkh.tour_id = :tour_id
                  AND lkh.ngay_bat_dau BETWEEN :bat_dau AND :ket_thuc
                  AND pdt.trang_thai IN ('hoàn tất', 'đã cọc')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $phien_ban['tour_id'],
                ':bat_dau' => $phien_ban['thoi_gian_bat_dau'],
                ':ket_thuc' => $phien_ban['thoi_gian_ket_thuc']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Lấy thêm số lượng lịch khởi hành
            $lich_query = "SELECT COUNT(*) as so_lich_khoi_hanh 
                      FROM lich_khoi_hanh 
                      WHERE tour_id = :tour_id 
                      AND ngay_bat_dau BETWEEN :bat_dau AND :ket_thuc";

            $stmt = $this->conn->prepare($lich_query);
            $stmt->execute([
                ':tour_id' => $phien_ban['tour_id'],
                ':bat_dau' => $phien_ban['thoi_gian_bat_dau'],
                ':ket_thuc' => $phien_ban['thoi_gian_ket_thuc']
            ]);

            $lich_result = $stmt->fetch(PDO::FETCH_ASSOC);

            return array_merge($result ?: [], $lich_result ?: []);
        } catch (PDOException $e) {
            error_log("Lỗi getThongKePhienBan: " . $e->getMessage());
            return [];
        }
    }

    // Tạo phiên bản mới
    public function createPhienBan($data)
    {
        try {
            error_log("createPhienBan called with data: " . print_r($data, true));

            // Kiểm tra kết nối
            if (!$this->conn) {
                throw new Exception("Không thể kết nối đến database!");
            }

            $query = "INSERT INTO phien_ban_tour 
                  (tour_id, ten_phien_ban, loai_phien_ban, gia_tour, 
                   gia_goc, khuyen_mai, thoi_gian_bat_dau, thoi_gian_ket_thuc, 
                   mo_ta, dich_vu_dac_biet, dieu_kien_ap_dung) 
                  VALUES 
                  (:tour_id, :ten_phien_ban, :loai_phien_ban, :gia_tour, 
                   :gia_goc, :khuyen_mai, :thoi_gian_bat_dau, :thoi_gian_ket_thuc, 
                   :mo_ta, :dich_vu_dac_biet, :dieu_kien_ap_dung)";

            error_log("SQL Query: " . $query);

            $stmt = $this->conn->prepare($query);

            $params = [
                ':tour_id' => $data['tour_id'],
                ':ten_phien_ban' => $data['ten_phien_ban'],
                ':loai_phien_ban' => $data['loai_phien_ban'],
                ':gia_tour' => $data['gia_tour'],
                ':gia_goc' => $data['gia_goc'],
                ':khuyen_mai' => $data['khuyen_mai'],
                ':thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'],
                ':thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'],
                ':mo_ta' => $data['mo_ta'],
                ':dich_vu_dac_biet' => $data['dich_vu_dac_biet'],
                ':dieu_kien_ap_dung' => $data['dieu_kien_ap_dung']
            ];

            error_log("Params: " . print_r($params, true));

            $result = $stmt->execute($params);

            if (!$result) {
                $error_info = $stmt->errorInfo();
                error_log("SQL Error: " . print_r($error_info, true));
                throw new Exception("Lỗi SQL: " . $error_info[2]);
            }

            $last_id = $this->conn->lastInsertId();
            error_log("Insert successful, last ID: " . $last_id);

            return $last_id;
        } catch (PDOException $e) {
            error_log("PDO Exception in createPhienBan: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("SQL State: " . $e->errorInfo[0] ?? 'N/A');
            return false;
        } catch (Exception $e) {
            error_log("General Exception in createPhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật phiên bản
    public function updatePhienBan($id, $data)
    {
        try {
            $query = "UPDATE phien_ban_tour 
                  SET ten_phien_ban = :ten_phien_ban, 
                      loai_phien_ban = :loai_phien_ban,
                      gia_tour = :gia_tour,
                      gia_goc = :gia_goc,
                      khuyen_mai = :khuyen_mai,
                      thoi_gian_bat_dau = :thoi_gian_bat_dau,
                      thoi_gian_ket_thuc = :thoi_gian_ket_thuc,
                      mo_ta = :mo_ta,
                      dich_vu_dac_biet = :dich_vu_dac_biet,
                      dieu_kien_ap_dung = :dieu_kien_ap_dung,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_phien_ban' => $data['ten_phien_ban'],
                ':loai_phien_ban' => $data['loai_phien_ban'],
                ':gia_tour' => $data['gia_tour'],
                ':gia_goc' => $data['gia_goc'],
                ':khuyen_mai' => $data['khuyen_mai'],
                ':thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'],
                ':thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'],
                ':mo_ta' => $data['mo_ta'],
                ':dich_vu_dac_biet' => $data['dich_vu_dac_biet'],
                ':dieu_kien_ap_dung' => $data['dieu_kien_ap_dung'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updatePhienBan: " . $e->getMessage());
            return false;
        }
    }



    // Kích hoạt phiên bản (đặt làm phiên bản hiện hành)
    public function activatePhienBan($phien_ban_id, $tour_id)
    {
        try {
            // Lấy thông tin phiên bản
            $phien_ban = $this->getPhienBanById($phien_ban_id);

            if (!$phien_ban) {
                return false;
            }

            // Cập nhật giá tour từ phiên bản
            $query = "UPDATE tour 
                  SET gia_tour = :gia_tour, updated_at = NOW()
                  WHERE id = :tour_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':gia_tour' => $phien_ban['gia_tour'],
                ':tour_id' => $tour_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi activatePhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Lấy phiên bản đang hiệu lực
    public function getPhienBanHienHanh($tour_id)
    {
        try {
            $now = date('Y-m-d');
            $query = "SELECT * FROM phien_ban_tour 
                  WHERE tour_id = :tour_id 
                  AND :now BETWEEN thoi_gian_bat_dau AND thoi_gian_ket_thuc
                  ORDER BY 
                    CASE loai_phien_ban 
                        WHEN 'dac_biet' THEN 1
                        WHEN 'khuyen_mai' THEN 2
                        WHEN 'mua' THEN 3
                        ELSE 4
                    END
                  LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id, ':now' => $now]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanHienHanh: " . $e->getMessage());
            return null;
        }
    }


    // Lấy chi tiết phiên bản (Sửa với debug)
    public function getPhienBanById($id)
    {
        try {
            error_log("Getting phien ban by ID: " . $id);

            $query = "SELECT pb.*, 
                  t.ten_tour, 
                  t.ma_tour, 
                  t.gia_tour as gia_tour_hien_tai,
                  t.mo_ta as mo_ta_tour,
                  t.hinh_anh,
                  t.trang_thai as trang_thai_tour,
                  dm.ten_danh_muc,
                  cs.ten_chinh_sach,
                  u.ho_ten as nguoi_tao_ten,
                  u.id as nguoi_tao_id
                  FROM phien_ban_tour pb
                  JOIN tour t ON pb.tour_id = t.id
                  LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                  LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id
                  LEFT JOIN nguoi_dung u ON pb.nguoi_tao = u.id
                  WHERE pb.id = :id";

            error_log("SQL Query: " . $query);

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("Query result: " . print_r($result, true));

            // Đảm bảo giá trị mặc định nếu NULL
            if ($result) {
                $result['gia_tour_hien_tai'] = $result['gia_tour_hien_tai'] ?? 0;
                $result['gia_goc'] = $result['gia_goc'] ?? $result['gia_tour_hien_tai'] ?? $result['gia_tour'];
                $result['khuyen_mai'] = $result['khuyen_mai'] ?? 0;
                $result['mo_ta'] = $result['mo_ta'] ?? '';
                $result['dich_vu_dac_biet'] = $result['dich_vu_dac_biet'] ?? '';
                $result['dieu_kien_ap_dung'] = $result['dieu_kien_ap_dung'] ?? '';
            } else {
                error_log("No result found for phien ban ID: " . $id);
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanById: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("Error Info: " . print_r($stmt->errorInfo(), true));
            return null;
        }
    }

    // ==================== MEDIA METHODS ====================

    // Lấy media theo tour
    public function getMediaByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM media_tour 
                      WHERE tour_id = :tour_id 
                      ORDER BY thu_tu_sap_xep, created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy media theo ID
    public function getMediaById($id)
    {
        try {
            $query = "SELECT * FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo media mới
    public function createMedia($data)
    {
        try {
            $query = "INSERT INTO media_tour (tour_id, loai_media, url, chu_thich, thu_tu_sap_xep) 
                      VALUES (:tour_id, :loai_media, :url, :chu_thich, :thu_tu_sap_xep)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':loai_media' => $data['loai_media'],
                ':url' => $data['url'],
                ':chu_thich' => $data['chu_thich'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createMedia: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật media
    public function updateMedia($id, $data)
    {
        try {
            $query = "UPDATE media_tour 
                      SET chu_thich = :chu_thich, thu_tu_sap_xep = :thu_tu_sap_xep
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':chu_thich' => $data['chu_thich'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateMedia: " . $e->getMessage());
            return false;
        }
    }

    // Xóa media
    public function deleteMedia($id)
    {
        try {
            $query = "DELETE FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteMedia: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả media của tour
    private function deleteAllMediaByTour($tour_id)
    {
        try {
            $query = "DELETE FROM media_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllMediaByTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== UTILITY METHODS ====================



    // Lấy tất cả tag
    public function getAllTagTour()
    {
        try {
            $query = "SELECT * FROM tag_tour ORDER BY ten_tag";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllTagTour: " . $e->getMessage());
            return [];
        }
    }

    // ==================== NHÀ CUNG CẤP TOUR METHODS ====================

    // Lấy tất cả nhà cung cấp
    public function getAllNhaCungCap()
    {
        try {
            $query = "SELECT * FROM nha_cung_cap ORDER BY ten_nha_cung_cap";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllNhaCungCap: " . $e->getMessage());
            return [];
        }
    }

    // Lấy nhà cung cấp của tour (dựa vào bảng phan_cong và lich_khoi_hanh)
    public function getNhaCungCapByTour($tour_id)
    {
        try {
            $query = "SELECT DISTINCT ncc.*, pc.loai_phan_cong, pc.trang_thai_xac_nhan,
                         lkh.ngay_bat_dau, lkh.ngay_ket_thuc
                  FROM phan_cong pc
                  JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                  JOIN nha_cung_cap ncc ON pc.doi_tac_id = ncc.id
                  WHERE lkh.tour_id = :tour_id 
                  AND pc.loai_phan_cong IN ('vận chuyển', 'khách sạn', 'nhà hàng', 'vé tham quan')
                  ORDER BY pc.loai_phan_cong, ncc.ten_nha_cung_cap";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getNhaCungCapByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch khởi hành của tour để gắn nhà cung cấp
    public function getLichKhoiHanhByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_khoi_hanh 
                  WHERE tour_id = :tour_id 
                  AND trang_thai IN ('đã lên lịch', 'đang diễn ra')
                  ORDER BY ngay_bat_dau";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhByTour: " . $e->getMessage());
            return [];
        }
    }

    // Thêm nhà cung cấp vào tour (thêm vào bảng phan_cong)
    public function addNhaCungCapToTour($data)
    {
        try {
            $query = "INSERT INTO phan_cong (lich_khoi_hanh_id, loai_phan_cong, 
                  doi_tac_id, ten_dich_vu, trang_thai_xac_nhan, ghi_chu) 
                  VALUES (:lich_khoi_hanh_id, :loai_phan_cong, 
                  :doi_tac_id, :ten_dich_vu, 'chờ xác nhận', :ghi_chu)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':loai_phan_cong' => $data['loai_phan_cong'],
                ':doi_tac_id' => $data['nha_cung_cap_id'],
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':ghi_chu' => $data['ghi_chu'] ?? ''
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi addNhaCungCapToTour: " . $e->getMessage());
            return false;
        }
    }

    // Xóa nhà cung cấp khỏi tour
    public function removeNhaCungCapFromTour($phan_cong_id)
    {
        try {
            $query = "DELETE FROM phan_cong WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $phan_cong_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi removeNhaCungCapFromTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật thông tin phân công nhà cung cấp
    public function updateNhaCungCapTour($id, $data)
    {
        try {
            $query = "UPDATE phan_cong 
                  SET loai_phan_cong = :loai_phan_cong, 
                      ten_dich_vu = :ten_dich_vu,
                      trang_thai_xac_nhan = :trang_thai_xac_nhan,
                      ghi_chu = :ghi_chu,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':loai_phan_cong' => $data['loai_phan_cong'],
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':trang_thai_xac_nhan' => $data['trang_thai_xac_nhan'],
                ':ghi_chu' => $data['ghi_chu'] ?? '',
                ':id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateNhaCungCapTour: " . $e->getMessage());
            return false;
        }
    }

    // Thêm vào class AdminTour

    public function getAllNhaCungCapAdmin($search = '', $loai_dich_vu = '')
    {
        try {
            $query = "SELECT * FROM nha_cung_cap WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $query .= " AND (ten_nha_cung_cap LIKE :search OR email LIKE :search OR so_dien_thoai LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($loai_dich_vu)) {
                $query .= " AND loai_dich_vu = :loai_dich_vu";
                $params[':loai_dich_vu'] = $loai_dich_vu;
            }

            $query .= " ORDER BY ten_nha_cung_cap";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllNhaCungCapAdmin: " . $e->getMessage());
            return [];
        }
    }

    public function createNhaCungCap($data)
    {
        try {
            $query = "INSERT INTO nha_cung_cap (ten_nha_cung_cap, loai_dich_vu, dia_chi, so_dien_thoai, email, mo_ta, created_at, nguoi_tao) 
                  VALUES (:ten_nha_cung_cap, :loai_dich_vu, :dia_chi, :so_dien_thoai, :email, :mo_ta, NOW(), :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_nha_cung_cap' => $data['ten_nha_cung_cap'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':dia_chi' => $data['dia_chi'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':email' => $data['email'],
                ':mo_ta' => $data['mo_ta'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createNhaCungCap: " . $e->getMessage());
            return false;
        }
    }

    // Lấy nhà cung cấp theo ID
    public function getNhaCungCapById($id)
    {
        try {
            $query = "SELECT * FROM nha_cung_cap WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getNhaCungCapById: " . $e->getMessage());
            return null;
        }
    }

    // Cập nhật nhà cung cấp
    public function updateNhaCungCap($id, $data)
    {
        try {
            $query = "UPDATE nha_cung_cap 
                  SET ten_nha_cung_cap = :ten_nha_cung_cap, 
                      loai_dich_vu = :loai_dich_vu,
                      dia_chi = :dia_chi,
                      so_dien_thoai = :so_dien_thoai,
                      email = :email,
                      mo_ta = :mo_ta,
                      danh_gia = :danh_gia,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_nha_cung_cap' => $data['ten_nha_cung_cap'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':dia_chi' => $data['dia_chi'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':email' => $data['email'],
                ':mo_ta' => $data['mo_ta'],
                ':danh_gia' => $data['danh_gia'] ?? 0,
                ':id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateNhaCungCap: " . $e->getMessage());
            return false;
        }
    }

    // Xóa nhà cung cấp
    public function deleteNhaCungCap($id)
    {
        try {
            // Kiểm tra xem nhà cung cấp có đang được sử dụng không
            $checkQuery = "SELECT COUNT(*) as count FROM phan_cong WHERE doi_tac_id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':id' => $id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false; // Không xóa vì đang được sử dụng
            }

            $query = "DELETE FROM nha_cung_cap WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteNhaCungCap: " . $e->getMessage());
            return false;
        }
    }
}
