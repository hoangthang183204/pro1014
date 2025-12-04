<?php
class LichTrinhModel
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $this->db = new Database();
    }

    /**
     * Lấy ID hướng dẫn viên từ user_id
     */
    public function getGuideIdByUserId($userId)
    {
        $query = "SELECT id FROM huong_dan_vien WHERE nguoi_dung_id = ?";

        $this->db->query($query);
        $this->db->bind(1, $userId);
        $result = $this->db->single();

        return $result ? $result['id'] : null;
    }

    /**
     * Lấy thông tin hướng dẫn viên từ user_id
     */
    public function getGuideByUserId($userId)
    {
        $query = "SELECT * FROM huong_dan_vien WHERE nguoi_dung_id = ?";

        $this->db->query($query);
        $this->db->bind(1, $userId);
        return $this->db->single();
    }

    /**
     * Lấy danh sách lịch trình tour của hướng dẫn viên
     */
    public function getLichTrinhByGuideId($guideId)
    {
        $query = "
            SELECT 
                pc.id as phan_cong_id,
                lkh.id as lich_khoi_hanh_id,
                t.ma_tour,
                t.ten_tour,
                t.hinh_anh,
                dm.ten_danh_muc,
                lkh.ngay_bat_dau,
                lkh.ngay_ket_thuc,
                lkh.gio_tap_trung,
                lkh.diem_tap_trung,
                lkh.so_cho_toi_da,
                lkh.so_cho_con_lai,
                lkh.trang_thai as trang_thai_lich,
                pc.trang_thai_xac_nhan,
                pc.ghi_chu as ghi_chu_phan_cong
            FROM phan_cong pc
            JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
            WHERE pc.huong_dan_vien_id = ?
            AND pc.loai_phan_cong = 'hướng dẫn viên'
            AND lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra')
            ORDER BY lkh.ngay_bat_dau ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $guideId);
        return $this->db->resultSet();
    }

    /**
     * Lấy chi tiết một lịch trình cụ thể
     */
    public function getChiTietLichTrinh($lichKhoiHanhId, $guideId)
    {
        // 1. Lấy thông tin chung của tour
        $query = "
            SELECT 
                pc.id as phan_cong_id,
                lkh.*,
                t.*,
                dm.ten_danh_muc,
                hdv.ho_ten as ten_huong_dan_vien,
                hdv.so_dien_thoai as sdt_hdv,
                pc.trang_thai_xac_nhan,
                pc.ghi_chu as ghi_chu_phan_cong,
                cs.quy_dinh_huy_doi,
                cs.luu_y_suc_khoe,
                cs.luu_y_hanh_ly,
                cs.luu_y_khac
            FROM phan_cong pc
            JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
            LEFT JOIN huong_dan_vien hdv ON pc.huong_dan_vien_id = hdv.id
            LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id
            WHERE lkh.id = ?
            AND pc.huong_dan_vien_id = ?
            AND pc.loai_phan_cong = 'hướng dẫn viên'
        ";

        $this->db->query($query);
        $this->db->bind(1, $lichKhoiHanhId);
        $this->db->bind(2, $guideId);
        $tourInfo = $this->db->single();

        if (!$tourInfo) {
            return null;
        }

        // 2. Lấy lịch trình chi tiết từng ngày
        $query = "
            SELECT * FROM lich_trinh_tour
            WHERE tour_id = ?
            ORDER BY thu_tu_sap_xep ASC, so_ngay ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $tourInfo['tour_id']);
        $lichTrinhChiTiet = $this->db->resultSet();

        // 3. Lấy tất cả khách hàng đã đặt tour (bao gồm cả khách đi cùng)
        $query = "
            SELECT 
                kh.id,
                kh.ho_ten,
                kh.so_dien_thoai,
                kh.cccd,
                kh.gioi_tinh,
                kh.ngay_sinh,
                kh.email,
                kh.dia_chi,
                kh.ghi_chu,
                pd.ma_dat_tour,
                pd.trang_thai as trang_thai_dat,
                pd.created_at as ngay_dat,
                pd.tong_tien,
                pd.so_luong_khach,
                pks.ten_khach_san,
                pks.so_phong,
                pks.loai_phong,
                -- Kiểm tra xem đây là người đặt hay khách đi cùng
                CASE 
                    WHEN kh.id = pd.khach_hang_id THEN 'người đặt'
                    ELSE 'khách đi cùng'
                END as loai_khach
            FROM phieu_dat_tour pd
            -- Lấy tất cả khách hàng có cùng phieu_dat_tour_id
            JOIN khach_hang kh ON kh.phieu_dat_tour_id = pd.id
            LEFT JOIN phan_phong_khach_san pks ON pks.khach_hang_id = kh.id 
                AND pks.lich_khoi_hanh_id = ?
            WHERE pd.lich_khoi_hanh_id = ?
            AND pd.trang_thai IN ('đã cọc', 'hoàn tất')
            ORDER BY pd.created_at DESC, loai_khach DESC, kh.ho_ten ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $lichKhoiHanhId);
        $this->db->bind(2, $lichKhoiHanhId);
        $danhSachKhach = $this->db->resultSet();

        // Debug - ghi log để kiểm tra
        error_log("LichTrinhModel::getChiTietLichTrinh - Lịch khởi hành ID: " . $lichKhoiHanhId);
        error_log("LichTrinhModel::getChiTietLichTrinh - Số khách hàng: " . count($danhSachKhach));
        
        if (count($danhSachKhach) > 0) {
            error_log("LichTrinhModel::getChiTietLichTrinh - Mẫu khách hàng đầu tiên: " . json_encode($danhSachKhach[0]));
        }

        // 4. Lấy checklist công việc
        $query = "
            SELECT * FROM checklist_truoc_tour
            WHERE lich_khoi_hanh_id = ?
            ORDER BY created_at ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $lichKhoiHanhId);
        $checklist = $this->db->resultSet();

        // 5. Lấy các phân công khác (xe, khách sạn...)
        $query = "
            SELECT 
                pc.*,
                dt.ten_doi_tac,
                dt.loai_dich_vu,
                dt.thong_tin_lien_he
            FROM phan_cong pc
            LEFT JOIN doi_tac dt ON pc.doi_tac_id = dt.id
            WHERE pc.lich_khoi_hanh_id = ?
            AND pc.loai_phan_cong != 'hướng dẫn viên'
            ORDER BY pc.created_at ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $lichKhoiHanhId);
        $phanCongKhac = $this->db->resultSet();

        return [
            'tour_info' => $tourInfo,
            'lich_trinh_chi_tiet' => $lichTrinhChiTiet,
            'danh_sach_khach' => $danhSachKhach,
            'checklist' => $checklist,
            'phan_cong_khac' => $phanCongKhac
        ];
    }

    /**
     * Cập nhật trạng thái checklist
     */
    public function updateChecklistStatus($checklistId, $status, $guideId)
    {
        // Cập nhật trực tiếp, không cần trạng thái đồng bộ
        $query = "
        UPDATE checklist_truoc_tour 
        SET 
            hoan_thanh = ?,
            nguoi_hoan_thanh = ?,
            thoi_gian_hoan_thanh = NOW()
        WHERE id = ?
    ";

        $this->db->query($query);
        $this->db->bind(1, $status ? 1 : 0);
        $this->db->bind(2, $guideId);
        $this->db->bind(3, $checklistId);

        return $this->db->execute();
    }

    /**
     * Lấy số tour theo trạng thái
     */
    public function getThongKeTour($guideId)
    {
        $query = "
            SELECT 
                COUNT(CASE WHEN lkh.trang_thai = 'đã lên lịch' THEN 1 END) as cho_len_lich,
                COUNT(CASE WHEN lkh.trang_thai = 'đang diễn ra' THEN 1 END) as dang_dien_ra,
                COUNT(CASE WHEN lkh.trang_thai = 'đã hoàn thành' THEN 1 END) as da_hoan_thanh,
                COUNT(*) as tong_tour
            FROM phan_cong pc
            JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
            WHERE pc.huong_dan_vien_id = ?
            AND pc.loai_phan_cong = 'hướng dẫn viên'
        ";

        $this->db->query($query);
        $this->db->bind(1, $guideId);
        return $this->db->single();
    }

    /**
     * Lấy lịch làm việc theo guideId
     */
    public function getLichLamViecByGuideId($guideId, $startDate, $endDate)
    {
        $query = "
            SELECT * FROM lich_lam_viec_hdv 
            WHERE huong_dan_vien_id = ? 
            AND ngay BETWEEN ? AND ?
            ORDER BY ngay ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $guideId);
        $this->db->bind(2, $startDate);
        $this->db->bind(3, $endDate);
        return $this->db->resultSet();
    }

    /**
     * Lấy tất cả lịch trình tour của hướng dẫn viên
     */
    public function getAllLichTrinhByGuideId($guideId)
    {
        $query = "
            SELECT 
                pc.id as phan_cong_id,
                lkh.id as lich_khoi_hanh_id,
                t.ma_tour,
                t.ten_tour,
                t.hinh_anh,
                dm.ten_danh_muc,
                lkh.ngay_bat_dau,
                lkh.ngay_ket_thuc,
                lkh.gio_tap_trung,
                lkh.diem_tap_trung,
                lkh.so_cho_toi_da,
                lkh.so_cho_con_lai,
                lkh.trang_thai as trang_thai_lich,
                pc.trang_thai_xac_nhan,
                pc.ghi_chu as ghi_chu_phan_cong
            FROM phan_cong pc
            JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
            WHERE pc.huong_dan_vien_id = ?
            AND pc.loai_phan_cong = 'hướng dẫn viên'
            AND lkh.trang_thai IN ('đã lên lịch', 'đang diễn ra', 'đã hoàn thành')
            ORDER BY lkh.ngay_bat_dau ASC
        ";

        $this->db->query($query);
        $this->db->bind(1, $guideId);
        return $this->db->resultSet();
    }

    /**
     * Lấy thông tin hướng dẫn viên
     */
    public function getGuideInfo($guideId)
    {
        $query = "SELECT * FROM huong_dan_vien WHERE id = ?";

        $this->db->query($query);
        $this->db->bind(1, $guideId);
        return $this->db->single();
    }

    /**
     * Tìm kiếm hướng dẫn viên theo tên
     */
    public function findGuideByName($name)
    {
        $query = "SELECT id, ho_ten, so_dien_thoai, email FROM huong_dan_vien 
                  WHERE ho_ten LIKE ? AND trang_thai = 'đang làm việc' 
                  ORDER BY ho_ten LIMIT 10";

        $this->db->query($query);
        $this->db->bind(1, "%{$name}%");
        return $this->db->resultSet();
    }
}

?>