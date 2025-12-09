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
    // ==================== QUẢN LÝ PHIÊN BẢN TOUR ====================
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
        error_log("Tour ID: " . $tour_id);
        error_log("Phien ban list: " . print_r($phien_ban_list, true));
        $stats = [
            'mua' => 0,
            'khuyen_mai' => 0,
            'dac_biet' => 0,
            'hien_hanh' => null,
            'total' => count($phien_ban_list)
        ];

        $now = date('Y-m-d');
        foreach ($phien_ban_list as $pb) {
            error_log("Phien ban: " . print_r($pb, true));

            switch ($pb['loai_phien_ban']) {
                case 'mua':
                    $stats['mua']++;
                    break;
                case 'khuyen_mai':
                    $stats['khuyen_mai']++;
                    break;
                case 'dac_biet':
                    $stats['dac_biet']++;
                    break;
            }
            if ($pb['thoi_gian_bat_dau'] <= $now && $pb['thoi_gian_ket_thuc'] >= $now) {
                $stats['hien_hanh'] = $pb['id'];
            }
        }

        require_once 'views/quanlytour/phienBanTour.php';
    }
    public function xemPhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        error_log("Xem phien ban - ID: " . $id . ", Tour ID: " . $tour_id);

        // Lấy thông tin phiên bản
        $phien_ban = $this->tourModel->getPhienBanById($id);

        error_log("Phien ban data: " . print_r($phien_ban, true));

        if (!$phien_ban) {
            $_SESSION['error'] = 'Phiên bản không tồn tại! ID: ' . $id;
            header('Location: index.php?act=tour-phien-ban&tour_id=' . $tour_id);
            exit();
        }

        // Lấy thông tin tour
        $tour = $this->tourModel->getTourById($phien_ban['tour_id']);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại! Tour ID: ' . $phien_ban['tour_id'];
            header('Location: index.php?act=tour');
            exit();
        }

        // Đảm bảo tour có giá trị
        $tour['gia_tour'] = $tour['gia_tour'] ?? 0;

    

        // Lấy các lịch khởi hành trong thời gian phiên bản hiệu lực
        $lich_khoi_hanh = $this->tourModel->getLichKhoiHanhTrongPhienBan(
            $phien_ban['tour_id'],
            $phien_ban['thoi_gian_bat_dau'],
            $phien_ban['thoi_gian_ket_thuc']
        );

        // Lấy thống kê đặt tour
        $thong_ke = $this->tourModel->getThongKePhienBan($id);

        // Lấy chi phí duyệt toán nếu có
        $du_toan = $this->tourModel->getDuToanByTour($phien_ban['tour_id']);

        // Debug: In ra tất cả dữ liệu
        error_log("Tour data: " . print_r($tour, true));
        error_log("Lich khoi hanh count: " . count($lich_khoi_hanh));

        require_once 'views/quanlytour/detailPhienBanTour.php';
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

        require_once 'views/quanlytour/addPhienBanTour.php';
    }

    // Xử lý thêm phiên bản - Sửa lại với debug
    public function storePhienBan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug: In ra dữ liệu POST
            error_log("POST data: " . print_r($_POST, true));

            try {
                $data = [
                    'tour_id' => $_POST['tour_id'] ?? 0,
                    'ten_phien_ban' => $_POST['ten_phien_ban'] ?? '',
                    'loai_phien_ban' => $_POST['loai_phien_ban'] ?? '',
                    'gia_tour' => $_POST['gia_tour'] ?? 0,
                    'gia_goc' => $_POST['gia_goc'] ?? $_POST['gia_tour'] ?? 0,
                    'khuyen_mai' => $_POST['khuyen_mai'] ?? 0,
                    'thoi_gian_bat_dau' => $_POST['thoi_gian_bat_dau'] ?? '',
                    'thoi_gian_ket_thuc' => $_POST['thoi_gian_ket_thuc'] ?? '',
                    'mo_ta' => $_POST['mo_ta'] ?? '',
                    'dich_vu_dac_biet' => $_POST['dich_vu_dac_biet'] ?? '',
                    'dieu_kien_ap_dung' => $_POST['dieu_kien_ap_dung'] ?? ''
                ];

                // Kiểm tra dữ liệu bắt buộc
                if (
                    empty($data['ten_phien_ban']) || empty($data['loai_phien_ban']) ||
                    empty($data['gia_tour']) || empty($data['thoi_gian_bat_dau']) ||
                    empty($data['thoi_gian_ket_thuc'])
                ) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                }

                // Chuyển đổi giá trị số
                $data['gia_tour'] = floatval(str_replace(',', '', $data['gia_tour']));
                $data['gia_goc'] = floatval(str_replace(',', '', $data['gia_goc']));
                $data['khuyen_mai'] = floatval($data['khuyen_mai']);

                // Tính % giảm giá nếu là khuyến mãi và có giá gốc
                if ($data['loai_phien_ban'] == 'khuyen_mai' && $data['gia_goc'] > 0 && $data['gia_tour'] > 0) {
                    $giam_gia = (($data['gia_goc'] - $data['gia_tour']) / $data['gia_goc']) * 100;
                    $data['khuyen_mai'] = round($giam_gia, 2);
                }

                error_log("Data to insert: " . print_r($data, true));

                $result = $this->tourModel->createPhienBan($data);

                if ($result) {
                    $_SESSION['success'] = 'Thêm phiên bản thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi thêm phiên bản!';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                error_log("Lỗi storePhienBan: " . $e->getMessage());

                // Quay lại form với dữ liệu cũ
                $_SESSION['old_data'] = $_POST;
                header('Location: index.php?act=phien-ban-create&tour_id=' . ($_POST['tour_id'] ?? 0));
                exit();
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
                'gia_goc' => $_POST['gia_goc'] ?? $_POST['gia_tour'],
                'khuyen_mai' => $_POST['khuyen_mai'] ?? 0,
                'thoi_gian_bat_dau' => $_POST['thoi_gian_bat_dau'],
                'thoi_gian_ket_thuc' => $_POST['thoi_gian_ket_thuc'],
                'mo_ta' => $_POST['mo_ta'],
                'dich_vu_dac_biet' => $_POST['dich_vu_dac_biet'] ?? '',
                'dieu_kien_ap_dung' => $_POST['dieu_kien_ap_dung'] ?? ''
            ];

            // Tính lại % giảm giá nếu là khuyến mãi
            if ($data['loai_phien_ban'] == 'khuyen_mai' && $data['gia_goc'] > 0) {
                $giam_gia = (($data['gia_goc'] - $data['gia_tour']) / $data['gia_goc']) * 100;
                $data['khuyen_mai'] = round($giam_gia, 2);
            }

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

    // Kích hoạt phiên bản
    public function activatePhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        try {
            $phien_ban = $this->tourModel->getPhienBanById($id);

            if (!$phien_ban) {
                throw new Exception("Phiên bản không tồn tại!");
            }

            // Cập nhật giá tour từ phiên bản
            $result = $this->tourModel->updateGiaTour($tour_id, $phien_ban['gia_tour']);

            if ($result) {
                $_SESSION['success'] = 'Kích hoạt phiên bản thành công! Giá tour đã được cập nhật.';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật giá tour!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?act=tour-phien-ban&tour_id=' . $tour_id);
        exit();
    }

    // Xóa phiên bản
    public function deletePhienBan()
    {
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        try {
            $result = $this->tourModel->deletePhienBan($id);

            if ($result) {
                $_SESSION['success'] = 'Xóa phiên bản thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa phiên bản!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?act=tour-phien-ban&tour_id=' . $tour_id);
        exit();
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
    // ==================== CLONE TOUR FUNCTIONS ====================

    // Hiển thị form clone tour
    public function clone()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $danh_muc_list = $this->tourModel->getAllDanhMuc();

        // Lấy lịch sử clone nếu có
        $clone_history = $this->tourModel->getCloneHistory($id);

        require_once 'views/quanlytour/cloneTour.php';
    }

    // Xử lý clone tour
    public function storeClone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $original_tour_id = $_POST['original_tour_id'];
                $user_id = $_SESSION['user_id'] ?? 1; // Lấy từ session

                $new_tour_data = [
                    'ten_tour' => $_POST['ten_tour'],
                    'danh_muc_id' => $_POST['danh_muc_id'] ?? null,
                    'mo_ta' => $_POST['mo_ta'] ?? '',
                    'gia_tour' => $_POST['gia_tour'] ?? null
                ];

                $result = $this->tourModel->cloneTour($original_tour_id, $new_tour_data, $user_id);

                if ($result['success']) {
                    $_SESSION['success'] = 'Clone tour thành công!';

                    // Lưu thông tin tour mới để hiển thị
                    $_SESSION['cloned_tour_info'] = [
                        'id' => $result['new_tour_id'],
                        'code' => $result['new_tour_code'],
                        'items_cloned' => $result['cloned_items']
                    ];

                    header('Location: index.php?act=tour-clone-success&id=' . $result['new_tour_id']);
                } else {
                    $_SESSION['error'] = 'Lỗi: ' . ($result['error'] ?? 'Không rõ nguyên nhân');
                    header('Location: index.php?act=tour-clone&id=' . $original_tour_id);
                }
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
                header('Location: index.php?act=tour-clone&id=' . ($_POST['original_tour_id'] ?? 0));
                exit();
            }
        }
    }

    // Hiển thị kết quả clone
    public function cloneSuccess()
    {
        $new_tour_id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($new_tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        // Lấy thông tin clone từ session hoặc database
        $clone_info = $_SESSION['cloned_tour_info'] ?? null;
        if ($clone_info) {
            unset($_SESSION['cloned_tour_info']); // Xóa sau khi dùng
        }

        // Lấy tour gốc
        $original_tour = $this->tourModel->getOriginalTour($new_tour_id);

        require_once 'views/quanlytour/cloneSuccses.php';
    }

    // Xem lịch sử clone của tour
    public function cloneHistory()
    {
        $tour_id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại!';
            header('Location: index.php?act=tour');
            exit();
        }

        $clone_history = $this->tourModel->getCloneHistory($tour_id);

        require_once 'views/quanlytour/cloneHistory.php';
    }

    // Clone nhanh (ajax)
    public function quickClone()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            try {
                $original_tour_id = $_POST['tour_id'] ?? 0;
                $user_id = $_SESSION['user_id'] ?? 1;

                $tour = $this->tourModel->getTourById($original_tour_id);
                if (!$tour) {
                    echo json_encode(['success' => false, 'error' => 'Tour không tồn tại']);
                    exit();
                }

                // Clone với thông tin mặc định
                $new_tour_data = [
                    'ten_tour' => $tour['ten_tour'] . ' (Copy)',
                    'danh_muc_id' => $tour['danh_muc_id'],
                    'mo_ta' => $tour['mo_ta'],
                    'gia_tour' => $tour['gia_tour']
                ];

                $result = $this->tourModel->cloneTour($original_tour_id, $new_tour_data, $user_id);

                echo json_encode($result);
                exit();
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit();
            }
        }
    }
    
}
