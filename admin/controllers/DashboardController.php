<?php
class DashboardController
{
    public $dashboardModel;
    
    public function __construct()
    {
        $this->dashboardModel = new AdminDashboard();
    }

    public function home()
    {
        $thongKe = $this->dashboardModel->getThongKeTongQuan();
        $tourSapKhoiHanh = $this->dashboardModel->getTourSapKhoiHanh();
        $suCoCanXuLy = $this->dashboardModel->getSuCoCanXuLy();
        
        // Thêm dữ liệu doanh thu
        $doanhThuThang = $this->dashboardModel->getDoanhThuThang();
        $bookingMoi = $this->dashboardModel->getBookingMoiThang();
        $doanhThuTheoThang = $this->dashboardModel->getDoanhThuTheoThang(date('Y'));
        
        require_once './views/home.php';
    }
}
?>