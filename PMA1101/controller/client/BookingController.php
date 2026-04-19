<?php
require_once 'models/Tour.php';
require_once 'models/TourDeparture.php';
require_once 'models/Booking.php';
require_once 'models/BookingCustomer.php';
require_once 'models/CustomerProfileModel.php';
require_once 'models/User.php';

class ClientBookingController
{
    private $tourModel;
    private $departureModel;
    private $bookingModel;
    private $profileModel;
    private $userModel;

    public function __construct()
    {
        $this->tourModel      = new Tour();
        $this->departureModel = new TourDeparture();
        $this->bookingModel   = new Booking();
        $this->profileModel   = new CustomerProfileModel();
        $this->userModel      = new User();
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

        // Kiểm tra trạng thái departure: chỉ cho phép 'open' hoặc 'guaranteed'
        $allowedStatuses = ['open', 'guaranteed'];
        if (!in_array($departure['status'] ?? '', $allowedStatuses)) {
            $_SESSION['error'] = 'Chuyến khởi hành này hiện đã đóng hoặc bị hủy. Vui lòng chọn ngày khác!';
            header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId);
            exit;
        }

        // Dọn dẹp booking pending hết hạn trước khi kiểm tra
        $this->bookingModel->cleanupExpiredPending();

        // Kiểm tra chỗ trống
        $availableSeats = $departure['max_seats'] - ($departure['booked_seats'] ?? 0);
        if ($availableSeats <= 0) {
            header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId);
            exit;
        }
        
        $userProfile = null;
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['user_id'];
            $userProfile = $this->profileModel->getByUserId($userId);
            // Optionally merge with user table data if needed
            $userData = $this->userModel->getById($userId);
            if ($userData) {
                $userProfile = array_merge($userData, $userProfile ?: []);
            }
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

        // Kiểm tra trạng thái departure: chỉ cho phép 'open' hoặc 'guaranteed'
        $allowedStatuses = ['open', 'guaranteed'];
        if (!in_array($departure['status'] ?? '', $allowedStatuses)) {
            $_SESSION['error'] = 'Chuyến khởi hành này hiện đã đóng hoặc bị hủy!';
            header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId);
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
                'status'          => Booking::STATUS_PENDING,
                'expires_at'      => date('Y-m-d H:i:s', strtotime('+' . BOOKING_HOLD_TIME . ' minutes')),
                'created_by'      => (isset($_SESSION['user'])) ? $_SESSION['user']['user_id'] : 0,
                'created_at'      => date('Y-m-d H:i:s'),
            ];

            $bookingId = $this->bookingModel->insert($bookingData);

            // 2. Insert booking_customers từ mảng dữ liệu động
            $bookingCustomerModel = new BookingCustomer();

            $names      = $_POST['companion_name']       ?? [];
            $genders    = $_POST['companion_gender']     ?? [];
            $birthDates = $_POST['companion_birth_date']  ?? [];
            $idCards    = $_POST['companion_id_card']     ?? [];
            $phones     = $_POST['companion_phone']      ?? [];
            $emails     = $_POST['companion_email']      ?? [];
            $addresses  = $_POST['companion_address']    ?? [];
            $notes      = $_POST['companion_note']       ?? [];

            // Tính toán index để phân loại (đã render theo thứ tự: Adult -> Child -> Infant)
            for ($i = 0; $i < count($names); $i++) {
                if (empty(trim($names[$i]))) continue;

                $type = 'adult';
                if ($i >= $adults && $i < ($adults + $children)) {
                    $type = 'child';
                } elseif ($i >= ($adults + $children)) {
                    $type = 'infant';
                }

                $bDate = $birthDates[$i] ?? null;
                if ($bDate && strpos($bDate, '/') !== false) {
                    $parts = explode('/', $bDate);
                    if (count($parts) === 3) {
                        $bDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
                    }
                }

                $customerData = [
                    'booking_id'     => $bookingId,
                    'full_name'      => trim($names[$i]),
                    'gender'         => $genders[$i]    ?? null,
                    'birth_date'     => $bDate,
                    'passenger_type' => $type,
                    'special_request' => $notes[$i]      ?? null
                ];

                // ID Card, Phone, Email và Address chỉ dành cho Adult
                if ($type === 'adult') {
                    $customerData['id_card'] = !empty($idCards[$i]) ? trim($idCards[$i]) : null;
                    $customerData['phone']   = !empty($phones[$i])  ? trim($phones[$i])  : null;
                    $customerData['email']   = !empty($emails[$i])  ? trim($emails[$i])  : null;
                    $customerData['address'] = !empty($addresses[$i]) ? trim($addresses[$i]) : null;
                } else {
                    $customerData['id_card'] = null;
                    $customerData['phone']   = null;
                    $customerData['email']   = null;
                    $customerData['address'] = null;
                }

                $bookingCustomerModel->insert($customerData);
            }

            // 3. Không cộng booked_seats tại thời điểm tạo đơn pending.
            // (Đã có logic dọn dẹp và syncBookedSeats trong detail() và create() của admin/client)

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

        if (!$booking || $booking['status'] === Booking::STATUS_EXPIRED) {
            $_SESSION['error'] = 'Đơn đặt tour đã hết hạn!';
            header('Location: ' . BASE_URL);
            exit;
        }

        // Xử lý upload minh chứng thanh toán
        require_once 'services/FileService.php';
        $paymentProof = null;
        if (isset($_FILES['payment_proof'])) {
            $paymentProof = FileService::upload($_FILES['payment_proof'], UPLOAD_DIR_PAYMENTS);
        }

        // Cập nhật: pending -> cho_xac_nhan, xóa expires_at, lưu minh chứng
        $updateData = [
            'status'     => Booking::STATUS_WAITING,
            'expires_at' => null
        ];
        if ($paymentProof) {
            $updateData['payment_proof'] = $paymentProof;
        }

        $this->bookingModel->update(
            $updateData,
            'id = :id',
            ['id' => $bookingId]
        );

        // Đồng bộ chỗ ngồi (Giữ chỗ)
        if (!empty($booking['departure_id'])) {
            $this->bookingModel->syncBookedSeats((int)$booking['departure_id']);
        }

        // 1. Lấy dữ liệu đầy đủ để gửi mail & tạo PDF
        $fullBooking = $this->bookingModel->getById($bookingId);
        $tour = $this->tourModel->findById($fullBooking['tour_id']);

        // 2. Gửi Email thông báo đã nhận yêu cầu xác nhận thanh toán
        require_once 'services/EmailService.php';
        EmailService::sendBookingConfirmation($fullBooking, $tour);

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

        if ($booking['status'] !== Booking::STATUS_PENDING && $booking['status'] !== Booking::STATUS_WAITING) {
            die(json_encode(['RspCode' => '02', 'Message' => 'Order already confirmed']));
        }

        // 1. Cập nhật trạng thái Booking
        $this->bookingModel->update(
            ['status' => Booking::STATUS_DEPOSITED, 'expires_at' => null],
            'id = :id',
            ['id' => $bookingId]
        );

        // 2. Đồng bộ chỗ ngồi
        if (!empty($booking['departure_id'])) {
            $this->bookingModel->syncBookedSeats((int)$booking['departure_id']);
        }

        // 3. Ghi nhận giao dịch tài chính
        require_once 'models/Transaction.php';
        $transactionModel = new Transaction();
        $transactionModel->recordBookingPayment(
            $bookingId, 
            $booking['total_price'], 
            'online_gateway', 
            "Thanh toán trực tuyến cho đơn hàng BK" . str_pad($bookingId, 6, '0', STR_PAD_LEFT)
        );

        // Notify staff (Simulation)
        error_log("IPN: Booking $bookingId has been paid successfully via Online Gateway.");

        echo json_encode(['RspCode' => '00', 'Message' => 'Success']);
    }
}
