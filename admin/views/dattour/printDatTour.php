<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Đặt Tour - <?php echo $dat_tour['ma_dat_tour']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-container, .invoice-container * {
                visibility: visible;
            }
            .invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            .table {
                border-collapse: collapse !important;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6 !important;
            }
        }

        /* Screen Styles */
        @media screen {
            .invoice-container {
                max-width: 210mm;
                margin: 20px auto;
                padding: 20px;
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                border: 1px solid #ddd;
            }
            body {
                background: #f8f9fa;
            }
        }

        .invoice-header {
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info h3 {
            color: #2c5aa0;
            margin-bottom: 5px;
        }

        .invoice-title {
            color: #dc3545;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section-title {
            color: #2c5aa0;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .table th {
            background-color: #f8f9fa !important;
            font-weight: 600;
        }

        .total-section {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 5px;
        }

        .text-red {
            color: #dc3545;
        }

        .text-green {
            color: #198754;
        }

        .text-blue {
            color: #2c5aa0;
        }

        .bank-info {
            background: #e7f3ff;
            border-left: 4px solid #2c5aa0;
            padding: 15px;
        }

        .signature-section {
            margin-top: 100px;
        }

        .footer-note {
            border-top: 2px dashed #ddd;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }

        /* Đảm bảo kích thước phù hợp khi in */
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- Nút điều khiển (chỉ hiện trên màn hình) -->
    <div class="container-fluid no-print py-3 bg-light border-bottom">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt text-primary"></i>
                        Xem trước hóa đơn
                    </h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="fas fa-print me-1"></i> In hóa đơn
                        </button>
                        <a href="index.php?act=dat-tour" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nội dung hóa đơn -->
    <div class="invoice-container">
        <!-- Header hóa đơn -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-6 company-info">
                    <h3 class="mb-1">CÔNG TY DU LỊCH LATA</h3>
                    <p class="mb-1"><strong>Địa chỉ:</strong> 123 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội</p>
                    <p class="mb-1"><strong>Hotline:</strong> 1900 1008 - <strong>Email:</strong> LATA@viettravel.com</p>
                    <p class="mb-0"><strong>MST:</strong> 0101234567</p>
                </div>
                <div class="col-6 text-end">
                    <h3 class="invoice-title mb-2">HÓA ĐƠN THANH TOÁN</h3>
                    <p class="mb-1"><strong>Mã hóa đơn:</strong> <?php echo $hoa_don['ma_hoa_don'] ?? 'HD' . date('YmdHis'); ?></p>
                    <p class="mb-1"><strong>Mã booking:</strong> <?php echo $dat_tour['ma_dat_tour']; ?></p>
                    <p class="mb-1"><strong>Ngày lập:</strong> <?php echo date('d/m/Y'); ?></p>
                    <p class="mb-0"><strong>Hạn thanh toán:</strong> <?php echo date('d/m/Y', strtotime('+2 days')); ?></p>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">
                    <i class="fas fa-user me-2"></i>THÔNG TIN KHÁCH HÀNG
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="35%"><strong>Họ tên:</strong></td>
                                <td><?php echo isset($khach_hang['ho_ten']) ? $khach_hang['ho_ten'] : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Số điện thoại:</strong></td>
                                <td><?php echo isset($khach_hang['so_dien_thoai']) ? $khach_hang['so_dien_thoai'] : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo isset($khach_hang['email']) ? $khach_hang['email'] : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="35%"><strong>CCCD/CMND:</strong></td>
                                <td><?php echo isset($khach_hang['cccd']) ? $khach_hang['cccd'] : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td><?php echo isset($khach_hang['dia_chi']) ? $khach_hang['dia_chi'] : 'N/A'; ?></td>
                            </tr>
                            <?php if (isset($dat_tour['loai_khach']) && $dat_tour['loai_khach'] == 'doan'): ?>
                            <tr>
                                <td><strong>Tên đoàn:</strong></td>
                                <td><?php echo isset($dat_tour['ten_doan']) ? $dat_tour['ten_doan'] : 'N/A'; ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin tour -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">
                    <i class="fas fa-map-marked-alt me-2"></i>THÔNG TIN TOUR
                </h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th width="40%">Tên tour</th>
                                <th width="15%" class="text-center">Ngày khởi hành</th>
                                <th width="15%" class="text-center">Ngày kết thúc</th>
                                <th width="15%" class="text-center">Số khách</th>
                                <th width="15%" class="text-center">Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong class="text-blue">
                                        <?php echo isset($tour['ten_tour']) ? $tour['ten_tour'] : 'N/A'; ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        Mã tour: <?php echo isset($tour['ma_tour']) ? $tour['ma_tour'] : 'N/A'; ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if (isset($lich_khoi_hanh['ngay_bat_dau']) && !empty($lich_khoi_hanh['ngay_bat_dau'])) {
                                        echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_bat_dau']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if (isset($lich_khoi_hanh['ngay_ket_thuc']) && !empty($lich_khoi_hanh['ngay_ket_thuc'])) {
                                        echo date('d/m/Y', strtotime($lich_khoi_hanh['ngay_ket_thuc']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo isset($dat_tour['so_luong_khach']) ? $dat_tour['so_luong_khach'] . ' khách' : 'N/A'; ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if (isset($tour['gia_tour'])) {
                                        echo number_format($tour['gia_tour'], 0, ',', '.') . '₫';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Danh sách thành viên -->
        <?php if (!empty($thanh_vien_list) && is_array($thanh_vien_list)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">
                    <i class="fas fa-users me-2"></i>DANH SÁCH THÀNH VIÊN (<?php echo count($thanh_vien_list); ?> người)
                </h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="25%">Họ tên</th>
                                <th width="15%" class="text-center">Ngày sinh</th>
                                <th width="10%" class="text-center">Giới tính</th>
                                <th width="20%">CCCD/CMND</th>
                                <th width="25%">Yêu cầu đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($thanh_vien_list as $index => $thanh_vien): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td><?php echo isset($thanh_vien['ho_ten']) ? $thanh_vien['ho_ten'] : 'N/A'; ?></td>
                                <td class="text-center">
                                    <?php 
                                    if (isset($thanh_vien['ngay_sinh']) && !empty($thanh_vien['ngay_sinh'])) {
                                        echo date('d/m/Y', strtotime($thanh_vien['ngay_sinh']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if (isset($thanh_vien['gioi_tinh'])) {
                                        echo $thanh_vien['gioi_tinh'];
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo isset($thanh_vien['cccd']) ? $thanh_vien['cccd'] : 'N/A'; ?>
                                </td>
                                <td>
                                    <?php 
                                    if (isset($thanh_vien['yeu_cau_dac_biet']) && !empty($thanh_vien['yeu_cau_dac_biet'])) {
                                        // Xử lý JSON nếu cần
                                        if (is_string($thanh_vien['yeu_cau_dac_biet'])) {
                                            $yeu_cau = json_decode($thanh_vien['yeu_cau_dac_biet'], true);
                                            if (json_last_error() === JSON_ERROR_NONE && isset($yeu_cau['yeu_cau'])) {
                                                echo $yeu_cau['yeu_cau'];
                                            } else {
                                                echo $thanh_vien['yeu_cau_dac_biet'];
                                            }
                                        } else {
                                            echo $thanh_vien['yeu_cau_dac_biet'];
                                        }
                                    } else {
                                        echo 'Không có';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Thông tin thanh toán -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">
                    <i class="fas fa-money-bill-wave me-2"></i>THÔNG TIN THANH TOÁN
                </h4>
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="total-section p-3">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="60%"><strong>Tổng tiền tour:</strong></td>
                                    <td class="text-end">
                                        <?php 
                                        if (isset($dat_tour['tong_tien'])) {
                                            echo number_format($dat_tour['tong_tien'], 0, ',', '.') . '₫';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Đã thanh toán:</strong></td>
                                    <td class="text-end text-green">
                                        <?php 
                                        $da_thanh_toan = isset($hoa_don['da_thanh_toan']) ? $hoa_don['da_thanh_toan'] : 0;
                                        echo number_format($da_thanh_toan, 0, ',', '.') . '₫';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Còn nợ:</strong></td>
                                    <td class="text-end text-red">
                                        <?php 
                                        $con_no = isset($hoa_don['con_no']) ? $hoa_don['con_no'] : (isset($dat_tour['tong_tien']) ? $dat_tour['tong_tien'] : 0);
                                        echo number_format($con_no, 0, ',', '.') . '₫';
                                        ?>
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>TỔNG CỘNG:</strong></td>
                                    <td class="text-end fw-bold fs-5 text-blue">
                                        <?php 
                                        if (isset($dat_tour['tong_tien'])) {
                                            echo number_format($dat_tour['tong_tien'], 0, ',', '.') . '₫';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phương thức thanh toán & Chữ ký -->
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-blue mb-3">PHƯƠNG THỨC THANH TOÁN</h5>
                <div class="bank-info">
                    <p class="mb-2">
                        <strong>
                            <?php 
                            $phuong_thuc = isset($hoa_don['phuong_thuc_thanh_toan']) ? $hoa_don['phuong_thuc_thanh_toan'] : 'tiền mặt';
                            echo getPaymentMethodText($phuong_thuc);
                            ?>
                        </strong>
                    </p>
                    <?php if ($phuong_thuc == 'chuyển khoản'): ?>
                    <p class="mb-1 small"><strong>Ngân hàng:</strong> Techcombank - Chi nhánh Hà Nội</p>
                    <p class="mb-1 small"><strong>Số tài khoản:</strong> 1903 6669 9990 01</p>
                    <p class="mb-1 small"><strong>Chủ tài khoản:</strong> CÔNG TY TNHH DU LỊCH VIETTRAVEL</p>
                    <p class="mb-0 small">
                        <strong>Nội dung chuyển khoản:</strong> 
                        <?php echo $dat_tour['ma_dat_tour']; ?> - <?php echo isset($khach_hang['ho_ten']) ? $khach_hang['ho_ten'] : 'Khách hàng'; ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="signature-section text-center">
                    <p class="mb-4"><strong>Hà Nội, ngày <?php echo date('d'); ?> tháng <?php echo date('m'); ?> năm <?php echo date('Y'); ?></strong></p>
                    <p class="fw-bold mb-1">NGƯỜI LẬP HÓA ĐƠN</p>
                    <p class="text-muted">(Ký và ghi rõ họ tên)</p>
                    <div style="margin-top: 60px;"></div>
                    <p class="border-top pt-2"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Quản trị viên'; ?></p>
                </div>
            </div>
        </div>

        <!-- Ghi chú cuối -->
        <div class="footer-note">
            <div class="alert alert-light border">
                <h6 class="alert-heading text-blue mb-2">
                    <i class="fas fa-info-circle me-2"></i>GHI CHÚ QUAN TRỌNG
                </h6>
                <p class="mb-1">• Quý khách vui lòng thanh toán trước ngày <strong><?php echo date('d/m/Y', strtotime('+7 days')); ?></strong></p>
                <p class="mb-1">• Hóa đơn này là chứng từ thanh toán có giá trị pháp lý</p>
                <p class="mb-1">• Mọi thắc mắc xin liên hệ hotline: <strong>1900 1234</strong> hoặc email: <strong>info@viettravel.com</strong></p>
                <p class="mb-0">• Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Debug: Hiển thị tất cả dữ liệu trong console
        console.log('dat_tour:', <?php echo json_encode($dat_tour); ?>);
        console.log('khach_hang:', <?php echo json_encode($khach_hang); ?>);
        console.log('tour:', <?php echo json_encode($tour); ?>);
        console.log('lich_khoi_hanh:', <?php echo json_encode($lich_khoi_hanh); ?>);
        console.log('thanh_vien_list:', <?php echo json_encode($thanh_vien_list); ?>);
        console.log('hoa_don:', <?php echo json_encode($hoa_don); ?>);

        // Xử lý phím tắt
        document.addEventListener('keydown', function(e) {
            // Ctrl + P để in
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // ESC để quay lại
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</body>
</html>

<?php
// Helper function
function getPaymentMethodText($method)
{
    $methods = [
        'tiền mặt' => 'Tiền mặt',
        'chuyển khoản' => 'Chuyển khoản ngân hàng',
        'thẻ' => 'Thẻ thanh toán',
        'khác' => 'Phương thức khác'
    ];
    return $methods[$method] ?? 'Tiền mặt';
}
?>