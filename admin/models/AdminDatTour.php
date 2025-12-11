<?php
class AdminDatTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

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

    // Thêm phương thức này vào class AdminDatTour
    public function getTourImageByDatTour($dat_tour_id)
    {
        try {
            $query = "SELECT 
            t.hinh_anh,
            t.ten_tour,
            t.ma_tour
        FROM phieu_dat_tour pdt
        JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
        JOIN tour t ON lkh.tour_id = t.id
        WHERE pdt.id = :dat_tour_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':dat_tour_id' => $dat_tour_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && !empty($result['hinh_anh'])) {
                // Xử lý đường dẫn ảnh
                if (filter_var($result['hinh_anh'], FILTER_VALIDATE_URL)) {
                    $result['hinh_anh_full'] = $result['hinh_anh'];
                } elseif (strpos($result['hinh_anh'], 'http') === 0 || strpos($result['hinh_anh'], '//') === 0) {
                    $result['hinh_anh_full'] = $result['hinh_anh'];
                } else {
                    $result['hinh_anh_full'] = 'uploads/tours/' . $result['hinh_anh'];
                }
            } else {
                $result['hinh_anh_full'] = 'https://via.placeholder.com/600x400?text=Chưa+có+ảnh';
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi getTourImageByDatTour: " . $e->getMessage());
            return null;
        }
    }

    // Lấy khách hàng chính
    public function getKhachHangChinh($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT kh.* FROM khach_hang kh 
                  JOIN phieu_dat_tour pdt ON kh.id = pdt.khach_hang_id 
                  WHERE pdt.id = :phieu_dat_tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Get Khach Hang Chinh Error: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật số chỗ còn lại
    private function updateSoChoConLai($lich_khoi_hanh_id, $so_cho_dat)
    {
        try {
            $query = "SELECT so_cho_con_lai FROM lich_khoi_hanh WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Không tìm thấy lịch khởi hành!");
            }

            $so_cho_con_lai = $result['so_cho_con_lai'];

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

            $query = "SELECT COUNT(*) as count FROM phieu_dat_tour 
                  WHERE YEAR(created_at) = :year 
                  AND MONTH(created_at) = :month";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':year' => $year, ':month' => $month]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $number = ($result['count'] ?? 0) + 1;
            $ma_dat_tour = $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);

            // Kiểm tra trùng lặp
            $check_query = "SELECT id FROM phieu_dat_tour WHERE ma_dat_tour = :ma_dat_tour";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':ma_dat_tour' => $ma_dat_tour]);

            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

            while ($existing) {
                $number++;
                $ma_dat_tour = $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
                $check_stmt->execute([':ma_dat_tour' => $ma_dat_tour]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
            }

            return $ma_dat_tour;
        } catch (PDOException $e) {
            error_log("Generate Ma Dat Tour Error: " . $e->getMessage());
            return $prefix . date('YmdHis') . rand(100, 999);
        }
    }

    // Lấy chi tiết đặt tour
    public function getDatTourById($id)
    {
        try {
            $query = "SELECT pdt.*, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.gio_tap_trung, lkh.diem_tap_trung,
                             t.ten_tour, t.ma_tour, t.gia_tour,
                             kh.ho_ten, kh.email, kh.so_dien_thoai, kh.cccd, kh.ngay_sinh, kh.gioi_tinh, kh.dia_chi, kh.ghi_chu,
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

    // Lấy tất cả đặt tour
    public function getAllDatTour($search = '', $trang_thai = '', $lich_khoi_hanh_id = '')
    {
        try {
            $query = "SELECT pdt.*, lkh.ngay_bat_dau, lkh.ngay_ket_thuc, lkh.gio_tap_trung, lkh.diem_tap_trung,
                         t.ten_tour, t.ma_tour, t.gia_tour,
                         kh.ho_ten, kh.so_dien_thoai, kh.email,
                         pdt.so_luong_khach as so_khach
                  FROM phieu_dat_tour pdt
                  LEFT JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
                  LEFT JOIN tour t ON lkh.tour_id = t.id
                  LEFT JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
                  WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (pdt.ma_dat_tour LIKE :search 
                        OR kh.ho_ten LIKE :search 
                        OR kh.so_dien_thoai LIKE :search
                        OR t.ten_tour LIKE :search)";
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

            $query .= " ORDER BY pdt.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get All Dat Tour Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch khởi hành có sẵn
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

    // Kiểm tra chỗ trống
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

            // Cập nhật thông tin khách hàng chính
            $this->updateKhachHang($data['khach_hang_id'], $data['khach_hang']);

            // Xóa các khách hàng cũ liên quan đến phiếu đặt tour này
            $query = "DELETE FROM khach_hang WHERE phieu_dat_tour_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            // Thêm thành viên mới vào bảng khach_hang
            foreach ($data['thanh_vien'] as $thanh_vien) {
                $this->createKhachHang($thanh_vien, $id);
            }

            // Cập nhật số lượng khách
            $so_luong_khach = count($data['thanh_vien']) + 1;
            $query = "UPDATE phieu_dat_tour 
                      SET so_luong_khach = :so_luong_khach,
                          tong_tien = (SELECT gia_tour FROM tour WHERE id = (SELECT tour_id FROM lich_khoi_hanh WHERE id = :lich_khoi_hanh_id)) * :so_luong_khach
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':so_luong_khach' => $so_luong_khach,
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

    // Cập nhật thông tin khách hàng
    private function updateKhachHang($id, $khach_hang)
    {
        try {
            $query = "UPDATE khach_hang 
                  SET ho_ten = :ho_ten, email = :email, so_dien_thoai = :so_dien_thoai, cccd = :cccd, 
                      ngay_sinh = :ngay_sinh, gioi_tinh = :gioi_tinh, dia_chi = :dia_chi, ghi_chu = :ghi_chu,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':ho_ten' => $khach_hang['ho_ten'],
                ':email' => $khach_hang['email'] ?? '',
                ':so_dien_thoai' => $khach_hang['so_dien_thoai'] ?? '',
                ':cccd' => $khach_hang['cccd'] ?? '',
                ':ngay_sinh' => $khach_hang['ngay_sinh'] ?? null,
                ':gioi_tinh' => $khach_hang['gioi_tinh'] ?? 'nam',
                ':dia_chi' => $khach_hang['dia_chi'] ?? '',
                ':ghi_chu' => $khach_hang['ghi_chu'] ?? '',
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Update Khach Hang Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách khách hàng theo phiếu đặt tour
    public function getKhachHangByDatTour($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT khach_hang_id FROM phieu_dat_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $phieu_dat_tour_id]);
            $phieu_dat_tour = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$phieu_dat_tour) {
                return [];
            }

            $khach_hang_list = [];

            // Thêm khách hàng chính
            $query = "SELECT * FROM khach_hang WHERE id = :khach_hang_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':khach_hang_id' => $phieu_dat_tour['khach_hang_id']]);
            $khach_hang_chinh = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($khach_hang_chinh) {
                $khach_hang_chinh['loai_khach'] = 'chinh';
                $khach_hang_list[] = $khach_hang_chinh;
            }

            // Thêm các khách hàng thành viên
            $query = "SELECT * FROM khach_hang WHERE phieu_dat_tour_id = :phieu_dat_tour_id AND id != :khach_hang_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':phieu_dat_tour_id' => $phieu_dat_tour_id,
                ':khach_hang_id' => $phieu_dat_tour['khach_hang_id']
            ]);
            $thanh_vien_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($thanh_vien_list as $thanh_vien) {
                $thanh_vien['loai_khach'] = 'thanh_vien';
                $khach_hang_list[] = $thanh_vien;
            }

            return $khach_hang_list;
        } catch (PDOException $e) {
            error_log("Get Khach Hang By Dat Tour Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tất cả khách hàng thuộc cùng một phiếu đặt tour
    public function getAllKhachHangByPhieuDatTour($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT * FROM khach_hang 
                  WHERE phieu_dat_tour_id = :phieu_dat_tour_id 
                  ORDER BY id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get All Khach Hang By Phieu Dat Tour Error: " . $e->getMessage());
            return [];
        }
    }

    public function datTourMoi($data)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy thông tin lịch khởi hành
            $lich_khoi_hanh = $this->getLichKhoiHanhById($data['lich_khoi_hanh_id']);
            if (!$lich_khoi_hanh) {
                throw new Exception("Không tìm thấy thông tin lịch khởi hành!");
            }

            // Tính tổng số khách
            $so_luong_khach = count($data['khach_hang_list']);

            // Kiểm tra chỗ trống
            if (!$this->kiemTraChoTrong($data['lich_khoi_hanh_id'], $so_luong_khach)) {
                $so_cho_con = $this->getSoChoConLai($data['lich_khoi_hanh_id']);
                throw new Exception("Số chỗ còn lại không đủ! Chỉ còn {$so_cho_con} chỗ, bạn đang đặt {$so_luong_khach} khách.");
            }

            // Tạo mã đặt tour
            $ma_dat_tour = $this->generateMaDatTour();
            $tong_tien = $lich_khoi_hanh['gia_tour'] * $so_luong_khach;

            // Tạo phiếu đặt tour với khách hàng đầu tiên làm người đại diện
            $khach_hang_dai_dien = $data['khach_hang_list'][0];
            $khach_hang_dai_dien_id = $this->createKhachHang($khach_hang_dai_dien);

            if (!$khach_hang_dai_dien_id) {
                throw new Exception("Không thể tạo thông tin khách hàng!");
            }

            // Tạo phiếu đặt tour
            $query = "INSERT INTO phieu_dat_tour 
                  (ma_dat_tour, lich_khoi_hanh_id, khach_hang_id, so_luong_khach, tong_tien, 
                   trang_thai, ghi_chu, nguoi_tao) 
                  VALUES (:ma_dat_tour, :lich_khoi_hanh_id, :khach_hang_id, :so_luong_khach, :tong_tien, 
                          :trang_thai, :ghi_chu, :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':ma_dat_tour' => $ma_dat_tour,
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':khach_hang_id' => $khach_hang_dai_dien_id,
                ':so_luong_khach' => $so_luong_khach,
                ':tong_tien' => $tong_tien,
                ':trang_thai' => 'chưa thanh toán',
                ':ghi_chu' => $data['ghi_chu'] ?? '',
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            if (!$result) {
                throw new Exception("Không thể tạo phiếu đặt tour!");
            }

            $phieu_dat_tour_id = $this->conn->lastInsertId();

            // Cập nhật phieu_dat_tour_id cho khách hàng đại diện
            $this->updateKhachHangPhieuDatTour($khach_hang_dai_dien_id, $phieu_dat_tour_id);

            // Tạo các khách hàng còn lại và liên kết với phiếu đặt tour
            for ($i = 1; $i < count($data['khach_hang_list']); $i++) {
                $khach_hang_id = $this->createKhachHang($data['khach_hang_list'][$i]);
                $this->updateKhachHangPhieuDatTour($khach_hang_id, $phieu_dat_tour_id);
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

    // Tạo khách hàng
    private function createKhachHang($khach_hang)
    {
        try {
            if (empty($khach_hang['ho_ten'])) {
                throw new Exception("Họ tên khách hàng không được để trống!");
            }

            $query = "INSERT INTO khach_hang 
                  (ho_ten, email, so_dien_thoai, cccd, ngay_sinh, gioi_tinh, dia_chi, ghi_chu, nguoi_tao) 
                  VALUES (:ho_ten, :email, :so_dien_thoai, :cccd, :ngay_sinh, :gioi_tinh, :dia_chi, :ghi_chu, :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ho_ten' => $khach_hang['ho_ten'],
                ':email' => $khach_hang['email'] ?? '',
                ':so_dien_thoai' => $khach_hang['so_dien_thoai'] ?? '',
                ':cccd' => $khach_hang['cccd'] ?? '',
                ':ngay_sinh' => $khach_hang['ngay_sinh'] ?? null,
                ':gioi_tinh' => $khach_hang['gioi_tinh'] ?? 'nam',
                ':dia_chi' => $khach_hang['dia_chi'] ?? '',
                ':ghi_chu' => $khach_hang['ghi_chu'] ?? '',
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create Khach Hang Error: " . $e->getMessage());
            throw new Exception("Không thể tạo thông tin khách hàng: " . $e->getMessage());
        }
    }

    // Cập nhật phiếu đặt tour cho khách hàng
    private function updateKhachHangPhieuDatTour($khach_hang_id, $phieu_dat_tour_id)
    {
        try {
            $query = "UPDATE khach_hang SET phieu_dat_tour_id = :phieu_dat_tour_id WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':phieu_dat_tour_id' => $phieu_dat_tour_id,
                ':id' => $khach_hang_id
            ]);
        } catch (PDOException $e) {
            error_log("Update Khach Hang Phieu Dat Tour Error: " . $e->getMessage());
            return false;
        }
    }

    // Lấy số chỗ còn lại
    public function getSoChoConLai($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT COALESCE(so_cho_con_lai, so_cho_toi_da) as so_cho_thuc_te 
                      FROM lich_khoi_hanh WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['so_cho_thuc_te'] : 0;
        } catch (PDOException $e) {
            error_log("Get So Cho Con Lai Error: " . $e->getMessage());
            return 0;
        }
    }

    public function thongKeDoanhThuThucTe($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');

            // Trong trường hợp thực tế, bạn cần query từ bảng giao_dich hoặc hoa_don
            // Dựa vào database, tôi thấy có bảng hoa_don

            $query = "SELECT 
            SUM(hd.da_thanh_toan) as doanh_thu_thuc_te
          FROM hoa_don hd
          JOIN phieu_dat_tour pdt ON pdt.id = hd.phieu_dat_tour_id
          WHERE MONTH(hd.ngay_thanh_toan) = :thang 
          AND YEAR(hd.ngay_thanh_toan) = :nam
          AND hd.trang_thai IN ('đã thanh toán', 'thanh toán một phần')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':thang' => $thang, ':nam' => $nam]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['doanh_thu_thuc_te'] ?? 0;
        } catch (PDOException $e) {
            error_log("Thong Ke Doanh Thu Thuc Te Error: " . $e->getMessage());
            return 0;
        }
    }


    // Thêm vào class AdminDatTour
    public function getLichTrinhTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour 
                  WHERE tour_id = :tour_id 
                  ORDER BY thu_tu_sap_xep ASC, so_ngay ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Lich Trinh Tour Error: " . $e->getMessage());
            return [];
        }
    }

    // Trong class AdminDatTour
    public function getNhaCungCapByLichKhoiHanh($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT pc.*, dt.ten_doi_tac, dt.loai_dich_vu, dt.thong_tin_lien_he 
                  FROM phan_cong pc
                  LEFT JOIN doi_tac dt ON pc.doi_tac_id = dt.id
                  WHERE pc.lich_khoi_hanh_id = :lich_khoi_hanh_id
                  AND pc.loai_phan_cong != 'hướng dẫn viên'
                  ORDER BY 
                    CASE pc.loai_phan_cong
                        WHEN 'khách sạn' THEN 1
                        WHEN 'vận chuyển' THEN 2
                        WHEN 'nhà hàng' THEN 3
                        WHEN 'vé tham quan' THEN 4
                        ELSE 5
                    END ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Nha Cung Cap By Lich Khoi Hanh Error: " . $e->getMessage());
            return [];
        }
    }


    public function getLichTrinhByLichKhoiHanh($lich_khoi_hanh_id)
    {
        try {
            $query = "SELECT t.id as tour_id 
                  FROM lich_khoi_hanh lkh
                  JOIN tour t ON lkh.tour_id = t.id
                  WHERE lkh.id = :lich_khoi_hanh_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':lich_khoi_hanh_id' => $lich_khoi_hanh_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result || empty($result['tour_id'])) {
                return [];
            }

            $tour_id = $result['tour_id'];

            // Lấy lịch trình tour
            $query2 = "SELECT * FROM lich_trinh_tour 
                   WHERE tour_id = :tour_id 
                   ORDER BY so_ngay ASC, thu_tu_sap_xep ASC";

            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute([':tour_id' => $tour_id]);
            return $stmt2->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Lich Trinh By Lich Khoi Hanh Error: " . $e->getMessage());
            return [];
        }
    }

    public function thongKeBooking($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');

            $query = "SELECT 
            COUNT(*) as tong_booking,
            SUM(CASE WHEN trang_thai = 'chưa thanh toán' THEN 1 ELSE 0 END) as chua_thanh_toan,
            SUM(CASE WHEN trang_thai = 'giữ chỗ' THEN 1 ELSE 0 END) as giu_cho,
            SUM(CASE WHEN trang_thai = 'đã thanh toán' THEN 1 ELSE 0 END) as da_thanh_toan,
            SUM(CASE WHEN trang_thai = 'hủy' THEN 1 ELSE 0 END) as huy,
            SUM(CASE WHEN trang_thai = 'đã thanh toán' THEN tong_tien ELSE 0 END) as doanh_thu_da_thanh_toan,
            SUM(CASE WHEN trang_thai = 'giữ chỗ' THEN tong_tien ELSE 0 END) as doanh_thu_giu_cho,
            SUM(CASE WHEN trang_thai = 'hủy' THEN tong_tien ELSE 0 END) as doanh_thu_huy,
            SUM(tong_tien) as tong_doanh_thu_bao_cao
          FROM phieu_dat_tour 
          WHERE MONTH(created_at) = :thang 
          AND YEAR(created_at) = :nam";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':thang' => $thang, ':nam' => $nam]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Thong Ke Booking Error: " . $e->getMessage());
            return [];
        }
    }

    public function getBookingMoiNhat($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');

            $query = "SELECT 
                pdt.*,
                lkh.ngay_bat_dau,
                t.ten_tour,
                t.ma_tour,
                kh.ho_ten,
                kh.so_dien_thoai,
                pdt.so_luong_khach as so_khach
            FROM phieu_dat_tour pdt
            JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            JOIN khach_hang kh ON pdt.khach_hang_id = kh.id
            WHERE MONTH(pdt.created_at) = :thang 
            AND YEAR(pdt.created_at) = :nam
            ORDER BY pdt.created_at DESC
            LIMIT 10";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':thang' => $thang, ':nam' => $nam]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get Booking Moi Nhat Error: " . $e->getMessage());
            return [];
        }
    }

    public function getThongKeTour($thang = null, $nam = null)
    {
        try {
            $thang = $thang ?? date('m');
            $nam = $nam ?? date('Y');

            $query = "SELECT 
                t.id,
                t.ma_tour,
                t.ten_tour,
                COUNT(pdt.id) as so_booking,
                SUM(CASE WHEN pdt.trang_thai IN ('đã thanh toán', 'giữ chỗ') THEN pdt.tong_tien ELSE 0 END) as doanh_thu,
                SUM(pdt.so_luong_khach) as tong_khach
            FROM phieu_dat_tour pdt
            JOIN lich_khoi_hanh lkh ON pdt.lich_khoi_hanh_id = lkh.id
            JOIN tour t ON lkh.tour_id = t.id
            WHERE MONTH(pdt.created_at) = :thang 
            AND YEAR(pdt.created_at) = :nam
            GROUP BY t.id
            ORDER BY doanh_thu DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':thang' => $thang, ':nam' => $nam]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Thong Ke Tour Error: " . $e->getMessage());
            return [];
        }
    }
}
