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
                          <a href="?act=tour" class="btn btn-outline-light me-2">
                              <i class="fas fa-arrow-left me-1"></i> Quay lại
                          </a>
                          <a href="?act=phien-ban-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-success">
                              <i class="fas fa-plus me-1"></i> Tạo Phiên Bản
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

                  <!-- Thông tin tour -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-4">
                                  <strong>Mã Tour:</strong> <?php echo htmlspecialchars($tour['ma_tour']); ?>
                              </div>
                              <div class="col-md-4">
                                  <strong>Tổng phiên bản:</strong>
                                  <span class="badge bg-primary"><?php echo count($phien_ban_list); ?> phiên bản</span>
                              </div>
                              <div class="col-md-4">
                                  <strong>Lịch trình hiện tại:</strong>
                                  <a href="?act=tour-lich-trinh&tour_id=<?php echo $tour['id']; ?>" class="btn btn-sm btn-outline-info">
                                      Xem lịch trình
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Danh sách phiên bản -->
                  <div class="card">
                      <div class="card-header">
                          <h5 class="mb-0">
                              <i class="fas fa-history me-2"></i>
                              Lịch sử phiên bản
                          </h5>
                      </div>
                      <div class="card-body">
                          <?php if (!empty($phien_ban_list)): ?>
                              <div class="row">
                                  <?php
                                    $phien_ban_moi_nhat = $this->tourModel->getPhienBanMoiNhat($tour['id']);
                                    ?>
                                  <?php foreach ($phien_ban_list as $phien_ban): ?>
                                      <div class="col-md-6 mb-3">
                                          <div class="card version-card <?php echo $phien_ban['id'] == $phien_ban_moi_nhat['id'] ? 'version-latest' : ''; ?>">
                                              <div class="card-body">
                                                  <div class="d-flex justify-content-between align-items-start mb-2">
                                                      <h6 class="card-title mb-0">
                                                          <?php echo htmlspecialchars($phien_ban['ten_phien_ban']); ?>
                                                          <?php if ($phien_ban['id'] == $phien_ban_moi_nhat['id']): ?>
                                                              <span class="badge bg-success ms-1">Mới nhất</span>
                                                          <?php endif; ?>
                                                      </h6>
                                                      <div class="btn-group btn-group-sm">
                                                          <a href="?act=phien-ban-xem&id=<?php echo $phien_ban['id']; ?>"
                                                              class="btn btn-outline-info" title="Xem chi tiết">
                                                              <i class="fas fa-eye"></i>
                                                          </a>
                                                          <a href="?act=phien-ban-ap-dung&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-warning"
                                                              onclick="return confirm('Bạn có chắc muốn áp dụng phiên bản này? Lịch trình hiện tại sẽ bị thay thế.')"
                                                              title="Áp dụng phiên bản">
                                                              <i class="fas fa-play"></i>
                                                          </a>
                                                          <a href="?act=phien-ban-edit&id=<?php echo $phien_ban['id']; ?>"
                                                              class="btn btn-outline-primary" title="Sửa">
                                                              <i class="fas fa-edit"></i>
                                                          </a>
                                                          <a href="?act=phien-ban-delete&id=<?php echo $phien_ban['id']; ?>&tour_id=<?php echo $tour['id']; ?>"
                                                              class="btn btn-outline-danger"
                                                              onclick="return confirm('Bạn có chắc muốn xóa phiên bản này?')"
                                                              title="Xóa">
                                                              <i class="fas fa-trash"></i>
                                                          </a>
                                                      </div>
                                                  </div>

                                                

                                                  <div class="d-flex justify-content-between align-items-center">
                                                      <small class="text-muted">
                                                          <i class="far fa-calendar me-1"></i>
                                                          <?php echo date('d/m/Y H:i', strtotime($phien_ban['created_at'])); ?>
                                                      </small>

                                                      <?php if ($phien_ban['ngay_hieu_luc']): ?>
                                                          <small class="text-info">
                                                              <i class="fas fa-calendar-check me-1"></i>
                                                              Hiệu lực: <?php echo date('d/m/Y', strtotime($phien_ban['ngay_hieu_luc'])); ?>
                                                          </small>
                                                      <?php endif; ?>
                                                  </div>

                                                  <?php
                                                    $lich_trinh_data = json_decode($phien_ban['du_lieu_lich_trinh'], true);
                                                    if (is_array($lich_trinh_data)):
                                                    ?>
                                                      <div class="mt-2">
                                                          <small class="text-muted">
                                                              <i class="fas fa-route me-1"></i>
                                                              <?php echo count($lich_trinh_data); ?> ngày lịch trình
                                                          </small>
                                                      </div>
                                                  <?php endif; ?>
                                              </div>
                                          </div>
                                      </div>
                                  <?php endforeach; ?>
                              </div>
                          <?php else: ?>
                              <div class="text-center py-4">
                                  <i class="fas fa-code-branch fa-3x text-muted mb-3"></i>
                                  <h5 class="text-muted">Chưa có phiên bản nào</h5>
                                  <p class="text-muted">Tạo phiên bản đầu tiên để lưu trữ các thay đổi lịch trình</p>
                                  <a href="?act=phien-ban-create&tour_id=<?php echo $tour['id']; ?>" class="btn btn-primary">
                                      <i class="fas fa-plus me-1"></i> Tạo Phiên Bản Đầu Tiên
                                  </a>
                              </div>
                          <?php endif; ?>
                      </div>
                  </div>

                  <!-- Hướng dẫn sử dụng -->
                  <div class="card mt-4">
                      <div class="card-header">
                          <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Hướng dẫn sử dụng phiên bản</h6>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-6">
                                  <h6>Khi nào nên tạo phiên bản?</h6>
                                  <ul class="small">
                                      <li>Trước khi thay đổi lớn lịch trình</li>
                                      <li>Sau khi hoàn thiện một mùa tour</li>
                                      <li>Khi có sự điều chỉnh cho dịp lễ, Tết</li>
                                      <li>Để lưu trữ các phiên bản theo mùa</li>
                                  </ul>
                              </div>
                              <div class="col-md-6">
                                  <h6>Tính năng chính:</h6>
                                  <ul class="small">
                                      <li><strong>Xem chi tiết:</strong> Xem lịch trình của phiên bản</li>
                                      <li><strong>Áp dụng:</strong> Khôi phục lịch trình từ phiên bản</li>
                                      <li><strong>Sao lưu:</strong> Tự động lưu toàn bộ lịch trình</li>
                                      <li><strong>Quản lý:</strong> Theo dõi lịch sử thay đổi</li>
                                  </ul>
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
  </body>

  </html>