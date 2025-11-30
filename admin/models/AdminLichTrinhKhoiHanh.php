<?php
class AdminLichKhoiHanh
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
        $this->autoUpdateTrangThai();
    }

    // Lấy tất cả tour đang hoạt động
    public function getAllToursActive()
    {
        try {
            $query = "SELECT id, ma_tour, ten_tour FROM tour 
                      WHERE trang_thai = 'đang hoạt động' 
                      ORDER BY ten_tour";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllToursActive: " . $e->getMessage());
            return [];
        }
    }

    // Tạo lịch khởi hành mới
    public function createLichKhoiHanh($data)
    {
        try {
            $query = "INSERT INTO lich_khoi_hanh 
                      (tour_id, ngay_bat_dau, ngay_ket_thuc, gio_tap_trung, 
                       diem_tap_trung, so_cho_con_lai, so_cho_toi_da, ghi_chu_van_hanh, 
                       trang_thai, nguoi_tao) 
                      VALUES 
                      (:tour_id, :ngay_bat_dau, :ngay_ket_thuc, :gio_tap_trung, 
                       :diem_tap_trung, :so_cho_toi_da, :so_cho_toi_da, :ghi_chu_van_hanh, 
                       'đã lên lịch', :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':ngay_bat_dau' => $data['ngay_bat_dau'],
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'],
                ':gio_tap_trung' => $data['gio_tap_trung'],
                ':diem_tap_trung' => $data['diem_tap_trung'],
                ':so_cho_toi_da' => $data['so_cho_toi_da'],
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createLichKhoiHanh: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật lịch khởi hành
    public function updateLichKhoiHanh($id, $data)
    {
        try {
            $query = "UPDATE lich_khoi_hanh 
                      SET tour_id = :tour_id, 
                          ngay_bat_dau = :ngay_bat_dau, 
                          ngay_ket_thuc = :ngay_ket_thuc,
                          gio_tap_trung = :gio_tap_trung, 
                          diem_tap_trung = :diem_tap_trung,
                          so_cho_toi_da = :so_cho_toi_da,
                          ghi_chu_van_hanh = :ghi_chu_van_hanh,
                          trang_thai = :trang_thai,
                          updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':ngay_bat_dau' => $data['ngay_bat_dau'],
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'],
                ':gio_tap_trung' => $data['gio_tap_trung'],
                ':diem_tap_trung' => $data['diem_tap_trung'],
                ':so_cho_toi_da' => $data['so_cho_toi_da'],
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateLichKhoiHanh: " . $e->getMessage());
            return false;
        }
    }

    // Xóa lịch khởi hành
    public function deleteLichKhoiHanh($id)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra xem có booking nào đang sử dụng lịch này không
            $check_query = "SELECT COUNT(*) as booking_count FROM phieu_dat_tour WHERE lich_khoi_hanh_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['booking_count'] > 0) {
                $this->conn->rollBack();
                return false; // Không thể xóa vì có booking
            }

            // Xóa các bản ghi liên quan
            $tables = [
                'phan_cong',
                'checklist_truoc_tour',
                'nhat_ky_tour',
                'bao_cao_su_co',
                'chi_phi_tour',
                'dich_vu_phat_sinh'
            ];

            foreach ($tables as $table) {
                $query = "DELETE FROM $table WHERE lich_khoi_hanh_id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([':id' => $id]);
            }

            // Xóa lịch khởi hành
            $query = "DELETE FROM lich_khoi_hanh WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi deleteLichKhoiHanh: " . $e->getMessage());
            return false;
        }
    }

    // Lấy tất cả hướng dẫn viên đang làm việc
    public function getAllHuongDanVien()
    {
        try {
            $query = "SELECT id, ho_ten, ngon_ngu, chuyen_mon, so_dien_thoai, email, 
                         loai_huong_dan_vien, danh_gia_trung_binh, so_tour_da_dan, trang_thai
                  FROM huong_dan_vien 
                  WHERE trang_thai = 'đang làm việc' 
                  ORDER BY ho_ten";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllHuongDanVien: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra xem HDV có bị trùng lịch không
    public function kiemTraTrungLich($huong_dan_vien_id, $lich_khoi_hanh_id, $ngay_bat_dau, $ngay_ket_thuc)
    {
        try {
            $query = "SELECT COUNT(*) as so_luong_trung
                  FROM phan_cong pc
                  JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                  WHERE pc.huong_dan_vien_id = :huong_dan_vien_id
                  AND pc.lich_khoi_hanh_id != :lich_khoi_hanh_id
                  AND lkh.trang_thai NOT IN ('đã hoàn thành', 'đã hủy')
                  AND (
                      (:ngay_bat_dau BETWEEN lkh.ngay_bat_dau AND lkh.ngay_ket_thuc)
                      OR (:ngay_ket_thuc BETWEEN lkh.ngay_bat_dau AND lkh.ngay_ket_thuc)
                      OR (lkh.ngay_bat_dau BETWEEN :ngay_bat_dau_2 AND :ngay_ket_thuc_2)
                  )";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':huong_dan_vien_id' => $huong_dan_vien_id,
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':ngay_bat_dau' => $ngay_bat_dau,
                ':ngay_ket_thuc' => $ngay_ket_thuc,
                ':ngay_bat_dau_2' => $ngay_bat_dau,
                ':ngay_ket_thuc_2' => $ngay_ket_thuc
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['so_luong_trung'] > 0;
        } catch (PDOException $e) {
            error_log("Lỗi kiemTraTrungLich: " . $e->getMessage());
            return true; // Trả về true để an toàn, không cho phân công nếu có lỗi
        }
    }

    // Lấy phân công HDV
    public function getPhanCongHDV($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT hdv.ho_ten as ten_hdv 
                  FROM phan_cong pc
                  JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                  WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id 
                  AND pc.loai_phan_cong = 'hướng dẫn viên'
                  AND pc.trang_thai_xac_nhan = 'đã xác nhận'
                  LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhanCongHDV: " . $e->getMessage());
            return null;
        }
    }

    // Phân công HDV với validation
    public function phanCongHDV($lich_khoi_hanh_id, $huong_dan_vien_id, $ngay_bat_dau, $ngay_ket_thuc, $ghi_chu = '')
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra xem tour hiện tại đã hoàn thành chưa
            $query_check_tour = "SELECT trang_thai FROM lich_khoi_hanh WHERE id = :lich_khoi_hanh_id";
            $stmt_check_tour = $this->conn->prepare($query_check_tour);
            $stmt_check_tour->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $tour = $stmt_check_tour->fetch(PDO::FETCH_ASSOC);

            if ($tour && $tour['trang_thai'] === 'đã hoàn thành') {
                throw new Exception("Không thể phân công HDV cho tour đã hoàn thành");
            }

            // Kiểm tra trùng lịch
            $trung_lich = $this->kiemTraTrungLich($huong_dan_vien_id, $lich_khoi_hanh_id, $ngay_bat_dau, $ngay_ket_thuc);

            if ($trung_lich) {
                throw new Exception("Hướng dẫn viên đã được phân công cho tour khác trong khoảng thời gian này");
            }

            // Xóa phân công cũ nếu có
            $query_delete = "DELETE FROM phan_cong 
                     WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                     AND loai_phan_cong = 'hướng dẫn viên'";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);

            // Thêm phân công mới
            $query_insert = "INSERT INTO phan_cong 
                     (lich_khoi_hanh_id, huong_dan_vien_id, loai_phan_cong, 
                      trang_thai_xac_nhan, ghi_chu, nguoi_tao) 
                     VALUES 
                     (:lich_khoi_hanh_id, :huong_dan_vien_id, 'hướng dẫn viên', 
                      'đã xác nhận', :ghi_chu, :nguoi_tao)";

            $stmt_insert = $this->conn->prepare($query_insert);
            $stmt_insert->execute([
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':huong_dan_vien_id' => $huong_dan_vien_id,
                ':ghi_chu' => $ghi_chu,
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            $this->conn->commit();
            return ['success' => true, 'message' => 'Phân công HDV thành công'];
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi phanCongHDV: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Hủy phân công HDV
    public function huyPhanCongHDV($lich_khoi_hanh_id)
    {
        try {
            // Kiểm tra xem tour đã hoàn thành chưa
            $query_check_tour = "SELECT trang_thai FROM lich_khoi_hanh WHERE id = :lich_khoi_hanh_id";
            $stmt_check_tour = $this->conn->prepare($query_check_tour);
            $stmt_check_tour->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $tour = $stmt_check_tour->fetch(PDO::FETCH_ASSOC);

            if ($tour && $tour['trang_thai'] === 'đã hoàn thành') {
                throw new Exception("Không thể hủy phân công HDV cho tour đã hoàn thành");
            }

            $query = "DELETE FROM phan_cong 
              WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
              AND loai_phan_cong = 'hướng dẫn viên'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);

            return ['success' => true, 'message' => 'Hủy phân công HDV thành công'];
        } catch (Exception $e) {
            error_log("Lỗi huyPhanCongHDV: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Lấy danh sách HDV có sẵn (không bị trùng lịch)
    public function getHuongDanVienCoSan($lich_khoi_hanh_id, $ngay_bat_dau, $ngay_ket_thuc)
    {
        try {
            $all_hdv = $this->getAllHuongDanVien();
            $hdv_co_san = [];

            foreach ($all_hdv as $hdv) {
                $trung_lich = $this->kiemTraTrungLich($hdv['id'], $lich_khoi_hanh_id, $ngay_bat_dau, $ngay_ket_thuc);
                if (!$trung_lich) {
                    $hdv_co_san[] = $hdv;
                }
            }

            return $hdv_co_san;
        } catch (PDOException $e) {
            error_log("Lỗi getHuongDanVienCoSan: " . $e->getMessage());
            return [];
        }
    }


    // Lấy checklist với sắp xếp
    public function getChecklistTruocTour($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT *, 
                  CASE 
                    WHEN hoan_thanh = 1 THEN 1
                    ELSE 0 
                  END as sort_order
                  FROM checklist_truoc_tour 
                  WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                  ORDER BY sort_order, created_at";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getChecklistTruocTour: " . $e->getMessage());
            return [];
        }
    }

    // Thêm checklist mới
    public function themChecklist($data)
    {
        try {
            $query = "INSERT INTO checklist_truoc_tour 
                  (lich_khoi_hanh_id, cong_viec, nguoi_tao, created_at) 
                  VALUES (:lich_khoi_hanh_id, :cong_viec, :nguoi_tao, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':cong_viec' => $data['cong_viec'],
                ':nguoi_tao' => $data['nguoi_tao']
            ]);

            return ['success' => true, 'message' => 'Thêm checklist thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi themChecklist: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Cập nhật checklist
    public function updateChecklist($id, $data)
    {
        try {
            $query = "UPDATE checklist_truoc_tour 
                  SET hoan_thanh = :hoan_thanh,
                      nguoi_hoan_thanh = :nguoi_hoan_thanh,
                      thoi_gian_hoan_thanh = :thoi_gian_hoan_thanh
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':hoan_thanh' => $data['hoan_thanh'],
                ':nguoi_hoan_thanh' => $data['nguoi_hoan_thanh'],
                ':thoi_gian_hoan_thanh' => $data['thoi_gian_hoan_thanh'],
                ':id' => $id
            ]);

            return ['success' => true, 'message' => 'Cập nhật thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi updateChecklist: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Xóa checklist
    public function xoaChecklist($id)
    {
        try {
            $query = "DELETE FROM checklist_truoc_tour WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            return ['success' => true, 'message' => 'Xóa checklist thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi xoaChecklist: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }



    // Cập nhật số chỗ còn lại
    public function updateSoChoConLai($lich_khoi_hanh_id, $so_cho_da_dat)
    {
        try {
            $query = "UPDATE lich_khoi_hanh 
                      SET so_cho_con_lai = so_cho_toi_da - :so_cho_da_dat,
                          updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':so_cho_da_dat' => $so_cho_da_dat,
                ':id' => $lich_khoi_hanh_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateSoChoConLai: " . $e->getMessage());
            return false;
        }
    }

    public function autoUpdateTrangThai()
    {
        try {
            $today = date('Y-m-d');

            // Cập nhật tour đang diễn ra
            $query_dang_dien_ra = "UPDATE lich_khoi_hanh 
                                  SET trang_thai = 'đang diễn ra' 
                                  WHERE ngay_bat_dau <= ? 
                                  AND ngay_ket_thuc >= ? 
                                  AND trang_thai != 'đã hủy'";

            $stmt1 = $this->conn->prepare($query_dang_dien_ra);
            $stmt1->execute([$today, $today]);

            // Cập nhật tour đã hoàn thành
            $query_da_hoan_thanh = "UPDATE lich_khoi_hanh 
                                   SET trang_thai = 'đã hoàn thành' 
                                   WHERE ngay_ket_thuc < ? 
                                   AND trang_thai NOT IN ('đã hoàn thành', 'đã hủy')";

            $stmt2 = $this->conn->prepare($query_da_hoan_thanh);
            $stmt2->execute([$today]);

            // Cập nhật tour đã lên lịch (tương lai)
            $query_da_len_lich = "UPDATE lich_khoi_hanh 
                                 SET trang_thai = 'đã lên lịch' 
                                 WHERE ngay_bat_dau > ? 
                                 AND trang_thai NOT IN ('đã lên lịch', 'đã hủy')";

            $stmt3 = $this->conn->prepare($query_da_len_lich);
            $stmt3->execute([$today]);

            error_log("Đã tự động cập nhật trạng thái lịch khởi hành - Ngày: " . $today);
        } catch (PDOException $e) {
            error_log("Lỗi autoUpdateTrangThai: " . $e->getMessage());
        }
    }

    // Lấy trạng thái hiện tại (tính toán real-time, không lưu DB)
    public function getTrangThaiHienTai($ngay_bat_dau, $ngay_ket_thuc, $trang_thai_hien_tai)
    {
        if ($trang_thai_hien_tai == 'đã hủy') {
            return 'đã hủy';
        }

        $today = date('Y-m-d');

        if ($ngay_bat_dau <= $today && $ngay_ket_thuc >= $today) {
            return 'đang diễn ra';
        } elseif ($ngay_ket_thuc < $today) {
            return 'đã hoàn thành';
        } elseif ($ngay_bat_dau > $today) {
            return 'đã lên lịch';
        }

        return $trang_thai_hien_tai;
    }

    // Sửa hàm getAllLichKhoiHanh để HIỂN THỊ trạng thái real-time
    public function getAllLichKhoiHanh($search = '', $trang_thai = '', $thang = '', $nam = '')
    {
        try {
            $query = "SELECT lkh.*, t.ma_tour, t.ten_tour, dm.ten_danh_muc
                      FROM lich_khoi_hanh lkh 
                      JOIN tour t ON lkh.tour_id = t.id 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
                      WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (t.ma_tour LIKE :search OR t.ten_tour LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($trang_thai)) {
                $query .= " AND lkh.trang_thai = :trang_thai";
                $params[':trang_thai'] = $trang_thai;
            }

            if (!empty($thang) && !empty($nam)) {
                $query .= " AND MONTH(lkh.ngay_bat_dau) = :thang AND YEAR(lkh.ngay_bat_dau) = :nam";
                $params[':thang'] = $thang;
                $params[':nam'] = $nam;
            }

            $query .= " ORDER BY lkh.ngay_bat_dau DESC, lkh.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // CẬP NHẬT TRẠNG THÁI REAL-TIME CHO KẾT QUẢ
            foreach ($results as &$result) {
                $result['trang_thai_hien_tai'] = $this->getTrangThaiHienTai(
                    $result['ngay_bat_dau'],
                    $result['ngay_ket_thuc'],
                    $result['trang_thai']
                );
            }

            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi getAllLichKhoiHanh: " . $e->getMessage());
            return [];
        }
    }

    // Sửa hàm getLichKhoiHanhById để HIỂN THỊ trạng thái real-time
    public function getLichKhoiHanhById($id)
    {
        try {
            $query = "SELECT lkh.*, t.ma_tour, t.ten_tour, t.danh_muc_id, dm.ten_danh_muc
                      FROM lich_khoi_hanh lkh 
                      JOIN tour t ON lkh.tour_id = t.id 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
                      WHERE lkh.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // THÊM TRẠNG THÁI REAL-TIME
                $result['trang_thai_hien_tai'] = $this->getTrangThaiHienTai(
                    $result['ngay_bat_dau'],
                    $result['ngay_ket_thuc'],
                    $result['trang_thai']
                );
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhById: " . $e->getMessage());
            return null;
        }
    }

    // Hàm để chạy cập nhật thủ công (nếu cần)
    public function manualUpdateAllTrangThai()
    {
        return $this->autoUpdateTrangThai();
    }
}
