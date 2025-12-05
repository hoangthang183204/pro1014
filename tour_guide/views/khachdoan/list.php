<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark">Danh Sách Hành Khách</h2>
            </div>

        </div>

        <div class="card mb-3 border-primary shadow-sm">
            <div class="card-body bg-light">
                <form method="GET" action="" class="row align-items-center">
                    <input type="hidden" name="act" value="xem_danh_sach_khach">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

                    <div class="col-md-5">
                        <label class="fw-bold mb-1 text-primary"><i class="fas fa-map-marker-alt me-1"></i> Chọn Trạm Điểm Danh:</label>
                        <select name="tram_id" class="form-select border-primary fw-bold" onchange="this.form.submit()">
                            <?php if (empty($dsTram)): ?>
                                <option value="0">Đang tạo trạm...</option>
                            <?php else: ?>
                                <?php foreach ($dsTram as $tram): ?>
                                    <option value="<?= $tram['id'] ?>" <?= $selected_tram_id == $tram['id'] ? 'selected' : '' ?>>
                                        Trạm <?= $tram['thu_tu'] ?>: <?= htmlspecialchars($tram['ten_tram']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4 text-center mt-3 mt-md-0">
                        <h5 class="mb-1">Tiến độ: <span class="text-success fw-bold"><?= $soLuongCoMat ?></span> / <?= $totalKhach ?> khách</h5>

                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-<?= $isDuNguoi ? 'success' : 'warning' ?> progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                style="width: <?= ($totalKhach > 0) ? ($tienDoCheckIn / $totalKhach) * 100 : 0 ?>%">
                                <?= ($totalKhach > 0) ? round(($tienDoCheckIn / $totalKhach) * 100) : 0 ?>%
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mt-3 mt-md-0 text-end">
                        <?php
                        // --- LOGIC TÌM TRẠM KẾ TIẾP ---
                        $next_tram_id = null;
                        foreach ($dsTram as $key => $tram) {
                            // Tìm thấy trạm hiện tại trong danh sách
                            if ($tram['id'] == $selected_tram_id) {
                                // Kiểm tra xem có trạm phía sau không (dựa vào index mảng)
                                if (isset($dsTram[$key + 1])) {
                                    $next_tram_id = $dsTram[$key + 1]['id'];
                                }
                                break;
                            }
                        }
                        ?>

                        <?php if ($isDuNguoi): ?>
                            <?php if ($next_tram_id): ?>
                                <a href="?act=xem_danh_sach_khach&id=<?= $_GET['id'] ?>&tram_id=<?= $next_tram_id ?>"
                                    class="btn btn-success w-100 fw-bold py-2 shadow">
                                    <i class="fas fa-arrow-right me-2"></i> ĐỦ NGƯỜI - ĐI TIẾP
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary w-100 fw-bold py-2 shadow" disabled>
                                    <i class="fas fa-flag-checkered me-2"></i> HOÀN THÀNH ĐIỂM DANH
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100 fw-bold py-2" disabled>
                                <i class="fas fa-user-clock me-2"></i> Đang chờ đủ khách...
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="fas fa-bus me-2"></i><?= htmlspecialchars($tourInfo['ten_tour']) ?></h5>
                    <small class="opacity-75">Ngày đi: <?= $tourInfo['ngay_di'] ?></small>
                </div>
            </div>

            <div class="p-3 bg-white border-bottom d-flex align-items-center gap-2">
    <span class="fw-bold text-muted"><i class="fas fa-tasks me-1"></i> Thao tác nhanh:</span>
    <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm action-bulk" data-status="đã đến">
        <i class="fas fa-check-circle me-1"></i> Đã đến (Chọn)
    </button>
    <button type="button" class="btn btn-danger btn-sm fw-bold shadow-sm action-bulk" data-status="vắng mặt">
        <i class="fas fa-user-times me-1"></i> Vắng mặt (Chọn)
    </button>
     <button type="button" class="btn btn-light btn-sm border fw-bold shadow-sm action-bulk" data-status="chưa đến">
        <i class="fas fa-undo me-1"></i> Reset (Chọn)
    </button>
</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="bg-light text-secondary">
                            <tr>
                            <th class="px-3" style="width: 40px;">
                <input type="checkbox" id="checkAll" class="form-check-input" style="cursor: pointer;">
            </th>
                                <th class="px-3">STT</th>
                                <th class="px-3">Họ và Tên</th>
                                <th class="px-3">Thông tin</th>
                                <th class="px-3">Liên hệ</th>
                                <th class="px-3">Ghi chú</th>
                                <th class="px-3 text-center" style="width: 170px;">Trạng thái (Tại trạm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dsKhach)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Chưa có khách trong danh sách.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1;
                                foreach ($dsKhach as $k):
                                    $is_canceled = isset($k['da_huy_truoc_do']) && $k['da_huy_truoc_do'] > 0;
                                    $row_class = $is_canceled ? 'table-secondary opacity-75' : '';
                                ?>
                                    <tr class="<?= $row_class ?>">
                                    <td class="px-3">
            <?php if (!$is_canceled): // Chỉ hiện checkbox nếu khách chưa bị hủy ?>
                <input type="checkbox" class="form-check-input check-item" value="<?= $k['id'] ?>" style="cursor: pointer;">
            <?php else: ?>
                <input type="checkbox" class="form-check-input" disabled>
            <?php endif; ?>
        </td>
                                        <td class="px-3"><?= $i++ ?></td>
                                        <td class="px-3">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($k['ho_ten']) ?></div>
                                            <small class="text-muted"><?= $k['nhom'] ?></small>
                                        </td>

                                        <td class="px-3">
                                            <span class="badge bg-light text-dark border"><?= $k['gioi_tinh'] ?></span>
                                            <?php if ($k['tuoi']): ?><span class="ms-1 text-muted small"><?= $k['tuoi'] ?> tuổi</span><?php endif; ?>
                                        </td>
                                        <td class="px-3">
                                            <a href="tel:<?= $k['sdt'] ?>" class="text-decoration-none fw-bold text-primary"><?= $k['sdt'] ?></a>
                                            <div class="small text-muted">Đặt bởi: <?= htmlspecialchars($k['nguoi_dat']) ?></div>
                                        </td>
                                        <td class="px-3">
                                            <?php if ($k['ghi_chu']): ?>
                                                <div class="text-danger small fw-bold bg-danger bg-opacity-10 p-2 rounded">
                                                    <?= htmlspecialchars($k['ghi_chu']) ?>
                                                </div>
                                            <?php else: ?> <span class="text-muted">-</span> <?php endif; ?>
                                        </td>

                                        <td class="px-3 text-center">
                                            <?php if ($is_canceled): ?>
                                                <div class="badge bg-danger text-wrap py-2" style="width: 100%;">
                                                    <i class="fas fa-ban me-1"></i> Đã vắng trạm trước
                                                </div>
                                            <?php else: ?>
                                                <select class="form-select form-select-sm status-select fw-bold border-0 shadow-sm"
                                                    data-id="<?= $k['id'] ?>"
                                                    style="cursor: pointer; background-color: 
                                    <?= $k['trang_thai_checkin'] == 'đã đến' ? '#d1e7dd' : ($k['trang_thai_checkin'] == 'vắng mặt' ? '#f8d7da' : '#f8f9fa') ?>;
                                color: 
                                    <?= $k['trang_thai_checkin'] == 'đã đến' ? '#0f5132' : ($k['trang_thai_checkin'] == 'vắng mặt' ? '#842029' : '#212529') ?>;">

                                                    <option value="chưa đến" <?= $k['trang_thai_checkin'] == 'chưa đến' ? 'selected' : '' ?>>⚪ Chưa đến</option>
                                                    <option value="đã đến" <?= $k['trang_thai_checkin'] == 'đã đến' ? 'selected' : '' ?>>🟢 Đã đến</option>
                                                    <option value="vắng mặt" <?= $k['trang_thai_checkin'] == 'vắng mặt' ? 'selected' : '' ?>>🔴 Vắng mặt</option>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Lấy ID trạm hiện tại từ PHP
        var currentTramId = '<?= $selected_tram_id ?>';
        // Sự kiện khi bấm vào nút "Check All" ở tiêu đề
        $('#checkAll').change(function() {
            var isChecked = $(this).prop('checked');
            // Chỉ chọn những ô không bị disable (những người chưa bị hủy/vắng trước đó)
            $('.check-item:not(:disabled)').prop('checked', isChecked);
        });
        $('.action-bulk').click(function() {
            var status = $(this).data('status'); // Lấy trạng thái: 'đã đến', 'vắng mặt', 'chưa đến'
            
            // Lấy danh sách các ID đã được tick chọn
            var selectedIds = [];
            $('.check-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Kiểm tra nếu chưa chọn ai
            if (selectedIds.length === 0) {
                alert('Vui lòng chọn ít nhất một khách hàng để thao tác!');
                return;
            }

            // Hộp thoại xác nhận
            var confirmMsg = 'Bạn có chắc chắn muốn cập nhật trạng thái "' + status.toUpperCase() + '" cho ' + selectedIds.length + ' khách hàng?';
            if (!confirm(confirmMsg)) {
                return;
            }

            // Gửi Ajax cập nhật hàng loạt
            $.ajax({
                url: '?act=check-all', // Router xử lý hàng loạt
                type: 'POST',
                dataType: 'json',
                data: {
                    ids: selectedIds,
                    status: status,
                    tram_id: currentTramId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload lại trang để cập nhật thanh tiến độ và danh sách
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (response.message || 'Cập nhật thất bại. Vui lòng thử lại.'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Lỗi hệ thống: Không thể kết nối đến server.');
                }
            });
        });

        // ==========================================
        // 3. LOGIC CẬP NHẬT LẺ TỪNG NGƯỜI (GIỮ NGUYÊN)
        // ==========================================
        
        $('.status-select').change(function() {
            var status = $(this).val();
            var id = $(this).data('id');
            var element = $(this);

            // Đổi màu ngay lập tức cho mượt (UX)
            updateColor(element, status);

            // Gửi Ajax cập nhật đơn lẻ
            $.ajax({
                url: '?act=check_in_khach', // Router xử lý đơn lẻ
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status,
                    tram_id: currentTramId
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Update thành công id: ' + id);
                        // Reload trang để cập nhật thanh tiến độ chung
                        location.reload();
                    } else {
                        alert('Lỗi: Cập nhật thất bại!');
                        // Nếu lỗi thì nên reload để trả lại trạng thái cũ
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert('Lỗi hệ thống khi cập nhật khách hàng này.');
                }
            });
        });

        // Hàm đổi màu nền select box
        function updateColor(element, status) {
            if (status == 'đã đến') {
                element.css({
                    'background-color': '#d1e7dd',
                    'color': '#0f5132'
                });
            } else if (status == 'vắng mặt') {
                element.css({
                    'background-color': '#f8d7da',
                    'color': '#842029'
                });
            } else {
                element.css({
                    'background-color': '#f8f9fa',
                    'color': '#212529'
                });
            }
        }
    });
</script>

<?php include './views/layout/footer.php'; ?>