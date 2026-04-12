<?php

/**
 * Debt Report Model - Báo cáo công nợ khách hàng (Đã loại bỏ Nhà cung cấp)
 */
class DebtReport extends BaseModel
{
    protected $table = 'bookings';
    private $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = self::$pdo;
    }

    /**
     * Lấy báo cáo công nợ khách hàng
     */
    public function getCustomerDebtReport($dateFrom = null, $dateTo = null, $filters = [])
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        // Apply filters
        if (!empty($filters['tour_id'])) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $filters['tour_id'];
        }
        if (!empty($filters['customer_id'])) {
            $whereConditions[] = "B.customer_id = :customer_id";
            $params[':customer_id'] = $filters['customer_id'];
        }
        if (!empty($filters['status'])) {
            $whereConditions[] = "B.status = :status";
            $params[':status'] = $filters['status'];
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        // Lấy công nợ theo từng khách hàng
        $sql = "SELECT 
                    COALESCE(B.customer_id, 0) as customer_id,
                    COALESCE(U.full_name, 'Khách vãng lai') as customer_name,
                    COALESCE(U.email, '') as customer_email,
                    COALESCE(U.phone, '') as customer_phone,
                    COUNT(B.id) as total_bookings,
                    SUM(B.final_price) as total_amount,
                    SUM(CASE WHEN B.status IN ('paid', 'completed') THEN B.final_price ELSE 0 END) as paid_amount,
                    SUM(CASE WHEN B.status NOT IN ('paid', 'completed') THEN B.final_price ELSE 0 END) as debt_amount,
                    AVG(B.final_price) as avg_booking_value,
                    MAX(B.booking_date) as last_booking_date
                FROM bookings B
                LEFT JOIN users U ON B.customer_id = U.user_id
                $whereClause
                GROUP BY B.customer_id, U.full_name, U.email, U.phone
                HAVING debt_amount > 0 OR total_amount > 0
                ORDER BY debt_amount DESC, total_amount DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $customerDebts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính toán các chỉ số tổng quan
        $totalCustomers = count($customerDebts);
        $totalDebtAmount = array_sum(array_column($customerDebts, 'debt_amount'));
        $totalPaidAmount = array_sum(array_column($customerDebts, 'paid_amount'));
        $totalAmount = array_sum(array_column($customerDebts, 'total_amount'));
        $avgDebtPerCustomer = $totalCustomers > 0 ? $totalDebtAmount / $totalCustomers : 0;

        // Phân loại công nợ
        $debtCategories = [
            'under_1m' => 0,
            '1m_5m' => 0,
            '5m_10m' => 0,
            '10m_20m' => 0,
            'over_20m' => 0
        ];

        foreach ($customerDebts as $debt) {
            $amount = $debt['debt_amount'];
            if ($amount < 1000000) {
                $debtCategories['under_1m']++;
            } elseif ($amount < 5000000) {
                $debtCategories['1m_5m']++;
            } elseif ($amount < 10000000) {
                $debtCategories['5m_10m']++;
            } elseif ($amount < 20000000) {
                $debtCategories['10m_20m']++;
            } else {
                $debtCategories['over_20m']++;
            }
        }

        return [
            'summary' => [
                'total_customers' => $totalCustomers,
                'total_debt_amount' => $totalDebtAmount,
                'total_paid_amount' => $totalPaidAmount,
                'total_amount' => $totalAmount,
                'avg_debt_per_customer' => $avgDebtPerCustomer,
                'debt_collection_rate' => $totalAmount > 0 ? (($totalPaidAmount / $totalAmount) * 100) : 0
            ],
            'customer_debts' => $customerDebts,
            'debt_categories' => $debtCategories,
            'overdue_analysis' => $this->getOverdueAnalysis($dateFrom, $dateTo, $filters)
        ];
    }

    /**
     * Placeholder cho báo cáo nhà cung cấp (đã loại bỏ)
     */
    public function getSupplierDebtReport($dateFrom = null, $dateTo = null, $filters = [])
    {
        return [
            'summary' => ['total_suppliers' => 0, 'total_debt_amount' => 0, 'total_amount' => 0, 'avg_debt_per_supplier' => 0],
            'supplier_debts' => [],
            'supplier_types' => [],
            'service_type_analysis' => []
        ];
    }

    /**
     * Phân tích công nợ quá hạn
     */
    private function getOverdueAnalysis($dateFrom, $dateTo, $filters = [])
    {
        $sql = "SELECT 
                    COUNT(*) as overdue_bookings,
                    SUM(B.final_price) as overdue_amount,
                    AVG(DATEDIFF(NOW(), B.booking_date)) as avg_days_overdue
                FROM bookings B
                WHERE B.booking_date BETWEEN :date_from AND :date_to
                AND B.status NOT IN ('paid', 'completed', 'cancelled')
                AND DATEDIFF(NOW(), B.booking_date) > 30";

        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if (!empty($filters['customer_id'])) {
            $sql .= " AND B.customer_id = :customer_id";
            $params[':customer_id'] = $filters['customer_id'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết công nợ theo khách hàng
     */
    public function getCustomerDebtDetails($customerId, $dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $sql = "SELECT 
                    B.id,
                    B.booking_date,
                    B.departure_date,
                    B.final_price,
                    B.status,
                    B.notes,
                    T.name as tour_name,
                    B.adults + B.children + B.infants as total_customers,
                    DATEDIFF(NOW(), B.booking_date) as days_overdue,
                    CASE 
                        WHEN B.status IN ('paid', 'completed') THEN 'Đã thanh toán'
                        WHEN DATEDIFF(NOW(), B.booking_date) > 30 THEN 'Quá hạn'
                        ELSE 'Chưa đến hạn'
                    END as payment_status
                FROM bookings B
                LEFT JOIN tours T ON B.tour_id = T.id
                WHERE B.customer_id = :customer_id
                AND B.booking_date BETWEEN :date_from AND :date_to
                ORDER BY B.booking_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customerId,
            ':date_from' => $dateFrom,
            ':date_to' => $dateTo
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Placeholder cho chi tiết NCC (đã loại bỏ)
     */
    public function getSupplierDebtDetails($supplierId, $dateFrom = null, $dateTo = null)
    {
        return [];
    }

    /**
     * Lấy báo cáo thanh toán (Chỉ từ khách hàng)
     */
    public function getPaymentReport($dateFrom = null, $dateTo = null, $type = 'customer')
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        if ($type === 'customer') {
            $sql = "SELECT 
                        DATE(B.updated_at) as payment_date,
                        COUNT(*) as payment_count,
                        SUM(B.final_price) as total_amount,
                        AVG(B.final_price) as avg_amount
                    FROM bookings B
                    WHERE B.updated_at BETWEEN :date_from AND :date_to
                    AND B.status IN ('paid', 'completed')
                    GROUP BY DATE(B.updated_at)
                    ORDER BY payment_date DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':date_from' => $dateFrom, ':date_to' => $dateTo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return []; // Không còn thanh toán NCC
        }
    }
}
