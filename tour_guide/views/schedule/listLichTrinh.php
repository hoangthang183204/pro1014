<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<main class="main-content">
    <div class="container-fluid">
        <!-- Tiêu đề chính -->
        <div class="page-header mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h1 class="page-title mb-2">📅 Lịch Trình Tour Của Tôi</h1>
                    <p class="text-muted mb-0">Quản lý và theo dõi các tour bạn được phân công</p>
                </div>
                
                <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-lam-viec" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt mr-2"></i> Lịch làm việc
                    </a>
                </div>
            </div>
        </div>

        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card bg-gradient-primary">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="stats-value"><?= $thongKe['cho_len_lich'] ?></h3>
                            <p class="stats-label mb-0">Tour chờ lịch</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card bg-gradient-success">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="stats-value"><?= $thongKe['dang_dien_ra'] ?></h3>
                            <p class="stats-label mb-0">Tour đang diễn ra</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card bg-gradient-secondary">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="stats-value"><?= $thongKe['da_hoan_thanh'] ?></h3>
                            <p class="stats-label mb-0">Tour đã hoàn thành</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Bộ lọc và tìm kiếm -->
<div class="card filter-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter mr-2"></i> Tìm kiếm & Lọc tour
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="search-box">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" 
                           class="form-control search-input" 
                           placeholder="Tìm kiếm tour theo tên, mã tour..." 
                           id="searchTours">
                    <button class="search-clear" type="button" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="filter-dropdown">
                    <div class="filter-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <select class="form-control filter-select" id="filterStatus">
                        <option value="">Tất cả trạng thái</option>
                        <option value="cho_len_lich">📅 Tour chờ lịch</option>
                        <option value="dang_dien_ra">▶️ Tour đang diễn ra</option>
                        <option value="da_hoan_thanh">✅ Tour đã hoàn thành</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-outline-secondary btn-filter w-100" id="resetFilter">
                    <i class="fas fa-redo mr-1"></i> Đặt lại
                </button>
            </div>
        </div>
        
        <!-- Bộ lọc nâng cao (tùy chọn) -->
        <div class="advanced-filters mt-3" id="advancedFilters" style="display: none;">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Thời gian</label>
                    <select class="form-control" id="filterDate">
                        <option value="">Tất cả thời gian</option>
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                        <option value="upcoming">Sắp diễn ra</option>
                        <option value="past">Đã qua</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Danh mục</label>
                    <select class="form-control" id="filterCategory">
                        <option value="">Tất cả danh mục</option>
                        <!-- Các option danh mục sẽ được thêm bằng JavaScript nếu cần -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sắp xếp</label>
                    <select class="form-control" id="sortBy">
                        <option value="date_asc">Ngày bắt đầu (cũ nhất)</option>
                        <option value="date_desc">Ngày bắt đầu (mới nhất)</option>
                        <option value="name_asc">Tên tour (A-Z)</option>
                        <option value="name_desc">Tên tour (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Nút hiển thị bộ lọc nâng cao -->
        <div class="text-center mt-3">
            <a href="#" class="text-decoration-none" id="toggleAdvancedFilters">
                <i class="fas fa-cogs mr-1"></i>
                <span>Bộ lọc nâng cao</span>
                <i class="fas fa-chevron-down ml-1"></i>
            </a>
        </div>
    </div>
</div>

        <!-- Danh sách lịch trình -->
        <div class="section-title mb-3">
            <h4 class="mb-0">
                <i class="fas fa-list-alt mr-2 text-primary"></i>
                Danh sách tour
                <span class="badge badge-light ml-2" id="tourCount"><?= count($lichTrinhList) ?></span>
            </h4>
        </div>

        <?php if (empty($lichTrinhList)): ?>
            <div class="empty-state-card">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h3 class="empty-state-title">Chưa có lịch trình nào</h3>
                <p class="empty-state-text mb-4">Bạn chưa được phân công tour nào sắp tới.</p>
                <a href="<?= BASE_URL_GUIDE ?>?act=profile" class="btn btn-primary">
                    <i class="fas fa-user-edit mr-2"></i> Cập nhật hồ sơ để nhận tour
                </a>
            </div>
        <?php else: ?>
            <div class="row" id="tourList">
                <?php foreach ($lichTrinhList as $tour): ?>
                    <?php
                    // Xác định màu sắc dựa trên trạng thái
                    $statusClass = '';
                    $statusIcon = '';
                    $statusColor = '';
                    
                    switch(strtolower($tour['trang_thai_lich'])) {
                        case 'đang diễn ra':
                            $statusClass = 'status-active';
                            $statusIcon = 'play-circle';
                            $statusColor = '#10b981';
                            break;
                        case 'đã hoàn thành':
                            $statusClass = 'status-completed';
                            $statusIcon = 'check-circle';
                            $statusColor = '#6b7280';
                            break;
                        default:
                            $statusClass = 'status-pending';
                            $statusIcon = 'clock';
                            $statusColor = '#3b82f6';
                    }
                    ?>
                    
                    <div class="col-md-6 col-xl-4 mb-4 tour-item" 
                         data-status="<?= strtolower($tour['trang_thai_lich']) ?>"
                         data-name="<?= strtolower($tour['ten_tour']) ?>">
                        <div class="tour-card h-100">
                            <!-- Header với status indicator -->
                            <div class="tour-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="tour-status-indicator <?= $statusClass ?>">
                                        <i class="fas fa-<?= $statusIcon ?> mr-2"></i>
                                        <span class="status-text"><?= strtoupper($tour['trang_thai_lich']) ?></span>
                                    </div>
                                    <span class="confirmation-badge badge-<?= $tour['trang_thai_xac_nhan'] == 'đã xác nhận' ? 'confirmed' : 'pending' ?>">
                                        <?= $tour['trang_thai_xac_nhan'] ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Nội dung chính -->
                            <div class="tour-card-body">
                                <!-- Mã tour và tên -->
                                <div class="tour-code mb-2">
                                    <span class="code-badge"><?= $tour['ma_tour'] ?></span>
                                    <span class="tour-category"><?= $tour['ten_danh_muc'] ?></span>
                                </div>
                                
                                <h5 class="tour-title"><?= htmlspecialchars($tour['ten_tour']) ?></h5>
                                
                                <!-- Thông tin chi tiết -->
                                <div class="tour-details">
                                    <div class="detail-item">
                                        <i class="far fa-calendar-alt text-primary"></i>
                                        <div>
                                            <span class="detail-label">Thời gian</span>
                                            <span class="detail-value">
                                                <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?> 
                                                - <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="far fa-clock text-primary"></i>
                                        <div>
                                            <span class="detail-label">Giờ tập trung</span>
                                            <span class="detail-value">
                                                <?= !empty($tour['gio_tap_trung']) ? date('H:i', strtotime($tour['gio_tap_trung'])) : 'Chưa cập nhật' ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <div>
                                            <span class="detail-label">Điểm tập trung</span>
                                            <span class="detail-value">
                                                <?= !empty($tour['diem_tap_trung']) ? htmlspecialchars($tour['diem_tap_trung']) : 'Chưa cập nhật' ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="fas fa-users text-primary"></i>
                                        <div>
                                            <span class="detail-label">Số khách</span>
                                            <span class="detail-value">
                                                <?= $tour['so_cho_con_lai'] ?>/<?= $tour['so_cho_toi_da'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Ghi chú phân công (nếu có) -->
                                <?php if (!empty($tour['ghi_chu_phan_cong'])): ?>
                                    <div class="assignment-note">
                                        <div class="note-header">
                                            <i class="fas fa-sticky-note text-info"></i>
                                            <span>Ghi chú phân công</span>
                                        </div>
                                        <p class="note-text"><?= htmlspecialchars($tour['ghi_chu_phan_cong']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Footer với button action -->
                            <div class="tour-card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="tour-duration">
                                        <?php
                                        $start = new DateTime($tour['ngay_bat_dau']);
                                        $end = new DateTime($tour['ngay_ket_thuc']);
                                        $diff = $start->diff($end);
                                        echo ($diff->days + 1) . ' ngày ' . $diff->days . ' đêm';
                                        ?>
                                    </div>
                                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh-detail&id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* ===== BASE & TYPOGRAPHY ===== */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f5f7fa;
    color: #1e293b;
}

.main-content {
    padding: 20px;
}

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

.page-header .text-muted {
    color: #64748b !important;
    font-size: 15px;
    font-weight: 400;
}

/* ===== STATS CARDS ===== */
.stats-card {
    border-radius: 12px;
    padding: 20px;
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-4px);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}

.stats-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stats-value {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stats-label {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 4px;
}

/* ===== FILTER CARD ===== */
.filter-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.filter-card .input-group-text {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.filter-card .form-control {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

.filter-card .form-control:focus {
    box-shadow: none;
    border-color: #ced4da;
}

/* ===== SECTION TITLE ===== */
.section-title {
    padding: 16px 0;
    border-bottom: 2px solid #e2e8f0;
}

.section-title h4 {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 18px;
}

/* ===== TOUR CARDS ===== */
.tour-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tour-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    border-color: #cbd5e1;
}

.tour-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

.tour-status-indicator {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: #d1fae5;
    color: #059669;
}

.status-pending {
    background: #dbeafe;
    color: #3b82f6;
}

.status-completed {
    background: #f1f5f9;
    color: #64748b;
}

.confirmation-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-confirmed {
    background: #d1fae5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.badge-pending {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
}

.tour-card-body {
    padding: 20px;
    flex: 1;
}

.tour-code {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.code-badge {
    background: #e0e7ff;
    color: #4f46e5;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

.tour-category {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.tour-title {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 18px;
    margin-bottom: 20px;
    line-height: 1.4;
    min-height: 50px;
}

.tour-details {
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
}

.detail-item i {
    margin-top: 2px;
    margin-right: 12px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.detail-label {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 2px;
}

.detail-value {
    display: block;
    color: #1e293b !important;
    font-size: 14px;
    font-weight: 500;
}

.assignment-note {
    background: #f0f9ff;
    border-radius: 8px;
    padding: 12px;
    margin-top: 16px;
    border-left: 3px solid #0ea5e9;
}

.note-header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.note-header i {
    margin-right: 8px;
    font-size: 14px;
}

.note-header span {
    color: #0c4a6e;
    font-size: 13px;
    font-weight: 600;
}

.note-text {
    color: #1e293b !important;
    font-size: 13px;
    line-height: 1.5;
    margin: 0;
}

.tour-card-footer {
    padding: 16px 20px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
}

.tour-duration {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border: none;
    border-radius: 8px;
    font-weight: 500;
    padding: 8px 16px;
    font-size: 14px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

/* ===== EMPTY STATE ===== */
.empty-state-card {
    background: white;
    border-radius: 16px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin: 40px 0;
    border: 2px dashed #e2e8f0;
}

.empty-state-icon {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 24px;
}

.empty-state-title {
    color: #1e293b !important;
    font-weight: 600;
    font-size: 24px;
    margin-bottom: 12px;
}

.empty-state-text {
    color: #64748b !important;
    font-size: 16px;
    max-width: 500px;
    margin: 0 auto;
}

/* ===== ALERTS ===== */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.alert-danger {
    background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
    color: #991b1b;
    border-left: 4px solid #dc2626;
}

.alert-success {
    background: linear-gradient(135deg, #bbf7d0 0%, #86efac 100%);
    color: #065f46;
    border-left: 4px solid #059669;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .stats-card {
        padding: 16px;
    }
    
    .stats-value {
        font-size: 24px;
    }
    
    .tour-card-header,
    .tour-card-body,
    .tour-card-footer {
        padding: 16px;
    }
    
    .tour-title {
        font-size: 16px;
    }
}

/* ===== PRINT STYLES ===== */
@media print {
    .btn,
    .filter-card,
    .stats-card,
    .section-title {
        display: none !important;
    }
    
    .tour-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}

/* ===== ANIMATIONS ===== */
.tour-item {
    animation: slideUp 0.4s ease forwards;
    opacity: 0;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tạo delay cho animation */
.tour-item:nth-child(1) { animation-delay: 0.1s; }
.tour-item:nth-child(2) { animation-delay: 0.2s; }
.tour-item:nth-child(3) { animation-delay: 0.3s; }
.tour-item:nth-child(4) { animation-delay: 0.4s; }
.tour-item:nth-child(5) { animation-delay: 0.5s; }
.tour-item:nth-child(6) { animation-delay: 0.6s; }

.filter-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.filter-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.filter-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
    padding: 16px 24px;
}

.filter-card .card-header h5 {
    color: white !important;
    font-weight: 600;
    margin: 0;
}

.filter-card .card-header i {
    color: rgba(255, 255, 255, 0.9);
}

.filter-card .card-body {
    padding: 24px;
}

/* ===== SEARCH BOX ===== */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.search-box:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    padding: 0 16px;
    color: #94a3b8;
    font-size: 16px;
}

.search-input {
    border: none !important;
    padding: 12px 0;
    font-size: 15px;
    color: #1e293b;
    background: transparent;
    flex: 1;
    outline: none;
    box-shadow: none !important;
}

.search-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 14px;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
}

.search-clear:hover {
    background: #f1f5f9;
    color: #64748b;
}

.search-input:not(:placeholder-shown) ~ .search-clear {
    opacity: 1;
    visibility: visible;
}

/* ===== FILTER DROPDOWN ===== */
.filter-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.filter-dropdown:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-icon {
    padding: 0 16px;
    color: #94a3b8;
    font-size: 16px;
    background: #f8fafc;
    height: 100%;
    display: flex;
    align-items: center;
    border-right: 2px solid #e2e8f0;
}

.filter-select {
    border: none !important;
    padding: 12px 16px;
    font-size: 15px;
    color: #1e293b;
    background: transparent;
    flex: 1;
    outline: none;
    box-shadow: none !important;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
}

.filter-select option {
    padding: 8px;
    font-size: 14px;
}

/* ===== FILTER BUTTONS ===== */
.btn-filter {
    height: 100%;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    font-weight: 500;
    color: #64748b;
    background: white;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-filter:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #475569;
    transform: translateY(-1px);
}

/* ===== ADVANCED FILTERS ===== */
.advanced-filters {
    padding: 20px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.advanced-filters .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
}

.advanced-filters .form-control {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #1e293b;
    background: white;
    transition: all 0.3s ease;
}

.advanced-filters .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* ===== TOGGLE ADVANCED FILTERS ===== */
#toggleAdvancedFilters {
    color: #3b82f6;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

#toggleAdvancedFilters:hover {
    color: #2563eb;
    text-decoration: none;
}

#toggleAdvancedFilters i {
    font-size: 12px;
    transition: transform 0.3s ease;
}

#toggleAdvancedFilters.active i.fa-chevron-down {
    transform: rotate(180deg);
}

/* ===== ACTIVE FILTER BADGES ===== */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    display: none;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
}

.filter-badge .badge-remove {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 12px;
    cursor: pointer;
    padding: 2px;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-badge .badge-remove:hover {
    background: #f1f5f9;
    color: #64748b;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .filter-card .card-body {
        padding: 16px;
    }
    
    .search-box,
    .filter-dropdown,
    .btn-filter {
        height: 48px;
    }
    
    .search-input,
    .filter-select {
        padding: 12px 8px;
        font-size: 14px;
    }
    
    .search-icon,
    .filter-icon {
        padding: 0 12px;
    }
    
    .advanced-filters {
        padding: 16px;
    }
    
    .advanced-filters .form-control {
        padding: 8px 12px;
    }
}

/* ===== LOADING STATE ===== */
.filter-loading {
    position: absolute;
    top: 50%;
    right: 16px;
    transform: translateY(-50%);
    color: #3b82f6;
    font-size: 14px;
    display: none;
}

.filter-loading.active {
    display: block;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* ===== PLACEHOLDER TEXT ===== */
.search-input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: #94a3b8;
    font-weight: 400;
}
.search-input::-moz-placeholder { /* Firefox 19+ */
    color: #94a3b8;
    font-weight: 400;
}
.search-input:-ms-input-placeholder { /* IE 10+ */
    color: #94a3b8;
    font-weight: 400;
}
.search-input:-moz-placeholder { /* Firefox 18- */
    color: #94a3b8;
    font-weight: 400;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchTours');
    const clearSearch = document.getElementById('clearSearch');
    const filterSelect = document.getElementById('filterStatus');
    const tourItems = document.querySelectorAll('.tour-item');
    const tourCount = document.getElementById('tourCount');
    const resetFilter = document.getElementById('resetFilter');
    const toggleAdvancedFilters = document.getElementById('toggleAdvancedFilters');
    const advancedFilters = document.getElementById('advancedFilters');
    
    // Hiển thị/ẩn bộ lọc nâng cao
    if (toggleAdvancedFilters) {
        toggleAdvancedFilters.addEventListener('click', function(e) {
            e.preventDefault();
            advancedFilters.style.display = advancedFilters.style.display === 'none' ? 'block' : 'none';
            this.classList.toggle('active');
            
            const chevron = this.querySelector('.fa-chevron-down');
            const text = this.querySelector('span');
            
            if (advancedFilters.style.display === 'block') {
                chevron.style.transform = 'rotate(180deg)';
                text.textContent = 'Ẩn bộ lọc nâng cao';
            } else {
                chevron.style.transform = 'rotate(0deg)';
                text.textContent = 'Bộ lọc nâng cao';
            }
        });
    }
    
    // Xóa nội dung tìm kiếm
    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            clearSearch.style.opacity = '0';
            clearSearch.style.visibility = 'hidden';
        });
    }
    
    // Hiển thị nút xóa khi có nội dung
    searchInput.addEventListener('input', function() {
        if (clearSearch) {
            if (this.value.trim() !== '') {
                clearSearch.style.opacity = '1';
                clearSearch.style.visibility = 'visible';
            } else {
                clearSearch.style.opacity = '0';
                clearSearch.style.visibility = 'hidden';
            }
        }
        filterTours();
    });
    
    // Đặt lại bộ lọc
    if (resetFilter) {
        resetFilter.addEventListener('click', function() {
            searchInput.value = '';
            filterSelect.value = '';
            
            // Reset các bộ lọc nâng cao
            const advancedSelects = advancedFilters.querySelectorAll('select');
            advancedSelects.forEach(select => {
                select.value = '';
            });
            
            // Ẩn nút xóa tìm kiếm
            if (clearSearch) {
                clearSearch.style.opacity = '0';
                clearSearch.style.visibility = 'hidden';
            }
            
            filterTours();
        });
    }
    
    // Hàm lọc tour với tìm kiếm đa tiêu chí
    function filterTours() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const filterValue = filterSelect.value;
        const filterDate = document.getElementById('filterDate')?.value || '';
        const filterCategory = document.getElementById('filterCategory')?.value || '';
        const sortBy = document.getElementById('sortBy')?.value || 'date_asc';
        
        let visibleCount = 0;
        const visibleItems = [];
        
        // Lọc theo các tiêu chí
        tourItems.forEach(item => {
            const tourName = item.dataset.name || '';
            const tourKeywords = item.dataset.keywords || '';
            const tourStatus = item.dataset.status || '';
            
            // Tìm kiếm theo tên hoặc từ khóa
            const matchSearch = searchTerm === '' || 
                tourName.includes(searchTerm) || 
                tourKeywords.includes(searchTerm);
            
            // Lọc theo trạng thái
            const matchFilter = !filterValue || tourStatus === filterValue;
            
            if (matchSearch && matchFilter) {
                item.style.display = 'block';
                visibleCount++;
                visibleItems.push({
                    element: item,
                    date: item.querySelector('.detail-value')?.textContent?.split('-')[0]?.trim() || '',
                    name: tourName
                });
            } else {
                item.style.display = 'none';
            }
        });
        
        // Cập nhật số lượng tour
        tourCount.textContent = visibleCount;
        
        // Sắp xếp các tour hiển thị
        if (visibleItems.length > 0) {
            sortTourItems(visibleItems, sortBy);
        }
    }
    
    // Hàm sắp xếp tour
    function sortTourItems(items, sortBy) {
        const container = document.querySelector('.row#tourList');
        
        // Tạo mảng clone để sắp xếp
        const sortedItems = [...items];
        
        sortedItems.sort((a, b) => {
            switch(sortBy) {
                case 'date_desc':
                    return new Date(b.date.split('/').reverse().join('-')) - 
                           new Date(a.date.split('/').reverse().join('-'));
                case 'date_asc':
                    return new Date(a.date.split('/').reverse().join('-')) - 
                           new Date(b.date.split('/').reverse().join('-'));
                case 'name_asc':
                    return a.name.localeCompare(b.name);
                case 'name_desc':
                    return b.name.localeCompare(a.name);
                default:
                    return 0;
            }
        });
        
        // Xóa và thêm lại các phần tử theo thứ tự đã sắp xếp
        sortedItems.forEach(item => {
            container.appendChild(item.element);
        });
    }
    
    // Thêm hiệu ứng tìm kiếm
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTours, 300); // Debounce 300ms
    });
    
    // Gọi filterTours khi thay đổi bộ lọc
    filterSelect.addEventListener('change', filterTours);
    
    // Lắng nghe sự kiện cho các bộ lọc nâng cao
    document.querySelectorAll('#filterDate, #filterCategory, #sortBy').forEach(element => {
        element.addEventListener('change', filterTours);
    });
    
    // Tìm kiếm nhanh bằng phím tắt
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F để focus vào ô tìm kiếm
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape để xóa tìm kiếm
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            filterTours();
        }
    });
    
    // Tự động focus vào ô tìm kiếm khi trang load (tùy chọn)
    // searchInput.focus();
    
    // Khởi tạo lọc ban đầu
    filterTours();
});

