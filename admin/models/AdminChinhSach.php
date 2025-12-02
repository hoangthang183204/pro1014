<?php
class AdminChinhSach
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả chính sách với filter
    public function getAll($search = '', $trang_thai = '')
    {
        try {
            $query = "SELECT * FROM chinh_sach_tour WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $query .= " AND (ten_chinh_sach LIKE :search OR mo_ta LIKE :search)";
                $params[':search'] = "%$search%";
            }

            $query .= " ORDER BY created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAll chính sách: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chính sách theo ID
    public function getById($id)
    {
        try {
            $query = "SELECT * FROM chinh_sach_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getById chính sách: " . $e->getMessage());
            return null;
        }
    }

    // Tạo chính sách mới
    public function create($data)
    {
        try {
            $query = "INSERT INTO chinh_sach_tour (ten_chinh_sach, quy_dinh_huy_doi, luu_y_suc_khoe, luu_y_hanh_ly, luu_y_khac, nguoi_tao) 
                      VALUES (:ten_chinh_sach, :quy_dinh_huy_doi, :luu_y_suc_khoe, :luu_y_hanh_ly, :luu_y_khac, :nguoi_tao)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_chinh_sach' => $data['ten_chinh_sach'],
                ':quy_dinh_huy_doi' => $data['quy_dinh_huy_doi'] ?? '',
                ':luu_y_suc_khoe' => $data['luu_y_suc_khoe'] ?? '',
                ':luu_y_hanh_ly' => $data['luu_y_hanh_ly'] ?? '',
                ':luu_y_khac' => $data['luu_y_khac'] ?? '',
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi create chính sách: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật chính sách
    public function update($id, $data)
    {
        try {
            $query = "UPDATE chinh_sach_tour 
                      SET ten_chinh_sach = :ten_chinh_sach, 
                          quy_dinh_huy_doi = :quy_dinh_huy_doi,
                          luu_y_suc_khoe = :luu_y_suc_khoe,
                          luu_y_hanh_ly = :luu_y_hanh_ly,
                          luu_y_khac = :luu_y_khac,
                          updated_at = NOW()
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_chinh_sach' => $data['ten_chinh_sach'],
                ':quy_dinh_huy_doi' => $data['quy_dinh_huy_doi'] ?? '',
                ':luu_y_suc_khoe' => $data['luu_y_suc_khoe'] ?? '',
                ':luu_y_hanh_ly' => $data['luu_y_hanh_ly'] ?? '',
                ':luu_y_khac' => $data['luu_y_khac'] ?? '',
                ':id' => $id
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi update chính sách: " . $e->getMessage());
            return false;
        }
    }

    // Xóa chính sách
    public function delete($id)
    {
        try {
            // Kiểm tra xem chính sách có đang được sử dụng không
            $checkQuery = "SELECT COUNT(*) as count FROM tour WHERE chinh_sach_id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':id' => $id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false; // Không xóa vì đang được sử dụng
            }
            
            $query = "DELETE FROM chinh_sach_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi delete chính sách: " . $e->getMessage());
            return false;
        }
    }

    // Đếm số tour đang sử dụng chính sách
    public function countToursUsing($id)
    {
        try {
            $query = "SELECT COUNT(*) as count FROM tour WHERE chinh_sach_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Lỗi countToursUsing: " . $e->getMessage());
            return 0;
        }
    }
}
?>