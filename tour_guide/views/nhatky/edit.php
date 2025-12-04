<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<style>
    /* CẤU TRÚC CARD: Giữ bo góc nhẹ cho mềm mại */
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* STYLE CHUNG CHO HEADER: Chữ trắng, In đậm */
    .card-header {
        border-bottom: 0;
        padding: 12px 20px;
    }
    .card-title {
        color: #ffffff; /* CHỮ MÀU TRẮNG */
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    /* 1. Card Thông Tin: MÀU XÁM ĐEN (Giống tiêu đề bảng Danh sách) */
    .card-primary .card-header {
        background-color: #343a40; 
    }

    /* 2. Card Sự Cố: MÀU ĐỎ (Cảnh báo rõ ràng) */
    .card-danger .card-header {
        background-color: #dc3545;
    }

    /* 3. Card Hình Ảnh: MÀU XANH CYAN (Tươi sáng) */
    .card-info .card-header {
        background-color: #17a2b8;
    }

    /* INPUT FORM: Sạch sẽ, dễ nhìn */
    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 10px;
    }
    .form-control:focus {
        border-color: #343a40; /* Focus màu tối cho đồng bộ */
        box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
    }

    /* NÚT BẤM: Đơn giản, phẳng */
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        padding: 10px 30px;
        font-weight: 600;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 10px 25px;
    }
</style>
<div class="content-wrapper"> <section class="content-header">
        <div class="container-fluid">
            <h3>Cập Nhật Nhật Ký</h3>
        </div>
    </section>

    <section class="content">
        <form action="?act=nhat_ky_update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $nhatKy['id'] ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông Tin Chi Tiết</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Ngày Viết</label>
                                    <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($nhatKy['ngay_nhat_ky'])) ?>" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Thời Tiết</label>
                                    <input type="text" name="thoi_tiet" class="form-control" value="<?= htmlspecialchars($nhatKy['thoi_tiet']) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Hoạt Động Chính</label>
                                <textarea name="hoat_dong" class="form-control" rows="5" required><?= htmlspecialchars($nhatKy['hoat_dong']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Ghi Chú / Điểm Nhấn</label>
                                <textarea name="diem_nhan" class="form-control" rows="3"><?= htmlspecialchars($nhatKy['diem_nhan']) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Hình Ảnh Đã Lưu</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($dsAnh)): ?>
                                    <?php foreach ($dsAnh as $anh): ?>
                                        <div class="col-md-3 col-6 mb-2 text-center">
                                            <img src="<?= BASE_URL_GUIDE . '/' . $anh['url'] ?>" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Chưa có hình ảnh nào.</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label>Thêm ảnh mới (nếu cần)</label>
                                <input type="file" name="hinh_anh[]" id="inputAnh" class="form-control" multiple>
                            </div>
                            <div class="row mt-3" id="previewContainer">
            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Cập Nhật Sự Cố</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($suCo): ?>
                                <input type="hidden" name="co_su_co" value="1"> <div class="mb-2">
                                    <label>Tiêu đề sự cố</label>
                                    <input type="text" name="tieu_de_su_co" class="form-control" value="<?= htmlspecialchars($suCo['tieu_de']) ?>">
                                </div>
                                <div class="mb-2">
                                    <label>Mức độ</label>
                                    <select name="muc_do_nghiem_trong" class="form-control">
                                        <option value="thấp" <?= $suCo['muc_do_nghiem_trong'] == 'thấp' ? 'selected' : '' ?>>Thấp</option>
                                        <option value="trung bình" <?= $suCo['muc_do_nghiem_trong'] == 'trung bình' ? 'selected' : '' ?>>Trung bình</option>
                                        <option value="cao" <?= $suCo['muc_do_nghiem_trong'] == 'cao' ? 'selected' : '' ?>>Cao</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Mô tả chi tiết</label>
                                    <textarea name="mo_ta_su_co" class="form-control" rows="3"><?= htmlspecialchars($suCo['mo_ta_su_co']) ?></textarea>
                                </div>
                                <div class="mb-2">
                                    <label>Cách xử lý</label>
                                    <textarea name="cach_xu_ly" class="form-control" rows="3"><?= htmlspecialchars($suCo['cach_xu_ly']) ?></textarea>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Ngày này không có sự cố nào được ghi nhận.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center pb-5">
                <a href="?act=nhat_ky" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-success px-5">Lưu Thay Đổi</button>
            </div>
        </form>
    </section>
</div>
<script>
    // Lắng nghe sự kiện khi người dùng chọn file
    document.getElementById('inputAnh').addEventListener('change', function(event) {
        var container = document.getElementById('previewContainer');
        container.innerHTML = ''; // Xóa các ảnh preview cũ (nếu chọn lại)

        var files = event.target.files; // Lấy danh sách file đã chọn

        if (files) {
            // Duyệt qua từng file
            [].forEach.call(files, function(file) {
                // Chỉ xử lý file ảnh
                if (file.type.match('image.*')) {
                    var reader = new FileReader();

                    // Khi đọc xong file thì tạo thẻ img
                    reader.onload = function(event) {
                        var colDiv = document.createElement('div');
                        colDiv.className = 'col-md-3 col-6 mb-2'; // Chia cột giống giao diện trên

                        var img = document.createElement('img');
                        img.className = 'img-thumbnail';
                        img.style.width = '100%';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.src = event.target.result; // Dữ liệu ảnh base64

                        colDiv.appendChild(img);
                        container.appendChild(colDiv);
                    };

                    // Bắt đầu đọc file
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
<?php require './views/layout/footer.php'; ?>