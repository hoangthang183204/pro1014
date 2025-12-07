<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">

        <!-- Header -->
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="?act=/">
                    <i class="fas fa-edit me-2"></i>
                    Sửa Danh Mục Tour
                </a>
                <a href="?act=danh-muc" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </nav>

        <div class="container mt-4">
            <!-- Thông báo -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="flex-grow-1">
                            <?php echo htmlspecialchars($_SESSION['error']); ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="">
                <!-- Form chính -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Chỉnh sửa thông tin danh mục
                        </h5>
                    </div>

                    <form action="?act=danh-muc-tour-update" method="POST">
                        <input type="hidden" name="id" value="<?= $danh_muc['id'] ?>">

                        <div class="card-body">
                            <!-- Tên danh mục -->
                            <div class="mb-4">
                                <label for="ten_danh_muc" class="form-label fw-bold">
                                    Tên danh mục <span class="text-danger">*</span>
                                    <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                                        <small class="text-info float-end">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Đang có <?= $danh_muc['so_luong_tour'] ?> tour sử dụng danh mục này
                                        </small>
                                    <?php endif; ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control"
                                        id="ten_danh_muc"
                                        name="ten_danh_muc"
                                        value="<?= htmlspecialchars($danh_muc['ten_danh_muc']) ?>"
                                        placeholder="Nhập tên danh mục tour"
                                        required>
                                </div>
                                <div class="form-text text-muted mt-1">
                                    Tên danh mục nên rõ ràng, dễ hiểu
                                </div>
                            </div>

                            <!-- Loại tour -->
                            <div class="mb-4">
                                <label for="loai_tour" class="form-label fw-bold">
                                    Loại tour <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <select class="form-select" id="loai_tour" name="loai_tour" required>
                                        <option value="">-- Chọn loại tour --</option>
                                        <option value="trong nước" <?= $danh_muc['loai_tour'] == 'trong nước' ? 'selected' : '' ?>>Tour trong nước</option>
                                        <option value="quốc tế" <?= $danh_muc['loai_tour'] == 'quốc tế' ? 'selected' : '' ?>>Tour quốc tế</option>
                                        <option value="theo yêu cầu" <?= $danh_muc['loai_tour'] == 'theo yêu cầu' ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                    </select>
                                </div>
                                <div class="form-text text-muted mt-1">
                                    <strong>Tour trong nước:</strong> Tour tham quan các địa điểm trong nước Việt Nam<br>
                                    <strong>Tour quốc tế:</strong> Tour tham quan các nước ngoài lãnh thổ Việt Nam<br>
                                    <strong>Tour theo yêu cầu:</strong> Tour thiết kế riêng theo yêu cầu cụ thể của khách hàng
                                </div>
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-4">
                                <label for="mo_ta" class="form-label fw-bold">
                                    Mô tả chi tiết
                                </label>
                                <textarea class="form-control"
                                    id="mo_ta"
                                    name="mo_ta"
                                    rows="4"
                                    placeholder="Mô tả chi tiết về danh mục tour này..."><?= !empty($danh_muc['mo_ta']) ? htmlspecialchars($danh_muc['mo_ta']) : '' ?></textarea>
                                <div class="form-text text-muted mt-1">
                                    Mô tả sẽ giúp khách hàng hiểu rõ hơn về loại tour trong danh mục này
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="mb-4">
                                <label for="trang_thai" class="form-label fw-bold">
                                    Trạng thái hoạt động
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-toggle-on"></i>
                                    </span>
                                    <select class="form-select" id="trang_thai" name="trang_thai">
                                        <option value="hoạt động" <?= $danh_muc['trang_thai'] == 'hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="khóa" <?= $danh_muc['trang_thai'] == 'khóa' ? 'selected' : '' ?>>Khóa</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <?php if ($danh_muc['trang_thai'] == 'hoạt động'): ?>
                                        <div class="alert alert-success alert-sm mb-0">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Hoạt động:</strong> Danh mục đang hiển thị và có thể sử dụng
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-secondary alert-sm mb-0">
                                            <i class="fas fa-ban me-2"></i>
                                            <strong>Khóa:</strong> Danh mục đang bị ẩn và không thể sử dụng
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Cảnh báo nếu có tour đang sử dụng -->
                            <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <div>
                                            <h6 class="mb-2">Lưu ý quan trọng</h6>
                                            <p class="mb-0">
                                                Danh mục này đang có <strong><?= $danh_muc['so_luong_tour'] ?> tour</strong> sử dụng.
                                                Việc thay đổi thông tin có thể ảnh hưởng đến các tour liên quan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card footer -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Cập nhật thay đổi
                                    </button>
                                </div>
                                <a href="?act=danh-muc" class="btn btn-outline-dark">
                                    <i class="fas fa-times me-1"></i> Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Lưu giá trị ban đầu để reset
        var initialValues = {
            ten_danh_muc: $('#ten_danh_muc').val(),
            loai_tour: $('#loai_tour').val(),
            mo_ta: $('#mo_ta').val(),
            trang_thai: $('#trang_thai').val()
        };


        // Xử lý form validation
        $('form').on('submit', function(e) {
            var tenDanhMuc = $('#ten_danh_muc').val().trim();
            var loaiTour = $('#loai_tour').val();

            // Xóa thông báo lỗi cũ
            $('.alert-danger').remove();

            if (!tenDanhMuc) {
                e.preventDefault();
                showToast('Tên danh mục không được để trống', 'danger');
                $('#ten_danh_muc').focus();
                return false;
            }

            if (!loaiTour) {
                e.preventDefault();
                showToast('Vui lòng chọn loại tour', 'danger');
                $('#loai_tour').focus();
                return false;
            }

            if (tenDanhMuc.length < 3) {
                e.preventDefault();
                showToast('Tên danh mục phải có ít nhất 3 ký tự', 'danger');
                $('#ten_danh_muc').focus();
                return false;
            }
        });

        // Hiển thị toast thông báo
        function showToast(message, type) {
            var toastId = 'toast-' + Date.now();
            var bgColor = type === 'danger' ? 'bg-danger' : 'bg-info';
            var icon = type === 'danger' ? 'exclamation-triangle' : 'info-circle';

            var toastHtml = `<div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${icon} me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;

            // Tạo container nếu chưa có
            if (!$('#toast-container').length) {
                $('body').append('<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            }

            $('#toast-container').append(toastHtml);
            var toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();

            // Tự động xóa sau 3 giây
            setTimeout(() => {
                if ($('#' + toastId).length) {
                    $('#' + toastId).remove();
                }
            }, 3000);
        }

        // Character counter cho textarea
        $('#mo_ta').on('input', function() {
            var length = $(this).val().length;
            var counter = $(this).siblings('.char-counter');

            if (counter.length === 0) {
                $(this).after(`<div class="form-text text-muted char-counter">Ký tự: ${length}/1000</div>`);
            } else {
                counter.text(`Ký tự: ${length}/1000`);
            }
        });

        // Hiệu ứng focus cho các input
        $('input, select, textarea').on('focus', function() {
            $(this).closest('.input-group').addClass('border-primary');
        }).on('blur', function() {
            $(this).closest('.input-group').removeClass('border-primary');
        });

        // Kiểm tra thay đổi
        $('form').on('change keyup', ':input', function() {
            var hasChanges =
                $('#ten_danh_muc').val() !== initialValues.ten_danh_muc ||
                $('#loai_tour').val() !== initialValues.loai_tour ||
                $('#mo_ta').val() !== initialValues.mo_ta ||
                $('#trang_thai').val() !== initialValues.trang_thai;

            var submitBtn = $('button[type="submit"]');
            if (hasChanges) {
                submitBtn.removeClass('btn-primary').addClass('btn-success')
                    .html('<i class="fas fa-save me-1"></i> Lưu thay đổi');
            } else {
                submitBtn.removeClass('btn-success').addClass('btn-primary')
                    .html('<i class="fas fa-save me-1"></i> Cập nhật thay đổi');
            }
        });
    });
</script>

<style>
    /* Form styling */
    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group {
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .input-group.border-primary {
        border: 2px solid #0d6efd;
        border-radius: 6px;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-right: none;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-label {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-text {
        font-size: 13px;
    }

    /* Card styling */
    .card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0 !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .card-body {
        padding: 20px;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 15px 20px;
        border-radius: 0 0 8px 8px;
    }

    /* Button styling */
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-success {
        background-color: #198754;
        border-color: #198754;
    }

    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 12px 16px;
        margin-bottom: 20px;
    }

    .alert-sm {
        padding: 8px 12px;
        font-size: 13px;
        margin-bottom: 0;
    }

    /* Badge styling */
    .badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Info card styling */
    .border-start {
        border-left-width: 4px !important;
    }

    /* Stat cards */
    .card.border-start h3 {
        font-size: 28px;
        font-weight: bold;
        margin: 10px 0;
    }

    /* Navbar styling */
    .navbar-dark.bg-dark {
        background-color: #343a40 !important;
        padding: 10px 0;
        margin-bottom: 20px;
        border-radius: 0;
    }

    .navbar-brand {
        font-size: 18px;
        font-weight: 500;
    }

    /* Toast styling */
    .toast-container {
        z-index: 1055;
    }

    .toast {
        border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }

        .card-body {
            padding: 15px;
        }

        .mb-4 {
            margin-bottom: 1rem !important;
        }

        .btn {
            padding: 6px 12px;
            font-size: 13px;
        }

        .input-group-text {
            min-width: 40px;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }

        .d-flex.justify-content-between>div {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .ms-2 {
            margin-left: 0 !important;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 10px;
        }

        .card-body {
            padding: 10px;
        }

        .btn {
            padding: 5px 10px;
            font-size: 12px;
        }

        .card-header .card-tools {
            margin-top: 10px;
            width: 100%;
        }

        .text-end,
        .float-end {
            text-align: left !important;
            float: none !important;
        }
    }
</style>