<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark">Danh Sách Hành Khách</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="?act=xem_danh_sach_khach">Chọn Tour</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="fas fa-bus me-2"></i><?= htmlspecialchars($tourInfo['ten_tour'] ?? 'Thông tin tour') ?></h5>
                    <small class="opacity-75"><i class="far fa-clock me-1"></i> Ngày đi: <?= $tourInfo['ngay_di'] ?? 'N/A' ?></small>
                </div>
                <a href="?act=xem_danh_sach_khach" class="btn btn-sm btn-light text-primary fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Đổi Tour
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="px-4 py-3">STT</th>
                                <th class="px-4 py-3">Họ và Tên</th>
                                <th class="px-4 py-3">Thông tin</th>
                                <th class="px-4 py-3">Nhóm / Mã Vé</th>
                                <th class="px-4 py-3">Liên hệ</th>
                                <th class="px-4 py-3">Ghi chú</th>
                                <th class="px-4 py-3 text-center" style="width: 160px;">Trạng thái Check-in</th> </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dsKhach)): ?>
                                <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-user-slash fa-2x mb-3"></i><br>Chưa có khách trong danh sách.</td></tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($dsKhach as $k): ?>
                                    <tr>
                                        <td class="px-4"><?= $i++ ?></td>
                                        <td class="px-4">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($k['ho_ten']) ?></div>
                                        </td>
                                        
                                        <td class="px-4">
                                            <span class="badge bg-light text-dark border"><?= $k['gioi_tinh'] ?></span>
                                            <?php if($k['tuoi']): ?>
                                                <span class="ms-1 text-muted small"><?= $k['tuoi'] ?> tuổi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4">
                                            <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                                <?= htmlspecialchars($k['nhom']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4">
                                            <a href="tel:<?= $k['sdt'] ?>" class="text-decoration-none fw-bold text-primary">
                                                <i class="fas fa-phone-alt me-1"></i><?= $k['sdt'] ?>
                                            </a>
                                            <div class="small text-muted fst-italic mt-1">
                                                Người đặt: <?= htmlspecialchars($k['nguoi_dat']) ?>
                                            </div>
                                        </td>
                                        <td class="px-4">
                                            <?php if ($k['ghi_chu']): ?>
                                                <div class="text-danger small fw-bold bg-danger bg-opacity-10 p-2 rounded">
                                                    <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($k['ghi_chu']) ?>
                                                </div>
                                            <?php else: ?> 
                                                <span class="text-muted">-</span> 
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-4 text-center">
                                            <select class="form-select form-select-sm status-select fw-bold border-0 shadow-sm" 
                                                    data-id="<?= $k['id'] ?>"
                                                    style="cursor: pointer;
                                                    background-color: 
                                                        <?= $k['trang_thai_checkin'] == 'đã đến' ? '#d1e7dd' : 
                                                           ($k['trang_thai_checkin'] == 'vắng mặt' ? '#f8d7da' : 
                                                           ($k['trang_thai_checkin'] == 'trễ' ? '#fff3cd' : '#f8f9fa')) ?>;
                                                    color: 
                                                        <?= $k['trang_thai_checkin'] == 'đã đến' ? '#0f5132' : 
                                                           ($k['trang_thai_checkin'] == 'vắng mặt' ? '#842029' : 
                                                           ($k['trang_thai_checkin'] == 'trễ' ? '#664d03' : '#212529')) ?>;">
                                                
                                                <option value="chưa đến" <?= $k['trang_thai_checkin'] == 'chưa đến' ? 'selected' : '' ?>>⚪ Chưa đến</option>
                                                <option value="đã đến" <?= $k['trang_thai_checkin'] == 'đã đến' ? 'selected' : '' ?>>🟢 Đã đến</option>
                                                <option value="trễ" <?= $k['trang_thai_checkin'] == 'trễ' ? 'selected' : '' ?>>🟡 Đến trễ</option>
                                                <option value="vắng mặt" <?= $k['trang_thai_checkin'] == 'vắng mặt' ? 'selected' : '' ?>>🔴 Vắng mặt</option>
                                            </select>
                                        </td>
                                        </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted small">
                <i class="fas fa-info-circle me-1"></i> Dữ liệu check-in sẽ được tự động lưu ngay khi bạn chọn.
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.status-select').change(function() {
        var status = $(this).val();
        var id = $(this).data('id');
        var element = $(this);

        // Hiệu ứng đổi màu UI
        if(status == 'đã đến') { 
            element.css({'background-color': '#d1e7dd', 'color': '#0f5132'});
        } else if(status == 'trễ') { 
            element.css({'background-color': '#fff3cd', 'color': '#664d03'});
        } else if(status == 'vắng mặt') { 
            element.css({'background-color': '#f8d7da', 'color': '#842029'});
        } else { // chưa đến
            element.css({'background-color': '#f8f9fa', 'color': '#212529'});
        }

        // Gửi Ajax cập nhật
        $.ajax({
            url: '?act=check_in_khach',
            type: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(response) {
                console.log('Đã cập nhật check-in');
            },
            error: function() {
                alert('Lỗi kết nối! Vui lòng thử lại.');
            }
        });
    });
});
</script>

<?php include './views/layout/footer.php'; ?>