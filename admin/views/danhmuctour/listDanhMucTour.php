<!-- Header -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="container-fluid">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="">
                        <i class="nav-icon fas fa-folder me-2"></i>
                        Quản Lý Loại Tour
                    </a>
                    <div>
                        <a href="?act=/" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container mt-4">
            <!-- Thông báo -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách danh mục tour</h3>
                    <div class="card-tools">
                        <a href="?act=danh-muc-tour-create" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Thêm danh mục mới
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên danh mục</th>
                                <th>Loại tour</th>
                                <th>Số lượng tour</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($danh_muc_list)): ?>
                                <?php foreach ($danh_muc_list as $index => $danh_muc): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?></td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'trong nước' => 'badge-primary',
                                                'quốc tế' => 'badge-success',
                                                'theo yêu cầu' => 'badge-info'
                                            ];
                                            $class = $badge_class[$danh_muc['loai_tour']] ?? 'badge-secondary';
                                            ?>
                                            <span class="badge <?php echo $class; ?>">
                                                <?php echo $danh_muc['ten_loai_hien_thi']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light">
                                                <?php echo $danh_muc['so_luong_tour']; ?> tour
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($danh_muc['mo_ta'] ?? 'Không có mô tả'); ?></td>
                                        <td>
                                            <?php if ($danh_muc['trang_thai'] == 'hoạt động'): ?>
                                                <span class="badge badge-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Khóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="?act=danh-muc-tour-edit&id=<?php echo $danh_muc['id']; ?>"
                                                    class="btn btn-sm btn-warning" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete"
                                                    data-id="<?php echo $danh_muc['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($danh_muc['ten_danh_muc']); ?>"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                        Chưa có danh mục tour nào
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục "<strong id="deleteName"></strong>"?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        // DataTable
        $('#example1').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            }
        });

        // Xử lý xóa
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#deleteName').text(name);
            $('#confirmDelete').attr('href', '?act=danh-muc-tour-delete&id=' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>