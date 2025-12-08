<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

// Lấy dữ liệu an toàn, đặt giá trị mặc định
$tour = $data['tour_info'] ?? [];
$lichTrinh = $data['lich_trinh_chi_tiet'] ?? [];
$danhSachKhach = $data['danh_sach_khach'] ?? [];
$checklist = $data['checklist'] ?? [];

// Đặt giá trị mặc định cho mảng tour, tránh key chưa được định nghĩa
$tour = array_merge([
    'trang_thai_lich' => 'chưa xác định',
    'trang_thai_xac_nhan' => 'chưa xác nhận',
    'ten_tour' => 'Không có tên',
    'ngay_bat_dau' => date('Y-m-d'),
    'ngay_ket_thuc' => date('Y-m-d'),
    'ma_tour' => '',
    'ten_danh_muc' => '',
    'gio_tap_trung' => '',
    'diem_tap_trung' => '',
    'ten_huong_dan_vien' => '',
    'sdt_hdv' => '',
    'so_cho_toi_da' => 0,
    'so_cho_con_lai' => 0,
    'lich_khoi_hanh_id' => 0,
    'ghi_chu_van_hanh' => '',
    'ghi_chu_phan_cong' => '',
    'quy_dinh_huy_doi' => '',
    'luu_y_suc_khoe' => '',
    'luu_y_hanh_ly' => '',
    'luu_y_khac' => ''
], $tour);
?>

