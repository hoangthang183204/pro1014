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
                          <i class="fas fa-user-tie me-2"></i>
                          Phân Công HDV: <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour']); ?>
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
                              <div class="col-md-4">
                                  <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour'] . ' - ' . $lich_khoi_hanh['ten_tour']); ?>
                              </div>
                              <div class="col-md-4">
                                  <strong>Thời gian:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?> - <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc'])); ?>
                              </div>
                              <div class="col-md-4">
                                  <strong>Điểm đến:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ten_diem_den']); ?>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <!-- Form phân công HDV -->
                      <div class="col-md-6">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="mb-0"><i class="fas fa-users me-2"></i>Phân công HDV</h5>
                              </div>
                              <div class="card-body">
                                  <form method="POST" action="?act=phan-cong-hdv-store">
                                      <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                                      <div class="mb-3">
                                          <label class="form-label">Chọn HDV <span class="text-danger">*</span></label>
                                          <select name="hdv_id" class="form-select" required>
                                              <option value="">Chọn hướng dẫn viên</option>
                                              <?php foreach ($hdv_list as $hdv): ?>
                                                  <option value="<?php echo $hdv['id']; ?>"
                                                      <?php echo $phan_cong_hien_tai && $phan_cong_hien_tai['hdv_id'] == $hdv['id'] ? 'selected' : ''; ?>>
                                                      <?php echo htmlspecialchars($hdv['ten_hdv']); ?>
                                                      - <?php echo htmlspecialchars($hdv['chuyen_mon']); ?>
                                                      - Ngôn ngữ: <?php echo htmlspecialchars(implode(', ', json_decode($hdv['ky_nang_ngon_ngu'] ?? '[]'))); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Ghi chú phân công</label>
                                          <textarea name="ghi_chu" class="form-control" rows="3"
                                              placeholder="Ghi chú đặc biệt cho HDV..."><?php echo $phan_cong_hien_tai ? htmlspecialchars($phan_cong_hien_tai['ghi_chu']) : ''; ?></textarea>
                                      </div>

                                      <button type="submit" class="btn btn-success w-100">
                                          <i class="fas fa-save me-1"></i> Lưu Phân Công
                                      </button>
                                  </form>
                              </div>
                          </div>
                      </div>

                      <!-- Cảnh báo trùng lịch -->
                      <div class="col-md-6">
                          <div class="card">
                              <div class="card-header">
                                  <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo trùng lịch</h5>
                              </div>
                              <div class="card-body">
                                  <?php if (!empty($hdv_trung_lich)): ?>
                                      <div class="alert alert-warning">
                                          <h6>HDV sau đang bận trong khoảng thời gian này:</h6>
                                          <ul class="mb-0">
                                              <?php foreach ($hdv_trung_lich as $hdv): ?>
                                                  <li>
                                                      <strong><?php echo htmlspecialchars($hdv['ten_hdv']); ?></strong>
                                                      - Cần kiểm tra lại lịch trình
                                                  </li>
                                              <?php endforeach; ?>
                                          </ul>
                                      </div>
                                  <?php else: ?>
                                      <div class="alert alert-success">
                                          <i class="fas fa-check-circle me-2"></i>
                                          Không có HDV nào bị trùng lịch trong khoảng thời gian này.
                                      </div>
                                  <?php endif; ?>

                                  <!-- HDV đã phân công hiện tại -->
                                  <?php if ($phan_cong_hien_tai): ?>
                                      <div class="mt-3 p-3 bg-light rounded">
                                          <h6>HDV hiện tại:</h6>
                                          <p class="mb-1">
                                              <strong><?php echo htmlspecialchars($phan_cong_hien_tai['ten_hdv']); ?></strong>
                                          </p>
                                          <p class="mb-1 text-muted small">
                                              Chuyên môn: <?php echo htmlspecialchars($phan_cong_hien_tai['chuyen_mon']); ?>
                                          </p>
                                          <p class="mb-0 text-muted small">
                                              Ngôn ngữ: <?php echo htmlspecialchars(implode(', ', json_decode($phan_cong_hien_tai['ky_nang_ngon_ngu'] ?? '[]'))); ?>
                                          </p>
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