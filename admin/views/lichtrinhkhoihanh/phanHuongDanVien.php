<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-user-tie me-2"></i>
                        Phân Công Hướng Dẫn Viên
                    </a>
                    <div>
                        <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông tin lịch khởi hành -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lịch khởi hành</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour'] . ' - ' . $lich_khoi_hanh['ten_tour']); ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Thời gian:</strong> 
                                <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?> 
                                - <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Trạng thái:</strong> 
                                <span class="badge bg-<?php
                                    echo match ($lich_khoi_hanh['trang_thai']) {
                                        'đã lên lịch' => 'success',
                                        'đang diễn ra' => 'warning',
                                        'đã hoàn thành' => 'primary',
                                        'đã hủy' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo htmlspecialchars($lich_khoi_hanh['trang_thai']); ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($lich_khoi_hanh['diem_tap_trung']): ?>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['diem_tap_trung']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lich_khoi_hanh['ghi_chu_van_hanh']): ?>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Ghi chú vận hành:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ghi_chu_van_hanh']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form phân công HDV -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Phân công hướng dẫn viên</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($phan_cong_hien_tai): ?>
                            <div class="alert alert-info mb-4">
                                <h6><i class="fas fa-user-check me-2"></i>HDV hiện tại:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><?php echo htmlspecialchars($phan_cong_hien_tai['ho_ten']); ?></strong></p>
                                        <?php if ($phan_cong_hien_tai['so_dien_thoai']): ?>
                                            <p class="mb-1"><small>Điện thoại: <?php echo htmlspecialchars($phan_cong_hien_tai['so_dien_thoai']); ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if ($phan_cong_hien_tai['ngon_ngu']): ?>
                                            <p class="mb-1"><small>Ngôn ngữ: 
                                                <?php 
                                                    $ngon_ngu = json_decode($phan_cong_hien_tai['ngon_ngu'], true);
                                                    echo $ngon_ngu ? htmlspecialchars(implode(', ', $ngon_ngu)) : 'Không có';
                                                ?>
                                            </small></p>
                                        <?php endif; ?>
                                        <?php if ($phan_cong_hien_tai['ghi_chu']): ?>
                                            <p class="mb-1"><small>Ghi chú: <?php echo htmlspecialchars($phan_cong_hien_tai['ghi_chu']); ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <a href="?act=huy-phan-cong&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger mt-2"
                                   onclick="return confirm('Bạn có chắc muốn hủy phân công HDV này?')">
                                    <i class="fas fa-times me-1"></i> Hủy phân công
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Chưa có HDV nào được phân công cho lịch này.
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?act=phan-cong-store">
                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Chọn hướng dẫn viên <span class="text-danger">*</span></label>
                                <select name="huong_dan_vien_id" class="form-select" required>
                                    <option value="">-- Chọn HDV --</option>
                                    <?php foreach ($huong_dan_vien_list as $hdv): ?>
                                        <?php
                                        $selected = $phan_cong_hien_tai && $phan_cong_hien_tai['huong_dan_vien_id'] == $hdv['id'] ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $hdv['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                                            <?php if ($hdv['so_dien_thoai']): ?>
                                                - <?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>
                                            <?php endif; ?>
                                            <?php if ($hdv['chuyen_mon']): ?>
                                                - <?php echo htmlspecialchars($hdv['chuyen_mon']); ?>
                                            <?php endif; ?>
                                            <?php if ($hdv['ngon_ngu']): ?>
                                                (<?php 
                                                    $ngon_ngu = json_decode($hdv['ngon_ngu'], true);
                                                    echo $ngon_ngu ? htmlspecialchars(implode(', ', $ngon_ngu)) : '';
                                                ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">
                                    Chọn hướng dẫn viên phù hợp với tour và có lịch trống
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú phân công</label>
                                <textarea name="ghi_chu" class="form-control" rows="3"
                                    placeholder="Ghi chú đặc biệt cho HDV, yêu cầu cụ thể, thông tin liên hệ..."><?php echo $phan_cong_hien_tai['ghi_chu'] ?? ''; ?></textarea>
                                <small class="text-muted">
                                    Ghi chú sẽ được hiển thị cho HDV khi nhận phân công
                                </small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Thông tin:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>HDV sẽ nhận được thông báo về phân công mới</li>
                                    <li>Phân công sẽ được đánh dấu là "đã xác nhận"</li>
                                    <li>Có thể thay đổi HDV bất kỳ lúc nào</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="?act=lich-khoi-hanh" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> 
                                    <?php echo $phan_cong_hien_tai ? 'Cập nhật phân công' : 'Phân công HDV'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
</script>

<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.alert ul {
    padding-left: 1.5rem;
}

.alert ul li {
    margin-bottom: 0.25rem;
}

.btn-group-sm .btn {
    margin: 0 2px;
}

@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }
    
    .card-body .row > div {
        margin-bottom: 10px;
    }
}
</style>