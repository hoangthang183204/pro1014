
<?php require './views/layout/header.php'; ?>

<?php include './views/layout/navbar.php'; ?>

<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">

    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-plus me-2"></i>
                        Thêm Lịch Khởi Hành
                    </a>
                    <div>
                        <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lịch khởi hành</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="?act=lich-khoi-hanh-store">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tour <span class="text-danger">*</span></label>
                                        <select name="tour_id" class="form-select" required>
                                            <option value="">Chọn tour</option>
                                            <?php foreach ($tours as $tour): ?>
                                                <option value="<?php echo $tour['id']; ?>" 
                                                    <?php echo isset($_POST['tour_id']) && $_POST['tour_id'] == $tour['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="date" name="ngay_bat_dau" class="form-control" required
                                            min="<?php echo date('Y-m-d'); ?>"
                                            value="<?php echo $_POST['ngay_bat_dau'] ?? ''; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="date" name="ngay_ket_thuc" class="form-control" required
                                            min="<?php echo date('Y-m-d'); ?>"
                                            value="<?php echo $_POST['ngay_ket_thuc'] ?? ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giờ tập trung</label>
                                        <input type="time" name="gio_tap_trung" class="form-control"
                                            value="<?php echo $_POST['gio_tap_trung'] ?? ''; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Điểm tập trung</label>
                                        <textarea name="diem_tap_trung" class="form-control" rows="3"
                                            placeholder="Địa điểm cụ thể..."><?php echo $_POST['diem_tap_trung'] ?? ''; ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Số chỗ tối đa <span class="text-danger">*</span></label>
                                        <input type="number" name="so_cho_toi_da" class="form-control" required
                                            min="1" max="100" value="<?php echo $_POST['so_cho_toi_da'] ?? '20'; ?>"
                                            placeholder="VD: 20">
                                        <small class="text-muted">Số lượng khách tối đa cho lịch này</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú vận hành</label>
                                <textarea name="ghi_chu_van_hanh" class="form-control" rows="4"
                                    placeholder="Ghi chú đặc biệt cho đội vận hành..."><?php echo $_POST['ghi_chu_van_hanh'] ?? ''; ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="?act=lich-khoi-hanh" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Lưu Lịch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>
<!-- End Footer -->

<script>
    $(function() {
        // Validate date range
        $('form').on('submit', function(e) {
            const ngayBatDau = $('input[name="ngay_bat_dau"]').val();
            const ngayKetThuc = $('input[name="ngay_ket_thuc"]').val();
            
            if (ngayBatDau && ngayKetThuc) {
                if (new Date(ngayBatDau) > new Date(ngayKetThuc)) {
                    alert('Ngày kết thúc phải sau ngày bắt đầu!');
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Auto set min date for end date based on start date
        $('input[name="ngay_bat_dau"]').on('change', function() {
            const startDate = $(this).val();
            if (startDate) {
                $('input[name="ngay_ket_thuc"]').attr('min', startDate);
            }
        });

        // Auto calculate end date if not set (default +2 days)
        $('input[name="ngay_bat_dau"]').on('change', function() {
            const startDate = $(this).val();
            const endDateInput = $('input[name="ngay_ket_thuc"]');
            
            if (startDate && !endDateInput.val()) {
                const start = new Date(startDate);
                start.setDate(start.getDate() + 2); // Default 2 days tour
                const endDate = start.toISOString().split('T')[0];
                endDateInput.val(endDate);
            }
        });
    });
</script>
</body>

</html>