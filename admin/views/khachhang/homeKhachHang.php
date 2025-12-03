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
                        <i class="fas fa-users me-2"></i>
                        Quản Lý Khách Hàng
                    </a>
                    <div>
                        <a href="?act=khach-hang-thong-ke" class="btn btn-info me-2">
                            <i class="fas fa-chart-bar me-1"></i> Thống kê
                        </a>
                        <a href="?act=khach-hang-export" class="btn btn-success">
                            <i class="fas fa-download me-1"></i> Export
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-primary mx-auto mb-2">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-primary"><?= $thong_ke['tong_khach_hang'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">Tổng khách hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-success mx-auto mb-2">
                                    <i class="fas fa-suitcase"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-success"><?= $thong_ke['khach_co_tour'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">Khách có tour</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-warning mx-auto mb-2">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-warning"><?= $thong_ke['khach_moi_hom_nay'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">Mới hôm nay</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-info mx-auto mb-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-info"><?= $thong_ke['tour_da_thanh_toan'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">Tour đã thanh toán</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-secondary mx-auto mb-2">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-secondary"><?= $thong_ke['tour_chua_thanh_toan'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">chưa thanh toán</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-dark mx-auto mb-2">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-dark"><?= $thong_ke['tour_giu_cho'] ?? 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">giữ chỗ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc và tìm kiếm -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter me-2 text-primary"></i>
                            Tìm Kiếm & Lọc Khách Hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <input type="hidden" name="act" value="khach-hang-search">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Từ khóa tìm kiếm</label>
                                <input type="text" name="tu_khoa" class="form-control" 
                                       placeholder="Tìm theo tên, SĐT, email, CCCD hoặc tên tour..."
                                       value="<?php echo htmlspecialchars($_GET['tu_khoa'] ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Trạng thái tour</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="chưa thanh toán" <?php echo ($_GET['trang_thai'] ?? '') === 'chưa thanh toán' ? 'selected' : ''; ?>>chưa thanh toán</option>
                                    <option value="giữ chỗ" <?php echo ($_GET['trang_thai'] ?? '') === 'giữ chỗ' ? 'selected' : ''; ?>>giữ chỗ</option>
                                    <option value="đã thanh toán" <?php echo ($_GET['trang_thai'] ?? '') === 'đã thanh toán' ? 'selected' : ''; ?>>đã thanh toán</option>
                                    <option value="hủy" <?php echo ($_GET['trang_thai'] ?? '') === 'hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                    <option value="khong_co_tour" <?php echo ($_GET['trang_thai'] ?? '') === 'khong_co_tour' ? 'selected' : ''; ?>>Không có tour</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Tìm Kiếm
                                    </button>
                                    <a href="?act=khach-hang" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh me-1"></i> Làm Mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách khách hàng -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Danh Sách Khách Hàng
                            <span class="badge bg-primary ms-2"><?php echo count($danh_sach_khach_hang); ?></span>
                        </h5>
                        <div class="text-muted small">
                            <?php echo count($danh_sach_khach_hang); ?> kết quả
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($danh_sach_khach_hang)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0" id="khachHangTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="60" class="text-center">#</th>
                                            <th>THÔNG TIN KHÁCH HÀNG</th>
                                            <th width="220">TOUR & BOOKING</th>
                                            <th width="140" class="text-center">THỜI GIAN TOUR</th>
                                            <th width="150" class="text-center">HƯỚNG DẪN VIÊN</th>
                                            <th width="120" class="text-center">TRẠNG THÁI</th>
                                            <th width="100" class="text-center">THAO TÁC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($danh_sach_khach_hang as $index => $khach): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <strong class="text-muted"><?php echo $index + 1; ?></strong>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold text-primary mb-1"><?php echo htmlspecialchars($khach['ho_ten']); ?></div>
                                                            <div class="small text-muted">
                                                                <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($khach['so_dien_thoai']); ?>
                                                                <?php if (!empty($khach['email'])): ?>
                                                                    <br><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($khach['email']); ?>
                                                                <?php endif; ?>
                                                                <?php if (!empty($khach['cccd'])): ?>
                                                                    <br><i class="fas fa-id-card me-1"></i><?php echo htmlspecialchars($khach['cccd']); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($khach['ten_tour']): ?>
                                                        <div class="fw-bold text-success mb-1"><?php echo htmlspecialchars($khach['ten_tour']); ?></div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-tag me-1"></i><?php echo $khach['ma_tour']; ?>
                                                            <br>
                                                            <i class="fas fa-receipt me-1"></i><?php echo $khach['ma_dat_tour']; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Chưa có tour</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($khach['ngay_bat_dau']): ?>
                                                        <div class="fw-bold text-dark">
                                                            <?php echo date('d/m/Y', strtotime($khach['ngay_bat_dau'])); ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <?php echo date('d/m/Y', strtotime($khach['ngay_ket_thuc'])); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($khach['ten_huong_dan_vien']): ?>
                                                        <div class="fw-bold text-info"><?php echo htmlspecialchars($khach['ten_huong_dan_vien']); ?></div>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Chưa phân công</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $trang_thai_class = [
                                                        'chưa thanh toán' => 'bg-warning',
                                                        'giữ chỗ' => 'bg-info',
                                                        'đã thanh toán' => 'bg-success',
                                                        'hủy' => 'bg-danger'
                                                    ];
                                                    $trang_thai = $khach['trang_thai_dat_tour'] ?? 'Không có tour';
                                                    $class = $trang_thai_class[$khach['trang_thai_dat_tour']] ?? 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $class; ?>">
                                                        <i class="fas fa-<?php 
                                                            switch($khach['trang_thai_dat_tour'] ?? '') {
                                                                case 'chưa thanh toán': echo 'clock'; break;
                                                                case 'giữ chỗ': echo 'money-bill-wave'; break;
                                                                case 'đã thanh toán': echo 'check-circle'; break;
                                                                case 'hủy': echo 'times-circle'; break;
                                                                default: echo 'question';
                                                            }
                                                        ?> me-1"></i>
                                                        <?php echo $trang_thai; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?act=khach-hang-chi-tiet&id=<?php echo $khach['id']; ?>"
                                                           class="btn btn-primary"
                                                           data-bs-toggle="tooltip"
                                                           title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if (!$khach['phieu_dat_tour_id']): ?>
                                                        <a href="?act=khach-hang-delete&id=<?php echo $khach['id']; ?>"
                                                           class="btn btn-danger"
                                                           onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')"
                                                           data-bs-toggle="tooltip"
                                                           title="Xóa khách hàng">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không tìm thấy khách hàng</h5>
                                <p class="text-muted">Không có khách hàng nào phù hợp với tiêu chí tìm kiếm</p>
                                <a href="?act=khach-hang" class="btn btn-primary">
                                    <i class="fas fa-refresh me-1"></i> Xem tất cả khách hàng
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin -->
                    <?php if (!empty($danh_sach_khach_hang)): ?>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Hiển thị <strong>1</strong> đến <strong><?php echo count($danh_sach_khach_hang); ?></strong> trong tổng số <strong><?php echo count($danh_sach_khach_hang); ?></strong> khách hàng
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Hướng dẫn:</strong> Click <i class="fas fa-eye text-primary"></i> để xem chi tiết khách hàng
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

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

    .stats-card .icon.bg-success {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stats-card .icon.bg-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .stats-card .icon.bg-info {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stats-card .icon.bg-secondary {
        background: linear-gradient(135deg, #a8c0ff, #3f2b96);
    }

    .stats-card .icon.bg-dark {
        background: linear-gradient(135deg, #434343, #000000);
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

    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
    }

    .btn-group .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
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

        .btn-group {
            flex-direction: column;
            gap: 2px;
        }

        .btn-group .btn-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-footer .d-flex {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-group .btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }

        .stats-card .card-body {
            padding: 1rem !important;
        }

        .stats-card h4 {
            font-size: 1.25rem;
        }

        .row.mb-4 .col-xl-2 {
            flex: 0 0 50%;
            max-width: 50%;
            margin-bottom: 1rem;
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

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>