
<!-- TRANG 1: DASHBOARD -->
<?php require './views/layout/header.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div id="dashboard" class="page-content active">
    <!-- THÊM CONTAINER BAO BỌC TOÀN BỘ NỘI DUNG -->
    <div class="dashboard-container">
        <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
        
        <!-- Thông tin HDV -->
        <?php if (isset($guideInfo) && !empty($guideInfo)): ?>
        <div class="guide-info mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <?php if (!empty($guideInfo['hinh_anh'])): ?>
                        <img src="<?= htmlspecialchars($guideInfo['hinh_anh']) ?>" 
                             alt="Avatar" 
                             class="rounded-circle me-3" 
                             style="width: 70px; height: 70px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px; background-color: var(--primary-color); color: white;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h4 class="mb-1">Xin chào, <?= htmlspecialchars($guideInfo['ho_ten'] ?? 'Hướng dẫn viên') ?>!</h4>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-id-card"></i> 
                            <?= htmlspecialchars($guideInfo['loai_huong_dan_vien'] ?? 'Nội địa') ?> | 
                            <i class="fas fa-star text-warning"></i> 
                            Đánh giá: <?= number_format($guideInfo['danh_gia_trung_binh'] ?? 0, 1) ?>/5 |
                            <i class="fas fa-route"></i> 
                            <?= $guideInfo['so_tour_da_dan'] ?? 0 ?> tour
                        </p>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block"><?= date('d/m/Y H:i') ?></small>
                    <span class="badge bg-success">
                        <i class="fas fa-circle"></i> Đang hoạt động
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Row 1: Lịch làm việc (chi tiết lớn) -->
        <div class="row mb-4">
            <!-- Lịch làm việc tháng - Full width -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-info">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Lịch Làm Việc - Tháng <?= date('m/Y') ?>
                        </h5>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-light me-1" onclick="changeCalendarMonth(-1)">
                                <i class="fas fa-chevron-left"></i> Tháng trước
                            </button>
                            <button class="btn btn-sm btn-light" onclick="changeCalendarMonth(1)">
                                Tháng sau <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Calendar header -->
                        <div class="calendar-header d-flex mb-3">
                            <?php
                            $daysOfWeek = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
                            foreach ($daysOfWeek as $day): ?>
                                <div class="calendar-header-day flex-fill text-center fw-bold text-muted">
                                    <?= $day ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Calendar grid -->
                        <div class="calendar-grid">
                            <?php
                            // Tạo calendar cho tháng hiện tại
                            $currentMonth = date('m');
                            $currentYear = date('Y');
                            $firstDay = date('N', strtotime("$currentYear-$currentMonth-01")); // Thứ trong tuần (1=Mon, 7=Sun)
                            $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
                            $currentDay = date('j');
                            
                            // Hiển thị các ô trống đầu tháng
                            for ($i = 1; $i < $firstDay; $i++): ?>
                                <div class="calendar-day empty"></div>
                            <?php endfor; 
                            
                            // Hiển thị các ngày trong tháng
                            for ($day = 1; $day <= $daysInMonth; $day++):
                                $dateStr = date('Y-m-d', strtotime("$currentYear-$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT)));
                                $isToday = ($day == $currentDay);
                                $hasEvent = false;
                                $eventsForDay = [];
                                
                                // Kiểm tra có sự kiện không
                                if (isset($eventsByDate[$dateStr])) {
                                    $hasEvent = true;
                                    $eventsForDay = $eventsByDate[$dateStr];
                                }
                            ?>
                                <div class="calendar-day 
                                    <?= $isToday ? 'today' : '' ?> 
                                    <?= $hasEvent ? 'has-event' : '' ?>"
                                    onclick="showDayEvents('<?= $dateStr ?>')">
                                    <div class="day-header">
                                        <span class="day-number"><?= $day ?></span>
                                        <?php if ($isToday): ?>
                                            <span class="today-badge">Hôm nay</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="day-events">
                                        <?php if ($hasEvent): 
                                            // Giới hạn hiển thị 3 sự kiện
                                            $displayEvents = array_slice($eventsForDay, 0, 3);
                                            foreach ($displayEvents as $event): 
                                                $eventClass = '';
                                                if ($event['type'] == 'tour' || $event['type'] == 'đã phân công') $eventClass = 'tour';
                                                if ($event['type'] == 'bận') $eventClass = 'busy';
                                                if ($event['type'] == 'nghỉ') $eventClass = 'off';
                                        ?>
                                            <div class="event-item event-<?= $eventClass ?>"
                                                 title="<?= htmlspecialchars($event['title']) ?>">
                                                <i class="fas fa-circle event-dot"></i>
                                                <span class="event-title"><?= htmlspecialchars(mb_substr($event['title'], 0, 20)) ?></span>
                                            </div>
                                        <?php endforeach; 
                                        
                                        // Nếu có nhiều hơn 3 sự kiện
                                        if (count($eventsForDay) > 3): ?>
                                            <div class="event-more">
                                                <small class="text-muted">+<?= count($eventsForDay) - 3 ?> sự kiện khác</small>
                                            </div>
                                        <?php endif;
                                        endif; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <!-- Legend -->
                        <div class="calendar-legend mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-2">Chú thích:</h6>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="legend-item">
                                            <span class="legend-dot tour"></span>
                                            <small>Tour / Đã phân công</small>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-dot busy"></span>
                                            <small>Bận</small>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-dot off"></span>
                                            <small>Nghỉ</small>
                                        </div>
                                        <div class="legend-item">
                                            <span class="legend-dot today"></span>
                                            <small>Hôm nay</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <div class="calendar-stats">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check text-success"></i> 
                                            <?= isset($calendarStats['tour_days']) ? $calendarStats['tour_days'] : 0 ?> ngày có tour
                                        </small>
                                        <small class="text-muted mx-2">|</small>
                                        <small class="text-muted">
                                            <i class="fas fa-exclamation-triangle text-warning"></i> 
                                            <?= isset($calendarStats['busy_days']) ? $calendarStats['busy_days'] : 0 ?> ngày bận
                                        </small>
                                        <small class="text-muted mx-2">|</small>
                                        <small class="text-muted">
                                            <i class="fas fa-bed text-info"></i> 
                                            <?= isset($calendarStats['off_days']) ? $calendarStats['off_days'] : 0 ?> ngày nghỉ
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="<?= BASE_URL_GUIDE ?>?act=lich-lam-viec" class="btn btn-outline-info">
                            <i class="fas fa-calendar-alt me-1"></i> Xem chi tiết lịch làm việc đầy đủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Row 2: Tour sắp khởi hành và Hoạt động hôm nay -->
        <div class="row mb-4">
            <!-- Tour sắp khởi hành -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-calendar-day me-2"></i>
                            Tour Sắp Khởi Hành
                        </h5>
                        <span class="badge bg-light text-primary"><?= isset($tourSapKhoiHanh) ? count($tourSapKhoiHanh) : 0 ?> tour</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($tourSapKhoiHanh)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($tourSapKhoiHanh as $tour): ?>
                                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh-detail&id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($tour['ten_tour'] ?? '') ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-day"></i> 
                                                    <?= isset($tour['ngay_bat_dau']) ? date('d/m/Y', strtotime($tour['ngay_bat_dau'])) : '' ?> 
                                                    - <?= isset($tour['ngay_ket_thuc']) ? date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) : '' ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="d-block">
                                                    <i class="fas fa-users"></i> <?= $tour['so_khach'] ?? 0 ?> khách
                                                </small>
                                                <span class="badge bg-<?= ($tour['trang_thai'] ?? '') == 'đang đi' ? 'warning' : 'info' ?>">
                                                    <?= $tour['trang_thai'] ?? '' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Không có tour sắp khởi hành</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center">
                        <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i> Xem tất cả lịch trình
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Hoạt động hôm nay -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center bg-success">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-calendar-check me-2"></i>
                            Hoạt Động Hôm Nay
                        </h5>
                        <span class="badge bg-light text-success"><?= date('d/m/Y') ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($lichTrinhHomNay)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($lichTrinhHomNay as $lich): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($lich['ten_tour'] ?? '') ?></h6>
                                                <p class="mb-1 text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> 
                                                    <?= htmlspecialchars($lich['ten_chi_tiet_dia_diem'] ?? 'Hoạt động tour') ?>
                                                </p>
                                                <?php if (!empty($lich['mo_ta_hoat_dong'])): ?>
                                                    <small class="text-muted">
                                                        <?= substr(htmlspecialchars($lich['mo_ta_hoat_dong']), 0, 80) ?>...
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-info">
                                                    <?= $lich['trang_thai_lich'] ?? 'đang diễn ra' ?>
                                                </span>
                                                <?php if (!empty($lich['thoi_gian'])): ?>
                                                    <small class="d-block text-muted mt-1">
                                                        <i class="fas fa-clock"></i> <?= $lich['thoi_gian'] ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Không có hoạt động trong ngày hôm nay</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Row 3: Sự kiện sắp tới và Đánh giá -->
        <div class="row mb-4">
            <!-- Sự kiện sắp tới (7 ngày tới) -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center bg-warning">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-clock me-2"></i>
                            Sự Kiện Sắp Tới (7 ngày tới)
                        </h5>
                        <span class="badge bg-light text-warning"><?= isset($upcomingEvents) ? count($upcomingEvents) : 0 ?> sự kiện</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($upcomingEvents)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($upcomingEvents as $event): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <?= htmlspecialchars($event['title'] ?? 'Sự kiện') ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-day"></i> 
                                                    <?= isset($event['date']) ? date('d/m/Y', strtotime($event['date'])) : '' ?>
                                                    (<?= $event['days_until'] ?? 0 ?> ngày nữa)
                                                    <?php if (!empty($event['ghi_chu'])): ?>
                                                        <br><i class="fas fa-sticky-note"></i> <?= htmlspecialchars($event['ghi_chu']) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <?php
                                                $eventType = $event['type'] ?? '';
                                                $badgeClass = 'bg-info';
                                                if ($eventType == 'tour' || $eventType == 'đã phân công') $badgeClass = 'bg-success';
                                                if ($eventType == 'bận') $badgeClass = 'bg-warning';
                                                if ($eventType == 'nghỉ') $badgeClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst($eventType) ?>
                                                </span>
                                                <?php if (isset($event['tour_data'])): ?>
                                                    <small class="d-block text-muted mt-1">
                                                        <i class="fas fa-users"></i> <?= $event['tour_data']['so_khach'] ?? 0 ?> khách
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Không có sự kiện sắp tới</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Đánh giá -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-purple">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-star me-2"></i>
                            Đánh Giá Của Bạn
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (isset($danhGia) && $danhGia['diem_trung_binh'] > 0): ?>
                            <div class="mb-3">
                                <div class="display-2 fw-bold text-warning">
                                    <?= number_format($danhGia['diem_trung_binh'], 1) ?>
                                    <small class="fs-5 text-muted">/5</small>
                                </div>
                                <div class="star-rating mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $danhGia['so_sao_tron']): ?>
                                            <i class="fas fa-star fa-2x text-warning"></i>
                                        <?php elseif ($danhGia['co_nua_sao'] && $i == $danhGia['so_sao_tron'] + 1): ?>
                                            <i class="fas fa-star-half-alt fa-2x text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star fa-2x text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" 
                                         style="width: <?= $danhGia['phan_tram'] ?>%"></div>
                                </div>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-route"></i> Dựa trên <?= $danhGia['so_tour'] ?> tour đã dẫn
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="py-4">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Chưa có đánh giá</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Kết thúc dashboard-container -->
</div>

<!-- Modal hiển thị sự kiện theo ngày -->
<div class="modal fade" id="dayEventsModal" tabindex="-1" aria-labelledby="dayEventsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayEventsModalTitle">Sự kiện ngày ...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="dayEventsModalBody">
                <!-- Nội dung sẽ được thêm bằng JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="<?= BASE_URL_GUIDE ?>?act=lich-lam-viec" class="btn btn-primary">
                    <i class="fas fa-calendar-alt me-1"></i> Xem chi tiết lịch
                </a>
            </div>
        </div>
    </div>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
:root {
    --primary-color: #3498db;
    --success-color: #2ecc71;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #17a2b8;
    --purple-color: #9b59b6;
}

/* LAYOUT CHÍNH - CĂN GIỮA TOÀN BỘ */
.page-content {
    margin-left: 250px; /* Sidebar width */
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 15px; /* Giảm padding từ 20px xuống 15px */
    display: flex;
    justify-content: flex-start; /* Thay đổi từ center thành flex-start */
    align-items: flex-start;
}

/* CONTAINER - GẦN SIDEBAR HƠN */
.dashboard-container {
    max-width: 100%; /* Thay đổi từ 1400px xuống 100% */
    width: 100%;
    padding: 0 15px; /* Giảm padding ngang */
    margin-left: 0; /* Reset margin */
}

/* TIÊU ĐỀ TRANG - CĂN TRÁI */
.page-title {
    text-align: left; /* Thay đổi từ center thành left */
    color: #2c3e50;
    font-size: 1.8rem; /* Giảm kích thước font */
    margin-bottom: 5px; /* Giảm margin */
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
    position: relative;
}

.page-title:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0; /* Thay đổi từ 50% thành 0 */
    transform: none; /* Bỏ transform */
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, var(--primary-color), var(--info-color));
    border-radius: 3px;
}