<main class="main-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh">Lịch Trình</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($tour['ten_tour']) ?></li>
            </ol>
        </nav>

        <!-- Tiêu đề -->
        <div class="page-header mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h1 class="page-title mb-2"><?= htmlspecialchars($tour['ten_tour']) ?></h1>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $tour['trang_thai_lich'])) ?>">
                            <?= ucfirst($tour['trang_thai_lich']) ?>
                        </span>
                        <span class="confirmation-badge confirmation-<?= $tour['trang_thai_xac_nhan'] == 'đã xác nhận' ? 'confirmed' : 'pending' ?>">
                            <?= $tour['trang_thai_xac_nhan'] ?>
                        </span>
                        <span class="date-badge">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?> - <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?>
                        </span>
                    </div>
                </div>
                
                <div class="action-buttons mt-3 mt-md-0">
                    <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky_add&lich_id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                       class="btn btn-success">
                        <i class="fas fa-plus mr-1"></i> Thêm nhật ký
                    </a>
                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cột trái: Thông tin chính - ĐÃ SỬA THÀNH col-12 -->
            <div class="col-12">
                <!-- Thông tin cơ bản -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i> Thông Tin Tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-barcode mr-2"></i> Mã tour:</div>
                                    <div class="info-value"><?= $tour['ma_tour'] ?></div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-tag mr-2"></i> Danh mục:</div>
                                    <div class="info-value"><?= $tour['ten_danh_muc'] ?></div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-clock mr-2"></i> Giờ tập trung:</div>
                                    <div class="info-value">
                                        <?= !empty($tour['gio_tap_trung']) ? date('H:i', strtotime($tour['gio_tap_trung'])) : 'Chưa cập nhật' ?>
                                    </div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-map-marker-alt mr-2"></i> Địa điểm tập trung:</div>
                                    <div class="info-value">
                                        <?= !empty($tour['diem_tap_trung']) ? htmlspecialchars($tour['diem_tap_trung']) : 'Chưa cập nhật' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-user mr-2"></i> HDV phụ trách:</div>
                                    <div class="info-value"><?= $tour['ten_huong_dan_vien'] ?></div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-phone mr-2"></i> Số điện thoại HDV:</div>
                                    <div class="info-value"><?= $tour['sdt_hdv'] ?></div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-users mr-2"></i> Số khách tối đa:</div>
                                    <div class="info-value"><?= $tour['so_cho_toi_da'] ?></div>
                                </div>
                                <div class="info-item mb-3">
                                    <div class="info-label"><i class="fas fa-users mr-2"></i> Số chỗ còn lại:</div>
                                    <div class="info-value"><?= $tour['so_cho_con_lai'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($tour['ghi_chu_van_hanh'])): ?>
                            <div class="note-card note-warning mt-4">
                                <div class="note-header">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span>Ghi chú vận hành</span>
                                </div>
                                <div class="note-content">
                                    <?= nl2br(htmlspecialchars($tour['ghi_chu_van_hanh'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($tour['ghi_chu_phan_cong'])): ?>
                            <div class="note-card note-info mt-3">
                                <div class="note-header">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    <span>Ghi chú phân công</span>
                                </div>
                                <div class="note-content">
                                    <?= nl2br(htmlspecialchars($tour['ghi_chu_phan_cong'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Lịch trình chi tiết từng ngày -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-calendar-day mr-2"></i> Lịch Trình Chi Tiết</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($lichTrinh)): ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>Chưa có lịch trình chi tiết</p>
                            </div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($lichTrinh as $index => $ngay): ?>
                                    <div class="timeline-day">
                                        <div class="day-header">
                                            <div class="day-number">
                                                <span>Ngày <?= isset($ngay['so_ngay']) ? $ngay['so_ngay'] : ($index + 1) ?></span>
                                            </div>
                                            <div class="day-info">
                                                <h4 class="day-title"><?= isset($ngay['tieu_de']) ? htmlspecialchars($ngay['tieu_de']) : 'Không có tiêu đề' ?></h4>
                                                <div class="day-date">
                                                    <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'] . ' + ' . ($index) . ' days')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="day-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="activity-card">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-hiking"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <h6>Hoạt động</h6>
                                                            <p><?= isset($ngay['mo_ta_hoat_dong']) ? nl2br(htmlspecialchars($ngay['mo_ta_hoat_dong'])) : 'Chưa cập nhật' ?></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="activity-card">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-utensils"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <h6>Bữa ăn</h6>
                                                            <p><?= isset($ngay['bua_an']) ? nl2br(htmlspecialchars($ngay['bua_an'])) : 'Chưa cập nhật' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="activity-card">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-hotel"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <h6>Chỗ ở</h6>
                                                            <p><?= isset($ngay['cho_o']) ? nl2br(htmlspecialchars($ngay['cho_o'])) : 'Chưa cập nhật' ?></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="activity-card">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-bus"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <h6>Phương tiện</h6>
                                                            <p><?= isset($ngay['phuong_tien']) ? nl2br(htmlspecialchars($ngay['phuong_tien'])) : 'Chưa cập nhật' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($ngay['ghi_chu_hdv'])): ?>
                                                <div class="guide-note">
                                                    <div class="note-header">
                                                        <i class="fas fa-clipboard-check mr-2"></i>
                                                        <span>Ghi chú cho HDV</span>
                                                    </div>
                                                    <div class="note-content">
                                                        <?= nl2br(htmlspecialchars($ngay['ghi_chu_hdv'])) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($index < count($lichTrinh) - 1): ?>
                                        <div class="timeline-connector"></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Danh sách khách hàng -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users mr-2"></i> Danh Sách Khách Hàng Đã Đặt Tour
                            <span class="badge-count"><?= isset($danhSachKhach) ? count($danhSachKhach) : 0 ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($danhSachKhach)): ?>
                            <div class="empty-state">
                                <i class="fas fa-user-friends"></i>
                                <p>Chưa có khách hàng nào đặt tour</p>
                            </div>
                        <?php else: ?>
                            <div class="table-container">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ tên</th>
                                            <th>SĐT</th>
                                            <th>CCCD</th>
                                            <th>Ngày sinh</th>
                                            <th>Giới tính</th>
                                            <th>Email</th>
                                            <th>Mã đặt tour</th>
                                            <th>Trạng thái đặt</th>
                                            <th>Khách sạn (Phòng)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $stt = 1;
                                        $totalCustomers = 0;
                                        $totalPaid = 0;
                                        $totalHold = 0;
                                        $totalUnpaid = 0;
                                        $totalCancelled = 0;
                                        foreach ($danhSachKhach as $khach): 
                                            $totalCustomers++;
                                            // Định dạng giới tính
                                            $gioiTinhText = match($khach['gioi_tinh'] ?? '') {
                                                'nam' => 'Nam',
                                                'nữ' => 'Nữ',
                                                'khác' => 'Khác',
                                                default => 'Chưa cập nhật'
                                            };
                                            
                                            // Xác định trạng thái đặt
                                            $trangThaiDat = strtolower(trim($khach['trang_thai_dat'] ?? ''));
                                            $trangThaiClass = 'secondary';
                                            $trangThaiText = 'Chưa xác định';
                                            
                                            if ($trangThaiDat === 'đã thanh toán' || $trangThaiDat === 'đã thanh toán hoàn tất') {
                                                $trangThaiClass = 'success';
                                                $trangThaiText = 'Đã thanh toán';
                                                $totalPaid++;
                                            } elseif ($trangThaiDat === 'giữ chỗ' || $trangThaiDat === 'chờ giữ chỗ') {
                                                $trangThaiClass = 'warning';
                                                $trangThaiText = 'Giữ chỗ';
                                                $totalHold++;
                                            } elseif ($trangThaiDat === 'hủy' || $trangThaiDat === 'đã hủy' || $trangThaiDat === 'hủy tour') {
                                                $trangThaiClass = 'danger';
                                                $trangThaiText = 'Đã hủy';
                                                $totalCancelled++;
                                            } elseif ($trangThaiDat === 'chờ thanh toán' || $trangThaiDat === 'chưa thanh toán') {
                                                $trangThaiClass = 'secondary';
                                                $trangThaiText = 'Chưa thanh toán';
                                                $totalUnpaid++;
                                            } elseif ($trangThaiDat === 'hoàn tất') {
                                                $trangThaiClass = 'success';
                                                $trangThaiText = 'Hoàn tất';
                                                $totalPaid++;
                                            } else {
                                                $trangThaiClass = 'secondary';
                                                $trangThaiText = htmlspecialchars($khach['trang_thai_dat'] ?? 'Chưa xác định');
                                                $totalUnpaid++;
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $stt++ ?></td>
                                            <td><strong><?= htmlspecialchars($khach['ho_ten'] ?? 'Chưa có tên') ?></strong></td>
                                            <td><?= htmlspecialchars($khach['so_dien_thoai'] ?? 'Chưa có') ?></td>
                                            <td><?= htmlspecialchars($khach['cccd'] ?? 'Chưa có') ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($khach['ngay_sinh'])): ?>
                                                    <?= date('d/m/Y', strtotime($khach['ngay_sinh'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa cập nhật</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?= $gioiTinhText ?></td>
                                            <td>
                                                <?php if (!empty($khach['email'])): ?>
                                                    <a href="mailto:<?= htmlspecialchars($khach['email']) ?>" class="email-link">
                                                        <?= htmlspecialchars($khach['email']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($khach['ma_dat_tour'])): ?>
                                                    <span class="booking-code">
                                                        <?= htmlspecialchars($khach['ma_dat_tour']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có mã</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="status-badge status-<?= $trangThaiClass ?>">
                                                    <?= $trangThaiText ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($khach['ten_khach_san'])): ?>
                                                    <div class="hotel-info">
                                                        <div class="hotel-name"><?= htmlspecialchars($khach['ten_khach_san']) ?></div>
                                                        <div class="room-details">
                                                            Phòng: <?= htmlspecialchars($khach['so_phong'] ?? 'Chưa có') ?>
                                                            <?php if (!empty($khach['loai_phong'])): ?>
                                                                (<?= $khach['loai_phong'] ?>)
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if (!empty($khach['ngay_nhan_phong'])): ?>
                                                            <div class="stay-dates">
                                                                <?= date('d/m/Y', strtotime($khach['ngay_nhan_phong'])) ?>
                                                                - <?= date('d/m/Y', strtotime($khach['ngay_tra_phong'])) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="no-room">Chưa phân phòng</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                
                                <!-- Thống kê -->
                                <div class="summary-card">
                                    <div class="summary-grid">
                                        <div class="summary-item">
                                            <div class="summary-label">Tổng khách hàng</div>
                                            <div class="summary-value"><?= count($danhSachKhach) ?></div>
                                        </div>
                                        <div class="summary-item">
                                            <div class="summary-label">Tổng người</div>
                                            <div class="summary-value">
                                                <?= array_sum(array_column($danhSachKhach, 'so_luong_khach')) ?>
                                            </div>
                                        </div>
                                        <div class="summary-item">
                                            <div class="summary-label">Đã phân phòng</div>
                                            <div class="summary-value">
                                                <?= count(array_filter($danhSachKhach, fn($k) => !empty($k['ten_khach_san']))) ?> /
                                                <?= count($danhSachKhach) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="status-summary">
                                        <div class="status-item status-paid">
                                            <span class="status-dot"></span>
                                            <span>Đã thanh toán: <?= $totalPaid ?></span>
                                        </div>
                                        <div class="status-item status-unpaid">
                                            <span class="status-dot"></span>
                                            <span>Chưa thanh toán: <?= $totalUnpaid ?></span>
                                        </div>
                                        <div class="status-item status-hold">
                                            <span class="status-dot"></span>
                                            <span>Giữ chỗ: <?= $totalHold ?></span>
                                        </div>
                                        <div class="status-item status-cancelled">
                                            <span class="status-dot"></span>
                                            <span>Đã hủy: <?= $totalCancelled ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Checklist & Thông tin khác - ĐÃ SỬA THÀNH col-12 -->
            <div class="col-12">
                <div class="row">
                    <!-- Checklist công việc -->
                    <div class="col-md-6">
                        <div class="card checklist-card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-clipboard-list mr-2"></i> Checklist Công Việc</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($checklist)): ?>
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard"></i>
                                        <p>Chưa có checklist công việc</p>
                                    </div>
                                <?php else: ?>
                                    <div class="checklist-items">
                                        <?php foreach ($checklist as $item): ?>
                                            <div class="checklist-item <?= isset($item['hoan_thanh']) && $item['hoan_thanh'] ? 'completed' : '' ?>">
                                                <div class="checklist-content">
                                                    <div class="custom-checkbox">
                                                        <input type="checkbox" 
                                                               class="checklist-checkbox" 
                                                               id="checklist_<?= $item['id'] ?>"
                                                               data-id="<?= $item['id'] ?>"
                                                               data-lich-id="<?= $tour['lich_khoi_hanh_id'] ?>"
                                                               <?= isset($item['hoan_thanh']) && $item['hoan_thanh'] ? 'checked' : '' ?>>
                                                        <label for="checklist_<?= $item['id'] ?>"></label>
                                                    </div>
                                                    <div class="checklist-text">
                                                        <div class="task-title"><?= isset($item['cong_viec']) ? htmlspecialchars($item['cong_viec']) : 'Không có tên công việc' ?></div>
                                                        <?php if (isset($item['hoan_thanh']) && $item['hoan_thanh'] && !empty($item['thoi_gian_hoan_thanh'])): ?>
                                                            <div class="completion-time">
                                                                <i class="fas fa-check-circle"></i>
                                                                Hoàn thành: <?= date('d/m/Y H:i', strtotime($item['thoi_gian_hoan_thanh'])) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="checklist-actions">
                                        <button class="btn btn-reset" onclick="resetAllChecklists()">
                                            <i class="fas fa-redo mr-1"></i> Đặt lại tất cả
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Chính sách tour -->
                    <div class="col-md-6">
                        <div class="card policy-card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-file-contract mr-2"></i> Chính Sách Tour</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($tour['quy_dinh_huy_doi'])): ?>
                                    <div class="empty-state">
                                        <i class="fas fa-file"></i>
                                        <p>Không có chính sách</p>
                                    </div>
                                <?php else: ?>
                                    <div class="policy-section">
                                        <div class="policy-title">Quy định hủy/đổi</div>
                                        <div class="policy-content">
                                            <?= nl2br(htmlspecialchars($tour['quy_dinh_huy_doi'])) ?>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($tour['luu_y_suc_khoe'])): ?>
                                        <div class="policy-section">
                                            <div class="policy-title">Lưu ý sức khỏe</div>
                                            <div class="policy-content">
                                                <?= nl2br(htmlspecialchars($tour['luu_y_suc_khoe'])) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($tour['luu_y_hanh_ly'])): ?>
                                        <div class="policy-section">
                                            <div class="policy-title">Lưu ý hành lý</div>
                                            <div class="policy-content">
                                                <?= nl2br(htmlspecialchars($tour['luu_y_hanh_ly'])) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($tour['luu_y_khac'])): ?>
                                        <div class="policy-section">
                                            <div class="policy-title">Lưu ý khác</div>
                                            <div class="policy-content">
                                                <?= nl2br(htmlspecialchars($tour['luu_y_khac'])) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* ===== COLOR PALETTE ===== */
:root {
    --primary: #2d9cdb;      /* Xanh ngọc sáng */
    --primary-dark: #1a7bb9; /* Xanh ngọc đậm */
    --primary-light: #4db6e6; /* Xanh ngọc nhạt */
    --secondary: #9b59b6;    /* Tím nhẹ */
    --secondary-light: #b480d3;
    --success: #27ae60;      /* Xanh lá cây */
    --warning: #f39c12;      /* Cam vàng */
    --danger: #e74c3c;       /* Đỏ cam */
    --info: #3498db;         /* Xanh dương */
    --light: #f5f7fa;        /* Xám nhạt */
    --dark: #2c3e50;         /* Xám đen */
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --gray-400: #ced4da;
    --gray-500: #adb5bd;
    --gray-600: #6c757d;
    --gray-700: #495057;
    --gray-800: #343a40;
    --gray-900: #212529;
    --border-radius: 12px;
    --box-shadow: 0 4px 20px rgba(45, 156, 219, 0.08);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== BASE STYLES ===== */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: var(--gray-800);
    background: linear-gradient(135deg, #f5f7fa 0%, #e9f2ff 100%);
    min-height: 100vh;
}

.main-content {
    padding: 20px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== BREADCRUMB ===== */
.custom-breadcrumb {
    margin-bottom: 24px;
}

.custom-breadcrumb .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.custom-breadcrumb .breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.custom-breadcrumb .breadcrumb-item a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.custom-breadcrumb .breadcrumb-item.active {
    color: var(--secondary);
    font-weight: 600;
}

.custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--gray-500);
    font-weight: bold;
}

/* ===== PAGE HEADER ===== */
.page-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border-radius: var(--border-radius);
    padding: 28px;
    box-shadow: var(--box-shadow);
    margin-bottom: 30px;
    border-left: 5px solid var(--primary);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, rgba(155, 89, 182, 0.05) 0%, rgba(45, 156, 219, 0.05) 100%);
    border-radius: 0 0 0 100%;
}

