<?php
class AdminHuongDanVienController
{
    private $model;

    public function __construct()
    {
        require_once './models/AdminHuongDanVien.php';
        $this->model = new AdminHuongDanVien();
    }

    public function index()
    {
        $huong_dan_vien_list = $this->model->getAllHDV();

        $data = [
            'huong_dan_vien_list' => $huong_dan_vien_list
        ];

        extract($data);
        include_once './views/quanlynhansu/listHuongDanVien.php';
    }

    public function detail($id)
    {
        $hdv = $this->model->getById($id);

        if (!$hdv) {
            $_SESSION['error'] = 'Không tìm thấy hướng dẫn viên!';
            header('Location: index.php?act=huong-dan-vien');
            exit();
        }

        $lich_lam_viec = $this->model->getLichLamViecHDV($id, 5);

        $tour_da_dan = $this->model->getTourDaDan($id, 5);

        $data = [
            'hdv' => $hdv,
            'lich_lam_viec' => $lich_lam_viec,
            'tour_da_dan' => $tour_da_dan,
            'loai_hdv_options' => [
                'nội địa' => 'Nội địa',
                'quốc tế' => 'Quốc tế',
                'chuyên tuyến' => 'Chuyên tuyến'
            ],
            'trang_thai_options' => [
                'đang làm việc' => 'Đang làm việc',
                'nghỉ việc' => 'Nghỉ việc',
                'tạm nghỉ' => 'Tạm nghỉ'
            ]
        ];

        // Load view
        extract($data);
        include_once './views/quanlynhansu/chiTietHDV.php';
    }
}
