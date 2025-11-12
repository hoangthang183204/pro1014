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
                          Thêm Chính Sách Mới
                      </a>
                      <div>
                          <a href="?act=danh-muc-chinh-sach" class="btn btn-outline-light">
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
                          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin chính sách</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=danh-muc-chinh-sach-store">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="mb-3">
                                          <label class="form-label">Tên chính sách <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_chinh_sach" class="form-control" required
                                              placeholder="VD: Chính sách tiêu chuẩn, Chính sách mùa cao điểm...">
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Quy định hủy/đổi tour</label>
                                  <textarea name="quy_dinh_huy_doi" class="form-control" rows="4"
                                      placeholder="VD: Hủy trước 7 ngày: hoàn 80% | Hủy trước 3 ngày: hoàn 50% | Hủy dưới 3 ngày: không hoàn..."></textarea>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Lưu ý sức khỏe</label>
                                  <textarea name="luu_y_suc_khoe" class="form-control" rows="3"
                                      placeholder="Lưu ý về sức khỏe cho khách hàng..."></textarea>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Lưu ý hành lý</label>
                                  <textarea name="luu_y_hanh_ly" class="form-control" rows="3"
                                      placeholder="Quy định về hành lý, ký gửi..."></textarea>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Lưu ý khác</label>
                                  <textarea name="luu_y_khac" class="form-control" rows="3"
                                      placeholder="Các lưu ý khác..."></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=danh-muc-chinh-sach" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Lưu Chính Sách
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