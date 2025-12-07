<?php
error_log("my_profile.php - Session data: " . print_r($_SESSION, true));
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$guideId = $_SESSION['guide_id'] ?? null;
$guideName = $_SESSION['guide_name'] ?? 'Hướng dẫn viên';

if (!$guideId) {
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}

// Lấy dữ liệu từ controller
$profile = $GLOBALS['profile'] ?? [];
$stats = $GLOBALS['stats'] ?? ['so_tour_da_dan' => 0, 'danh_gia_trung_binh' => 0];

// Cấu hình hiển thị trạng thái
$currentStatus = $profile['trang_thai'] ?? 'đang làm việc';

$statusConfig = [
    'đang làm việc' => [
        'class' => 'dang-lam-viec',
        'text' => 'Đang làm việc',
        'desc' => 'Sẵn sàng nhận tour'
    ],
    'tạm nghỉ' => [
        'class' => 'tam-nghi',
        'text' => 'Tạm nghỉ',
        'desc' => 'Tạm thời không nhận tour'
    ],
    'nghỉ việc' => [
        'class' => 'nghi-viec',
        'text' => 'Nghỉ việc',
        'desc' => 'Ngừng làm việc vĩnh viễn'
    ]
];

$statusInfo = $statusConfig[$currentStatus] ?? $statusConfig['đang làm việc'];

// Hàm avatar
if (!function_exists('getDefaultAvatar')) {
    function getDefaultAvatar($name) {
        $initial = mb_substr($name, 0, 1, 'UTF-8');
        $colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffecd2', '#fcb69f', '#a8edea', '#fed6e3'];
        $colorIndex = ord($initial) % count($colors);
        $backgroundColor = $colors[$colorIndex];

        $svg = '<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 150">
            <rect width="150" height="150" fill="' . $backgroundColor . '" rx="8"/>
            <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="60" fill="white" 
                  text-anchor="middle" dominant-baseline="middle" font-weight="bold">' . $initial . '</text>
        </svg>';

        return "data:image/svg+xml;base64," . base64_encode($svg);
    }
}

if (!function_exists('getAvatarUrl')) {
    function getAvatarUrl($profile) {
        if (!empty($profile['hinh_anh'])) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $profile['hinh_anh'];
            if (file_exists($imagePath) && is_file($imagePath)) {
                return "http://localhost/pro1014" . $profile['hinh_anh'];
            }
        }
        return getDefaultAvatar($profile['ho_ten'] ?? 'HDV');
    }
}

$avatarUrl = getAvatarUrl($profile);
$languages = !empty($profile['ngon_ngu']) ? json_decode($profile['ngon_ngu'], true) : [];
?>

