<nav class="sidebar">
    <ul>
        <!-- 
          Lưu ý: data-page khớp với ID của các div trong index.php 
          Bạn có thể dùng PHP để kiểm tra quyền user và ẩn/hiện các menu này
        -->
        <li class="active" data-page="dashboard">
            <a href="#"><i class="fas fa-tachometer-alt"></i> Tổng Quan</a>
        </li>
        <li data-page="my-tours">
            <a href="#"><i class="fas fa-route"></i> Tour Của Tôi</a>
        </li>
        <li data-page="schedule">
            <a href="#"><i class="fas fa-calendar-alt"></i> Lịch Trình</a>
        </li>
        <li data-page="issues">
            <a href="#"><i class="fas fa-exclamation-circle"></i> Sự Cố</a>
        </li>
        <li data-page="customers">
            <a href="#"><i class="fas fa-users"></i> Khách Hàng</a>
        </li>
        <li data-page="reports">
            <a href="#"><i class="fas fa-file-invoice-dollar"></i> Báo Cáo</a>
        </li>
         <li>
            <a href="<?= BASE_URL_GUIDE ?>?act=my-profile"><i class="fas fa-user-circle"></i> Thông tin tài khoản</a>
        </li>
        <li data-page="settings">
            <a href="#"><i class="fas fa-cog"></i> Cài Đặt</a>
        </li>
        <li data-page="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
        </li>
    </ul>
</nav>