<?php

class PDFService
{
    /**
     * Tạo nội dung HTML cho hóa đơn (Invoice)
     */
    public static function generateBookingInvoiceHtml($booking, $tour)
    {
        $bookingCode = 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
        
        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; color: #555;'>
            <table cellpadding='0' cellspacing='0' style='width: 100%; line-height: inherit; text-align: left;'>
                <tr class='top'>
                    <td colspan='2' style='padding-bottom: 20px;'>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='font-size: 30px; line-height: 30px; color: #333; font-weight: bold;'>
                                    TravelAgile
                                </td>
                                <td style='text-align: right;'>
                                    Hóa đơn #: {$bookingCode}<br>
                                    Ngày tạo: " . date('d/m/Y') . "<br>
                                    Hạn thanh toán: " . date('d/m/Y', strtotime('+2 days')) . "
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <tr class='information'>
                    <td colspan='2' style='padding-bottom: 40px;'>
                        <table style='width: 100%;'>
                            <tr>
                                <td>
                                    Công ty TNHH TravelAgile<br>
                                    123 Đường ABC, Hà Nội<br>
                                    Việt Nam
                                </td>
                                <td style='text-align: right;'>
                                    {$booking['contact_name']}<br>
                                    {$booking['contact_phone']}<br>
                                    {$booking['contact_email']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
                <tr class='heading'>
                    <td style='background: #eee; border-bottom: 1px solid #ddd; font-weight: bold;'>
                        Dịch vụ
                    </td>
                    <td style='background: #eee; border-bottom: 1px solid #ddd; text-align: right; font-weight: bold;'>
                        Giá thanh toán
                    </td>
                </tr>
                
                <tr class='item'>
                    <td style='border-bottom: 1px solid #eee;'>
                        <strong>Tour: {$tour['name']}</strong><br>
                        " . date('d/m/Y', strtotime($booking['departure_date'])) . " | {$booking['adults']} người lớn, {$booking['children']} trẻ em
                    </td>
                    <td style='border-bottom: 1px solid #eee; text-align: right;'>
                        " . number_format($booking['total_price'], 0, ',', '.') . " VNĐ
                    </td>
                </tr>
                
                <tr class='total'>
                    <td></td>
                    <td style='text-align: right; font-weight: bold; border-top: 2px solid #eee; font-size: 20px;'>
                       Tổng cộng: " . number_format($booking['total_price'], 0, ',', '.') . " VNĐ
                    </td>
                </tr>
            </table>
            
            <div style='margin-top: 50px; text-align: center; font-size: 12px; color: #999;'>
                Cảm ơn bạn đã tin tưởng dịch vụ của TravelAgile!
            </div>
        </div>
        ";

        return $html;
    }

    /**
     * Xuất ra file PDF (Simulation)
     */
    public static function exportToPDF($html, $filename = 'invoice.pdf')
    {
        // Trong môi trường không có Dompdf, giả lập việc xuất file
        // Nếu có Dompdf:
        // $dompdf = new Dompdf\Dompdf();
        // $dompdf->loadHtml($html);
        // $dompdf->render();
        // $dompdf->stream($filename);
        
        $logDir = PATH_ROOT . 'assets/uploads/invoices/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        $filePath = $logDir . str_replace('.pdf', '.html', $filename);
        file_put_contents($filePath, $html);
        
        return BASE_ASSETS_UPLOADS . 'invoices/' . str_replace('.pdf', '.html', $filename);
    }
}
