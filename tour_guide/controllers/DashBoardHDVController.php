
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
            
            // === THÊM DỮ LIỆU MỚI CHO CALENDAR ===
            // Đảm bảo có các biến mặc định trước
            $data['lichLamViec'] = [];
            $data['lichTrinhTours'] = [];
            $data['upcomingEvents'] = [];
            $data['calendarStats'] = ['tour_days' => 0, 'busy_days' => 0, 'off_days' => 0];
            $data['eventsByDate'] = [];
            
            try {
                // Lấy sự kiện lịch làm việc
                $data['lichLamViec'] = $this->model->getLichLamViec($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getLichLamViec: " . $e->getMessage());
                $data['lichLamViec'] = [];
            }
            
            try {
                // Lấy danh sách tour của hướng dẫn viên
                $data['lichTrinhTours'] = $this->model->getLichTrinhTours($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getLichTrinhTours: " . $e->getMessage());
                $data['lichTrinhTours'] = [];
            }
            
            try {
                // Lấy sự kiện sắp tới (7 ngày)
                $data['upcomingEvents'] = $this->model->getUpcomingEvents($hdvId, 7);
            } catch (Exception $e) {
                error_log("Lỗi getUpcomingEvents: " . $e->getMessage());
                $data['upcomingEvents'] = [];
            }
            
            try {
                // Lấy thống kê calendar
                $data['calendarStats'] = $this->model->getCalendarStats($hdvId);
            } catch (Exception $e) {
                error_log("Lỗi getCalendarStats: " . $e->getMessage());
                $data['calendarStats'] = ['tour_days' => 0, 'busy_days' => 0, 'off_days' => 0];
            }
            
            try {
                // Tạo mảng eventsByDate cho calendar
                if (!empty($data['lichLamViec']) || !empty($data['lichTrinhTours'])) {
                    $data['eventsByDate'] = $this->prepareEventsByDate($data['lichLamViec'], $data['lichTrinhTours']);
                }
            } catch (Exception $e) {
                error_log("Lỗi prepareEventsByDate: " . $e->getMessage());
                $data['eventsByDate'] = [];
            }
            
            // Các phương thức khác (không bắt buộc) - XÓA checklist
            $data['nhatKyGanDay'] = $this->model->getNhatKyGanDay($hdvId, 3);
            $data['thongKeLoaiTour'] = $this->model->getThongKeLoaiTour($hdvId);
            $data['topKhachHang'] = $this->model->getTopKhachHang($hdvId, 3);
            $data['thongBaoMoi'] = $this->model->getThongBaoMoi($hdvId);

            // Debug dữ liệu
            error_log("Dashboard Controller - tourSapKhoiHanh count: " . count($data['tourSapKhoiHanh']));
            error_log("Dashboard Controller - eventsByDate count: " . count($data['eventsByDate']));
            error_log("Dashboard Controller - upcomingEvents count: " . count($data['upcomingEvents']));
            
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
                'thongKe' => [],
                'doanhThu' => ['tong_doanh_thu' => 0, 'so_don_hang' => 0],
                'danhGia' => ['diem_trung_binh' => 0, 'so_tour' => 0],
                'lichTrinhHomNay' => [],
                'nhatKyGanDay' => [],
                'thongKeLoaiTour' => [],
                'topKhachHang' => [],
                'thongBaoMoi' => [],
                'lichLamViec' => [],
                'lichTrinhTours' => [],
                'upcomingEvents' => [],
                'calendarStats' => ['tour_days' => 0, 'busy_days' => 0, 'off_days' => 0],
                'eventsByDate' => []
            ];
            
            extract($data);
            require_once './views/trangchu.php';
        }
    }
    
    /**
     * Chuẩn bị mảng eventsByDate cho calendar từ lịch làm việc và tour
     */
    private function prepareEventsByDate($lichLamViec, $lichTrinhTours)
    {
        $eventsByDate = [];
        
        // Kiểm tra xem có dữ liệu không
        if (empty($lichLamViec) && empty($lichTrinhTours)) {
            return $eventsByDate;
        }
        
        // Thêm lịch làm việc vào mảng sự kiện
        if (!empty($lichLamViec)) {
            foreach ($lichLamViec as $item) {
                $date = $item['ngay'];
                if (!isset($eventsByDate[$date])) {
                    $eventsByDate[$date] = [];
                }
                
                $eventsByDate[$date][] = [
                    'type' => $item['loai_lich'],
                    'title' => $this->getEventTitle($item['loai_lich'], $item['ghi_chu'] ?? ''),
                    'ghi_chu' => $item['ghi_chu'] ?? ''
                ];
            }
        }
        
        // Thêm tour vào mảng sự kiện
        if (!empty($lichTrinhTours)) {
            foreach ($lichTrinhTours as $tour) {
                try {
                    // Kiểm tra ngày bắt đầu và kết thúc có hợp lệ không
                    if (empty($tour['ngay_bat_dau']) || empty($tour['ngay_ket_thuc'])) {
                        continue;
                    }
                    
                    $tourStart = new DateTime($tour['ngay_bat_dau']);
                    $tourEnd = new DateTime($tour['ngay_ket_thuc']);
                    
                    // Tạo khoảng ngày của tour
                    $period = new DatePeriod(
                        $tourStart,
                        new DateInterval('P1D'),
                        $tourEnd->modify('+1 day')
                    );
                    
                    foreach ($period as $date) {
                        $dateStr = $date->format('Y-m-d');
                        
                        // Nếu là ngày bắt đầu tour, hiển thị tên tour
                        if ($date->format('Y-m-d') == $tourStart->format('Y-m-d')) {
                            if (!isset($eventsByDate[$dateStr])) {
                                $eventsByDate[$dateStr] = [];
                            }
                            
                            // Thêm sự kiện tour
                            $eventsByDate[$dateStr][] = [
                                'type' => 'tour',
                                'title' => $tour['ten_tour'],
                                'tour_data' => [
                                    'ten_tour' => $tour['ten_tour'],
                                    'so_khach' => $tour['so_khach'] ?? 0,
                                    'ma_tour' => $tour['ma_tour'] ?? '',
                                    'trang_thai_lich' => $tour['trang_thai_lich'] ?? ''
                                ]
                            ];
                        }
                    }
                } catch (Exception $e) {
                    // Bỏ qua tour có ngày không hợp lệ
                    error_log("Lỗi xử lý tour: " . $e->getMessage());
                    continue;
                }
            }
        }
        
        return $eventsByDate;
    }
    
    /**
     * Tạo tiêu đề sự kiện
     */
    private function getEventTitle($loaiLich, $ghiChu)
    {
        $titles = [
            'đã phân công' => 'Có tour',
            'bận' => 'Bận',
            'nghỉ' => 'Nghỉ',
            'có thể làm' => 'Có thể làm'
        ];
        
        $title = $titles[$loaiLich] ?? $loaiLich;
        if ($ghiChu && strlen($ghiChu) > 0) {
            $title .= ': ' . $ghiChu;
        }
        
        return $title;
    }
}
?>