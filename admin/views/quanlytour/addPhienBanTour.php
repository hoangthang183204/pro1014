<!-- views/quanlytour/addPhienBanTour.php -->
<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-plus me-2"></i>
                        Tạo Phiên Bản Mới
                    </a>
                    <div>
                        <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin phiên bản</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?act=phien-ban-store">
                                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tên phiên bản <span class="text-danger">*</span></label>
                                                <input type="text" name="ten_phien_ban" class="form-control" required
                                                       placeholder="VD: Hè 2024, Khuyến mãi Black Friday, VIP Tết...">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Loại phiên bản <span class="text-danger">*</span></label>
                                                <select name="loai_phien_ban" class="form-control" required id="loaiPhienBan">
                                                    <option value="">-- Chọn loại --</option>
                                                    <option value="mua">Theo mùa</option>
                                                    <option value="khuyen_mai">Khuyến mãi</option>
                                                    <option value="dac_biet">Đặc biệt (VIP, Lễ...)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Giá gốc</label>
                                                <div class="input-group">
                                                    <input type="number" name="gia_goc" class="form-control" 
                                                           value="<?php echo $tour['gia_tour']; ?>" 
                                                           placeholder="Giá tour hiện tại">
                                                    <span class="input-group-text">đ</span>
                                                </div>
                                                <small class="text-muted">Giá tour hiện tại: <?php echo number_format($tour['gia_tour'], 0, ',', '.'); ?> đ</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Giá phiên bản <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="gia_tour" class="form-control" required
                                                           value="<?php echo $tour['gia_tour']; ?>" 
                                                           placeholder="Giá áp dụng cho phiên bản này">
                                                    <span class="input-group-text">đ</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Khuyến mãi (%)</label>
                                                <div class="input-group">
                                                    <input type="number" name="khuyen_mai" class="form-control" 
                                                           min="0" max="100" step="0.1" 
                                                           placeholder="Tự động tính nếu có giá gốc">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                <small id="giamGiaText" class="text-success"></small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                                <input type="date" name="thoi_gian_bat_dau" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                                                <input type="date" name="thoi_gian_ket_thuc" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3" id="dichVuDacBietGroup" style="display: none;">
                                        <label class="form-label">Dịch vụ đặc biệt</label>
                                        <textarea name="dich_vu_dac_biet" class="form-control" rows="2"
                                                  placeholder="Mô tả dịch vụ đặc biệt cho phiên bản VIP..."></textarea>
                                        <small class="text-muted">VD: Xe đưa đón riêng, phòng suite, hướng dẫn viên riêng...</small>
                                    </div>
                                    
                                    <div class="mb-3" id="dieuKienApDungGroup" style="display: none;">
                                        <label class="form-label">Điều kiện áp dụng</label>
                                        <textarea name="dieu_kien_ap_dung" class="form-control" rows="2"
                                                  placeholder="Điều kiện áp dụng cho phiên bản khuyến mãi..."></textarea>
                                        <small class="text-muted">VD: Áp dụng cho nhóm từ 10 người, đặt trước 30 ngày...</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả phiên bản</label>
                                        <textarea name="mo_ta" class="form-control" rows="3"
                                                  placeholder="Mô tả chi tiết về phiên bản này..."></textarea>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>" 
                                           class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i> Hủy
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i> Tạo Phiên Bản
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Hướng dẫn sử dụng</h6>
                            </div>
                            <div class="card-body">
                                <h6>Loại phiên bản:</h6>
                                <ul class="mb-3">
                                    <li><strong>Theo mùa:</strong> Giá khác nhau theo mùa (hè, đông, lễ hội)</li>
                                    <li><strong>Khuyến mãi:</strong> Giảm giá, ưu đãi đặc biệt</li>
                                    <li><strong>Đặc biệt:</strong> VIP, dịch vụ cao cấp, tour riêng</li>
                                </ul>
                                
                                <h6>Giá phiên bản:</h6>
                                <ul>
                                    <li>Nhập giá gốc để tính % giảm giá</li>
                                    <li>Giá phiên bản là giá áp dụng thực tế</li>
                                    <li>Hệ thống tự tính % khuyến mãi</li>
                                </ul>
                                
                                <div class="alert alert-info mt-3">
                                    <strong><i class="fas fa-info-circle me-1"></i>Lưu ý:</strong>
                                    <p class="mb-0 small">Phiên bản hiện hành là phiên bản có thời gian hiệu lực bao gồm ngày hiện tại.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loaiPhienBan = document.getElementById('loaiPhienBan');
    const giaGocInput = document.querySelector('input[name="gia_goc"]');
    const giaTourInput = document.querySelector('input[name="gia_tour"]');
    const khuyenMaiInput = document.querySelector('input[name="khuyen_mai"]');
    const giamGiaText = document.getElementById('giamGiaText');
    const dichVuDacBietGroup = document.getElementById('dichVuDacBietGroup');
    const dieuKienApDungGroup = document.getElementById('dieuKienApDungGroup');
    
    // Hiển thị/ẩn các field theo loại phiên bản
    loaiPhienBan.addEventListener('change', function() {
        const loai = this.value;
        
        if (loai === 'dac_biet') {
            dichVuDacBietGroup.style.display = 'block';
            dieuKienApDungGroup.style.display = 'none';
        } else if (loai === 'khuyen_mai') {
            dichVuDacBietGroup.style.display = 'none';
            dieuKienApDungGroup.style.display = 'block';
        } else {
            dichVuDacBietGroup.style.display = 'none';
            dieuKienApDungGroup.style.display = 'none';
        }
    });
    
    // Tính % giảm giá
    function tinhGiamGia() {
        const giaGoc = parseFloat(giaGocInput.value) || 0;
        const giaTour = parseFloat(giaTourInput.value) || 0;
        
        if (giaGoc > 0 && giaTour > 0 && giaTour < giaGoc) {
            const giamGia = ((giaGoc - giaTour) / giaGoc) * 100;
            giamGiaText.textContent = `Giảm ${giamGia.toFixed(2)}% (${formatCurrency(giaGoc - giaTour)})`;
            khuyenMaiInput.value = giamGia.toFixed(2);
        } else {
            giamGiaText.textContent = '';
            khuyenMaiInput.value = '';
        }
    }
    
    giaGocInput.addEventListener('input', tinhGiamGia);
    giaTourInput.addEventListener('input', tinhGiamGia);
    
    // Định dạng tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
});
</script>

<?php include './views/layout/footer.php'; ?>