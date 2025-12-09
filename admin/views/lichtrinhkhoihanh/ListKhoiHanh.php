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
                        <i class="fas fa-calendar-alt me-2"></i>
                        Quản Lý Lịch Khởi Hành
                    </a>
                    <a href="?act=lich-khoi-hanh-create" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Thêm Lịch
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc lịch khởi hành</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <input type="hidden" name="act" value="lich-khoi-hanh">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên tour..."
                                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="trang_thai"
                                        class="form-select shadow-sm py-2 border p-2"
                                        style="border-radius: 0.5rem !important;">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="đã lên lịch" <?php echo ($_GET['trang_thai'] ?? '') === 'đã lên lịch' ? 'selected' : ''; ?>>Đã lên lịch</option>
                                        <option value="đang đi" <?php echo ($_GET['trang_thai'] ?? '') === 'đang đi' ? 'selected' : ''; ?>>Đang đi</option>
                                        <option value="đã hoàn thành" <?php echo ($_GET['trang_thai'] ?? '') === 'đã hoàn thành' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                        <option value="đã hủy" <?php echo ($_GET['trang_thai'] ?? '') === 'đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="thang" class="form-select shadow-sm py-2 border p-2" style="border-radius: 0.5rem !important;">>
                                        <option value="">Tất cả tháng</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($_GET['thang'] ?? '') == $i ? 'selected' : ''; ?>>
                                                Tháng <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Lọc
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danh sách lịch khởi hành -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lịch khởi hành (<?php echo count($lich_khoi_hanh); ?>)</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($lich_khoi_hanh)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="200">Tour</th>
                                            <th width="150">Thời gian</th>
                                            <th width="150">Điểm tập trung</th>
                                            <th width="120" class="text-center">Số chỗ</th>
                                            <th width="120" class="text-center">Trạng thái</th>
                                            <th width="180" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // KHỞI TẠO MODEL ĐỂ SỬ DỤNG HÀM
                                        $lichKhoiHanhModel = new AdminLichKhoiHanh();
                                        ?>
                                        <?php foreach ($lich_khoi_hanh as $lich): ?>
                                            <?php
                                            // SỬ DỤNG HÀM TỪ MODEL ĐỂ LẤY TRẠNG THÁI REAL-TIME
                                            $trang_thai_hien_tai = $lichKhoiHanhModel->getTrangThaiHienTai(
                                                $lich['ngay_bat_dau'],
                                                $lich['ngay_ket_thuc'],
                                                $lich['trang_thai']
                                            );

                                            // Kiểm tra quyền xoá (chỉ được xoá khi trạng thái là "đã hủy")
                                            $cho_phep_xoa = ($trang_thai_hien_tai === 'đã hủy');
                                            ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong class="text-primary"><?php echo htmlspecialchars($lich['ma_tour']); ?></strong><br>
                                                        <strong><?php echo htmlspecialchars($lich['ten_tour']); ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo date('d/m/Y', strtotime($lich['ngay_bat_dau'])); ?></strong><br>
                                                        <small class="text-muted">
                                                            <?php echo $lich['gio_tap_trung'] ? date('H:i', strtotime($lich['gio_tap_trung'])) : 'Chưa có giờ'; ?>
                                                        </small>
                                                        <br>
                                                        <small class="text-muted">
                                                            Kết thúc: <?php echo date('d/m/Y', strtotime($lich['ngay_ket_thuc'])); ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($lich['diem_tap_trung']): ?>
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <?php echo htmlspecialchars($lich['diem_tap_trung']); ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa có</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        <strong class="text-success"><?php echo $lich['so_cho_con_lai']; ?></strong>
                                                        <small class="text-muted">/<?php echo $lich['so_cho_toi_da']; ?></small>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php
                                                            $so_cho_da_dat = $lich['so_cho_toi_da'] - $lich['so_cho_con_lai'];
                                                            $ty_le_dat = $lich['so_cho_toi_da'] > 0 ?
                                                                round(($so_cho_da_dat / $lich['so_cho_toi_da']) * 100, 1) : 0;
                                                            ?>
                                                            Đã đặt: <?php echo $ty_le_dat; ?>%
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?php
                                                                            echo match ($trang_thai_hien_tai) {
                                                                                'đã lên lịch' => 'success',
                                                                                'đang đi' => 'warning',
                                                                                'đã hoàn thành' => 'primary',
                                                                                'đã hủy' => 'danger',
                                                                                default => 'secondary'
                                                                            };
                                                                            ?>">
                                                        <?php echo htmlspecialchars($trang_thai_hien_tai); ?>
                                                    </span>
                                                    <?php if ($trang_thai_hien_tai === 'đã lên lịch'): ?>
                                                        <?php
                                                        // Tính số ngày còn lại CHÍNH XÁC
                                                        $ngay_bat_dau = strtotime($lich['ngay_bat_dau']);
                                                        $hom_nay = strtotime(date('Y-m-d')); // Chỉ lấy phần ngày, bỏ qua giờ

                                                        $ngay_con_lai = floor(($ngay_bat_dau - $hom_nay) / (60 * 60 * 24));

                                                        if ($ngay_con_lai > 1) {
                                                            echo '<br><small class="text-muted">Còn ' . $ngay_con_lai . ' ngày</small>';
                                                        } elseif ($ngay_con_lai == 1) {
                                                            echo '<br><small class="text-warning">Khởi hành ngày mai</small>';
                                                        } elseif ($ngay_con_lai == 0) {
                                                            echo '<br><small class="text-success"><strong>Khởi hành hôm nay</strong></small>';
                                                        } elseif ($ngay_con_lai < 0) {
                                                            echo '<br><small class="text-danger"><i class="fas fa-exclamation-circle"></i> Đáng lý đã khởi hành</small>';
                                                        }
                                                        ?>
                                                    <?php elseif ($trang_thai_hien_tai === 'đang đi'): ?>
                                                        <?php

                                                        $ngay_bat_dau = strtotime($lich['ngay_bat_dau']);
                                                        $ngay_ket_thuc = strtotime($lich['ngay_ket_thuc']);
                                                        $hom_nay = strtotime(date('Y-m-d'));

                                                        $ngay_da_di = floor(($hom_nay - $ngay_bat_dau) / (60 * 60 * 24)) + 1;
                                                        $tong_ngay = floor(($ngay_ket_thuc - $ngay_bat_dau) / (60 * 60 * 24)) + 1;

                                                        // Đảm bảo ngày đã đi không vượt quá tổng ngày
                                                        $ngay_da_di = min($ngay_da_di, $tong_ngay);

                                                        echo '<br><small class="text-info">Ngày ' . $ngay_da_di . '/' . $tong_ngay . '</small>';

                                                        // Hiển thị tiến độ nếu tour dài
                                                        if ($tong_ngay > 3) {
                                                            $phan_tram = round(($ngay_da_di / $tong_ngay) * 100, 1);
                                                            echo '<br><small class="text-muted">' . $phan_tram . '% hoàn thành</small>';
                                                        }
                                                        ?>
                                                    <?php endif; ?>

                                                    <!-- Hiển thị cảnh báo nếu trạng thái DB khác real-time -->
                                                    <?php if ($trang_thai_hien_tai !== $lich['trang_thai']): ?>
                                                        <br>
                                                        <small class="text-warning">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            DB: <?= $lich['trang_thai'] ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">

                                                    <!-- Hàng 1 -->
                                                    <div class="btn-group btn-group-sm mb-1">

                                                        <!-- Nút Sửa -->
                                                        <?php if ($trang_thai_hien_tai !== 'đã hoàn thành' && $trang_thai_hien_tai !== 'đã hủy'): ?>
                                                            <a href="?act=lich-khoi-hanh-edit&id=<?php echo $lich['id']; ?>"
                                                                class="btn btn-primary" title="Sửa lịch">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary" disabled title="Không thể sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Nút Phân công HDV -->
                                                        <?php if ($trang_thai_hien_tai !== 'đã hoàn thành' && $trang_thai_hien_tai !== 'đã hủy'): ?>
                                                            <a href="?act=phan-cong&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                                class="btn btn-info" title="Phân công HDV">
                                                                <i class="fas fa-user-tie"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary" disabled title="Không thể phân công">
                                                                <i class="fas fa-user-tie"></i>
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Checklist -->
                                                        <a href="?act=checklist-truoc-tour&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                            class="btn btn-warning" title="Checklist">
                                                            <i class="fas fa-tasks"></i>
                                                        </a>
                                                        <!-- Thêm nút Lịch trình -->
                                                        <a href="?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                            class="btn btn-info" title="Lịch trình">
                                                            <i class="fas fa-route"></i>
                                                        </a>
                                                    </div>
                                                    <!-- Hàng 2 -->
                                                    <div class="btn-group btn-group-sm">

                                                        <!-- Phân phòng -->
                                                        <?php if ($trang_thai_hien_tai !== 'đã hoàn thành' && $trang_thai_hien_tai !== 'đã hủy'): ?>
                                                            <a href="?act=phan-phong&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                                class="btn btn-info" title="Phân phòng">
                                                                <i class="fas fa-hotel"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary" disabled title="Không thể phân phòng">
                                                                <i class="fas fa-hotel"></i>
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Trạm dừng chân -->
                                                        <a href="?act=tram-dung-chan&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                            class="btn btn-success" title="Trạm dừng chân">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </a>
                                                        <!-- Xóa -->
                                                        <?php if ($cho_phep_xoa): ?>
                                                            <a href="?act=lich-khoi-hanh-delete&id=<?php echo $lich['id']; ?>"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Bạn có chắc muốn xóa lịch này?')"
                                                                title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary" disabled title="Chỉ được xoá khi tour đã hủy">
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
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có lịch khởi hành</h5>
                                <p class="text-muted">Hãy tạo lịch khởi hành mới</p>
                                <a href="?act=lich-khoi-hanh-create" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tạo Lịch Đầu Tiên
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer với thông tin phân trang -->
                    <?php if (!empty($lich_khoi_hanh)): ?>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Đang xem <strong>1</strong> đến <strong><?php echo count($lich_khoi_hanh); ?></strong> trong tổng số <strong><?php echo count($lich_khoi_hanh); ?></strong> mục
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Lưu ý:</strong> Trạng thái tự động cập nhật theo ngày hiện tại
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>