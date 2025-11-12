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
                          <i class="fas fa-suitcase me-2"></i>
                          Quản Lý Tour
                      </a>
                      <a href="?act=tour-create" class="btn btn-success">
                          <i class="fas fa-plus me-1"></i> Thêm Tour
                      </a>
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

                  <!-- Bộ lọc và tìm kiếm -->
                  <div class="card mb-4">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Tìm kiếm & Lọc</h5>
                      </div>
                      <div class="card-body">
                          <form method="GET">
                              <input type="hidden" name="act" value="tour">
                              <div class="row">
                                  <div class="col-md-4">
                                      <input type="text" name="search" class="form-control" placeholder="Tìm theo mã tour, tên tour..."
                                          value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                  </div>
                                  <div class="col-md-3">
                                      <select name="trang_thai" class="form-select">
                                          <option value="">Tất cả trạng thái</option>
                                          <option value="bản_nháp" <?php echo ($_GET['trang_thai'] ?? '') === 'bản_nháp' ? 'selected' : ''; ?>>Bản nháp</option>
                                          <option value="đang_hoạt_động" <?php echo ($_GET['trang_thai'] ?? '') === 'đang_hoạt_động' ? 'selected' : ''; ?>>Đang hoạt động</option>
                                          <option value="ngừng_hoạt_động" <?php echo ($_GET['trang_thai'] ?? '') === 'ngừng_hoạt_động' ? 'selected' : ''; ?>>Ngừng hoạt động</option>
                                      </select>
                                  </div>
                                  <div class="col-md-3">
                                      <select name="diem_den_id" class="form-select">
                                          <option value="">Tất cả điểm đến</option>
                                          <?php foreach ($diem_den_list as $diem_den): ?>
                                              <option value="<?php echo $diem_den['id']; ?>"
                                                  <?php echo ($_GET['diem_den_id'] ?? '') == $diem_den['id'] ? 'selected' : ''; ?>>
                                                  <?php echo htmlspecialchars($diem_den['ten_diem_den']); ?>
                                              </option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <button type="submit" class="btn btn-primary w-100">
                                          <i class="fas fa-search me-1"></i> Tìm kiếm
                                      </button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <!-- Danh sách tour -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách Tour (<?php echo count($tours); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($tours)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Mã Tour</th>
                                              <th>Tên Tour</th>
                                              <th>Điểm đến</th>
                                              <th>Loại tour</th>
                                              <th>Trạng thái</th>
                                              <th>Ngày tạo</th>
                                              <th width="150">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($tours as $tour): ?>
                                              <tr>
                                                  <td>
                                                      <strong class="text-primary"><?php echo htmlspecialchars($tour['ma_tour']); ?></strong>
                                                  </td>
                                                  <td><?php echo htmlspecialchars($tour['ten_tour']); ?></td>
                                                  <td><?php echo htmlspecialchars($tour['ten_diem_den']); ?></td>
                                                  <td><?php echo htmlspecialchars($tour['ten_loai']); ?></td>
                                                  <td>
                                                      <span class="badge bg-<?php
                                                                            echo match ($tour['trang_thai']) {
                                                                                'đang_hoạt_động' => 'success',
                                                                                'bản_nháp' => 'secondary',
                                                                                'ngừng_hoạt_động' => 'danger',
                                                                                default => 'warning'
                                                                            };
                                                                            ?>">
                                                          <?php echo htmlspecialchars($tour['trang_thai']); ?>
                                                      </span>
                                                  </td>
                                                  <td><?php echo date('d/m/Y', strtotime($tour['created_at'])); ?></td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=tour-edit&id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <a href="?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-info" title="Lịch trình">
                                                              <i class="fas fa-route"></i>
                                                          </a>
                                                          <a href="?act=tour-media&tour_id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-warning" title="Media">
                                                              <i class="fas fa-images"></i>
                                                          </a>
                                                          <a href="?act=tour-phien-ban&tour_id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-secondary" title="Phiên bản">
                                                              <i class="fas fa-code-branch"></i>
                                                          </a>
                                                          <a href="?act=tour-delete&id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa tour này?')"
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
                                  <h5 class="text-muted">Không có tour nào</h5>
                                  <p class="text-muted">Hãy thêm tour mới để bắt đầu</p>
                                  <a href="?act=tour-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Thêm Tour Đầu Tiên
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