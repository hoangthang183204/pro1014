<?php
class HDVDashboard
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
        // Tắt ONLY_FULL_GROUP_BY cho session hiện tại
        $this->conn->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    }

    // === CÁC PHƯƠNG THỨC CŨ (GIỮ NGUYÊN) ===

    /**
     * Lấy thông tin hướng dẫn viên từ nguoi_dung_id
     */
    public function getGuideInfo($userId)
    {
        $sql = "SELECT hdv.*, nd.ten_dang_nhap, nd.vai_tro 
                FROM huong_dan_vien hdv
                JOIN nguoi_dung nd ON hdv.nguoi_dung_id = nd.id
                WHERE hdv.nguoi_dung_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tour sắp khởi hành (7 ngày tới)
     */
    public function getTourSapKhoiHanh($hdvId)
    {
        $sql = "SELECT 
                    t.id as tour_id,
                    t.ten_tour,
                    t.ma_tour,
                    t.hinh_anh,
                    lkh.id as lich_khoi_hanh_id,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc,
                    lkh.gio_tap_trung,
                    lkh.diem_tap_trung,
                    lkh.trang_thai,
                    lkh.so_cho_toi_da,
                    lkh.so_cho_con_lai,
                    dm.ten_danh_muc,
                    pc.trang_thai_xac_nhan,
                    COUNT(DISTINCT pdt.id) as so_phieu_dat,
                    COUNT(DISTINCT kh.id) as so_khach
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                LEFT JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id 
                    AND pdt.trang_thai IN ('giữ chỗ', 'đã thanh toán')
                LEFT JOIN khach_hang kh ON pdt.id = kh.phieu_dat_tour_id
                LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND lkh.trang_thai IN ('đã lên lịch', 'đang đi')
                AND lkh.ngay_bat_dau BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                GROUP BY lkh.id, t.id, t.ten_tour, t.ma_tour, t.hinh_anh, 
                         lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.gio_tap_trung, 
                         lkh.diem_tap_trung, lkh.trang_thai, lkh.so_cho_toi_da, 
                         lkh.so_cho_con_lai, dm.ten_danh_muc, pc.trang_thai_xac_nhan
                ORDER BY lkh.ngay_bat_dau ASC
                LIMIT 5";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sự cố cần xử lý (30 ngày gần nhất) - VẪN GIỮ NHƯNG CONTROLLER KHÔNG GỌI
     */
    public function getSuCoCanXuLy($hdvId)
    {
        $sql = "SELECT 
                    bcs.id,
                    bcs.tieu_de,
                    bcs.ngay_su_co,
                    bcs.mo_ta_su_co,
                    bcs.cach_xu_ly,
                    bcs.muc_do_nghiem_trong,
                    bcs.thoi_gian_bao_cao,
                    t.ten_tour,
                    t.ma_tour,
                    lkh.id as lich_khoi_hanh_id,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc
                FROM bao_cao_su_co bcs
                JOIN lich_khoi_hanh lkh ON bcs.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                WHERE bcs.huong_dan_vien_id = ?
                AND bcs.thoi_gian_bao_cao >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                AND (bcs.cach_xu_ly IS NULL OR bcs.cach_xu_ly = '')
                ORDER BY 
                    CASE bcs.muc_do_nghiem_trong
                        WHEN 'nghiêm trọng' THEN 1
                        WHEN 'cao' THEN 2
                        WHEN 'trung bình' THEN 3
                        WHEN 'thấp' THEN 4
                        ELSE 5
                    END ASC,
                    bcs.thoi_gian_bao_cao DESC
                LIMIT 10";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê tổng quan
     */
    public function getThongKe($hdvId)
    {
        // Sử dụng subquery để tránh GROUP BY phức tạp
        $sql = "SELECT 
                    -- Tổng số tour đã được phân công
                    (SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên') as tong_tour,
                    
                    -- Tour đã hoàn thành
                    (SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên'
                     AND lkh.trang_thai = 'đã hoàn thành') as tour_hoan_thanh,
                    
                    -- Tour đang chạy
                    (SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên'
                     AND lkh.trang_thai = 'đang đi') as tour_dang_chay,
                    
                    -- Tour đã lên lịch
                    (SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên'
                     AND lkh.trang_thai = 'đã lên lịch') as tour_len_lich,
                    
                    -- Tour bị hủy
                    (SELECT COUNT(DISTINCT pc.lich_khoi_hanh_id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên'
                     AND lkh.trang_thai = 'đã hủy') as tour_huy,
                    
                    -- Số khách hàng đã phục vụ
                    (SELECT COUNT(DISTINCT kh.id)
                     FROM phan_cong pc
                     JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                     JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                     JOIN khach_hang kh ON pdt.id = kh.phieu_dat_tour_id
                     WHERE pc.huong_dan_vien_id = ?
                     AND pc.loai_phan_cong = 'hướng dẫn viên') as tong_khach,
                    
                    -- Sự cố chưa xử lý
                    (SELECT COUNT(DISTINCT bcs.id)
                     FROM bao_cao_su_co bcs
                     WHERE bcs.huong_dan_vien_id = ?
                     AND (bcs.cach_xu_ly IS NULL OR bcs.cach_xu_ly = '')) as su_co_chua_xu_ly,
                    
                    -- Sự cố đã xử lý
                    (SELECT COUNT(DISTINCT bcs.id)
                     FROM bao_cao_su_co bcs
                     WHERE bcs.huong_dan_vien_id = ?
                     AND bcs.cach_xu_ly IS NOT NULL AND bcs.cach_xu_ly != '') as su_co_da_xu_ly";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId, $hdvId, $hdvId, $hdvId, $hdvId, $hdvId, $hdvId, $hdvId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy doanh thu tháng hiện tại
     */
    public function getDoanhThuThang($hdvId)
    {
        $sql = "SELECT 
                    COALESCE(SUM(pdt.tong_tien), 0) as tong_doanh_thu,
                    COUNT(DISTINCT pdt.id) as so_don_hang,
                    COALESCE(AVG(pdt.tong_tien), 0) as trung_binh_don,
                    COALESCE(MIN(pdt.tong_tien), 0) as don_thap_nhat,
                    COALESCE(MAX(pdt.tong_tien), 0) as don_cao_nhat
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND MONTH(pdt.created_at) = MONTH(CURDATE())
                AND YEAR(pdt.created_at) = YEAR(CURDATE())
                AND pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ')";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy doanh thu theo tháng (6 tháng gần nhất)
     */
    public function getDoanhThuTheoThang($hdvId)
    {
        $sql = "SELECT 
                    DATE_FORMAT(pdt.created_at, '%Y-%m') as thang,
                    SUM(pdt.tong_tien) as doanh_thu,
                    COUNT(DISTINCT pdt.id) as so_don
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND pdt.created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ')
                GROUP BY DATE_FORMAT(pdt.created_at, '%Y-%m')
                ORDER BY thang DESC
                LIMIT 6";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy đánh giá trung bình và thống kê
     */
    public function getDanhGiaTrungBinh($hdvId)
    {
        $sql = "SELECT 
                    danh_gia_trung_binh as diem_trung_binh,
                    so_tour_da_dan as so_tour,
                    FLOOR(danh_gia_trung_binh) as so_sao_tron,
                    (danh_gia_trung_binh - FLOOR(danh_gia_trung_binh)) >= 0.5 as co_nua_sao
                FROM huong_dan_vien 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return [
                'diem_trung_binh' => 0,
                'so_tour' => 0,
                'so_sao_tron' => 0,
                'co_nua_sao' => false,
                'phan_tram' => 0
            ];
        }
        
        // Tính phần trăm (0-5 -> 0-100%)
        $result['phan_tram'] = ($result['diem_trung_binh'] / 5) * 100;
        
        return $result;
    }

    /**
     * Lấy lịch trình hôm nay
     */
    public function getLichTrinhHomNay($hdvId)
    {
        $today = date('Y-m-d');
        
        $sql = "SELECT 
                    t.id as tour_id,
                    t.ten_tour,
                    t.ma_tour,
                    lkh.id as lich_khoi_hanh_id,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc,
                    ltr.id as lich_trinh_id,
                    ltr.so_ngay,
                    ltr.tieu_de as ten_chi_tiet_dia_diem,
                    ltr.mo_ta_hoat_dong,
                    ltr.cho_o,
                    ltr.bua_an,
                    ltr.phuong_tien,
                    ltr.ghi_chu_hdv,
                    ltr.thu_tu_sap_xep
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                LEFT JOIN lich_trinh_tour ltr ON t.id = ltr.tour_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND (
                    (lkh.ngay_bat_dau <= ? AND lkh.ngay_ket_thuc >= ?) -- Tour đang diễn ra
                    OR 
                    (DATE(lkh.ngay_bat_dau) = ?) -- Tour bắt đầu hôm nay
                )
                AND (
                    ltr.so_ngay = 1 
                    OR ltr.so_ngay = DATEDIFF(?, lkh.ngay_bat_dau) + 1
                    OR ltr.id IS NULL
                )
                ORDER BY 
                    CASE 
                        WHEN lkh.trang_thai = 'đang đi' THEN 1
                        WHEN lkh.trang_thai = 'đã lên lịch' THEN 2
                        ELSE 3
                    END,
                    lkh.ngay_bat_dau ASC,
                    ltr.thu_tu_sap_xep ASC
                LIMIT 5";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId, $today, $today, $today, $today]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy nhật ký gần đây
     */
    public function getNhatKyGanDay($hdvId, $limit = 5)
    {
        $sql = "SELECT 
                    nk.id,
                    nk.ngay_nhat_ky,
                    nk.thoi_tiet,
                    nk.hoat_dong,
                    nk.diem_nhan,
                    nk.created_at,
                    t.ten_tour,
                    lkh.id as lich_khoi_hanh_id
                FROM nhat_ky_tour nk
                JOIN lich_khoi_hanh lkh ON nk.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                WHERE nk.huong_dan_vien_id = ?
                ORDER BY nk.ngay_nhat_ky DESC, nk.created_at DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $hdvId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thêm số ảnh cho mỗi nhật ký
        foreach ($results as &$result) {
            $sqlCount = "SELECT COUNT(*) as so_anh FROM media_nhat_ky WHERE nhat_ky_id = ?";
            $stmtCount = $this->conn->prepare($sqlCount);
            $stmtCount->execute([$result['id']]);
            $count = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $result['so_anh'] = $count['so_anh'] ?? 0;
        }
        
        return $results;
    }

    /**
     * Lấy checklist chưa hoàn thành - VẪN GIỮ NHƯNG CONTROLLER KHÔNG GỌI
     */
    public function getChecklistChuaHoanThanh($hdvId)
    {
        $sql = "SELECT 
                    ck.id,
                    ck.cong_viec,
                    ck.hoan_thanh,
                    ck.thoi_gian_hoan_thanh,
                    t.ten_tour,
                    lkh.id as lich_khoi_hanh_id,
                    lkh.ngay_bat_dau,
                    DATEDIFF(lkh.ngay_bat_dau, CURDATE()) as so_ngay_con_lai
                FROM checklist_truoc_tour ck
                JOIN lich_khoi_hanh lkh ON ck.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                JOIN phan_cong pc ON lkh.id = pc.lich_khoi_hanh_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND ck.hoan_thanh = 0
                AND lkh.trang_thai IN ('đã lên lịch', 'đang đi')
                AND lkh.ngay_bat_dau >= CURDATE()
                ORDER BY lkh.ngay_bat_dau ASC
                LIMIT 10";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê theo loại tour
     */
    public function getThongKeLoaiTour($hdvId)
    {
        $sql = "SELECT 
                    dm.ten_danh_muc,
                    dm.loai_tour,
                    COUNT(DISTINCT lkh.id) as so_tour,
                    COALESCE(SUM(pdt.tong_tien), 0) as tong_doanh_thu,
                    COUNT(DISTINCT pdt.id) as so_don,
                    COALESCE(AVG(pdt.tong_tien), 0) as trung_binh_don
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                LEFT JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND (pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ') OR pdt.id IS NULL)
                GROUP BY dm.id, dm.ten_danh_muc, dm.loai_tour
                ORDER BY so_tour DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê theo mùa
     */
    public function getThongKeTheoMua($hdvId)
    {
        $sql = "SELECT 
                    CASE 
                        WHEN MONTH(lkh.ngay_bat_dau) IN (1,2,3) THEN 'Mùa Xuân'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (4,5,6) THEN 'Mùa Hè'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (7,8,9) THEN 'Mùa Thu'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (10,11,12) THEN 'Mùa Đông'
                    END as mua,
                    COUNT(DISTINCT lkh.id) as so_tour,
                    COUNT(DISTINCT pdt.id) as so_don,
                    COALESCE(SUM(pdt.tong_tien), 0) as doanh_thu,
                    COALESCE(AVG(pdt.tong_tien), 0) as trung_binh_don
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                LEFT JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND (pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ') OR pdt.id IS NULL)
                GROUP BY 
                    CASE 
                        WHEN MONTH(lkh.ngay_bat_dau) IN (1,2,3) THEN 'Mùa Xuân'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (4,5,6) THEN 'Mùa Hè'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (7,8,9) THEN 'Mùa Thu'
                        WHEN MONTH(lkh.ngay_bat_dau) IN (10,11,12) THEN 'Mùa Đông'
                    END
                ORDER BY 
                    CASE 
                        WHEN MONTH(lkh.ngay_bat_dau) IN (1,2,3) THEN 1
                        WHEN MONTH(lkh.ngay_bat_dau) IN (4,5,6) THEN 2
                        WHEN MONTH(lkh.ngay_bat_dau) IN (7,8,9) THEN 3
                        WHEN MONTH(lkh.ngay_bat_dau) IN (10,11,12) THEN 4
                    END";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy top khách hàng thân thiết
     */
    public function getTopKhachHang($hdvId, $limit = 5)
    {
        $sql = "SELECT 
                    kh.id,
                    kh.ho_ten,
                    kh.email,
                    kh.so_dien_thoai,
                    COUNT(DISTINCT pdt.id) as so_lan_dat,
                    COALESCE(SUM(pdt.tong_tien), 0) as tong_chi_tieu,
                    MAX(pdt.created_at) as lan_dat_gan_nhat
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN phieu_dat_tour pdt ON lkh.id = pdt.lich_khoi_hanh_id
                JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ')
                GROUP BY kh.id, kh.ho_ten, kh.email, kh.so_dien_thoai
                ORDER BY so_lan_dat DESC, tong_chi_tieu DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $hdvId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông báo mới cho HDV
     */
    public function getThongBaoMoi($hdvId)
    {
        $sql = "SELECT 
                    'phan_cong' as loai,
                    pc.created_at as thoi_gian,
                    CONCAT('Bạn được phân công tour ', t.ten_tour) as noi_dung,
                    CONCAT('?act=lich-trinh-detail&id=', lkh.id) as link
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                WHERE pc.huong_dan_vien_id = ?
                AND pc.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                
                UNION ALL
                
                SELECT 
                    'su_co' as loai,
                    bcs.thoi_gian_bao_cao as thoi_gian,
                    CONCAT('Có sự cố mới: ', bcs.tieu_de) as noi_dung,
                    CONCAT('?act=nhat_ky&lich_id=', bcs.lich_khoi_hanh_id) as link
                FROM bao_cao_su_co bcs
                WHERE bcs.huong_dan_vien_id = ?
                AND bcs.thoi_gian_bao_cao >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                
                ORDER BY thoi_gian DESC
                LIMIT 10";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hdvId, $hdvId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === CÁC PHƯƠNG THỨC MỚI (CHO CALENDAR) ===

    /**
     * Lấy lịch làm việc của hướng dẫn viên
     */
    public function getLichLamViec($hdvId, $startDate = null, $endDate = null)
    {
        if (!$startDate) $startDate = date('Y-m-01');
        if (!$endDate) $endDate = date('Y-m-t');
        
        $sql = "SELECT 
                    ngay,
                    loai_lich,
                    ghi_chu,
                    created_at
                FROM lich_lam_viec 
                WHERE huong_dan_vien_id = :hdv_id 
                AND ngay BETWEEN :start_date AND :end_date
                ORDER BY ngay ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':hdv_id' => $hdvId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách lịch trình tour
     */
    public function getLichTrinhTours($hdvId)
    {
        $sql = "SELECT 
                    DISTINCT lkh.id as lich_khoi_hanh_id,
                    t.ma_tour,
                    t.ten_tour,
                    lkh.ngay_bat_dau,
                    lkh.ngay_ket_thuc,
                    lkh.trang_thai as trang_thai_lich,
                    pc.trang_thai_xac_nhan
                FROM phan_cong pc
                JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                JOIN tour t ON lkh.tour_id = t.id
                WHERE pc.huong_dan_vien_id = :hdv_id
                AND pc.loai_phan_cong = 'hướng dẫn viên'
                AND lkh.trang_thai IN ('đã lên lịch', 'đang đi', 'đã hoàn thành')
                ORDER BY lkh.ngay_bat_dau DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':hdv_id' => $hdvId]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thêm số khách cho mỗi tour
        foreach ($results as &$tour) {
            $sqlCount = "SELECT COUNT(DISTINCT kh.id) as so_khach
                        FROM phieu_dat_tour pdt
                        JOIN khach_hang kh ON pdt.id = kh.phieu_dat_tour_id
                        WHERE pdt.lich_khoi_hanh_id = :lich_id
                        AND pdt.trang_thai IN ('giữ chỗ', 'đã thanh toán')";
            
            $stmtCount = $this->conn->prepare($sqlCount);
            $stmtCount->execute([':lich_id' => $tour['lich_khoi_hanh_id']]);
            $count = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $tour['so_khach'] = $count['so_khach'] ?? 0;
        }
        
        return $results;
    }

    /**
     * Lấy sự kiện sắp tới trong N ngày
     */
    public function getUpcomingEvents($hdvId, $days = 7)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $events = [];
        
        // 1. Lấy lịch làm việc sắp tới
        $sqlLichLamViec = "SELECT 
                            ngay,
                            loai_lich,
                            ghi_chu
                        FROM lich_lam_viec 
                        WHERE huong_dan_vien_id = :hdv_id 
                        AND ngay BETWEEN :start_date AND :end_date
                        ORDER BY ngay ASC";
        
        $stmt1 = $this->conn->prepare($sqlLichLamViec);
        $stmt1->execute([
            ':hdv_id' => $hdvId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        $lichLamViec = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($lichLamViec as $item) {
            $daysUntil = floor((strtotime($item['ngay']) - strtotime($startDate)) / (60 * 60 * 24));
            $eventTitle = $this->getEventTitle($item['loai_lich'], $item['ghi_chu']);
            
            $events[] = [
                'date' => $item['ngay'],
                'type' => $item['loai_lich'],
                'title' => $eventTitle,
                'ghi_chu' => $item['ghi_chu'],
                'days_until' => $daysUntil
            ];
        }
        
        // 2. Lấy tour sắp khởi hành trong khoảng thời gian
        $sqlTour = "SELECT 
                        DISTINCT lkh.id as lich_khoi_hanh_id,
                        t.ma_tour,
                        t.ten_tour,
                        lkh.ngay_bat_dau,
                        lkh.ngay_ket_thuc,
                        pc.trang_thai_xac_nhan
                    FROM phan_cong pc
                    JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    WHERE pc.huong_dan_vien_id = :hdv_id
                    AND pc.loai_phan_cong = 'hướng dẫn viên'
                    AND lkh.ngay_bat_dau BETWEEN :start_date AND :end_date
                    AND lkh.trang_thai IN ('đã lên lịch', 'đang đi')
                    ORDER BY lkh.ngay_bat_dau ASC";
        
        $stmt2 = $this->conn->prepare($sqlTour);
        $stmt2->execute([
            ':hdv_id' => $hdvId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        $tours = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($tours as $tour) {
            $daysUntil = floor((strtotime($tour['ngay_bat_dau']) - strtotime($startDate)) / (60 * 60 * 24));
            
            // Thêm số khách
            $sqlKhach = "SELECT COUNT(DISTINCT kh.id) as so_khach
                        FROM phieu_dat_tour pdt
                        JOIN khach_hang kh ON pdt.id = kh.phieu_dat_tour_id
                        WHERE pdt.lich_khoi_hanh_id = :lich_id
                        AND pdt.trang_thai IN ('giữ chỗ', 'đã thanh toán')";
            
            $stmtKhach = $this->conn->prepare($sqlKhach);
            $stmtKhach->execute([':lich_id' => $tour['lich_khoi_hanh_id']]);
            $khach = $stmtKhach->fetch(PDO::FETCH_ASSOC);
            
            $events[] = [
                'date' => $tour['ngay_bat_dau'],
                'type' => 'tour',
                'title' => 'Tour: ' . $tour['ten_tour'],
                'ghi_chu' => 'Khởi hành tour ' . $tour['ma_tour'],
                'days_until' => $daysUntil,
                'tour_data' => [
                    'ten_tour' => $tour['ten_tour'],
                    'ma_tour' => $tour['ma_tour'],
                    'so_khach' => $khach['so_khach'] ?? 0,
                    'trang_thai_xac_nhan' => $tour['trang_thai_xac_nhan']
                ]
            ];
        }
        
        // Sắp xếp theo ngày
        usort($events, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        return $events;
    }

    /**
     * Lấy thống kê calendar
     */
    public function getCalendarStats($hdvId)
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        $sql = "SELECT 
                    SUM(CASE WHEN loai_lich = 'đã phân công' THEN 1 ELSE 0 END) as tour_days,
                    SUM(CASE WHEN loai_lich = 'bận' THEN 1 ELSE 0 END) as busy_days,
                    SUM(CASE WHEN loai_lich = 'nghỉ' THEN 1 ELSE 0 END) as off_days
                FROM lich_lam_viec 
                WHERE huong_dan_vien_id = :hdv_id 
                AND ngay BETWEEN :start_date AND :end_date";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':hdv_id' => $hdvId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'tour_days' => (int)($result['tour_days'] ?? 0),
            'busy_days' => (int)($result['busy_days'] ?? 0),
            'off_days' => (int)($result['off_days'] ?? 0)
        ];
    }

    /**
     * Helper function: Tạo tiêu đề sự kiện
     */
    private function getEventTitle($loaiLich, $ghiChu)
    {
        $titles = [
            'đã phân công' => 'Có tour',
            'bận' => 'Bận',
            'nghỉ' => 'Nghỉ',
            'có thể làm' => 'Có thể làm'
        ];
        
        $title = $titles[$loaiLich] ?? $loaiLich;
        if ($ghiChu && strlen($ghiChu) > 0) {
            $title .= ': ' . $ghiChu;
        }
        
        return $title;
    }
}
?>