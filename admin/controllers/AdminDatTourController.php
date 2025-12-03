<?php

class AdminDatTourController
{
    public $datTourModel;

    public function __construct()
    {
        $this->datTourModel = new AdminDatTour();
    }

    // Danh sách đặt tour
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $trang_thai = $_GET['trang_thai'] ?? '';
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? '';

        try {
            $dat_tours = $this->datTourModel->getAllDatTour($search, $trang_thai, $lich_khoi_hanh_id);
            $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();

            require_once 'views/dattour/listDatTour.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải danh sách: " . $e->getMessage();
            require_once 'views/dattour/listDatTour.php';
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate dữ liệu
                $errors = [];
                if (empty($_POST['lich_khoi_hanh_id'])) {
                    $errors[] = "Vui lòng chọn lịch khởi hành!";
                }

                // Validate danh sách khách hàng
                $khach_hang_list = [];
                if (isset($_POST['khach_hang_ho_ten']) && is_array($_POST['khach_hang_ho_ten'])) {
                    foreach ($_POST['khach_hang_ho_ten'] as $index => $ho_ten) {
                        if (!empty(trim($ho_ten))) {
                            $khach_hang_list[] = [
                                'ho_ten' => trim($ho_ten),
                                'email' => $_POST['khach_hang_email'][$index] ?? '',
                                'so_dien_thoai' => $_POST['khach_hang_so_dien_thoai'][$index] ?? '',
                                'cccd' => $_POST['khach_hang_cccd'][$index] ?? '',
                                'ngay_sinh' => $_POST['khach_hang_ngay_sinh'][$index] ?? null,
                                'gioi_tinh' => $_POST['khach_hang_gioi_tinh'][$index] ?? 'nam',
                                'dia_chi' => $_POST['khach_hang_dia_chi'][$index] ?? '',
                                'ghi_chu' => $_POST['khach_hang_ghi_chu'][$index] ?? ''
                            ];
                        }
                    }
                }

                if (empty($khach_hang_list)) {
                    $errors[] = "Vui lòng thêm ít nhất một khách hàng!";
                }

                if (!empty($errors)) {
                    throw new Exception(implode("<br>", $errors));
                }

                // Chuẩn bị dữ liệu
                $data = [
                    'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                    'khach_hang_list' => $khach_hang_list,
                    'ghi_chu' => $_POST['ghi_chu'] ?? ''
                ];

                // Tạo booking
                $phieu_dat_tour_id = $this->datTourModel->datTourMoi($data);

                if ($phieu_dat_tour_id) {
                    $booking = $this->datTourModel->getDatTourById($phieu_dat_tour_id);
                    $_SESSION['success'] = "Đặt tour thành công! Mã booking: " . $booking['ma_dat_tour'];
                    header('Location: index.php?act=dat-tour');
                    exit;
                } else {
                    throw new Exception("Không thể tạo booking!");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?act=dat-tour-create');
                exit;
            }
        }
    }

    // Form đặt tour - Phiên bản đơn giản
    public function create()
    {
        $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();
        require_once 'views/dattour/createDatTour.php';
    }

    // Trong method show()
    public function show()
    {
        $id = $_GET['id'] ?? 0;

        // Lấy thông tin đặt tour
        $dat_tour = $this->datTourModel->getDatTourById($id);

        if (!$dat_tour) {
            $_SESSION['error'] = "Không tìm thấy đặt tour!";
            header('Location: index.php?act=dat-tour');
            return;
        }

        // Lấy tất cả khách hàng thuộc phiếu đặt tour này
        $all_khach_hang = $this->datTourModel->getAllKhachHangByPhieuDatTour($id);

        // Lấy khách hàng chính
        $khach_hang_chinh = $this->datTourModel->getKhachHangChinh($id);

        // Lấy lịch trình tour
        $lich_trinh_tour = $this->datTourModel->getLichTrinhByLichKhoiHanh($dat_tour['lich_khoi_hanh_id']);

        require_once 'views/dattour/detailDatTour.php';
    }

    // Form sửa đặt tour
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $dat_tour = $this->datTourModel->getDatTourById($id);

        if (!$dat_tour) {
            $_SESSION['error'] = "Không tìm thấy đặt tour!";
            header('Location: index.php?act=dat-tour');
            return;
        }

        // Lấy danh sách khách hàng từ bảng khach_hang
        $khach_hang_list = $this->datTourModel->getKhachHangByDatTour($id);
        $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();

        require_once 'views/dattour/editDatTour.php';
    }

    // Cập nhật đặt tour
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'];

                // Lấy thông tin khách hàng chính từ form
                $khach_hang_chinh = [
                    'ho_ten' => $_POST['ho_ten'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                    'cccd' => $_POST['cccd'] ?? '',
                    'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                    'gioi_tinh' => $_POST['gioi_tinh'] ?? 'nam',
                    'dia_chi' => $_POST['dia_chi'] ?? '',
                    'ghi_chu' => $_POST['ghi_chu_khach'] ?? ''
                ];

                $data = [
                    'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                    'ghi_chu' => $_POST['ghi_chu'] ?? '',
                    'khach_hang_id' => $_POST['khach_hang_id'] ?? '',
                    'khach_hang' => $khach_hang_chinh,
                    'thanh_vien' => []
                ];

                // Xử lý thông tin thành viên
                if (isset($_POST['thanh_vien_ho_ten']) && is_array($_POST['thanh_vien_ho_ten'])) {
                    foreach ($_POST['thanh_vien_ho_ten'] as $index => $ho_ten) {
                        if (!empty(trim($ho_ten))) {
                            $data['thanh_vien'][] = [
                                'ho_ten' => trim($ho_ten),
                                'email' => $_POST['thanh_vien_email'][$index] ?? '',
                                'so_dien_thoai' => $_POST['thanh_vien_so_dien_thoai'][$index] ?? '',
                                'cccd' => $_POST['thanh_vien_cccd'][$index] ?? '',
                                'ngay_sinh' => $_POST['thanh_vien_ngay_sinh'][$index] ?? null,
                                'gioi_tinh' => $_POST['thanh_vien_gioi_tinh'][$index] ?? 'nam',
                                'dia_chi' => $_POST['thanh_vien_dia_chi'][$index] ?? '',
                                'ghi_chu' => $_POST['thanh_vien_yeu_cau'][$index] ?? ''
                            ];
                        }
                    }
                }

                $result = $this->datTourModel->updateDatTour($id, $data);

                if ($result) {
                    $_SESSION['success'] = "Cập nhật đặt tour thành công!";
                    header('Location: index.php?act=dat-tour-show&id=' . $id);
                    exit;
                } else {
                    throw new Exception("Không thể cập nhật đặt tour!");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?act=dat-tour-edit&id=' . $_POST['id']);
                exit;
            }
        }
    }

    // Cập nhật trạng thái
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $trang_thai = $_POST['trang_thai'];

            $result = $this->datTourModel->updateTrangThai($id, $trang_thai);

            if ($result) {
                $_SESSION['success'] = "Cập nhật trạng thái thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật trạng thái!";
            }

            header('Location: index.php?act=dat-tour-show&id=' . $id);
            exit;
        }
    }

    // Xóa đặt tour
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        $result = $this->datTourModel->deleteDatTour($id);

        if ($result) {
            $_SESSION['success'] = "Xóa đặt tour thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa đặt tour!";
        }

        header('Location: index.php?act=dat-tour');
        exit;
    }

    // In hóa đơn
    public function print()
    {
        $id = $_GET['id'] ?? 0;

        try {
            // Lấy thông tin đặt tour
            $dat_tour = $this->datTourModel->getDatTourById($id);

            if (!$dat_tour) {
                $_SESSION['error'] = "Không tìm thấy đặt tour!";
                header('Location: index.php?act=dat-tour');
                return;
            }

            // Lấy tất cả khách hàng thuộc phiếu đặt tour này
            $khach_hang_list = $this->datTourModel->getAllKhachHangByPhieuDatTour($id);

            // Lấy thông tin hóa đơn nếu có
            $hoa_don = $this->getHoaDonByPhieuDatTour($id);

            require_once 'views/dattour/printDatTour.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải hóa đơn: " . $e->getMessage();
            header('Location: index.php?act=dat-tour');
        }
    }

    // Lấy hóa đơn theo phiếu đặt tour
    private function getHoaDonByPhieuDatTour($phieu_dat_tour_id)
    {
        try {
            $query = "SELECT * FROM hoa_don WHERE phieu_dat_tour_id = :phieu_dat_tour_id ORDER BY id DESC LIMIT 1";
            $stmt = $this->datTourModel->conn->prepare($query);
            $stmt->execute([':phieu_dat_tour_id' => $phieu_dat_tour_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Get Hoa Don Error: " . $e->getMessage());
            return [];
        }
    }

    // Lấy thông tin lịch khởi hành (AJAX)
    public function getLichKhoiHanhInfo()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh = $this->datTourModel->getLichKhoiHanhById($id);

        header('Content-Type: application/json');
        echo json_encode($lich_khoi_hanh);
        exit;
    }

    // Tìm kiếm khách hàng (AJAX)
    public function searchKhachHang()
    {
        $so_dien_thoai = $_GET['so_dien_thoai'] ?? '';

        if (empty($so_dien_thoai)) {
            echo json_encode([]);
            exit;
        }

        try {
            $query = "SELECT * FROM khach_hang WHERE so_dien_thoai LIKE :so_dien_thoai LIMIT 10";
            $stmt = $this->datTourModel->conn->prepare($query);
            $stmt->execute([':so_dien_thoai' => "%$so_dien_thoai%"]);
            $khach_hang = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($khach_hang);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([]);
        }
        exit;
    }

    // Lấy thông tin khách hàng theo ID (AJAX)
    public function getKhachHangById()
    {
        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            echo json_encode([]);
            exit;
        }

        try {
            $query = "SELECT * FROM khach_hang WHERE id = :id";
            $stmt = $this->datTourModel->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            $khach_hang = $stmt->fetch(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($khach_hang ?: []);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([]);
        }
        exit;
    }

    // Thống kê
    public function thongKe()
    {
        $thang = $_GET['thang'] ?? date('m');
        $nam = $_GET['nam'] ?? date('Y');

        try {
            // Lấy thống kê tổng quan
            $thong_ke = $this->datTourModel->thongKeBooking($thang, $nam);

            // Lấy booking mới nhất
            $booking_moi_nhat = $this->datTourModel->getBookingMoiNhat($thang, $nam);

            // Lấy thống kê theo tour
            $thong_ke_tour = $this->datTourModel->getThongKeTour($thang, $nam);

            require_once 'views/dattour/thongKe.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải thống kê: " . $e->getMessage();
            require_once 'views/dattour/thongKe.php';
        }
    }
}
