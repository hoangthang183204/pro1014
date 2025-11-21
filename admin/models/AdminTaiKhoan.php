<?php
class AdminTaiKhoan
{
    private $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // 🔥 KHỞI TẠO SESSION Ở ĐÂY
        }

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
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        $tenDangNhap = $_POST['ten_dang_nhap'];
        $hoTen = $_POST['ho_ten'];
        $email = $_POST['email'];
        $soDienThoai = $_POST['so_dien_thoai'] ?? '';
        $matKhau = $_POST['mat_khau'];
        $confirm = $_POST['confirm'];
        $vaiTro = $_POST['vai_tro'] ?? 'nhan_vien';
        $trangThai = $_POST['trang_thai'] ?? 'hoạt động';

        // Kiểm tra mật khẩu khớp
        if ($matKhau !== $confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
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
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Thêm vào cơ sở dữ liệu
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

        try {
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

            $_SESSION['success'] = "Đăng ký thành công!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        }

        header("Location: " . BASE_URL_ADMIN . "?act=login");
        exit();
    }

    public function loginprocess()
    {
        if (empty($_POST['email']) || empty($_POST['mat_khau'])) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header("Location: " . BASE_URL_ADMIN . "?act=login");
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
            header("Location: " . BASE_URL_ADMIN . "?act=login");
            exit();
        }

        // Kiểm tra mật khẩu
        if (!password_verify($matKhau, $user['mat_khau'])) {
            $_SESSION['error'] = "Mật khẩu không chính xác!";
            header("Location: " . BASE_URL_ADMIN . "?act=login");
            exit();
        }

        // Lưu thông tin đăng nhập vào session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['ho_ten'];
        $_SESSION['admin_vai_tro'] = $user['vai_tro'];
        $_SESSION['success'] = "Đăng nhập thành công!";

        // Cập nhật last_login
        $sqlUpdate = "UPDATE nguoi_dung SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sqlUpdate);
        $stmt->execute([$user['id']]);

        header("Location: " . BASE_URL_ADMIN);
        exit();
    }

    public function logout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Xóa toàn bộ session liên quan đến admin
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_vai_tro']);

    // Thông báo thành công
    $_SESSION['success'] = "Đăng xuất thành công!";

    // Chuyển hướng về trang đăng nhập
    header("Location: " . BASE_URL_ADMIN . "?act=login");
    exit();
}
}
