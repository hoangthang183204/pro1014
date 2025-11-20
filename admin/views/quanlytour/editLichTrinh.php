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
                          <i class="fas fa-edit me-2"></i>
                          Sửa Lịch Trình: Ngày <?php echo $lich_trinh['so_ngay']; ?>
                      </a>
                      <div>
                          <a href="?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>" class="btn btn-outline-light">
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

                  <!-- Thông tin tour -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <h5 class="card-title">Tour: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?></h5>
                      </div>
                  </div>

                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa lịch trình</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=lich-trinh-update">
                              <input type="hidden" name="id" value="<?php echo $lich_trinh['id']; ?>">
                              <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Số ngày <span class="text-danger">*</span></label>
                                          <input type="number" name="so_ngay" class="form-control" required
                                              min="1" max="30"
                                              value="<?php echo htmlspecialchars($lich_trinh['so_ngay']); ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Tiêu đề ngày <span class="text-danger">*</span></label>
                                          <input type="text" name="tieu_de" class="form-control" required
                                              value="<?php echo htmlspecialchars($lich_trinh['tieu_de']); ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Thứ tự sắp xếp</label>
                                          <input type="number" name="thu_tu_sap_xep" class="form-control"
                                              min="0" value="<?php echo htmlspecialchars($lich_trinh['thu_tu_sap_xep']); ?>">
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Chỗ ở</label>
                                          <input type="text" name="cho_o" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['cho_o']); ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Bữa ăn</label>
                                          <input type="text" name="bua_an" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['bua_an']); ?>">
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Phương tiện</label>
                                          <input type="text" name="phuong_tien" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['phuong_tien']); ?>">
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Mô tả hoạt động chi tiết <span class="text-danger">*</span></label>
                                  <textarea name="mo_ta_hoat_dong" class="form-control" rows="6" required><?php echo htmlspecialchars($lich_trinh['mo_ta_hoat_dong']); ?></textarea>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Ghi chú cho HDV</label>
                                  <textarea name="ghi_chu_hdv" class="form-control" rows="3"><?php echo htmlspecialchars($lich_trinh['ghi_chu_hdv']); ?></textarea>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>" class="btn btn-secondary">
                                      <i class="fas fa-times me-1"></i> Hủy
                                  </a>
                                  <button type="submit" class="btn btn-success">
                                      <i class="fas fa-save me-1"></i> Cập nhật
                                  </button>
                              </div>
                          </form>
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