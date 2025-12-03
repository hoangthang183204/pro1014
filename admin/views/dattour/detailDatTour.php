<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-eye me-2"></i>
                        Chi Tiết Đặt Tour
                    </a>
                    <a href="?act=dat-tour" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <?php if ($dat_tour): ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Thông tin chung -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Thông Tin Đặt Tour</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã đặt tour:</strong> <?php echo $dat_tour['ma_dat_tour']; ?></p>
                                            <p><strong>Tour:</strong> <?php echo $dat_tour['ten_tour']; ?></p>
                                            <p><strong>Ngày đi:</strong> <?php echo date('d/m/Y', strtotime($dat_tour['ngay_bat_dau'])); ?></p>
                                            <p><strong>Ngày kết thúc:</strong> <?php echo date('d/m/Y', strtotime($dat_tour['ngay_ket_thuc'])); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Trạng thái:</strong> 
                                                <span class="badge bg-<?php 
                                                    switch($dat_tour['trang_thai']) {
                                                        case 'đã thanh toán': echo 'success'; break;
                                                        case 'giữ chỗ': echo 'info'; break;
                                                        case 'chưa thanh toán': echo 'warning'; break;
                                                        case 'hủy': echo 'danger'; break;
                                                        default: echo 'secondary';
                                                    }
                                                ?>">
                                                    <?php echo $dat_tour['trang_thai']; ?>
                                                </span>
                                            </p>
                                            <p><strong>Số khách:</strong> <?php echo $dat_tour['so_luong_khach']; ?></p>
                                            <p><strong>Tổng tiền:</strong> 
                                                <span class="text-success fw-bold">
                                                    <?php echo number_format($dat_tour['tong_tien'], 0, ',', '.'); ?> VNĐ
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lịch trình tour -->
                            <?php if (!empty($lich_trinh_tour)): ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>Lịch Trình Tour
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <?php foreach ($lich_trinh_tour as $index => $lich): ?>
                                            <div class="timeline-item mb-4">
                                                <div class="timeline-marker bg-primary"></div>
                                                <div class="timeline-content">
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">
                                                                <strong>Ngày <?php echo $lich['so_ngay']; ?>:</strong> 
                                                                <?php echo htmlspecialchars($lich['tieu_de']); ?>
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p><strong>Hoạt động:</strong><br>
                                                                <?php echo nl2br(htmlspecialchars($lich['mo_ta_hoat_dong'])); ?>
                                                            </p>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><strong><i class="fas fa-bed me-1"></i>Chỗ ở:</strong><br>
                                                                        <?php echo htmlspecialchars($lich['cho_o']); ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><strong><i class="fas fa-utensils me-1"></i>Bữa ăn:</strong><br>
                                                                        <?php echo htmlspecialchars($lich['bua_an']); ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty($lich['phuong_tien'])): ?>
                                                            <p><strong><i class="fas fa-bus me-1"></i>Phương tiện:</strong><br>
                                                                <?php echo htmlspecialchars($lich['phuong_tien']); ?>
                                                            </p>
                                                            <?php endif; ?>
                                                            <?php if (!empty($lich['ghi_chu_hdv'])): ?>
                                                            <div class="alert alert-info mt-2">
                                                                <strong><i class="fas fa-info-circle me-1"></i>Ghi chú HDV:</strong><br>
                                                                <?php echo nl2br(htmlspecialchars($lich['ghi_chu_hdv'])); ?>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có thông tin lịch trình cho tour này.</p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Danh sách khách hàng -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users me-2"></i>Danh Sách Khách Hàng (<?php echo count($all_khach_hang); ?> người)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Họ Tên</th>
                                                    <th>SĐT</th>
                                                    <th>CCCD</th>
                                                    <th>Ngày Sinh</th>
                                                    <th>Giới Tính</th>
                                                    <th>Ghi Chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($all_khach_hang as $index => $khach): ?>
                                                    <tr>
                                                        <td><?php echo $index + 1; ?></td>
                                                        <td><?php echo htmlspecialchars($khach['ho_ten']); ?></td>
                                                        <td><?php echo htmlspecialchars($khach['so_dien_thoai']); ?></td>
                                                        <td><?php echo htmlspecialchars($khach['cccd']); ?></td>
                                                        <td><?php echo $khach['ngay_sinh'] ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : ''; ?></td>
                                                        <td><?php echo $khach['gioi_tinh']; ?></td>
                                                        <td><?php echo htmlspecialchars($khach['ghi_chu']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Thông tin tour -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Tour</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Mã tour:</strong> <?php echo $dat_tour['ma_tour']; ?></p>
                                    <p><strong>Giá tour/khách:</strong> 
                                        <span class="text-primary fw-bold">
                                            <?php echo number_format($dat_tour['gia_tour'], 0, ',', '.'); ?> VNĐ
                                        </span>
                                    </p>
                                    <p><strong>Giờ tập trung:</strong> <?php echo date('H:i', strtotime($dat_tour['gio_tap_trung'])); ?></p>
                                    <p><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($dat_tour['diem_tap_trung']); ?></p>
                                </div>
                            </div>

                            <!-- Thao tác -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>Thao Tác</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="?act=dat-tour-print&id=<?php echo $dat_tour['id']; ?>" class="btn btn-info">
                                            <i class="fas fa-print me-1"></i> In Hóa Đơn
                                        </a>
                                        
                                        <!-- Cập nhật trạng thái -->
                                        <form method="POST" action="?act=dat-tour-update-status" class="mt-3">
                                            <input type="hidden" name="id" value="<?php echo $dat_tour['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Cập nhật trạng thái</label>
                                                <select name="trang_thai" class="form-control">
                                                    <option value="chưa thanh toán" <?php echo $dat_tour['trang_thai'] == 'chưa thanh toán' ? 'selected' : ''; ?>>chưa thanh toán</option>
                                                    <option value="giữ chỗ" <?php echo $dat_tour['trang_thai'] == 'giữ chỗ' ? 'selected' : ''; ?>>giữ chỗ</option>
                                                    <option value="đã thanh toán" <?php echo $dat_tour['trang_thai'] == 'đã thanh toán' ? 'selected' : ''; ?>>đã thanh toán</option>
                                                    <option value="hủy" <?php echo $dat_tour['trang_thai'] == 'hủy' ? 'selected' : ''; ?>>Hủy</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">Không tìm thấy thông tin đặt tour!</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    top: 10px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    z-index: 1;
}

.timeline-content {
    margin-left: 10px;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>

<?php include 'views/layout/footer.php'; ?>