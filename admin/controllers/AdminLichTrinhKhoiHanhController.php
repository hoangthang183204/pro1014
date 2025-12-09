<?php
class AdminLichKhoiHanhController
{
    public $lichKhoiHanhModel;
    public $tourModel;

    public function __construct()
    {
        $this->lichKhoiHanhModel = new AdminLichKhoiHanh();
        $this->tourModel = new AdminTour();
    }

    // Danh sách lịch khởi hành
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $trang_thai = $_GET['trang_thai'] ?? '';
        $thang = $_GET['thang'] ?? '';
        $nam = $_GET['nam'] ?? '';

        $lich_khoi_hanh = $this->lichKhoiHanhModel->getAllLichKhoiHanh($search, $trang_thai, $thang, $nam);

        require_once './views/lichtrinhkhoihanh/listKhoiHanh.php';
    }

    // Hiển thị form tạo lịch khởi hành mới
    public function create()
    {
        $tours = $this->lichKhoiHanhModel->getAllToursActive();

        require_once './views/lichtrinhkhoihanh/addLichKhoiHanh.php';
    }

    // Xử lý tạo lịch khởi hành mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_bat_dau' => $_POST['ngay_bat_dau'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'gio_tap_trung' => $_POST['gio_tap_trung'],
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'so_cho_toi_da' => $_POST['so_cho_toi_da'],
                'ghi_chu_van_hanh' => $_POST['ghi_chu_van_hanh'] ?? ''
            ];

            // Validate ngày
            if ($data['ngay_bat_dau'] > $data['ngay_ket_thuc']) {
                $_SESSION['error'] = 'Ngày kết thúc phải sau ngày bắt đầu';
                header('Location: ?act=lich-khoi-hanh-create');
                return;
            }

            $result = $this->lichKhoiHanhModel->createLichKhoiHanh($data);

            if ($result) {
                header('Location: index.php?act=lich-khoi-hanh&success=' . urlencode('Thêm lịch trình thành công!'));
            } else {
                header('Location: index.php?act=lich-khoi-hanh-create&error=' . urlencode('Có lỗi xảy ra khi thêm!'));
            }
        }
    }

    // Hiển thị form sửa lịch khởi hành
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $tours = $this->lichKhoiHanhModel->getAllToursActive();

        require_once './views/lichtrinhkhoihanh/editLichKhoiHanh.php';
    }

    // Xử lý cập nhật lịch khởi hành
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_bat_dau' => $_POST['ngay_bat_dau'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'gio_tap_trung' => $_POST['gio_tap_trung'],
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'so_cho_toi_da' => $_POST['so_cho_toi_da'],
                'ghi_chu_van_hanh' => $_POST['ghi_chu_van_hanh'] ?? '',
                'trang_thai' => $_POST['trang_thai']
            ];

            // Validate ngày
            if ($data['ngay_bat_dau'] > $data['ngay_ket_thuc']) {
                $_SESSION['error'] = 'Ngày kết thúc phải sau ngày bắt đầu';
                header('Location: ?act=lich-khoi-hanh-edit&id=' . $id);
                return;
            }

            $result = $this->lichKhoiHanhModel->updateLichKhoiHanh($id, $data);

            if ($result) {
                header('Location: index.php?act=lich-khoi-hanh&success=' . urlencode('Sửa lịch trình thành công!'));
            } else {
                header('Location: index.php?act=lich-khoi-hanh-edit&error=' . urlencode('Có lỗi xảy ra khi sửa lịch trình!'));
            }
        }
    }

    // Xóa lịch khởi hành
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        $result = $this->lichKhoiHanhModel->deleteLichKhoiHanh($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa lịch khởi hành thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa lịch khởi hành. Có thể đã có booking hoặc có lỗi xảy ra';
        }

        header('Location: ?act=lich-khoi-hanh');
    }

    public function phanCong()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        if (!$lich_khoi_hanh_id) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        // Kiểm tra nếu tour đã hoàn thành
        if ($lich_khoi_hanh['trang_thai'] === 'đã hoàn thành') {
            $_SESSION['error'] = 'Không thể phân công HDV cho tour đã hoàn thành';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        // Lấy danh sách HDV có sẵn (không bị trùng lịch)
        $huong_dan_vien_list = $this->lichKhoiHanhModel->getHuongDanVienCoSan(
            $lich_khoi_hanh_id,
            $lich_khoi_hanh['ngay_bat_dau'],
            $lich_khoi_hanh['ngay_ket_thuc']
        );

        $phan_cong_hien_tai = $this->lichKhoiHanhModel->getPhanCongHDV($lich_khoi_hanh_id);

        // Lấy tất cả HDV để hiển thị thông tin những người bận
        $all_hdv = $this->lichKhoiHanhModel->getAllHuongDanVien();

        require_once './views/lichtrinhkhoihanh/phanHuongDanVien.php';
    }

    // Xử lý phân công HDV
    public function phanCongStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'];
            $huong_dan_vien_id = $_POST['huong_dan_vien_id'];
            $ghi_chu = $_POST['ghi_chu'] ?? '';

            if (!$lich_khoi_hanh_id || !$huong_dan_vien_id) {
                $_SESSION['error'] = 'Vui lòng chọn HDV';
                header('Location: ?act=phan-cong&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
                return;
            }

            // Lấy thông tin lịch khởi hành để lấy ngày bắt đầu và kết thúc
            $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

            if (!$lich_khoi_hanh) {
                $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
                header('Location: ?act=phan-cong&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
                return;
            }

            $result = $this->lichKhoiHanhModel->phanCongHDV(
                $lich_khoi_hanh_id,
                $huong_dan_vien_id,
                $lich_khoi_hanh['ngay_bat_dau'],
                $lich_khoi_hanh['ngay_ket_thuc'],
                $ghi_chu
            );

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ?act=phan-cong&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
        }
    }

    // Hủy phân công HDV
    public function huyPhanCong()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        if (!$lich_khoi_hanh_id) {
            $_SESSION['error'] = 'Không tìm thấy lịch khởi hành';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $result = $this->lichKhoiHanhModel->huyPhanCongHDV($lich_khoi_hanh_id);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ?act=phan-cong&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
    }

    // Quản lý checklist trước tour
    public function checklistTruocTour()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $checklist = $this->lichKhoiHanhModel->getChecklistTruocTour($lich_khoi_hanh_id);

        require_once './views/lichtrinhkhoihanh/checkListChuanbi.php';
    }

    public function themChecklist()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;
            $cong_viec = $_POST['cong_viec'] ?? '';

            if (empty($cong_viec)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập công việc']);
                return;
            }

            $result = $this->lichKhoiHanhModel->themChecklist([
                'lich_khoi_hanh_id' => $lich_khoi_hanh_id,
                'cong_viec' => $cong_viec,
                'nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            echo json_encode($result);
        }
    }

    public function updateChecklist()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $hoan_thanh = $_POST['hoan_thanh'] ?? 0;

            $result = $this->lichKhoiHanhModel->updateChecklist($id, [
                'hoan_thanh' => $hoan_thanh,
                'nguoi_hoan_thanh' => $_SESSION['user_id'] ?? 1,
                'thoi_gian_hoan_thanh' => $hoan_thanh ? date('Y-m-d H:i:s') : null
            ]);

            echo json_encode($result);
        }
    }

    public function xoaChecklist()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;

            $result = $this->lichKhoiHanhModel->xoaChecklist($id);

            echo json_encode($result);
        }
    }


    // Hiển thị trang quản lý trạm dừng chân
    public function tramDungChan()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại';
            header('Location: ?act=lich-khoi-hanh');
            return;
        }

        $tram_dung_chan = $this->lichKhoiHanhModel->getTramDungChan($lich_khoi_hanh_id);

        require_once './views/lichtrinhkhoihanh/tramDungChan.php';
    }

    // Thêm trạm dừng chân mới
    public function themTramDungChan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;
            $data = [
                'ten_tram' => $_POST['ten_tram'] ?? '',
                'thu_tu' => $_POST['thu_tu'] ?? 1
            ];

            $result = $this->lichKhoiHanhModel->themTramDungChan($lich_khoi_hanh_id, $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
        }
    }

    public function xoaTramDungChan()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        if (!$id || !$lich_khoi_hanh_id) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
            return;
        }

        $result = $this->lichKhoiHanhModel->xoaTramDungChan($id);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
    }

    public function suaTramDungChanForm()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        if (!$id || !$lich_khoi_hanh_id) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
            return;
        }

        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);
        $tram = $this->lichKhoiHanhModel->getTramById($id);

        if (!$lich_khoi_hanh || !$tram) {
            $_SESSION['error'] = 'Không tìm thấy thông tin';
            header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
            return;
        }

        require_once './views/lichtrinhkhoihanh/suaTram.php';
    }

    // Xử lý sửa trạm
    public function suaTramDungChan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;
            $data = [
                'ten_tram' => $_POST['ten_tram'] ?? '',
                'thu_tu' => $_POST['thu_tu'] ?? 1
            ];

            $result = $this->lichKhoiHanhModel->suaTramDungChan($id, $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }

            header('Location: ?act=tram-dung-chan&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
        }
    }

    // Hiển thị danh sách lịch trình của một lịch khởi hành
    public function lichTrinh()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại!';
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }

        // Lấy thông tin tour
        $tour = $this->tourModel->getTourById($lich_khoi_hanh['tour_id']);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }

        // Lấy lịch trình
        $lich_trinh = $this->lichKhoiHanhModel->getLichTrinhByLichKhoiHanh($lich_khoi_hanh_id);

        require_once './views/lichtrinhkhoihanh/lichTrinhTour.php';
    }

    public function storeLichTrinh()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;

            try {
                $data = [
                    'so_thu_tu_ngay' => $_POST['so_ngay'],
                    'so_ngay' => $_POST['so_ngay'],
                    'tieu_de' => $_POST['tieu_de'],
                    'mo_ta_hoat_dong' => $_POST['mo_ta_hoat_dong'],
                    'cho_o' => $_POST['cho_o'],
                    'bua_an' => $_POST['bua_an'],
                    'phuong_tien' => $_POST['phuong_tien'],
                    'ghi_chu_hdv' => $_POST['ghi_chu_hdv'] ?? '',
                    'thu_tu_sap_xep' => $_POST['thu_tu_sap_xep'] ?? 0
                ];

                $result = $this->lichKhoiHanhModel->createLichTrinh($lich_khoi_hanh_id, $data);

                if ($result) {
                    $_SESSION['success'] = 'Thêm lịch trình thành công!';
                } else {
                    $_SESSION['error'] = 'Không thể thêm lịch trình!';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }

            header('Location: ?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
            exit();
        }
    }

    // Form thêm lịch trình mới - ĐÃ SỬA
    public function createLichTrinh()
    {
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_khoi_hanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại!';
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }

        // Lấy thông tin tour
        $tour = $this->tourModel->getTourById($lich_khoi_hanh['tour_id']);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }

        // Lấy max số thứ tự ngày hiện tại
        $max_so_thu_tu = 0;
        $lich_trinh = $this->lichKhoiHanhModel->getLichTrinhByLichKhoiHanh($lich_khoi_hanh_id);
        if (!empty($lich_trinh)) {
            $max_so_thu_tu = max(array_column($lich_trinh, 'so_thu_tu_ngay'));
        }

        require_once './views/lichtrinhkhoihanh/addLichTrinh.php';
    }

    // Form sửa lịch trình
    public function editLichTrinh()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        $lich_trinh = $this->lichKhoiHanhModel->getLichTrinhById($id);
        $lich_khoi_hanh = $this->lichKhoiHanhModel->getLichKhoiHanhById($lich_khoi_hanh_id);

        if (!$lich_trinh || !$lich_khoi_hanh) {
            $_SESSION['error'] = 'Không tìm thấy thông tin!';
            header('Location: ?act=lich-khoi-hanh');
            exit();
        }

        // Lấy thông tin tour
        $tour = $this->tourModel->getTourById($lich_khoi_hanh['tour_id']);

        require_once './views/lichtrinhkhoihanh/editLichTrinh.php';
    }

    // Xử lý cập nhật lịch trình
    public function updateLichTrinh()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $lich_khoi_hanh_id = $_POST['lich_khoi_hanh_id'] ?? 0;

            try {
                $data = [
                    'so_thu_tu_ngay' => $_POST['so_ngay'],
                    'so_ngay' => $_POST['so_ngay'],
                    'tieu_de' => $_POST['tieu_de'],
                    'mo_ta_hoat_dong' => $_POST['mo_ta_hoat_dong'],
                    'cho_o' => $_POST['cho_o'],
                    'bua_an' => $_POST['bua_an'],
                    'phuong_tien' => $_POST['phuong_tien'],
                    'ghi_chu_hdv' => $_POST['ghi_chu_hdv'] ?? '',
                    'thu_tu_sap_xep' => $_POST['thu_tu_sap_xep'] ?? 0
                ];

                $result = $this->lichKhoiHanhModel->updateLichTrinh($id, $data);

                if ($result) {
                    $_SESSION['success'] = 'Cập nhật lịch trình thành công!';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật lịch trình!';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }

            header('Location: ?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
            exit();
        }
    }

    // Xóa lịch trình
    public function deleteLichTrinh()
    {
        $id = $_GET['id'] ?? 0;
        $lich_khoi_hanh_id = $_GET['lich_khoi_hanh_id'] ?? 0;

        try {
            $result = $this->lichKhoiHanhModel->deleteLichTrinh($id);

            if ($result) {
                $_SESSION['success'] = 'Xóa lịch trình thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa lịch trình!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=' . $lich_khoi_hanh_id);
        exit();
    }
}
