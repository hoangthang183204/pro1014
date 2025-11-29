<?php 
// Sử dụng __DIR__ để nối chuỗi đường dẫn chính xác từ vị trí file hiện tại
// Cấu trúc: views/trangchu.php -> views/layout/header.php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';

// Kiểm tra đăng nhập và lấy thông tin hướng dẫn viên
$guideId = $_SESSION['guide_id'] ?? null;
$guideName = $_SESSION['guide_name'] ?? 'Hướng dẫn viên';

if (!$guideId) {
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}

// Lấy thông tin profile để hiển thị ảnh đại diện
require_once __DIR__ . '/../models/PersonalGuideModel.php';
$guideModel = new PersonalGuideModel();
$profile = $guideModel->getMyProfile($guideId);

// Hàm tạo avatar mặc định
function getDefaultAvatar($name) {
    $initial = mb_substr($name, 0, 1, 'UTF-8');
    $colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffecd2', '#fcb69f', '#a8edea', '#fed6e3'];
    $colorIndex = ord($initial) % count($colors);
    $backgroundColor = $colors[$colorIndex];

    $svg = '<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150">
        <rect width="150" height="150" fill="' . $backgroundColor . '" rx="8"/>
        <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="60" fill="white" 
              text-anchor="middle" dominant-baseline="middle" font-weight="bold">' . $initial . '</text>
    </svg>';

    return "data:image/svg+xml;base64," . base64_encode($svg);
}

// Hàm lấy URL avatar
function getAvatarUrl($profile) {
    if (!empty($profile['hinh_anh'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $profile['hinh_anh'];
        if (file_exists($imagePath) && is_file($imagePath)) {
            return "http://localhost/pro1014" . $profile['hinh_anh'];
        }
    }
    return getDefaultAvatar($profile['ho_ten'] ?? 'HDV');
}

$avatarUrl = getAvatarUrl($profile);
?>

<!-- Phần Nội Dung Chính (Main Content) -->
<main class="main-content">

    <!-- TRANG 1: DASHBOARD -->
    <div id="dashboard" class="page-content active">
        <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
        
        <!-- Card Dashboard (đã bỏ phần thông tin cá nhân) -->
        <div class="dashboard-cards">
            <!-- Card 1: Tour Đang Hoạt Động -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Tour Đang Hoạt Động</h2>
                    <div class="card-icon tour-icon" style="background-color: var(--primary-color);">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
                <div class="card-content">
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

            <!-- Card 4: Thống kê nhanh -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Thống Kê Nhanh</h2>
                    <div class="card-icon" style="background-color: var(--info-color);">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div class="card-content">
                    <p>Tổng số tour: <strong>24</strong></p>
                    <p>Tour hoàn thành: <strong>22</strong></p>
                    <p>Tour đang chạy: <strong>2</strong></p>
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

<style>
/* Cập nhật grid layout cho dashboard */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 2rem;
}

/* Card styles */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e1e5e9;
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header .card-title {
    margin: 0;
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.card-content {
    padding: 1.5rem;
}

.card-content p {
    margin: 0.5rem 0;
    color: #4a5568;
}

.alert {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 0.75rem;
    border-radius: 6px;
    margin-top: 1rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.empty-state {
    text-align: center;
    color: #718096;
    font-style: italic;
    padding: 2rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-cards {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .card-header {
        padding: 1.25rem;
    }
    
    .card-content {
        padding: 1.25rem;
    }
}
</style>

<?php 
// Include footer
include __DIR__ . '/layout/footer.php'; 
?>