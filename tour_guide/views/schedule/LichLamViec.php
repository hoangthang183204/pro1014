<?php
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$guideInfo = $data['guide_info'] ?? [];
$lichLamViec = $data['lich_lam_viec'] ?? [];
$lichTrinhTours = $data['lich_trinh_tours'] ?? [];
$currentMonth = $data['current_month'] ?? date('m');
$currentYear = $data['current_year'] ?? date('Y');

// Tính toán ngày đầu tháng và cuối tháng một cách an toàn
try {
    // Đảm bảo tháng có định dạng đúng (2 chữ số)
    $currentMonthPadded = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
    $currentMonthStart = new DateTime("{$currentYear}-{$currentMonthPadded}-01");
    $currentMonthEnd = new DateTime($currentMonthStart->format('Y-m-t')); // 't' trả về ngày cuối tháng
} catch (Exception $e) {
    // Fallback về tháng hiện tại nếu có lỗi
    $currentMonthStart = new DateTime();
    $currentMonthStart->modify('first day of this month');
    $currentMonthEnd = new DateTime($currentMonthStart->format('Y-m-t'));
}

// Hàm helper để tạo tiêu đề sự kiện
function getEventTitle($loaiLich, $ghiChu) {
    $titles = [
        'đã phân công' => 'Có tour',
        'bận' => 'Bận',
        'nghỉ' => 'Nghỉ',
        'có thể làm' => 'Có thể làm'
    ];
    
    $title = $titles[$loaiLich] ?? $loaiLich;
    if ($ghiChu && strlen($ghiChu) > 0) {
        $title .= ': ' . $ghiChu;
    }
    
    return $title;
}

// Hàm helper để tạo lớp CSS cho sự kiện
function getEventClass($type) {
    switch($type) {
        case 'tour': return 'tour-event';
        case 'đã phân công': return 'tour-event';
        case 'bận': return 'busy-event';
        case 'nghỉ': return 'off-event';
        default: return 'busy-event';
    }
}

// Tạo mảng dữ liệu sự kiện cho dễ truy cập
$eventsByDate = [];

// Thêm lịch làm việc vào mảng sự kiện
foreach ($lichLamViec as $item) {
    $date = $item['ngay'];
    if (!isset($eventsByDate[$date])) {
        $eventsByDate[$date] = [];
    }
    $eventsByDate[$date][] = [
        'type' => $item['loai_lich'],
        'title' => getEventTitle($item['loai_lich'], $item['ghi_chu'] ?? ''),
        'ghi_chu' => $item['ghi_chu'] ?? ''
    ];
}

// Thêm tour vào mảng sự kiện (ưu tiên hiển thị tour)
foreach ($lichTrinhTours as $tour) {
    try {
        $tourStart = new DateTime($tour['ngay_bat_dau']);
        $tourEnd = new DateTime($tour['ngay_ket_thuc']);
        
        // Tạo khoảng ngày của tour
        $period = new DatePeriod(
            $tourStart,
            new DateInterval('P1D'),
            $tourEnd->modify('+1 day')
        );
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            // Nếu là ngày bắt đầu tour, hiển thị tên tour
            if ($date->format('Y-m-d') == $tourStart->format('Y-m-d')) {
                if (!isset($eventsByDate[$dateStr])) {
                    $eventsByDate[$dateStr] = [];
                }
                // Chỉ thêm tour nếu chưa có sự kiện nào (ưu tiên tour)
                $hasTour = false;
                foreach ($eventsByDate[$dateStr] as $event) {
                    if ($event['type'] === 'tour' || $event['type'] === 'đã phân công') {
                        $hasTour = true;
                        break;
                    }
                }
                if (!$hasTour) {
                    array_unshift($eventsByDate[$dateStr], [
                        'type' => 'tour',
                        'title' => $tour['ten_tour'],
                        'tour_data' => $tour
                    ]);
                }
            }
        }
    } catch (Exception $e) {
        // Bỏ qua tour có ngày không hợp lệ
        continue;
    }
}

