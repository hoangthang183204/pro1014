<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

// Lấy dữ liệu an toàn, đặt giá trị mặc định
$tour = $data['tour_info'] ?? [];
$lichTrinh = $data['lich_trinh_chi_tiet'] ?? [];
$danhSachKhach = $data['danh_sach_khach'] ?? [];
$checklist = $data['checklist'] ?? [];
$phanCongKhac = $data['phan_cong_khac'] ?? [];

// Đặt giá trị mặc định cho mảng tour, tránh key chưa được định nghĩa
$tour = array_merge([
    'trang_thai_lich' => 'chưa xác định',
    'trang_thai_xac_nhan' => 'chưa xác nhận',
    'ten_tour' => 'Không có tên',
    'ngay_bat_dau' => date('Y-m-d'),
    'ngay_ket_thuc' => date('Y-m-d'),
    'ma_tour' => '',
    'ten_danh_muc' => '',
    'gio_tap_trung' => '',
    'diem_tap_trung' => '',
    'ten_huong_dan_vien' => '',
    'sdt_hdv' => '',
    'so_cho_toi_da' => 0,
    'so_cho_con_lai' => 0,
    'lich_khoi_hanh_id' => 0,
    'ghi_chu_van_hanh' => '',
    'ghi_chu_phan_cong' => '',
    'quy_dinh_huy_doi' => '',
    'luu_y_suc_khoe' => '',
    'luu_y_hanh_ly' => '',
    'luu_y_khac' => ''
], $tour);
?>

