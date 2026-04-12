<?php

class EmailService
{
    /**
     * Gửi email xác nhận đặt tour (Simulation)
     */
    public static function sendBookingConfirmation($booking, $tour)
    {
        $to = $booking['contact_email'];
        $subject = "Xác nhận đặt tour: " . $tour['name'];
        $bookingCode = 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
        
        $message = "
        <html>
        <head>
            <title>Xác nhận đặt tour</title>
        </head>
        <body>
            <h2>Cảm ơn bạn đã đặt tour tại TravelAgile!</h2>
            <p>Xin chào <strong>{$booking['contact_name']}</strong>,</p>
            <p>Chúng tôi đã nhận được yêu cầu đặt tour của bạn. Dưới đây là thông tin chi tiết:</p>
            <ul>
                <li><strong>Mã đơn hàng:</strong> {$bookingCode}</li>
                <li><strong>Tên tour:</strong> {$tour['name']}</li>
                <li><strong>Ngày khởi hành:</strong> " . date('d/m/Y', strtotime($booking['departure_date'])) . "</li>
                <li><strong>Số lượng:</strong> {$booking['adults']} người lớn, {$booking['children']} trẻ em</li>
                <li><strong>Tổng tiền:</strong> " . number_format($booking['total_price'], 0, ',', '.') . " VNĐ</li>
            </ul>
            <p>Trạng thái hiện tại: <strong>Chờ xác nhận thanh toán</strong></p>
            <p>Chúng tôi sẽ kiểm tra và liên hệ lại với bạn trong vòng 24h.</p>
            <hr>
            <p>Đây là email tự động, vui lòng không phản hồi.</p>
        </body>
        </html>
        ";

        // Real implementation using PHP mail() or placeholders for PHPMailer
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: TravelAgile <noreply@travelagile.com>" . "\r\n";

        // Simulation/Log as fallback for local dev
        $logDir = PATH_ROOT . 'logs/emails/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . $bookingCode . '_' . time() . '.html';
        file_put_contents($logFile, $message);

        // Attempt real mail sent
        try {
            return @mail($to, $subject, $message, $headers);
        } catch (Exception $e) {
            return false;
        }
    }
}
