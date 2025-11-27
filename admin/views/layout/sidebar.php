  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="./assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./assets/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION['admin_name'] ?? 'Khách' ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?= BASE_URL_ADMIN ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href=" <?= BASE_URL_ADMIN . '?act=danh-muc' ?> " class="nav-link">
              <i class="nav-icon fas fa-folder me-2"></i>
              <p>
                Danh Mục Tour
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href=" <?= BASE_URL_ADMIN . '?act=tour' ?> " class="nav-link">
              <i class="nav-icon fas fa-suitcase-rolling"></i>
              <p>
                Quản Lý Tour
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href=" <?= BASE_URL_ADMIN . '?act=lich-khoi-hanh' ?> " class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Lịch Trình Khởi Hành
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href=" <?= BASE_URL_ADMIN . '?act=dat-tour' ?> " class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                BooKing Tour
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Quản Lý Khách Hàng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= BASE_URL_ADMIN . '?act=khach-hang' ?>" class="nav-link">
                  <i class="fas fa-user-friends nav-icon"></i>
                  <p>Khách Hàng</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= BASE_URL_ADMIN . '?act=thanh-vien-tour' ?>" class="nav-link">
                  <i class="fas fa-user-check nav-icon"></i>
                  <p>Thành Viên</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href=" <?= BASE_URL_ADMIN . '?act=lich-lam-viec-hdv' ?> " class="nav-link">
              <i class="nav-icon fas fa-user-clock"></i>
              <p>
                Lịch Làm Việc HDV
              </p>
            </a>
          </li>

          <!--  -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <style>
    .nav-treeview {
      padding-left: 20px;
    }

    .nav-treeview .nav-link {
      padding-left: 40px !important;
    }

    .nav-treeview .nav-icon {
      margin-left: 10px;
    }
  </style>