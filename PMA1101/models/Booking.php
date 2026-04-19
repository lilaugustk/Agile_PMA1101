<?php
// require_once 'models/BaseModel.php';

class Booking extends BaseModel
{
    const STATUS_PENDING       = 'pending';
    const STATUS_WAITING       = 'cho_xac_nhan';
    const STATUS_DEPOSITED     = 'da_coc';
    const STATUS_PAID          = 'da_thanh_toan';
    const STATUS_COMPLETED     = 'hoan_tat';
    const STATUS_CANCELLED     = 'da_huy';
    const STATUS_EXPIRED       = 'expired';
    const STATUS_OPERATING     = 'dang_dien_ra';

    protected $table = 'bookings';
    protected $columns = [
        'id',
        'tour_id',
        'departure_id',
        'original_price',
        'final_price',
        'total_price',
        'status',
        'booking_date',
        'departure_date',
        'adults',
        'children',
        'infants',
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_address',
        'notes',
        'discount_note',
        'expires_at',
        'payment_proof',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function applyOperationalStatus(array $booking): array
    {
        $baseStatus = $booking['status'] ?? '';
        $booking['operational_status'] = $baseStatus;

        // Trạng thái vận hành "Đang diễn ra" dựa trên ngày khởi hành và gán HDV
        if (in_array($baseStatus, [self::STATUS_DEPOSITED, self::STATUS_PAID], true) && !empty($booking['departure_date'])) {
            $startDate = $booking['assignment_start_date'] ?? $booking['departure_date'];
            $endDate = $booking['assignment_end_date'] ?? $startDate;
            $today = date('Y-m-d');

            if ($today >= $startDate && $today <= $endDate) {
                $booking['operational_status'] = self::STATUS_OPERATING;
            }
        }

        return $booking;
    }

    /**
     * Lấy danh sách booking với bộ lọc và phân trang chuyên nghiệp
     */
    public function getAllBookings($page = 1, $perPage = 15, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereConditions = ["1=1"];

        // Bộ lọc từ khóa (Tìm theo Mã, Tên khách, Tên tour)
        if (!empty($filters['keyword'])) {
            $whereConditions[] = "(B.id LIKE :keyword OR B.contact_name LIKE :keyword OR U.full_name LIKE :keyword OR T.name LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        // Bộ lọc trạng thái
        if (!empty($filters['status'])) {
            if ($filters['status'] === self::STATUS_OPERATING) {
                $whereConditions[] = "B.status IN ('" . self::STATUS_DEPOSITED . "', '" . self::STATUS_PAID . "')";
                $whereConditions[] = "DATE(CURDATE()) >= DATE(B.departure_date)";
                $whereConditions[] = "DATE(CURDATE()) <= DATE(COALESCE((
                    SELECT MAX(TA2.end_date)
                    FROM tour_assignments TA2
                    WHERE TA2.tour_id = B.tour_id
                      AND TA2.start_date = B.departure_date
                ), B.departure_date))";
            } else {
                $whereConditions[] = "B.status = :status";
                $params[':status'] = $filters['status'];
            }
        }

