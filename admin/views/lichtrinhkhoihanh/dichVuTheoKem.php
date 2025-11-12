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
                          <i class="fas fa-concierge-bell me-2"></i>
                          Dịch Vụ Kèm Theo: <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour']); ?>
                      </a>
                      <div>
                          <a href="?act=lich-khoi-hanh" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông tin lịch -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-6">
                                  <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour'] . ' - ' . $lich_khoi_hanh['ten_tour']); ?>
                              </div>
                              <div class="col-md-6">
                                  <strong>Thời gian:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?> - <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <!-- Form thêm dịch vụ -->
                      <div class="col-md-4">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Thêm dịch vụ</h5>
                              </div>
                              <div class="card-body">
                                  <form>
                                      <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                                      <div class="mb-3">
                                          <label class="form-label">Loại dịch vụ</label>
                                          <select name="loai_dich_vu" class="form-select">
                                              <option value="vận_chuyển">Vận chuyển</option>
                                              <option value="khách_sạn">Khách sạn</option>
                                              <option value="nhà_hàng">Nhà hàng</option>
                                              <option value="vé_tham_quan">Vé tham quan</option>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Tên dịch vụ</label>
                                          <input type="text" name="ten_dich_vu" class="form-control"
                                              placeholder="VD: Xe 16 chỗ, Khách sạn ABC...">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Đối tác</label>
                                          <select name="doi_tac_id" class="form-select">
                                              <option value="">Chọn đối tác</option>
                                              <?php foreach ($doi_tac_list as $doi_tac): ?>
                                                  <option value="<?php echo $doi_tac['id']; ?>">
                                                      <?php echo htmlspecialchars($doi_tac['ten_doi_tac']); ?>
                                                      (<?php echo htmlspecialchars($doi_tac['loai_dich_vu']); ?>)
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Trạng thái xác nhận</label>
                                          <select name="trang_thai_xac_nhan" class="form-select">
                                              <option value="chờ_xác_nhận">Chờ xác nhận</option>
                                              <option value="đã_xác_nhận">Đã xác nhận</option>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Ghi chú</label>
                                          <textarea name="ghi_chu" class="form-control" rows="3"
                                              placeholder="Ghi chú về dịch vụ..."></textarea>
                                      </div>

                                      <button type="submit" class="btn btn-success w-100">
                                          <i class="fas fa-save me-1"></i> Thêm Dịch Vụ
                                      </button>
                                  </form>
                              </div>
                          </div>
                      </div>

                      <!-- Danh sách dịch vụ -->
                      <div class="col-md-8">
                          <div class="card">
                              <div class="card-header d-flex justify-content-between align-items-center">
                                  <h5 class="mb-0">Danh sách dịch vụ (<?php echo count($dich_vu_list); ?>)</h5>
                              </div>
                              <div class="card-body p-0">
                                  <?php if (!empty($dich_vu_list)): ?>
                                      <div class="table-responsive">
                                          <table class="table table-hover mb-0">
                                              <thead class="table-light">
                                                  <tr>
                                                      <th>Loại dịch vụ</th>
                                                      <th>Tên dịch vụ</th>
                                                      <th>Đối tác</th>
                                                      <th>Trạng thái</th>
                                                      <th width="100">Thao tác</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  <?php foreach ($dich_vu_list as $dich_vu): ?>
                                                      <tr>
                                                          <td>
                                                              <span class="badge bg-<?php
                                                                                    echo match ($dich_vu['loai_phan_cong']) {
                                                                                        'vận_chuyển' => 'primary',
                                                                                        'khách_sạn' => 'success',
                                                                                        'nhà_hàng' => 'warning',
                                                                                        'vé_tham_quan' => 'info',
                                                                                        default => 'secondary'
                                                                                    };
                                                                                    ?>">
                                                                  <?php echo htmlspecialchars($dich_vu['loai_phan_cong']); ?>
                                                              </span>
                                                          </td>
                                                          <td><?php echo htmlspecialchars($dich_vu['ten_dich_vu']); ?></td>
                                                          <td>
                                                              <?php if ($dich_vu['ten_doi_tac']): ?>
                                                                  <?php echo htmlspecialchars($dich_vu['ten_doi_tac']); ?>
                                                              <?php else: ?>
                                                                  <span class="text-muted">Không có</span>
                                                              <?php endif; ?>
                                                          </td>
                                                          <td>
                                                              <span class="badge bg-<?php
                                                                                    echo match ($dich_vu['trang_thai_xac_nhan']) {
                                                                                        'đã_xác_nhận' => 'success',
                                                                                        'chờ_xác_nhận' => 'warning',
                                                                                        'đã_hủy' => 'danger',
                                                                                        default => 'secondary'
                                                                                    };
                                                                                    ?>">
                                                                  <?php echo htmlspecialchars($dich_vu['trang_thai_xac_nhan']); ?>
                                                              </span>
                                                          </td>
                                                          <td>
                                                              <div class="btn-group btn-group-sm">
                                                                  <button class="btn btn-outline-primary" title="Sửa">
                                                                      <i class="fas fa-edit"></i>
                                                                  </button>
                                                                  <button class="btn btn-outline-danger" title="Xóa">
                                                                      <i class="fas fa-trash"></i>
                                                                  </button>
                                                              </div>
                                                          </td>
                                                      </tr>
                                                  <?php endforeach; ?>
                                              </tbody>
                                          </table>
                                      </div>
                                  <?php else: ?>
                                      <div class="text-center py-4">
                                          <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
                                          <h5 class="text-muted">Chưa có dịch vụ nào</h5>
                                          <p class="text-muted">Thêm dịch vụ cho lịch khởi hành này</p>
                                      </div>
                                  <?php endif; ?>
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