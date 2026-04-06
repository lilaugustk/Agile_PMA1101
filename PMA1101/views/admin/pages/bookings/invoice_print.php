<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Xác Nhận Đặt Tour #<?= $booking['id'] ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        
        .invoice-box {
            max-width: 850px;
            margin: auto;
            border: 1px solid #eee;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            color: #3b82f6;
            margin: 0 0 5px 0;
            font-size: 28px;
            letter-spacing: -1px;
        }
        
        .company-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        
        .invoice-details {
            text-align: right;
        }
        
        .invoice-details h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #111;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .section-title {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 15px;
            display: block;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        
        .info-item {
            margin-bottom: 10px;
            font-size: 15px;
        }
        
        .info-item strong {
            color: #1e293b;
            width: 140px;
            display: inline-block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 12px 15px;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 15px;
        }
        
        .total-section {
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #1e293b;
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: #3b82f6;
            margin-top: 5px;
        }
        
        .footer-note {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 13px;
            color: #94a3b8;
            text-align: center;
        }
        
        .stamp {
            margin-top: 30px;
            text-align: right;
            padding-right: 50px;
        }
        
        .stamp-circle {
            display: inline-block;
            width: 120px;
            height: 120px;
            border: 3px double #ef4444;
            border-radius: 50%;
            color: #ef4444;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
            padding-top: 30px;
            transform: rotate(-15deg);
            opacity: 0.6;
            text-transform: uppercase;
        }

        @media print {
            body { padding: 0; }
            .invoice-box { border: none; box-shadow: none; }
            .no-print { display: none; }
        }
        
        .no-print-bar {
            background: #1e293b;
            padding: 15px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .btn-print {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }
        
        body { padding-top: 80px; }
        @media print { body { padding-top: 0; } .no-print-bar { display: none; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <button onclick="window.print()" class="btn-print">In Phiếu Xác Nhận</button>
        <button onclick="window.close()" class="btn-print" style="background: transparent; border: 1px solid #475569; margin-left: 10px;">Đóng</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h1>ANTIGRAVITY TRAVEL</h1>
                <p>123 Đường Sáng Tạo, Quận Cầu Giấy, Hà Nội</p>
                <p>Hotline: 1900 8888 | Email: contact@antigravity.vn</p>
                <p>Website: www.antigravity-travel.vn</p>
            </div>
            <div class="invoice-details">
                <h2 style="color: #3b82f6;">PHIẾU XÁC NHẬN ĐẶT TOUR</h2>
                <p>Số: <strong>BK-<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></strong></p>
                <p>Ngày lập: <?= date('d/m/Y') ?></p>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <span class="section-title">Thông tin khách hàng</span>
                <div class="info-item"><strong>Khách hàng:</strong> <?= htmlspecialchars($booking['customer_name'] ?? $booking['contact_name']) ?></div>
                <div class="info-item"><strong>Điện thoại:</strong> <?= htmlspecialchars($booking['contact_phone']) ?></div>
                <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($booking['contact_email']) ?></div>
                <div class="info-item"><strong>Địa chỉ:</strong> <?= htmlspecialchars($booking['contact_address']) ?></div>
            </div>
            <div>
                <span class="section-title">Chi tiết Tour</span>
                <div class="info-item"><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></div>
                <div class="info-item"><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($booking['departure_date'])) ?></div>
                <div class="info-item"><strong>Thời gian:</strong> <?= htmlspecialchars($booking['duration'] ?? 'N/A') ?></div>
                <div class="info-item"><strong>Trạng thái:</strong> 
                    <?php
                    $statusText = 'Chờ xác nhận';
                    if ($booking['status'] == 'da_coc') $statusText = 'Đã đặt cọc';
                    if ($booking['status'] == 'hoan_tat') $statusText = 'Hoàn tất';
                    if ($booking['status'] == 'da_huy') $statusText = 'Đã hủy';
                    echo $statusText;
                    ?>
                </div>
            </div>
        </div>

        <span class="section-title">Danh sách khách đi đoàn</span>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">STT</th>
                    <th>Họ và Tên</th>
                    <th>Phân loại</th>
                    <th>Phòng</th>
                    <th style="text-align: right;">Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <!-- Người đặt là khách đầu tiên -->
                <tr>
                    <td>1</td>
                    <td><strong><?= htmlspecialchars($booking['customer_name'] ?? $booking['contact_name']) ?></strong></td>
                    <td>Người lớn</td>
                    <td><?= htmlspecialchars($booking['contact_room_type'] ?? 'Standard') ?></td>
                    <td style="text-align: right;">Người đặt chính</td>
                </tr>
                <?php if (!empty($companions)): ?>
                    <?php foreach ($companions as $index => $companion): ?>
                    <tr>
                        <td><?= $index + 2 ?></td>
                        <td><?= htmlspecialchars($companion['full_name']) ?></td>
                        <td>
                            <?php 
                            if ($companion['passenger_type'] == 'adult') echo 'Người lớn';
                            elseif ($companion['passenger_type'] == 'child') echo 'Trẻ em';
                            else echo 'Em bé';
                            ?>
                        </td>
                        <td><?= htmlspecialchars($companion['room_type'] ?? '-') ?></td>
                        <td style="text-align: right;"><?= htmlspecialchars($companion['special_request'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>Tổng tiền:</span>
                <span><?= number_format($booking['total_price']) ?> VNĐ</span>
            </div>
            <div class="total-row">
                <span>Khuyến mãi/Giảm giá:</span>
                <span>0 VNĐ</span>
            </div>
            <div class="total-row grand-total">
                <span>THÀNH TIỀN:</span>
                <span><?= number_format($booking['final_price']) ?> VNĐ</span>
            </div>
        </div>

        <div class="stamp">
            <div class="stamp-circle">
                Antigravity<br>Đã Xác Nhận<br>✓
            </div>
            <p style="margin-top: 10px; font-weight: 600;">(Chữ ký và đóng dấu của cty)</p>
        </div>

        <div class="footer-note">
            <p>Cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của Antigravity Travel!</p>
            <p>Lưu ý: Quý khách vui lòng mang theo phiếu này khi tham gia chuyến đi.</p>
        </div>
    </div>
</body>
</html>
