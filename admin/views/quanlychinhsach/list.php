<?php
// File: views/quanlychinhsach/list.php
require './views/layout/header.php';
include './views/layout/navbar.php';
include './views/layout/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h3><i class="fas fa-file-contract me-2"></i> Danh sách chính sách</h3>
                </div>
            </div>

            <?php if (empty($chinh_sach_list)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Chưa có chính sách nào.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ID</th>
                                <th>Tên chính sách</th>
                                <th width="150">Ngày tạo</th>
                                <th width="120">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chinh_sach_list as $cs): ?>
                                <tr>
                                    <td><?php echo $cs['id']; ?></td>
                                    <td>
                                        <a href="?act=chinh-sach-view&id=<?php echo $cs['id']; ?>" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($cs['ten_chinh_sach']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($cs['created_at'])); ?>
                                    </td>
                                    <td>
                                        <a href="?act=chinh-sach-view&id=<?php echo $cs['id']; ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>