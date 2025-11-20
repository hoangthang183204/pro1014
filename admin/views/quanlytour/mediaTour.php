<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid p-0">
            <nav class="navbar navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="?act=/">
                        <i class="fas fa-images me-2"></i>
                        Media: <?php echo htmlspecialchars($tour['ten_tour']); ?>
                    </a>
                    <div>
                        <a href="?act=tour" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload hình ảnh/video</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="?act=upload-media" enctype="multipart/form-data">
                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Chọn file</label>
                                        <input type="file" name="media_files[]" class="form-control" multiple
                                            accept=".jpg,.jpeg,.png,.gif,.webp,.mp4,.avi,.mov,.wmv" required>
                                        <small class="text-muted">Có thể chọn nhiều file cùng lúc (Tối đa 10MB/file)</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Loại media</label>
                                        <select name="loai_media" class="form-select" required>
                                            <option value="hình ảnh">Hình ảnh</option>
                                            <option value="video">Video</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Chú thích chung</label>
                                        <input type="text" name="chu_thich" class="form-control"
                                            placeholder="Chú thích cho tất cả file...">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload me-1"></i> Upload Media
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thư viện media (<?php echo count($media_list); ?> files)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($media_list)): ?>
                            <div class="row">
                                <?php foreach ($media_list as $media): ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="card media-card">
                                            <?php if ($media['loai_media'] == 'hình ảnh'): ?>
                                                <?php
                                                // SỬA ĐƯỜNG DẪN VẬT LÝ
                                                $physical_path = $_SERVER['DOCUMENT_ROOT'] . '/pro1014/uploads/imgproduct/' . $media['url'];
                                                $image_url = '/pro1014/uploads/imgproduct/' . $media['url'];
                                                $image_exists = file_exists($physical_path);
                                                ?>

                                                <?php if ($image_exists): ?>
                                                    <img src="<?php echo $image_url; ?>"
                                                        class="card-img-top media-thumbnail"
                                                        alt="<?php echo htmlspecialchars($media['chu_thich']); ?>"
                                                        style="height: 200px; object-fit: cover;"
                                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5Mb2FkaW5nLi4uPC90ZXh0Pjwvc3ZnPg=='">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-danger text-white d-flex align-items-center justify-content-center media-thumbnail" style="height: 200px;">
                                                        <div class="text-center">
                                                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                                                            <br>
                                                            <small><strong>LỖI: File không tồn tại</strong></small>
                                                            <br>
                                                            <small>Tên file: <?php echo $media['url']; ?></small>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="card-img-top bg-dark text-white d-flex align-items-center justify-content-center media-thumbnail" style="height: 200px;">
                                                    <i class="fas fa-video fa-3x"></i>
                                                    <small class="position-absolute bottom-0 end-0 bg-dark text-white p-1">
                                                        VIDEO
                                                    </small>
                                                </div>
                                            <?php endif; ?>

                                            <div class="card-body">
                                                <form method="POST" action="?act=update-media-info" class="mb-2">
                                                    <input type="hidden" name="media_id" value="<?php echo $media['id']; ?>">
                                                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="chu_thich" class="form-control form-control-sm"
                                                            value="<?php echo htmlspecialchars($media['chu_thich']); ?>"
                                                            placeholder="Chú thích...">
                                                        <button type="submit" class="btn btn-outline-primary" title="Lưu chú thích">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                    </div>
                                                </form>

                                                <div class="btn-group w-100">
                                                    <?php if ($media['loai_media'] == 'hình ảnh' && $image_exists): ?>
                                                        <a href="<?php echo $image_url; ?>"
                                                            class="btn btn-outline-info btn-sm"
                                                            target="_blank"
                                                            title="Xem gốc">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="?act=delete-media&media_id=<?php echo $media['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa media này? File sẽ bị xóa vĩnh viễn.')"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>

                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <?php echo $media['loai_media']; ?> •
                                                        Thứ tự: <?php echo $media['thu_tu_sap_xep']; ?>
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y H:i', strtotime($media['created_at'])); ?>
                                                    </small>
                                                    <?php if ($media['loai_media'] == 'hình ảnh' && !$image_exists): ?>
                                                        <br>
                                                        <small class="text-danger">
                                                            <i class="fas fa-exclamation-circle"></i>
                                                            File không tồn tại trên server
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có media</h5>
                                <p class="text-muted">Hãy upload hình ảnh hoặc video cho tour</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>