<?php

class DanhGiaModel
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
    }

    // Lấy danh sách tour đã hoàn thành mà HDV đã dẫn (cần đánh giá)
    public function getToursCanDanhGia($huong_dan_vien_id) {
    // Query mới - không dùng đến danh_gia_tour vì không có lich_khoi_hanh_id
    $query = "
        SELECT 
            lk.id,
            t.ten_tour,
            lk.ngay_bat_dau,
            lk.ngay_ket_thuc,
            DATEDIFF(lk.ngay_ket_thuc, lk.ngay_bat_dau) + 1 as so_ngay,
            dm.loai_tour,
            
            -- Kiểm tra đã đánh giá chưa bằng cách JOIN với bảng khác
            (SELECT COUNT(*) FROM danh_gia_tour dg 
             JOIN phieu_dat_tour pdt ON dg.phieu_dat_tour_id = pdt.id
             WHERE pdt.lich_khoi_hanh_id = lk.id 
             AND EXISTS (
                 -- Cần có cách xác định HDV nào đã đánh giá
                 SELECT 1 FROM phan_cong pc 
                 WHERE pc.lich_khoi_hanh_id = lk.id 
                 AND pc.huong_dan_vien_id = :hdv_id
             )) as da_danh_gia,
            
            (SELECT dg.id FROM danh_gia_tour dg 
             JOIN phieu_dat_tour pdt ON dg.phieu_dat_tour_id = pdt.id
             WHERE pdt.lich_khoi_hanh_id = lk.id 
             LIMIT 1) as danh_gia_id
             
        FROM lich_khoi_hanh lk
        JOIN tour t ON lk.tour_id = t.id
        JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
        JOIN phan_cong pc ON lk.id = pc.lich_khoi_hanh_id 
            AND pc.huong_dan_vien_id = :hdv_id2 
            AND pc.loai_phan_cong = 'hướng dẫn viên'
        WHERE lk.trang_thai = 'đã hoàn thành'
            AND lk.ngay_ket_thuc <= CURDATE()
        GROUP BY lk.id
        ORDER BY lk.ngay_ket_thuc DESC
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute([
        ':hdv_id' => $huong_dan_vien_id,
        ':hdv_id2' => $huong_dan_vien_id
    ]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Lấy thông tin chi tiết tour để đánh giá
    public function getTourInfoForReview($lich_khoi_hanh_id, $huong_dan_vien_id)
    {
        $query = "
            SELECT 
                lk.id,
                t.ten_tour,
                lk.ngay_bat_dau,
                lk.ngay_ket_thuc,
                pc_ks.ten_dich_vu as ten_khach_san,
                dt_ks.ten_doi_tac as nha_cung_cap_khach_san,
                dt_ks.thong_tin_lien_he as thong_tin_khach_san,
                pc_nh.ten_dich_vu as ten_nha_hang,
                dt_nh.ten_doi_tac as nha_cung_cap_nha_hang,
                dt_nh.thong_tin_lien_he as thong_tin_nha_hang,
                pc_xe.ten_dich_vu as loai_xe,
                dt_xe.ten_doi_tac as nha_cung_cap_xe,
                pc_xe.ghi_chu as bien_so,
                dt_xe.thong_tin_lien_he as thong_tin_xe,
                pc_hdv.doi_tac_id as huong_dan_vien_dia_phuong_id,
                dt_hdv.ten_doi_tac as ten_huong_dan_vien
            FROM lich_khoi_hanh lk
            JOIN tour t ON lk.tour_id = t.id
            LEFT JOIN phan_cong pc_ks ON lk.id = pc_ks.lich_khoi_hanh_id 
                AND pc_ks.loai_phan_cong = 'khách sạn'
            LEFT JOIN doi_tac dt_ks ON pc_ks.doi_tac_id = dt_ks.id
            LEFT JOIN phan_cong pc_nh ON lk.id = pc_nh.lich_khoi_hanh_id 
                AND pc_nh.loai_phan_cong = 'nhà hàng'
            LEFT JOIN doi_tac dt_nh ON pc_nh.doi_tac_id = dt_nh.id
            LEFT JOIN phan_cong pc_xe ON lk.id = pc_xe.lich_khoi_hanh_id 
                AND pc_xe.loai_phan_cong = 'vận chuyển'
            LEFT JOIN doi_tac dt_xe ON pc_xe.doi_tac_id = dt_xe.id
            LEFT JOIN phan_cong pc_hdv ON lk.id = pc_hdv.lich_khoi_hanh_id 
                AND pc_hdv.loai_phan_cong = 'hướng dẫn viên'
                AND pc_hdv.huong_dan_vien_id != :hdv_id
            LEFT JOIN doi_tac dt_hdv ON pc_hdv.doi_tac_id = dt_hdv.id
            WHERE lk.id = :lich_khoi_hanh_id
                AND EXISTS (
                    SELECT 1 FROM phan_cong pc 
                    WHERE pc.lich_khoi_hanh_id = lk.id 
                    AND pc.huong_dan_vien_id = :hdv_id2
                    AND pc.loai_phan_cong = 'hướng dẫn viên'
                )
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
            ':hdv_id' => $huong_dan_vien_id,
            ':hdv_id2' => $huong_dan_vien_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lưu đánh giá (sử dụng bảng danh_gia_tour)
    public function saveDanhGia($data) {
    error_log("=== BẮT ĐẦU saveDanhGia ===");
    error_log("Data nhận được: " . print_r($data, true));
    
    try {
        // Kiểm tra kết nối database
        if (!$this->db) {
            error_log("Lỗi: Không có kết nối database");
            return false;
        }
        
        $query = "
            INSERT INTO danh_gia_tour (
                phieu_dat_tour_id,
                huong_dan_vien_id,
                lich_khoi_hanh_id,
                diem_so,
                noi_dung_danh_gia,
                danh_gia_dich_vu,
                ngay_danh_gia,
                created_at,
                updated_at
            ) VALUES (
                :phieu_dat_tour_id,
                :huong_dan_vien_id,
                :lich_khoi_hanh_id,
                :diem_so,
                :noi_dung_danh_gia,
                :danh_gia_dich_vu,
                NOW(),
                NOW(),
                NOW()
            )
        ";
        
        error_log("Query: " . $query);
        
        $stmt = $this->db->prepare($query);
        
        // Debug từng tham số
        foreach ($data as $key => $value) {
            error_log("$key = " . (is_string($value) ? $value : print_r($value, true)));
        }
        
        $result = $stmt->execute($data);
        
        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            error_log("Lỗi execute: " . print_r($errorInfo, true));
        } else {
            $lastId = $this->db->lastInsertId();
            error_log("Insert thành công, ID: " . $lastId);
        }
        
        error_log("=== KẾT THÚC saveDanhGia ===");
        return $result;
        
    } catch (PDOException $e) {
        error_log("PDOException trong saveDanhGia: " . $e->getMessage());
        error_log("Error Code: " . $e->getCode());
        error_log("SQL State: " . $e->errorInfo[0] ?? 'N/A');
        error_log("Driver Code: " . $e->errorInfo[1] ?? 'N/A');
        error_log("Driver Message: " . $e->errorInfo[2] ?? 'N/A');
        return false;
    }
}

    // Lấy danh sách đánh giá đã gửi
    public function getDanhGiaList($huong_dan_vien_id)
    {
        $query = "
            SELECT 
                dg.*,
                t.ten_tour,
                lk.ngay_bat_dau,
                lk.ngay_ket_thuc,
                JSON_EXTRACT(dg.danh_gia_dich_vu, '$.diem_tong_quan') as diem_tong_quan,
                JSON_EXTRACT(dg.danh_gia_dich_vu, '$.diem_khach_san') as diem_khach_san,
                JSON_EXTRACT(dg.danh_gia_dich_vu, '$.diem_nha_hang') as diem_nha_hang,
                JSON_EXTRACT(dg.danh_gia_dich_vu, '$.diem_xe_van_chuyen') as diem_xe_van_chuyen,
                JSON_EXTRACT(dg.danh_gia_dich_vu, '$.de_xuat_tiep_tuc_su_dung') as de_xuat_tiep_tuc_su_dung,
                'submitted' as trang_thai
            FROM danh_gia_tour dg
            JOIN lich_khoi_hanh lk ON dg.lich_khoi_hanh_id = lk.id
            JOIN tour t ON lk.tour_id = t.id
            WHERE dg.huong_dan_vien_id = :huong_dan_vien_id
            ORDER BY dg.created_at DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':huong_dan_vien_id' => $huong_dan_vien_id]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xử lý JSON fields
        foreach ($results as &$row) {
            if ($row['danh_gia_dich_vu']) {
                $dichVuData = json_decode($row['danh_gia_dich_vu'], true);
                if (is_array($dichVuData)) {
                    $row = array_merge($row, $dichVuData);
                }
            }
        }

        return $results;
    }

    // Lấy chi tiết đánh giá
    public function getDanhGiaDetail($id, $huong_dan_vien_id)
    {
        $query = "
            SELECT 
                dg.*,
                t.ten_tour,
                lk.ngay_bat_dau,
                lk.ngay_ket_thuc,
                dm.loai_tour
            FROM danh_gia_tour dg
            JOIN lich_khoi_hanh lk ON dg.lich_khoi_hanh_id = lk.id
            JOIN tour t ON lk.tour_id = t.id
            JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
            WHERE dg.id = :id 
                AND dg.huong_dan_vien_id = :huong_dan_vien_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':huong_dan_vien_id' => $huong_dan_vien_id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Xử lý JSON fields
        if ($result && $result['danh_gia_dich_vu']) {
            $dichVuData = json_decode($result['danh_gia_dich_vu'], true);
            if (is_array($dichVuData)) {
                $result = array_merge($result, $dichVuData);
            }
        }

        return $result;
    }

    // Kiểm tra đã đánh giá chưa
    public function isAlreadyReviewed($lich_khoi_hanh_id, $huong_dan_vien_id) {
    // Kiểm tra qua phieu_dat_tour
    $query = "
        SELECT COUNT(*) as count 
        FROM danh_gia_tour dg
        JOIN phieu_dat_tour pdt ON dg.phieu_dat_tour_id = pdt.id
        WHERE pdt.lich_khoi_hanh_id = :lich_khoi_hanh_id 
        -- Cần thêm logic để xác định HDV nào đã đánh giá
        -- Tạm thời kiểm tra qua phan_cong
        AND EXISTS (
            SELECT 1 FROM phan_cong pc 
            WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id2
            AND pc.huong_dan_vien_id = :huong_dan_vien_id
        )
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute([
        ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
        ':lich_khoi_hanh_id2' => $lich_khoi_hanh_id,
        ':huong_dan_vien_id' => $huong_dan_vien_id
    ]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

    // Lấy phieu_dat_tour_id đầu tiên cho lich_khoi_hanh_id
    public function getFirstPhieuDatTourId($lich_khoi_hanh_id) {
    $query = "
        SELECT id 
        FROM phieu_dat_tour 
        WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
        LIMIT 1
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Nếu không tìm thấy, tạo một phiếu đặt tour mặc định
    if (!$result) {
        return $this->createDummyPhieuDatTour($lich_khoi_hanh_id);
    }
    
    return $result['id'];
}

private function createDummyPhieuDatTour($lich_khoi_hanh_id) {
    error_log("Tạo phiếu đặt tour mặc định cho lịch khởi hành ID: " . $lich_khoi_hanh_id);
    
    // Lấy thông tin lịch khởi hành
    $query = "SELECT tour_id, so_cho_toi_da FROM lich_khoi_hanh WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':id' => $lich_khoi_hanh_id]);
    $lich_khoi_hanh = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lich_khoi_hanh) {
        return 0;
    }
    
    // Tạo mã đặt tour
    $ma_dat_tour = 'DG' . date('YmdHis') . rand(100, 999);
    
    // Tạo khách hàng mặc định nếu cần
    $khach_hang_id = $this->getOrCreateDefaultKhachHang();
    
    // Tạo phiếu đặt tour
    $query = "
        INSERT INTO phieu_dat_tour (
            ma_dat_tour,
            lich_khoi_hanh_id,
            khach_hang_id,
            so_luong_khach,
            tong_tien,
            trang_thai,
            created_at,
            updated_at
        ) VALUES (
            :ma_dat_tour,
            :lich_khoi_hanh_id,
            :khach_hang_id,
            1,
            0,
            'đã thanh toán',
            NOW(),
            NOW()
        )
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute([
        ':ma_dat_tour' => $ma_dat_tour,
        ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
        ':khach_hang_id' => $khach_hang_id
    ]);
    
    return $this->db->lastInsertId();
}

private function getOrCreateDefaultKhachHang() {
    // Kiểm tra xem đã có khách hàng mặc định chưa
    $query = "SELECT id FROM khach_hang WHERE email = 'default@tour.com' LIMIT 1";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return $result['id'];
    }
    
    // Tạo khách hàng mặc định
    $query = "
        INSERT INTO khach_hang (
            ho_ten,
            email,
            so_dien_thoai,
            created_at,
            updated_at
        ) VALUES (
            'Khách hàng mặc định',
            'default@tour.com',
            '0000000000',
            NOW(),
            NOW()
        )
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    
    return $this->db->lastInsertId();
}
}
