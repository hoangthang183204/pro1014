<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<main class="main-content">
    <div class="container-fluid">
        <!-- Tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">📅 Lịch Trình Tour Của Tôi</h1>
            <div class="thong-ke">
                <span class="badge badge-primary" style="color: green">Chờ lịch: <?= $thongKe['cho_len_lich'] ?></span>
                <span class="badge badge-success" style="color: green">Đang diễn ra: <?= $thongKe['dang_dien_ra'] ?></span>
                <span class="badge badge-secondary" style="color: green">Đã hoàn thành: <?= $thongKe['da_hoan_thanh'] ?></span>
            </div>
            <a href="<?= BASE_URL_GUIDE ?>?act=lich-lam-viec" class="btn btn-outline-info btn-sm">
            <i class="fas fa-calendar-alt mr-1"></i> Lịch làm việc
        </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Danh sách lịch trình -->
        <div class="row">
            <?php if (empty($lichTrinhList)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h3>Chưa có lịch trình nào</h3>
                        <p>Bạn chưa được phân công tour nào sắp tới.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($lichTrinhList as $tour): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card lich-trinh-card h-100"
                             data-end-date="<?= $tour['ngay_ket_thuc'] ?>">
                            <!-- Header card -->
                            <div class="card-header d-flex justify-content-between align-items-center"
                                 style="background: linear-gradient(135deg, <?= $tour['trang_thai_lich'] == 'đang diễn ra' ? '#4CAF50' : '#2196F3' ?>, <?= $tour['trang_thai_lich'] == 'đang diễn ra' ? '#8BC34A' : '#03A9F4' ?>);">
                                <span class="badge badge-light">
                                    <?= strtoupper($tour['trang_thai_lich']) ?>
                                </span>
                                <span class="badge badge-<?= $tour['trang_thai_xac_nhan'] == 'đã xác nhận' ? 'success' : 'warning' ?>">
                                    <?= $tour['trang_thai_xac_nhan'] ?>
                                </span>
                            </div>

                            <!-- Nội dung -->
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($tour['ten_tour']) ?></h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-tag mr-1"></i> <?= $tour['ten_danh_muc'] ?>
                                </p>
                                
                                <div class="tour-info">
                                    <p><i class="fas fa-calendar-alt mr-2"></i> 
                                        <strong>Từ:</strong> <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?>
                                        <br><span class="ml-4"><strong>Đến:</strong> <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?></span>
                                    </p>
                                    
                                    <p><i class="fas fa-clock mr-2"></i> 
                                        <strong>Giờ tập trung:</strong> <?= date('H:i', strtotime($tour['gio_tap_trung'])) ?>
                                    </p>
                                    
                                    <p><i class="fas fa-map-marker-alt mr-2"></i> 
                                        <strong>Điểm tập trung:</strong> <?= htmlspecialchars($tour['diem_tap_trung']) ?>
                                    </p>
                                    
                                    <p><i class="fas fa-users mr-2"></i> 
                                        <strong>Số chỗ:</strong> <?= $tour['so_cho_con_lai'] ?>/<?= $tour['so_cho_toi_da'] ?>
                                    </p>
                                </div>
                                
                                <?php if (!empty($tour['ghi_chu_phan_cong'])): ?>
                                    <div class="alert alert-info p-2 mt-2">
                                        <small><i class="fas fa-sticky-note mr-1"></i> <?= htmlspecialchars($tour['ghi_chu_phan_cong']) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Footer card -->
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh-detail&id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                    </a>
                                    <span class="text-muted align-self-center">
                                        Mã: <?= $tour['ma_tour'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
/* ===== TYPOGRAPHY & BASE ===== */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8fafc;
}

.page-title {
    color: #1a365d !important;
    font-weight: 700;
    font-size: 1.8rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #4299e1;
    display: inline-block;
    margin-bottom: 1.5rem;
}

/* ===== HEADER SECTION ===== */
.d-flex.justify-content-between.align-items-center.mb-4 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem !important;
}

.thong-ke {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.badge-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border: none;
}

.badge-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    border: none;
}

.badge-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
    color: white !important;
    border: none;
}

.badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* ===== TOUR CARDS ===== */
.lich-trinh-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
    height: 100%;
    background: white;
    position: relative;
}

.lich-trinh-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.lich-trinh-card:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

/* Card header với gradient đẹp */
.card-header {
    border: none;
    padding: 1rem 1.25rem;
    position: relative;
    overflow: hidden;
}

.card-header::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
}

