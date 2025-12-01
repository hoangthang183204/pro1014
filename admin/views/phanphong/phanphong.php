<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <i class="fas fa-bed me-2"></i>
                        QUẢN LÝ ĐẶT PHÒNG KHÁCH SẠN
                    </a>
                    <div>
                        <span class="text-white me-3">
                            Tour: <strong><?php echo htmlspecialchars($lich_khoi_hanh['ten_tour'] ?? ''); ?></strong>
                        </span>
                        <a href="?act=danh-sach-khach-tour&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" 
                           class="btn btn-light me-2">
                            <i class="fas fa-users me-1"></i> Quản Lý Khách
                        </a>
                        <a href="?act=lich-khoi-hanh" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay Lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông tin tour -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body bg-light">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tour</small>
                                        <strong><?php echo htmlspecialchars($lich_khoi_hanh['ten_tour'] ?? ''); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day text-success me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Ngày đi</small>
                                        <strong><?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'] ?? '')); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-friends text-info me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Tổng khách</small>
                                        <span class="badge bg-primary fs-6"><?php echo $tong_so_khach; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Đã đặt phòng</small>
                                        <span class="badge bg-success fs-6">
                                            <?php echo is_countable($danh_sach_phan_phong) ? count($danh_sach_phan_phong) : 0; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Chưa đặt phòng</small>
                                        <span class="badge bg-warning fs-6">
                                            <?php echo is_countable($khach_chua_phan_phong) ? count($khach_chua_phan_phong) : 0; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê phân phòng -->
                <?php if (!empty($thong_ke) && is_array($thong_ke)): ?>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Thống Kê Loại Phòng</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($thong_ke as $stat): ?>
                                    <div class="col-md-2 mb-2">
                                        <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="card-body text-center p-3">
                                                <h4 class="mb-1"><?php echo $stat['so_luong']; ?></h4>
                                                <small class="opacity-75"><?php echo htmlspecialchars($stat['loai_phong']); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (isset($thong_ke[0]['tong_phong'])): ?>
                                    <div class="col-md-2 mb-2">
                                        <div class="card text-white bg-dark">
                                            <div class="card-body text-center p-3">
                                                <h4 class="mb-1"><?php echo $thong_ke[0]['tong_phong']; ?></h4>
                                                <small class="opacity-75">Tổng phòng</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Danh sách phân phòng -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    Danh Sách Đặt Phòng
                                </h5>
                                <div>
                                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalThemHangLoat">
                                        <i class="fas fa-layer-group me-1"></i> Đặt Nhiều Phòng
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="printRoomList()">
                                        <i class="fas fa-print me-1"></i> In Danh Sách
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($danh_sach_phan_phong) && is_array($danh_sach_phan_phong)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50" class="text-center">#</th>
                                                    <th>Thông Tin Phòng</th>
                                                    <th>Thông Tin Khách</th>
                                                    <th width="120">Loại Phòng</th>
                                                    <th width="150">Thời Gian</th>
                                                    <th width="100" class="text-center">Thao Tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($danh_sach_phan_phong as $index => $pp): ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $index + 1; ?></td>
                                                        <td class="align-middle">
                                                            <div class="fw-bold text-primary">
                                                                <i class="fas fa-hotel me-1"></i>
                                                                <?php echo htmlspecialchars($pp['ten_khach_san']); ?>
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-door-closed me-1"></i>
                                                                Phòng: <strong><?php echo htmlspecialchars($pp['so_phong']); ?></strong>
                                                            </div>
                                                            <?php if ($pp['ghi_chu']): ?>
                                                                <div class="text-muted small mt-1">
                                                                    <i class="fas fa-sticky-note me-1"></i>
                                                                    <?php echo htmlspecialchars($pp['ghi_chu']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="fw-bold">
                                                                <i class="fas fa-user me-1"></i>
                                                                <?php echo htmlspecialchars($pp['ho_ten']); ?>
                                                            </div>
                                                            <div class="text-muted small">
                                                                <?php if ($pp['cccd']): ?>
                                                                    <div><i class="fas fa-id-card me-1"></i> <?php echo htmlspecialchars($pp['cccd']); ?></div>
                                                                <?php endif; ?>
                                                                <?php if ($pp['so_dien_thoai']): ?>
                                                                    <div><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($pp['so_dien_thoai']); ?></div>
                                                                <?php endif; ?>
                                                                <?php if ($pp['ma_dat_tour']): ?>
                                                                    <div><i class="fas fa-ticket-alt me-1"></i> <?php echo htmlspecialchars($pp['ma_dat_tour']); ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <?php
                                                            $loai_phong_class = [
                                                                'đơn' => 'bg-info',
                                                                'đôi' => 'bg-primary',
                                                                'giường phụ' => 'bg-warning',
                                                                'ghép' => 'bg-secondary'
                                                            ];
                                                            $class = $loai_phong_class[$pp['loai_phong']] ?? 'bg-dark';
                                                            ?>
                                                            <span class="badge <?php echo $class; ?> py-2 px-3">
                                                                <i class="fas fa-bed me-1"></i>
                                                                <?php echo htmlspecialchars($pp['loai_phong']); ?>
                                                            </span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="small">
                                                                <i class="fas fa-sign-in-alt text-success me-1"></i>
                                                                <?php echo date('d/m/Y', strtotime($pp['ngay_nhan_phong'])); ?>
                                                            </div>
                                                            <div class="small text-muted">
                                                                <i class="fas fa-sign-out-alt text-danger me-1"></i>
                                                                <?php echo date('d/m/Y', strtotime($pp['ngay_tra_phong'])); ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" 
                                                                        onclick="suaPhanPhong(<?php echo $pp['id']; ?>)"
                                                                        data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-danger" 
                                                                        onclick="xoaPhanPhong(<?php echo $pp['id']; ?>, '<?php echo htmlspecialchars(addslashes($pp['ho_ten'])); ?>')"
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
                                    <div class="text-center py-5">
                                        <i class="fas fa-hotel fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Chưa có đặt phòng nào</h5>
                                        <p class="text-muted">Bắt đầu đặt phòng cho khách hàng bằng form bên cạnh</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Khách chưa đặt phòng & Form thêm nhanh -->
                    <div class="col-md-4">
                        <!-- Khách chưa đặt phòng -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-clock me-2"></i>
                                    Khách Chưa Đặt Phòng
                                    <span class="badge bg-light text-dark float-end"><?php echo is_countable($khach_chua_phan_phong) ? count($khach_chua_phan_phong) : 0; ?></span>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($khach_chua_phan_phong) && is_array($khach_chua_phan_phong)): ?>
                                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($khach_chua_phan_phong as $khach): ?>
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($khach['ho_ten']); ?></div>
                                                        <small class="text-muted">
                                                            <?php if ($khach['so_dien_thoai']): ?>
                                                                <div><i class="fas fa-phone fa-xs me-1"></i> <?php echo htmlspecialchars($khach['so_dien_thoai']); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ($khach['ma_dat_tour']): ?>
                                                                <div><i class="fas fa-ticket-alt fa-xs me-1"></i> <?php echo htmlspecialchars($khach['ma_dat_tour']); ?></div>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                    <button class="btn btn-primary btn-sm ms-2" 
                                                            onclick="themPhanPhongNhanh(<?php echo $khach['id']; ?>, '<?php echo htmlspecialchars(addslashes($khach['ho_ten'])); ?>')"
                                                            data-bs-toggle="tooltip" title="Đặt phòng cho khách này">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                        <p class="text-muted mb-0">Tất cả khách đã được đặt phòng</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Form đặt phòng nhanh -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Đặt Phòng Nhanh
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="formThemNhanh" action="?act=phan-phong-create" method="POST">
                                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">
                                    <input type="hidden" name="khach_hang_id" id="khachHangIdNhanh">
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Khách sạn <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="ten_khach_san" class="form-control" required 
                                                   placeholder="Nhập tên khách sạn..." list="khachSanList" id="tenKhachSanInput">
                                            <datalist id="khachSanList">
                                                <?php foreach ($danh_sach_khach_san as $ks): ?>
                                                    <option value="<?php echo htmlspecialchars($ks['ten_khach_san']); ?>">
                                                <?php endforeach; ?>
                                            </datalist>
                                            <button class="btn btn-outline-secondary" type="button" onclick="clearHotelInput()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Số phòng <span class="text-danger">*</span></label>
                                            <input type="text" name="so_phong" class="form-control form-control-sm" required 
                                                   placeholder="Ví dụ: 101, A12...">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Loại phòng</label>
                                            <select name="loai_phong" class="form-select form-select-sm" required>
                                                <option value="đơn">Phòng đơn</option>
                                                <option value="đôi" selected>Phòng đôi</option>
                                                <option value="giường phụ">Giường phụ</option>
                                                <option value="ghép">Ghép phòng</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Nhận phòng</label>
                                            <input type="date" name="ngay_nhan_phong" class="form-control form-control-sm" 
                                                   value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold">Trả phòng</label>
                                            <input type="date" name="ngay_tra_phong" class="form-control form-control-sm" 
                                                   value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Ghi chú</label>
                                        <textarea name="ghi_chu" class="form-control form-control-sm" rows="2" 
                                                  placeholder="Yêu cầu đặc biệt, dị ứng..."></textarea>
                                    </div>
                                    
                                    <div class="alert alert-info py-2 mb-3" id="selectedGuestInfo" style="display: none;">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            Đang đặt phòng cho: <strong id="selectedGuestName"></strong>
                                            <button type="button" class="btn btn-sm btn-link p-0 ms-2" onclick="clearSelectedGuest()">
                                                <i class="fas fa-times"></i> Bỏ chọn
                                            </button>
                                        </small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100" id="btnLuuPhong">
                                        <i class="fas fa-save me-1"></i> Lưu Đặt Phòng
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

