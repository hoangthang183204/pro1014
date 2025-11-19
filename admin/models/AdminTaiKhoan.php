<?php

class AdminTaiKhoan
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function registerprocess()
    {
        session_start();

        // Kiểm tra dữ liệu đầu vào
        if (
            empty($_POST['ten_dang_nhap']) || empty($_POST['ho_ten']) || empty($_POST['email']) ||
            empty($_POST['password']) || empty($_POST['confirm'])
        ) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        $tenDangNhap = $_POST['ten_dang_nhap'];
        $hoTen = $_POST['ho_ten'];
        $email = $_POST['email'];
        $soDienThoai = $_POST['so_dien_thoai'] ?? '';
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];
        $vaiTro = 'admin'; // Mặc định vai trò là admin
        $trangThai = 'hoat_dong'; // Mặc định trạng thái là hoạt động

        // Kiểm tra mật khẩu khớp
        if ($password !== $confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Kiểm tra email trùng
        $sqlCheck = "SELECT * FROM nguoi_dung WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$email]);
        $rs = $stmt->fetch();

        if ($rs) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Hash mật khẩu
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Thêm vào cơ sở dữ liệu
        $sqlInsert = "INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, ho_ten, email, so_dien_thoai, vai_tro, trang_thai, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        try {
            $stmt = $this->conn->prepare($sqlInsert);
            $stmt->execute([$tenDangNhap, $hash, $hoTen, $email, $soDienThoai, $vaiTro, $trangThai]);
            $_SESSION['success'] = "Đăng ký thành công!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        }

        // Chuyển hướng
        header("Location: " . BASE_URL_ADMIN . "?act=login");
        exit();
    }

    public function loginprocess()
    {
        session_start();

        // Kiểm tra dữ liệu đầu vào
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header("Location: " . BASE_URL_ADMIN . "?act=login");
            exit();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Kiểm tra email trong cơ sở dữ liệu
        $sqlCheck = "SELECT * FROM nguoi_dung WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error'] = "Email không tồn tại!";
            header("Location: " . BASE_URL_ADMIN . "?act=login");
            exit();
        }

        // Kiểm tra mật khẩu
        if (!password_verify($password, $user['mat_khau'])) {
            $_SESSION['error'] = "Mật khẩu không chính xác!";
            header("Location: " . BASE_URL_ADMIN . "?act=login");
            exit();
        }

        // Lưu thông tin đăng nhập vào session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['ho_ten'];
        $_SESSION['success'] = "Đăng nhập thành công!";

        // Cập nhật thời gian đăng nhập cuối cùng
        $sqlUpdate = "UPDATE nguoi_dung SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sqlUpdate);
        $stmt->execute([$user['id']]);

        // Chuyển hướng đến trang chủ admin
        header("Location: " . BASE_URL_ADMIN);
        exit();
    }
}