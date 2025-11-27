<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-user me-2"></i>
                        Chi Tiết Thành Viên Tour
                    </a>
                    <a href="?act=thanh-vien-tour" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Thông báo -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <?php if ($thong_tin_thanh_vien): ?>
                <div class="row">
                    <!-- Thông tin thành viên -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Thông tin Thành viên</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?act=thanh-vien-tour-cap-nhat">
                                    <input type="hidden" name="id" value="<?php echo $thong_tin_thanh_vien['id']; ?>">
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Họ tên:</th>
                                            <td>
                                                <input type="text" name="ho_ten" class="form-control form-control-sm" 
                                                       value="<?php echo htmlspecialchars($thong_tin_thanh_vien['ho_ten']); ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>CCCD/CMND:</th>
                                            <td>
                                                <input type="text" name="cccd" class="form-control form-control-sm" 
                                                       value="<?php echo htmlspecialchars($thong_tin_thanh_vien['cccd'] ?? ''); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ngày sinh:</th>
                                            <td>
                                                <input type="date" name="ngay_sinh" class="form-control form-control-sm" 
                                                       value="<?php echo $thong_tin_thanh_vien['ngay_sinh'] ?? ''; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Giới tính:</th>
                                            <td>
                                                <select name="gioi_tinh" class="form-select form-select-sm">
                                                    <option value="">Chọn giới tính</option>
                                                    <option value="nam" <?php echo ($thong_tin_thanh_vien['gioi_tinh'] ?? '') === 'nam' ? 'selected' : ''; ?>>Nam</option>
                                                    <option value="nữ" <?php echo ($thong_tin_thanh_vien['gioi_tinh'] ?? '') === 'nữ' ? 'selected' : ''; ?>>Nữ</option>
                                                    <option value="khác" <?php echo ($thong_tin_thanh_vien['gioi_tinh'] ?? '') === 'khác' ? 'selected' : ''; ?>>Khác</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Yêu cầu đặc biệt:</th>
                                            <td>
                                                <textarea name="yeu_cau_dac_biet" class="form-control form-control-sm" rows="3"
                                                          placeholder="Nhập yêu cầu đặc biệt (nếu có)"><?php echo htmlspecialchars($thong_tin_thanh_vien['yeu_cau_dac_biet'] ?? ''); ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save me-1"></i> Cập nhật
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Xử lý yêu cầu đặc biệt -->
                        <?php if (!empty($thong_tin_thanh_vien['yeu_cau_dac_biet']) && !$thong_tin_thanh_vien['da_xu_ly_yeu_cau']): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Xử lý Yêu cầu Đặc biệt</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?act=thanh-vien-tour-xu-ly-yeu-cau">
                                    <input type="hidden" name="id" value="<?php echo $thong_tin_thanh_vien['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Yêu cầu:</strong></label>
                                        <p class="text-muted"><?php echo htmlspecialchars($thong_tin_thanh_vien['yeu_cau_dac_biet']); ?></p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Ghi chú xử lý:</strong></label>
                                        <textarea name="ghi_chu_xu_ly" class="form-control" rows="3" 
                                                  placeholder="Nhập ghi chú về việc xử lý yêu cầu..." required></textarea>
                                    </div>
                                    
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Đánh dấu đã xử lý
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php elseif (!empty($thong_tin_thanh_vien['yeu_cau_dac_biet']) && $thong_tin_thanh_vien['da_xu_ly_yeu_cau']): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Yêu cầu Đã Xử lý</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Yêu cầu:</strong></label>
                                    <p class="text-muted"><?php echo htmlspecialchars($thong_tin_thanh_vien['yeu_cau_dac_biet']); ?></p>
                                </div>
                                
                                <?php if (!empty($thong_tin_thanh_vien['ghi_chu_xu_ly'])): ?>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Ghi chú xử lý:</strong></label>
                                    <p class="text-muted"><?php echo htmlspecialchars($thong_tin_thanh_vien['ghi_chu_xu_ly']); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thông tin tour và khách hàng -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-suitcase me-2"></i>Thông tin Tour</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Tour:</th>
                                        <td><strong class="text-success"><?php echo htmlspecialchars($thong_tin_thanh_vien['ten_tour']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Mã tour:</th>
                                        <td><?php echo htmlspecialchars($thong_tin_thanh_vien['ma_tour']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mã đặt tour:</th>
                                        <td>
                                            <span class="badge bg-dark"><?php echo $thong_tin_thanh_vien['ma_dat_tour']; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Thời gian:</th>
                                        <td>
                                            <i class="fas fa-calendar-alt text-muted me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($thong_tin_thanh_vien['ngay_bat_dau'])); ?> - 
                                            <?php echo date('d/m/Y', strtotime($thong_tin_thanh_vien['ngay_ket_thuc'])); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Điểm tập trung:</th>
                                        <td>
                                            <i class="fas fa-map-pin text-muted me-2"></i>
                                            <?php echo htmlspecialchars($thong_tin_thanh_vien['diem_tap_trung']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Hướng dẫn viên:</th>
                                        <td>
                                            <?php if ($thong_tin_thanh_vien['ten_huong_dan_vien']): ?>
                                                <i class="fas fa-user-check text-muted me-2"></i>
                                                <strong><?php echo htmlspecialchars($thong_tin_thanh_vien['ten_huong_dan_vien']); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa phân công</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            <?php
                                            $trang_thai_class = [
                                                'chờ xác nhận' => 'warning',
                                                'đã cọc' => 'info',
                                                'hoàn tất' => 'success',
                                                'hủy' => 'danger'
                                            ];
                                            $class = $trang_thai_class[$thong_tin_thanh_vien['trang_thai_dat_tour']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $class; ?>">
                                                <?php echo $thong_tin_thanh_vien['trang_thai_dat_tour']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Thông tin Khách hàng Đặt</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Khách hàng:</th>
                                        <td><strong><?php echo htmlspecialchars($thong_tin_thanh_vien['ten_khach_hang']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Số điện thoại:</th>
                                        <td>
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <?php echo htmlspecialchars($thong_tin_thanh_vien['sdt_khach_hang']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>
                                            <?php if ($thong_tin_thanh_vien['email_khach_hang']): ?>
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                <?php echo htmlspecialchars($thong_tin_thanh_vien['email_khach_hang']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tổng tiền:</th>
                                        <td>
                                            <strong class="text-success"><?php echo number_format($thong_tin_thanh_vien['tong_tien'], 0, ',', '.'); ?> VNĐ</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h5>Không tìm thấy thông tin thành viên</h5>
                        <p class="mb-0">Thành viên không tồn tại hoặc đã bị xóa</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    .table-borderless th {
        width: 40%;
        font-weight: 600;
        color: #495057;
    }

    .table-borderless td {
        color: #6c757d;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .form-control-sm, .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .card .card-body {
        padding: 1.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .row .col-md-6 {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .btn-secondary {
            font-size: 0.875rem;
            padding: 6px 12px;
        }

        .card-body {
            padding: 1rem;
        }

        .table-borderless th,
        .table-borderless td {
            display: block;
            width: 100%;
            padding: 0.5rem 0;
        }

        .table-borderless tr {
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 0;
        }
    }
</style>