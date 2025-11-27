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
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống Kê Khách Hàng
                    </a>
                    <a href="?act=khach-hang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thống kê theo tháng -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Thống kê Khách hàng theo Tháng</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($thong_ke_chi_tiet)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center">Tháng/Năm</th>
                                            <th class="text-center">Tổng khách hàng</th>
                                            <th class="text-center">Khách có tour</th>
                                            <th class="text-center">Tỷ lệ có tour</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($thong_ke_chi_tiet as $thong_ke): ?>
                                        <tr>
                                            <td class="text-center fw-bold">
                                                Tháng <?php echo $thong_ke['thang']; ?>/<?php echo $thong_ke['nam']; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary"><?php echo $thong_ke['so_luong']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?php echo $thong_ke['khach_co_tour']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $ty_le = $thong_ke['so_luong'] > 0 ? 
                                                    round(($thong_ke['khach_co_tour'] / $thong_ke['so_luong']) * 100, 1) : 0;
                                                $ty_le_class = $ty_le >= 50 ? 'success' : ($ty_le >= 30 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?php echo $ty_le_class; ?>">
                                                    <?php echo $ty_le; ?>%
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có dữ liệu thống kê</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
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

    .badge {
        font-size: 0.875em;
        padding: 0.5em 0.75em;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>