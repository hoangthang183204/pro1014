<?php
class AdminThanhToanController
{
    public $thanhToanModel;

    public function __construct()
    {
        $this->thanhToanModel = new AdminThanhToan();
    }

    // Modal thanh toán nhanh
    public function modalThanhToanNhanh()
    {
        $phieu_dat_id = $_GET['id'] ?? 0;
        
        if (!$phieu_dat_id) {
            echo '<div class="alert alert-danger">Thiếu ID phiếu đặt tour</div>';
            return;
        }
        
        $thong_tin = $this->thanhToanModel->getThongTinPhieuDat($phieu_dat_id);
        
        if (!$thong_tin) {
            echo '<div class="alert alert-danger">Không tìm thấy thông tin đặt tour với ID: ' . $phieu_dat_id . '</div>';
            return;
        }

        // Include file view
        include 'views/thanhtoan/modelThanhToanNhanh.php';
    }

    // Xử lý thanh toán nhanh
    public function processThanhToanNhanh()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phieu_dat_id = $_POST['phieu_dat_id'] ?? 0;
            $phuong_thuc = $_POST['phuong_thuc'] ?? 'tiền mặt';
            $ghi_chu = $_POST['ghi_chu'] ?? 'Thanh toán nhanh';
            $nguoi_thu = $_SESSION['user_id'] ?? 1;

            if (!$phieu_dat_id) {
                $_SESSION['error'] = 'Thiếu thông tin phiếu đặt tour';
                header('Location: index.php?act=dat-tour');
                exit();
            }

            $result = $this->thanhToanModel->thanhToanToanBo($phieu_dat_id, $phuong_thuc, $ghi_chu, $nguoi_thu);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: index.php?act=dat-tour-show&id=' . $phieu_dat_id);
            exit();
        }
    }
}
?>