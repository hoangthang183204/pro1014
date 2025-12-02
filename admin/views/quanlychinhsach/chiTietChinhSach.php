<?php
// File: views/quanlychinhsach/view.php
require './views/layout/header.php';
include './views/layout/navbar.php';
include './views/layout/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            
            <!-- Nút quay lại -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="?act=chinh-sach" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

            <!-- Thông tin chính sách -->
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        <?php echo htmlspecialchars($chinh_sach['ten_chinh_sach']); ?>
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Thông tin chung -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ID:</strong> <?php echo $chinh_sach['id']; ?></p>
                                <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($chinh_sach['created_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Số tour đang sử dụng:</strong> 
                                    <?php echo $tour_count; ?> tour
                                </p>
                                <?php if (!empty($chinh_sach['updated_at'])): ?>
                                    <p><strong>Ngày cập nhật:</strong> <?php echo date('d/m/Y H:i', strtotime($chinh_sach['updated_at'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quy định hủy/đổi -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-exchange-alt me-2 text-warning"></i>
                            Quy định hủy/đổi tour
                        </h5>
                        <?php if (!empty($chinh_sach['quy_dinh_huy_doi'])): ?>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($chinh_sach['quy_dinh_huy_doi'])); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><em>Chưa có quy định hủy/đổi</em></p>
                        <?php endif; ?>
                    </div>

                    <!-- Lưu ý sức khỏe -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-heartbeat me-2 text-danger"></i>
                            Lưu ý về sức khỏe
                        </h5>
                        <?php if (!empty($chinh_sach['luu_y_suc_khoe'])): ?>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_suc_khoe'])); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><em>Chưa có lưu ý về sức khỏe</em></p>
                        <?php endif; ?>
                    </div>

                    <!-- Lưu ý hành lý -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-suitcase me-2 text-info"></i>
                            Lưu ý về hành lý
                        </h5>
                        <?php if (!empty($chinh_sach['luu_y_hanh_ly'])): ?>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_hanh_ly'])); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><em>Chưa có lưu ý về hành lý</em></p>
                        <?php endif; ?>
                    </div>

                    <!-- Lưu ý khác -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-sticky-note me-2 text-success"></i>
                            Lưu ý khác
                        </h5>
                        <?php if (!empty($chinh_sach['luu_y_khac'])): ?>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_khac'])); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><em>Không có lưu ý khác</em></p>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="far fa-clock me-1"></i>
                        Được tạo vào: <?php echo date('d/m/Y H:i:s', strtotime($chinh_sach['created_at'])); ?>
                    </small>
                </div>
            </div>

        </div>
    </section>
</div>

<?php require './views/layout/footer.php'; ?>