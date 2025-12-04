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
                        (SELECT COUNT(*) FROM tour WHERE trang_thai = 'đang hoạt động') as tong_tour,
                        (SELECT COUNT(*) FROM lich_khoi_hanh WHERE trang_thai = 'đã lên lịch' AND ngay_bat_dau >= CURDATE()) as tour_sap_khoi_hanh,
                        (SELECT COUNT(*) FROM bao_cao_su_co WHERE DATE(thoi_gian_bao_cao) = CURDATE()) as su_co_hom_nay,
                        (SELECT COUNT(*) FROM huong_dan_vien WHERE trang_thai = 'đang làm việc') as hdv_dang_lam";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $thongKe = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Thêm dữ liệu biểu đồ
            $thongKe['tour_theo_thang'] = $this->getTourTheoThang(date('Y'));
            $thongKe['tour_hoan_thanh_theo_thang'] = $this->getTourHoanThanhTheoThang(date('Y'));
            $thongKe['tour_da_len_lich'] = $this->countTourByStatus('đã lên lịch');
            $thongKe['tour_dang_dien_ra'] = $this->countTourByStatus('đang đi');
            $thongKe['tour_da_hoan_thanh'] = $this->countTourByStatus('đã hoàn thành');
            $thongKe['tour_da_huy'] = $this->countTourByStatus('đã hủy');
            
            return $thongKe;
            
        } catch (PDOException $e) {
            error_log("Dashboard Error: " . $e->getMessage());
            return [
                'tong_tour' => 0,
                'tour_sap_khoi_hanh' => 0,
                'su_co_hom_nay' => 0,
                'hdv_dang_lam' => 0,
                'tour_theo_thang' => array_fill(0, 12, 0),
                'tour_hoan_thanh_theo_thang' => array_fill(0, 12, 0),
                'tour_da_len_lich' => 0,
                'tour_dang_dien_ra' => 0,
                'tour_da_hoan_thanh' => 0,
                'tour_da_huy' => 0
            ];
        }
    }

    // Lấy số tour theo tháng
    public function getTourTheoThang($nam) {
        try {
            $query = "SELECT MONTH(ngay_bat_dau) as thang, COUNT(*) as so_luong 
                      FROM lich_khoi_hanh 
                      WHERE YEAR(ngay_bat_dau) = :nam 
                      GROUP BY MONTH(ngay_bat_dau) 
                      ORDER BY thang";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':nam' => $nam]);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array_fill(0, 12, 0); // Index 0-11 cho tháng 1-12
            
            foreach ($result as $row) {
                $data[$row['thang'] - 1] = (int)$row['so_luong'];
            }
            
            return $data;
        } catch (PDOException $e) {
            error_log("Lỗi getTourTheoThang: " . $e->getMessage());
            return array_fill(0, 12, 0);
        }
    }

    // Lấy số tour đã hoàn thành theo tháng
    public function getTourHoanThanhTheoThang($nam) {
        try {
            $query = "SELECT MONTH(ngay_ket_thuc) as thang, COUNT(*) as so_luong 
                      FROM lich_khoi_hanh 
                      WHERE YEAR(ngay_ket_thuc) = :nam AND trang_thai = 'đã hoàn thành'
                      GROUP BY MONTH(ngay_ket_thuc) 
                      ORDER BY thang";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':nam' => $nam]);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array_fill(0, 12, 0);
            
            foreach ($result as $row) {
                $data[$row['thang'] - 1] = (int)$row['so_luong'];
            }
            
            return $data;
        } catch (PDOException $e) {
            error_log("Lỗi getTourHoanThanhTheoThang: " . $e->getMessage());
            return array_fill(0, 12, 0);
        }
    }

    // Đếm tour theo trạng thái
    public function countTourByStatus($trang_thai) {
        try {
            $query = "SELECT COUNT(*) as so_luong 
                      FROM lich_khoi_hanh 
                      WHERE trang_thai = :trang_thai";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':trang_thai' => $trang_thai]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['so_luong'] ?? 0;
        } catch (PDOException $e) {
            error_log("Lỗi countTourByStatus: " . $e->getMessage());
            return 0;
        }
    }

    public function getTourSapKhoiHanh($limit = 5)
    {
        try {
            $query = "SELECT lkh.id, t.ma_tour, t.ten_tour, lkh.ngay_bat_dau, 
                             hdv.ho_ten as ten_hdv, lkh.so_cho_con_lai
                      FROM lich_khoi_hanh lkh
                      JOIN tour t ON lkh.tour_id = t.id
                      LEFT JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id AND pc.loai_phan_cong = 'hướng dẫn viên'
                      LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
                      WHERE lkh.trang_thai = 'đã lên lịch' 
                      AND lkh.ngay_bat_dau >= CURDATE()
                      ORDER BY lkh.ngay_bat_dau ASC 
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Tour Sap Khoi Hanh Error: " . $e->getMessage());
            return [];
        }
    }

    public function getSuCoCanXuLy($limit = 5)
    {
        try {
            $query = "SELECT bsc.tieu_de, bsc.muc_do_nghiem_trong, bsc.thoi_gian_bao_cao, 
                             t.ten_tour, hdv.ho_ten as ten_hdv
                      FROM bao_cao_su_co bsc
                      JOIN lich_khoi_hanh lkh ON bsc.lich_khoi_hanh_id = lkh.id
                      JOIN tour t ON lkh.tour_id = t.id
                      JOIN huong_dan_vien hdv ON bsc.huong_dan_vien_id = hdv.id
                      WHERE bsc.muc_do_nghiem_trong IN ('cao', 'nghiêm trọng')
                      ORDER BY bsc.thoi_gian_bao_cao DESC
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Su Co Can Xu Ly Error: " . $e->getMessage());
            return [];
        }
    }

    // Thêm phương thức mới để lấy doanh thu
    public function getDoanhThuThang($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');
            
            $query = "SELECT COALESCE(SUM(tong_tien), 0) as doanh_thu
                      FROM phieu_dat_tour 
                      WHERE MONTH(created_at) = :thang 
                      AND YEAR(created_at) = :nam
                      AND trang_thai IN ('hoàn tất', 'đã cọc')";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':thang', $thang, PDO::PARAM_INT);
            $stmt->bindValue(':nam', $nam, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['doanh_thu'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Doanh Thu Error: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy số booking mới trong tháng
    public function getBookingMoiThang()
    {
        try {
            $query = "SELECT COUNT(*) as booking_moi
                      FROM phieu_dat_tour 
                      WHERE MONTH(created_at) = MONTH(CURDATE()) 
                      AND YEAR(created_at) = YEAR(CURDATE())
                      AND trang_thai = 'chưa thanh toán'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['booking_moi'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Booking Moi Error: " . $e->getMessage());
            return 0;
        }
    }

    // Lấy dữ liệu doanh thu theo tháng
    public function getDoanhThuTheoThang($nam) {
        try {
            $query = "SELECT MONTH(created_at) as thang, COALESCE(SUM(tong_tien), 0) as doanh_thu
                      FROM phieu_dat_tour 
                      WHERE YEAR(created_at) = :nam 
                      AND trang_thai IN ('đã thanh toán', 'giữ chỗ')
                      GROUP BY MONTH(created_at) 
                      ORDER BY thang";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':nam' => $nam]);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array_fill(0, 12, 0);
            
            foreach ($result as $row) {
                $data[$row['thang'] - 1] = (float)$row['doanh_thu'];
            }
            
            return $data;
        } catch (PDOException $e) {
            error_log("Lỗi getDoanhThuTheoThang: " . $e->getMessage());
            return array_fill(0, 12, 0);
        }
    }
}
?>