.page-title {
    color: var(--dark) !important;
    font-weight: 700;
    font-size: 32px;
    margin: 0;
    line-height: 1.3;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    text-transform: capitalize;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-đang-diễn-ra,
.status-dang-dien-ra {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    border: 2px solid #27ae60;
}

.status-chờ-lịch,
.status-cho-lich {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: white;
    border: 2px solid var(--primary);
}

.status-đã-hoàn-thành,
.status-da-hoan-thanh {
    background: linear-gradient(135deg, #95a5a6 0%, #bdc3c7 100%);
    color: white;
    border: 2px solid #95a5a6;
}

.confirmation-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 500;
    transition: var(--transition);
}

.confirmation-confirmed {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    border: 2px solid #27ae60;
    box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
}

.confirmation-pending {
    background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
    color: white;
    border: 2px solid #f39c12;
    box-shadow: 0 2px 8px rgba(243, 156, 18, 0.2);
}

.date-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 8px;
    color: var(--gray-700);
    font-size: 14px;
    font-weight: 500;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
}

.date-badge i {
    color: var(--primary);
    margin-right: 8px;
}

.date-badge:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 156, 219, 0.1);
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.btn {
    font-weight: 600;
    border-radius: 8px;
    padding: 12px 24px;
    font-size: 14px;
    transition: var(--transition);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
}

