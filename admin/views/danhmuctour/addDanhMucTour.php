<!-- Header -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-primary">
                <a class="navbar-brand" href="">
                    <i class="nav-icon fas fa-plus-circle me-2"></i>
                    Thêm Danh Mục Tour Mới
                </a>
                <div>
                    <a href="?act=danh-muc" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                    </a>
                </div>
            </nav>
        </div>

        <div class="container mt-4">
            <!-- Thông báo session -->
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-folder-plus mr-2"></i>
                                Thông tin danh mục tour
                            </h3>
                        </div>
                        <form action="?act=danh-muc-tour-store" method="POST">
                            <div class="card-body">
                                <!-- Tên danh mục -->
                                <div class="form-group">
                                    <label for="ten_danh_muc">
                                        Tên danh mục 
                                        <span class="text-danger">*</span>
                                        <small class="text-muted float-right">(Ví dụ: Tour Miền Bắc, Tour Châu Âu, Tour Honeymoon)</small>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-heading"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="ten_danh_muc" name="ten_danh_muc"
                                            placeholder="Nhập tên danh mục tour" 
                                            value="<?= isset($_POST['ten_danh_muc']) ? htmlspecialchars($_POST['ten_danh_muc']) : '' ?>"
                                            required>
                                    </div>
                                    <small class="form-text text-muted">Tên danh mục nên rõ ràng, dễ hiểu</small>
                                </div>

                                <!-- Loại tour -->
                                <div class="form-group">
                                    <label for="loai_tour">
                                        Loại tour 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-globe"></i>
                                            </span>
                                        </div>
                                        <select class="form-control select2" id="loai_tour" name="loai_tour" required style="width: 100%;">
                                            <option value="">-- Chọn loại tour --</option>
                                            <option value="trong nước" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'trong nước') ? 'selected' : '' ?>>Tour trong nước</option>
                                            <option value="quốc tế" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'quốc tế') ? 'selected' : '' ?>>Tour quốc tế</option>
                                            <option value="theo yêu cầu" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'theo yêu cầu') ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Card thông tin loại tour -->
                                <div class="card card-info collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Giải thích các loại tour
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-primary">
                                                            <i class="fas fa-home mr-2"></i>
                                                            Tour trong nước
                                                        </h6>
                                                        <p class="card-text small">
                                                            Tour tham quan các địa điểm trong nước Việt Nam.<br>
                                                            <strong>Ví dụ:</strong> Đà Nẵng - Hội An, Hạ Long - Hải Phòng, Sài Gòn - Mekong
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-success">
                                                            <i class="fas fa-globe-americas mr-2"></i>
                                                            Tour quốc tế
                                                        </h6>
                                                        <p class="card-text small">
                                                            Tour tham quan các nước ngoài lãnh thổ Việt Nam.<br>
                                                            <strong>Ví dụ:</strong> Thái Lan, Singapore, Nhật Bản, Châu Âu
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-info">
                                                            <i class="fas fa-user-cog mr-2"></i>
                                                            Tour theo yêu cầu
                                                        </h6>
                                                        <p class="card-text small">
                                                            Tour thiết kế riêng theo yêu cầu cụ thể của khách hàng.<br>
                                                            <strong>Ví dụ:</strong> Tour team building, tour gia đình, tour VIP
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mô tả -->
                                <div class="form-group">
                                    <label for="mo_ta">
                                        Mô tả chi tiết
                                        <small class="text-muted float-right">(Không bắt buộc)</small>
                                    </label>
                                    <textarea class="form-control" id="mo_ta" name="mo_ta" rows="5"
                                        placeholder="Mô tả chi tiết về danh mục tour này..."><?= isset($_POST['mo_ta']) ? htmlspecialchars($_POST['mo_ta']) : '' ?></textarea>
                                    <small class="form-text text-muted">
                                        Ví dụ: "Danh mục tour miền Bắc bao gồm các tour tham quan Hà Nội, Hạ Long, Sapa..."
                                    </small>
                                </div>

                                <!-- Trạng thái -->
                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái hoạt động</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-toggle-on"></i>
                                            </span>
                                        </div>
                                        <select class="form-control" id="trang_thai" name="trang_thai">
                                            <option value="hoạt động" selected>Hoạt động</option>
                                            <option value="khóa" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == 'khóa') ? 'selected' : '' ?>>Khóa</option>
                                        </select>
                                    </div>
                                    <small class="form-text text-muted">
                                        <strong>Hoạt động:</strong> Danh mục sẽ hiển thị và có thể sử dụng<br>
                                        <strong>Khóa:</strong> Danh mục sẽ bị ẩn và không thể sử dụng
                                    </small>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer bg-light">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save mr-2"></i> Lưu danh mục
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo mr-2"></i> Làm mới
                                </button>
                                <a href="?act=danh-muc" class="btn btn-default float-right">
                                    <i class="fas fa-times mr-2"></i> Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Khởi tạo Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: '-- Chọn loại tour --',
            allowClear: true
        });

        // Xử lý form validation
        $('form').on('submit', function(e) {
            var tenDanhMuc = $('#ten_danh_muc').val().trim();
            var loaiTour = $('#loai_tour').val();

            if (!tenDanhMuc) {
                e.preventDefault();
                showError('Tên danh mục không được để trống');
                $('#ten_danh_muc').focus();
                return false;
            }

            if (!loaiTour) {
                e.preventDefault();
                showError('Vui lòng chọn loại tour');
                $('#loai_tour').select2('open');
                return false;
            }

            if (tenDanhMuc.length < 3) {
                e.preventDefault();
                showError('Tên danh mục phải có ít nhất 3 ký tự');
                $('#ten_danh_muc').focus();
                return false;
            }
        });

        // Hiển thị lỗi
        function showError(message) {
            // Xóa thông báo lỗi cũ
            $('.alert-danger').remove();
            
            // Thêm thông báo lỗi mới
            var alertHtml = '<div class="alert alert-danger alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                '<h5><i class="icon fas fa-ban"></i> Lỗi!</h5>' +
                message +
                '</div>';
            
            $('.container.mt-4').prepend(alertHtml);
        }

        // Tự động focus vào trường tên danh mục
        $('#ten_danh_muc').focus();

        // Character counter cho textarea
        $('#mo_ta').on('input', function() {
            var length = $(this).val().length;
            var remaining = 1000 - length;
            
            // Cập nhật counter nếu có
            var counter = $(this).siblings('.char-counter');
            if (counter.length === 0) {
                $(this).after('<small class="form-text text-muted char-counter">Ký tự: ' + length + '/1000</small>');
            } else {
                counter.text('Ký tự: ' + length + '/1000');
            }
        });

        // Hiệu ứng cho các input
        $('input, select, textarea').on('focus', function() {
            $(this).parent().addClass('border-primary');
        }).on('blur', function() {
            $(this).parent().removeClass('border-primary');
        });
    });
</script>

<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
    
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
    }
    
    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
    
    .card-info:not(.collapsed-card) .card-header {
        background: #17a2b8;
    }
    
    .form-group.border-primary {
        border: 1px solid #007bff;
        border-radius: .25rem;
        padding: 10px;
        background-color: rgba(0,123,255,.05);
    }
    
    .btn-lg {
        padding: .5rem 2rem;
        font-size: 1.1rem;
    }
</style>