<?php
require_once './models/KhachDoanModel.php';

class KhachDoanController
{
    public $model;

    public function __construct()
    {
        $this->model = new KhachDoanModel();
    }

    public function update_checkin_status()
    {
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? 'chưa đến';
            $tram_id = $_POST['tram_id'] ?? null; // Nhận thêm ID trạm

            if ($id && $tram_id) {
                $result = $this->model->updateCheckIn($id, $status, $tram_id);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function index()
    {
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
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

        // 1. Tự động tạo trạm nếu chưa có
        $this->model->checkAndCreateTramMau($id_lich);

        // 2. Lấy danh sách trạm & Xác định trạm hiện tại
        $dsTram = $this->model->getTramByLich($id_lich);
        $selected_tram_id = $_GET['tram_id'] ?? ($dsTram[0]['id'] ?? 0);

        // 3. Lấy thông tin tour & Danh sách khách theo trạm
        $info = $this->model->getTourInfoById($id_lich);
        $tourInfo = [
            'ten_tour' => $info['ten_tour'] ?? 'Không tìm thấy tour',
            'ngay_di' => isset($info['ngay_bat_dau']) ? date('d/m/Y', strtotime($info['ngay_bat_dau'])) : 'N/A'
        ];

        $rawList = $this->model->getKhachDoanByLich($id_lich, $selected_tram_id);
        $dsKhach = [];
        $soLuongCoMat = 0; // Chỉ đếm người thực sự có mặt
        $tienDoCheckIn = 0; // Đếm số người đã kiểm tra (bao gồm cả vắng)

        if (!empty($rawList)) {
            foreach ($rawList as $row) {
                $ghiChu = $row['ghi_chu'] ?? '';
                $nhom = "Mã vé: " . $row['ma_dat_tour'];

                // Đếm người đã check-in
                // LOGIC ĐẾM MỚI:
                if ($row['trang_thai_checkin'] == 'đã đến') {
                    $soLuongCoMat++;    // Tăng số người hiện diện
                    $tienDoCheckIn++;   // Tăng tiến độ
                } elseif ($row['trang_thai_checkin'] == 'vắng mặt') {
                    $tienDoCheckIn++;   // Chỉ tăng tiến độ, KHÔNG tăng số người hiện diện
                }

                $tuoi = '';
                if (!empty($row['ngay_sinh'])) {
                    $birthDate = new DateTime($row['ngay_sinh']);
                    $today = new DateTime();
                    $tuoi = $today->diff($birthDate)->y;
                }

                $dsKhach[] = [
                    'id' => $row['id'],
                    'trang_thai_checkin' => $row['trang_thai_checkin'],
                    'ho_ten' => $row['ten_khach'],
                    'gioi_tinh' => ucfirst($row['gioi_tinh']),
                    'tuoi' => $tuoi,
                    'sdt' => $row['sdt_lien_he'],
                    'nguoi_dat' => $row['nguoi_dat'],
                    'nhom' => $nhom,
                    'ghi_chu' => $ghiChu,
                    // [MỚI - QUAN TRỌNG] Thêm dòng này để view biết khách đã bị hủy chưa
                    'da_huy_truoc_do' => $row['da_huy_truoc_do'] ?? 0
                ];
            }
        }

        $totalKhach = count($dsKhach);
        $isDuNguoi = ($totalKhach > 0 && $tienDoCheckIn == $totalKhach);

        require_once './views/khachdoan/list.php';
    }
}