<main class="main-content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="page-header">
        <h1 class="page-title">Thông Tin Tài Khoản</h1>
        <div class="page-subtitle">Quản lý thông tin cá nhân và cập nhật hồ sơ</div>
    </div>

    <div class="profile-container">
        <!-- Thông tin cơ bản -->
        <div class="profile-section">
            <div class="section-header">
                <h2 class="section-title">Thông Tin Cá Nhân</h2>
            </div>
            <div class="section-content">
                <div class="profile-main-info">
                    <div class="profile-avatar-large">
                        <img src="<?= $avatarUrl ?>"
                            alt="Avatar của <?= htmlspecialchars($profile['ho_ten'] ?? $guideName) ?>"
                            onerror="this.src='<?= getDefaultAvatar($profile['ho_ten'] ?? 'HDV') ?>'">
                    </div>
                    <div class="profile-main-details">
                        <h2 class="profile-name"><?= htmlspecialchars($profile['ho_ten'] ?? $guideName) ?></h2>
                        <p class="profile-role">
                            <?= !empty($profile['loai_huong_dan_vien']) ? htmlspecialchars($profile['loai_huong_dan_vien']) : 'Hướng dẫn viên' ?>
                        </p>
                        
                        <!-- Hiển thị trạng thái làm việc -->
                        <div class="work-status">
                            <div class="status-display">
                                <span class="status-badge status-<?= $statusInfo['class'] ?>">
                                    <i class="fas fa-circle"></i> <?= $statusInfo['text'] ?>
                                </span>
                                <div class="status-description">
                                    <i class="fas fa-info-circle"></i> <?= $statusInfo['desc'] ?>
                                </div>
                            </div>
                        </div>

                        <div class="profile-stats">
                            <div class="profile-stat">
                                <span class="stat-number"><?= $stats['so_tour_da_dan'] ?? 0 ?></span>
                                <span class="stat-label">Tour đã dẫn</span>
                            </div>
                            <div class="profile-stat">
                                <span class="stat-number"><?= number_format($stats['danh_gia_trung_binh'] ?? 0, 1) ?></span>
                                <span class="stat-label">Đánh giá TB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="profile-section">
            <div class="section-header">
                <h2 class="section-title">Thông Tin Liên Hệ</h2>
            </div>
            <div class="section-content">
                <div class="contact-grid">
                    <div class="contact-item">
                        <div class="contact-label">EMAIL</div>
                        <div class="contact-value"><?= !empty($profile['email']) ? htmlspecialchars($profile['email']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">SỐ ĐIỆN THOẠI</div>
                        <div class="contact-value"><?= !empty($profile['so_dien_thoai']) ? htmlspecialchars($profile['so_dien_thoai']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">ĐỊA CHỈ</div>
                        <div class="contact-value"><?= !empty($profile['dia_chi']) ? htmlspecialchars($profile['dia_chi']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-label">NGÀY SINH</div>
                        <div class="contact-value"><?= !empty($profile['ngay_sinh']) && $profile['ngay_sinh'] != '0000-00-00' ? date('d/m/Y', strtotime($profile['ngay_sinh'])) : 'Chưa cập nhật' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chuyên môn -->
        <div class="profile-section">
            <div class="section-header">
                <h2 class="section-title">Thông Tin Chuyên Môn</h2>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">LOẠI HDV</div>
                        <div class="info-value"><?= !empty($profile['loai_huong_dan_vien']) ? htmlspecialchars($profile['loai_huong_dan_vien']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">SỐ GIẤY PHÉP</div>
                        <div class="info-value"><?= !empty($profile['so_giay_phep_hanh_nghe']) ? htmlspecialchars($profile['so_giay_phep_hanh_nghe']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NGÀY CẤP GIẤY PHÉP</div>
                        <div class="info-value"><?= !empty($profile['ngay_cap_giay_phep']) && $profile['ngay_cap_giay_phep'] != '0000-00-00' ? date('d/m/Y', strtotime($profile['ngay_cap_giay_phep'])) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NƠI CẤP GIẤY PHÉP</div>
                        <div class="info-value"><?= !empty($profile['noi_cap_giay_phep']) ? htmlspecialchars($profile['noi_cap_giay_phep']) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">NGÔN NGỮ</div>
                        <div class="info-value">
                            <?php
                            if (!empty($languages) && is_array($languages)) {
                                $languageNames = [
                                    'vi' => 'Tiếng Việt',
                                    'en' => 'Tiếng Anh',
                                    'zh' => 'Tiếng Trung',
                                    'ja' => 'Tiếng Nhật',
                                    'ko' => 'Tiếng Hàn',
                                    'fr' => 'Tiếng Pháp'
                                ];

                                $displayLanguages = [];
                                foreach ($languages as $langCode) {
                                    if (isset($languageNames[$langCode])) {
                                        $displayLanguages[] = $languageNames[$langCode];
                                    } else {
                                        $displayLanguages[] = $langCode;
                                    }
                                }
                                echo htmlspecialchars(implode(', ', $displayLanguages));
                            } else {
                                echo 'Chưa cập nhật';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">KINH NGHIỆM</div>
                        <div class="info-value"><?= !empty($profile['kinh_nghiem']) ? nl2br(htmlspecialchars($profile['kinh_nghiem'])) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">CHUYÊN MÔN</div>
                        <div class="info-value"><?= !empty($profile['chuyen_mon']) ? nl2br(htmlspecialchars($profile['chuyen_mon'])) : 'Chưa cập nhật' ?></div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">TÌNH TRẠNG SỨC KHỎE</div>
                        <div class="info-value"><?= !empty($profile['tinh_trang_suc_khoe']) ? nl2br(htmlspecialchars($profile['tinh_trang_suc_khoe'])) : 'Chưa cập nhật' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Styles cho hiển thị trạng thái */
.work-status {
    margin: 1rem 0 1.5rem 0;
}

.status-display {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge i {
    font-size: 10px;
}

.status-badge.status-dang-lam-viec {
    background: #d4edda;
    color: #155724;
    border: 2px solid #28a745;
}

.status-badge.status-tam-nghi {
    background: #fff3cd;
    color: #856404;
    border: 2px solid #ffc107;
}

.status-badge.status-nghi-viec {
    background: #f8d7da;
    color: #721c24;
    border: 2px solid #dc3545;
}

.status-description {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 14px;
    font-style: italic;
}

.status-description i {
    color: #17a2b8;
}

/* CSS cơ bản giữ nguyên */
.main-content {
    padding: 0;
    margin: 0;
    width: 100%;
    max-width: none;
}

.profile-container {
    max-width: none;
    margin: 0;
    padding: 0 20px;
    width: 100%;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem 20px 1rem 20px;
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 0.5rem 0;
}

.page-subtitle {
    font-size: 1.1rem;
    color: #718096;
}

.profile-section {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin: 0 20px 1.5rem 20px;
}

.section-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.section-header .section-title {
    margin: 0;
    color: white;
    font-size: 1.4rem;
    font-weight: 600;
}

.section-content {
    padding: 2rem;
}

.profile-main-info {
    display: flex;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.profile-avatar-large {
    flex-shrink: 0;
}

.profile-avatar-large img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.profile-main-details {
    flex: 1;
    min-width: 300px;
}

.profile-name {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
}

.profile-role {
    margin: 0 0 1.5rem 0;
    font-size: 1.1rem;
    color: #667eea;
    font-weight: 500;
    text-transform: capitalize;
}

.profile-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.profile-stat {
    text-align: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    min-width: 120px;
    border: 1px solid #e2e8f0;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.contact-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.contact-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.5;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 20px 1.5rem 20px;
}

.alert-success {
    background: #c6f6d5;
    color: #276749;
    border: 1px solid #9ae6b4;
}

.alert-error {
    background: #fed7d7;
    color: #9b2c2c;
    border: 1px solid #feb2b2;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-section {
        margin: 0 15px 1.5rem 15px;
    }
    
    .alert {
        margin: 0 15px 1.5rem 15px;
    }
    
    .page-header {
        padding: 2rem 15px 1rem 15px;
    }
    
    .profile-main-info {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .profile-stats {
        justify-content: center;
        gap: 1rem;
    }

    .profile-stat {
        min-width: 100px;
        padding: 0.75rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .contact-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .section-content {
        padding: 1.5rem;
    }

    .section-header {
        padding: 1.25rem 1.5rem;
    }

    .page-title {
        font-size: 2rem;
    }
    
    .profile-avatar-large img {
        width: 100px;
        height: 100px;
    }
    
    .profile-name {
        font-size: 1.5rem;
    }
    
    .status-display {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .profile-section {
        margin: 0 10px 1.5rem 10px;
    }
    
    .alert {
        margin: 0 10px 1.5rem 10px;
    }
    
    .page-header {
        padding: 2rem 10px 1rem 10px;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .profile-stats {
        flex-direction: column;
        align-items: center;
    }
    
    .profile-stat {
        width: 100%;
        max-width: 200px;
    }
    
    .section-content {
        padding: 1rem;
    }
    
    .section-header {
        padding: 1rem 1.25rem;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>