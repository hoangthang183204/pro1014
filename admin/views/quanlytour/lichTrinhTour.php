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
                          <i class="fas fa-route me-2"></i>
                          Lịch Trình: <?php echo htmlspecialchars($tour['ten_tour']); ?>
                      </a>
                      <div>
                          <a href="?act=tour" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Quản lý lịch trình theo ngày</h5>
                          <button class="btn btn-primary btn-sm">
                              <i class="fas fa-plus me-1"></i> Thêm ngày
                          </button>
                      </div>
                      <div class="card-body">
                          <?php if (!empty($lich_trinh)): ?>
                              <div class="accordion" id="accordionLichTrinh">
                                  <?php foreach ($lich_trinh as $index => $ngay): ?>
                                      <div class="accordion-item">
                                          <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                              <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                  data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="true">
                                                  <strong>Ngày <?php echo $ngay['so_ngay']; ?>:</strong>
                                                  <?php echo htmlspecialchars($ngay['tieu_de']); ?>
                                              </button>
                                          </h2>
                                          <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse show"
                                              aria-labelledby="heading<?php echo $index; ?>">
                                              <div class="accordion-body">
                                                  <div class="row">
                                                      <div class="col-md-6">
                                                          <h6>Hoạt động:</h6>
                                                          <p><?php echo nl2br(htmlspecialchars($ngay['mo_ta_hoat_dong'])); ?></p>

                                                          <h6>Ghi chú HDV:</h6>
                                                          <p class="text-info"><?php echo nl2br(htmlspecialchars($ngay['ghi_chu_hdv'])); ?></p>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <h6>Thông tin chi tiết:</h6>
                                                          <p><strong>Chỗ ở:</strong> <?php echo htmlspecialchars($ngay['cho_o']); ?></p>
                                                          <p><strong>Bữa ăn:</strong> <?php echo htmlspecialchars($ngay['bua_an']); ?></p>
                                                          <p><strong>Phương tiện:</strong> <?php echo htmlspecialchars($ngay['phuong_tien']); ?></p>
                                                      </div>
                                                  </div>
                                                  <div class="mt-3">
                                                      <button class="btn btn-outline-primary btn-sm">
                                                          <i class="fas fa-edit me-1"></i> Sửa
                                                      </button>
                                                      <button class="btn btn-outline-danger btn-sm">
                                                          <i class="fas fa-trash me-1"></i> Xóa
                                                      </button>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có lịch trình</h5>
                                  <p class="text-muted">Hãy thêm lịch trình cho tour này</p>
                                  <button class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm Lịch Trình Đầu Tiên
                                  </button>
                              </div>
                          <?php endif; ?>
                      </div>
                  </div>
              </div>
          </div>

          <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php include './views/layout/footer.php'; ?>
  <!-- End Footer -->
  </body>

  </html>