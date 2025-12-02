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
                        <i class="fas fa-edit me-2"></i>
                        Sửa Tour: <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>
                    </a>
                    <div>
                        <a href="?act=tour" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin tour</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="?act=tour-update" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $tour['id']; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mã Tour <span class="text-danger">*</span></label>
                                        <input type="text" name="ma_tour" class="form-control" required
                                            value="<?php echo htmlspecialchars($tour['ma_tour'] ?? ''); ?>">
                                        <small class="text-muted">Mã tour duy nhất để phân biệt các tour</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tên Tour <span class="text-danger">*</span></label>
                                        <input type="text" name="ten_tour" class="form-control" required
                                            value="<?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>">
                                    </div>



                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh đại diện</label>
                                        <!-- Hiển thị hình ảnh hiện tại -->
                                        <?php if (!empty($tour['hinh_anh'])): ?>
                                            <div class="mb-2">
                                                <img src="uploads/tours/<?php echo htmlspecialchars($tour['hinh_anh']); ?>"
                                                    class="img-thumbnail"
                                                    style="max-height: 150px;"
                                                    alt="Hình ảnh tour">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="delete_hinh_anh"
                                                        value="1"
                                                        id="delete_hinh_anh">
                                                    <label class="form-check-label text-danger" for="delete_hinh_anh">
                                                        Xóa hình ảnh hiện tại
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                                        <small class="text-muted">Để trống nếu không thay đổi hình ảnh</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Danh Mục <span class="text-danger">*</span></label>
                                        <select name="danh_muc_id" class="form-select" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            <?php foreach ($danh_muc_list as $danh_muc): ?>
                                                <option value="<?php echo $danh_muc['id']; ?>"
                                                    <?php echo ($tour['danh_muc_id'] ?? '') == $danh_muc['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?>
                                                    (<?php echo htmlspecialchars($danh_muc['loai_tour']); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Giá Tour (VNĐ) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="gia_tour" class="form-control" required
                                                placeholder="Nhập giá tour"
                                                min="0" step="1000"
                                                value="<?php echo htmlspecialchars($tour['gia_tour'] ?? ''); ?>">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                        <small class="text-muted">Để 0 nếu là tour theo yêu cầu</small>
                                    </div>

                                    <!-- <div class="mb-3">
                                        <label class="form-label">Đường dẫn Online</label>
                                        <input type="url" name="duong_dan_online" class="form-control"
                                            placeholder="https://tour.com/duong-dan-tour"
                                            value="<?php echo htmlspecialchars($tour['duong_dan_online'] ?? ''); ?>">
                                        <small class="text-muted">Link trang đặt tour online (nếu có)</small>
                                    </div> -->

                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="trang_thai" value="đang hoạt động"
                                                <?php echo ($tour['trang_thai'] ?? '') == 'đang hoạt động' ? 'checked' : ''; ?>>
                                            <label class="form-check-label">
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="trang_thai" value="tạm dừng"
                                                <?php echo ($tour['trang_thai'] ?? '') == 'tạm dừng' ? 'checked' : ''; ?>>
                                            <label class="form-check-label">
                                                <span class="badge bg-secondary">Tạm dừng</span>
                                            </label>
                                        </div>
                                        <small class="text-muted">Tour "Tạm dừng" sẽ không hiển thị cho khách hàng</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô Tả Tour <span class="text-danger">*</span></label>
                                <textarea name="mo_ta" class="form-control" rows="6" required
                                    placeholder="Mô tả chi tiết về tour: điểm đến, hoạt động, dịch vụ bao gồm, trải nghiệm..."><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></textarea>
                                <small class="text-muted">Mô tả đầy đủ về tour để thu hút khách hàng</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="?act=tour" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Cập nhật Tour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Khởi tạo Select2 cho tags
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Chọn tags",
            allowClear: true
        });

        // Validate form
        $('form').on('submit', function() {
            const maTour = $('input[name="ma_tour"]').val();
            const tenTour = $('input[name="ten_tour"]').val();
            const danhMuc = $('select[name="danh_muc_id"]').val();
            const giaTour = $('input[name="gia_tour"]').val();
            const chinhSach = $('select[name="chinh_sach_id"]').val();
            const moTa = $('textarea[name="mo_ta"]').val();

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

    .text-danger {
        color: #dc3545 !important;
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.25rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
    }
</style>