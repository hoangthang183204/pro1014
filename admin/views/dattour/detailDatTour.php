<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-eye mr-2"></i>
                                Chi Tiết Đặt Tour
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="?act=dat-tour">Đặt Tour</a></li>
                                <li class="breadcrumb-item active">Chi Tiết</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Thông báo -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo htmlspecialchars($_SESSION['success']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo htmlspecialchars($_SESSION['error']); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Cột trái - Thông tin chính -->
                        <div class="col-lg-8">
                            <!-- Thông tin đặt tour -->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-ticket-alt mr-2"></i>
                                        Thông Tin Đặt Tour
                                    </h3>
                                    <div class="card-tools">
                                        <span class="badge badge-lg <?php echo getStatusBadgeClass($dat_tour['trang_thai']); ?>">
                                            <?php echo $dat_tour['trang_thai']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Mã Đặt Tour:</strong></td>
                                                    <td>
                                                        <span class="badge badge-primary badge-lg">
                                                            <?php echo htmlspecialchars($dat_tour['ma_dat_tour']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tour:</strong></td>
                                                    <td>
                                                        <div class="font-weight-bold"><?php echo htmlspecialchars($dat_tour['ten_tour']); ?></div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($dat_tour['ma_tour']); ?></small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngày Khởi Hành:</strong></td>
                                                    <td>
                                                        <i class="far fa-calendar-alt text-primary mr-1"></i>
                                                        <?php echo date('d/m/Y', strtotime($dat_tour['ngay_bat_dau'])); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngày Kết Thúc:</strong></td>
                                                    <td>
                                                        <i class="far fa-calendar-alt text-primary mr-1"></i>
                                                        <?php echo date('d/m/Y', strtotime($dat_tour['ngay_ket_thuc'])); ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Giờ Tập Trung:</strong></td>
                                                    <td>
                                                        <i class="far fa-clock text-primary mr-1"></i>
                                                        <?php echo date('H:i', strtotime($dat_tour['gio_tap_trung'] ?? '08:00')); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Điểm Tập Trung:</strong></td>
                                                    <td><?php echo htmlspecialchars($dat_tour['diem_tap_trung'] ?? 'Chưa cập nhật'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Giá Tour:</strong></td>
                                                    <td>
                                                        <strong class="text-success">
                                                            <?php echo number_format($dat_tour['gia_tour'], 0, ',', '.'); ?> VNĐ/người
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Loại Khách:</strong></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo $dat_tour['loai_khach'] === 'doan' ? 'info' : 'secondary'; ?>">
                                                            <?php echo $dat_tour['loai_khach'] === 'doan' ? 'Khách đoàn' : 'Khách lẻ'; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <?php if ($dat_tour['loai_khach'] === 'doan' && !empty($dat_tour['ten_doan'])): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-info py-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-building mr-2"></i>
                                                        <div>
                                                            <strong class="mr-2">Thông Tin:</strong>
                                                            <?php echo htmlspecialchars($dat_tour['ten_doan']); ?>
                                                            <?php if (!empty($dat_tour['loai_doan'])): ?>
                                                                - <strong>Loại đoàn:</strong> <?php echo htmlspecialchars($dat_tour['loai_doan']); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Danh sách thành viên -->
                            <div class="card card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-users mr-2"></i>
                                        Danh Sách Thành Viên
                                        <span class="badge badge-primary ml-1"><?php echo count($thanh_vien_list); ?></span>
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <?php if (!empty($thanh_vien_list)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th width="50" class="text-center">STT</th>
                                                        <th>Họ Tên</th>
                                                        <th width="150" class="text-center">CCCD/CMND</th>
                                                        <th width="120" class="text-center">Ngày Sinh</th>
                                                        <th width="100" class="text-center">Giới Tính</th>
                                                        <th>Yêu Cầu Đặc Biệt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($thanh_vien_list as $index => $thanh_vien): ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $index + 1; ?></td>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($thanh_vien['ho_ten']); ?></strong>
                                                                <?php if ($index === 0): ?>
                                                                    <span class="badge badge-success ml-1">Chủ tour</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center"><?php echo htmlspecialchars($thanh_vien['cccd'] ?? '---'); ?></td>
                                                            <td class="text-center">
                                                                <?php echo $thanh_vien['ngay_sinh'] ? date('d/m/Y', strtotime($thanh_vien['ngay_sinh'])) : '---'; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-secondary">
                                                                    <?php 
                                                                    $gioi_tinh_text = [
                                                                        'nam' => 'Nam',
                                                                        'nữ' => 'Nữ', 
                                                                        'khác' => 'Khác'
                                                                    ];
                                                                    echo $gioi_tinh_text[$thanh_vien['gioi_tinh']] ?? '---';
                                                                    ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $yeu_cau = json_decode($thanh_vien['yeu_cau_dac_biet'] ?? '[]', true);
                                                                if (!empty($yeu_cau['yeu_cau'])): 
                                                                ?>
                                                                    <div class="d-flex flex-wrap gap-1">
                                                                        <span class="badge badge-warning"><?php echo htmlspecialchars($yeu_cau['yeu_cau']); ?></span>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted">---</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Không có thành viên nào</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Cột phải - Thông tin phụ -->
                        <div class="col-lg-4">
                            <!-- Thông tin khách hàng -->
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user mr-2"></i>
                                        Thông Tin Khách Hàng
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 70px; height: 70px;">
                                            <i class="fas fa-user text-white fa-2x"></i>
                                        </div>
                                    </div>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Họ Tên:</strong></td>
                                            <td><?php echo htmlspecialchars($dat_tour['ho_ten']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số Điện Thoại:</strong></td>
                                            <td>
                                                <i class="fas fa-phone text-primary mr-1"></i>
                                                <?php echo htmlspecialchars($dat_tour['so_dien_thoai']); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>
                                                <?php if (!empty($dat_tour['email'])): ?>
                                                    <i class="fas fa-envelope text-primary mr-1"></i>
                                                    <?php echo htmlspecialchars($dat_tour['email']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">---</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Địa Chỉ:</strong></td>
                                            <td>
                                                <?php if (!empty($dat_tour['dia_chi'])): ?>
                                                    <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                                                    <?php echo htmlspecialchars($dat_tour['dia_chi']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">---</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Thông tin thanh toán -->
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                        Thông Tin Thanh Toán
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Số Lượng:</strong></td>
                                            <td class="text-right">
                                                <span class="badge badge-primary">
                                                    <?php echo $dat_tour['so_luong_khach']; ?> người
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đơn Giá:</strong></td>
                                            <td class="text-right"><?php echo number_format($dat_tour['gia_tour'], 0, ',', '.'); ?> VNĐ</td>
                                        </tr>
                                        <?php if (isset($dat_tour['giam_gia']) && $dat_tour['giam_gia'] > 0): ?>
                                            <tr>
                                                <td><strong>Giảm Giá:</strong></td>
                                                <td class="text-right text-danger">-<?php echo number_format($dat_tour['giam_gia'], 0, ',', '.'); ?> VNĐ</td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr class="border-top">
                                            <td><strong>Tổng Tiền:</strong></td>
                                            <td class="text-right">
                                                <h4 class="text-success font-weight-bold mb-0">
                                                    <?php echo number_format($dat_tour['tong_tien'], 0, ',', '.'); ?> VNĐ
                                                </h4>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Quản lý trạng thái -->
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cog mr-2"></i>
                                        Quản Lý Trạng Thái
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="?act=dat-tour-update-status">
                                        <input type="hidden" name="id" value="<?php echo $dat_tour['id']; ?>">
                                        <div class="form-group">
                                            <label class="form-label"><strong>Trạng Thái Hiện Tại:</strong></label>
                                            <div class="text-center mb-3">
                                                <span class="badge badge-lg <?php echo getStatusBadgeClass($dat_tour['trang_thai']); ?> p-2">
                                                    <?php echo $dat_tour['trang_thai']; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"><strong>Cập Nhật Trạng Thái:</strong></label>
                                            <select name="trang_thai" class="form-control select2" style="width: 100%;">
                                                <option value="chờ xác nhận" <?php echo $dat_tour['trang_thai'] === 'chờ xác nhận' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                                <option value="đã cọc" <?php echo $dat_tour['trang_thai'] === 'đã cọc' ? 'selected' : ''; ?>>Đã cọc</option>
                                                <option value="hoàn tất" <?php echo $dat_tour['trang_thai'] === 'hoàn tất' ? 'selected' : ''; ?>>Hoàn tất</option>
                                                <option value="huỷ" <?php echo $dat_tour['trang_thai'] === 'hủy' ? 'selected' : ''; ?>>Huỷ</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <i class="fas fa-sync mr-1"></i> Cập Nhật Trạng Thái
                                        </button>
                                    </form>

                                    <div class="mt-3 pt-3 border-top">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <strong>Người tạo:</strong> <?php echo htmlspecialchars($dat_tour['nguoi_tao_ten'] ?? 'System'); ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($dat_tour['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <?php if (!empty($dat_tour['ghi_chu'])): ?>
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-sticky-note mr-2"></i>
                                            Ghi Chú
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-justify"><?php echo nl2br(htmlspecialchars($dat_tour['ghi_chu'])); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Action buttons -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="?act=dat-tour" class="btn btn-default btn-block mb-2">
                                        <i class="fas fa-arrow-left mr-1"></i> Quay Lại Danh Sách
                                    </a>
                                    <div class="btn-group w-100">
                                        <a href="?act=dat-tour-print&id=<?php echo $dat_tour['id']; ?>" class="btn btn-info" target="_blank">
                                            <i class="fas fa-print mr-1"></i> In
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
function confirmDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa đơn đặt tour này?')) {
        window.location.href = '?act=dat-tour-delete&id=<?php echo $dat_tour['id']; ?>';
    }
}

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4'
    });
});
</script>

<style>
.badge-lg {
    font-size: 14px;
    padding: 8px 12px;
}

.table-borderless td {
    border: none !important;
    padding: 8px 0;
}

.card-outline {
    border-top: 3px solid;
}

.card-outline.card-primary {
    border-top-color: #007bff;
}

.card-outline.card-info {
    border-top-color: #17a2b8;
}

.card-outline.card-success {
    border-top-color: #28a745;
}

.card-outline.card-warning {
    border-top-color: #ffc107;
}

.card-outline.card-secondary {
    border-top-color: #6c757d;
}

.gap-1 {
    gap: 0.25rem;
}
</style>

<?php
// Helper function for status badge classes
function getStatusBadgeClass($status) {
    $classes = [
        'chờ xác nhận' => 'badge-warning',
        'đã cọc' => 'badge-info',
        'hoàn tất' => 'badge-success',
        'hủy' => 'badge-danger'
    ];
    return $classes[$status] ?? 'badge-secondary';
}
?>