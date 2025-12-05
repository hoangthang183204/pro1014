<?php
require_once './models/KhachDoanModel.php';

class KhachDoanController
{
    public $model;

    public function __construct()
    {
        $this->model = new KhachDoanModel();
    }

    // Trong file KhachDoanController.php

public function update_checkin_status()
{
    // Kiểm tra đăng nhập
    if (!checkGuideLogin()) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit(); // <--- Thêm exit
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
        exit(); // <--- BẮT BUỘC PHẢI CÓ DÒNG NÀY để ngắt HTML thừa
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
        $soLuongCoMat = 0;
        $tienDoCheckIn = 0;

        if (!empty($rawList)) {
            foreach ($rawList as $row) {


                // Lấy thông tin đã hủy trước đó
                $daHuyTruocDo = $row['da_huy_truoc_do'] ?? 0;

                // --- LOGIC ĐẾM MỚI (ĐÃ SỬA) ---
                if ($daHuyTruocDo > 0) {
                    $tienDoCheckIn++;
                } else {
                    // Nếu khách bình thường, xét trạng thái hiện tại
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
                    'da_huy_truoc_do' => $row['da_huy_truoc_do'] ?? 0,
                    'yeu_cau_confirmed' => $yeuCauConfirmed // THÊM DÒNG NÀY
                ];
            }
        }

        $totalKhach = count($dsKhach);
        $isDuNguoi = ($totalKhach > 0 && $tienDoCheckIn == $totalKhach);

        require_once './views/khachdoan/list.php';
    }
    // === THÊM MỚI: Xử lý Check-in hàng loạt ===
    public function check_in_bulk()
    {
        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? []; // Mảng ID khách hàng
            $status = $_POST['status'] ?? '';
            $tram_id = $_POST['tram_id'] ?? 0;

            if (empty($ids) || empty($status) || empty($tram_id)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            // Gọi Model xử lý hàng loạt
            $count = $this->model->updateCheckInBulk($ids, $status, $tram_id);

            if ($count !== false) {
                echo json_encode(['success' => true, 'count' => $count]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật database']);
            }
        }
    }



    // === THÊM MỚI: Phương thức confirm yêu cầu đặc biệt ===
    public function confirm_yeu_cau()
    {
        // Bật hiển thị lỗi PHP
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if (!checkGuideLogin()) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug chi tiết
            error_log("=== CONFIRM YEU CAU DEBUG ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("Session guide_id: " . ($_SESSION['guide_id'] ?? 'NOT SET'));

            $khach_id = $_POST['khach_id'] ?? null;

            error_log("khach_id từ POST: " . ($khach_id ?: 'NULL'));

            if (!$khach_id) {
                error_log("ERROR: Thiếu khach_id");
                echo json_encode(['success' => false, 'message' => 'Thiếu ID khách hàng']);
                return;
            }

            // Kiểm tra xem khach_id có phải là số không
            if (!is_numeric($khach_id)) {
                error_log("ERROR: khach_id không phải số: " . $khach_id);
                echo json_encode(['success' => false, 'message' => 'ID khách hàng không hợp lệ']);
                return;
            }

            // CHỈ CẦN khach_id, không cần tram_id nữa
            try {
                $result = $this->model->confirmYeuCauDacBiet($khach_id);

                error_log("Kết quả từ Model: " . ($result ? 'TRUE' : 'FALSE'));

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Đã xác nhận thành công']);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Không thể xác nhận. Vui lòng kiểm tra: 
                    1. Cột yeu_cau_dac_biet_confirmed có trong bảng checkin_khach_hang không?
                    2. Khách hàng có tồn tại không?
                    3. Kiểm tra error log PHP.'
                    ]);
                }
            } catch (Exception $e) {
                error_log("EXCEPTION in confirm_yeu_cau: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
        }
    }
}