.btn-outline-secondary {
    background: transparent;
    color: var(--gray-700) !important;
    border: 2px solid var(--gray-300);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.1);
}

.btn-outline-secondary:hover {
    background: var(--gray-100);
    border-color: var(--primary);
    color: var(--primary) !important;
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(45, 156, 219, 0.2);
}

/* ===== CARD STYLES ===== */
.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 24px;
    background: white;
    transition: var(--transition);
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(45, 156, 219, 0.15);
}

.card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-bottom: none;
    padding: 20px 24px;
    border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    border-radius: 0 0 0 100%;
}

.card-title {
    color: white !important;
    font-weight: 600;
    font-size: 18px;
    margin: 0;
    display: flex;
    align-items: center;
    position: relative;
    z-index: 1;
}

.card-title i {
    color: rgba(255, 255, 255, 0.9);
    margin-right: 12px;
    font-size: 20px;
}

.card-body {
    padding: 24px;
}

/* ===== INFO CARD ===== */
.info-card .info-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 18px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--gray-200);
    transition: var(--transition);
}

.info-card .info-item:hover {
    border-bottom-color: var(--primary);
    transform: translateX(5px);
}

.info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.info-label {
    color: var(--gray-600);
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
}

.info-label i {
    color: var(--primary);
    width: 20px;
    margin-right: 10px;
    font-size: 16px;
}

