<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Đánh Giá Đã Gửi</h2>
            </div>
            <div class="col-sm-6 text-end">
                <a href="?act=danh_gia" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Đánh giá tour mới
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-list-check me-2"></i> Danh sách đánh giá đã gửi</h5>
            </div>
            <div class="card-body">
                <?php if (empty($danhGiaList)): ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        Bạn chưa gửi đánh giá nào. 
                        <a href="?act=danh_gia" class="alert-link">Đánh giá ngay</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tour</th>
                                    <th>Ngày gửi</th>
                                    <th>Điểm tổng</th>
                                    <th>Khách sạn</th>
                                    <th>Nhà hàng</th>
                                    <th>Xe</th>
                                    <th>Đề xuất</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danhGiaList as $dg): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary"><?= htmlspecialchars($dg['ten_tour']) ?></strong><br>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($dg['ngay_bat_dau'])) ?></small>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($dg['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-warning text-dark fs-6">
                                            <?= str_repeat('★', $dg['diem_tong_quan']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $dg['diem_khach_san'] >= 4 ? 'success' : ($dg['diem_khach_san'] >= 3 ? 'warning' : 'danger') ?>">
                                            <?= $dg['diem_khach_san'] ?>★
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $dg['diem_nha_hang'] >= 4 ? 'success' : ($dg['diem_nha_hang'] >= 3 ? 'warning' : 'danger') ?>">
                                            <?= $dg['diem_nha_hang'] ?>★
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $dg['diem_xe_van_chuyen'] >= 4 ? 'success' : ($dg['diem_xe_van_chuyen'] >= 3 ? 'warning' : 'danger') ?>">
                                            <?= $dg['diem_xe_van_chuyen'] ?>★
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($dg['de_xuat_tiep_tuc_su_dung'] == 'co'): ?>
                                            <span class="badge bg-success">Nên tiếp tục</span>
                                        <?php elseif ($dg['de_xuat_tiep_tuc_su_dung'] == 'khong'): ?>
                                            <span class="badge bg-danger">Không nên</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Có điều kiện</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dg['trang_thai'] == 'submitted'): ?>
                                            <span class="badge bg-info">Đã gửi</span>
                                        <?php elseif ($dg['trang_thai'] == 'approved'): ?>
                                            <span class="badge bg-success">Đã duyệt</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="?act=danh_gia_detail&id=<?= $dg['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                                        </a>
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