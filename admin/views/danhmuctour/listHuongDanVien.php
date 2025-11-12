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
                          <i class="fas fa-user-tie me-2"></i>
                          Quản Lý Hướng Dẫn Viên
                      </a>
                      <div>
                          <a href="?act=danh-muc" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Danh mục
                          </a>
                          <a href="?act=danh-muc-hdv-create" class="btn btn-success">
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

                  <!-- Danh sách HDV -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách hướng dẫn viên (<?php echo count($hdv_list); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($hdv_list)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th width="50">#</th>
                                              <th>Họ tên</th>
                                              <th>Chuyên môn</th>
                                              <th>Ngôn ngữ</th>
                                              <th>Trạng thái</th>
                                              <th width="120">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($hdv_list as $index => $hdv): ?>
                                              <tr>
                                                  <td><?php echo $index + 1; ?></td>
                                                  <td>
                                                      <strong><?php echo htmlspecialchars($hdv['ten_hdv']); ?></strong>
                                                      <?php if ($hdv['thong_tin_lien_he']): ?>
                                                          <br><small class="text-muted"><?php echo htmlspecialchars($hdv['thong_tin_lien_he']); ?></small>
                                                      <?php endif; ?>
                                                  </td>
                                                  <td>
                                                      <small class="text-muted"><?php echo htmlspecialchars($hdv['chuyen_mon']); ?></small>
                                                  </td>
                                                  <td>
                                                      <?php
                                                        $ngon_ngu = json_decode($hdv['ky_nang_ngon_ngu'], true);
                                                        if (is_array($ngon_ngu)):
                                                            foreach ($ngon_ngu as $nn):
                                                        ?>
                                                              <span class="badge bg-info me-1"><?php echo strtoupper($nn); ?></span>
                                                      <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                  </td>
                                                  <td>
                                                      <span class="badge bg-<?php echo $hdv['trang_thai'] == 'đang_làm_việc' ? 'success' : 'secondary'; ?>">
                                                          <?php echo htmlspecialchars($hdv['trang_thai']); ?>
                                                      </span>
                                                  </td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=danh-muc-hdv-edit&id=<?php echo $hdv['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <a href="?act=danh-muc-hdv-delete&id=<?php echo $hdv['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa HDV này?')"
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
                                  <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có hướng dẫn viên nào</h5>
                                  <p class="text-muted">Hãy thêm HDV mới để phân công tour</p>
                                  <a href="?act=danh-muc-hdv-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm HDV Đầu Tiên
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