<main class="main-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh">Lịch Trình</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($tour['ten_tour']) ?></li>
            </ol>
        </nav>

        <!-- Tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1"><?= htmlspecialchars($tour['ten_tour']) ?></h1>
                <div class="d-flex align-items-center">
                    <span class="badge badge-primary mr-2" style="color: green;">
                        <?= isset($tour['trang_thai_lich']) && !empty($tour['trang_thai_lich']) ? strtoupper($tour['trang_thai_lich']) : '' ?>
                    </span>
                    <span class="badge badge-<?= $tour['trang_thai_xac_nhan'] == 'đã xác nhận' ? 'success' : 'warning' ?>" style="color: green;">
                        <?= $tour['trang_thai_xac_nhan'] ?>
                    </span>
                    <small class="text-muted ml-3">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?> - <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?>
                    </small>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="<?= BASE_URL_GUIDE ?>?act=nhat_ky_add&lich_id=<?= $tour['lich_khoi_hanh_id'] ?>" 
                   class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i> Thêm nhật ký
                </a>
                <a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Cột trái: Thông tin chính -->
            <div class="col-lg-8">
                <!-- Thông tin cơ bản -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Thông Tin Tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-barcode mr-2"></i> Mã tour:</strong> <?= $tour['ma_tour'] ?></p>
                                <p><strong><i class="fas fa-tag mr-2"></i> Danh mục:</strong> <?= $tour['ten_danh_muc'] ?></p>
                                <p><strong><i class="fas fa-clock mr-2"></i> Giờ tập trung:</strong> 
                                    <?= !empty($tour['gio_tap_trung']) ? date('H:i', strtotime($tour['gio_tap_trung'])) : 'Chưa cập nhật' ?>
                                </p>
                                <p><strong><i class="fas fa-map-marker-alt mr-2"></i> Địa điểm tập trung:</strong> 
                                    <?= !empty($tour['diem_tap_trung']) ? htmlspecialchars($tour['diem_tap_trung']) : 'Chưa cập nhật' ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-user mr-2"></i> HDV phụ trách:</strong> <?= $tour['ten_huong_dan_vien'] ?></p>
                                <p><strong><i class="fas fa-phone mr-2"></i> Số điện thoại HDV:</strong> <?= $tour['sdt_hdv'] ?></p>
                                <p><strong><i class="fas fa-users mr-2"></i> Số khách tối đa:</strong> <?= $tour['so_cho_toi_da'] ?></p>
                                <p><strong><i class="fas fa-users mr-2"></i> Số chỗ còn lại:</strong> <?= $tour['so_cho_con_lai'] ?></p>
                            </div>
                        </div>
                        
                        <?php if (!empty($tour['ghi_chu_van_hanh'])): ?>
                            <div class="alert alert-warning mt-3">
                                <h6><i class="fas fa-exclamation-triangle mr-2"></i> Ghi chú vận hành:</h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($tour['ghi_chu_van_hanh'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($tour['ghi_chu_phan_cong'])): ?>
                            <div class="alert alert-info mt-3">
                                <h6><i class="fas fa-sticky-note mr-2"></i> Ghi chú phân công:</h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($tour['ghi_chu_phan_cong'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Lịch trình chi tiết từng ngày -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-day mr-2"></i> Lịch Trình Chi Tiết</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($lichTrinh)): ?>
                            <div class="alert alert-info">Chưa có lịch trình chi tiết</div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($lichTrinh as $index => $ngay): ?>
                                    <div class="timeline-item mb-4">
                                        <div class="timeline-header d-flex align-items-center">
                                            <div class="timeline-day bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 100px; height: 50px;">
                                                <strong>Ngày <?= isset($ngay['so_ngay']) ? $ngay['so_ngay'] : ($index + 1) ?></strong>
                                            </div>
                                            <div>
                                                <h5 class="mb-1"><?= isset($ngay['tieu_de']) ? htmlspecialchars($ngay['tieu_de']) : 'Không có tiêu đề' ?></h5>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'] . ' + ' . ($index) . ' days')) ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-content mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-box mb-3">
                                                        <h6><i class="fas fa-hiking mr-2"></i> Hoạt động:</h6>
                                                        <p class="mb-0"><?= isset($ngay['mo_ta_hoat_dong']) ? nl2br(htmlspecialchars($ngay['mo_ta_hoat_dong'])) : 'Chưa cập nhật' ?></p>
                                                    </div>
                                                    
                                                    <div class="info-box mb-3">
                                                        <h6><i class="fas fa-utensils mr-2"></i> Bữa ăn:</h6>
                                                        <p class="mb-0"><?= isset($ngay['bua_an']) ? nl2br(htmlspecialchars($ngay['bua_an'])) : 'Chưa cập nhật' ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="info-box mb-3">
                                                        <h6><i class="fas fa-hotel mr-2"></i> Chỗ ở:</h6>
                                                        <p class="mb-0"><?= isset($ngay['cho_o']) ? nl2br(htmlspecialchars($ngay['cho_o'])) : 'Chưa cập nhật' ?></p>
                                                    </div>
                                                    
                                                    <div class="info-box mb-3">
                                                        <h6><i class="fas fa-bus mr-2"></i> Phương tiện:</h6>
                                                        <p class="mb-0"><?= isset($ngay['phuong_tien']) ? nl2br(htmlspecialchars($ngay['phuong_tien'])) : 'Chưa cập nhật' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($ngay['ghi_chu_hdv'])): ?>
                                                <div class="alert alert-warning">
                                                    <h6><i class="fas fa-clipboard-check mr-2"></i> Ghi chú cho HDV:</h6>
                                                    <p class="mb-0"><?= nl2br(htmlspecialchars($ngay['ghi_chu_hdv'])) ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($index < count($lichTrinh) - 1): ?>
                                        <hr class="my-4">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Danh sách khách hàng -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-users mr-2"></i> Danh Sách Khách Hàng Đã Đặt Tour</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($danhSachKhach)): ?>
                            <div class="alert alert-info">Chưa có khách hàng nào đặt tour</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ tên</th>
                                            <th>Loại</th>
                                            <th>SĐT</th>
                                            <th>CCCD</th>
                                            <th>Ngày sinh</th>
                                            <th>Giới tính</th>
                                            <th>Email</th>
                                            <th>Mã đặt tour</th>
                                            <th>Khách sạn(Phòng)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $stt = 1;
                                        $tongKhach = count($danhSachKhach);
                                        foreach ($danhSachKhach as $khach): 
                                        ?>
                                            <tr>
                                                <td><?= $stt ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($khach['ho_ten']) ?></strong>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if (isset($khach['loai_khach'])) {
                                                        if ($khach['loai_khach'] == 'người đặt') {
                                                            echo '<span class="badge badge-primary" style="color: green">Người đặt</span>';
                                                        } else {
                                                            echo '<span class="badge badge-secondary" style="color: green">Khách đi cùng</span>';
                                                        }
                                                    } else {
                                                        echo '<span class="badge badge-warning">Chưa xác định</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($khach['so_dien_thoai']) ?></td>
                                                <td><?= htmlspecialchars($khach['cccd'] ?? 'Chưa cập nhật') ?></td>
                                                <td>
                                                    <?php if (!empty($khach['ngay_sinh'])): ?>
                                                        <?= date('d/m/Y', strtotime($khach['ngay_sinh'])) ?>
                                                    <?php else: ?>
                                                        Chưa cập nhật
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $gioiTinh = $khach['gioi_tinh'] ?? '';
                                                    echo match($gioiTinh) {
                                                        'nam' => 'Nam',
                                                        'nữ' => 'Nữ',
                                                        default => 'Chưa xác định'
                                                    };
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($khach['email'] ?? '') ?></td>
                                                <td>
                                                    <?php if (!empty($khach['ma_dat_tour'])): ?>
                                                        <span class="badge badge-secondary" style="color: #495057;">
                                                            <?= $khach['ma_dat_tour'] ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa có mã</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($khach['ten_khach_san'])): ?>
                                                        <small style="color: #495057;">
                                                            <?= htmlspecialchars($khach['ten_khach_san']) ?><br>
                                                            <?= $khach['so_phong'] ?? '' ?> (<?= $khach['loai_phong'] ?? '' ?>)
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa phân phòng</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php $stt++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Thống kê -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white text-center">
                                        <div class="card-body p-3">
                                            <h4 class="mb-0"><?= $tongKhach ?></h4>
                                            <small>Tổng số khách</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white text-center">
                                        <div class="card-body p-3">
                                            <h4 class="mb-0"><?= count(array_filter($danhSachKhach, fn($k) => !empty($k['cccd']))) ?></h4>
                                            <small>Đã có CCCD</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white text-center">
                                        <div class="card-body p-3">
                                            <h4 class="mb-0"><?= count(array_filter($danhSachKhach, fn($k) => !empty($k['ten_khach_san']))) ?></h4>
                                            <small>Đã phân phòng</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white text-center">
                                        <div class="card-body p-3">
                                            <h4 class="mb-0"><?= count(array_unique(array_column($danhSachKhach, 'ma_dat_tour'))) ?></h4>
                                            <small>Số phiếu đặt</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Checklist & Thông tin khác -->
            <div class="col-lg-4">
                <!-- Checklist công việc -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i> Checklist Công Việc</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($checklist)): ?>
                            <div class="alert alert-info">Chưa có checklist công việc</div>
                        <?php else: ?>
                            <div class="checklist-items">
                                <?php foreach ($checklist as $item): ?>
                                    <div class="checklist-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-checkbox mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input checklist-checkbox" 
                                                               id="checklist_<?= $item['id'] ?>"
                                                               data-id="<?= $item['id'] ?>"
                                                               data-lich-id="<?= $tour['lich_khoi_hanh_id'] ?>"
                                                               <?= isset($item['hoan_thanh']) && $item['hoan_thanh'] ? 'checked' : '' ?>>
                                                        <label class="custom-control-label" for="checklist_<?= $item['id'] ?>"></label>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 <?= isset($item['hoan_thanh']) && $item['hoan_thanh'] ? 'text-success text-decoration-line-through' : '' ?>">
                                                            <?= isset($item['cong_viec']) ? htmlspecialchars($item['cong_viec']) : 'Không có tên công việc' ?>
                                                        </h6>
                                                        <?php if (isset($item['hoan_thanh']) && $item['hoan_thanh'] && !empty($item['thoi_gian_hoan_thanh'])): ?>
                                                            <small class="text-muted">
                                                                <i class="fas fa-check-circle text-success mr-1"></i>
                                                                Hoàn thành: <?= date('d/m/Y H:i', strtotime($item['thoi_gian_hoan_thanh'])) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-warning" onclick="resetAllChecklists()">
                                    <i class="fas fa-redo mr-1"></i> Đặt lại tất cả
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Chính sách tour -->
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-file-contract mr-2"></i> Chính Sách Tour</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($tour['quy_dinh_huy_doi'])): ?>
                            <div class="alert alert-info">Không có chính sách</div>
                        <?php else: ?>
                            <div class="chinh-sach-item">
                                <h6>Quy định hủy/đổi:</h6>
                                <p class="small"><?= nl2br(htmlspecialchars($tour['quy_dinh_huy_doi'])) ?></p>
                            </div>
                            
                            <?php if (!empty($tour['luu_y_suc_khoe'])): ?>
                                <div class="chinh-sach-item mt-3">
                                    <h6>Lưu ý sức khỏe:</h6>
                                    <p class="small"><?= nl2br(htmlspecialchars($tour['luu_y_suc_khoe'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($tour['luu_y_hanh_ly'])): ?>
                                <div class="chinh-sach-item mt-3">
                                    <h6>Lưu ý hành lý:</h6>
                                    <p class="small"><?= nl2br(htmlspecialchars($tour['luu_y_hanh_ly'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($tour['luu_y_khac'])): ?>
                                <div class="chinh-sach-item mt-3">
                                    <h6>Lưu ý khác:</h6>
                                    <p class="small"><?= nl2br(htmlspecialchars($tour['luu_y_khac'])) ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.timeline-day {
    flex-shrink: 0;
}

.info-box {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.info-box h6 {
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.phan-cong-item {
    background: #f8f9fa;
}

.chinh-sach-item {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 1rem;
}

.chinh-sach-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.checklist-item:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>

<script>
// Xử lý checkbox checklist
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.checklist-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checklistId = this.dataset.id;
            const isChecked = this.checked ? 1 : 0;
            
            console.log('Sending AJAX:', {
                checklistId: checklistId,
                isChecked: isChecked
            });
            
            // Hiển thị loading
            const originalHTML = this.parentElement.innerHTML;
            this.parentElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Gửi yêu cầu AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= BASE_URL_GUIDE ?>?act=update-checklist-guide');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                console.log('AJAX Response:', xhr.responseText);
                
                // Khôi phục checkbox
                checkbox.parentElement.innerHTML = originalHTML;
                checkbox.checked = isChecked;
                
                // Lắng nghe lại sự kiện
                const newCheckbox = document.querySelector(`[data-id="${checklistId}"]`);
                if (newCheckbox) {
                    newCheckbox.addEventListener('change', checkboxHandler);
                }
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Cập nhật giao diện
                            const item = document.querySelector(`.checklist-item input[data-id="${checklistId}"]`).closest('.checklist-item');
                            const text = item.querySelector('h6');
                            
                            if (isChecked) {
                                text.classList.add('text-success', 'text-decoration-line-through');
                                
                                // Thêm thời gian hoàn thành
                                let timeSpan = item.querySelector('.completion-time');
                                if (!timeSpan) {
                                    timeSpan = document.createElement('small');
                                    timeSpan.className = 'text-muted completion-time d-block mt-1';
                                    timeSpan.innerHTML = `<i class="fas fa-check-circle text-success mr-1"></i> Hoàn thành: ${new Date().toLocaleString('vi-VN')}`;
                                    text.parentElement.appendChild(timeSpan);
                                }
                            } else {
                                text.classList.remove('text-success', 'text-decoration-line-through');
                                
                                // Xóa thời gian hoàn thành
                                const timeSpan = item.querySelector('.completion-time');
                                if (timeSpan) {
                                    timeSpan.remove();
                                }
                            }
                            
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                            // Rollback trạng thái checkbox
                            checkbox.checked = !isChecked;
                        }
                    } catch (e) {
                        console.error('JSON Parse Error:', e);
                        showToast('error', 'Lỗi xử lý dữ liệu');
                        checkbox.checked = !isChecked;
                    }
                } else {
                    showToast('error', 'Lỗi kết nối: ' + xhr.status);
                    checkbox.checked = !isChecked;
                }
            };
            
            xhr.onerror = function() {
                console.error('AJAX Error');
                // Khôi phục checkbox
                checkbox.parentElement.innerHTML = originalHTML;
                checkbox.checked = !isChecked;
                showToast('error', 'Có lỗi xảy ra khi kết nối server');
            };
            
            xhr.send(`id=${checklistId}&hoan_thanh=${isChecked}`);
        });
    });
});

