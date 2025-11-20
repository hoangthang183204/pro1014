<?php
class AdminDanhMucTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy thống kê danh mục
    public function getThongKeDanhMuc()
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM diem_den) as tong_diem_den,
                        (SELECT COUNT(*) FROM danh_muc_tour) as tong_danh_muc_tour,
                        (SELECT COUNT(*) FROM tag_tour) as tong_tag_tour,
                        (SELECT COUNT(*) FROM chinh_sach_tour) as tong_chinh_sach,
                        (SELECT COUNT(*) FROM doi_tac) as tong_doi_tac,
                        (SELECT COUNT(*) FROM huong_dan_vien) as tong_hdv,
                        (SELECT COUNT(*) FROM tour WHERE danh_muc_id IN (SELECT id FROM danh_muc_tour WHERE loai_tour = 'trong nước')) as tour_trong_nuoc,
                        (SELECT COUNT(*) FROM tour WHERE danh_muc_id IN (SELECT id FROM danh_muc_tour WHERE loai_tour = 'quốc tế')) as tour_quoc_te,
                        (SELECT COUNT(*) FROM tour WHERE danh_muc_id IN (SELECT id FROM danh_muc_tour WHERE loai_tour = 'theo yêu cầu')) as tour_theo_yeu_cau";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                'tong_diem_den' => 0,
                'tong_danh_muc_tour' => 0,
                'tong_tag_tour' => 0,
                'tong_chinh_sach' => 0,
                'tong_doi_tac' => 0,
                'tong_hdv' => 0,
                'tour_trong_nuoc' => 0,
                'tour_quoc_te' => 0,
                'tour_theo_yeu_cau' => 0
            ];
        }
    }

    // ==================== DANH MỤC TOUR ====================
    public function getAllDanhMucTour()
    {
        try {
            $query = "SELECT dmt.*, 
                             COUNT(t.id) as so_luong_tour,
                             CASE 
                                 WHEN dmt.loai_tour = 'trong nước' THEN 'Tour trong nước'
                                 WHEN dmt.loai_tour = 'quốc tế' THEN 'Tour quốc tế'
                                 WHEN dmt.loai_tour = 'theo yêu cầu' THEN 'Tour theo yêu cầu'
                                 ELSE dmt.loai_tour
                             END as ten_loai_hien_thi
                      FROM danh_muc_tour dmt
                      LEFT JOIN tour t ON dmt.id = t.danh_muc_id
                      GROUP BY dmt.id
                      ORDER BY 
                        CASE dmt.loai_tour
                            WHEN 'trong nước' THEN 1
                            WHEN 'quốc tế' THEN 2
                            WHEN 'theo yêu cầu' THEN 3
                            ELSE 4
                        END, dmt.ten_danh_muc";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getDanhMucTourById($id)
    {
        try {
            $query = "SELECT * FROM danh_muc_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getDanhMucTourByLoai($loai_tour)
    {
        try {
            $query = "SELECT * FROM danh_muc_tour WHERE loai_tour = :loai_tour AND trang_thai = 'hoạt động' ORDER BY ten_danh_muc";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':loai_tour' => $loai_tour]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function createDanhMucTour($data)
    {
        try {
            $query = "INSERT INTO danh_muc_tour (ten_danh_muc, loai_tour, mo_ta, trang_thai, nguoi_tao) 
                      VALUES (:ten_danh_muc, :loai_tour, :mo_ta, :trang_thai, :nguoi_tao)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_danh_muc' => $data['ten_danh_muc'],
                ':loai_tour' => $data['loai_tour'],
                ':mo_ta' => $data['mo_ta'],
                ':trang_thai' => $data['trang_thai'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateDanhMucTour($id, $data)
    {
        try {
            $query = "UPDATE danh_muc_tour 
                      SET ten_danh_muc = :ten_danh_muc, loai_tour = :loai_tour, mo_ta = :mo_ta, 
                          trang_thai = :trang_thai, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_danh_muc' => $data['ten_danh_muc'],
                ':loai_tour' => $data['loai_tour'],
                ':mo_ta' => $data['mo_ta'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDanhMucTour($id)
    {
        try {
            // Kiểm tra xem danh mục có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tour WHERE danh_muc_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM danh_muc_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== ĐIỂM ĐẾN ====================
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

    public function getDiemDenById($id)
    {
        try {
            $query = "SELECT * FROM diem_den WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createDiemDen($data)
    {
        try {
            $query = "INSERT INTO diem_den (ten_diem_den, mo_ta, nguoi_tao) 
                      VALUES (:ten_diem_den, :mo_ta, :nguoi_tao)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_diem_den' => $data['ten_diem_den'],
                ':mo_ta' => $data['mo_ta'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateDiemDen($id, $data)
    {
        try {
            $query = "UPDATE diem_den 
                      SET ten_diem_den = :ten_diem_den, mo_ta = :mo_ta, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_diem_den' => $data['ten_diem_den'],
                ':mo_ta' => $data['mo_ta'],
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDiemDen($id)
    {
        try {
            // Kiểm tra xem điểm đến có đang được sử dụng trong tour không
            $check_query = "SELECT COUNT(*) as count FROM lich_trinh_tour 
                           WHERE mo_ta_hoat_dong LIKE CONCAT('%', (SELECT ten_diem_den FROM diem_den WHERE id = :id), '%')
                           OR cho_o LIKE CONCAT('%', (SELECT ten_diem_den FROM diem_den WHERE id = :id), '%')";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM diem_den WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== TAG TOUR ====================
    public function getAllTagTour()
    {
        try {
            $query = "SELECT tt.*, COUNT(ttag.tour_id) as so_luong_tour 
                      FROM tag_tour tt
                      LEFT JOIN tour_tag ttag ON tt.id = ttag.tag_id
                      GROUP BY tt.id
                      ORDER BY tt.ten_tag";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTagTourById($id)
    {
        try {
            $query = "SELECT * FROM tag_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createTagTour($data)
    {
        try {
            $query = "INSERT INTO tag_tour (ten_tag, nguoi_tao) VALUES (:ten_tag, :nguoi_tao)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_tag' => $data['ten_tag'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateTagTour($id, $data)
    {
        try {
            $query = "UPDATE tag_tour SET ten_tag = :ten_tag WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_tag' => $data['ten_tag'],
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteTagTour($id)
    {
        try {
            // Kiểm tra xem tag có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tour_tag WHERE tag_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM tag_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== CHÍNH SÁCH TOUR ====================
    public function getAllChinhSach()
    {
        try {
            $query = "SELECT cs.*, COUNT(t.id) as so_luong_tour 
                      FROM chinh_sach_tour cs
                      LEFT JOIN tour t ON cs.id = t.chinh_sach_id
                      GROUP BY cs.id
                      ORDER BY cs.ten_chinh_sach";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getChinhSachById($id)
    {
        try {
            $query = "SELECT * FROM chinh_sach_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createChinhSach($data)
    {
        try {
            $query = "INSERT INTO chinh_sach_tour (ten_chinh_sach, quy_dinh_huy_doi, luu_y_suc_khoe, luu_y_hanh_ly, luu_y_khac, nguoi_tao) 
                      VALUES (:ten_chinh_sach, :quy_dinh_huy_doi, :luu_y_suc_khoe, :luu_y_hanh_ly, :luu_y_khac, :nguoi_tao)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_chinh_sach' => $data['ten_chinh_sach'],
                ':quy_dinh_huy_doi' => $data['quy_dinh_huy_doi'],
                ':luu_y_suc_khoe' => $data['luu_y_suc_khoe'],
                ':luu_y_hanh_ly' => $data['luu_y_hanh_ly'],
                ':luu_y_khac' => $data['luu_y_khac'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateChinhSach($id, $data)
    {
        try {
            $query = "UPDATE chinh_sach_tour 
                      SET ten_chinh_sach = :ten_chinh_sach, quy_dinh_huy_doi = :quy_dinh_huy_doi, 
                          luu_y_suc_khoe = :luu_y_suc_khoe, luu_y_hanh_ly = :luu_y_hanh_ly, 
                          luu_y_khac = :luu_y_khac, updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_chinh_sach' => $data['ten_chinh_sach'],
                ':quy_dinh_huy_doi' => $data['quy_dinh_huy_doi'],
                ':luu_y_suc_khoe' => $data['luu_y_suc_khoe'],
                ':luu_y_hanh_ly' => $data['luu_y_hanh_ly'],
                ':luu_y_khac' => $data['luu_y_khac'],
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteChinhSach($id)
    {
        try {
            // Kiểm tra xem chính sách có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tour WHERE chinh_sach_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM chinh_sach_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== ĐỐI TÁC ====================
    public function getAllDoiTac()
    {
        try {
            $query = "SELECT dt.*, COUNT(pc.id) as so_lan_su_dung 
                      FROM doi_tac dt
                      LEFT JOIN phan_cong pc ON dt.id = pc.doi_tac_id
                      GROUP BY dt.id
                      ORDER BY dt.ten_doi_tac";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getDoiTacById($id)
    {
        try {
            $query = "SELECT * FROM doi_tac WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createDoiTac($data)
    {
        try {
            $query = "INSERT INTO doi_tac (ten_doi_tac, loai_dich_vu, thong_tin_lien_he, nguoi_tao) 
                      VALUES (:ten_doi_tac, :loai_dich_vu, :thong_tin_lien_he, :nguoi_tao)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_doi_tac' => $data['ten_doi_tac'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he'],
                ':nguoi_tao' => $_SESSION['user_id'] ?? 1
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateDoiTac($id, $data)
    {
        try {
            $query = "UPDATE doi_tac 
                      SET ten_doi_tac = :ten_doi_tac, loai_dich_vu = :loai_dich_vu, 
                          thong_tin_lien_he = :thong_tin_lien_he, updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_doi_tac' => $data['ten_doi_tac'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he'],
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDoiTac($id)
    {
        try {
            // Kiểm tra xem đối tác có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM phan_cong WHERE doi_tac_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM doi_tac WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== HƯỚNG DẪN VIÊN ====================
    public function getAllHDV()
    {
        try {
            $query = "SELECT * FROM huong_dan_vien ORDER BY ho_ten";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getAllHDV: " . $e->getMessage());
            return [];
        }
    }

    public function getHDVById($id)
    {
        try {
            $query = "SELECT * FROM huong_dan_vien WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getHDVById: " . $e->getMessage());
            return null;
        }
    }

    public function updateHDV($id, $data)
    {
        try {
            $query = "UPDATE huong_dan_vien 
                  SET ho_ten = ?, so_dien_thoai = ?, email = ?, dia_chi = ?,
                      so_giay_phep_hanh_nghe = ?, loai_huong_dan_vien = ?, 
                      chuyen_mon = ?, trang_thai = ?, ngon_ngu = ?, ghi_chu = ?,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id = ?";

            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                $data['ho_ten'],
                $data['so_dien_thoai'],
                $data['email'],
                $data['dia_chi'],
                $data['so_giay_phep_hanh_nghe'],
                $data['loai_huong_dan_vien'],
                $data['chuyen_mon'],
                $data['trang_thai'],
                $data['ngon_ngu'],
                $data['ghi_chu'],
                $id
            ]);

            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Lỗi updateHDV: " . $e->getMessage());
            return false;
        }
    }

    public function deleteHDV($id)
    {
        try {
            // Kiểm tra xem HDV có đang được phân công không
            $check_query = "SELECT COUNT(*) as count FROM phan_cong WHERE huong_dan_vien_id = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return false;
            }

            $query = "DELETE FROM huong_dan_vien WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Lỗi deleteHDV: " . $e->getMessage());
            return false;
        }
    }
}