.info-value {
    color: var(--dark) !important;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.4;
}

.note-card {
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.note-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.note-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 5px solid #f39c12;
}

.note-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 5px solid var(--primary);
}

.note-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    color: var(--dark);
    font-weight: 600;
    font-size: 15px;
}

.note-header i {
    margin-right: 10px;
    font-size: 18px;
}

.note-warning .note-header i {
    color: #f39c12;
}

.note-info .note-header i {
    color: var(--primary);
}

.note-content {
    color: var(--gray-800);
    line-height: 1.7;
    font-size: 14px;
}

/* ===== TIMELINE ===== */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary) 0%, var(--secondary) 100%);
}

.timeline-day {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 2px solid var(--gray-200);
    position: relative;
    transition: var(--transition);
}

.timeline-day:hover {
    border-color: var(--primary);
    box-shadow: 0 8px 25px rgba(45, 156, 219, 0.15);
}

.timeline-day::before {
    content: '';
    position: absolute;
    left: -41px;
    top: 30px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary);
    border: 3px solid white;
    box-shadow: 0 0 0 3px var(--primary-light);
}

.day-header {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
}

.day-number {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    width: 90px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    margin-right: 20px;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
}

.day-info {
    flex: 1;
}

.day-title {
    color: var(--dark) !important;
    font-weight: 600;
    font-size: 20px;
    margin: 0 0 6px 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.day-date {
    color: var(--gray-600);
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.day-date i {
    margin-right: 6px;
    color: var(--primary);
}

.activity-card {
    display: flex;
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
}

.activity-card:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(45, 156, 219, 0.1);
}

.activity-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(45, 156, 219, 0.2);
}

