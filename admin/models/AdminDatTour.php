<?php
class AdminDatTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }



    // Lấy thông tin lịch khởi hành theo ID
    public function getLichKhoiHanhById($id)
    {
        try {
            $query = "SELECT lkh.*, t.ten_tour, t.gia_tour, t.ma_tour
                      FROM lich_khoi_hanh lkh 
                      LEFT JOIN tour t ON lkh.tour_id = t.id 
                      WHERE lkh.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Lich Khoi Hanh By ID Error: " . $e->getMessage());
            return null;
        }
    }

    // Tạo hoặc tìm khách hàng
    private function findOrCreateKhachHang($khach_hang)
    {
        try {
            // Validate dữ liệu khách hàng
            if (empty($khach_hang['ho_ten']) || empty($khach_hang['so_dien_thoai'])) {
                throw new Exception("Thông tin khách hàng không đầy đủ!");
            }

            // Tìm khách hàng theo số điện thoại
            $query = "SELECT id FROM khach_hang WHERE so_dien_thoai = :so_dien_thoai";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':so_dien_thoai' => $khach_hang['so_dien_thoai']]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Cập nhật thông tin khách hàng nếu đã tồn tại
                $this->updateKhachHang($existing['id'], $khach_hang);
                return $existing['id'];
            }

            // Tạo khách hàng mới
            $query = "INSERT INTO khach_hang 
                  (ho_ten, email, so_dien_thoai, cccd, ngay_sinh, gioi_tinh, dia_chi, nguoi_tao) 
                  VALUES (:ho_ten, :email, :so_dien_thoai, :cccd, :ngay_sinh, :gioi_tinh, :dia_chi, :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ho_ten' => $khach_hang['ho_ten'],
                ':email' => $khach_hang['email'] ?? '',
                ':so_dien_thoai' => $khach_hang['so_dien_thoai'],
                ':cccd' => $khach_hang['cccd'] ?? '',
                ':ngay_sinh' => $khach_hang['ngay_sinh'] ?? null,
                ':gioi_tinh' => $khach_hang['gioi_tinh'] ?? 'nam',
                ':dia_chi' => $khach_hang['dia_chi'] ?? '',
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Find or Create Khach Hang Error: " . $e->getMessage());
            throw new Exception("Không thể tạo thông tin khách hàng: " . $e->getMessage());
        }
    }

    // Cập nhật thông tin khách hàng
    private function updateKhachHang($id, $khach_hang)
    {
        try {
            $query = "UPDATE khach_hang 
                  SET ho_ten = :ho_ten, email = :email, cccd = :cccd, 
                      ngay_sinh = :ngay_sinh, gioi_tinh = :gioi_tinh, dia_chi = :dia_chi,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':ho_ten' => $khach_hang['ho_ten'],
                ':email' => $khach_hang['email'] ?? '',
                ':cccd' => $khach_hang['cccd'] ?? '',
                ':ngay_sinh' => $khach_hang['ngay_sinh'] ?? null,
                ':gioi_tinh' => $khach_hang['gioi_tinh'] ?? 'nam',
                ':dia_chi' => $khach_hang['dia_chi'] ?? '',
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Update Khach Hang Error: " . $e->getMessage());
            return false;
        }
    }

    // Tạo thành viên
    private function createThanhVien($phieu_dat_tour_id, $thanh_vien)
    {
        try {
            // Validate dữ liệu thành viên
            if (empty($thanh_vien['ho_ten'])) {
                throw new Exception("Họ tên thành viên không được để trống!");
            }

            $query = "INSERT INTO thanh_vien_dat_tour 
                  (phieu_dat_tour_id, ho_ten, cccd, ngay_sinh, gioi_tinh, yeu_cau_dac_biet) 
                  VALUES (:phieu_dat_tour_id, :ho_ten, :cccd, :ngay_sinh, :gioi_tinh, :yeu_cau_dac_biet)";

            $yeu_cau_json = !empty($thanh_vien['yeu_cau_dac_biet']) ?
                json_encode(['yeu_cau' => $thanh_vien['yeu_cau_dac_biet']]) :
                json_encode(['yeu_cau' => '']);

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':phieu_dat_tour_id' => $phieu_dat_tour_id,
                ':ho_ten' => $thanh_vien['ho_ten'],
                ':cccd' => $thanh_vien['cccd'] ?? '',
                ':ngay_sinh' => $thanh_vien['ngay_sinh'] ?? null,
                ':gioi_tinh' => $thanh_vien['gioi_tinh'] ?? 'nam',
                ':yeu_cau_dac_biet' => $yeu_cau_json
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Create Thanh Vien Error: " . $e->getMessage());
            throw new Exception("Không thể tạo thông tin thành viên: " . $e->getMessage());
        }
    }

    // Cập nhật số chỗ còn lại
    private function updateSoChoConLai($lich_khoi_hanh_id, $so_cho_dat)
    {
        try {
            // Kiểm tra xem số chỗ còn lại có null không
            $query = "SELECT so_cho_con_lai FROM lich_khoi_hanh WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Không tìm thấy lịch khởi hành!");
            }

            $so_cho_con_lai = $result['so_cho_con_lai'];

            // Nếu số chỗ còn lại là null, lấy số chỗ tối đa
            if ($so_cho_con_lai === null) {
                $query = "SELECT so_cho_toi_da FROM lich_khoi_hanh WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([':id' => $lich_khoi_hanh_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $so_cho_con_lai = $result['so_cho_toi_da'];
            }

            if ($so_cho_con_lai < $so_cho_dat) {
                throw new Exception("Số chỗ còn lại không đủ!");
            }

            $query = "UPDATE lich_khoi_hanh 
                  SET so_cho_con_lai = :so_cho_con_lai 
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':so_cho_con_lai' => $so_cho_con_lai - $so_cho_dat,
                ':id' => $lich_khoi_hanh_id
            ]);

            if (!$result || $stmt->rowCount() === 0) {
                throw new Exception("Không thể cập nhật số chỗ còn lại");
            }

            return true;
        } catch (PDOException $e) {
            error_log("Update So Cho Con Lai Error: " . $e->getMessage());
            throw new Exception("Lỗi cập nhật số chỗ: " . $e->getMessage());
        }
    }

    // Tạo mã đặt tour
    public function generateMaDatTour()
    {
        try {
            $prefix = "DT";
            $year = date('Y');
            $month = date('m');

            // Lấy số thứ tự mới nhất trong tháng
            $query = "SELECT COUNT(*) as count FROM phieu_dat_tour 
                  WHERE YEAR(created_at) = :year 
                  AND MONTH(created_at) = :month";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':year' => $year, ':month' => $month]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $number = ($result['count'] ?? 0) + 1;

            // Tạo mã và kiểm tra trùng lặp
            $ma_dat_tour = $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);

            // Kiểm tra xem mã đã tồn tại chưa
            $check_query = "SELECT id FROM phieu_dat_tour WHERE ma_dat_tour = :ma_dat_tour";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':ma_dat_tour' => $ma_dat_tour]);

            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

            // Nếu mã đã tồn tại, tăng số lên cho đến khi tìm được mã mới
            while ($existing) {
                $number++;
                $ma_dat_tour = $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);

                $check_stmt->execute([':ma_dat_tour' => $ma_dat_tour]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
            }

            return $ma_dat_tour;
        } catch (PDOException $e) {
            error_log("Generate Ma Dat Tour Error: " . $e->getMessage());
            // Fallback: sử dụng timestamp để tạo mã unique
            return $prefix . date('YmdHis') . rand(100, 999);
        }
    }

    public function getAllDatTour($search = '', $trang_thai = '', $lich_khoi_hanh_id = '', $loai_khach = '')
    {
        try {
            $query = "SELECT pdt.*, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.gio_tap_trung, lkh.diem_tap_trung,
                             t.ten_tour, t.ma_tour, t.gia_tour,
                             kh.ho_ten, kh.so_dien_thoai, kh.email,
                             COUNT(tvdt.id) as so_khach
                      FROM phieu_dat_tour pdt
                      LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                      LEFT JOIN tour t ON lkh.tour_id = t.id
                      LEFT JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                      LEFT JOIN thanh_vien_dat_tour tvdt ON pdt.id = tvdt.phieu_dat_tour_id
                      WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (pdt.ma_dat_tour LIKE :search 
                            OR kh.ho_ten LIKE :search 
                            OR kh.so_dien_thoai LIKE :search
                            OR t.ten_tour LIKE :search
                            OR pdt.ten_doan LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($trang_thai)) {
                $query .= " AND pdt.trang_thai = :trang_thai";
                $params[':trang_thai'] = $trang_thai;
            }

            if (!empty($lich_khoi_hanh_id)) {
                $query .= " AND pdt.lich_khoi_hanh_id = :lich_khoi_hanh_id";
                $params[':lich_khoi_hanh_id'] = $lich_khoi_hanh_id;
            }

            if (!empty($loai_khach)) {
                $query .= " AND pdt.loai_khach = :loai_khach";
                $params[':loai_khach'] = $loai_khach;
            }

            $query .= " GROUP BY pdt.id ORDER BY pdt.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get All Dat Tour Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy chi tiết đặt tour
    public function getDatTourById($id)
    {
        try {
            $query = "SELECT pdt.*, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.gio_tap_trung, lkh.diem_tap_trung,
                             t.ten_tour, t.ma_tour, t.gia_tour,
                             kh.ho_ten, kh.email, kh.so_dien_thoai, kh.cccd, kh.ngay_sinh, kh.gioi_tinh, kh.dia_chi,
                             u.ho_ten as nguoi_tao_ten
                      FROM phieu_dat_tour pdt
                      LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                      LEFT JOIN tour t ON lkh.tour_id = t.id
                      LEFT JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                      LEFT JOIN nguoi_dung u ON pdt.nguoi_tao = u.id
                      WHERE pdt.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Dat Tour By ID Error: " . $e->getMessage());
            return null;
        }
    }

    // Lấy danh sách thành viên
    public function getThanhVienByDatTour($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT * FROM thanh_vien_dat_tour 
                      WHERE phieu_dat_tour_id = :phieu_dat_tour_id 
                      ORDER BY id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Thanh Vien Error: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật trạng thái
    public function updateTrangThai($id, $trang_thai)
    {
        try {
            $query = "UPDATE phieu_dat_tour SET trang_thai = :trang_thai, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':trang_thai' => $trang_thai, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Update Trang Thai Error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa đặt tour
    public function deleteDatTour($id)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy thông tin để cập nhật số chỗ
            $query = "SELECT lich_khoi_hanh_id, so_luong_khach FROM phieu_dat_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $dat_tour = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dat_tour) {
                throw new Exception("Không tìm thấy đặt tour");
            }

            // Xóa thành viên
            $query = "DELETE FROM thanh_vien_dat_tour WHERE phieu_dat_tour_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            // Xóa phiếu đặt tour
            $query = "DELETE FROM phieu_dat_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([':id' => $id]);

            if ($result) {
                // Cập nhật số chỗ còn lại
                $query = "UPDATE lich_khoi_hanh 
                          SET so_cho_con_lai = so_cho_con_lai + :so_cho 
                          WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':so_cho' => $dat_tour['so_luong_khach'],
                    ':id' => $dat_tour['lich_khoi_hanh_id']
                ]);
            }

            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Delete Dat Tour Error: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật đặt tour
    public function updateDatTour($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Cập nhật phiếu đặt tour
            $query = "UPDATE phieu_dat_tour 
                      SET lich_khoi_hanh_id = :lich_khoi_hanh_id, 
                          ghi_chu = :ghi_chu,
                          updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':ghi_chu' => $data['ghi_chu'],
                ':id' => $id
            ]);

            // Xóa thành viên cũ và thêm mới
            $query = "DELETE FROM thanh_vien_dat_tour WHERE phieu_dat_tour_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            // Thêm thành viên mới
            foreach ($data['thanh_vien'] as $thanh_vien) {
                $this->createThanhVien($id, $thanh_vien);
            }

            // Cập nhật số lượng khách
            $query = "UPDATE phieu_dat_tour 
                      SET so_luong_khach = :so_luong_khach,
                          tong_tien = (SELECT gia_tour FROM tour WHERE id = (SELECT tour_id FROM lich_khoi_hanh WHERE id = :lich_khoi_hanh_id)) * :so_luong_khach
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':so_luong_khach' => count($data['thanh_vien']),
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':id' => $id
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Không thể cập nhật đặt tour: " . $e->getMessage());
        }
    }

    // Thống kê booking
    public function thongKeBooking($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');

            $query = "SELECT 
                    COUNT(*) as tong_booking,
                    SUM(CASE WHEN trang_thai = 'chờ xác nhận' THEN 1 ELSE 0 END) as cho_xac_nhan,
                    SUM(CASE WHEN trang_thai = 'đã cọc' THEN 1 ELSE 0 END) as da_coc,
                    SUM(CASE WHEN trang_thai = 'hoàn tất' THEN 1 ELSE 0 END) as hoan_tat,
                    SUM(CASE WHEN trang_thai = 'hủy' THEN 1 ELSE 0 END) as huy,
                    SUM(tong_tien) as tong_doanh_thu
                  FROM phieu_dat_tour 
                  WHERE MONTH(created_at) = :thang 
                  AND YEAR(created_at) = :nam";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':thang' => $thang, ':nam' => $nam]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Thong Ke Booking Error: " . $e->getMessage());
            return [];
        }
    }

    // Thống kê tổng quan
    public function getBookingStats()
    {
        try {
            $query = "SELECT 
                    COUNT(*) as tong_booking,
                    SUM(CASE WHEN trang_thai = 'chờ xác nhận' THEN 1 ELSE 0 END) as cho_xac_nhan,
                    SUM(CASE WHEN trang_thai = 'đã xác nhận' THEN 1 ELSE 0 END) as da_xac_nhan,
                    SUM(CASE WHEN trang_thai = 'đã hoàn thành' THEN 1 ELSE 0 END) as hoan_tat,
                    SUM(CASE WHEN trang_thai = 'đã hủy' THEN 1 ELSE 0 END) as huy,
                    SUM(tong_tien) as doanh_thu,
                    SUM(so_luong_khach) as tong_khach
                  FROM phieu_dat_tour 
                  WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                  AND YEAR(created_at) = YEAR(CURRENT_DATE())";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Booking Stats Error: " . $e->getMessage());
            return [];
        }
    }

    public function getBookingByLoaiKhach($loai_khach = null)
    {
        try {
            $query = "SELECT pdt.*, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, 
                         t.ten_tour, kh.ho_ten, kh.so_dien_thoai,
                         COUNT(tvdt.id) as so_khach
                  FROM phieu_dat_tour pdt
                  LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                  LEFT JOIN tour t ON lkh.tour_id = t.id
                  LEFT JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                  LEFT JOIN thanh_vien_dat_tour tvdt ON pdt.id = tvdt.phieu_dat_tour_id
                  WHERE 1=1";

            $params = [];

            if ($loai_khach) {
                $query .= " AND pdt.loai_khach = :loai_khach";
                $params[':loai_khach'] = $loai_khach;
            }

            $query .= " GROUP BY pdt.id ORDER BY pdt.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Booking By Loai Khach Error: " . $e->getMessage());
            return [];
        }
    }


    public function getLichKhoiHanhAvailable()
    {
        try {
            $query = "SELECT lkh.*, t.ten_tour, t.gia_tour, t.ma_tour,
                             COALESCE(lkh.so_cho_con_lai, lkh.so_cho_toi_da) as so_cho_thuc_te
                      FROM lich_khoi_hanh lkh
                      LEFT JOIN tour t ON lkh.tour_id = t.id
                      WHERE lkh.trang_thai = 'đã lên lịch' 
                      AND (lkh.so_cho_con_lai > 0 OR lkh.so_cho_con_lai IS NULL)
                      AND lkh.ngay_bat_dau >= CURDATE()
                      ORDER BY lkh.ngay_bat_dau ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Lich Khoi Hanh Error: " . $e->getMessage());
            return [];
        }
    }

    // Kiểm tra chỗ trống - ĐÃ SỬA
    public function kiemTraChoTrong($lich_khoi_hanh_id, $so_luong_khach)
    {
        try {
            $query = "SELECT 
                        COALESCE(so_cho_con_lai, so_cho_toi_da) as so_cho_thuc_te,
                        so_cho_toi_da
                      FROM lich_khoi_hanh 
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Không tìm thấy lịch khởi hành!");
            }

            $so_cho_thuc_te = $result['so_cho_thuc_te'];
            return $so_cho_thuc_te >= $so_luong_khach;
        } catch (PDOException $e) {
            error_log("Kiem Tra Cho Trong Error: " . $e->getMessage());
            return false;
        }
    }

    // Đặt tour mới - ĐÃ SỬA
    public function datTourMoi($data, $loai_khach = 'le')
    {
        try {
            $this->conn->beginTransaction();

            // Lấy thông tin lịch khởi hành để tính giá
            $lich_khoi_hanh = $this->getLichKhoiHanhById($data['lich_khoi_hanh_id']);
            if (!$lich_khoi_hanh) {
                throw new Exception("Không tìm thấy thông tin lịch khởi hành!");
            }

            // Kiểm tra chỗ trống - CHI TIẾT HƠN
            $so_luong_khach = count($data['thanh_vien']);
            if (!$this->kiemTraChoTrong($data['lich_khoi_hanh_id'], $so_luong_khach)) {
                $query = "SELECT COALESCE(so_cho_con_lai, so_cho_toi_da) as so_cho_thuc_te 
                      FROM lich_khoi_hanh WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([':id' => $data['lich_khoi_hanh_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                throw new Exception("Số chỗ còn lại không đủ! Chỉ còn " . $result['so_cho_thuc_te'] . " chỗ, bạn đang đặt " . $so_luong_khach . " khách.");
            }

            // Tạo hoặc tìm khách hàng
            $khach_hang_id = $this->findOrCreateKhachHang($data['khach_hang']);

            // Tạo mã đặt tour
            $ma_dat_tour = $this->generateMaDatTour();
            $tong_tien = $lich_khoi_hanh['gia_tour'] * $so_luong_khach;

            // Thông tin đoàn (thay cho công ty)
            $ten_doan = ($loai_khach === 'doan') ? ($data['ten_doan'] ?? '') : null;
            $loai_doan = ($loai_khach === 'doan') ? ($data['loai_doan'] ?? null) : null;

            // Tạo phiếu đặt tour
            $query = "INSERT INTO phieu_dat_tour 
                  (ma_dat_tour, lich_khoi_hanh_id, khach_hang_id, so_luong_khach, tong_tien, 
                   trang_thai, ghi_chu, loai_khach, ten_doan, loai_doan, nguoi_tao) 
                  VALUES (:ma_dat_tour, :lich_khoi_hanh_id, :khach_hang_id, :so_luong_khach, :tong_tien, 
                          :trang_thai, :ghi_chu, :loai_khach, :ten_doan, :loai_doan, :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':ma_dat_tour' => $ma_dat_tour,
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':khach_hang_id' => $khach_hang_id,
                ':so_luong_khach' => $so_luong_khach,
                ':tong_tien' => $tong_tien,
                ':trang_thai' => 'chờ xác nhận',
                ':ghi_chu' => $data['ghi_chu'] ?? '',
                ':loai_khach' => $loai_khach,
                ':ten_doan' => $ten_doan,
                ':loai_doan' => $loai_doan,
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Lỗi database: " . $errorInfo[2]);
            }

            $phieu_dat_tour_id = $this->conn->lastInsertId();

            // Thêm thành viên
            foreach ($data['thanh_vien'] as $thanh_vien) {
                $this->createThanhVien($phieu_dat_tour_id, $thanh_vien);
            }

            // Cập nhật số chỗ còn lại
            $this->updateSoChoConLai($data['lich_khoi_hanh_id'], $so_luong_khach);

            $this->conn->commit();
            return $phieu_dat_tour_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("DAT TOUR MOI ERROR: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
