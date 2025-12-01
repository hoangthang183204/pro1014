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
                kh.ho_ten,
                kh.cccd,
                kh.gioi_tinh,
                pdt.ma_dat_tour,
                kh.so_dien_thoai,
                kh.email,
                kh.ngay_sinh
            FROM phan_phong_khach_san pp
            JOIN khach_hang kh ON pp.khach_hang_id = kh.id
            JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
            WHERE pp.lich_khoi_hanh_id = :lich_khoi_hanh_id
            ORDER BY pp.ten_khach_san, pp.so_phong";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
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
                kh.ho_ten,
                kh.cccd,
                kh.gioi_tinh,
                kh.ngay_sinh,
                kh.so_dien_thoai,
                lkh.ngay_bat_dau,
                lkh.ngay_ket_thuc,
                t.ten_tour,
                t.ma_tour
            FROM phan_phong_khach_san pp
            JOIN khach_hang kh ON pp.khach_hang_id = kh.id
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
                kh.id,
                kh.ho_ten,
                kh.gioi_tinh,
                kh.cccd,
                kh.ngay_sinh,
                pdt.ma_dat_tour,
                kh.so_dien_thoai,
                kh.email,
                pdt.trang_thai as trang_thai_dat
            FROM khach_hang kh
            JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
            WHERE pdt.lich_khoi_hanh_id = :lich_khoi_hanh_id
            AND pdt.trang_thai IN ('đã cọc', 'hoàn tất')
            AND kh.id NOT IN (
                SELECT khach_hang_id 
                FROM phan_phong_khach_san 
                WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id
                AND khach_hang_id IS NOT NULL
            )
            ORDER BY kh.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Lỗi getKhachChuaPhanPhong: " . $e->getMessage());
            return [];
        }
    }

    // Thêm phân phòng mới
    public function themPhanPhong($data) {
        try {
            // Kiểm tra khách hàng đã có phòng chưa
            $checkQuery = "SELECT COUNT(*) as count FROM phan_phong_khach_san 
                          WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                          AND khach_hang_id = :khach_hang_id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':khach_hang_id' => $data['khach_hang_id']
            ]);
            $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($checkResult['count'] > 0) {
                return ['success' => false, 'message' => 'Khách hàng đã được phân phòng trước đó'];
            }

            $query = "INSERT INTO phan_phong_khach_san 
                     (lich_khoi_hanh_id, khach_hang_id, ten_khach_san, 
                      so_phong, loai_phong, ngay_nhan_phong, ngay_tra_phong, ghi_chu, nguoi_tao, created_at)
                     VALUES (:lich_khoi_hanh_id, :khach_hang_id, :ten_khach_san,
                             :so_phong, :loai_phong, :ngay_nhan_phong, :ngay_tra_phong, :ghi_chu, :nguoi_tao, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':khach_hang_id' => $data['khach_hang_id'],
                ':ten_khach_san' => $data['ten_khach_san'],
                ':so_phong' => $data['so_phong'],
                ':loai_phong' => $data['loai_phong'],
                ':ngay_nhan_phong' => $data['ngay_nhan_phong'],
                ':ngay_tra_phong' => $data['ngay_tra_phong'],
                ':ghi_chu' => $data['ghi_chu'] ?? '',
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
                ':ghi_chu' => $data['ghi_chu'] ?? '',
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
                // Kiểm tra khách đã có phòng chưa
                $checkQuery = "SELECT COUNT(*) as count FROM phan_phong_khach_san 
                              WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id 
                              AND khach_hang_id = :khach_hang_id";
                $checkStmt = $this->conn->prepare($checkQuery);
                $checkStmt->execute([
                    ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                    ':khach_hang_id' => $khach_id
                ]);
                $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($checkResult['count'] > 0) {
                    continue; // Bỏ qua khách đã có phòng
                }

                $query = "INSERT INTO phan_phong_khach_san 
                         (lich_khoi_hanh_id, khach_hang_id, ten_khach_san, 
                          so_phong, loai_phong, ngay_nhan_phong, ngay_tra_phong, ghi_chu, nguoi_tao, created_at)
                         VALUES (:lich_khoi_hanh_id, :khach_hang_id, :ten_khach_san,
                                 :so_phong, :loai_phong, :ngay_nhan_phong, :ngay_tra_phong, :ghi_chu, :nguoi_tao, NOW())";

                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                    ':khach_hang_id' => $khach_id,
                    ':ten_khach_san' => $data['ten_khach_san'],
                    ':so_phong' => $data['so_phong'],
                    ':loai_phong' => $data['loai_phong'],
                    ':ngay_nhan_phong' => $data['ngay_nhan_phong'],
                    ':ngay_tra_phong' => $data['ngay_tra_phong'],
                    ':ghi_chu' => $data['ghi_chu'] ?? '',
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
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
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

    // Lấy danh sách khách hàng theo lịch khởi hành
    public function getKhachHangByLichKhoiHanh($lich_khoi_hanh_id) {
        try {
            $query = "SELECT 
                kh.*,
                pdt.ma_dat_tour,
                pdt.trang_thai as trang_thai_dat_tour
            FROM khach_hang kh
            JOIN phieu_dat_tour pdt ON kh.phieu_dat_tour_id = pdt.id
            WHERE pdt.lich_khoi_hanh_id = :lich_khoi_hanh_id
            AND pdt.trang_thai IN ('đã cọc', 'hoàn tất')
            ORDER BY kh.ho_ten";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Lỗi getKhachHangByLichKhoiHanh: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách khách sạn đã sử dụng
    public function getDanhSachKhachSan($lich_khoi_hanh_id) {
        try {
            $query = "SELECT DISTINCT ten_khach_san 
                     FROM phan_phong_khach_san 
                     WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id
                     ORDER BY ten_khach_san";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Lỗi getDanhSachKhachSan: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách phòng đã sử dụng trong khách sạn
    public function getDanhSachPhong($lich_khoi_hanh_id, $ten_khach_san) {
        try {
            $query = "SELECT DISTINCT so_phong, loai_phong 
                     FROM phan_phong_khach_san 
                     WHERE lich_khoi_hanh_id = :lich_khoi_hanh_id
                     AND ten_khach_san = :ten_khach_san
                     ORDER BY so_phong";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                ':ten_khach_san' => $ten_khach_san
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Lỗi getDanhSachPhong: " . $e->getMessage());
            return [];
        }
    }
}
?>