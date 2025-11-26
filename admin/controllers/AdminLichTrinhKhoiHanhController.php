<?php
class AdminLichKhoiHanhController
{
    public $lichKhoiHanhModel;

    public function __construct()
    {
        $this->lichKhoiHanhModel = new AdminLichKhoiHanh();
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

        require_once './views/lichtrinhkhoihanh/addLichTrinh.php';
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

        require_once './views/lichtrinhkhoihanh/editLichTrinh.php';
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
}
