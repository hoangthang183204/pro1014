<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Đánh Giá Đã Gửi</h2>
                <p class="text-muted mt-1">Danh sách các đánh giá tour bạn đã gửi</p>
            </div>
            <div class="col-sm-6 text-end">
                <a href="?act=danh_gia" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Đánh giá tour mới
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (empty($danhGiaList)): ?>
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Chưa có đánh giá nào</h4>
                    <p class="text-muted mb-4">Bạn chưa gửi đánh giá nào cho các tour đã hoàn thành.</p>
                    <a href="?act=danh_gia" class="btn btn-primary">
                        <i class="fas fa-star me-1"></i> Đánh giá tour ngay
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="200">Tour</th>
                                    <th width="120">Ngày đi</th>
                                    <th width="120">Ngày về</th>
                                    <th width="100">Tổng quan</th>
                                    <th width="100">Khách sạn</th>
                                    <th width="100">Nhà hàng</th>
                                    <th width="120">Xe vận chuyển</th>
                                    <th width="120">Đề xuất</th>
                                    <th width="100" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($danhGiaList as $dg): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary"><?= htmlspecialchars($dg['ten_tour'] ?? '') ?></strong>
                                    </td>
                                    <td><?= isset($dg['ngay_bat_dau']) ? date('d/m/Y', strtotime($dg['ngay_bat_dau'])) : 'N/A' ?></td>
                                    <td><?= isset($dg['ngay_ket_thuc']) ? date('d/m/Y', strtotime($dg['ngay_ket_thuc'])) : 'N/A' ?></td>
                                    <td>
                                        <span class="badge bg-warning text-dark fs-6 px-2 py-1 d-inline-flex align-items-center">
                                            <span class="me-1"><?= str_repeat('★', $dg['diem_tong_quan'] ?? 0) ?></span>
                                            <span class="fw-bold">(<?= $dg['diem_tong_quan'] ?? 0 ?>)</span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge fs-6 px-2 py-1 d-inline-flex align-items-center
                                            <?= ($dg['diem_khach_san'] ?? 0) >= 4 ? 'bg-success text-white' : 
                                               (($dg['diem_khach_san'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                                            <span class="me-1"><?= str_repeat('★', $dg['diem_khach_san'] ?? 0) ?></span>
                                            <span class="fw-bold">(<?= $dg['diem_khach_san'] ?? 0 ?>)</span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge fs-6 px-2 py-1 d-inline-flex align-items-center
                                            <?= ($dg['diem_nha_hang'] ?? 0) >= 4 ? 'bg-success text-white' : 
                                               (($dg['diem_nha_hang'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                                            <span class="me-1"><?= str_repeat('★', $dg['diem_nha_hang'] ?? 0) ?></span>
                                            <span class="fw-bold">(<?= $dg['diem_nha_hang'] ?? 0 ?>)</span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge fs-6 px-2 py-1 d-inline-flex align-items-center
                                            <?= ($dg['diem_xe_van_chuyen'] ?? 0) >= 4 ? 'bg-success text-white' : 
                                               (($dg['diem_xe_van_chuyen'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                                            <span class="me-1"><?= str_repeat('★', $dg['diem_xe_van_chuyen'] ?? 0) ?></span>
                                            <span class="fw-bold">(<?= $dg['diem_xe_van_chuyen'] ?? 0 ?>)</span>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (isset($dg['de_xuat_tiep_tuc_su_dung'])): ?>
                                            <?php if ($dg['de_xuat_tiep_tuc_su_dung'] == 'co'): ?>
                                                <span class="badge bg-success px-3 py-1">
                                                    <i class="fas fa-thumbs-up me-1"></i> Nên tiếp tục
                                                </span>
                                            <?php elseif ($dg['de_xuat_tiep_tuc_su_dung'] == 'khong'): ?>
                                                <span class="badge bg-danger px-3 py-1">
                                                    <i class="fas fa-thumbs-down me-1"></i> Không nên
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark px-3 py-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i> Có điều kiện
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-3 py-1">Chưa có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="?act=danh_gia_detail&id=<?= $dg['id'] ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">
                                <i class="fas fa-info-circle me-1"></i> 
                                Hiển thị <strong><?= count($danhGiaList) ?></strong> đánh giá
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="?act=danh_gia" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách tour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Custom CSS cho trang đánh giá */
.badge {
    font-weight: 500;
    min-width: 90px;
    justify-content: center;
}

.table th {
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    padding: 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.card {
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 1rem 1.25rem;
}

.card-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.125);
    padding: 1rem 1.25rem;
}

/* Style cho sao đánh giá */
.fa-star {
    color: #ffc107;
}

.bg-warning .fa-star {
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .badge {
        min-width: 70px;
        font-size: 0.8rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Animation cho badge */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Custom colors for ratings */
.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #dc3545, #e83e8c) !important;
}

.bg-primary {
    background: linear-gradient(135deg, #007bff, #6610f2) !important;
}

/* Hover effect for rows */
.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f1f8ff;
    transform: scale(1.002);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>

<?php include './views/layout/footer.php'; ?>