<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-calendar-check me-2"></i>
                        Đặt Tour Khách Lẻ
                    </a>
                    <div class="btn-group">
                        <a href="?act=dat-tour" class="btn btn-outline-light mx-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="?act=dat-tour-doan" class="btn btn-outline-light">
                            <i class="fas fa-building me-1"></i> Đặt Tour Đoàn
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Có lỗi xảy ra!</h6>
                                <?php echo htmlspecialchars($_SESSION['error']); ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Thành công!</h6>
                                <?php echo htmlspecialchars($_SESSION['success']); ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form method="POST" action="?act=dat-tour-store-booking" id="datTourForm" class="needs-validation" novalidate>
                    <input type="hidden" name="loai_khach" value="le">
                    
                    <div class="row">
                        <!-- Cột trái - Thông tin chính -->
                        <div class="col-lg-8">
                            <!-- Thông tin tour -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                                        Thông Tin Tour
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Chọn Lịch Khởi Hành <span class="text-danger">*</span></label>
                                                <select name="lich_khoi_hanh_id" id="lich_khoi_hanh_id" class="form-control" required>
                                                    <option value="">-- Vui lòng chọn lịch khởi hành --</option>
                                                    <?php foreach ($lich_khoi_hanh_list as $lkh): ?>
                                                        <?php 
                                                        $so_cho_con_lai = $lkh['so_cho_con_lai'] ?? $lkh['so_cho_toi_da'];
                                                        $disabled = $so_cho_con_lai <= 0 ? 'disabled' : '';
                                                        $selected = isset($_POST['lich_khoi_hanh_id']) && $_POST['lich_khoi_hanh_id'] == $lkh['id'] ? 'selected' : '';
                                                        ?>
                                                        <option value="<?php echo $lkh['id']; ?>" 
                                                                data-gia="<?php echo $lkh['gia_tour']; ?>"
                                                                data-so-cho="<?php echo $so_cho_con_lai; ?>"
                                                                data-tour="<?php echo htmlspecialchars($lkh['ten_tour']); ?>"
                                                                data-ngay-bat-dau="<?php echo $lkh['ngay_bat_dau']; ?>"
                                                                data-ngay-ket-thuc="<?php echo $lkh['ngay_ket_thuc']; ?>"
                                                                data-gio-tap-trung="<?php echo $lkh['gio_tap_trung']; ?>"
                                                                data-diem-tap-trung="<?php echo htmlspecialchars($lkh['diem_tap_trung']); ?>"
                                                                <?php echo $disabled; ?>
                                                                <?php echo $selected; ?>>
                                                            <?php echo htmlspecialchars($lkh['ten_tour']); ?> - 
                                                            <?php echo date('d/m/Y', strtotime($lkh['ngay_bat_dau'])); ?> - 
                                                            <?php echo number_format($lkh['gia_tour'], 0, ',', '.'); ?> VNĐ -
                                                            <?php echo $so_cho_con_lai > 0 ? "Còn {$so_cho_con_lai} chỗ" : "HẾT CHỖ"; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn lịch khởi hành.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hiển thị thông tin tour chi tiết -->
                                    <div id="tour_detail" class="d-none">
                                        <div class="alert alert-light border-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong id="tour_name" class="text-primary"></strong><br>
                                                    <small class="text-muted" id="tour_dates"></small>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <strong class="text-success" id="tour_price"></strong><br>
                                                    <small class="text-muted" id="tour_seats"></small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <small><i class="fas fa-map-marker-alt me-1"></i> <span id="tour_meeting_point"></span></small><br>
                                                <small><i class="fas fa-clock me-1"></i> Giờ tập trung: <span id="tour_meeting_time"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin khách hàng -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user me-2 text-info"></i>
                                        Thông Tin Khách Hàng
                                    </h5>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Họ Tên <span class="text-danger">*</span></label>
                                                <input type="text" name="ho_ten" class="form-control" required 
                                                       value="<?php echo $_POST['ho_ten'] ?? ''; ?>"
                                                       placeholder="Nhập họ tên đầy đủ">
                                                <div class="invalid-feedback">Vui lòng nhập họ tên.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Số Điện Thoại <span class="text-danger">*</span></label>
                                                <input type="text" name="so_dien_thoai" class="form-control" required 
                                                       value="<?php echo $_POST['so_dien_thoai'] ?? ''; ?>"
                                                       placeholder="Nhập số điện thoại">
                                                <div class="invalid-feedback">Vui lòng nhập số điện thoại.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" required
                                                       value="<?php echo $_POST['email'] ?? ''; ?>"
                                                       placeholder="Nhập email">
                                                <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">CCCD/CMND <span class="text-danger">*</span></label>
                                                <input type="text" name="cccd" class="form-control" required
                                                       value="<?php echo $_POST['cccd'] ?? ''; ?>"
                                                       placeholder="Nhập số CCCD/CMND">
                                                <div class="invalid-feedback">Vui lòng nhập số CCCD/CMND.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Ngày Sinh <span class="text-danger">*</span></label>
                                                <input type="date" name="ngay_sinh" class="form-control" required 
                                                       value="<?php echo $_POST['ngay_sinh'] ?? ''; ?>"
                                                       max="<?php echo date('Y-m-d'); ?>">
                                                <div class="invalid-feedback">Vui lòng chọn ngày sinh.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Giới Tính <span class="text-danger">*</span></label>
                                                <select name="gioi_tinh" class="form-control" required>
                                                    <option value="">-- Chọn giới tính --</option>
                                                    <option value="nam" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] == 'nam') ? 'selected' : ''; ?>>Nam</option>
                                                    <option value="nữ" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] == 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                                    <option value="khác" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] == 'khác') ? 'selected' : ''; ?>>Khác</option>
                                                </select>
                                                <div class="invalid-feedback">Vui lòng chọn giới tính.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Địa Chỉ <span class="text-danger">*</span></label>
                                                <input type="text" name="dia_chi" class="form-control" required
                                                       value="<?php echo $_POST['dia_chi'] ?? ''; ?>"
                                                       placeholder="Nhập địa chỉ">
                                                <div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Danh sách thành viên -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-users me-2 text-warning"></i>
                                            Thành Viên 
                                            <span class="badge bg-warning ms-2" id="so_thanh_vien_badge">0</span>
                                        </h5>
                                        <div class="btn-group">
                                            <div class="input-group input-group-sm me-2" style="width: 180px;">
                                                <input type="number" id="so_luong_them" class="form-control" placeholder="Số lượng" min="1" value="1">
                                                <button type="button" id="btn_them_nhanh" class="btn btn-outline-warning">
                                                    <i class="fas fa-bolt me-1"></i> Thêm nhanh
                                                </button>
                                            </div>
                                            <button type="button" id="btn_them_thanh_vien" class="btn btn-warning btn-sm">
                                                <i class="fas fa-plus me-1"></i> Thêm thành viên
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">                                    
                                    <div id="danh_sach_thanh_vien">
                                        <!-- Danh sách thành viên sẽ được thêm vào đây -->
                                    </div>
                                    
                                    <div class="text-center py-4" id="empty_state">
                                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Chưa có thành viên nào</h6>
                                        <p class="text-muted small">Nhấn "Thêm thành viên" để thêm người đi kèm</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-edit me-2 text-secondary"></i>
                                        Ghi Chú & Yêu Cầu Đặc Biệt
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <textarea name="ghi_chu" class="form-control" rows="4" 
                                              placeholder="Ghi chú thêm, yêu cầu đặc biệt, dị ứng thức ăn, yêu cầu về phòng..."><?php echo $_POST['ghi_chu'] ?? ''; ?></textarea>
                                    <div class="form-text">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Thông tin này sẽ giúp chúng tôi phục vụ bạn tốt hơn.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cột phải - Tổng hợp & Thanh toán -->
                        <div class="col-lg-4">
                            <!-- Tổng hợp đơn hàng -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-receipt me-2 text-primary"></i>
                                        Tổng Hợp Đơn Hàng
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="display-4 text-primary fw-bold" id="tong_so_khach">0</div>
                                        <div class="text-muted">Tổng số khách đặt tour</div>
                                    </div>

                                    <!-- Chi tiết số lượng -->
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Khách hàng chính:</small>
                                            <small class="fw-bold text-success">Miễn phí</small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Thành viên đi kèm:</small>
                                            <small class="fw-bold" id="so_thanh_vien_display">0 người</small>
                                        </div>
                                    </div>

                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Giá tour/người:</span>
                                            <span class="fw-bold" id="gia_tour_don">0 VNĐ</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Số lượng khách:</span>
                                            <span class="fw-bold" id="so_luong_khach_display">0</span>
                                        </div>
                                        <div class="border-top pt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h5 text-dark">Tổng tiền:</span>
                                                <span class="h4 text-success fw-bold" id="tong_tien">0 VNĐ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-info-circle text-info me-2"></i>
                                            <small class="fw-bold">Thông tin chỗ trống</small>
                                        </div>
                                        <small class="text-muted" id="thong_bao_cho">
                                            Vui lòng chọn lịch khởi hành để xem số chỗ còn lại
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin tour được chọn -->
                            <div class="card mb-4 border-0 shadow-sm d-none" id="selected_tour_card">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map me-2 text-info"></i>
                                        Tour Đã Chọn
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6 id="selected_tour_name" class="text-primary mb-2"></h6>
                                    <div class="small text-muted mb-3" id="selected_tour_date"></div>
                                    
                                    <div class="tour-features">
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-users text-success me-2"></i>
                                            <small>Số chỗ còn: <span id="selected_tour_seats" class="fw-bold"></span></small>
                                        </div>
                                        <div class="feature-item d-flex align-items-center mb-2">
                                            <i class="fas fa-money-bill-wave text-warning me-2"></i>
                                            <small>Giá: <span id="selected_tour_price" class="fw-bold"></span></small>
                                        </div>
                                        <div class="feature-item d-flex align-items-center">
                                            <i class="fas fa-clock text-info me-2"></i>
                                            <small>Giờ tập trung: <span id="selected_tour_time" class="fw-bold"></span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ưu đãi cho khách lẻ -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-gift me-2 text-warning"></i>
                                        Ưu Đãi Đặc Biệt
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled small mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Khách hàng chính miễn phí
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Giảm giá nhóm từ 4 người
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Hỗ trợ đón tận nơi
                                        </li>
                                        <li>
                                            <i class="fas fa-check text-success me-2"></i>
                                            Bảo hiểm du lịch
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" id="btn_submit">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Xác Nhận Đặt Tour
                                        </button>
                                        <a href="?act=dat-tour" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-times me-2"></i>
                                            Hủy & Quay Lại
                                        </a>
                                        
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Thông tin của bạn được bảo mật an toàn
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Template cho thành viên -->
<template id="template_thanh_vien">
    <div class="thanh-vien-item card mb-3 border-warning">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="card-title mb-0 text-warning">
                    <i class="fas fa-user me-2"></i>
                    Thành viên <span class="thanh-vien-stt"></span>
                </h6>
                <button type="button" class="btn btn-sm btn-outline-danger btn-xoa-thanh-vien">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" name="thanh_vien_ho_ten[]" class="form-control form-control-sm thanh-vien-ho-ten" required
                               placeholder="Nhập họ tên thành viên">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">CCCD/CMND <span class="text-danger">*</span></label>
                        <input type="text" name="thanh_vien_cccd[]" class="form-control form-control-sm" required
                               placeholder="Nhập số CCCD/CMND">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ngày Sinh <span class="text-danger">*</span></label>
                        <input type="date" name="thanh_vien_ngay_sinh[]" class="form-control form-control-sm" required 
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Giới Tính <span class="text-danger">*</span></label>
                        <select name="thanh_vien_gioi_tinh[]" class="form-control form-control-sm" required>
                            <option value="nam">Nam</option>
                            <option value="nữ">Nữ</option>
                            <option value="khác">Khác</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Yêu Cầu Đặc Biệt</label>
                        <input type="text" name="thanh_vien_yeu_cau[]" class="form-control form-control-sm" 
                               placeholder="Dị ứng, ăn chay, yêu cầu đặc biệt...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<?php include './views/layout/footer.php'; ?>

