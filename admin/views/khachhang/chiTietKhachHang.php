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
                        <i class="fas fa-user me-2"></i>
                        Chi Tiết Khách Hàng
                    </a>
                    <a href="?act=khach-hang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
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

                <?php if ($thong_tin_khach_hang): ?>
                <div class="row">
                    <!-- Thông tin cá nhân -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Thông tin Cá nhân</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Họ tên:</th>
                                        <td><strong class="text-primary"><?php echo htmlspecialchars($thong_tin_khach_hang['ho_ten']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Số điện thoại:</th>
                                        <td>
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <?php echo htmlspecialchars($thong_tin_khach_hang['so_dien_thoai']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>
                                            <?php if ($thong_tin_khach_hang['email']): ?>
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                <?php echo htmlspecialchars($thong_tin_khach_hang['email']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>CCCD/CMND:</th>
                                        <td>
                                            <?php if ($thong_tin_khach_hang['cccd']): ?>
                                                <i class="fas fa-id-card text-muted me-2"></i>
                                                <?php echo htmlspecialchars($thong_tin_khach_hang['cccd']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày sinh:</th>
                                        <td>
                                            <?php if ($thong_tin_khach_hang['ngay_sinh']): ?>
                                                <i class="fas fa-birthday-cake text-muted me-2"></i>
                                                <?php echo date('d/m/Y', strtotime($thong_tin_khach_hang['ngay_sinh'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td>
                                            <?php if ($thong_tin_khach_hang['dia_chi']): ?>
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                <?php echo htmlspecialchars($thong_tin_khach_hang['dia_chi']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày tạo:</th>
                                        <td>
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($thong_tin_khach_hang['created_at'])); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin tour hiện tại -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-suitcase me-2"></i>Thông tin Tour Hiện tại</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($thong_tin_khach_hang['ten_tour']): ?>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Tour:</th>
                                            <td><strong class="text-success"><?php echo htmlspecialchars($thong_tin_khach_hang['ten_tour']); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <th>Mã tour:</th>
                                            <td><?php echo htmlspecialchars($thong_tin_khach_hang['ma_tour']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mã đặt tour:</th>
                                            <td>
                                                <span class="badge bg-dark"><?php echo $thong_tin_khach_hang['ma_dat_tour']; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian:</th>
                                            <td>
                                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                <?php echo date('d/m/Y', strtotime($thong_tin_khach_hang['ngay_bat_dau'])); ?> - 
                                                <?php echo date('d/m/Y', strtotime($thong_tin_khach_hang['ngay_ket_thuc'])); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Điểm tập trung:</th>
                                            <td>
                                                <i class="fas fa-map-pin text-muted me-2"></i>
                                                <?php echo htmlspecialchars($thong_tin_khach_hang['diem_tap_trung']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Hướng dẫn viên:</th>
                                            <td>
                                                <?php if ($thong_tin_khach_hang['ten_huong_dan_vien']): ?>
                                                    <i class="fas fa-user-check text-muted me-2"></i>
                                                    <strong><?php echo htmlspecialchars($thong_tin_khach_hang['ten_huong_dan_vien']); ?></strong>
                                                    <?php if ($thong_tin_khach_hang['sdt_huong_dan_vien']): ?>
                                                        <br><small class="text-muted ms-4"><?php echo $thong_tin_khach_hang['sdt_huong_dan_vien']; ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa phân công</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Trạng thái:</th>
                                            <td>
                                                <?php
                                                $trang_thai_class = [
                                                    'chờ xác nhận' => 'warning',
                                                    'đã cọc' => 'info',
                                                    'hoàn tất' => 'success',
                                                    'hủy' => 'danger'
                                                ];
                                                $class = $trang_thai_class[$thong_tin_khach_hang['trang_thai_dat']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $class; ?>">
                                                    <?php echo $thong_tin_khach_hang['trang_thai_dat']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                <?php else: ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-suitcase fa-2x text-muted mb-3"></i>
                                        <h6 class="text-muted">Khách hàng chưa có tour nào</h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thành viên trong tour hiện tại -->
                <?php if ($thong_tin_khach_hang['ten_tour'] && !empty($thanh_vien_tour_hien_tai)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Thành Viên Trong Tour Hiện Tại
                            <span class="badge bg-light text-dark"><?php echo count($thanh_vien_tour_hien_tai); ?> người</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Họ tên</th>
                                        <th width="120" class="text-center">CCCD</th>
                                        <th width="100" class="text-center">Ngày sinh</th>
                                        <th width="80" class="text-center">Giới tính</th>
                                        <th width="120" class="text-center">Yêu cầu đặc biệt</th>
                                        <th width="100" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($thanh_vien_tour_hien_tai as $index => $thanh_vien): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($thanh_vien['ho_ten']); ?>
                                            <?php if ($thanh_vien['ho_ten'] === $thong_tin_khach_hang['ho_ten']): ?>
                                                <span class="badge bg-primary ms-1">Người đặt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo $thanh_vien['cccd'] ?? 'Chưa cập nhật'; ?></td>
                                        <td class="text-center">
                                            <?php echo $thanh_vien['ngay_sinh'] ? date('d/m/Y', strtotime($thanh_vien['ngay_sinh'])) : 'Chưa cập nhật'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $gioi_tinh_labels = [
                                                'nam' => 'Nam',
                                                'nữ' => 'Nữ', 
                                                'khác' => 'Khác'
                                            ];
                                            echo $gioi_tinh_labels[$thanh_vien['gioi_tinh']] ?? 'Chưa xác định';
                                            ?>
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
                    </div>
                </div>
                <?php endif; ?>

                <!-- Lịch sử đặt tour -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử Đặt Tour</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($lich_su_dat_tour)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="150">Mã đặt tour</th>
                                            <th>Tour</th>
                                            <th width="120" class="text-center">Ngày đặt</th>
                                            <th width="150" class="text-center">Thời gian tour</th>
                                            <th width="120" class="text-center">Tổng tiền</th>
                                            <th width="100" class="text-center">Trạng thái</th>
                                            <th width="80" class="text-center">Thành viên</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lich_su_dat_tour as $tour): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-dark"><?php echo $tour['ma_dat_tour']; ?></strong>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($tour['ten_tour']); ?></div>
                                            </td>
                                            <td class="text-center">
                                                <?php echo date('d/m/Y', strtotime($tour['ngay_dat'])); ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="small">
                                                    <?php echo date('d/m/Y', strtotime($tour['ngay_bat_dau'])); ?>
                                                </div>
                                                <div class="small text-muted">
                                                    đến <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'])); ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-success"><?php echo number_format($tour['tong_tien'], 0, ',', '.'); ?> VNĐ</strong>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $trang_thai_class = [
                                                    'chờ xác nhận' => 'warning',
                                                    'đã cọc' => 'info',
                                                    'hoàn tất' => 'success',
                                                    'hủy' => 'danger'
                                                ];
                                                $class = $trang_thai_class[$tour['trang_thai']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $class; ?>">
                                                    <?php echo $tour['trang_thai']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (isset($tour['so_thanh_vien']) && $tour['so_thanh_vien'] > 0): ?>
                                                    <span class="badge bg-primary"><?php echo $tour['so_thanh_vien']; ?> người</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">1 người</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">Không có lịch sử đặt tour</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h5>Không tìm thấy thông tin khách hàng</h5>
                        <p class="mb-0">Khách hàng không tồn tại hoặc đã bị xóa</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    .table-borderless th {
        width: 40%;
        font-weight: 600;
        color: #495057;
    }

    .table-borderless td {
        color: #6c757d;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .card .card-body {
        padding: 1.25rem;
    }

    .btn-group .btn {
        margin: 0 2px;
        border-radius: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .row .col-md-6 {
            margin-bottom: 1rem;
        }

        .btn-group .btn {
            padding: 0.2rem 0.4rem;
            margin: 0 1px;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-secondary {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .card-body {
            padding: 1rem;
        }

        .table-borderless th,
        .table-borderless td {
            display: block;
            width: 100%;
            padding: 0.5rem 0;
        }

        .table-borderless tr {
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 0;
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
    }
</style>