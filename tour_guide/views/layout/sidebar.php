<!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul>
                <li class="active" data-page="dashboard"><a href="#"><i class="fas fa-tachometer-alt"></i> Tổng Quan</a></li>
                <li data-page="my-tours"><a href="#"><i class="fas fa-route"></i> Tour Của Tôi</a></li>
                <li data-page="schedule"><a href="#"><i class="fas fa-calendar-alt"></i> Lịch Trình</a></li>
                <li data-page="issues"><a href="#"><i class="fas fa-exclamation-circle"></i> Sự Cố</a></li>
                <li data-page="customers"><a href="#"><i class="fas fa-users"></i> Khách Hàng</a></li>
                <li data-page="reports"><a href="#"><i class="fas fa-file-invoice-dollar"></i> Báo Cáo</a></li>
                <li data-page="settings"><a href="#"><i class="fas fa-cog"></i> Cài Đặt</a></li>
                <li data-page="logout"><a href="#"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Dashboard Page -->
            <div id="dashboard" class="page-content active">
                <h1 class="page-title">Dashboard - Tổng Quan Hệ Thống</h1>
                
                <!-- Dashboard Cards -->
                <div class="dashboard-cards">
                    <!-- Tour Đang Hoạt Động -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Tour Đang Hoạt Động</h2>
                            <div class="card-icon tour-icon">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <p><strong>Tour Sắp Khởi Hành</strong></p>
                            <p>Hà Nội - Sapa (2 ngày 1 đêm)</p>
                            <p>Khởi hành: 15/06/2023</p>
                            <div class="alert">
                                <i class="fas fa-exclamation-triangle"></i> Sự Cố Hôm Nay
                            </div>
                            <p><strong>Hướng Dẫn Viên Đang Làm Việc</strong></p>
                            <p>Nguyễn Văn A - 3 tour đang phụ trách</p>
                        </div>
                    </div>
                    
                    <!-- Tour Sắp Khởi Hành -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Tour Sắp Khởi Hành</h2>
                            <div class="card-icon guide-icon">
                                <i class="fas fa-plane-departure"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="empty-state">
                                Không có tour nào sắp khởi hành
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sự Cố Cần Xử Lý -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Sự Cố Cần Xử Lý</h2>
                            <div class="card-icon issue-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="empty-state">
                                Không có sự cố nào cần xử lý
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông Tin Cá Nhân -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Thông Tin Cá Nhân</h2>
                            <div class="card-icon" style="background-color: #9b59b6;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <p><strong>Họ tên:</strong> Nguyễn Văn A</p>
                            <p><strong>Số điện thoại:</strong> 0987 654 321</p>
                            <p><strong>Email:</strong> nguyenvana@lata.vn</p>
                            <p><strong>Ngôn ngữ:</strong> Tiếng Việt, Tiếng Anh</p>
                            <p><strong>Đánh giá:</strong> 4.8/5 (124 đánh giá)</p>
                        </div>
                    </div>
                    
                    <!-- Lịch Trình Tuần -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Lịch Trình Tuần</h2>
                            <div class="card-icon" style="background-color: #1abc9c;">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <p><strong>Thứ 2:</strong> Nghỉ</p>
                            <p><strong>Thứ 3:</strong> Tour Hà Nội - Hạ Long</p>
                            <p><strong>Thứ 4:</strong> Tour Hà Nội - Hạ Long</p>
                            <p><strong>Thứ 5:</strong> Tour Hà Nội - Sapa</p>
                            <p><strong>Thứ 6:</strong> Tour Hà Nội - Sapa</p>
                            <p><strong>Thứ 7:</strong> Nghỉ</p>
                            <p><strong>Chủ nhật:</strong> Chuẩn bị tour tuần sau</p>
                        </div>
                    </div>
                    
                    <!-- Thống Kê Hiệu Suất -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Thống Kê Hiệu Suất</h2>
                            <div class="card-icon" style="background-color: #e67e22;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <p><strong>Tour đã dẫn:</strong> 24 tour</p>
                            <p><strong>Tỷ lệ đánh giá tốt:</strong> 95%</p>
                            <p><strong>Khách hàng hài lòng:</strong> 98%</p>
                            <p><strong>Sự cố đã xử lý:</strong> 5</p>
                            <p><strong>Doanh thu tạo ra:</strong> 245,000,000 VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Tours Page -->
            <div id="my-tours" class="page-content">
                <h1 class="page-title">Tour Của Tôi</h1>
                
                <table>
                    <thead>
                        <tr>
                            <th>Mã Tour</th>
                            <th>Tên Tour</th>
                            <th>Ngày Khởi Hành</th>
                            <th>Số Khách</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TOUR-001</td>
                            <td>Hà Nội - Sapa (2N1Đ)</td>
                            <td>15/06/2023</td>
                            <td>12</td>
                            <td><span class="status-badge status-active">Đang hoạt động</span></td>
                            <td>
                                <button>Chi tiết</button>
                                <button>Sự cố</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TOUR-002</td>
                            <td>Hà Nội - Hạ Long (3N2Đ)</td>
                            <td>18/06/2023</td>
                            <td>8</td>
                            <td><span class="status-badge status-pending">Sắp khởi hành</span></td>
                            <td>
                                <button>Chi tiết</button>
                                <button>Sự cố</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TOUR-003</td>
                            <td>Hà Nội - Ninh Bình (1N)</td>
                            <td>22/06/2023</td>
                            <td>15</td>
                            <td><span class="status-badge status-pending">Sắp khởi hành</span></td>
                            <td>
                                <button>Chi tiết</button>
                                <button>Sự cố</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TOUR-004</td>
                            <td>Hà Nội - Mai Châu (2N1Đ)</td>
                            <td>10/06/2023</td>
                            <td>10</td>
                            <td><span class="status-badge status-completed">Đã kết thúc</span></td>
                            <td>
                                <button>Chi tiết</button>
                                <button>Đánh giá</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Schedule Page -->
            <div id="schedule" class="page-content">
                <h1 class="page-title">Lịch Trình</h1>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Lịch Trình Tháng 6/2023</h2>
                    </div>
                    <div class="card-content">
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                            <div style="text-align: center; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                                <div style="font-weight: bold;">T2</div>
                                <div>12</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Nghỉ</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #d4edda; border-radius: 5px;">
                                <div style="font-weight: bold;">T3</div>
                                <div>13</div>
                                <div style="font-size: 0.8rem; color: #155724;">Tour Hạ Long</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #d4edda; border-radius: 5px;">
                                <div style="font-weight: bold;">T4</div>
                                <div>14</div>
                                <div style="font-size: 0.8rem; color: #155724;">Tour Hạ Long</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #d4edda; border-radius: 5px;">
                                <div style="font-weight: bold;">T5</div>
                                <div>15</div>
                                <div style="font-size: 0.8rem; color: #155724;">Tour Sapa</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #d4edda; border-radius: 5px;">
                                <div style="font-weight: bold;">T6</div>
                                <div>16</div>
                                <div style="font-size: 0.8rem; color: #155724;">Tour Sapa</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                                <div style="font-weight: bold;">T7</div>
                                <div>17</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Nghỉ</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                                <div style="font-weight: bold;">CN</div>
                                <div>18</div>
                                <div style="font-size: 0.8rem; color: #6c757d;">Chuẩn bị</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h2 class="card-title">Chi Tiết Lịch Trình</h2>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="date-select">Chọn ngày:</label>
                            <input type="date" id="date-select" value="2023-06-15">
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <h3>Lịch trình ngày 15/06/2023</h3>
                            <ul style="list-style-type: none; padding-left: 0;">
                                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <strong>06:00</strong> - Đón khách tại điểm hẹn
                                </li>
                                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <strong>07:00</strong> - Khởi hành đi Sapa
                                </li>
                                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <strong>12:00</strong> - Ăn trưa tại Sapa
                                </li>
                                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <strong>14:00</strong> - Tham quan bản Cát Cát
                                </li>
                                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <strong>18:00</strong> - Ăn tối và nghỉ ngơi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Issues Page -->
            <div id="issues" class="page-content">
                <h1 class="page-title">Quản Lý Sự Cố</h1>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Báo Cáo Sự Cố Mới</h2>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="issue-tour">Tour liên quan:</label>
                            <select id="issue-tour">
                                <option value="">Chọn tour</option>
                                <option value="TOUR-001">TOUR-001 - Hà Nội - Sapa</option>
                                <option value="TOUR-002">TOUR-002 - Hà Nội - Hạ Long</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-type">Loại sự cố:</label>
                            <select id="issue-type">
                                <option value="">Chọn loại sự cố</option>
                                <option value="transport">Vấn đề phương tiện</option>
                                <option value="accommodation">Vấn đề chỗ ở</option>
                                <option value="customer">Vấn đề khách hàng</option>
                                <option value="weather">Vấn đề thời tiết</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-description">Mô tả sự cố:</label>
                            <textarea id="issue-description" rows="4" placeholder="Mô tả chi tiết sự cố..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="issue-priority">Mức độ ưu tiên:</label>
                            <select id="issue-priority">
                                <option value="low">Thấp</option>
                                <option value="medium">Trung bình</option>
                                <option value="high">Cao</option>
                                <option value="urgent">Khẩn cấp</option>
                            </select>
                        </div>
                        
                        <button>Gửi Báo Cáo</button>
                    </div>
                </div>
                
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h2 class="card-title">Sự Cố Đã Báo Cáo</h2>
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã Sự Cố</th>
                                    <th>Tour</th>
                                    <th>Loại Sự Cố</th>
                                    <th>Mức Độ</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Báo Cáo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ISSUE-001</td>
                                    <td>TOUR-001</td>
                                    <td>Vấn đề khách hàng</td>
                                    <td><span style="color: #e74c3c; font-weight: bold;">Cao</span></td>
                                    <td><span class="status-badge status-pending">Đang xử lý</span></td>
                                    <td>14/06/2023</td>
                                </tr>
                                <tr>
                                    <td>ISSUE-002</td>
                                    <td>TOUR-002</td>
                                    <td>Vấn đề phương tiện</td>
                                    <td><span style="color: #f39c12; font-weight: bold;">Trung bình</span></td>
                                    <td><span class="status-badge status-completed">Đã giải quyết</span></td>
                                    <td>10/06/2023</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customers Page -->
            <div id="customers" class="page-content">
                <h1 class="page-title">Quản Lý Khách Hàng</h1>
                
                <table>
                    <thead>
                        <tr>
                            <th>Mã KH</th>
                            <th>Họ Tên</th>
                            <th>Số Điện Thoại</th>
                            <th>Tour Đã Tham Gia</th>
                            <th>Đánh Giá</th>
                            <th>Ghi Chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CUS-001</td>
                            <td>Trần Thị B</td>
                            <td>0912 345 678</td>
                            <td>TOUR-001, TOUR-004</td>
                            <td>★★★★★</td>
                            <td>Khách hàng thân thiết</td>
                        </tr>
                        <tr>
                            <td>CUS-002</td>
                            <td>Lê Văn C</td>
                            <td>0988 777 666</td>
                            <td>TOUR-002</td>
                            <td>★★★★☆</td>
                            <td>Có yêu cầu đặc biệt về ăn uống</td>
                        </tr>
                        <tr>
                            <td>CUS-003</td>
                            <td>Phạm Thị D</td>
                            <td>0901 234 567</td>
                            <td>TOUR-003</td>
                            <td>★★★★★</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Reports Page -->
            <div id="reports" class="page-content">
                <h1 class="page-title">Báo Cáo & Thống Kê</h1>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Báo Cáo Doanh Thu</h2>
                        </div>
                        <div class="card-content">
                            <p><strong>Tháng 6/2023</strong></p>
                            <p>Tổng doanh thu: 85,000,000 VNĐ</p>
                            <p>Số tour đã dẫn: 4</p>
                            <p>Doanh thu trung bình/tour: 21,250,000 VNĐ</p>
                            <button style="margin-top: 10px;">Xem Chi Tiết</button>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Báo Cáo Đánh Giá</h2>
                        </div>
                        <div class="card-content">
                            <p><strong>Tháng 6/2023</strong></p>
                            <p>Tổng số đánh giá: 24</p>
                            <p>Đánh giá trung bình: 4.8/5</p>
                            <p>Phản hồi tích cực: 95%</p>
                            <button style="margin-top: 10px;">Xem Chi Tiết</button>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Báo Cáo Sự Cố</h2>
                        </div>
                        <div class="card-content">
                            <p><strong>Tháng 6/2023</strong></p>
                            <p>Tổng số sự cố: 2</p>
                            <p>Đã giải quyết: 1</p>
                            <p>Đang xử lý: 1</p>
                            <button style="margin-top: 10px;">Xem Chi Tiết</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Page -->
            <div id="settings" class="page-content">
                <h1 class="page-title">Cài Đặt</h1>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Thông Tin Cá Nhân</h2>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="fullname">Họ và tên:</label>
                            <input type="text" id="fullname" value="Nguyễn Văn A">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" id="phone" value="0987 654 321">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" value="nguyenvana@lata.vn">
                        </div>
                        
                        <div class="form-group">
                            <label for="languages">Ngôn ngữ:</label>
                            <input type="text" id="languages" value="Tiếng Việt, Tiếng Anh">
                        </div>
                        
                        <button>Lưu Thay Đổi</button>
                    </div>
                </div>
                
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h2 class="card-title">Đổi Mật Khẩu</h2>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="current-password">Mật khẩu hiện tại:</label>
                            <input type="password" id="current-password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new-password">Mật khẩu mới:</label>
                            <input type="password" id="new-password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm-password">Xác nhận mật khẩu mới:</label>
                            <input type="password" id="confirm-password">
                        </div>
                        
                        <button>Đổi Mật Khẩu</button>
                    </div>
                </div>
            </div>

            <!-- Logout Page -->
            <div id="logout" class="page-content">
                <div class="card" style="max-width: 500px; margin: 50px auto; text-align: center;">
                    <div class="card-header">
                        <h2 class="card-title">Đăng Xuất</h2>
                    </div>
                    <div class="card-content">
                        <p>Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?</p>
                        <div style="margin-top: 20px;">
                            <button style="background-color: #e74c3c; margin-right: 10px;">Đăng Xuất</button>
                            <button style="background-color: #95a5a6;">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>