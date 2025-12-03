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
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Trạng thái:</strong> 
                                                <span class="badge bg-<?php 
                                                    switch($dat_tour['trang_thai']) {
                                                        case 'hoàn tất': echo 'success'; break;
                                                        case 'đã cọc': echo 'info'; break;
                                                        case 'chờ xác nhận': echo 'warning'; break;
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

                            <!-- Danh sách khách hàng -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        Danh Sách Khách Hàng (<?php echo count($all_khach_hang); ?> người)
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
                            <!-- Thao tác -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Thao Tác</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <!-- <a href="?act=dat-tour-edit&id=<?php echo $dat_tour['id']; ?>" class="btn btn-warning">
                                            <i class="fas fa-edit me-1"></i> Sửa
                                        </a> -->
                                        <a href="?act=dat-tour-print&id=<?php echo $dat_tour['id']; ?>" class="btn btn-info">
                                            <i class="fas fa-print me-1"></i> In Hóa Đơn
                                        </a>
                                        
                                        <!-- Cập nhật trạng thái -->
                                        <form method="POST" action="?act=dat-tour-update-status" class="mt-3">
                                            <input type="hidden" name="id" value="<?php echo $dat_tour['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Cập nhật trạng thái</label>
                                                <select name="trang_thai" class="form-control">
                                                    <option value="chờ xác nhận" <?php echo $dat_tour['trang_thai'] == 'chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                                    <option value="đã cọc" <?php echo $dat_tour['trang_thai'] == 'đã cọc' ? 'selected' : ''; ?>>Đã cọc</option>
                                                    <option value="hoàn tất" <?php echo $dat_tour['trang_thai'] == 'hoàn tất' ? 'selected' : ''; ?>>Hoàn tất</option>
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

<?php include 'views/layout/footer.php'; ?>