<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-users me-2"></i>
                        Đặt Tour Mới
                    </a>
                    <a href="?act=dat-tour" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST" action="?act=dat-tour-store" id="datTourForm">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Thông tin tour -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marked-alt me-2"></i>
                                        Thông Tin Tour
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Chọn Lịch Khởi Hành <span class="text-danger">*</span></label>
                                        <select name="lich_khoi_hanh_id" id="lich_khoi_hanh_id" class="form-control" required>
                                            <option value="">-- Chọn lịch khởi hành --</option>
                                            <?php foreach ($lich_khoi_hanh_list as $lkh): ?>
                                                <?php $so_cho_con = $lkh['so_cho_con_lai'] ?? $lkh['so_cho_toi_da']; ?>
                                                <option value="<?php echo $lkh['id']; ?>"
                                                    data-gia="<?php echo $lkh['gia_tour']; ?>"
                                                    data-so-cho="<?php echo $so_cho_con; ?>">
                                                    <?php echo htmlspecialchars($lkh['ten_tour']); ?> |
                                                    <?php echo date('d/m/Y', strtotime($lkh['ngay_bat_dau'])); ?> -
                                                    <?php echo date('d/m/Y', strtotime($lkh['ngay_ket_thuc'])); ?> |
                                                    <?php echo number_format($lkh['gia_tour'], 0, ',', '.'); ?> VNĐ |
                                                    <span class="<?php echo $so_cho_con > 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo $so_cho_con > 0 ? "Còn {$so_cho_con} chỗ" : "HẾT CHỖ"; ?>
                                                    </span>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Danh sách khách hàng -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-users me-2"></i>
                                            Danh Sách Khách Hàng
                                            <span class="badge bg-primary ms-2" id="so_khach_badge">0</span>
                                        </h5>
                                        <div>
                                            <!-- Thêm nhanh nhiều khách -->
                                            <div class="input-group input-group-sm" style="width: 250px;">
                                                <input type="number" id="so_luong_khach_nhanh" class="form-control" 
                                                    placeholder="Số lượng" min="1" max="20" value="1">
                                                <button type="button" id="btnThemNhanh" class="btn btn-success">
                                                    <i class="fas fa-bolt me-1"></i> Thêm Nhanh
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnThemKhach" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i> Thêm Từng Khách
                                            </button>
                                            <button type="button" id="btnXoaHet" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash me-1"></i> Xóa Hết
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="danh_sach_khach">
                                        <!-- Khách hàng sẽ được thêm động ở đây -->
                                    </div>
                                    <div class="text-center py-4 text-muted" id="empty_state">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                                        <p>Chưa có khách hàng nào</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ghi chú
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-edit me-2"></i>
                                        Ghi Chú
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Ghi chú thêm..."></textarea>
                                </div>
                            </div> -->
                        </div>

                        <div class="col-lg-4">
                            <!-- Tổng hợp -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-receipt me-2"></i>
                                        Tổng Hợp
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Giá tour/khách:</span>
                                        <span id="gia_tour_don">0 VNĐ</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Số lượng khách:</span>
                                        <span id="so_luong_khach">0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Tổng tiền:</strong>
                                        <strong class="text-success" id="tong_tien">0 VNĐ</strong>
                                    </div>
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small id="thong_bao_cho" class="text-muted">
                                            Vui lòng chọn lịch khởi hành
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Đặt Tour
                                    </button>
                                    <a href="?act=dat-tour" class="btn btn-outline-secondary w-100">
                                        Hủy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Template khách hàng -->
