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
        
        require_once './views/home.php';
    }
}
?>