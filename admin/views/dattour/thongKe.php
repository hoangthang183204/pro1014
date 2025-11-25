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
                    <a class="navbar-brand" href="?act=dat-tour">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống Kê Booking
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Bộ lọc tháng/năm -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter me-2 text-primary"></i>
                            Lọc Thời Gian
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="dat-tour-thong-ke">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Tháng</label>
                                    <select name="thang" class="form-control">
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php echo ($thang == $i) ? 'selected' : ''; ?>>
                                                Tháng <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Năm</label>
                                    <select name="nam" class="form-control">
                                        <?php for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++): ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php echo ($nam == $i) ? 'selected' : ''; ?>>
                                                Năm <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Xem
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="icon bg-primary d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h6 class="text-muted mb-1 small fw-semibold">Tổng Booking</h6>
                                    <h4 class="mb-0 fw-bold"><?php echo $thong_ke['tong_booking'] ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="icon bg-warning d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h6 class="text-muted mb-1 small fw-semibold">Chờ Xác Nhận</h6>
                                    <h4 class="mb-0 fw-bold"><?php echo $thong_ke['cho_xac_nhan'] ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="icon bg-success d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h6 class="text-muted mb-1 small fw-semibold">Hoàn Tất</h6>
                                    <h4 class="mb-0 fw-bold"><?php echo $thong_ke['hoan_tat'] ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="icon bg-info d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <h6 class="text-muted mb-1 small fw-semibold">Doanh Thu</h6>
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($thong_ke['tong_doanh_thu'] ?? 0, 0, ',', '.'); ?>₫</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phân loại theo loại khách -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user me-2 text-info"></i>
                                        Booking Khách Lẻ
                                        <span class="badge bg-info ms-2"><?php echo count($booking_le); ?></span>
                                    </h5>
                                    <div class="text-muted small">
                                        Tổng: <?php echo count($booking_le); ?> booking
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($booking_le)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="120">Mã Booking</th>
                                                    <th>Khách Hàng</th>
                                                    <th width="120" class="text-center">Trạng Thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($booking_le as $booking): ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-primary"><?php echo $booking['ma_dat_tour']; ?></strong>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold"><?php echo $booking['ho_ten']; ?></div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-phone me-1"></i><?php echo $booking['so_dien_thoai']; ?>
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge <?php echo getStatusBadgeClass($booking['trang_thai']); ?>">
                                                                <i class="fas fa-<?php echo getStatusIcon($booking['trang_thai']); ?> me-1"></i>
                                                                <?php echo $booking['trang_thai']; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Không có booking khách lẻ</h6>
                                        <p class="text-muted small">Chưa có booking khách lẻ trong tháng này</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users me-2 text-success"></i>
                                        Booking Đoàn
                                        <span class="badge bg-success ms-2"><?php echo count($booking_doan); ?></span>
                                    </h5>
                                    <div class="text-muted small">
                                        Tổng: <?php echo count($booking_doan); ?> booking
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($booking_doan)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="120">Mã Booking</th>
                                                    <th>Khách Đoàn</th>
                                                    <th width="120" class="text-center">Trạng Thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($booking_doan as $booking): ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-primary"><?php echo $booking['ma_dat_tour']; ?></strong>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold"><?php echo $booking['ten_doan']; ?></div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-user-tie me-1"></i><?php echo $booking['ho_ten']; ?>
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge <?php echo getStatusBadgeClass($booking['trang_thai']); ?>">
                                                                <i class="fas fa-<?php echo getStatusIcon($booking['trang_thai']); ?> me-1"></i>
                                                                <?php echo $booking['trang_thai']; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Không có booking công ty</h6>
                                        <p class="text-muted small">Chưa có booking công ty trong tháng này</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê chi tiết -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2 text-warning"></i>
                                    Thống Kê Chi Tiết Theo Trạng Thái
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Trạng Thái</th>
                                                        <th width="100" class="text-center">Số Lượng</th>
                                                        <th width="150" class="text-center">Tỷ Lệ</th>
                                                        <th width="150" class="text-center">Doanh Thu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $tong_booking = $thong_ke['tong_booking'] ?? 1;
                                                    $status_stats = [
                                                        'chờ xác nhận' => $thong_ke['cho_xac_nhan'] ?? 0,
                                                        'đã cọc' => $thong_ke['da_coc'] ?? 0,
                                                        'hoàn tất' => $thong_ke['hoan_tat'] ?? 0,
                                                        'hủy' => $thong_ke['huy'] ?? 0
                                                    ];

                                                    foreach ($status_stats as $status => $count):
                                                        $ty_le = $tong_booking > 0 ? ($count / $tong_booking) * 100 : 0;
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge <?php echo getStatusBadgeClass($status); ?>">
                                                                    <?php echo $status; ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center fw-bold"><?php echo $count; ?></td>
                                                            <td class="text-center">
                                                                <div class="progress" style="height: 8px;">
                                                                    <div class="progress-bar <?php echo getStatusProgressClass($status); ?>"
                                                                        style="width: <?php echo $ty_le; ?>%">
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted"><?php echo number_format($ty_le, 1); ?>%</small>
                                                            </td>
                                                            <td class="text-center text-success fw-bold">
                                                                <?php
                                                                $doanh_thu = $status == 'hoàn tất' ? $thong_ke['tong_doanh_thu'] ?? 0 : 0;
                                                                echo number_format($doanh_thu, 0, ',', '.'); ?>₫
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-4 bg-light rounded">
                                            <h6 class="text-muted mb-3">Tổng Quan Tháng</h6>
                                            <div class="mb-3">
                                                <div class="h4 text-primary"><?php echo $thong_ke['tong_booking'] ?? 0; ?></div>
                                                <small class="text-muted">Tổng số booking</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="h5 text-success"><?php echo number_format($thong_ke['tong_doanh_thu'] ?? 0, 0, ',', '.'); ?>₫</div>
                                                <small class="text-muted">Tổng doanh thu</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="h6 text-info"><?php echo $thong_ke['hoan_tat'] ?? 0; ?></div>
                                                <small class="text-muted">Booking hoàn tất</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// Helper functions
