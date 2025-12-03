<!-- views/quanlytour/phienBanTour.php -->
<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-code-branch me-2"></i>
                        Quản lý Phiên Bản: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?>
                    </a>
                    <div>
                        <a href="?act=tour" class="btn btn-outline-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Về Danh sách Tour
                        </a>
                        <a href="?act=phien-ban-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Tạo Phiên Bản Mới
                        </a>
                    </div>
                </div>
            </nav>

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

            <!-- Thống kê -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Tổng phiên bản</h6>
                                    <h4><?php echo count($phien_ban_list); ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-code-branch fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Theo mùa</h6>
                                    <h4><?php echo $stats['mua']; ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-sun fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Khuyến mãi</h6>
                                    <h4><?php echo $stats['khuyen_mai']; ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tag fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Đặc biệt</h6>
                                    <h4><?php echo $stats['dac_biet']; ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-crown fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách phiên bản -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách phiên bản</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($phien_ban_list)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-code-branch fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có phiên bản nào</h5>
                            <p>Bắt đầu bằng cách tạo phiên bản đầu tiên cho tour này</p>
                            <a href="?act=phien-ban-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Tạo Phiên Bản Đầu Tiên
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Phiên bản</th>
                                        <th width="15%">Loại</th>
                                        <th width="15%">Giá</th>
                                        <th width="20%">Thời gian hiệu lực</th>
                                        <th width="10%">Trạng thái</th>
                                        <th width="15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($phien_ban_list as $index => $phien_ban): ?>
                                        <?php
                                        $now = date('Y-m-d');
                                        $is_active = ($phien_ban['thoi_gian_bat_dau'] <= $now && $phien_ban['thoi_gian_ket_thuc'] >= $now);
                                        ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?></strong>
                                                <?php if ($phien_ban['mo_ta']): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars(substr($phien_ban['mo_ta'], 0, 50)) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $badge_class = 'bg-secondary';
                                                $icon = 'fas fa-code-branch';

                                                switch ($phien_ban['loai_phien_ban']) {
                                                    case 'mua':
                                                        $badge_class = 'bg-success';
                                                        $icon = 'fas fa-sun';
                                                        break;
                                                    case 'khuyen_mai':
                                                        $badge_class = 'bg-warning text-dark';
                                                        $icon = 'fas fa-tag';
                                                        break;
                                                    case 'dac_biet':
                                                        $badge_class = 'bg-danger';
                                                        $icon = 'fas fa-crown';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>">
                                                    <i class="<?php echo $icon; ?> me-1"></i>
                                                    <?php
                                                    switch ($phien_ban['loai_phien_ban']) {
                                                        case 'mua':
                                                            echo 'Theo mùa';
                                                            break;
                                                        case 'khuyen_mai':
                                                            echo 'Khuyến mãi';
                                                            break;
                                                        case 'dac_biet':
                                                            echo 'Đặc biệt';
                                                            break;
                                                    }
                                                    ?>
                                                </span>
                                                <?php if ($phien_ban['khuyen_mai'] > 0): ?>
                                                    <br><small class="text-success">Giảm <?php echo $phien_ban['khuyen_mai']; ?>%</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="text-primary"><?php echo number_format($phien_ban['gia_tour'], 0, ',', '.'); ?> đ</strong>
                                                <?php if ($phien_ban['gia_goc'] > $phien_ban['gia_tour']): ?>
                                                    <br><small class="text-muted"><s><?php echo number_format($phien_ban['gia_goc'], 0, ',', '.'); ?> đ</s></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y', strtotime($phien_ban['thoi_gian_bat_dau'])); ?>
                                                <br>đến<br>
                                                <?php echo date('d/m/Y', strtotime($phien_ban['thoi_gian_ket_thuc'])); ?>
                                            </td>
                                            <td>
                                                <?php if ($is_active): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Hiện hành
                                                    </span>
                                                <?php elseif ($phien_ban['thoi_gian_bat_dau'] > $now): ?>
                                                    <span class="badge bg-info">Sắp diễn ra</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="?act=phien-ban-xem&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <!-- <a href="?act=phien-ban-edit&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                    class="btn btn-sm btn-warning" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a> -->
                                                <a href="?act=phien-ban-activate&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('Kích hoạt phiên bản này làm phiên bản hiện hành?')"
                                                    title="Kích hoạt">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <a href="?act=phien-ban-delete&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Xóa phiên bản này?')"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
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
    </section>
</div>

<?php include './views/layout/footer.php'; ?>