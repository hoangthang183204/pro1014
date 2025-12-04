<?php
// Kiểm tra xem có đang ở trang chủ không (không có ?act=...)
$is_home = !isset($_GET['act']);
?>
<nav class="sidebar">
    <ul>
        <li class="nav-item <?= $is_home ? 'active' : '' ?>" <?= $is_home ? 'data-page="dashboard"' : '' ?>>
            <a href="<?= BASE_URL_GUIDE ?>">
                <i class="fas fa-tachometer-alt"></i> Tổng Quan
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= BASE_URL_GUIDE . '?act=nhat_ky' ?>"
                class="nav-link d-flex align-items-center <?= (isset($_GET['act']) && $_GET['act'] == 'nhat_ky') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-book"></i>
                <p class="m-0 pl-2">Nhật Ký Tour</p>
            </a>
        </li>

        <li class="nav-item <?= (isset($_GET['act']) && $_GET['act'] == 'lich-trinh') ? 'active' : '' ?>">
            <a href="<?= BASE_URL_GUIDE . '?act=lich-trinh' ?>" class="nav-link d-flex align-items-center">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p class="m-0 pl-2">Lịch Trình Tour</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL_GUIDE . '?act=xem_danh_sach_khach' ?>"
                class="nav-link d-flex align-items-center <?= (isset($_GET['act']) && $_GET['act'] == 'xem_danh_sach_khach') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p class="m-0 pl-2">Danh Sách Khách</p>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL_GUIDE ?>?act=my-profile">
                <i class="fas fa-user-circle"></i> Thông tin tài khoản
            </a>
        </li>
        <li>
            <a href="?act=profile-settings"><i class="fas fa-cog"></i> Cài Đặt </a>
        </li>
        <li class="nav-item">
            <a href="index.php?act=logout" class="nav-link d-flex align-items-center"
                onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p class="m-0 pl-2">Đăng Xuất</p>
            </a>
        </li>
    </ul>
</nav>