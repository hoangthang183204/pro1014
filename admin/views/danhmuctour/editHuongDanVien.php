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
                          <i class="fas fa-edit me-2"></i>
                          Sửa Đối Hướng Dẫn Viên: <?php echo htmlspecialchars($hdv['ten_hdv']); ?>
                      </a>
                      <div>
                          <a href="?act=danh-muc-doi-tac" class="btn btn-outline-light">
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
                          <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin hướng dẫn viên</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=danh-muc-hdv-update">
                              <div class="row">
                                  <input type="hidden" name="id" value="<?php echo $hdv['id']; ?>">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Họ tên HDV <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_hdv" class="form-control" required
                                              placeholder="VD: Nguyễn Văn A, Trần Thị B..." value="<?php echo $hdv['ten_hdv'] ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Chuyên môn</label>
                                          <input type="text" name="chuyen_mon" class="form-control"
                                              placeholder="VD: Chuyên tour miền Bắc, Tour biển đảo..." value="<?php echo $hdv['chuyen_mon'] ?>">
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Kỹ năng ngôn ngữ</label>
                                          <select name="ky_nang_ngon_ngu[]" class="form-select" multiple>
                                              <option value="vi" <?php echo in_array('vi', $hdv) ? 'selected' : ''; ?>>Tiếng Việt</option>
                                              <option value="en" <?php echo in_array('en', $hdv) ? 'selected' : ''; ?>>Tiếng Anh</option>
                                              <option value="fr" <?php echo in_array('fr', $hdv) ? 'selected' : ''; ?>>Tiếng Pháp</option>
                                              <option value="zh" <?php echo in_array('zh', $hdv) ? 'selected' : ''; ?>>Tiếng Trung</option>
                                              <option value="ja" <?php echo in_array('ja', $hdv) ? 'selected' : ''; ?>>Tiếng Nhật</option>
                                              <option value="ko" <?php echo in_array('ko', $hdv) ? 'selected' : ''; ?>>Tiếng Hàn</option>
                                          </select>
                                          <!-- <small class="text-muted">Giữ Ctrl để chọn nhiều ngôn ngữ</small> -->
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Trạng thái</label>
                                          <select name="trang_thai" class="form-select" required>
                                              <option value="đang_làm_việc" <?php echo ($hdv['trang_thai'] == 'đang_làm_việc') ? 'selected' : ''; ?>>Đang làm việc</option>
                                              <option value="ngừng_làm_việc" <?php echo ($hdv['trang_thai'] == 'ngừng_làm_việc') ? 'selected' : ''; ?>>Ngừng làm việc</option>
                                          </select>
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Thông tin liên hệ</label>
                                  <textarea name="thong_tin_lien_he" class="form-control" rows="3"
                                      placeholder="Số điện thoại, email, địa chỉ..."><?php echo $hdv['thong_tin_lien_he'] ?></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=danh-muc-hdv" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Cập Nhật
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