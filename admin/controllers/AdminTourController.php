<?php
class AdminTourController
{
    public $tourModel;

    public function __construct()
    {
        $this->tourModel = new AdminTour();
    }

    // Danh sách Tour (CRUD, tìm kiếm, filter)
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $trang_thai = $_GET['trang_thai'] ?? '';
        $diem_den_id = $_GET['diem_den_id'] ?? '';

        $tours = $this->tourModel->getAllTours($search, $trang_thai, $diem_den_id);
        $diem_den_list = $this->tourModel->getAllDiemDen();

        require_once './views/quanlytour/listTour.php';
    }

    // Hiển thị form thêm tour mới
    public function create()
    {
        $diem_den_list = $this->tourModel->getAllDiemDen();
        $loai_tour_list = $this->tourModel->getAllLoaiTour();
        $tag_list = $this->tourModel->getAllTagTour();
        $chinh_sach_list = $this->tourModel->getAllChinhSach();

        require_once './views/quanlytour/addTour.php';
    }

    // Xử lý thêm tour mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_tour' => $_POST['ma_tour'],
                'ten_tour' => $_POST['ten_tour'],
                'mo_ta_tuyen' => $_POST['mo_ta_tuyen'],
                'diem_den_id' => $_POST['diem_den_id'],
                'loai_tour_id' => $_POST['loai_tour_id'],
                'chinh_sach_id' => $_POST['chinh_sach_id'],
                'tag_ids' => $_POST['tag_ids'] ?? []
            ];

            $result = $this->tourModel->createTour($data);

            if ($result) {
                header('Location: ?act=tour&success=Thêm tour thành công');
            } else {
                header('Location: ?act=tour-create&error=Có lỗi xảy ra khi thêm tour');
            }
        }
    }

    // Hiển thị form sửa tour
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->getTourById($id);

        if (!$tour) {
            header('Location: ?act=tour&error=Tour không tồn tại');
            return;
        }

        $diem_den_list = $this->tourModel->getAllDiemDen();
        $loai_tour_list = $this->tourModel->getAllLoaiTour();
        $tag_list = $this->tourModel->getAllTagTour();
        $chinh_sach_list = $this->tourModel->getAllChinhSach();
        $tour_tags = $this->tourModel->getTourTags($id);

        require_once './views/quanlytour/editTour.php';
    }

    // Xử lý cập nhật tour
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'ma_tour' => $_POST['ma_tour'],
                'ten_tour' => $_POST['ten_tour'],
                'mo_ta_tuyen' => $_POST['mo_ta_tuyen'],
                'diem_den_id' => $_POST['diem_den_id'],
                'loai_tour_id' => $_POST['loai_tour_id'],
                'chinh_sach_id' => $_POST['chinh_sach_id'],
                'trang_thai' => $_POST['trang_thai'],
                'tag_ids' => $_POST['tag_ids'] ?? []
            ];

            $result = $this->tourModel->updateTour($id, $data);

            if ($result) {
                header('Location: ?act=tour&success=Cập nhật tour thành công');
            } else {
                header('Location: ?act=tour-edit&id=' . $id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }

    // Xóa tour
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $result = $this->tourModel->deleteTour($id);

        if ($result) {
            header('Location: ?act=tour&success=Xóa tour thành công');
        } else {
            header('Location: ?act=tour&error=Có lỗi xảy ra khi xóa tour');
        }
    }

    // Quản lý lịch trình tour
    public function lichTrinh()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            header('Location: ?act=tour&error=Tour không tồn tại');
            return;
        }

        $lich_trinh = $this->tourModel->getLichTrinhByTour($tour_id);

        require_once './views/quanlytour/lichTrinhTour.php';
    }

    // Quản lý media tour
    public function media()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            header('Location: ?act=tour&error=Tour không tồn tại');
            return;
        }

        $media_list = $this->tourModel->getMediaByTour($tour_id);

        require_once './views/quanlytour/mediaTour.php';
    }

    public function uploadMedia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_id = $_POST['tour_id'] ?? 0;
            $loai_media = $_POST['loai_media'] ?? 'hình_ảnh';
            $chu_thich = $_POST['chu_thich'] ?? '';

            $tour = $this->tourModel->getTourById($tour_id);
            if (!$tour) {
                header('Location: ?act=tour&error=Tour không tồn tại');
                return;
            }

            if (isset($_FILES['media_files']) && !empty($_FILES['media_files']['name'][0])) {
                $success_count = 0;
                $error_messages = [];

                foreach ($_FILES['media_files']['name'] as $key => $name) {
                    if ($_FILES['media_files']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_data = [
                            'name' => $name,
                            'tmp_name' => $_FILES['media_files']['tmp_name'][$key],
                            'size' => $_FILES['media_files']['size'][$key],
                            'type' => $_FILES['media_files']['type'][$key],
                            'error' => $_FILES['media_files']['error'][$key]
                        ];

                        $upload_result = $this->tourModel->uploadMediaFile($tour_id, $file_data, $loai_media, $chu_thich);

                        if ($upload_result !== false) {
                            $success_count++;
                        } else {
                            $error_messages[] = "Lỗi upload file: " . $name;
                        }
                    } else {
                        $upload_errors = [
                            0 => 'Không có lỗi',
                            1 => 'File vượt quá upload_max_filesize',
                            2 => 'File vượt quá MAX_FILE_SIZE',
                            3 => 'File chỉ được upload một phần',
                            4 => 'Không có file được upload',
                            6 => 'Thiếu thư mục tạm',
                            7 => 'Không thể ghi file',
                            8 => 'PHP extension dừng upload'
                        ];
                        $error_messages[] = "Lỗi file " . $name . ": " . ($upload_errors[$_FILES['media_files']['error'][$key]] ?? 'Lỗi không xác định');
                    }
                }

                if ($success_count > 0) {
                    $message = "Upload thành công " . $success_count . " file";
                    if (!empty($error_messages)) {
                        $message .= " (Có " . count($error_messages) . " file lỗi)";
                    }
                    header('Location: ?act=tour-media&tour_id=' . $tour_id . '&success=' . urlencode($message));
                } else {
                    $error_message = "Upload thất bại";
                    if (!empty($error_messages)) {
                        $error_message = implode(", ", $error_messages);
                    }
                    header('Location: ?act=tour-media&tour_id=' . $tour_id . '&error=' . urlencode($error_message));
                }
            } else {
                header('Location: ?act=tour-media&tour_id=' . $tour_id . '&error=Vui lòng chọn file để upload');
            }
        }
    }

    public function deleteMedia()
    {
        $media_id = $_GET['media_id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;

        if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
            // Hiển thị trang xác nhận
            $media = $this->tourModel->getMediaById($media_id);
            if (!$media) {
                header('Location: ?act=tour-media&tour_id=' . $tour_id . '&error=Media không tồn tại');
                return;
            }

            echo "
        <script>
            if(confirm('Bạn có chắc muốn xóa media này? File sẽ bị xóa vĩnh viễn khỏi server.')) {
                window.location.href = '?act=delete-media&media_id=$media_id&tour_id=$tour_id&confirm=yes';
            } else {
                window.location.href = '?act=tour-media&tour_id=$tour_id';
            }
        </script>
        ";
            return;
        }

        // Thực hiện xóa
        $result = $this->tourModel->deleteMedia($media_id);

        if ($result) {
            header('Location: ?act=tour-media&tour_id=' . $tour_id . '&success=Xóa media thành công');
        } else {
            header('Location: ?act=tour-media&tour_id=' . $tour_id . '&error=Có lỗi xảy ra khi xóa media');
        }
    }

    public function updateMediaInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $media_id = $_POST['media_id'] ?? 0;
            $tour_id = $_POST['tour_id'] ?? 0;
            $chu_thich = $_POST['chu_thich'] ?? '';

            $result = $this->tourModel->updateMediaInfo($media_id, ['chu_thich' => $chu_thich]);

            if ($result) {
                header('Location: ?act=tour-media&tour_id=' . $tour_id . '&success=Cập nhật chú thích thành công');
            } else {
                header('Location: ?act=tour-media&tour_id=' . $tour_id . '&error=Có lỗi xảy ra khi cập nhật');
            }
        }
    }
    // Quản lý phiên bản tour
    public function phienBan()
    {
        $tour_id = $_GET['tour_id'] ?? 0;
        $tour = $this->tourModel->getTourById($tour_id);

        if (!$tour) {
            header('Location: ?act=tour&error=Tour không tồn tại');
            return;
        }

        $phien_ban_list = $this->tourModel->getPhienBanByTour($tour_id);

        require_once './views/quanlytour/phienBanTour.php';
    }
}
