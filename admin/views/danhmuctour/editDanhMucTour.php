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
            <nav class="navbar navbar-dark bg-warning">
                <a class="navbar-brand" href="">
                    <i class="nav-icon fas fa-edit me-2"></i>
                    Sửa Danh Mục Tour
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

            <?php if ($danh_muc): ?>
                <!-- Thông tin danh mục -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-hashtag mr-2"></i> ID:</strong>
                                        <span class="badge badge-dark"><?= $danh_muc['id'] ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-calculator mr-2"></i> Số tour:</strong>
                                        <span class="badge badge-info"><?= $danh_muc['so_luong_tour'] ?? 0 ?> tour</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-calendar-alt mr-2"></i> Cập nhật:</strong>
                                        <span class="text-muted"><?= !empty($danh_muc['updated_at']) ? date('d/m/Y H:i', strtotime($danh_muc['updated_at'])) : 'Chưa cập nhật' ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-calendar-plus mr-2"></i> Tạo lúc:</strong>
                                        <span class="text-muted"><?= date('d/m/Y H:i', strtotime($danh_muc['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-edit mr-2"></i>
                                    Chỉnh sửa thông tin danh mục
                                </h3>
                            </div>
                            <form action="?act=danh-muc-tour-update" method="POST">
                                <input type="hidden" name="id" value="<?= $danh_muc['id'] ?>">

                                <div class="card-body">
                                    <!-- Tên danh mục -->
                                    <div class="form-group">
                                        <label for="ten_danh_muc">
                                            Tên danh mục 
                                            <span class="text-danger">*</span>
                                            <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                                                <small class="text-info float-right">
                                                    <i class="fas fa-info-circle"></i>
                                                    Đang có <?= $danh_muc['so_luong_tour'] ?> tour sử dụng danh mục này
                                                </small>
                                            <?php endif; ?>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-heading"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="ten_danh_muc" name="ten_danh_muc"
                                                value="<?= htmlspecialchars($danh_muc['ten_danh_muc']) ?>"
                                                placeholder="Nhập tên danh mục tour" required>
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
                                                <option value="trong nước" <?= $danh_muc['loai_tour'] == 'trong nước' ? 'selected' : '' ?>>Tour trong nước</option>
                                                <option value="quốc tế" <?= $danh_muc['loai_tour'] == 'quốc tế' ? 'selected' : '' ?>>Tour quốc tế</option>
                                                <option value="theo yêu cầu" <?= $danh_muc['loai_tour'] == 'theo yêu cầu' ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">
                                            <strong>Tour trong nước:</strong> Tour tham quan các địa điểm trong nước Việt Nam<br>
                                            <strong>Tour quốc tế:</strong> Tour tham quan các nước ngoài lãnh thổ Việt Nam<br>
                                            <strong>Tour theo yêu cầu:</strong> Tour thiết kế riêng theo yêu cầu cụ thể của khách hàng
                                        </small>
                                    </div>

                                    <!-- Mô tả -->
                                    <div class="form-group">
                                        <label for="mo_ta">
                                            Mô tả chi tiết
                                            <small class="text-muted float-right">(Không bắt buộc)</small>
                                        </label>
                                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="5"
                                            placeholder="Mô tả chi tiết về danh mục tour này..."><?= !empty($danh_muc['mo_ta']) ? htmlspecialchars($danh_muc['mo_ta']) : '' ?></textarea>
                                        <small class="form-text text-muted">
                                            Mô tả sẽ giúp khách hàng hiểu rõ hơn về loại tour trong danh mục này
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
                                                <option value="hoạt động" <?= $danh_muc['trang_thai'] == 'hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                                                <option value="khóa" <?= $danh_muc['trang_thai'] == 'khóa' ? 'selected' : '' ?>>Khóa</option>
                                            </select>
                                        </div>
                                        <div class="mt-2">
                                            <?php if ($danh_muc['trang_thai'] == 'hoạt động'): ?>
                                                <div class="alert alert-success alert-sm mb-0">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    <strong>Hoạt động:</strong> Danh mục đang hiển thị và có thể sử dụng
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-secondary alert-sm mb-0">
                                                    <i class="fas fa-ban mr-2"></i>
                                                    <strong>Khóa:</strong> Danh mục đang bị ẩn và không thể sử dụng
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Cảnh báo nếu có tour đang sử dụng -->
                                    <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-info-circle mr-2"></i> Lưu ý quan trọng</h6>
                                            <p class="mb-0">
                                                Danh mục này đang có <strong><?= $danh_muc['so_luong_tour'] ?> tour</strong> sử dụng. 
                                                Việc thay đổi thông tin có thể ảnh hưởng đến các tour liên quan.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer bg-light">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save mr-2"></i> Cập nhật thay đổi
                                    </button>
                                    <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="fas fa-redo mr-2"></i> Khôi phục ban đầu
                                    </button>
                                    <a href="?act=danh-muc" class="btn btn-default float-right">
                                        <i class="fas fa-times mr-2"></i> Hủy bỏ
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card thông tin thống kê -->
                <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Thống kê tour trong danh mục
                                    </h3>
                                    <div class="card-tools">
                                        <a href="?act=danh-muc-tours&danh_muc_id=<?= $danh_muc['id'] ?>" class="btn btn-sm btn-light">
                                            <i class="fas fa-eye mr-1"></i> Xem tất cả tour
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="small-box bg-gradient-info">
                                                <div class="inner">
                                                    <h3><?= $danh_muc['so_luong_tour'] ?></h3>
                                                    <p>Tổng số tour</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-list"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="small-box bg-gradient-success">
                                                <div class="inner">
                                                    <h3>0</h3>
                                                    <p>Tour đang diễn ra</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-plane"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="small-box bg-gradient-warning">
                                                <div class="inner">
                                                    <h3>0</h3>
                                                    <p>Tour sắp diễn ra</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="small-box bg-gradient-secondary">
                                                <div class="inner">
                                                    <h3>0</h3>
                                                    <p>Tour đã hoàn thành</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Không tìm thấy danh mục -->
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card card-danger">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-4x text-danger mb-4"></i>
                                <h3>Không tìm thấy danh mục tour</h3>
                                <p class="text-muted">Danh mục bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                                <a href="?act=danh-muc" class="btn btn-primary mt-3">
                                    <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

        // Lưu giá trị ban đầu để reset
        var initialValues = {
            ten_danh_muc: $('#ten_danh_muc').val(),
            loai_tour: $('#loai_tour').val(),
            mo_ta: $('#mo_ta').val(),
            trang_thai: $('#trang_thai').val()
        };

        // Hàm reset form về giá trị ban đầu
        window.resetForm = function() {
            $('#ten_danh_muc').val(initialValues.ten_danh_muc);
            $('#loai_tour').val(initialValues.loai_tour).trigger('change');
            $('#mo_ta').val(initialValues.mo_ta);
            $('#trang_thai').val(initialValues.trang_thai);
            
            showAlert('Đã khôi phục về giá trị ban đầu', 'info');
        };

        // Xử lý form validation
        $('form').on('submit', function(e) {
            var tenDanhMuc = $('#ten_danh_muc').val().trim();
            var loaiTour = $('#loai_tour').val();

            if (!tenDanhMuc) {
                e.preventDefault();
                showAlert('Tên danh mục không được để trống', 'danger');
                $('#ten_danh_muc').focus();
                return false;
            }

            if (!loaiTour) {
                e.preventDefault();
                showAlert('Vui lòng chọn loại tour', 'danger');
                $('#loai_tour').select2('open');
                return false;
            }

            if (tenDanhMuc.length < 3) {
                e.preventDefault();
                showAlert('Tên danh mục phải có ít nhất 3 ký tự', 'danger');
                $('#ten_danh_muc').focus();
                return false;
            }
        });

        // Hiển thị thông báo
        function showAlert(message, type) {
            // Xóa thông báo cũ
            $('.alert-dismissible').remove();
            
            var alertClass = type === 'danger' ? 'alert-danger' : 'alert-info';
            var iconClass = type === 'danger' ? 'fa-ban' : 'fa-info-circle';
            var title = type === 'danger' ? 'Lỗi!' : 'Thông báo';
            
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                '<h5><i class="icon fas ' + iconClass + '"></i> ' + title + '</h5>' +
                message +
                '</div>';
            
            $('.container.mt-4').prepend(alertHtml);
        }

        // Character counter cho textarea
        $('#mo_ta').on('input', function() {
            var length = $(this).val().length;
            var remaining = 1000 - length;
            
            // Cập nhật counter
            var counter = $(this).siblings('.char-counter');
            if (counter.length === 0) {
                $(this).after('<small class="form-text text-muted char-counter">Ký tự: ' + length + '/1000</small>');
            } else {
                counter.text('Ký tự: ' + length + '/1000');
            }
        });

        // Hiệu ứng cho các input
        $('input, select, textarea').on('focus', function() {
            $(this).parent().addClass('border-warning');
        }).on('blur', function() {
            $(this).parent().removeClass('border-warning');
        });

        // Kiểm tra thay đổi
        $('form').on('change keyup', ':input', function() {
            var hasChanges = 
                $('#ten_danh_muc').val() !== initialValues.ten_danh_muc ||
                $('#loai_tour').val() !== initialValues.loai_tour ||
                $('#mo_ta').val() !== initialValues.mo_ta ||
                $('#trang_thai').val() !== initialValues.trang_thai;
            
            if (hasChanges) {
                $('button[type="submit"]').removeClass('btn-warning').addClass('btn-success').html('<i class="fas fa-save mr-2"></i> Lưu thay đổi');
            } else {
                $('button[type="submit"]').removeClass('btn-success').addClass('btn-warning').html('<i class="fas fa-save mr-2"></i> Cập nhật');
            }
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
    
    .small-box {
        border-radius: .25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        position: relative;
        display: block;
        margin-bottom: 20px;
        color: white;
    }
    
    .small-box > .inner {
        padding: 10px;
    }
    
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: bold;
        margin: 0 0 10px;
        white-space: nowrap;
        padding: 0;
    }
    
    .small-box p {
        font-size: 1rem;
    }
    
    .small-box .icon {
        position: absolute;
        top: -10px;
        right: 10px;
        z-index: 0;
        font-size: 70px;
        color: rgba(255,255,255,0.15);
    }
    
    .form-group.border-warning {
        border: 1px solid #ffc107;
        border-radius: .25rem;
        padding: 10px;
        background-color: rgba(255,193,7,.05);
    }
    
    .btn-lg {
        padding: .5rem 2rem;
        font-size: 1.1rem;
    }
    
    .alert-sm {
        padding: .5rem 1rem;
        font-size: .875rem;
        margin-bottom: 0;
    }
</style>