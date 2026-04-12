<?php
require_once 'models/BaseModel.php';

class TourDeparture extends BaseModel
{
    protected $table = 'tour_departures';
    protected $columns = [
        'id',
        'tour_id',
        'departure_date',
        'max_seats',
        'price_adult',
        'price_child',
        'status',
        'created_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get departures by tour ID
     */
    public function getByTourId($tourId)
    {
        $sql = "SELECT 
                    td.id,
                    td.tour_id,
                    td.departure_date,
                    td.max_seats,
                    td.booked_seats,
                    td.price_adult,
                    td.price_child,
                    td.status,
                    td.notes,
                    (td.max_seats - td.booked_seats) as available_seats
                FROM tour_departures td
                WHERE td.tour_id = :tour_id
                    AND td.status = 'open'
                    AND td.departure_date >= CURDATE()
                ORDER BY td.departure_date ASC";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Lấy lịch khởi hành gần nhất (>= hôm nay) cho tour
     * @param int $tourId
     * @return array|null
     */
    public function getNextDepartureByTourId($tourId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE tour_id = :tid AND departure_date >= CURDATE() AND (status = 'open' OR status = 'guaranteed') ORDER BY departure_date ASC LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['tid' => $tourId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function findById($id)
    {
        $item = $this->find('*', 'id = :id', ['id' => $id]);
        return $item ?: null;
    }

    /**
     * Tăng hoặc giảm số lượng ghế đã đặt
     * @param int $id ID của lịch khởi hành
     * @param int $delta Số lượng thay đổi (dương để tăng, âm để giảm)
     * @return bool
     */
    public function adjustBookedSeats($id, $delta)
    {
        $sql = "UPDATE {$this->table} 
                SET booked_seats = GREATEST(0, booked_seats + :delta) 
                WHERE id = :id";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'delta' => $delta]);
    }

    /**
     * Tính tổng doanh thu từ tất cả Booking của chuyến đi
     */
    public function getTotalRevenue($departureId)
    {
        $sql = "SELECT SUM(final_price) as total 
                FROM bookings 
                WHERE tour_departure_id = :did 
                AND status IN ('paid', 'completed', 'da_thanh_toan', 'hoan_tat')";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['did' => $departureId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy tất cả lịch khởi hành kèm số lượng booking thực tế
     */
    /**
     * Lấy danh sách lịch khởi hành với bộ lọc và phân trang
     */
    public function getAllWithBookingStats($page = 1, $perPage = 15, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereConditions = ["1=1"];

        // Lọc theo Tour cụ thể
        if (!empty($filters['tour_id'])) {
            $whereConditions[] = "td.tour_id = :tour_id";
            $params[':tour_id'] = $filters['tour_id'];
        }

        // Lọc theo từ khóa (Tên tour)
        if (!empty($filters['keyword'])) {
            $whereConditions[] = "t.name LIKE :keyword";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $whereConditions[] = "td.status = :status";
            $params[':status'] = $filters['status'];
        }

        // Lọc theo khoảng ngày khởi hành
        if (!empty($filters['date_from'])) {
            $whereConditions[] = "td.departure_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $whereConditions[] = "td.departure_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $whereConditions);

        // Đếm tổng
        $countSql = "SELECT COUNT(*) 
                     FROM {$this->table} td
                     JOIN tours t ON td.tour_id = t.id
                     WHERE $whereClause";
        $countStmt = self::$pdo->prepare($countSql);
        foreach ($params as $key => $val) {
            $countStmt->bindValue($key, $val);
        }
        $countStmt->execute();
        $totalItems = (int)$countStmt->fetchColumn();

        // Truy vấn chính
        $sql = "SELECT td.*, t.name as tour_name, 
                       (SELECT COUNT(*) FROM bookings WHERE departure_id = td.id AND status != 'cancelled') as booking_count,
                       (SELECT SUM(final_price) FROM bookings WHERE departure_id = td.id AND status IN ('paid', 'completed')) as revenue
                FROM {$this->table} td
                JOIN tours t ON td.tour_id = t.id
                WHERE $whereClause
                ORDER BY td.departure_date DESC
                LIMIT :limit OFFSET :offset";

        $stmt = self::$pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $totalItems,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($totalItems / $perPage)
        ];
    }

    /**
     * Đồng bộ số ghế đã đặt với dữ liệu booking thực tế
     * @param int|null $departureId Nếu null sẽ đồng bộ tất cả các chuyến trong 1 tháng vừa qua và tương lai
     */
    public function syncBookedSeats($departureId = null)
    {
        $condition = $departureId ? "WHERE id = :id" : "WHERE departure_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        $params = $departureId ? [':id' => $departureId] : [];

        $sql = "UPDATE {$this->table} td
                SET booked_seats = (
                    SELECT COALESCE(SUM(b.adults + b.children), 0)
                    FROM bookings b
                    WHERE b.departure_id = td.id
                    AND b.status NOT IN ('cancelled', 'da_huy', 'expired', 'cancelled')
                )
                $condition";
        
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
