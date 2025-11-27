<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản Lý Nhật Ký Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>">Home</a></li>
                        <li class="breadcrumb-item active">Nhật ký tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách nhật ký</h3>
                    <div class="card-tools">
                        <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky_add" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Viết Nhật Ký Mới
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 20%">Tour</th>
                                <th style="width: 15%">Ngày Viết</th>
                                <th style="width: 15%">Thời Tiết</th>
                                <th style="width: 30%">Hoạt Động Chính</th>
                                <th style="width: 20%" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($listNhatKy)): ?>
                                <?php foreach ($listNhatKy as $nk): ?>
                                <tr>
                                    <td>
                                        <a><?= htmlspecialchars($nk['ten_tour']) ?></a>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($nk['ngay_nhat_ky'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($nk['thoi_tiet']) ?></td>
                                    <td>
                                        <?= htmlspecialchars(substr($nk['hoat_dong'], 0, 50)) ?>...
                                    </td>
                                    <td class="project-actions text-center">
                                        <a class="btn btn-info btn-sm" href="<?= BASE_URL_GUIDE ?>?act=nhat_ky_edit&id=<?= $nk['id'] ?>">
                                            <i class="fas fa-pencil-alt"></i> Sửa
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="<?= BASE_URL_GUIDE ?>?act=nhat_ky_delete&id=<?= $nk['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa nhật ký này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có nhật ký nào được ghi nhận.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
    </section>
    </div>
<?php include './views/layout/footer.php'; ?>