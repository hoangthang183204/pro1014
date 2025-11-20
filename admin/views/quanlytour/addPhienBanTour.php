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
                          <i class="fas fa-plus me-2"></i>
                          Tạo Phiên Bản Mới
                      </a>
                      <div>
                          <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông báo -->
                  <?php if (isset($_GET['error'])): ?>
                      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                  <?php endif; ?>

                  <!-- Thông tin tour -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <h5 class="card-title">Tour: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?></h5>
                          <p class="card-text">
                              <strong>Lịch trình hiện tại:</strong>
                              <span class="badge bg-info"><?php echo count($lich_trinh_hien_tai); ?> ngày</span>
                          </p>
                      </div>
                  </div>

                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin phiên bản</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=phien-ban-store">
                              <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Tên phiên bản <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_phien_ban" class="form-control" required
                                              placeholder="VD: Mùa hè 2024, Tết Nguyên Đán, Cao điểm tháng 6...">
                                          <small class="text-muted">Tên dễ nhận biết để quản lý</small>
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Ngày hiệu lực</label>
                                          <input type="date" name="ngay_hieu_luc" class="form-control">
                                          <small class="text-muted">Ngày bắt đầu áp dụng phiên bản này (nếu có)</small>
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Mô tả phiên bản</label>
                                  <textarea name="mo_ta" class="form-control" rows="3"
                                      placeholder="Mô tả về phiên bản, lý do tạo, đặc điểm..."></textarea>
                              </div>

                              <!-- Thông tin lịch trình sẽ được sao lưu -->
                              <div class="alert alert-info">
                                  <h6><i class="fas fa-info-circle me-2"></i>Thông tin sao lưu</h6>
                                  <p class="mb-1">Phiên bản này sẽ sao lưu toàn bộ lịch trình hiện tại:</p>
                                  <ul class="mb-0">
                                      <li><strong>Số ngày:</strong> <?php echo count($lich_trinh_hien_tai); ?> ngày lịch trình</li>
                                      <li><strong>Tổng hoạt động:</strong> <?php echo count($lich_trinh_hien_tai); ?> mục</li>
                                      <li><strong>Thời điểm:</strong> <?php echo date('d/m/Y H:i'); ?></li>
                                  </ul>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Tạo Phiên Bản
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>

                  <!-- Xem trước lịch trình hiện tại -->
                  <?php if (!empty($lich_trinh_hien_tai)): ?>
                      <div class="card mt-4">
                          <div class="card-header">
                              <h6 class="mb-0"><i class="fas fa-eye me-2"></i>Xem trước lịch trình sẽ được sao lưu</h6>
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table table-sm table-hover">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Ngày</th>
                                              <th>Tiêu đề</th>
                                              <th>Chỗ ở</th>
                                              <th>Bữa ăn</th>
                                              <th>Phương tiện</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($lich_trinh_hien_tai as $item): ?>
                                              <tr>
                                                  <td><strong>Ngày <?php echo $item['so_ngay']; ?></strong></td>
                                                  <td><?php echo htmlspecialchars($item['tieu_de']); ?></td>
                                                  <td><small><?php echo htmlspecialchars($item['cho_o']); ?></small></td>
                                                  <td><small><?php echo htmlspecialchars($item['bua_an']); ?></small></td>
                                                  <td><small><?php echo htmlspecialchars($item['phuong_tien']); ?></small></td>
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
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php include './views/layout/footer.php'; ?>
  <!-- End Footer -->
  </body>

  </html>