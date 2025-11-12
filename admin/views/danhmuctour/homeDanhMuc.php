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
                          <i class="fas fa-folder me-2"></i>
                          Quản Lý Danh Mục Tour
                      </a>
                  </div>
              </nav>

              <div class="container mt-4">
                  <div class="row">
                      <!-- Điểm đến -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-primary">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                              Điểm Đến</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_diem_den'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-diem-den" class="btn btn-primary btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-diem-den-create" class="btn btn-outline-primary btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Loại tour -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-success">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                              Loại Tour</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_loai_tour'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-suitcase fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-loai-tour" class="btn btn-success btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-loai-tour-create" class="btn btn-outline-success btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Tag tour -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-info">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                              Tag Tour</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_tag_tour'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-tags fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-tag-tour" class="btn btn-info btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-tag-tour-create" class="btn btn-outline-info btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Chính sách -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-warning">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                              Chính Sách</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_chinh_sach'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-chinh-sach" class="btn btn-warning btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-chinh-sach-create" class="btn btn-outline-warning btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Đối tác -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-secondary">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                              Đối Tác</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_doi_tac'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-doi-tac" class="btn btn-secondary btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-doi-tac-create" class="btn btn-outline-secondary btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Hướng dẫn viên -->
                      <div class="col-xl-4 col-md-6 mb-4">
                          <div class="card category-card border-left-danger">
                              <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                      <div class="col mr-2">
                                          <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                              HDV</div>
                                          <div class="h5 mb-0 font-weight-bold text-gray-800">
                                              <?php echo $thong_ke['tong_hdv'] ?? 0; ?>
                                          </div>
                                      </div>
                                      <div class="col-auto">
                                          <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                      </div>
                                  </div>
                                  <div class="mt-3">
                                      <a href="?act=danh-muc-hdv" class="btn btn-danger btn-sm">
                                          <i class="fas fa-list me-1"></i> Quản lý
                                      </a>
                                      <a href="?act=danh-muc-hdv-create" class="btn btn-outline-danger btn-sm">
                                          <i class="fas fa-plus me-1"></i> Thêm mới
                                      </a>
                                  </div>
                              </div>
                          </div>
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