.activity-icon i {
    color: white;
    font-size: 20px;
}

.activity-content h6 {
    color: var(--dark) !important;
    font-weight: 600;
    font-size: 15px;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
}

.activity-content p {
    color: var(--gray-700);
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

.guide-note {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-radius: 10px;
    padding: 20px;
    margin-top: 24px;
    border-left: 5px solid #f39c12;
    position: relative;
    overflow: hidden;
}

.guide-note::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(243, 156, 18, 0.1) 0%, rgba(243, 156, 18, 0) 100%);
    border-radius: 0 0 0 100%;
}

/* ===== TABLE STYLES ===== */
.table-container {
    overflow-x: auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden;
}

.custom-table thead {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

.custom-table th {
    color: white !important;
    font-weight: 600;
    font-size: 14px;
    padding: 18px;
    text-align: left;
    border-bottom: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
}

.custom-table th::after {
    content: '';
    position: absolute;
    right: 0;
    top: 20%;
    height: 60%;
    width: 1px;
    background: rgba(255, 255, 255, 0.2);
}

.custom-table th:last-child::after {
    display: none;
}

.custom-table td {
    color: var(--gray-800) !important;
    padding: 18px;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition);
}

.custom-table tbody tr {
    transition: var(--transition);
}

.custom-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(45, 156, 219, 0.05) 0%, rgba(155, 89, 182, 0.05) 100%);
}

.custom-table tbody tr:hover td {
    transform: translateX(5px);
}

.booking-code {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: var(--primary-dark);
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Courier New', monospace;
    border: 1px solid var(--primary-light);
}

.email-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.email-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.hotel-info {
    line-height: 1.5;
}

.hotel-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 6px;
}

.room-details {
    color: var(--gray-600);
    font-size: 13px;
    font-weight: 500;
}

.stay-dates {
    color: var(--gray-500);
    font-size: 12px;
    margin-top: 4px;
    font-weight: 500;
}

.no-room {
    color: #f39c12;
    font-style: italic;
    font-weight: 500;
    padding: 6px 12px;
    background: rgba(243, 156, 18, 0.1);
    border-radius: 6px;
    display: inline-block;
}

/* ===== SUMMARY CARD ===== */
.summary-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border-radius: 12px;
    padding: 24px;
    margin-top: 24px;
    border: 2px solid var(--gray-200);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.summary-item {
    text-align: center;
    padding: 20px;
    background: white;
    border-radius: 10px;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
}

.summary-item:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(45, 156, 219, 0.15);
}

.summary-label {
    color: var(--gray-600);
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
}

.summary-value {
    color: var(--primary-dark) !important;
    font-weight: 700;
    font-size: 24px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.status-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    padding-top: 20px;
    border-top: 2px solid var(--gray-200);
}

