<?php

// Kết nối CSDL qua PDO
function connectDB()
{
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave)
{
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dãn tuyệt đối của file

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

function deleteFile($file)
{
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
}

// Bắt đầu session nếu chưa có
function ensureSessionStarted(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

// Kiểm tra đã đăng nhập admin chưa
function isAdminLoggedIn(): bool
{
    ensureSessionStarted();
    return !empty($_SESSION['admin_id']);
}

// Hàm rào lại: gọi ở đầu admin/index.php
function CheckloginAdmin(): void
{
    ensureSessionStarted();

    // Route hiện tại (lấy từ query string)
    $act = $_GET['act'] ?? '/';

    // Các route công khai không cần đăng nhập
    $publicRoutes = [
        'login',
        'check-login-admin', // nếu bạn có xử lý riêng
        'logout-admin',
        'register',
        'register-process'
    ];

    // Nếu route thuộc public thì cho qua
    if (in_array($act, $publicRoutes, true)) {
        return;
    }

    // Nếu chưa login -> chuyển về trang đăng nhập
    if (!isAdminLoggedIn()) {
        header('Location: ' . BASE_URL_ADMIN . '?act=login');
        exit();
    }
}

function ensureAdminLoggedIn(): void
{
    ensureSessionStarted();

    if (empty($_SESSION['admin_id'])) {
        header("Location: " . BASE_URL_ADMIN . "?act=login");
        exit();
    }
}
