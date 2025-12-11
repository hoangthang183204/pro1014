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
                    <a class="navbar-brand" href="?act=danh-muc">
                        <i class="fas fa-file-contract me-2"></i>
                        Quản Lý Danh Mục Tour
                    </a>
                    <a href="?act=danh-muc-tour-create" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm Danh Mục
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="flex-grow-1">
                                <?php echo htmlspecialchars($_SESSION['success']); ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div class="flex-grow-1">
                                <?php echo htmlspecialchars($_SESSION['error']); ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc danh mục</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select class="form-control" id="filterLoaiTour">
                                    <option value="">Tất cả loại tour</option>
                                    <option value="trong nước">Tour trong nước</option>
                                    <option value="quốc tế">Tour quốc tế</option>
                                    <option value="theo yêu cầu">Tour theo yêu cầu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="filterTrangThai">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="hoạt động">Hoạt động</option>
                                    <option value="khóa">Khóa</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-center gap-2">

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Tìm kiếm..."
                                    id="searchInput">

                                <!-- Nút tìm -->
                                <button class="btn btn-outline-primary px-3" id="searchBtn" title="Tìm kiếm">
                                    <i class="fas fa-search"></i>
                                </button>

                                <!-- Nút xóa -->
                                <button class="btn btn-outline-danger px-3" id="clearFilters" title="Xóa tìm kiếm">
                                    <i class="fas fa-times"></i>
                                </button>

                            </div>


                        </div>
                    </div>
                </div>

                <!-- Danh sách danh mục -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Danh Mục (<?php echo count($danh_muc_list); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($danh_muc_list)): ?>
                            <?php
                            $loaiTourLabels = [
                                'trong nước' => ['label' => 'Tour trong nước', 'class' => 'bg-primary'],
                                'quốc tế' => ['label' => 'Tour quốc tế', 'class' => 'bg-success'],
                                'theo yêu cầu' => ['label' => 'Tour theo yêu cầu', 'class' => 'bg-info']
                            ];
                            ?>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="danhMucTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50" class="text-center">#</th>
                                            <th width="200">Tên danh mục</th>
                                            <th width="150">Loại tour</th>
                                            <th width="120" class="text-center">Số lượng tour</th>
                                            <th width="200">Mô tả</th>
                                            <th width="120" class="text-center">Trạng thái</th>
                                            <th width="150" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $stt = 1; ?>
                                        <?php foreach ($danh_muc_list as $danh_muc): ?>
                                            <?php $cho_phep_xoa = ($danh_muc['so_luong_tour'] == 0); ?>
                                            <tr>
                                                <td class="text-center"><?= $stt++; ?></td>
                                                <td>
                                                    <div>
                                                        <strong class="text-primary"><?= htmlspecialchars($danh_muc['ten_danh_muc']) ?></strong>
                                                        <?php if ($danh_muc['tour_dang_di'] > 0): ?>
                                                            <br>
                                                            <small class="text-danger">
                                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                                Đang có <?= $danh_muc['tour_dang_di'] ?> tour đang diễn ra
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $loai = $danh_muc['loai_tour'];
                                                    $info = $loaiTourLabels[$loai] ?? ['label' => $loai, 'class' => 'bg-secondary'];
                                                    ?>
                                                    <span class="badge <?= $info['class'] ?>">
                                                        <?= $info['label'] ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        <strong class="text-success"><?= $danh_muc['so_luong_tour'] ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (!empty($danh_muc['mo_ta'])): ?>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars(mb_substr($danh_muc['mo_ta'], 0, 80) . (strlen($danh_muc['mo_ta']) > 80 ? '...' : '')); ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Không có mô tả</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($danh_muc['trang_thai'] == 'hoạt động'): ?>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Khóa</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <!-- Nút Sửa -->
                                                        <a href="?act=danh-muc-tour-edit&id=<?= $danh_muc['id'] ?>"
                                                            class="btn btn-primary" title="Sửa danh mục">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- Nút Xoá -->
                                                        <?php if ($cho_phep_xoa): ?>
                                                            <a href="javascript:if(confirm('Bạn có chắc muốn xóa danh mục <?= addslashes(htmlspecialchars($danh_muc['ten_danh_muc'])) ?>?')) window.location.href='?act=danh-muc-tour-delete&id=<?= $danh_muc['id'] ?>';"
                                                                class="btn btn-danger"
                                                                title="Xóa danh mục">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary"
                                                                disabled
                                                                title="Không thể xóa danh mục đang có tour">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có danh mục nào</h5>
                                <p class="text-muted">Hãy tạo danh mục mới để quản lý tour</p>
                                <a href="?act=danh-muc-tour-create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tạo Danh Mục Đầu Tiên
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin thống kê -->
                    <?php if (!empty($danh_muc_list)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Tổng: <strong><?= count($danh_muc_list) ?></strong> danh mục
                                    <?php if (($thong_ke['tong_tour'] ?? 0) > 0): ?>
                                        | Tour: <strong><?= $thong_ke['tong_tour'] ?? 0 ?></strong>
                                    <?php endif; ?>
                                </div>
                                <div id="paginationContainer"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>

<style>
    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .btn-primary {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        padding: 12px 8px;
    }

    .table td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .02);
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 2px;
        border-radius: 4px;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 12px 20px;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group .btn {
            padding: 0.2rem 0.4rem;
            margin: 0 1px;
        }

        .text-center.py-4 {
            padding: 2rem 1rem !important;
        }

        .card-footer .d-flex {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }

        .input-group-append .btn {
            padding: 8px 12px;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-success {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }

        .btn-group .btn {
            flex: 1;
            min-width: 36px;
            font-size: 0.75rem;
        }

        .card-footer {
            padding: 10px 15px;
        }

        .card-footer .text-muted {
            font-size: 0.875rem;
        }

        .input-group {
            flex-wrap: nowrap;
        }

        .input-group .form-control {
            font-size: 0.875rem;
            padding: 6px 10px;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Khởi tạo DataTable với cấu hình đơn giản
        var table = $('#danhMucTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json",
                "info": "", // Ẩn thông tin hiển thị
                "infoEmpty": "",
                "infoFiltered": "",
                "lengthMenu": "Hiển thị _MENU_ mục",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                }
            },
            "order": [
                [0, 'asc']
            ],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "dom": 'rt<"row"<"col-md-12"p>>', // Chỉ hiển thị phân trang
            "drawCallback": function(settings) {
                movePaginationToFooter();
            },
            "initComplete": function() {
                // Ẩn các phần không cần thiết
                $('.dataTables_length').hide();
                $('.dataTables_filter').hide();
                $('.dataTables_info').hide();
            }
        });

        // Di chuyển phân trang vào footer
        function movePaginationToFooter() {
            var pagination = $('.dataTables_paginate');
            $('#paginationContainer').html(pagination.clone());
            pagination.hide();
        }

        // Xử lý bộ lọc loại tour
        $('#filterLoaiTour').on('change', function() {
            var value = $(this).val();
            table.column(2).search(value).draw();
        });

        // Xử lý bộ lọc trạng thái
        $('#filterTrangThai').on('change', function() {
            var value = $(this).val();
            table.column(5).search(value).draw();
        });

        // Xử lý tìm kiếm
        $('#searchBtn').on('click', function() {
            var value = $('#searchInput').val();
            table.search(value).draw();
        });

        $('#searchInput').on('keyup', function(e) {
            if (e.keyCode === 13) {
                table.search(this.value).draw();
            }
        });

        // Xóa bộ lọc
        $('#clearFilters').on('click', function() {
            $('#filterLoaiTour').val('').trigger('change');
            $('#filterTrangThai').val('').trigger('change');
            $('#searchInput').val('');
            table.search('').columns().search('').draw();
        });

        // Auto-focus search input
        $('#searchInput').focus();

        // Di chuyển phân trang vào footer
        movePaginationToFooter();
    });
</script>