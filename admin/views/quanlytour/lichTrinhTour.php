<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <!-- Header -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-route me-2"></i>
                        Lịch Trình: <?php echo htmlspecialchars($tour['ten_tour']); ?>
                    </a>
                    <div>
                        <a href="?act=tour" class="btn btn-outline-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <a href="?act=lich-trinh-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Thêm Ngày Mới
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                 <!-- Thông báo -->
                  <?php if (isset($_GET['success'])): ?>
                      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                  <?php endif; ?>

                  <?php if (isset($_GET['error'])): ?>
                      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                  <?php endif; ?>

                <!-- Thông tin tour -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Mã Tour:</strong> <?php echo htmlspecialchars($tour['ma_tour']); ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Tổng số ngày:</strong>
                                <span class="badge bg-primary">
                                    <?php
                                    $max_ngay = 0;
                                    if (!empty($lich_trinh)) {
                                        $max_ngay = max(array_column($lich_trinh, 'so_ngay'));
                                    }
                                    echo $max_ngay . ' ngày';
                                    ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <strong>Số lịch trình:</strong>
                                <span class="badge bg-info"><?php echo count($lich_trinh); ?> mục</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách lịch trình -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Lịch trình chi tiết theo ngày
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($lich_trinh)): ?>
                            <div class="timeline">
                                <?php foreach ($lich_trinh as $item): ?>
                                    <div class="timeline-item">
                                        <div class="card mb-3">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-primary day-badge">Ngày <?php echo $item['so_ngay']; ?></span>
                                                    <strong class="ms-2"><?php echo htmlspecialchars($item['tieu_de']); ?></strong>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?act=lich-trinh-edit&id=<?php echo $item['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                        class="btn btn-outline-primary" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?act=lich-trinh-delete&id=<?php echo $item['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                        class="btn btn-outline-danger delete-lich-trinh"
                                                        data-so-ngay="<?php echo $item['so_ngay']; ?>"
                                                        data-tieu-de="<?php echo htmlspecialchars($item['tieu_de']); ?>"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6>Hoạt động chính:</h6>
                                                        <p class="mb-3"><?php echo nl2br(htmlspecialchars($item['mo_ta_hoat_dong'])); ?></p>

                                                        <?php if (!empty($item['ghi_chu_hdv'])): ?>
                                                            <h6>Ghi chú HDV:</h6>
                                                            <div class="alert alert-info py-2">
                                                                <small><i class="fas fa-info-circle me-1"></i><?php echo nl2br(htmlspecialchars($item['ghi_chu_hdv'])); ?></small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>Thông tin chi tiết:</h6>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-bed me-1 text-success"></i> Chỗ ở:</strong><br>
                                                            <small><?php echo htmlspecialchars($item['cho_o']); ?></small>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-utensils me-1 text-warning"></i> Bữa ăn:</strong><br>
                                                            <small><?php echo htmlspecialchars($item['bua_an']); ?></small>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-bus me-1 text-primary"></i> Phương tiện:</strong><br>
                                                            <small><?php echo htmlspecialchars($item['phuong_tien']); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có lịch trình nào</h5>
                                <p class="text-muted">Hãy thêm lịch trình cho tour này</p>
                                <a href="?act=lich-trinh-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Thêm Lịch Trình Đầu Tiên
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tóm tắt lịch trình -->
                <?php if (!empty($lich_trinh)): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i>Tóm tắt lịch trình</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($lich_trinh as $item): ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <span class="badge bg-primary">Ngày <?php echo $item['so_ngay']; ?></span>
                                                    <?php echo htmlspecialchars($item['tieu_de']); ?>
                                                </h6>
                                                <p class="card-text small text-muted">
                                                    <?php
                                                    $mo_ta = strip_tags($item['mo_ta_hoat_dong']);
                                                    echo strlen($mo_ta) > 100 ? substr($mo_ta, 0, 100) . '...' : $mo_ta;
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);

    // Xác nhận xóa lịch trình
    $('.delete-lich-trinh').on('click', function(e) {
        e.preventDefault();
        var deleteUrl = $(this).attr('href');
        var soNgay = $(this).data('so-ngay');
        var tieuDe = $(this).data('tieu-de');
        
        if (confirm('Bạn có chắc muốn xóa lịch trình Ngày ' + soNgay + ': "' + tieuDe + '"?')) {
            window.location.href = deleteUrl;
        }
    });

    // Xóa parameter success/error từ URL
    if (window.history.replaceState && (window.location.search.includes('success=') || window.location.search.includes('error='))) {
        var urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('success');
        urlParams.delete('error');
        var newUrl = window.location.pathname + '?' + urlParams.toString();
        window.history.replaceState({}, document.title, newUrl);
    }
});
</script>

<style>
.alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border-left-color: #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-left-color: #dc3545;
}

.day-badge {
    font-size: 0.9em;
    padding: 0.4em 0.8em;
}

.timeline-item {
    position: relative;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -20px;
    top: 20px;
    width: 12px;
    height: 12px;
    background: #007bff;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #007bff;
}
</style>