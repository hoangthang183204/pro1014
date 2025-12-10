<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Chi Tiết Đánh Giá</h2>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="?act=danh_gia">Tour cần đánh giá</a></li>
                        <li class="breadcrumb-item"><a href="?act=danh_gia_list">Đánh giá đã gửi</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> 
                    <?= htmlspecialchars($danhGia['ten_tour'] ?? 'Chưa có thông tin') ?>
                </h5>
                <div class="d-flex flex-wrap mt-2">
                    <span class="badge bg-light text-dark me-3">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <?= isset($danhGia['ngay_bat_dau']) ? date('d/m/Y', strtotime($danhGia['ngay_bat_dau'])) : 'N/A' ?> - 
                        <?= isset($danhGia['ngay_ket_thuc']) ? date('d/m/Y', strtotime($danhGia['ngay_ket_thuc'])) : 'N/A' ?>
                    </span>
                    <?php if (isset($danhGia['loai_tour'])): ?>
                        <span class="badge bg-info me-3">
                            <i class="fas fa-tag me-1"></i> <?= $danhGia['loai_tour'] ?>
                        </span>
                    <?php endif; ?>
                    <span class="badge bg-secondary">
                        <i class="fas fa-clock me-1"></i>
                        <?= isset($danhGia['ngay_danh_gia']) ? date('d/m/Y H:i', strtotime($danhGia['ngay_danh_gia'])) : date('d/m/Y H:i') ?>
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- TỔNG QUAN ĐIỂM SỐ -->
                <div class="row mb-5">
                    <div class="col-md-3 mb-3">
                        <div class="p-4 border rounded text-center bg-light h-100">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Tổng quan
                            </h5>
                            <div class="display-4 fw-bold text-warning mb-2">
                                <?= number_format($danhGia['diem_so'] ?? 0, 1) ?>
                            </div>
                            <div class="fs-5 text-warning mb-2">
                                <?= str_repeat('★', floor($danhGia['diem_tong_quan'] ?? 0)) ?>
                                <?= ($danhGia['diem_tong_quan'] ?? 0) - floor($danhGia['diem_tong_quan'] ?? 0) >= 0.5 ? '★' : '' ?>
                            </div>
                            <small class="text-muted">(<?= $danhGia['diem_tong_quan'] ?? 0 ?>/5 điểm)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="p-4 border rounded text-center h-100
                            <?= ($danhGia['diem_khach_san'] ?? 0) >= 4 ? 'bg-success text-white' : 
                               (($danhGia['diem_khach_san'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                            <h5 class="mb-3">
                                <i class="fas fa-hotel me-2"></i>Khách sạn
                            </h5>
                            <div class="display-4 fw-bold mb-2">
                                <?= $danhGia['diem_khach_san'] ?? 0 ?>
                            </div>
                            <div class="fs-5 mb-2">
                                <?= str_repeat('★', $danhGia['diem_khach_san'] ?? 0) ?>
                            </div>
                            <small>(<?= $danhGia['diem_khach_san'] ?? 0 ?>/5 điểm)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="p-4 border rounded text-center h-100
                            <?= ($danhGia['diem_nha_hang'] ?? 0) >= 4 ? 'bg-success text-white' : 
                               (($danhGia['diem_nha_hang'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                            <h5 class="mb-3">
                                <i class="fas fa-utensils me-2"></i>Nhà hàng
                            </h5>
                            <div class="display-4 fw-bold mb-2">
                                <?= $danhGia['diem_nha_hang'] ?? 0 ?>
                            </div>
                            <div class="fs-5 mb-2">
                                <?= str_repeat('★', $danhGia['diem_nha_hang'] ?? 0) ?>
                            </div>
                            <small>(<?= $danhGia['diem_nha_hang'] ?? 0 ?>/5 điểm)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="p-4 border rounded text-center h-100
                            <?= ($danhGia['diem_xe_van_chuyen'] ?? 0) >= 4 ? 'bg-success text-white' : 
                               (($danhGia['diem_xe_van_chuyen'] ?? 0) >= 3 ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                            <h5 class="mb-3">
                                <i class="fas fa-bus me-2"></i>Xe vận chuyển
                            </h5>
                            <div class="display-4 fw-bold mb-2">
                                <?= $danhGia['diem_xe_van_chuyen'] ?? 0 ?>
                            </div>
                            <div class="fs-5 mb-2">
                                <?= str_repeat('★', $danhGia['diem_xe_van_chuyen'] ?? 0) ?>
                            </div>
                            <small>(<?= $danhGia['diem_xe_van_chuyen'] ?? 0 ?>/5 điểm)</small>
                        </div>
                    </div>
                </div>

                <!-- CHI TIẾT ĐÁNH GIÁ -->
                <div class="row">
                    <!-- Nhận xét tổng quan -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment me-2"></i>Nhận xét tổng quan
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($danhGia['noi_dung_tong_quan'])): ?>
                                    <div class="p-3 bg-light rounded">
                                        <?= nl2br(htmlspecialchars($danhGia['noi_dung_tong_quan'])) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center py-3">
                                        <i class="fas fa-info-circle me-2"></i>Không có nhận xét
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Nhận xét chi tiết các dịch vụ -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>Nhận xét chi tiết
                                </h6>
                            </div>
                            <div class="card-body">
                                <!-- Khách sạn -->
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="fas fa-hotel me-2"></i>Khách sạn
                                    </h6>
                                    <?php if (!empty($danhGia['nhan_xet_khach_san'])): ?>
                                        <p class="mb-1"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_khach_san'])) ?></p>
                                    <?php else: ?>
                                        <p class="text-muted mb-1">Không có nhận xét</p>
                                    <?php endif; ?>
                                    <?php if (!empty($danhGia['nha_cung_cap_khach_san'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-building me-1"></i>
                                            Nhà cung cấp: <?= htmlspecialchars($danhGia['nha_cung_cap_khach_san']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Nhà hàng -->
                                <div class="mb-3">
                                    <h6 class="text-success">
                                        <i class="fas fa-utensils me-2"></i>Nhà hàng
                                    </h6>
                                    <?php if (!empty($danhGia['nhan_xet_nha_hang'])): ?>
                                        <p class="mb-1"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_nha_hang'])) ?></p>
                                    <?php else: ?>
                                        <p class="text-muted mb-1">Không có nhận xét</p>
                                    <?php endif; ?>
                                    <?php if (!empty($danhGia['nha_cung_cap_nha_hang'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-building me-1"></i>
                                            Nhà cung cấp: <?= htmlspecialchars($danhGia['nha_cung_cap_nha_hang']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Xe vận chuyển -->
                                <div class="mb-3">
                                    <h6 class="text-danger">
                                        <i class="fas fa-bus me-2"></i>Xe vận chuyển
                                    </h6>
                                    <?php if (!empty($danhGia['nhan_xet_xe_van_chuyen'])): ?>
                                        <p class="mb-1"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_xe_van_chuyen'])) ?></p>
                                    <?php else: ?>
                                        <p class="text-muted mb-1">Không có nhận xét</p>
                                    <?php endif; ?>
                                    <?php if (!empty($danhGia['nha_cung_cap_xe'])): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-building me-1"></i>
                                            Nhà cung cấp: <?= htmlspecialchars($danhGia['nha_cung_cap_xe']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ĐỀ XUẤT VÀ GÓP Ý -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>Đề xuất cải thiện
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($danhGia['de_xuat_cai_thien'])): ?>
                                    <div class="p-3 bg-light rounded">
                                        <?= nl2br(htmlspecialchars($danhGia['de_xuat_cai_thien'])) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center py-3">
                                        <i class="fas fa-info-circle me-2"></i>Không có đề xuất
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-thumbs-up me-2"></i>Kết luận & Đề xuất
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <!-- Dịch vụ bổ sung -->
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Dịch vụ bổ sung:</h6>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="badge bg-secondary fs-6 px-3 py-2 me-3">
                                            <?= $danhGia['diem_dich_vu_bo_sung'] ?? 0 ?>/5 điểm
                                        </div>
                                        <div class="text-warning fs-5">
                                            <?= str_repeat('★', $danhGia['diem_dich_vu_bo_sung'] ?? 0) ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($danhGia['nhan_xet_dich_vu_bo_sung'])): ?>
                                        <p class="mt-2 mb-0">
                                            <small><?= nl2br(htmlspecialchars($danhGia['nhan_xet_dich_vu_bo_sung'])) ?></small>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Đề xuất tiếp tục sử dụng -->
                                <div>
                                    <h6 class="text-muted mb-3">Đề xuất tiếp tục sử dụng:</h6>
                                    <?php if (isset($danhGia['de_xuat_tiep_tuc_su_dung'])): ?>
                                        <?php if ($danhGia['de_xuat_tiep_tuc_su_dung'] == 'co'): ?>
                                            <div class="alert alert-success py-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-thumbs-up fa-2x me-3"></i>
                                                    <div>
                                                        <h5 class="mb-1">Nên tiếp tục sử dụng</h5>
                                                        <p class="mb-0 small">Dịch vụ đạt yêu cầu và nên tiếp tục hợp tác</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php elseif ($danhGia['de_xuat_tiep_tuc_su_dung'] == 'khong'): ?>
                                            <div class="alert alert-danger py-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-thumbs-down fa-2x me-3"></i>
                                                    <div>
                                                        <h5 class="mb-1">Không nên tiếp tục</h5>
                                                        <p class="mb-0 small">Không đạt yêu cầu, nên thay thế nhà cung cấp</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning py-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                                    <div>
                                                        <h5 class="mb-1">Có điều kiện</h5>
                                                        <p class="mb-0 small">Cần cải thiện một số điểm trước khi tiếp tục hợp tác</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Chưa có đề xuất</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="?act=danh_gia_list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                    </a>
                    <div class="text-muted small">
                        <i class="fas fa-id-card me-1"></i>Mã đánh giá: #<?= $danhGia['id'] ?? 'N/A' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom CSS cho trang chi tiết */
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.display-4 {
    font-size: 3rem;
    font-weight: 700;
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.alert {
    border-radius: 8px;
    border: none;
}

.fa-star {
    color: #ffc107;
}

.text-warning .fa-star {
    color: inherit;
}

.bg-success .fa-star,
.bg-danger .fa-star,
.bg-warning .fa-star {
    color: white;
}

/* Custom card header gradients */
.card-header.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

.card-header.bg-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
}

.card-header.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .col-md-3, .col-md-6 {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* Animation cho các card điểm số */
.col-md-3 .card {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom scrollbar cho các phần nội dung dài */
.card-body {
    max-height: 500px;
    overflow-y: auto;
}

.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Style cho breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-item a:hover {
    color: #007bff;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 500;
}
</style>

<?php include './views/layout/footer.php'; ?>