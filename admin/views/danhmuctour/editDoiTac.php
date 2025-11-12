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
                          Sửa Đối Tác: <?php echo htmlspecialchars($doi_tac['ten_doi_tac']); ?>
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
                          <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin đối tác</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=danh-muc-doi-tac-update">
                              <input type="hidden" name="id" value="<?php echo $doi_tac['id']; ?>">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Tên đối tác <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_doi_tac" class="form-control" required
                                              placeholder="VD: Công ty Vận tải ABC, Khách sạn XYZ..." value="<?php echo htmlspecialchars($doi_tac['ten_doi_tac']);  ?>">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Loại dịch vụ <span class="text-danger">*</span></label>
                                          <select name="loai_dich_vu" class="form-select" required>
                                              <option value="">Chọn loại dịch vụ</option>
                                              <option value="vận_chuyển" <?php echo ($doi_tac['loai_dich_vu'] == 'vận_chuyển') ? 'selected' : ''; ?>>Vận chuyển</option>
                                              <option value="khách_sạn" <?php echo ($doi_tac['loai_dich_vu'] == 'khách_sạn') ? 'selected' : ''; ?>>Khách sạn</option>
                                              <option value="nhà_hàng" <?php echo ($doi_tac['loai_dich_vu'] == 'nhà_hàng') ? 'selected' : ''; ?>>Nhà hàng</option>
                                              <option value="vé_tham_quan" <?php echo ($doi_tac['loai_dich_vu'] == 'vé_tham_quan') ? 'selected' : ''; ?>>Vé tham quan</option>
                                              <option value="khác" <?php echo ($doi_tac['loai_dich_vu'] == 'khác') ? 'selected' : ''; ?>>Khác</option>
                                          </select>
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Thông tin liên hệ</label>
                                  <textarea name="thong_tin_lien_he" class="form-control" rows="3"
                                      placeholder="Địa chỉ, số điện thoại, email, người liên hệ..."><?php echo htmlspecialchars($doi_tac['thong_tin_lien_he']) ?></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=danh-muc-doi-tac" class="btn btn-secondary">
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