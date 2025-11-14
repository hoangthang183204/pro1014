<?php
class AdminTaiKhoanController
{
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "pro1014");
        $this->conn->set_charset("utf8mb4");
    }

    // Hiển thị form đăng ký
    public function register()
    {
        require_once __DIR__ . '/../views/auth/register.php';
;
    }

    // Xử lý đăng ký
    public function registerprocess()
    {
        session_start();

        $email = $_POST['email'];
        $pass = $_POST['password'];
        $confirm = $_POST['confirm'];

        // Kiểm tra mật khẩu
        if ($pass !== $confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Kiểm tra email trùng
        $sqlCheck = "SELECT * FROM admins WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $rs = $stmt->get_result();

        if ($rs->num_rows > 0) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Hash mật khẩu
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        // Thêm vào DB
        $sqlInsert = "INSERT INTO admins (email, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sqlInsert);
        $stmt->bind_param("ss", $email, $hash);
        $stmt->execute();

        $_SESSION['success'] = "Đăng ký thành công!";
        header("Location: " . BASE_URL_ADMIN . "?act=register");
        exit();
    }
}