/* ===== CARD CONTENT ===== */
.card-body {
    padding: 1.5rem;
}

.card-title {
    color: #1a365d !important;
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.card-text.text-muted {
    color: #6b7280 !important;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.card-text.text-muted i {
    color: #667eea;
    margin-right: 8px;
}

/* ===== TOUR INFO ===== */
.tour-info {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
}

.tour-info p {
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    color: #4a5568 !important;
    display: flex;
    align-items: flex-start;
    line-height: 1.5;
}

.tour-info p i {
    color: #667eea;
    margin-right: 10px;
    margin-top: 2px;
    min-width: 20px;
    text-align: center;
}

.tour-info strong {
    color: #2d3748 !important;
    font-weight: 600;
    min-width: 110px;
    display: inline-block;
}

/* ===== ALERT BOX ===== */
.alert {
    border: none;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.alert-info {
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
    border-left: 4px solid #4299e1;
    color: #2c5282 !important;
}

/* ===== CARD FOOTER ===== */
.card-footer {
    background: white !important;
    border-top: 1px solid #e2e8f0 !important;
    padding: 1rem 1.5rem;
    border-radius: 0 0 16px 16px !important;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(66, 153, 225, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(66, 153, 225, 0.4);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin: 2rem 0;
}

.empty-state i {
    color: #cbd5e0;
    margin-bottom: 1.5rem;
    font-size: 4rem;
}

.empty-state h3 {
    color: #2d3748 !important;
    font-weight: 700;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #718096 !important;
    font-size: 1.1rem;
}

/* ===== BADGE ENHANCEMENT ===== */
.card-header .badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.badge-light {
    background: rgba(255, 255, 255, 0.9) !important;
    color: #2d3748 !important;
    backdrop-filter: blur(10px);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .thong-ke {
        justify-content: center;
    }
    
    .tour-info p {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .tour-info strong {
        margin-bottom: 4px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.col-md-6.col-lg-4.mb-4 {
    animation: fadeIn 0.5s ease forwards;
}

.col-md-6.col-lg-4.mb-4:nth-child(1) { animation-delay: 0.1s; }
.col-md-6.col-lg-4.mb-4:nth-child(2) { animation-delay: 0.2s; }
.col-md-6.col-lg-4.mb-4:nth-child(3) { animation-delay: 0.3s; }
.col-md-6.col-lg-4.mb-4:nth-child(4) { animation-delay: 0.4s; }
.col-md-6.col-lg-4.mb-4:nth-child(5) { animation-delay: 0.5s; }
.col-md-6.col-lg-4.mb-4:nth-child(6) { animation-delay: 0.6s; }

/* ===== STATUS INDICATORS ===== */
/* Thêm indicator nhỏ cho trạng thái tour */
.lich-trinh-card::after {
    content: '';
    position: absolute;
    top: 10px;
    right: 10px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #48bb78; /* Mặc định xanh (đang diễn ra) */
}

.lich-trinh-card[data-end-date]::after {
    background: #4299e1; /* Xanh dương (chờ lịch) */
}

/* Override bằng JavaScript cho tour đã hoàn thành */
.lich-trinh-card.completed::after {
    background: #a0aec0; /* Xám (đã hoàn thành) */
}

/* ===== CUSTOM SCROLLBAR ===== */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

/* ===== BUTTON ENHANCEMENT ===== */
.btn-outline-info {
    color: #0c4a6e !important;
    border: 2px solid #0ea5e9;
    background: transparent;
    font-weight: 600;
}

.btn-outline-info:hover {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: white !important;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(14, 165, 233, 0.3);
}

/* ===== TOUR CODE STYLING ===== */
.text-muted.align-self-center {
    color: #718096 !important;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.25rem 0.5rem;
    background: #f7fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}
</style>


<script>
// Hàm format ngày
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Cập nhật trạng thái tự động
function updateTourStatus() {
    const cards = document.querySelectorAll('.lich-trinh-card');
    const now = new Date();
    
    cards.forEach(card => {
        const endDate = new Date(card.dataset.endDate);
        if (endDate < now) {
            const badge = card.querySelector('.badge-light');
            if (badge) {
                badge.textContent = 'ĐÃ HOÀN THÀNH';
                badge.className = 'badge badge-secondary';
            }
        }
    });
}

// Chạy khi trang load
document.addEventListener('DOMContentLoaded', function() {
    updateTourStatus();
});
</script>

<?php
include __DIR__ . '/../layout/footer.php';
?>