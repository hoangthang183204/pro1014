<?php
require_once __DIR__ . '/../models/LichTrinhModel.php';

class LichTrinhController
{
    private $model;

    public function __construct()
    {
        $this->model = new LichTrinhModel();
    }

    /**
     * Hiển thị danh sách lịch trình
     */
    public function index()
    {
        // Kiểm tra đăng nhập
        $userId = $_SESSION['user_id'] ?? $_SESSION['guide_id'] ?? null;
        if (!$userId) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Lấy ID hướng dẫn viên từ user_id
        $guideId = $this->model->getGuideIdByUserId($userId);
        if (!$guideId) {
            // Nếu không tìm thấy qua user_id, thử dùng trực tiếp guide_id từ session
            $guideId = $_SESSION['guide_id'] ?? null;
            if (!$guideId) {
                $_SESSION['error'] = "Không tìm thấy thông tin hướng dẫn viên";
                include __DIR__ . '/../views/schedule/listLichTrinh.php';
                return;
            }
        }

        // Lấy dữ liệu
        $lichTrinhList = $this->model->getLichTrinhByGuideId($guideId);
        $thongKe = $this->model->getThongKeTour($guideId);

        // Hiển thị view
        include __DIR__ . '/../views/schedule/listLichTrinh.php';
    }

    /**
     * Hiển thị chi tiết lịch trình
     */
    public function detail()
    {
        // Kiểm tra đăng nhập
        $userId = $_SESSION['user_id'] ?? $_SESSION['guide_id'] ?? null;
        if (!$userId) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Lấy ID hướng dẫn viên từ user_id
        $guideId = $this->model->getGuideIdByUserId($userId);
        if (!$guideId) {
            $guideId = $_SESSION['guide_id'] ?? null;
            if (!$guideId) {
                $_SESSION['error'] = "Không tìm thấy thông tin hướng dẫn viên";
                header("Location: " . BASE_URL_GUIDE . "?act=lich-trinh");
                exit();
            }
        }

        // Lấy ID lịch khởi hành
        $lichKhoiHanhId = $_GET['id'] ?? null;
        if (!$lichKhoiHanhId) {
            $_SESSION['error'] = "Không tìm thấy lịch trình";
            header("Location: " . BASE_URL_GUIDE . "?act=lich-trinh");
            exit();
        }

        // Lấy dữ liệu chi tiết
        $data = $this->model->getChiTietLichTrinh($lichKhoiHanhId, $guideId);

        if (!$data) {
            $_SESSION['error'] = "Bạn không có quyền xem lịch trình này hoặc lịch trình không tồn tại";
            header("Location: " . BASE_URL_GUIDE . "?act=lich-trinh");
            exit();
        }
         // DEBUG: Kiểm tra dữ liệu
    echo "<pre>";
    echo "LichKhoiHanh ID: " . $lichKhoiHanhId . "\n";
    echo "Guide ID: " . $guideId . "\n";
    echo "Data: " . print_r($data, true);
    echo "</pre>";
    // exit(); // Tạm thời comment để xem dữ liệu

        // Hiển thị view
        include __DIR__ . '/../views/schedule/detailLichTrinh.php';
    }

    /**
     * Xử lý cập nhật checklist
     */
    public function updateChecklist()
    {
        // Kiểm tra đăng nhập
        $userId = $_SESSION['user_id'] ?? $_SESSION['guide_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit();
        }

        // Lấy ID hướng dẫn viên từ user_id
        $guideId = $this->model->getGuideIdByUserId($userId);
        if (!$guideId) {
            $guideId = $_SESSION['guide_id'] ?? null;
            if (!$guideId) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin hướng dẫn viên']);
                exit();
            }
        }

        // Lấy dữ liệu từ POST
        $checklistId = $_POST['checklist_id'] ?? null;
        $status = $_POST['status'] ?? 0;
        $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? null;

        if (!$checklistId || !$lichKhoiHanhId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit();
        }

        // Cập nhật database - KHÔNG CẦN trạng thái đồng bộ
        $result = $this->model->updateChecklistStatus($checklistId, $status, $guideId);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'thoi_gian' => date('d/m/Y H:i')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
    }
    /**
     * Hiển thị lịch làm việc của hướng dẫn viên
     */
    public function lichLamViec()
    {
        // Kiểm tra đăng nhập
        $userId = $_SESSION['user_id'] ?? $_SESSION['guide_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem lịch làm việc";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Lấy ID hướng dẫn viên từ user_id
        $guideId = $this->model->getGuideIdByUserId($userId);
        if (!$guideId) {
            $guideId = $_SESSION['guide_id'] ?? null;
            if (!$guideId) {
                $_SESSION['error'] = "Không tìm thấy thông tin hướng dẫn viên";
                header("Location: " . BASE_URL_GUIDE . "?act=lich-trinh");
                exit();
            }
        }

        // Lấy thông tin hướng dẫn viên
        $guideInfo = $this->model->getGuideInfo($guideId);

        if (!$guideInfo) {
            $_SESSION['error'] = "Không tìm thấy thông tin hướng dẫn viên";
            header("Location: " . BASE_URL_GUIDE . "?act=lich-trinh");
            exit();
        }

        // Lấy tham số ngày (nếu có)
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        // Tính ngày đầu tháng và cuối tháng
        $startDate = date("{$year}-{$month}-01");
        $endDate = date("{$year}-{$month}-t", strtotime($startDate));

        // Lấy lịch làm việc
        $lichLamViec = $this->model->getLichLamViecByGuideId($guideId, $startDate, $endDate);

        // Lấy tất cả lịch trình tour
        $lichTrinhTours = $this->model->getAllLichTrinhByGuideId($guideId);

        // Gộp dữ liệu
        $data = [
            'guide_info' => $guideInfo,
            'lich_lam_viec' => $lichLamViec ?? [],
            'lich_trinh_tours' => $lichTrinhTours ?? [],
            'current_month' => $month,
            'current_year' => $year
        ];

        // Hiển thị view
        include __DIR__ . '/../views/schedule/lichLamViec.php';
    }
    public function updateChecklistForGuide()
{
    // Kiểm tra đăng nhập
    $userId = $_SESSION['user_id'] ?? $_SESSION['guide_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        exit();
    }

    // Lấy ID hướng dẫn viên từ user_id
    $guideId = $this->model->getGuideIdByUserId($userId);
    if (!$guideId) {
        $guideId = $_SESSION['guide_id'] ?? null;
        if (!$guideId) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin hướng dẫn viên']);
            exit();
        }
    }

    // Lấy dữ liệu từ POST
    $checklistId = $_POST['id'] ?? null;
    $status = $_POST['hoan_thanh'] ?? 0;
    
    // Validate
    if (!$checklistId) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin checklist']);
        exit();
    }

    // Cập nhật database
    $result = $this->model->updateChecklistStatus($checklistId, $status, $guideId);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật checklist thành công',
            'thoi_gian' => date('d/m/Y H:i:s')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
    }
}
}
?>