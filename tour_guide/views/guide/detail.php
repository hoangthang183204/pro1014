<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$request = $GLOBALS['request'] ?? [];
?>

<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Chi Tiết Yêu Cầu Nghỉ</h1>
        <a href="<?= BASE_URL_GUIDE ?>?act=bao-nghi" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <div class="profile-container">
        <div class="profile-section">
            <div class="section-content">
                <div class="request-detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Mã yêu cầu:</div>
                        <div class="detail-value"><?= htmlspecialchars($request['ma_yeu_cau']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Loại nghỉ:</div>
                        <div class="detail-value"><?= htmlspecialchars($request['loai_nghi']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Ngày bắt đầu:</div>
                        <div class="detail-value"><?= date('d/m/Y', strtotime($request['ngay_bat_dau'])) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Ngày kết thúc:</div>
                        <div class="detail-value">
                            <?= !empty($request['ngay_ket_thuc']) && $request['ngay_ket_thuc'] != $request['ngay_bat_dau'] 
                                ? date('d/m/Y', strtotime($request['ngay_ket_thuc'])) 
                                : '1 ngày' ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Trạng thái:</div>
                        <div class="detail-value">
                            <?php
                            $statusColors = [
                                'cho_duyet' => 'warning',
                                'da_duyet' => 'success',
                                'tu_choi' => 'danger',
                                'da_huy' => 'secondary'
                            ];
                            $statusTexts = [
                                'cho_duyet' => 'Chờ duyệt',
                                'da_duyet' => 'Đã duyệt',
                                'tu_choi' => 'Từ chối',
                                'da_huy' => 'Đã hủy'
                            ];
                            ?>
                            <span class="badge badge-<?= $statusColors[$request['trang_thai']] ?? 'secondary' ?>">
                                <?= $statusTexts[$request['trang_thai']] ?? $request['trang_thai'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-item full-width">
                        <div class="detail-label">Lý do:</div>
                        <div class="detail-value"><?= nl2br(htmlspecialchars($request['ly_do'])) ?></div>
                    </div>
                    
                    <?php if (!empty($request['file_dinh_kem'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">File đính kèm:</div>
                        <div class="detail-value">
                            <a href="<?= htmlspecialchars($request['file_dinh_kem']) ?>" 
                               target="_blank" class="btn btn-sm btn-outline">
                                <i class="fas fa-download"></i> Tải xuống
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($request['phan_hoi_admin'])): ?>
                    <div class="detail-item full-width">
                        <div class="detail-label">Phản hồi từ admin:</div>
                        <div class="detail-value admin-feedback">
                            <?= nl2br(htmlspecialchars($request['phan_hoi_admin'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.request-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5;
}

.admin-feedback {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding: 1rem 0;
    border-bottom: 2px solid #667eea;
}

.page-title {
    color: #2d3748;
    margin: 0;
    font-size: 1.8rem;
    font-weight: 600;
}

/* Nút Quay lại */
.btn-outline {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

/* ===== CARD CHI TIẾT ===== */
.profile-section {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    border: 1px solid #e2e8f0;
}

.section-content {
    padding: 0;
}

/* ===== GRID CHI TIẾT ===== */
.request-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.detail-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
}

/* ===== BADGE TRẠNG THÁI ===== */
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-secondary {
    background: #e5e7eb;
    color: #374151;
}

/* ===== NÚT TẢI XUỐNG ===== */
.btn-sm {
    padding: 8px 16px;
    font-size: 0.9rem;
}

.btn-outline.btn-sm {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.btn-outline.btn-sm:hover {
    background: #667eea;
    color: white;
}

/* ===== PHẢN HỒI ADMIN ===== */
.admin-feedback {
    background: #f1f5f9;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    margin-top: 0.5rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .request-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-section {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .profile-section {
        padding: 1rem;
    }
    
    .detail-item {
        padding: 0.75rem;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>