<!-- Header -->
<?php require './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Thêm Danh Mục Tour Mới</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?act=/">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="?act=danh-muc">Danh mục</a></li>
                        <li class="breadcrumb-item"><a href="?act=danh-muc-tour">Danh mục tour</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Thông báo lỗi -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin danh mục tour</h3>
                        </div>
                        <!-- form start -->
                        <form action="?act=danh-muc-tour-store" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="ten_danh_muc">Tên danh mục <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ten_danh_muc" name="ten_danh_muc" 
                                           placeholder="Nhập tên danh mục" required>
                                </div>

                                <div class="form-group">
                                    <label for="loai_tour">Loại tour <span class="text-danger">*</span></label>
                                    <select class="form-control" id="loai_tour" name="loai_tour" required>
                                        <option value="">-- Chọn loại tour --</option>
                                        <option value="trong nước">Tour trong nước</option>
                                        <option value="quốc tế">Tour quốc tế</option>
                                        <option value="theo yêu cầu">Tour theo yêu cầu</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Tour trong nước:</strong> Tour tham quan các địa điểm trong nước<br>
                                        <strong>Tour quốc tế:</strong> Tour tham quan các nước ngoài<br>
                                        <strong>Tour theo yêu cầu:</strong> Tour thiết kế riêng theo yêu cầu khách hàng
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="mo_ta">Mô tả</label>
                                    <textarea class="form-control" id="mo_ta" name="mo_ta" rows="4" 
                                              placeholder="Nhập mô tả về danh mục tour"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select class="form-control" id="trang_thai" name="trang_thai">
                                        <option value="hoạt động" selected>Hoạt động</option>
                                        <option value="khóa">Khóa</option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Lưu danh mục
                                </button>
                                <a href="?act=danh-muc-tour" class="btn btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<?php include './views/layout/footer.php'; ?>