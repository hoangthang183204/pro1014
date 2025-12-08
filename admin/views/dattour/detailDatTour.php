<?php require 'views/layout/header.php'; ?>
<?php include 'views/layout/navbar.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-eye me-2"></i>
                        Chi Tiết Đặt Tour
                    </a>
                    <a href="?act=dat-tour" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </nav>

            <div class="container mt-4">
                <?php if ($dat_tour):
                    // Xử lý đường dẫn ảnh
                    $hinh_anh = $dat_tour['hinh_anh'] ?? '';
                    if (!empty($hinh_anh)) {
                        if (filter_var($hinh_anh, FILTER_VALIDATE_URL) || strpos($hinh_anh, 'http') === 0 || strpos($hinh_anh, '//') === 0) {
                            $image_url = $hinh_anh;
                        } else {
                            $image_url = 'uploads/tours/' . $hinh_anh;
                        }
                        $has_image = true;
                    } else {
                        $has_image = false;
                    }
                ?>
                    <div class="row">
                        <div class="col-lg-8">

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Thông Tin Đặt Tour</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-3 text-center mb-3 mb-md-0">
                                            <?php if ($has_image): ?>
                                                <img src="<?php echo htmlspecialchars($image_url); ?>"
                                                    alt="<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>"
                                                    class="img-fluid rounded shadow-sm tour-main-image"
                                                    style="max-height: 150px; object-fit: cover; cursor: pointer;"
                                                    onclick="openImageModal('<?php echo htmlspecialchars($image_url); ?>', '<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>')">
                                                <small class="text-muted d-block mt-2">Ảnh đại diện tour</small>
                                            <?php else: ?>
                                                <div class="no-image-placeholder bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="height: 150px; width: 100%;">
                                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                                </div>
                                                <small class="text-muted d-block mt-2">Chưa có ảnh</small>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Cột thông tin -->
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Mã đặt tour:</strong> <span class="badge bg-dark"><?php echo $dat_tour['ma_dat_tour']; ?></span></p>
                                                    <p><strong>Tour:</strong> <span class="fw-bold"><?php echo $dat_tour['ten_tour']; ?></span></p>
                                                    <p><strong>Ngày đi:</strong> <i class="fas fa-plane-departure text-primary me-1"></i><?php echo date('d/m/Y', strtotime($dat_tour['ngay_bat_dau'])); ?></p>
                                                    <p><strong>Ngày về:</strong> <i class="fas fa-plane-arrival text-success me-1"></i><?php echo date('d/m/Y', strtotime($dat_tour['ngay_ket_thuc'])); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Trạng thái:</strong>
                                                        <span class="badge bg-<?php
                                                                                switch ($dat_tour['trang_thai']) {
                                                                                    case 'đã thanh toán':
                                                                                        echo 'success';
                                                                                        break;
                                                                                    case 'giữ chỗ':
                                                                                        echo 'info';
                                                                                        break;
                                                                                    case 'chưa thanh toán':
                                                                                        echo 'warning';
                                                                                        break;
                                                                                    case 'hủy':
                                                                                        echo 'danger';
                                                                                        break;
                                                                                    default:
                                                                                        echo 'secondary';
                                                                                }
                                                                                ?> p-2">
                                                            <?php echo $dat_tour['trang_thai']; ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Số khách:</strong> <i class="fas fa-users text-info me-1"></i><?php echo $dat_tour['so_luong_khach']; ?> người</p>
                                                    <p><strong>Tổng tiền:</strong>
                                                        <span class="text-success fw-bold fs-5">
                                                            <?php echo number_format($dat_tour['tong_tien'], 0, ',', '.'); ?> VNĐ
                                                        </span>
                                                    </p>
                                                    <?php if (!empty($dat_tour['ghi_chu'])): ?>
                                                        <p><strong>Ghi chú:</strong> <span class="text-muted"><?php echo htmlspecialchars($dat_tour['ghi_chu']); ?></span></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lịch trình tour -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>Lịch Trình Tour
                                        <?php if (!empty($lich_trinh_tour)): ?>
                                            <span class="badge bg-secondary ms-2"><?php echo count($lich_trinh_tour); ?> ngày</span>
                                        <?php endif; ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($lich_trinh_tour)): ?>
                                        <div class="tour-timeline">
                                            <?php foreach ($lich_trinh_tour as $index => $lich): ?>
                                                <div class="timeline-item mb-4">
                                                    <div class="timeline-marker bg-primary">
                                                        <span class="text-white fw-bold"><?php echo $lich['so_ngay']; ?></span>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div class="card">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0 fw-bold">
                                                                    <i class="fas fa-sun me-2 text-warning"></i>
                                                                    Ngày <?php echo $lich['so_ngay']; ?>:
                                                                    <?php echo htmlspecialchars($lich['tieu_de']); ?>
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <!-- Hoạt động -->
                                                                <?php if (!empty($lich['mo_ta_hoat_dong'])): ?>
                                                                    <div class="mb-3">
                                                                        <h6 class="text-primary">
                                                                            <i class="fas fa-map-marked-alt me-2"></i>Hoạt động chính
                                                                        </h6>
                                                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($lich['mo_ta_hoat_dong'])); ?></p>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <div class="row">
                                                                    <!-- Chỗ ở -->
                                                                    <?php if (!empty($lich['cho_o'])): ?>
                                                                        <div class="col-md-6 mb-3">
                                                                            <h6 class="text-success">
                                                                                <i class="fas fa-bed me-2"></i>Chỗ ở
                                                                            </h6>
                                                                            <p class="mb-0"><?php echo htmlspecialchars($lich['cho_o']); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <!-- Bữa ăn -->
                                                                    <?php if (!empty($lich['bua_an'])): ?>
                                                                        <div class="col-md-6 mb-3">
                                                                            <h6 class="text-warning">
                                                                                <i class="fas fa-utensils me-2"></i>Bữa ăn
                                                                            </h6>
                                                                            <p class="mb-0"><?php echo htmlspecialchars($lich['bua_an']); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <!-- Phương tiện -->
                                                                <?php if (!empty($lich['phuong_tien'])): ?>
                                                                    <div class="mb-3">
                                                                        <h6 class="text-info">
                                                                            <i class="fas fa-bus me-2"></i>Phương tiện
                                                                        </h6>
                                                                        <p class="mb-0"><?php echo htmlspecialchars($lich['phuong_tien']); ?></p>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <!-- Ghi chú HDV -->
                                                                <?php if (!empty($lich['ghi_chu_hdv'])): ?>
                                                                    <div class="alert alert-info mt-3">
                                                                        <h6 class="alert-heading">
                                                                            <i class="fas fa-info-circle me-2"></i>Ghi chú HDV
                                                                        </h6>
                                                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($lich['ghi_chu_hdv'])); ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Chưa có thông tin lịch trình cho tour này.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Danh sách khách hàng -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-users me-2"></i>Danh Sách Khách Hàng (<?php echo count($all_khach_hang); ?> người)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">STT</th>
                                                    <th>Họ Tên</th>
                                                    <th width="120">SĐT</th>
                                                    <th width="130">CCCD</th>
                                                    <th width="100">Ngày Sinh</th>
                                                    <th width="90">Giới Tính</th>
                                                    <th>Ghi Chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($all_khach_hang as $index => $khach): ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php
                                                                $avatar_initial = mb_substr(trim($khach['ho_ten']), 0, 1);
                                                                $gender_class = ($khach['gioi_tinh'] == 'Nam') ? 'bg-primary' : 'bg-pink';
                                                                ?>
                                                                <div class="avatar-circle me-2 <?php echo $gender_class; ?>">
                                                                    <span class="avatar-text"><?php echo $avatar_initial; ?></span>
                                                                </div>
                                                                <?php echo htmlspecialchars($khach['ho_ten']); ?>
                                                            </div>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($khach['so_dien_thoai']); ?></td>
                                                        <td><code><?php echo htmlspecialchars($khach['cccd']); ?></code></td>
                                                        <td><?php echo $khach['ngay_sinh'] ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : '---'; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo ($khach['gioi_tinh'] == 'Nam') ? 'primary' : 'pink'; ?>">
                                                                <?php echo $khach['gioi_tinh']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($khach['ghi_chu'])): ?>
                                                                <span class="text-muted small"><?php echo htmlspecialchars($khach['ghi_chu']); ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted small">---</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SIDEBAR -->
                        <div class="col-lg-4">
                            <!-- KIỂU 3: HÌNH ẢNH TRONG CARD RIÊNG -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-images me-2"></i>Hình Ảnh Tour</h5>
                                </div>
                                <div class="card-body text-center">
                                    <?php if ($has_image): ?>
                                        <img src="<?php echo htmlspecialchars($image_url); ?>"
                                            alt="<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>"
                                            class="img-fluid rounded mb-3 tour-sidebar-image"
                                            style="max-height: 200px; object-fit: cover; cursor: pointer;"
                                            onclick="openImageModal('<?php echo htmlspecialchars($image_url); ?>', '<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>')">

                                        <div class="d-grid gap-2">
                                            <!-- <button class="btn btn-outline-primary btn-sm"
                                                onclick="openImageModal('<?php echo htmlspecialchars($image_url); ?>', '<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>')">
                                                <i class="fas fa-expand me-1"></i> Xem ảnh lớn
                                            </button> -->
                                            <!-- <button class="btn btn-outline-secondary btn-sm"
                                                onclick="downloadImage('<?php echo htmlspecialchars($image_url); ?>', '<?php echo htmlspecialchars($dat_tour['ten_tour']); ?>')">
                                                <i class="fas fa-download me-1"></i> Tải ảnh
                                            </button> -->
                                        </div>
                                    <?php else: ?>
                                        <div class="no-image-container bg-light rounded p-5 mb-3">
                                            <i class="fas fa-image fa-4x text-secondary mb-3"></i>
                                            <p class="text-muted mb-0">Tour chưa có hình ảnh</p>
                                        </div>
                                        <small class="text-muted">Liên hệ quản lý để cập nhật ảnh</small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Thao tác -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>Thao Tác</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="?act=dat-tour-print&id=<?php echo $dat_tour['id']; ?>" class="btn btn-info" target="_blank">
                                            <i class="fas fa-print me-1"></i> In Hóa Đơn
                                        </a>

                                        <!-- Cập nhật trạng thái -->
                                        <form method="POST" action="?act=dat-tour-update-status" class="mt-3">
                                            <input type="hidden" name="id" value="<?php echo $dat_tour['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Cập nhật trạng thái</label>
                                                <select name="trang_thai" class="form-control form-select">
                                                    <option value="chưa thanh toán" <?php echo $dat_tour['trang_thai'] == 'chưa thanh toán' ? 'selected' : ''; ?>>chưa thanh toán</option>
                                                    <option value="giữ chỗ" <?php echo $dat_tour['trang_thai'] == 'giữ chỗ' ? 'selected' : ''; ?>>giữ chỗ</option>
                                                    <option value="đã thanh toán" <?php echo $dat_tour['trang_thai'] == 'đã thanh toán' ? 'selected' : ''; ?>>đã thanh toán</option>
                                                    <option value="hủy" <?php echo $dat_tour['trang_thai'] == 'hủy' ? 'selected' : ''; ?>>Hủy</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-save me-1"></i> Cập nhật
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Không tìm thấy thông tin đặt tour!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- MODAL XEM ẢNH LỚN -->
<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImageModal()">&times;</span>
    <div class="modal-image-content">
        <img src="" alt="">
        <div class="image-title text-white"></div>
    </div>
