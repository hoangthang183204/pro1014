<?php
// Tệp: models/PersonalGuideModel.php

require_once __DIR__ . '/../models/Database.php';

class PersonalGuideModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMyProfile($guideId) {
        $query = "SELECT * FROM huong_dan_vien WHERE id = ?";
        return $this->db->selectOne($query, [$guideId]);
    }

    public function updateProfile($guideId, $data) {
        $sql = "UPDATE huong_dan_vien SET 
                ho_ten = ?, 
                email = ?, 
                so_dien_thoai = ?, 
                ngay_sinh = ?, 
                dia_chi = ?, 
                loai_huong_dan_vien = ?, 
                ngon_ngu = ?, 
                kinh_nghiem = ?, 
                chuyen_mon = ?, 
                tinh_trang_suc_khoe = ?,
                so_giay_phep_hanh_nghe = ?, 
                ngay_cap_giay_phep = ?, 
                noi_cap_giay_phep = ?,
                updated_at = NOW()
                WHERE id = ?";

        $params = [
            $data['ho_ten'], 
            $data['email'], 
            $data['so_dien_thoai'], 
            $data['ngay_sinh'], 
            $data['dia_chi'], 
            $data['loai_huong_dan_vien'],
            $data['ngon_ngu'],
            $data['kinh_nghiem'],
            $data['chuyen_mon'],
            $data['tinh_trang_suc_khoe'],
            $data['so_giay_phep_hanh_nghe'],
            $data['ngay_cap_giay_phep'],
            $data['noi_cap_giay_phep'],
            $guideId
        ];

        return $this->db->executeQuery($sql, $params);
    }
    
    public function updateAvatar($guideId, $avatarPath) {
        $sql = "UPDATE huong_dan_vien SET hinh_anh = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->executeQuery($sql, [$avatarPath, $guideId]);
    }

    public function getGuideStats($guideId) {
        $query = "SELECT 
                    so_tour_da_dan,
                    danh_gia_trung_binh
                  FROM huong_dan_vien 
                  WHERE id = ?";
        return $this->db->selectOne($query, [$guideId]);
    }

    public function getActiveToursCount($guideId) {
        $query = "SELECT COUNT(*) as count 
                  FROM phan_cong pc
                  JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                  WHERE pc.huong_dan_vien_id = ? 
                  AND lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra')
                  AND pc.trang_thai_xac_nhan = 'đã xác nhận'";
        $result = $this->db->selectOne($query, [$guideId]);
        return $result['count'] ?? 0;
    }
}
?>