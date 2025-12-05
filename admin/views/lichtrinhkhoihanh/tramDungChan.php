<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<?php
$lich_khoi_hanh_id = $lich_khoi_hanh['id'];
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=lich-khoi-hanh">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Quản Lý Lộ Trình
                    </a>
                    <div>
                        <a href="?act=lich-khoi-hanh" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Thông tin tour -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Mã tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour']); ?></p>
                                <p><strong>Tên tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour']); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Ngày bắt đầu:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?></p>
                                <p><strong>Ngày kết thúc:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Trạng thái:</strong> 
                                    <span class="badge bg-<?php
                                        echo match ($lich_khoi_hanh['trang_thai_hien_tai']) {
                                            'đã lên lịch' => 'success',
                                            'đang đi' => 'warning',
                                            'đã hoàn thành' => 'primary',
                                            'đã hủy' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo htmlspecialchars($lich_khoi_hanh['trang_thai_hien_tai']); ?>
                                    </span>
                                </p>
                                <p><strong>Số chỗ:</strong> <?php echo $lich_khoi_hanh['so_cho_con_lai']; ?>/<?php echo $lich_khoi_hanh['so_cho_toi_da']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Form thêm trạm -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm Lộ Trình</h5>
                            </div>
                            <div class="card-body">
                                <form action="?act=tram-dung-chan-them" method="POST">
                                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh_id; ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tên lộ trình *</label>
                                        <input type="text" name="ten_tram" class="form-control" required 
                                               placeholder="VD: Điểm đón, Trạm nghỉ, Điểm đến...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Thứ tự *</label>
                                        <input type="number" name="thu_tu" class="form-control" min="1" value="1" required>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-plus me-1"></i> Thêm lộ trình
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Danh sách trạm -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách lộ trình</h5>
                                <span class="badge bg-light text-dark"><?php echo count($tram_dung_chan); ?> lộ trình</span>
                            </div>
                            <div class="card-body p-0">
                                <?php if (empty($tram_dung_chan)): ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Chưa có lộ trình</h5>
                                        <p class="text-muted">Hãy thêm trạm đầu tiên cho tour này</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>Tên lộ trình</th>
                                                    <th width="180" class="text-center">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tram_dung_chan as $tram): ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <strong><?php echo $tram['thu_tu']; ?></strong>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($tram['ten_tram']); ?></strong>
                                                        </td>
                                                        
                                                      
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="?act=xoa-tram&id=<?php echo $tram['id']; ?>&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh_id; ?>"
                                                                   class="btn btn-danger"
                                                                   onclick="return confirm('Bạn có chắc muốn xóa trạm này?')"
                                                                   title="Xóa">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>