</div>

<style>
    /* Timeline cho lịch trình tour */
    .tour-timeline {
        position: relative;
        padding-left: 30px;
    }

    .tour-timeline:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #dee2e6, #6c757d);
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -45px;
        top: 20px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #0d6efd;
    }

    .timeline-marker span {
        font-size: 0.8rem;
    }

    .timeline-content {
        margin-left: 0;
    }

    /* Style cho hình ảnh */
    .tour-main-image {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .tour-main-image:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .tour-sidebar-image {
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
    }

    .tour-sidebar-image:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    .no-image-placeholder {
        transition: all 0.3s ease;
    }

    .no-image-placeholder:hover {
        background-color: #e9ecef !important;
    }

    /* Banner */
    .tour-banner {
        transition: opacity 0.3s ease;
    }

    .tour-banner:hover {
        opacity: 0.95;
    }

    .banner-overlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .tour-banner-container:hover .banner-overlay {
        opacity: 1;
    }

    /* Avatar cho khách hàng */
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }

    .avatar-circle-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
    }

    .bg-pink {
        background-color: #e83e8c;
    }

    .avatar-text {
        line-height: 1;
    }

    .avatar-text-lg {
        line-height: 1;
    }

    /* Modal cho ảnh lớn */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal-image-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        animation: zoomIn 0.3s ease;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-image-content img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10000;
    }

    .close-modal:hover {
        background: rgba(220, 53, 69, 0.8);
        transform: rotate(90deg);
    }

    .image-title {
        position: absolute;
        bottom: -50px;
        left: 0;
        width: 100%;
        text-align: center;
        font-size: 1.2rem;
        padding: 10px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 0 0 8px 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tour-timeline {
            padding-left: 25px;
        }

        .timeline-marker {
            left: -35px;
            width: 25px;
            height: 25px;
        }

        .timeline-marker span {
            font-size: 0.7rem;
        }

        .tour-banner {
            height: 180px !important;
        }

        .banner-overlay h3 {
            font-size: 1.2rem;
        }

        .modal-image-content {
            max-width: 95%;
            max-height: 80%;
        }

        .close-modal {
            top: 10px;
            right: 15px;
            font-size: 30px;
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 576px) {
        .tour-banner {
            height: 150px !important;
        }

        .banner-overlay {
            padding: 10px;
        }

        .banner-overlay h3 {
            font-size: 1rem;
        }

        .banner-overlay p {
            font-size: 0.9rem;
        }
    }

    /* Animation cho ảnh */
    .tour-main-image,
    .tour-sidebar-image,
    .tour-banner {
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Hàm mở modal ảnh lớn
    function openImageModal(imageUrl, title) {
        const modal = document.getElementById('imageModal');
        const modalImage = modal.querySelector('img');
        const modalTitle = modal.querySelector('.image-title');

        modalImage.src = imageUrl;
        modalImage.alt = title;
        modalTitle.textContent = title;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Thêm hiệu ứng
        modal.style.animation = 'fadeIn 0.3s ease';
    }

    // Hàm đóng modal
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Hàm tải ảnh
    function downloadImage(imageUrl, title) {
        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = title + '.jpg';
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Đóng modal khi click ngoài
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Đóng modal bằng phím ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Kiểm tra và hiển thị ảnh lỗi
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjEwMCIgeT0iNzUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzk5OSI+RW1wdHkgSW1hZ2U8L3RleHQ+PC9zdmc+';
                this.style.objectFit = 'contain';
                this.style.padding = '20px';
            });
        });
    });
</script>

<?php include 'views/layout/footer.php'; ?>