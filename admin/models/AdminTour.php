<?php
class AdminTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour với filter
    public function getAllTours($search = '', $trang_thai = '', $diem_den_id = '')
    {
        try {
            $query = "SELECT t.*, dd.ten_diem_den, lt.ten_loai 
                      FROM tours t 
                      LEFT JOIN diem_den dd ON t.diem_den_id = dd.id 
                      LEFT JOIN loai_tour lt ON t.loai_tour_id = lt.id 
                      WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (t.ma_tour LIKE :search OR t.ten_tour LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($trang_thai)) {
                $query .= " AND t.trang_thai = :trang_thai";
                $params[':trang_thai'] = $trang_thai;
            }

            if (!empty($diem_den_id)) {
                $query .= " AND t.diem_den_id = :diem_den_id";
                $params[':diem_den_id'] = $diem_den_id;
            }

            $query .= " ORDER BY t.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy tour theo ID
    public function getTourById($id)
    {
        try {
            $query = "SELECT t.*, dd.ten_diem_den, lt.ten_loai, cs.ten_chinh_sach
                      FROM tours t 
                      LEFT JOIN diem_den dd ON t.diem_den_id = dd.id 
                      LEFT JOIN loai_tour lt ON t.loai_tour_id = lt.id 
                      LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id 
                      WHERE t.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Tạo tour mới
    public function createTour($data)
    {
        try {
            $this->conn->beginTransaction();

            // Insert tour
            $query = "INSERT INTO tours (ma_tour, ten_tour, mo_ta_tuyen, diem_den_id, loai_tour_id, chinh_sach_id, trang_thai) 
                      VALUES (:ma_tour, :ten_tour, :mo_ta_tuyen, :diem_den_id, :loai_tour_id, :chinh_sach_id, 'bản_nháp')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':mo_ta_tuyen' => $data['mo_ta_tuyen'],
                ':diem_den_id' => $data['diem_den_id'],
                ':loai_tour_id' => $data['loai_tour_id'],
                ':chinh_sach_id' => $data['chinh_sach_id']
            ]);

            $tour_id = $this->conn->lastInsertId();

            // Insert tags
            if (!empty($data['tag_ids'])) {
                $this->insertTourTags($tour_id, $data['tag_ids']);
            }

            $this->conn->commit();
            return $tour_id;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Cập nhật tour
    public function updateTour($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Update tour
            $query = "UPDATE tours 
                      SET ma_tour = :ma_tour, ten_tour = :ten_tour, mo_ta_tuyen = :mo_ta_tuyen, 
                          diem_den_id = :diem_den_id, loai_tour_id = :loai_tour_id, 
                          chinh_sach_id = :chinh_sach_id, trang_thai = :trang_thai,
                          updated_at = NOW()
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':mo_ta_tuyen' => $data['mo_ta_tuyen'],
                ':diem_den_id' => $data['diem_den_id'],
                ':loai_tour_id' => $data['loai_tour_id'],
                ':chinh_sach_id' => $data['chinh_sach_id'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);

            // Update tags
            $this->deleteTourTags($id);
            if (!empty($data['tag_ids'])) {
                $this->insertTourTags($id, $data['tag_ids']);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Xóa tour
    public function deleteTour($id)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa tags trước
            $this->deleteTourTags($id);

            // Xóa tour
            $query = "DELETE FROM tours WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Thêm tags cho tour
    private function insertTourTags($tour_id, $tag_ids)
    {
        $query = "INSERT INTO tour_tag (tour_id, tag_id) VALUES (:tour_id, :tag_id)";
        $stmt = $this->conn->prepare($query);

        foreach ($tag_ids as $tag_id) {
            $stmt->execute([':tour_id' => $tour_id, ':tag_id' => $tag_id]);
        }
    }

    // Xóa tags của tour
    private function deleteTourTags($tour_id)
    {
        $query = "DELETE FROM tour_tag WHERE tour_id = :tour_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':tour_id' => $tour_id]);
    }

    // Lấy tags của tour
    public function getTourTags($tour_id)
    {
        try {
            $query = "SELECT tag_id FROM tour_tag WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy tất cả điểm đến
    public function getAllDiemDen()
    {
        try {
            $query = "SELECT * FROM diem_den ORDER BY ten_diem_den";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy tất cả loại tour
    public function getAllLoaiTour()
    {
        try {
            $query = "SELECT * FROM loai_tour ORDER BY ten_loai";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy tất cả tag tour
    public function getAllTagTour()
    {
        try {
            $query = "SELECT * FROM tag_tour ORDER BY ten_tag";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy tất cả chính sách
    public function getAllChinhSach()
    {
        try {
            $query = "SELECT * FROM chinh_sach_tour ORDER BY ten_chinh_sach";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy lịch trình tour
    public function getLichTrinhByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour WHERE tour_id = :tour_id ORDER BY so_ngay, thu_tu_sap_xep";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy media tour
    public function getMediaByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM media_tour WHERE tour_id = :tour_id ORDER BY thu_tu_sap_xep";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaByTour: " . $e->getMessage());
            return [];
        }
    }

    public function uploadMediaFile($tour_id, $file, $loai_media = 'hình_ảnh', $chu_thich = '')
    {
        try {
            error_log("=== START UPLOAD DEBUG ===");
            error_log("File name: " . $file['name']);
            error_log("File size: " . $file['size']);
            error_log("File temp: " . $file['tmp_name']);
            error_log("File error: " . $file['error']);

            if (!$this->validateFile($file, $loai_media)) {
                error_log("VALIDATE FAILED");
                return false;
            }

            $upload_dir = $this->createUploadDirectory();
            error_log("Upload dir: " . $upload_dir);

            if (!$upload_dir) {
                error_log("CREATE DIRECTORY FAILED");
                return false;
            }

            // Kiểm tra quyền thư mục
            error_log("Dir exists: " . (is_dir($upload_dir) ? 'YES' : 'NO'));
            error_log("Dir writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));

            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . time() . '.' . strtolower($file_extension);
            $file_path = $upload_dir . '\\' . $file_name;

            error_log("Destination path: " . $file_path);

            // Kiểm tra file tạm trước khi move
            error_log("Temp file exists: " . (file_exists($file['tmp_name']) ? 'YES' : 'NO'));
            error_log("Temp file readable: " . (is_readable($file['tmp_name']) ? 'YES' : 'NO'));

            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                error_log("MOVE UPLOADED FILE FAILED");
                $last_error = error_get_last();
                error_log("Last error: " . print_r($last_error, true));
                return false;
            }

            // Kiểm tra sau khi move
            error_log("After move - File exists: " . (file_exists($file_path) ? 'YES' : 'NO'));
            error_log("After move - File size: " . filesize($file_path));

            $web_path = $file_name;

            $max_order = $this->getMaxMediaOrder($tour_id);

            $query = "INSERT INTO media_tour (tour_id, loai_media, url, chu_thich, thu_tu_sap_xep) 
                  VALUES (:tour_id, :loai_media, :url, :chu_thich, :thu_tu_sap_xep)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $tour_id,
                ':loai_media' => $loai_media,
                ':url' => $web_path,
                ':chu_thich' => $chu_thich,
                ':thu_tu_sap_xep' => $max_order + 1
            ]);

            $last_id = $this->conn->lastInsertId();
            error_log("Database insert successful, ID: " . $last_id);
            error_log("=== END UPLOAD DEBUG ===");

            return $last_id;
        } catch (PDOException $e) {
            error_log("UPLOAD EXCEPTION: " . $e->getMessage());
            return false;
        }
    }

    private function validateFile($file, $loai_media)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("Lỗi upload file: " . $file['error']);
            return false;
        }

        if (!file_exists($file['tmp_name'])) {
            error_log("File tạm không tồn tại: " . $file['tmp_name']);
            return false;
        }

        $max_size = 10 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            error_log("File quá lớn: " . $file['name'] . " - " . $file['size'] . " bytes");
            return false;
        }

        if ($file['size'] == 0) {
            error_log("File rỗng: " . $file['name']);
            return false;
        }

        $allowed_image_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_video_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];

        $file_type = mime_content_type($file['tmp_name']);

        if ($loai_media === 'hình_ảnh') {
            if (!in_array($file_type, $allowed_image_types)) {
                error_log("Định dạng ảnh không hợp lệ: " . $file_type . " - File: " . $file['name']);
                return false;
            }
        } elseif ($loai_media === 'video') {
            if (!in_array($file_type, $allowed_video_types)) {
                error_log("Định dạng video không hợp lệ: " . $file_type . " - File: " . $file['name']);
                return false;
            }
        }

        return true;
    }

    private function createUploadDirectory()
    {
        $upload_dir = 'D:\\laragon\\www\\pro1014\\uploads\\imgproduct';

        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                error_log("Không thể tạo thư mục upload: " . $upload_dir);
                return false;
            }
        }

        if (!is_writable($upload_dir)) {
            chmod($upload_dir, 0777);
        }

        return $upload_dir;
    }

    private function getMaxMediaOrder($tour_id)
    {
        try {
            $query = "SELECT MAX(thu_tu_sap_xep) as max_order FROM media_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['max_order'] ?: 0;
        } catch (PDOException $e) {
            error_log("Lỗi getMaxMediaOrder: " . $e->getMessage());
            return 0;
        }
    }

    public function deleteMedia($media_id)
    {
        try {
            // Lấy thông tin media trước khi xóa
            $media = $this->getMediaById($media_id);
            if (!$media) {
                error_log("Không tìm thấy media với ID: " . $media_id);
                return false;
            }

            $this->conn->beginTransaction();

            // ĐƯỜNG DẪN FILE VẬT LÝ
            $physical_path = 'D:\\laragon\\www\\pro1014\\uploads\\imgproduct\\' . $media['url'];

            error_log("Attempting to delete file: " . $physical_path);
            error_log("File exists: " . (file_exists($physical_path) ? 'Yes' : 'No'));

            // Xóa file vật lý nếu tồn tại
            if (file_exists($physical_path)) {
                if (unlink($physical_path)) {
                    error_log("Đã xóa file thành công: " . $physical_path);
                } else {
                    error_log("Lỗi: Không thể xóa file: " . $physical_path);
                    // Vẫn tiếp tục xóa record trong DB ngay cả khi không xóa được file
                }
            } else {
                error_log("File không tồn tại: " . $physical_path);
            }

            // Xóa record trong database
            $query = "DELETE FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([':id' => $media_id]);

            if ($result) {
                error_log("Đã xóa record trong database với ID: " . $media_id);
            } else {
                error_log("Lỗi xóa record trong database với ID: " . $media_id);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi deleteMedia: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi khác trong deleteMedia: " . $e->getMessage());
            return false;
        }
    }

    public function getMediaById($media_id)
    {
        try {
            $query = "SELECT * FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $media_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaById: " . $e->getMessage());
            return null;
        }
    }

    public function updateMediaInfo($media_id, $data)
    {
        try {
            $query = "UPDATE media_tour SET chu_thich = :chu_thich WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':chu_thich' => $data['chu_thich'],
                ':id' => $media_id
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateMediaInfo: " . $e->getMessage());
            return false;
        }
    }

    // Lấy phiên bản tour
    public function getPhienBanByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM phien_ban_tour WHERE tour_id = :tour_id ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
