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
      <!-- <section class="content-header">
      </section> -->

      <!-- Main content -->
      <section class="content">
          <div class="container-fluid p-0">
              <!-- Header -->
              <nav class="navbar navbar-dark bg-dark">
                  <div class="container-fluid">
                      <a class="navbar-brand" href="?act=/">
                          <i class="fas fa-eye me-2"></i>
                          Xem Phiên Bản: <?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?>
                      </a>
                      <div>
                          <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                          <a href="?act=phien-ban-ap-dung&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                              class="btn btn-warning"
                              onclick="return confirm('Bạn có chắc muốn áp dụng phiên bản này? Lịch trình hiện tại sẽ bị thay thế.')">
                              <i class="fas fa-play me-1"></i> Áp dụng Phiên Bản
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông tin phiên bản -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-4">
                                  <strong>Tour:</strong> <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?>
                              </div>
                              <div class="col-md-4">
                                  <strong>Phiên bản:</strong> <?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?>
                              </div>
                              <div class="col-md-4">
                                  <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($phien_ban['created_at'])); ?>
                              </div>
                          </div>
                          <?php if ($phien_ban['mo_ta']): ?>
                              <div class="mt-2">
                                  <strong>Mô tả:</strong> <?php echo htmlspecialchars($phien_ban['mo_ta']); ?>
                              </div>
                          <?php endif; ?>
                          <?php if ($phien_ban['ngay_hieu_luc']): ?>
                              <div class="mt-1">
                                  <strong>Ngày hiệu lực:</strong> <?php echo date('d/m/Y', strtotime($phien_ban['ngay_hieu_luc'])); ?>
                              </div>
                          <?php endif; ?>
                      </div>
                  </div>

                  <!-- Lịch trình của phiên bản -->
                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0">
                              <i class="fas fa-route me-2"></i>
                              Lịch trình trong phiên bản này
                          </h5>
                      </div>
                      <div class="card-body">
                          <?php if (!empty($lich_trinh)): ?>
                              <div class="timeline">
                                  <?php foreach ($lich_trinh as $item): ?>
                                      <div class="timeline-item">
                                          <div class="card mb-3">
                                              <div class="card-header bg-light">
                                                  <span class="badge bg-secondary">Ngày <?php echo $item['so_ngay']; ?></span>
                                                  <strong class="ms-2"><?php echo htmlspecialchars($item['tieu_de']); ?></strong>
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
                                  <h5 class="text-muted">Không có lịch trình trong phiên bản này</h5>
                              </div>
                          <?php endif; ?>
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
  <!-- End Footer -->
  </body>

  </html>