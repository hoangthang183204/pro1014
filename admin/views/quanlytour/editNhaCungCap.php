<?php
// File: views/quanlytour/editNhaCungCap.php
?>

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
                        <i class="fas fa-edit me-2"></i>
                        Sửa Nhà Cung Cấp: <?php echo htmlspecialchars($nha_cung_cap['ten_nha_cung_cap']); ?>
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
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Thông tin nhà cung cấp
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=tour-nha-cung-cap-update" method="POST" id="editNhaCungCapForm">
                            <input type="hidden" name="id" value="<?php echo $nha_cung_cap['id']; ?>">
                            
                            <div class="row g-3">
                                <!-- Tên nhà cung cấp -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Tên nhà cung cấp <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_nha_cung_cap" class="form-control" required
                                           value="<?php echo htmlspecialchars($nha_cung_cap['ten_nha_cung_cap']); ?>"
                                           placeholder="VD: Công ty Vận tải Phương Trang">
                                </div>
                                
                                <!-- Loại dịch vụ -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Loại dịch vụ <span class="text-danger">*</span></label>
                                    <select name="loai_dich_vu" class="form-select" required>
                                        <option value="">-- Chọn loại dịch vụ --</option>
                                        <option value="vận chuyển" <?php echo $nha_cung_cap['loai_dich_vu'] == 'vận chuyển' ? 'selected' : ''; ?>>🚌 Vận chuyển</option>
                                        <option value="khách sạn" <?php echo $nha_cung_cap['loai_dich_vu'] == 'khách sạn' ? 'selected' : ''; ?>>🏨 Khách sạn</option>
                                        <option value="nhà hàng" <?php echo $nha_cung_cap['loai_dich_vu'] == 'nhà hàng' ? 'selected' : ''; ?>>🍽️ Nhà hàng</option>
                                        <option value="vé máy bay" <?php echo $nha_cung_cap['loai_dich_vu'] == 'vé máy bay' ? 'selected' : ''; ?>>✈️ Vé máy bay</option>
                                        <option value="vé tham quan" <?php echo $nha_cung_cap['loai_dich_vu'] == 'vé tham quan' ? 'selected' : ''; ?>>🎫 Vé tham quan</option>
                                        <option value="visa" <?php echo $nha_cung_cap['loai_dich_vu'] == 'visa' ? 'selected' : ''; ?>>📝 Visa</option>
                                        <option value="bảo hiểm" <?php echo $nha_cung_cap['loai_dich_vu'] == 'bảo hiểm' ? 'selected' : ''; ?>>🛡️ Bảo hiểm</option>
                                        <option value="hướng dẫn viên" <?php echo $nha_cung_cap['loai_dich_vu'] == 'hướng dẫn viên' ? 'selected' : ''; ?>>🎤 Hướng dẫn viên</option>
                                        <option value="khác" <?php echo $nha_cung_cap['loai_dich_vu'] == 'khác' ? 'selected' : ''; ?>>📦 Khác</option>
                                    </select>
                                </div>
                                
                                <!-- Đánh giá -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Đánh giá (0-5)</label>
                                    <div class="d-flex align-items-center">
                                        <input type="range" name="danh_gia" class="form-range" min="0" max="5" step="0.5" 
                                               value="<?php echo $nha_cung_cap['danh_gia'] ?? 0; ?>"
                                               oninput="document.getElementById('ratingValue').innerText = this.value">
                                        <span class="ms-3">
                                            <span id="ratingValue" class="fw-bold"><?php echo $nha_cung_cap['danh_gia'] ?? 0; ?></span> 
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
                                               value="<?php echo htmlspecialchars($nha_cung_cap['so_dien_thoai'] ?? ''); ?>"
                                               placeholder="VD: 0909123456">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" 
                                               value="<?php echo htmlspecialchars($nha_cung_cap['email'] ?? ''); ?>"
                                               placeholder="VD: contact@company.com">
                                    </div>
                                </div>
                                
                                <!-- Địa chỉ -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" name="dia_chi" class="form-control" 
                                               value="<?php echo htmlspecialchars($nha_cung_cap['dia_chi'] ?? ''); ?>"
                                               placeholder="VD: 123 Nguyễn Văn Linh, Quận 7, TP.HCM">
                                    </div>
                                </div>
                                
                                <!-- Mô tả -->
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Mô tả chi tiết</label>
                                    <textarea name="mo_ta" class="form-control" rows="4" 
                                              placeholder="Mô tả về nhà cung cấp, dịch vụ cung cấp..."><?php echo htmlspecialchars($nha_cung_cap['mo_ta'] ?? ''); ?></textarea>
                                </div>
                                
                                <!-- Thông tin hệ thống -->
                                <div class="col-md-12 mt-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6><i class="fas fa-info-circle me-2"></i>Thông tin hệ thống</h6>
                                            <div class="row small">
                                                <div class="col-md-4">
                                                    <strong>Ngày tạo:</strong><br>
                                                    <?php echo date('d/m/Y H:i', strtotime($nha_cung_cap['created_at'])); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Ngày cập nhật:</strong><br>
                                                    <?php echo !empty($nha_cung_cap['updated_at']) ? date('d/m/Y H:i', strtotime($nha_cung_cap['updated_at'])) : 'Chưa cập nhật'; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>ID:</strong><br>
                                                    <?php echo $nha_cung_cap['id']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Nút hành động -->
                                <div class="col-md-12">
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                                            </button>
                                        </div>
                                        <div class="btn-group">
                                            <a href="?act=tour-nha-cung-cap-list" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> Hủy bỏ
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Lưu thay đổi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Thống kê sử dụng -->
                <?php 
                // Lấy thông tin sử dụng (nếu cần)
                // $tour_su_dung = $this->tourModel->getToursUsingNhaCungCap($nha_cung_cap['id']);
                ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê sử dụng</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Thông tin:</strong> 
                            <?php 
                            // Hiển thị thông báo về việc sử dụng
                            if (isset($tour_su_dung) && count($tour_su_dung) > 0) {
                                echo "Nhà cung cấp này đang được sử dụng trong " . count($tour_su_dung) . " tour. Không thể xóa.";
                            } else {
                                echo "Nhà cung cấp này chưa được sử dụng trong tour nào.";
                            }
                            ?>
                        </div>
                        
                        <!-- Có thể thêm bảng thống kê chi tiết ở đây -->
                        <!--
                        <?php if (isset($tour_su_dung) && !empty($tour_su_dung)): ?>
                        <div class="table-responsive mt-3">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th>Loại dịch vụ</th>
                                        <th>Ngày sử dụng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tour_su_dung as $tour): ?>
                                    <tr>
                                        <td><?php echo $tour['ten_tour']; ?></td>
                                        <td><span class="badge bg-info"><?php echo $tour['loai_phan_cong']; ?></span></td>
                                        <td><?php echo date('d/m/Y', strtotime($tour['ngay_bat_dau'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
    
    // Validate form
    $('#editNhaCungCapForm').on('submit', function(e) {
        let isValid = true;
        let errorMessages = [];
        
        // Check tên nhà cung cấp
        const tenNCC = $('input[name="ten_nha_cung_cap"]').val().trim();
        if (tenNCC.length < 3) {
            errorMessages.push('Tên nhà cung cấp phải có ít nhất 3 ký tự');
            isValid = false;
        }
        
        // Check loại dịch vụ
        const loaiDV = $('select[name="loai_dich_vu"]').val();
        if (!loaiDV) {
            errorMessages.push('Vui lòng chọn loại dịch vụ');
            isValid = false;
        }
        
        // Check email format
        const email = $('input[name="email"]').val().trim();
        if (email && !isValidEmail(email)) {
            errorMessages.push('Email không đúng định dạng');
            isValid = false;
        }
        
        // Check phone format (nếu có)
        const phone = $('input[name="so_dien_thoai"]').val().trim();
        if (phone && !isValidPhone(phone)) {
            errorMessages.push('Số điện thoại không đúng định dạng (10-11 số)');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showErrors(errorMessages);
        }
    });
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function isValidPhone(phone) {
        const re = /^(0|\+84)(\d{9,10})$/;
        return re.test(phone);
    }
    
    function showErrors(messages) {
        // Xóa thông báo cũ
        $('.alert').remove();
        
        // Tạo thông báo lỗi
        let alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Vui lòng sửa các lỗi sau:</strong>
                <ul class="mb-0 mt-2">
        `;
        
        messages.forEach(message => {
            alertHtml += `<li>${message}</li>`;
        });
        
        alertHtml += `
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Thêm thông báo vào đầu container
        $('.container.mt-4').prepend(alertHtml);
        
        // Cuộn đến thông báo
        $('html, body').animate({
            scrollTop: $('.alert').offset().top - 100
        }, 500);
    }
    
    // Format số điện thoại khi nhập
    $('input[name="so_dien_thoai"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.startsWith('84')) {
            value = '0' + value.substring(2);
        }
        $(this).val(value);
    });
});
</script>

<style>
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    min-width: 45px;
    justify-content: center;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
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

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
    border-left-color: #17a2b8;
}

.form-control:focus, .form-select:focus, .form-range:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-range::-webkit-slider-thumb {
    background: #0d6efd;
    border: none;
}

.form-range::-moz-range-thumb {
    background: #0d6efd;
    border: none;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
}

.btn-group .btn:first-child {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

.btn-group .btn:last-child {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}

hr {
    opacity: 0.2;
}
</style>