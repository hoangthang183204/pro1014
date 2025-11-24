<?php

// Thay vào đó, kiểm tra đăng nhập đơn giản
if (!isset($_SESSION['guide_id'])) {
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hướng dẫn viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <style>
        /* GIỮ NGUYÊN TOÀN BỘ CSS */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        
        /* ... GIỮ NGUYÊN TOÀN BỘ PHẦN CSS ... */
    </style> -->
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
        }

        /* Header */
        header {
            background-color: var(--secondary-color);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--primary-color);
        }

        /* Main Layout */
        .main-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: calc(100vh - 120px);
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .sidebar li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar li.active {
            background-color: var(--primary-color);
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            overflow-y: auto;
        }

        .page-title {
            margin-bottom: 20px;
            color: var(--secondary-color);
            border-bottom: 2px solid var(--light-color);
            padding-bottom: 10px;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .tour-icon {
            background-color: var(--primary-color);
        }

        .issue-icon {
            background-color: var(--warning-color);
        }

        .guide-icon {
            background-color: var(--success-color);
        }

        .card-content {
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }

        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 10px 15px;
            margin: 10px 0;
            color: #856404;
        }

        /* Page Content */
        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--secondary-color);
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Footer */
        footer {
            background-color: var(--secondary-color);
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <i class="fas fa-map-marked-alt"></i>
            <h1>WEBSITE LATA - Hướng dẫn viên</h1>
            
        </div>
          <div class="user-welcome">
                <h2>Chào mừng trở lại, <?php echo $_SESSION['guide_name']; ?>!</h2>
                <p>Hôm nay là <?php echo date('d/m/Y'); ?> - Chúc bạn một ngày làm việc hiệu quả!</p>
            </div>

        <div class="user-info">
            
            <div>
                <div><?php echo $_SESSION['guide_name']; ?></div>
                <div style="font-size: 0.8rem; color: #ccc;">
                    <?php 
                    // Thay thế hàm getRoleName bằng logic trực tiếp
                    $vai_tro = $_SESSION['guide_vai_tro'] ?? '';
                    $role_names = [
                        'admin' => 'Quản trị viên',
                        'nhan_vien' => 'Nhân viên',
                        'huong_dan_vien' => 'Hướng dẫn viên',
                        'huong_dan_yien' => 'Hướng dẫn viên'
                    ];
                    echo $role_names[$vai_tro] ?? 'Người dùng';
                    ?>
                </div>


            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul>
                <li class="active"><a href="?act=guide-dashboard"><i class="fas fa-tachometer-alt"></i> Tổng Quan</a></li>
                <li><a href="#"><i class="fas fa-route"></i> Tour Của Tôi</a></li>
                <li><a href="#"><i class="fas fa-calendar-alt"></i> Lịch Trình</a></li>
                <li><a href="#"><i class="fas fa-exclamation-circle"></i> Sự Cố</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Khách Hàng</a></li>
                <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Báo Cáo</a></li>
                <li><a href="?act=profile"><i class="fas fa-cog"></i> Cài Đặt</a></li>
                <li><a href="?act=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hiển thị thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
            
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <!-- Tour Đang Hoạt Động -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Tour Đang Hoạt Động</h2>
                        <div class="card-icon tour-icon">
                            <i class="fas fa-route"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <p><strong>Tour Sắp Khởi Hành</strong></p>
                        <p>Hà Nội - Sapa (2 ngày 1 đêm)</p>
                        <p>Khởi hành: <?php echo date('d/m/Y', strtotime('+3 days')); ?></p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Cần chuẩn bị tài liệu tour
                        </div>
                    </div>
                </div>
                
                <!-- Thông Tin Cá Nhân -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Thông Tin Cá Nhân</h2>
                        <div class="card-icon" style="background-color: #9b59b6;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <p><strong>Họ tên:</strong> <?php echo $_SESSION['guide_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $_SESSION['guide_email']; ?></p>
                        <p><strong>Vai trò:</strong> 
                            <?php 
                            $vai_tro = $_SESSION['guide_vai_tro'] ?? '';
                            $role_names = [
                                'admin' => 'Quản trị viên',
                                'nhan_vien' => 'Nhân viên',
                                'huong_dan_vien' => 'Hướng dẫn viên',
                                'huong_dan_yien' => 'Hướng dẫn viên'
                            ];
                            echo $role_names[$vai_tro] ?? 'Người dùng';
                            ?>
                        </p>
                        <p><strong>Đánh giá:</strong> 4.8/5 (124 đánh giá)</p>
                        <a href="?act=profile" style="color: var(--primary-color); text-decoration: none; font-weight: bold;">
                            <i class="fas fa-edit"></i> Cập nhật thông tin
                        </a>
                    </div>
                </div>
                
                <!-- Lịch Trình Tuần -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Lịch Trình Tuần</h2>
                        <div class="card-icon" style="background-color: #1abc9c;">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <?php
                        $days = [
                            'Thứ 2' => 'Nghỉ',
                            'Thứ 3' => 'Tour Hà Nội - Hạ Long',
                            'Thứ 4' => 'Tour Hà Nội - Hạ Long',
                            'Thứ 5' => 'Tour Hà Nội - Sapa',
                            'Thứ 6' => 'Tour Hà Nội - Sapa',
                            'Thứ 7' => 'Nghỉ',
                            'Chủ nhật' => 'Chuẩn bị tour tuần sau'
                        ];
                        
                        foreach ($days as $day => $schedule): 
                            $color = $schedule === 'Nghỉ' ? '#95a5a6' : '#27ae60';
                        ?>
                            <p><strong><?php echo $day; ?>:</strong> <span style="color: <?php echo $color; ?>"><?php echo $schedule; ?></span></p>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Thống Kê Hiệu Suất -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Thống Kê Hiệu Suất</h2>
                        <div class="card-icon" style="background-color: #e67e22;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <p><strong>Tour đã dẫn:</strong> 24 tour</p>
                        <p><strong>Tỷ lệ đánh giá tốt:</strong> 95%</p>
                        <p><strong>Khách hàng hài lòng:</strong> 98%</p>
                        <p><strong>Sự cố đã xử lý:</strong> 5</p>
                        <p><strong>Doanh thu tạo ra:</strong> 245,000,000 VNĐ</p>
                    </div>
                </div>
                
                <!-- Tour Sắp Khởi Hành -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Tour Sắp Khởi Hành</h2>
                        <div class="card-icon guide-icon">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i> Có 2 tour sắp khởi hành
                        </div>
                        <p><strong>Hà Nội - Sapa</strong></p>
                        <p>Khởi hành: <?php echo date('d/m/Y', strtotime('+3 days')); ?></p>
                        <p><strong>Hà Nội - Hạ Long</strong></p>
                        <p>Khởi hành: <?php echo date('d/m/Y', strtotime('+5 days')); ?></p>
                    </div>
                </div>
                
                <!-- Sự Cố Cần Xử Lý -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Sự Cố Cần Xử Lý</h2>
                        <div class="card-icon issue-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Không có sự cố nào cần xử lý
                        </div>
                        <p>Tất cả sự cố đã được giải quyết. Tiếp tục phát huy!</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // JavaScript cho sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar li');
            const currentPage = '<?php echo $_GET['act'] ?? 'guide-dashboard'; ?>';
            
            // Highlight menu item hiện tại
            sidebarItems.forEach(item => {
                const link = item.querySelector('a');
                if (link) {
                    const href = link.getAttribute('href');
                    if (href && href.includes(currentPage)) {
                        item.classList.add('active');
                    }
                }
            });
            
            // Xử lý click menu
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    const link = this.querySelector('a');
                    if (link && link.getAttribute('href') !== '#') {
                        window.location.href = link.getAttribute('href');
                    }
                });
            });
        });
    </script>
</body>
</html>