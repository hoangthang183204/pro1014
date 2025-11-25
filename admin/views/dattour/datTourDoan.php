<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-users me-2"></i>
                        Đặt Tour Theo Đoàn
                    </a>
                    <div class="btn-group">
                        <a href="?act=dat-tour" class="btn btn-outline-light mx-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="?act=dat-tour-le" class="btn btn-outline-light">
                            <i class="fas fa-user me-1"></i> Đặt Khách Lẻ
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

                <form method="POST" action="?act=dat-tour-store-booking" id="datTourForm" class="needs-validation" novalidate>
                    <input type="hidden" name="loai_khach" value="doan">
                    
                    <div class="row">
                        <!-- Cột trái - Thông tin chính -->
                        <div class="col-lg-8">
                            <!-- Thông tin tour -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                                        Thông Tin Tour & Lịch Trình
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">Chọn Lịch Khởi Hành <span class="text-danger">*</span></label>
                                                <select name="lich_khoi_hanh_id" id="lich_khoi_hanh_id" class="form-control" required>
                                                    <option value="">-- Vui lòng chọn lịch khởi hành --</option>
                                                    <?php foreach ($lich_khoi_hanh_list as $lkh): ?>
                                                        <?php 
                                                        $so_cho_con_lai = $lkh['so_cho_con_lai'] ?? $lkh['so_cho_toi_da'];
                                                        $disabled = $so_cho_con_lai <= 0 ? 'disabled' : '';
                                                        ?>
                                                        <option value="<?php echo $lkh['id']; ?>" 
                                                                data-gia="<?php echo $lkh['gia_tour']; ?>"
                                                                data-so-cho="<?php echo $so_cho_con_lai; ?>"
                                                                data-tour="<?php echo htmlspecialchars($lkh['ten_tour']); ?>"
                                                                data-ngay-bat-dau="<?php echo $lkh['ngay_bat_dau']; ?>"
                                                                data-ngay-ket-thuc="<?php echo $lkh['ngay_ket_thuc']; ?>"
                                                                data-gio-tap-trung="<?php echo $lkh['gio_tap_trung']; ?>"
                                                                data-diem-tap-trung="<?php echo htmlspecialchars($lkh['diem_tap_trung']); ?>"
                                                                <?php echo $disabled; ?>>
                                                            <?php echo htmlspecialchars($lkh['ten_tour']); ?> | 
                                                            <?php echo date('d/m/Y', strtotime($lkh['ngay_bat_dau'])); ?> - 
                                                            <?php echo date('d/m/Y', strtotime($lkh['ngay_ket_thuc'])); ?> | 
                                                            <?php echo number_format($lkh['gia_tour'], 0, ',', '.'); ?> VNĐ |
                                                            <span class="<?php echo $so_cho_con_lai > 0 ? 'text-success' : 'text-danger'; ?>">
                                                                <?php echo $so_cho_con_lai > 0 ? "Còn {$so_cho_con_lai} chỗ" : "HẾT CHỖ"; ?>
                                                            </span>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Vui lòng chọn lịch khởi hành.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thông tin tour chi tiết -->
                                    <div id="tour_detail" class="d-none">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card bg-light rounded p-3 mb-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                                        <strong>Thời gian tour</strong>
                                                    </div>
                                                    <div class="ps-4">
                                                        <span id="thoi_gian_info" class="text-dark"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card bg-light rounded p-3 mb-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                                        <strong>Điểm tập trung</strong>
                                                    </div>
                                                    <div class="ps-4">
                                                        <span id="diem_tap_trung_info" class="text-dark"></span>
                                                        <br>
                                                        <small class="text-muted" id="gio_tap_trung_info"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin đoàn -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users me-2 text-info"></i>
                                        Thông Tin Đoàn
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tên Đoàn <span class="text-danger">*</span></label>
                                                <input type="text" name="ten_doan" class="form-control" required 
                                                       placeholder="Nhập tên đoàn (VD: Đoàn gia đình, Đoàn bạn bè, Đoàn công ty...)">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập tên đoàn.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Loại Đoàn <span class="text-danger">*</span></label>
                                                <select name="loai_doan" class="form-control" required>
                                                    <option value="">-- Chọn loại đoàn --</option>
                                                    <option value="Gia đình">Gia đình</option>
                                                    <option value="Bạn bè">Bạn bè</option>
                                                    <option value="Công ty">Công ty</option>
                                                    <option value="Trường học">Trường học</option>
                                                    <option value="Đoàn thể">Đoàn thể</option>
                                                    <option value="Khác">Khác</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Vui lòng chọn loại đoàn.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin người đại diện -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-tie me-2 text-warning"></i>
                                        Thông Tin Người Đại Diện
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info mb-3 border-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <small>Thông tin người đại diện đoàn (người liên hệ và chịu trách nhiệm đặt tour)</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Họ Tên Người Đại Diện <span class="text-danger">*</span></label>
                                                <input type="text" name="ho_ten" class="form-control" required 
                                                       placeholder="Nhập họ tên đầy đủ">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập họ tên người đại diện.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Số Điện Thoại <span class="text-danger">*</span></label>
                                                <input type="text" name="so_dien_thoai" class="form-control" required 
                                                       placeholder="Nhập số điện thoại">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập số điện thoại.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" required
                                                       placeholder="Nhập địa chỉ email">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập email hợp lệ.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">CCCD/CMND <span class="text-danger">*</span></label>
                                                <input type="text" name="cccd" class="form-control" required
                                                       placeholder="Nhập số CCCD/CMND">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập số CCCD/CMND.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Ngày Sinh <span class="text-danger">*</span></label>
                                                <input type="date" name="ngay_sinh" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
                                                <div class="invalid-feedback">
                                                    Vui lòng chọn ngày sinh.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Giới Tính <span class="text-danger">*</span></label>
                                                <select name="gioi_tinh" class="form-control" required>
                                                    <option value="">-- Chọn giới tính --</option>
                                                    <option value="nam">Nam</option>
                                                    <option value="nữ">Nữ</option>
                                                    <option value="khác">Khác</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Vui lòng chọn giới tính.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Địa Chỉ <span class="text-danger">*</span></label>
                                                <input type="text" name="dia_chi" class="form-control" required
                                                       placeholder="Nhập địa chỉ liên hệ">
                                                <div class="invalid-feedback">
                                                    Vui lòng nhập địa chỉ.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Danh sách thành viên tham gia -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-users me-2 text-success"></i>
                                            Danh Sách Thành Viên Tham Gia
                                            <span class="badge bg-success ms-2" id="so_thanh_vien_badge">0</span>
                                        </h5>
                                        <div class="btn-group">
                                            <div class="input-group input-group-sm me-2" style="width: 180px;">
                                                <input type="number" id="so_luong_thanh_vien" class="form-control" 
                                                       placeholder="Số lượng" min="1" max="50" value="1">
                                                <button type="button" id="btnThemNhanh" class="btn btn-outline-success">
                                                    <i class="fas fa-bolt me-1"></i> Thêm nhanh
                                                </button>
                                            </div>
                                            <button type="button" id="btnThemThanhVien" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus me-1"></i> Thêm thành viên
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success mb-3 border-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <small>Thêm danh sách thành viên tham gia tour. Thông tin CCCD và ngày sinh là bắt buộc để làm thủ tục.</small>
                                        </div>
                                    </div>
                                    
                                    <div id="danh_sach_thanh_vien">
                                        <!-- Thành viên sẽ được thêm động ở đây -->
                                    </div>
                                    
                                    <div class="text-center py-4" id="empty_state">
                                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Chưa có thành viên nào</h6>
                                        <p class="text-muted small">Nhấn "Thêm thành viên" để thêm thành viên tham gia</p>
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
                                              placeholder="Ghi chú thêm, yêu cầu đặc biệt cho đoàn, yêu cầu về phòng họp, teambuilding, chế độ ăn uống..."></textarea>
                                    <div class="form-text">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Thông tin này sẽ giúp chúng tôi chuẩn bị tốt hơn cho chuyến đi của đoàn bạn.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cột phải - Tổng hợp & Thanh toán -->
                        <div class="col-lg-4">
                            <!-- Thông tin tổng hợp -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-receipt me-2 text-primary"></i>
                                        Tổng Hợp Đơn Hàng
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="display-4 text-primary fw-bold" id="tong_so_thanh_vien">0</div>
                                        <div class="text-muted">Tổng số thành viên</div>
                                    </div>

                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Giá tour/người:</span>
                                            <span class="fw-bold" id="gia_tour_don">0 VNĐ</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Số lượng thành viên:</span>
                                            <span class="fw-bold" id="so_luong_thanh_vien_display">0</span>
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

                            <!-- Ưu đãi cho đoàn -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-gift me-2 text-warning"></i>
                                        Ưu Đãi Cho Đoàn
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled small mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Giảm giá từ 10 thành viên
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Tặng quà lưu niệm cho đoàn
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Hướng dẫn viên riêng
                                        </li>
                                        <li>
                                            <i class="fas fa-check text-success me-2"></i>
                                            Hỗ trợ chụp ảnh đoàn
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2" id="btnSubmit">
                                            <i class="fas fa-users me-2"></i>
                                            Đặt Tour Theo Đoàn
                                        </button>
                                        <a href="?act=dat-tour" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-times me-2"></i>
                                            Hủy & Quay Lại
                                        </a>
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Hỗ trợ tốt nhất cho các đoàn du lịch
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
    <div class="thanh-vien-item card mb-3 border-success">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="card-title mb-0 text-success">
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
                        <input type="date" name="thanh_vien_ngay_sinh[]" class="form-control form-control-sm" required max="<?php echo date('Y-m-d'); ?>">
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

