<?php
class AdminTaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function registerprocess()
    {
        session_start();

        // Kiểm tra dữ liệu đầu vào
        if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm'])) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        $email = $_POST['email'];
        $pass = $_POST['password'];
        $confirm = $_POST['confirm'];

        // Kiểm tra mật khẩu khớp
        if ($pass !== $confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Kiểm tra email trùng
        $sqlCheck = "SELECT * FROM tai_khoan WHERE email = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute([$email]);
        $rs = $stmt->fetch();

        if ($rs) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header("Location: " . BASE_URL_ADMIN . "?act=register");
            exit();
        }

        // Hash mật khẩu
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        // Thêm vào cơ sở dữ liệu
        $sqlInsert = "INSERT INTO tai_khoan (email, password) VALUES (?, ?)";
        try {
            $stmt = $this->conn->prepare($sqlInsert);
            $stmt->execute([$email, $hash]);
            $_SESSION['success'] = "Đăng ký thành công!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        }

        // Chuyển hướng
        header("Location: " . BASE_URL_ADMIN . "?act=login");
        exit();
    }

    // Lấy user theo email
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM tai_khoan WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thử đăng nhập: trả về user array nếu thành công, false nếu thất bại
    public function attemptLogin($email, $password)
    {
        $user = $this->getByEmail($email);
        if (!$user) return false;

        if (isset($user['password']) && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
