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
                    <div>
                        <span class="text-light small">
                            <i class="fas fa-info-circle me-1"></i>
                            Quản lý thông tin hướng dẫn viên
                        </span>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Danh sách HDV -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách hướng dẫn viên (<?php echo count($huong_dan_vien_list); ?>)</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> In
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($huong_dan_vien_list)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="table-hdv">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Thông tin HDV</th>
                                            <th width="120">Loại HDV</th>
                                            <th width="120">Ngôn ngữ</th>
                                            <th width="100" class="text-center">Thống kê</th>
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
                                                        <?php if (isset($hdv['hinh_anh']) && $hdv['hinh_anh']): ?>
                                                            <img src="<?php echo htmlspecialchars($hdv['hinh_anh']); ?>" 
                                                                 class="rounded me-3" width="50" height="50" 
                                                                 style="object-fit: cover;" 
                                                                 alt="<?php echo htmlspecialchars($hdv['ho_ten']); ?>">
                                                        <?php else: ?>
                                                            <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-user-tie"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong class="d-block"><?php echo htmlspecialchars($hdv['ho_ten']); ?></strong>
                                                            <?php if (isset($hdv['so_dien_thoai']) && $hdv['so_dien_thoai']): ?>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (isset($hdv['email']) && $hdv['email']): ?>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($hdv['email']); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            <?php if (isset($hdv['so_giay_phep_hanh_nghe']) && $hdv['so_giay_phep_hanh_nghe']): ?>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-id-card me-1"></i>GP: <?php echo htmlspecialchars($hdv['so_giay_phep_hanh_nghe']); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        $loai_hdv = $hdv['loai_huong_dan_vien'] ?? 'nội địa';
                                                        echo match($loai_hdv) {
                                                            'nội địa' => 'primary',
                                                            'quốc tế' => 'warning',
                                                            'chuyên tuyến' => 'info',
                                                            default => 'secondary'
                                                        };
                                                    ?>">
                                                        <?php echo htmlspecialchars($loai_hdv); ?>
                                                    </span>
                                                    <?php if (isset($hdv['chuyen_mon']) && $hdv['chuyen_mon']): ?>
                                                        <br><small class="text-muted mt-1 d-block"><?php echo htmlspecialchars($hdv['chuyen_mon']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $ngon_ngu = [];
                                                    if (isset($hdv['ngon_ngu']) && $hdv['ngon_ngu']) {
                                                        $ngon_ngu = json_decode($hdv['ngon_ngu'], true) ?: [];
                                                    }
                                                    
                                                    if (is_array($ngon_ngu) && !empty($ngon_ngu)):
                                                        echo '<div class="d-flex flex-wrap gap-1">';
                                                        foreach (array_slice($ngon_ngu, 0, 3) as $nn):
                                                    ?>
                                                            <span class="badge bg-dark small"><?php echo strtoupper(htmlspecialchars($nn)); ?></span>
                                                    <?php
                                                        endforeach;
                                                        if (count($ngon_ngu) > 3):
                                                    ?>
                                                            <span class="badge bg-secondary small">+<?php echo count($ngon_ngu) - 3; ?></span>
                                                    <?php
                                                        endif;
                                                        echo '</div>';
                                                    else:
                                                        echo '<span class="text-muted small">Chưa cập nhật</span>';
                                                    endif;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    $so_tour = $hdv['so_tour_da_dan'] ?? 0;
                                                    $danh_gia = $hdv['danh_gia_trung_binh'] ?? 0;
                                                    
                                                    if ($so_tour > 0): ?>
                                                        <div class="text-primary fw-bold"><?php echo $so_tour; ?> tour</div>
                                                        <?php if ($danh_gia > 0): ?>
                                                            <div class="text-warning small">
                                                                <i class="fas fa-star"></i> <?php echo number_format($danh_gia, 1); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Chưa có tour</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    $trang_thai = $hdv['trang_thai'] ?? 'đang làm việc';
                                                    $status_config = match ($trang_thai) {
                                                        'đang làm việc' => ['color' => 'success', 'icon' => 'check-circle'],
                                                        'nghỉ việc' => ['color' => 'danger', 'icon' => 'times-circle'],
                                                        'tạm nghỉ' => ['color' => 'warning', 'icon' => 'pause-circle'],
                                                        default => ['color' => 'secondary', 'icon' => 'question-circle']
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?php echo $status_config['color']; ?>">
                                                        <i class="fas fa-<?php echo $status_config['icon']; ?> me-1"></i>
                                                        <?php echo htmlspecialchars($trang_thai); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="?act=chi-tiet-huong-dan-vien&id=<?php echo $hdv['id']; ?>" 
                                                           class="btn btn-outline-info"
                                                           title="Xem chi tiết"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <!-- <a href="?act=sua-huong-dan-vien&id=<?php echo $hdv['id']; ?>" 
                                                           class="btn btn-outline-warning"
                                                           title="Sửa"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?act=xoa-huong-dan-vien&id=<?php echo $hdv['id']; ?>" 
                                                           class="btn btn-outline-danger"
                                                           title="Xóa"
                                                           data-bs-toggle="tooltip"
                                                           onclick="return confirm('Bạn có chắc chắn muốn xóa hướng dẫn viên <?php echo htmlspecialchars($hdv['ho_ten']); ?> không?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a> -->
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
                                <p class="text-muted mb-4">Liên hệ quản trị hệ thống để thêm hướng dẫn viên</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>

function exportToExcel() {
    const table = document.getElementById('table-hdv');
    const html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'danh-sach-huong-dan-vien-' + new Date().toISOString().split('T')[0] + '.xls';
    link.click();
}
</script>