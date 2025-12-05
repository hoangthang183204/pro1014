<?php 
class DashboardHDVController
{
    private $model;

    public function __construct()
    {
        require_once __DIR__ . '/../models/HDVDashboard.php';
        $this->model = new HDVDashboard();
    }

    public function home()
    {
        if (!checkGuideLogin()) {
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $guideId = $_SESSION['guide_id'];
        
        // Debug: Kiểm tra guide_id trong session
        error_log("Dashboard Controller - guide_id từ session: " . $guideId);
        
        // Lấy ID hướng dẫn viên từ Model
        $guideInfo = $this->model->getGuideInfo($guideId);
        
        if (!$guideInfo) {
            error_log("Dashboard Controller - Không tìm thấy guideInfo cho user_id: " . $guideId);
            $_SESSION['error'] = "Không tìm thấy thông tin hướng dẫn viên!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }
        
        error_log("Dashboard Controller - Tìm thấy hdv_id: " . $guideInfo['id']);
        
        $hdvId = $guideInfo['id'];
        
        try {
            // Lấy tất cả dữ liệu từ Model với try-catch riêng cho từng phương thức
            $data = [];
            
            // Guide info
            $data['guideInfo'] = $guideInfo;
            
            // Các phương thức có thể gây lỗi
            try {
                $data['tourSapKhoiHanh'] = $this->model->getTourSapKhoiHanh($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getTourSapKhoiHanh: " . $e->getMessage());
                $data['tourSapKhoiHanh'] = [];
            }
            
            try {
                $data['suCoCanXuLy'] = $this->model->getSuCoCanXuLy($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getSuCoCanXuLy: " . $e->getMessage());
                $data['suCoCanXuLy'] = [];
            }
            
            try {
                $data['thongKe'] = $this->model->getThongKe($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getThongKe: " . $e->getMessage());
                $data['thongKe'] = [];
            }
            
            try {
                $data['doanhThu'] = $this->model->getDoanhThuThang($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getDoanhThuThang: " . $e->getMessage());
                $data['doanhThu'] = ['tong_doanh_thu' => 0, 'so_don_hang' => 0];
            }
            
            try {
                $data['danhGia'] = $this->model->getDanhGiaTrungBinh($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getDanhGiaTrungBinh: " . $e->getMessage());
                $data['danhGia'] = ['diem_trung_binh' => 0, 'so_tour' => 0];
            }
            
            try {
                $data['lichTrinhHomNay'] = $this->model->getLichTrinhHomNay($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getLichTrinhHomNay: " . $e->getMessage());
                $data['lichTrinhHomNay'] = [];
            }
            
            // Các phương thức khác (không bắt buộc)
            $data['nhatKyGanDay'] = $this->model->getNhatKyGanDay($hdvId, 3);
            $data['checklistChuaHoanThanh'] = $this->model->getChecklistChuaHoanThanh($hdvId);
            $data['thongKeLoaiTour'] = $this->model->getThongKeLoaiTour($hdvId);
            $data['topKhachHang'] = $this->model->getTopKhachHang($hdvId, 3);
            $data['thongBaoMoi'] = $this->model->getThongBaoMoi($hdvId);

            // Debug dữ liệu
            error_log("Dashboard Controller - tourSapKhoiHanh count: " . count($data['tourSapKhoiHanh']));
            error_log("Dashboard Controller - thongKe: " . json_encode($data['thongKe']));
            
            // Hiển thị view
            extract($data);
            require_once './views/trangchu.php';
            
        } catch (Exception $e) {
            error_log("Dashboard Controller - Lỗi tổng: " . $e->getMessage());
            error_log("Dashboard Controller - Trace: " . $e->getTraceAsString());
            
            // Hiển thị lỗi đơn giản và vẫn load view
            echo "<div style='padding: 10px; margin: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>";
            echo "<p><strong>Lỗi Dashboard:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><small>Vui lòng thông báo cho quản trị viên</small></p>";
            echo "</div>";
            
            // Vẫn hiển thị view cơ bản với dữ liệu rỗng
            $data = [
                'guideInfo' => $guideInfo ?? [],
                'tourSapKhoiHanh' => [],
                'suCoCanXuLy' => [],
                'thongKe' => [],
                'doanhThu' => ['tong_doanh_thu' => 0, 'so_don_hang' => 0],
                'danhGia' => ['diem_trung_binh' => 0, 'so_tour' => 0],
                'lichTrinhHomNay' => [],
                'nhatKyGanDay' => [],
                'checklistChuaHoanThanh' => [],
                'thongKeLoaiTour' => [],
                'topKhachHang' => [],
                'thongBaoMoi' => []
            ];
            
            extract($data);
            require_once './views/trangchu.php';
        }
    }
}
?>