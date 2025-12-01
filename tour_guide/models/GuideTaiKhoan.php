<?php

class GuideTaiKhoan
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function registerprocess()
    {
        // Kiểm tra dữ liệu đầu vào
        if (
            empty($_POST['ten_dang_nhap']) || empty($_POST['ho_ten']) || empty($_POST['email']) ||
            empty($_POST['mat_khau']) || empty($_POST['confirm'])
        ) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
            header("Location: " . BASE_URL_GUIDE . "?act=register");
            exit();
        }

        $tenDangNhap = $_POST['ten_dang_nhap'];
        $hoTen = $_POST['ho_ten'];
        $email = $_POST['email'];
        $soDienThoai = $_POST['so_dien_thoai'] ?? '';
        $matKhau = $_POST['mat_khau'];
        $confirm = $_POST['confirm'];
        $vaiTro = 'huong_dan_vien'; // CHỈ cho phép đăng ký làm hướng dẫn viên
        $trangThai = 'hoạt động';

        // Kiểm tra mật khẩu khớp
        if ($matKhau !== $confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: " . BASE_URL_GUIDE . "?act=register");
            exit();
        }

        // Mã hóa mật khẩu
        $matKhauHash = password_hash($matKhau, PASSWORD_DEFAULT);

        // Kiểm tra email trùng
        $sqlCheck = "SELECT * FROM nguoi_dung WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$email]);
        $rs = $stmt->fetch();

        if ($rs) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header("Location: " . BASE_URL_GUIDE . "?act=register");
            exit();
        }

        // Kiểm tra tên đăng nhập trùng
        $sqlCheckUsername = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ?";
        $stmtUsername = $this->conn->prepare($sqlCheckUsername);
        $stmtUsername->execute([$tenDangNhap]);
        $rsUsername = $stmtUsername->fetch();

        if ($rsUsername) {
            $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
            header("Location: " . BASE_URL_GUIDE . "?act=register");
            exit();
        }

        try {
            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // 1. Thêm vào bảng nguoi_dung
            $sql = "INSERT INTO nguoi_dung (
                ten_dang_nhap, 
                mat_khau, 
                ho_ten, 
                email, 
                so_dien_thoai, 
                vai_tro, 
                trang_thai,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $tenDangNhap,
                $matKhauHash,
                $hoTen,
                $email,
                $soDienThoai,
                $vaiTro,
                $trangThai
            ]);

            // 2. Lấy ID người dùng vừa tạo
            $userId = $this->conn->lastInsertId();

            // 3. Tạo profile trống trong bảng huong_dan_vien
            $sqlGuide = "INSERT INTO huong_dan_vien (
                nguoi_dung_id,
                ho_ten,
                email,
                so_dien_thoai,
                loai_huong_dan_vien,
                trang_thai,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, 'nội địa', 'đang làm việc', NOW(), NOW())";

            $stmtGuide = $this->conn->prepare($sqlGuide);
            $stmtGuide->execute([
                $userId,
                $hoTen,
                $email,
                $soDienThoai
            ]);

            // Commit transaction
            $this->conn->commit();

            $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();

        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
            $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
            header("Location: " . BASE_URL_GUIDE . "?act=register");
            exit();
        }
    }

    public function loginprocess()
    {
        if (empty($_POST['email']) || empty($_POST['mat_khau'])) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $email = $_POST['email'];
        $matKhau = $_POST['mat_khau'];

        // Kiểm tra email
        $sqlCheck = "SELECT * FROM nguoi_dung WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error'] = "Email không tồn tại!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Kiểm tra mật khẩu
        if (!password_verify($matKhau, $user['mat_khau'])) {
            $_SESSION['error'] = "Mật khẩu không chính xác!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Kiểm tra trạng thái tài khoản
        if ($user['trang_thai'] !== 'hoạt động') {
            $_SESSION['error'] = "Tài khoản của bạn đã bị khóa!";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        $vai_tro = $user['vai_tro'];

        if ($vai_tro !== 'huong_dan_vien') {
            $_SESSION['error'] = "Tài khoản này không có quyền truy cập! Chỉ Hướng Dẫn Viên mới được đăng nhập.";
            header("Location: " . BASE_URL_GUIDE . "?act=login");
            exit();
        }

        // Đảm bảo profile tồn tại
        $this->ensureGuideProfileExists($user['id']);

        // Lưu session
        $_SESSION['guide_id'] = $user['id'];
        $_SESSION['guide_name'] = $user['ho_ten'];
        $_SESSION['guide_email'] = $user['email'];
        $_SESSION['guide_vai_tro'] = $vai_tro;
        $_SESSION['guide_logged_in'] = true;
        $_SESSION['success'] = "Đăng nhập thành công!";

        // Cập nhật last_login
        $sqlUpdate = "UPDATE nguoi_dung SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sqlUpdate);
        $stmt->execute([$user['id']]);

        header("Location: " . BASE_URL_GUIDE);
        exit();
    }

    // Phương thức đảm bảo profile tồn tại
    private function ensureGuideProfileExists($userId)
    {
        // Kiểm tra xem đã có profile chưa
        $sqlCheck = "SELECT id FROM huong_dan_vien WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        if (!$profile) {
            // Lấy thông tin người dùng
            $sqlUser = "SELECT ho_ten, email, so_dien_thoai FROM nguoi_dung WHERE id = ?";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([$userId]);
            $userInfo = $stmtUser->fetch();

            // Tạo profile mới
            $sqlCreate = "INSERT INTO huong_dan_vien (
                nguoi_dung_id,
                ho_ten,
                email,
                so_dien_thoai,
                loai_huong_dan_vien,
                trang_thai,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, 'nội địa', 'đang làm việc', NOW(), NOW())";

            $stmtCreate = $this->conn->prepare($sqlCreate);
            $stmtCreate->execute([
                $userId,
                $userInfo['ho_ten'] ?? '',
                $userInfo['email'] ?? '',
                $userInfo['so_dien_thoai'] ?? ''
            ]);
        }
    }

    // Thêm phương thức logout
    public function logout()
    {
        // Xóa tất cả session
        session_destroy();

        // Chuyển hướng về trang login
        header("Location: " . BASE_URL_GUIDE . "?act=login");
        exit();
    }
}
?>