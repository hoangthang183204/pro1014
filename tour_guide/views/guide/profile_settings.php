[file name]: profile_settings.php
[file content begin]
<?php
// Tệp: views/guide/profile_settings.php

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$guideId = $_SESSION['guide_id'] ?? null;
if (!$guideId) {
    header("Location: " . BASE_URL_GUIDE . "?act=login");
    exit();
}

$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Lấy dữ liệu từ controller (đã được truyền qua $GLOBALS)
$profile = $GLOBALS['profile'] ?? [];

// Lấy thông tin thống kê
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

function getSettingsAvatarUrl($profile) {
    if (!empty($profile['hinh_anh'])) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/pro1014' . $profile['hinh_anh'];
        if (file_exists($imagePath)) {
            return "http://localhost/pro1014" . $profile['hinh_anh'];
        }
    }
    return getDefaultAvatar($profile['ho_ten'] ?? 'HDV');
}

$avatarUrl = getSettingsAvatarUrl($profile);
$languages = json_decode($profile['ngon_ngu'] ?? '[]', true);
?>

<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Cài Đặt Hồ Sơ</h1>
        <div class="page-subtitle">Cập nhật thông tin cá nhân và ảnh đại diện</div>
    </div>
    
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="alert alert-error"><i class="fas fa-times-circle"></i> <?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Thống kê hiển thị -->
    <div class="stats-summary">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-suitcase"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= htmlspecialchars($stats['so_tour_da_dan'] ?? 0) ?></div>
                <div class="stat-label">Tour đã dẫn</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= number_format($stats['danh_gia_trung_binh'] ?? 0, 1) ?></div>
                <div class="stat-label">Đánh giá trung bình</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number"><?= htmlspecialchars($profile['loai_huong_dan_vien'] ?? 'Nội địa') ?></div>
                <div class="stat-label">Loại hướng dẫn viên</div>
            </div>
        </div>
    </div>

    <form action="<?= BASE_URL_GUIDE ?>?act=update-profile" method="POST" enctype="multipart/form-data" class="profile-settings-form">
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Ảnh Đại Diện & Thông Tin Cá Nhân</h2>
            </div>
            <div class="card-content profile-form-layout">
                
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

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Thông Tin Chuyên Môn & Giấy Phép</h2>
            </div>
            <div class="card-content">
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

        <!-- Card thống kê có thể sửa -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Thống Kê Hoạt Động (Có thể chỉnh sửa)</h2>
            </div>
            <div class="card-content">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="so_tour_da_dan">Số tour đã dẫn *</label>
                        <input type="number" id="so_tour_da_dan" name="so_tour_da_dan" 
                               value="<?= htmlspecialchars($stats['so_tour_da_dan'] ?? 0) ?>" 
                               min="0" max="999" step="1" required>
                        <small>Số lượng tour đã hoàn thành (tối đa 999)</small>
                    </div>
                    <div class="form-group">
                        <label for="danh_gia_trung_binh">Đánh giá trung bình *</label>
                        <input type="number" id="danh_gia_trung_binh" name="danh_gia_trung_binh" 
                               value="<?= number_format($stats['danh_gia_trung_binh'] ?? 0, 1) ?>" 
                               min="0" max="5" step="0.1" required>
                        <small>Điểm đánh giá từ khách hàng (0.0 - 5.0)</small>
                    </div>
                    <div class="form-group full-width">
                        <div class="rating-preview">
                            <div class="rating-stars-preview">
                                <span id="star-preview"></span>
                            </div>
                            <span id="rating-text-preview" class="rating-text">(<?= number_format($stats['danh_gia_trung_binh'] ?? 0, 1) ?>/5.0)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Thay Đổi</button>
            <a href="<?= BASE_URL_GUIDE ?>?act=my-profile" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</main>

<style>
.profile-settings-form {
    max-width: 1000px;
    margin: 0 auto;
}

/* Thống kê summary */
.stats-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 20px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

.profile-form-layout {
    display: flex;
    gap: 30px;
    padding: 20px;
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
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group input[type="number"] {
    -moz-appearance: textfield;
}

.form-group input[type="number"]::-webkit-outer-spin-button,
.form-group input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
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

/* Hiển thị rating preview */
.rating-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background-color: #f9fafb;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.rating-stars-preview {
    color: #fbbf24;
    font-size: 1.2rem;
}

.rating-stars-preview i {
    margin-right: 3px;
}

.rating-text {
    color: #6b7280;
    font-weight: 600;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e1e5e9;
    overflow: hidden;
    margin-bottom: 20px;
}

.card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-header .card-title {
    margin: 0;
    color: white;
    font-size: 1.4rem;
    font-weight: 600;
}

.card-content {
    padding: 2rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
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

@media (max-width: 768px) {
    .stats-summary {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 15px;
    }
    
    .stat-number {
        font-size: 1.5rem;
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
    
    .card-content {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        font-size: 1.5rem;
    }
    
    .rating-preview {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
const avatarInput = document.getElementById('avatar');
const avatarPreview = document.getElementById('avatar-img-preview');
const ratingInput = document.getElementById('danh_gia_trung_binh');
const starPreview = document.getElementById('star-preview');
const ratingTextPreview = document.getElementById('rating-text-preview');

// Preview avatar
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

// Hiển thị rating preview
function updateRatingPreview() {
    const rating = parseFloat(ratingInput.value) || 0;
    
    // Giới hạn giá trị từ 0-5
    if (rating < 0) ratingInput.value = 0;
    if (rating > 5) ratingInput.value = 5;
    
    const validRating = Math.min(Math.max(rating, 0), 5);
    const fullStars = Math.floor(validRating);
    const halfStar = (validRating - fullStars) >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    
    let starsHtml = '';
    
    // Sao đầy
    for (let i = 0; i < fullStars; i++) {
        starsHtml += '<i class="fas fa-star"></i>';
    }
    
    // Sao nửa
    if (halfStar) {
        starsHtml += '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Sao rỗng
    for (let i = 0; i < emptyStars; i++) {
        starsHtml += '<i class="far fa-star"></i>';
    }
    
    starPreview.innerHTML = starsHtml;
    ratingTextPreview.textContent = `(${validRating.toFixed(1)}/5.0)`;
}

// Khởi tạo rating preview
if (ratingInput && starPreview && ratingTextPreview) {
    updateRatingPreview();
    
    // Cập nhật khi giá trị thay đổi
    ratingInput.addEventListener('input', updateRatingPreview);
    ratingInput.addEventListener('change', updateRatingPreview);
}

// Kiểm tra dữ liệu trước khi submit
document.querySelector('.profile-settings-form').addEventListener('submit', function(e) {
    const tourCount = document.getElementById('so_tour_da_dan');
    const rating = document.getElementById('danh_gia_trung_binh');
    
    // Kiểm tra số tour
    if (tourCount.value < 0) {
        alert('Số tour đã dẫn không thể âm');
        tourCount.focus();
        e.preventDefault();
        return;
    }
    
    if (tourCount.value > 999) {
        alert('Số tour đã dẫn tối đa là 999');
        tourCount.focus();
        e.preventDefault();
        return;
    }
    
    // Kiểm tra đánh giá
    if (rating.value < 0 || rating.value > 5) {
        alert('Đánh giá phải nằm trong khoảng 0.0 đến 5.0');
        rating.focus();
        e.preventDefault();
        return;
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
[file content end]