<?php
require_once 'models/BaseModel.php';

/**
 * Model quản lý phân công tour cho hướng dẫn viên
 * Sử dụng bảng tour_assignments có sẵn
 */
class TourAssignment extends BaseModel
{
    protected $table = 'tour_assignments';
    protected $columns = [
        'id',
        'tour_id',
        'departure_id',
        'guide_id',
        'driver_name',
        'start_date',
        'end_date',
        'status'
    ];

    private static ?bool $hasDurationDaysColumn = null;

    private function hasDurationDaysColumn(): bool
    {
        if (self::$hasDurationDaysColumn !== null) {
            return self::$hasDurationDaysColumn;
        }

        try {
            $stmt = self::$pdo->query("SHOW COLUMNS FROM tours LIKE 'duration_days'");
            self::$hasDurationDaysColumn = (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            self::$hasDurationDaysColumn = false;
        }

        return self::$hasDurationDaysColumn;
    }

    /**
     * Lấy chi tiết assignment theo ID
     */
    public function getById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Phân công tour cho HDV
     * @param int $guideId
     * @param int $tourId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string $status
     * @return int|false - ID của assignment hoặc false nếu thất bại
     */
    public function assignTourToGuide($guideId, $tourId, $startDate = null, $endDate = null, $status = 'active')
    {
        try {
            return $this->insert([
                'guide_id' => $guideId,
                'tour_id' => $tourId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'driver_name' => null
            ]);
        } catch (Exception $e) {
            error_log('Error assigning tour to guide: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách tour của một HDV (đang active)
     * @param int $guideId
     * @return array
     */
    public function getToursByGuide($guideId)
    {
        $sql = "SELECT 
                    ta.*,
                    t.id as tour_id,
                    t.name as tour_name,
                    t.base_price,
                    t.description
                FROM {$this->table} AS ta
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                WHERE ta.guide_id = :guide_id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách HDV của một tour
     * @param int $tourId
     * @return array
     */
    public function getGuidesByTour($tourId)
    {
        $sql = "SELECT 
                    ta.*,
                    g.id as guide_id,
                    u.full_name as guide_name,
                    u.email as guide_email,
                    u.phone as guide_phone,
                    g.languages,
                    g.experience_years
                FROM {$this->table} AS ta
                LEFT JOIN guides AS g ON ta.guide_id = g.id
                LEFT JOIN users AS u ON g.user_id = u.user_id
                WHERE ta.tour_id = :tour_id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hủy phân công tour cho HDV
     * @param int $id - ID của tour_assignment
     * @return bool
     */
    public function removeAssignment($id)
    {
        try {
            return $this->delete('id = :id', ['id' => $id]);
        } catch (Exception $e) {
            error_log('Error removing tour assignment: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra HDV có phụ trách tour không (bất kỳ thời điểm nào)
     * @param int $guideId
     * @param int $tourId
     * @return bool
     */
    public function isGuideAssignedToTour($guideId, $tourId)
    {
        $result = $this->find('id', 'guide_id = :guide_id AND tour_id = :tour_id', [
            'guide_id' => $guideId,
            'tour_id' => $tourId
        ]);
        return !empty($result);
    }

    /**
     * Lấy tất cả phân công với thông tin chi tiết
     * @return array
     */
    public function getAllAssignments()
    {
        $sql = "SELECT 
                    ta.*,
                    g.id as guide_id,
                    u.full_name as guide_name,
                    u.email as guide_email,
                    t.id as tour_id,
                    t.name as tour_name
                FROM {$this->table} AS ta
                LEFT JOIN guides AS g ON ta.guide_id = g.id
                LEFT JOIN users AS u ON g.user_id = u.user_id
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật trạng thái assignment
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        try {
            return $this->update(
                ['status' => $status],
                'id = :id',
                ['id' => $id]
            );
        } catch (Exception $e) {
            error_log('Error updating assignment status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy các tour assignments đang active của HDV
     * @param int $guideId
     * @return array
     */
    public function getActiveAssignmentsByGuide($guideId)
    {
        $sql = "SELECT 
                    ta.*,
                    t.id as tour_id,
                    t.name as tour_name,
                    t.base_price
                FROM {$this->table} AS ta
                LEFT JOIN tours AS t ON ta.tour_id = t.id
                WHERE ta.guide_id = :guide_id 
                AND (ta.status = 'active' OR ta.status IS NULL)
                AND (ta.end_date IS NULL OR ta.end_date >= CURDATE())
                ORDER BY ta.start_date DESC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['guide_id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách tour khả dụng cho HDV/admin nhận phân công.
     * Chỉ lấy các booking đã xác nhận thanh toán/cọc theo ngày khởi hành.
     * @return array
     */
    public function getAvailableTours()
    {
        $sql = "SELECT 
            t.id as tour_id, 
            t.name as tour_name, 
            t.category_id, 
            t.description, 
            t.base_price as tour_base_price,
            b.departure_id,
            COALESCE(td.departure_date, b.departure_date) as departure_date,
            COUNT(DISTINCT b.id) as booking_count,
            SUM(b.adults + b.children + b.infants) as total_customers,
            COALESCE(SUM(b.total_price), 0) as total_booking_price,
            GROUP_CONCAT(DISTINCT b.id ORDER BY b.id) as booking_ids,
            td.max_seats
        FROM tours t
        INNER JOIN bookings b ON t.id = b.tour_id 
            AND b.status IN ('da_coc', 'da_thanh_toan')
        LEFT JOIN tour_departures td ON td.id = b.departure_id
        WHERE NOT EXISTS (
            SELECT 1 
            FROM tour_assignments ta
            WHERE ta.tour_id = t.id 
            AND ta.start_date = COALESCE(td.departure_date, b.departure_date)
            AND ta.status = 'active'
        )
        AND COALESCE(td.departure_date, b.departure_date) >= CURDATE()
        AND t.status = 'active'
        GROUP BY t.id, t.name, t.category_id, t.description, t.base_price, b.departure_id, COALESCE(td.departure_date, b.departure_date), td.max_seats
        ORDER BY COALESCE(td.departure_date, b.departure_date) ASC, t.name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Danh sách đoàn khởi hành để admin phân công HDV.
     * Mỗi dòng là 1 departure có booking hợp lệ.
     * @return array
     */
    public function getDepartureAssignmentsForAdmin($departureId = null)
    {
        $sql = "SELECT
                td.id AS departure_id,
                td.tour_id,
                td.departure_date,
                td.max_seats,
                td.booked_seats,
                td.status AS departure_status,
                t.name AS tour_name,
                t.description,
                COUNT(DISTINCT b.id) AS booking_count,
                COALESCE(SUM(b.adults + b.children + b.infants), 0) AS total_customers,
                COALESCE(SUM(b.adults + b.children + b.infants), 0) AS booked_seats_live,
                COALESCE(SUM(b.total_price), 0) AS total_booking_price,
                ta.id AS assignment_id,
                ta.guide_id AS assigned_guide_id,
                ta.status AS assignment_status,
                u.full_name AS assigned_guide_name
            FROM tour_departures td
            INNER JOIN tours t ON t.id = td.tour_id AND t.status = 'active'
            LEFT JOIN bookings b ON b.departure_id = td.id
                AND b.status IN ('cho_xac_nhan', 'da_coc', 'da_thanh_toan', 'hoan_tat')
            LEFT JOIN tour_assignments ta ON ta.departure_id = td.id
                AND ta.status = 'active'
            LEFT JOIN guides g ON g.id = ta.guide_id
            LEFT JOIN users u ON u.user_id = g.user_id
            WHERE td.departure_date >= CURDATE()
              AND (:departure_id IS NULL OR td.id = :departure_id)
            GROUP BY
                td.id, td.tour_id, td.departure_date, td.max_seats, td.booked_seats, td.status,
                t.name, t.description,
                ta.id, ta.guide_id, ta.status, u.full_name
            HAVING (total_customers > 0 OR assignment_id IS NOT NULL)
            ORDER BY td.departure_date ASC, t.name ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['departure_id' => $departureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy assignment active theo departure bằng map tour_id + start_date.
     * @param int $departureId
     * @return array|false
     */
    public function getActiveAssignmentByDeparture($departureId)
    {
        $sql = "SELECT ta.*, u.full_name as assigned_guide_name
            FROM {$this->table} ta
            INNER JOIN tour_departures td
                ON td.tour_id = ta.tour_id
                AND td.departure_date = ta.start_date
            LEFT JOIN guides g ON ta.guide_id = g.id
            LEFT JOIN users u ON g.user_id = u.user_id
            WHERE td.id = :departure_id
              AND ta.status = 'active'
            ORDER BY ta.id DESC
            LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['departure_id' => $departureId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra HDV đã được phân công đoàn khác cùng ngày chưa.
     * @param int $guideId
     * @param string $startDate
     * @param int|null $excludeAssignmentId
     * @return bool
     */
    public function guideHasAssignmentOnDate($guideId, $startDate, $excludeAssignmentId = null)
    {
        $sql = "SELECT COUNT(*) AS cnt
            FROM {$this->table}
            WHERE guide_id = :guide_id
              AND start_date = :start_date
              AND status = 'active'";
        $params = [
            'guide_id' => $guideId,
            'start_date' => $startDate
        ];

        if ($excludeAssignmentId !== null) {
            $sql .= " AND id <> :exclude_assignment_id";
            $params['exclude_assignment_id'] = $excludeAssignmentId;
        }

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int)($result['cnt'] ?? 0)) > 0;
    }

    /**
     * Tìm assignment active bị chồng thời gian của HDV.
     * @param int $guideId
     * @param string $startDate
     * @param string $endDate
     * @param int|null $excludeAssignmentId
     * @return array|false
     */
    public function getGuideOverlappingAssignment($guideId, $startDate, $endDate, $excludeAssignmentId = null)
    {
        $durationExpr = $this->hasDurationDaysColumn()
            ? "GREATEST(COALESCE(NULLIF(t.duration_days, 0), (SELECT COUNT(*) FROM itineraries ti WHERE ti.tour_id = ta.tour_id), 1), 1)"
            : "GREATEST(COALESCE((SELECT COUNT(*) FROM itineraries ti WHERE ti.tour_id = ta.tour_id), 1), 1)";

        $sql = "SELECT
                ta.id,
                ta.tour_id,
                ta.start_date,
                COALESCE(
                    ta.end_date,
                    DATE_ADD(
                        ta.start_date,
                        INTERVAL ({$durationExpr} - 1) DAY
                    )
                ) AS end_date,
                t.name AS tour_name
            FROM {$this->table} ta
            LEFT JOIN tours t ON t.id = ta.tour_id
            WHERE ta.guide_id = :guide_id
              AND (ta.status = 'active' OR ta.status IS NULL)
              AND ta.start_date IS NOT NULL
              AND (
                    ta.start_date <= :new_end_date
                    AND COALESCE(
                        ta.end_date,
                        DATE_ADD(
                            ta.start_date,
                            INTERVAL ({$durationExpr} - 1) DAY
                        )
                    ) >= :new_start_date
              )";

        $params = [
            'guide_id' => $guideId,
            'new_start_date' => $startDate,
            'new_end_date' => $endDate
        ];

        if ($excludeAssignmentId !== null) {
            $sql .= " AND ta.id <> :exclude_assignment_id";
            $params['exclude_assignment_id'] = $excludeAssignmentId;
        }

        $sql .= " ORDER BY ta.start_date ASC LIMIT 1";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách ngày khởi hành của tour
     * @param int $tourId
     * @return array
     */
    public function getTourDepartureDates($tourId)
    {
        $sql = "SELECT 
                id,
                departure_date,
                max_seats,
                booked_seats,
                (max_seats - booked_seats) as available_seats,
                status
            FROM tour_departures
            WHERE tour_id = :tour_id
                AND status = 'open'
                AND departure_date >= CURDATE()
            ORDER BY departure_date ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra tour đã có HDV chưa (cho ngày cụ thể)
     * @param int $tourId
     * @param string|null $startDate - Ngày khởi hành cụ thể
     * @return bool
     */
    public function tourHasGuide($tourId, $startDate = null)
    {
        if ($startDate) {
            // Check theo cả tour_id và start_date
            $sql = "SELECT COUNT(*) as count 
                FROM tour_assignments 
                WHERE tour_id = :tour_id 
                AND start_date = :start_date
                AND status = 'active'";

            $stmt = self::$pdo->prepare($sql);
            $stmt->execute([
                'tour_id' => $tourId,
                'start_date' => $startDate
            ]);
        } else {
            // Check chỉ theo tour_id (backward compatibility)
            $sql = "SELECT COUNT(*) as count 
                FROM tour_assignments 
                WHERE tour_id = :tour_id 
                AND status = 'active'";

            $stmt = self::$pdo->prepare($sql);
            $stmt->execute(['tour_id' => $tourId]);
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Lấy danh sách assignment nào chưa có start_date
     * @return array
     */
    public function getAssignmentsMissingDates()
    {
        $sql = "SELECT * FROM {$this->table} WHERE start_date IS NULL OR start_date = ''";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật start_date/end_date cho assignment
     * @param int $id
     * @param string|null $startDate
     * @param string|null $endDate
     * @return bool|int
     */
    public function updateAssignmentDates($id, $startDate = null, $endDate = null)
    {
        $data = ['start_date' => $startDate, 'end_date' => $endDate];
        return $this->update($data, 'id = :id', ['id' => $id]);
    }

    /**
     * Lấy chi tiết phân bổ khách theo tour version
     * @param int $tourId
     * @return array
     */
    public function getTourVersionBreakdown($tourId)
    {
        // Module tour_versions đã bị xóa.
        // Trả về mảng rỗng để không phá vỡ logic giao diện gọi hàm này.
        return [];
    }
}
