<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-building me-2"></i>
                        Quản lý Nhà Cung Cấp
                    </a>
                    <div>
                        <a href="?act=tour-nha-cung-cap-create" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Thêm mới
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Search và Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="index.php" class="row g-3">
                            <input type="hidden" name="act" value="tour-nha-cung-cap-list">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, số điện thoại..." value="<?php echo $_GET['search'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="loai_dich_vu" class="form-select">
                                    <option value="">Tất cả loại dịch vụ</option>
                                    <option value="vận chuyển" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'vận chuyển' ? 'selected' : ''; ?>>Vận chuyển</option>
                                    <option value="khách sạn" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'khách sạn' ? 'selected' : ''; ?>>Khách sạn</option>
                                    <option value="nhà hàng" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'nhà hàng' ? 'selected' : ''; ?>>Nhà hàng</option>
                                    <option value="vé máy bay" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'vé máy bay' ? 'selected' : ''; ?>>Vé máy bay</option>
                                    <option value="vé tham quan" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'vé tham quan' ? 'selected' : ''; ?>>Vé tham quan</option>
                                    <option value="visa" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'visa' ? 'selected' : ''; ?>>Visa</option>
                                    <option value="bảo hiểm" <?php echo ($_GET['loai_dich_vu'] ?? '') == 'bảo hiểm' ? 'selected' : ''; ?>>Bảo hiểm</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($nha_cung_cap_list)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có nhà cung cấp nào</h5>
                                <p class="text-muted">Hãy thêm nhà cung cấp đầu tiên</p>
                                <a href="?act=tour-nha-cung-cap-create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Thêm nhà cung cấp
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên nhà cung cấp</th>
                                            <th>Loại dịch vụ</th>
                                            <th>Liên hệ</th>
                                            <th>Địa chỉ</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($nha_cung_cap_list as $ncc): ?>
                                            <tr>
                                                <td><?php echo $ncc['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($ncc['ten_nha_cung_cap']); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($ncc['email']); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?php echo $ncc['loai_dich_vu']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>
                                                        <i class="fas fa-phone"></i> <?php echo $ncc['so_dien_thoai']; ?><br>
                                                    </small>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars(substr($ncc['dia_chi'], 0, 50)); ?>
                                                        <?php if (strlen($ncc['dia_chi']) > 50): ?>...<?php endif; ?></small>
                                                </td>
                                                <!-- Cập nhật phần hành động trong table -->
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="?act=tour-nha-cung-cap-edit&id=<?php echo $ncc['id']; ?>"
                                                            class="btn btn-outline-info" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?act=tour-nha-cung-cap-delete&id=<?php echo $ncc['id']; ?>"
                                                            class="btn btn-outline-danger delete-ncc"
                                                            data-name="<?php echo htmlspecialchars($ncc['ten_nha_cung_cap']); ?>"
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
    </section>
</div>

<?php include './views/layout/footer.php'; ?>