<template id="template_khach">
    <div class="khach-item card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="card-title mb-0">Khách hàng <span class="stt-khach"></span></h6>
                <button type="button" class="btn btn-sm btn-outline-danger btn-xoa-khach">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small">Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" name="khach_hang_ho_ten[]" class="form-control form-control-sm" required
                            placeholder="Nhập họ tên">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small">Số Điện Thoại</label>
                        <input type="text" name="khach_hang_so_dien_thoai[]" class="form-control form-control-sm"
                            placeholder="Nhập SĐT">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small">Email</label>
                        <input type="email" name="khach_hang_email[]" class="form-control form-control-sm"
                            placeholder="Nhập email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label small">CCCD/CMND</label>
                        <input type="text" name="khach_hang_cccd[]" class="form-control form-control-sm"
                            placeholder="Nhập CCCD">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label small">Ngày Sinh</label>
                        <input type="date" name="khach_hang_ngay_sinh[]" class="form-control form-control-sm"
                            max="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label small">Giới Tính</label>
                        <select name="khach_hang_gioi_tinh[]" class="form-control form-control-sm">
                            <option value="nam">Nam</option>
                            <option value="nữ">Nữ</option>
                            <option value="khác">Khác</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label small">Địa Chỉ</label>
                        <input type="text" name="khach_hang_dia_chi[]" class="form-control form-control-sm"
                            placeholder="Nhập địa chỉ">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-0">
                        <label class="form-label small">Ghi Chú</label>
                        <input type="text" name="khach_hang_ghi_chu[]" class="form-control form-control-sm"
                            placeholder="Ghi chú ...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<?php include 'views/layout/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('datTourForm');
        const lichKhoiHanhSelect = document.getElementById('lich_khoi_hanh_id');
        const danhSachKhach = document.getElementById('danh_sach_khach');
        const emptyState = document.getElementById('empty_state');
        const templateKhach = document.getElementById('template_khach');
        const btnThemKhach = document.getElementById('btnThemKhach');
        const btnThemNhanh = document.getElementById('btnThemNhanh');
        const btnXoaHet = document.getElementById('btnXoaHet');
        const soLuongKhachNhanh = document.getElementById('so_luong_khach_nhanh');

        let soKhach = 0;
        let giaTour = 0;
        let soChoConLai = 0;

        // Xử lý chọn lịch khởi hành
        lichKhoiHanhSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                giaTour = parseFloat(selectedOption.dataset.gia);
                soChoConLai = parseInt(selectedOption.dataset.soCho);

                // Cập nhật max cho input thêm nhanh
                soLuongKhachNhanh.max = soChoConLai;
                
                // Nếu đã chọn số lượng lớn hơn số chỗ còn lại, reset về max
                if (parseInt(soLuongKhachNhanh.value) > soChoConLai) {
                    soLuongKhachNhanh.value = soChoConLai;
                }

                document.getElementById('gia_tour_don').textContent = formatCurrency(giaTour);
                updateTongTien();
                updateThongBaoCho();
            }
        });

        // Thêm từng khách
        btnThemKhach.addEventListener('click', function() {
            if (soChoConLai > 0 && soKhach < soChoConLai) {
                themKhach();
            } else {
                alert('Không thể thêm khách. Số chỗ còn lại: ' + soChoConLai);
            }
        });

        // Thêm nhanh nhiều khách
        btnThemNhanh.addEventListener('click', function() {
            if (!lichKhoiHanhSelect.value) {
                alert('Vui lòng chọn lịch khởi hành trước!');
                lichKhoiHanhSelect.focus();
                return;
            }

            const soLuong = parseInt(soLuongKhachNhanh.value);
            
            if (!soLuong || soLuong < 1) {
                alert('Vui lòng nhập số lượng hợp lệ!');
                soLuongKhachNhanh.focus();
                return;
            }

            if (soLuong > soChoConLai) {
                alert(`Số lượng (${soLuong}) vượt quá số chỗ còn lại (${soChoConLai})!`);
                return;
            }

            if (soKhach + soLuong > soChoConLai) {
                const coTheThem = soChoConLai - soKhach;
                alert(`Chỉ có thể thêm tối đa ${coTheThem} khách nữa!`);
                return;
            }

            // Thêm nhanh nhiều khách
            for (let i = 0; i < soLuong; i++) {
                themKhach();
            }

            // Focus vào ô họ tên của khách đầu tiên vừa thêm
            const hoTenInputs = document.querySelectorAll('input[name="khach_hang_ho_ten[]"]');
            if (hoTenInputs.length > 0) {
                const lastIndex = hoTenInputs.length - soLuong;
                hoTenInputs[lastIndex > 0 ? lastIndex : 0].focus();
            }
        });

        // Xóa hết khách
        btnXoaHet.addEventListener('click', function() {
            if (soKhach > 0) {
                if (confirm(`Bạn có chắc muốn xóa tất cả ${soKhach} khách hàng?`)) {
                    danhSachKhach.innerHTML = '';
                    soKhach = 0;
                    updateEmptyState();
                    updateTongTien();
                    updateSoKhachBadge();
                }
            } else {
                alert('Chưa có khách hàng nào để xóa!');
            }
        });

        // Enter để thêm nhanh
        soLuongKhachNhanh.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnThemNhanh.click();
            }
        });

        function themKhach() {
            soKhach++;

            const clone = templateKhach.content.cloneNode(true);
            const khachItem = clone.querySelector('.khach-item');
            khachItem.querySelector('.stt-khach').textContent = soKhach;

            // Xử lý xóa khách
            khachItem.querySelector('.btn-xoa-khach').addEventListener('click', function() {
                if (confirm('Xóa khách hàng này?')) {
                    khachItem.remove();
                    soKhach--;
                    updateTongTien();
                    updateEmptyState();
                    updateSoKhachBadge();
                    updateThongBaoCho();
                }
            });

            // Validate real-time
            const hoTenInput = khachItem.querySelector('input[name="khach_hang_ho_ten[]"]');
            hoTenInput.addEventListener('input', updateTongTien);

            danhSachKhach.appendChild(clone);
            updateEmptyState();
            updateTongTien();
            updateSoKhachBadge();
            updateThongBaoCho();
        }

        function updateEmptyState() {
            emptyState.style.display = soKhach > 0 ? 'none' : 'block';
        }

        function updateTongTien() {
            const soKhachHopLe = getSoKhachHopLe();
            const tongTien = soKhachHopLe * giaTour;

            document.getElementById('so_luong_khach').textContent = soKhachHopLe;
            document.getElementById('tong_tien').textContent = formatCurrency(tongTien);
            updateThongBaoCho();
            updateSoKhachBadge();
        }

        function getSoKhachHopLe() {
            let count = 0;
            const hoTenInputs = document.querySelectorAll('input[name="khach_hang_ho_ten[]"]');

            for (let input of hoTenInputs) {
                if (input.value.trim() !== '') {
                    count++;
                }
            }
            return count;
        }

        function updateThongBaoCho() {
            const soKhachHopLe = getSoKhachHopLe();
            const thongBao = document.getElementById('thong_bao_cho');

            if (soChoConLai > 0) {
                if (soKhachHopLe <= soChoConLai) {
                    const coTheThem = soChoConLai - soKhachHopLe;
                    thongBao.innerHTML = `<span class="text-success">
                        Đã đặt: ${soKhachHopLe}/${soChoConLai} khách | 
                        Có thể thêm: ${coTheThem} khách
                    </span>`;
                } else {
                    thongBao.innerHTML = `<span class="text-danger">Vượt quá số chỗ! Tối đa: ${soChoConLai} khách</span>`;
                }
            } else {
                thongBao.innerHTML = `<span class="text-danger">Tour đã hết chỗ</span>`;
            }
        }

        function updateSoKhachBadge() {
            document.getElementById('so_khach_badge').textContent = getSoKhachHopLe();
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        // Form validation
        form.addEventListener('submit', function(event) {
            const soKhachHopLe = getSoKhachHopLe();

            if (!lichKhoiHanhSelect.value) {
                alert('Vui lòng chọn lịch khởi hành!');
                event.preventDefault();
                return;
            }

            if (soKhachHopLe === 0) {
                alert('Vui lòng thêm ít nhất một khách hàng!');
                event.preventDefault();
                return;
            }

            if (soKhachHopLe > soChoConLai) {
                alert(`Số lượng khách (${soKhachHopLe}) vượt quá số chỗ còn lại (${soChoConLai})!`);
                event.preventDefault();
                return;
            }

            // Kiểm tra thông tin khách hàng
            const hoTenInputs = document.querySelectorAll('input[name="khach_hang_ho_ten[]"]');
            for (let input of hoTenInputs) {
                if (!input.value.trim()) {
                    alert('Vui lòng nhập đầy đủ họ tên cho tất cả khách hàng!');
                    input.focus();
                    event.preventDefault();
                    return;
                }
            }
        });

        // Auto-focus vào ô số lượng khi thêm nhanh
        soLuongKhachNhanh.addEventListener('focus', function() {
            this.select();
        });
    });
</script>

<style>
.input-group {
    max-width: 250px;
}

.khach-item {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}
</style>