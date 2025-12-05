<?php
class DanhGiaModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tour đã hoàn thành của HDV
    public function getToursCompletedByGuide($guide_id) {
        try {
            $sql = "SELECT lkh.id, t.ten_tour, lkh.ngay_bat_dau, lkh.ngay_ket_thuc,
                           t.so_ngay, t.loai_tour,
                           (SELECT COUNT(*) FROM danh_gia_tour dg WHERE dg.lich_khoi_hanh_id = lkh.id) as da_danh_gia
                    FROM phan_cong pc
                    JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    WHERE pc.huong_dan_vien_id = :guide_id 
                    AND lkh.trang_thai = 'hoàn thành'
                    AND lkh.ngay_ket_thuc <= CURDATE()
                    ORDER BY lkh.ngay_ket_thuc DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guide_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Lỗi getToursCompletedByGuide: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra đã đánh giá chưa
    public function checkDaDanhGia($lich_khoi_hanh_id, $guide_id) {
        try {
            $sql = "SELECT id, trang_thai FROM danh_gia_tour 
                    WHERE lich_khoi_hanh_id = :lich_id AND huong_dan_vien_id = :guide_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':lich_id' => $lich_khoi_hanh_id,
                ':guide_id' => $guide_id
            ]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy thông tin tour để đánh giá
    public function getTourInfoForReview($lich_khoi_hanh_id) {
        try {
            $sql = "SELECT t.*, lkh.*, 
                           ks.ten as ten_khach_san, ks.dia_chi as dc_khach_san,
                           nh.ten as ten_nha_hang, nh.dia_chi as dc_nha_hang,
                           xe.bien_so, xe.loai_xe, xe.ten_tai_xe,
                           hdv.ho_ten as ten_huong_dan_vien
                    FROM lich_khoi_hanh lkh
                    JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN khach_san ks ON lkh.khach_san_id = ks.id
                    LEFT JOIN nha_hang nh ON lkh.nha_hang_id = nh.id
                    LEFT JOIN xe_van_chuyen xe ON lkh.xe_id = xe.id
                    LEFT JOIN huong_dan_vien hdv ON lkh.huong_dan_vien_phu_id = hdv.id
                    WHERE lkh.id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $lich_khoi_hanh_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lưu đánh giá
    public function saveDanhGia($data) {
        try {
            $sql = "INSERT INTO danh_gia_tour (
                lich_khoi_hanh_id, huong_dan_vien_id,
                diem_tong_quan, noi_dung_tong_quan,
                diem_khach_san, nhan_xet_khach_san,
                diem_nha_hang, nhan_xet_nha_hang,
                diem_xe_van_chuyen, nhan_xet_xe_van_chuyen,
                diem_dich_vu_bo_sung, nhan_xet_dich_vu_bo_sung,
                nha_cung_cap_khach_san, nha_cung_cap_nha_hang, nha_cung_cap_xe,
                de_xuat_cai_thien, de_xuat_tiep_tuc_su_dung,
                trang_thai
            ) VALUES (
                :lich_id, :guide_id,
                :diem_tong_quan, :noi_dung_tong_quan,
                :diem_khach_san, :nhan_xet_khach_san,
                :diem_nha_hang, :nhan_xet_nha_hang,
                :diem_xe_van_chuyen, :nhan_xet_xe_van_chuyen,
                :diem_dich_vu_bo_sung, :nhan_xet_dich_vu_bo_sung,
                :nha_cung_cap_khach_san, :nha_cung_cap_nha_hang, :nha_cung_cap_xe,
                :de_xuat_cai_thien, :de_xuat_tiep_tuc_su_dung,
                'submitted'
            )";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
            
        } catch (PDOException $e) {
            error_log("Lỗi saveDanhGia: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật đánh giá
    public function updateDanhGia($id, $data) {
        try {
            $sql = "UPDATE danh_gia_tour SET 
                diem_tong_quan = :diem_tong_quan,
                noi_dung_tong_quan = :noi_dung_tong_quan,
                diem_khach_san = :diem_khach_san,
                nhan_xet_khach_san = :nhan_xet_khach_san,
                diem_nha_hang = :diem_nha_hang,
                nhan_xet_nha_hang = :nhan_xet_nha_hang,
                diem_xe_van_chuyen = :diem_xe_van_chuyen,
                nhan_xet_xe_van_chuyen = :nhan_xet_xe_van_chuyen,
                diem_dich_vu_bo_sung = :diem_dich_vu_bo_sung,
                nhan_xet_dich_vu_bo_sung = :nhan_xet_dich_vu_bo_sung,
                nha_cung_cap_khach_san = :nha_cung_cap_khach_san,
                nha_cung_cap_nha_hang = :nha_cung_cap_nha_hang,
                nha_cung_cap_xe = :nha_cung_cap_xe,
                de_xuat_cai_thien = :de_xuat_cai_thien,
                de_xuat_tiep_tuc_su_dung = :de_xuat_tiep_tuc_su_dung,
                trang_thai = 'submitted',
                updated_at = NOW()
                WHERE id = :id AND huong_dan_vien_id = :guide_id";
            
            $data[':id'] = $id;
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
            
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lấy danh sách đánh giá đã gửi
    public function getDanhGiaByGuide($guide_id) {
        try {
            $sql = "SELECT dg.*, t.ten_tour, lkh.ngay_bat_dau, lkh.ngay_ket_thuc
                    FROM danh_gia_tour dg
                    JOIN lich_khoi_hanh lkh ON dg.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    WHERE dg.huong_dan_vien_id = :guide_id
                    ORDER BY dg.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':guide_id', $guide_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy chi tiết đánh giá
    public function getDanhGiaDetail($id, $guide_id) {
        try {
            $sql = "SELECT dg.*, t.ten_tour, lkh.ngay_bat_dau, lkh.ngay_ket_thuc,
                           ks.ten as ten_khach_san, nh.ten as ten_nha_hang,
                           xe.bien_so, xe.loai_xe
                    FROM danh_gia_tour dg
                    JOIN lich_khoi_hanh lkh ON dg.lich_khoi_hanh_id = lkh.id
                    JOIN tour t ON lkh.tour_id = t.id
                    LEFT JOIN khach_san ks ON lkh.khach_san_id = ks.id
                    LEFT JOIN nha_hang nh ON lkh.nha_hang_id = nh.id
                    LEFT JOIN xe_van_chuyen xe ON lkh.xe_id = xe.id
                    WHERE dg.id = :id AND dg.huong_dan_vien_id = :guide_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id, ':guide_id' => $guide_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>