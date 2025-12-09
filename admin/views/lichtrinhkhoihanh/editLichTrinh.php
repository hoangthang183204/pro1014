  <?php require './views/layout/header.php'; ?>
  <?php include './views/layout/navbar.php'; ?>
  <?php include './views/layout/sidebar.php'; ?>

  <div class="content-wrapper">
      <section class="content">
          <div class="container-fluid p-0">
              <!-- Header -->
              <nav class="navbar navbar-dark bg-dark">
                  <div class="container-fluid">
                      <a class="navbar-brand" href="?act=lich-khoi-hanh">
                          <i class="fas fa-edit me-2"></i>
                          Sửa Lịch Trình: Ngày <?php echo $lich_trinh['so_ngay']; ?>
                      </a>
                      <div>
                          <a href="?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" class="btn btn-outline-light">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                      </div>
                  </div>
              </nav>

              <div class="container mt-4">
                  <!-- Thông báo -->
                  <?php if (isset($_SESSION['success'])): ?>
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <div class="d-flex align-items-center">
                              <i class="fas fa-check-circle text-success me-2"></i>
                              <strong><?php echo $_SESSION['success'];
                                        unset($_SESSION['success']); ?></strong>
                          </div>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                  <?php endif; ?>

                  <?php if (isset($_SESSION['error'])): ?>
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <div class="d-flex align-items-center">
                              <i class="fas fa-exclamation-circle text-danger me-2"></i>
                              <strong><?php echo $_SESSION['error'];
                                        unset($_SESSION['error']); ?></strong>
                          </div>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                  <?php endif; ?>

                  <!-- Thông tin tour và lịch khởi hành -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <h5 class="card-title">Tour: <?php echo htmlspecialchars($tour['ma_tour'] . ' - ' . $tour['ten_tour']); ?></h5>
                          <p class="card-text mb-0">
                              <i class="fas fa-calendar-alt me-1"></i>
                              Ngày khởi hành: <?php echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau'])); ?>
                          </p>
                      </div>
                  </div>

                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa lịch trình</h5>
                      </div>
                      <div class="card-body">
                          <form method="POST" action="?act=lich-khoi-hanh-lich-trinh-update">
                              <input type="hidden" name="id" value="<?php echo $lich_trinh['id']; ?>">
                              <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich_khoi_hanh['id']; ?>">

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Số ngày <span class="text-danger">*</span></label>
                                          <input type="number" name="so_ngay" class="form-control" required
                                              min="1" max="30"
                                              value="<?php echo htmlspecialchars($lich_trinh['so_ngay']); ?>">
                                          <small class="text-muted">Số thứ tự ngày trong tour</small>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Tiêu đề ngày <span class="text-danger">*</span></label>
                                          <input type="text" name="tieu_de" class="form-control" required
                                              value="<?php echo htmlspecialchars($lich_trinh['tieu_de']); ?>">
                                          <small class="text-muted">VD: Khám phá Đà Lạt, Tận hưởng biển Nha Trang...</small>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Thứ tự sắp xếp</label>
                                          <input type="number" name="thu_tu_sap_xep" class="form-control"
                                              min="0" value="<?php echo htmlspecialchars($lich_trinh['thu_tu_sap_xep']); ?>">
                                          <small class="text-muted">Số nhỏ hiển thị trước</small>
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="mb-3">
                                          <label class="form-label">Chỗ ở</label>
                                          <input type="text" name="cho_o" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['cho_o']); ?>">
                                          <small class="text-muted">VD: Khách sạn 3 sao, Homestay...</small>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Bữa ăn</label>
                                          <input type="text" name="bua_an" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['bua_an']); ?>">
                                          <small class="text-muted">VD: Sáng: buffet, Trưa: nhà hàng...</small>
                                      </div>

                                      <div class="mb-3">
                                          <label class="form-label">Phương tiện</label>
                                          <input type="text" name="phuong_tien" class="form-control"
                                              value="<?php echo htmlspecialchars($lich_trinh['phuong_tien']); ?>">
                                          <small class="text-muted">VD: Xe ô tô 16 chỗ, Máy bay...</small>
                                      </div>
                                  </div>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Mô tả hoạt động chi tiết <span class="text-danger">*</span></label>
                                  <textarea name="mo_ta_hoat_dong" class="form-control" rows="6" required
                                      placeholder="Mô tả chi tiết các hoạt động trong ngày, lịch trình giờ giấc cụ thể..."><?php echo htmlspecialchars($lich_trinh['mo_ta_hoat_dong']); ?></textarea>
                                  <small class="text-muted">Mô tả càng chi tiết càng tốt để khách hàng nắm rõ lịch trình</small>
                              </div>

                              <div class="mb-3">
                                  <label class="form-label">Ghi chú cho HDV</label>
                                  <textarea name="ghi_chu_hdv" class="form-control" rows="3"
                                      placeholder="Các lưu ý đặc biệt cho hướng dẫn viên..."><?php echo htmlspecialchars($lich_trinh['ghi_chu_hdv']); ?></textarea>
                                  <small class="text-muted">Chỉ HDV mới nhìn thấy mục này</small>
                              </div>

                              <div class="d-flex justify-content-between">
                                  <a href="?act=lich-khoi-hanh-lich-trinh&lich_khoi_hanh_id=<?php echo $lich_khoi_hanh['id']; ?>" class="btn btn-secondary">
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
  </div>
  <?php include './views/layout/footer.php'; ?>

  <script>
      $(document).ready(function() {
          // Tự động ẩn thông báo sau 5 giây
          setTimeout(function() {
              $('.alert').fadeOut(300, function() {
                  $(this).remove();
              });
          }, 5000);

          // Xóa tham số success/error từ URL
          if (window.history.replaceState) {
              var urlParams = new URLSearchParams(window.location.search);
              if (urlParams.has('success') || urlParams.has('error')) {
                  urlParams.delete('success');
                  urlParams.delete('error');
                  var newUrl = window.location.pathname + '?' + urlParams.toString();
                  window.history.replaceState({}, document.title, newUrl);
              }
          }
      });
  </script>

  <style>
      .alert {
          border-radius: 8px;
          border: none;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          border-left: 4px solid transparent;
      }

      .alert-success {
          background: linear-gradient(135deg, #d4edda, #c3e6cb);
          color: #155724;
          border-left-color: #28a745;
      }

      .alert-danger {
          background: linear-gradient(135deg, #f8d7da, #f5c6cb);
          color: #721c24;
          border-left-color: #dc3545;
      }

      .form-label {
          font-weight: 600;
          color: #495057;
      }

      .form-control {
          border-radius: 6px;
          border: 1px solid #ced4da;
          padding: 0.5rem 0.75rem;
      }

      .form-control:focus {
          border-color: #80bdff;
          box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      }
  </style>