<?php include 'views/layout/footer.php'; ?>

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
    border-left: 4px solid #28a745;
    transition: all 0.3s ease;
}

.thanh-vien-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.info-card {
    border-left: 4px solid #007bff;
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
    
    .input-group {
        width: 100% !important;
        margin-bottom: 10px;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các biến và event listeners
    const form = document.getElementById('datTourForm');
    const lichKhoiHanhSelect = document.getElementById('lich_khoi_hanh_id');
    const danhSachThanhVien = document.getElementById('danh_sach_thanh_vien');
    const emptyState = document.getElementById('empty_state');
    const templateThanhVien = document.getElementById('template_thanh_vien');
    const soLuongThanhVienInput = document.getElementById('so_luong_thanh_vien');
    
    let soThanhVien = 0;
    let giaTour = 0;
    let soChoConLai = 0;

    // Xử lý chọn lịch khởi hành
    lichKhoiHanhSelect.addEventListener('change', function() {
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
            document.getElementById('tour_detail').classList.remove('d-none');
            document.getElementById('thoi_gian_info').textContent = 
                `${formatDate(ngayBatDau)} - ${formatDate(ngayKetThuc)}`;
            document.getElementById('diem_tap_trung_info').textContent = diemTapTrung;
            document.getElementById('gio_tap_trung_info').textContent = `Giờ tập trung: ${gioTapTrung}`;

            // Hiển thị card tour được chọn
            const selectedTourCard = document.getElementById('selected_tour_card');
            selectedTourCard.classList.remove('d-none');
            document.getElementById('selected_tour_name').textContent = tenTour;
            document.getElementById('selected_tour_date').textContent = 
                `${formatDate(ngayBatDau)} - ${formatDate(ngayKetThuc)}`;
            document.getElementById('selected_tour_seats').textContent = soChoConLai;
            document.getElementById('selected_tour_price').textContent = formatCurrency(giaTour);
            document.getElementById('selected_tour_time').textContent = gioTapTrung;

            // Cập nhật thông tin thanh toán
            document.getElementById('gia_tour_don').textContent = formatCurrency(giaTour);
            updateTongTien();
            updateThongBaoCho();
        } else {
            document.getElementById('tour_detail').classList.add('d-none');
            document.getElementById('selected_tour_card').classList.add('d-none');
            resetThongTinThanhToan();
        }
    });

    // Thêm thành viên đơn lẻ
    document.getElementById('btnThemThanhVien').addEventListener('click', function() {
        if (soChoConLai > 0 && soThanhVien < soChoConLai) {
            themThanhVien();
        } else {
            showAlert('Số chỗ còn lại không đủ để thêm thành viên mới.', 'warning');
        }
    });

    // Thêm nhanh nhiều thành viên
    document.getElementById('btnThemNhanh').addEventListener('click', function() {
        const soLuong = parseInt(soLuongThanhVienInput.value);
        
        if (!soLuong || soLuong < 1) {
            showAlert('Vui lòng nhập số lượng hợp lệ!', 'warning');
            return;
        }
        
        if (soThanhVien + soLuong <= soChoConLai) {
            for (let i = 0; i < soLuong; i++) {
                themThanhVien();
            }
            soLuongThanhVienInput.value = '1';
            showAlert(`Đã thêm ${soLuong} thành viên vào danh sách`, 'success');
        } else {
            const coTheThem = soChoConLai - soThanhVien;
            showAlert(`Chỉ có thể thêm tối đa ${coTheThem} thành viên nữa.`, 'warning');
        }
    });

    // Hàm thêm thành viên
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
                updateTongTien();
                updateEmptyState();
                updateSoThanhVienBadge();
            }
        });

        // Validate real-time
        const hoTenInput = thanhVienItem.querySelector('.thanh-vien-ho-ten');
        hoTenInput.addEventListener('input', function() {
            updateTongTien();
        });

        danhSachThanhVien.appendChild(clone);
        updateEmptyState();
        updateTongTien();
        updateSoThanhVienBadge();
    }

    // Cập nhật trạng thái empty
    function updateEmptyState() {
        if (soThanhVien > 0) {
            emptyState.classList.add('d-none');
        } else {
            emptyState.classList.remove('d-none');
        }
    }

    // Cập nhật tổng tiền
    function updateTongTien() {
        const tongSoThanhVien = getSoThanhVienHopLe();
        const tongTien = tongSoThanhVien * giaTour;
        
        document.getElementById('tong_so_thanh_vien').textContent = tongSoThanhVien;
        document.getElementById('so_luong_thanh_vien_display').textContent = tongSoThanhVien;
        document.getElementById('tong_tien').textContent = formatCurrency(tongTien);
        
        updateThongBaoCho();
        updateSoThanhVienBadge();
    }

    // Đếm số thành viên hợp lệ (có tên)
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

    // Cập nhật thông báo chỗ
    function updateThongBaoCho() {
        const tongSoThanhVien = getSoThanhVienHopLe();
        const thongBao = document.getElementById('thong_bao_cho');
        
        if (soChoConLai > 0) {
            if (tongSoThanhVien <= soChoConLai) {
                thongBao.innerHTML = `<span class="text-success">Có thể đặt tối đa ${soChoConLai} thành viên. Đang đặt: ${tongSoThanhVien}/${soChoConLai}</span>`;
            } else {
                thongBao.innerHTML = `<span class="text-danger">Vượt quá số chỗ còn lại! Tối đa: ${soChoConLai} thành viên</span>`;
            }
        } else {
            thongBao.innerHTML = `<span class="text-danger">Tour đã hết chỗ trống</span>`;
        }
    }

    // Cập nhật badge số thành viên
    function updateSoThanhVienBadge() {
        const soThanhVienHopLe = getSoThanhVienHopLe();
        document.getElementById('so_thanh_vien_badge').textContent = soThanhVienHopLe;
    }

    // Reset thông tin thanh toán
    function resetThongTinThanhToan() {
        document.getElementById('tong_so_thanh_vien').textContent = '0';
        document.getElementById('tong_tien').textContent = '0 VNĐ';
        document.getElementById('gia_tour_don').textContent = '0 VNĐ';
        document.getElementById('so_luong_thanh_vien_display').textContent = '0';
        document.getElementById('thong_bao_cho').textContent = 'Vui lòng chọn lịch khởi hành để xem số chỗ còn lại';
        updateSoThanhVienBadge();
    }

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND' 
        }).format(amount);
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    // Show alert
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

    // Form validation
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Hiển thị loading
        showLoading(true);
        
        // Submit form
        this.submit();
    });

    function validateForm() {
        // Kiểm tra lịch khởi hành
        if (!lichKhoiHanhSelect.value) {
            showAlert('Vui lòng chọn lịch khởi hành!', 'warning');
            lichKhoiHanhSelect.focus();
            return false;
        }
        
        // Kiểm tra số chỗ
        const tongSoThanhVien = getSoThanhVienHopLe();
        if (tongSoThanhVien === 0) {
            showAlert('Vui lòng thêm ít nhất một thành viên tham gia.', 'warning');
            return false;
        }
        
        if (tongSoThanhVien > soChoConLai) {
            showAlert(`Số lượng thành viên (${tongSoThanhVien}) vượt quá số chỗ còn lại (${soChoConLai}).`, 'warning');
            return false;
        }
        
        // Kiểm tra thông tin đoàn
        const tenDoan = document.querySelector('input[name="ten_doan"]').value.trim();
        if (!tenDoan) {
            showAlert('Vui lòng nhập tên đoàn.', 'warning');
            document.querySelector('input[name="ten_doan"]').focus();
            return false;
        }

        // Kiểm tra loại đoàn
        const loaiDoan = document.querySelector('select[name="loai_doan"]').value;
        if (!loaiDoan) {
            showAlert('Vui lòng chọn loại đoàn.', 'warning');
            document.querySelector('select[name="loai_doan"]').focus();
            return false;
        }

        // Kiểm tra thông tin người đại diện
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                const fieldName = field.labels[0]?.textContent?.replace('*', '') || 'thông tin';
                showAlert(`Vui lòng điền đầy đủ ${fieldName}!`, 'warning');
                field.focus();
                return false;
            }
        }
        
        return true;
    }

    function showLoading(show) {
        const btnSubmit = document.getElementById('btnSubmit');
        if (show) {
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang xử lý...';
            btnSubmit.disabled = true;
        } else {
            btnSubmit.innerHTML = '<i class="fas fa-users me-2"></i> Đặt Tour Theo Đoàn';
            btnSubmit.disabled = false;
        }
    }
});
</script>