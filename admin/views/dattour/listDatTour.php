<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-calendar-check me-2"></i>
                        Quản Lý Đặt Tour
                    </a>
                    <div class="d-flex gap-5">
                        <a href="?act=dat-tour-create" class="btn btn-success mx-2">
                            <i class="fas fa-plus me-1"></i> Đặt Tour Mới
                        </a>
                    </div>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Bộ lọc -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc đặt tour</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="dat-tour">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm mã, tên, SĐT..."
                                        value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="trang_thai" class="form-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="chưa thanh toán" <?php echo ($trang_thai ?? '') === 'chưa thanh toán' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                        <option value="giữ chỗ" <?php echo ($trang_thai ?? '') === 'giữ chỗ' ? 'selected' : ''; ?>>Giữ chỗ</option>
                                        <option value="đã thanh toán" <?php echo ($trang_thai ?? '') === 'đã thanh toán' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                        <option value="hủy" <?php echo ($trang_thai ?? '') === 'hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="lich_khoi_hanh_id" class="form-select">
                                        <option value="">Tất cả lịch trình</option>
                                        <?php foreach ($lich_khoi_hanh_list as $lkh): ?>
                                            <option value="<?php echo $lkh['id']; ?>"
                                                <?php echo ($lich_khoi_hanh_id ?? '') == $lkh['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($lkh['ten_tour']); ?> - <?php echo date('d/m/Y', strtotime($lkh['ngay_bat_dau'])); ?>
                                                (Còn <?php echo $lkh['so_cho_thuc_te'] ?? $lkh['so_cho_toi_da']; ?> chỗ)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-around">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Tìm Kiếm
                                        </button>
                                        <a href="?act=dat-tour-thong-ke" class="btn btn-success">
                                            <i class="fas fa-chart-bar me-1"></i> Thống Kê
                                        </a>
                                        <a href="?act=dat-tour" class="btn btn-secondary">
                                            <i class="fas fa-refresh me-1"></i> Làm Mới
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách đặt tour -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Danh sách Đặt Tour
                            <span class="badge bg-primary ms-2"><?php echo count($dat_tours); ?></span>
                        </h5>
                        <div class="text-muted small">
                            Hiển thị <?php echo count($dat_tours); ?> kết quả
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($dat_tours)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="datTourTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="120">MÃ ĐẶT TOUR</th>
                                            <th>TOUR & LỊCH TRÌNH</th>
                                            <th width="160">KHÁCH HÀNG</th>
                                            <th width="80" class="text-center">SỐ KHÁCH</th>
                                            <th width="100" class="text-center">NGÀY ĐI</th>
                                            <th width="120" class="text-center">TỔNG TIỀN</th>
                                            <th width="120" class="text-center">TRẠNG THÁI</th>
                                            <th width="150" class="text-center">THAO TÁC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dat_tours as $dat_tour): ?>
                                            <tr>
                                                <td>
                                                    <strong class="text-primary"><?php echo htmlspecialchars($dat_tour['ma_dat_tour']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y', strtotime($dat_tour['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($dat_tour['ten_tour']); ?></div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?php echo date('d/m/Y', strtotime($dat_tour['ngay_bat_dau'])); ?> -
                                                        <?php echo date('d/m/Y', strtotime($dat_tour['ngay_ket_thuc'])); ?>
                                                    </div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <?php echo htmlspecialchars($dat_tour['diem_tap_trung'] ?? 'Chưa cập nhật'); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($dat_tour['ho_ten']); ?></div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($dat_tour['so_dien_thoai']); ?>
                                                    </div>
                                                    <?php if (!empty($dat_tour['email'])): ?>
                                                        <div class="text-muted small">
                                                            <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($dat_tour['email']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary rounded-pill" style="font-size: 14px; padding: 6px 10px;">
                                                        <?php echo $dat_tour['so_khach']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="fw-bold"><?php echo date('d/m/Y', strtotime($dat_tour['ngay_bat_dau'])); ?></div>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?php echo date('H:i', strtotime($dat_tour['gio_tap_trung'] ?? '08:00')); ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="text-success fw-bold">
                                                        <?php echo number_format($dat_tour['tong_tien'], 0, ',', '.'); ?>₫
                                                    </div>
                                                    <div class="text-muted small">
                                                        <?php echo number_format($dat_tour['gia_tour'] ?? 0, 0, ',', '.'); ?>₫/khách
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $status_config = [
                                                        'chưa thanh toán' => ['class' => 'bg-warning', 'icon' => 'clock'],
                                                        'giữ chỗ' => ['class' => 'bg-info', 'icon' => 'money-bill-wave'],
                                                        'đã thanh toán' => ['class' => 'bg-success', 'icon' => 'check-circle'],
                                                        'hủy' => ['class' => 'bg-danger', 'icon' => 'times-circle']
                                                    ];
                                                    $config = $status_config[$dat_tour['trang_thai']] ?? ['class' => 'bg-secondary', 'icon' => 'question'];
                                                    ?>
                                                    <span class="badge <?php echo $config['class']; ?>">
                                                        <i class="fas fa-<?php echo $config['icon']; ?> me-1"></i>
                                                        <?php echo $dat_tour['trang_thai']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="?act=dat-tour-show&id=<?php echo $dat_tour['id']; ?>"
                                                            class="btn btn-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <!-- <?php if ($dat_tour['trang_thai'] == 'chưa thanh toán' || $dat_tour['trang_thai'] == 'giữ chỗ'): ?>
                                                            <a href="?act=dat-tour-edit&id=<?php echo $dat_tour['id']; ?>"
                                                                class="btn btn-warning btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?> -->
                                                        <?php if ($dat_tour['trang_thai'] == 'hủy'): ?>
                                                            <a href="?act=dat-tour-delete&id=<?php echo $dat_tour['id']; ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn xóa đặt tour này?')"
                                                                data-bs-toggle="tooltip"
                                                                title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="?act=dat-tour-print&id=<?php echo $dat_tour['id']; ?>"
                                                            class="btn btn-info btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            title="In hóa đơn">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có đặt tour nào</h5>
                                <p class="text-muted">Chưa có đặt tour nào được tạo</p>
                                <div class="mt-3">
                                    <a href="?act=dat-tour-create" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Đặt Tour Mới
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin phân trang -->
                    <?php if (!empty($dat_tours)): ?>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($dat_tours); ?></strong> trong tổng số <strong><?php echo count($dat_tours); ?></strong> mục
                                </div>
                                <div class="text-muted small">
                                    Tổng doanh thu: <strong class="text-success"><?php 
                                        $tong_doanh_thu = array_sum(array_column($dat_tours, 'tong_tien'));
                                        echo number_format($tong_doanh_thu, 0, ',', '.'); ?>₫
                                    </strong>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'views/layout/footer.php'; ?>

<style>
    .stats-card {
        border-radius: 8px;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stats-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .table th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 0.75rem 0.5rem;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .btn-group .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .stats-card .icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group {
            flex-direction: column;
            gap: 2px;
        }

        .btn-group .btn-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>