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
                      <a class="navbar-brand" href="?act=danh-muc">
                          <i class="fas fa-plus me-2"></i>
                          Thêm Điểm Đến Mới
                      </a>
                      <div>
                          <a href="?act=danh-muc-diem-den" class="btn btn-outline-light">
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
                          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin điểm đến</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=danh-muc-diem-den-store">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="mb-3">
                                          <label class="form-label">Tên điểm đến <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_diem_den" class="form-control" required
                                              placeholder="VD: Đà Lạt, Nha Trang, Phú Quốc...">
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Mô tả</label>
                                  <textarea name="mo_ta" class="form-control" rows="4"
                                      placeholder="Mô tả về điểm đến, đặc điểm nổi bật..."></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=danh-muc-diem-den" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Lưu Điểm Đến
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