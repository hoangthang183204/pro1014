<?php

// Kết nối CSDL qua PDO
function connectDB() {
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

function uploadFile($file, $folderSave){
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dãn tuyệt đối của file

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

function deleteFile($file){
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
}




function getRoleName($vai_tro) {
    $roles = [
        'admin' => 'Quản trị viên',
        'nhan_vien' => 'Nhân viên',
        'huong_dan_vien' => 'Hướng dẫn viên',
        'huong_dan_yien' => 'Hướng dẫn viên' // Xử lý lỗi chính tả nếu có
    ];
    
    return $roles[$vai_tro] ?? 'Người dùng';
}

function checkGuideLogin() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['guide_id']) && 
           isset($_SESSION['guide_logged_in']) && 
           $_SESSION['guide_logged_in'] === true;
}

/**
 * Kiểm tra vai trò Guide
 */
function hasGuideRole($required_role) {
    return isset($_SESSION['guide_vai_tro']) && $_SESSION['guide_vai_tro'] === $required_role;
}

/**
 * Redirect nếu chưa đăng nhập Guide
 */
function requireGuideLogin() {
    if (!checkGuideLogin()) {
        $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục!";
        header('Location: ' . BASE_URL_GUIDE . '?act=login');
        exit();
    }
}

/**
 * Redirect nếu không có quyền Guide
 */
function requireGuideRole($required_role) {
    requireGuideLogin();
    if (!hasGuideRole($required_role)) {
        $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
        header('Location: ' . BASE_URL_GUIDE);
        exit();
    }
}

/**
 * Lấy thông tin user đang đăng nhập (Guide)
 */
function getCurrentGuide() {
    if (checkGuideLogin()) {
        return [
            'id' => $_SESSION['guide_id'],
            'name' => $_SESSION['guide_name'],
            'vai_tro' => $_SESSION['guide_vai_tro'],
            'email' => $_SESSION['guide_email'] ?? ''
        ];
    }
    return null;
}
