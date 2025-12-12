<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <i class="fas fa-copy me-2"></i>
                    Clone Tour: <span class="text-primary"><?php echo htmlspecialchars($tour['ten_tour']); ?></span>
                </a>
                <div>
                    <a href="?act=tour" class="btn btn-outline-light me-2">
                        <i class="fas fa-arrow-left me-1"></i> Về Danh sách Tour
                    </a>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">
                            <?php echo htmlspecialchars($_SESSION['success']); ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

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

            <!-- Card thông tin tour gốc -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>Thông tin tour gốc
                    </h3>
                    <div class="card-tools">
                        <a href="?act=tour-edit&id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-edit me-1"></i> Mở tour gốc
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Mã Tour</span>
                                    <span class="info-box-number"><?php echo htmlspecialchars($tour['ma_tour']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Lịch trình</span>
                                    <span class="info-box-number"><?php echo $tour['so_lich_trinh'] ?? 0; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-tags"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Phiên bản</span>
                                    <span class="info-box-number"><?php echo $tour['so_phien_ban'] ?? 0; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger">
                                    <i class="fas fa-images"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Hình ảnh</span>
                                    <span class="info-box-number"><?php echo $tour['so_media'] ?? 0; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Form clone tour -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs me-2"></i>
                                Cấu hình tour mới
                            </h3>
                        </div>
                        <form action="index.php?act=tour-store-clone" method="POST" id="cloneForm">
                            <input type="hidden" name="original_tour_id" value="<?php echo $tour['id']; ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên tour mới <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ten_tour"
                                                value="<?php echo htmlspecialchars($tour['ten_tour']) . ' (Copy)'; ?>" required>
                                            <small class="form-text text-muted">Tên tour sẽ được sao chép, bạn có thể chỉnh sửa</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mã tour mới</label>
                                            <input type="text" class="form-control" value="Tự động tạo" disabled>
                                            <small class="form-text text-muted">Mã tour sẽ được tạo tự động</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh mục</label>
                                            <select class="form-control select2" style="width: 100%;" name="danh_muc_id">
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php foreach ($danh_muc_list as $danh_muc): ?>
                                                    <option value="<?php echo $danh_muc['id']; ?>"
                                                        <?php echo ($danh_muc['id'] == $tour['danh_muc_id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giá tour mới (VNĐ)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gia_tour"
                                                    value="<?php echo number_format($tour['gia_tour'] ?? 0); ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Để trống sẽ giữ nguyên giá tour gốc</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Mô tả tour mới</label>
                                    <textarea class="form-control" name="mo_ta" rows="4"><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></textarea>
                                    <small class="form-text text-muted">Có thể chỉnh sửa mô tả tour mới</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Lịch sử clone -->
                    <?php if (!empty($clone_history)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history me-2"></i>
                                    Lịch sử clone
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tour</th>
                                                <th>Thời gian</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($clone_history as $history): ?>
                                                <tr>
                                                    <td>
                                                        <strong class="text-primary"><?php echo htmlspecialchars($history['new_tour_code']); ?></strong><br>
                                                        <small><?php echo htmlspecialchars($history['new_tour_name']); ?></small>
                                                    </td>
                                                    <td>
                                                        <small><?php echo date('d/m/Y', strtotime($history['cloned_at'])); ?></small><br>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($history['cloned_at'])); ?></small>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="index.php?act=tour-edit&id=<?php echo $history['new_tour_id']; ?>"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Thống kê -->
                    <!-- <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie me-2"></i>
                                Thống kê tour gốc
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="p-2 bg-info bg-opacity-10 rounded">
                                        <h4 class="text-info"><?php echo $tour['so_lich_trinh'] ?? 0; ?></h4>
                                        <small class="text-muted">Lịch trình</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-2 bg-success bg-opacity-10 rounded">
                                        <h4 class="text-success"><?php echo $tour['so_phien_ban'] ?? 0; ?></h4>
                                        <small class="text-muted">Phiên bản</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-warning bg-opacity-10 rounded">
                                        <h4 class="text-warning"><?php echo $tour['so_media'] ?? 0; ?></h4>
                                        <small class="text-muted">Hình ảnh</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-primary bg-opacity-10 rounded">
                                        <h4 class="text-primary"><?php echo $tour['so_lich_khoi_hanh'] ?? 0; ?></h4>
                                        <small class="text-muted">Lịch KH</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Quick Clone -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt me-2"></i>
                                Clone nhanh
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Tạo bản sao nhanh với tất cả cấu hình mặc định</p>
                            <button type="button" class="btn btn-success btn-block" onclick="quickClone()">
                                <i class="fas fa-copy me-2"></i>Clone nhanh
                            </button>
                            <div id="quickCloneResult" class="mt-2"></div>
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
        // Format số tiền
        $('input[name="gia_tour"]').on('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('vi-VN');
            }
            e.target.value = value;
        });

        // Auto-generate tên tour
        $('input[name="ten_tour"]').on('input', function(e) {
            const originalName = "<?php echo htmlspecialchars($tour['ten_tour']); ?>";
            const currentValue = e.target.value;

            if (!currentValue.trim()) {
                e.target.value = originalName + ' (Copy)';
            }
        });

        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });

    // Clone nhanh bằng AJAX
    function quickClone() {
        const btn = event.target;
        const originalBtnText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang clone...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('tour_id', <?php echo $tour['id']; ?>);

        fetch('index.php?act=tour-quick-clone', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('quickCloneResult');
                if (data.success) {
                    resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Clone thành công!</h6>
                    <p class="mb-1">Mã tour mới: <strong>${data.new_tour_code}</strong></p>
                    <div class="mt-2">
                        <a href="index.php?act=tour-edit&id=${data.new_tour_id}" class="btn btn-sm btn-success me-2">
                            <i class="fas fa-edit"></i> Mở tour mới
                        </a>
                        <a href="index.php?act=tour-clone-success&id=${data.new_tour_id}" class="btn btn-sm btn-info">
                            <i class="fas fa-info-circle"></i> Chi tiết
                        </a>
                    </div>
                </div>
            `;

                    // Reload trang sau 3 giây để cập nhật lịch sử
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="fas fa-times-circle me-2"></i>Lỗi!</h6>
                    <p class="mb-0">${data.error}</p>
                </div>
            `;
                }
            })
            .catch(error => {
                document.getElementById('quickCloneResult').innerHTML = `
            <div class="alert alert-danger">
                <h6><i class="fas fa-times-circle me-2"></i>Lỗi kết nối!</h6>
                <p class="mb-0">${error.message}</p>
            </div>
        `;
            })
            .finally(() => {
                btn.innerHTML = originalBtnText;
                btn.disabled = false;
            });
    }
</script>

<style>
    .info-box {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .info-box-icon {
        border-radius: 5px 0 0 5px;
    }

    .callout {
        border-left: 5px solid #007bff;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }

    .callout-info {
        border-left-color: #17a2b8;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .04);
    }

    .card-outline {
        border-top: 3px solid;
    }

    .card-outline.card-primary {
        border-top-color: #007bff;
    }

    .card-outline.card-warning {
        border-top-color: #ffc107;
    }

    .card-outline.card-success {
        border-top-color: #28a745;
    }
</style>