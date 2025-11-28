<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"> Chọn Tour Cần Xem</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Chọn Tour</li>
                </ol>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title text-primary mb-0"><i class="fas fa-filter me-2"></i>Danh sách Tour bạn đang phụ trách</h5>
            </div>
            <div class="card-body">
                <?php if (empty($myTours)): ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i> Bạn hiện chưa được phân công tour nào.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($myTours as $tour): ?>
                            <a href="?act=xem_danh_sach_khach&id=<?= $tour['id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 mb-2 border rounded shadow-sm hover-effect">
                                <div>
                                    <h5 class="mb-1 fw-bold text-primary"><?= htmlspecialchars($tour['ten_tour']) ?></h5>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> 
                                        Khởi hành: <span class="fw-bold"><?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?></span>
                                    </small>
                                </div>
                                <span class="badge rounded-pill bg-<?= $tour['trang_thai'] == 'đang diễn ra' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($tour['trang_thai']) ?> <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>