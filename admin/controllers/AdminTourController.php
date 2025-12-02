<?php
class AdminTourController
{
    private $tourModel;

    public function __construct()
    {
        $this->tourModel = new AdminTour();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $trang_thai = $_GET['trang_thai'] ?? '';
        $danh_muc_id = $_GET['danh_muc_id'] ?? '';

        $tours = $this->tourModel->getAllTours($search, $trang_thai, $danh_muc_id);
        $danh_muc_list = $this->tourModel->getAllDanhMuc();

        require_once 'views/quanlytour/listTour.php';
    }

    // Hiển thị form thêm tour
    public function create()
    {
        $danh_muc_list = $this->tourModel->getAllDanhMuc();
        // XÓA: $chinh_sach_list = $this->tourModel->getAllChinhSach();
        $diem_den_list = $this->tourModel->getAllDiemDen();

        require_once 'views/quanlytour/addTour.php';
    }

    // Xử lý thêm tour
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_tour' => $_POST['ma_tour'],
                'ten_tour' => $_POST['ten_tour'],
                'danh_muc_id' => $_POST['danh_muc_id'],
                'mo_ta' => $_POST['mo_ta'],
                'gia_tour' => $_POST['gia_tour'],
                // XÓA: 'chinh_sach_id' => $_POST['chinh_sach_id'],
                'duong_dan_online' => $_POST['duong_dan_online'] ?? '',
            ];

            // Xử lý upload hình ảnh
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
                $uploadResult = $this->uploadImage($_FILES['hinh_anh']);
                if ($uploadResult['success']) {
                    $data['hinh_anh'] = $uploadResult['file_name'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: index.php?act=tour-create');
                    exit();
                }
            }

            $result = $this->tourModel->createTour($data);

            if ($result) {
                header('Location: index.php?act=tour&success=' . urlencode('Thêm tour thành công!'));
            } else {
                header('Location: index.php?act=tour-create&error=' . urlencode('Có lỗi xảy ra khi thêm tour!'));
            }
            exit();
        }
    }

    // Hiển thị form sửa tour
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $danh_muc_list = $this->tourModel->getAllDanhMuc();
        // XÓA: $chinh_sach_list = $this->tourModel->getAllChinhSach();
        $diem_den_list = $this->tourModel->getAllDiemDen();

        require_once 'views/quanlytour/editTour.php';
    }

    // Xử lý cập nhật tour
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $data = [
                'ma_tour' => $_POST['ma_tour'],
                'ten_tour' => $_POST['ten_tour'],
                'danh_muc_id' => $_POST['danh_muc_id'],
                'mo_ta' => $_POST['mo_ta'],
                'gia_tour' => $_POST['gia_tour'],
                // XÓA: 'chinh_sach_id' => $_POST['chinh_sach_id'],
                'trang_thai' => $_POST['trang_thai'],
                'duong_dan_online' => $_POST['duong_dan_online'] ?? '',
            ];

            // Xử lý upload hình ảnh mới
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
                $uploadResult = $this->uploadImage($_FILES['hinh_anh']);
                if ($uploadResult['success']) {
                    $data['hinh_anh'] = $uploadResult['file_name'];
                    // Xóa ảnh cũ nếu có
                    $old_tour = $this->tourModel->getTourById($id);
                    if ($old_tour && $old_tour['hinh_anh']) {
                        $this->deleteImage($old_tour['hinh_anh']);
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: index.php?act=tour-edit&id=' . $id);
                    exit();
                }
            }

            $result = $this->tourModel->updateTour($id, $data);

            if ($result) {
                header('Location: index.php?act=tour&success=' . urlencode('Cập nhật tour thành công!'));
            } else {
                header('Location: index.php?act=tour-edit&id=' . $id . '&error=' . urlencode('Có lỗi xảy ra khi cập nhật!'));
            }
            exit();
        }
    }

    // Xóa tour
    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        // Xóa ảnh trước khi xóa tour
        $tour = $this->tourModel->getTourById($id);
        if ($tour && $tour['hinh_anh']) {
            $this->deleteImage($tour['hinh_anh']);
        }

        $result = $this->tourModel->deleteTour($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa tour thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa tour!';
        }

        header('Location: index.php?act=tour');
        exit();
    }

    // ==================== QUẢN LÝ LỊCH TRÌNH TOUR ====================

    // Hiển thị lịch trình tour
    public function lichTrinh()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $lich_trinh = $this->tourModel->getLichTrinhByTour($tour_id);

        require_once 'views/quanlytour/lichTrinhTour.php';
    }

    // Hiển thị form thêm lịch trình
    public function createLichTrinh()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        require_once 'views/quanlytour/addLichTrinh.php';
    }

    // Xử lý thêm lịch trình
    public function storeLichTrinh()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'],
                'so_ngay' => $_POST['so_ngay'],
                'tieu_de' => $_POST['tieu_de'],
                'mo_ta_hoat_dong' => $_POST['mo_ta_hoat_dong'],
                'cho_o' => $_POST['cho_o'],
                'bua_an' => $_POST['bua_an'],
                'phuong_tien' => $_POST['phuong_tien'],
                'ghi_chu_hdv' => $_POST['ghi_chu_hdv'],
                'thu_tu_sap_xep' => $_POST['thu_tu_sap_xep'] ?? 0
            ];

            $result = $this->tourModel->createLichTrinh($data);

            if ($result) {
                $_SESSION['success'] = 'Thêm lịch trình thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm lịch trình!';
            }

            header('Location: index.php?act=tour-lich-trinh&tour_id=' . $data['tour_id']);
            exit();
        }
    }

    // Hiển thị form sửa lịch trình
    public function editLichTrinh()
    {
        $id = $_GET['id'] ?? 0;
        $lich_trinh = $this->tourModel->getLichTrinhById($id);

        if (!$lich_trinh) {
            $_SESSION['error'] = 'Lịch trình không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $tour = $this->tourModel->getTourById($lich_trinh['tour_id']);

        require_once 'views/quanlytour/editLichTrinh.php';
    }

    // Xử lý cập nhật lịch trình
    public function updateLichTrinh()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $data = [
                'so_ngay' => $_POST['so_ngay'],
                'tieu_de' => $_POST['tieu_de'],
                'mo_ta_hoat_dong' => $_POST['mo_ta_hoat_dong'],
                'cho_o' => $_POST['cho_o'],
                'bua_an' => $_POST['bua_an'],
                'phuong_tien' => $_POST['phuong_tien'],
                'ghi_chu_hdv' => $_POST['ghi_chu_hdv'],
                'thu_tu_sap_xep' => $_POST['thu_tu_sap_xep'] ?? 0
            ];

            $result = $this->tourModel->updateLichTrinh($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật lịch trình thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật lịch trình!';
            }

            header('Location: index.php?act=tour-lich-trinh&tour_id=' . $_POST['tour_id']);
            exit();
        }
    }

    // Xóa lịch trình
    public function deleteLichTrinh()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        $result = $this->tourModel->deleteLichTrinh($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa lịch trình thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa lịch trình!';
        }

        header('Location: index.php?act=tour-lich-trinh&tour_id=' . $tour_id);
        exit();
    }

    // ==================== QUẢN LÝ PHIÊN BẢN TOUR ====================

    // Hiển thị danh sách phiên bản tour
    public function phienBan()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $phien_ban_list = $this->tourModel->getPhienBanByTour($tour_id);

        require_once 'views/quanlytour/phienBanTour.php';
    }

    // Hiển thị form thêm phiên bản
    public function createPhienBan()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        require_once 'views/quanlytour/addPhienBan.php';
    }

    // Xử lý thêm phiên bản
    public function storePhienBan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ten_phien_ban' => $_POST['ten_phien_ban'],
                'loai_phien_ban' => $_POST['loai_phien_ban'],
                'gia_tour' => $_POST['gia_tour'],
                'thoi_gian_bat_dau' => $_POST['thoi_gian_bat_dau'],
                'thoi_gian_ket_thuc' => $_POST['thoi_gian_ket_thuc'],
                'mo_ta' => $_POST['mo_ta']
            ];

            $result = $this->tourModel->createPhienBan($data);

            if ($result) {
                $_SESSION['success'] = 'Thêm phiên bản thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm phiên bản!';
            }

            header('Location: index.php?act=tour-phien-ban&tour_id=' . $data['tour_id']);
            exit();
        }
    }

    // Hiển thị form sửa phiên bản
    public function editPhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $phien_ban = $this->tourModel->getPhienBanById($id);

        if (!$phien_ban) {
            $_SESSION['error'] = 'Phiên bản không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $tour = $this->tourModel->getTourById($phien_ban['tour_id']);

        require_once 'views/quanlytour/editPhienBan.php';
    }

    // Xử lý cập nhật phiên bản
    public function updatePhienBan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $data = [
                'ten_phien_ban' => $_POST['ten_phien_ban'],
                'loai_phien_ban' => $_POST['loai_phien_ban'],
                'gia_tour' => $_POST['gia_tour'],
                'thoi_gian_bat_dau' => $_POST['thoi_gian_bat_dau'],
                'thoi_gian_ket_thuc' => $_POST['thoi_gian_ket_thuc'],
                'mo_ta' => $_POST['mo_ta']
            ];

            $result = $this->tourModel->updatePhienBan($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật phiên bản thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật phiên bản!';
            }

            header('Location: index.php?act=tour-phien-ban&tour_id=' . $_POST['tour_id']);
            exit();
        }
    }

    // Xóa phiên bản
    public function deletePhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        $result = $this->tourModel->deletePhienBan($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa phiên bản thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa phiên bản!';
        }

        header('Location: index.php?act=tour-phien-ban&tour_id=' . $tour_id);
        exit();
    }

    // Áp dụng phiên bản
    public function apDungPhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        $result = $this->tourModel->apDungPhienBan($id, $tour_id);

        if ($result) {
            $_SESSION['success'] = 'Áp dụng phiên bản thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi áp dụng phiên bản!';
        }

        header('Location: index.php?act=tour-phien-ban&tour_id=' . $tour_id);
        exit();
    }

    // Xem chi tiết phiên bản
    public function xemPhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $phien_ban = $this->tourModel->getPhienBanById($id);

        if (!$phien_ban) {
            $_SESSION['error'] = 'Phiên bản không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $tour = $this->tourModel->getTourById($phien_ban['tour_id']);

        require_once 'views/quanlytour/chiTietPhienBan.php';
    }

    // ==================== QUẢN LÝ MEDIA TOUR ====================

    // Hiển thị media tour
    public function media()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $media_list = $this->tourModel->getMediaByTour($tour_id);

        require_once 'views/quanlytour/mediaTour.php';
    }

    // Upload media
    public function uploadMedia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_id = $_POST['tour_id'];
            $loai_media = $_POST['loai_media'];
            $chu_thich = $_POST['chu_thich'] ?? '';
            $thu_tu_sap_xep = $_POST['thu_tu_sap_xep'] ?? 0;

            if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === 0) {
                $uploadResult = $this->uploadMediaFile($_FILES['media_file'], $loai_media);

                if ($uploadResult['success']) {
                    $data = [
                        'tour_id' => $tour_id,
                        'loai_media' => $loai_media,
                        'url' => $uploadResult['file_name'],
                        'chu_thich' => $chu_thich,
                        'thu_tu_sap_xep' => $thu_tu_sap_xep
                    ];

                    $result = $this->tourModel->createMedia($data);

                    if ($result) {
                        $_SESSION['success'] = 'Upload media thành công!';
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi lưu thông tin media!';
                        // Xóa file đã upload nếu lỗi
                        $this->deleteMediaFile($uploadResult['file_name']);
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                }
            } else {
                $_SESSION['error'] = 'Vui lòng chọn file để upload!';
            }

            header('Location: index.php?act=tour-media&tour_id=' . $tour_id);
            exit();
        }
    }

    // Xóa media
    public function deleteMedia()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        // Lấy thông tin media trước khi xóa
        $media = $this->tourModel->getMediaById($id);

        $result = $this->tourModel->deleteMedia($id);

        if ($result) {
            // Xóa file vật lý
            if ($media && $media['url']) {
                $this->deleteMediaFile($media['url']);
            }
            $_SESSION['success'] = 'Xóa media thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa media!';
        }

        header('Location: index.php?act=tour-media&tour_id=' . $tour_id);
        exit();
    }

    // Cập nhật thông tin media
    public function updateMediaInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tour_id = $_POST['tour_id'];

            $data = [
                'chu_thich' => $_POST['chu_thich'],
                'thu_tu_sap_xep' => $_POST['thu_tu_sap_xep'] ?? 0
            ];

            $result = $this->tourModel->updateMedia($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật media thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật media!';
            }

            header('Location: index.php?act=tour-media&tour_id=' . $tour_id);
            exit();
        }
    }

    // ==================== UTILITY FUNCTIONS ====================

    // Upload hình ảnh
    private function uploadImage($file)
    {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)'];
        }

        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'File quá lớn, kích thước tối đa 5MB'];
        }

        $upload_dir = 'uploads/tours/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'tour_' . time() . '_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return ['success' => true, 'file_name' => $file_name];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi upload file'];
        }
    }

    // Upload media file
    private function uploadMediaFile($file, $loai_media)
    {
        if ($loai_media === 'hình ảnh') {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_size = 5 * 1024 * 1024; // 5MB
            $upload_dir = 'uploads/tours/media/images/';
        } else {
            $allowed_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
            $max_size = 50 * 1024 * 1024; // 50MB
            $upload_dir = 'uploads/tours/media/videos/';
        }

        if (!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Định dạng file không được hỗ trợ'];
        }

        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'File quá lớn'];
        }

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'media_' . time() . '_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return ['success' => true, 'file_name' => $file_name];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi upload file'];
        }
    }

    // Xóa hình ảnh
    private function deleteImage($file_name)
    {
        $file_path = 'uploads/tours/' . $file_name;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Xóa media file
    private function deleteMediaFile($file_name)
    {
        // Thử xóa từ cả hai thư mục
        $paths = [
            'uploads/tours/media/images/' . $file_name,
            'uploads/tours/media/videos/' . $file_name
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    // Thêm vào class AdminTourController, sau phương thức media()

    // ==================== QUẢN LÝ NHÀ CUNG CẤP TOUR ====================

    // Hiển thị nhà cung cấp của tour
    public function nhaCungCap()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $nha_cung_cap_list = $this->tourModel->getNhaCungCapByTour($tour_id);
        $all_nha_cung_cap = $this->tourModel->getAllNhaCungCap();
        $lich_khoi_hanh_list = $this->tourModel->getLichKhoiHanhByTour($tour_id);

        require_once 'views/quanlytour/nhaCungCap.php';
    }

    // Thêm nhà cung cấp vào tour
    public function addNhaCungCapToTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'],
                'nha_cung_cap_id' => $_POST['nha_cung_cap_id'],
                'loai_phan_cong' => $_POST['loai_phan_cong'],
                'ten_dich_vu' => $_POST['ten_dich_vu'] ?? '',
                'ghi_chu' => $_POST['ghi_chu'] ?? ''
            ];

            $result = $this->tourModel->addNhaCungCapToTour($data);

            if ($result) {
                $_SESSION['success'] = 'Thêm nhà cung cấp thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm nhà cung cấp!';
            }

            header('Location: index.php?act=tour-nha-cung-cap&tour_id=' . $_POST['tour_id']);
            exit();
        }
    }

    // Cập nhật trạng thái nhà cung cấp tour
    public function updateNhaCungCapTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $tour_id = $_POST['tour_id'];

            $data = [
                'loai_phan_cong' => $_POST['loai_phan_cong'],
                'ten_dich_vu' => $_POST['ten_dich_vu'] ?? '',
                'trang_thai_xac_nhan' => $_POST['trang_thai_xac_nhan'],
                'ghi_chu' => $_POST['ghi_chu'] ?? ''
            ];

            $result = $this->tourModel->updateNhaCungCapTour($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
            }

            header('Location: index.php?act=tour-nha-cung-cap&tour_id=' . $tour_id);
            exit();
        }
    }

    // Xóa nhà cung cấp khỏi tour
    public function removeNhaCungCapFromTour()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        $result = $this->tourModel->removeNhaCungCapFromTour($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa nhà cung cấp!';
        }

        header('Location: index.php?act=tour-nha-cung-cap&tour_id=' . $tour_id);
        exit();
    }
    // ==================== QUẢN LÝ NHÀ CUNG CẤP (DANH SÁCH) ====================

    // Danh sách nhà cung cấp
    public function listNhaCungCap()
    {
        $search = $_GET['search'] ?? '';
        $loai_dich_vu = $_GET['loai_dich_vu'] ?? '';

        // Tạo phương thức mới trong model
        $nha_cung_cap_list = $this->tourModel->getAllNhaCungCapAdmin($search, $loai_dich_vu);

        require_once 'views/quanlytour/listNhaCungCap.php';
    }

    // Thêm mới nhà cung cấp
    public function createNhaCungCap()
    {
        require_once 'views/quanlytour/addNhaCungCap.php';
    }

    public function storeNhaCungCap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_nha_cung_cap' => $_POST['ten_nha_cung_cap'],
                'loai_dich_vu' => $_POST['loai_dich_vu'],
                'dia_chi' => $_POST['dia_chi'] ?? '',
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                'email' => $_POST['email'] ?? '',
                'mo_ta' => $_POST['mo_ta'] ?? ''
            ];

            $result = $this->tourModel->createNhaCungCap($data);

            if ($result) {
                $_SESSION['success'] = 'Thêm nhà cung cấp thành công!';
                header('Location: index.php?act=tour-nha-cung-cap-list');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm nhà cung cấp!';
                header('Location: index.php?act=tour-nha-cung-cap-create');
            }
            exit();
        }
    }

    // Thêm vào class AdminTourController, sau phương thức storeNhaCungCap()

    // Sửa nhà cung cấp
    public function editNhaCungCap()
    {
        $id = $_GET['id'] ?? 0;
        $nha_cung_cap = $this->tourModel->getNhaCungCapById($id);

        if (!$nha_cung_cap) {
            $_SESSION['error'] = 'Nhà cung cấp không tồn tại!';
            header('Location: index.php?act=tour-nha-cung-cap-list');
            exit();
        }

        require_once 'views/quanlytour/editNhaCungCap.php';
    }

    public function updateNhaCungCap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $data = [
                'ten_nha_cung_cap' => $_POST['ten_nha_cung_cap'],
                'loai_dich_vu' => $_POST['loai_dich_vu'],
                'dia_chi' => $_POST['dia_chi'] ?? '',
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                'email' => $_POST['email'] ?? '',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'danh_gia' => $_POST['danh_gia'] ?? 0
            ];

            $result = $this->tourModel->updateNhaCungCap($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
            }

            header('Location: index.php?act=tour-nha-cung-cap-list');
            exit();
        }
    }

    // Xóa nhà cung cấp
    public function deleteNhaCungCap()
    {
        $id = $_GET['id'] ?? 0;

        $result = $this->tourModel->deleteNhaCungCap($id);

        if ($result === false) {
            $_SESSION['error'] = 'Không thể xóa nhà cung cấp này vì đang được sử dụng trong tour!';
        } elseif ($result) {
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa!';
        }

        header('Location: index.php?act=tour-nha-cung-cap-list');
        exit();
    }
}


