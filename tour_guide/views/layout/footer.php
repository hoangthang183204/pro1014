</div> <footer>
        <p>WEBSITE LATA &copy; <?php echo date("Y"); ?> - Hệ thống quản lý tour du lịch</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                    // Chỉ thao tác class active trên các item có data-page
                    if (item.hasAttribute('data-page')) {
                        item.classList.remove('active');
                        if (item.getAttribute('data-page') === pageId) {
                            item.classList.add('active');
                        }
                    }
                });
            }
            
            // Bắt sự kiện click menu
            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const pageId = this.getAttribute('data-page');
                    
                    // --- ĐOẠN SỬA QUAN TRỌNG ---
                    // Chỉ ngăn chặn hành vi mặc định (load trang) nếu thẻ li đó có thuộc tính data-page
                    if (pageId) {
                        e.preventDefault(); 
                        switchPage(pageId);
                    }
                    // Nếu không có data-page (ví dụ: nút Đăng xuất, Nhật ký tour), 
                    // code sẽ bỏ qua đoạn này và để thẻ <a> trong sidebar hoạt động bình thường (chuyển trang).
                });
            });
            
            // Xử lý nút hủy logout (nếu bạn có dùng modal, còn nếu confirm mặc định thì không cần)
            const cancelLogoutBtn = document.querySelector('#logout button:last-child');
            if (cancelLogoutBtn) {
                cancelLogoutBtn.addEventListener('click', () => switchPage('dashboard'));
            }
        });
    </script>
</body>
</html>