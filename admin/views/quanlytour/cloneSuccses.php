<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Clone Tour Thành Công!
                </a>
                <div>
                    <a href="?act=tour" class="btn btn-outline-light me-2">
                        <i class="fas fa-arrow-left me-1"></i> Về Danh sách Tour
                    </a>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">
                            <?php echo htmlspecialchars($_SESSION['success']); ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="flex-grow-1">
                            <?php echo htmlspecialchars($_SESSION['error']); ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <!-- Success Card -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Tour mới đã được tạo thành công
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="callout callout-success">
                                <h4><i class="fas fa-suitcase-rolling me-2"></i><?php echo htmlspecialchars($tour['ten_tour']); ?></h4>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="120">Mã tour:</th>
                                        <td><strong class="text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Giá tour:</th>
                                        <td><span class="badge bg-success p-2"><?php echo number_format($tour['gia_tour'] ?? 0); ?> VNĐ</span></td>
                                    </tr>
                                    <tr>
                                        <th>Danh mục:</th>
                                        <td><?php echo htmlspecialchars($tour['ten_danh_muc'] ?? 'Chưa phân loại'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            <span class="badge bg-success"><?php echo $tour['trang_thai']; ?></span>
                                            <span class="badge bg-info ml-2">Cloned</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Thành phần đã clone -->
                            <?php if ($clone_info): ?>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-clipboard-check me-2"></i>
                                            Thành phần đã được sao chép
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center">
                                                <div class="p-3 border rounded">
                                                    <h3 class="text-primary mb-1"><?php echo $clone_info['items_cloned']['lich_trinh'] ?? 0; ?></h3>
                                                    <small class="text-muted">Lịch trình</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="p-3 border rounded">
                                                    <h3 class="text-success mb-1"><?php echo $clone_info['items_cloned']['phien_ban'] ?? 0; ?></h3>
                                                    <small class="text-muted">Phiên bản</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="p-3 border rounded">
                                                    <h3 class="text-warning mb-1"><?php echo $clone_info['items_cloned']['media'] ?? 0; ?></h3>
                                                    <small class="text-muted">Hình ảnh</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="p-3 border rounded">
                                                    <h3 class="text-info mb-1"><?php echo $clone_info['items_cloned']['lich_khoi_hanh'] ?? 0; ?></h3>
                                                    <small class="text-muted">Lịch KH</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Tour gốc -->
                            <?php if ($original_tour): ?>
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-link me-2"></i>
                                            Liên kết với tour gốc
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1"><?php echo htmlspecialchars($original_tour['ten_tour']); ?></h6>
                                                <p class="text-muted mb-0">
                                                    <small>Mã: <?php echo htmlspecialchars($original_tour['ma_tour']); ?></small>
                                                </p>
                                            </div>
                                            <div class="media-right">
                                                <a href="index.php?act=tour-edit&id=<?php echo $original_tour['id']; ?>"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-external-link-alt"></i> Mở tour gốc
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <?php if ($tour['hinh_anh']): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Hình ảnh tour</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img src="uploads/tours/<?php echo htmlspecialchars($tour['hinh_anh']); ?>"
                                            alt="Tour Image" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-bolt me-2"></i>
                                        Hành động nhanh
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <a href="index.php?act=tour-edit&id=<?php echo $tour['id']; ?>"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-edit me-2 text-primary"></i>Sửa tour mới
                                        </a>
                                        <a href="index.php?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-calendar-alt me-2 text-info"></i>Quản lý lịch trình
                                        </a>
                                        <a href="index.php?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-tags me-2 text-warning"></i>Phiên bản tour
                                        </a>
                                        <a href="index.php?act=tour-media&tour_id=<?php echo $tour['id']; ?>"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-images me-2 text-success"></i>Quản lý hình ảnh
                                        </a>
                                        <a href="index.php?act=tour"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-list me-2 text-secondary"></i>Danh sách tour
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?act=tour" class="btn btn-secondary">
                            <i class="fas fa-list me-2"></i>Danh sách tour
                        </a>
                        <a href="index.php?act=tour-edit&id=<?php echo $tour['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Bắt đầu chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lời khuyên -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        Lời khuyên tiếp theo
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Kiểm tra và chỉnh sửa thông tin tour mới nếu cần</li>
                        <li>Tạo lịch khởi hành cho tour mới</li>
                        <li>Thiết lập giá và khuyến mãi phù hợp</li>
                        <li>Upload hình ảnh mới nếu cần thiết</li>
                        <li>Thêm nhà cung cấp cho tour mới</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
    // Tự động chuyển về trang edit sau 10 giây nếu không có hành động
    setTimeout(() => {
        window.location.href = 'index.php?act=tour-edit&id=<?php echo $tour['id']; ?>';
    }, 10000);
</script>