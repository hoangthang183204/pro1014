<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=khach-hang">
                        <i class="fas fa-user me-2"></i>
                        Chi Tiết Khách Hàng
                    </a>
                    <div class="btn-group">
                        <a href="?act=khach-hang" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <?php if ($thong_tin_khach_hang): ?>
                        <a href="?act=khach-hang-edit&id=<?php echo $thong_tin_khach_hang['id']; ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Sửa
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if ($thong_tin_khach_hang): ?>
                <div class="row">
                    <!-- Thông tin cá nhân -->
                    <div class="col-md-6">
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-circle me-2"></i>
                                    Thông Tin Cá Nhân
                                </h5>
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
                                        <th>Giới tính:</th>
                                        <td>
                                            <?php
                                            $gioi_tinh_labels = [
                                                'nam' => 'Nam',
                                                'nữ' => 'Nữ',
                                                'khác' => 'Khác'
                                            ];
                                            echo $gioi_tinh_labels[$thong_tin_khach_hang['gioi_tinh']] ?? 'Chưa xác định';
                                            ?>
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
                                        <th>Ghi chú:</th>
                                        <td>
                                            <?php if ($thong_tin_khach_hang['ghi_chu']): ?>
                                                <?php echo htmlspecialchars($thong_tin_khach_hang['ghi_chu']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không có ghi chú</span>
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
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-suitcase me-2"></i>
                                    Thông Tin Tour Hiện Tại
                                </h5>
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
                                            <th>Mã booking:</th>
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
                                            <th>Giờ tập trung:</th>
                                            <td>
                                                <i class="fas fa-clock text-muted me-2"></i>
                                                <?php echo date('H:i', strtotime($thong_tin_khach_hang['gio_tap_trung'])); ?>
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
                                                $class = $trang_thai_class[$thong_tin_khach_hang['trang_thai_dat_tour']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $class; ?>">
                                                    <?php echo $thong_tin_khach_hang['trang_thai_dat_tour']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tổng tiền:</th>
                                            <td>
                                                <strong class="text-success">
                                                    <?php echo number_format($thong_tin_khach_hang['tong_tien'], 0, ',', '.'); ?> VNĐ
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                <?php else: ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-suitcase fa-2x text-muted mb-3"></i>
                                        <h6 class="text-muted">Khách hàng chưa có tour nào</h6>
                                        <p class="text-muted small">Khách hàng chưa tham gia tour nào hoặc tour đã kết thúc</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách khách hàng cùng booking -->
                <?php if (!empty($khach_hang_cung_booking)): ?>
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Thành Viên Cùng Booking
                            <span class="badge bg-light text-dark"><?php echo count($khach_hang_cung_booking); ?> người</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Họ tên</th>
                                        <th width="120" class="text-center">Số điện thoại</th>
                                        <th width="120" class="text-center">CCCD</th>
                                        <th width="100" class="text-center">Ngày sinh</th>
                                        <th width="80" class="text-center">Giới tính</th>
                                        <th width="100" class="text-center">Vai trò</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($khach_hang_cung_booking as $index => $khach): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($khach['ho_ten']); ?>
                                            <?php if ($khach['id'] == $thong_tin_khach_hang['id']): ?>
                                                <span class="badge bg-primary ms-1">Khách hàng này</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo $khach['so_dien_thoai'] ?? 'Chưa cập nhật'; ?></td>
                                        <td class="text-center"><?php echo $khach['cccd'] ?? 'Chưa cập nhật'; ?></td>
                                        <td class="text-center">
                                            <?php echo $khach['ngay_sinh'] ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : 'Chưa cập nhật'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $gioi_tinh_labels = [
                                                'nam' => 'Nam',
                                                'nữ' => 'Nữ', 
                                                'khác' => 'Khác'
                                            ];
                                            echo $gioi_tinh_labels[$khach['gioi_tinh']] ?? 'Chưa xác định';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($khach['la_khach_chinh']): ?>
                                                <span class="badge bg-success">Người đặt</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Thành viên</span>
                                            <?php endif; ?>
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Lịch Sử Đặt Tour
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($lich_su_dat_tour)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="150">Mã booking</th>
                                            <th>Tour</th>
                                            <th width="120" class="text-center">Ngày đặt</th>
                                            <th width="150" class="text-center">Thời gian tour</th>
                                            <th width="120" class="text-center">Số khách</th>
                                            <th width="120" class="text-center">Tổng tiền</th>
                                            <th width="100" class="text-center">Trạng thái</th>
                                            <th width="120" class="text-center">Hướng dẫn viên</th>
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
                                                <small class="text-muted"><?php echo $tour['ma_tour']; ?></small>
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
                                                <span class="badge bg-primary"><?php echo $tour['tong_thanh_vien']; ?> người</span>
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
                                                <?php echo $tour['huong_dan_vien'] ? htmlspecialchars($tour['huong_dan_vien']) : '<span class="text-muted">Chưa phân công</span>'; ?>
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
                                <p class="text-muted small">Khách hàng chưa tham gia tour nào</p>
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

    .card {
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0;
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