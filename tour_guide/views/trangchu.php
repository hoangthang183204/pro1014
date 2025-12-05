<!-- TRANG 1: DASHBOARD -->
 <?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div id="dashboard" class="page-content active">
    <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
    
    <!-- Thông tin HDV -->
    <?php if (isset($guideInfo) && !empty($guideInfo)): ?>
    <div class="guide-info mb-4 p-3 bg-light rounded">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <?php if (!empty($guideInfo['hinh_anh'])): ?>
                    <img src="<?= htmlspecialchars($guideInfo['hinh_anh']) ?>" 
                         alt="Avatar" 
                         class="rounded-circle me-3" 
                         style="width: 70px; height: 70px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 70px; background-color: var(--primary-color); color: white;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <h4 class="mb-1">Xin chào, <?= htmlspecialchars($guideInfo['ho_ten'] ?? 'Hướng dẫn viên') ?>!</h4>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-id-card"></i> 
                        <?= htmlspecialchars($guideInfo['loai_huong_dan_vien'] ?? 'Nội địa') ?> | 
                        <i class="fas fa-star text-warning"></i> 
                        Đánh giá: <?= number_format($guideInfo['danh_gia_trung_binh'] ?? 0, 1) ?>/5 |
                        <i class="fas fa-route"></i> 
                        <?= $guideInfo['so_tour_da_dan'] ?? 0 ?> tour
                    </p>
                </div>
            </div>
            <div class="text-end">
                <small class="text-muted d-block"><?= date('d/m/Y H:i') ?></small>
                <span class="badge bg-success">
                    <i class="fas fa-circle"></i> Đang hoạt động
                </span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Row 1: Thống kê chính -->

    
    <!-- Row 2: Tour sắp khởi hành và Sự cố -->
    <div class="row mb-4">
        <!-- Tour sắp khởi hành -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day text-primary me-2"></i>
                        Tour Sắp Khởi Hành
                    </h5>
                    <span class="badge bg-primary"><?= isset($tourSapKhoiHanh) ? count($tourSapKhoiHanh) : 0 ?> tour</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($tourSapKhoiHanh)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($tourSapKhoiHanh as $tour): ?>
                                <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh-detail&id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($tour['ten_tour'] ?? '') ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-day"></i> 
                                                <?= isset($tour['ngay_bat_dau']) ? date('d/m/Y', strtotime($tour['ngay_bat_dau'])) : '' ?> 
                                                - <?= isset($tour['ngay_ket_thuc']) ? date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) : '' ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="d-block">
                                                <i class="fas fa-users"></i> <?= $tour['so_khach'] ?? 0 ?> khách
                                            </small>
                                            <span class="badge bg-<?= ($tour['trang_thai'] ?? '') == 'đang đi' ? 'warning' : 'info' ?>">
                                                <?= $tour['trang_thai'] ?? '' ?>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Không có tour sắp khởi hành</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list"></i> Xem tất cả lịch trình
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sự cố cần xử lý -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-warning">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Sự Cố Cần Xử Lý
                    </h5>
                    <span class="badge bg-white text-warning"><?= isset($suCoCanXuLy) ? count($suCoCanXuLy) : 0 ?> sự cố</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($suCoCanXuLy)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($suCoCanXuLy as $suCo): ?>
                                <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky&lich_id=<?= $suCo['lich_khoi_hanh_id'] ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($suCo['tieu_de'] ?? '') ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-route"></i> <?= htmlspecialchars($suCo['ten_tour'] ?? '') ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <?php
                                            $mucDo = $suCo['muc_do_nghiem_trong'] ?? 'trung bình';
                                            $badgeClass = 'warning';
                                            if ($mucDo == 'nghiêm trọng' || $mucDo == 'cao') $badgeClass = 'danger';
                                            if ($mucDo == 'thấp') $badgeClass = 'success';
                                            ?>
                                            <span class="badge bg-<?= $badgeClass ?>">
                                                <?= ucfirst($mucDo) ?>
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                <?= isset($suCo['created_at']) ? date('d/m/Y', strtotime($suCo['created_at'])) : '' ?>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">Không có sự cố cần xử lý</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i> Quản lý nhật ký & sự cố
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Row 3: Hoạt động hôm nay và Checklist -->
    <div class="row mb-4">
        <!-- Hoạt động hôm nay -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check text-success me-2"></i>
                        Hoạt Động Hôm Nay
                    </h5>
                    <span class="badge bg-success"><?= date('d/m/Y') ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($lichTrinhHomNay)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($lichTrinhHomNay as $lich): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($lich['ten_tour'] ?? '') ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?= htmlspecialchars($lich['ten_chi_tiet_dia_diem'] ?? 'Hoạt động tour') ?>
                                            </p>
                                            <?php if (!empty($lich['mo_ta_hoat_dong'])): ?>
                                                <small class="text-muted">
                                                    <?= substr(htmlspecialchars($lich['mo_ta_hoat_dong']), 0, 80) ?>...
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Không có hoạt động trong ngày hôm nay</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Checklist chưa hoàn thành -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks text-info me-2"></i>
                        Checklist Chưa Hoàn Thành
                    </h5>
                    <span class="badge bg-info"><?= isset($checklistChuaHoanThanh) ? count($checklistChuaHoanThanh) : 0 ?> việc</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($checklistChuaHoanThanh)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($checklistChuaHoanThanh as $check): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($check['cong_viec'] ?? '') ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-route"></i> <?= htmlspecialchars($check['ten_tour'] ?? '') ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-warning d-block">
                                                <i class="fas fa-clock"></i> 
                                                <?= $check['so_ngay_con_lai'] ?? 0 ?> ngày nữa
                                            </small>
                                            <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh-detail&id=<?= $check['lich_khoi_hanh_id'] ?>" 
                                               class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-check"></i> Đánh dấu
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">Tất cả checklist đã hoàn thành</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Row 4: Đánh giá và Top khách hàng -->
    <div class="row mb-4">
        <!-- Đánh giá -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star text-warning me-2"></i>
                        Đánh Giá Của Bạn
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php if (isset($danhGia) && $danhGia['diem_trung_binh'] > 0): ?>
                        <div class="mb-4">
                            <div class="display-1 fw-bold text-warning">
                                <?= number_format($danhGia['diem_trung_binh'], 1) ?>
                                <small class="fs-4 text-muted">/5</small>
                            </div>
                            <div class="star-rating mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $danhGia['so_sao_tron']): ?>
                                        <i class="fas fa-star fa-2x text-warning"></i>
                                    <?php elseif ($danhGia['co_nua_sao'] && $i == $danhGia['so_sao_tron'] + 1): ?>
                                        <i class="fas fa-star-half-alt fa-2x text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star fa-2x text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-warning" 
                                     style="width: <?= $danhGia['phan_tram'] ?>%"></div>
                            </div>
                            <p class="text-muted mb-0">
                                Dựa trên <?= $danhGia['so_tour'] ?> tour đã dẫn
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Chưa có đánh giá</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Top khách hàng -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-crown text-purple me-2"></i>
                        Top Khách Hàng Thân Thiết
                    </h5>
                    <span class="badge bg-purple">TOP 3</span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($topKhachHang)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($topKhachHang as $index => $khach): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-circle me-3 bg-purple text-white">
                                                <?= $index + 1 ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($khach['ho_ten'] ?? '') ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> <?= htmlspecialchars($khach['so_dien_thoai'] ?? '') ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-success d-block">
                                                <i class="fas fa-shopping-cart"></i> 
                                                <?= $khach['so_lan_dat'] ?? 0 ?> tour
                                            </small>
                                            <small class="text-primary">
                                                <?= isset($khach['tong_chi_tieu']) ? number_format($khach['tong_chi_tieu'], 0, ',', '.') . 'đ' : '0đ' ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Chưa có thông tin khách hàng</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './views/layout/footer.php'; ?>
<style>
:root {
    --primary-color: #3498db;
    --success-color: #2ecc71;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #17a2b8;
    --purple-color: #9b59b6;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    background: white;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stat-card {
    border: none;
    border-radius: 8px;
    color: white;
}

.stat-card .card-body {
    padding: 15px;
}

.stat-card i {
    opacity: 0.8;
}

.bg-purple {
    background-color: var(--purple-color) !important;
}

.text-purple {
    color: var(--purple-color) !important;
}

.rank-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
}

.star-rating {
    display: inline-flex;
    gap: 5px;
}

.progress {
    background-color: #e9ecef;
    border-radius: 5px;
}

.progress-bar {
    border-radius: 5px;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e0e0e0;
    padding: 15px;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    padding: 10px 15px;
}

.page-title {
    color: #2c3e50;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.guide-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .row > div {
        margin-bottom: 15px;
    }
    
    .display-1 {
        font-size: 3rem;
    }
    
    .star-rating .fa-2x {
        font-size: 1.5rem;
    }
}
</style>