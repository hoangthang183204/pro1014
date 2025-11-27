<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="page-title d-flex justify-content-between align-items-center">
        <h3>Quản Lý Nhật Ký Tour</h3>
        <a href="?act=nhat_ky_add" class="btn btn-primary"><i class="fas fa-plus"></i> Viết Nhật Ký Mới</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Ngày</th>
                        <th>Thời Tiết</th>
                        <th>Hoạt Động Chính</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listNhatKy)): ?>
                        <?php foreach ($listNhatKy as $nk): ?>
                        <tr>
                            <td><?= $nk['ten_tour'] ?></td>
                            <td><?= date('d/m/Y', strtotime($nk['ngay_nhat_ky'])) ?></td>
                            <td><?= $nk['thoi_tiet'] ?></td>
                            <td><?= substr($nk['hoat_dong'], 0, 50) ?>...</td>
                            <td>
                                <a href="?act=nhat_ky_edit&id=<?= $nk['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="?act=nhat_ky_delete&id=<?= $nk['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Chưa có nhật ký nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>