// Tạo lịch bằng PHP
function getCalendarDays($year, $month, $eventsByDate) {
    // Ngày đầu tiên của tháng
    $firstDay = new DateTime("$year-$month-01");
    // Ngày cuối cùng của tháng
    $lastDay = new DateTime($firstDay->format('Y-m-t'));
    
    // Ngày đầu tiên trong tuần (0 = Chủ nhật, 1 = Thứ 2)
    $startDay = $firstDay->format('w');
    // Chuyển từ Chủ nhật = 0 sang Thứ 2 = 0
    $startDay = $startDay == 0 ? 6 : $startDay - 1;
    
    // Tổng số ngày trong tháng
    $daysInMonth = $lastDay->format('j');
    
    // Tính toán số tuần
    $totalCells = $startDay + $daysInMonth;
    $weeks = ceil($totalCells / 7);
    
    $calendar = [];
    $dayCounter = 1;
    $currentDate = new DateTime();
    
    for ($week = 0; $week < $weeks; $week++) {
        $weekDays = [];
        
        for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
            $cellIndex = ($week * 7) + $dayOfWeek;
            
            if ($cellIndex < $startDay || $dayCounter > $daysInMonth) {
                // Ô trống (tháng trước hoặc tháng sau)
                $weekDays[] = [
                    'type' => 'empty',
                    'day' => null,
                    'date' => null,
                    'events' => [],
                    'is_today' => false
                ];
            } else {
                // Ngày trong tháng
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
                $isToday = ($currentDate->format('Y-m-d') == $dateStr);
                
                $weekDays[] = [
                    'type' => 'current',
                    'day' => $dayCounter,
                    'date' => $dateStr,
                    'events' => $eventsByDate[$dateStr] ?? [],
                    'is_today' => $isToday
                ];
                
                $dayCounter++;
            }
        }
        
        $calendar[] = $weekDays;
    }
    
    return $calendar;
}

// Tạo lịch
$calendar = getCalendarDays($currentYear, $currentMonth, $eventsByDate);
?>

