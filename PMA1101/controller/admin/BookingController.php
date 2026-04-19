<?php

class BookingController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Booking();
    }

    public function index()
    {
        // Lấy thông tin phân trang và bộ lọc
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 15;

        $filters = [
            'keyword'     => isset($_GET['keyword']) ? trim($_GET['keyword']) : '',
            'status'      => isset($_GET['status']) ? trim($_GET['status']) : '',
            'category_id' => isset($_GET['category_id']) ? trim($_GET['category_id']) : '',
            'price_min'   => isset($_GET['price_min']) ? trim($_GET['price_min']) : '',
            'price_max'   => isset($_GET['price_max']) ? trim($_GET['price_max']) : '',
            'date_from'   => $_GET['date_from'] ?? '',
            'date_to'     => $_GET['date_to'] ?? '',
            'sort_by'     => $_GET['sort_by'] ?? 'booking_date',
            'sort_dir'    => $_GET['sort_dir'] ?? 'DESC'
        ];

        // Lấy thông tin user hiện tại
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        $guideId = null;
        if ($userRole === 'guide') {
            $guideModel = new Guide();
            $guide = $guideModel->getByUserId($userId);
            $guideId = $guide['id'] ?? null;
        }

        // Dọn dẹp đơn hàng hết hạn tự động
        $this->model->cleanupExpiredPending();

        // Gọi Model lấy dữ liệu (đã bao gồm phân trang và bộ lọc)
        $result = $this->model->getAllByRole($userRole, $guideId, $page, $perPage, $filters);
        
        $bookings = $result['data'];
        $pagination = [
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ];

        // Lấy thống kê
        $stats = $this->model->getStats();

        // Lấy danh mục tour để phục vụ bộ lọc
        require_once 'models/TourCategory.php';
        $categoryModel = new TourCategory();
        $categories = $categoryModel->select('*', null, [], 'name ASC');

        require_once PATH_VIEW_ADMIN . 'pages/bookings/index.php';
    }

    public function create()
    {
        // Load customers and tours data for dropdown
        $customerModel = new UserModel();
        $tourModel = new Tour();

        $customers = $customerModel->getAllWithProfiles();
        $tours = $tourModel->select('*', null, [], 'name ASC');

        require_once PATH_VIEW_ADMIN . 'pages/bookings/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
            exit;
        }

        try {
            // Validate inputs
            $customer_id = $_POST['customer_id'] ?? null;
            $tour_id = $_POST['tour_id'] ?? null;
            $booking_date = $_POST['booking_date'] ?? null;
            $total_price = $_POST['total_price'] ?? null;
            $status = $_POST['status'] ?? 'cho_xac_nhan';
            $notes = $_POST['notes'] ?? '';

            // Basic validation
            if (!$customer_id || !$tour_id || !$booking_date || !$total_price || !$status) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
                exit;
            }

            // --- CAPACITY CHECK ---
            if (!empty($_POST['departure_id'])) {
                require_once 'models/TourDeparture.php';
                $departureModel = new TourDeparture();
                $departure = $departureModel->findById($_POST['departure_id']);
                if ($departure) {
                    $requestedSeats = (int)($_POST['adults'] ?? 1) + (int)($_POST['children'] ?? 0);
                    $availableSeats = $departure['max_seats'] - $departure['booked_seats'];
                    if ($availableSeats < $requestedSeats) {
                        $_SESSION['error'] = "Số lượng chỗ không đủ! Chuyến đi này chỉ còn <b>{$availableSeats}</b> chỗ trống.";
                        header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
                        exit;
                    }
                }
            }

            // Insert booking
            $booking_id = $this->model->insert([
                'customer_id' => !empty($customer_id) ? $customer_id : null,
                'tour_id' => $tour_id,
                'departure_id' => $_POST['departure_id'] ?? null,
                'booking_date' => $booking_date,
                'total_price' => $total_price,
                'final_price' => $total_price,
                'status' => $status,
                'notes' => $notes,
                'adults' => (int)($_POST['adults'] ?? 1),
                'children' => (int)($_POST['children'] ?? 0),
                'infants' => (int)($_POST['infants'] ?? 0),
                'contact_name' => $_POST['contact_name'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_address' => $_POST['contact_address'] ?? '',
                'created_by' => $_SESSION['user']['user_id'] ?? null
            ]);

            // Sync seats after manual creation
            if (!empty($_POST['departure_id'])) {
                $this->model->syncBookedSeats((int)$_POST['departure_id']);
            }

            // Insert booking customers (companions)
            if (!empty($_POST['companion_name'])) {
                require_once 'models/BookingCustomer.php';
                require_once 'models/CustomerProfileModel.php';
                $bookingCustomerModel = new BookingCustomer();
                $userModel = new UserModel();
                $profileModel = new CustomerProfileModel();

                foreach ($_POST['companion_name'] as $index => $name) {
                    if (!empty($name)) {
                        $guestUserId = $_POST['companion_user_id'][$index] ?? null;
                        $createAcc = isset($_POST['companion_create_account'][$index]) && $_POST['companion_create_account'][$index] == '1';
                        $guestEmail = $_POST['companion_email'][$index] ?? '';

                        // Tự động tạo tài khoản nếu được chọn và chưa có user_id
                        if ($createAcc && empty($guestUserId) && !empty($guestEmail)) {
                            if (!$userModel->emailExists($guestEmail)) {
                                $guestUserId = $userModel->insert([
                                    'full_name' => $name,
                                    'email' => $guestEmail,
                                    'phone' => $_POST['companion_phone'][$index] ?? '',
                                    'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                                    'role' => 'customer'
                                ]);
                            } else {
                                $existingUser = $userModel->select('user_id', 'email = :email', ['email' => $guestEmail]);
                                if (!empty($existingUser)) {
                                    $guestUserId = $existingUser[0]['user_id'];
                                }
                            }
                        }

                        $bookingCustomerData = [
                            'booking_id' => $booking_id,
                            'user_id' => !empty($guestUserId) ? $guestUserId : null,
                            'full_name' => $name,
                            'email' => $guestEmail,
                            'passenger_type' => $_POST['companion_passenger_type'][$index] ?? 'adult',
                            'is_foc' => isset($_POST['companion_is_foc'][$index]) ? 1 : 0,
                            'gender' => $_POST['companion_gender'][$index] ?? '',
                            'birth_date' => $this->normalizeDateInput($_POST['companion_birth_date'][$index] ?? null),
                            'phone' => $_POST['companion_phone'][$index] ?? '',
                            'address' => $_POST['companion_address'][$index] ?? '',
                            'id_card' => $_POST['companion_id_card'][$index] ?? '',
                            'special_request' => $_POST['companion_special_request'][$index] ?? '',
                            'room_type' => $_POST['companion_room_type'][$index] ?? ''
                        ];

                        $bookingCustomerModel->insert($bookingCustomerData);

                        // Đồng bộ thông tin vào hồ sơ gốc nếu có User Account
                        if (!empty($guestUserId)) {
                            // Cập nhật thông tin cơ bản ở bảng Users
                            $userModel->update([
                                'full_name' => $bookingCustomerData['full_name'],
                                'phone' => $bookingCustomerData['phone']
                            ], 'user_id = :uid', ['uid' => $guestUserId]);

                            // Cập nhật/Tạo mới hồ sơ chi tiết ở bảng customer_profiles
                            $profileModel->upsertProfile($guestUserId, [
                                'full_name' => $bookingCustomerData['full_name'],
                                'email' => $bookingCustomerData['email'],
                                'phone' => $bookingCustomerData['phone'],
                                'gender' => $bookingCustomerData['gender'],
                                'birth_date' => $bookingCustomerData['birth_date'],
                                'id_card' => $bookingCustomerData['id_card'],
                                'address' => $bookingCustomerData['address'],
                                'passenger_type' => $bookingCustomerData['passenger_type'],
                                'special_request' => $bookingCustomerData['special_request']
                            ]);
                        }
                    }
                }
            }

            $_SESSION['success'] = 'Tạo đơn đặt tour thành công';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/create');
            exit;
        }
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Handle price update from calculator widget
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
            $calculatedTotal = $_POST['calculated_total'] ?? null;

            if ($calculatedTotal) {
                try {
                    $this->model->update([
                        'total_price' => $calculatedTotal,
                        'final_price' => $calculatedTotal
                    ], 'id = :id', ['id' => $id]);

                    $_SESSION['success'] = 'Cập nhật giá thành công!';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Lỗi khi cập nhật giá: ' . $e->getMessage();
                }
            }

            // Redirect to prevent form resubmission
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
            exit;
        }

        $booking = $this->model->getBookingWithDetails($id);

        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $canEdit = true;
        if (($booking['status'] ?? '') === 'hoan_tat') {
            $canEdit = false;
        }

        // Lấy danh sách khách đi kèm
        $bookingCustomerModel = new BookingCustomer();
        $companions = $bookingCustomerModel->getByBooking($id);

        // Ghi nhận số tiền đã thanh toán/đã cọc để hiển thị tại trang chi tiết.
        $pdo = BaseModel::getPdo();
        $paidAmount = 0.0;
        try {
            $stmtPaid = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) AS total_paid
                FROM transactions
                WHERE booking_id = :booking_id
                  AND type = 'income'");
            $stmtPaid->execute(['booking_id' => $id]);
            $paidAmount = (float)($stmtPaid->fetch(PDO::FETCH_ASSOC)['total_paid'] ?? 0);

            if ($paidAmount <= 0) {
                $stmtFallback = $pdo->prepare("SELECT COALESCE(SUM(payment_amount), 0) AS total_paid
                    FROM booking_customers
                    WHERE booking_id = :booking_id");
                $stmtFallback->execute(['booking_id' => $id]);
                $paidAmount = (float)($stmtFallback->fetch(PDO::FETCH_ASSOC)['total_paid'] ?? 0);
            }
        } catch (Exception $e) {
            $paidAmount = 0.0;
        }
        $booking['paid_amount'] = $paidAmount;

        require_once PATH_VIEW_ADMIN . 'pages/bookings/detail.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($id, $userId, $userRole)) {
            $_SESSION['error'] = 'Bạn không có quyền sửa booking này';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $booking = $this->model->getBookingWithDetails($id);

        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        if (($booking['status'] ?? '') === 'hoan_tat') {
            $_SESSION['error'] = 'Booking đã hoàn tất, không thể chỉnh sửa.';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
            exit;
        }

        // Lấy danh sách khách đi kèm
        $bookingCustomerModel = new BookingCustomer();
        $companions = $bookingCustomerModel->getByBooking($id);

        // Load customers and tours data for dropdown
        $customerModel = new UserModel();
        $tourModel = new Tour();

        $customers = $customerModel->select('*', "role = :role", ['role' => 'customer']);
        $tours = $tourModel->select('*', null, [], 'name ASC');

        // Get guides list
        $guideModel = new Guide();
        $guides = $guideModel->getAll();

        require_once PATH_VIEW_ADMIN . 'pages/bookings/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        $booking = $this->model->select('status', 'id = :id', ['id' => $id]);
        if (!empty($booking) && $booking[0]['status'] === 'hoan_tat') {
            $_SESSION['error'] = 'Không thể chỉnh sửa đơn hàng đã hoàn tất.';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
            exit;
        }

        if (!$this->model->canUserEditBooking($id, $userId, $userRole)) {
            $_SESSION['error'] = 'Bạn không có quyền sửa booking này';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        try {
            $this->model->beginTransaction();

            $currentBooking = $this->model->getById($id);
            if (($currentBooking['status'] ?? '') === 'hoan_tat') {
                $_SESSION['error'] = 'Booking đã hoàn tất, không thể cập nhật.';
                $this->model->rollBack();
                header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
                exit;
            }

            // Validate inputs
            $customer_id = $_POST['customer_id'] ?? null;
            
            // Dự phòng: Nếu customer_id trống, lấy từ phần tử đầu tiên của danh sách khách hàng
            if (empty($customer_id) && !empty($_POST['companion_user_id'][0])) {
                $customer_id = $_POST['companion_user_id'][0];
            }

            $tour_id = $_POST['tour_id'] ?? null;
            $booking_date = $_POST['booking_date'] ?? null;
            $total_price = $_POST['total_price'] ?? null;
            $status = $_POST['status'] ?? 'cho_xac_nhan';
            $notes = $_POST['notes'] ?? '';
            // Update booking
            $this->model->update([
                'customer_id' => !empty($customer_id) ? $customer_id : null,
                'tour_id' => $tour_id,
                'departure_id' => $_POST['departure_id'] ?? null,
                'booking_date' => $booking_date,
                'total_price' => $total_price,
                'final_price' => $total_price,
                'status' => $status,
                'notes' => $notes,
                'adults' => (int)($_POST['adults'] ?? 1),
                'children' => (int)($_POST['children'] ?? 0),
                'infants' => (int)($_POST['infants'] ?? 0),
                'contact_name' => $_POST['contact_name'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_address' => $_POST['contact_address'] ?? ''
            ], 'id = :id', ['id' => $id]);

            // Đồng bộ lại danh sách khách đi cùng theo dữ liệu form edit
            $bookingCustomerModel = new BookingCustomer();
            $userModel = new UserModel();
            $bookingCustomerModel->deleteByBooking($id);
            if (!empty($_POST['companion_name']) && is_array($_POST['companion_name'])) {
                foreach ($_POST['companion_name'] as $index => $name) {
                    if (!empty($name)) {
                        $guestUserId = $_POST['companion_user_id'][$index] ?? null;
                        $createAcc = isset($_POST['companion_create_account'][$index]) && $_POST['companion_create_account'][$index] == '1';
                        $guestEmail = $_POST['companion_email'][$index] ?? '';

                        // Tự động tạo tài khoản tương tự phần store
                        if ($createAcc && empty($guestUserId) && !empty($guestEmail)) {
                            if (!$userModel->emailExists($guestEmail)) {
                                $guestUserId = $userModel->insert([
                                    'full_name' => $name,
                                    'email' => $guestEmail,
                                    'phone' => $_POST['companion_phone'][$index] ?? '',
                                    'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                                    'role' => 'customer'
                                ]);
                            } else {
                                $existingUser = $userModel->select('user_id', 'email = :email', ['email' => $guestEmail]);
                                if (!empty($existingUser)) {
                                    $guestUserId = $existingUser[0]['user_id'];
                                }
                            }
                        }

                        $bookingCustomerData = [
                            'booking_id' => $id,
                            'user_id' => !empty($guestUserId) ? $guestUserId : null,
                            'full_name' => $name,
                            'email' => $guestEmail,
                            'passenger_type' => $_POST['companion_passenger_type'][$index] ?? 'adult',
                            'is_foc' => (isset($_POST['companion_is_foc'][$index]) && $_POST['companion_is_foc'][$index] == '1') ? 1 : 0,
                            'gender' => $_POST['companion_gender'][$index] ?? '',
                            'birth_date' => $this->normalizeDateInput($_POST['companion_birth_date'][$index] ?? null),
                            'phone' => $_POST['companion_phone'][$index] ?? '',
                            'address' => $_POST['companion_address'][$index] ?? '',
                            'id_card' => $_POST['companion_id_card'][$index] ?? '',
                            'special_request' => $_POST['companion_special_request'][$index] ?? '',
                            'room_type' => $_POST['companion_room_type'][$index] ?? ''
                        ];

                        $bookingCustomerModel->insert($bookingCustomerData);

                        // Đồng bộ ngược lại bảng users nếu có user_id
                        if (!empty($guestUserId)) {
                            $userModel->update([
                                'full_name' => $bookingCustomerData['full_name'],
                                'phone' => $bookingCustomerData['phone']
                            ], 'user_id = :uid', ['uid' => $guestUserId]);
                        }
                    }
                }
            }


            // Tự động đồng bộ số lượng chỗ sau khi update
            if (!empty($_POST['departure_id'])) {
                require_once 'models/TourDeparture.php';
                $departureModel = new TourDeparture();
                $departureModel->syncBookedSeats($_POST['departure_id']);
            }

            $this->model->commit();
            $_SESSION['success'] = 'Cập nhật đơn đặt tour thành công';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
            exit;
        } catch (Exception $e) {
            $this->model->rollBack();
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location:' . BASE_URL_ADMIN . '&action=bookings/edit&id=' . $id);
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Kiểm tra quyền - chỉ admin mới được xóa
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Chỉ admin mới có quyền xóa booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        try {
            $booking = $this->model->getById($id);
            if (!$booking) {
                $_SESSION['error'] = 'Không tìm thấy booking';
                header('Location:' . BASE_URL_ADMIN . '&action=bookings');
                exit;
            }

            if (($booking['status'] ?? '') === 'hoan_tat') {
                $_SESSION['error'] = 'Booking đã hoàn tất, không thể xóa';
                header('Location:' . BASE_URL_ADMIN . '&action=bookings/detail&id=' . $id);
                exit;
            }

            $result = $this->model->deleteBooking($id);

            if ($result) {
                // Phase 7: Trả lại số chỗ
                if ($booking && !empty($booking['departure_id'])) {
                    require_once 'models/TourDeparture.php';
                    $departureModel = new TourDeparture();
                    $departureModel->syncBookedSeats($booking['departure_id']);
                }
                $_SESSION['success'] = 'Xóa booking thành công';
            } else {
                $_SESSION['error'] = 'Không thể xóa booking';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        header('Location:' . BASE_URL_ADMIN . '&action=bookings');
        exit;
    }

    public function invoice()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Lấy thông tin chi tiết booking
        $booking = $this->model->getBookingWithDetails($id);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy dữ liệu booking';
            header('Location:' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        // Lấy danh sách khách đi cùng
        require_once 'models/BookingCustomer.php';
        $bcModel = new BookingCustomer();
        $customers = $bcModel->getByBooking($id);

        // Lấy thông tin thanh toán (Lịch sử thanh toán)
        require_once 'models/Payment.php';
        $paymentModel = new Payment();
        $payments = $paymentModel->select('*', 'booking_id = :bid', ['bid' => $id], 'payment_date DESC');

        require_once PATH_VIEW_ADMIN . 'pages/bookings/invoice.php';
    }

    /**
     * AJAX endpoint để cập nhật trạng thái booking
     */
    public function updateStatus()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        $paidAmountInput = $_POST['paid_amount'] ?? null;

        if (!$bookingId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền cập nhật booking này']);
            exit;
        }

        // Validate status
        $validStatuses = ['pending', 'cho_xac_nhan', 'da_coc', 'da_thanh_toan', 'hoan_tat', 'da_huy', 'expired'];
        if (!in_array($newStatus, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
            exit;
        }

        // Cập nhật trạng thái
        $currentBooking = $this->model->getById($bookingId);
        if (($currentBooking['status'] ?? '') === Booking::STATUS_COMPLETED && $newStatus !== Booking::STATUS_COMPLETED) {
            echo json_encode(['success' => false, 'message' => 'Booking đã hoàn tất, không thể đổi trạng thái khác']);
            exit;
        }

        $result = $this->model->updateStatus($bookingId, $newStatus);

        if ($result) {
            // Ghi nhận giao dịch nếu là trạng thái thanh toán
            if (in_array($newStatus, [Booking::STATUS_DEPOSITED, Booking::STATUS_PAID], true) && $paidAmountInput !== null && $paidAmountInput !== '') {
                $amount = (float)str_replace([',', ' '], ['', ''], (string)$paidAmountInput);
                if ($amount > 0) {
                    require_once 'models/Transaction.php';
                    $transactionModel = new Transaction();
                    $transactionModel->recordBookingPayment(
                        $bookingId,
                        $amount,
                        'admin_manual',
                        "Ghi nhận thanh toán thủ công khi chuyển trạng thái sang " . $newStatus
                    );
                }
            }

            // Đồng bộ chỗ ngồi
            if (!empty($currentBooking['departure_id'])) {
                $this->model->syncBookedSeats((int)$currentBooking['departure_id']);
            }

            // Lấy tên trạng thái để hiển thị
            $statusNames = [
                'pending' => 'Chờ thanh toán',
                'cho_xac_nhan' => 'Chờ xác nhận',
                'da_coc' => 'Đã cọc',
                'da_thanh_toan' => 'Đã thanh toán',
                'hoan_tat' => 'Hoàn tất',
                'da_huy' => 'Hủy',
                'expired' => 'Hết hạn'
            ];

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'status' => $newStatus,
                'status_text' => $statusNames[$newStatus] ?? $newStatus,
                'paid_amount' => $paidAmount,
                'paid_amount_formatted' => number_format($paidAmount, 0, ',', '.')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái']);
        }
        exit;
    }
    public function addCompanion()
    {
        header('Content-Type: application/json');
        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin booking']);
            exit;
        }

        $booking = $this->model->getById($bookingId);
        if (($booking['status'] ?? '') === 'hoan_tat') {
            echo json_encode(['success' => false, 'message' => 'Booking đã hoàn tất, không thể thêm khách']);
            exit;
        }

        // Kiểm tra quyền sửa booking này
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thêm khách cho booking này']);
            exit;
        }
        // Lấy dữ liệu từ form
        $data = [
            'booking_id' => $bookingId,
            'full_name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_date' => $this->normalizeDateInput($_POST['birth_date'] ?? null),
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'] ?? null,
            'id_card' => $_POST['id_card'] ?? null,
            'room_type' => $_POST['room_type'] ?? null,
            'passenger_type' => $_POST['passenger_type'] ?? 'adult',
            'is_foc' => (isset($_POST['is_foc']) && ($_POST['is_foc'] == '1' || $_POST['is_foc'] == 'on')) ? 1 : 0,
            'special_request' => $_POST['special_request'] ?? null
        ];
        // Validate
        if (empty($data['full_name'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập họ tên khách']);
            exit;
        }
        // Thêm vào database
        try {
            $companionModel = new BookingCustomer();
            $companionId = $companionModel->insert($data);

            echo json_encode([
                'success' => true,
                'message' => 'Thêm khách đi kèm thành công',
                'companion_id' => $companionId,
                'companion' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Cập nhật thông tin khách đi kèm
     * AJAX endpoint
     */
    public function updateCompanion()
    {
        header('Content-Type: application/json');
        $companionId = $_POST['companion_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$companionId || !$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $booking = $this->model->getById($bookingId);
        if (($booking['status'] ?? '') === 'hoan_tat') {
            echo json_encode(['success' => false, 'message' => 'Booking đã hoàn tất, không thể chỉnh sửa khách']);
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền sửa khách này']);
            exit;
        }
        // Lấy dữ liệu từ form
        $data = [
            'full_name' => $_POST['name'] ?? '',
            'gender' => $_POST['gender'] ?? null,
            'birth_date' => $this->normalizeDateInput($_POST['birth_date'] ?? null),
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'] ?? null,
            'id_card' => $_POST['id_card'] ?? null,
            'room_type' => $_POST['room_type'] ?? null,
            'passenger_type' => $_POST['passenger_type'] ?? 'adult',
            'is_foc' => (isset($_POST['is_foc']) && ($_POST['is_foc'] == '1' || $_POST['is_foc'] == 'on')) ? 1 : 0,
            'special_request' => $_POST['special_request'] ?? null
        ];

        // Validate
        if (empty($data['full_name'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập họ tên khách']);
            exit;
        }
        // Cập nhật database
        try {
            $companionModel = new BookingCustomer();
            
            // Lấy thông tin hiện tại để kiểm tra user_id
            $existingCompanion = $companionModel->find('*', 'id = :id', ['id' => $companionId]);
            $guestUserId = $existingCompanion['user_id'] ?? null;

            $companionModel->update($data, 'id = :id', ['id' => $companionId]);

            // Đồng bộ ngược lại bảng users nếu có user_id
            if (!empty($guestUserId)) {
                require_once 'models/UserModel.php';
                $userModel = new UserModel();
                $userModel->update([
                    'full_name' => $data['full_name'],
                    'phone' => $data['phone']
                ], 'user_id = :uid', ['uid' => $guestUserId]);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật thông tin khách thành công',
                'companion' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    /**
     * Xóa khách đi kèm
     * AJAX endpoint
     */
    public function deleteCompanion()
    {
        header('Content-Type: application/json');
        $companionId = $_POST['companion_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$companionId || !$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $booking = $this->model->getById($bookingId);
        if (($booking['status'] ?? '') === 'hoan_tat') {
            echo json_encode(['success' => false, 'message' => 'Booking đã hoàn tất, không thể xóa khách']);
            exit;
        }

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;
        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xóa khách này']);
            exit;
        }
        // Xóa khỏi database
        try {
            $companionModel = new BookingCustomer();
            // FIX: Sử dụng đúng cú pháp delete với WHERE clause
            $companionModel->delete('id = :id', ['id' => $companionId]);

            echo json_encode([
                'success' => true,
                'message' => 'Xóa khách đi kèm thành công'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Cập nhật yêu cầu đặc biệt (dành cho HDV)
     * AJAX endpoint
     */
    public function updateSpecialRequest()
    {
        header('Content-Type: application/json');

        $companionId = $_POST['companion_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        $specialRequest = $_POST['special_request'] ?? '';

        if (!$companionId || !$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }

        $booking = $this->model->getById($bookingId);
        if (($booking['status'] ?? '') === 'hoan_tat') {
            echo json_encode(['success' => false, 'message' => 'Booking đã hoàn tất, không thể cập nhật yêu cầu']);
            exit;
        }

        // Kiểm tra quyền - HDV chỉ được sửa booking được phân công
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!$this->model->canUserEditBooking($bookingId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền cập nhật yêu cầu này']);
            exit;
        }

        // Cập nhật special_request
        try {
            $companionModel = new BookingCustomer();
            $companionModel->update([
                'special_request' => $specialRequest
            ], 'id = :id', ['id' => $companionId]);

            echo json_encode([
                'success' => true,
                'message' => 'Cập nhật yêu cầu đặc biệt thành công'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Hiển thị trang check-in
     */
    public function checkin()
    {
        $bookingId = $_GET['id'] ?? null;

        if (!$bookingId) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin booking
        $booking = $this->model->getById($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            return;
        }

        // Lấy thông tin tour
        $tourModel = new Tour();
        $tour = $tourModel->find('*', 'id = :id', ['id' => $booking['tour_id']]);

        // Lấy danh sách khách
        $customerModel = new BookingCustomer();
        $customers = $customerModel->getCustomersWithCheckinStatus($bookingId);
        $stats = $customerModel->getCheckinStats($bookingId);

        require_once PATH_VIEW_ADMIN . 'pages/bookings/checkin.php';
    }

    /**
     * Cập nhật trạng thái check-in (AJAX)
     */
    public function updateCheckin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            exit;
        }

        $customerId = $_POST['customer_id'] ?? null;
        $status = $_POST['status'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (!$customerId || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        // Validate status
        if (!in_array($status, ['not_arrived', 'checked_in', 'absent'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit;
        }


        try {
            $userId = $_SESSION['user']['user_id'] ?? null;
            $customerModel = new BookingCustomer();

            // Check if this is a main customer (ID starts with 'main_')
            if (strpos($customerId, 'main_') === 0) {
                // Extract booking_id from 'main_42' -> 42
                $bookingId = (int)str_replace('main_', '', $customerId);

                // Get booking info with customer_name
                $sql = "SELECT 
                            B.*,
                            U.full_name AS customer_name
                        FROM bookings AS B
                        LEFT JOIN users AS U ON B.customer_id = U.user_id
                        WHERE B.id = :booking_id";

                $pdo = BaseModel::getPdo();
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['booking_id' => $bookingId]);
                $booking = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$booking) {
                    echo json_encode(['success' => false, 'message' => 'Không tìm thấy booking']);
                    exit;
                }

                // Check if main customer record already exists
                $sql = "SELECT id FROM booking_customers 
                        WHERE booking_id = :booking_id 
                        AND full_name = :full_name 
                        LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'booking_id' => $bookingId,
                    'full_name' => $booking['customer_name']
                ]);
                $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingCustomer) {
                    // Update existing record
                    $result = $customerModel->updateCheckinStatus(
                        $existingCustomer['id'],
                        $status,
                        $userId,
                        $notes
                    );

                    if ($result) {
                        $timestamp = ($status === 'checked_in') ? date('H:i d/m/Y') : null;
                        echo json_encode([
                            'success' => true,
                            'message' => 'Cập nhật thành công',
                            'timestamp' => $timestamp
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
                    }
                } else {
                    // Create new booking_customer record for main customer
                    $customerData = [
                        'booking_id' => $bookingId,
                        'full_name' => $booking['customer_name'] ?? 'N/A',
                        'passenger_type' => 'adult',
                        'is_foc' => 0,
                        'checkin_status' => $status,
                        'checked_by' => $userId,
                        'checkin_time' => ($status !== 'not_arrived') ? date('Y-m-d H:i:s') : null,
                        'checkin_notes' => $notes
                    ];

                    $newCustomerId = $customerModel->insert($customerData);

                    if ($newCustomerId) {
                        $timestamp = ($status === 'checked_in') ? date('H:i d/m/Y') : null;
                        echo json_encode([
                            'success' => true,
                            'message' => 'Cập nhật thành công',
                            'timestamp' => $timestamp
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Không thể tạo record']);
                    }
                }
            } else {
                // Regular customer - update existing record
                $result = $customerModel->updateCheckinStatus(
                    $customerId,
                    $status,
                    $userId,
                    $notes
                );

                if ($result) {
                    $timestamp = ($status === 'checked_in') ? date('H:i d/m/Y') : null;
                    echo json_encode([
                        'success' => true,
                        'message' => 'Cập nhật thành công',
                        'timestamp' => $timestamp
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
                }
            }
        } catch (Exception $e) {
            error_log('Check-in error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Check-in hàng loạt
     */
    public function bulkCheckin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }

        $customerIds = $_POST['customer_ids'] ?? [];
        $status = $_POST['status'] ?? 'checked_in';

        if (empty($customerIds)) {
            echo json_encode(['success' => false, 'message' => 'Chưa chọn khách']);
            return;
        }

        try {
            $userId = $_SESSION['user']['user_id'] ?? null;
            $customerModel = new BookingCustomer();
            $count = 0;

            foreach ($customerIds as $customerId) {
                if ($customerModel->updateCheckinStatus($customerId, $status, $userId)) {
                    $count++;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => "Đã check-in {$count} khách",
                'count' => $count
            ]);
        } catch (Exception $e) {
            error_log('Bulk check-in error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
        }
    }

    /**
     * In danh sách đoàn
     * Hỗ trợ 2 mode:
     *   - ?departure_id=X  → In danh sách toàn bộ đoàn của 1 chuyến khởi hành
     *   - ?id=X            → In từ booking đơn lẻ cụ thể
     */
    public function printGroupList()
    {
        $departureId = $_GET['departure_id'] ?? null;
        $bookingId   = $_GET['id'] ?? null;
        $data = $this->buildGroupListData($departureId, $bookingId);
        if (!$data) {
            return;
        }
        extract($data);
        require_once PATH_VIEW_ADMIN . 'pages/bookings/print-group-list.php';
    }

    public function groupCheckin()
    {
        $departureId = $_GET['departure_id'] ?? null;
        $bookingId   = $_GET['id'] ?? null;
        $data = $this->buildGroupListData($departureId, $bookingId);
        if (!$data) {
            return;
        }
        extract($data);
        require_once PATH_VIEW_ADMIN . 'pages/bookings/group-checkin.php';
    }

    private function buildGroupListData($departureId = null, $bookingId = null)
    {
        $pdo = $this->model->getPdo();

        // ── MODE 1: Từ trang Vận hành đoàn → theo departure_id ──
        if ($departureId) {
            // Lấy thông tin departure + tour
            $stmt = $pdo->prepare("
                SELECT td.*, t.name as tour_name, t.id as the_tour_id
                FROM tour_departures td
                JOIN tours t ON td.tour_id = t.id
                WHERE td.id = :dep_id
            ");
            $stmt->execute([':dep_id' => $departureId]);
            $departure = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$departure) {
                $_SESSION['error'] = 'Không tìm thấy chuyến khởi hành';
                header('Location: ' . BASE_URL_ADMIN . '&action=tours/departures');
                return null;
            }

            $tour = ['id' => $departure['the_tour_id'], 'name' => $departure['tour_name']];

            // Lấy TẤT CẢ booking của chuyến này (không hủy / expired)
            $stmt = $pdo->prepare("
                SELECT b.*, 
                    COALESCE(u.full_name, b.contact_name) as full_name,
                    COALESCE(u.email, b.contact_email) as email,
                    COALESCE(u.phone, b.contact_phone) as phone
                FROM bookings b
                LEFT JOIN users u ON b.customer_id = u.user_id
                WHERE b.departure_id = :dep_id
                AND b.status NOT IN ('da_huy', 'expired', 'cancelled')
                ORDER BY b.status DESC, b.id ASC
            ");
            $stmt->execute([':dep_id' => $departureId]);
            $allBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Dùng thông tin departure làm header
            $booking = [
                'id' => 0,
                'tour_id' => $departure['the_tour_id'],
                'departure_date' => $departure['departure_date'],
                'departure_id' => $departureId,
            ];

        } else {
            // ── MODE 2: Từ booking đơn lẻ → theo booking_id ──
            if (!$bookingId) {
                $_SESSION['error'] = 'Không tìm thấy booking';
                header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
                return null;
            }

            $booking = $this->model->getById($bookingId);
            if (!$booking) {
                $_SESSION['error'] = 'Booking không tồn tại';
                header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
                return null;
            }

            $tourModel = new Tour();
            $tour = $tourModel->find('*', 'id = :id', ['id' => $booking['tour_id']]);

            // Lấy TẤT CẢ booking của tour này đã xác nhận thanh toán
            $stmt = $pdo->prepare("
                SELECT b.*, COALESCE(u.full_name, b.contact_name) as full_name,
                    COALESCE(u.email, b.contact_email) as email,
                    COALESCE(u.phone, b.contact_phone) as phone
                FROM bookings b
                LEFT JOIN users u ON b.customer_id = u.user_id
                WHERE b.tour_id = :tour_id 
                AND b.status IN ('da_coc', 'da_thanh_toan')
            ");
            $stmt->execute([':tour_id' => $booking['tour_id']]);
            $allBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Tổng hợp danh sách khách từ tất cả booking
        $customerModel = new BookingCustomer();
        $customers = [];

        foreach ($allBookings as $bk) {
            $bookingCustomers = $customerModel->getByBooking($bk['id']);

            $mainCustomerExists = false;
            foreach ($bookingCustomers as $customer) {
                if ($customer['full_name'] === $bk['full_name']) {
                    $mainCustomerExists = true;
                    break;
                }
            }

            if (!empty($bk['full_name']) && !$mainCustomerExists) {
                $customers[] = [
                    'full_name'             => $bk['full_name'],
                    'gender'                => '',
                    'birth_date'            => '',
                    'id_card'               => '',
                    'passenger_type'        => 'adult',
                    'room_type'             => '',
                    'special_request'       => '',
                    'is_foc'                => 0,
                    'booking_code'          => $bk['id'],
                    'booking_customer_name' => $bk['full_name'],
                    'booking_status'        => $bk['status'],
                ];
            }

            foreach ($bookingCustomers as $customer) {
                $customer['booking_code']          = $bk['id'];
                $customer['booking_customer_name'] = $bk['full_name'] ?? 'N/A';
                $customer['booking_status']        = $bk['status'];
                $customers[] = $customer;
            }
        }

        $stats = ['adults' => 0, 'children' => 0, 'infants' => 0, 'total' => count($customers), 'checked_in' => 0, 'not_arrived' => 0, 'absent' => 0];
        foreach ($customers as $customer) {
            $type = $customer['passenger_type'] ?? 'adult';
            if ($type === 'adult') $stats['adults']++;
            elseif ($type === 'child') $stats['children']++;
            elseif ($type === 'infant') $stats['infants']++;

            $customer['checkin_status'] = $customer['checkin_status'] ?? 'not_arrived';
            if ($customer['checkin_status'] === 'checked_in') $stats['checked_in']++;
            elseif ($customer['checkin_status'] === 'absent') $stats['absent']++;
            else $stats['not_arrived']++;
        }
        return compact('booking', 'tour', 'customers', 'stats');
    }

    /**
     * AJAX endpoint to get departures by tour ID
     */
    public function getDeparturesByTour()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $tourId = $_GET['tour_id'] ?? null;
        $includeId = $_GET['include_id'] ?? null;

        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Tour ID is required']);
            exit;
        }

        try {
            $departureModel = new TourDeparture();
            $departures = $departureModel->getByTourId($tourId, $includeId);

            // Get tour base price for fallback
            $tourModel = new Tour();
            $tour = $tourModel->find('*', 'id = :id', ['id' => $tourId]);
            $basePrice = $tour['base_price'] ?? 0;

            // Format departures for response
            $formattedDepartures = array_map(function ($dep) use ($basePrice) {
                $pAdult = (float)($dep['price_adult'] ?: $basePrice);
                return [
                    'id' => $dep['id'],
                    'departure_date' => $dep['departure_date'],
                    'formatted_date' => date('d/m/Y', strtotime($dep['departure_date'])),
                    'price_adult' => $pAdult,
                    'price_child' => (float)($dep['price_child'] ?: ($pAdult * 0.75)), // Mặc định 75% nếu trống
                    'price_infant' => (float)($dep['price_infant'] ?: 0),
                    'available_seats' => (int)$dep['available_seats'],
                    'max_seats' => (int)$dep['max_seats']
                ];
            }, $departures);

            echo json_encode([
                'success' => true,
                'departures' => $formattedDepartures,
                'base_price' => $basePrice
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching departures: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Giao diện phân bổ phòng cho đoàn (US37)
     */
    public function allocateRooms()
    {
        $bookingId = $_GET['id'] ?? null;
        if (!$bookingId) {
            header('Location: ' . BASE_URL_ADMIN . '&action=bookings');
            exit;
        }

        $booking = $this->model->getBookingWithDetails($bookingId);
        $customerModel = new BookingCustomer();
        $customers = $customerModel->getByBooking($bookingId);

        require_once PATH_VIEW_ADMIN . 'pages/bookings/room_allocation.php';
    }

    /**
     * Lưu phân bổ phòng (AJAX)
     */
    public function saveRoomAllocation()
    {
        header('Content-Type: application/json');
        $allocations = $_POST['allocations'] ?? [];

        if (empty($allocations)) {
            echo json_encode(['success' => false, 'message' => 'Không có dữ liệu để lưu']);
            exit;
        }

        try {
            $customerModel = new BookingCustomer();
            foreach ($allocations as $id => $room) {
                $customerModel->update(['room_number' => $room], 'id = :id', ['id' => $id]);
            }
            echo json_encode(['success' => true, 'message' => 'Lưu phân bổ phòng thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    private function normalizeDateInput($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y'];
        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $value);
            if ($dt instanceof DateTime && $dt->format($format) === $value) {
                return $dt->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    /**
     * Xuất hóa đơn / Phiếu xác nhận (Phần của Phase 7 Audit)
     */
    public function exportInvoice()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("Thiếu ID booking");
        }

        $booking = $this->model->getBookingWithDetails($id);
        if (!$booking) {
            die("Không tìm thấy booking");
        }

        // Lấy danh sách khách đi kèm
        $bookingCustomerModel = new BookingCustomer();
        $companions = $bookingCustomerModel->getByBooking($id);

        // Hiển thị view hóa đơn (optimized for print)
        require_once PATH_VIEW_ADMIN . 'pages/bookings/invoice_print.php';
    }
}