.status-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    padding: 10px 20px;
    background: white;
    border-radius: 25px;
    border: 2px solid var(--gray-200);
    transition: var(--transition);
}

.status-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    box-shadow: 0 0 10px currentColor;
}

.status-paid .status-dot {
    background: #27ae60;
    color: #27ae60;
}

.status-unpaid .status-dot {
    background: var(--gray-500);
    color: var(--gray-500);
}

.status-hold .status-dot {
    background: #f39c12;
    color: #f39c12;
}

.status-cancelled .status-dot {
    background: #e74c3c;
    color: #e74c3c;
}

/* ===== CHECKLIST ===== */
.checklist-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border: 2px solid var(--gray-200);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.checklist-item:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(45, 156, 219, 0.1);
}

.checklist-item.completed {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border-color: #27ae60;
}

.checklist-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.custom-checkbox {
    position: relative;
    margin-top: 3px;
}

.custom-checkbox input[type="checkbox"] {
    opacity: 0;
    position: absolute;
    width: 0;
    height: 0;
}

.custom-checkbox label {
    width: 24px;
    height: 24px;
    border: 2px solid var(--gray-400);
    border-radius: 6px;
    cursor: pointer;
    display: block;
    position: relative;
    transition: var(--transition);
    background: white;
}

.custom-checkbox label::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 14px;
    height: 14px;
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    border-radius: 3px;
    transition: var(--transition);
}

.custom-checkbox input[type="checkbox"]:checked + label {
    border-color: #27ae60;
    background: white;
    box-shadow: 0 0 15px rgba(39, 174, 96, 0.3);
}

.custom-checkbox input[type="checkbox"]:checked + label::after {
    transform: translate(-50%, -50%) scale(1);
}

.checklist-text {
    flex: 1;
}

.task-title {
    color: var(--dark) !important;
    font-weight: 600;
    font-size: 15px;
    line-height: 1.5;
    margin-bottom: 6px;
}

.checklist-item.completed .task-title {
    color: #27ae60 !important;
    text-decoration: line-through;
    font-weight: 500;
}

.completion-time {
    color: var(--gray-600);
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.completion-time i {
    color: #27ae60;
    font-size: 12px;
}

.checklist-actions {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid var(--gray-200);
}

.btn-reset {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid var(--gray-300);
    color: var(--gray-700);
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-reset:hover {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-color: var(--primary);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(45, 156, 219, 0.3);
}

/* ===== POLICY CARD ===== */
.policy-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid var(--gray-200);
    position: relative;
}

.policy-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.policy-section::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 50px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    transition: var(--transition);
}

.policy-section:hover::before {
    width: 100%;
}

.policy-title {
    color: var(--dark) !important;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.policy-title::before {
    content: '✓';
    color: var(--primary);
    font-size: 14px;
    font-weight: bold;
}

.policy-content {
    color: var(--gray-700);
    font-size: 14px;
    line-height: 1.7;
    padding-left: 24px;
    border-left: 3px solid var(--gray-300);
    padding-left: 15px;
    margin-left: 5px;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.empty-state p {
    color: var(--gray-600);
    margin: 0;
    font-size: 16px;
    font-weight: 500;
}

/* ===== BADGE COUNT ===== */
.badge-count {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-left: 10px;
    box-shadow: 0 2px 8px rgba(155, 89, 182, 0.3);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        padding: 20px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
    
    .day-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .day-number {
        margin-bottom: 15px;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .status-summary {
        flex-direction: column;
        align-items: center;
    }
    
    .timeline {
        padding-left: 30px;
    }
    
    .timeline::before {
        left: 15px;
    }
    
    .timeline-day::before {
        left: -31px;
    }
}

/* ===== SCROLLBAR STYLING ===== */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-light) 100%);
}

/* ===== PRINT STYLES ===== */
@media print {
    .btn,
    .action-buttons,
    .checklist-actions {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .page-header {
        border: 1px solid #ddd !important;
    }
}

/* ===== COL-12 SPECIFIC STYLES ===== */
.col-12 .row {
    margin-left: 0;
    margin-right: 0;
}

.col-12 .col-md-6 {
    padding-left: 0;
    padding-right: 0;
}

@media (min-width: 768px) {
    .col-12 .col-md-6:first-child {
        padding-right: 12px;
    }
    
    .col-12 .col-md-6:last-child {
        padding-left: 12px;
    }
}

/* Ensure cards stack nicely on mobile */
@media (max-width: 767px) {
    .col-12 .col-md-6 {
        width: 100%;
        margin-bottom: 20px;
    }
}
</style>

<script>
// Checklist functionality
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('checklist-checkbox')) {
            handleChecklistChange(e.target);
        }
    });
    
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

