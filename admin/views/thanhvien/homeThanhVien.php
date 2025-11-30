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
                        <i class="fas fa-user-friends me-2"></i>
                        Quản Lý Thành Viên Tour
                    </a>
                    <div>
                        <a href="?act=khach-hang" class="btn btn-info me-2">
                            <i class="fas fa-users me-1"></i> Quản lý Khách hàng
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
                                <h4 class="card-title mb-1"><?= $thong_ke['tong_thanh_vien'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Tổng thành viên</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['co_yeu_cau_dac_biet'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Có yêu cầu đặc biệt</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['da_xu_ly_yeu_cau'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Đã xử lý yêu cầu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['thanh_vien_nam'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Thành viên nam</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-secondary">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['thanh_vien_nu'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Thành viên nữ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-dark">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= $thong_ke['so_phieu_dat_tour'] ?? 0 ?></h4>
                                <p class="card-text small mb-0">Số phiếu đặt tour</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Tìm kiếm thành viên</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="thanh-vien-tour-tim-kiem">
                            <div class="row g-3">
                                <div class="col-md-9">
                                    <input type="text" name="tu_khoa" class="form-control" 
                                           placeholder="Tìm theo tên, CCCD, tên khách hàng hoặc tên tour..."
                                           value="<?php echo htmlspecialchars($_GET['tu_khoa'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3 d-flex">
                                    <button type="submit" class="btn btn-primary w-100 mx-2">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="?act=thanh-vien-tour" class="btn btn-secondary w-100">
                                        <i class="fas fa-refresh me-1"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách thành viên -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Thành viên Tour (<?php echo count($danh_sach_thanh_vien); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($danh_sach_thanh_vien)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="thanhVienTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Thông tin Thành viên</th>
                                            <th width="150">Khách hàng đặt</th>
                                            <th width="180">Tour</th>
                                            <th width="120" class="text-center">Thời gian</th>
                                            <th width="100" class="text-center">Yêu cầu</th>
                                            <th width="100" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($danh_sach_thanh_vien as $index => $thanh_vien): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <strong><?php echo $index + 1; ?></strong>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?php echo htmlspecialchars($thanh_vien['ho_ten']); ?></div>
                                                    <div class="small text-muted">
                                                        <?php if (!empty($thanh_vien['cccd'])): ?>
                                                            <i class="fas fa-id-card me-1"></i><?php echo htmlspecialchars($thanh_vien['cccd']); ?>
                                                        <?php endif; ?>
                                                        <?php if (!empty($thanh_vien['ngay_sinh'])): ?>
                                                            <br><i class="fas fa-birthday-cake me-1"></i><?php echo date('d/m/Y', strtotime($thanh_vien['ngay_sinh'])); ?>
                                                        <?php endif; ?>
                                                        <br><i class="fas fa-venus-mars me-1"></i>
                                                        <?php
                                                        $gioi_tinh_labels = [
                                                            'nam' => 'Nam',
                                                            'nữ' => 'Nữ',
                                                            'khác' => 'Khác'
                                                        ];
                                                        echo $gioi_tinh_labels[$thanh_vien['gioi_tinh']] ?? 'Chưa xác định';
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($thanh_vien['ten_khach_hang']); ?></div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($thanh_vien['so_dien_thoai']); ?>
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-ticket-alt me-1"></i><?php echo $thanh_vien['ma_dat_tour']; ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($thanh_vien['ten_tour']); ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small">
                                                        <?php echo date('d/m/Y', strtotime($thanh_vien['ngay_bat_dau'])); ?>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <?php echo date('d/m/Y', strtotime($thanh_vien['ngay_ket_thuc'])); ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($thanh_vien['yeu_cau_dac_biet'])): ?>
                                                        <?php if ($thanh_vien['da_xu_ly_yeu_cau']): ?>
                                                            <span class="badge bg-success" title="Đã xử lý">
                                                                <i class="fas fa-check"></i> Đã xử lý
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning" title="Chờ xử lý">
                                                                <i class="fas fa-clock"></i> Chờ xử lý
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Không có</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?act=thanh-vien-tour-chi-tiet&id=<?php echo $thanh_vien['id']; ?>"
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
                                <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có thành viên nào</h5>
                                <p class="text-muted">Chưa có thành viên nào trong hệ thống</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin -->
                    <?php if (!empty($danh_sach_thanh_vien)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($danh_sach_thanh_vien); ?></strong> trong tổng số <strong><?php echo count($danh_sach_thanh_vien); ?></strong> thành viên
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Thông tin:</strong> Quản lý tất cả thành viên tham gia các tour
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

        .btn-info {
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