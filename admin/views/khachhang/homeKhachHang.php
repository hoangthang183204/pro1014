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
                    <a class="navbar-brand" href="?act=/">
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
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['tong_khach_hang'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Tổng khách hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['khach_hang_co_tour'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Khách có tour</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['khach_moi_hom_nay'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Mới hôm nay</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['tour_da_hoan_thanh'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Tour hoàn thành</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-secondary">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['tour_cho_xac_nhan'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Chờ xác nhận</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-dark">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['tour_da_coc'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Đã cọc</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Tìm kiếm khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="khach-hang-tim-kiem">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="tu_khoa" class="form-control" 
                                           placeholder="Tìm theo tên, số điện thoại, email hoặc tên tour..."
                                           value="<?php echo htmlspecialchars($_GET['tu_khoa'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="trang_thai" class="form-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="chờ xác nhận" <?php echo ($_GET['trang_thai'] ?? '') === 'chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                        <option value="đã cọc" <?php echo ($_GET['trang_thai'] ?? '') === 'đã cọc' ? 'selected' : ''; ?>>Đã cọc</option>
                                        <option value="hoàn tất" <?php echo ($_GET['trang_thai'] ?? '') === 'hoàn tất' ? 'selected' : ''; ?>>Hoàn tất</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="?act=khach-hang" class="btn btn-secondary w-100 mt-2">
                                        <i class="fas fa-refresh me-1"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách khách hàng -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Khách hàng (<?php echo count($danh_sach_khach_hang); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($danh_sach_khach_hang)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="khachHangTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Thông tin Khách hàng</th>
                                            <th width="200">Tour</th>
                                            <th width="120" class="text-center">Thời gian</th>
                                            <th width="150" class="text-center">Hướng dẫn viên</th>
                                            <th width="120" class="text-center">Trạng thái</th>
                                            <th width="100" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($danh_sach_khach_hang as $index => $khach): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <strong><?php echo $index + 1; ?></strong>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?php echo htmlspecialchars($khach['ho_ten']); ?></div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($khach['so_dien_thoai']); ?>
                                                        <?php if (!empty($khach['email'])): ?>
                                                            <br><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($khach['email']); ?>
                                                        <?php endif; ?>
                                                        <?php if (!empty($khach['cccd'])): ?>
                                                            <br><i class="fas fa-id-card me-1"></i><?php echo htmlspecialchars($khach['cccd']); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($khach['ten_tour']): ?>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($khach['ten_tour']); ?></div>
                                                        <small class="text-muted"><?php echo $khach['ma_dat_tour']; ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa có tour</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($khach['ngay_bat_dau']): ?>
                                                        <div class="small">
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
                                                        <span class="fw-bold"><?php echo htmlspecialchars($khach['ten_huong_dan_vien']); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa phân công</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $trang_thai_class = [
                                                        'chờ xác nhận' => 'warning',
                                                        'đã cọc' => 'info',
                                                        'hoàn tất' => 'success',
                                                        'hủy' => 'danger'
                                                    ];
                                                    $trang_thai = $khach['trang_thai_dat_tour'] ?? 'Chưa đặt tour';
                                                    $class = $trang_thai_class[$khach['trang_thai_dat_tour']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?php echo $class; ?>">
                                                        <?php echo $trang_thai; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?act=khach-hang-chi-tiet&id=<?php echo $khach['id']; ?>"
                                                           class="btn btn-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có khách hàng nào</h5>
                                <p class="text-muted">Chưa có khách hàng nào trong hệ thống</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin -->
                    <?php if (!empty($danh_sach_khach_hang)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($danh_sach_khach_hang); ?></strong> trong tổng số <strong><?php echo count($danh_sach_khach_hang); ?></strong> khách hàng
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Thông tin:</strong> Click vào biểu tượng <i class="fas fa-eye"></i> để xem chi tiết
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

    .btn-primary {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        padding: 12px 8px;
    }

    .table td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .02);
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 2px;
        border-radius: 4px;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 12px 20px;
    }

    .card .card-body.text-center {
        padding: 1rem !important;
    }

    .card .card-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group .btn {
            padding: 0.2rem 0.4rem;
            margin: 0 1px;
        }

        .text-center.py-4 {
            padding: 2rem 1rem !important;
        }

        .card-footer .d-flex {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }

        .row.mb-4 .col-md-2 {
            margin-bottom: 10px;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-success, .btn-info {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }

        .btn-group .btn {
            flex: 1;
            min-width: 36px;
            font-size: 0.75rem;
        }

        .card-footer {
            padding: 10px 15px;
        }

        .card-footer .text-muted {
            font-size: 0.875rem;
        }

        .row.mb-4 {
            margin-bottom: 1rem !important;
        }

        .row.mb-4 .col-md-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>