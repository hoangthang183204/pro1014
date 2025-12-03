<?php
class AdminHuongDanVien
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllHDV()
    {
        try {
            $sql = "SELECT 
                    hdv.*,
                    COALESCE((
                        SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                        FROM phan_cong pc
                        WHERE pc.huong_dan_vien_id = hdv.id
                        AND pc.loai_phan_cong = 'hướng dẫn viên'
                        AND pc.trang_thai_xac_nhan = 'đã xác nhận'
                    ), 0) as so_tour_da_dan
                    FROM huong_dan_vien hdv
                    ORDER BY hdv.id DESC";
            
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as &$hdv) {
                if (!empty($hdv['ngon_ngu'])) {
                    $ngon_ngu = json_decode($hdv['ngon_ngu'], true);
                    $hdv['ngon_ngu'] = is_array($ngon_ngu) ? $ngon_ngu : [];
                } else {
                    $hdv['ngon_ngu'] = [];
                }
                
                $hdv['so_tour_da_dan'] = (int)$hdv['so_tour_da_dan'];
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi getAllHDV: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT 
                    hdv.*,
                    COALESCE((
                        SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                        FROM phan_cong pc
                        WHERE pc.huong_dan_vien_id = hdv.id
                        AND pc.loai_phan_cong = 'hướng dẫn viên'
                        AND pc.trang_thai_xac_nhan = 'đã xác nhận'
                    ), 0) as so_tour_da_dan
                    FROM huong_dan_vien hdv
                    WHERE hdv.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $hdv = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hdv) {
                // Xử lý JSON ngôn ngữ
                if (!empty($hdv['ngon_ngu'])) {
                    $ngon_ngu = json_decode($hdv['ngon_ngu'], true);
                    $hdv['ngon_ngu'] = is_array($ngon_ngu) ? $ngon_ngu : [];
                } else {
                    $hdv['ngon_ngu'] = [];
                }

                if (is_null($hdv['danh_gia_trung_binh'])) {
                    $hdv['danh_gia_trung_binh'] = 0;
                }
                
                $hdv['so_tour_da_dan'] = (int)$hdv['so_tour_da_dan'];
            }

            return $hdv;
        } catch (PDOException $e) {
            error_log("Lỗi getById: " . $e->getMessage());
            return null;
        }
    }
    
    public function getUserInfo($user_id)
    {
        try {
            $sql = "SELECT ten_dang_nhap, vai_tro FROM nguoi_dung WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getUserInfo: " . $e->getMessage());
            return null;
        }
    }

    public function getLichLamViecHDV($hdv_id, $limit = 5)
    {
        try {
            $sql = "SELECT 
                        llv.id,
                        llv.ngay,
                        llv.loai_lich,
                        llv.ghi_chu,
                        llv.created_at,
                        DATE_FORMAT(llv.ngay, '%W') as thu
                    FROM lich_lam_viec_hdv llv
                    WHERE llv.huong_dan_vien_id = ? 
                    ORDER BY llv.ngay DESC 
                    LIMIT ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $hdv_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as &$row) {
                $thu = $row['thu'] ?? '';
                $thu_viet = [
                    'Monday' => 'Thứ Hai',
                    'Tuesday' => 'Thứ Ba',
                    'Wednesday' => 'Thứ Tư',
                    'Thursday' => 'Thứ Năm',
                    'Friday' => 'Thứ Sáu',
                    'Saturday' => 'Thứ Bảy',
                    'Sunday' => 'Chủ Nhật'
                ];
                $row['thu_viet'] = $thu_viet[$thu] ?? $thu;
                unset($row['thu']); 
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi getLichLamViecHDV: " . $e->getMessage());
            return [];
        }
    }

    public function getTourDaDan($hdv_id, $limit = 5)
    {
        try {
            $sql = "SELECT 
                        pc.id as phan_cong_id,
                        pc.lich_khoi_hanh_id,
                        pc.trang_thai_xac_nhan,
                        pc.created_at as ngay_phan_cong,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        lkh.trang_thai as trang_thai_tour,
                        t.id as tour_id,
                        t.ma_tour,
                        t.ten_tour
                    FROM phan_cong pc
                    INNER JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    INNER JOIN tour t ON lkh.tour_id = t.id
                    WHERE pc.huong_dan_vien_id = ? 
                    AND pc.loai_phan_cong = 'hướng dẫn viên'
                    AND pc.trang_thai_xac_nhan = 'đã xác nhận'
                    ORDER BY lkh.ngay_bat_dau DESC 
                    LIMIT ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $hdv_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getTourDaDan: " . $e->getMessage());
            return [];
        }
    }
}
?>