<!-- Modal Đặt Nhiều Phòng -->
<div class="modal fade" id="modalThemHangLoat">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-layer-group me-2"></i>
                    Đặt Nhiều Phòng Cùng Lúc
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="?act=phan-phong-hang-loat" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Khách sạn <span class="text-danger">*</span></label>
                            <input type="text" name="ten_khach_san" class="form-control" required 
                                   placeholder="Tên khách sạn..." list="khachSanListModal">
                            <datalist id="khachSanListModal">
                                <?php foreach ($danh_sach_khach_san as $ks): ?>
                                    <option value="<?php echo htmlspecialchars($ks['ten_khach_san']); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Số phòng <span class="text-danger">*</span></label>
                            <input type="text" name="so_phong" class="form-control" required placeholder="Ví dụ: 101">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Loại phòng</label>
                            <select name="loai_phong" class="form-select" required>
                                <option value="đôi" selected>Phòng đôi</option>
                                <option value="đơn">Phòng đơn</option>
                                <option value="giường phụ">Giường phụ</option>
                                <option value="ghép">Ghép phòng</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nhận phòng</label>
                            <input type="date" name="ngay_nhan_phong" class="form-control" 
                                   value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trả phòng</label>
                            <input type="date" name="ngay_tra_phong" class="form-control" 
                                   value="<?php echo date('Y-m-d', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú (dùng chung)</label>
                        <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú chung cho tất cả phòng..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn khách hàng <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                            <?php if (!empty($khach_chua_phan_phong) && is_array($khach_chua_phan_phong)): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Tổng: <?php echo count($khach_chua_phan_phong); ?> khách chưa đặt phòng</small>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleSelectAll(true)">
                                            <i class="fas fa-check-double me-1"></i> Chọn tất cả
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleSelectAll(false)">
                                            <i class="fas fa-times me-1"></i> Bỏ chọn
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php foreach ($khach_chua_phan_phong as $khach): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="danh_sach_khach[]" 
                                                       value="<?php echo $khach['id']; ?>" id="khach_<?php echo $khach['id']; ?>">
                                                <label class="form-check-label" for="khach_<?php echo $khach['id']; ?>">
                                                    <?php echo htmlspecialchars($khach['ho_ten']); ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars($khach['ma_dat_tour'] ?? 'N/A'); ?>
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users-slash fa-2x mb-3"></i>
                                    <p class="mb-0">Không có khách chưa đặt phòng</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Đặt Phòng Cho Khách Đã Chọn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Đặt Phòng -->
