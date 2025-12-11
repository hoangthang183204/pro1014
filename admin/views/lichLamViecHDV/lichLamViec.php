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
                    <a class="navbar-brand" href="?act=lich-lam-viec-hdv">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Quản Lý Lịch Làm Việc HDV
                    </a>
                    <a href="?act=huong-dan-vien" class="btn btn-outline-light">
                        <i class="fas fa-user-tie me-1"></i> Quản lý HDV
                    </a>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-primary mx-auto mb-2">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-primary"><?= is_array($lich_lam_viec) ? count($lich_lam_viec) : 0 ?></h4>
                                <p class="card-text small mb-0 text-muted">Tổng lịch làm việc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-success mx-auto mb-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-success">
                                    <?php 
                                    if (is_array($lich_lam_viec)) {
                                        echo count(array_filter($lich_lam_viec, function($item) { 
                                            return $item['loai_lich'] == 'có thể làm'; 
                                        }));
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </h4>
                                <p class="card-text small mb-0 text-muted">Có thể làm</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-warning mx-auto mb-2">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-warning">
                                    <?php 
                                    if (is_array($lich_lam_viec)) {
                                        echo count(array_filter($lich_lam_viec, function($item) { 
                                            return $item['loai_lich'] == 'bận'; 
                                        }));
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </h4>
                                <p class="card-text small mb-0 text-muted">Bận</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-danger mx-auto mb-2">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-danger">
                                    <?php 
                                    if (is_array($lich_lam_viec)) {
                                        echo count(array_filter($lich_lam_viec, function($item) { 
                                            return $item['loai_lich'] == 'nghỉ'; 
                                        }));
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </h4>
                                <p class="card-text small mb-0 text-muted">Nghỉ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-info mx-auto mb-2">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-info">
                                    <?php 
                                    if (is_array($lich_lam_viec)) {
                                        echo count(array_filter($lich_lam_viec, function($item) { 
                                            return $item['loai_lich'] == 'đã phân công'; 
                                        }));
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </h4>
                                <p class="card-text small mb-0 text-muted">Đã phân công</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-3">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-3">
                                <div class="icon bg-secondary mx-auto mb-2">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="card-title mb-1 fw-bold text-secondary"><?= count($hdv_list) ?></h4>
                                <p class="card-text small mb-0 text-muted">Tổng HDV</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Danh sách lịch làm việc -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Lịch Làm Việc (<?php echo is_array($lich_lam_viec) ? count($lich_lam_viec) : 0; ?>)</h5>
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalThemLich">
                            <i class="fas fa-plus me-1"></i> Thêm Lịch
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="lichLamViecTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50" class="text-center">#</th>
                                            <th width="200">Hướng dẫn viên</th>
                                            <th width="120" class="text-center">Ngày làm việc</th>
                                            <th width="150" class="text-center">Loại lịch</th>
                                            <th>Ghi chú</th>
                                            <th width="150" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lich_lam_viec as $index => $lich): ?>
                                            <tr>
                                                <td class="text-center"><?= $index + 1 ?></td>
                                                <td>
                                                    <div>
                                                        <strong class="text-primary"><?= htmlspecialchars($lich['ho_ten']) ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-phone me-1"></i><?= htmlspecialchars($lich['so_dien_thoai']) ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        <strong><?= date('d/m/Y', strtotime($lich['ngay'])) ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php 
                                                            $thu = date('l', strtotime($lich['ngay']));
                                                            $thu_viet = [
                                                                'Monday' => 'Thứ 2',
                                                                'Tuesday' => 'Thứ 3',
                                                                'Wednesday' => 'Thứ 4',
                                                                'Thursday' => 'Thứ 5',
                                                                'Friday' => 'Thứ 6',
                                                                'Saturday' => 'Thứ 7',
                                                                'Sunday' => 'Chủ nhật'
                                                            ];
                                                            echo $thu_viet[$thu] ?? $thu;
                                                            ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $badge_class = [
                                                        'có thể làm' => 'bg-success',
                                                        'bận' => 'bg-warning',
                                                        'nghỉ' => 'bg-danger',
                                                        'đã phân công' => 'bg-info'
                                                    ];
                                                    $class = $badge_class[$lich['loai_lich']] ?? 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?= $class ?>">
                                                        <?= $lich['loai_lich'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($lich['ghi_chu'])): ?>
                                                        <small class="text-muted"><?= htmlspecialchars($lich['ghi_chu']) ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <!-- Nút sửa -->
                                                        <button type="button" 
                                                                class="btn btn-primary btn-edit"
                                                                data-id="<?= $lich['id'] ?>"
                                                                data-hdv="<?= $lich['huong_dan_vien_id'] ?>"
                                                                data-ngay="<?= date('Y-m-d', strtotime($lich['ngay'])) ?>"
                                                                data-loai="<?= $lich['loai_lich'] ?>"
                                                                data-ghichu="<?= htmlspecialchars($lich['ghi_chu']) ?>"
                                                                data-toggle="modal" 
                                                                data-target="#modalThemLich"
                                                                title="Sửa lịch làm việc">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <!-- Nút xóa -->
                                                        <a href="?act=lich-lam-viec-hdv-xoa&id=<?= $lich['id'] ?>" 
                                                           class="btn btn-danger"
                                                           onclick="return confirm('Bạn có chắc muốn xóa lịch làm việc này?')"
                                                           title="Xóa lịch làm việc">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có lịch làm việc nào</h5>
                                <p class="text-muted">
                                    <?php if (isset($_GET['tu_khoa']) || isset($_GET['loai_lich']) || isset($_GET['huong_dan_vien_id'])): ?>
                                        Không tìm thấy lịch làm việc phù hợp
                                    <?php else: ?>
                                        Hãy thêm lịch làm việc mới
                                    <?php endif; ?>
                                </p>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalThemLich">
                                    <i class="fas fa-plus me-1"></i> Thêm Lịch Đầu Tiên
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($lich_lam_viec); ?></strong> trong tổng số <strong><?php echo count($lich_lam_viec); ?></strong> lịch làm việc
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cập nhật: <?php echo date('d/m/Y H:i'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal thêm/sửa lịch -->
<div class="modal fade" id="modalThemLich" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Thêm Lịch Làm Việc
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formThemLich" method="POST">
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                        <select class="form-select" name="huong_dan_vien_id" id="inputHDV" required>
                            <option value="">-- Chọn hướng dẫn viên --</option>
                            <?php foreach($hdv_list as $hdv): ?>
                            <option value="<?= $hdv['id'] ?>"><?= $hdv['ho_ten'] ?> - <?= $hdv['so_dien_thoai'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày làm việc <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="ngay" id="inputNgay" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Loại lịch <span class="text-danger">*</span></label>
                        <select class="form-select" name="loai_lich" id="inputLoai" required>
                            <option value="có thể làm">Có thể làm</option>
                            <option value="bận">Bận</option>
                            <option value="nghỉ">Nghỉ</option>
                            <option value="đã phân công">Đã phân công</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="ghi_chu" id="inputGhiChu" rows="3" 
                                  placeholder="Nhập ghi chú (nếu có)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> 
                        <span id="btnSubmitText">Lưu Lịch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .btn-primary {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        padding: 12px 8px;
    }

    .table td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .02);
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 2px;
        border-radius: 4px;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 12px 20px;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Stats Cards */
    .stats-card {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stats-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .stats-card .icon.bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stats-card .icon.bg-success {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stats-card .icon.bg-warning {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .stats-card .icon.bg-danger {
        background: linear-gradient(135deg, #ff5858, #f09819);
    }

    .stats-card .icon.bg-info {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stats-card .icon.bg-secondary {
        background: linear-gradient(135deg, #a8c0ff, #3f2b96);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group .btn {
            padding: 0.2rem 0.4rem;
            margin: 0 1px;
        }

        .text-center.py-4 {
            padding: 2rem 1rem !important;
        }

        .card-footer .d-flex {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-success {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }

        .btn-group .btn {
            flex: 1;
            min-width: 36px;
            font-size: 0.75rem;
        }

        .card-footer {
            padding: 10px 15px;
        }

        .card-footer .text-muted {
            font-size: 0.875rem;
        }

        .stats-card .icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .row.mb-4 .col-xl-2 {
            flex: 0 0 50%;
            max-width: 50%;
            margin-bottom: 1rem;
        }
    }
</style>

<script>
$(document).ready(function() {
    // Xử lý sửa lịch làm việc
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        var hdv = $(this).data('hdv');
        var ngay = $(this).data('ngay');
        var loai = $(this).data('loai');
        var ghichu = $(this).data('ghichu');
        
        // Điền dữ liệu vào form
        $('#inputID').val(id);
        $('#inputHDV').val(hdv);
        $('#inputNgay').val(ngay);
        $('#inputLoai').val(loai);
        $('#inputGhiChu').val(ghichu);
        
        // Đổi action và tiêu đề
        $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-cap-nhat');
        $('#modalTitle').html('<i class="fas fa-edit me-2"></i>Sửa Lịch Làm Việc');
        $('#btnSubmitText').text('Cập nhật');
    });
    
    // Reset form khi mở modal thêm mới
    $('#modalThemLich').on('show.bs.modal', function(e) {
        if (!$(e.relatedTarget).hasClass('btn-edit')) {
            $('#formThemLich')[0].reset();
            $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-them');
            $('#modalTitle').html('<i class="fas fa-calendar-plus me-2"></i>Thêm Lịch Làm Việc');
            $('#btnSubmitText').text('Lưu Lịch');
            $('#inputID').val('');
        }
    });

    // Reset form khi đóng modal
    $('#modalThemLich').on('hidden.bs.modal', function() {
        $('#formThemLich')[0].reset();
        $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-them');
        $('#modalTitle').html('<i class="fas fa-calendar-plus me-2"></i>Thêm Lịch Làm Việc');
        $('#btnSubmitText').text('Lưu Lịch');
        $('#inputID').val('');
    });
});
</script>