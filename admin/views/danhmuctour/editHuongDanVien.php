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
                        Sửa Hướng Dẫn Viên
                    </a>
                    <div>
                        <a href="?act=huong-dan-vien" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo lỗi -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông báo thành công -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin hướng dẫn viên</h5>
                    </div>
                    <div class="card-body">
                        <form action="?act=update-huong-dan-vien" method="POST" id="editForm">
                            <input type="hidden" name="id" value="<?php echo $hdv['id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ho_ten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" 
                                               value="<?php echo htmlspecialchars($hdv['ho_ten']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                               value="<?php echo htmlspecialchars($hdv['so_dien_thoai'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?php echo htmlspecialchars($hdv['email'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="so_giay_phep_hanh_nghe" class="form-label">Số giấy phép hành nghề</label>
                                        <input type="text" class="form-control" id="so_giay_phep_hanh_nghe" name="so_giay_phep_hanh_nghe"
                                               value="<?php echo htmlspecialchars($hdv['so_giay_phep_hanh_nghe'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="loai_huong_dan_vien" class="form-label">Loại hướng dẫn viên</label>
                                        <select class="form-select" id="loai_huong_dan_vien" name="loai_huong_dan_vien" required>
                                            <option value="nội địa" <?php echo ($hdv['loai_huong_dan_vien'] ?? '') === 'nội địa' ? 'selected' : ''; ?>>Nội địa</option>
                                            <option value="quốc tế" <?php echo ($hdv['loai_huong_dan_vien'] ?? '') === 'quốc tế' ? 'selected' : ''; ?>>Quốc tế</option>
                                            <option value="chuyên tuyến" <?php echo ($hdv['loai_huong_dan_vien'] ?? '') === 'chuyên tuyến' ? 'selected' : ''; ?>>Chuyên tuyến</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="trang_thai" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="trang_thai" name="trang_thai" required>
                                            <option value="đang làm việc" <?php echo ($hdv['trang_thai'] ?? '') === 'đang làm việc' ? 'selected' : ''; ?>>Đang làm việc</option>
                                            <option value="tạm nghỉ" <?php echo ($hdv['trang_thai'] ?? '') === 'tạm nghỉ' ? 'selected' : ''; ?>>Tạm nghỉ</option>
                                            <option value="nghỉ việc" <?php echo ($hdv['trang_thai'] ?? '') === 'nghỉ việc' ? 'selected' : ''; ?>>Nghỉ việc</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="chuyen_mon" class="form-label">Chuyên môn</label>
                                <textarea class="form-control" id="chuyen_mon" name="chuyen_mon" rows="3"><?php echo htmlspecialchars($hdv['chuyen_mon'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2"><?php echo htmlspecialchars($hdv['dia_chi'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ngôn ngữ</label>
                                <div class="row">
                                    <?php
                                    $ngon_ngu_list = ['Tiếng Việt', 'Tiếng Anh', 'Tiếng Pháp', 'Tiếng Trung', 'Tiếng Nhật', 'Tiếng Hàn'];
                                    $selected_ngon_ngu = [];
                                    if (isset($hdv['ngon_ngu']) && $hdv['ngon_ngu']) {
                                        $selected_ngon_ngu = json_decode($hdv['ngon_ngu'], true) ?: [];
                                    }
                                    ?>
                                    <?php foreach ($ngon_ngu_list as $nn): ?>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="ngon_ngu[]" 
                                                       value="<?php echo $nn; ?>" 
                                                       id="ngon_ngu_<?php echo str_replace(' ', '_', $nn); ?>"
                                                       <?php echo in_array($nn, $selected_ngon_ngu) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="ngon_ngu_<?php echo str_replace(' ', '_', $nn); ?>">
                                                    <?php echo $nn; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="ghi_chu" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3"><?php echo htmlspecialchars($hdv['ghi_chu'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="?act=huong-dan-vien" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Validate form trước khi submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    const hoTen = document.getElementById('ho_ten').value.trim();
    if (!hoTen) {
        e.preventDefault();
        alert('Vui lòng nhập họ tên hướng dẫn viên');
        document.getElementById('ho_ten').focus();
        return false;
    }
    
    // Kiểm tra checkbox ngôn ngữ - ít nhất chọn 1
    const ngonNguChecked = document.querySelectorAll('input[name="ngon_ngu[]"]:checked');
    if (ngonNguChecked.length === 0) {
        if (!confirm('Bạn chưa chọn ngôn ngữ nào. Bạn có muốn tiếp tục không?')) {
            e.preventDefault();
            return false;
        }
    }
    
    return true;
});

// Hiển thị loading khi submit
document.getElementById('editForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang cập nhật...';
    submitBtn.disabled = true;
});
</script>

<?php include './views/layout/footer.php'; ?>