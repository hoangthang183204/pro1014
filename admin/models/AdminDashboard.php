<?php
class AdminDashboard
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getThongKeTongQuan()
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM tours WHERE trang_thai = 'đang_hoạt_động') as tong_tour,
                        (SELECT COUNT(*) FROM lich_khoi_hanh WHERE trang_thai = 'đã_lên_lịch' AND ngay_bat_dau >= CURDATE()) as tour_sap_khoi_hanh,
                        (SELECT COUNT(*) FROM bao_cao_su_co WHERE DATE(thoi_gian_bao_cao) = CURDATE()) as su_co_hom_nay,
                        (SELECT COUNT(*) FROM huong_dan_vien WHERE trang_thai = 'đang_làm_việc') as hdv_dang_lam";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Log lỗi nếu cần
            return [
                'tong_tour' => 0,
                'tour_sap_khoi_hanh' => 0,
                'su_co_hom_nay' => 0,
                'hdv_dang_lam' => 0
            ];
        }
    }

    public function getTourSapKhoiHanh($limit = 5)
    {
        try {
            $query = "SELECT lkh.id, t.ma_tour, t.ten_tour, lkh.ngay_bat_dau, hdv.ten_hdv
                      FROM lich_khoi_hanh lkh
                      JOIN tours t ON lkh.tour_id = t.id
                      LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hdv'
                      LEFT JOIN huong_dan_vien hdv ON pc.hdv_id = hdv.id
                      WHERE lkh.trang_thai = 'đã_lên_lịch' 
                      AND lkh.ngay_bat_dau >= CURDATE()
                      ORDER BY lkh.ngay_bat_dau ASC 
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getSuCoCanXuLy($limit = 5)
    {
        try {
            $query = "SELECT bsc.tieu_de, bsc.muc_do_nghiem_trong, bsc.thoi_gian_bao_cao, 
                             t.ten_tour, hdv.ten_hdv
                      FROM bao_cao_su_co bsc
                      JOIN lich_khoi_hanh lkh ON bsc.lich_khoi_hanh_id = lkh.id
                      JOIN tours t ON lkh.tour_id = t.id
                      JOIN huong_dan_vien hdv ON bsc.hdv_id = hdv.id
                      WHERE bsc.muc_do_nghiem_trong IN ('cao', 'nghiêm_trọng')
                      ORDER BY bsc.thoi_gian_bao_cao DESC
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>