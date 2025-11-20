<footer>
        <p>WEBSITE LATA &copy; 2023 - Hệ thống quản lý tour du lịch</p>
    </footer>

    <script>
        // JavaScript cho chuyển trang khi click sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar li');
            const pageContents = document.querySelectorAll('.page-content');
            
            // Hàm chuyển trang
            function switchPage(pageId) {
                // Ẩn tất cả các trang
                pageContents.forEach(page => {
                    page.classList.remove('active');
                });
                
                // Hiển thị trang được chọn
                const activePage = document.getElementById(pageId);
                if (activePage) {
                    activePage.classList.add('active');
                }
                
                // Cập nhật trạng thái active trên sidebar
                sidebarItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-page') === pageId) {
                        item.classList.add('active');
                    }
                });
            }
            
            // Thêm sự kiện click cho từng mục sidebar
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    const pageId = this.getAttribute('data-page');
                    switchPage(pageId);
                });
            });
            
            // Xử lý nút hủy trên trang đăng xuất
            const cancelLogoutBtn = document.querySelector('#logout button:last-child');
            if (cancelLogoutBtn) {
                cancelLogoutBtn.addEventListener('click', function() {
                    switchPage('dashboard');
                });
            }
            
            // Giả lập thông báo mới sau 3 giây
            setTimeout(() => {
                const tourCard = document.querySelector('#dashboard .card:nth-child(2) .empty-state');
                if (tourCard) {
                    tourCard.innerHTML = `
                        <p><strong>Tour mới:</strong> Hà Nội - Ninh Bình</p>
                        <p>Khởi hành: 20/06/2023</p>
                        <button style="background-color: var(--primary-color); color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-top: 10px;">Xem chi tiết</button>
                    `;
                }
            }, 3000);
        });
    </script>