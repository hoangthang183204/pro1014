<?php
class AdminChinhSachController
{
    private $chinhSachModel;

    public function __construct()
    {
        $this->chinhSachModel = new AdminChinhSach();
    }

    // Danh sách chính sách
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $chinh_sach_list = $this->chinhSachModel->getAll($search);
        require_once 'views/quanlychinhsach/list.php';
    }

    // Xem chi tiết chính sách (chỉ xem, không edit)
    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $chinh_sach = $this->chinhSachModel->getById($id);
        
        if (!$chinh_sach) {
            $_SESSION['error'] = 'Chính sách không tồn tại!';
            header('Location: index.php?act=chinh-sach');
            exit();
        }
        
        // Đếm số tour đang sử dụng
        $tour_count = $this->chinhSachModel->countToursUsing($id);
        
        require_once 'views/quanlychinhsach/chiTietChinhSach.php';
    }
}
?>