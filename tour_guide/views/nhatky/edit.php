<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="page-title">
        <h3>Cập Nhật Nhật Ký</h3>
    </div>

    <form action="?act=nhat_ky_update" method="POST">
        <input type="hidden" name="id" value="<?= $nhatKy['id'] ?>">
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày Viết</label>
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($nhatKy['ngay_nhat_ky'])) ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thời Tiết</label>
                        <input type="text" name="thoi_tiet" class="form-control" value="<?= $nhatKy['thoi_tiet'] ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hoạt Động Chính</label>
                    <textarea name="hoat_dong" class="form-control" rows="5" required><?= $nhatKy['hoat_dong'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi Chú / Điểm Nhấn</label>
                    <textarea name="diem_nhan" class="form-control" rows="3"><?= $nhatKy['diem_nhan'] ?></textarea>
                </div>
                
                <?php if($suCo): ?>
                <div class="alert alert-warning">
                    <strong><i class="fas fa-exclamation-triangle"></i> Cảnh báo sự cố đã ghi nhận:</strong><br>
                    <?= $suCo['tieu_de'] ?> - Mức độ: <?= $suCo['muc_do_nghiem_trong'] ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-end">
            <a href="?act=nhat_ky" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>

<?php require './views/layout/footer.php'; ?>