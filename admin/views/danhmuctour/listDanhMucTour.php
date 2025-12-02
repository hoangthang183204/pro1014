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
        <div class="container mt-4">
            <!-- Thông báo session -->
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Thống kê nhanh -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-home"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tour trong nước</span>
                            <span class="info-box-number"><?= $thong_ke['tong_tour_trong_nuoc'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-globe-americas"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tour quốc tế</span>
                            <span class="info-box-number"><?= $thong_ke['tong_tour_quoc_te'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-user-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tour theo yêu cầu</span>
                            <span class="info-box-number"><?= $thong_ke['tong_tour_custom'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-plane"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tour đang diễn ra</span>
                            <span class="info-box-number"><?= $thong_ke['tour_dang_dien_ra'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="nav-icon fas fa-folder me-2"></i>
                        Quản Lý Danh Mục Tour
                    </h3>
                    <div class="card-tools">
                        <a href="?act=danh-muc-tour-create" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Thêm mới
                        </a>
                        <a href="?act=danh-muc-filter" class="btn btn-sm btn-success ml-1">
                            <i class="fas fa-filter mr-1"></i> Lọc tour
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control form-control-sm" id="filterLoaiTour">
                                <option value="">Tất cả loại tour</option>
                                <option value="trong nước">Tour trong nước</option>
                                <option value="quốc tế">Tour quốc tế</option>
                                <option value="theo yêu cầu">Tour theo yêu cầu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control form-control-sm" id="filterTrangThai">
                                <option value="">Tất cả trạng thái</option>
                                <option value="hoạt động">Hoạt động</option>
                                <option value="khóa">Khóa</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Tên danh mục</th>
                                <th width="15%">Loại tour</th>
                                <th width="15%">Số lượng tour</th>
                                <th width="25%">Mô tả</th>
                                <th width="10%">Trạng thái</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($danh_muc_list)): ?>
                                <?php 
                                $loaiTourLabels = [
                                    'trong nước' => ['label' => 'Tour trong nước', 'class' => 'badge-primary'],
                                    'quốc tế' => ['label' => 'Tour quốc tế', 'class' => 'badge-success'],
                                    'theo yêu cầu' => ['label' => 'Tour theo yêu cầu', 'class' => 'badge-info']
                                ];
                                ?>
                                
                                <?php foreach ($danh_muc_list as $index => $danh_muc): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($danh_muc['ten_danh_muc']) ?></strong>
                                            <?php if ($danh_muc['tour_dang_di'] > 0): ?>
                                                <span class="badge badge-danger ml-1" data-toggle="tooltip" title="Tour đang diễn ra">
                                                    <?= $danh_muc['tour_dang_di'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $loai = $danh_muc['loai_tour'];
                                            $info = $loaiTourLabels[$loai] ?? ['label' => $loai, 'class' => 'badge-secondary'];
                                            ?>
                                            <span class="badge <?= $info['class'] ?>">
                                                <?= $info['label'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-light p-2">
                                                    <?= $danh_muc['so_luong_tour'] ?> tour
                                                </span>
                                                <?php if ($danh_muc['so_luong_tour'] > 0): ?>
                                                    <a href="?act=danh-muc-tours&danh_muc_id=<?= $danh_muc['id'] ?>" 
                                                       class="btn btn-xs btn-link ml-2" 
                                                       data-toggle="tooltip" 
                                                       title="Xem danh sách tour">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?= !empty($danh_muc['mo_ta']) ? 
                                                htmlspecialchars(mb_strimwidth($danh_muc['mo_ta'], 0, 50, "...")) : 
                                                '<span class="text-muted">Không có mô tả</span>' ?>
                                        </td>
                                        <td>
                                            <?php if ($danh_muc['trang_thai'] == 'hoạt động'): ?>
                                                <span class="badge badge-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Khóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="?act=danh-muc-tour-edit&id=<?= $danh_muc['id'] ?>"
                                                    class="btn btn-warning" 
                                                    data-toggle="tooltip" 
                                                    title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if ($danh_muc['so_luong_tour'] == 0): ?>
                                                    <button type="button"
                                                        class="btn btn-danger btn-delete"
                                                        data-id="<?= $danh_muc['id'] ?>"
                                                        data-name="<?= htmlspecialchars($danh_muc['ten_danh_muc']) ?>"
                                                        data-toggle="tooltip"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button"
                                                        class="btn btn-danger"
                                                        data-toggle="tooltip"
                                                        title="Không thể xóa (có tour đang sử dụng)"
                                                        disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                        <h5>Chưa có danh mục tour nào</h5>
                                        <p class="mb-0">Hãy tạo danh mục đầu tiên!</p>
                                        <a href="?act=danh-muc-tour-create" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus mr-1"></i> Thêm danh mục
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Tổng cộng: <strong><?= count($danh_muc_list) ?></strong> danh mục
                                | Tour đang diễn ra: <strong><?= $thong_ke['tour_dang_dien_ra'] ?? 0 ?></strong>
                                | Tour sắp diễn ra: <strong><?= $thong_ke['tour_sap_dien_ra'] ?? 0 ?></strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="?act=/" class="btn btn-default">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                    Xác nhận xóa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="deleteName" class="text-danger"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Hành động này sẽ không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Hủy
                </button>
                <a href="#" id="confirmDelete" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i> Xóa
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<style>
    .info-box {
        min-height: 80px;
        border-radius: .25rem;
        margin-bottom: 0;
    }
    .info-box-icon {
        border-top-left-radius: .25rem;
        border-bottom-left-radius: .25rem;
        font-size: 1.875rem;
        width: 70px;
    }
    .info-box-content {
        padding: 10px;
    }
    .info-box-text {
        text-transform: uppercase;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .info-box-number {
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>

<script>
    $(document).ready(function() {
        // Khởi tạo tooltip
        $('[data-toggle="tooltip"]').tooltip();
        
        // DataTable với các tùy chọn
        var table = $('#example1').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json"
            },
            "order": [[0, 'asc']],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            "drawCallback": function(settings) {
                // Re-init tooltips sau khi DataTable vẽ lại
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // Xử lý bộ lọc
        $('#filterLoaiTour').on('change', function() {
            var value = $(this).val();
            table.column(2).search(value).draw();
        });

        $('#filterTrangThai').on('change', function() {
            var value = $(this).val();
            table.column(5).search(value).draw();
        });

        $('#searchBtn').on('click', function() {
            var value = $('#searchInput').val();
            table.search(value).draw();
        });

        $('#searchInput').on('keyup', function(e) {
            if (e.keyCode === 13) {
                table.search(this.value).draw();
            }
        });

        // Xử lý xóa
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#deleteName').text(name);
            $('#confirmDelete').attr('href', '?act=danh-muc-tour-delete&id=' + id);
            $('#deleteModal').modal('show');
        });

        // Clear filters
        $('#clearFilters').on('click', function() {
            $('#filterLoaiTour').val('').trigger('change');
            $('#filterTrangThai').val('').trigger('change');
            $('#searchInput').val('');
            table.search('').columns().search('').draw();
        });

        // Auto-focus search input
        $('#searchInput').focus();
    });
</script>