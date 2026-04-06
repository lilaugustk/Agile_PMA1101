<?php
require_once 'models/Tour.php';
require_once 'models/TourDeparture.php';
require_once 'models/Booking.php';
require_once 'models/BookingCustomer.php';

class ClientBookingController
{
    private $tourModel;
    private $departureModel;
    private $bookingModel;

    public function __construct()
    {
        $this->tourModel      = new Tour();
        $this->departureModel = new TourDeparture();
        $this->bookingModel   = new Booking();
    }

    // ─────────────────────────────────────────────────────────────
    // BƯỚC 2: Hiển thị form nhập thông tin
    // URL: ?action=booking-create&tour_id=X&departure_id=Y
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        $tourId      = $_GET['tour_id']      ?? null;
        $departureId = $_GET['departure_id'] ?? null;

        if (!$tourId || !$departureId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $tour      = $this->tourModel->findById($tourId);
        $departure = $this->departureModel->findById($departureId);

        if (!$tour || !$departure) {
            header('Location: ' . BASE_URL);
            exit;
        }

        // Dọn dẹp booking pending hết hạn trước khi kiểm tra
        $this->bookingModel->cleanupExpiredPending();

        // Kiểm tra chỗ trống
        $availableSeats = $departure['max_seats'] - ($departure['booked_seats'] ?? 0);
        if ($availableSeats <= 0) {
            $_SESSION['error'] = 'Chuyến đi này đã hết chỗ!';
            header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId);
            exit;
        }

        // Tính số ngày từ itinerary nếu cần
        if (!isset($tour['duration_days'])) {
            $itineraries = $this->tourModel->getRelatedData('itineraries', $tourId);
            $tour['duration_days'] = count($itineraries) > 0 ? count($itineraries) : 'N/A';
        }

