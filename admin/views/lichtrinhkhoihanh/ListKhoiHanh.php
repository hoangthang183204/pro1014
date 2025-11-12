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
                          <i class="fas fa-calendar-alt me-2"></i>
                          Quản Lý Lịch Khởi Hành
                      </a>
                      <a href="?act=lich-khoi-hanh-create" class="btn btn-success">
                          <i class="fas fa-plus me-1"></i> Thêm Lịch
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

                  <!-- Bộ lọc -->
                  <div class="card mb-4">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc theo thời gian & trạng thái</h5>
                      </div>
                      <div class="card-body">
                          <form method="GET">
                              <input type="hidden" name="act" value="lich-khoi-hanh">
                              <div class="row">
                                  <div class="col-md-3">
                                      <input type="text" name="search" class="form-control" placeholder="Tìm tour..."
                                          value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                  </div>
                                  <div class="col-md-2">
                                      <select name="thang" class="form-select">
                                          <?php for ($i = 1; $i <= 12; $i++): ?>
                                              <option value="<?php echo $i; ?>" <?php echo ($_GET['thang'] ?? date('m')) == $i ? 'selected' : ''; ?>>
                                                  Tháng <?php echo $i; ?>
                                              </option>
                                          <?php endfor; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <select name="nam" class="form-select">
                                          <?php for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++): ?>
                                              <option value="<?php echo $i; ?>" <?php echo ($_GET['nam'] ?? date('Y')) == $i ? 'selected' : ''; ?>>
                                                  Năm <?php echo $i; ?>
                                              </option>
                                          <?php endfor; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-3">
                                      <select name="trang_thai" class="form-select">
                                          <option value="">Tất cả trạng thái</option>
                                          <option value="đã_lên_lịch" <?php echo ($_GET['trang_thai'] ?? '') === 'đã_lên_lịch' ? 'selected' : ''; ?>>Đã lên lịch</option>
                                          <option value="đã_hoàn_thành" <?php echo ($_GET['trang_thai'] ?? '') === 'đã_hoàn_thành' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                          <option value="đã_hủy" <?php echo ($_GET['trang_thai'] ?? '') === 'đã_hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <button type="submit" class="btn btn-primary w-100">
                                          <i class="fas fa-search me-1"></i> Lọc
                                      </button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <!-- Danh sách lịch khởi hành -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Lịch khởi hành (<?php echo count($lich_khoi_hanh); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($lich_khoi_hanh)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Tour</th>
                                              <th>Thời gian</th>
                                              <th>Điểm đến</th>
                                              <th>HDV</th>
                                              <th>Trạng thái</th>
                                              <th width="200">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($lich_khoi_hanh as $lich): ?>
                                              <tr>
                                                  <td>
                                                      <div>
                                                          <strong class="text-primary"><?php echo htmlspecialchars($lich['ma_tour']); ?></strong><br>
                                                          <small class="text-muted"><?php echo htmlspecialchars($lich['ten_tour']); ?></small>
                                                      </div>
                                                  </td>
                                                  <td>
                                                      <div>
                                                          <strong><?php echo date('d/m/Y', strtotime($lich['ngay_bat_dau'])); ?></strong><br>
                                                          <small class="text-muted"><?php echo $lich['gio_tap_trung']; ?></small>
                                                      </div>
                                                  </td>
                                                  <td><?php echo htmlspecialchars($lich['ten_diem_den']); ?></td>
                                                  <td>
                                                      <?php if ($lich['ten_hdv']): ?>
                                                          <span class="badge bg-info"><?php echo htmlspecialchars($lich['ten_hdv']); ?></span>
                                                      <?php else: ?>
                                                          <span class="badge bg-warning">Chưa phân công</span>
                                                      <?php endif; ?>
                                                  </td>
                                                  <td>
                                                      <span class="badge bg-<?php
                                                                            echo match ($lich['trang_thai']) {
                                                                                'đã_lên_lịch' => 'success',
                                                                                'đã_hoàn_thành' => 'primary',
                                                                                'đã_hủy' => 'danger',
                                                                                default => 'secondary'
                                                                            };
                                                                            ?>">
                                                          <?php echo htmlspecialchars($lich['trang_thai']); ?>
                                                      </span>
                                                  </td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=lich-khoi-hanh-edit&id=<?php echo $lich['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <a href="?act=phan-cong-hdv&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                              class="btn btn-outline-info" title="Phân công HDV">
                                                              <i class="fas fa-user-tie"></i>
                                                          </a>
                                                          <a href="?act=dich-vu-kem-theo&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                              class="btn btn-outline-warning" title="Dịch vụ">
                                                              <i class="fas fa-concierge-bell"></i>
                                                          </a>
                                                          <a href="?act=checklist-chuan-bi&lich_khoi_hanh_id=<?php echo $lich['id']; ?>"
                                                              class="btn btn-outline-success" title="Checklist">
                                                              <i class="fas fa-tasks"></i>
                                                          </a>
                                                          <a href="?act=lich-khoi-hanh-delete&id=<?php echo $lich['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa lịch này?')"
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
                                  <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Không có lịch khởi hành</h5>
                                  <p class="text-muted">Hãy tạo lịch khởi hành mới</p>
                                  <a href="?act=lich-khoi-hanh-create" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Tạo Lịch Đầu Tiên
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