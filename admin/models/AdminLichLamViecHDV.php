<?php
class AdminLichLamViecHDV
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả lịch làm việc
    public function getAllLichLamViec($filters = [])
    {
        $sql = "SELECT llv.*, hdv.ho_ten, hdv.so_dien_thoai 
                FROM lich_lam_viec_hdv llv 
                JOIN huong_dan_vien hdv ON llv.huong_dan_vien_id = hdv.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['huong_dan_vien_id'])) {
            $sql .= " AND llv.huong_dan_vien_id = ?";
            $params[] = $filters['huong_dan_vien_id'];
        }
        
        if (!empty($filters['thang'])) {
            $sql .= " AND DATE_FORMAT(llv.ngay, '%Y-%m') = ?";
            $params[] = $filters['thang'];
        }
        
        $sql .= " ORDER BY llv.ngay DESC, hdv.ho_ten ASC";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllLichLamViec: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách HDV đang làm việc
    public function getHDVDangLamViec()
    {
        $sql = "SELECT id, ho_ten, so_dien_thoai 
                FROM huong_dan_vien 
                WHERE trang_thai = 'đang làm việc' 
                ORDER BY ho_ten ASC";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getHDVDangLamViec: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết 1 lịch làm việc
    public function getById($id)
    {
        $sql = "SELECT llv.*, hdv.ho_ten 
                FROM lich_lam_viec_hdv llv 
                JOIN huong_dan_vien hdv ON llv.huong_dan_vien_id = hdv.id 
                WHERE llv.id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getById: " . $e->getMessage());
            return null;
        }
    }

    // Thêm lịch làm việc mới
    public function create($data)
    {
        $sql = "INSERT INTO lich_lam_viec_hdv 
                (huong_dan_vien_id, ngay, loai_lich, ghi_chu, nguoi_tao, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['huong_dan_vien_id'],
                $data['ngay'],
                $data['loai_lich'],
                $data['ghi_chu'],
                $data['nguoi_tao']
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi create: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật lịch làm việc
    public function update($id, $data)
    {
        $sql = "UPDATE lich_lam_viec_hdv 
                SET huong_dan_vien_id = ?, ngay = ?, loai_lich = ?, ghi_chu = ?, updated_at = NOW() 
                WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['huong_dan_vien_id'],
                $data['ngay'],
                $data['loai_lich'],
                $data['ghi_chu'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi update: " . $e->getMessage());
            return false;
        }
    }

    // Xóa lịch làm việc
    public function delete($id)
    {
        $sql = "DELETE FROM lich_lam_viec_hdv WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Lỗi delete: " . $e->getMessage());
            return false;
        }
    }

    // Kiểm tra trùng lịch
    public function kiemTraTrungLich($huong_dan_vien_id, $ngay)
    {
        $sql = "SELECT COUNT(*) as count FROM lich_lam_viec_hdv 
                WHERE huong_dan_vien_id = ? AND ngay = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$huong_dan_vien_id, $ngay]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Lỗi kiemTraTrungLich: " . $e->getMessage());
            return false;
        }
    }
}
?>