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
                          Thêm Lịch Khởi Hành
                      </a>
                      <div>
                          <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
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

                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lịch khởi hành</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=lich-khoi-hanh-store">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Tour <span class="text-danger">*</span></label>
                                          <select name="tour_id" class="form-select" required>
                                              <option value="">Chọn tour</option>
                                              <?php foreach ($tours as $tour): ?>
                                                  <option value="<?php echo $tour['id']; ?>">
                                                      <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                          <input type="date" name="ngay_bat_dau" class="form-control" required
                                              min="<?php echo date('Y-m-d'); ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                          <input type="date" name="ngay_ket_thuc" class="form-control" required
                                              min="<?php echo date('Y-m-d'); ?>">
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Giờ tập trung</label>
                                          <input type="time" name="gio_tap_trung" class="form-control">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Điểm tập trung</label>
                                          <textarea name="diem_tap_trung" class="form-control" rows="3"
                                              placeholder="Địa điểm cụ thể..."></textarea>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Số chỗ dự kiến</label>
                                          <input type="number" name="so_cho_du_kien" class="form-control"
                                              min="1" max="100" placeholder="VD: 20">
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Ghi chú vận hành</label>
                                  <textarea name="ghi_chu_van_hanh" class="form-control" rows="4"
                                      placeholder="Ghi chú đặc biệt cho đội vận hành..."></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=lich-khoi-hanh" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Lưu Lịch
                                  </button>
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
  <!-- End Footer -->

  <script>
      $(function() {
          $("#example1").DataTable({
              "responsive": true,
              "lengthChange": false,
              "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
          $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "responsive": true,
          });
      });
  </script>
  <!-- Code injected by live-server -->
  </body>

  </html>