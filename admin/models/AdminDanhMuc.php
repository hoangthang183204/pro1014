<?php
class AdminDanhMucTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy thống kê theo yêu cầu
    public function getThongKeDanhMuc()
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM tour t 
                         INNER JOIN danh_muc_tour dmt ON t.danh_muc_id = dmt.id 
                         WHERE dmt.loai_tour = 'trong nước' AND t.trang_thai = 'đang hoạt động') as tong_tour_trong_nuoc,
                        (SELECT COUNT(*) FROM tour t 
                         INNER JOIN danh_muc_tour dmt ON t.danh_muc_id = dmt.id 
                         WHERE dmt.loai_tour = 'quốc tế' AND t.trang_thai = 'đang hoạt động') as tong_tour_quoc_te,
                        (SELECT COUNT(*) FROM tour t 
                         INNER JOIN danh_muc_tour dmt ON t.danh_muc_id = dmt.id 
                         WHERE dmt.loai_tour = 'theo yêu cầu' AND t.trang_thai = 'đang hoạt động') as tong_tour_custom,
                        (SELECT COUNT(*) FROM danh_muc_tour WHERE trang_thai = 'hoạt động') as tong_danh_muc,
                        (SELECT COUNT(*) FROM lich_khoi_hanh WHERE trang_thai = 'đang diễn ra') as tour_dang_dien_ra,
                        (SELECT COUNT(*) FROM lich_khoi_hanh WHERE ngay_bat_dau > CURDATE() AND trang_thai = 'đã lên lịch') as tour_sap_dien_ra";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getThongKeDanhMuc: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tất cả danh mục tour với số lượng tour
    public function getAllDanhMucTour()
    {
        try {
            $query = "SELECT dmt.*, 
                             COUNT(t.id) as so_luong_tour,
                             (SELECT COUNT(*) FROM lich_khoi_hanh lkh 
                              INNER JOIN tour t2 ON lkh.tour_id = t2.id 
                              WHERE t2.danh_muc_id = dmt.id AND lkh.trang_thai = 'đang diễn ra') as tour_dang_di
                      FROM danh_muc_tour dmt
                      LEFT JOIN tour t ON dmt.id = t.danh_muc_id AND t.trang_thai = 'đang hoạt động'
                      GROUP BY dmt.id
                      ORDER BY 
                        CASE dmt.loai_tour
                            WHEN 'trong nước' THEN 1
                            WHEN 'quốc tế' THEN 2
                            WHEN 'theo yêu cầu' THEN 3
                            ELSE 4
                        END, dmt.ten_danh_muc";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllDanhMucTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh mục theo ID
    public function getDanhMucTourById($id)
    {
        try {
            $query = "SELECT dmt.*, 
                             COUNT(t.id) as so_luong_tour
                      FROM danh_muc_tour dmt
                      LEFT JOIN tour t ON dmt.id = t.danh_muc_id
                      WHERE dmt.id = :id
                      GROUP BY dmt.id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDanhMucTourById: " . $e->getMessage());
            return null;
        }
    }

    // Lấy danh mục theo loại
    public function getDanhMucTourByLoai($loai_tour)
    {
        try {
            $query = "SELECT * FROM danh_muc_tour 
                      WHERE loai_tour = :loai_tour AND trang_thai = 'hoạt động' 
                      ORDER BY ten_danh_muc";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':loai_tour' => $loai_tour]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDanhMucTourByLoai: " . $e->getMessage());
            return [];
        }
    }

    // Tạo danh mục mới
    public function createDanhMucTour($data)
    {
        try {
            $query = "INSERT INTO danh_muc_tour (ten_danh_muc, loai_tour, mo_ta, trang_thai, nguoi_tao) 
                      VALUES (:ten_danh_muc, :loai_tour, :mo_ta, :trang_thai, :nguoi_tao)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_danh_muc' => $data['ten_danh_muc'],
                ':loai_tour' => $data['loai_tour'],
                ':mo_ta' => $data['mo_ta'],
                ':trang_thai' => $data['trang_thai'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createDanhMucTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật danh mục
    public function updateDanhMucTour($id, $data)
    {
        try {
            $query = "UPDATE danh_muc_tour 
                      SET ten_danh_muc = :ten_danh_muc, 
                          loai_tour = :loai_tour, 
                          mo_ta = :mo_ta, 
                          trang_thai = :trang_thai, 
                          updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':ten_danh_muc' => $data['ten_danh_muc'],
                ':loai_tour' => $data['loai_tour'],
                ':mo_ta' => $data['mo_ta'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateDanhMucTour: " . $e->getMessage());
            return false;
        }
    }

    // Xóa danh mục
    public function deleteDanhMucTour($id)
    {
        try {
            // Kiểm tra xem có tour nào đang sử dụng danh mục này không
            $check_query = "SELECT COUNT(*) as count FROM tour WHERE danh_muc_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM danh_muc_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteDanhMucTour: " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách tour theo danh mục
    public function getToursByDanhMuc($danh_muc_id)
    {
        try {
            $query = "SELECT t.*, 
                             COUNT(lkh.id) as so_lan_khoi_hanh,
                             MIN(lkh.ngay_bat_dau) as khoi_hanh_gan_nhat,
                             dmt.ten_danh_muc,
                             dmt.loai_tour
                      FROM tour t
                      INNER JOIN danh_muc_tour dmt ON t.danh_muc_id = dmt.id
                      LEFT JOIN lich_khoi_hanh lkh ON t.id = lkh.tour_id AND lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra')
                      WHERE t.danh_muc_id = :danh_muc_id AND t.trang_thai = 'đang hoạt động'
                      GROUP BY t.id
                      ORDER BY t.ten_tour";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':danh_muc_id' => $danh_muc_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getToursByDanhMuc: " . $e->getMessage());
            return [];
        }
    }

    // Lọc và tìm kiếm tour
    public function filterTours($danh_muc_id = 0, $loai_tour = '', $search = '')
    {
        try {
            $query = "SELECT t.*, dmt.ten_danh_muc, dmt.loai_tour
                      FROM tour t
                      INNER JOIN danh_muc_tour dmt ON t.danh_muc_id = dmt.id
                      WHERE t.trang_thai = 'đang hoạt động'";
            
            $params = [];
            
            if ($danh_muc_id > 0) {
                $query .= " AND t.danh_muc_id = :danh_muc_id";
                $params[':danh_muc_id'] = $danh_muc_id;
            }
            
            if (!empty($loai_tour)) {
                $query .= " AND dmt.loai_tour = :loai_tour";
                $params[':loai_tour'] = $loai_tour;
            }
            
            if (!empty($search)) {
                $query .= " AND (t.ten_tour LIKE :search OR t.ma_tour LIKE :search OR t.mo_ta LIKE :search)";
                $params[':search'] = "%$search%";
            }
            
            $query .= " ORDER BY t.ten_tour";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi filterTours: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tất cả loại tour (dùng cho dropdown)
    public function getAllLoaiTour()
    {
        return [
            'trong nước' => 'Tour trong nước',
            'quốc tế' => 'Tour quốc tế',
            'theo yêu cầu' => 'Tour theo yêu cầu'
        ];
    }
}