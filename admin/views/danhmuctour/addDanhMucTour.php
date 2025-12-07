<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">

        <!-- Header -->
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="?act=/">
                    <i class="fas fa-folder-plus me-2"></i>
                    Thêm Danh Mục Tour Mới
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

            <div class="row">
                <div class="">
                    <!-- Form card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i>
                                Thông tin danh mục tour
                            </h5>
                        </div>

                        <form action="?act=danh-muc-tour-store" method="POST">
                            <div class="card-body">
                                <!-- Tên danh mục -->
                                <div class="mb-4">
                                    <label for="ten_danh_muc" class="form-label fw-bold">
                                        Tên danh mục <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-heading"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control"
                                            id="ten_danh_muc"
                                            name="ten_danh_muc"
                                            placeholder="Nhập tên danh mục tour"
                                            value="<?= isset($_POST['ten_danh_muc']) ? htmlspecialchars($_POST['ten_danh_muc']) : '' ?>"
                                            required>
                                    </div>
                                    <div class="form-text text-muted mt-1">
                                        Ví dụ: Tour Miền Bắc, Tour Châu Âu, Tour Honeymoon
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
                                            <option value="trong nước" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'trong nước') ? 'selected' : '' ?>>Tour trong nước</option>
                                            <option value="quốc tế" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'quốc tế') ? 'selected' : '' ?>>Tour quốc tế</option>
                                            <option value="theo yêu cầu" <?= (isset($_POST['loai_tour']) && $_POST['loai_tour'] == 'theo yêu cầu') ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                        </select>
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
                                        placeholder="Mô tả chi tiết về danh mục tour này..."><?= isset($_POST['mo_ta']) ? htmlspecialchars($_POST['mo_ta']) : '' ?></textarea>
                                    <div class="form-text text-muted mt-1">
                                        Ví dụ: "Danh mục tour miền Bắc bao gồm các tour tham quan Hà Nội, Hạ Long, Sapa..."
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
                                            <option value="hoạt động" selected>Hoạt động</option>
                                            <option value="khóa" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == 'khóa') ? 'selected' : '' ?>>Khóa</option>
                                        </select>
                                    </div>
                                    <div class="form-text text-muted mt-1">
                                        <strong>Hoạt động:</strong> Danh mục sẽ hiển thị và có thể sử dụng<br>
                                        <strong>Khóa:</strong> Danh mục sẽ bị ẩn và không thể sử dụng
                                    </div>
                                </div>
                            </div>

                            <!-- Card footer -->
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Lưu danh mục
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary ms-2">
                                            <i class="fas fa-redo me-1"></i> Làm mới
                                        </button>
                                    </div>
                                    <a href="?act=danh-muc" class="btn btn-outline-dark">
                                        <i class="fas fa-times me-1"></i> Hủy bỏ
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Information card -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Giải thích các loại tour
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-start border-primary border-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">
                                                <i class="fas fa-home me-2"></i>
                                                Tour trong nước
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                Tour tham quan các địa điểm trong nước Việt Nam.
                                            </p>
                                            <small class="text-muted">
                                                <strong>Ví dụ:</strong> Đà Nẵng - Hội An, Hạ Long - Hải Phòng, Sài Gòn - Mekong
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-start border-success border-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-success">
                                                <i class="fas fa-globe-americas me-2"></i>
                                                Tour quốc tế
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                Tour tham quan các nước ngoài lãnh thổ Việt Nam.
                                            </p>
                                            <small class="text-muted">
                                                <strong>Ví dụ:</strong> Thái Lan, Singapore, Nhật Bản, Châu Âu
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-start border-info border-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-info">
                                                <i class="fas fa-user-cog me-2"></i>
                                                Tour theo yêu cầu
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                Tour thiết kế riêng theo yêu cầu cụ thể của khách hàng.
                                            </p>
                                            <small class="text-muted">
                                                <strong>Ví dụ:</strong> Tour team building, tour gia đình, tour VIP
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Tự động focus vào trường tên danh mục
        $('#ten_danh_muc').focus();

        // Xử lý form validation
        $('form').on('submit', function(e) {
            var tenDanhMuc = $('#ten_danh_muc').val().trim();
            var loaiTour = $('#loai_tour').val();

            // Xóa thông báo lỗi cũ
            $('.alert-danger').remove();

            if (!tenDanhMuc) {
                e.preventDefault();
                showError('Tên danh mục không được để trống');
                $('#ten_danh_muc').focus();
                return false;
            }

            if (!loaiTour) {
                e.preventDefault();
                showError('Vui lòng chọn loại tour');
                $('#loai_tour').focus();
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
            var alertHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div class="flex-grow-1">${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>`;

            $('.container.mt-4').prepend(alertHtml);
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

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: 1px solid transparent;
        padding: 12px 16px;
        margin-bottom: 20px;
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

    /* Information cards */
    .card.h-100 {
        transition: transform 0.2s ease;
    }

    .card.h-100:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
</style>