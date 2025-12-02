<?php
// File: views/quanlytour/phienban/list.php
require './views/layout/header.php';
include './views/layout/navbar.php';
include './views/layout/sidebar.php';

if (!isset($tour_info) || !$tour_info) {
    echo '<div class="alert alert-danger">Tour không tồn tại!</div>';
    require './views/layout/footer.php';
    exit();
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-code-branch me-2"></i>
                                Phiên bản tour: <?php echo htmlspecialchars($tour_info['ten_tour']); ?>
                            </h3>
                            <small class="text-muted">
                                Mã tour: <?php echo $tour_info['ma_tour']; ?> | 
                                Giá hiện tại: <strong><?php echo number_format($tour_info['gia_tour'] ?? 0, 0, ',', '.'); ?> đ</strong>
                            </small>
                        </div>
                        <div>
                            <a href="?act=phien-ban-create&tour_id=<?php echo $tour_id; ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Thêm phiên bản
                            </a>
                            <a href="?act=tour" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Thống kê nhanh -->
            <?php if (!empty($phien_ban_list)): 
                $stats = [
                    'mùa' => 0,
                    'khuyến mãi' => 0,
                    'đặc biệt' => 0,
                    'active' => 0
                ];
                
                foreach ($phien_ban_list as $pb) {
                    $stats[$pb['loai_phien_ban']]++;
                    
                    // Kiểm tra phiên bản đang active
                    $is_active = true;
                    if ($pb['thoi_gian_bat_dau'] && strtotime($pb['thoi_gian_bat_dau']) > time()) {
                        $is_active = false;
                    }
                    if ($pb['thoi_gian_ket_thuc'] && strtotime($pb['thoi_gian_ket_thuc']) < time()) {
                        $is_active = false;
                    }
                    if ($is_active) $stats['active']++;
                }
            ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="h4 mb-1"><?php echo count($phien_ban_list); ?></div>
                                    <small class="text-muted">Tổng phiên bản</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="h4 mb-1 text-success"><?php echo $stats['active']; ?></div>
                                    <small class="text-muted">Đang áp dụng</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="h4 mb-1 text-info"><?php echo $stats['mùa']; ?></div>
                                    <small class="text-muted">Mùa</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="h4 mb-1 text-warning"><?php echo $stats['khuyến mãi']; ?></div>
                                    <small class="text-muted">Khuyến mãi</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="h4 mb-1 text-danger"><?php echo $stats['đặc biệt']; ?></div>
                                    <small class="text-muted">Đặc biệt</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Danh sách phiên bản -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Danh sách phiên bản
                    </h5>
                </div>
                
                <div class="card-body">
                    <?php if (empty($phien_ban_list)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-code-branch fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có phiên bản nào</h5>
                            <p class="text-muted mb-4">Thêm phiên bản đầu tiên cho tour này</p>
                            <a href="?act=phien-ban-create&tour_id=<?php echo $tour_id; ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Thêm phiên bản đầu tiên
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Tên phiên bản</th>
                                        <th width="120">Loại</th>
                                        <th width="150">Giá</th>
                                        <th width="150">Thời gian</th>
                                        <th width="100">Trạng thái</th>
                                        <th width="180">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($phien_ban_list as $pb): 
                                        // Kiểm tra trạng thái
                                        $is_active = true;
                                        $status_class = 'success';
                                        $status_text = 'Đang áp dụng';
                                        
                                        if ($pb['thoi_gian_bat_dau'] && strtotime($pb['thoi_gian_bat_dau']) > time()) {
                                            $is_active = false;
                                            $status_class = 'info';
                                            $status_text = 'Sắp diễn ra';
                                        }
                                        if ($pb['thoi_gian_ket_thuc'] && strtotime($pb['thoi_gian_ket_thuc']) < time()) {
                                            $is_active = false;
                                            $status_class = 'secondary';
                                            $status_text = 'Đã kết thúc';
                                        }
                                        
                                        // Badge class theo loại
                                        $badge_class = [
                                            'mùa' => 'bg-info',
                                            'khuyến mãi' => 'bg-success',
                                            'đặc biệt' => 'bg-warning'
                                        ];
                                    ?>
                                        <tr>
                                            <td><?php echo $pb['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($pb['ten_phien_ban']); ?></strong>
                                                <?php if (!empty($pb['mo_ta'])): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars(substr($pb['mo_ta'], 0, 50)); ?>...</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $badge_class[$pb['loai_phien_ban']] ?? 'bg-secondary'; ?>">
                                                    <?php echo ucfirst($pb['loai_phien_ban']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success"><?php echo number_format($pb['gia_tour'], 0, ',', '.'); ?> đ</strong>
                                            </td>
                                            <td>
                                                <?php if ($pb['thoi_gian_bat_dau']): ?>
                                                    <small class="d-block">Từ: <?php echo date('d/m/Y', strtotime($pb['thoi_gian_bat_dau'])); ?></small>
                                                <?php endif; ?>
                                                <?php if ($pb['thoi_gian_ket_thuc']): ?>
                                                    <small class="d-block">Đến: <?php echo date('d/m/Y', strtotime($pb['thoi_gian_ket_thuc'])); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?act=phien-ban-detail&id=<?php echo $pb['id']; ?>" 
                                                       class="btn btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="?act=phien-ban-edit&id=<?php echo $pb['id']; ?>" 
                                                       class="btn btn-outline-primary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($is_active && $pb['gia_tour'] != $tour_info['gia_tour']): ?>
                                                        <a href="?act=phien-ban-apply&id=<?php echo $pb['id']; ?>&tour_id=<?php echo $tour_id; ?>" 
                                                           class="btn btn-outline-success" title="Áp dụng phiên bản">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="?act=phien-ban-delete&id=<?php echo $pb['id']; ?>&tour_id=<?php echo $tour_id; ?>" 
                                                       class="btn btn-outline-danger delete-phien-ban" 
                                                       data-name="<?php echo htmlspecialchars($pb['ten_phien_ban']); ?>"
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
    </section>
</div>

<script>
$(document).ready(function() {
    // Xác nhận xóa phiên bản
    $('.delete-phien-ban').click(function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');
        var name = $(this).data('name');
        
        if (confirm('Bạn có chắc muốn xóa phiên bản "' + name + '"?')) {
            window.location.href = deleteUrl;
        }
    });
    
    // Xác nhận áp dụng phiên bản
    $('.btn-outline-success').click(function(e) {
        var applyUrl = $(this).attr('href');
        if (!confirm('Áp dụng phiên bản này sẽ cập nhật giá tour hiện tại. Tiếp tục?')) {
            e.preventDefault();
        }
    });
});
</script>

<?php include './views/layout/footer.php'; ?>