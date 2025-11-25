<?php
class DashboardHDVController
{
    public $dashboardModel;
    
    public function __construct()
    {
        $this->dashboardModel = new HDVDashboard();
    }

    public function home()
    {
        // $thongKe = $this->dashboardModel->getThongKeTongQuan();
        // $tourSapKhoiHanh = $this->dashboardModel->getTourSapKhoiHanh();
        // $suCoCanXuLy = $this->dashboardModel->getSuCoCanXuLy();
        
        require_once './views/trangchu.php';
    }
}