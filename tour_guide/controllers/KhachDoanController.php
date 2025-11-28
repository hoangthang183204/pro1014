<?php
require_once './models/KhachDoanModel.php';

class KhachDoanController
{
    public $model;

    public function __construct()
    {
        $this->model = new KhachDoanModel();
    }

    public function index()
    {
        // 1. Kiểm tra đăng nhập (Bắt buộc)
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem danh sách khách!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // 2. Lấy ID lịch khởi hành từ URL
        $id_lich = $_GET['id'] ?? null;

        // ---------------------------------------------------------
        // TRƯỜNG HỢP A: KHÔNG CÓ ID (Người dùng bấm từ Sidebar)
        // -> Hiển thị danh sách các Tour của HDV này để chọn
        // ---------------------------------------------------------
        if (!$id_lich) {
            $guide_id = $_SESSION['guide_id']; // Lấy ID của HDV đang đăng nhập

            // Gọi Model lấy danh sách tour được phân công
            $myTours = $this->model->getToursByGuide($guide_id);

            // Gửi dữ liệu sang View chọn tour
            require_once './views/khachdoan/chontours.php';
            return;
        }

        // ---------------------------------------------------------
        // TRƯỜNG HỢP B: CÓ ID (Người dùng đã chọn 1 tour cụ thể)
        // -> Hiển thị danh sách khách hàng của tour đó
        // 1. LUÔN Lấy thông tin Tour trước (để hiển thị trên Header xanh)
        $info = $this->model->getTourInfoById($id_lich);
        $tourInfo = [
            'ten_tour' => $info['ten_tour'] ?? 'Không tìm thấy tour',
            'ngay_di' => isset($info['ngay_bat_dau']) ? date('d/m/Y', strtotime($info['ngay_bat_dau'])) : 'N/A'
        ];

        // 2. Lấy danh sách khách
        $rawList = $this->model->getKhachDoanByLich($id_lich);
        $dsKhach = [];

        if (!empty($rawList)) {
            foreach ($rawList as $row) {
                // ... (Logic xử lý vòng lặp foreach giữ nguyên như cũ) ...
                // Copy đoạn xử lý json, tuổi, nhóm vào đây
                $noteData = json_decode($row['yeu_cau_dac_biet'], true);
                $ghiChu = isset($noteData['yeu_cau']) ? $noteData['yeu_cau'] : '';

                $nhom = ($row['loai_khach'] == 'doan') ? "Đoàn: " . $row['ten_doan'] : "Nhóm: " . $row['ma_dat_tour'];

                $tuoi = '';
                if (!empty($row['ngay_sinh'])) {
                    $birthDate = new DateTime($row['ngay_sinh']);
                    $today = new DateTime();
                    $tuoi = $today->diff($birthDate)->y;
                }

                $dsKhach[] = [
                    'ho_ten' => $row['ten_khach'],
                    'gioi_tinh' => ucfirst($row['gioi_tinh']),
                    'tuoi' => $tuoi,
                    'sdt' => $row['sdt_lien_he'],
                    'nguoi_dat' => $row['nguoi_dat'],
                    'nhom' => $nhom,
                    'ghi_chu' => $ghiChu
                ];
            }
        }

        require_once './views/khachdoan/list.php';
    }
}
