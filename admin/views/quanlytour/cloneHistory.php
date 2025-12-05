<?php require './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <!-- Header -->
            <div class="content-header mb-3">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-history me-2"></i>
                                Lịch sử Clone: <span class="text-primary"><?php echo htmlspecialchars($tour['ten_tour']); ?></span>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="?act=dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="?act=tour"><i class="fas fa-suitcase"></i> Quản lý Tour</a></li>
                                <li class="breadcrumb-item"><a href="?act=tour-edit&id=<?php echo $tour['id']; ?>"><?php echo htmlspecialchars($tour['ten_tour']); ?></a></li>
                                <li class="breadcrumb-item active"><i class="fas fa-history"></i> Lịch sử clone</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- Info card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin tour</h3>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($tour['ten_tour']); ?></p>
                            <hr>
                            <h1 class="text-success"><?php echo count($clone_history); ?></h1>
                            <p class="text-muted mb-0">Số lần đã clone</p>
                            <a href="index.php?act=tour-clone&id=<?php echo $tour['id']; ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-copy me-2"></i>Tạo clone mới
                            </a>
                        </div>
                    </div>

                    <div class="card card-info mt-3">
                        <!-- <div class="card-header">
                            <h3 class="card-title">Thống kê clone</h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="p-2 bg-primary bg-opacity-10 rounded">
                                        <h5 class="text-primary mb-1">
                                            <?php 
                                                $total_lich_trinh = 0;
                                                foreach ($clone_history as $history) {
                                                    $details = json_decode($history['clone_details'] ?? '{}', true);
                                                    $total_lich_trinh += $details['lich_trinh_cloned'] ?? 0;
                                                }
                                                echo $total_lich_trinh;
                                            ?>
                                        </h5>
                                        <small class="text-muted">Lịch trình</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-2 bg-success bg-opacity-10 rounded">
                                        <h5 class="text-success mb-1">
                                            <?php 
                                                $total_phien_ban = 0;
                                                foreach ($clone_history as $history) {
                                                    $details = json_decode($history['clone_details'] ?? '{}', true);
                                                    $total_phien_ban += $details['phien_ban_cloned'] ?? 0;
                                                }
                                                echo $total_phien_ban;
                                            ?>
                                        </h5>
                                        <small class="text-muted">Phiên bản</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-warning bg-opacity-10 rounded">
                                        <h5 class="text-warning mb-1">
                                            <?php 
                                                $total_media = 0;
                                                foreach ($clone_history as $history) {
                                                    $details = json_decode($history['clone_details'] ?? '{}', true);
                                                    $total_media += $details['media_cloned'] ?? 0;
                                                }
                                                echo $total_media;
                                            ?>
                                        </h5>
                                        <small class="text-muted">Media</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-info bg-opacity-10 rounded">
                                        <h5 class="text-info mb-1"><?php echo count($clone_history); ?></h5>
                                        <small class="text-muted">Tour clone</small>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="col-md-9">
                    <?php if (empty($clone_history)): ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Chưa có lịch sử clone</h4>
                                <p class="text-muted">Tour này chưa được clone lần nào.</p>
                                <a href="index.php?act=tour-clone&id=<?php echo $tour['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-copy me-2"></i>Tạo clone đầu tiên
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-list me-2"></i>
                                    Danh sách bản sao (<?php echo count($clone_history); ?>)
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="100">Mã tour</th>
                                                <th>Tên tour</th>
                                                <th width="150">Người tạo</th>
                                                <th width="150">Thời gian</th>
                                                <th width="100">Trạng thái</th>
                                                <th width="120">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($clone_history as $history): ?>
                                                <tr>
                                                    <td>
                                                        <strong class="text-primary"><?php echo htmlspecialchars($history['new_tour_code']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold"><?php echo htmlspecialchars($history['new_tour_name']); ?></div>
                                                        <?php 
                                                        $details = json_decode($history['clone_details'] ?? '{}', true);
                                                        if ($details): ?>
                                                            <small class="text-muted">
                                                                <?php 
                                                                $items = [];
                                                                if (isset($details['lich_trinh_cloned']) && $details['lich_trinh_cloned'] > 0) {
                                                                    $items[] = $details['lich_trinh_cloned'] . ' lịch trình';
                                                                }
                                                                if (isset($details['phien_ban_cloned']) && $details['phien_ban_cloned'] > 0) {
                                                                    $items[] = $details['phien_ban_cloned'] . ' phiên bản';
                                                                }
                                                                if (isset($details['media_cloned']) && $details['media_cloned'] > 0) {
                                                                    $items[] = $details['media_cloned'] . ' media';
                                                                }
                                                                echo implode(', ', $items);
                                                                ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($history['cloned_by_name'] ?? 'N/A'); ?></td>
                                                    <td>
                                                        <?php echo date('d/m/Y', strtotime($history['cloned_at'])); ?><br>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($history['cloned_at'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="index.php?act=tour-edit&id=<?php echo $history['new_tour_id']; ?>" 
                                                               class="btn btn-outline-primary" title="Mở">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="index.php?act=tour-clone&id=<?php echo $history['new_tour_id']; ?>" 
                                                               class="btn btn-outline-success" title="Clone từ bản này">
                                                                <i class="fas fa-copy"></i>
                                                            </a>
                                                            <a href="index.php?act=tour-lich-trinh&tour_id=<?php echo $history['new_tour_id']; ?>" 
                                                               class="btn btn-outline-info" title="Lịch trình">
                                                                <i class="fas fa-calendar-alt"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './views/layout/footer.php'; ?>