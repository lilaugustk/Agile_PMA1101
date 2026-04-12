<?php

/**
 * Revenue Report Model - Báo cáo doanh thu chi tiết (Thay thế cho ProfitLossReport)
 */
class ProfitLossReport extends BaseModel
{
    protected $table = 'financial_reports';
    private $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = self::$pdo;
    }

    /**
     * Lấy báo cáo doanh thu tổng quan theo khoảng thời gian
     */
    public function getProfitLossSummary($dateFrom = null, $dateTo = null, $filters = [])
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
        if (!empty($filters['category_id'])) {
            $whereConditions[] = "T.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        // Doanh thu từ bookings
        $revenueSql = "SELECT 
                           SUM(B.final_price) as total_revenue,
                           COUNT(B.id) as total_bookings,
                           AVG(B.final_price) as avg_revenue_per_booking,
                           SUM(B.adults + B.children + B.infants) as total_customers
                       FROM bookings B
                       LEFT JOIN tours T ON B.tour_id = T.id
                       $whereClause
                       AND B.status IN ('completed', 'paid')";

        $stmt = $this->pdo->prepare($revenueSql);
        $stmt->execute($params);
        $revenueData = $stmt->fetch();

        // Chi phí điều chỉnh (discount, FOC, etc.)
        $adjustmentSql = "SELECT 
                              SUM(CASE WHEN BPA.adjust_type IN ('discount_cash', 'discount_percent') THEN ABS(BPA.amount) ELSE 0 END) as total_discounts,
                              SUM(CASE WHEN BPA.adjust_type = 'foc' THEN ABS(BPA.amount) ELSE 0 END) as total_foc_value,
                              SUM(CASE WHEN BPA.adjust_type = 'surcharge' THEN BPA.amount ELSE 0 END) as total_surcharges,
                              COUNT(*) as total_adjustments
                          FROM booking_price_adjustments BPA
                          LEFT JOIN bookings B ON BPA.booking_id = B.id
                          LEFT JOIN tours T ON B.tour_id = T.id
                          $whereClause
                          AND B.status IN ('completed', 'paid')";

        $stmt = $this->pdo->prepare($adjustmentSql);
        $stmt->execute($params);
        $adjustmentData = $stmt->fetch();

        $totalRevenue = $revenueData['total_revenue'] ?? 0;
        $totalDiscounts = $adjustmentData['total_discounts'] ?? 0;
        $totalFocValue = $adjustmentData['total_foc_value'] ?? 0;
        $totalSurcharges = $adjustmentData['total_surcharges'] ?? 0;

        $netRevenue = $totalRevenue - $totalDiscounts - $totalFocValue + $totalSurcharges;

        return [
            'revenue' => [
                'total' => $totalRevenue,
                'net' => $netRevenue,
                'discounts' => $totalDiscounts,
                'foc_value' => $totalFocValue,
                'surcharges' => $totalSurcharges,
                'avg_per_booking' => $revenueData['avg_revenue_per_booking'] ?? 0,
                'bookings_count' => $revenueData['total_bookings'] ?? 0,
                'customers_count' => $revenueData['total_customers'] ?? 0
            ],
            'expense' => [
                'total' => 0,
                'supplier_count' => 0,
                'bookings_with_expenses' => 0
            ],
            'profit' => [
                'gross' => $netRevenue, // Không có chi phí thì lợi nhuận gộp = doanh thu thuần
                'margin' => 100,
                'avg_per_booking' => ($revenueData['total_bookings'] ?? 0) > 0 ? $netRevenue / ($revenueData['total_bookings'] ?? 1) : 0,
                'avg_per_customer' => ($revenueData['total_customers'] ?? 0) > 0 ? $netRevenue / ($revenueData['total_customers'] ?? 1) : 0
            ],
            'adjustments' => [
                'total_count' => $adjustmentData['total_adjustments'] ?? 0
            ]
        ];
    }

    /**
     * Lấy báo cáo doanh thu chi tiết theo từng tour
     */
    public function getTourProfitLossDetails($dateFrom = null, $dateTo = null, $filters = [])
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if (!empty($filters['tour_id'])) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $filters['tour_id'];
        }
        if (!empty($filters['category_id'])) {
            $whereConditions[] = "T.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    T.id as tour_id,
                    T.name as tour_name,
                    TC.name as category_name,
                    T.base_price as tour_price,
                    COUNT(B.id) as booking_count,
                    SUM(B.final_price) as total_revenue,
                    SUM(B.adults + B.children + B.infants) as total_customers,
                    AVG(B.final_price) as avg_revenue_per_booking
                FROM tours T
                LEFT JOIN tour_categories TC ON T.category_id = TC.id
                LEFT JOIN bookings B ON T.id = B.tour_id
                $whereClause
                AND B.status IN ('completed', 'paid')
                GROUP BY T.id, T.name, TC.name, T.base_price
                HAVING total_revenue > 0
                ORDER BY total_revenue DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tours as &$tour) {
            $tourData = $this->getTourDetailedData($tour['tour_id'], $dateFrom, $dateTo);

            $tour['expense'] = 0;
            $tour['discounts'] = $tourData['discounts'];
            $tour['foc_value'] = $tourData['foc_value'];
            $tour['surcharges'] = $tourData['surcharges'];
            $tour['net_revenue'] = $tour['total_revenue'] - $tour['discounts'] - $tour['foc_value'] + $tour['surcharges'];
            $tour['gross_profit'] = $tour['net_revenue'];
            $tour['profit_margin'] = 100;
            $tour['profit_per_customer'] = $tour['total_customers'] > 0 ? $tour['gross_profit'] / $tour['total_customers'] : 0;
            $tour['expense_breakdown'] = [];
            $tour['supplier_count'] = 0;
            $tour['adjustment_count'] = $tourData['adjustment_count'];
        }

        return $tours;
    }

    private function getTourDetailedData($tourId, $dateFrom, $dateTo)
    {
        $params = [':tour_id' => $tourId, ':date_from' => $dateFrom, ':date_to' => $dateTo];

        $adjustmentSql = "SELECT 
                              SUM(CASE WHEN BPA.adjust_type IN ('discount_cash', 'discount_percent') THEN ABS(BPA.amount) ELSE 0 END) as total_discounts,
                              SUM(CASE WHEN BPA.adjust_type = 'foc' THEN ABS(BPA.amount) ELSE 0 END) as total_foc_value,
                              SUM(CASE WHEN BPA.adjust_type = 'surcharge' THEN BPA.amount ELSE 0 END) as total_surcharges,
                              COUNT(*) as adjustment_count
                          FROM booking_price_adjustments BPA
                          LEFT JOIN bookings B ON BPA.booking_id = B.id
                          WHERE B.tour_id = :tour_id
                          AND B.booking_date BETWEEN :date_from AND :date_to
                          AND B.status IN ('completed', 'paid')";

        $stmt = $this->pdo->prepare($adjustmentSql);
        $stmt->execute($params);
        $adjustmentData = $stmt->fetch();

        return [
            'expense' => 0,
            'supplier_count' => 0,
            'discounts' => $adjustmentData['total_discounts'] ?? 0,
            'foc_value' => $adjustmentData['total_foc_value'] ?? 0,
            'surcharges' => $adjustmentData['total_surcharges'] ?? 0,
            'adjustment_count' => $adjustmentData['adjustment_count'] ?? 0,
            'expense_breakdown' => []
        ];
    }

    public function getCategoryProfitLoss($dateFrom = null, $dateTo = null, $filters = [])
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if (!empty($filters['category_id'])) {
            $whereConditions[] = "TC.id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $sql = "SELECT 
                    TC.id as category_id,
                    TC.name as category_name,
                    COUNT(DISTINCT T.id) as tour_count,
                    COUNT(B.id) as booking_count,
                    SUM(B.final_price) as total_revenue,
                    SUM(B.adults + B.children + B.infants) as total_customers
                FROM tour_categories TC
                LEFT JOIN tours T ON TC.id = T.category_id
                LEFT JOIN bookings B ON T.id = B.tour_id
                $whereClause
                AND B.status IN ('completed', 'paid')
                GROUP BY TC.id, TC.name
                HAVING total_revenue > 0
                ORDER BY total_revenue DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as &$category) {
            $categoryData = $this->getCategoryDetailedData($category['category_id'], $dateFrom, $dateTo);

            $category['expense'] = 0;
            $category['discounts'] = $categoryData['discounts'];
            $category['foc_value'] = $categoryData['foc_value'];
            $category['surcharges'] = $categoryData['surcharges'];
            $category['net_revenue'] = $category['total_revenue'] - $category['discounts'] - $category['foc_value'] + $category['surcharges'];
            $category['gross_profit'] = $category['net_revenue'];
            $category['profit_margin'] = 100;
            $category['profit_per_booking'] = $category['booking_count'] > 0 ? $category['gross_profit'] / $category['booking_count'] : 0;
        }

        return $categories;
    }

    private function getCategoryDetailedData($categoryId, $dateFrom, $dateTo)
    {
        $params = [':category_id' => $categoryId, ':date_from' => $dateFrom, ':date_to' => $dateTo];

        $adjustmentSql = "SELECT 
                              SUM(CASE WHEN BPA.adjust_type IN ('discount_cash', 'discount_percent') THEN ABS(BPA.amount) ELSE 0 END) as total_discounts,
                              SUM(CASE WHEN BPA.adjust_type = 'foc' THEN ABS(BPA.amount) ELSE 0 END) as total_foc_value,
                              SUM(CASE WHEN BPA.adjust_type = 'surcharge' THEN BPA.amount ELSE 0 END) as total_surcharges
                          FROM booking_price_adjustments BPA
                          LEFT JOIN bookings B ON BPA.booking_id = B.id
                          LEFT JOIN tours T ON B.tour_id = T.id
                          WHERE T.category_id = :category_id
                          AND B.booking_date BETWEEN :date_from AND :date_to
                          AND B.status IN ('completed', 'paid')";

        $stmt = $this->pdo->prepare($adjustmentSql);
        $stmt->execute($params);
        $adjustmentData = $stmt->fetch();

        return [
            'expense' => 0,
            'discounts' => $adjustmentData['total_discounts'] ?? 0,
            'foc_value' => $adjustmentData['total_foc_value'] ?? 0,
            'surcharges' => $adjustmentData['total_surcharges'] ?? 0
        ];
    }

    public function getMonthlyProfitLoss($year = null, $filters = [])
    {
        $year = $year ?? date('Y');

        $whereConditions = ["YEAR(B.booking_date) = :year"];
        $params = [':year' => $year];

        if (!empty($filters['tour_id'])) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $filters['tour_id'];
        }
        if (!empty($filters['category_id'])) {
            $whereConditions[] = "T.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        $revenueSql = "SELECT 
                           MONTH(B.booking_date) as month,
                           SUM(B.final_price) as total_revenue,
                           COUNT(B.id) as booking_count,
                           SUM(B.adults + B.children + B.infants) as total_customers
                       FROM bookings B
                       LEFT JOIN tours T ON B.tour_id = T.id
                       $whereClause
                       AND B.status IN ('completed', 'paid')
                       GROUP BY MONTH(B.booking_date)";

        $stmt = $this->pdo->prepare($revenueSql);
        $stmt->execute($params);
        $revenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenue = 0;
            $bookings = 0;
            $customers = 0;

            foreach ($revenueData as $data) {
                if ($data['month'] == $month) {
                    $revenue = $data['total_revenue'];
                    $bookings = $data['booking_count'];
                    $customers = $data['total_customers'];
                    break;
                }
            }

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue' => $revenue,
                'expense' => 0,
                'profit' => $revenue,
                'profit_margin' => 100,
                'booking_count' => $bookings,
                'customers_count' => $customers,
                'avg_revenue_per_booking' => $bookings > 0 ? $revenue / $bookings : 0,
                'avg_profit_per_booking' => $bookings > 0 ? $revenue / $bookings : 0
            ];
        }

        return $monthlyData;
    }

    public function getTopProfitableTours($dateFrom = null, $dateTo = null, $limit = 10)
    {
        $tourDetails = $this->getTourProfitLossDetails($dateFrom, $dateTo);
        return [
            'most_profitable' => array_slice($tourDetails, 0, $limit),
            'least_profitable' => array_slice(array_reverse($tourDetails), 0, $limit)
        ];
    }

    public function getExpenseAnalysis($dateFrom = null, $dateTo = null, $filters = [])
    {
        return []; // Báo cáo sản lượng chi phí không còn khả dụng
    }
}
