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
                        <i class="fas fa-suitcase me-2"></i>
                        Quản Lý Tour
                    </a>
                    <a href="?act=tour-create" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm Tour
                    </a>
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

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc tour</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="tour">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm theo mã tour, tên tour..."
                                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="trang_thai" class="form-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="đang hoạt động" <?php echo ($_GET['trang_thai'] ?? '') === 'đang hoạt động' ? 'selected' : ''; ?>>Đang hoạt động</option>
                                        <option value="tạm dừng" <?php echo ($_GET['trang_thai'] ?? '') === 'tạm dừng' ? 'selected' : ''; ?>>Tạm dừng</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="danh_muc_id" class="form-select">
                                        <option value="">Tất cả danh mục</option>
                                        <?php foreach ($danh_muc_list as $danh_muc): ?>
                                            <option value="<?php echo $danh_muc['id']; ?>"
                                                <?php echo ($_GET['danh_muc_id'] ?? '') == $danh_muc['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Lọc
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách tour -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Tour (<?php echo count($tours); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($tours)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="tourTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="120">Mã Tour</th>
                                            <th>Tên Tour</th>
                                            <th width="150">Danh mục</th>
                                            <th width="150" class="text-center">Giá</th>
                                            <th width="120" class="text-center">Trạng thái</th>
                                            <th width="150" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tours as $tour): ?>
                                            <tr>
                                                <td>
                                                    <strong class="text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></strong>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($tour['ten_tour']); ?></div>
                                                    <?php if (!empty($tour['mo_ta'])): ?>
                                                        <small class="text-muted">
                                                            <?php echo htmlspecialchars(mb_substr($tour['mo_ta'], 0, 100) . (strlen($tour['mo_ta']) > 100 ? '...' : '')); ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($tour['ten_danh_muc'] ?? 'Chưa phân loại'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($tour['gia_tour']): ?>
                                                        <strong class="text-success"><?php echo number_format($tour['gia_tour'], 0, ',', '.'); ?> VNĐ</strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">Liên hệ</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($tour['trang_thai'] === 'đang hoạt động'): ?>
                                                        <span class="badge bg-success">Đang hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tạm dừng</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="?act=tour-edit&id=<?php echo $tour['id']; ?>" 
                                                           class="btn btn-primary btn-sm" title="Sửa tour">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>" 
                                                           class="btn btn-info btn-sm" title="Lịch trình">
                                                            <i class="fas fa-route"></i>
                                                        </a>
                                                        <a href="?act=tour-media&tour_id=<?php echo $tour['id']; ?>" 
                                                           class="btn btn-warning btn-sm" title="Hình ảnh">
                                                            <i class="fas fa-images"></i>
                                                        </a>
                                                        <a href="?act=tour-delete&id=<?php echo $tour['id']; ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
                                                           title="Xóa tour">
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
                                <i class="fas fa-suitcase fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có tour nào</h5>
                                <p class="text-muted">Hãy thêm tour mới để bắt đầu quản lý</p>
                                <a href="?act=tour-create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Thêm Tour Đầu Tiên
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Footer với thông tin phân trang -->
                    <?php if (!empty($tours)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($tours); ?></strong> trong tổng số <strong><?php echo count($tours); ?></strong> mục
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>

</script>

<style>
.form-control,
.form-select {
    padding: 8px 14px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

</style>