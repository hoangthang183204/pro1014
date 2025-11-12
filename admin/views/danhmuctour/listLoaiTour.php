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
                          <i class="fas fa-suitcase me-2"></i>
                          Quản Lý Loại Tour
                      </a>
                      <div>
                          <a href="?act=danh-muc" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Danh mục
                          </a>
                          <a href="?act=danh-muc-loai-tour-create" class="btn btn-success">
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

                  <!-- Danh sách loại tour -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách loại tour (<?php echo count($loai_tour_list); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($loai_tour_list)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th width="50">#</th>
                                              <th>Tên loại tour</th>
                                              <th>Mô tả</th>
                                              <th>Ngày tạo</th>
                                              <th width="120">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($loai_tour_list as $index => $loai_tour): ?>
                                              <tr>
                                                  <td><?php echo $index + 1; ?></td>
                                                  <td>
                                                      <strong><?php echo htmlspecialchars($loai_tour['ten_loai']); ?></strong>
                                                  </td>
                                                  <td>
                                                      <?php if ($loai_tour['mo_ta']): ?>
                                                          <small class="text-muted"><?php echo htmlspecialchars($loai_tour['mo_ta']); ?></small>
                                                      <?php else: ?>
                                                          <span class="text-muted">Chưa có mô tả</span>
                                                      <?php endif; ?>
                                                  </td>
                                                  <td><?php echo date('d/m/Y', strtotime($loai_tour['created_at'])); ?></td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=danh-muc-loai-tour-edit&id=<?php echo $loai_tour['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <a href="?act=danh-muc-loai-tour-delete&id=<?php echo $loai_tour['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa loại tour này?')"
                                                              title="Xóa">
                                                              <i class="fas fa-trash"></i>
                                                          </a>
                                                      </div>
                                                  </td>
                                              </tr>
                                          <?php endforeach; ?>
                                      </tbody>
                                  </table>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-suitcase fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có loại tour nào</h5>
                                  <p class="text-muted">Hãy thêm loại tour mới để bắt đầu</p>
                                  <a href="?act=danh-muc-loai-tour-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm Loại Tour Đầu Tiên
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