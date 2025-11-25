<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Tour - LATA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
        }
        
        /* Header Styles */
        header {
            background-color: var(--secondary-color);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .logo { display: flex; align-items: center; }
        .logo img { height: 40px; margin-right: 10px; }
        .logo h1 { font-size: 1.5rem; font-weight: 600; }
        
        .user-info { display: flex; align-items: center; }
        .user-info img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; border: 2px solid var(--primary-color); }
        
        /* Layout */
        .main-container { display: grid; grid-template-columns: 250px 1fr; min-height: calc(100vh - 120px); }
        
        /* Sidebar & Nav */
        .sidebar { background-color: var(--dark-color); color: white; padding: 20px 0; }
        .sidebar ul { list-style: none; }
        .sidebar li { padding: 12px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); transition: background-color 0.3s; cursor: pointer; }
        .sidebar li:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar li.active { background-color: var(--primary-color); }
        .sidebar a { color: white; text-decoration: none; display: flex; align-items: center; width: 100%; }
        .sidebar i { margin-right: 10px; width: 20px; text-align: center; }
        
        /* Content */
        .main-content { padding: 20px; overflow-y: auto; }
        .page-title { margin-bottom: 20px; color: var(--secondary-color); border-bottom: 2px solid var(--light-color); padding-bottom: 10px; }
        
        /* Cards */
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .card-title { font-size: 1.2rem; font-weight: 600; color: var(--secondary-color); }
        .card-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: var(--secondary-color); color: white; }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .status-active { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        
        /* Utilities */
        .page-content { display: none; }
        .page-content.active { display: block; }
        .form-group { margin-bottom: 15px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background-color: var(--primary-color); color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        
        /* Footer */
        footer { background-color: var(--secondary-color); color: white; text-align: center; padding: 15px; font-size: 0.9rem; }
        
        @media (max-width: 768px) {
            .main-container { grid-template-columns: 1fr; }
            .sidebar { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <i class="fas fa-map-marked-alt fa-2x" style="margin-right: 10px;"></i>
            <h1>WEBSITE LATA - Hướng dẫn viên</h1>
        </div>
        <div class="user-info">
            <!-- Ảnh đại diện có thể lấy từ DB -->
            <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random" alt="Avatar">
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
        </div>
    </header>
    
    <!-- Bắt đầu Container chính (sẽ đóng ở footer hoặc index) -->
    <div class="main-container">