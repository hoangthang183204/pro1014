<?php
class BaoNghiModel extends Database {
    
    public function getAllByGuideId($huong_dan_vien_id) {
        $sql = "SELECT 
                    bn.*,
                    hdv.ho_ten,
                    hdv.so_dien_thoai,
                    hdv.email,
                    hdv.trang_thai as trang_thai_hdv
                FROM bao_nghi bn
                LEFT JOIN huong_dan_vien hdv ON bn.huong_dan_vien_id = hdv.id
                WHERE bn.huong_dan_vien_id = :huong_dan_vien_id
                ORDER BY bn.ngay_tao DESC";
        
        try {
            $this->query($sql);
            $this->bind(':huong_dan_vien_id', $huong_dan_vien_id);
            $result = $this->resultSet();
            return $result ?: [];
        } catch (Exception $e) {
            error_log("Error in getAllByGuideId: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy yêu cầu nghỉ theo ID
     */
    public function getById($id, $huong_dan_vien_id = null) {
        $sql = "SELECT bn.*, hdv.ho_ten, hdv.so_dien_thoai, hdv.email, hdv.trang_thai as trang_thai_hdv 
                FROM bao_nghi bn
                LEFT JOIN huong_dan_vien hdv ON bn.huong_dan_vien_id = hdv.id
                WHERE bn.id = :id";
        
        if ($huong_dan_vien_id) {
            $sql .= " AND bn.huong_dan_vien_id = :huong_dan_vien_id";
        }
        
        try {
            $this->query($sql);
            $this->bind(':id', $id);
            if ($huong_dan_vien_id) {
                $this->bind(':huong_dan_vien_id', $huong_dan_vien_id);
            }
            return $this->single();
        } catch (Exception $e) {
            error_log("Error in getById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Tạo yêu cầu nghỉ mới
     */
    public function create($data) {
        $sql = "INSERT INTO bao_nghi (
                    ma_yeu_cau,
                    huong_dan_vien_id,
                    loai_nghi,
                    ngay_bat_dau,
                    ngay_ket_thuc,
                    ly_do,
                    file_dinh_kem,
                    trang_thai,
                    ghi_chu_quan_tri,
                    ngay_tao,
                    nguoi_tao
                ) VALUES (
                    :ma_yeu_cau,
                    :huong_dan_vien_id,
                    :loai_nghi,
                    :ngay_bat_dau,
                    :ngay_ket_thuc,
                    :ly_do,
                    :file_dinh_kem,
                    :trang_thai,
                    :ghi_chu_quan_tri,
                    NOW(),
                    :nguoi_tao
                )";
        
        try {
            $this->query($sql);
            
            // Bind các tham số
            $this->bind(':ma_yeu_cau', $data['ma_yeu_cau']);
            $this->bind(':huong_dan_vien_id', $data['huong_dan_vien_id']);
            $this->bind(':loai_nghi', $data['loai_nghi']);
            $this->bind(':ngay_bat_dau', $data['ngay_bat_dau']);
            $this->bind(':ngay_ket_thuc', $data['ngay_ket_thuc'] ?? null);
            $this->bind(':ly_do', $data['ly_do']);
            $this->bind(':file_dinh_kem', $data['file_dinh_kem'] ?? null);
            $this->bind(':trang_thai', $data['trang_thai']);
            $this->bind(':ghi_chu_quan_tri', $data['ghi_chu_quan_tri'] ?? null);
            $this->bind(':nguoi_tao', $data['nguoi_tao']);
            
            $result = $this->execute();
            return $result;
        } catch (Exception $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật yêu cầu nghỉ
     */
    public function update($id, $data) {
        $sql = "UPDATE bao_nghi SET
                    loai_nghi = :loai_nghi,
                    ngay_bat_dau = :ngay_bat_dau,
                    ngay_ket_thuc = :ngay_ket_thuc,
                    ly_do = :ly_do,
                    file_dinh_kem = :file_dinh_kem,
                    trang_thai = :trang_thai,
                    ghi_chu_quan_tri = :ghi_chu_quan_tri,
                    ngay_cap_nhat = NOW(),
                    nguoi_cap_nhat = :nguoi_cap_nhat
                WHERE id = :id";
        
        try {
            $this->query($sql);
            
            // Bind các tham số
            $this->bind(':id', $id);
            $this->bind(':loai_nghi', $data['loai_nghi']);
            $this->bind(':ngay_bat_dau', $data['ngay_bat_dau']);
            $this->bind(':ngay_ket_thuc', $data['ngay_ket_thuc'] ?? null);
            $this->bind(':ly_do', $data['ly_do']);
            $this->bind(':file_dinh_kem', $data['file_dinh_kem'] ?? null);
            $this->bind(':trang_thai', $data['trang_thai']);
            $this->bind(':ghi_chu_quan_tri', $data['ghi_chu_quan_tri'] ?? null);
            $this->bind(':nguoi_cap_nhat', $data['nguoi_cap_nhat'] ?? $data['huong_dan_vien_id']);
            
            $result = $this->execute();
            return $result;
        } catch (Exception $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa yêu cầu nghỉ
     */
    public function delete($id, $huong_dan_vien_id = null) {
        $sql = "DELETE FROM bao_nghi WHERE id = :id";
        
        if ($huong_dan_vien_id) {
            $sql .= " AND huong_dan_vien_id = :huong_dan_vien_id";
        }
        
        try {
            $this->query($sql);
            $this->bind(':id', $id);
            
            if ($huong_dan_vien_id) {
                $this->bind(':huong_dan_vien_id', $huong_dan_vien_id);
            }
            
            $result = $this->execute();
            return $result;
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đếm số ngày nghỉ trong tháng
     */
    public function countDaysOffThisMonth($huong_dan_vien_id, $month = null, $year = null) {
        $currentMonth = $month ?? date('m');
        $currentYear = $year ?? date('Y');
        $firstDay = "$currentYear-$currentMonth-01";
        $lastDay = date('Y-m-t', strtotime($firstDay));
        
        $sql = "SELECT 
                    SUM(
                        CASE 
                            WHEN ngay_ket_thuc IS NULL OR ngay_ket_thuc = '' OR ngay_ket_thuc = ngay_bat_dau THEN 1
                            ELSE 
                                DATEDIFF(
                                    LEAST(ngay_ket_thuc, :last_day),
                                    GREATEST(ngay_bat_dau, :first_day)
                                ) + 1
                        END
                    ) as total_days
                FROM bao_nghi
                WHERE huong_dan_vien_id = :huong_dan_vien_id
                AND trang_thai = 'da_duyet'
                AND (
                    (ngay_bat_dau <= :last_day) 
                    AND 
                    (COALESCE(ngay_ket_thuc, ngay_bat_dau) >= :first_day)
                )";
        
        try {
            $this->query($sql);
            $this->bind(':huong_dan_vien_id', $huong_dan_vien_id);
            $this->bind(':first_day', $firstDay);
            $this->bind(':last_day', $lastDay);
            
            $result = $this->single();
            return $result ?: ['total_days' => 0];
        } catch (Exception $e) {
            error_log("Error in countDaysOffThisMonth: " . $e->getMessage());
            return ['total_days' => 0];
        }
    }
    
    /**
     * Lấy thống kê nghỉ
     */
    public function getStats($huong_dan_vien_id) {
        $sql = "SELECT 
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN trang_thai = 'cho_duyet' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN trang_thai = 'da_duyet' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN trang_thai = 'tu_choi' THEN 1 ELSE 0 END) as rejected
                FROM bao_nghi
                WHERE huong_dan_vien_id = :huong_dan_vien_id";
        
        try {
            $this->query($sql);
            $this->bind(':huong_dan_vien_id', $huong_dan_vien_id);
            
            $result = $this->single();
            
            // Trả về đầy đủ các key cần thiết
            if ($result) {
                $result['pending_requests'] = $result['pending'] ?? 0;
                $result['approved_requests'] = $result['approved'] ?? 0;
                $result['rejected_requests'] = $result['rejected'] ?? 0;
            } else {
                $result = [
                    'total_requests' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'pending_requests' => 0,
                    'approved_requests' => 0,
                    'rejected_requests' => 0
                ];
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in getStats: " . $e->getMessage());
            return [
                'total_requests' => 0,
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'pending_requests' => 0,
                'approved_requests' => 0,
                'rejected_requests' => 0
            ];
        }
    }
    
    /**
     * Lấy tất cả yêu cầu thay đổi trạng thái (cho admin)
     */
    public function getAllStatusChangeRequests() {
        $sql = "SELECT 
                    bn.*,
                    hdv.ho_ten,
                    hdv.so_dien_thoai,
                    hdv.email,
                    hdv.trang_thai as trang_thai_hien_tai
                FROM bao_nghi bn
                LEFT JOIN huong_dan_vien hdv ON bn.huong_dan_vien_id = hdv.id
                WHERE bn.loai_nghi = 'thay_doi_trang_thai'
                ORDER BY bn.ngay_tao DESC";
        
        try {
            $this->query($sql);
            return $this->resultSet() ?: [];
        } catch (Exception $e) {
            error_log("Error in getAllStatusChangeRequests: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tất cả yêu cầu nghỉ (cho admin)
     */
    public function getAllRequests() {
        $sql = "SELECT 
                    bn.*,
                    hdv.ho_ten,
                    hdv.so_dien_thoai,
                    hdv.email,
                    hdv.trang_thai as trang_thai_hdv
                FROM bao_nghi bn
                LEFT JOIN huong_dan_vien hdv ON bn.huong_dan_vien_id = hdv.id
                ORDER BY bn.ngay_tao DESC";
        
        try {
            $this->query($sql);
            return $this->resultSet() ?: [];
        } catch (Exception $e) {
            error_log("Error in getAllRequests: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Phương thức hỗ trợ để lấy ID vừa insert
     */
    public function lastInsertId() {
        return parent::lastInsertId();
    }
}
?>