<?php
class AdminTour
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connectDB();
    }

    private function connectDB()
    {
        try {
            $host = 'localhost';
            $dbname = 'pro1014';
            $username = 'root';
            $password = '';

            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Lỗi kết nối database: " . $e->getMessage());
        }
    }

    // ==================== TOUR METHODS ====================

    // Lấy tất cả tour với filter
    public function getAllTours($search = '', $trang_thai = '', $danh_muc_id = '')
    {
        try {
            $query = "SELECT t.*, dm.ten_danh_muc, cs.ten_chinh_sach,
                             COUNT(DISTINCT lt.id) as so_lich_trinh,
                             COUNT(DISTINCT pb.id) as so_phien_ban,
                             COUNT(DISTINCT mt.id) as so_media
                      FROM tour t 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
                      LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id
                      LEFT JOIN lich_trinh_tour lt ON t.id = lt.tour_id
                      LEFT JOIN phien_ban_tour pb ON t.id = pb.tour_id
                      LEFT JOIN media_tour mt ON t.id = mt.tour_id
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

            if (!empty($danh_muc_id)) {
                $query .= " AND t.danh_muc_id = :danh_muc_id";
                $params[':danh_muc_id'] = $danh_muc_id;
            }

            $query .= " GROUP BY t.id ORDER BY t.created_at DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllTours: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tour theo ID
    public function getTourById($id)
    {
        try {
            $query = "SELECT t.*, dm.ten_danh_muc, cs.ten_chinh_sach
                      FROM tour t 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
                      LEFT JOIN chinh_sach_tour cs ON t.chinh_sach_id = cs.id 
                      WHERE t.id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getTourById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo tour mới
    public function createTour($data)
    {
        try {
            $this->conn->beginTransaction();

            // Insert tour
            $query = "INSERT INTO tour (ma_tour, ten_tour, danh_muc_id, mo_ta, gia_tour, chinh_sach_id, hinh_anh, duong_dan_online, trang_thai) 
                      VALUES (:ma_tour, :ten_tour, :danh_muc_id, :mo_ta, :gia_tour, :chinh_sach_id, :hinh_anh, :duong_dan_online, 'đang hoạt động')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':danh_muc_id' => $data['danh_muc_id'],
                ':mo_ta' => $data['mo_ta'],
                ':gia_tour' => $data['gia_tour'],
                ':chinh_sach_id' => $data['chinh_sach_id'],
                ':hinh_anh' => $data['hinh_anh'] ?? null,
                ':duong_dan_online' => $data['duong_dan_online']
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
            error_log("Lỗi createTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật tour
    public function updateTour($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Build update query
            $query = "UPDATE tour 
                      SET ma_tour = :ma_tour, ten_tour = :ten_tour, danh_muc_id = :danh_muc_id, 
                          mo_ta = :mo_ta, gia_tour = :gia_tour, chinh_sach_id = :chinh_sach_id, 
                          trang_thai = :trang_thai, duong_dan_online = :duong_dan_online, 
                          updated_at = NOW()";

            // Thêm hình ảnh nếu có
            if (isset($data['hinh_anh'])) {
                $query .= ", hinh_anh = :hinh_anh";
            }

            $query .= " WHERE id = :id";

            $params = [
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':danh_muc_id' => $data['danh_muc_id'],
                ':mo_ta' => $data['mo_ta'],
                ':gia_tour' => $data['gia_tour'],
                ':chinh_sach_id' => $data['chinh_sach_id'],
                ':trang_thai' => $data['trang_thai'],
                ':duong_dan_online' => $data['duong_dan_online'],
                ':id' => $id
            ];

            if (isset($data['hinh_anh'])) {
                $params[':hinh_anh'] = $data['hinh_anh'];
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            // Update tags
            $this->deleteTourTags($id);
            if (!empty($data['tag_ids'])) {
                $this->insertTourTags($id, $data['tag_ids']);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi updateTour: " . $e->getMessage());
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

            // Xóa media
            $this->deleteAllMediaByTour($id);

            // Xóa lịch trình
            $this->deleteAllLichTrinhByTour($id);

            // Xóa phiên bản
            $this->deleteAllPhienBanByTour($id);

            // Xóa tour
            $query = "DELETE FROM tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Lỗi deleteTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== TAG METHODS ====================

    // Thêm tags cho tour
    private function insertTourTags($tour_id, $tag_ids)
    {
        try {
            $query = "INSERT INTO tour_tag (tour_id, tag_id) VALUES (:tour_id, :tag_id)";
            $stmt = $this->conn->prepare($query);

            foreach ($tag_ids as $tag_id) {
                $stmt->execute([':tour_id' => $tour_id, ':tag_id' => $tag_id]);
            }
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi insertTourTags: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tags của tour
    private function deleteTourTags($tour_id)
    {
        try {
            $query = "DELETE FROM tour_tag WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteTourTags: " . $e->getMessage());
            return false;
        }
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
            error_log("Lỗi getTourTags: " . $e->getMessage());
            return [];
        }
    }

    // ==================== LỊCH TRÌNH METHODS ====================

    // Lấy lịch trình tour
    public function getLichTrinhByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour 
                      WHERE tour_id = :tour_id 
                      ORDER BY so_ngay, thu_tu_sap_xep";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichTrinhByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch trình theo ID
    public function getLichTrinhById($id)
    {
        try {
            $query = "SELECT * FROM lich_trinh_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichTrinhById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo lịch trình mới
    public function createLichTrinh($data)
    {
        try {
            $query = "INSERT INTO lich_trinh_tour (tour_id, so_ngay, tieu_de, mo_ta_hoat_dong, 
                      cho_o, bua_an, phuong_tien, ghi_chu_hdv, thu_tu_sap_xep) 
                      VALUES (:tour_id, :so_ngay, :tieu_de, :mo_ta_hoat_dong, 
                      :cho_o, :bua_an, :phuong_tien, :ghi_chu_hdv, :thu_tu_sap_xep)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':so_ngay' => $data['so_ngay'],
                ':tieu_de' => $data['tieu_de'],
                ':mo_ta_hoat_dong' => $data['mo_ta_hoat_dong'],
                ':cho_o' => $data['cho_o'],
                ':bua_an' => $data['bua_an'],
                ':phuong_tien' => $data['phuong_tien'],
                ':ghi_chu_hdv' => $data['ghi_chu_hdv'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật lịch trình
    public function updateLichTrinh($id, $data)
    {
        try {
            $query = "UPDATE lich_trinh_tour 
                      SET so_ngay = :so_ngay, tieu_de = :tieu_de, mo_ta_hoat_dong = :mo_ta_hoat_dong,
                          cho_o = :cho_o, bua_an = :bua_an, phuong_tien = :phuong_tien,
                          ghi_chu_hdv = :ghi_chu_hdv, thu_tu_sap_xep = :thu_tu_sap_xep,
                          updated_at = NOW()
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':so_ngay' => $data['so_ngay'],
                ':tieu_de' => $data['tieu_de'],
                ':mo_ta_hoat_dong' => $data['mo_ta_hoat_dong'],
                ':cho_o' => $data['cho_o'],
                ':bua_an' => $data['bua_an'],
                ':phuong_tien' => $data['phuong_tien'],
                ':ghi_chu_hdv' => $data['ghi_chu_hdv'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Xóa lịch trình
    public function deleteLichTrinh($id)
    {
        try {
            $query = "DELETE FROM lich_trinh_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteLichTrinh: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả lịch trình của tour
    private function deleteAllLichTrinhByTour($tour_id)
    {
        try {
            $query = "DELETE FROM lich_trinh_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllLichTrinhByTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== PHIÊN BẢN METHODS ====================

    // Lấy phiên bản theo tour
    public function getPhienBanByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM phien_ban_tour 
                      WHERE tour_id = :tour_id 
                      ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy phiên bản theo ID
    public function getPhienBanById($id)
    {
        try {
            $query = "SELECT pb.*, t.ten_tour, t.ma_tour 
                      FROM phien_ban_tour pb
                      JOIN tour t ON pb.tour_id = t.id
                      WHERE pb.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getPhienBanById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo phiên bản mới
    public function createPhienBan($data)
    {
        try {
            $query = "INSERT INTO phien_ban_tour (tour_id, ten_phien_ban, loai_phien_ban, 
                      gia_tour, thoi_gian_bat_dau, thoi_gian_ket_thuc, mo_ta) 
                      VALUES (:tour_id, :ten_phien_ban, :loai_phien_ban, 
                      :gia_tour, :thoi_gian_bat_dau, :thoi_gian_ket_thuc, :mo_ta)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':ten_phien_ban' => $data['ten_phien_ban'],
                ':loai_phien_ban' => $data['loai_phien_ban'],
                ':gia_tour' => $data['gia_tour'],
                ':thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'],
                ':thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'],
                ':mo_ta' => $data['mo_ta']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createPhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật phiên bản
    public function updatePhienBan($id, $data)
    {
        try {
            $query = "UPDATE phien_ban_tour 
                      SET ten_phien_ban = :ten_phien_ban, loai_phien_ban = :loai_phien_ban,
                          gia_tour = :gia_tour, thoi_gian_bat_dau = :thoi_gian_bat_dau,
                          thoi_gian_ket_thuc = :thoi_gian_ket_thuc, mo_ta = :mo_ta,
                          updated_at = NOW()
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_phien_ban' => $data['ten_phien_ban'],
                ':loai_phien_ban' => $data['loai_phien_ban'],
                ':gia_tour' => $data['gia_tour'],
                ':thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'],
                ':thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'],
                ':mo_ta' => $data['mo_ta'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updatePhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Xóa phiên bản
    public function deletePhienBan($id)
    {
        try {
            $query = "DELETE FROM phien_ban_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deletePhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Áp dụng phiên bản
    public function apDungPhienBan($phien_ban_id, $tour_id)
    {
        try {
            // Lấy thông tin phiên bản
            $phien_ban = $this->getPhienBanById($phien_ban_id);
            
            if (!$phien_ban) {
                return false;
            }

            // Cập nhật tour với thông tin từ phiên bản
            $query = "UPDATE tour 
                      SET gia_tour = :gia_tour, updated_at = NOW()
                      WHERE id = :tour_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':gia_tour' => $phien_ban['gia_tour'],
                ':tour_id' => $tour_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi apDungPhienBan: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả phiên bản của tour
    private function deleteAllPhienBanByTour($tour_id)
    {
        try {
            $query = "DELETE FROM phien_ban_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllPhienBanByTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== MEDIA METHODS ====================

    // Lấy media theo tour
    public function getMediaByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM media_tour 
                      WHERE tour_id = :tour_id 
                      ORDER BY thu_tu_sap_xep, created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy media theo ID
    public function getMediaById($id)
    {
        try {
            $query = "SELECT * FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getMediaById: " . $e->getMessage());
            return null;
        }
    }

    // Tạo media mới
    public function createMedia($data)
    {
        try {
            $query = "INSERT INTO media_tour (tour_id, loai_media, url, chu_thich, thu_tu_sap_xep) 
                      VALUES (:tour_id, :loai_media, :url, :chu_thich, :thu_tu_sap_xep)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tour_id' => $data['tour_id'],
                ':loai_media' => $data['loai_media'],
                ':url' => $data['url'],
                ':chu_thich' => $data['chu_thich'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createMedia: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật media
    public function updateMedia($id, $data)
    {
        try {
            $query = "UPDATE media_tour 
                      SET chu_thich = :chu_thich, thu_tu_sap_xep = :thu_tu_sap_xep
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':chu_thich' => $data['chu_thich'],
                ':thu_tu_sap_xep' => $data['thu_tu_sap_xep'],
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Lỗi updateMedia: " . $e->getMessage());
            return false;
        }
    }

    // Xóa media
    public function deleteMedia($id)
    {
        try {
            $query = "DELETE FROM media_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteMedia: " . $e->getMessage());
            return false;
        }
    }

    // Xóa tất cả media của tour
    private function deleteAllMediaByTour($tour_id)
    {
        try {
            $query = "DELETE FROM media_tour WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteAllMediaByTour: " . $e->getMessage());
            return false;
        }
    }

    // ==================== UTILITY METHODS ====================

    // Lấy tất cả danh mục
    public function getAllDanhMuc()
    {
        try {
            $query = "SELECT * FROM danh_muc_tour WHERE trang_thai = 'hoạt động' ORDER BY ten_danh_muc";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllDanhMuc: " . $e->getMessage());
            return [];
        }
    }

    // Lấy tất cả tag
    public function getAllTagTour()
    {
        try {
            $query = "SELECT * FROM tag_tour ORDER BY ten_tag";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllTagTour: " . $e->getMessage());
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
            error_log("Lỗi getAllChinhSach: " . $e->getMessage());
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
            error_log("Lỗi getAllDiemDen: " . $e->getMessage());
            return [];
        }
    }
}
?>