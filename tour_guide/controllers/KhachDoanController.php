<?php
require_once './models/KhachDoanModel.php';

class KhachDoanController
{
    public $model;

    public function __construct()
    {
        $this->model = new KhachDoanModel();
    }

    // THÊM: Hàm xử lý Ajax cập nhật check-in
    public function update_checkin_status() {
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? 'chưa đến';

            if ($id) {
                $result = $this->model->updateCheckIn($id, $status);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function index()
    {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem danh sách khách!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $id_lich = $_GET['id'] ?? null;

        if (!$id_lich) {
            $guide_id = $_SESSION['guide_id'];
            $myTours = $this->model->getToursByGuide($guide_id);
            require_once './views/khachdoan/chontours.php';
            return;
        }

        $info = $this->model->getTourInfoById($id_lich);
        $tourInfo = [
            'ten_tour' => $info['ten_tour'] ?? 'Không tìm thấy tour',
            'ngay_di' => isset($info['ngay_bat_dau']) ? date('d/m/Y', strtotime($info['ngay_bat_dau'])) : 'N/A'
        ];

        $rawList = $this->model->getKhachDoanByLich($id_lich);
        $dsKhach = [];

        if (!empty($rawList)) {
            foreach ($rawList as $row) {
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
                    'id' => $row['id'], // QUAN TRỌNG: ID thành viên để update
                    'trang_thai_checkin' => $row['trang_thai_checkin'], // QUAN TRỌNG: Trạng thái hiện tại
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