<main class="main-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL_GUIDE ?>?act=lich-trinh">Lịch Trình</a></li>
                <li class="breadcrumb-item active">Lịch Làm Việc</li>
            </ol>
        </nav>

        <!-- Tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">📅 Lịch Làm Việc</h1>
                <div class="d-flex align-items-center">
                    <span class="text-muted">
                        <i class="fas fa-user mr-1"></i> <?= htmlspecialchars($guideInfo['ho_ten'] ?? '') ?>
                    </span>
                    <span class="text-muted ml-3">
                        <i class="fas fa-calendar-alt mr-1"></i> Tháng <?= $currentMonth ?>/<?= $currentYear ?>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons">
                <!-- Chọn tháng/năm -->
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="month" 
                           class="form-control" 
                           id="monthPicker"
                           value="<?= $currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" onclick="changeMonth()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Lịch làm việc tháng -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar mr-2"></i> Lịch Làm Việc Tháng</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="width: 14%">Thứ 2</th>
                                        <th style="width: 14%">Thứ 3</th>
                                        <th style="width: 14%">Thứ 4</th>
                                        <th style="width: 14%">Thứ 5</th>
                                        <th style="width: 14%">Thứ 6</th>
                                        <th style="width: 14%">Thứ 7</th>
                                        <th style="width: 14%">Chủ nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($calendar as $week): ?>
                                    <tr>
                                        <?php foreach ($week as $day): ?>
                                        <td class="calendar-day <?= $day['type'] == 'empty' ? 'other-month empty-day' : '' ?> <?= $day['is_today'] ? 'today' : '' ?>" 
                                            <?php if ($day['type'] == 'current'): ?>
                                            onclick="showDayDetail('<?= $day['date'] ?>')"
                                            style="cursor: pointer;"
                                            <?php endif; ?>
                                            >
                                            <?php if ($day['type'] == 'current'): ?>
                                                <div class="calendar-date"><?= $day['day'] ?></div>
                                                <?php foreach ($day['events'] as $event): ?>
                                                    <span class="<?= getEventClass($event['type']) ?>" 
                                                          title="<?= htmlspecialchars($event['title']) ?>">
                                                        <?= htmlspecialchars(mb_substr($event['title'], 0, 15)) ?>
                                                        <?= mb_strlen($event['title']) > 15 ? '...' : '' ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách tour trong tháng -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i> Tour Trong Tháng</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($lichTrinhTours)): ?>
                            <div class="alert alert-info">Không có tour nào trong tháng này</div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($lichTrinhTours as $tour): ?>
                                    <?php 
                                    try {
                                        $tourStart = new DateTime($tour['ngay_bat_dau']);
                                        $tourEnd = new DateTime($tour['ngay_ket_thuc']);
                                        
                                        // Kiểm tra xem tour có trong tháng này không
                                        if ($tourStart <= $currentMonthEnd && $tourEnd >= $currentMonthStart):
                                    ?>
                                        <div class="list-group-item list-group-item-action mb-2">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($tour['ten_tour']) ?></h6>
                                                <small class="text-muted"><?= $tour['ma_tour'] ?></small>
                                            </div>
                                            <p class="mb-1 small">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                <?= date('d/m/Y', strtotime($tour['ngay_bat_dau'])) ?> - <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?>
                                            </p>
                                            <small>
                                                <span class="badge badge-<?= 
                                                    $tour['trang_thai_lich'] == 'đang diễn ra' ? 'success' : 
                                                    ($tour['trang_thai_lich'] == 'đã hoàn thành' ? 'secondary' : 'primary')
                                                ?>">
                                                    <?= $tour['trang_thai_lich'] ?>
                                                </span>
                                                <?php if ($tour['ghi_chu_phan_cong']): ?>
                                                    <span class="badge badge-info ml-1">Có ghi chú</span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php 
                                        endif;
                                    } catch (Exception $e) {
                                        // Bỏ qua tour có ngày không hợp lệ
                                        continue;
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Thống kê -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i> Thống Kê Tháng</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $countTour = 0;
                        $countBusy = 0;
                        $countOff = 0;
                        
                        foreach ($lichLamViec as $item) {
                            switch ($item['loai_lich']) {
                                case 'đã phân công':
                                    $countTour++;
                                    break;
                                case 'bận':
                                    $countBusy++;
                                    break;
                                case 'nghỉ':
                                    $countOff++;
                                    break;
                            }
                        }
                        
                        // Đếm số ngày có tour từ mảng eventsByDate
                        $countTourDays = 0;
                        foreach ($eventsByDate as $date => $events) {
                            foreach ($events as $event) {
                                if ($event['type'] === 'tour') {
                                    $countTourDays++;
                                    break; // Mỗi ngày chỉ đếm 1 lần
                                }
                            }
                        }
                        ?>
                        <div class="text-center">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="bg-primary text-white rounded p-3">
                                        <h4 class="mb-0"><?= $countTourDays ?></h4>
                                        <small>Ngày có tour</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-warning text-white rounded p-3">
                                        <h4 class="mb-0"><?= $countBusy ?></h4>
                                        <small>Ngày bận</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="bg-success text-white rounded p-3">
                                        <h4 class="mb-0"><?= $countOff ?></h4>
                                        <small>Ngày nghỉ</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-secondary text-white rounded p-3">
                                        <h4 class="mb-0"><?= count($lichTrinhTours) ?></h4>
                                        <small>Tổng tour</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.calendar-day {
    min-height: 100px;
    padding: 5px;
    position: relative;
    transition: all 0.3s;
}

.calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-date {
    font-size: 12px;
    font-weight: bold;
    color: #6c757d;
    margin-bottom: 5px;
}

.tour-event {
    background-color: #28a745;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 11px;
    margin-bottom: 2px;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.busy-event {
    background-color: #ffc107;
    color: black;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 11px;
    margin-bottom: 2px;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.off-event {
    background-color: #17a2b8;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 11px;
    margin-bottom: 2px;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.empty-day {
    background-color: #f8f9fa;
}

.other-month {
    background-color: #f8f9fa;
    color: #adb5bd;
}

.today {
    background-color: #e7f5ff;
    border: 2px solid #007bff;
}
</style>

<script>
// JavaScript đơn giản
function changeMonth() {
    const monthPicker = document.getElementById('monthPicker');
    const [year, month] = monthPicker.value.split('-');
    
    window.location.href = `<?= BASE_URL_GUIDE ?>?act=lich-lam-viec&month=${month}&year=${year}`;
}

function showDayDetail(dateStr) {
    // Tạo modal đơn giản
    const date = new Date(dateStr);
    const formattedDate = date.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Tạo nội dung modal từ PHP data
    let content = `<h5>${formattedDate}</h5>`;
    content += '<p class="text-muted">Thông tin chi tiết về ngày này</p>';
    content += '<p>Bạn có thể xem thông tin chi tiết trong phần "Lịch Trình"</p>';
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('dayDetailModal'));
    document.getElementById('modalTitle').innerHTML = formattedDate;
    document.getElementById('modalBody').innerHTML = content;
    modal.show();
}
</script>

<!-- Modal chi tiết ngày -->
<div class="modal fade" id="dayDetailModal" tabindex="-1" aria-labelledby="dayDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Chi tiết ngày</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Nội dung sẽ được thêm bằng JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../layout/footer.php';
?>