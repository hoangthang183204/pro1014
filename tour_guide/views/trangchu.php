<?php 
// Sử dụng __DIR__ để nối chuỗi đường dẫn chính xác từ vị trí file hiện tại
// Cấu trúc: views/trangchu.php -> views/layout/header.php
include __DIR__ . '/layout/header.php'; 
include __DIR__ . '/layout/sidebar.php'; 
?>

<!-- Phần Nội Dung Chính (Main Content) -->
<main class="main-content">

    <!-- TRANG 1: DASHBOARD -->
    <div id="dashboard" class="page-content active">
        <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
        
        <div class="dashboard-cards">
            <!-- Card 1: Tour Đang Hoạt Động -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Tour Đang Hoạt Động</h2>
                    <!-- Lưu ý: biến CSS var(--primary-color) đã được định nghĩa bên header -->
                    <div class="card-icon tour-icon" style="background-color: var(--primary-color);">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
                <div class="card-content">
                    <!-- Ví dụ hiển thị dữ liệu tĩnh (sau này thay bằng PHP echo từ DB) -->
                    <p><strong>Tour Sắp Khởi Hành:</strong> Hà Nội - Sapa</p>
                    <p style="font-size: 0.9rem; color: #666;">Khởi hành: 15/06/2023</p>
                    
                    <div class="alert">
                        <i class="fas fa-exclamation-triangle"></i> Có 1 sự cố mới cần xử lý
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Thống Kê Hiệu Suất -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Hiệu Suất</h2>
                    <div class="card-icon" style="background-color: var(--warning-color);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="card-content">
                    <p>Doanh thu tháng: <strong>85,000,000 VNĐ</strong></p>
                    <p>Đánh giá trung bình: <strong>4.8/5</strong> (24 đánh giá)</p>
                    <p>Khách hàng hài lòng: 98%</p>
                </div>
            </div>

            <!-- Card 3: Lịch Trình -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Lịch Trình Hôm Nay</h2>
                    <div class="card-icon" style="background-color: var(--success-color);">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
                <div class="card-content">
                    <div class="empty-state">
                        Không có lịch trình trong ngày hôm nay.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CÁC TRANG KHÁC (Để trống hoặc include nội dung tương tự) -->
    <!-- Ví dụ trang Tour của tôi -->
    <div id="my-tours" class="page-content">
        <h1 class="page-title">Danh Sách Tour Của Tôi</h1>
        <p>Đang tải dữ liệu tour...</p>
    </div>

    <!-- Ví dụ trang Lịch trình -->
    <div id="schedule" class="page-content">
        <h1 class="page-title">Lịch Trình Chi Tiết</h1>
        <p>Đang tải lịch trình...</p>
    </div>

    <!-- Các trang khác tương tự: issues, customers, reports, settings, logout -->
    <!-- Phần xử lý logic JS đã có trong footer.php để switch tab -->

</main>

<?php 
// Include footer
include __DIR__ . '/layout/footer.php'; 
?>