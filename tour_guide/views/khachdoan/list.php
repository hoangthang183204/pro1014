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
                        <label class="fw-bold mb-1 text-primary"><i class="fas fa-map-marker-alt me-1"></i> Chọn Lộ Trình Điểm Danh:</label>
                        <select name="tram_id" class="form-select border-primary fw-bold" onchange="this.form.submit()">
                            <?php if (empty($dsTram)): ?>
                                <option value="0">Đang tạo Lộ Trình...</option>
                            <?php else: ?>
                                <?php
                                // Lấy thứ tự (thu_tu) của trạm đang được chọn hiện tại để so sánh
                                $current_thu_tu = 0;
                                foreach ($dsTram as $t) {
                                    if ($t['id'] == $selected_tram_id) {
                                        $current_thu_tu = $t['thu_tu'];
                                        break;
                                    }
                                }
                                ?>

                                <?php foreach ($dsTram as $tram): ?>
                                    <?php
                                    // Kiểm tra xem Lộ trình này có được phép chọn không
                                    $isAllowed = in_array($tram['id'], $allowedTramIds);

                                    // Logic hiển thị thông báo
                                    $note = '';
                                    if (!$isAllowed) {
                                        if ($tram['thu_tu'] < $current_thu_tu) {
                                            $note = '(Đã hoàn thành - Không thể quay lại)';
                                        } else {
                                            $note = '(Hoàn thành Lộ trình hiện tại để mở)';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $tram['id'] ?>"
                                        <?= $selected_tram_id == $tram['id'] ? 'selected' : '' ?>
                                        <?= !$isAllowed ? 'disabled' : '' ?>
                                        style="<?= !$isAllowed ? 'color: #999; background: #eee;' : '' ?>">
                                        Lộ Trình <?= $tram['thu_tu'] ?>: <?= htmlspecialchars($tram['ten_tram']) ?> <?= $note ?>
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
                                <!-- THÊM CỘT MỚI -->
                                <th class="px-3 text-center" style="width: 120px;">Yêu cầu đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dsKhach)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Chưa có khách trong danh sách.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1;
                                foreach ($dsKhach as $k):
                                    $is_canceled = isset($k['da_huy_truoc_do']) && $k['da_huy_truoc_do'] > 0;
                                    $row_class = $is_canceled ? 'table-secondary opacity-75' : '';
                                ?>
                                    <tr class="<?= $row_class ?>">
                                        <td class="px-3">
                                            <?php if (!$is_canceled): // Chỉ hiện checkbox nếu khách chưa bị hủy 
                                            ?>
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

                                        <!-- CỘT MỚI: Yêu cầu đặc biệt -->
                                        <td class="px-3 text-center">
                                            <?php
                                            $hasYeuCau = isset($k['ghi_chu']) && !empty(trim($k['ghi_chu']));
                                            $isConfirmed = isset($k['yeu_cau_confirmed']) && $k['yeu_cau_confirmed'] == 1;
                                            ?>

                                            <?php if ($hasYeuCau): ?>
                                                <?php if ($isConfirmed): ?>
                                                    <!-- ĐÃ XÁC NHẬN: Nút xanh, chỉ để xem -->
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="viewYeuCau('<?= htmlspecialchars($k['ho_ten']) ?>', '<?= htmlspecialchars($k['ghi_chu']) ?>')">
                                                        <i class="fas fa-check-circle me-1"></i> Đã xác nhận
                                                    </button>
                                                <?php else: ?>
                                                    <!-- CHƯA XÁC NHẬN: Nút vàng, có thể xác nhận -->
                                                    <button class="btn btn-sm btn-warning btn-yc"
                                                        data-khach-id="<?= $k['id'] ?>"
                                                        data-khach-ten="<?= htmlspecialchars($k['ho_ten']) ?>"
                                                        data-ghi-chu="<?= htmlspecialchars($k['ghi_chu']) ?>">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Cần xác nhận
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- KHÔNG CÓ YÊU CẦU -->
                                                <span class="badge bg-light text-dark border">-</span>
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

<!-- Modal xác nhận yêu cầu -->
<div class="modal fade" id="yeuCauModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check me-2"></i>Xác nhận yêu cầu đặc biệt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Khách hàng:</label>
                    <p class="form-control-plaintext fw-bold" id="modalKhachTen"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Yêu cầu đặc biệt:</label>
                    <div class="alert alert-warning p-3" id="modalYeuCau"></div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmCheck">
                    <label class="form-check-label" for="confirmCheck">
                        <span class="fw-bold text-success">✓</span> Tôi đã kiểm tra và xử lý yêu cầu này
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success" id="btnXacNhan">
                    <i class="fas fa-check me-1"></i> Xác nhận đã xử lý
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem yêu cầu đã xác nhận -->
<div class="modal fade" id="viewYeuCauModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Xem yêu cầu đã xác nhận
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Khách hàng:</label>
                    <p class="form-control-plaintext fw-bold" id="viewKhachTen"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Yêu cầu đặc biệt:</label>
                    <div class="alert alert-info p-3" id="viewYeuCau"></div>
                </div>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> Yêu cầu này đã được xác nhận xử lý.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        const currentTramId = '<?= isset($selected_tram_id) ? $selected_tram_id : 0 ?>';

        // 1. XỬ LÝ CHECK ALL
        $('#checkAll').change(function() {
            $('.check-item:not(:disabled)').prop('checked', $(this).is(':checked'));
        });

        $(document).on('change', '.check-item', function() {
            var allEnabled = $('.check-item:not(:disabled)');
            var allChecked = $('.check-item:not(:disabled):checked');
            $('#checkAll').prop('checked', allEnabled.length > 0 && allEnabled.length === allChecked.length);
        });

        // 2. XỬ LÝ HÀNG LOẠT (BULK ACTION)
        $('.action-bulk').click(function() {
            var status = $(this).data('status');
            var selectedIds = [];
            $('.check-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Vui lòng chọn ít nhất một khách hàng!');
                return;
            }

            if (!confirm('Xác nhận cập nhật trạng thái cho ' + selectedIds.length + ' khách hàng?')) return;

            var $btn = $(this).prop('disabled', true);

            $.ajax({
                url: '?act=check_in_bulk', // <--- ĐÃ SỬA: Phải trùng tên hàm trong Controller
                type: 'POST',
                dataType: 'json',
                data: {
                    ids: selectedIds,
                    status: status,
                    tram_id: currentTramId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (response.message || 'Cập nhật thất bại'));
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Lỗi hệ thống (Bulk): Kiểm tra Console để xem chi tiết.');
                    $btn.prop('disabled', false);
                }
            });
        });

        // 3. XỬ LÝ CHECK-IN ĐƠN LẺ
        $('.status-select').change(function() {
            var status = $(this).val();
            var id = $(this).data('id');
            var element = $(this);

            // Đổi màu tạm thời để user thấy phản hồi ngay
            updateColor(element, status);

            $.ajax({
                url: '?act=update_checkin_status',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: status,
                    tram_id: currentTramId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload để cập nhật lại thanh tiến độ chính xác từ server
                        location.reload();
                    } else {
                        alert('Cập nhật thất bại! Vui lòng thử lại.');
                        location.reload(); // Reload lại để trả về trạng thái cũ nếu lỗi
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // Xem lỗi cụ thể trong F12
                    alert('Lỗi hệ thống! Vui lòng kiểm tra lại server.');
                }
            });
        });

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

        // 4. XỬ LÝ YÊU CẦU ĐẶC BIỆT
        // (Giữ nguyên logic cũ của bạn ở phần này, chỉ copy lại phần viewYeuCau, btn-yc...)
        // ... Code xử lý modal yêu cầu đặc biệt ...
        let currentKhachId = null;
        $(document).on('click', '.btn-yc', function() {
            currentKhachId = $(this).data('khach-id');
            $('#modalKhachTen').text($(this).data('khach-ten'));
            $('#modalYeuCau').text($(this).data('ghi-chu'));
            $('#confirmCheck').prop('checked', false);
            $('#yeuCauModal').modal('show');
        });

        $('#btnXacNhan').click(function() {
            if (!$('#confirmCheck').is(':checked')) {
                alert('Vui lòng tích xác nhận!');
                return;
            }
            $.ajax({
                url: '?act=confirm_yeu_cau',
                type: 'POST',
                dataType: 'json',
                data: {
                    khach_id: currentKhachId
                },
                success: function(res) {
                    if (res.success) {
                        alert('Thành công!');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + res.message);
                    }
                }
            });
        });
    });

    function viewYeuCau(ten, ghichu) {
        $('#viewKhachTen').text(ten);
        $('#viewYeuCau').text(ghichu);
        $('#viewYeuCauModal').modal('show');
    }
</script>

<?php include './views/layout/footer.php'; ?>