/* GUIDE INFO - CĂN TRÁI */
.guide-info {
    background: white;
    border-radius: 10px; /* Giảm border-radius */
    padding: 20px; /* Giảm padding */
    margin-bottom: 20px; /* Giảm margin */
    box-shadow: 0 3px 10px rgba(0,0,0,0.06); /* Giảm shadow */
    text-align: left; /* Thay đổi từ center thành left */
    border-left: 5px solid var(--primary-color);
}

/* CARD STYLING - GỌN GÀNG HƠN */
.card {
    background: white;
    border-radius: 10px; /* Giảm border-radius */
    box-shadow: 0 3px 10px rgba(0,0,0,0.06); /* Giảm shadow */
    margin-bottom: 5%; /* Giảm margin */
    border: none;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
}

.card:hover {
    transform: translateY(-3px); /* Giảm hiệu ứng hover */
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.card-header {
    padding: 15px 20px; /* Giảm padding */
    border-bottom: 1px solid #e0e0e0;
    background: linear-gradient(135deg, var(--info-color), #0d8abc);
    color: white;
    text-align: left; /* Thay đổi từ center thành left */
}

.card-header.bg-primary {
    background: linear-gradient(135deg, var(--primary-color), #2980b9);
}

.card-header.bg-success {
    background: linear-gradient(135deg, var(--success-color), #27ae60);
}

.card-header.bg-warning {
    background: linear-gradient(135deg, var(--warning-color), #e67e22);
}

.card-header.bg-purple {
    background: linear-gradient(135deg, var(--purple-color), #8e44ad);
}

.card-body {
    padding: 20px; /* Giảm padding */
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    padding: 15px 20px; /* Giảm padding */
    text-align: center;
}

/* CALENDAR - GỌN GÀNG HƠN */
.calendar-header {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    border-radius: 6px;
    padding: 10px 12px;
    margin: 10px 0;
    text-align: left;
}

.calendar-header-day {
    padding: 6px 4px;
    font-weight: 600;
    color: #495057;
    text-align: center;
    font-size: 0.85rem;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    min-height: 100px; /* Giảm từ 500px xuống 400px */
    margin: 10px 0;
}

.calendar-day {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    min-height: 20px; /* Giảm từ 120px xuống 90px */
    background-color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    text-align: left;
}

.day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    padding-bottom: 4px;
    border-bottom: 1px solid #eee;
}

.day-number {
    font-size: 1rem;
    font-weight: bold;
    color: #495057;
}

.today-badge {
    font-size: 0.65rem;
    padding: 2px 5px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 8px;
    font-weight: 500;
}

.day-events {
    flex: 1;
    overflow: hidden;
}

.event-item {
    font-size: 0.75rem;
    padding: 2px 4px;
    margin-bottom: 3px;
    border-radius: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    gap: 4px;
}

.event-dot {
    font-size: 0.5rem;
    flex-shrink: 0;
}

.event-title {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
}

.event-more {
    font-size: 0.7rem;
    padding: 2px 0;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 3px;
    margin-top: 3px;
}

/* Giảm kích thước card header */
.card-header {
    padding: 12px 15px;
}

.card-header h5 {
    font-size: 1rem;
    margin: 0;
}

/* Giảm padding trong card-body */
.card-body {
    padding: 15px;
}

/* Legend nhỏ hơn */
.calendar-legend {
    margin-top: 15px;
    font-size: 0.85rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.legend-dot.tour { background-color: #28a745; }
.legend-dot.busy { background-color: #ffc107; }
.legend-dot.off { background-color: #dc3545; }
.legend-dot.today { background-color: #007bff; }

.calendar-stats {
    font-size: 0.8rem;
}

/* Nút trong calendar header nhỏ hơn */
.card-header .btn-sm {
    padding: 4px 8px;
    font-size: 0.8rem;
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .calendar-grid {
        grid-template-columns: repeat(1, 1fr);
        min-height: auto;
        gap: 6px;
    }
    
    .calendar-day {
        min-height: auto;
        padding: 6px;
    }
    
    .calendar-header {
        padding: 8px 10px;
    }
    
    .calendar-header-day {
        font-size: 0.8rem;
        padding: 4px 2px;
    }
}

/* For tablets */
@media (min-width: 769px) and (max-width: 1200px) {
    .calendar-grid {
        grid-template-columns: repeat(4, 1fr);
        min-height: 350px;
    }
}

/* Thêm style cho các event type */
.event-tour {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 2px solid #28a745;
}

.event-busy {
    background-color: rgba(255, 193, 7, 0.1);
    border-left: 2px solid #ffc107;
}

.event-off {
    background-color: rgba(220, 53, 69, 0.1);
    border-left: 2px solid #dc3545;
}

/* ROW VÀ COLUMN - SÁT HƠN */
.row {
    margin-left: -12px; /* Giảm negative margin */
    margin-right: -12px;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start; /* Thay đổi từ center thành flex-start */
}

.row > div {
    padding-left: 12px; /* Giảm padding */
    padding-right: 12px;
    margin-bottom: 20px; /* Giảm margin */
}

/* BUTTON - NHỎ GỌN HƠN */
.btn {
    border-radius: 6px; /* Giảm border-radius */
    padding: 8px 16px; /* Giảm padding */
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px; /* Giảm gap */
    font-size: 0.9rem; /* Giảm kích thước font */
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .page-content {
        margin-left: 0;
        padding: 10px; /* Giảm padding */
    }
    
    .dashboard-container {
        padding: 0 10px; /* Giảm padding */
    }
    
    .calendar-grid {
        grid-template-columns: repeat(1, 1fr);
        min-height: auto;
        gap: 8px; /* Giảm gap */
    }
    
    .row {
        margin-left: -8px; /* Giảm negative margin */
        margin-right: -8px;
    }
    
    .row > div {
        padding-left: 8px; /* Giảm padding */
        padding-right: 8px;
        margin-bottom: 15px; /* Giảm margin */
    }
    
    .page-title {
        font-size: 1.5rem; /* Giảm kích thước font */
        margin-bottom: 15px; /* Giảm margin */
    }
    
    .guide-info {
        padding: 15px; /* Giảm padding */
        margin-bottom: 15px; /* Giảm margin */
    }
}

@media (min-width: 769px) and (max-width: 1200px) {
    .calendar-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .page-content {
        margin-left: 220px; /* Giảm khoảng cách sidebar */
        padding: 12px; /* Giảm padding */
    }
}

/* THÊM STYLE CHO KHOẢNG CÁCH GẦN SIDEBAR */
@media (min-width: 1201px) {
    .page-content {
        margin-left: 100px; /* Giảm khoảng cách sidebar từ 250px xuống 220px */
        margin-right: 100px; /* Giảm khoảng cách sidebar từ 250px xuống 220px */
        padding: 15px 15px 15px 20px; /* Giảm padding phải, tăng padding trái */
    }
    
    .dashboard-container {
        padding-left: 10px; /* Thêm padding trái để không quá sát */
    }
}
</style>

<script>
// Hàm hiển thị sự kiện theo ngày
function showDayEvents(dateStr) {
    const date = new Date(dateStr);
    const formattedDate = date.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    let content = `<h5 class="mb-3">${formattedDate}</h5>`;
    
    // Kiểm tra có sự kiện không
    const events = <?= json_encode($eventsByDate ?? []) ?>;
    const dayEvents = events[dateStr] || [];
    
    if (dayEvents.length > 0) {
        content += '<div class="table-responsive">';
        content += '<table class="table table-hover">';
        content += '<thead><tr><th>Sự kiện</th><th>Loại</th><th>Ghi chú</th></tr></thead>';
        content += '<tbody>';
        
        dayEvents.forEach(event => {
            let badgeClass = 'bg-info';
            let eventTypeText = event.type;
            
            if (event.type === 'tour' || event.type === 'đã phân công') {
                badgeClass = 'bg-success';
                eventTypeText = 'Tour';
            } else if (event.type === 'bận') {
                badgeClass = 'bg-warning';
                eventTypeText = 'Bận';
            } else if (event.type === 'nghỉ') {
                badgeClass = 'bg-danger';
                eventTypeText = 'Nghỉ';
            }
            
            content += `
                <tr>
                    <td><strong>${event.title}</strong></td>
                    <td><span class="badge ${badgeClass}">${eventTypeText}</span></td>
                    <td>${event.ghi_chu || '-'}</td>
                </tr>
            `;
        });
        
        content += '</tbody></table></div>';
        
        // Thêm tour info nếu có
        const tourEvents = dayEvents.filter(e => e.type === 'tour' || e.type === 'đã phân công');
        if (tourEvents.length > 0) {
            content += '<div class="alert alert-info mt-3">';
            content += '<h6><i class="fas fa-info-circle me-2"></i>Thông tin tour:</h6>';
            content += '<ul class="mb-0">';
            tourEvents.forEach(event => {
                if (event.tour_data) {
                    content += `<li>${event.tour_data.ten_tour || 'Tour'} - ${event.tour_data.so_khach || 0} khách</li>`;
                }
            });
            content += '</ul></div>';
        }
    } else {
        content += '<div class="alert alert-info text-center py-4">';
        content += '<i class="fas fa-calendar-times fa-3x mb-3"></i>';
        content += '<p class="mb-0">Không có sự kiện nào trong ngày này</p>';
        content += '</div>';
    }
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('dayEventsModal'));
    document.getElementById('dayEventsModalTitle').textContent = `Sự kiện ngày ${formattedDate}`;
    document.getElementById('dayEventsModalBody').innerHTML = content;
    modal.show();
}

// Hàm đổi tháng
function changeCalendarMonth(direction) {
    const currentMonth = <?= date('m') ?>;
    const currentYear = <?= date('Y') ?>;
    
    let newMonth = currentMonth + direction;
    let newYear = currentYear;
    
    if (newMonth < 1) {
        newMonth = 12;
        newYear--;
    } else if (newMonth > 12) {
        newMonth = 1;
        newYear++;
    }
    
    window.location.href = `<?= BASE_URL_GUIDE ?>?act=dashboard&month=${newMonth}&year=${newYear}`;
}
</script>
