<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=huong-dan-vien">
                        <i class="fas fa-user-tie me-2"></i>
                        Chi Tiết Hướng Dẫn Viên
                    </a>
                    <div>
                        <a href="?act=huong-dan-vien" class="btn btn-outline-light btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Thông tin chính -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Thông tin cá nhân</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if (isset($hdv['hinh_anh']) && $hdv['hinh_anh']): ?>
                                    <img src="<?php echo htmlspecialchars($hdv['hinh_anh']); ?>" 
                                         class="img-fluid rounded mb-3" 
                                         style="max-height: 200px;" 
                                         alt="<?php echo htmlspecialchars($hdv['ho_ten']); ?>">
                                <?php else: ?>
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mx-auto mb-3" 
                                         style="width: 200px; height: 200px;">
                                        <i class="fas fa-user-tie fa-4x"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <h4 class="text-primary"><?php echo htmlspecialchars($hdv['ho_ten']); ?></h4>
                                
                                <div class="mt-3">
                                    <span class="badge bg-<?php 
                                        echo match($hdv['loai_huong_dan_vien']) {
                                            'nội địa' => 'primary',
                                            'quốc tế' => 'warning',
                                            'chuyên tuyến' => 'info',
                                            default => 'secondary'
                                        };
                                    ?> me-2">
                                        <?php echo htmlspecialchars($hdv['loai_huong_dan_vien']); ?>
                                    </span>
                                    
                                    <span class="badge bg-<?php 
                                        echo match ($hdv['trang_thai']) {
                                            'đang làm việc' => 'success',
                                            'nghỉ việc' => 'danger',
                                            'tạm nghỉ' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <i class="fas fa-<?php 
                                            echo match ($hdv['trang_thai']) {
                                                'đang làm việc' => 'check-circle',
                                                'nghỉ việc' => 'times-circle',
                                                'tạm nghỉ' => 'pause-circle',
                                                default => 'question-circle'
                                            };
                                        ?> me-1"></i>
                                        <?php echo htmlspecialchars($hdv['trang_thai']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Thống kê</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <h3 class="text-primary"><?php echo $hdv['so_tour_da_dan'] ?? 0; ?></h3>
                                    <p class="text-muted mb-0">Tour đã dẫn</p>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <h4 class="text-warning">
                                        <i class="fas fa-star"></i> 
                                        <?php echo number_format($hdv['danh_gia_trung_binh'] ?? 0, 1); ?>
                                    </h4>
                                    <p class="text-muted mb-0">Đánh giá trung bình</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết thông tin -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Số điện thoại</label>
                                            <p class="mb-0"><?php echo $hdv['so_dien_thoai'] ? htmlspecialchars($hdv['so_dien_thoai']) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email</label>
                                            <p class="mb-0"><?php echo $hdv['email'] ? htmlspecialchars($hdv['email']) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Số giấy phép hành nghề</label>
                                            <p class="mb-0"><?php echo $hdv['so_giay_phep_hanh_nghe'] ? htmlspecialchars($hdv['so_giay_phep_hanh_nghe']) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ngày sinh</label>
                                            <p class="mb-0"><?php echo $hdv['ngay_sinh'] ? date('d/m/Y', strtotime($hdv['ngay_sinh'])) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ngày cấp giấy phép</label>
                                            <p class="mb-0"><?php echo $hdv['ngay_cap_giay_phep'] ? date('d/m/Y', strtotime($hdv['ngay_cap_giay_phep'])) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nơi cấp giấy phép</label>
                                            <p class="mb-0"><?php echo $hdv['noi_cap_giay_phep'] ? htmlspecialchars($hdv['noi_cap_giay_phep']) : '<span class="text-muted">Chưa cập nhật</span>'; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ngôn ngữ -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ngôn ngữ</label>
                                    <div>
                                        <?php
                                        $ngon_ngu = [];
                                        if (isset($hdv['ngon_ngu']) && $hdv['ngon_ngu']) {
                                            $ngon_ngu = json_decode($hdv['ngon_ngu'], true) ?: [];
                                        }
                                        
                                        if (is_array($ngon_ngu) && !empty($ngon_ngu)):
                                            foreach ($ngon_ngu as $nn):
                                        ?>
                                                <span class="badge bg-dark me-1 mb-1"><?php echo strtoupper(htmlspecialchars($nn)); ?></span>
                                        <?php
                                            endforeach;
                                        else:
                                            echo '<span class="text-muted">Chưa cập nhật</span>';
                                        endif;
                                        ?>
                                    </div>
                                </div>

                                <!-- Chuyên môn -->
                                <?php if (isset($hdv['chuyen_mon']) && $hdv['chuyen_mon']): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chuyên môn</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($hdv['chuyen_mon']); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Kinh nghiệm -->
                                <?php if (isset($hdv['kinh_nghiem']) && $hdv['kinh_nghiem']): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kinh nghiệm</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($hdv['kinh_nghiem']); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Địa chỉ -->
                                <?php if (isset($hdv['dia_chi']) && $hdv['dia_chi']): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($hdv['dia_chi']); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Tình trạng sức khỏe -->
                                <?php if (isset($hdv['tinh_trang_suc_khoe']) && $hdv['tinh_trang_suc_khoe']): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tình trạng sức khỏe</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($hdv['tinh_trang_suc_khoe']); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Ghi chú -->
                                <?php if (isset($hdv['ghi_chu']) && $hdv['ghi_chu']): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ghi chú</label>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($hdv['ghi_chu'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Thông tin hệ thống -->
                                <div class="border-top pt-3 mt-3">
                                    <h6 class="fw-bold">Thông tin hệ thống</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Ngày tạo: <?php echo $hdv['created_at'] ? date('d/m/Y H:i', strtotime($hdv['created_at'])) : 'Chưa cập nhật'; ?></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Cập nhật lần cuối: <?php echo $hdv['updated_at'] ? date('d/m/Y H:i', strtotime($hdv['updated_at'])) : 'Chưa cập nhật'; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="?act=huong-dan-vien" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>