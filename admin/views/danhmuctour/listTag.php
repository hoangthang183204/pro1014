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
                          <i class="fas fa-tags me-2"></i>
                          Quản Lý Tag Tour
                      </a>
                      <div>
                          <a href="?act=danh-muc" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Danh mục
                          </a>
                          <a href="?act=danh-muc-tag-tour-create" class="btn btn-success">
                              <i class="fas fa-plus me-1"></i> Thêm mới
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông báo -->
                  <?php if (isset($_GET['success'])): ?>
                      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                  <?php endif; ?>

                  <?php if (isset($_GET['error'])): ?>
                      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                  <?php endif; ?>

                  <!-- Danh sách tag tour -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách tag tour (<?php echo count($tag_tour_list); ?>)</h5>
                      </div>
                      <div class="card-body">
                          <?php if (!empty($tag_tour_list)): ?>
                              <div class="row">
                                  <?php foreach ($tag_tour_list as $tag_tour): ?>
                                      <div class="col-md-3 mb-3">
                                          <div class="card">
                                              <div class="card-body text-center">
                                                  <h5 class="card-title">
                                                      <span class="badge bg-primary"><?php echo htmlspecialchars($tag_tour['ten_tag']); ?></span>
                                                  </h5>
                                                  <div class="btn-group btn-group-sm mt-2">
                                                      <a href="?act=danh-muc-tag-tour-edit&id=<?php echo $tag_tour['id']; ?>"
                                                          class="btn btn-outline-primary">
                                                          <i class="fas fa-edit"></i>
                                                      </a>
                                                      <a href="?act=danh-muc-tag-tour-delete&id=<?php echo $tag_tour['id']; ?>"
                                                          class="btn btn-outline-danger"
                                                          onclick="return confirm('Bạn có chắc muốn xóa tag này?')">
                                                          <i class="fas fa-trash"></i>
                                                      </a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có tag tour nào</h5>
                                  <p class="text-muted">Hãy thêm tag mới để phân loại tour</p>
                                  <a href="?act=danh-muc-tag-tour-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm Tag Đầu Tiên
                                  </a>
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