// Hàm xử lý checkbox
function checkboxHandler() {
    const checklistId = this.dataset.id;
    const isChecked = this.checked ? 1 : 0;
    
    // Gửi AJAX request
    $.ajax({
        url: '<?= BASE_URL_GUIDE ?>?act=update-checklist-guide',
        type: 'POST',
        data: {
            id: checklistId,
            hoan_thanh: isChecked
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                // Cập nhật giao diện
                const item = $(`input[data-id="${checklistId}"]`).closest('.checklist-item');
                const text = item.find('h6');
                
                if (isChecked) {
                    text.addClass('text-success text-decoration-line-through');
                    
                    // Thêm thời gian hoàn thành
                    if (!item.find('.completion-time').length) {
                        const timeHtml = `<small class="text-muted completion-time d-block mt-1">
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                            Hoàn thành: ${new Date().toLocaleString('vi-VN')}
                                          </small>`;
                        text.after(timeHtml);
                    }
                } else {
                    text.removeClass('text-success text-decoration-line-through');
                    item.find('.completion-time').remove();
                }
                
                showToast('success', result.message);
            } else {
                showToast('error', result.message);
                // Rollback checkbox state
                $(`input[data-id="${checklistId}"]`).prop('checked', !isChecked);
            }
        },
        error: function() {
            showToast('error', 'Lỗi kết nối server');
            $(`input[data-id="${checklistId}"]`).prop('checked', !isChecked);
        }
    });
}

// Đặt lại tất cả checklist
function resetAllChecklists() {
    if (!confirm('Bạn có chắc chắn muốn đặt lại tất cả checklist?')) {
        return;
    }
    
    const checkboxes = document.querySelectorAll('.checklist-checkbox');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event('change'));
        }
    });
}

// Hiển thị thông báo
function showToast(type, message) {
    // Kiểm tra xem đã có toast container chưa
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1050';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Thêm jQuery nếu chưa có
if (typeof jQuery == 'undefined') {
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    document.head.appendChild(script);
}
</script>

<?php
include __DIR__ . '/../layout/footer.php';
?>