        $error   = $_SESSION['error']   ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        require_once PATH_VIEW_CLIENT . 'pages/bookings/create.php';
    }

    // ─────────────────────────────────────────────────────────────
    // BƯỚC 2→3: Xử lý submit form, lưu booking vào DB
    // POST: ?action=booking-store
    // ─────────────────────────────────────────────────────────────
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $tourId      = trim($_POST['tour_id']      ?? '');
        $departureId = trim($_POST['departure_id'] ?? '');

        // --- Validate bắt buộc ---
        $contactName  = trim($_POST['full_name'] ?? '');
        $contactPhone = trim($_POST['phone']     ?? '');
        $contactEmail = trim($_POST['email']     ?? '');
        $contactAddr  = trim($_POST['address']   ?? '');
        $noteText     = trim($_POST['note']      ?? '');

        $errors = [];
        if (empty($contactName))  $errors[] = 'Vui lòng nhập họ và tên.';
        if (empty($contactPhone)) $errors[] = 'Vui lòng nhập số điện thoại.';
        if (empty($contactEmail) || !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Vui lòng nhập email hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header("Location: " . BASE_URL . "?action=booking-create&tour_id=$tourId&departure_id=$departureId");
            exit;
        }

        // --- Validate số lượng ---
        $adults   = max(1, (int)($_POST['adults']   ?? 1));
        $children = max(0, (int)($_POST['children'] ?? 0));
        $infants  = max(0, (int)($_POST['infants']  ?? 0));
        $totalSeats = $adults + $children; // infants thường không chiếm ghế đơn

        // --- Check availability (sau khi cleanup expired) ---
        $this->bookingModel->cleanupExpiredPending();
        $departure = $this->departureModel->findById($departureId);

        if (!$departure) {
            $_SESSION['error'] = 'Chuyến đi không tồn tại!';
            header("Location: " . BASE_URL . "?action=booking-create&tour_id=$tourId&departure_id=$departureId");
            exit;
        }

        $availableSeats = $departure['max_seats'] - ($departure['booked_seats'] ?? 0);
        if ($availableSeats < $totalSeats) {
            $_SESSION['error'] = "Số chỗ còn lại không đủ! Chỉ còn <strong>{$availableSeats}</strong> chỗ.";
            header("Location: " . BASE_URL . "?action=booking-create&tour_id=$tourId&departure_id=$departureId");
            exit;
        }

        // --- Tính giá ---
        $tour = $this->tourModel->findById($tourId);
        $priceAdult = $departure['price_adult'] > 0 ? $departure['price_adult'] : $tour['base_price'];
        $priceChild = $departure['price_child'] > 0 ? $departure['price_child'] : ($priceAdult * 0.7); // 70% giá người lớn nếu không có giá riêng
        $finalPrice = ($adults * $priceAdult) + ($children * $priceChild);

        // --- Transaction ---
        try {
            $this->bookingModel->beginTransaction();

            // 1. Insert booking chính
            $bookingData = [
                'tour_id'         => $tourId,
                'departure_id'    => $departureId,
                'customer_id'     => (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer')
                                        ? $_SESSION['user']['user_id'] : null,
                'booking_date'    => date('Y-m-d H:i:s'),
                'departure_date'  => $departure['departure_date'],
                'adults'          => $adults,
                'children'        => $children,
                'infants'         => $infants,
                'contact_name'    => $contactName,
                'contact_phone'   => $contactPhone,
                'contact_email'   => $contactEmail,
                'contact_address' => $contactAddr ?: null,
                'notes'           => $noteText ?: null,
                'original_price'  => $finalPrice,
                'final_price'     => $finalPrice,
                'total_price'     => $finalPrice,
                'status'          => 'pending',
                'expires_at'      => date('Y-m-d H:i:s', strtotime('+30 minutes')),
                'created_by'      => (isset($_SESSION['user'])) ? $_SESSION['user']['user_id'] : 0,
                'created_at'      => date('Y-m-d H:i:s'),
            ];

            $bookingId = $this->bookingModel->insert($bookingData);

            // 2. Insert booking_customers (1 row/người lớn + 1 row/trẻ em)
            $bookingCustomerModel = new BookingCustomer();

            // Người đại diện (adult đầu tiên)
            $bookingCustomerModel->insert([
                'booking_id'     => $bookingId,
                'full_name'      => $contactName,
                'phone'          => $contactPhone,
                'passenger_type' => 'adult',
            ]);

            // Thêm các adult còn lại (placeholder)
            for ($i = 2; $i <= $adults; $i++) {
                $bookingCustomerModel->insert([
                    'booking_id'     => $bookingId,
                    'full_name'      => "Người lớn $i",
                    'passenger_type' => 'adult',
                ]);
            }

            // Thêm trẻ em (placeholder)
            for ($i = 1; $i <= $children; $i++) {
                $bookingCustomerModel->insert([
                    'booking_id'     => $bookingId,
                    'full_name'      => "Trẻ em $i",
                    'passenger_type' => 'child',
                ]);
            }

            // Thêm em bé (placeholder)
            for ($i = 1; $i <= $infants; $i++) {
                $bookingCustomerModel->insert([
                    'booking_id'     => $bookingId,
                    'full_name'      => "Em bé $i",
                    'passenger_type' => 'infant',
                ]);
            }

            // 3. Cập nhật số ghế đã đặt
            $newBookedSeats = ($departure['booked_seats'] ?? 0) + $totalSeats;
            $this->departureModel->update(
                ['booked_seats' => $newBookedSeats],
                'id = :id',
                ['id' => $departureId]
            );

            $this->bookingModel->commit();

            // Redirect sang trang thanh toán với ID (không expose code trực tiếp)
            header('Location: ' . BASE_URL . '?action=booking-payment&id=' . $bookingId);
            exit;

        } catch (Exception $e) {
            $this->bookingModel->rollBack();
            $_SESSION['error'] = 'Lỗi xử lý đặt tour: ' . $e->getMessage();
            header("Location: " . BASE_URL . "?action=booking-create&tour_id=$tourId&departure_id=$departureId");
            exit;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // BƯỚC 3: Trang thanh toán
    // URL: ?action=booking-payment&id=X
    // ─────────────────────────────────────────────────────────────
    public function payment()
    {
        $bookingId = (int)($_GET['id'] ?? 0);
        if (!$bookingId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $booking = $this->bookingModel->getDetailForClient($bookingId);

        if (!$booking || $booking['status'] === 'expired') {
            $_SESSION['error'] = 'Đơn đặt tour đã hết hạn hoặc không tồn tại!';
            header('Location: ' . BASE_URL);
            exit;
        }

        // Tạo mã hiển thị
        $bookingCode = 'BK' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);

        // Tính countdown (số giây còn lại)
        $expiresAt = $booking['expires_at'];
        $secondsLeft = $expiresAt ? max(0, strtotime($expiresAt) - time()) : 0;

        require_once PATH_VIEW_CLIENT . 'pages/bookings/payment.php';
    }

    // ─────────────────────────────────────────────────────────────
    // BƯỚC 3→4: Khách xác nhận đã thanh toán
    // POST: ?action=booking-confirm
    // ─────────────────────────────────────────────────────────────
    public function confirmPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        if (!$bookingId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking || $booking['status'] === 'expired') {
            $_SESSION['error'] = 'Đơn đặt tour đã hết hạn!';
            header('Location: ' . BASE_URL);
            exit;
        }

        // Cập nhật: pending → cho_xac_nhan, xóa expires_at
        $this->bookingModel->update(
            ['status' => 'cho_xac_nhan', 'expires_at' => null],
            'id = :id',
            ['id' => $bookingId]
        );

        // 1. Lấy dữ liệu đầy đủ để gửi mail & tạo PDF
        $fullBooking = $this->bookingModel->getById($bookingId);
        $tour = $this->tourModel->findById($fullBooking['tour_id']);

        // 2. Gửi Email (Simulation)
        EmailService::sendBookingConfirmation($fullBooking, $tour);

        // 3. Tạo Hóa đơn / Biên nhận (Simulation)
        $invoiceHtml = PDFService::generateBookingInvoiceHtml($fullBooking, $tour);
        $invoiceUrl = PDFService::exportToPDF($invoiceHtml, 'invoice_' . $bookingId . '.pdf');
        
        // Lưu URL hóa đơn vào session để hiển thị ở trang success
        $_SESSION['last_invoice_url'] = $invoiceUrl;

        $bookingCode = 'BK' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);
        header('Location: ' . BASE_URL . '?action=booking-success&code=' . $bookingCode);
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // BƯỚC 4: Trang hoàn tất
    // URL: ?action=booking-success&code=BKxxxxxx
    // ─────────────────────────────────────────────────────────────
    public function success()
    {
        $code = $_GET['code'] ?? '';
        if (!$code) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $booking = $this->bookingModel->getByCode($code);
        $bookingCode = $code;

        require_once PATH_VIEW_CLIENT . 'pages/bookings/success.php';
    }

    // ─────────────────────────────────────────────────────────────
    // IPN SIMULATOR (US31)
    // URL: ?action=payment-ipn&booking_id=X&status=success
    // ─────────────────────────────────────────────────────────────
    public function ipnSimulator()
    {
        $bookingId = (int)($_GET['booking_id'] ?? 0);
        $status    = $_GET['status'] ?? '';

        if (!$bookingId || $status !== 'success') {
            die(json_encode(['RspCode' => '99', 'Message' => 'Invalid Request']));
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            die(json_encode(['RspCode' => '01', 'Message' => 'Order not found']));
        }

        if ($booking['status'] !== 'pending' && $booking['status'] !== 'cho_xac_nhan') {
            die(json_encode(['RspCode' => '02', 'Message' => 'Order already confirmed']));
        }

        // Cập nhật lên trạng thái Đã Cọc hoặc Hoàn Tất tùy số tiền
        $this->bookingModel->update(
            ['status' => 'da_coc', 'expires_at' => null],
            'id = :id',
            ['id' => $bookingId]
        );

        // Notify staff (Simulation)
        error_log("IPN: Booking $bookingId has been paid successfully via Online Gateway.");

        echo json_encode(['RspCode' => '00', 'Message' => 'Success']);
    }
}
