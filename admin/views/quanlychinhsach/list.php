<?php
require './views/layout/header.php';
include './views/layout/navbar.php';
include './views/layout/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=chinh-sach">
                        <i class="fas fa-file-contract me-2"></i>
                        Chính Sách & Điều Khoản Website
                    </a>
                    <!-- <div class="d-flex align-items-center">
                        <span class="text-white me-3 small">
                            <i class="fas fa-clock me-1"></i>
                            Cập nhật: <?php echo date('d/m/Y'); ?>
                        </span>
                    </div> -->
                </div>
            </nav>
            <div class="container mt-4">
                <!-- Bảng điều hướng nhanh -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="?act=/"><i class="fas fa-home"></i> Trang chủ</a></li>
                                        <li class="breadcrumb-item active">Chính Sách & Điều Khoản</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">   
                    <!-- Nội dung chính -->
                    <div class="col-lg-12 col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <!-- Header chính -->
                                <div class="text-center mb-5">
                                    <h1 class="display-5 fw-bold text-primary mb-3">
                                        <i class="fas fa-file-contract me-3"></i>
                                        Chính Sách & Điều Khoản Website
                                    </h1>
                                    <p class="lead text-muted mb-4">
                                        Đọc kỹ các điều khoản và chính sách trước khi sử dụng dịch vụ đặt tour của chúng tôi
                                    </p>
                                    <div class="alert alert-info border-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Lưu ý:</strong> Bằng việc đặt tour, bạn đồng ý với tất cả các điều khoản dưới đây
                                    </div>
                                </div>

                                <!-- Phần 1: Điều khoản sử dụng -->
                                <section id="section-1" class="mb-5">
                                    <h2 class="h3 mb-4 text-primary border-bottom pb-2">
                                        <i class="fas fa-gavel me-2"></i> Điều khoản sử dụng
                                    </h2>
                                    
                                    <div class="mb-4">
                                        <h4 class="h5 mb-3">1.1. Giới thiệu</h4>
                                        <p>Chào mừng bạn đến với <strong>Tour Du Lịch LATA</strong> - đơn vị tổ chức tour du lịch uy tín hàng đầu. Khi truy cập và sử dụng website <strong>lata.vn</strong>, bạn đồng ý tuân thủ và bị ràng buộc bởi các điều khoản và điều kiện sử dụng sau đây.</p>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="h5 mb-3">1.2. Điều kiện đặt tour</h4>
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Khách hàng từ 18 tuổi trở lên có đầy đủ năng lực pháp luật
                                            </li>
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Cung cấp thông tin chính xác, đầy đủ khi đặt tour
                                            </li>
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Đọc kỹ thông tin tour, lịch trình và giá cả trước khi đặt
                                            </li>
                                            <li class="list-group-item border-0 px-0 py-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Thanh toán đúng hạn theo thông báo của hệ thống
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="h5 mb-3">1.3. Thông tin liên hệ</h4>
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><i class="fas fa-building me-2 text-primary"></i> <strong>Công ty TNHH Du Lịch LATA</strong></p>
                                                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i> 123 Bắc Từ Liên, TP. Hà Nội</p>
                                                        <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i> Hotline: 1900 1234</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> Email: lata@.vn</p>
                                                        <p class="mb-2"><i class="fas fa-globe me-2 text-primary"></i> Website: www.lata.vn</p>
                                                        <p class="mb-2"><i class="fas fa-clock me-2 text-primary"></i> Giờ làm việc: 8:00 - 20:00 hàng ngày</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                           
                                <section id="section-5" class="mb-5">
                                    <h2 class="h3 mb-4 text-primary border-bottom pb-2">
                                        <i class="fas fa-user-shield me-2"></i> Chính sách bảo mật
                                    </h2>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title text-success">
                                                        <i class="fas fa-lock me-2"></i> Thông tin thu thập
                                                    </h5>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2"><i class="fas fa-circle fa-xs text-success me-2"></i> Họ tên, ngày sinh</li>
                                                        <li class="mb-2"><i class="fas fa-circle fa-xs text-success me-2"></i> Số điện thoại, email</li>
                                                        <li class="mb-2"><i class="fas fa-circle fa-xs text-success me-2"></i> Địa chỉ, CMND/CCCD</li>
                                                        <li class="mb-2"><i class="fas fa-circle fa-xs text-success me-2"></i> Thông tin thanh toán</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">
                                                        <i class="fas fa-shield-alt me-2"></i> Cam kết bảo mật
                                                    </h5>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Mã hóa SSL 256-bit</li>
                                                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Không chia sẻ thông tin cho bên thứ 3</li>
                                                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Tuân thủ Luật Bảo vệ thông tin cá nhân</li>
                                                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> Xóa thông tin theo yêu cầu</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>                  

                                <!-- Footer chính sách -->
                                <div class="mt-5 pt-4 border-top">
                                    <div class="alert alert-light border">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-file-signature fa-2x text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="alert-heading mb-2">Cam kết của chúng tôi</h5>
                                                <p class="mb-2">Chúng tôi cam kết cung cấp dịch vụ chất lượng, minh bạch và bảo vệ quyền lợi khách hàng tối đa.</p>
                                                <p class="mb-0"><strong>Hiệu lực:</strong> Từ ngày 01/01/2024</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<style>
    /* Style chung */
    .form-control,
    .form-select {
        padding: 8px 14px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .card {
        border-radius: 8px;
    }

    .card-header {
        border-radius: 8px 8px 0 0;
    }

    /* Sidebar sticky */
    .sticky-top {
        z-index: 1020;
    }

    /* List group trong sidebar */
    .list-group-item {
        border: none;
        padding: 12px 20px;
        color: #495057;
        transition: all 0.2s;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
        padding-left: 25px;
    }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Circle number cho quy trình */
    .circle-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto;
    }

    .card.border-primary .circle-number {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .card.border-success .circle-number {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .card.border-warning .circle-number {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .card.border-info .circle-number {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    /* Table style */
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table.table-bordered {
        border: 1px solid #dee2e6;
    }

    /* Section styling */
    section {
        scroll-margin-top: 20px;
    }

    h2 {
        margin-top: 2rem;
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        border: none;
    }

    /* Breadcrumb */
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: #0d6efd;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 0 10px;
        }

        .display-5 {
            font-size: 2rem;
        }

        .lead {
            font-size: 1rem;
        }

        .row.text-center .col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
            margin-bottom: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .sticky-top {
            position: relative !important;
            top: 0 !important;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1rem;
        }

        .display-5 {
            font-size: 1.75rem;
        }

        .row.text-center .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card.border-0.shadow-sm {
            margin-bottom: 1rem;
        }

        h2.h3 {
            font-size: 1.25rem;
        }
    }

    /* Smooth scroll */
    html {
        scroll-behavior: smooth;
    }
</style>

<script>
    // Highlight active nav item khi scroll
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('section[id]');
        const navItems = document.querySelectorAll('.list-group-item');

        function highlightNav() {
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;
                
                if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navItems.forEach(item => {
                item.classList.remove('active');
                const href = item.getAttribute('href');
                if (href === `#${current}`) {
                    item.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', highlightNav);

        // Auto hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-dismissible')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Add smooth scroll to nav items
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>