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
            <!-- Cột trái: Thông tin chính -->
            <div class="col-lg-8">
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
            </div>

            <!-- Cột phải: Checklist & Thông tin khác -->
            <div class="col-lg-4">
                <!-- Checklist công việc -->
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

                <!-- Chính sách tour -->
                <div class="card policy-card">
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
            <div class="col-12">
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
        </div>

    </div>
</main>

<style>
/* ===== BASE STYLES ===== */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: #1e293b;
    background-color: #f8fafc;
}

.main-content {
    padding: 20px;
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
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.custom-breadcrumb .breadcrumb-item.active {
    color: #64748b;
    font-weight: 500;
}

.custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #94a3b8;
}

/* ===== PAGE HEADER ===== */
.page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
    border-left: 4px solid #3b82f6;
}

.page-title {
    color: #1e293b !important;
    font-weight: 700;
    font-size: 28px;
    margin: 0;
    line-height: 1.3;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.status-đang-diễn-ra,
.status-dang-dien-ra {
    background: #d1fae5;
    color: #059669;
}

.status-chờ-lịch,
.status-cho-lich {
    background: #dbeafe;
    color: #3b82f6;
}

.status-đã-hoàn-thành,
.status-da-hoan-thanh {
    background: #f1f5f9;
    color: #64748b;
}

.confirmation-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.confirmation-confirmed {
    background: #d1fae5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.confirmation-pending {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
}

.date-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #f8fafc;
    border-radius: 6px;
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.date-badge i {
    color: #94a3b8;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.btn {
    font-weight: 500;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-outline-secondary {
    color: #64748b !important;
    border: 2px solid #e2e8f0;
    background: transparent;
}

.btn-success:hover,
.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* ===== CARD STYLES ===== */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px;
    border-radius: 12px 12px 0 0 !important;
}

.card-title {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 18px;
    margin: 0;
    display: flex;
    align-items: center;
}

.card-title i {
    color: #3b82f6;
    margin-right: 8px;
}

.card-body {
    padding: 24px;
}

/* ===== INFO CARD ===== */
.info-card .info-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px;
}

.info-label {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
}

.info-label i {
    color: #3b82f6;
    width: 20px;
    margin-right: 8px;
}

.info-value {
    color: #1e293b !important;
    font-size: 15px;
    font-weight: 500;
}

.note-card {
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
}

.note-warning {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
}

.note-info {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
}

.note-header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #1e293b;
    font-weight: 600;
    font-size: 14px;
}

.note-header i {
    margin-right: 8px;
}

.note-content {
    color: #1e293b;
    line-height: 1.6;
    font-size: 14px;
}

/* ===== TIMELINE ===== */
.timeline-day {
    background: white;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
}

.day-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.day-number {
    background: #3b82f6;
    color: white;
    width: 80px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    margin-right: 16px;
    flex-shrink: 0;
}

.day-info {
    flex: 1;
}

.day-title {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 18px;
    margin: 0 0 4px 0;
}

