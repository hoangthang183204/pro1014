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
                <span class="badge badge-primary">Chờ lịch: <?= $thongKe['cho_len_lich'] ?></span>
                <span class="badge badge-success">Đang diễn ra: <?= $thongKe['dang_dien_ra'] ?></span>
                <span class="badge badge-secondary">Đã hoàn thành: <?= $thongKe['da_hoan_thanh'] ?></span>
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
.lich-trinh-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e1e5e9;
}

.lich-trinh-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.tour-info p {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    color: #6c757d;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
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