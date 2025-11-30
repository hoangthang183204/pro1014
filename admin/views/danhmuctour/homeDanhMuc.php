<!-- Header -->
<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">

    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="">
                        <i class="nav-icon fas fa-folder me-2"></i>
                        Quản Lý Danh Mục Tour
                    </a>
                    <div>
                        <a href="?act=/" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">

                <!-- GRID 3 CỘT — TỰ THU GỌN KHI MOBILE -->
                <div class="grid-container">

                    <!-- Danh mục Tour -->
                    <div class="ui-card">
                        <div class="ui-icon bg-primary">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <h4>Danh Mục Tour</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_danh_muc_tour'] ?? 0; ?></p>

                        <div class="ui-subgrid">
                            <span>Trong nước: <b><?php echo $thong_ke['tour_trong_nuoc'] ?? 0; ?></b></span>
                            <span>Quốc tế: <b><?php echo $thong_ke['tour_quoc_te'] ?? 0; ?></b></span>
                            <span>Theo yêu cầu: <b><?php echo $thong_ke['tour_theo_yeu_cau'] ?? 0; ?></b></span>
                        </div>

                        <a href="?act=danh-muc-tour" class="ui-btn primary">Quản lý</a>
                    </div>

                    <!-- Điểm đến -->
                    <div class="ui-card">
                        <div class="ui-icon bg-success">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Điểm Đến</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_diem_den'] ?? 0; ?></p>

                        <a href="?act=danh-muc-diem-den" class="ui-btn success">Danh sách</a>
                        <a href="?act=danh-muc-diem-den-create" class="ui-btn outline-success">Thêm mới</a>
                    </div>

                    <!-- Tag Tour -->
                    <div class="ui-card">
                        <div class="ui-icon bg-info">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h4>Tag Tour</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_tag_tour'] ?? 0; ?></p>

                        <a href="?act=danh-muc-tag-tour" class="ui-btn infoo">Danh sách</a>
                        <a href="?act=danh-muc-tag-tour-create" class="ui-btn outline-info">Thêm mới</a>
                    </div>

                    <!-- Chính sách -->
                    <div class="ui-card">
                        <div class="ui-icon bg-warning">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h4>Chính Sách Tour</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_chinh_sach'] ?? 0; ?></p>

                        <a href="?act=danh-muc-chinh-sach" class="ui-btn warning">Danh sách</a>
                        <a href="?act=danh-muc-chinh-sach-create" class="ui-btn outline-warning">Thêm mới</a>
                    </div>

                    <!-- Đối tác -->
                    <div class="ui-card">
                        <div class="ui-icon bg-secondary">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Đối Tác</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_doi_tac'] ?? 0; ?></p>

                        <a href="?act=danh-muc-doi-tac" class="ui-btn secondary">Danh sách</a>
                        <a href="?act=danh-muc-doi-tac-create" class="ui-btn outline-secondary">Thêm mới</a>
                    </div>

                    <!-- Hướng dẫn viên -->
                    <div class="ui-card">
                        <div class="ui-icon bg-danger">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>Hướng Dẫn Viên</h4>
                        <p class="ui-number"><?php echo $thong_ke['tong_hdv'] ?? 0; ?></p>

                        <a href="?act=huong-dan-vien" class="ui-btn danger">Danh sách</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>
<style>
    /* GRID LAYOUT */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 22px;
    }

    /* CARD */
    .ui-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: 0.25s ease;
        border: 1px solid #eef0f3;
    }

    .ui-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    /* ICON */
    .ui-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: #fff;
        font-size: 1.6rem;
    }

    /* NUMBER */
    .ui-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 8px 0 12px;
        color: #222;
    }

    /* SUB GRID */
    .ui-subgrid {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        margin-bottom: 15px;
        color: #555;
    }

    /* BUTTONS */
    .ui-btn {
        display: block;
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        transition: 0.25s;
        color: #fff;
    }

    .ui-btn:hover {
        opacity: 0.85;
    }

    /* Filled Buttons */
    .primary {
        background: #007bff;
    }

    .success {
        background: #28a745;
    }

    .infoo {
        background: #17a2b8;
    }

    .warning {
        background: #ffc107;
        color: #333;
    }

    .secondary {
        background: #6c757d;
    }

    .danger {
        background: #dc3545;
    }

    /* Outlined */
    .outline-success,
    .outline-info,
    .outline-danger,
    .outline-warning,
    .outline-secondary {
        background: transparent;
        border: 1px solid currentColor;
        color: inherit;
    }

    /* Colors */
    .bg-primary {
        background: #007bff
    }

    .bg-success {
        background: #28a745
    }

    .bg-info {
        background: #17a2b8
    }

    .bg-warning {
        background: #ffc107
    }

    .bg-secondary {
        background: #6c757d
    }

    .bg-danger {
        background: #dc3545
    }
</style>