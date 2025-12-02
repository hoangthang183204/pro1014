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
                        <i class="fas fa-plus-circle me-2"></i>
                        Thêm Nhà Cung Cấp Mới
                    </a>
                    <div>
                        <a href="?act=tour-nha-cung-cap-list" class="btn btn-outline-light">
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin nhà cung cấp</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=tour-nha-cung-cap-store" method="POST" id="nhaCungCapForm">
                            <div class="row g-3">
                                <!-- Tên nhà cung cấp -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Tên nhà cung cấp <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_nha_cung_cap" class="form-control" required
                                           placeholder="VD: Công ty Vận tải Phương Trang">
                                </div>
                                
                                <!-- Loại dịch vụ -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Loại dịch vụ <span class="text-danger">*</span></label>
                                    <select name="loai_dich_vu" class="form-select" required>
                                        <option value="">-- Chọn loại dịch vụ --</option>
                                        <option value="vận chuyển">🚌 Vận chuyển</option>
                                        <option value="khách sạn">🏨 Khách sạn</option>
                                        <option value="nhà hàng">🍽️ Nhà hàng</option>
                                        <option value="vé máy bay">✈️ Vé máy bay</option>
                                        <option value="vé tham quan">🎫 Vé tham quan</option>
                                        <option value="visa">📝 Visa</option>
                                        <option value="bảo hiểm">🛡️ Bảo hiểm</option>
                                        <option value="hướng dẫn viên">🎤 Hướng dẫn viên</option>
                                        <option value="khác">📦 Khác</option>
                                    </select>
                                </div>
                                
                                <!-- Đánh giá -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Đánh giá (0-5)</label>
                                    <div class="d-flex align-items-center">
                                        <input type="range" name="danh_gia" class="form-range" min="0" max="5" step="0.5" value="0">
                                        <span class="ms-3">
                                            <span id="ratingValue" class="fw-bold">0</span> 
                                            <i class="fas fa-star text-warning ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Thông tin liên hệ -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số điện thoại</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" name="so_dien_thoai" class="form-control" 
                                               placeholder="VD: 0909123456">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" 
                                               placeholder="VD: contact@company.com">
                                    </div>
                                </div>
                                
                                <!-- Địa chỉ -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" name="dia_chi" class="form-control" 
                                               placeholder="VD: 123 Nguyễn Văn Linh, Quận 7, TP.HCM">
                                    </div>
                                </div>
                                
                                <!-- Mô tả -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Mô tả chi tiết</label>
                                    <textarea name="mo_ta" class="form-control" rows="4" 
                                              placeholder="Mô tả về nhà cung cấp, dịch vụ cung cấp..."></textarea>
                                </div>
                                
                                <!-- Nút hành động -->
                                <div class="col-md-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="?act=tour-nha-cung-cap-list" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i> Hủy bỏ
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Lưu nhà cung cấp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Hướng dẫn -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>Hướng dẫn nhập thông tin</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-lightbulb text-warning me-2"></i>Mẹo nhập thông tin:</h6>
                                <ul class="small">
                                    <li>Tên nhà cung cấp nên rõ ràng, dễ nhận biết</li>
                                    <li>Chọn đúng loại dịch vụ chính mà nhà cung cấp cung cấp</li>
                                    <li>Nhập đầy đủ thông tin liên hệ để dễ dàng liên hệ khi cần</li>
                                    <li>Mô tả chi tiết giúp dễ dàng tìm kiếm và phân loại</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-exclamation-triangle text-danger me-2"></i>Lưu ý quan trọng:</h6>
                                <ul class="small">
                                    <li>Thông tin có dấu <span class="text-danger">*</span> là bắt buộc</li>
                                    <li>Nhà cung cấp sau khi tạo có thể được gán vào các tour</li>
                                    <li>Không thể xóa nhà cung cấp đang được sử dụng trong tour</li>
                                    <li>Đánh giá giúp lựa chọn nhà cung cấp tốt hơn</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Hiển thị giá trị đánh giá
    $('input[name="danh_gia"]').on('input', function() {
        $('#ratingValue').text($(this).val());
    });
    
    // Validate form
    $('#nhaCungCapForm').on('submit', function(e) {
        let isValid = true;
        
        // Check tên nhà cung cấp
        const tenNCC = $('input[name="ten_nha_cung_cap"]').val().trim();
        if (tenNCC.length < 3) {
            showError('Tên nhà cung cấp phải có ít nhất 3 ký tự');
            isValid = false;
        }
        
        // Check loại dịch vụ
        const loaiDV = $('select[name="loai_dich_vu"]').val();
        if (!loaiDV) {
            showError('Vui lòng chọn loại dịch vụ');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function showError(message) {
        // Xóa thông báo cũ
        $('.alert').remove();
        
        // Hiển thị thông báo mới
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('.container.mt-4').prepend(alertHtml);
        
        // Tự động ẩn sau 5s
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
});
</script>

<style>
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-left-color: #dc3545;
}

input:focus, select:focus, textarea:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>