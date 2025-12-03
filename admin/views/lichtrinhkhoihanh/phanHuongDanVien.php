<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-user-tie me-2"></i>
                        Phân Công Hướng Dẫn Viên
                    </a>
                    <div>
                        <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <!-- Hiển thị thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông tin lịch khởi hành -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lịch khởi hành</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour'] . ' - ' . $lich_khoi_hanh['ten_tour']); ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Thời gian:</strong>
                                <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>
                                - <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-<?php
                                                        echo match ($lich_khoi_hanh['trang_thai']) {
                                                            'đã lên lịch' => 'success',
                                                            'đang diễn ra' => 'warning',
                                                            'đã hoàn thành' => 'primary',
                                                            'đã hủy' => 'danger',
                                                            default => 'secondary'
                                                        };
                                                        ?>">
                                    <?php echo htmlspecialchars($lich_khoi_hanh['trang_thai']); ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($lich_khoi_hanh['diem_tap_trung']): ?>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['diem_tap_trung']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lich_khoi_hanh['ghi_chu_van_hanh']): ?>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Ghi chú vận hành:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ghi_chu_van_hanh']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form phân công HDV -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Phân công hướng dẫn viên</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cảnh báo tour đã hoàn thành -->
                        <?php if ($lich_khoi_hanh['trang_thai'] === 'đã hoàn thành'): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Tour đã hoàn thành!</strong> Không thể phân công hoặc hủy phân công HDV cho tour này.
                            </div>
                        <?php endif; ?>

                        <!-- Hiển thị HDV hiện tại -->
                        <!-- Hiển thị HDV hiện tại -->
                        <?php if (isset($phan_cong_hien_tai) && $phan_cong_hien_tai): ?>
                            <div class="alert alert-info mb-4">
                                <h6><i class="fas fa-user-check me-2"></i>HDV hiện tại:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><?php echo htmlspecialchars($phan_cong_hien_tai['ho_ten'] ?? 'N/A'); ?></strong></p>
                                        <?php if (isset($phan_cong_hien_tai['so_dien_thoai']) && $phan_cong_hien_tai['so_dien_thoai']): ?>
                                            <p class="mb-1"><small>Điện thoại: <?php echo htmlspecialchars($phan_cong_hien_tai['so_dien_thoai']); ?></small></p>
                                        <?php endif; ?>
                                        <?php if (isset($phan_cong_hien_tai['email']) && $phan_cong_hien_tai['email']): ?>
                                            <p class="mb-1"><small>Email: <?php echo htmlspecialchars($phan_cong_hien_tai['email']); ?></small></p>
                                        <?php endif; ?>
                                        <?php if (isset($phan_cong_hien_tai['loai_huong_dan_vien']) && $phan_cong_hien_tai['loai_huong_dan_vien']): ?>
                                            <p class="mb-1"><small>Loại HDV: <?php echo htmlspecialchars($phan_cong_hien_tai['loai_huong_dan_vien']); ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (isset($phan_cong_hien_tai['ngon_ngu']) && $phan_cong_hien_tai['ngon_ngu']): ?>
                                            <p class="mb-1"><small>Ngôn ngữ:
                                                    <?php
                                                    $ngon_ngu = is_string($phan_cong_hien_tai['ngon_ngu']) ?
                                                        json_decode($phan_cong_hien_tai['ngon_ngu'], true) :
                                                        $phan_cong_hien_tai['ngon_ngu'];
                                                    echo is_array($ngon_ngu) ? htmlspecialchars(implode(', ', $ngon_ngu)) : htmlspecialchars($phan_cong_hien_tai['ngon_ngu']);
                                                    ?>
                                                </small></p>
                                        <?php endif; ?>
                                        <?php if (isset($phan_cong_hien_tai['chuyen_mon']) && $phan_cong_hien_tai['chuyen_mon']): ?>
                                            <p class="mb-1"><small>Chuyên môn: <?php echo htmlspecialchars($phan_cong_hien_tai['chuyen_mon']); ?></small></p>
                                        <?php endif; ?>
                                        <?php if (isset($phan_cong_hien_tai['kinh_nghiem']) && $phan_cong_hien_tai['kinh_nghiem']): ?>
                                            <p class="mb-1"><small>Kinh nghiệm: <?php echo htmlspecialchars($phan_cong_hien_tai['kinh_nghiem']); ?></small></p>
                                        <?php endif; ?>
                                        <?php if (isset($phan_cong_hien_tai['ghi_chu']) && $phan_cong_hien_tai['ghi_chu']): ?>
                                            <p class="mb-1"><small>Ghi chú: <?php echo htmlspecialchars($phan_cong_hien_tai['ghi_chu']); ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($lich_khoi_hanh['trang_thai'] !== 'đã hoàn thành'): ?>
                                    <a href="?act=huy-phan-cong&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>"
                                        class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="return confirm('Bạn có chắc muốn hủy phân công HDV này?')">
                                        <i class="fas fa-times me-1"></i> Hủy phân công
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Chưa có HDV nào được phân công cho lịch này.
                            </div>
                        <?php endif; ?>

                        <!-- Form phân công -->
                        <?php if (empty($huong_dan_vien_list) && !isset($phan_cong_hien_tai)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Không có HDV khả dụng!</strong> Tất cả HDV đều bận trong khoảng thời gian này hoặc không có HDV đang làm việc.
                            </div>
                        <?php elseif ($lich_khoi_hanh['trang_thai'] !== 'đã hoàn thành'): ?>
                            <form method="POST" action="?act=phan-cong-store">
                                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                                <div class="mb-3">
                                    <label class="form-label">Chọn hướng dẫn viên <span class="text-danger">*</span></label>
                                    <select name="huong_dan_vien_id" class="form-select" required>
                                        <option value="">-- Chọn HDV --</option>
                                        <?php foreach ($huong_dan_vien_list as $hdv): ?>
                                            <?php
                                            $selected = (isset($phan_cong_hien_tai) && $phan_cong_hien_tai && $phan_cong_hien_tai['huong_dan_vien_id'] == $hdv['id']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $hdv['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                                                <?php if (isset($hdv['so_dien_thoai']) && $hdv['so_dien_thoai']): ?>
                                                    - <?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>
                                                <?php endif; ?>
                                                <?php if (isset($hdv['chuyen_mon']) && $hdv['chuyen_mon']): ?>
                                                    - <?php echo htmlspecialchars($hdv['chuyen_mon']); ?>
                                                <?php endif; ?>
                                                <?php if (isset($hdv['ngon_ngu']) && $hdv['ngon_ngu']): ?>
                                                    (<?php
                                                        $ngon_ngu = is_string($hdv['ngon_ngu']) ?
                                                            json_decode($hdv['ngon_ngu'], true) :
                                                            $hdv['ngon_ngu'];
                                                        echo is_array($ngon_ngu) ? htmlspecialchars(implode(', ', $ngon_ngu)) : htmlspecialchars($hdv['ngon_ngu']);
                                                        ?>)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">
                                        Chỉ hiển thị những HDV có lịch trống trong khoảng thời gian từ
                                        <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>
                                        đến <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ghi chú phân công</label>
                                    <textarea name="ghi_chu" class="form-control" rows="3"
                                        placeholder="Ghi chú đặc biệt cho HDV, yêu cầu cụ thể, thông tin liên hệ..."><?php echo isset($phan_cong_hien_tai['ghi_chu']) ? htmlspecialchars($phan_cong_hien_tai['ghi_chu']) : ''; ?></textarea>
                                    <small class="text-muted">
                                        Ghi chú sẽ được hiển thị cho HDV khi nhận phân công
                                    </small>
                                </div>

                                <!-- <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Thông tin phân công:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Chỉ những HDV có lịch trống mới được hiển thị</li>
                                        <li>HDV sẽ nhận được thông báo về phân công mới</li>
                                        <li>Không thể phân công HDV cho tour đã hoàn thành</li>
                                        <li>Có thể thay đổi HDV bất kỳ lúc nào trước khi tour hoàn thành</li>
                                        <li>Hệ thống tự động kiểm tra trùng lịch khi phân công</li>
                                    </ul>
                                </div> -->

                                <div class="d-flex justify-content-between">
                                    <a href="?act=lich-khoi-hanh" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Quay lại
                                    </a>
                                    <?php if (!empty($huong_dan_vien_list)): ?>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i>
                                            <?php echo (isset($phan_cong_hien_tai) && $phan_cong_hien_tai) ? 'Cập nhật phân công' : 'Phân công HDV'; ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-success" disabled>
                                            <i class="fas fa-ban me-1"></i> Không có HDV khả dụng
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Thông tin về các HDV bận -->
                <?php if (isset($all_hdv) && !empty($all_hdv) && isset($huong_dan_vien_list)): ?>
                    <?php
                    $available_hdv_ids = array_column($huong_dan_vien_list, 'id');
                    $hdv_busy = array_filter($all_hdv, function ($hdv) use ($available_hdv_ids) {
                        return !in_array($hdv['id'], $available_hdv_ids);
                    });
                    ?>

                    <?php if (!empty($hdv_busy) && !isset($phan_cong_hien_tai) && $lich_khoi_hanh['trang_thai'] !== 'đã hoàn thành'): ?>
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user-clock me-2"></i>HDV đang bận trong khoảng thời gian này</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Họ tên</th>
                                                <th>Điện thoại</th>
                                                <th>Ngôn ngữ</th>
                                                <th>Chuyên môn</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($hdv_busy as $hdv): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($hdv['ho_ten'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($hdv['so_dien_thoai'] ?? 'N/A'); ?></td>
                                                    <td>
                                                        <?php
                                                        if (isset($hdv['ngon_ngu']) && $hdv['ngon_ngu']) {
                                                            $ngon_ngu = is_string($hdv['ngon_ngu']) ?
                                                                json_decode($hdv['ngon_ngu'], true) :
                                                                $hdv['ngon_ngu'];
                                                            echo is_array($ngon_ngu) ? htmlspecialchars(implode(', ', $ngon_ngu)) : htmlspecialchars($hdv['ngon_ngu']);
                                                        } else {
                                                            echo '---';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo (isset($hdv['chuyen_mon']) && $hdv['chuyen_mon']) ? htmlspecialchars($hdv['chuyen_mon']) : '---'; ?></td>
                                                    <td><span class="badge bg-warning">Đang bận</span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .alert ul {
        padding-left: 1.5rem;
    }

    .alert ul li {
        margin-bottom: 0.25rem;
    }

    .btn-group-sm .btn {
        margin: 0 2px;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .badge {
        font-size: 0.75em;
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .card-body .row>div {
            margin-bottom: 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn {
            font-size: 0.875rem;
        }
    }
</style>