// Hàm bổ sung: highlight từ khóa tìm kiếm trong kết quả
function highlightSearchText() {
    const searchTerm = document.getElementById('searchTours').value.trim().toLowerCase();
    
    if (!searchTerm) return;
    
    document.querySelectorAll('.tour-title').forEach(title => {
        const originalText = title.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        const highlighted = originalText.replace(regex, '<mark class="search-highlight">$1</mark>');
        title.innerHTML = highlighted;
    });
}

// Thêm CSS cho highlight
const highlightStyle = document.createElement('style');
highlightStyle.textContent = `
    .search-highlight {
        background-color: #FFEB3B;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: bold;
    }
    
    .no-results {
        text-align: center;
        padding: 40px;
        color: #64748b;
    }
    
    .no-results i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #cbd5e1;
    }
    
    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
`;
document.head.appendChild(highlightStyle);

// Thêm xử lý hiển thị thông báo khi không có kết quả
function showNoResultsMessage() {
    const tourList = document.getElementById('tourList');
    const existingNoResults = document.querySelector('.no-results');
    
    if (existingNoResults) {
        existingNoResults.remove();
    }
    
    const visibleTours = document.querySelectorAll('.tour-item[style="display: block"]');
    
    if (visibleTours.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'col-12 no-results';
        noResults.innerHTML = `
            <i class="fas fa-search"></i>
            <h3>Không tìm thấy tour nào</h3>
            <p>Không có tour nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
            <button class="btn btn-outline-primary mt-2" onclick="resetFilters()">
                <i class="fas fa-redo mr-1"></i> Đặt lại bộ lọc
            </button>
        `;
        tourList.appendChild(noResults);
    }
}

// Hàm đặt lại tất cả bộ lọc
function resetFilters() {
    document.getElementById('searchTours').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterDate').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('sortBy').value = 'date_asc';
    
    if (document.getElementById('clearSearch')) {
        document.getElementById('clearSearch').style.opacity = '0';
        document.getElementById('clearSearch').style.visibility = 'hidden';
    }
    
    filterTours();
}

// Gắn hàm showNoResultsMessage vào cuối filterTours
const originalFilterTours = window.filterTours;
window.filterTours = function() {
    originalFilterTours();
    showNoResultsMessage();
    highlightSearchText();
};
</script>

<?php
include __DIR__ . '/../layout/footer.php';
?>