<div class="modal fade" id="modalSuaPhanPhong">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Sửa Thông Tin Đặt Phòng
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSuaPhanPhong" action="?act=phan-phong-update" method="POST">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Khách sạn <span class="text-danger">*</span></label>
                        <input type="text" name="ten_khach_san" id="editTenKhachSan" class="form-control" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Số phòng <span class="text-danger">*</span></label>
                            <input type="text" name="so_phong" id="editSoPhong" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Loại phòng</label>
                            <select name="loai_phong" id="editLoaiPhong" class="form-select" required>
                                <option value="đơn">Phòng đơn</option>
                                <option value="đôi">Phòng đôi</option>
                                <option value="giường phụ">Giường phụ</option>
                                <option value="ghép">Ghép phòng</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Nhận phòng</label>
                            <input type="date" name="ngay_nhan_phong" id="editNgayNhanPhong" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Trả phòng</label>
                            <input type="date" name="ngay_tra_phong" id="editNgayTraPhong" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" id="editGhiChu" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Đặt Phòng -->
<div class="modal fade" id="modalXoaPhanPhong">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash me-2"></i>
                    Xóa Đặt Phòng
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formXoaPhanPhong" action="?act=phan-phong-delete" method="POST">
                <input type="hidden" name="id" id="deleteId">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Xác nhận xóa đặt phòng</h5>
                        <p class="text-muted">Bạn có chắc chắn muốn xóa đặt phòng cho khách hàng:</p>
                        <h6 class="text-danger" id="deleteTenKhach"></h6>
                        <p class="text-muted mt-3"><small>Hành động này không thể hoàn tác</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Xác Nhận Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Khởi tạo tooltip
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Tự động lấy thông tin phòng khi chọn khách sạn
    document.getElementById('tenKhachSanInput').addEventListener('change', function() {
        const lich_khoi_hanh_id = <?php echo $lich_khoi_hanh['id']; ?>;
        const ten_khach_san = this.value;
        
        if (ten_khach_san) {
            fetch(`?act=phan-phong-api-phong&lich_khoi_hanh_id=${lich_khoi_hanh_id}&ten_khach_san=${encodeURIComponent(ten_khach_san)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        showToast('info', `Khách sạn này đã có ${data.data.length} phòng được đặt`);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
});

// Xử lý thêm đặt phòng nhanh
function themPhanPhongNhanh(khachId, tenKhach) {
    document.getElementById('khachHangIdNhanh').value = khachId;
    document.getElementById('selectedGuestName').textContent = tenKhach;
    document.getElementById('selectedGuestInfo').style.display = 'block';
    
    // Scroll đến form
    document.getElementById('formThemNhanh').scrollIntoView({ behavior: 'smooth' });
    
    // Focus vào input khách sạn
    document.querySelector('#formThemNhanh input[name="ten_khach_san"]').focus();
    
    showToast('success', `Đã chọn khách: ${tenKhach}. Vui lòng điền thông tin phòng và bấm "Lưu Đặt Phòng"`);
}

// Xử lý sửa đặt phòng
function suaPhanPhong(id) {
    fetch(`?act=phan-phong-api&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pp = data.data;
                document.getElementById('editId').value = pp.id;
                document.getElementById('editTenKhachSan').value = pp.ten_khach_san;
                document.getElementById('editSoPhong').value = pp.so_phong;
                document.getElementById('editLoaiPhong').value = pp.loai_phong;
                document.getElementById('editNgayNhanPhong').value = pp.ngay_nhan_phong;
                document.getElementById('editNgayTraPhong').value = pp.ngay_tra_phong;
                document.getElementById('editGhiChu').value = pp.ghi_chu || '';
                
                const modal = new bootstrap.Modal(document.getElementById('modalSuaPhanPhong'));
                modal.show();
            } else {
                showToast('error', data.message || 'Lỗi khi lấy dữ liệu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Lỗi kết nối server');
        });
}

// Xử lý xóa đặt phòng
function xoaPhanPhong(id, tenKhach) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteTenKhach').textContent = tenKhach;
    
    const modal = new bootstrap.Modal(document.getElementById('modalXoaPhanPhong'));
    modal.show();
}