async function handleChecklistChange(checkbox) {
    const checklistId = checkbox.dataset.id;
    const isChecked = checkbox.checked;
    const item = checkbox.closest('.checklist-item');
    const taskTitle = item.querySelector('.task-title');
    
    const originalText = taskTitle.textContent;
    taskTitle.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang cập nhật...';
    
    // Add loading animation
    item.style.opacity = '0.7';
    item.style.pointerEvents = 'none';
    
    try {
        const response = await fetch('<?= BASE_URL_GUIDE ?>?act=update-checklist-guide', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${checklistId}&hoan_thanh=${isChecked ? '1' : '0'}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (isChecked) {
                item.classList.add('completed');
                
                // Add success animation
                item.style.animation = 'none';
                setTimeout(() => {
                    item.style.animation = 'pulse 0.6s';
                }, 10);
                
                let completionTime = item.querySelector('.completion-time');
                if (!completionTime) {
                    completionTime = document.createElement('div');
                    completionTime.className = 'completion-time';
                    const now = new Date();
                    const formattedTime = now.getDate().toString().padStart(2, '0') + '/' + 
                                         (now.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                         now.getFullYear() + ' ' + 
                                         now.getHours().toString().padStart(2, '0') + ':' + 
                                         now.getMinutes().toString().padStart(2, '0');
                    completionTime.innerHTML = `<i class="fas fa-check-circle"></i> Hoàn thành: ${formattedTime}`;
                    taskTitle.parentElement.appendChild(completionTime);
                }
            } else {
                item.classList.remove('completed');
                const completionTime = item.querySelector('.completion-time');
                if (completionTime) {
                    completionTime.remove();
                }
            }
            
            showToast('success', 'Cập nhật checklist thành công!');
        } else {
            checkbox.checked = !isChecked;
            showToast('error', result.message || 'Có lỗi xảy ra khi cập nhật');
        }
    } catch (error) {
        checkbox.checked = !isChecked;
        showToast('error', 'Lỗi kết nối server. Vui lòng thử lại.');
        console.error('Error:', error);
    } finally {
        taskTitle.textContent = originalText;
        item.style.opacity = '1';
        item.style.pointerEvents = 'auto';
        item.style.animation = '';
    }
}

function resetAllChecklists() {
    if (!confirm('Bạn có chắc chắn muốn đặt lại tất cả checklist?')) {
        return;
    }
    
    const checkboxes = document.querySelectorAll('.checklist-checkbox:checked');
    
    if (checkboxes.length === 0) {
        showToast('info', 'Không có checklist nào để đặt lại');
        return;
    }
    
    // Add reset animation
    const checklistItems = document.querySelectorAll('.checklist-item.completed');
    checklistItems.forEach(item => {
        item.style.animation = 'shake 0.5s';
    });
    
    checkboxes.forEach((checkbox, index) => {
        setTimeout(() => {
            checkbox.checked = false;
            const event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        }, index * 150);
    });
    
    setTimeout(() => {
        checklistItems.forEach(item => {
            item.style.animation = '';
        });
        showToast('success', `Đã đặt lại ${checkboxes.length} checklist`);
    }, checkboxes.length * 150 + 500);
}

function showToast(type, message) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    const icon = type === 'success' ? 'check-circle' : 
                type === 'error' ? 'exclamation-circle' : 
                'info-circle';
    const color = type === 'success' ? '#27ae60' : 
                 type === 'error' ? '#e74c3c' : 
                 '#3498db';
    
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.style.background = `linear-gradient(135deg, ${color} 0%, ${color}80 100%)`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex align-items-center p-3">
            <i class="fas fa-${icon} fa-lg me-3"></i>
            <div class="toast-body" style="font-weight: 500;">${message}</div>
            <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { 
        delay: 3000,
        animation: true 
    });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Add shake animation for reset
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(shakeStyle);
</script>

<?php
include __DIR__ . '/../layout/footer.php';
?>