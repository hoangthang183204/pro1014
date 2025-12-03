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
                        Thống Kê Đặt Tour
                    </a>
                    <div class="btn-group">
                        <a href="?act=dat-tour" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
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
                        <form method="GET" class="row g-3 align-items-end">
                            <input type="hidden" name="act" value="dat-tour-thong-ke">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tháng</label>
                                <select name="thang" class="form-select">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                            <?php echo ($thang == $i) ? 'selected' : ''; ?>>
                                            Tháng <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Năm</label>
                                <select name="nam" class="form-select">
                                    <?php for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                            <?php echo ($nam == $i) ? 'selected' : ''; ?>>
                                            Năm <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-bar me-1"></i> Xem Thống Kê
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Thông tin tổng quan -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-primary me-3">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-1 small fw-semibold">Tổng Booking</h6>
                                        <h4 class="mb-0 fw-bold text-primary"><?php echo $thong_ke['tong_booking'] ?? 0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-warning me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-1 small fw-semibold">chưa thanh toán</h6>
                                        <h4 class="mb-0 fw-bold text-warning"><?php echo $thong_ke['chua_thanh_toan'] ?? 0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-success me-3">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-1 small fw-semibold">đã thanh toán</h6>
                                        <h4 class="mb-0 fw-bold text-success"><?php echo $thong_ke['hoan_tat'] ?? 0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-info me-3">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-1 small fw-semibold">Doanh Thu</h6>
                                        <h4 class="mb-0 fw-bold text-info">
                                            <?php echo number_format($thong_ke['tong_doanh_thu'] ?? 0, 0, ',', '.'); ?>₫
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking mới nhất -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history me-2 text-primary"></i>
                                    Booking Mới Nhất
                                    <span class="badge bg-primary ms-2"><?php echo count($booking_moi_nhat); ?></span>
                                </h5>
                                <small class="text-muted">
                                    Tháng <?php echo $thang; ?>/<?php echo $nam; ?>
                                </small>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($booking_moi_nhat)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="120">MÃ BOOKING</th>
                                                    <th>KHÁCH HÀNG</th>
                                                    <th width="200">TOUR</th>
                                                    <th width="100" class="text-center">SỐ KHÁCH</th>
                                                    <th width="130" class="text-center">TỔNG TIỀN</th>
                                                    <th width="120" class="text-center">TRẠNG THÁI</th>
                                                    <th width="110" class="text-center">NGÀY ĐẶT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($booking_moi_nhat as $booking): ?>
                                                    <tr>
                                                        <td>
                                                            <strong class="text-primary"><?php echo $booking['ma_dat_tour']; ?></strong>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold"><?php echo htmlspecialchars($booking['ho_ten']); ?></div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-phone me-1"></i>
                                                                <?php echo htmlspecialchars($booking['so_dien_thoai']); ?>
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold small"><?php echo htmlspecialchars($booking['ten_tour']); ?></div>
                                                            <small class="text-muted">
                                                                <?php echo date('d/m/Y', strtotime($booking['ngay_bat_dau'])); ?>
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary rounded-pill">
                                                                <?php echo $booking['so_khach']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="text-success fw-bold">
                                                                <?php echo number_format($booking['tong_tien'], 0, ',', '.'); ?>₫
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $status_config = [
                                                                'chưa thanh toán' => ['class' => 'bg-warning', 'icon' => 'clock'],
                                                                'giữ chỗ' => ['class' => 'bg-info', 'icon' => 'money-bill-wave'],
                                                                'đã thanh toán' => ['class' => 'bg-success', 'icon' => 'check-circle'],
                                                                'hủy' => ['class' => 'bg-danger', 'icon' => 'times-circle']
                                                            ];
                                                            $config = $status_config[$booking['trang_thai']] ?? ['class' => 'bg-secondary', 'icon' => 'question'];
                                                            ?>
                                                            <span class="badge <?php echo $config['class']; ?>">
                                                                <i class="fas fa-<?php echo $config['icon']; ?> me-1"></i>
                                                                <?php echo $booking['trang_thai']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <small class="text-muted">
                                                                <?php echo date('d/m/Y', strtotime($booking['created_at'])); ?>
                                                            </small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Không có booking nào</h6>
                                        <p class="text-muted small">Chưa có booking nào trong tháng <?php echo $thang; ?>/<?php echo $nam; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê chi tiết theo trạng thái -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2 text-warning"></i>
                                    Thống Kê Theo Trạng Thái
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>TRẠNG THÁI</th>
                                                        <th width="100" class="text-center">SỐ LƯỢNG</th>
                                                        <th width="200" class="text-center">TỶ LỆ</th>
                                                        <th width="150" class="text-center">DOANH THU</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $tong_booking = $thong_ke['tong_booking'] ?? 1;
                                                    $status_stats = [
                                                        'chưa thanh toán' => [
                                                            'count' => $thong_ke['chua_thanh_toan'] ?? 0,
                                                            'doanh_thu' => 0
                                                        ],
                                                        'giữ chỗ' => [
                                                            'count' => $thong_ke['giu_cho'] ?? 0,
                                                            'doanh_thu' => 0
                                                        ],
                                                        'đã thanh toán' => [
                                                            'count' => $thong_ke['da_thanh_toan'] ?? 0,
                                                            'doanh_thu' => $thong_ke['tong_doanh_thu'] ?? 0
                                                        ],
                                                        'hủy' => [
                                                            'count' => $thong_ke['huy'] ?? 0,
                                                            'doanh_thu' => 0
                                                        ]
                                                    ];

                                                    foreach ($status_stats as $status => $data):
                                                        $count = $data['count'];
                                                        $doanh_thu = $data['doanh_thu'];
                                                        $ty_le = $tong_booking > 0 ? ($count / $tong_booking) * 100 : 0;
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge <?php echo getStatusBadgeClass($status); ?>">
                                                                    <i class="fas fa-<?php echo getStatusIcon($status); ?> me-1"></i>
                                                                    <?php echo $status; ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center fw-bold"><?php echo $count; ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                                                        <div class="progress-bar <?php echo getStatusProgressClass($status); ?>" 
                                                                            style="width: <?php echo $ty_le; ?>%">
                                                                        </div>
                                                                    </div>
                                                                    <span class="text-muted small"><?php echo number_format($ty_le, 1); ?>%</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="fw-bold text-success">
                                                                    <?php echo number_format($doanh_thu, 0, ',', '.'); ?>₫
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-4 bg-light rounded h-100">
                                            <h6 class="text-muted mb-4">TỔNG QUAN THÁNG</h6>
                                            <div class="mb-4">
                                                <div class="h3 text-primary fw-bold"><?php echo $thong_ke['tong_booking'] ?? 0; ?></div>
                                                <small class="text-muted">Tổng số booking</small>
                                            </div>
                                            <div class="mb-4">
                                                <div class="h4 text-success fw-bold">
                                                    <?php echo number_format($thong_ke['tong_doanh_thu'] ?? 0, 0, ',', '.'); ?>₫
                                                </div>
                                                <small class="text-muted">Tổng doanh thu</small>
                                            </div>
                                            <div class="mb-4">
                                                <div class="h5 text-info fw-bold"><?php echo $thong_ke['hoan_tat'] ?? 0; ?></div>
                                                <small class="text-muted">Booking đã thanh toán</small>
                                            </div>
                                            <div class="mb-4">
                                                <div class="h5 text-warning fw-bold"><?php echo $thong_ke['chua_thanh_toan'] ?? 0; ?></div>
                                                <small class="text-muted">Đang chưa thanh toán</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê theo tour -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2 text-info"></i>
                                    Thống Kê Theo Tour
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($thong_ke_tour)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>TOUR</th>
                                                    <th width="120" class="text-center">SỐ BOOKING</th>
                                                    <th width="120" class="text-center">SỐ KHÁCH</th>
                                                    <th width="150" class="text-center">DOANH THU</th>
                                                    <th width="100" class="text-center">TỶ LỆ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $tong_doanh_thu_tour = array_sum(array_column($thong_ke_tour, 'doanh_thu'));
                                                foreach ($thong_ke_tour as $tour): 
                                                    $ty_le = $tong_doanh_thu_tour > 0 ? ($tour['doanh_thu'] / $tong_doanh_thu_tour) * 100 : 0;
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <div class="fw-bold"><?php echo htmlspecialchars($tour['ten_tour']); ?></div>
                                                            <small class="text-muted"><?php echo $tour['ma_tour']; ?></small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary rounded-pill">
                                                                <?php echo $tour['so_booking']; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center fw-bold"><?php echo $tour['tong_khach']; ?></td>
                                                        <td class="text-center">
                                                            <span class="fw-bold text-success">
                                                                <?php echo number_format($tour['doanh_thu'], 0, ',', '.'); ?>₫
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-info">
                                                                <?php echo number_format($ty_le, 1); ?>%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Không có dữ liệu tour</h6>
                                        <p class="text-muted small">Chưa có booking nào cho các tour trong tháng <?php echo $thang; ?>/<?php echo $nam; ?></p>
                                    </div>
                                <?php endif; ?>
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
        'chưa thanh toán' => 'bg-warning',
        'giữ chỗ' => 'bg-info',
        'đã thanh toán' => 'bg-success',
        'hủy' => 'bg-danger'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

function getStatusIcon($status)
{
    $icons = [
        'chưa thanh toán' => 'clock',
        'giữ chỗ' => 'money-bill-wave',
        'đã thanh toán' => 'check-circle',
        'hủy' => 'times-circle'
    ];
    return $icons[$status] ?? 'question';
}

function getStatusProgressClass($status)
{
    $classes = [
        'chưa thanh toán' => 'bg-warning',
        'giữ chỗ' => 'bg-info',
        'đã thanh toán' => 'bg-success',
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
    }
</style>