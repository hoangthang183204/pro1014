<?php
class AdminLichKhoiHanh
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả lịch khởi hành với filter
    public function getAllLichKhoiHanh($search = '', $trang_thai = '', $thang = '', $nam = '')
    {
        try {
            $query = "SELECT lkh.*, t.ma_tour, t.ten_tour, dd.ten_diem_den,
                             (SELECT ten_hdv FROM huong_dan_vien hdv 
                              JOIN phan_cong pc ON hdv.id = pc.hdv_id 
                              WHERE pc.lich_khoi_hanh_id = lkh.id AND pc.loai_phan_cong = 'hdv' LIMIT 1) as ten_hdv
                      FROM lich_khoi_hanh lkh 
                      JOIN tours t ON lkh.tour_id = t.id 
                      JOIN diem_den dd ON t.diem_den_id = dd.id 
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
            
            $query .= " ORDER BY lkh.ngay_bat_dau DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy lịch khởi hành theo ID
    public function getLichKhoiHanhById($id)
    {
        try {
            $query = "SELECT lkh.*, t.ma_tour, t.ten_tour, t.diem_den_id, dd.ten_diem_den
                      FROM lich_khoi_hanh lkh 
                      JOIN tours t ON lkh.tour_id = t.id 
                      JOIN diem_den dd ON t.diem_den_id = dd.id 
                      WHERE lkh.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }

    // Lấy tất cả tour đang hoạt động
    public function getAllToursActive()
    {
        try {
            $query = "SELECT id, ma_tour, ten_tour FROM tours 
                      WHERE trang_thai = 'đang_hoạt_động' 
                      ORDER BY ten_tour";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Tạo lịch khởi hành mới
    public function createLichKhoiHanh($data)
    {
        try {
            $query = "INSERT INTO lich_khoi_hanh (tour_id, ngay_bat_dau, ngay_ket_thuc, gio_tap_trung, 
                      diem_tap_trung, so_cho_du_kien, ghi_chu_van_hanh, trang_thai) 
                      VALUES (:tour_id, :ngay_bat_dau, :ngay_ket_thuc, :gio_tap_trung, 
                      :diem_tap_trung, :so_cho_du_kien, :ghi_chu_van_hanh, 'đã_lên_lịch')";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':ngay_bat_dau' => $data['ngay_bat_dau'],
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'],
                ':gio_tap_trung' => $data['gio_tap_trung'],
                ':diem_tap_trung' => $data['diem_tap_trung'],
                ':so_cho_du_kien' => $data['so_cho_du_kien'],
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh']
            ]);
            
            return $this->conn->lastInsertId();
            
        } catch (PDOException $e) {
            return false;
        }
    }

    // Cập nhật lịch khởi hành
    public function updateLichKhoiHanh($id, $data)
    {
        try {
            $query = "UPDATE lich_khoi_hanh 
                      SET tour_id = :tour_id, ngay_bat_dau = :ngay_bat_dau, ngay_ket_thuc = :ngay_ket_thuc,
                          gio_tap_trung = :gio_tap_trung, diem_tap_trung = :diem_tap_trung,
                          so_cho_du_kien = :so_cho_du_kien, ghi_chu_van_hanh = :ghi_chu_van_hanh,
                          trang_thai = :trang_thai
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':ngay_bat_dau' => $data['ngay_bat_dau'],
                ':ngay_ket_thuc' => $data['ngay_ket_thuc'],
                ':gio_tap_trung' => $data['gio_tap_trung'],
                ':diem_tap_trung' => $data['diem_tap_trung'],
                ':so_cho_du_kien' => $data['so_cho_du_kien'],
                ':ghi_chu_van_hanh' => $data['ghi_chu_van_hanh'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);
            
            return true;
            
        } catch (PDOException $e) {
            return false;
        }
    }

    // Xóa lịch khởi hành
    public function deleteLichKhoiHanh($id)
    {
        try {
            $this->conn->beginTransaction();
            
            // Xóa phân công trước
            $query1 = "DELETE FROM phan_cong WHERE lich_khoi_hanh_id = :id";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute([':id' => $id]);
            
            // Xóa checklist
            $query2 = "DELETE FROM checklist_truoc_tour WHERE lich_khoi_hanh_id = :id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute([':id' => $id]);
            
            // Xóa lịch khởi hành
            $query3 = "DELETE FROM lich_khoi_hanh WHERE id = :id";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->execute([':id' => $id]);
            
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Lấy tất cả HDV
    public function getAllHDV()
    {
        try {
            $query = "SELECT * FROM huong_dan_vien 
                      WHERE trang_thai = 'đang_làm_việc' 
                      ORDER BY ten_hdv";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy HDV bị trùng lịch
    public function getHDVTrungLich($lich_khoi_hanh_id, $ngay_bat_dau, $ngay_ket_thuc)
    {
        try {
            $query = "SELECT DISTINCT hdv.id, hdv.ten_hdv
                      FROM huong_dan_vien hdv
                      JOIN phan_cong pc ON hdv.id = pc.hdv_id
                      JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                      WHERE pc.loai_phan_cong = 'hdv'
                      AND lkh.id != :lich_khoi_hanh_id
                      AND lkh.trang_thai = 'đã_lên_lịch'
                      AND ((lkh.ngay_bat_dau BETWEEN :ngay_bat_dau AND :ngay_ket_thuc)
                           OR (lkh.ngay_ket_thuc BETWEEN :ngay_bat_dau AND :ngay_ket_thuc)
                           OR (:ngay_bat_dau BETWEEN lkh.ngay_bat_dau AND lkh.ngay_ket_thuc))";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':ngay_bat_dau' => $ngay_bat_dau,
                ':ngay_ket_thuc' => $ngay_ket_thuc
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy phân công HDV hiện tại
    public function getPhanCongHDV($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT pc.*, hdv.ten_hdv, hdv.ky_nang_ngon_ngu, hdv.chuyen_mon
                      FROM phan_cong pc
                      JOIN huong_dan_vien hdv ON pc.hdv_id = hdv.id
                      WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id 
                      AND pc.loai_phan_cong = 'hdv'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }

    // Lấy tất cả đối tác
    public function getAllDoiTac()
    {
        try {
            $query = "SELECT * FROM doi_tac ORDER BY ten_doi_tac";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy dịch vụ kèm theo
    public function getDichVuKemTheo($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT pc.*, dt.ten_doi_tac, dt.loai_dich_vu
                      FROM phan_cong pc
                      LEFT JOIN doi_tac dt ON pc.doi_tac_id = dt.id
                      WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id 
                      AND pc.loai_phan_cong != 'hdv'
                      ORDER BY pc.loai_phan_cong";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy checklist chuẩn bị
    public function getChecklistChuanBi($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT * FROM checklist_truoc_tour 
                      WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                      ORDER BY created_at";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    // Phân công HDV
    public function phanCongHDV($lich_khoi_hanh_id, $hdv_id, $ghi_chu = '')
    {
        try {
            // Xóa phân công cũ
            $query_delete = "DELETE FROM phan_cong 
                            WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                            AND loai_phan_cong = 'hdv'";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            
            // Thêm phân công mới
            $query_insert = "INSERT INTO phan_cong (lich_khoi_hanh_id, hdv_id, loai_phan_cong, ghi_chu, trang_thai_xac_nhan)
                            VALUES (:lich_khoi_hanh_id, :hdv_id, 'hdv', :ghi_chu, 'đã_xác_nhận')";
            $stmt_insert = $this->conn->prepare($query_insert);
            $stmt_insert->execute([
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':hdv_id' => $hdv_id,
                ':ghi_chu' => $ghi_chu
            ]);
            
            return true;
            
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>



