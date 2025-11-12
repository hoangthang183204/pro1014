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
                          <i class="fas fa-plus me-2"></i>
                          Thêm Tour Mới
                      </a>
                      <div>
                          <a href="?act=tour" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông báo -->
                  <?php if (isset($_GET['error'])): ?>
                      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                  <?php endif; ?>

                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=tour-store">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Mã Tour <span class="text-danger">*</span></label>
                                          <input type="text" name="ma_tour" class="form-control" required
                                              placeholder="VD: DL-001, NT-2024">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Tên Tour <span class="text-danger">*</span></label>
                                          <input type="text" name="ten_tour" class="form-control" required
                                              placeholder="Nhập tên tour">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Điểm đến <span class="text-danger">*</span></label>
                                          <select name="diem_den_id" class="form-select" required>
                                              <option value="">Chọn điểm đến</option>
                                              <?php foreach ($diem_den_list as $diem_den): ?>
                                                  <option value="<?php echo $diem_den['id']; ?>">
                                                      <?php echo htmlspecialchars($diem_den['ten_diem_den']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Loại tour <span class="text-danger">*</span></label>
                                          <select name="loai_tour_id" class="form-select" required>
                                              <option value="">Chọn loại tour</option>
                                              <?php foreach ($loai_tour_list as $loai_tour): ?>
                                                  <option value="<?php echo $loai_tour['id']; ?>">
                                                      <?php echo htmlspecialchars($loai_tour['ten_loai']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Chính sách tour</label>
                                          <select name="chinh_sach_id" class="form-select">
                                              <option value="">Chọn chính sách</option>
                                              <?php foreach ($chinh_sach_list as $chinh_sach): ?>
                                                  <option value="<?php echo $chinh_sach['id']; ?>">
                                                      <?php echo htmlspecialchars($chinh_sach['ten_chinh_sach']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Tags</label>
                                          <select name="tag_ids[]" class="form-select" multiple>
                                              <?php foreach ($tag_list as $tag): ?>
                                                  <option value="<?php echo $tag['id']; ?>">
                                                      <?php echo htmlspecialchars($tag['ten_tag']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                          <!-- <small class="text-muted">Giữ Ctrl để chọn nhiều tags</small> -->
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Mô tả tuyến</label>
                                  <textarea name="mo_ta_tuyen" class="form-control" rows="4"
                                      placeholder="Mô tả ngắn về hành trình tour..."></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=tour" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Lưu Tour
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
          <!-- /.row -->

          <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php include './views/layout/footer.php'; ?>
  <!-- End Footer -->
  </body>

  </html>