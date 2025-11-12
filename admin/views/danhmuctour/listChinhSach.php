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
                          <i class="fas fa-file-contract me-2"></i>
                          Quản Lý Chính Sách Tour
                      </a>
                      <div>
                          <a href="?act=danh-muc" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Danh mục
                          </a>
                          <a href="?act=danh-muc-chinh-sach-create" class="btn btn-success">
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

                  <!-- Danh sách chính sách -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách chính sách (<?php echo count($chinh_sach_list); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($chinh_sach_list)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th width="50">#</th>
                                              <th>Tên chính sách</th>
                                              <th>Quy định hủy/đổi</th>
                                              <th>Ngày tạo</th>
                                              <th width="120">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($chinh_sach_list as $index => $chinh_sach): ?>
                                              <tr>
                                                  <td><?php echo $index + 1; ?></td>
                                                  <td>
                                                      <strong><?php echo htmlspecialchars($chinh_sach['ten_chinh_sach']); ?></strong>
                                                  </td>
                                                  <td>
                                                      <?php if ($chinh_sach['quy_dinh_huy_doi']): ?>
                                                          <small class="text-muted">
                                                              <?php
                                                                $quy_dinh = strip_tags($chinh_sach['quy_dinh_huy_doi']);
                                                                echo strlen($quy_dinh) > 100 ? substr($quy_dinh, 0, 100) . '...' : $quy_dinh;
                                                                ?>
                                                          </small>
                                                      <?php else: ?>
                                                          <span class="text-muted">Chưa có quy định</span>
                                                      <?php endif; ?>
                                                  </td>
                                                  <td><?php echo date('d/m/Y', strtotime($chinh_sach['created_at'])); ?></td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=danh-muc-chinh-sach-edit&id=<?php echo $chinh_sach['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <button type="button" class="btn btn-outline-info"
                                                              data-bs-toggle="modal" data-bs-target="#modalChinhSach<?php echo $chinh_sach['id']; ?>"
                                                              title="Xem chi tiết">
                                                              <i class="fas fa-eye"></i>
                                                          </button>
                                                          <a href="?act=danh-muc-chinh-sach-delete&id=<?php echo $chinh_sach['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa chính sách này?')"
                                                              title="Xóa">
                                                              <i class="fas fa-trash"></i>
                                                          </a>
                                                      </div>
                                                  </td>
                                              </tr>

                                              <!-- Modal xem chi tiết -->
                                              <div class="modal fade" id="modalChinhSach<?php echo $chinh_sach['id']; ?>" tabindex="-1">
                                                  <div class="modal-dialog modal-lg">
                                                      <div class="modal-content">
                                                          <div class="modal-header">
                                                              <h5 class="modal-title"><?php echo htmlspecialchars($chinh_sach['ten_chinh_sach']); ?></h5>
                                                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                          </div>
                                                          <div class="modal-body">
                                                              <div class="mb-3">
                                                                  <strong>Quy định hủy/đổi:</strong>
                                                                  <p><?php echo nl2br(htmlspecialchars($chinh_sach['quy_dinh_huy_doi'])); ?></p>
                                                              </div>
                                                              <?php if ($chinh_sach['luu_y_suc_khoe']): ?>
                                                                  <div class="mb-3">
                                                                      <strong>Lưu ý sức khỏe:</strong>
                                                                      <p><?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_suc_khoe'])); ?></p>
                                                                  </div>
                                                              <?php endif; ?>
                                                              <?php if ($chinh_sach['luu_y_hanh_ly']): ?>
                                                                  <div class="mb-3">
                                                                      <strong>Lưu ý hành lý:</strong>
                                                                      <p><?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_hanh_ly'])); ?></p>
                                                                  </div>
                                                              <?php endif; ?>
                                                              <?php if ($chinh_sach['luu_y_khac']): ?>
                                                                  <div class="mb-3">
                                                                      <strong>Lưu ý khác:</strong>
                                                                      <p><?php echo nl2br(htmlspecialchars($chinh_sach['luu_y_khac'])); ?></p>
                                                                  </div>
                                                              <?php endif; ?>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          <?php endforeach; ?>
                                      </tbody>
                                  </table>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có chính sách nào</h5>
                                  <p class="text-muted">Hãy thêm chính sách mới để áp dụng cho tour</p>
                                  <a href="?act=danh-muc-chinh-sach-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm Chính Sách Đầu Tiên
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