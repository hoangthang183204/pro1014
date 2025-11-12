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
                          <i class="fas fa-code-branch me-2"></i>
                          Phiên Bản: <?php echo htmlspecialchars($tour['ten_tour']); ?>
                      </a>
                      <div>
                          <a href="?act=tour" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Tạo phiên bản mới -->
                  <div class="card mb-4">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Tạo phiên bản mới</h5>
                      </div>
                      <div class="card-body">
                          <form>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="mb-3">
                                          <label class="form-label">Tên phiên bản</label>
                                          <input type="text" class="form-control" placeholder="VD: Mùa hè 2024, Tết Nguyên Đán">
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="mb-3">
                                          <label class="form-label">Ngày hiệu lực</label>
                                          <input type="date" class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="mb-3">
                                          <label class="form-label">&nbsp;</label>
                                          <button type="submit" class="btn btn-success w-100">
                                              <i class="fas fa-save me-1"></i> Tạo Phiên Bản
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <!-- Danh sách phiên bản -->
                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0">Lịch sử phiên bản (<?php echo count($phien_ban_list); ?>)</h5>
                      </div>
                      <div class="card-body p-0">
                          <?php if (!empty($phien_ban_list)): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover mb-0">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Tên phiên bản</th>
                                              <th>Ngày hiệu lực</th>
                                              <th>Ngày tạo</th>
                                              <th width="120">Thao tác</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($phien_ban_list as $phien_ban): ?>
                                              <tr>
                                                  <td>
                                                      <strong><?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?></strong>
                                                  </td>
                                                  <td>
                                                      <?php if ($phien_ban['ngay_hieu_luc']): ?>
                                                          <?php echo date('d/m/Y', strtotime($phien_ban['ngay_hieu_luc'])); ?>
                                                      <?php else: ?>
                                                          <span class="text-muted">Chưa đặt</span>
                                                      <?php endif; ?>
                                                  </td>
                                                  <td><?php echo date('d/m/Y H:i', strtotime($phien_ban['created_at'])); ?></td>
                                                  <td>
                                                      <div class="btn-group btn-group-sm">
                                                          <button class="btn btn-outline-primary" title="Xem chi tiết">
                                                              <i class="fas fa-eye"></i>
                                                          </button>
                                                          <button class="btn btn-outline-success" title="Áp dụng">
                                                              <i class="fas fa-play"></i>
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
                                  <i class="fas fa-code-branch fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có phiên bản nào</h5>
                                  <p class="text-muted">Tạo phiên bản đầu tiên để lưu trữ các thay đổi</p>
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
  </body>

  </html>