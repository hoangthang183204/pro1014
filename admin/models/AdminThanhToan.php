<?php
class AdminThanhToan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy thông tin chi tiết phiếu đặt tour
    public function getThongTinPhieuDat($phieu_dat_id)
    {
        try {
            $query = "SELECT 
                        pdt.*,
                        kh.ho_ten,
                        kh.so_dien_thoai,
                        t.ten_tour,
                        t.ma_tour,
                        lkh.ngay_bat_dau
                    FROM phieu_dat_tour pdt
                    INNER JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                    INNER JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                    INNER JOIN tour t ON lkh.tour_id = t.id
                    WHERE pdt.id = :id
                    LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $phieu_dat_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Thong Tin Phieu Dat Error: " . $e->getMessage());
            return null;
        }
    }

    // Thanh toán toàn bộ
    public function thanhToanToanBo($phieu_dat_id, $phuong_thuc, $ghi_chu, $nguoi_thu)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy thông tin phiếu đặt
            $query = "SELECT id, tong_tien, trang_thai FROM phieu_dat_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $phieu_dat_id]);
            $phieu_dat = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$phieu_dat) {
                throw new Exception("Không tìm thấy phiếu đặt tour");
            }

            $tong_tien = (float)$phieu_dat['tong_tien'];

            // Cập nhật trạng thái phiếu đặt tour
            $query_update = "UPDATE phieu_dat_tour 
                           SET trang_thai = 'hoàn tất', 
                               updated_at = CURRENT_TIMESTAMP 
                           WHERE id = :id";
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->execute(['id' => $phieu_dat_id]);

            // Tạo hóa đơn
            $ma_hoa_don = 'HD' . date('YmdHis') . rand(100, 999);
            $query_hoa_don = "INSERT INTO hoa_don 
                        (ma_hoa_don, phieu_dat_tour_id, tong_tien, da_thanh_toan, con_no, 
                         phuong_thuc_thanh_toan, trang_thai, ngay_thanh_toan, nguoi_tao)
                        VALUES (:ma_hoa_don, :phieu_dat_id, :tong_tien, :da_thanh_toan, :con_no,
                                :phuong_thuc, :trang_thai, NOW(), :nguoi_tao)";

            $stmt_hoa_don = $this->conn->prepare($query_hoa_don);
            $stmt_hoa_don->execute([
                'ma_hoa_don' => $ma_hoa_don,
                'phieu_dat_id' => $phieu_dat_id,
                'tong_tien' => $tong_tien,
                'da_thanh_toan' => $tong_tien,
                'con_no' => 0,
                'phuong_thuc' => $phuong_thuc,
                'trang_thai' => 'đã thanh toán',
                'nguoi_tao' => $nguoi_thu
            ]);

            $hoa_don_id = $this->conn->lastInsertId();

            // Ghi lịch sử thanh toán
            $query_lich_su = "INSERT INTO lich_su_thanh_toan 
                        (hoa_don_id, so_tien, phuong_thuc, nguoi_thu, ghi_chu)
                        VALUES (:hoa_don_id, :so_tien, :phuong_thuc, :nguoi_thu, :ghi_chu)";

            $stmt_lich_su = $this->conn->prepare($query_lich_su);
            $stmt_lich_su->execute([
                'hoa_don_id' => $hoa_don_id,
                'so_tien' => $tong_tien,
                'phuong_thuc' => $phuong_thuc,
                'nguoi_thu' => $nguoi_thu,
                'ghi_chu' => $ghi_chu
            ]);

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Thanh toán thành công! Tour đã được xác nhận hoàn tất.',
                'ma_hoa_don' => $ma_hoa_don
            ];

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Thanh Toan Toan Bo Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi thanh toán: ' . $e->getMessage()
            ];
        }
    }
}
?>