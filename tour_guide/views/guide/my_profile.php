<?php
// Tệp: views/guide/my_profile.php

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$guideId = $_SESSION['guide_id'] ?? null;
$guideName = $_SESSION['guide_name'] ?? 'Hướng dẫn viên';

if (!$guideId) {
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}

// Lấy dữ liệu từ controller (đã được truyền qua $GLOBALS)
$profile = $GLOBALS['profile'] ?? [];
$activeTours = $GLOBALS['activeTours'] ?? 0;
$stats = $GLOBALS['stats'] ?? ['so_tour_da_dan' => 0, 'danh_gia_trung_binh' => 0];

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

function getAvatarUrl($profile) {
    if (!empty($profile['hinh_anh'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $profile['hinh_anh'];
        if (file_exists($imagePath) && is_file($imagePath)) {
            return "http://localhost/pro1014" . $profile['hinh_anh'];
        }
    }
    return getDefaultAvatar($profile['ho_ten'] ?? 'HDV');
}

$avatarUrl = getAvatarUrl($profile);
$languages = !empty($profile['ngon_ngu']) ? json_decode($profile['ngon_ngu'], true) : [];
?>

<?php
// ... phần PHP giữ nguyên ...

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

    <div class="profile-tabs">
        <button class="tab-button active" data-tab="view-profile">Xem Hồ Sơ</button>
    </div>

    <!-- TAB 1: Xem hồ sơ -->
    <div id="view-profile" class="tab-content active">
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
    </div>

    <!-- TAB 2: Chỉnh sửa hồ sơ -->
    <div id="edit-profile" class="tab-content">
        <div class="profile-container">
            <form action="<?= BASE_URL_GUIDE ?>?act=update-profile" method="POST" enctype="multipart/form-data" class="profile-settings-form">
                
                <div class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Ảnh Đại Diện & Thông Tin Cá Nhân</h2>
                    </div>
                    <div class="section-content">
                        <div class="profile-form-layout">
                            
                            <div class="avatar-column">
                                <div class="avatar-preview">
                                    <img id="avatar-img-preview" src="<?= htmlspecialchars($avatarUrl) ?>" alt="Ảnh đại diện" width="150" height="150">
                                </div>
                                <div class="form-group avatar-form">
                                    <label for="avatar">Ảnh đại diện mới</label>
                                    <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">
                                    <small>Định dạng: JPG, PNG, GIF, WebP. Tối đa: 2MB.</small>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="ho_ten">Họ và tên *</label>
                                    <input type="text" id="ho_ten" name="ho_ten" value="<?= htmlspecialchars($profile['ho_ten'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="so_dien_thoai">Số điện thoại</label>
                                    <input type="tel" id="so_dien_thoai" name="so_dien_thoai" value="<?= htmlspecialchars($profile['so_dien_thoai'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ngay_sinh">Ngày sinh</label>
                                    <input type="date" id="ngay_sinh" name="ngay_sinh" value="<?= htmlspecialchars($profile['ngay_sinh'] ?? '') ?>">
                                </div>
                                <div class="form-group full-width">
                                    <label for="dia_chi">Địa chỉ</label>
                                    <input type="text" id="dia_chi" name="dia_chi" value="<?= htmlspecialchars($profile['dia_chi'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="loai_huong_dan_vien">Loại HDV</label>
                                    <select id="loai_huong_dan_vien" name="loai_huong_dan_vien">
                                        <option value="nội địa" <?= (($profile['loai_huong_dan_vien'] ?? '') == 'nội địa') ? 'selected' : '' ?>>Trong nước</option>
                                        <option value="quốc tế" <?= (($profile['loai_huong_dan_vien'] ?? '') == 'quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                                        <option value="chuyên tuyến" <?= (($profile['loai_huong_dan_vien'] ?? '') == 'chuyên tuyến') ? 'selected' : '' ?>>Chuyên tuyến</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label for="ngon_ngu">Ngôn ngữ (Chọn nhiều)</label>
                                    <select id="ngon_ngu" name="ngon_ngu[]" multiple size="4">
                                        <option value="vi" <?= in_array('vi', $languages) ? 'selected' : '' ?>>Tiếng Việt</option>
                                        <option value="en" <?= in_array('en', $languages) ? 'selected' : '' ?>>Tiếng Anh</option>
                                        <option value="zh" <?= in_array('zh', $languages) ? 'selected' : '' ?>>Tiếng Trung</option>
                                        <option value="ja" <?= in_array('ja', $languages) ? 'selected' : '' ?>>Tiếng Nhật</option>
                                        <option value="ko" <?= in_array('ko', $languages) ? 'selected' : '' ?>>Tiếng Hàn</option>
                                        <option value="fr" <?= in_array('fr', $languages) ? 'selected' : '' ?>>Tiếng Pháp</option>
                                    </select>
                                    <small>Giữ phím Ctrl (hoặc Cmd) để chọn nhiều ngôn ngữ.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <div class="section-header">
                        <h2 class="section-title">Thông Tin Chuyên Môn & Giấy Phép</h2>
                    </div>
                    <div class="section-content">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="kinh_nghiem">Kinh nghiệm</label>
                                <textarea id="kinh_nghiem" name="kinh_nghiem" rows="3" placeholder="Mô tả kinh nghiệm làm hướng dẫn viên..."><?= htmlspecialchars($profile['kinh_nghiem'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group full-width">
                                <label for="chuyen_mon">Chuyên môn</label>
                                <textarea id="chuyen_mon" name="chuyen_mon" rows="3" placeholder="Các chuyên môn đặc biệt..."><?= htmlspecialchars($profile['chuyen_mon'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group full-width">
                                <label for="tinh_trang_suc_khoe">Tình trạng sức khỏe (ghi chú)</label>
                                <textarea id="tinh_trang_suc_khoe" name="tinh_trang_suc_khoe" rows="2" placeholder="Tình trạng sức khỏe hiện tại..."><?= htmlspecialchars($profile['tinh_trang_suc_khoe'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="so_giay_phep_hanh_nghe">Số giấy phép hành nghề</label>
                                <input type="text" id="so_giay_phep_hanh_nghe" name="so_giay_phep_hanh_nghe" value="<?= htmlspecialchars($profile['so_giay_phep_hanh_nghe'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="ngay_cap_giay_phep">Ngày cấp</label>
                                <input type="date" id="ngay_cap_giay_phep" name="ngay_cap_giay_phep" value="<?= htmlspecialchars($profile['ngay_cap_giay_phep'] ?? '') ?>">
                            </div>
                            <div class="form-group full-width">
                                <label for="noi_cap_giay_phep">Nơi cấp</label>
                                <input type="text" id="noi_cap_giay_phep" name="noi_cap_giay_phep" value="<?= htmlspecialchars($profile['noi_cap_giay_phep'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Thay Đổi</button>
                    <button type="button" class="btn btn-outline" onclick="switchTab('view-profile')">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
/* Reset và layout chính */
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

/* Header */
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

/* Tabs */
.profile-tabs {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    border-bottom: 2px solid #e2e8f0;
    background: #fff;
    padding: 0 20px;
}

.tab-button {
    padding: 12px 32px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 1rem;
    font-weight: 600;
    color: #718096;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-button.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-button:hover {
    color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Profile Sections */
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

/* Profile Main Info */
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

/* Contact Grid */
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

/* Info Grid */
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

/* Form Styles */
.profile-settings-form {
    width: 100%;
}

.profile-form-layout {
    display: flex;
    gap: 30px;
    padding: 0;
}

.avatar-column {
    flex: 0 0 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.avatar-preview {
    margin-bottom: 15px;
}

.avatar-preview img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 4px solid var(--primary-color, #667eea);
    object-fit: cover;
}

.avatar-form {
    text-align: center;
}

.form-grid {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background: #fff;
    width: 100%;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group small {
    margin-top: 5px;
    color: #6b7280;
    font-size: 12px;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 15px;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

/* Alerts */
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
@media (max-width: 1200px) {
    .contact-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-section {
        margin: 0 15px 1.5rem 15px;
    }
    
    .alert {
        margin: 0 15px 1.5rem 15px;
    }
    
    .profile-tabs {
        padding: 0 15px;
    }
    
    .page-header {
        padding: 2rem 15px 1rem 15px;
    }
    
    .profile-tabs {
        flex-direction: column;
    }
    
    .tab-button {
        text-align: center;
        padding: 12px;
    }
    
    .profile-form-layout {
        flex-direction: column;
        gap: 20px;
    }
    
    .avatar-column {
        flex: none;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
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

    .section-content {
        padding: 1.5rem;
    }

    .section-header {
        padding: 1.25rem 1.5rem;
    }

    .page-title {
        font-size: 2rem;
    }

    .form-actions {
        flex-direction: column;
    }
    
    .profile-avatar-large img {
        width: 100px;
        height: 100px;
    }
    
    .profile-name {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .profile-section {
        margin: 0 10px 1.5rem 10px;
    }
    
    .alert {
        margin: 0 10px 1.5rem 10px;
    }
    
    .profile-tabs {
        padding: 0 10px;
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

<script>
// JavaScript để chuyển tab
function switchTab(tabName) {
    // Ẩn tất cả các tab content
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Hiển thị tab được chọn
    document.getElementById(tabName).classList.add('active');
    
    // Cập nhật trạng thái active cho tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}

// Thêm event listeners cho tab buttons
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        switchTab(this.getAttribute('data-tab'));
    });
});

// Xử lý preview avatar
const avatarInput = document.getElementById('avatar');
const avatarPreview = document.getElementById('avatar-img-preview');

if (avatarInput && avatarPreview) {
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>