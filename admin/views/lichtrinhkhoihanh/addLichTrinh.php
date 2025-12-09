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
                        <i class="fas fa-plus me-2"></i>
                        Thêm Lịch Trình Mới
                    </a>
                    <div>
                        <!-- SỬA: thay đổi link quay lại -->
                        <a href="?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            <strong><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></strong>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông tin tour và lịch khởi hành -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Tour: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?></h5>
                        <p class="card-text mb-0">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Ngày khởi hành: <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lịch trình</h5>
                    </div>
                    <div class="card-body">
                        <!-- SỬA: thay đổi action và thêm hidden field lich_khoi_hanh_id -->
                        <form method="POST" action="?act=lich-khoi-hanh-lich-trinh-store">
                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số ngày <span class="text-danger">*</span></label>
                                        <input type="number" name="so_ngay" class="form-control" required
                                            min="1" max="30"
                                            value="<?php echo ($max_so_ngay + 1); ?>">
                                        <small class="text-muted">Ngày thứ mấy trong tour</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề ngày <span class="text-danger">*</span></label>
                                        <input type="text" name="tieu_de" class="form-control" required
                                            placeholder="VD: Khám phá Đà Lạt, Tận hưởng biển Nha Trang..."
                                            value="<?php echo htmlspecialchars($_POST['tieu_de'] ?? ''); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thứ tự sắp xếp</label>
                                        <input type="number" name="thu_tu_sap_xep" class="form-control"
                                            min="0" value="<?php echo htmlspecialchars($_POST['thu_tu_sap_xep'] ?? '0'); ?>">
                                        <small class="text-muted">Số nhỏ hiển thị trước</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Chỗ ở</label>
                                        <input type="text" name="cho_o" class="form-control"
                                            placeholder="VD: Khách sạn 3 sao, Homestay..."
                                            value="<?php echo htmlspecialchars($_POST['cho_o'] ?? ''); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Bữa ăn</label>
                                        <input type="text" name="bua_an" class="form-control"
                                            placeholder="VD: Sáng: buffet, Trưa: nhà hàng..."
                                            value="<?php echo htmlspecialchars($_POST['bua_an'] ?? ''); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phương tiện</label>
                                        <input type="text" name="phuong_tien" class="form-control"
                                            placeholder="VD: Xe ô tô 16 chỗ, Máy bay..."
                                            value="<?php echo htmlspecialchars($_POST['phuong_tien'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả hoạt động chi tiết <span class="text-danger">*</span></label>
                                <textarea name="mo_ta_hoat_dong" class="form-control" rows="6" required
                                    placeholder="Mô tả chi tiết các hoạt động trong ngày, lịch trình giờ giấc cụ thể..."><?php echo htmlspecialchars($_POST['mo_ta_hoat_dong'] ?? ''); ?></textarea>
                                <small class="text-muted">Mô tả càng chi tiết càng tốt để khách hàng nắm rõ lịch trình</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú cho HDV</label>
                                <textarea name="ghi_chu_hdv" class="form-control" rows="3"
                                    placeholder="Các lưu ý đặc biệt cho hướng dẫn viên..."><?php echo htmlspecialchars($_POST['ghi_chu_hdv'] ?? ''); ?></textarea>
                                <small class="text-muted">Chỉ HDV mới nhìn thấy mục này</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <!-- SỬA: thay đổi link hủy -->
                                <a href="?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Lưu Lịch Trình
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
});
</script>