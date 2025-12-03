<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=huong-dan-vien">
                        <i class="fas fa-user-tie me-2"></i>
                        Danh Sách Hướng Dẫn Viên
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h3 class="mb-0"><?php echo count($huong_dan_vien_list); ?></h3>
                                <small class="text-primary">
                                    <i class="fas fa-users me-1"></i> Tổng HDV
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php
                                    $dang_lam_viec = 0;
                                    foreach ($huong_dan_vien_list as $hdv) {
                                        if (isset($hdv['trang_thai']) && $hdv['trang_thai'] == 'đang làm việc') {
                                            $dang_lam_viec++;
                                        }
                                    }
                                    echo $dang_lam_viec;
                                    ?>
                                </h3>
                                <small class="text-success">
                                    <i class="fas fa-user-check me-1"></i> Đang làm việc
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php
                                    $noi_dia = 0;
                                    foreach ($huong_dan_vien_list as $hdv) {
                                        if (isset($hdv['loai_huong_dan_vien']) && $hdv['loai_huong_dan_vien'] == 'nội địa') {
                                            $noi_dia++;
                                        }
                                    }
                                    echo $noi_dia;
                                    ?>
                                </h3>
                                <small class="text-warning">
                                    <i class="fas fa-map-marker-alt me-1"></i> Nội địa
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php
                                    $quoc_te = 0;
                                    foreach ($huong_dan_vien_list as $hdv) {
                                        if (isset($hdv['loai_huong_dan_vien']) && $hdv['loai_huong_dan_vien'] == 'quốc tế') {
                                            $quoc_te++;
                                        }
                                    }
                                    echo $quoc_te;
                                    ?>
                                </h3>
                                <small class="text-info">
                                    <i class="fas fa-globe me-1"></i> Quốc tế
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách HDV -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách hướng dẫn viên</h5>
                        <span class="badge bg-dark"><?php echo count($huong_dan_vien_list); ?> HDV</span>
                    </div>

                    <div class="card-body p-0">
                        <?php if (!empty($huong_dan_vien_list)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="table-hdv">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Thông tin HDV</th>
                                            <th width="120">Loại HDV</th>
                                            <th width="120">Ngôn ngữ</th>
                                            <th width="100" class="text-center">Số tour</th>
                                            <th width="120" class="text-center">Trạng thái</th>
                                            <th width="150" class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($huong_dan_vien_list as $index => $hdv): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        <?php if (isset($hdv['hinh_anh']) && !empty($hdv['hinh_anh'])): ?>
                                                            <img src="<?php echo htmlspecialchars($hdv['hinh_anh']); ?>"
                                                                class="rounded-circle me-3"
                                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                                alt="<?php echo htmlspecialchars($hdv['ho_ten']); ?>">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong class="d-block"><?php echo htmlspecialchars($hdv['ho_ten']); ?></strong>
                                                            <small class="text-muted d-block">
                                                                <i class="fas fa-id-badge me-1"></i>ID: <?php echo $hdv['id']; ?>
                                                            </small>
                                                            <?php if (isset($hdv['so_dien_thoai']) && !empty($hdv['so_dien_thoai'])): ?>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (isset($hdv['email']) && !empty($hdv['email'])): ?>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($hdv['email']); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $loai_hdv = isset($hdv['loai_huong_dan_vien']) ? $hdv['loai_huong_dan_vien'] : 'nội địa';
                                                    $badge_color = match ($loai_hdv) {
                                                        'nội địa' => 'bg-primary',
                                                        'quốc tế' => 'bg-warning',
                                                        'chuyên tuyến' => 'bg-info',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <span class="badge <?php echo $badge_color; ?>">
                                                        <?php echo htmlspecialchars($loai_hdv); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $ngon_ngu = [];
                                                    if (isset($hdv['ngon_ngu']) && !empty($hdv['ngon_ngu'])) {                                                      
                                                        if (is_string($hdv['ngon_ngu'])) {
                                                            $ngon_ngu_data = json_decode($hdv['ngon_ngu'], true);
                                                            if (is_array($ngon_ngu_data)) {
                                                                $ngon_ngu = $ngon_ngu_data;
                                                            }
                                                        }
                                                        elseif (is_array($hdv['ngon_ngu'])) {
                                                            $ngon_ngu = $hdv['ngon_ngu'];
                                                        }
                                                    }

                                                    if (!empty($ngon_ngu)):
                                                        echo '<div class="d-flex flex-wrap gap-1">';
                                                        foreach ($ngon_ngu as $nn):
                                                            if (!empty($nn)):
                                                    ?>
                                                                <span class="badge bg-dark small"><?php echo strtoupper(htmlspecialchars($nn)); ?></span>
                                                    <?php
                                                            endif;
                                                        endforeach;
                                                        echo '</div>';
                                                    else:
                                                        echo '<span class="text-muted small">Chưa cập nhật</span>';
                                                    endif;
                                                    ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    $so_tour = isset($hdv['so_tour_da_dan']) ? (int)$hdv['so_tour_da_dan'] : 0;
                                                    if ($so_tour > 0):
                                                    ?>
                                                        <span class="badge bg-primary" style="font-size: 14px;"
                                                            data-bs-toggle="tooltip" title="<?php echo $so_tour; ?> tour đã dẫn">
                                                            <?php echo $so_tour; ?> tour
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary" style="font-size: 14px;"
                                                            data-bs-toggle="tooltip" title="Chưa có tour nào">
                                                            Chưa có
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $trang_thai = isset($hdv['trang_thai']) ? $hdv['trang_thai'] : 'đang làm việc';
                                                    $status_config = match ($trang_thai) {
                                                        'đang làm việc' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Đang làm việc'],
                                                        'nghỉ việc' => ['color' => 'danger', 'icon' => 'times-circle', 'text' => 'Nghỉ việc'],
                                                        'tạm nghỉ' => ['color' => 'warning', 'icon' => 'pause-circle', 'text' => 'Tạm nghỉ'],
                                                        default => ['color' => 'secondary', 'icon' => 'question-circle', 'text' => 'Không xác định']
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?php echo $status_config['color']; ?>">
                                                        <i class="fas fa-<?php echo $status_config['icon']; ?> me-1"></i>
                                                        <?php echo $status_config['text']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="?act=huong-dan-vien-chi-tiet&id=<?php echo $hdv['id']; ?>"
                                                            class="btn btn-outline-info"
                                                            title="Xem chi tiết"
                                                            data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
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
                                <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Chưa có hướng dẫn viên nào</h4>
                                <p class="text-muted mb-4">Hãy thêm hướng dẫn viên mới</p>
                                <a href="?act=huong-dan-vien-them" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Thêm HDV mới
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function confirmDelete(id, name) {
        if (confirm('Bạn có chắc chắn muốn xóa hướng dẫn viên "' + name + '" không?')) {
            window.location.href = '?act=huong-dan-vien-xoa&id=' + id;
        }
    }

    function exportToExcel() {
        const table = document.getElementById('table-hdv');
        if (!table) return;

        const html = table.outerHTML;
        const url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'danh-sach-huong-dan-vien-' + new Date().toISOString().split('T')[0] + '.xls';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Tự động ẩn thông báo sau 5 giây
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);

        // Khởi tạo tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

<style>
    .table td {
        vertical-align: middle;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .badge {
        font-weight: 500;
        padding: 5px 10px;
    }
</style>

<?php include './views/layout/footer.php'; ?>