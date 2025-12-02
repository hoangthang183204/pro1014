<?php
class AdminTour
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour với filter
    public function getAllTours($search = '', $trang_thai = '', $danh_muc_id = '')
    {
        try {
            // XÓA JOIN VÀO chinh_sach_tour
            $query = "SELECT t.*, dm.ten_danh_muc,
                             COUNT(DISTINCT lt.id) as so_lich_trinh,
                             COUNT(DISTINCT pb.id) as so_phien_ban,
                             COUNT(DISTINCT mt.id) as so_media
                      FROM tour t 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
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
            // XÓA JOIN VÀO chinh_sach_tour
            $query = "SELECT t.*, dm.ten_danh_muc
                      FROM tour t 
                      LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id 
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

            // Insert tour (BỎ chinh_sach_id)
            $query = "INSERT INTO tour (ma_tour, ten_tour, danh_muc_id, mo_ta, gia_tour, hinh_anh, duong_dan_online, trang_thai) 
                      VALUES (:ma_tour, :ten_tour, :danh_muc_id, :mo_ta, :gia_tour, :hinh_anh, :duong_dan_online, 'đang hoạt động')";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ma_tour' => $data['ma_tour'],
                ':ten_tour' => $data['ten_tour'],
                ':danh_muc_id' => $data['danh_muc_id'],
                ':mo_ta' => $data['mo_ta'],
                ':gia_tour' => $data['gia_tour'],
                ':hinh_anh' => $data['hinh_anh'] ?? null,
                ':duong_dan_online' => $data['duong_dan_online']
            ]);

            $tour_id = $this->conn->lastInsertId();

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

            // Build update query (BỎ chinh_sach_id)
            $query = "UPDATE tour 
                      SET ma_tour = :ma_tour, ten_tour = :ten_tour, danh_muc_id = :danh_muc_id, 
                          mo_ta = :mo_ta, gia_tour = :gia_tour, 
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
                ':trang_thai' => $data['trang_thai'],
                ':duong_dan_online' => $data['duong_dan_online'],
                ':id' => $id
            ];

            if (isset($data['hinh_anh'])) {
                $params[':hinh_anh'] = $data['hinh_anh'];
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

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

    // ==================== NHÀ CUNG CẤP TOUR METHODS ====================

    // Lấy tất cả nhà cung cấp
    public function getAllNhaCungCap()
    {
        try {
            $query = "SELECT * FROM nha_cung_cap ORDER BY ten_nha_cung_cap";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllNhaCungCap: " . $e->getMessage());
            return [];
        }
    }

    // Lấy nhà cung cấp của tour (dựa vào bảng phan_cong và lich_khoi_hanh)
    public function getNhaCungCapByTour($tour_id)
    {
        try {
            $query = "SELECT DISTINCT ncc.*, pc.loai_phan_cong, pc.trang_thai_xac_nhan,
                         lkh.ngay_bat_dau, lkh.ngay_ket_thuc
                  FROM phan_cong pc
                  JOIN lich_khoi_hanh lkh ON pc.lich_khoi_hanh_id = lkh.id
                  JOIN nha_cung_cap ncc ON pc.doi_tac_id = ncc.id
                  WHERE lkh.tour_id = :tour_id 
                  AND pc.loai_phan_cong IN ('vận chuyển', 'khách sạn', 'nhà hàng', 'vé tham quan')
                  ORDER BY pc.loai_phan_cong, ncc.ten_nha_cung_cap";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getNhaCungCapByTour: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch khởi hành của tour để gắn nhà cung cấp
    public function getLichKhoiHanhByTour($tour_id)
    {
        try {
            $query = "SELECT * FROM lich_khoi_hanh 
                  WHERE tour_id = :tour_id 
                  AND trang_thai IN ('đã lên lịch', 'đang diễn ra')
                  ORDER BY ngay_bat_dau";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':tour_id' => $tour_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getLichKhoiHanhByTour: " . $e->getMessage());
            return [];
        }
    }

    // Thêm nhà cung cấp vào tour (thêm vào bảng phan_cong)
    public function addNhaCungCapToTour($data)
    {
        try {
            $query = "INSERT INTO phan_cong (lich_khoi_hanh_id, loai_phan_cong, 
                  doi_tac_id, ten_dich_vu, trang_thai_xac_nhan, ghi_chu) 
                  VALUES (:lich_khoi_hanh_id, :loai_phan_cong, 
                  :doi_tac_id, :ten_dich_vu, 'chờ xác nhận', :ghi_chu)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':lich_khoi_hanh_id' => $data['lich_khoi_hanh_id'],
                ':loai_phan_cong' => $data['loai_phan_cong'],
                ':doi_tac_id' => $data['nha_cung_cap_id'],
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':ghi_chu' => $data['ghi_chu'] ?? ''
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi addNhaCungCapToTour: " . $e->getMessage());
            return false;
        }
    }

    // Xóa nhà cung cấp khỏi tour
    public function removeNhaCungCapFromTour($phan_cong_id)
    {
        try {
            $query = "DELETE FROM phan_cong WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $phan_cong_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi removeNhaCungCapFromTour: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật thông tin phân công nhà cung cấp
    public function updateNhaCungCapTour($id, $data)
    {
        try {
            $query = "UPDATE phan_cong 
                  SET loai_phan_cong = :loai_phan_cong, 
                      ten_dich_vu = :ten_dich_vu,
                      trang_thai_xac_nhan = :trang_thai_xac_nhan,
                      ghi_chu = :ghi_chu,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':loai_phan_cong' => $data['loai_phan_cong'],
                ':ten_dich_vu' => $data['ten_dich_vu'] ?? '',
                ':trang_thai_xac_nhan' => $data['trang_thai_xac_nhan'],
                ':ghi_chu' => $data['ghi_chu'] ?? '',
                ':id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateNhaCungCapTour: " . $e->getMessage());
            return false;
        }
    }

    // Thêm vào class AdminTour

    public function getAllNhaCungCapAdmin($search = '', $loai_dich_vu = '')
    {
        try {
            $query = "SELECT * FROM nha_cung_cap WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $query .= " AND (ten_nha_cung_cap LIKE :search OR email LIKE :search OR so_dien_thoai LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if (!empty($loai_dich_vu)) {
                $query .= " AND loai_dich_vu = :loai_dich_vu";
                $params[':loai_dich_vu'] = $loai_dich_vu;
            }

            $query .= " ORDER BY ten_nha_cung_cap";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllNhaCungCapAdmin: " . $e->getMessage());
            return [];
        }
    }

    public function createNhaCungCap($data)
    {
        try {
            $query = "INSERT INTO nha_cung_cap (ten_nha_cung_cap, loai_dich_vu, dia_chi, so_dien_thoai, email, mo_ta, created_at, nguoi_tao) 
                  VALUES (:ten_nha_cung_cap, :loai_dich_vu, :dia_chi, :so_dien_thoai, :email, :mo_ta, NOW(), :nguoi_tao)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_nha_cung_cap' => $data['ten_nha_cung_cap'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':dia_chi' => $data['dia_chi'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':email' => $data['email'],
                ':mo_ta' => $data['mo_ta'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi createNhaCungCap: " . $e->getMessage());
            return false;
        }
    }

    // Lấy nhà cung cấp theo ID
    public function getNhaCungCapById($id)
    {
        try {
            $query = "SELECT * FROM nha_cung_cap WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getNhaCungCapById: " . $e->getMessage());
            return null;
        }
    }

    // Cập nhật nhà cung cấp
    public function updateNhaCungCap($id, $data)
    {
        try {
            $query = "UPDATE nha_cung_cap 
                  SET ten_nha_cung_cap = :ten_nha_cung_cap, 
                      loai_dich_vu = :loai_dich_vu,
                      dia_chi = :dia_chi,
                      so_dien_thoai = :so_dien_thoai,
                      email = :email,
                      mo_ta = :mo_ta,
                      danh_gia = :danh_gia,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_nha_cung_cap' => $data['ten_nha_cung_cap'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':dia_chi' => $data['dia_chi'],
                ':so_dien_thoai' => $data['so_dien_thoai'],
                ':email' => $data['email'],
                ':mo_ta' => $data['mo_ta'],
                ':danh_gia' => $data['danh_gia'] ?? 0,
                ':id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateNhaCungCap: " . $e->getMessage());
            return false;
        }
    }

    // Xóa nhà cung cấp
    public function deleteNhaCungCap($id)
    {
        try {
            // Kiểm tra xem nhà cung cấp có đang được sử dụng không
            $checkQuery = "SELECT COUNT(*) as count FROM phan_cong WHERE doi_tac_id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([':id' => $id]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false; // Không xóa vì đang được sử dụng
            }

            $query = "DELETE FROM nha_cung_cap WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi deleteNhaCungCap: " . $e->getMessage());
            return false;
        }
    }
}
