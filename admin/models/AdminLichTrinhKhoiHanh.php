<?php
class AdminLichKhoiHanh
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connectDB();
    }

    private function connectDB()
    {
        try {
            $host = 'localhost';
            $dbname = 'pro1014';
            $username = 'root';
            $password = '';

            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Lỗi kết nối database: " . $e->getMessage());
        }
    }

    // Lấy tất cả lịch khởi hành với filter
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllLichKhoiHanh: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch khởi hành theo ID
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
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhById: " . $e->getMessage());
            return null;
        }
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

    // Lấy phân công HDV hiện tại - SỬA LẠI CHO ĐÚNG
    public function getPhanCongHDV($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT pc.*, hdv.ho_ten, hdv.so_dien_thoai, hdv.email, hdv.ngon_ngu, 
                         hdv.chuyen_mon, hdv.loai_huong_dan_vien
                  FROM phan_cong pc
                  JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                  WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id
                  AND pc.loai_phan_cong = 'hướng dẫn viên'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhanCongHDV: " . $e->getMessage());
            return null;
        }
    }

    // Phân công HDV - SỬA LẠI CHO ĐÚNG
    public function phanCongHDV($lich_khoi_hanh_id, $huong_dan_vien_id, $ghi_chu = '')
    {
        try {
            $this->conn->beginTransaction();

            // Xóa phân công cũ nếu có
            $query_delete = "DELETE FROM phan_cong 
                         WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                         AND loai_phan_cong = 'hướng dẫn viên'";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);

            // Thêm phân công mới - SỬA TÊN TRƯỜNG CHO ĐÚNG
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
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi phanCongHDV: " . $e->getMessage());
            return false;
        }
    }

    // Hủy phân công HDV - GIỮ NGUYÊN (ĐÃ ĐÚNG)
    public function huyPhanCongHDV($lich_khoi_hanh_id)
    {
        try {
            $query = "DELETE FROM phan_cong 
                  WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                  AND loai_phan_cong = 'hướng dẫn viên'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi huyPhanCongHDV: " . $e->getMessage());
            return false;
        }
    }
    // Lấy checklist trước tour
    public function getChecklistTruocTour($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT * FROM checklist_truoc_tour 
                      WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                      ORDER BY created_at";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getChecklistTruocTour: " . $e->getMessage());
            return [];
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
}
