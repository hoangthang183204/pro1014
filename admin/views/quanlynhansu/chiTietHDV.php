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
                        <i class="fas fa-user-tie me-2"></i>
                        Chi Tiết Hướng Dẫn Viên
                    </a>
                    <div>
                        <a href="?act=huong-dan-vien" class="btn btn-outline-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông tin HDV -->
                <div class="row">
                    <!-- Thông tin cá nhân -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Thông tin cá nhân</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (isset($hdv['hinh_anh']) && !empty($hdv['hinh_anh'])): ?>
                                    <img src="<?php echo htmlspecialchars($hdv['hinh_anh']); ?>"
                                        class="rounded-circle mb-3"
                                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary mb-3 d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 150px; height: 150px; border: 3px solid #dee2e6;">
                                        <i class="fas fa-user-tie fa-4x text-white"></i>
                                    </div>
                                <?php endif; ?>

                                <h4 class="mb-2"><?php echo htmlspecialchars($hdv['ho_ten']); ?></h4>
                                <p class="text-muted mb-2">ID: <?php echo $hdv['id']; ?></p>

                                <div class="mb-3">
                                    <span class="badge bg-<?php
                                                            echo match ($hdv['trang_thai'] ?? 'đang làm việc') {
                                                                'đang làm việc' => 'success',
                                                                'nghỉ việc' => 'danger',
                                                                'tạm nghỉ' => 'warning',
                                                                default => 'secondary'
                                                            };
                                                            ?>" style="font-size: 14px;">
                                        <?php echo htmlspecialchars($trang_thai_options[$hdv['trang_thai']] ?? $hdv['trang_thai'] ?? 'Không xác định'); ?>
                                    </span>
                                    <span class="badge bg-info ms-1" style="font-size: 14px;">
                                        <?php echo htmlspecialchars($loai_hdv_options[$hdv['loai_huong_dan_vien']] ?? $hdv['loai_huong_dan_vien'] ?? 'Không xác định'); ?>
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h6>
                                        <i class="fas fa-star text-warning me-1"></i>
                                        Đánh giá: <?php echo isset($hdv['danh_gia_trung_binh']) ? number_format($hdv['danh_gia_trung_binh'], 1) : '0.0'; ?>/5
                                    </h6>
                                    <h6>
                                        <i class="fas fa-suitcase text-primary me-1"></i>
                                        Số tour đã dẫn:
                                        <span class="badge bg-primary">
                                            <?php echo isset($hdv['so_tour_da_dan']) ? (int)$hdv['so_tour_da_dan'] : 0; ?> tour
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin chi tiết -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>Thông tin liên hệ</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%" class="border-0">Số điện thoại:</th>
                                                <td class="border-0"><?php echo isset($hdv['so_dien_thoai']) && !empty($hdv['so_dien_thoai']) ? htmlspecialchars($hdv['so_dien_thoai']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Email:</th>
                                                <td class="border-0"><?php echo isset($hdv['email']) && !empty($hdv['email']) ? htmlspecialchars($hdv['email']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Địa chỉ:</th>
                                                <td class="border-0"><?php echo isset($hdv['dia_chi']) && !empty($hdv['dia_chi']) ? htmlspecialchars($hdv['dia_chi']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Ngày sinh:</th>
                                                <td class="border-0">
                                                    <?php echo isset($hdv['ngay_sinh']) && !empty($hdv['ngay_sinh']) ? date('d/m/Y', strtotime($hdv['ngay_sinh'])) : 'Chưa cập nhật'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Ngôn ngữ:</th>
                                                <td class="border-0">
                                                    <?php if (!empty($hdv['ngon_ngu'])): ?>
                                                        <?php foreach ($hdv['ngon_ngu'] as $nn): ?>
                                                            <span class="badge bg-secondary me-1 mb-1"><?php echo htmlspecialchars($nn); ?></span>
                                                        <?php endforeach; ?>
                                                        <?php else: ?>Chưa cập nhật<?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Thông tin nghề nghiệp</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%" class="border-0">Số giấy phép:</th>
                                                <td class="border-0"><?php echo isset($hdv['so_giay_phep_hanh_nghe']) && !empty($hdv['so_giay_phep_hanh_nghe']) ? htmlspecialchars($hdv['so_giay_phep_hanh_nghe']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Ngày cấp:</th>
                                                <td class="border-0">
                                                    <?php echo isset($hdv['ngay_cap_giay_phep']) && !empty($hdv['ngay_cap_giay_phep']) ? date('d/m/Y', strtotime($hdv['ngay_cap_giay_phep'])) : 'Chưa cập nhật'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Nơi cấp:</th>
                                                <td class="border-0"><?php echo isset($hdv['noi_cap_giay_phep']) && !empty($hdv['noi_cap_giay_phep']) ? htmlspecialchars($hdv['noi_cap_giay_phep']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Chuyên môn:</th>
                                                <td class="border-0"><?php echo isset($hdv['chuyen_mon']) && !empty($hdv['chuyen_mon']) ? htmlspecialchars($hdv['chuyen_mon']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="border-0">Sức khỏe:</th>
                                                <td class="border-0"><?php echo isset($hdv['tinh_trang_suc_khoe']) && !empty($hdv['tinh_trang_suc_khoe']) ? htmlspecialchars($hdv['tinh_trang_suc_khoe']) : 'Chưa cập nhật'; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kinh nghiệm -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Kinh nghiệm làm việc</h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($hdv['kinh_nghiem']) && !empty($hdv['kinh_nghiem'])): ?>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($hdv['kinh_nghiem'])); ?></p>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Chưa có thông tin kinh nghiệm</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lịch làm việc gần đây -->
                <!-- <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Lịch làm việc gần đây</h5>
                        <a href="?act=lich-lam-viec-hdv&tu_khoa=<?php echo urlencode($hdv['ho_ten']); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Xem tất cả
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($lich_lam_viec)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="120">Ngày làm việc</th>
                                            <th width="120">Thứ</th>
                                            <th width="120">Trạng thái</th>
                                            <th>Ghi chú</th>
                                            <th width="150">Ngày tạo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lich_lam_viec as $lich): ?>
                                            <tr>
                                                <td class="fw-bold">
                                                    <i class="fas fa-calendar-day text-primary me-1"></i>
                                                    <?php echo date('d/m/Y', strtotime($lich['ngay'])); ?>
                                                </td>
                                                <td class="text-muted">
                                                    <?php echo $lich['thu_viet'] ?? ''; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $loai_lich = $lich['loai_lich'] ?? '';
                                                    $badge_class = [
                                                        'có thể làm' => 'success',
                                                        'bận' => 'warning',
                                                        'nghỉ' => 'danger',
                                                        'đã phân công' => 'info'
                                                    ];
                                                    $icon_class = [
                                                        'có thể làm' => 'check-circle',
                                                        'bận' => 'clock',
                                                        'nghỉ' => 'bed',
                                                        'đã phân công' => 'user-check'
                                                    ];
                                                    $class = $badge_class[$loai_lich] ?? 'secondary';
                                                    $icon = $icon_class[$loai_lich] ?? 'question-circle';
                                                    ?>
                                                    <span class="badge bg-<?php echo $class; ?>">
                                                        <i class="fas fa-<?php echo $icon; ?> me-1"></i>
                                                        <?php echo htmlspecialchars($loai_lich); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($lich['ghi_chu'])): ?>
                                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                            <?php echo htmlspecialchars($lich['ghi_chu']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Không có ghi chú</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-muted small">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo date('d/m/Y', strtotime($lich['created_at'])); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-times fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-2">Chưa có lịch làm việc</h6>
                                <p class="text-muted small mb-0">Hướng dẫn viên chưa được lên lịch làm việc</p>
                                <a href="?act=lich-lam-viec-hdv" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-plus me-1"></i> Thêm lịch làm việc
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($lich_lam_viec)): ?>
                        <div class="card-footer bg-white border-top py-2">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Hiển thị <?php echo count($lich_lam_viec); ?> lịch làm việc gần nhất
                            </div>
                        </div>
                    <?php endif; ?>
                </div> -->

                <!-- Tour đã dẫn gần đây -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-route me-2"></i>Tour đã dẫn gần đây</h5>
                        <?php if (!empty($tour_da_dan)): ?>
                            <span class="badge bg-primary"><?php echo count($tour_da_dan); ?> tour</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tour_da_dan)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="100">Mã tour</th>
                                            <th>Tên tour</th>
                                            <th width="120">Thời gian</th>
                                            <th width="100">Trạng thái tour</th>
                                            <th width="120">Ngày phân công</th>
                                            <th width="100" class="text-center">Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tour_da_dan as $tour): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-dark">
                                                        <i class="fas fa-hashtag me-1"></i>
                                                        <?php echo htmlspecialchars($tour['ma_tour']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary">
                                                        <?php echo htmlspecialchars($tour['ten_tour']); ?>
                                                    </div>
                                                    <div class="small text-muted">
                                                        ID: <?php echo $tour['tour_id']; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <div>
                                                            <i class="fas fa-plane-departure text-success me-1"></i>
                                                            <?php echo date('d/m/Y', strtotime($tour['ngay_bat_dau'])); ?>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-plane-arrival text-danger me-1"></i>
                                                            <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'])); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $trang_thai_tour = $tour['trang_thai_tour'] ?? '';
                                                    $tour_badge_class = [
                                                        'đã lên lịch' => 'info',
                                                        'đang đi' => 'warning',
                                                        'đã hoàn thành' => 'success',
                                                        'đã hủy' => 'danger'
                                                    ];
                                                    $tour_icon_class = [
                                                        'đã lên lịch' => 'calendar-check',
                                                        'đang đi' => 'spinner',
                                                        'đã hoàn thành' => 'check-circle',
                                                        'đã hủy' => 'times-circle'
                                                    ];
                                                    $tour_class = $tour_badge_class[$trang_thai_tour] ?? 'secondary';
                                                    $tour_icon = $tour_icon_class[$trang_thai_tour] ?? 'question-circle';
                                                    ?>
                                                    <span class="badge bg-<?php echo $tour_class; ?>">
                                                        <i class="fas fa-<?php echo $tour_icon; ?> me-1"></i>
                                                        <?php echo htmlspecialchars($trang_thai_tour); ?>
                                                    </span>
                                                </td>
                                                <td class="text-muted small">
                                                    <i class="fas fa-calendar-plus me-1"></i>
                                                    <?php echo date('d/m/Y', strtotime($tour['ngay_phan_cong'])); ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="?act=lich-khoi-hanh&id=<?php echo $tour['lich_khoi_hanh_id']; ?>"
                                                        class="btn btn-sm btn-outline-info"
                                                        title="Xem chi tiết tour"
                                                        data-bs-toggle="tooltip">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-suitcase-rolling fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-2">Chưa có tour nào được phân công</h6>
                                <p class="text-muted small mb-0">Hướng dẫn viên chưa được phân công dẫn tour nào</p>
                                <a href="?act=phan-cong-tour" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-plus me-1"></i> Phân công tour
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($tour_da_dan)): ?>
                        <div class="card-footer bg-white border-top py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Hiển thị <?php echo count($tour_da_dan); ?> tour gần nhất
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Tổng cộng: <?php echo $hdv['so_tour_da_dan'] ?? 0; ?> tour
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(function() {
            $('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    });
</script>

<style>
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid transparent;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left-color: #dc3545;
    }

    .card-header h5 {
        font-size: 16px;
        font-weight: 600;
    }

    .table-sm th {
        font-weight: 600;
        color: #495057;
    }

    .table-sm td {
        color: #6c757d;
    }

    .badge {
        font-weight: 500;
    }
</style>

<?php include './views/layout/footer.php'; ?>