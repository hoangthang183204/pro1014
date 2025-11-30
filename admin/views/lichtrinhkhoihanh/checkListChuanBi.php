<!-- Header -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-tasks me-2"></i>
                        Checklist Chuẩn Bị:
                        <?php echo isset($lich_khoi_hanh['ten_tour']) && is_string($lich_khoi_hanh['ten_tour']) ? htmlspecialchars($lich_khoi_hanh['ten_tour']) : 'Không xác định'; ?>
                    </a>
                    <div>
                        <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Kiểm tra dữ liệu lich_khoi_hanh -->
                <?php if (!isset($lich_khoi_hanh) || !is_array($lich_khoi_hanh) || empty($lich_khoi_hanh)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Dữ liệu lịch khởi hành không tồn tại hoặc không hợp lệ.
                        <a href="?act=lich-khoi-hanh" class="alert-link">Quay lại danh sách</a>
                    </div>
                <?php else: ?>

                    <!-- Thông tin lịch -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Tour:</strong>
                                    <?php echo isset($lich_khoi_hanh['ma_tour']) && is_string($lich_khoi_hanh['ma_tour']) ? htmlspecialchars($lich_khoi_hanh['ma_tour']) : 'N/A'; ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Ngày đi:</strong>
                                    <?php
                                    if (isset($lich_khoi_hanh['ngay_bat_dau']) && !empty($lich_khoi_hanh['ngay_bat_dau'])) {
                                        echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>HDV:</strong>
                                    <?php
                                    $hdv_phan_cong = null;
                                    if (isset($this->lichKhoiHanhModel) && isset($lich_khoi_hanh['id'])) {
                                        $hdv_phan_cong = $this->lichKhoiHanhModel->getPhanCongHDV($lich_khoi_hanh['id']);
                                    }

                                    if ($hdv_phan_cong && !empty($hdv_phan_cong['ten_hdv'])):
                                    ?>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($hdv_phan_cong['ten_hdv']); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Chưa phân công</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Tiến độ:</strong>
                                    <?php
                                    $hoan_thanh = 0;
                                    $tong = !empty($checklist) && is_array($checklist) ? count($checklist) : 0;
                                    if ($tong > 0) {
                                        $hoan_thanh = count(array_filter($checklist, function ($item) {
                                            return isset($item['hoan_thanh']) && $item['hoan_thanh'];
                                        }));
                                        $phan_tram = round(($hoan_thanh / $tong) * 100);
                                    } else {
                                        $phan_tram = 0;
                                    }
                                    ?>
                                    <div class="progress mt-1" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: <?php echo $phan_tram; ?>%"
                                            aria-valuenow="<?php echo $phan_tram; ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small><?php echo $hoan_thanh; ?>/<?php echo $tong; ?> hoàn thành (<?php echo $phan_tram; ?>%)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form thêm checklist -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Thêm mục checklist</h5>
                        </div>
                        <div class="card-body">
                            <form id="formThemChecklist" method="POST" action="?act=checklist-them">
                                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo isset($lich_khoi_hanh['id']) ? $lich_khoi_hanh['id'] : 0; ?>">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="mb-3">
                                            <input type="text" name="cong_viec" class="form-control"
                                                placeholder="Nhập công việc cần chuẩn bị..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-1"></i> Thêm
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Danh sách checklist -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Danh sách công việc cần chuẩn bị</h5>
                            <div>
                                <button class="btn btn-sm btn-outline-success" id="btnCheckAll">
                                    <i class="fas fa-check-double me-1"></i> Check tất cả
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="btnUncheckAll">
                                    <i class="fas fa-times me-1"></i> Bỏ check
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($checklist) && is_array($checklist)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($checklist as $index => $item): ?>
                                        <?php if (isset($item['id']) && isset($item['cong_viec'])): ?>
                                            <div class="list-group-item checklist-item <?php echo !empty($item['hoan_thanh']) ? 'completed' : ''; ?>">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3 checklist-checkbox" type="checkbox"
                                                        id="checklist-<?php echo $item['id']; ?>"
                                                        <?php echo !empty($item['hoan_thanh']) ? 'checked' : ''; ?>
                                                        data-id="<?php echo $item['id']; ?>">
                                                    <label class="form-check-label flex-grow-1" for="checklist-<?php echo $item['id']; ?>">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="<?php echo !empty($item['hoan_thanh']) ? 'text-decoration-line-through text-muted' : ''; ?>">
                                                                <?php echo htmlspecialchars($item['cong_viec']); ?>
                                                            </span>
                                                            <div>
                                                                <?php if (!empty($item['hoan_thanh']) && !empty($item['thoi_gian_hoan_thanh'])): ?>
                                                                    <small class="text-success">
                                                                        <i class="fas fa-check me-1"></i>
                                                                        Hoàn thành: <?php echo date('H:i d/m', strtotime($item['thoi_gian_hoan_thanh'])); ?>
                                                                    </small>
                                                                <?php endif; ?>
                                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                                    onclick="xoaChecklist(<?php echo $item['id']; ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có checklist</h5>
                                    <p class="text-muted">Thêm các công việc cần chuẩn bị trước tour</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Checklist mẫu -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Checklist mẫu (Thêm nhanh)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Tài liệu & Vé</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="In danh sách khách hàng">
                                        In danh sách khách
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị vé máy bay">
                                        Vé máy bay
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị vé tham quan">
                                        Vé tham quan
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <h6>Vận chuyển</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Xác nhận xe vận chuyển">
                                        Xác nhận xe
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Kiểm tra lộ trình xe">
                                        Lộ trình xe
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <h6>Khác</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị dụng cụ y tế">
                                        Dụng cụ y tế
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị bảng tên HDV">
                                        Bảng tên HDV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; // End check lich_khoi_hanh 
                ?>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Xử lý form thêm checklist
        $('#formThemChecklist').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            location.reload();
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    } catch (e) {
                        console.error('Lỗi parse JSON:', e);
                        alert('Lỗi hệ thống');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                    alert('Lỗi kết nối');
                }
            });
        });

        // Xử lý checklist template
        $('.add-template').on('click', function() {
            var text = $(this).data('text');
            $('input[name="cong_viec"]').val(text);
        });

        // Xử lý check/uncheck checklist
        $('.checklist-checkbox').on('change', function() {
            var checklistId = $(this).data('id');
            var isCompleted = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '?act=checklist-update',
                type: 'POST',
                data: {
                    id: checklistId,
                    hoan_thanh: isCompleted
                },
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (!result.success) {
                            alert('Lỗi cập nhật: ' + result.message);
                            location.reload();
                        }
                    } catch (e) {
                        console.error('Lỗi parse JSON:', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                    alert('Lỗi kết nối');
                    location.reload();
                }
            });
        });

        // Check tất cả
        $('#btnCheckAll').on('click', function() {
            $('.checklist-checkbox').prop('checked', true).trigger('change');
        });

        // Bỏ check tất cả
        $('#btnUncheckAll').on('click', function() {
            $('.checklist-checkbox').prop('checked', false).trigger('change');
        });
    });

    // Hàm xóa checklist
    function xoaChecklist(id) {
        if (confirm('Bạn có chắc muốn xóa mục checklist này?')) {
            $.ajax({
                url: '?act=checklist-xoa',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            location.reload();
                        } else {
                            alert('Lỗi: ' + result.message);
                        }
                    } catch (e) {
                        console.error('Lỗi parse JSON:', e);
                        alert('Lỗi hệ thống');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi AJAX:', error);
                    alert('Lỗi kết nối');
                }
            });
        }
    }
    // Cập nhật tiến độ realtime
    function updateProgress() {
        var total = $('.checklist-checkbox').length;
        var completed = $('.checklist-checkbox:checked').length;
        var percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

        $('.progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
        $('.progress-bar').next('small').text(completed + '/' + total + ' hoàn thành (' + percentage + '%)');
    }

    // Gọi updateProgress khi checkbox thay đổi
    $('.checklist-checkbox').on('change', function() {
        // ... code xử lý AJAX hiện tại ...

        // Sau khi AJAX thành công, cập nhật tiến độ
        setTimeout(updateProgress, 100);
    });

    // Khởi tạo tiến độ khi trang load
    updateProgress();

    // Thêm hiệu ứng khi thêm template
    $('.add-template').on('click', function() {
        var text = $(this).data('text');
        $('input[name="cong_viec"]').val(text).focus();

        // Hiệu ứng highlight
        $(this).addClass('btn-success').removeClass('btn-outline-secondary');
        setTimeout(() => {
            $(this).addClass('btn-outline-secondary').removeClass('btn-success');
        }, 1000);
    });
</script>

<style>
    .checklist-item {
        border: none;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }

    .checklist-item:last-child {
        border-bottom: none;
    }

    .checklist-item.completed {
        background-color: #f8fff8;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .add-template {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .checklist-item {
        border: none;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
        transition: background-color 0.3s ease;
    }

    .checklist-item:last-child {
        border-bottom: none;
    }

    .checklist-item.completed {
        background-color: #f8fff8;
    }

    .checklist-item:hover {
        background-color: #f8f9fa;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .add-template {
        margin-right: 5px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }

    .add-template:hover {
        transform: translateY(-2px);
    }

    .progress {
        background-color: #e9ecef;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Animation khi check */
    .checklist-item.completed .form-check-label {
        transition: all 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body .row>div {
            margin-bottom: 10px;
        }

        .checklist-item {
            padding: 10px 15px;
        }
    }
</style>
</body>

</html>