<!-- views/quanlytour/chiTietPhienBan.php -->
<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-eye me-2"></i>
                        Chi Tiết Phiên Bản: <?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?>
                    </a>
                    <div>
                        <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>"
                            class="btn btn-outline-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <!-- <a href="?act=phien-ban-edit&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                            class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a> -->
                        <a href="?act=phien-ban-activate&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                            class="btn btn-success me-2"
                            onclick="return confirm('Kích hoạt phiên bản này? Giá tour sẽ được cập nhật thành <?php echo number_format($phien_ban['gia_tour'], 0, ',', '.'); ?> đ')">
                            <i class="fas fa-play me-1"></i> Kích hoạt
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Xóa
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Thông báo -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="container-fluid mt-4">
                <!-- Thông tin chính -->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Thông tin cơ bản -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin phiên bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Tour:</th>
                                                <td>
                                                    <strong class="text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></strong><br>
                                                    <small><?php echo htmlspecialchars($tour['ten_tour']); ?></small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Danh mục:</th>
                                                <td><?php echo htmlspecialchars($tour['ten_danh_muc'] ?? 'Chưa phân loại'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Loại phiên bản:</th>
                                                <td>
                                                    <?php
                                                    $badge_class = 'bg-secondary';
                                                    $icon = 'fas fa-code-branch';
                                                    $loai_text = '';

                                                    switch ($phien_ban['loai_phien_ban']) {
                                                        case 'mua':
                                                            $badge_class = 'bg-success';
                                                            $icon = 'fas fa-sun';
                                                            $loai_text = 'Theo mùa';
                                                            break;
                                                        case 'khuyen_mai':
                                                            $badge_class = 'bg-warning text-dark';
                                                            $icon = 'fas fa-tag';
                                                            $loai_text = 'Khuyến mãi';
                                                            break;
                                                        case 'dac_biet':
                                                            $badge_class = 'bg-danger';
                                                            $icon = 'fas fa-crown';
                                                            $loai_text = 'Đặc biệt (VIP)';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?> fs-6">
                                                        <i class="<?php echo $icon; ?> me-1"></i><?php echo $loai_text; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Thời gian hiệu lực:</th>
                                                <td>
                                                    <span class="text-success">
                                                        <?php echo date('d/m/Y', strtotime($phien_ban['thoi_gian_bat_dau'])); ?>
                                                    </span>
                                                    đến
                                                    <span class="text-danger">
                                                        <?php echo date('d/m/Y', strtotime($phien_ban['thoi_gian_ket_thuc'])); ?>
                                                    </span>
                                                    <?php
                                                    $now = date('Y-m-d');
                                                    $is_active = ($phien_ban['thoi_gian_bat_dau'] <= $now && $phien_ban['thoi_gian_ket_thuc'] >= $now);
                                                    ?>
                                                    <br>
                                                    <small class="<?php echo $is_active ? 'text-success' : 'text-secondary'; ?>">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?php echo $is_active ? 'Đang hiệu lực' : 'Đã kết thúc'; ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Giá gốc:</th>
                                                <td>
                                                    <?php if ($phien_ban['gia_goc'] && $phien_ban['gia_goc'] > $phien_ban['gia_tour']): ?>
                                                        <span class="text-decoration-line-through text-muted">
                                                            <?php echo number_format($phien_ban['gia_goc'], 0, ',', '.'); ?> đ
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">
                                                            <?php echo number_format($tour['gia_hien_tai'], 0, ',', '.'); ?> đ
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Giá phiên bản:</th>
                                                <td>
                                                    <span class="text-primary fw-bold fs-5">
                                                        <?php echo number_format($phien_ban['gia_tour'], 0, ',', '.'); ?> đ
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Khuyến mãi:</th>
                                                <td>
                                                    <?php if ($phien_ban['khuyen_mai'] > 0): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-percentage me-1"></i>
                                                            Giảm <?php echo $phien_ban['khuyen_mai']; ?>%
                                                        </span>
                                                        <?php if ($phien_ban['gia_goc'] > 0): ?>
                                                            <br>
                                                            <small class="text-success">
                                                                Tiết kiệm: <?php echo number_format($phien_ban['gia_goc'] - $phien_ban['gia_tour'], 0, ',', '.'); ?> đ
                                                            </small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Không có khuyến mãi</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Người tạo:</th>
                                                <td>
                                                    <?php echo htmlspecialchars($phien_ban['nguoi_tao_ten'] ?? 'Hệ thống'); ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($phien_ban['created_at'])); ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Mô tả -->
                                <?php if (!empty($phien_ban['mo_ta'])): ?>
                                    <div class="mt-3 pt-3 border-top">
                                        <h6><i class="fas fa-align-left me-2"></i>Mô tả:</h6>
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($phien_ban['mo_ta'])); ?></p>
                                    </div>
                                <?php endif; ?>

                                <!-- Dịch vụ đặc biệt -->
                                <?php if (!empty($phien_ban['dich_vu_dac_biet'])): ?>
                                    <div class="mt-3 pt-3 border-top">
                                        <h6><i class="fas fa-star me-2 text-warning"></i>Dịch vụ đặc biệt:</h6>
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($phien_ban['dich_vu_dac_biet'])); ?></p>
                                    </div>
                                <?php endif; ?>

                                <!-- Điều kiện áp dụng -->
                                <?php if (!empty($phien_ban['dieu_kien_ap_dung'])): ?>
                                    <div class="mt-3 pt-3 border-top">
                                        <h6><i class="fas fa-clipboard-check me-2 text-info"></i>Điều kiện áp dụng:</h6>
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($phien_ban['dieu_kien_ap_dung'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Thống kê -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê hoạt động</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($thong_ke) && ($thong_ke['tong_dat'] > 0 || $thong_ke['so_lich_khoi_hanh'] > 0)): ?>
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <h2 class="text-primary"><?php echo $thong_ke['so_lich_khoi_hanh'] ?? 0; ?></h2>
                                                    <small class="text-muted">Lịch khởi hành</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body">
                                                    <h2 class="text-success"><?php echo $thong_ke['tong_dat'] ?? 0; ?></h2>
                                                    <small class="text-muted">Đơn đặt tour</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body">
                                                    <h2 class="text-warning"><?php echo $thong_ke['tong_khach'] ?? 0; ?></h2>
                                                    <small class="text-muted">Tổng khách</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body">
                                                    <h2 class="text-danger"><?php echo number_format($thong_ke['doanh_thu'] ?? 0, 0, ',', '.'); ?></h2>
                                                    <small class="text-muted">Doanh thu (VNĐ)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($thong_ke['diem_danh_gia_tb'] > 0): ?>
                                        <div class="mt-3">
                                            <h6>Đánh giá trung bình:</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="text-warning me-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= round($thong_ke['diem_danh_gia_tb']) ? 'text-warning' : 'text-muted'; ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="fw-bold"><?php echo number_format($thong_ke['diem_danh_gia_tb'], 1); ?>/5</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Chưa có thống kê hoạt động</h5>
                                        <p class="text-muted">Chưa có đặt tour nào trong thời gian này</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Lịch khởi hành -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Lịch khởi hành</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($lich_khoi_hanh)): ?>
                                    <div class="timeline">
                                        <?php foreach ($lich_khoi_hanh as $lkh): ?>
                                            <div class="timeline-item mb-3">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    <?php echo date('d/m', strtotime($lkh['ngay_bat_dau'])); ?> -
                                                                    <?php echo date('d/m', strtotime($lkh['ngay_ket_thuc'])); ?>
                                                                </h6>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-users me-1"></i>
                                                                    <?php echo $lkh['so_cho_con_lai']; ?>/<?php echo $lkh['so_cho_toi_da']; ?> chỗ
                                                                </small>
                                                            </div>
                                                            <div>
                                                                <?php
                                                                $trang_thai_badge = 'bg-secondary';
                                                                $trang_thai_icon = 'fas fa-clock';

                                                                switch ($lkh['trang_thai']) {
                                                                    case 'đang diễn ra':
                                                                        $trang_thai_badge = 'bg-success';
                                                                        $trang_thai_icon = 'fas fa-play-circle';
                                                                        break;
                                                                    case 'đã hoàn thành':
                                                                        $trang_thai_badge = 'bg-info';
                                                                        $trang_thai_icon = 'fas fa-check-circle';
                                                                        break;
                                                                    case 'đã lên lịch':
                                                                        $trang_thai_badge = 'bg-primary';
                                                                        $trang_thai_icon = 'fas fa-calendar-check';
                                                                        break;
                                                                    case 'đã hủy':
                                                                        $trang_thai_badge = 'bg-danger';
                                                                        $trang_thai_icon = 'fas fa-times-circle';
                                                                        break;
                                                                }
                                                                ?>
                                                                <span class="badge <?php echo $trang_thai_badge; ?>">
                                                                    <i class="<?php echo $trang_thai_icon; ?> me-1"></i><?php echo $lkh['trang_thai']; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <?php if ($lkh['so_dat_tour'] > 0): ?>
                                                            <div class="mt-2">
                                                                <small class="text-success">
                                                                    <i class="fas fa-check me-1"></i>
                                                                    <?php echo $lkh['so_dat_tour']; ?> đơn đã đặt
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Chưa có lịch khởi hành</h6>
                                        <p class="text-muted small">Không có lịch khởi hành nào trong thời gian hiệu lực</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Duyệt toán chi phí -->
                        <?php if (!empty($du_toan)): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Duyệt toán chi phí</h6>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($du_toan as $dt): ?>
                                            <div class="list-group-item border-0 px-0 py-2">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($dt['loai_chi_phi']); ?></small>
                                                        <br>
                                                        <span class="fw-bold"><?php echo number_format($dt['so_tien_du_toan'], 0, ',', '.'); ?> đ</span>
                                                    </div>
                                                    <?php if ($dt['mo_ta']): ?>
                                                        <small class="text-muted"><?php echo htmlspecialchars(substr($dt['mo_ta'], 0, 30)) . '...'; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- So sánh giá -->
                        <!-- Phần so sánh giá - sửa phần này -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fas fa-balance-scale me-2"></i>So sánh giá</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td>Giá hiện tại:</td>
                                        <td class="text-end">
                                            <span class="fw-bold">
                                                <?php
                                                // Lấy giá tour hiện tại - đảm bảo không NULL
                                                $gia_hien_tai = $tour['gia_tour'] ?? 0;
                                                echo number_format($gia_hien_tai, 0, ',', '.');
                                                ?> đ
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Giá phiên bản:</td>
                                        <td class="text-end">
                                            <span class="fw-bold text-primary">
                                                <?php echo number_format($phien_ban['gia_tour'], 0, ',', '.'); ?> đ
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td>Chênh lệch:</td>
                                        <td class="text-end">
                                            <?php
                                            // Đảm bảo giá trị không NULL
                                            $gia_hien_tai = $tour['gia_tour'] ?? 0;
                                            $gia_phien_ban = $phien_ban['gia_tour'] ?? 0;

                                            $chenh_lech = $gia_phien_ban - $gia_hien_tai;

                                            if ($gia_hien_tai > 0) {
                                                $chenh_lech_percent = ($chenh_lech / $gia_hien_tai) * 100;
                                            } else {
                                                $chenh_lech_percent = 0;
                                            }
                                            ?>
                                            <span class="fw-bold <?php echo $chenh_lech < 0 ? 'text-success' : ($chenh_lech > 0 ? 'text-danger' : 'text-muted'); ?>">
                                                <?php echo $chenh_lech < 0 ? '-' : '+'; ?>
                                                <?php echo number_format(abs($chenh_lech), 0, ',', '.'); ?> đ
                                                (<?php echo number_format(abs($chenh_lech_percent), 1); ?>%)
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lịch trình tour -->
                <?php if (!empty($lich_trinh)): ?>
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-route me-2"></i>Lịch trình tour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($lich_trinh as $item): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-header bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Ngày <?php echo $item['so_ngay']; ?></h6>
                                                    <span class="badge bg-primary"><?php echo htmlspecialchars($item['tieu_de']); ?></span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <?php if (!empty($item['mo_ta_hoat_dong'])): ?>
                                                    <p class="small"><?php echo nl2br(htmlspecialchars(substr($item['mo_ta_hoat_dong'], 0, 100))); ?>...</p>
                                                <?php endif; ?>

                                                <div class="mt-2">
                                                    <?php if (!empty($item['cho_o'])): ?>
                                                        <div class="mb-1">
                                                            <small><i class="fas fa-bed text-success me-1"></i> <strong>Chỗ ở:</strong></small><br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($item['cho_o']); ?></small>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['bua_an'])): ?>
                                                        <div class="mb-1">
                                                            <small><i class="fas fa-utensils text-warning me-1"></i> <strong>Bữa ăn:</strong></small><br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($item['bua_an']); ?></small>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['phuong_tien'])): ?>
                                                        <div class="mb-1">
                                                            <small><i class="fas fa-bus text-primary me-1"></i> <strong>Phương tiện:</strong></small><br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($item['phuong_tien']); ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($item['ghi_chu_hdv'])): ?>
                                                <div class="card-footer bg-light">
                                                    <small><i class="fas fa-info-circle text-info me-1"></i> <strong>Ghi chú HDV:</strong></small><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars(substr($item['ghi_chu_hdv'], 0, 80)); ?>...</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Cảnh báo!</h6>
                    <p class="mb-1">Bạn đang chuẩn bị xóa phiên bản:</p>
                    <ul>
                        <li><strong><?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?></strong></li>
                        <li>Tour: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?></li>
                        <li>Loại:
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
                        </li>
                    </ul>
                    <p class="mb-0 text-danger"><strong>Hành động này không thể hoàn tác!</strong></p>
                </div>

                <?php if (!empty($lich_khoi_hanh)): ?>
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-calendar-times me-2"></i>Không thể xóa!</h6>
                        <p class="mb-0">Phiên bản này đang có <strong><?php echo count($lich_khoi_hanh); ?> lịch khởi hành</strong> trong thời gian hiệu lực.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Hủy
                </button>
                <?php if (empty($lich_khoi_hanh)): ?>
                    <a href="?act=phien-ban-delete&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                        class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Xóa vĩnh viễn
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="fas fa-ban me-1"></i> Không thể xóa
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal kích hoạt phiên bản -->
<div class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="activateModalLabel">
                    <i class="fas fa-play-circle me-2"></i>Kích hoạt phiên bản
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn kích hoạt phiên bản này làm phiên bản hiện hành?</p>
                <div class="alert alert-info">
                    <h6>Thông tin kích hoạt:</h6>
                    <ul class="mb-0">
                        <li>Giá tour sẽ được cập nhật từ
                            <strong><?php echo number_format($tour['gia_hien_tai'], 0, ',', '.'); ?> đ</strong>
                            thành
                            <strong class="text-primary"><?php echo number_format($phien_ban['gia_tour'], 0, ',', '.'); ?> đ</strong>
                        </li>
                        <li>Tất cả đặt tour mới sẽ áp dụng giá này</li>
                        <li>Đặt tour cũ vẫn giữ nguyên giá</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Hủy
                </button>
                <a href="?act=phien-ban-activate&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                    class="btn btn-success">
                    <i class="fas fa-check me-1"></i> Đồng ý kích hoạt
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-item {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 10px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #6c757d;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #6c757d;
    }

    .timeline-item:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 22px;
        bottom: -10px;
        width: 2px;
        background-color: #e9ecef;
    }

    .timeline-item:last-child:after {
        display: none;
    }

    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    // Tự động hiển thị modal kích hoạt nếu URL có tham số activate
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('activate') === 'true') {
            const activateModal = new bootstrap.Modal(document.getElementById('activateModal'));
            activateModal.show();
        }
    });

    // Hiển thị thông báo khi kích hoạt thành công
    <?php if (isset($_SESSION['activate_success'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            alert('<?php echo $_SESSION['activate_success']; ?>');
        });
        <?php unset($_SESSION['activate_success']); ?>
    <?php endif; ?>
</script>

<?php include './views/layout/footer.php'; ?>