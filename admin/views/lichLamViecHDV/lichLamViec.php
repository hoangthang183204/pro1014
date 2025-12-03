<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container mt-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mt-3 text-dark">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Quản Lý Lịch Làm Việc HDV
                    </h1>
                </div>
                <div>
                    <a href="?act=huong-dan-vien" class="btn btn-outline-primary">
                        <i class="fas fa-user-tie me-1"></i> Quản lý HDV
                    </a>
                </div>
            </div>

            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Thống kê nhanh -->
            <div class="row mb-4">
                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1"><?= is_array($lich_lam_viec) ? count($lich_lam_viec) : 0 ?></h3>
                            <p class="card-text text-muted small">Tổng lịch làm việc</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1">
                                <?php 
                                if (is_array($lich_lam_viec)) {
                                    echo count(array_filter($lich_lam_viec, function($item) { 
                                        return $item['loai_lich'] == 'có thể làm'; 
                                    }));
                                } else {
                                    echo 0;
                                }
                                ?>
                            </h3>
                            <p class="card-text text-muted small">Có thể làm</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1">
                                <?php 
                                if (is_array($lich_lam_viec)) {
                                    echo count(array_filter($lich_lam_viec, function($item) { 
                                        return $item['loai_lich'] == 'bận'; 
                                    }));
                                } else {
                                    echo 0;
                                }
                                ?>
                            </h3>
                            <p class="card-text text-muted small">Bận</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="fas fa-bed fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1">
                                <?php 
                                if (is_array($lich_lam_viec)) {
                                    echo count(array_filter($lich_lam_viec, function($item) { 
                                        return $item['loai_lich'] == 'nghỉ'; 
                                    }));
                                } else {
                                    echo 0;
                                }
                                ?>
                            </h3>
                            <p class="card-text text-muted small">Nghỉ</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1">
                                <?php 
                                if (is_array($lich_lam_viec)) {
                                    echo count(array_filter($lich_lam_viec, function($item) { 
                                        return $item['loai_lich'] == 'đã phân công'; 
                                    }));
                                } else {
                                    echo 0;
                                }
                                ?>
                            </h3>
                            <p class="card-text text-muted small">Đã phân công</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-secondary mb-2">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h3 class="card-title mb-1"><?= count($hdv_list) ?></h3>
                            <p class="card-text text-muted small">Tổng HDV</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-search text-primary me-2"></i>Tìm kiếm lịch làm việc
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <input type="hidden" name="act" value="lich-lam-viec-hdv-loc">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="tu_khoa" class="form-control border-start-0" 
                                           placeholder="Tìm theo tên HDV, số điện thoại hoặc ghi chú..."
                                           value="<?php echo htmlspecialchars($_GET['tu_khoa'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>
                            <?php if (!empty($_GET['tu_khoa'])): ?>
                            <div class="col-md-2">
                                <a href="index.php?act=lich-lam-viec-hdv" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-1"></i> Xóa lọc
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách lịch làm việc -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-list text-primary me-2"></i>Danh sách Lịch Làm Việc
                        <span class="badge bg-primary ms-2"><?php echo is_array($lich_lam_viec) ? count($lich_lam_viec) : 0; ?></span>
                    </h5>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalThemLich">
                        <i class="fas fa-plus me-1"></i> Thêm Lịch Làm Việc
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="lichLamViecTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50" class="text-center">#</th>
                                        <th>Thông tin Hướng dẫn viên</th>
                                        <th width="120" class="text-center">Ngày làm việc</th>
                                        <th width="150" class="text-center">Loại lịch</th>
                                        <th>Ghi chú</th>
                                        <th width="100" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lich_lam_viec as $index => $lich): ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold text-dark"><?php echo $index + 1; ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($lich['ho_ten']); ?></div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-phone fa-xs me-1"></i>
                                                    <?php echo htmlspecialchars($lich['so_dien_thoai']); ?>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="fw-bold text-dark"><?php echo date('d/m/Y', strtotime($lich['ngay'])); ?></div>
                                                <div class="small text-muted">
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
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php
                                                $badge_class = [
                                                    'có thể làm' => 'success',
                                                    'bận' => 'warning', 
                                                    'nghỉ' => 'danger',
                                                    'đã phân công' => 'info'
                                                ];
                                                $class = $badge_class[$lich['loai_lich']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $class ?> px-3 py-2">
                                                    <?= $lich['loai_lich'] ?>
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <?php if (!empty($lich['ghi_chu'])): ?>
                                                    <?php echo htmlspecialchars($lich['ghi_chu']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">---</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm">
                                                    <!-- Nút sửa - ĐÃ THÊM VÀO -->
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm btn-edit"
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
                                                    
                                                    <a href="?act=lich-lam-viec-hdv-xoa&id=<?= $lich['id'] ?>" 
                                                       class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa lịch làm việc này?')"
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
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-2">Không có lịch làm việc nào</h5>
                            <p class="text-muted mb-4">
                                <?php if (!empty($_GET['tu_khoa'])): ?>
                                    Không tìm thấy lịch làm việc phù hợp với từ khóa "<?php echo htmlspecialchars($_GET['tu_khoa']); ?>"
                                <?php else: ?>
                                    Chưa có lịch làm việc nào trong hệ thống
                                <?php endif; ?>
                            </p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalThemLich">
                                <i class="fas fa-plus me-1"></i> Thêm lịch làm việc đầu tiên
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Đang xem <strong>1</strong> đến <strong><?php echo count($lich_lam_viec); ?></strong> trong tổng số <strong><?php echo count($lich_lam_viec); ?></strong> lịch làm việc
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                Cập nhật: <?php echo date('d/m/Y H:i'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal thêm/sửa lịch -->
<div class="modal fade" id="modalThemLich" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Thêm Lịch Làm Việc
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form id="formThemLich" method="POST">
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-dark">
                            <i class="fas fa-user-tie me-1 text-primary"></i>
                            Hướng dẫn viên <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="huong_dan_vien_id" id="inputHDV" required>
                            <option value="">-- Chọn hướng dẫn viên --</option>
                            <?php foreach($hdv_list as $hdv): ?>
                            <option value="<?= $hdv['id'] ?>"><?= $hdv['ho_ten'] ?> - <?= $hdv['so_dien_thoai'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">
                            <i class="fas fa-calendar-day me-1 text-primary"></i>
                            Ngày làm việc <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" name="ngay" id="inputNgay" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">
                            <i class="fas fa-tags me-1 text-primary"></i>
                            Loại lịch <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="loai_lich" id="inputLoai" required>
                            <option value="có thể làm">Có thể làm</option>
                            <option value="bận">Bận</option>
                            <option value="nghỉ">Nghỉ</option>
                            <option value="đã phân công">Đã phân công</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark">
                            <i class="fas fa-sticky-note me-1 text-primary"></i>
                            Ghi chú
                        </label>
                        <textarea class="form-control" name="ghi_chu" id="inputGhiChu" rows="3" 
                                  placeholder="Nhập ghi chú (nếu có)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> 
                        <span id="btnSubmitText">Lưu Lịch Làm Việc</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    console.log("JavaScript đã được tải!");
    
    // Xử lý sửa lịch làm việc
    $(document).on('click', '.btn-edit', function() {
        console.log("Nút sửa được click!");
        
        var id = $(this).data('id');
        var hdv = $(this).data('hdv');
        var ngay = $(this).data('ngay');
        var loai = $(this).data('loai');
        var ghichu = $(this).data('ghichu');
        
        console.log("Dữ liệu nhận được:", {
            id: id,
            hdv: hdv,
            ngay: ngay,
            loai: loai,
            ghichu: ghichu
        });
        
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
        
        console.log("Form đã được điền dữ liệu!");
    });
    
    // Reset form khi mở modal thêm mới
    $('#modalThemLich').on('show.bs.modal', function(e) {
        console.log("Modal được mở, relatedTarget:", e.relatedTarget);
        
        if (!$(e.relatedTarget).hasClass('btn-edit')) {
            console.log("Reset form thêm mới");
            $('#formThemLich')[0].reset();
            $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-them');
            $('#modalTitle').html('<i class="fas fa-calendar-plus me-2"></i>Thêm Lịch Làm Việc');
            $('#btnSubmitText').text('Lưu Lịch Làm Việc');
            $('#inputID').val('');
        }
    });

    // Reset form khi đóng modal
    $('#modalThemLich').on('hidden.bs.modal', function() {
        console.log("Modal đóng, reset form");
        $('#formThemLich')[0].reset();
        $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-them');
        $('#modalTitle').html('<i class="fas fa-calendar-plus me-2"></i>Thêm Lịch Làm Việc');
        $('#btnSubmitText').text('Lưu Lịch Làm Việc');
        $('#inputID').val('');
    });

    // Debug form submit
    $('#formThemLich').on('submit', function(e) {
        console.log("Form submitting to:", $(this).attr('action'));
        console.log("Form data:", {
            id: $('#inputID').val(),
            huong_dan_vien_id: $('#inputHDV').val(),
            ngay: $('#inputNgay').val(),
            loai_lich: $('#inputLoai').val(),
            ghi_chu: $('#inputGhiChu').val()
        });
    });

    // Kiểm tra xem nút sửa có tồn tại không
    console.log("Số nút sửa:", $('.btn-edit').length);
});
</script>

<style>
/* Tùy chỉnh giao diện */
.card {
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,.125);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px;
    font-size: 0.9rem;
}

.table td {
    padding: 12px;
    vertical-align: middle;
    border-color: #f1f3f4;
}

.table-hover tbody tr:hover {
    background-color: rgba(13,110,253,.03);
}

.badge {
    padding: 0.4em 0.8em;
    font-weight: 500;
    border-radius: 20px;
    font-size: 0.85rem;
}

.btn-group .btn {
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
}

.modal-content {
    border-radius: 10px;
    border: none;
}

.modal-header {
    border-radius: 10px 10px 0 0;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .col-xl-2.col-md-4.col-sm-6 {
        margin-bottom: 0.75rem;
    }
}

@media (max-width: 576px) {
    .h3 {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.3s ease;
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>