<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="page-title">
        <h3>Viết Nhật Ký Tour</h3>
    </div>

    <form action="?act=nhat_ky_store" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- Cột trái: Thông tin chính -->
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">Thông Tin Hành Trình</div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Chọn Tour Đang Dẫn (*)</label>
                            <select name="lich_khoi_hanh_id" class="form-select" required>
                                <option value="">-- Chọn lịch khởi hành --</option>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= $tour['id'] ?>">
                                        <?= $tour['ten_tour'] ?> (<?= date('d/m', strtotime($tour['ngay_bat_dau'])) ?> - <?= date('d/m', strtotime($tour['ngay_ket_thuc'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày Viết (*)</label>
                                <input type="date" name="ngay_nhat_ky" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thời Tiết</label>
                                <input type="text" name="thoi_tiet" class="form-control" placeholder="Ví dụ: Nắng đẹp, 28 độ C">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hoạt Động Chính Trong Ngày (*)</label>
                            <textarea name="hoat_dong" class="form-control" rows="4" placeholder="Mô tả các điểm đến, hoạt động đã thực hiện..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi Chú / Phản Hồi Khách</label>
                            <textarea name="diem_nhan" class="form-control" rows="3" placeholder="Khách thích gì? Có phàn nàn gì không?"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Sự cố & Media -->
            <div class="col-md-4">
                <!-- Toggle Sự cố -->
                <div class="card mb-3 border-danger">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <span>Báo Cáo Sự Cố</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleSuCo" name="co_su_co" value="1">
                        </div>
                    </div>
                    <div class="card-body" id="formSuCo" style="display: none;">
                        <div class="mb-2">
                            <label class="small fw-bold">Tiêu đề sự cố</label>
                            <input type="text" name="tieu_de_su_co" class="form-control form-control-sm">
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Mức độ</label>
                            <select name="muc_do_nghiem_trong" class="form-select form-select-sm">
                                <option value="thấp">Thấp</option>
                                <option value="trung bình" selected>Trung bình</option>
                                <option value="cao">Cao</option>
                                <option value="nghiêm trọng">Nghiêm trọng</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Mô tả sự cố</label>
                            <textarea name="mo_ta_su_co" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Cách xử lý</label>
                            <textarea name="cach_xu_ly" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="card">
                    <div class="card-header">Hình Ảnh / Video</div>
                    <div class="card-body">
                        <input type="file" name="hinh_anh[]" class="form-control" multiple>
                        <small class="text-muted">Chọn nhiều ảnh (Giữ Ctrl)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3 mb-5">
            <a href="?act=nhat_ky" class="btn btn-secondary me-2">Hủy</a>
            <button type="submit" class="btn btn-primary px-4">Lưu Nhật Ký</button>
        </div>
    </form>
</div>

<script>
    // Script ẩn hiện form sự cố
    document.getElementById('toggleSuCo').addEventListener('change', function() {
        const form = document.getElementById('formSuCo');
        form.style.display = this.checked ? 'block' : 'none';
        
        // Toggle required attribute cho input sự cố nếu cần thiết
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if(this.checked) input.setAttribute('required', 'required');
            else input.removeAttribute('required');
        });
    });
</script>

<?php include './views/layout/footer.php'; ?>