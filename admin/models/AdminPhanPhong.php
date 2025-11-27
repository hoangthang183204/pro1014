<?php
class AdminPhanPhong {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy danh sách phân phòng theo lịch khởi hành
    public function getDanhSachPhanPhong($lich_khoi_hanh_id) {
        try {
            $query = "SELECT 
                pp.*,
                tvd.ho_ten,
                tvd.cccd,
                tvd.gioi_tinh,
                pdt.ma_dat_tour,
                kh.so_dien_thoai,
                kh.email
            FROM phan_phong_khach_san pp
            JOIN thanh_vien_dat_tour tvd ON pp.thanh_vien_dat_tour_id = tvd.id
            JOIN phieu_dat_tour pdt ON tvd.phieu_dat_tour_id = pdt.id
            JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
            WHERE pp.lich_khoi_hanh_id = :lich_khoi_hanh_id
            ORDER BY pp.ten_khach_san, pp.so_phong, pp.loai_phong";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getDanhSachPhanPhong: " . $e->getMessage());
            return [];
        }
    }

    // Lấy thông tin phân phòng theo ID
    public function getChiTietPhanPhong($id) {
        try {
            $query = "SELECT 
                pp.*,
                tvd.ho_ten,
                tvd.cccd,
                tvd.gioi_tinh,
                pdt.ma_dat_tour,
                kh.so_dien_thoai,
                lkh.ngay_bat_dau,
                lkh.ngay_ket_thuc,
                t.ten_tour
            FROM phan_phong_khach_san pp
            JOIN thanh_vien_dat_tour tvd ON pp.thanh_vien_dat_tour_id = tvd.id
            JOIN phieu_dat_tour pdt ON tvd.phieu_dat_tour_id = pdt.id
            JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
            JOIN lich_khoi_hanh lkh ON pp.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            WHERE pp.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getChiTietPhanPhong: " . $e->getMessage());
            return null;
        }
    }

    // Lấy danh sách khách chưa phân phòng
    public function getKhachChuaPhanPhong($lich_khoi_hanh_id) {
        try {
            $query = "SELECT 
                tvd.id,
                tvd.ho_ten,
                tvd.gioi_tinh,
                tvd.cccd,
                pdt.ma_dat_tour,
                kh.so_dien_thoai,
                kh.email
            FROM thanh_vien_dat_tour tvd
            JOIN phieu_dat_tour pdt ON tvd.phieu_dat_tour_id = pdt.id
            JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
            WHERE pdt.lich_khoi_hanh_id = :lich_khoi_hanh_id
            AND tvd.id NOT IN (
                SELECT thanh_vien_dat_tour_id 
                FROM phan_phong_khach_san 
                WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id
            )
            ORDER BY tvd.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getKhachChuaPhanPhong: " . $e->getMessage());
            return [];
        }
    }

    // Thêm phân phòng mới
    public function themPhanPhong($data) {
        try {
            $query = "INSERT INTO phan_phong_khach_san 
                     (lich_khoi_hanh_id, thanh_vien_dat_tour_id, ten_khach_san, 
                      so_phong, loai_phong, ngay_nhan_phong, ngay_tra_phong, ghi_chu, nguoi_tao)
                     VALUES (:lich_khoi_hanh_id, :thanh_vien_dat_tour_id, :ten_khach_san,
                             :so_phong, :loai_phong, :ngay_nhan_phong, :ngay_tra_phong, :ghi_chu, :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':thanh_vien_dat_tour_id' => $data['thanh_vien_dat_tour_id'],
                ':ten_khach_san' => $data['ten_khach_san'],
                ':so_phong' => $data['so_phong'],
                ':loai_phong' => $data['loai_phong'],
                ':ngay_nhan_phong' => $data['ngay_nhan_phong'],
                ':ngay_tra_phong' => $data['ngay_tra_phong'],
                ':ghi_chu' => $data['ghi_chu'],
                ':nguoi_tao' => $data['nguoi_tao']
            ]);

            return ['success' => true, 'message' => 'Phân phòng thành công', 'id' => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Lỗi themPhanPhong: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Cập nhật phân phòng
    public function capNhatPhanPhong($data) {
        try {
            $query = "UPDATE phan_phong_khach_san 
                     SET ten_khach_san = :ten_khach_san,
                         so_phong = :so_phong,
                         loai_phong = :loai_phong,
                         ngay_nhan_phong = :ngay_nhan_phong,
                         ngay_tra_phong = :ngay_tra_phong,
                         ghi_chu = :ghi_chu,
                         updated_at = NOW()
                     WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_khach_san' => $data['ten_khach_san'],
                ':so_phong' => $data['so_phong'],
                ':loai_phong' => $data['loai_phong'],
                ':ngay_nhan_phong' => $data['ngay_nhan_phong'],
                ':ngay_tra_phong' => $data['ngay_tra_phong'],
                ':ghi_chu' => $data['ghi_chu'],
                ':id' => $data['id']
            ]);

            return ['success' => true, 'message' => 'Cập nhật phân phòng thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi capNhatPhanPhong: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Xóa phân phòng
    public function xoaPhanPhong($id) {
        try {
            $query = "DELETE FROM phan_phong_khach_san WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            return ['success' => true, 'message' => 'Xóa phân phòng thành công'];
        } catch (PDOException $e) {
            error_log("Lỗi xoaPhanPhong: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Phân phòng hàng loạt
    public function phanPhongHangLoat($data) {
        try {
            $this->conn->beginTransaction();

            foreach ($data['danh_sach_khach'] as $khach_id) {
                $query = "INSERT INTO phan_phong_khach_san 
                         (lich_khoi_hanh_id, thanh_vien_dat_tour_id, ten_khach_san, 
                          so_phong, loai_phong, ngay_nhan_phong, ngay_tra_phong, ghi_chu, nguoi_tao)
                         VALUES (:lich_khoi_hanh_id, :thanh_vien_dat_tour_id, :ten_khach_san,
                                 :so_phong, :loai_phong, :ngay_nhan_phong, :ngay_tra_phong, :ghi_chu, :nguoi_tao)";

                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                    ':thanh_vien_dat_tour_id' => $khach_id,
                    ':ten_khach_san' => $data['ten_khach_san'],
                    ':so_phong' => $data['so_phong'],
                    ':loai_phong' => $data['loai_phong'],
                    ':ngay_nhan_phong' => $data['ngay_nhan_phong'],
                    ':ngay_tra_phong' => $data['ngay_tra_phong'],
                    ':ghi_chu' => $data['ghi_chu'],
                    ':nguoi_tao' => $data['nguoi_tao']
                ]);
            }

            $this->conn->commit();
            return ['success' => true, 'message' => 'Phân phòng hàng loạt thành công'];

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi phanPhongHangLoat: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Thống kê phân phòng
    public function getThongKePhanPhong($lich_khoi_hanh_id) {
        try {
            $query = "SELECT 
                COUNT(*) as tong_phong,
                COUNT(DISTINCT ten_khach_san) as so_khach_san,
                COUNT(DISTINCT so_phong) as so_phong,
                loai_phong,
                COUNT(*) as so_luong
            FROM phan_phong_khach_san 
            WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id
            GROUP BY loai_phong";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getThongKePhanPhong: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra phòng trùng
    public function kiemTraPhongTrung($lich_khoi_hanh_id, $ten_khach_san, $so_phong, $loai_phong, $id = null) {
        try {
            $query = "SELECT COUNT(*) as count 
                     FROM phan_phong_khach_san 
                     WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                     AND ten_khach_san = :ten_khach_san 
                     AND so_phong = :so_phong 
                     AND loai_phong = :loai_phong";
            
            $params = [
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':ten_khach_san' => $ten_khach_san,
                ':so_phong' => $so_phong,
                ':loai_phong' => $loai_phong
            ];

            if ($id) {
                $query .= " AND id != :id";
                $params[':id'] = $id;
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Lỗi kiemTraPhongTrung: " . $e->getMessage());
            return false;
        }
    }
}
?>