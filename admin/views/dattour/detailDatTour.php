<?php
// views/dattour/detailDatTour.php

// KIỂM TRA VÀ ĐỒNG BỘ TÊN BIẾN
if (isset($dat_tour) && !isset($thong_tin_dat_tour)) {
    $thong_tin_dat_tour = $dat_tour;
}

if (!isset($thong_tin_dat_tour)) {
    die("Lỗi: Không tìm thấy thông tin đặt tour");
}
?>

<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>
                        CHI TIẾT ĐẶT TOUR: <?php echo $thong_tin_dat_tour['ma_dat_tour']; ?>
                    </h4>
                </div>
            </div>

            <!-- Thông tin chính -->
            <div class="row">
                <!-- Thông tin tour -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Tour</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($thong_tin_dat_tour['ten_tour'] ?? 'N/A'); ?></p>
                                    <p><strong>Mã tour:</strong> <?php echo $thong_tin_dat_tour['ma_tour'] ?? 'N/A'; ?></p>
                                    <p><strong>Ngày khởi hành:</strong> <?php echo date('d/m/Y', strtotime($thong_tin_dat_tour['ngay_bat_dau'] ?? '')); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Ngày kết thúc:</strong> <?php echo date('d/m/Y', strtotime($thong_tin_dat_tour['ngay_ket_thuc'] ?? '')); ?></p>
                                    <p><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($thong_tin_dat_tour['diem_tap_trung'] ?? 'N/A'); ?></p>
                                    <p><strong>Giờ tập trung:</strong> <?php echo $thong_tin_dat_tour['gio_tap_trung'] ?? 'N/A'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khách hàng -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông Tin Khách Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($thong_tin_dat_tour['ho_ten'] ?? 'N/A'); ?></p>
                                    <p><strong>Số điện thoại:</strong> <?php echo $thong_tin_dat_tour['so_dien_thoai'] ?? 'N/A'; ?></p>
                                    <p><strong>Email:</strong> <?php echo $thong_tin_dat_tour['email'] ?? 'N/A'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>CCCD:</strong> <?php echo $thong_tin_dat_tour['cccd'] ?? 'N/A'; ?></p>
                                    <p><strong>Ngày sinh:</strong> <?php echo $thong_tin_dat_tour['ngay_sinh'] ? date('d/m/Y', strtotime($thong_tin_dat_tour['ngay_sinh'])) : 'N/A'; ?></p>
                                    <p><strong>Giới tính:</strong> <?php echo $thong_tin_dat_tour['gioi_tinh'] ?? 'N/A'; ?></p>
                                </div>
                            </div>
                            <?php if (!empty($thong_tin_dat_tour['dia_chi'])): ?>
                                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($thong_tin_dat_tour['dia_chi']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Thông tin thanh toán & trạng thái -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thông Tin Thanh Toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h3 class="text-primary"><?php echo number_format($thong_tin_dat_tour['tong_tien'] ?? 0); ?>₫</h3>
                                <small class="text-muted">Tổng tiền</small>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Trạng thái:</strong>
                                <?php
                                // SỬA: Dùng đúng giá trị ENUM theo database
                                $trang_thai_class = [
                                    'chờ xác nhận' => 'warning',
                                    'đã cọc' => 'info', 
                                    'hoàn tất' => 'success',
                                    'hủy' => 'danger'
                                ];
                                $trang_thai = $thong_tin_dat_tour['trang_thai'] ?? 'chờ xác nhận';
                                $class = $trang_thai_class[$trang_thai] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $class; ?> float-end">
                                    <?php echo $trang_thai; ?>
                                </span>
                            </div>

                            <div class="mb-3">
                                <strong>Loại khách:</strong>
                                <span class="float-end">
                                    <?php echo ($thong_tin_dat_tour['loai_khach'] ?? 'le') === 'doan' ? 'Đoàn' : 'Lẻ'; ?>
                                </span>
                            </div>

                            <?php if (!empty($thong_tin_dat_tour['ten_doan'])): ?>
                                <div class="mb-3">
                                    <strong>Tên đoàn:</strong>
                                    <span class="float-end"><?php echo htmlspecialchars($thong_tin_dat_tour['ten_doan']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($thong_tin_dat_tour['loai_doan'])): ?>
                                <div class="mb-3">
                                    <strong>Loại đoàn:</strong>
                                    <span class="float-end"><?php echo htmlspecialchars($thong_tin_dat_tour['loai_doan']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Các nút thao tác -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Thao Tác</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="?act=dat-tour" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay Lại Danh Sách
                                </a>
                                
                                <a href="?act=dat-tour-print&id=<?php echo $thong_tin_dat_tour['id']; ?>" class="btn btn-primary" target="_blank">
                                    <i class="fas fa-print me-1"></i> In
                                </a>
                                
                                <?php 
                            
                                if ($thong_tin_dat_tour['trang_thai'] != 'hoàn tất'): ?>
                                    <button type="button" class="btn btn-success" onclick="openThanhToanModal(<?php echo $thong_tin_dat_tour['id']; ?>)">
                                        <i class="fas fa-credit-card me-1"></i> Thanh Toán
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check me-1"></i> Đã Thanh Toán
                                    </button>
                                <?php endif; ?>
                                
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách thành viên -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Danh Sách Thành Viên (<?php echo count($thanh_vien_list ?? []); ?> người)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($thanh_vien_list)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ Tên</th>
                                        <th>CCCD</th>
                                        <th>Ngày Sinh</th>
                                        <th>Giới Tính</th>
                                        <th>Yêu Cầu Đặc Biệt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($thanh_vien_list as $index => $thanh_vien): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($thanh_vien['ho_ten']); ?></td>
                                            <td><?php echo $thanh_vien['cccd'] ?? 'N/A'; ?></td>
                                            <td><?php echo $thanh_vien['ngay_sinh'] ? date('d/m/Y', strtotime($thanh_vien['ngay_sinh'])) : 'N/A'; ?></td>
                                            <td><?php echo $thanh_vien['gioi_tinh'] ?? 'N/A'; ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($thanh_vien['yeu_cau_dac_biet'])) {
                                                    $yeu_cau = json_decode($thanh_vien['yeu_cau_dac_biet'], true);
                                                    echo htmlspecialchars($yeu_cau['yeu_cau'] ?? '');
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Không có thành viên nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Container cho Thanh toán -->
<div id="modalContainer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openThanhToanModal(phieuDatId) {
    console.log('Opening payment modal for:', phieuDatId);
    
    // Load modal content
    $('#modalContainer').load('index.php?act=thanh-toan-nhanh-modal&id=' + phieuDatId, function(response, status, xhr) {
        if (status === "error") {
            console.error('Error loading modal:', xhr.status, xhr.statusText);
            alert('Lỗi khi tải form thanh toán');
            return;
        }
        $('#thanhToanModal').modal('show');
    });
}

function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa đặt tour này?')) {
        window.location.href = 'index.php?act=dat-tour-delete&id=' + id;
    }
}
</script>

<?php include './views/layout/footer.php'; ?>