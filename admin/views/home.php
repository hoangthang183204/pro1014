<!-- Header -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="dashboard-header py-3 mb-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="h3 mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard - Tổng Quan Hệ Thống
                        </h1>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark">
                            <?php echo date('d/m/Y'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <!-- Thống kê nhanh -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h2 class="card-title"><?php echo $thongKe['tong_tour'] ?? 0; ?></h2>
                                    <p class="card-text mb-0">Tour Đang Hoạt Động</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-suitcase fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h2 class="card-title"><?php echo $thongKe['tour_sap_khoi_hanh'] ?? 0; ?></h2>
                                    <p class="card-text mb-0">Tour Sắp Khởi Hành</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-plane-departure fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card stat-card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h2 class="card-title"><?php echo $thongKe['su_co_hom_nay'] ?? 0; ?></h2>
                                    <p class="card-text mb-0">Sự Cố Hôm Nay</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card stat-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h2 class="card-title"><?php echo $thongKe['hdv_dang_lam'] ?? 0; ?></h2>
                                    <p class="card-text mb-0">HDV Đang Làm Việc</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ phân tích -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Phân Tích Tour Theo Tháng (Năm <?php echo date('Y'); ?>)
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="chartFilter" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-1"></i> Lọc
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="filterChart('all')">Tất cả tour</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterChart('completed')">Tour đã hoàn thành</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterChart('upcoming')">Tour sắp khởi hành</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="tourChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Phân Bổ Trạng Thái Tour
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Tour sắp khởi hành -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Tour Sắp Khởi Hành
                            </h5>
                            <span class="badge bg-light text-primary"><?php echo count($tourSapKhoiHanh); ?></span>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($tourSapKhoiHanh)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($tourSapKhoiHanh as $tour): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></h6>
                                                    <p class="mb-1"><?php echo htmlspecialchars($tour['ten_tour']); ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user me-1"></i>
                                                            <?php echo htmlspecialchars($tour['ten_hdv'] ?? 'Chưa phân công'); ?>
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-chair me-1"></i>
                                                            <?php echo $tour['so_cho_con_lai'] ?? 0; ?> chỗ trống
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold text-success">
                                                        <?php echo date('d/m', strtotime($tour['ngay_bat_dau'])); ?>
                                                    </div>
                                                    <small class="text-muted">Ngày đi</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Không có tour nào sắp khởi hành</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sự cố cần xử lý -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Sự Cố Cần Xử Lý
                            </h5>
                            <span class="badge bg-light text-warning"><?php echo count($suCoCanXuLy); ?></span>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($suCoCanXuLy)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($suCoCanXuLy as $su_co): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($su_co['tieu_de']); ?></h6>
                                                        <span class="badge bg-<?php echo $su_co['muc_do_nghiem_trong'] == 'nghiêm trọng' ? 'danger' : 'warning'; ?> ms-2">
                                                            <?php echo htmlspecialchars($su_co['muc_do_nghiem_trong']); ?>
                                                        </span>
                                                    </div>
                                                    <p class="mb-1 text-muted"><?php echo htmlspecialchars($su_co['ten_tour']); ?></p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        <?php echo htmlspecialchars($su_co['ten_hdv']); ?>
                                                        •
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?php echo date('H:i d/m', strtotime($su_co['thoi_gian_bao_cao'])); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">Không có sự cố nào cần xử lý</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Dữ liệu biểu đồ từ PHP
    const chartData = {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ],
        datasets: [{
            label: 'Tour khởi hành',
            data: [<?php echo implode(', ', $thongKe['tour_theo_thang'] ?? array_fill(0, 12, 0)); ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.4
        }, {
            label: 'Tour hoàn thành',
            data: [<?php echo implode(', ', $thongKe['tour_hoan_thanh_theo_thang'] ?? array_fill(0, 12, 0)); ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            tension: 0.4
        }]
    };

    const revenueData = {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [<?php
                    $doanhThuData = $doanhThuTheoThang ?? array_fill(0, 12, 0);
                    echo implode(', ', $doanhThuData);
                    ?>],
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 2,
            fill: true
        }]
    };

    const statusData = {
        labels: ['Đã lên lịch', 'Đang đi', 'Đã hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [
                <?php echo $thongKe['tour_da_len_lich'] ?? 0; ?>,
                <?php echo $thongKe['tour_dang_dien_ra'] ?? 0; ?>,
                <?php echo $thongKe['tour_da_hoan_thanh'] ?? 0; ?>,
                <?php echo $thongKe['tour_da_huy'] ?? 0; ?>
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Khởi tạo biểu đồ
    const tourChart = new Chart(document.getElementById('tourChart'), {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: revenueData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            }
        }
    });

    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: statusData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<style>
    .stat-card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .card-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .card-text {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        padding: 1rem;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .badge {
        font-size: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-title {
            font-size: 1.5rem;
        }

        .stat-card .fa-2x {
            font-size: 1.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
        }
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>