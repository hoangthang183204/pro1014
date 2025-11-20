<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Thêm Tour Mới</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?act=/"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="?act=tour">Quản lý Tour</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Thông báo -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-ban"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-check"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Thông tin Tour Mới
                    </h3>
                    <div class="card-tools">
                        <a href="?act=tour" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="?act=tour-store" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ma_tour">Mã Tour <span class="text-danger">*</span></label>
                                    <input type="text" id="ma_tour" name="ma_tour" class="form-control" required
                                        placeholder="VD: DL-001, QT-001, CUSTOM-001"
                                        value="<?php echo htmlspecialchars($_POST['ma_tour'] ?? ''); ?>">
                                    <small class="form-text text-muted">Mã tour duy nhất để phân biệt các tour</small>
                                </div>

                                <div class="form-group">
                                    <label for="ten_tour">Tên Tour <span class="text-danger">*</span></label>
                                    <input type="text" id="ten_tour" name="ten_tour" class="form-control" required
                                        placeholder="Nhập tên tour đầy đủ"
                                        value="<?php echo htmlspecialchars($_POST['ten_tour'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="danh_muc_id">Danh Mục <span class="text-danger">*</span></label>
                                    <select id="danh_muc_id" name="danh_muc_id" class="form-control" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($danh_muc_list as $danh_muc): ?>
                                            <option value="<?php echo $danh_muc['id']; ?>" 
                                                <?php echo (isset($_POST['danh_muc_id']) && $_POST['danh_muc_id'] == $danh_muc['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?>
                                                (<?php echo htmlspecialchars($danh_muc['loai_tour']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="gia_tour">Giá Tour (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" id="gia_tour" name="gia_tour" class="form-control" 
                                            placeholder="Nhập giá tour" required
                                            min="0" step="1000"
                                            value="<?php echo htmlspecialchars($_POST['gia_tour'] ?? ''); ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Để 0 nếu là tour theo yêu cầu (khách hàng sẽ liên hệ)</small>
                                </div>

                                <div class="form-group">
                                    <label for="hinh_anh">Hình ảnh đại diện</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="hinh_anh" name="hinh_anh" 
                                            accept="image/*">
                                        <label class="custom-file-label" for="hinh_anh">Chọn file ảnh</label>
                                    </div>
                                    <small class="form-text text-muted">Hình ảnh hiển thị chính của tour (JPEG, PNG, GIF, WebP)</small>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="chinh_sach_id">Chính Sách Tour <span class="text-danger">*</span></label>
                                    <select id="chinh_sach_id" name="chinh_sach_id" class="form-control" required>
                                        <option value="">-- Chọn chính sách --</option>
                                        <?php foreach ($chinh_sach_list as $chinh_sach): ?>
                                            <option value="<?php echo $chinh_sach['id']; ?>"
                                                <?php echo (isset($_POST['chinh_sach_id']) && $_POST['chinh_sach_id'] == $chinh_sach['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($chinh_sach['ten_chinh_sach']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Chính sách hủy tour, hoàn tiền, điều khoản</small>
                                </div>

                                <div class="form-group">
                                    <label for="tag_ids">Tags</label>
                                    <select id="tag_ids" name="tag_ids[]" class="form-control select2" multiple="multiple" 
                                        data-placeholder="Chọn tags" style="width: 100%;">
                                        <?php foreach ($tag_list as $tag): ?>
                                            <option value="<?php echo $tag['id']; ?>"
                                                <?php echo (isset($_POST['tag_ids']) && in_array($tag['id'], $_POST['tag_ids'])) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($tag['ten_tag']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Có thể chọn nhiều tags để phân loại tour</small>
                                </div>

                                <div class="form-group">
                                    <label for="duong_dan_online">Đường dẫn Online</label>
                                    <input type="url" id="duong_dan_online" name="duong_dan_online" class="form-control"
                                        placeholder="https://tour.com/duong-dan-tour"
                                        value="<?php echo htmlspecialchars($_POST['duong_dan_online'] ?? ''); ?>">
                                    <small class="form-text text-muted">Link trang đặt tour online (nếu có)</small>
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trang_thai" value="đang hoạt động" checked>
                                        <label class="form-check-label">
                                            <span class="badge badge-success">Đang hoạt động</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="trang_thai" value="tạm dừng">
                                        <label class="form-check-label">
                                            <span class="badge badge-secondary">Tạm dừng</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Tour "Tạm dừng" sẽ không hiển thị cho khách hàng</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mo_ta">Mô Tả Tour <span class="text-danger">*</span></label>
                                    <textarea id="mo_ta" name="mo_ta" class="form-control" rows="6" required
                                        placeholder="Mô tả chi tiết về tour: điểm đến, hoạt động, dịch vụ bao gồm, trải nghiệm..."><?php echo htmlspecialchars($_POST['mo_ta'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">Mô tả đầy đủ về tour để thu hút khách hàng</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="?act=tour" class="btn btn-default">
                                        <i class="fas fa-times mr-1"></i> Hủy bỏ
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i> Lưu Tour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Sau khi tạo tour, bạn có thể thêm lịch trình chi tiết, hình ảnh và quản lý lịch khởi hành.
                    </small>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Khởi tạo Select2 cho tags
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: "Chọn tags",
        allowClear: true
    });

    // Hiển thị tên file khi chọn
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Tự động tạo mã tour nếu để trống
    $('#ten_tour').on('blur', function() {
        if (!$('#ma_tour').val()) {
            const tourName = $(this).val();
            if (tourName) {
                // Tạo mã tour từ tên (lấy chữ cái đầu mỗi từ)
                const code = tourName.split(' ')
                    .map(word => word.charAt(0).toUpperCase())
                    .join('')
                    .substring(0, 4);
                
                const randomNum = Math.floor(Math.random() * 900) + 100;
                $('#ma_tour').val(code + '-' + randomNum);
            }
        }
    });

    // Validate form
    $('form').on('submit', function() {
        const maTour = $('#ma_tour').val();
        const tenTour = $('#ten_tour').val();
        const danhMuc = $('#danh_muc_id').val();
        const giaTour = $('#gia_tour').val();
        const chinhSach = $('#chinh_sach_id').val();
        const moTa = $('#mo_ta').val();

        if (!maTour || !tenTour || !danhMuc || giaTour === '' || !chinhSach || !moTa) {
            alert('Vui lòng điền đầy đủ các thông tin bắt buộc (có dấu *)');
            return false;
        }

        // Kiểm tra mã tour không trùng
        if (maTour.length < 3) {
            alert('Mã tour phải có ít nhất 3 ký tự');
            return false;
        }

        return true;
    });
});
</script>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.select2-container--bootstrap4 .select2-selection--multiple {
    min-height: 38px;
}

.form-check {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-check-input {
    margin-right: 0.5rem;
}

.form-check-label .badge {
    font-size: 0.8em;
    padding: 0.3em 0.6em;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.custom-file-label::after {
    content: "Duyệt";
}

.text-danger {
    color: #dc3545 !important;
}
</style>