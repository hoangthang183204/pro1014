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
            <a href="<?= BASE_URL_GUIDE . '?act=nhat_ky' ?>" class="nav-link d-flex align-items-center <?= (isset($_GET['act']) && $_GET['act'] == 'nhat_ky') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-book"></i>
                <p class="m-0 pl-2">Nhật Ký Tour</p>
            </a>
        </li>

        <li data-page="my-tours">
            <a href="#"><i class="fas fa-route"></i> Tour Của Tôi</a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Lịch Trình</a>
        </li>
        <li class="nav-item">
            <a href="<?= BASE_URL_GUIDE . '?act=xem_danh_sach_khach' ?>"
                class="nav-link d-flex align-items-center <?= (isset($_GET['act']) && $_GET['act'] == 'xem_danh_sach_khach') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p class="m-0 pl-2">Danh Sách Khách</p>
            </a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Phản Hồi</a>
        </li>
        <li class="nav-item">
            <a href="index.php?act=logout" class="nav-link d-flex align-items-center" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p class="m-0 pl-2">Đăng Xuất</p>
            </a>
        </li>
    </ul>
</nav>