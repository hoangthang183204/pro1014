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
          <!-- Header -->
          <div class="dashboard-header py-3 mb-4">
              <div class="container">
                  <div class="row align-items-center">
                      <div class="col">
                          <h1 class="h3 mb-0">
                              <i class="fas fa-tachometer-alt me-2"></i>
                              Dashboard - Tổng Quan Hệ Thống
                          </h1>
                      </div>
                      <div class="col-auto">
                          <span class="badge bg-light text-dark">
                            
                          </span>
                      </div>
                  </div>
              </div>
          </div>

          <div class="container">
              <!-- Thống kê nhanh -->
              <div class="row mb-4">
                  <div class="col-xl-3 col-md-6 mb-3">
                      <div class="card stat-card bg-primary text-white">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <div>
                                      <h2 class="card-title"><?php echo $thongKe['tong_tour'] ?? 0; ?></h2>
                                      <p class="card-text mb-0">Tour Đang Hoạt Động</p>
                                  </div>
                                  <div class="align-self-center">
                                      <i class="fas fa-suitcase fa-2x opacity-75"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 mb-3">
                      <div class="card stat-card bg-success text-white">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <div>
                                      <h2 class="card-title"><?php echo $thongKe['tour_sap_khoi_hanh'] ?? 0; ?></h2>
                                      <p class="card-text mb-0">Tour Sắp Khởi Hành</p>
                                  </div>
                                  <div class="align-self-center">
                                      <i class="fas fa-plane-departure fa-2x opacity-75"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 mb-3">
                      <div class="card stat-card bg-warning text-dark">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <div>
                                      <h2 class="card-title"><?php echo $thongKe['su_co_hom_nay'] ?? 0; ?></h2>
                                      <p class="card-text mb-0">Sự Cố Hôm Nay</p>
                                  </div>
                                  <div class="align-self-center">
                                      <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-xl-3 col-md-6 mb-3">
                      <div class="card stat-card bg-info text-white">
                          <div class="card-body">
                              <div class="d-flex justify-content-between">
                                  <div>
                                      <h2 class="card-title"><?php echo $thongKe['hdv_dang_lam'] ?? 0; ?></h2>
                                      <p class="card-text mb-0">HDV Đang Làm Việc</p>
                                  </div>
                                  <div class="align-self-center">
                                      <i class="fas fa-users fa-2x opacity-75"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <!-- Tour sắp khởi hành -->
                  <div class="col-lg-6 mb-4">
                      <div class="card h-100">
                          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                              <h5 class="mb-0">
                                  <i class="fas fa-calendar-alt me-2"></i>
                                  Tour Sắp Khởi Hành
                              </h5>
                              <span class="badge bg-light text-primary"><?php echo count($tourSapKhoiHanh); ?></span>
                          </div>
                          <div class="card-body p-0">
                              <?php if (!empty($tourSapKhoiHanh)): ?>
                                  <div class="list-group list-group-flush">
                                      <?php foreach ($tourSapKhoiHanh as $tour): ?>
                                          <div class="list-group-item">
                                              <div class="d-flex w-100 justify-content-between align-items-start">
                                                  <div class="flex-grow-1">
                                                      <h6 class="mb-1 text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></h6>
                                                      <p class="mb-1"><?php echo htmlspecialchars($tour['ten_tour']); ?></p>
                                                      <small class="text-muted">
                                                          <i class="fas fa-user me-1"></i>
                                                          <?php echo htmlspecialchars($tour['ten_hdv'] ?? 'Chưa phân công'); ?>
                                                      </small>
                                                  </div>
                                                  <div class="text-end">
                                                      <div class="fw-bold text-success">
                                                          <?php echo date('d/m', strtotime($tour['ngay_bat_dau'])); ?>
                                                      </div>
                                                      <small class="text-muted">Ngày đi</small>
                                                  </div>
                                              </div>
                                          </div>
                                      <?php endforeach; ?>
                                  </div>
                              <?php else: ?>
                                  <div class="text-center py-4">
                                      <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                      <p class="text-muted mb-0">Không có tour nào sắp khởi hành</p>
                                  </div>
                              <?php endif; ?>
                          </div>
                      </div>
                  </div>

                  <!-- Sự cố cần xử lý -->
                  <div class="col-lg-6 mb-4">
                      <div class="card h-100">
                          <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                              <h5 class="mb-0">
                                  <i class="fas fa-exclamation-circle me-2"></i>
                                  Sự Cố Cần Xử Lý
                              </h5>
                              <span class="badge bg-light text-warning"><?php echo count($suCoCanXuLy); ?></span>
                          </div>
                          <div class="card-body p-0">
                              <?php if (!empty($suCoCanXuLy)): ?>
                                  <div class="list-group list-group-flush">
                                      <?php foreach ($suCoCanXuLy as $su_co): ?>
                                          <div class="list-group-item">
                                              <div class="d-flex w-100 justify-content-between align-items-start">
                                                  <div class="flex-grow-1">
                                                      <div class="d-flex justify-content-between align-items-start mb-1">
                                                          <h6 class="mb-0"><?php echo htmlspecialchars($su_co['tieu_de']); ?></h6>
                                                          <span class="badge bg-<?php echo $su_co['muc_do_nghiem_trong'] == 'nghiêm_trọng' ? 'danger' : 'warning'; ?> ms-2">
                                                              <?php echo htmlspecialchars($su_co['muc_do_nghiem_trong']); ?>
                                                          </span>
                                                      </div>
                                                      <p class="mb-1 text-muted"><?php echo htmlspecialchars($su_co['ten_tour']); ?></p>
                                                      <small class="text-muted">
                                                          <i class="fas fa-user me-1"></i>
                                                          <?php echo htmlspecialchars($su_co['ten_hdv']); ?>
                                                          •
                                                          <i class="fas fa-clock me-1"></i>
                                                          <?php echo date('H:i d/m', strtotime($su_co['thoi_gian_bao_cao'])); ?>
                                                      </small>
                                                  </div>
                                              </div>
                                          </div>
                                      <?php endforeach; ?>
                                  </div>
                              <?php else: ?>
                                  <div class="text-center py-4">
                                      <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                      <p class="text-muted mb-0">Không có sự cố nào cần xử lý</p>
                                  </div>
                              <?php endif; ?>
                          </div>
                      </div>
                  </div>
              </div>
      </section>

  </div>

  <!-- Footer -->
  <?php include './views/layout/footer.php'; ?>
  <!-- End Footer -->
  </body>

  </html>