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
        $loai_khach = $_GET['loai_khach'] ?? '';

        try {
            $dat_tours = $this->datTourModel->getAllDatTour($search, $trang_thai, $lich_khoi_hanh_id, $loai_khach);
            $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();
            $stats = $this->datTourModel->getBookingStats();

            require_once 'views/dattour/listDatTour.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi tải danh sách: " . $e->getMessage();
            require_once 'views/dattour/listDatTour.php';
        }
    }

    // Form đặt tour khách lẻ
    public function datTourLe()
    {
        $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();
        require_once 'views/dattour/datTourLe.php';
    }

    // Form đặt tour công ty
    public function datTourDoan()
    {
        $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();
        require_once 'views/dattour/datTourDoan.php';
    }

    // Xử lý đặt tour mới - ĐÃ SỬA
    public function storeBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $loai_khach = $_POST['loai_khach'] ?? 'le';

                // Validate dữ liệu
                $errors = [];
                if (empty($_POST['lich_khoi_hanh_id'])) {
                    $errors[] = "Vui lòng chọn lịch khởi hành!";
                }
                if (empty($_POST['ho_ten'])) {
                    $errors[] = "Vui lòng nhập họ tên!";
                }
                if (empty($_POST['so_dien_thoai'])) {
                    $errors[] = "Vui lòng nhập số điện thoại!";
                }
                if ($loai_khach === 'doan' && empty($_POST['ten_doan'])) {
                    $errors[] = "Vui lòng nhập tên đoàn!";
                }
                if ($loai_khach === 'doan' && empty($_POST['loai_doan'])) {
                    $errors[] = "Vui lòng chọn loại đoàn!";
                }

                if (!empty($errors)) {
                    throw new Exception(implode("<br>", $errors));
                }

                // Chuẩn bị dữ liệu
                $data = [
                    'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                    'khach_hang' => [
                        'ho_ten' => $_POST['ho_ten'],
                        'email' => $_POST['email'] ?? '',
                        'so_dien_thoai' => $_POST['so_dien_thoai'],
                        'cccd' => $_POST['cccd'] ?? '',
                        'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                        'gioi_tinh' => $_POST['gioi_tinh'] ?? 'nam',
                        'dia_chi' => $_POST['dia_chi'] ?? ''
                    ],
                    'thanh_vien' => [],
                    'ghi_chu' => $_POST['ghi_chu'] ?? ''
                ];

                // Thêm thông tin đoàn
                if ($loai_khach === 'doan') {
                    $data['ten_doan'] = $_POST['ten_doan'];
                    $data['loai_doan'] = $_POST['loai_doan'] ?? '';
                }

                // Xử lý thông tin thành viên
                if (isset($_POST['thanh_vien_ho_ten']) && is_array($_POST['thanh_vien_ho_ten'])) {
                    foreach ($_POST['thanh_vien_ho_ten'] as $index => $ho_ten) {
                        if (!empty(trim($ho_ten))) {
                            $data['thanh_vien'][] = [
                                'ho_ten' => trim($ho_ten),
                                'cccd' => $_POST['thanh_vien_cccd'][$index] ?? '',
                                'ngay_sinh' => $_POST['thanh_vien_ngay_sinh'][$index] ?? null,
                                'gioi_tinh' => $_POST['thanh_vien_gioi_tinh'][$index] ?? 'nam',
                                'yeu_cau_dac_biet' => $_POST['thanh_vien_yeu_cau'][$index] ?? ''
                            ];
                        }
                    }
                }

                // Kiểm tra có thành viên nào không
                if (empty($data['thanh_vien'])) {
                    throw new Exception("Vui lòng thêm ít nhất một khách hàng tham gia!");
                }

                // Tạo booking
                $phieu_dat_tour_id = $this->datTourModel->datTourMoi($data, $loai_khach);

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

                // Quay lại form tương ứng
                $redirect = $loai_khach === 'doan' ? 'dat-tour-doan' : 'dat-tour-le';
                header('Location: index.php?act=' . $redirect);
                exit;
            }
        }
    }

    // Chi tiết đặt tour
    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $dat_tour = $this->datTourModel->getDatTourById($id);

        if (!$dat_tour) {
            $_SESSION['error'] = "Không tìm thấy đặt tour!";
            header('Location: index.php?act=dat-tour');
            return;
        }

        $thanh_vien_list = $this->datTourModel->getThanhVienByDatTour($id);
        require_once 'views/dattour/detailDatTour.php';
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

    // Lấy thông tin lịch khởi hành (AJAX)
    public function getLichKhoiHanhInfo()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh = $this->datTourModel->getLichKhoiHanhById($id);

        header('Content-Type: application/json');
        echo json_encode($lich_khoi_hanh);
        exit;
    }

    // Thống kê
    public function thongKe()
    {
        $thang = $_GET['thang'] ?? date('m');
        $nam = $_GET['nam'] ?? date('Y');

        $thong_ke = $this->datTourModel->thongKeBooking($thang, $nam);
        $booking_le = $this->datTourModel->getBookingByLoaiKhach('le');
        $booking_doan = $this->datTourModel->getBookingByLoaiKhach('doan');
        require_once 'views/dattour/thongKe.php';
    }

    // Sửa đặt tour
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $dat_tour = $this->datTourModel->getDatTourById($id);

        if (!$dat_tour) {
            $_SESSION['error'] = "Không tìm thấy đặt tour!";
            header('Location: index.php?act=dat-tour');
            return;
        }

        $thanh_vien_list = $this->datTourModel->getThanhVienByDatTour($id);
        $lich_khoi_hanh_list = $this->datTourModel->getLichKhoiHanhAvailable();

        require_once 'views/dattour/editDatTour.php';
    }

    // Cập nhật đặt tour
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'];
                $data = [
                    'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                    'ghi_chu' => $_POST['ghi_chu'] ?? '',
                    'thanh_vien' => []
                ];

                // Xử lý thông tin thành viên
                if (isset($_POST['thanh_vien_ho_ten']) && is_array($_POST['thanh_vien_ho_ten'])) {
                    foreach ($_POST['thanh_vien_ho_ten'] as $index => $ho_ten) {
                        if (!empty(trim($ho_ten))) {
                            $data['thanh_vien'][] = [
                                'id' => $_POST['thanh_vien_id'][$index] ?? null,
                                'ho_ten' => trim($ho_ten),
                                'cccd' => $_POST['thanh_vien_cccd'][$index] ?? '',
                                'ngay_sinh' => $_POST['thanh_vien_ngay_sinh'][$index] ?? null,
                                'gioi_tinh' => $_POST['thanh_vien_gioi_tinh'][$index] ?? 'nam',
                                'yeu_cau_dac_biet' => $_POST['thanh_vien_yeu_cau'][$index] ?? ''
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

    // In hóa đơn

    public function print()
    {
        $id = $_GET['id'] ?? 0;

        // Lấy thông tin đặt tour
        $dat_tour = $this->datTourModel->getDatTourById($id);

        if (!$dat_tour) {
            $_SESSION['error'] = "Không tìm thấy đặt tour!";
            header('Location: index.php?act=dat-tour');
            return;
        }

        // Lấy các thông tin liên quan
        $thanh_vien_list = $this->datTourModel->getThanhVienByDatTour($id);

        // Lấy thông tin khách hàng từ kết quả getDatTourById (đã có sẵn)
        $khach_hang = [
            'ho_ten' => $dat_tour['ho_ten'] ?? '',
            'email' => $dat_tour['email'] ?? '',
            'so_dien_thoai' => $dat_tour['so_dien_thoai'] ?? '',
            'cccd' => $dat_tour['cccd'] ?? '',
            'ngay_sinh' => $dat_tour['ngay_sinh'] ?? '',
            'gioi_tinh' => $dat_tour['gioi_tinh'] ?? '',
            'dia_chi' => $dat_tour['dia_chi'] ?? ''
        ];

        // Lấy thông tin tour và lịch khởi hành từ kết quả getDatTourById
        $tour = [
            'ten_tour' => $dat_tour['ten_tour'] ?? '',
            'ma_tour' => $dat_tour['ma_tour'] ?? '',
            'gia_tour' => $dat_tour['gia_tour'] ?? 0
        ];

        $lich_khoi_hanh = [
            'ngay_bat_dau' => $dat_tour['ngay_bat_dau'] ?? '',
            'ngay_ket_thuc' => $dat_tour['ngay_ket_thuc'] ?? '',
            'gio_tap_trung' => $dat_tour['gio_tap_trung'] ?? '',
            'diem_tap_trung' => $dat_tour['diem_tap_trung'] ?? ''
        ];

        require_once 'views/dattour/printDatTour.php';
    }
}
