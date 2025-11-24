<?php
// middleware/check-login.php

function checkLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Danh sách các route không cần đăng nhập
    $publicRoutes = [
        'login',
        'login-process',
        'register',
        'register-process',
        'logout-admin'
    ];

    $act = $_GET['act'] ?? '/';

    // Nếu không phải route public và chưa đăng nhập
    if (!in_array($act, $publicRoutes) && empty($_SESSION['admin_id'])) {
        $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục!";
        header('Location: ' . BASE_URL_ADMIN . '?act=login');
        exit();
    }

    // Nếu đã đăng nhập nhưng cố truy cập trang login/register
    if (in_array($act, ['login', 'register']) && !empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL_ADMIN);
        exit();
    }

    // Kiểm tra phân quyền theo vai trò
    checkRolePermission($act);
}

function checkRolePermission($act)
{
    if (empty($_SESSION['admin_id'])) {
        return;
    }

    $userRole = $_SESSION['admin_vai_tro'] ?? 'nhan_vien';
    
    // Danh sách route chỉ dành cho Admin
    $adminOnlyRoutes = [
        'register',
        'register-process',
        'danh-muc',
        'danh-muc-diem-den',
        'danh-muc-diem-den-create',
        'danh-muc-diem-den-store',
        'danh-muc-diem-den-edit',
        'danh-muc-diem-den-update',
        'danh-muc-diem-den-delete',
        'danh-muc-tour',
        'danh-muc-tour-create',
        'danh-muc-tour-store',
        'danh-muc-tour-edit',
        'danh-muc-tour-update',
        'danh-muc-tour-delete',
        'danh-muc-tag-tour',
        'danh-muc-tag-tour-create',
        'danh-muc-tag-tour-store',
        'danh-muc-tag-tour-edit',
        'danh-muc-tag-tour-update',
        'danh-muc-tag-tour-delete',
        'danh-muc-chinh-sach',
        'danh-muc-chinh-sach-create',
        'danh-muc-chinh-sach-store',
        'danh-muc-chinh-sach-edit',
        'danh-muc-chinh-sach-update',
        'danh-muc-chinh-sach-delete',
        'danh-muc-doi-tac',
        'danh-muc-doi-tac-create',
        'danh-muc-doi-tac-store',
        'danh-muc-doi-tac-edit',
        'danh-muc-doi-tac-update',
        'danh-muc-doi-tac-delete'
    ];

    // Danh sách route chỉ dành cho Hướng dẫn viên
    $hdvOnlyRoutes = [
        'nhat-ky-tour',
        'nhat-ky-tour-create',
        'nhat-ky-tour-store',
        'checkin-khach-hang',
        'checkin-khach-hang-process',
        'bao-cao-su-co',
        'bao-cao-su-co-create',
        'bao-cao-su-co-store'
    ];

    // Route mà HDV không được truy cập
    $hdvForbiddenRoutes = [
        'register',
        'register-process',
        'phan-cong',
        'phan-cong-store',
        'huy-phan-cong'
    ];

  
}