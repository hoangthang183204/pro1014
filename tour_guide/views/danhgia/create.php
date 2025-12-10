<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2 class="m-0 text-dark"><i class="fas fa-star me-2"></i> Đánh Giá Tour</h2>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="?act=danh_gia">Tour cần đánh giá</a></li>
                        <li class="breadcrumb-item active">Đánh giá tour</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check me-2"></i> 
                    Đánh giá tour: <?= htmlspecialchars($tourInfo['ten_tour']) ?>
                </h5>
                <small class="opacity-75">
                    Ngày đi: <?= date('d/m/Y', strtotime($tourInfo['ngay_bat_dau'])) ?> | 
                    Ngày về: <?= date('d/m/Y', strtotime($tourInfo['ngay_ket_thuc'])) ?>
                </small>
            </div>
            
            <form id="formDanhGia" method="POST">
                <input type="hidden" name="lich_khoi_hanh_id" value="<?= $tourInfo['id'] ?>">
                
                <div class="card-body">
                    <!-- PHẦN 1: THÔNG TIN TOUR -->
                    <div class="card mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Thông tin tour</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Khách sạn:</strong><br>
                                    <?= $tourInfo['ten_khach_san'] ?? 'Chưa có thông tin' ?><br>
                                    <small class="text-muted"><?= $tourInfo['nha_cung_cap_khach_san'] ?? '' ?></small></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Nhà hàng:</strong><br>
                                    <?= $tourInfo['ten_nha_hang'] ?? 'Chưa có thông tin' ?><br>
                                    <small class="text-muted"><?= $tourInfo['nha_cung_cap_nha_hang'] ?? '' ?></small></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Xe vận chuyển:</strong><br>
                                    <?= $tourInfo['loai_xe'] ?? 'Chưa có thông tin' ?><br>
                                    <small class="text-muted">Biển số: <?= $tourInfo['bien_so'] ?? '' ?></small></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PHẦN 2: ĐÁNH GIÁ TỔNG QUAN -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Đánh giá tổng quan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm đánh giá tổng quan (1-5 sao):</label>
                                <div class="rating-stars mb-3">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="diem_tong_quan" 
                                                   id="tong_quan_<?= $i ?>" value="<?= $i ?>" 
                                                   <?= $i == 5 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="tong_quan_<?= $i ?>">
                                                <?= str_repeat('★', $i) ?> (<?= $i ?>)
                                            </label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét tổng quan:</label>
                                <textarea class="form-control" name="noi_dung_tong_quan" rows="3" 
                                          placeholder="Nhận xét chung về chất lượng tour, dịch vụ..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PHẦN 3: ĐÁNH GIÁ KHÁCH SẠN -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-hotel me-2"></i> Đánh giá khách sạn</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm đánh giá (1-5 sao):</label>
                                <div class="mb-3">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="diem_khach_san" 
                                                   id="ks_<?= $i ?>" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="ks_<?= $i ?>"><?= $i ?> sao</label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét chi tiết:</label>
                                <textarea class="form-control" name="nhan_xet_khach_san" rows="3"
                                          placeholder="Nhận xét về phòng, vệ sinh, dịch vụ, nhân viên..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PHẦN 4: ĐÁNH GIÁ NHÀ HÀNG -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-utensils me-2"></i> Đánh giá nhà hàng</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm đánh giá (1-5 sao):</label>
                                <div class="mb-3">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="diem_nha_hang" 
                                                   id="nh_<?= $i ?>" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="nh_<?= $i ?>"><?= $i ?> sao</label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét chi tiết:</label>
                                <textarea class="form-control" name="nhan_xet_nha_hang" rows="3"
                                          placeholder="Nhận xét về chất lượng món ăn, phục vụ, không gian..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PHẦN 5: ĐÁNH GIÁ XE VẬN CHUYỂN -->
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="fas fa-bus me-2"></i> Đánh giá xe vận chuyển</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm đánh giá (1-5 sao):</label>
                                <div class="mb-3">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="diem_xe_van_chuyen" 
                                                   id="xe_<?= $i ?>" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="xe_<?= $i ?>"><?= $i ?> sao</label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét chi tiết:</label>
                                <textarea class="form-control" name="nhan_xet_xe_van_chuyen" rows="3"
                                          placeholder="Nhận xét về tình trạng xe, tài xế, an toàn..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PHẦN 6: DỊCH VỤ BỔ SUNG & ĐỀ XUẤT -->
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Đề xuất & Góp ý</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm đánh giá dịch vụ bổ sung (1-5 sao):</label>
                                <div class="mb-3">
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="diem_dich_vu_bo_sung" 
                                                   id="dvbs_<?= $i ?>" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="dvbs_<?= $i ?>"><?= $i ?> sao</label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhận xét dịch vụ bổ sung:</label>
                                <textarea class="form-control" name="nhan_xet_dich_vu_bo_sung" rows="2"
                                          placeholder="HDV địa phương, hướng dẫn tham quan, hoạt động bổ sung..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đề xuất cải thiện:</label>
                                <textarea class="form-control" name="de_xuat_cai_thien" rows="3"
                                          placeholder="Những điểm cần cải thiện để tour tốt hơn..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đề xuất tiếp tục sử dụng dịch vụ:</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="de_xuat_tiep_tuc_su_dung" 
                                               id="tieptuc_co" value="co" checked required>
                                        <label class="form-check-label text-success fw-bold" for="tieptuc_co">
                                            <i class="fas fa-thumbs-up me-1"></i> Có, nên tiếp tục
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="de_xuat_tiep_tuc_su_dung" 
                                               id="tieptuc_khong" value="khong" required>
                                        <label class="form-check-label text-danger fw-bold" for="tieptuc_khong">
                                            <i class="fas fa-thumbs-down me-1"></i> Không, không nên tiếp tục
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="de_xuat_tiep_tuc_su_dung" 
                                               id="tieptuc_dieukien" value="co_dieu_kien" required>
                                        <label class="form-check-label text-warning fw-bold" for="tieptuc_dieukien">
                                            <i class="fas fa-exclamation-circle me-1"></i> Có điều kiện
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="?act=danh_gia" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i> Gửi đánh giá
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#formDanhGia').on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        const requiredFields = [
            'diem_tong_quan', 
            'diem_khach_san', 
            'diem_nha_hang', 
            'diem_xe_van_chuyen',
            'diem_dich_vu_bo_sung',
            'de_xuat_tiep_tuc_su_dung'
        ];
        
        let isValid = true;
        requiredFields.forEach(field => {
            if (!$(`[name="${field}"]:checked`).length) {
                isValid = false;
                $(`[name="${field}"]`).parent().addClass('text-danger');
            } else {
                $(`[name="${field}"]`).parent().removeClass('text-danger');
            }
        });
        
        if (!isValid) {
            alert('Vui lòng đánh giá đầy đủ các tiêu chí bắt buộc (có dấu *)');
            return;
        }
        
        if (!confirm('Bạn có chắc chắn muốn gửi đánh giá này?')) {
            return;
        }
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '?act=danh_gia_store',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin me-2"></i> Đang gửi...');
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '?act=danh_gia_list';
                } else {
                    alert('Lỗi: ' + response.message);
                    $('button[type="submit"]').prop('disabled', false)
                        .html('<i class="fas fa-paper-plane me-2"></i> Gửi đánh giá');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Lỗi kết nối server! Vui lòng thử lại.');
                $('button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-paper-plane me-2"></i> Gửi đánh giá');
            }
        });
    });
});
</script>

<style>
.rating-stars .form-check-input:checked + .form-check-label {
    color: #ffc107;
    font-weight: bold;
}
.card-header {
    border-bottom: none;
}
.form-check-inline {
    margin-right: 15px !important;
}
.form-check-label {
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    transition: all 0.3s;
}
.form-check-label:hover {
    background-color: #f8f9fa;
}
</style>

<?php include './views/layout/footer.php'; ?>