<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Đánh Giá Tour</h2>
            </div>
            <div class="col-sm-6 text-end">
                <a href="?act=danh_gia_list" class="btn btn-outline-primary">
                    <i class="fas fa-list me-1"></i> Xem đánh giá đã gửi
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i> Danh sách tour đã hoàn thành (cần đánh giá)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tours)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Hiện không có tour nào cần đánh giá.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tour</th>
                                    <th>Ngày đi</th>
                                    <th>Ngày về</th>
                                    <th>Số ngày</th>
                                    <th>Loại tour</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tours as $tour): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary"><?= htmlspecialchars($tour['ten_tour']) ?></strong>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?></td>
                                    <td><?= $tour['so_ngay'] ?> ngày</td>
                                    <td>
                                        <?php if ($tour['loai_tour'] == 'trong nước'): ?>
                                            <span class="badge bg-primary">Trong nước</span>
                                        <?php elseif ($tour['loai_tour'] == 'quốc tế'): ?>
                                            <span class="badge bg-success">Quốc tế</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $tour['loai_tour'] ?? 'Tiêu chuẩn' ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($tour['da_danh_gia'] > 0): ?>
                                            <span class="badge bg-success">Đã đánh giá</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Chưa đánh giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tour['da_danh_gia'] == 0): ?>
                                            <a href="?act=danh_gia_create&id=<?= $tour['id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i> Đánh giá ngay
                                            </a>
                                        <?php else: ?>
                                            <a href="?act=danh_gia_detail&id=<?= $tour['da_danh_gia'] ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye me-1"></i> Xem đánh giá
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>