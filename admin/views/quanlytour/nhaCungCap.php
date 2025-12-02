<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-handshake me-2"></i>
                        Nhà Cung Cấp: <?php echo htmlspecialchars($tour['ten_tour']); ?>
                    </a>
                    <div>
                        <a href="?act=tour" class="btn btn-outline-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="?act=tour-edit&id=<?php echo $tour['id']; ?>" class="btn btn-info">
                            <i class="fas fa-edit me-1"></i> Sửa tour
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông tin tour -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Mã Tour:</strong> <?php echo htmlspecialchars($tour['ma_tour']); ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Tổng nhà cung cấp:</strong>
                                <span class="badge bg-primary">
                                    <?php echo count($nha_cung_cap_list); ?> NCC
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Đã xác nhận:</strong>
                                <span class="badge bg-success">
                                    <?php 
                                        $confirmed = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['trang_thai_xac_nhan'] == 'đã xác nhận') $confirmed++;
                                        }
                                        echo $confirmed;
                                    ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Chờ xác nhận:</strong>
                                <span class="badge bg-warning">
                                    <?php 
                                        $pending = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['trang_thai_xac_nhan'] == 'chờ xác nhận') $pending++;
                                        }
                                        echo $pending;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form thêm nhà cung cấp -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Thêm nhà cung cấp mới
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addForm">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="addForm">
                        <div class="card-body">
                            <form action="index.php?act=tour-add-nha-cung-cap" method="POST" class="row g-3">
                                <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Lịch khởi hành <span class="text-danger">*</span></label>
                                    <select name="lich_khoi_hanh_id" class="form-select" required>
                                        <option value="">-- Chọn lịch --</option>
                                        <?php foreach ($lich_khoi_hanh_list as $lich): ?>
                                            <option value="<?php echo $lich['id']; ?>">
                                                <?php echo date('d/m/Y', strtotime($lich['ngay_bat_dau'])); ?> -
                                                <?php echo date('d/m/Y', strtotime($lich['ngay_ket_thuc'])); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Loại dịch vụ <span class="text-danger">*</span></label>
                                    <select name="loai_phan_cong" class="form-select" required>
                                        <option value="vận chuyển">🚌 Vận chuyển</option>
                                        <option value="khách sạn">🏨 Khách sạn</option>
                                        <option value="nhà hàng">🍽️ Nhà hàng</option>
                                        <option value="vé tham quan">🎫 Vé tham quan</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Nhà cung cấp <span class="text-danger">*</span></label>
                                    <select name="nha_cung_cap_id" class="form-select" required>
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        <?php foreach ($all_nha_cung_cap as $ncc): ?>
                                            <option value="<?php echo $ncc['id']; ?>">
                                                <?php echo htmlspecialchars($ncc['ten_nha_cung_cap']); ?> 
                                                (<?php echo $ncc['loai_dich_vu']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Tên dịch vụ</label>
                                    <input type="text" name="ten_dich_vu" class="form-control" placeholder="VD: Xe 45 chỗ, Khách sạn 3 sao...">
                                </div>
                                
                                <div class="col-md-9">
                                    <label class="form-label fw-bold">Ghi chú</label>
                                    <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Mô tả chi tiết dịch vụ..."></textarea>
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i> Thêm nhà cung cấp
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Thống kê nhanh -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php 
                                        $count_transport = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['loai_phan_cong'] == 'vận chuyển') $count_transport++;
                                        }
                                        echo $count_transport;
                                    ?>
                                </h3>
                                <small class="text-primary">
                                    <i class="fas fa-truck me-1"></i> Vận chuyển
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php 
                                        $count_hotel = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['loai_phan_cong'] == 'khách sạn') $count_hotel++;
                                        }
                                        echo $count_hotel;
                                    ?>
                                </h3>
                                <small class="text-success">
                                    <i class="fas fa-hotel me-1"></i> Khách sạn
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php 
                                        $count_restaurant = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['loai_phan_cong'] == 'nhà hàng') $count_restaurant++;
                                        }
                                        echo $count_restaurant;
                                    ?>
                                </h3>
                                <small class="text-warning">
                                    <i class="fas fa-utensils me-1"></i> Nhà hàng
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h3 class="mb-0">
                                    <?php 
                                        $count_ticket = 0;
                                        foreach($nha_cung_cap_list as $ncc) {
                                            if($ncc['loai_phan_cong'] == 'vé tham quan') $count_ticket++;
                                        }
                                        echo $count_ticket;
                                    ?>
                                </h3>
                                <small class="text-info">
                                    <i class="fas fa-ticket-alt me-1"></i> Vé tham quan
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách nhà cung cấp -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Danh sách nhà cung cấp
                        </h5>
                        <span class="badge bg-dark"><?php echo count($nha_cung_cap_list); ?> NCC</span>
                    </div>
                    
                    <div class="card-body">
                        <?php if (empty($nha_cung_cap_list)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có nhà cung cấp nào</h5>
                                <p class="text-muted">Hãy thêm nhà cung cấp cho tour này</p>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($nha_cung_cap_list as $ncc): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 border">
                                            <div class="card-header d-flex justify-content-between align-items-center" 
                                                 style="background: <?php 
                                                     switch($ncc['loai_phan_cong']) {
                                                         case 'vận chuyển': echo '#e3f2fd'; break;
                                                         case 'khách sạn': echo '#f3e5f5'; break;
                                                         case 'nhà hàng': echo '#e8f5e9'; break;
                                                         case 'vé tham quan': echo '#fff3e0'; break;
                                                     }
                                                 ?>;">
                                                <div class="d-flex align-items-center">
                                                    <div class="service-type-icon 
                                                        <?php 
                                                            switch($ncc['loai_phan_cong']) {
                                                                case 'vận chuyển': echo 'transport-icon'; break;
                                                                case 'khách sạn': echo 'hotel-icon'; break;
                                                                case 'nhà hàng': echo 'restaurant-icon'; break;
                                                                case 'vé tham quan': echo 'ticket-icon'; break;
                                                            }
                                                        ?>">
                                                        <?php 
                                                            switch($ncc['loai_phan_cong']) {
                                                                case 'vận chuyển': echo '🚌'; break;
                                                                case 'khách sạn': echo '🏨'; break;
                                                                case 'nhà hàng': echo '🍽️'; break;
                                                                case 'vé tham quan': echo '🎫'; break;
                                                            }
                                                        ?>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?php echo ucfirst($ncc['loai_phan_cong']); ?></h6>
                                                        <small class="text-muted"><?php echo !empty($ncc['ten_dich_vu']) ? $ncc['ten_dich_vu'] : 'Không có tên dịch vụ'; ?></small>
                                                    </div>
                                                </div>
                                                <?php if ($ncc['trang_thai_xac_nhan'] === 'đã xác nhận'): ?>
                                                    <span class="badge bg-success">✓ Xác nhận</span>
                                                <?php elseif ($ncc['trang_thai_xac_nhan'] === 'chờ xác nhận'): ?>
                                                    <span class="badge bg-warning">⏳ Chờ</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">✗ Hủy</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="card-body">
                                                <h6 class="card-title fw-bold mb-3">
                                                    <?php echo htmlspecialchars($ncc['ten_nha_cung_cap']); ?>
                                                </h6>
                                                
                                                <div class="contact-item">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    <span><?php echo $ncc['so_dien_thoai'] ?: 'Chưa có số điện thoại'; ?></span>
                                                </div>
                                                <div class="contact-item">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    <span><?php echo $ncc['email'] ?: 'Chưa có email'; ?></span>
                                                </div>
                                                <div class="contact-item">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    <span><?php echo $ncc['dia_chi'] ? substr($ncc['dia_chi'], 0, 50) . '...' : 'Chưa có địa chỉ'; ?></span>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="far fa-calendar me-1"></i>
                                                        Lịch: <?php echo date('d/m/Y', strtotime($ncc['ngay_bat_dau'])); ?> - 
                                                        <?php echo date('d/m/Y', strtotime($ncc['ngay_ket_thuc'])); ?>
                                                    </small>
                                                    
                                                    <?php if (!empty($ncc['ghi_chu'])): ?>
                                                        <div class="alert alert-light mt-2 p-2" style="font-size: 12px;">
                                                            <small><strong>Ghi chú:</strong> <?php echo htmlspecialchars(substr($ncc['ghi_chu'], 0, 100)); ?>
                                                            <?php if(strlen($ncc['ghi_chu']) > 100): ?>...<?php endif; ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="card-footer bg-transparent border-top d-flex justify-content-between">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal<?php echo $ncc['id']; ?>">
                                                    <i class="fas fa-edit me-1"></i> Sửa
                                                </button>
                                                <a href="index.php?act=tour-remove-nha-cung-cap&id=<?php echo $ncc['id']; ?>&tour_id=<?php echo $tour_id; ?>" 
                                                   class="btn btn-sm btn-outline-danger delete-ncc" 
                                                   data-name="<?php echo htmlspecialchars($ncc['ten_nha_cung_cap']); ?>">
                                                    <i class="fas fa-trash me-1"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal chỉnh sửa -->
                                    <div class="modal fade" id="editModal<?php echo $ncc['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit me-2"></i>Chỉnh sửa nhà cung cấp
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="index.php?act=tour-update-nha-cung-cap" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $ncc['id']; ?>">
                                                    <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                                                    
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Loại dịch vụ</label>
                                                                <select name="loai_phan_cong" class="form-select" required>
                                                                    <option value="vận chuyển" <?php echo $ncc['loai_phan_cong'] === 'vận chuyển' ? 'selected' : ''; ?>>🚌 Vận chuyển</option>
                                                                    <option value="khách sạn" <?php echo $ncc['loai_phan_cong'] === 'khách sạn' ? 'selected' : ''; ?>>🏨 Khách sạn</option>
                                                                    <option value="nhà hàng" <?php echo $ncc['loai_phan_cong'] === 'nhà hàng' ? 'selected' : ''; ?>>🍽️ Nhà hàng</option>
                                                                    <option value="vé tham quan" <?php echo $ncc['loai_phan_cong'] === 'vé tham quan' ? 'selected' : ''; ?>>🎫 Vé tham quan</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Trạng thái xác nhận</label>
                                                                <select name="trang_thai_xac_nhan" class="form-select" required>
                                                                    <option value="chờ xác nhận" <?php echo $ncc['trang_thai_xac_nhan'] === 'chờ xác nhận' ? 'selected' : ''; ?>>⏳ Chờ xác nhận</option>
                                                                    <option value="đã xác nhận" <?php echo $ncc['trang_thai_xac_nhan'] === 'đã xác nhận' ? 'selected' : ''; ?>>✓ Đã xác nhận</option>
                                                                    <option value="đã hủy" <?php echo $ncc['trang_thai_xac_nhan'] === 'đã hủy' ? 'selected' : ''; ?>>✗ Đã hủy</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-bold">Tên dịch vụ</label>
                                                                <input type="text" name="ten_dich_vu" class="form-control" 
                                                                       value="<?php echo htmlspecialchars($ncc['ten_dich_vu'] ?? ''); ?>"
                                                                       placeholder="Nhập tên dịch vụ cụ thể...">
                                                            </div>
                                                            
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-bold">Ghi chú</label>
                                                                <textarea name="ghi_chu" class="form-control" rows="4" 
                                                                          placeholder="Nhập ghi chú chi tiết về dịch vụ..."><?php echo htmlspecialchars($ncc['ghi_chu'] ?? ''); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Đóng
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save me-1"></i> Lưu thay đổi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tóm tắt theo loại dịch vụ -->
                <?php if (!empty($nha_cung_cap_list)): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Phân loại nhà cung cấp</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $services = [
                                    'vận chuyển' => ['icon' => '🚌', 'color' => 'primary'],
                                    'khách sạn' => ['icon' => '🏨', 'color' => 'success'],
                                    'nhà hàng' => ['icon' => '🍽️', 'color' => 'warning'],
                                    'vé tham quan' => ['icon' => '🎫', 'color' => 'info']
                                ];
                                
                                foreach($services as $service => $info):
                                    $count = 0;
                                    foreach($nha_cung_cap_list as $ncc) {
                                        if($ncc['loai_phan_cong'] == $service) $count++;
                                    }
                                    if($count > 0):
                                ?>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="card border-<?php echo $info['color']; ?>">
                                            <div class="card-body text-center">
                                                <h2 class="mb-2"><?php echo $info['icon']; ?></h2>
                                                <h4 class="mb-0"><?php echo $count; ?></h4>
                                                <small class="text-<?php echo $info['color']; ?>">
                                                    <?php echo ucfirst($service); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);

    // Xác nhận xóa nhà cung cấp
    $('.delete-ncc').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');
        var name = $(this).data('name');
        
        if (confirm('Bạn có chắc muốn xóa nhà cung cấp "' + name + '" khỏi tour này?')) {
            window.location.href = deleteUrl;
        }
    });
});
</script>

<style>
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border-left-color: #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-left-color: #dc3545;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 13px;
}

.service-type-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 20px;
}

.transport-icon { background: #e3f2fd; color: #1976d2; }
.hotel-icon { background: #f3e5f5; color: #7b1fa2; }
.restaurant-icon { background: #e8f5e9; color: #388e3c; }
.ticket-icon { background: #fff3e0; color: #f57c00; }

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>