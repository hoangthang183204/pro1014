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
        // Kiểm tra đăng nhập
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? 'chưa đến';
            $tram_id = $_POST['tram_id'] ?? null;

            if ($id && $tram_id) {
                $result = $this->model->updateCheckIn($id, $status, $tram_id);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            }
            exit(); // Đã có exit -> Tốt
        }
    }

    public function index()
    {
        // 1. Kiểm tra đăng nhập
        if (!checkGuideLogin()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $id_lich = $_GET['id'] ?? null;
        
        // Nếu chưa chọn lịch, hiển thị danh sách tour
        if (!$id_lich) {
            $guide_id = $_SESSION['guide_id'];
            $myTours = $this->model->getToursByGuide($guide_id);
            require_once './views/khachdoan/chontours.php';
            return;
        }

        // 2. Lấy danh sách trạm
        $dsTram = $this->model->getTramByLich($id_lich);

        // =========================================================================
        // [LOGIC MỚI] KIỂM TRA VÀ CHẶN TRẠM CHƯA MỞ KHÓA
        // =========================================================================
        $allowedTramIds = $this->model->getAllowedTramIds($id_lich);

        // [ĐÃ SỬA] Kiểm tra an toàn: Nếu không có trạm nào thì mặc định là 0
        $defaultTramId = !empty($dsTram) ? $dsTram[0]['id'] : 0;
        $requested_tram_id = $_GET['tram_id'] ?? $defaultTramId;

        // Kiểm tra: Nếu trạm muốn xem KHÔNG nằm trong danh sách được phép
        if (!empty($allowedTramIds) && !in_array($requested_tram_id, $allowedTramIds)) {
            // Tìm trạm cao nhất (xa nhất) mà họ được phép truy cập
            $lastAllowedId = end($allowedTramIds);
            
            // Chuyển hướng trình duyệt về trạm hợp lệ đó
            header("Location: ?act=xem_danh_sach_khach&id=$id_lich&tram_id=$lastAllowedId");
            exit();
        }

        $selected_tram_id = $requested_tram_id;
        // =========================================================================

        // 3. Lấy thông tin tour
        $info = $this->model->getTourInfoById($id_lich);
        $tourInfo = [
            'ten_tour' => $info['ten_tour'] ?? 'Không tìm thấy tour',
            'ngay_di' => isset($info['ngay_bat_dau']) ? date('d/m/Y', strtotime($info['ngay_bat_dau'])) : 'N/A'
        ];

        // 4. Lấy danh sách khách và tính toán tiến độ
        $rawList = $this->model->getKhachDoanByLich($id_lich, $selected_tram_id);
        $dsKhach = [];
        $soLuongCoMat = 0;
        $tienDoCheckIn = 0;

        if (!empty($rawList)) {
            foreach ($rawList as $row) {
                $daHuyTruocDo = $row['da_huy_truoc_do'] ?? 0;
                if ($daHuyTruocDo > 0) {
                    $tienDoCheckIn++;
                } else {
                    if ($row['trang_thai_checkin'] == 'đã đến') {
                        $soLuongCoMat++;
                        $tienDoCheckIn++;
                    } elseif ($row['trang_thai_checkin'] == 'vắng mặt') {
                        $tienDoCheckIn++;
                    }
                }

                $ghiChu = $row['ghi_chu'] ?? '';
                $nhom = "Mã vé: " . $row['ma_dat_tour'];
                $yeuCauConfirmed = $row['yeu_cau_confirmed'] ?? 0;

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
                    'da_huy_truoc_do' => $daHuyTruocDo,
                    'yeu_cau_confirmed' => $yeuCauConfirmed
                ];
            }
        }

        $totalKhach = count($dsKhach);
        $isDuNguoi = ($totalKhach > 0 && $tienDoCheckIn == $totalKhach);

        require_once './views/khachdoan/list.php';
    }

    // === Xử lý Check-in hàng loạt ===
    public function check_in_bulk()
    {
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit(); // [ĐÃ SỬA] Thêm exit
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? [];
            $status = $_POST['status'] ?? '';
            $tram_id = $_POST['tram_id'] ?? 0;

            if (empty($ids) || empty($status) || empty($tram_id)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit(); // [ĐÃ SỬA] Thêm exit
            }

            $count = $this->model->updateCheckInBulk($ids, $status, $tram_id);

            if ($count !== false) {
                echo json_encode(['success' => true, 'count' => $count]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật database']);
            }
            exit();
        }
    }

    // === Phương thức confirm yêu cầu đặc biệt ===
    public function confirm_yeu_cau()
    {
        // Bật hiển thị lỗi PHP để debug nếu cần
        // error_reporting(E_ALL); ini_set('display_errors', 1);
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit(); // [ĐÃ SỬA] Thêm exit
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $khach_id = $_POST['khach_id'] ?? null;

            if (!$khach_id || !is_numeric($khach_id)) {
                echo json_encode(['success' => false, 'message' => 'ID khách hàng không hợp lệ']);
                exit(); // [ĐÃ SỬA] Thêm exit
            }

            try {
                $result = $this->model->confirmYeuCauDacBiet($khach_id);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Đã xác nhận thành công']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể xác nhận.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
            }
            exit();
        }
    }
} 