.day-date {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.activity-card {
    display: flex;
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
}

.activity-icon i {
    color: #3b82f6;
    font-size: 16px;
}

.activity-content h6 {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 14px;
    margin: 0 0 6px 0;
}

.activity-content p {
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
    margin: 0;
}

.guide-note {
    background: #fffbeb;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    border-left: 4px solid #f59e0b;
}

/* ===== TABLE STYLES ===== */
.table-container {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table thead {
    background: #f8fafc;
}

.custom-table th {
    color: rgba(255, 255, 255, 1)ff !important;
    font-weight: 600;
    font-size: 13px;
    padding: 16px;
    text-align: left;
    border-bottom: 2px solid #e2e8f0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.custom-table td {
    color: #1e293b !important;
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 14px;
}

.custom-table tbody tr:hover {
    background: #f8fafc;
}

.booking-code {
    background: #e0e7ff;
    color: #4f46e5;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

.email-link {
    color: #3b82f6;
    text-decoration: none;
}

.email-link:hover {
    text-decoration: underline;
}

.hotel-info {
    line-height: 1.4;
}

.hotel-name {
    font-weight: 500;
    color: #1e293b;
    margin-bottom: 4px;
}

.room-details {
    color: #64748b;
    font-size: 12px;
}

.stay-dates {
    color: #94a3b8;
    font-size: 11px;
    margin-top: 2px;
}

.no-room {
    color: #f59e0b;
    font-style: italic;
}

/* ===== SUMMARY CARD ===== */
.summary-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    margin-top: 24px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.summary-item {
    text-align: center;
}

.summary-label {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 4px;
}

.summary-value {
    color: #1e293b !important;
    font-weight: 700;
    font-size: 20px;
}

.status-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-paid .status-dot {
    background: #10b981;
}

.status-unpaid .status-dot {
    background: #94a3b8;
}

.status-hold .status-dot {
    background: #f59e0b;
}

.status-cancelled .status-dot {
    background: #ef4444;
}

/* ===== CHECKLIST ===== */
.checklist-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    transition: all 0.2s;
}

.checklist-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.checklist-item.completed {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.checklist-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.custom-checkbox {
    position: relative;
    margin-top: 2px;
}

.custom-checkbox input[type="checkbox"] {
    opacity: 0;
    position: absolute;
    width: 0;
    height: 0;
}

.custom-checkbox label {
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e1;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    position: relative;
    transition: all 0.2s;
}

.custom-checkbox label::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 2px;
    transition: all 0.2s;
}

.custom-checkbox input[type="checkbox"]:checked + label {
    border-color: #10b981;
}

.custom-checkbox input[type="checkbox"]:checked + label::after {
    transform: translate(-50%, -50%) scale(1);
}

.checklist-text {
    flex: 1;
}

.task-title {
    color: #1e293b !important;
    font-weight: 500;
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 4px;
}

.checklist-item.completed .task-title {
    color: #64748b !important;
    text-decoration: line-through;
}

.completion-time {
    color: #64748b;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.completion-time i {
    color: #10b981;
    font-size: 10px;
}

.checklist-actions {
    text-align: center;
    margin-top: 20px;
}

.btn-reset {
    background: transparent;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-reset:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

/* ===== POLICY CARD ===== */
.policy-section {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.policy-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.policy-title {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.policy-content {
    color: #475569;
    font-size: 13px;
    line-height: 1.6;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state p {
    color: #64748b;
    margin: 0;
    font-size: 14px;
}

/* ===== BADGE COUNT ===== */
.badge-count {
    background: #3b82f6;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    margin-left: 8px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        padding: 16px;
    }
    
    .page-title {
        font-size: 22px;
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
        margin-bottom: 12px;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
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
});

async function handleChecklistChange(checkbox) {
    const checklistId = checkbox.dataset.id;
    const isChecked = checkbox.checked;
    const item = checkbox.closest('.checklist-item');
    const taskTitle = item.querySelector('.task-title');
    
    const originalText = taskTitle.textContent;
    taskTitle.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang cập nhật...';
    
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
            
            showToast('success', 'Cập nhật thành công');
        } else {
            checkbox.checked = !isChecked;
            showToast('error', result.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        checkbox.checked = !isChecked;
        showToast('error', 'Lỗi kết nối server');
        console.error('Error:', error);
    } finally {
        taskTitle.textContent = originalText;
    }
}

function resetAllChecklists() {
    if (!confirm('Bạn có chắc chắn muốn đặt lại tất cả checklist?')) {
        return;
    }
    
    const checkboxes = document.querySelectorAll('.checklist-checkbox:checked');
    
    checkboxes.forEach((checkbox, index) => {
        setTimeout(() => {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        }, index * 100);
    });
    
    setTimeout(() => {
        showToast('success', 'Đã đặt lại tất cả checklist');
    }, checkboxes.length * 100 + 500);
}

function showToast(type, message) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1050';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}
</script>

<?php
include __DIR__ . '/../layout/footer.php';
?>