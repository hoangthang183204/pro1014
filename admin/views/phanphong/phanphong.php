<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-hotel me-2"></i>
                        Quản Lý Phân Phòng: <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour'] ?? ''); ?>
                    </a>
                    <div>
                        <a href="?act=danh-sach-khach-tour&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" 
                           class="btn btn-outline-light me-2">
                            <i class="fas fa-users me-1"></i> Quản Lý Khách
                        </a>
                        <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay Lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông tin tour -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour'] ?? ''); ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Ngày đi:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'] ?? '')); ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Tổng khách:</strong> 
                                <span class="badge bg-primary"><?php echo count($danh_sach_phan_phong) + count($khach_chua_phan_phong); ?></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Đã phân phòng:</strong> 
                                <span class="badge bg-success"><?php echo count($danh_sach_phan_phong); ?></span>
                            </div>
                            <div class="col-md-3">
                                <strong>Chưa phân phòng:</strong> 
                                <span class="badge bg-warning"><?php echo count($khach_chua_phan_phong); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê phân phòng -->
                <?php if (!empty($thong_ke)): ?>
                <div class="row mb-4">
                    <?php foreach ($thong_ke as $stat): ?>
                    <div class="col-md-2">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center p-2">
                                <h6 class="mb-0"><?php echo $stat['so_luong']; ?></h6>
                                <small><?php echo htmlspecialchars($stat['loai_phong']); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Danh sách phân phòng -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    Danh Sách Phân Phòng
                                </h5>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalThemHangLoat">
                                    <i class="fas fa-layer-group me-1"></i> Phân Phòng Hàng Loạt
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($danh_sach_phan_phong)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>Khách Sạn & Phòng</th>
                                                    <th>Khách Hàng</th>
                                                    <th width="120">Loại Phòng</th>
                                                    <th width="150">Thời Gian</th>
                                                    <th width="100" class="text-center">Thao Tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($danh_sach_phan_phong as $index => $pp): ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                                        <td>
                                                            <div class="fw-bold text-primary"><?php echo htmlspecialchars($pp['ten_khach_san']); ?></div>
                                                            <div class="text-muted small">Phòng: <?php echo htmlspecialchars($pp['so_phong']); ?></div>
                                                            <?php if ($pp['ghi_chu']): ?>
                                                                <div class="text-muted small"><?php echo htmlspecialchars($pp['ghi_chu']); ?></div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold"><?php echo htmlspecialchars($pp['ho_ten']); ?></div>
                                                            <div class="text-muted small">
                                                                <?php echo htmlspecialchars($pp['ma_dat_tour']); ?>
                                                                <br>
                                                                <?php echo htmlspecialchars($pp['so_dien_thoai']); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $loai_phong_class = [
                                                                'đơn' => 'secondary',
                                                                'đôi' => 'primary',
                                                                'giường phụ' => 'warning',
                                                                'ghép' => 'info'
                                                            ];
                                                            $class = $loai_phong_class[$pp['loai_phong']] ?? 'dark';
                                                            ?>
                                                            <span class="badge bg-<?php echo $class; ?>">
                                                                <?php echo htmlspecialchars($pp['loai_phong']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                Nhận: <?php echo date('d/m', strtotime($pp['ngay_nhan_phong'])); ?>
                                                            </div>
                                                            <div class="small text-muted">
                                                                Trả: <?php echo date('d/m', strtotime($pp['ngay_tra_phong'])); ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-warning" 
                                                                        onclick="suaPhanPhong(<?php echo $pp['id']; ?>)"
                                                                        data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-danger" 
                                                                        onclick="xoaPhanPhong(<?php echo $pp['id']; ?>, '<?php echo htmlspecialchars($pp['ho_ten']); ?>')"
                                                                        data-bs-toggle="tooltip" title="Xóa">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Chưa có phân phòng</h5>
                                        <p class="text-muted">Bắt đầu phân phòng cho khách hàng</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Khách chưa phân phòng & Form thêm nhanh -->
                    <div class="col-md-4">
                        <!-- Khách chưa phân phòng -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-clock me-2"></i>
                                    Khách Chưa Phân Phòng (<?php echo count($khach_chua_phan_phong); ?>)
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($khach_chua_phan_phong)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($khach_chua_phan_phong as $khach): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold"><?php echo htmlspecialchars($khach['ho_ten']); ?></div>
                                                        <small class="text-muted">
                                                            <?php echo htmlspecialchars($khach['ma_dat_tour']); ?>
                                                        </small>
                                                    </div>
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="themPhanPhongNhanh(<?php echo $khach['id']; ?>)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                        <p class="text-muted mb-0">Đã phân phòng hết</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Form thêm nhanh -->
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Phân Phòng Nhanh
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="formThemNhanh" action="?act=phan-phong-them" method="POST">
                                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">
                                    <input type="hidden" name="thanh_vien_dat_tour_id" id="thanhVienIdNhanh">
                                    
                                    <div class="mb-2">
                                        <label class="form-label small">Khách sạn</label>
                                        <input type="text" name="ten_khach_san" class="form-control form-control-sm" required placeholder="Tên khách sạn...">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label class="form-label small">Số phòng</label>
                                                <input type="text" name="so_phong" class="form-control form-control-sm" required placeholder="Số phòng...">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label class="form-label small">Loại phòng</label>
                                                <select name="loai_phong" class="form-select form-select-sm" required>
                                                    <option value="đơn">Đơn</option>
                                                    <option value="đôi">Đôi</option>
                                                    <option value="giường phụ">Giường phụ</option>
                                                    <option value="ghép">Ghép</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label class="form-label small">Nhận phòng</label>
                                                <input type="date" name="ngay_nhan_phong" class="form-control form-control-sm" 
                                                       value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label class="form-label small">Trả phòng</label>
                                                <input type="date" name="ngay_tra_phong" class="form-control form-control-sm" 
                                                       value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small">Ghi chú</label>
                                        <textarea name="ghi_chu" class="form-control form-control-sm" rows="2"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-save me-1"></i> Lưu Phân Phòng
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Phân Phòng Hàng Loạt -->
<div class="modal fade" id="modalThemHangLoat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Phân Phòng Hàng Loạt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formHangLoat" action="?act=phan-phong-hang-loat" method="POST">
                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Chọn khách hàng</label>
                        <?php if (!empty($khach_chua_phan_phong)): ?>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($khach_chua_phan_phong as $khach): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="danh_sach_khach[]" 
                                               value="<?php echo $khach['id']; ?>" id="khach_<?php echo $khach['id']; ?>">
                                        <label class="form-check-label" for="khach_<?php echo $khach['id']; ?>">
                                            <?php echo htmlspecialchars($khach['ho_ten']); ?> - <?php echo htmlspecialchars($khach['ma_dat_tour']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="chonTatCa()">Chọn tất cả</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="boChonTatCa()">Bỏ chọn tất cả</button>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Không có khách nào chưa phân phòng</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Khách sạn</label>
                        <input type="text" name="ten_khach_san" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Số phòng</label>
                                <input type="text" name="so_phong" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Loại phòng</label>
                                <select name="loai_phong" class="form-select" required>
                                    <option value="đơn">Đơn</option>
                                    <option value="đôi">Đôi</option>
                                    <option value="giường phụ">Giường phụ</option>
                                    <option value="ghép">Ghép</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Nhận phòng</label>
                                <input type="date" name="ngay_nhan_phong" class="form-control" 
                                       value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Trả phòng</label>
                                <input type="date" name="ngay_tra_phong" class="form-control" 
                                       value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" form="formHangLoat" class="btn btn-primary">Phân Phòng Hàng Loạt</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh Sửa Phân Phòng -->
<div class="modal fade" id="modalSuaPhanPhong" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh Sửa Phân Phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalSuaBody">
                <!-- Nội dung sẽ được load bằng AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="modalXoa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn xóa phân phòng của <strong id="tenKhachXoa"></strong>?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="formXoa" method="POST" style="display: inline;">
                    <input type="hidden" name="id" id="idPhanPhongXoa">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
// Thêm phân phòng nhanh
function themPhanPhongNhanh(thanhVienId) {
    $('#thanhVienIdNhanh').val(thanhVienId);
    $('#formThemNhanh')[0].scrollIntoView({ behavior: 'smooth' });
    $('#formThemNhanh input[name="ten_khach_san"]').focus();
}

// Sửa phân phòng
function suaPhanPhong(id) {
    $.ajax({
        url: '?act=api-phan-phong&id=' + id,
        type: 'GET',
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#modalSuaBody').html(`
                        <form action="?act=phan-phong-cap-nhat" method="POST">
                            <input type="hidden" name="id" value="${result.data.id}">
                            
                            <div class="mb-3">
                                <label class="form-label">Khách sạn</label>
                                <input type="text" name="ten_khach_san" class="form-control" 
                                       value="${result.data.ten_khach_san}" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số phòng</label>
                                        <input type="text" name="so_phong" class="form-control" 
                                               value="${result.data.so_phong}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Loại phòng</label>
                                        <select name="loai_phong" class="form-select" required>
                                            <option value="đơn" ${result.data.loai_phong === 'đơn' ? 'selected' : ''}>Đơn</option>
                                            <option value="đôi" ${result.data.loai_phong === 'đôi' ? 'selected' : ''}>Đôi</option>
                                            <option value="giường phụ" ${result.data.loai_phong === 'giường phụ' ? 'selected' : ''}>Giường phụ</option>
                                            <option value="ghép" ${result.data.loai_phong === 'ghép' ? 'selected' : ''}>Ghép</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nhận phòng</label>
                                        <input type="date" name="ngay_nhan_phong" class="form-control" 
                                               value="${result.data.ngay_nhan_phong}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trả phòng</label>
                                        <input type="date" name="ngay_tra_phong" class="form-control" 
                                               value="${result.data.ngay_tra_phong}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3">${result.data.ghi_chu || ''}</textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            </div>
                        </form>
                    `);
                    $('#modalSuaPhanPhong').modal('show');
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (e) {
                console.error('Lỗi parse JSON:', e);
                alert('Lỗi hệ thống');
            }
        }
    });
}

// Xóa phân phòng
function xoaPhanPhong(id, ten) {
    $('#idPhanPhongXoa').val(id);
    $('#tenKhachXoa').text(ten);
    $('#formXoa').attr('action', '?act=phan-phong-xoa');
    $('#modalXoa').modal('show');
}

// Chọn tất cả trong phân phòng hàng loạt
function chonTatCa() {
    $('input[name="danh_sach_khach[]"]').prop('checked', true);
}

function boChonTatCa() {
    $('input[name="danh_sach_khach[]"]').prop('checked', false);
}

$(document).ready(function() {
    // Bật tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Hiển thị thông báo
    <?php if (isset($_SESSION['success'])): ?>
        toastr.success('<?php echo $_SESSION['success']; unset($_SESSION['success']); ?>');
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        toastr.error('<?php echo $_SESSION['error']; unset($_SESSION['error']); ?>');
    <?php endif; ?>
});
</script>

<style>
.list-group-item {
    border: none;
    border-bottom: 1px solid #eee;
    padding: 10px 15px;
}

.list-group-item:last-child {
    border-bottom: none;
}

.btn-group-sm > .btn {
    padding: 0.2rem 0.4rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>