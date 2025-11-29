<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Nhanh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .payment-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .payment-card {
            max-width: 500px;
            width: 90%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border: 1px solid #ddd;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .payment-header {
            background: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            position: relative;
        }
        .btn-close-custom {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .payment-body {
            padding: 25px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .tour-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        .info-label {
            color: #666;
            font-weight: normal;
        }
        .info-value {
            font-weight: bold;
            color: #333;
        }
        .amount-section {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            text-align: center;
        }
        .amount-box {
            flex: 1;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin: 0 5px;
            background: #f8f9fa;
        }
        .amount-value {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .amount-label {
            color: #666;
            font-size: 0.85rem;
        }
        .section-title {
            font-weight: bold;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
        }
        .payment-methods {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .payment-method {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .payment-method:hover {
            border-color: #28a745;
        }
        .payment-method.selected {
            border-color: #28a745;
            background-color: #f8fff9;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }
        .confirmation-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .btn-pay {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            width: 100%;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        .btn-pay:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        .btn-back {
            background: white;
            color: #333;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #f8f9fa;
            border-color: #28a745;
        }
        hr {
            margin: 25px 0;
            border: none;
            border-top: 2px dashed #ddd;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
</head>
<body>
    <!-- Overlay che mờ background -->
    <div class="payment-overlay">
        <div class="payment-card">
            <!-- Header -->
            <div class="payment-header">
                <h2 class="mb-0">THANH TOÁN NHANH</h2>
                <button type="button" class="btn-close-custom" onclick="closePayment()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="payment-body">
                <?php if (!isset($thong_tin)): ?>
                    <div class="alert alert-danger text-center">
                        Không tìm thấy thông tin đặt tour
                    </div>
                <?php else: 
                    $tong_tien = (float)$thong_tin['tong_tien'];
                    $da_thanh_toan = (float)($thong_tin['da_thanh_toan'] ?? 0);
                    $con_no = $tong_tien - $da_thanh_toan;
                    $ti_le_thanh_toan = $tong_tien > 0 ? ($da_thanh_toan / $tong_tien) * 100 : 0;
                ?>
                
                <!-- Thông tin tour -->
                <div class="tour-title">
                    <?php echo htmlspecialchars($thong_tin['ten_tour'] ?? 'N/A'); ?>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="info-row">
                    <span class="info-label">Mã đặt tour:</span>
                    <span class="info-value"><?php echo $thong_tin['ma_dat_tour'] ?? 'N/A'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Khách hàng:</span>
                    <span class="info-value"><?php echo htmlspecialchars($thong_tin['ho_ten'] ?? 'N/A'); ?></span>
                </div>

                <!-- Thông tin thanh toán -->
                <div class="amount-section">
                    <div class="amount-box">
                        <div class="amount-value"><?php echo number_format($tong_tien); ?>₫</div>
                        <div class="amount-label">Tổng tiền</div>
                    </div>
                    <div class="amount-box">
                        <div class="amount-value"><?php echo number_format($con_no); ?>₫</div>
                        <div class="amount-label">Cần thanh toán</div>
                    </div>
                    <div class="amount-box">
                        <div class="amount-value"><?php echo number_format($ti_le_thanh_toan, 0); ?>%</div>
                        <div class="amount-label">Đã thanh toán</div>
                    </div>
                </div>

                <hr>

                <?php if ($con_no <= 0): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-check-circle me-2"></i>
                        Tour đã được thanh toán đủ
                    </div>
                    <button type="button" class="btn-back" onclick="closePayment()">
                        ĐÓNG
                    </button>
                <?php else: ?>
                    <form id="formThanhToanNhanh" method="POST" action="index.php?act=thanh-toan-nhanh-process">
                        <input type="hidden" name="phieu_dat_id" value="<?php echo $thong_tin['id']; ?>">
                        
                        <!-- Phương thức thanh toán -->
                        <div class="section-title">Phương thức thanh toán *</div>
                        <div class="payment-methods">
                            <div class="payment-method selected" onclick="selectPaymentMethod('tien_mat')">
                                <input type="radio" class="d-none" name="phuong_thuc" 
                                       id="tien_mat" value="tiền mặt" checked>
                                <div>Tiền mặt</div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod('chuyen_khoan')">
                                <input type="radio" class="d-none" name="phuong_thuc" 
                                       id="chuyen_khoan" value="chuyển khoản">
                                <div>Chuyển khoản</div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod('the')">
                                <input type="radio" class="d-none" name="phuong_thuc" 
                                       id="the" value="thẻ">
                                <div>Thẻ</div>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="section-title">Ghi chú thanh toán</div>
                        <textarea class="form-control" name="ghi_chu" rows="2" 
                                  style="resize: none;">Thanh toán toàn bộ tour</textarea>

                        <!-- Xác nhận thanh toán -->
                        <div class="confirmation-box">
                            <strong>Xác nhận thanh toán</strong><br>
                            Số tiền: <strong style="color: #28a745;"><?php echo number_format($con_no); ?>₫</strong>
                        </div>

                        <!-- Nút hành động -->
                        <button type="submit" class="btn-pay">
                            <i class="fas fa-check me-2"></i>XÁC NHẬN THANH TOÁN
                        </button>
                        <button type="button" class="btn-back" onclick="closePayment()">
                            <i class="fas fa-times me-2"></i>QUAY LẠI
                        </button>
                    </form>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Chọn phương thức thanh toán
        function selectPaymentMethod(methodId) {
            // Bỏ chọn tất cả
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('selected');
            });
            
            // Chọn phương thức được click
            const selectedMethod = document.querySelector(`[onclick="selectPaymentMethod('${methodId}')"]`);
            selectedMethod.classList.add('selected');
            
            // Check radio button tương ứng
            document.getElementById(methodId).checked = true;
        }
        
        // Đóng modal thanh toán
        function closePayment() {
            window.history.back();
        }
        
        // Hiệu ứng loading khi submit form
        document.getElementById('formThanhToanNhanh')?.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>ĐANG XỬ LÝ...';
            submitBtn.disabled = true;
        });
        
        // Đóng khi click ra ngoài
        document.querySelector('.payment-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closePayment();
            }
        });
        
        // Đóng khi nhấn ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePayment();
            }
        });
        
        // Focus vào form khi mở
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('input, select, textarea');
            if (firstInput) {
                firstInput.focus();
            }
        });
    </script>
</body>
</html>