        // Bộ lọc ngày đặt (Từ ngày)
        if (!empty($filters['date_from'])) {
            $whereConditions[] = "DATE(B.booking_date) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }

        // Bộ lọc ngày đặt (Đến ngày)
        if (!empty($filters['date_to'])) {
            $whereConditions[] = "DATE(B.booking_date) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        // Bộ lọc danh mục tour
        if (!empty($filters['category_id'])) {
            $whereConditions[] = "T.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        // Bộ lọc giá (Từ)
        if (!empty($filters['price_min'])) {
            $whereConditions[] = "B.final_price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }

        // Bộ lọc giá (Đến)
        if (!empty($filters['price_max'])) {
            $whereConditions[] = "B.final_price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }

        // Phân quyền cho HDV (nếu có)
        if (isset($filters['guide_id']) && !empty($filters['guide_id'])) {
            $whereConditions[] = "EXISTS (SELECT 1 FROM tour_assignments TA WHERE TA.tour_id = B.tour_id AND TA.guide_id = :guide_id)";
            $params[':guide_id'] = $filters['guide_id'];
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Đếm tổng số bản ghi
        $countSql = "SELECT COUNT(*) FROM bookings AS B 
                     LEFT JOIN tours AS T ON B.tour_id = T.id
                     LEFT JOIN users AS U ON B.customer_id = U.user_id
                     WHERE $whereClause";
        $countStmt = self::$pdo->prepare($countSql);
        foreach ($params as $key => $val) {
            $countStmt->bindValue($key, $val);
        }
        $countStmt->execute();
        $totalItems = (int)$countStmt->fetchColumn();

        // Xử lý sắp xếp
        $sortDir = strtoupper($filters['sort_dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        $orderBy = "B.booking_date $sortDir, B.id $sortDir";
        
        if (!empty($filters['sort_by'])) {
            if ($filters['sort_by'] === 'total_price') {
                $orderBy = "B.final_price $sortDir";
            } elseif ($filters['sort_by'] === 'booking_date') {
                $orderBy = "B.booking_date $sortDir";
            } elseif ($filters['sort_by'] === 'customer') {
                $orderBy = "customer_name $sortDir";
            } elseif ($filters['sort_by'] === 'tour') {
                $orderBy = "tour_name $sortDir";
            }
        }

        // Truy vấn dữ liệu chính
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name, 
                    COALESCE(U.full_name, B.contact_name) AS customer_name,
                    TA.start_date AS assignment_start_date,
                    TA.end_date AS assignment_end_date
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                LEFT JOIN tour_assignments AS TA 
                    ON TA.tour_id = B.tour_id 
                    AND TA.start_date = B.departure_date
                WHERE $whereClause
                ORDER BY $orderBy
                LIMIT :limit OFFSET :offset";

        $stmt = self::$pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row = $this->applyOperationalStatus($row);
        }

        return [
            'data' => $rows,
            'total' => $totalItems,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalItems / $perPage)
        ];
    }

    public function getMonthlyRevenue($month, $year)
    {
        $sql = "SELECT SUM(final_price) as revenue FROM {$this->table} 
                WHERE MONTH(booking_date) = :month 
                AND YEAR(booking_date) = :year 
                AND status IN ('da_coc', 'da_thanh_toan', 'completed', 'paid')";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($data['revenue'] ?? 0);
    }

    /**
     * Lấy thống kê trạng thái booking
     * @return array
     */
    public function getBookingStatusStats()
    {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count,
                    SUM(CASE WHEN status IN ('completed', 'hoan_tat', 'paid', 'da_thanh_toan') THEN final_price ELSE 0 END) as total_revenue
                FROM {$this->table}
                WHERE 1
                GROUP BY status";

        $stmt = self::$pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mapping = [
            self::STATUS_PENDING      => self::STATUS_PENDING,
            self::STATUS_WAITING      => self::STATUS_WAITING,
            self::STATUS_DEPOSITED    => self::STATUS_DEPOSITED,
            self::STATUS_PAID         => self::STATUS_PAID,
            self::STATUS_COMPLETED    => self::STATUS_COMPLETED,
            self::STATUS_CANCELLED    => self::STATUS_CANCELLED,
            self::STATUS_EXPIRED      => self::STATUS_EXPIRED,
            'confirmed'               => self::STATUS_DEPOSITED,
            'paid'                    => self::STATUS_PAID,
            'completed'               => self::STATUS_COMPLETED,
            'cancelled'               => self::STATUS_CANCELLED
        ];

        $statusLabels = [
            self::STATUS_PENDING      => 'Chờ thanh toán',
            self::STATUS_WAITING      => 'Chờ xác nhận',
            self::STATUS_DEPOSITED    => 'Đã cọc',
            self::STATUS_PAID         => 'Đã thanh toán',
            self::STATUS_COMPLETED    => 'Hoàn tất',
            self::STATUS_CANCELLED    => 'Đã hủy',
            self::STATUS_EXPIRED      => 'Hết hạn'
        ];

        $unifiedStats = [];
        foreach ($results as $row) {
            $key = $mapping[$row['status']] ?? $row['status'];
            if (!isset($unifiedStats[$key])) {
                $unifiedStats[$key] = [
                    'status' => $statusLabels[$key] ?? ucfirst($key),
                    'count' => 0,
                    'revenue' => 0
                ];
            }
            $unifiedStats[$key]['count'] += (int)$row['count'];
            $unifiedStats[$key]['revenue'] += (float)$row['total_revenue'];
        }

        $totalBookings = array_sum(array_column($unifiedStats, 'count'));
        $totalRevenue = array_sum(array_column($unifiedStats, 'revenue'));

        $statsArray = [];
        foreach ($unifiedStats as $key => $item) {
            $statsArray[] = [
                'status' => $item['status'],
                'count' => $item['count'],
                'percentage' => $totalBookings > 0 ? round(($item['count'] / $totalBookings) * 100, 1) : 0,
                'revenue' => $item['revenue']
            ];
        }

        // Sort by count descending
        usort($statsArray, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return [
            'stats' => $statsArray,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue
        ];
    }

    public function getNewBookingsThisMonth($month, $year)
    {
        $conditions = "MONTH(booking_date) = :month AND YEAR(booking_date) = :year";
        return $this->count($conditions, ['month' => $month, 'year' => $year]);
    }

    public function getNewCustomersThisMonth($month, $year)
    {
        $sql = "SELECT COUNT(DISTINCT BC.full_name) as count 
                FROM bookings B 
                LEFT JOIN booking_customers BC ON B.id = BC.booking_id 
                WHERE MONTH(B.booking_date) = :month AND YEAR(B.booking_date) = :year";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);
        $data = $stmt->fetch();
        return $data['count'] ?? 0;
    }

    /**
     * Lấy các booking đang chờ xác nhận gần đây
     * @param int $limit
     * @return array
     */
    public function getRecentPendingBookings($limit = 5)
    {
        $sql = "SELECT 
                    B.id,
                    B.booking_date,
                    B.status,
                    T.name AS tour_name, 
                    COALESCE(U.full_name, MIN(BC.full_name), 'Khách lẻ') AS customer_name
                FROM {$this->table} AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                LEFT JOIN booking_customers AS BC ON B.id = BC.booking_id
                WHERE B.status = 'cho_xac_nhan'
                GROUP BY B.id, B.booking_date, B.status, T.name
                ORDER BY B.booking_date DESC, B.id DESC
                LIMIT " . (int)$limit;
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin booking theo ID
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Lấy booking theo mã (BK000001 -> id=1)
     */
    public function getByCode($code)
    {
        $id = intval(substr($code, 2));
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Lấy thông tin booking đầy đủ cho client (join tour + departure)
     */
    public function getDetailForClient($bookingId)
    {
        $sql = "SELECT 
                    B.*,
                    T.name AS tour_name,
                    T.base_price AS tour_base_price,
                    TD.departure_date AS dep_departure_date,
                    TD.price_adult,
                    TD.price_child,
                    TD.max_seats,
                    TD.booked_seats,
                    (SELECT image_url FROM tour_gallery_images WHERE tour_id = T.id AND main_img = 1 LIMIT 1) AS tour_main_image,
                    (SELECT image_url FROM tour_gallery_images WHERE tour_id = T.id LIMIT 1) AS tour_first_image
                FROM bookings AS B
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN tour_departures AS TD ON B.departure_id = TD.id
                WHERE B.id = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Chuyển booking pending hết hạn sang expired.
     * booked_seats được tính qua sync theo trạng thái, không trừ thủ công tại đây.
     */
    public function cleanupExpiredPending()
    {
        try {
            // Lấy danh sách booking hết hạn
            $sql = "SELECT id, departure_id 
                    FROM bookings
                    WHERE status = :pending
                    AND expires_at IS NOT NULL
                    AND expires_at < NOW()";
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute([':pending' => self::STATUS_PENDING]);
            $expired = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($expired)) return;

            foreach ($expired as $row) {
                // Cập nhật booking thành expired
                $this->update(
                    ['status' => self::STATUS_EXPIRED, 'expires_at' => null],
                    'id = :id',
                    ['id' => $row['id']]
                );
                
                // Nếu là đơn đã xác nhận/đã giữ chỗ mà bị hết hạn (trường hợp admin set expires_at)
                // Thì sync lại chỗ ngồi. Ở đây pending -> expired thì không cần sync vì pending chưa được tính.
            }
        } catch (Exception $e) {
            error_log('cleanupExpiredPending error: ' . $e->getMessage());
        }
    }

    /**
     * Lấy tất cả bookings của một tour
     * @param int $tourId
     * @return array
     */
    public function getByTourId($tourId)
    {
        $sql = "SELECT 
                    B.*,
                    U.full_name AS customer_name
                FROM bookings AS B
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                WHERE B.tour_id = :tour_id
                AND B.status IN ('cho_xac_nhan', 'da_coc')
                ORDER BY B.id ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả bookings của một khách hàng
     * @param int $customerId
     * @return array
     */
    public function getByCustomerId($customerId)
    {
        $sql = "SELECT B.*, 
                       T.name AS tour_name, 
                       T.base_price AS tour_base_price,
                       TA.start_date AS assignment_start_date,
                       TA.end_date AS assignment_end_date
                FROM {$this->table} AS B
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN tour_assignments AS TA
                    ON TA.tour_id = B.tour_id
                    AND TA.start_date = B.departure_date
                WHERE B.customer_id = :customer_id
                ORDER BY B.booking_date DESC, B.id DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row = $this->applyOperationalStatus($row);
        }
        unset($row);

        return $rows;
    }

    /**
     * Lấy thông tin booking chi tiết kèm tour và customer
     * @param int $id
     * @return array|false
     */
    public function getBookingWithDetails($id)
    {
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name,
                    T.base_price AS tour_base_price,
                    COALESCE(U.full_name, B.contact_name) AS customer_name,
                    COALESCE(U.email, B.contact_email) AS customer_email,
                    COALESCE(U.phone, B.contact_phone) AS customer_phone,
                    TA.start_date AS assignment_start_date,
                    TA.end_date AS assignment_end_date
                FROM bookings AS B 
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                LEFT JOIN tour_assignments AS TA
                    ON TA.tour_id = B.tour_id
                    AND TA.start_date = B.departure_date
                WHERE B.id = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($booking) {
            $booking = $this->applyOperationalStatus($booking);
        }
        return $booking;
    }

    /**
     * Xóa booking và các dữ liệu liên quan
     * @param int $id
     * @return bool
     */
    public function deleteBooking($id)
    {
        try {
            $this->beginTransaction();

            // Xóa booking customers trước
            $bookingCustomerModel = new BookingCustomer();
            $bookingCustomerModel->deleteByBooking($id);

            // Xóa booking
            $this->delete('id = :id', ['id' => $id]);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Kiểm tra user có quyền sửa booking không
     * @param int $bookingId
     * @param int $userId
     * @param string $userRole - 'admin' hoặc 'guide'
     * @return bool
     */
    public function canUserEditBooking($bookingId, $userId, $userRole)
    {
        // Admin có toàn quyền
        if ($userRole === 'admin') {
            return true;
        }

        // HDV chỉ được sửa booking của tour mình phụ trách
        if ($userRole === 'guide') {
            // Lấy thông tin booking
            $booking = $this->getById($bookingId);
            if (!$booking) {
                return false;
            }

            // Lấy guide_id từ user_id
            $guideModel = new Guide();
            $guide = $guideModel->getByUserId($userId);
            if (!$guide) {
                return false;
            }

            // Kiểm tra HDV có phụ trách tour này không
            $assignmentModel = new TourAssignment();
            return $assignmentModel->isGuideAssignedToTour($guide['id'], $booking['tour_id']);
        }

        return false;
    }

    /**
     * Lấy danh sách bookings của tour mà HDV phụ trách
     * @param int $guideId
     * @return array
     */
    public function getBookingsForGuide($guideId)
    {
        $sql = "SELECT 
                    B.*, 
                    T.name AS tour_name, 
                    COALESCE(U.full_name, B.contact_name) AS customer_name
                FROM {$this->table} AS B 
                INNER JOIN tour_assignments AS TA ON B.tour_id = TA.tour_id
                LEFT JOIN tours AS T ON B.tour_id = T.id
                LEFT JOIN users AS U ON B.customer_id = U.user_id
                WHERE TA.guide_id = :guide_id
                ORDER BY B.booking_date DESC, B.id DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllByRole($userRole, $guideId = null, $page = 1, $perPage = 15, $filters = [])
    {
        if ($userRole === 'guide' && $guideId) {
            $filters['guide_id'] = $guideId;
        }
        
        if ($userRole === 'admin' || ($userRole === 'guide' && $guideId)) {
            return $this->getAllBookings($page, $perPage, $filters);
        }

        return ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0];
    }
    /**
     * Cập nhật trạng thái booking
     * @param int $bookingId
     * @param string $newStatus
     * @return bool
     */
    public function updateStatus($bookingId, $newStatus)
    {
        try {
            // Validate status
            $validStatuses = ['pending', 'cho_xac_nhan', 'da_coc', 'da_thanh_toan', 'hoan_tat', 'da_huy', 'expired'];
            if (!in_array($newStatus, $validStatuses)) {
                return false;
            }

            return $this->update(
                ['status' => $newStatus],
                'id = :id',
                ['id' => $bookingId]
            );
        } catch (Exception $e) {
            error_log('Error updating booking status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thống kê booking theo khoảng thời gian
     */
    public function getBookingStats($dateFrom = null, $dateTo = null, $tourId = null, $status = null, $source = null, $skipGrowth = false)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        if ($status) {
            $whereConditions[] = "B.status = :status";
            $params[':status'] = $status;
        }

        if ($source) {
            $whereConditions[] = "B.source = :source";
            $params[':source'] = $source;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        // Tổng booking
        $sql = "SELECT 
                    COUNT(B.id) as total_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'hoan_tat') THEN 1 ELSE 0 END) as successful_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'hoan_tat') THEN B.final_price ELSE 0 END) as total_revenue,
                    COALESCE(SUM(customer_counts.total_customers), 0) as total_customers
                FROM bookings B 
                LEFT JOIN (
                    SELECT 
                        booking_id,
                        COUNT(id) as total_customers
                    FROM booking_customers 
                    GROUP BY booking_id
                ) customer_counts ON B.id = customer_counts.booking_id
                $whereClause";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $stats = $stmt->fetch();

        // Tính tỷ lệ thành công và chuyển đổi
        $totalBookings = $stats['total_bookings'] ?? 0;
        $successfulBookings = $stats['successful_bookings'] ?? 0;
        $totalCustomers = $stats['total_customers'] ?? 0;
        $successRate = $totalBookings > 0 ? ($successfulBookings / $totalBookings) * 100 : 0;
        $avgCustomersPerBooking = $totalBookings > 0 ? $totalCustomers / $totalBookings : 0;

        // Tỷ lệ chuyển đổi (ước tính từ pending -> successful)
        $pendingBookings = $this->count(
            "booking_date BETWEEN :date_from AND :date_to AND status = 'pending'",
            [':date_from' => $dateFrom, ':date_to' => $dateTo]
        );
        $conversionRate = $pendingBookings > 0 ? ($successfulBookings / ($pendingBookings + $successfulBookings)) * 100 : 0;

        // Lấy dữ liệu kỳ trước để tính growth (chỉ khi không skip)
        $bookingGrowth = 0;
        if (!$skipGrowth) {
            $previousStats = $this->getPreviousPeriodStats($dateFrom, $dateTo, $tourId, $status, $source);
            $bookingGrowth = $this->calculateGrowth($totalBookings, $previousStats['total_bookings']);
        }

        return [
            'total_bookings' => $totalBookings,
            'successful_bookings' => $successfulBookings,
            'success_rate' => $successRate,
            'conversion_rate' => $conversionRate,
            'total_revenue' => $stats['total_revenue'] ?? 0,
            'total_customers' => $totalCustomers,
            'avg_customers_per_booking' => $avgCustomersPerBooking,
            'booking_growth' => $bookingGrowth
        ];
    }

    /**
     * Lấy báo cáo booking chi tiết
     */
    public function getBookingReport($dateFrom = null, $dateTo = null, $tourId = null, $status = null, $source = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        if ($status) {
            $whereConditions[] = "B.status = :status";
            $params[':status'] = $status;
        }

        if ($source) {
            $whereConditions[] = "B.source = :source";
            $params[':source'] = $source;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    B.*,
                    T.name AS tour_name,
                    TC.name AS category_name,
                    COALESCE(U.full_name, B.contact_name) AS customer_name,
                    COALESCE(U.email, B.contact_email) AS customer_email,
                    COALESCE(U.phone, B.contact_phone) AS customer_phone,
                    TA.start_date AS assignment_start_date,
                    TA.end_date AS assignment_end_date
                FROM bookings B
                LEFT JOIN tours T ON B.tour_id = T.id
                LEFT JOIN tour_categories TC ON T.category_id = TC.id
                LEFT JOIN users U ON B.customer_id = U.user_id
                LEFT JOIN tour_assignments TA 
                    ON TA.tour_id = B.tour_id 
                    AND TA.start_date = B.departure_date
                $whereClause
                ORDER BY B.booking_date DESC, B.id DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row = $this->applyOperationalStatus($row);
        }
        return $rows;
    }

    /**
     * Lấy tất cả bookings với filter theo role
     * @param string $userRole - 'admin' hoặc 'guide'
     * @param int|null $guideId - Chỉ cần nếu role là 'guide'
     * @return array
     */
    public function getTopBookedTours($dateFrom = null, $dateTo = null, $limit = 10)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $sql = "SELECT 
                    T.id,
                    T.name AS tour_name,
                    COUNT(B.id) AS booking_count,
                    COALESCE(SUM(B.final_price), 0) AS total_revenue,
                    COALESCE(SUM(customer_counts.count), 0) AS total_passengers
                FROM tours T
                JOIN bookings B ON T.id = B.tour_id
                LEFT JOIN (
                    SELECT booking_id, COUNT(id) as count 
                    FROM booking_customers 
                    GROUP BY booking_id
                ) customer_counts ON B.id = customer_counts.booking_id
                WHERE B.booking_date BETWEEN :date_from AND :date_to
                AND B.status IN ('completed', 'hoan_tat')
                GROUP BY T.id, T.name
                ORDER BY booking_count DESC, total_revenue DESC
                LIMIT " . (int)$limit;

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([':date_from' => $dateFrom, ':date_to' => $dateTo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Phân tích booking theo nguồn
     */
    public function getSourceAnalysis($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $sql = "SELECT 
                    COALESCE(
                        NULLIF(B.source, ''),
                        CASE
                            WHEN B.customer_id IS NULL THEN 'admin'
                            ELSE 'website'
                        END
                    ) AS source,
                    COUNT(B.id) AS booking_count,
                    SUM(CASE WHEN B.status IN ('completed', 'hoan_tat') THEN 1 ELSE 0 END) AS successful_bookings,
                    COALESCE(SUM(B.final_price), 0) AS revenue
                FROM bookings B
                WHERE B.booking_date BETWEEN :date_from AND :date_to
                GROUP BY source
                ORDER BY booking_count DESC";

        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute([':date_from' => $dateFrom, ':date_to' => $dateTo]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tính tỷ lệ chuyển đổi cho mỗi nguồn
            foreach ($results as &$result) {
                $totalBookings = $result['booking_count'];
                $successfulBookings = $result['successful_bookings'];
                $result['conversion_rate'] = $totalBookings > 0 ? ($successfulBookings / $totalBookings) * 100 : 0;
                $result['source'] = match (strtolower((string)$result['source'])) {
                    'website' => 'Website',
                    'admin' => 'Admin',
                    'walkin' => 'Tại quầy',
                    'hotline' => 'Hotline',
                    'facebook' => 'Facebook',
                    'zalo' => 'Zalo',
                    default => ucfirst((string)$result['source'])
                };
            }

            return $results;
        } catch (PDOException $e) {
            // Return empty if column 'source' not found or other error
            return [];
        }
    }

    /**
     * Lấy dữ liệu booking theo tháng cho biểu đồ
     */
    public function getMonthlyBookingData($year = null, $tourId = null)
    {
        $year = $year ?? date('Y');

        $whereConditions = ["YEAR(B.booking_date) = :year"];
        $params = [':year' => $year];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    MONTH(B.booking_date) as month,
                    COUNT(B.id) as total_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'hoan_tat') THEN 1 ELSE 0 END) as successful_bookings,
                    SUM(CASE WHEN B.status IN ('completed', 'hoan_tat') THEN B.final_price ELSE 0 END) as revenue,
                    COALESCE(SUM(customer_counts.total_customers), 0) as total_customers
                FROM bookings B 
                LEFT JOIN (
                    SELECT 
                        booking_id,
                        COUNT(id) as total_customers
                    FROM booking_customers 
                    GROUP BY booking_id
                ) customer_counts ON B.id = customer_counts.booking_id
                $whereClause
                GROUP BY MONTH(B.booking_date)
                ORDER BY month";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Đảm bảo có đủ 12 tháng
        $result = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthData = array_filter($monthlyData, function ($data) use ($month) {
                return $data['month'] == $month;
            });

            if (!empty($monthData)) {
                $row = reset($monthData);
                $row['month_name'] = "Tháng " . $month;
                $result[] = $row;
            } else {
                $result[] = [
                    'month' => $month,
                    'month_name' => "Tháng " . $month,
                    'total_bookings' => 0,
                    'successful_bookings' => 0,
                    'revenue' => 0,
                    'total_customers' => 0
                ];
            }
        }

        return $result;
    }

    /**
     * Lấy thống kê kỳ trước
     */
    private function getPreviousPeriodStats($dateFrom, $dateTo, $tourId = null, $status = null, $source = null)
    {
        // Tính khoảng thời gian kỳ trước
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1;
        $prevDateTo = date('Y-m-d', strtotime($dateFrom . ' -1 day'));
        $prevDateFrom = date('Y-m-d', strtotime($prevDateTo . ' -' . ($days - 1) . ' days'));

        // Skip growth calculation để tránh vòng lặp vô hạn
        return $this->getBookingStats($prevDateFrom, $prevDateTo, $tourId, $status, $source, true);
    }

    /**
     * Tính tỷ lệ tăng trưởng
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Lấy thống kê booking cho dashboard
     */
    public function getStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = :pending THEN 1 ELSE 0 END) as soft_pending,
                    SUM(CASE WHEN status = :waiting THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = :deposited THEN 1 ELSE 0 END) as deposited,
                    SUM(CASE WHEN status = :completed THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = :cancelled THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = :expired THEN 1 ELSE 0 END) as expired,
                    SUM(CASE WHEN status IN (:deposited, :paid, :completed) THEN final_price ELSE 0 END) as total_revenue
                FROM bookings";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([
            ':pending'   => self::STATUS_PENDING,
            ':waiting'   => self::STATUS_WAITING,
            ':deposited' => self::STATUS_DEPOSITED,
            ':paid'      => self::STATUS_PAID,
            ':completed' => self::STATUS_COMPLETED,
            ':cancelled' => self::STATUS_CANCELLED,
            ':expired'   => self::STATUS_EXPIRED
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách booking chưa thanh toán đủ (Công nợ phải thu)
     */
    public function getUnpaidBookings()
    {
        $sql = "SELECT b.id, b.final_price, u.full_name as customer_name, t.name as tour_name, td.departure_date,
                       (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE booking_id = b.id AND type = 'income') as paid_amount
                FROM {$this->table} b
                JOIN users u ON b.customer_id = u.user_id
                JOIN tours t ON b.tour_id = t.id
                JOIN tour_departures td ON b.departure_id = td.id
                WHERE b.status NOT IN ('cancelled', 'da_huy')
                HAVING paid_amount < b.final_price
                ORDER BY td.departure_date DESC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đồng bộ số lượng chỗ đã đặt cho một lịch khởi hành
     * Quy tắc:
     * - Chờ xác nhận (Hold): Tính vào số lượng khách dự kiến.
     * - Đã cọc/Thanh toán/Hoàn tất: Tính vào số lượng khách chính thức.
     */
    public function syncBookedSeats($departureId)
    {
        if (!$departureId) return false;

        $sql = "SELECT SUM(adults + children) as total_seats 
                FROM bookings 
                WHERE departure_id = :departure_id 
                AND status IN (:waiting, :deposited, :paid, :completed)";
        
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([
            ':departure_id' => $departureId,
            ':waiting'     => self::STATUS_WAITING,
            ':deposited'   => self::STATUS_DEPOSITED,
            ':paid'        => self::STATUS_PAID,
            ':completed'   => self::STATUS_COMPLETED
        ]);
        
        $totalSeats = (int)$stmt->fetchColumn();

        $updateSql = "UPDATE tour_departures SET booked_seats = :total WHERE id = :id";
        $updateStmt = self::$pdo->prepare($updateSql);
        return $updateStmt->execute([':total' => $totalSeats, ':id' => $departureId]);
    }

    /**
     * Lấy danh sách bookings cho một departure cụ thể (cho Manifest)
     */
    public function getBookingsByDeparture($departureId)
    {
        $sql = "SELECT b.*, 
                       COALESCE(u.full_name, b.contact_name) as customer_name,
                       COALESCE(u.phone, b.contact_phone) as customer_phone
                FROM {$this->table} b
                LEFT JOIN users u ON b.customer_id = u.user_id
                WHERE b.departure_id = :did
                AND b.status IN (:waiting, :deposited, :paid, :completed)
                ORDER BY b.id ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([
            'did' => $departureId,
            'waiting' => self::STATUS_WAITING,
            'deposited' => self::STATUS_DEPOSITED,
            'paid' => self::STATUS_PAID,
            'completed' => self::STATUS_COMPLETED
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
