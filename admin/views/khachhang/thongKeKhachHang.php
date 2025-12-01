<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=khach-hang">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống Kê Khách Hàng
                    </a>
                    <div class="btn-group">
                        <a href="?act=khach-hang" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="?act=khach-hang-export" class="btn btn-success">
                            <i class="fas fa-download me-1"></i> Export
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thống kê tổng quan -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="icon bg-primary mx-auto mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="card-title mb-1 fw-bold text-primary">
                                    <?php echo number_format($tong_so_khach_hang); ?>
                                </h3>
                                <p class="card-text text-muted mb-0">Tổng số khách hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="icon bg-success mx-auto mb-3">
                                    <i class="fas fa-suitcase"></i>
                                </div>
                                <h3 class="card-title mb-1 fw-bold text-success">
                                    <?php echo number_format($khach_hang_co_tour); ?>
                                </h3>
                                <p class="card-text text-muted mb-0">Khách hàng có tour</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="icon bg-info mx-auto mb-3">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <h3 class="card-title mb-1 fw-bold text-info">
                                    <?php echo $ty_le_co_tour; ?>%
                                </h3>
                                <p class="card-text text-muted mb-0">Tỷ lệ khách có tour</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="icon bg-warning mx-auto mb-3">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h3 class="card-title mb-1 fw-bold text-warning">
                                    <?php echo number_format($khach_moi_thang_nay); ?>
                                </h3>
                                <p class="card-text text-muted mb-0">Khách mới tháng <?php echo date('m/Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê chi tiết theo tháng -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Thống Kê Chi Tiết Theo Tháng (12 tháng gần nhất)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($thong_ke_chi_tiet)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="150" class="text-center">THÁNG/NĂM</th>
                                            <th width="120" class="text-center">TỔNG KHÁCH HÀNG</th>
                                            <th width="120" class="text-center">KHÁCH CÓ TOUR</th>
                                            <th width="120" class="text-center">KHÁCH MỚI</th>
                                            <th width="150" class="text-center">TỶ LỆ CÓ TOUR</th>
                                            <th width="150" class="text-center">TĂNG TRƯỞNG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $previous_month_count = null;
                                        foreach ($thong_ke_chi_tiet as $index => $thong_ke): 
                                            $ty_le_co_tour = $thong_ke['so_luong'] > 0 ? 
                                                round(($thong_ke['so_booking'] / $thong_ke['so_luong']) * 100, 1) : 0;
                                            
                                            // Tính tăng trưởng
                                            $tang_truong = '';
                                            $tang_truong_class = 'secondary';
                                            if ($previous_month_count !== null && $previous_month_count > 0) {
                                                $phan_tram_tang = (($thong_ke['so_luong'] - $previous_month_count) / $previous_month_count) * 100;
                                                if ($phan_tram_tang > 0) {
                                                    $tang_truong = '+' . round($phan_tram_tang, 1) . '%';
                                                    $tang_truong_class = 'success';
                                                } elseif ($phan_tram_tang < 0) {
                                                    $tang_truong = round($phan_tram_tang, 1) . '%';
                                                    $tang_truong_class = 'danger';
                                                } else {
                                                    $tang_truong = '0%';
                                                    $tang_truong_class = 'warning';
                                                }
                                            } else {
                                                $tang_truong = 'Mới';
                                                $tang_truong_class = 'info';
                                            }
                                            $previous_month_count = $thong_ke['so_luong'];
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold">
                                                    <div class="text-primary">Tháng <?php echo $thong_ke['thang']; ?></div>
                                                    <div class="small text-muted">Năm <?php echo $thong_ke['nam']; ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary fs-6">
                                                        <?php echo number_format($thong_ke['so_luong']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success fs-6">
                                                        <?php echo number_format($thong_ke['so_booking']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning fs-6">
                                                        <?php echo number_format($thong_ke['so_luong']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $ty_le_class = $ty_le_co_tour >= 50 ? 'success' : ($ty_le_co_tour >= 30 ? 'warning' : 'danger');
                                                    ?>
                                                    <div class="progress mb-2" style="height: 8px;">
                                                        <div class="progress-bar bg-<?php echo $ty_le_class; ?>" 
                                                             style="width: <?php echo min($ty_le_co_tour, 100); ?>%">
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-<?php echo $ty_le_class; ?>">
                                                        <i class="fas fa-chart-line me-1"></i>
                                                        <?php echo $ty_le_co_tour; ?>%
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?php echo $tang_truong_class; ?> fs-6">
                                                        <i class="fas fa-<?php 
                                                            echo $tang_truong_class == 'success' ? 'arrow-up' : 
                                                                ($tang_truong_class == 'danger' ? 'arrow-down' : 'minus');
                                                        ?> me-1"></i>
                                                        <?php echo $tang_truong; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có dữ liệu thống kê</h5>
                                <p class="text-muted">Chưa có dữ liệu thống kê cho 12 tháng gần nhất</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Biểu đồ phân bố -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2 text-warning"></i>
                                    Phân Bố Khách Hàng
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="p-3 bg-light rounded">
                                            <h3 class="text-primary fw-bold"><?php echo number_format($khach_hang_co_tour); ?></h3>
                                            <p class="text-muted mb-0">Có tour</p>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="p-3 bg-light rounded">
                                            <h3 class="text-secondary fw-bold"><?php echo number_format($tong_so_khach_hang - $khach_hang_co_tour); ?></h3>
                                            <p class="text-muted mb-0">Không có tour</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: <?php echo $ty_le_co_tour; ?>%"
                                             data-bs-toggle="tooltip" 
                                             title="Khách có tour: <?php echo $ty_le_co_tour; ?>%">
                                            <?php echo $ty_le_co_tour; ?>%
                                        </div>
                                        <div class="progress-bar bg-secondary" 
                                             style="width: <?php echo 100 - $ty_le_co_tour; ?>%"
                                             data-bs-toggle="tooltip" 
                                             title="Khách không có tour: <?php echo 100 - $ty_le_co_tour; ?>%">
                                            <?php echo 100 - $ty_le_co_tour; ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-trending-up me-2 text-info"></i>
                                    Xu Hướng Đăng Ký
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-2x text-info mb-3"></i>
                                    <h5 class="text-info"><?php echo number_format($khach_moi_thang_nay); ?></h5>
                                    <p class="text-muted mb-1">Khách hàng mới tháng <?php echo date('m/Y'); ?></p>
                                    <?php if ($tang_truong_thang_nay > 0): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-up me-1"></i>
                                            Tăng <?php echo $tang_truong_thang_nay; ?>% so với tháng trước
                                        </span>
                                    <?php elseif ($tang_truong_thang_nay < 0): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-down me-1"></i>
                                            Giảm <?php echo abs($tang_truong_thang_nay); ?>% so với tháng trước
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-minus me-1"></i>
                                            Không thay đổi so với tháng trước
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    .card {
        border-radius: 8px;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        padding: 1rem 1.25rem;
    }

    /* Stats Cards */
    .stats-card {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stats-card .icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .stats-card .icon.bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stats-card .icon.bg-success {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stats-card .icon.bg-info {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stats-card .icon.bg-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    /* Table Styles */
    .table th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 0.75rem 0.5rem;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
    }

    .badge.fs-6 {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.875rem;
    }

    .progress {
        border-radius: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .badge.fs-6 {
            font-size: 0.75rem !important;
            padding: 0.375rem 0.75rem;
        }

        .row .col-md-6 {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .stats-card .card-body {
            padding: 1.5rem !important;
        }

        .stats-card h3 {
            font-size: 1.5rem;
        }

        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }

        .btn-group {
            flex-direction: column;
            gap: 5px;
        }

        .btn-group .btn {
            font-size: 0.875rem;
            padding: 6px 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>