// Chọn tất cả/bỏ chọn tất cả
function toggleSelectAll(checked) {
    const checkboxes = document.querySelectorAll('#modalThemHangLoat input[name="danh_sach_khach[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
    
    const count = checked ? checkboxes.length : 0;
    showToast('info', checked ? `Đã chọn ${count} khách hàng` : 'Đã bỏ chọn tất cả');
}

// Xóa input khách sạn
function clearHotelInput() {
    document.getElementById('tenKhachSanInput').value = '';
    document.getElementById('tenKhachSanInput').focus();
}

// Bỏ chọn khách hàng
function clearSelectedGuest() {
    document.getElementById('khachHangIdNhanh').value = '';
    document.getElementById('selectedGuestInfo').style.display = 'none';
    showToast('info', 'Đã bỏ chọn khách hàng');
}

// In danh sách phòng
function printRoomList() {
    const printWindow = window.open('', '_blank');
    const title = "<?php echo htmlspecialchars($lich_khoi_hanh['ten_tour']); ?>";
    const ngayDi = "<?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>";
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Danh Sách Phòng - ${title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .header h1 { margin: 0; color: #2c3e50; }
                .header .info { color: #666; margin-top: 5px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; text-align: left; }
                td { border: 1px solid #dee2e6; padding: 8px; }
                .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
                .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
                .badge-single { background: #17a2b8; color: white; }
                .badge-double { background: #007bff; color: white; }
                .badge-extra { background: #ffc107; color: #212529; }
                .badge-share { background: #6c757d; color: white; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>DANH SÁCH ĐẶT PHÒNG KHÁCH SẠN</h1>
                <div class="info">
                    <strong>Tour:</strong> ${title} | 
                    <strong>Ngày đi:</strong> ${ngayDi} | 
                    <strong>Ngày in:</strong> ${new Date().toLocaleDateString('vi-VN')}
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Khách Sạn & Phòng</th>
                        <th>Khách Hàng</th>
                        <th width="100">Loại Phòng</th>
                        <th width="120">Thời Gian</th>
                        <th width="80">Ghi Chú</th>
                    </tr>
                </thead>
                <tbody>
    `);
    
    <?php if (!empty($danh_sach_phan_phong) && is_array($danh_sach_phan_phong)): ?>
        <?php foreach ($danh_sach_phan_phong as $index => $pp): ?>
            printWindow.document.write(`
                <tr>
                    <td>${<?php echo $index + 1; ?>}</td>
                    <td>
                        <strong>${<?php echo json_encode($pp['ten_khach_san']); ?>}</strong><br>
                        <small>Phòng: ${<?php echo json_encode($pp['so_phong']); ?>}</small>
                    </td>
                    <td>
                        <strong>${<?php echo json_encode($pp['ho_ten']); ?>}</strong><br>
                        <small>CCCD: ${<?php echo json_encode($pp['cccd'] ?? ''); ?>}</small><br>
                        <small>ĐT: ${<?php echo json_encode($pp['so_dien_thoai'] ?? ''); ?>}</small>
                    </td>
                    <td>
                        <span class="badge badge-${<?php echo json_encode($pp['loai_phong']); ?>}">
                            ${<?php echo json_encode($pp['loai_phong']); ?>}
                        </span>
                    </td>
                    <td>
                        Nhận: ${<?php echo json_encode(date('d/m/Y', strtotime($pp['ngay_nhan_phong']))); ?>}<br>
                        Trả: ${<?php echo json_encode(date('d/m/Y', strtotime($pp['ngay_tra_phong']))); ?>}
                    </td>
                    <td>${<?php echo json_encode($pp['ghi_chu'] ?? ''); ?>}</td>
                </tr>
            `);
        <?php endforeach; ?>
    <?php endif; ?>
    
    printWindow.document.write(`
                </tbody>
            </table>
            <div class="footer">
                <p>Tổng số phòng: ${<?php echo is_countable($danh_sach_phan_phong) ? count($danh_sach_phan_phong) : 0; ?>} | 
                In bởi: ${<?php echo json_encode($_SESSION['user_name'] ?? 'System'); ?>} | 
                ${new Date().toLocaleString('vi-VN')}</p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); }, 500);
}

// Toast notification
function showToast(type, message) {
    // Tạo toast container nếu chưa có
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Tạo toast mới
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    // Icon theo type
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'info': 'info-circle',
        'warning': 'exclamation-triangle'
    };
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${icons[type] || 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    // Xóa toast sau khi ẩn
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
}

// Validate form đặt phòng nhanh
document.getElementById('formThemNhanh').addEventListener('submit', function(e) {
    const khachHangId = document.getElementById('khachHangIdNhanh').value;
    const tenKhachSan = document.querySelector('input[name="ten_khach_san"]').value;
    const soPhong = document.querySelector('input[name="so_phong"]').value;
    
    if (!khachHangId || khachHangId === '') {
        e.preventDefault();
        showToast('error', 'Vui lòng chọn khách hàng từ danh sách "Khách Chưa Đặt Phòng"');
        return false;
    }
    
    if (!tenKhachSan.trim() || !soPhong.trim()) {
        e.preventDefault();
        showToast('error', 'Vui lòng nhập đầy đủ thông tin khách sạn và số phòng');
        return false;
    }
    
    // Đổi text button để người dùng biết đang xử lý
    const btn = document.getElementById('btnLuuPhong');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
    btn.disabled = true;
    
    // Sau 3 giây khôi phục button nếu vẫn chưa chuyển trang
    setTimeout(() => {
        if (btn.disabled) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            showToast('warning', 'Yêu cầu đang xử lý, vui lòng chờ...');
        }
    }, 3000);
});

// Hiển thị thông báo từ session
<?php if (isset($_SESSION['success'])): ?>
    showToast('success', '<?php echo $_SESSION['success']; ?>');
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    showToast('error', '<?php echo $_SESSION['error']; ?>');
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

// Kiểm tra nếu form bị submit không thành công
if (window.location.search.includes('error=')) {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    if (error) {
        showToast('error', decodeURIComponent(error));
    }
}
</script>

<style>
.content-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 56px);
}

.card {
    border-radius: 10px;
    border: none;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    padding: 1rem 1.25rem;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.75rem 1.25rem;
    transition: background-color 0.2s;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}

.form-control, .form-select {
    border: 1px solid #ced4da;
    border-radius: 5px;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 5px;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.modal-content {
    border-radius: 10px;
    border: none;
}

.toast-container {
    z-index: 9999;
}

#selectedGuestInfo {
    transition: all 0.3s ease;
}

#btnLuuPhong:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

@media print {
    .no-print {
        display: none !important;
    }
}
</style>

<?php require './views/layout/footer.php'; ?>