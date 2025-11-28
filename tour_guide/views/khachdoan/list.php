<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark">Danh Sách Hành Khách</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="?act=xem_danh_sach_khach">Chọn Tour</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="fas fa-bus me-2"></i><?= htmlspecialchars($tourInfo['ten_tour'] ?? 'Thông tin tour') ?></h5>
                    <small class="opacity-75"><i class="far fa-clock me-1"></i> Ngày đi: <?= $tourInfo['ngay_di'] ?? 'N/A' ?></small>
                </div>
                <a href="?act=xem_danh_sach_khach" class="btn btn-sm btn-light text-primary fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Đổi Tour
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="px-4 py-3">STT</th>
                                <th class="px-4 py-3">Họ và Tên</th>
                                <th class="px-4 py-3">Thông tin</th>
                                <th class="px-4 py-3">Nhóm / Mã Vé</th>
                                <th class="px-4 py-3">Liên hệ (Người đặt)</th>
                                <th class="px-4 py-3">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dsKhach)): ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-user-slash fa-2x mb-3"></i><br>Chưa có khách trong danh sách.</td></tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($dsKhach as $k): ?>
                                    <tr>
                                        <td class="px-4"><?= $i++ ?></td>
                                        <td class="px-4">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($k['ho_ten']) ?></div>
                                        </td>
                                        <td class="px-4">
                                            <span class="badge bg-light text-dark border"><?= $k['gioi_tinh'] ?></span>
                                            <?php if($k['tuoi']): ?>
                                                <span class="ms-1 text-muted small"><?= $k['tuoi'] ?> tuổi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4">
                                            <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                                <?= htmlspecialchars($k['nhom']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4">
                                            <a href="tel:<?= $k['sdt'] ?>" class="text-decoration-none fw-bold text-primary">
                                                <i class="fas fa-phone-alt me-1"></i><?= $k['sdt'] ?>
                                            </a>
                                            <div class="small text-muted fst-italic mt-1">
                                                Người đặt: <?= htmlspecialchars($k['nguoi_dat']) ?>
                                            </div>
                                        </td>
                                        <td class="px-4">
                                            <?php if ($k['ghi_chu']): ?>
                                                <div class="text-danger small fw-bold bg-danger bg-opacity-10 p-2 rounded">
                                                    <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($k['ghi_chu']) ?>
                                                </div>
                                            <?php else: ?> 
                                                <span class="text-muted">-</span> 
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted small">
                <i class="fas fa-info-circle me-1"></i> Danh sách hiển thị những khách hàng đã đặt cọc hoặc thanh toán hoàn tất.
            </div>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>