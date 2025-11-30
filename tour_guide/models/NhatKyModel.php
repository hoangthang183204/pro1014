<?php
class NhatKyModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy danh sách lịch khởi hành được phân công cho HDV (theo ID động)
    public function getAssignedTours($hdv_id)
    {
        $sql = "SELECT lkh.id, t.ten_tour, lkh.ngay_bat_dau, lkh.ngay_ket_thuc 
            FROM lich_khoi_hanh lkh
            JOIN tour t ON lkh.tour_id = t.id
            JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id
            WHERE pc.huong_dan_vien_id = ? 
            ORDER BY lkh.ngay_bat_dau DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdv_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách nhật ký của HDV
    public function getAllNhatKy($hdv_id)
    {
        $sql = "SELECT nk.*, t.ten_tour, lkh.ngay_bat_dau 
                FROM nhat_ky_tour nk
                JOIN lich_khoi_hanh lkh ON nk.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                WHERE nk.huong_dan_vien_id = ?
                ORDER BY nk.ngay_nhat_ky DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdv_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNhatKyById($id)
    {
        $sql = "SELECT * FROM nhat_ky_tour WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSuCoByDateAndTour($lich_khoi_hanh_id, $ngay)
    {
        $sql = "SELECT * FROM bao_cao_su_co WHERE lich_khoi_hanh_id = ? AND ngay_su_co = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_khoi_hanh_id, $ngay]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertNhatKy($lich_id, $hdv_id, $ngay, $thoi_tiet, $hoat_dong, $diem_nhan, $user_id)
    {
        $sql = "INSERT INTO nhat_ky_tour (lich_khoi_hanh_id, huong_dan_vien_id, ngay_nhat_ky, thoi_tiet, hoat_dong, diem_nhan, nguoi_tao) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_id, $hdv_id, $ngay, $thoi_tiet, $hoat_dong, $diem_nhan, $user_id]);
        return $this->conn->lastInsertId();
    }

    public function updateNhatKy($id, $thoi_tiet, $hoat_dong, $diem_nhan)
    {
        $sql = "UPDATE nhat_ky_tour SET thoi_tiet = ?, hoat_dong = ?, diem_nhan = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$thoi_tiet, $hoat_dong, $diem_nhan, $id]);
    }

    public function insertSuCo($lich_id, $hdv_id, $tieu_de, $ngay, $mo_ta, $cach_xu_ly, $muc_do, $user_id)
    {
        $sql = "INSERT INTO bao_cao_su_co (lich_khoi_hanh_id, huong_dan_vien_id, tieu_de, ngay_su_co, mo_ta_su_co, cach_xu_ly, muc_do_nghiem_trong, nguoi_tao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_id, $hdv_id, $tieu_de, $ngay, $mo_ta, $cach_xu_ly, $muc_do, $user_id]);
    }

    public function insertMedia($nhat_ky_id, $url, $user_id)
    {
        $sql = "INSERT INTO media_nhat_ky (nhat_ky_id, url, nguoi_tao) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nhat_ky_id, $url, $user_id]);
    }

    // Logic xóa đã sửa: Xóa Media trước -> Xóa Nhật ký sau
    public function deleteNhatKy($id)
    {
        // 1. Xóa ảnh trong bảng media_nhat_ky trước
        $sqlMedia = "DELETE FROM media_nhat_ky WHERE nhat_ky_id = ?";
        $stmtMedia = $this->conn->prepare($sqlMedia);
        $stmtMedia->execute([$id]);

        // 2. Sau đó mới xóa nhật ký
        $sql = "DELETE FROM nhat_ky_tour WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }
    public function getGuideIdFromUser($user_id)
    {
        $sql = "SELECT id FROM huong_dan_vien WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nếu tìm thấy thì trả về ID HDV, nếu không thì trả về false
        return $result ? $result['id'] : false;
    }
}