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
                        <i class="fas fa-calendar-alt me-2"></i>
                        Quản Lý Lịch Làm Việc HDV
                    </a>
                    <div>
                        <a href="?act=huong-dan-vien" class="btn btn-info me-2">
                            <i class="fas fa-user-tie me-1"></i> Quản lý HDV
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= is_array($lich_lam_viec) ? count($lich_lam_viec) : 0 ?></h4>
                                <p class="card-text small mb-0">Tổng lịch làm việc</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1">
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
                                <p class="card-text small mb-0">Có thể làm</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1">
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
                                <p class="card-text small mb-0">Bận</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1">
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
                                <p class="card-text small mb-0">Nghỉ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1">
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
                                <p class="card-text small mb-0">Đã phân công</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-white bg-secondary">
                            <div class="card-body text-center p-3">
                                <h4 class="card-title mb-1"><?= count($hdv_list) ?></h4>
                                <p class="card-text small mb-0">Tổng HDV</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Tìm kiếm lịch làm việc</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="lich-lam-viec-hdv-loc">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="tu_khoa" class="form-control" 
                                           placeholder="Tìm theo tên HDV, số điện thoại hoặc ghi chú..."
                                           value="<?php echo htmlspecialchars($_GET['tu_khoa'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách lịch làm việc -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Lịch Làm Việc (<?php echo is_array($lich_lam_viec) ? count($lich_lam_viec) : 0; ?>)</h5>
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalThemLich">
                            <i class="fas fa-plus me-1"></i> Thêm Lịch Làm Việc
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="lichLamViecTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Thông tin Hướng dẫn viên</th>
                                            <th width="120">Ngày làm việc</th>
                                            <th width="150">Loại lịch</th>
                                            <th>Ghi chú</th>
                                            <th width="100" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lich_lam_viec as $index => $lich): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <strong><?php echo $index + 1; ?></strong>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?php echo htmlspecialchars($lich['ho_ten']); ?></div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($lich['so_dien_thoai']); ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="fw-bold"><?php echo date('d/m/Y', strtotime($lich['ngay'])); ?></div>
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
                                                <td class="text-center">
                                                    <?php
                                                    $badge_class = [
                                                        'có thể làm' => 'success',
                                                        'bận' => 'warning', 
                                                        'nghỉ' => 'danger',
                                                        'đã phân công' => 'info'
                                                    ];
                                                    $class = $badge_class[$lich['loai_lich']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $class ?>">
                                                        <?= $lich['loai_lich'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($lich['ghi_chu'])): ?>
                                                        <?php echo htmlspecialchars($lich['ghi_chu']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?act=lich-lam-viec-hdv-xoa&id=<?= $lich['id'] ?>" 
                                                           class="btn btn-danger"
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
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có lịch làm việc nào</h5>
                                <p class="text-muted">Chưa có lịch làm việc nào trong hệ thống</p>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalThemLich">
                                    <i class="fas fa-plus me-1"></i> Thêm lịch làm việc đầu tiên
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin -->
                    <?php if (is_array($lich_lam_viec) && !empty($lich_lam_viec)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($lich_lam_viec); ?></strong> trong tổng số <strong><?php echo count($lich_lam_viec); ?></strong> lịch làm việc
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Thông tin:</strong> Quản lý lịch làm việc của hướng dẫn viên
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
<div class="modal fade" id="modalThemLich">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle">Thêm Lịch Làm Việc</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formThemLich" method="POST">
                <input type="hidden" name="id" id="inputID">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hướng dẫn viên: <span class="text-danger">*</span></label>
                        <select class="form-control" name="huong_dan_vien_id" id="inputHDV" required>
                            <option value="">Chọn hướng dẫn viên</option>
                            <?php foreach($hdv_list as $hdv): ?>
                            <option value="<?= $hdv['id'] ?>"><?= $hdv['ho_ten'] ?> - <?= $hdv['so_dien_thoai'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày: <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="ngay" id="inputNgay" required>
                    </div>
                    <div class="form-group">
                        <label>Loại lịch: <span class="text-danger">*</span></label>
                        <select class="form-control" name="loai_lich" id="inputLoai" required>
                            <option value="có thể làm">Có thể làm</option>
                            <option value="bận">Bận</option>
                            <option value="nghỉ">Nghỉ</option>
                            <option value="đã phân công">Đã phân công</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú:</label>
                        <textarea class="form-control" name="ghi_chu" id="inputGhiChu" rows="3" 
                                  placeholder="Nhập ghi chú (nếu có)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu Lịch Làm Việc</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>

<!-- DEBUG: Hiển thị dữ liệu để kiểm tra -->
<script>
console.log("Dữ liệu lịch làm việc:", <?= json_encode($lich_lam_viec) ?>);
</script>

<script>
$(document).ready(function() {
    console.log("JavaScript đã được tải!");
    
    // Xử lý thêm/sửa - SỬA QUAN TRỌNG
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
        $('#modalTitle').text('Sửa Lịch Làm Việc');
        
        // Hiển thị modal
        $('#modalThemLich').modal('show');
        
        console.log("Form đã được điền dữ liệu!");
    });
    
    // Reset form khi mở modal thêm mới
    $('#modalThemLich').on('show.bs.modal', function(e) {
        console.log("Modal được mở, relatedTarget:", e.relatedTarget);
        
        if (!$(e.relatedTarget).hasClass('btn-edit')) {
            console.log("Reset form thêm mới");
            $('#formThemLich')[0].reset();
            $('#formThemLich').attr('action', 'index.php?act=lich-lam-viec-hdv-them');
            $('#modalTitle').text('Thêm Lịch Làm Việc');
            $('#inputID').val('');
        }
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
    $('.btn-edit').each(function(index) {
        console.log("Nút sửa " + index + ":", {
            id: $(this).data('id'),
            hdv: $(this).data('hdv'),
            ngay: $(this).data('ngay'),
            loai: $(this).data('loai')
        });
    });
});
</script>

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

    .card .card-body.text-center {
        padding: 1rem !important;
    }

    .card .card-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
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

        .row.mb-4 .col-md-2 {
            margin-bottom: 10px;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-info {
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

        .row.mb-4 {
            margin-bottom: 1rem !important;
        }

        .row.mb-4 .col-md-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>