<?php 
// Hiển thị dashboard giống admin nhưng dùng layout hướng dẫn viên
// Partial dashboard (admin) được reuse
// if (session_status() !== PHP_SESSION_ACTIVE) {
//     session_start();
// }

// Nếu chưa login -> chuyển tới trang login admin (hoặc bạn có thể đổi sang login hướng dẫn viên)
// if (empty($_SESSION['admin_id'])) {
//     header('Location: ' . BASE_URL_ADMIN . '?act=login');
//     exit();
// }

// Include header/sidebar của tour_guide rồi include partial admin dashboard
require './views/layout/header.php';
require './views/layout/sidebar.php';
require './views/layout/footer.php';
?>
