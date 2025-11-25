</div> <!-- Kết thúc .main-container -->

    <footer>
        <p>WEBSITE LATA &copy; <?php echo date("Y"); ?> - Hệ thống quản lý tour du lịch</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar li');
            const pageContents = document.querySelectorAll('.page-content');
            
            // Hàm chuyển trang (Tab switching)
            function switchPage(pageId) {
                // Ẩn tất cả content
                pageContents.forEach(page => page.classList.remove('active'));
                
                // Hiển thị content được chọn
                const activePage = document.getElementById(pageId);
                if (activePage) activePage.classList.add('active');
                
                // Cập nhật active trên sidebar
                sidebarItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-page') === pageId) {
                        item.classList.add('active');
                    }
                });
            }
            
            // Bắt sự kiện click menu
            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); // Ngăn chặn load lại trang nếu dùng thẻ a href="#"
                    const pageId = this.getAttribute('data-page');
                    switchPage(pageId);
                });
            });
            
            // Xử lý nút hủy logout
            const cancelLogoutBtn = document.querySelector('#logout button:last-child');
            if (cancelLogoutBtn) {
                cancelLogoutBtn.addEventListener('click', () => switchPage('dashboard'));
            }
        });
    </script>
</body>
</html>