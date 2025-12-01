<?php
// Tệp: models/PersonalGuideModel.php

require_once __DIR__ . '/../models/Database.php';

class PersonalGuideModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Sửa lại phương thức getMyProfile để lấy theo nguoi_dung_id
    public function getMyProfile($guideId) {
        // guideId ở đây là nguoi_dung_id từ session
        $query = "SELECT * FROM huong_dan_vien WHERE nguoi_dung_id = ?";
        return $this->db->selectOne($query, [$guideId]);
    }

    // Tệp: models/PersonalGuideModel.php

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
            so_tour_da_dan = ?,  
            danh_gia_trung_binh = ?, 
            updated_at = NOW()
            WHERE nguoi_dung_id = ?";

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
        $data['so_tour_da_dan'],  
        $data['danh_gia_trung_binh'],  
        $guideId 
    ];

    return $this->db->executeQuery($sql, $params);
}
    
    public function updateAvatar($guideId, $avatarPath) {
        $sql = "UPDATE huong_dan_vien SET hinh_anh = ?, updated_at = NOW() WHERE nguoi_dung_id = ?";
        return $this->db->executeQuery($sql, [$avatarPath, $guideId]);
    }

    public function getGuideStats($guideId) {
        $query = "SELECT 
                    so_tour_da_dan,
                    danh_gia_trung_binh
                  FROM huong_dan_vien 
                  WHERE nguoi_dung_id = ?";
        return $this->db->selectOne($query, [$guideId]);
    }

    public function getActiveToursCount($guideId) {
        // Lấy id của hướng dẫn viên từ bảng huong_dan_vien
        $guideInfo = $this->getMyProfile($guideId);
        if (!$guideInfo || !isset($guideInfo['id'])) {
            return 0;
        }
        
        $query = "SELECT COUNT(*) as count 
                  FROM phan_cong pc
                  JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                  WHERE pc.huong_dan_vien_id = ? 
                  AND lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra')
                  AND pc.trang_thai_xac_nhan = 'đã xác nhận'";
        $result = $this->db->selectOne($query, [$guideInfo['id']]);
        return $result['count'] ?? 0;
    }

    // Thêm phương thức tạo profile
    public function createProfile($guideId, $data) {
        $sql = "INSERT INTO huong_dan_vien 
                (nguoi_dung_id, ho_ten, email, so_dien_thoai, ngay_sinh, dia_chi, 
                 loai_huong_dan_vien, ngon_ngu, kinh_nghiem, chuyen_mon, 
                 tinh_trang_suc_khoe, so_giay_phep_hanh_nghe, ngay_cap_giay_phep, 
                 noi_cap_giay_phep, trang_thai, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $guideId,
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
            $data['trang_thai']
        ];
        
        return $this->db->executeQuery($sql, $params);
    }
}
?>