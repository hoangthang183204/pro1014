<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Chi Tiết Đánh Giá</h2>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="?act=danh_gia_list">Đánh giá đã gửi</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check me-2"></i> 
                    Đánh giá tour: <?= htmlspecialchars($danhGia['ten_tour']) ?>
                </h5>
                <small class="opacity-75">
                    Ngày gửi: <?= date('d/m/Y H:i', strtotime($danhGia['created_at'])) ?> | 
                    Trạng thái: 
                    <?php if ($danhGia['trang_thai'] == 'submitted'): ?>
                        <span class="badge bg-info">Đã gửi</span>
                    <?php elseif ($danhGia['trang_thai'] == 'approved'): ?>
                        <span class="badge bg-success">Đã duyệt</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Nháp</span>
                    <?php endif; ?>
                </small>
            </div>
            
            <div class="card-body">
                <!-- THÔNG TIN TOUR -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-info">
                            <div class="card-header bg-info text-white py-2">
                                <small><i class="fas fa-calendar me-1"></i> Thời gian</small>
                            </div>
                            <div class="card-body p-3">
                                <p class="mb-1"><strong>Ngày đi:</strong> <?= date('d/m/Y', strtotime($danhGia['ngay_bat_dau'])) ?></p>
                                <p class="mb-0"><strong>Ngày về:</strong> <?= date('d/m/Y', strtotime($danhGia['ngay_ket_thuc'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white py-2">
                                <small><i class="fas fa-hotel me-1"></i> Khách sạn</small>
                            </div>
                            <div class="card-body p-3">
                                <p class="mb-1"><strong>Tên:</strong> <?= $danhGia['ten_khach_san'] ?? 'N/A' ?></p>
                                <p class="mb-0"><strong>Nhà CC:</strong> <?= $danhGia['nha_cung_cap_khach_san'] ?? '' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-warning">
                            <div class="card-header bg-warning text-white py-2">
                                <small><i class="fas fa-bus me-1"></i> Xe vận chuyển</small>
                            </div>
                            <div class="card-body p-3">
                                <p class="mb-1"><strong>Loại xe:</strong> <?= $danhGia['loai_xe'] ?? 'N/A' ?></p>
                                <p class="mb-0"><strong>Biển số:</strong> <?= $danhGia['bien_so'] ?? '' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ĐIỂM ĐÁNH GIÁ -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Tổng hợp điểm đánh giá</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="text-primary">Tổng quan</h5>
                                            <div class="fs-2 fw-bold text-warning">
                                                <?= str_repeat('★', $danhGia['diem_tong_quan']) ?>
                                            </div>
                                            <small class="text-muted"><?= $danhGia['diem_tong_quan'] ?>/5 điểm</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="text-primary">Khách sạn</h5>
                                            <div class="fs-2 fw-bold text-<?= $danhGia['diem_khach_san'] >= 4 ? 'success' : ($danhGia['diem_khach_san'] >= 3 ? 'warning' : 'danger') ?>">
                                                <?= str_repeat('★', $danhGia['diem_khach_san']) ?>
                                            </div>
                                            <small class="text-muted"><?= $danhGia['diem_khach_san'] ?>/5 điểm</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="text-primary">Nhà hàng</h5>
                                            <div class="fs-2 fw-bold text-<?= $danhGia['diem_nha_hang'] >= 4 ? 'success' : ($danhGia['diem_nha_hang'] >= 3 ? 'warning' : 'danger') ?>">
                                                <?= str_repeat('★', $danhGia['diem_nha_hang']) ?>
                                            </div>
                                            <small class="text-muted"><?= $danhGia['diem_nha_hang'] ?>/5 điểm</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="text-primary">Xe vận chuyển</h5>
                                            <div class="fs-2 fw-bold text-<?= $danhGia['diem_xe_van_chuyen'] >= 4 ? 'success' : ($danhGia['diem_xe_van_chuyen'] >= 3 ? 'warning' : 'danger') ?>">
                                                <?= str_repeat('★', $danhGia['diem_xe_van_chuyen']) ?>
                                            </div>
                                            <small class="text-muted"><?= $danhGia['diem_xe_van_chuyen'] ?>/5 điểm</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NHẬN XÉT CHI TIẾT -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-comment me-2"></i> Nhận xét tổng quan</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($danhGia['noi_dung_tong_quan']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($danhGia['noi_dung_tong_quan'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">Không có nhận xét</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Đề xuất cải thiện</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($danhGia['de_xuat_cai_thien']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($danhGia['de_xuat_cai_thien'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">Không có đề xuất</p>
                                <?php endif; ?>
                                
                                <hr>
                                <p class="mb-1"><strong>Đề xuất tiếp tục sử dụng:</strong></p>
                                <?php if ($danhGia['de_xuat_tiep_tuc_su_dung'] == 'co'): ?>
                                    <span class="badge bg-success p-2">
                                        <i class="fas fa-thumbs-up me-1"></i> Có, nên tiếp tục
                                    </span>
                                <?php elseif ($danhGia['de_xuat_tiep_tuc_su_dung'] == 'khong'): ?>
                                    <span class="badge bg-danger p-2">
                                        <i class="fas fa-thumbs-down me-1"></i> Không, không nên tiếp tục
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning p-2">
                                        <i class="fas fa-exclamation-circle me-1"></i> Có điều kiện
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NHẬN XÉT CHI TIẾT DỊCH VỤ -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-hotel me-1"></i> Khách sạn</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($danhGia['nhan_xet_khach_san']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_khach_san'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">Không có nhận xét</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-utensils me-1"></i> Nhà hàng</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($danhGia['nhan_xet_nha_hang']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_nha_hang'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">Không có nhận xét</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="fas fa-bus me-1"></i> Xe vận chuyển</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($danhGia['nhan_xet_xe_van_chuyen']): ?>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($danhGia['nhan_xet_xe_van_chuyen'])) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">Không có nhận xét</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light">
                <a href="?act=danh_gia_list" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card-header {
    border-bottom: none;
}
</style>

<?php include './views/layout/footer.php'; ?>