<style>
.form-control,
.form-control-sm,
.form-select {
    padding: 8px 14px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.card {
    border-radius: 8px;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    padding: 1rem 1.25rem;
}

.thanh-vien-item {
    border-left: 4px solid #ffc107;
    transition: all 0.3s ease;
}

.thanh-vien-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.tour-features .feature-item {
    padding: 4px 0;
}

.sticky-top {
    z-index: 100;
}

.display-4 {
    font-size: 3.5rem;
    font-weight: 300;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .input-group {
        margin-bottom: 10px;
        width: 100% !important;
    }
    
    .sticky-top {
        position: relative !important;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biến toàn cục
    let giaTour = 0;
    let soChoConLai = 0;
    let soThanhVien = 0;

    // Elements
    const form = document.getElementById('datTourForm');
    const lichKhoiHanhSelect = document.getElementById('lich_khoi_hanh_id');
    const danhSachThanhVien = document.getElementById('danh_sach_thanh_vien');
    const emptyState = document.getElementById('empty_state');
    const templateThanhVien = document.getElementById('template_thanh_vien');
    const soLuongThemInput = document.getElementById('so_luong_them');
    const btnThemThanhVien = document.getElementById('btn_them_thanh_vien');
    const btnThemNhanh = document.getElementById('btn_them_nhanh');
    const btnSubmit = document.getElementById('btn_submit');

    // Khởi tạo
    init();

    function init() {
        // Xử lý chọn lịch khởi hành
        lichKhoiHanhSelect.addEventListener('change', handleLichKhoiHanhChange);
        
        // Thêm thành viên đơn lẻ
        btnThemThanhVien.addEventListener('click', handleThemThanhVien);
        
        // Thêm nhanh nhiều thành viên
        btnThemNhanh.addEventListener('click', handleThemNhanh);
        
        // Form submission
        form.addEventListener('submit', handleFormSubmit);
        
        // Real-time validation
        setupRealTimeValidation();
        
        // Kiểm tra nếu có giá trị POST trước đó
        checkPreviousValues();
        
        // Khởi tạo tổng số khách mặc định là 0 (chỉ tính thành viên)
        updateAll();
    }

    function handleLichKhoiHanhChange() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            giaTour = parseFloat(selectedOption.dataset.gia);
            soChoConLai = parseInt(selectedOption.dataset.soCho);
            const tenTour = selectedOption.dataset.tour;
            const ngayBatDau = selectedOption.dataset.ngayBatDau;
            const ngayKetThuc = selectedOption.dataset.ngayKetThuc;
            const gioTapTrung = selectedOption.dataset.gioTapTrung;
            const diemTapTrung = selectedOption.dataset.diemTapTrung;

            // Hiển thị thông tin tour
            showTourInfo(tenTour, ngayBatDau, ngayKetThuc, gioTapTrung, diemTapTrung);
            
            // Cập nhật thông tin thanh toán
            updateThongTinThanhToan();
        } else {
            hideTourInfo();
            resetThongTinThanhToan();
        }
    }

    function showTourInfo(tenTour, ngayBatDau, ngayKetThuc, gioTapTrung, diemTapTrung) {
        // Hiển thị thông tin tour chi tiết
        const tourDetail = document.getElementById('tour_detail');
        tourDetail.classList.remove('d-none');
        document.getElementById('tour_name').textContent = tenTour;
        document.getElementById('tour_dates').textContent = `${formatDate(ngayBatDau)} - ${formatDate(ngayKetThuc)}`;
        document.getElementById('tour_price').textContent = formatCurrency(giaTour);
        document.getElementById('tour_seats').textContent = `Còn ${soChoConLai} chỗ`;
        document.getElementById('tour_meeting_point').textContent = diemTapTrung;
        document.getElementById('tour_meeting_time').textContent = gioTapTrung;

        // Hiển thị card tour được chọn
        const selectedTourCard = document.getElementById('selected_tour_card');
        selectedTourCard.classList.remove('d-none');
        document.getElementById('selected_tour_name').textContent = tenTour;
        document.getElementById('selected_tour_date').textContent = `${formatDate(ngayBatDau)} - ${formatDate(ngayKetThuc)}`;
        document.getElementById('selected_tour_seats').textContent = soChoConLai;
        document.getElementById('selected_tour_price').textContent = formatCurrency(giaTour);
        document.getElementById('selected_tour_time').textContent = gioTapTrung;

        // Cập nhật giá tour đơn
        document.getElementById('gia_tour_don').textContent = formatCurrency(giaTour);
    }

    function hideTourInfo() {
        document.getElementById('tour_detail').classList.add('d-none');
        const selectedTourCard = document.getElementById('selected_tour_card');
        selectedTourCard.classList.add('d-none');
    }

    function handleThemThanhVien() {
        if (!validateBeforeAddingMember()) return;
        themThanhVien();
    }

    function handleThemNhanh() {
        const soLuong = parseInt(soLuongThemInput.value);
        
        if (!validateBeforeAddingMember(soLuong)) return;
        
        for (let i = 0; i < soLuong; i++) {
            themThanhVien();
        }
        
        soLuongThemInput.value = '1';
        showAlert(`Đã thêm ${soLuong} thành viên vào danh sách`, 'success');
    }

    function validateBeforeAddingMember(soLuong = 1) {
        if (!lichKhoiHanhSelect.value) {
            showAlert('Vui lòng chọn lịch khởi hành trước!', 'warning');
            return false;
        }
        
        const tongSoKhachHienTai = getTongSoKhach(); // Chỉ tính thành viên
        const soChoConLaiHienTai = soChoConLai - tongSoKhachHienTai;
        
        if (soLuong > soChoConLaiHienTai) {
            showAlert(`Chỉ có thể thêm tối đa ${soChoConLaiHienTai} thành viên nữa.`, 'warning');
            return false;
        }
        
        return true;
    }

    function themThanhVien() {
        soThanhVien++;
        
        const clone = templateThanhVien.content.cloneNode(true);
        const thanhVienItem = clone.querySelector('.thanh-vien-item');
        thanhVienItem.querySelector('.thanh-vien-stt').textContent = soThanhVien;
        
        // Xử lý xóa thành viên
        thanhVienItem.querySelector('.btn-xoa-thanh-vien').addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa thành viên này?')) {
                thanhVienItem.remove();
                soThanhVien--;
                updateAll();
            }
        });

        // Real-time validation
        const hoTenInput = thanhVienItem.querySelector('.thanh-vien-ho-ten');
        hoTenInput.addEventListener('input', function() {
            updateAll();
        });

        danhSachThanhVien.appendChild(clone);
        updateAll();
    }

    // Hàm quan trọng: Tính tổng số khách (CHỈ tính thành viên đi kèm)
    function getTongSoKhach() {
        return getSoThanhVienHopLe(); // Chỉ trả về số thành viên hợp lệ
    }

    function getSoThanhVienHopLe() {
        let count = 0;
        const hoTenInputs = document.querySelectorAll('.thanh-vien-ho-ten');
        
        for (let input of hoTenInputs) {
            if (input.value.trim() !== '') {
                count++;
            }
        }
        return count;
    }

    function updateAll() {
        updateEmptyState();
        updateThongTinThanhToan();
        updateSoThanhVienBadge();
        updateSoThanhVienDisplay();
        updateThongBaoCho();
    }

    function updateEmptyState() {
        if (soThanhVien > 0) {
            emptyState.classList.add('d-none');
        } else {
            emptyState.classList.remove('d-none');
        }
    }

    function updateThongTinThanhToan() {
        const tongSoKhach = getTongSoKhach(); // Chỉ tính thành viên
        const tongTien = tongSoKhach * giaTour;
        
        document.getElementById('tong_so_khach').textContent = tongSoKhach;
        document.getElementById('so_luong_khach_display').textContent = tongSoKhach;
        document.getElementById('tong_tien').textContent = formatCurrency(tongTien);
    }

    function updateSoThanhVienDisplay() {
        const soThanhVienHopLe = getSoThanhVienHopLe();
        document.getElementById('so_thanh_vien_display').textContent = `${soThanhVienHopLe} người`;
    }

    function updateSoThanhVienBadge() {
        const soThanhVienHopLe = getSoThanhVienHopLe();
        document.getElementById('so_thanh_vien_badge').textContent = soThanhVienHopLe;
    }

    function updateThongBaoCho() {
        const tongSoKhach = getTongSoKhach(); // Chỉ tính thành viên
        
        if (soChoConLai > 0) {
            if (tongSoKhach <= soChoConLai) {
                const choConLai = soChoConLai - tongSoKhach;
                document.getElementById('thong_bao_cho').innerHTML = `<span class="text-success">Có thể đặt tối đa ${soChoConLai} khách. Đang đặt: ${tongSoKhach}/${soChoConLai} (còn ${choConLai} chỗ)</span>`;
            } else {
                document.getElementById('thong_bao_cho').innerHTML = `<span class="text-danger">Vượt quá số chỗ còn lại! Tối đa: ${soChoConLai} khách</span>`;
            }
        } else {
            document.getElementById('thong_bao_cho').innerHTML = `<span class="text-danger">Tour đã hết chỗ trống</span>`;
        }
    }

    function resetThongTinThanhToan() {
        document.getElementById('tong_so_khach').textContent = '0';
        document.getElementById('tong_tien').textContent = '0 VNĐ';
        document.getElementById('gia_tour_don').textContent = '0 VNĐ';
        document.getElementById('so_luong_khach_display').textContent = '0';
        document.getElementById('so_thanh_vien_display').textContent = '0 người';
        document.getElementById('thong_bao_cho').textContent = 'Vui lòng chọn lịch khởi hành để xem số chỗ còn lại';
        updateSoThanhVienBadge();
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Hiển thị loading
        showLoading(true);
        
        // Submit form
        this.submit();
    }

    function validateForm() {
        // Kiểm tra lịch khởi hành
        if (!lichKhoiHanhSelect.value) {
            showAlert('Vui lòng chọn lịch khởi hành!', 'warning');
            lichKhoiHanhSelect.focus();
            return false;
        }
        
        // Kiểm tra số chỗ (chỉ tính thành viên)
        const tongSoKhach = getTongSoKhach();
        if (tongSoKhach > soChoConLai) {
            showAlert(`Số khách (${tongSoKhach}) vượt quá số chỗ còn lại (${soChoConLai})!`, 'warning');
            return false;
        }
        
        // Kiểm tra thông tin khách hàng chính
        if (!validateKhachHangChinh()) {
            return false;
        }
        
        // Kiểm tra thông tin thành viên
        if (!validateThanhVien()) {
            return false;
        }
        
        return true;
    }

    function validateKhachHangChinh() {
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                const fieldName = field.labels[0]?.textContent?.replace('*', '') || 'thông tin';
                showAlert(`Vui lòng điền đầy đủ ${fieldName}!`, 'warning');
                field.focus();
                return false;
            }
        }
        
        // Kiểm tra email
        const emailField = form.querySelector('input[name="email"]');
        if (emailField.value && !isValidEmail(emailField.value)) {
            showAlert('Vui lòng nhập email hợp lệ!', 'warning');
            emailField.focus();
            return false;
        }
        
        return true;
    }

    function validateThanhVien() {
        const thanhVienHoTen = form.querySelectorAll('input[name="thanh_vien_ho_ten[]"]');
        const thanhVienCCCD = form.querySelectorAll('input[name="thanh_vien_cccd[]"]');
        const thanhVienNgaySinh = form.querySelectorAll('input[name="thanh_vien_ngay_sinh[]"]');
        
        for (let i = 0; i < thanhVienHoTen.length; i++) {
            const hasHoTen = thanhVienHoTen[i].value.trim();
            const hasCCCD = thanhVienCCCD[i].value.trim();
            const hasNgaySinh = thanhVienNgaySinh[i].value;
            
            if (hasHoTen && (!hasCCCD || !hasNgaySinh)) {
                showAlert(`Vui lòng điền đầy đủ CCCD và ngày sinh cho thành viên ${i + 1}!`, 'warning');
                thanhVienCCCD[i].focus();
                return false;
            }
            
            if (!hasHoTen && (hasCCCD || hasNgaySinh)) {
                showAlert(`Vui lòng điền đầy đủ họ tên cho thành viên ${i + 1}!`, 'warning');
                thanhVienHoTen[i].focus();
                return false;
            }
        }
        
        return true;
    }

    function setupRealTimeValidation() {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    }

    function validateField(field) {
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
        
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showLoading(show) {
        if (show) {
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang xử lý...';
            btnSubmit.disabled = true;
        } else {
            btnSubmit.innerHTML = '<i class="fas fa-check-circle me-2"></i> Xác Nhận Đặt Tour';
            btnSubmit.disabled = false;
        }
    }

    function showAlert(message, type = 'info') {
        const alertClass = {
            'success': 'alert-success',
            'warning': 'alert-warning', 
            'danger': 'alert-danger',
            'info': 'alert-info'
        }[type] || 'alert-info';
        
        const iconClass = {
            'success': 'fa-check-circle',
            'warning': 'fa-exclamation-triangle',
            'danger': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        }[type] || 'fa-info-circle';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${iconClass} me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">${type === 'success' ? 'Thành công!' : type === 'warning' ? 'Cảnh báo!' : 'Thông tin!'}</h6>
                    ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Thêm alert vào đầu form
        form.insertBefore(alertDiv, form.firstChild);
        
        // Tự động xóa alert sau 5 giây
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND' 
        }).format(amount);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    function checkPreviousValues() {
        // Nếu có giá trị POST trước đó, kích hoạt change event
        if (lichKhoiHanhSelect.value) {
            lichKhoiHanhSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>