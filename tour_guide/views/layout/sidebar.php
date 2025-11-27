<nav class="sidebar">
    <ul>
        <li  data-page="dashboard">
            <a href="<?= BASE_URL_GUIDE ?>"><i class="fas fa-tachometer-alt"></i> Tổng Quan</a>
        </li>

        <li class="<?= (isset($_GET['act']) && ($_GET['act'] == 'nhat_ky' || $_GET['act'] == 'nhat_ky_detail')) ? 'active' : '' ?>" data-page="nhat-ky">
            <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky"><i class="fas fa-book"></i> Nhật Ký Tour</a>
        </li>
        
        <li data-page="my-tours">
            <a href="#"><i class="fas fa-route"></i> Tour Của Tôi</a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Lịch Trình</a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Khách Hàng</a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Phản Hồi</a>
        </li>
        <li data-page="logout">
            <a href="?act=logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
        </li>
    </ul>
</nav>