<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn <?= 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --primary: #2563eb;
        }
        body {
            background: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #334155;
            -webkit-print-color-adjust: exact;
        }
        .invoice-card {
            background: #fff;
            max-width: 850px;
            margin: 40px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
        }
        .invoice-header {
            background: #fdfdfd;
            padding: 50px 50px 30px;
            border-bottom: 1px solid #f1f5f9;
        }
        .invoice-body {
            padding: 40px 50px;
        }
        .invoice-footer {
            padding: 30px 50px 50px;
            background: #fdfdfd;
            border-top: 1px solid #f1f5f9;
        }
        .text-primary { color: var(--primary) !important; }
        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px;
        }
        .table tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        
        .no-print-zone {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
        
        @media print {
            .no-print { display: none !important; }
            .invoice-card {
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: none;
            }
            body { background: #fff; }
        }
    </style>
</head>
<body>

    <div class="no-print no-print-zone">
        <div class="d-flex gap-2 bg-white p-2 rounded-pill shadow-lg border">
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2">
                <i class="ph ph-printer"></i> In Hóa đơn
            </button>
            <button onclick="window.close()" class="btn btn-light rounded-pill px-3">Đóng</button>
        </div>
    </div>

    <div class="invoice-card">
        <div class="invoice-header d-flex justify-content-between align-items-start">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-primary rounded-3 p-2 text-white">
                        <i class="ph-fill ph-airplane-tilt" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">TravelAgile</h3>
                </div>
                <p class="text-muted small mb-0">Công ty TNHH Du lịch & Lữ hành Agile</p>
                <p class="text-muted small">123 Đường Cầu Giấy, Hà Nội • 024.123.4567</p>
            </div>
            <div class="text-end">
                <h2 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Hóa Đơn</h2>
                <h6 class="text-muted mb-3">Mã: <span class="text-dark fw-bold">#<?= 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span></h6>
                <div class="small">
                    <div class="text-muted">Ngày xuất: <span class="text-dark fw-bold"><?= date('d/m/Y H:i') ?></span></div>
                    <div class="text-muted">Trạng thái: 
                        <?php if ($booking['status'] == 'hoan_tat'): ?>
                            <span class="status-badge status-paid">Đã Thu Đủ</span>
                        <?php else: ?>
                            <span class="status-badge status-pending"><?= $booking['status'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="row mb-5">
                <div class="col-6">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3">Khách hàng</h6>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($booking['customer_name']) ?></h5>
                    <p class="text-muted small mb-0"><?= htmlspecialchars($booking['customer_email']) ?></p>
                    <p class="text-muted small"><?= htmlspecialchars($booking['customer_phone']) ?></p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3">Chi tiết tour</h6>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($booking['tour_name']) ?></h5>
                    <p class="text-muted small mb-0">Ngày đi: <span class="text-dark fw-bold"><?= date('d/m/Y', strtotime($booking['departure_date'])) ?></span></p>
                    <?php if (!empty($booking['bus_company_name'])): ?>
                        <p class="text-muted small">Nhà xe: <?= htmlspecialchars($booking['bus_company_name']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <table class="table mb-4">
                <thead>
                    <tr>
                        <th class="ps-0">Mô tả dịch vụ</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-end pe-0">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-0">
                            <div class="fw-bold text-dark">Giá tour cơ bản</div>
                            <small class="text-muted"><?= htmlspecialchars($booking['tour_name']) ?></small>
                        </td>
                        <td class="text-center"><?= $booking['adults'] + $booking['children'] ?> khách</td>
                        <td class="text-end"><?= number_format($booking['tour_base_price']) ?> ₫</td>
                        <td class="text-end pe-0"><?= number_format($booking['total_price']) ?> ₫</td>
                    </tr>
                </tbody>
            </table>

            <div class="row justify-content-end">
                <div class="col-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?= number_format($booking['total_price']) ?> ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">VAT (0%):</span>
                        <span class="fw-bold">0 ₫</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold text-dark">Tổng cộng:</h5>
                        <h5 class="fw-bold text-primary"><?= number_format($booking['final_price']) ?> ₫</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="row">
                <div class="col-7">
                    <h6 class="fw-bold text-dark mb-2">Thông tin thanh toán</h6>
                    <p class="text-muted small mb-1">Tài khoản: <strong>CÔNG TY TNHH TRAVELAGILE</strong></p>
                    <p class="text-muted small mb-1">Số TK: <strong>123456789</strong> - Ngân hàng Vietcombank</p>
                    <p class="text-muted small">Nội dung: <strong><?= 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></strong></p>
                </div>
                <div class="col-5 text-center pt-4">
                    <div class="mb-4">
                        <p class="small text-muted mb-4">Người lập phiếu</p>
                        <div style="height: 60px;"></div>
                        <p class="fw-bold text-dark mb-0"><?= $_SESSION['user']['full_name'] ?? 'Admin' ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-5 pt-3 border-top text-center">
                <p class="text-muted small mb-0">Cảm ơn Quý khách đã tin tưởng và đồng hành cùng <strong>TravelAgile</strong>!</p>
            </div>
        </div>
    </div>

    <script>
        // Tự động mở hộp thoại in nếu được yêu cầu qua URL
        if (window.location.search.includes('print=true')) {
            window.onload = function() { window.print(); }
        }
    </script>

</body>
</html>
