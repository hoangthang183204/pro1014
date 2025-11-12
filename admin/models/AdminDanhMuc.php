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
                        (SELECT COUNT(*) FROM loai_tour) as tong_loai_tour,
                        (SELECT COUNT(*) FROM tag_tour) as tong_tag_tour,
                        (SELECT COUNT(*) FROM chinh_sach_tour) as tong_chinh_sach,
                        (SELECT COUNT(*) FROM doi_tac) as tong_doi_tac,
                        (SELECT COUNT(*) FROM huong_dan_vien) as tong_hdv";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [
                'tong_diem_den' => 0,
                'tong_loai_tour' => 0,
                'tong_tag_tour' => 0,
                'tong_chinh_sach' => 0,
                'tong_doi_tac' => 0,
                'tong_hdv' => 0
            ];
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
            $query = "INSERT INTO diem_den (ten_diem_den, mo_ta) VALUES (:ten_diem_den, :mo_ta)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_diem_den' => $data['ten_diem_den'],
                ':mo_ta' => $data['mo_ta']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateDiemDen($id, $data)
    {
        try {
            $query = "UPDATE diem_den SET ten_diem_den = :ten_diem_den, mo_ta = :mo_ta WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_diem_den' => $data['ten_diem_den'],
                ':mo_ta' => $data['mo_ta'],
                ':id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDiemDen($id)
    {
        try {
            // Kiểm tra xem điểm đến có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tours WHERE diem_den_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false; // Không cho xóa nếu đang được sử dụng
            }
            
            $query = "DELETE FROM diem_den WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== LOẠI TOUR ====================
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

    public function getLoaiTourById($id)
    {
        try {
            $query = "SELECT * FROM loai_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createLoaiTour($data)
    {
        try {
            $query = "INSERT INTO loai_tour (ten_loai, mo_ta) VALUES (:ten_loai, :mo_ta)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_loai' => $data['ten_loai'],
                ':mo_ta' => $data['mo_ta']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateLoaiTour($id, $data)
    {
        try {
            $query = "UPDATE loai_tour SET ten_loai = :ten_loai, mo_ta = :mo_ta WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_loai' => $data['ten_loai'],
                ':mo_ta' => $data['mo_ta'],
                ':id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteLoaiTour($id)
    {
        try {
            // Kiểm tra xem loại tour có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tours WHERE loai_tour_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false;
            }
            
            $query = "DELETE FROM loai_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== TAG TOUR ====================
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
            $query = "INSERT INTO tag_tour (ten_tag) VALUES (:ten_tag)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':ten_tag' => $data['ten_tag']]);
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
            return true;
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
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== CHÍNH SÁCH TOUR ====================
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
            $query = "INSERT INTO chinh_sach_tour (ten_chinh_sach, quy_dinh_huy_doi, luu_y_suc_khoe, luu_y_hanh_ly, luu_y_khac) 
                      VALUES (:ten_chinh_sach, :quy_dinh_huy_doi, :luu_y_suc_khoe, :luu_y_hanh_ly, :luu_y_khac)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_chinh_sach' => $data['ten_chinh_sach'],
                ':quy_dinh_huy_doi' => $data['quy_dinh_huy_doi'],
                ':luu_y_suc_khoe' => $data['luu_y_suc_khoe'],
                ':luu_y_hanh_ly' => $data['luu_y_hanh_ly'],
                ':luu_y_khac' => $data['luu_y_khac']
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
                          luu_y_suc_khoe = :luu_y_suc_khoe, luu_y_hanh_ly = :luu_y_hanh_ly, luu_y_khac = :luu_y_khac
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
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteChinhSach($id)
    {
        try {
            // Kiểm tra xem chính sách có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM tours WHERE chinh_sach_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false;
            }
            
            $query = "DELETE FROM chinh_sach_tour WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== ĐỐI TÁC ====================
    public function getAllDoiTac()
    {
        try {
            $query = "SELECT * FROM doi_tac ORDER BY ten_doi_tac";
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
            $query = "INSERT INTO doi_tac (ten_doi_tac, loai_dich_vu, thong_tin_lien_he) 
                      VALUES (:ten_doi_tac, :loai_dich_vu, :thong_tin_lien_he)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_doi_tac' => $data['ten_doi_tac'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he']
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
                      SET ten_doi_tac = :ten_doi_tac, loai_dich_vu = :loai_dich_vu, thong_tin_lien_he = :thong_tin_lien_he
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_doi_tac' => $data['ten_doi_tac'],
                ':loai_dich_vu' => $data['loai_dich_vu'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he'],
                ':id' => $id
            ]);
            return true;
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
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ==================== HƯỚNG DẪN VIÊN ====================
    public function getAllHDV()
    {
        try {
            $query = "SELECT * FROM huong_dan_vien ORDER BY ten_hdv";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getHDVById($id)
    {
        try {
            $query = "SELECT * FROM huong_dan_vien WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createHDV($data)
    {
        try {
            $query = "INSERT INTO huong_dan_vien (ten_hdv, ky_nang_ngon_ngu, chuyen_mon, thong_tin_lien_he, trang_thai) 
                      VALUES (:ten_hdv, :ky_nang_ngon_ngu, :chuyen_mon, :thong_tin_lien_he, :trang_thai)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_hdv' => $data['ten_hdv'],
                ':ky_nang_ngon_ngu' => $data['ky_nang_ngon_ngu'],
                ':chuyen_mon' => $data['chuyen_mon'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he'],
                ':trang_thai' => $data['trang_thai']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateHDV($id, $data)
    {
        try {
            $query = "UPDATE huong_dan_vien 
                      SET ten_hdv = :ten_hdv, ky_nang_ngon_ngu = :ky_nang_ngon_ngu, chuyen_mon = :chuyen_mon, 
                          thong_tin_lien_he = :thong_tin_lien_he, trang_thai = :trang_thai
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten_hdv' => $data['ten_hdv'],
                ':ky_nang_ngon_ngu' => $data['ky_nang_ngon_ngu'],
                ':chuyen_mon' => $data['chuyen_mon'],
                ':thong_tin_lien_he' => $data['thong_tin_lien_he'],
                ':trang_thai' => $data['trang_thai'],
                ':id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteHDV($id)
    {
        try {
            // Kiểm tra xem HDV có đang được sử dụng không
            $check_query = "SELECT COUNT(*) as count FROM phan_cong WHERE hdv_id = :id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([':id' => $id]);
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return false;
            }
            
            $query = "DELETE FROM huong_dan_vien WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>