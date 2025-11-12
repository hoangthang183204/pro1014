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
                          <i class="fas fa-tasks me-2"></i>
                          Checklist Chuẩn Bị: <?php echo htmlspecialchars($lich_khoi_hanh['ten_tour']); ?>
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
                              <div class="col-md-3">
                                  <strong>Tour:</strong> <?php echo htmlspecialchars($lich_khoi_hanh['ma_tour']); ?>
                              </div>
                              <div class="col-md-3">
                                  <strong>Ngày đi:</strong> <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>
                              </div>
                              <div class="col-md-3">
                                  <strong>HDV:</strong>
                                  <?php
                                    $hdv_phan_cong = $this->lichKhoiHanhModel->getPhanCongHDV($lich_khoi_hanh['id']);
                                    if ($hdv_phan_cong):
                                    ?>
                                      <span class="badge bg-info"><?php echo htmlspecialchars($hdv_phan_cong['ten_hdv']); ?></span>
                                  <?php else: ?>
                                      <span class="badge bg-warning">Chưa phân công</span>
                                  <?php endif; ?>
                              </div>
                              <div class="col-md-3">
                                  <strong>Tiến độ:</strong>
                                  <?php
                                    $hoan_thanh = 0;
                                    $tong = count($checklist);
                                    if ($tong > 0) {
                                        $hoan_thanh = count(array_filter($checklist, function ($item) {
                                            return $item['hoan_thanh'];
                                        }));
                                        $phan_tram = round(($hoan_thanh / $tong) * 100);
                                    } else {
                                        $phan_tram = 0;
                                    }
                                    ?>
                                  <div class="progress mt-1" style="height: 10px;">
                                      <div class="progress-bar bg-success" role="progressbar"
                                          style="width: <?php echo $phan_tram; ?>%"
                                          aria-valuenow="<?php echo $phan_tram; ?>" aria-valuemin="0" aria-valuemax="100">
                                      </div>
                                  </div>
                                  <small><?php echo $hoan_thanh; ?>/<?php echo $tong; ?> hoàn thành (<?php echo $phan_tram; ?>%)</small>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Form thêm checklist -->
                  <div class="card mb-4">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Thêm mục checklist</h5>
                      </div>
                      <div class="card-body">
                          <form id="formThemChecklist">
                              <div class="row">
                                  <div class="col-md-10">
                                      <div class="mb-3">
                                          <input type="text" name="cong_viec" class="form-control"
                                              placeholder="Nhập công việc cần chuẩn bị..." required>
                                      </div>
                                  </div>
                                  <div class="col-md-2">
                                      <button type="submit" class="btn btn-primary w-100">
                                          <i class="fas fa-plus me-1"></i> Thêm
                                      </button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <!-- Danh sách checklist -->
                  <div class="card">
                      <div class="card-header d-flex justify-content-between align-items-center">
                          <h5 class="mb-0">Danh sách công việc cần chuẩn bị</h5>
                          <div>
                              <button class="btn btn-sm btn-outline-success" id="btnCheckAll">
                                  <i class="fas fa-check-double me-1"></i> Check tất cả
                              </button>
                              <button class="btn btn-sm btn-outline-secondary" id="btnUncheckAll">
                                  <i class="fas fa-times me-1"></i> Bỏ check
                              </button>
                          </div>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($checklist)): ?>
                              <div class="list-group list-group-flush">
                                  <?php foreach ($checklist as $index => $item): ?>
                                      <div class="list-group-item checklist-item <?php echo $item['hoan_thanh'] ? 'completed' : ''; ?>">
                                          <div class="form-check d-flex align-items-center">
                                              <input class="form-check-input me-3" type="checkbox"
                                                  id="checklist-<?php echo $item['id']; ?>"
                                                  <?php echo $item['hoan_thanh'] ? 'checked' : ''; ?>
                                                  data-id="<?php echo $item['id']; ?>">
                                              <label class="form-check-label flex-grow-1" for="checklist-<?php echo $item['id']; ?>">
                                                  <div class="d-flex justify-content-between align-items-center">
                                                      <span class="<?php echo $item['hoan_thanh'] ? 'text-decoration-line-through text-muted' : ''; ?>">
                                                          <?php echo htmlspecialchars($item['cong_viec']); ?>
                                                      </span>
                                                      <div>
                                                          <?php if ($item['hoan_thanh'] && $item['thoi_gian_hoan_thanh']): ?>
                                                              <small class="text-success">
                                                                  <i class="fas fa-check me-1"></i>
                                                                  Hoàn thành: <?php echo date('H:i d/m', strtotime($item['thoi_gian_hoan_thanh'])); ?>
                                                              </small>
                                                          <?php endif; ?>
                                                          <button class="btn btn-sm btn-outline-danger ms-2"
                                                              onclick="xoaChecklist(<?php echo $item['id']; ?>)">
                                                              <i class="fas fa-trash"></i>
                                                          </button>
                                                      </div>
                                                  </div>
                                              </label>
                                          </div>
                                      </div>
                                  <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có checklist</h5>
                                  <p class="text-muted">Thêm các công việc cần chuẩn bị trước tour</p>
                              </div>
                          <?php endif; ?>
                      </div>
                  </div>

                  <!-- Checklist mẫu -->
                  <div class="card mt-4">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Checklist mẫu (Thêm nhanh)</h5>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-4">
                                  <h6>Tài liệu & Vé</h6>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="In danh sách khách hàng">
                                      In danh sách khách
                                  </button>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị vé máy bay">
                                      Vé máy bay
                                  </button>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị vé tham quan">
                                      Vé tham quan
                                  </button>
                              </div>
                              <div class="col-md-4">
                                  <h6>Vận chuyển</h6>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Xác nhận xe vận chuyển">
                                      Xác nhận xe
                                  </button>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Kiểm tra lộ trình xe">
                                      Lộ trình xe
                                  </button>
                              </div>
                              <div class="col-md-4">
                                  <h6>Khác</h6>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị dụng cụ y tế">
                                      Dụng cụ y tế
                                  </button>
                                  <button class="btn btn-sm btn-outline-secondary mb-1 add-template" data-text="Chuẩn bị bảng tên HDV">
                                      Bảng tên HDV
                                  </button>
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