function getStatusBadgeClass($status)
{
    $classes = [
        'chờ xác nhận' => 'bg-warning',
        'đã cọc' => 'bg-info',
        'hoàn tất' => 'bg-success',
        'hủy' => 'bg-danger'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

function getStatusIcon($status)
{
    $icons = [
        'chờ xác nhận' => 'clock',
        'đã cọc' => 'money-bill',
        'hoàn tất' => 'check-circle',
        'hủy' => 'times-circle'
    ];
    return $icons[$status] ?? 'question';
}

function getStatusProgressClass($status)
{
    $classes = [
        'chờ xác nhận' => 'bg-warning',
        'đã cọc' => 'bg-info',
        'hoàn tất' => 'bg-success',
        'hủy' => 'bg-danger'
    ];
    return $classes[$status] ?? 'bg-secondary';
}
?>

<?php include './views/layout/footer.php'; ?>

<style>
    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

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
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .stats-card .icon.bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stats-card .icon.bg-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .stats-card .icon.bg-success {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stats-card .icon.bg-info {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
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

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Progress Bars */
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
    }

    .progress-bar {
        border-radius: 4px;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .stats-card .icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .card-body {
            padding: 1rem;
        }

        .row.g-3 {
            margin: 0 -8px;
        }

        .col-md-3,
        .col-md-2 {
            padding: 0 8px;
            margin-bottom: 12px;
        }

        .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 22px;
        }

        .card {
            border-radius: 12px !important;
        }

        .card-body {
            padding: 20px !important;
        }

        .card .card-title {
            font-size: 14px;
            font-weight: 600;
        }

        .card h4 {
            font-weight: 700;
        }

        /* Hover hiệu ứng nhẹ */
        .card:hover {
            transform: translateY(-4px);
            transition: 0.2s ease;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            color: white;
            font-size: 1.25rem;
        }

        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Đảm bảo chiều cao đồng đều */
        .card-body {
            padding: 1.25rem;
        }

        /* Căn chỉnh nội dung */
        .d-flex.align-items-center {
            min-height: 60px;
        }
    }
</style>