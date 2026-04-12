<?php

/**
 * Financial Report Model - Báo cáo tài chính (Đã đơn giản hóa thành báo cáo doanh thu)
 */
class FinancialReport extends BaseModel
{
    protected $table = 'bookings';
    public function __construct()
    {
        parent::__construct();
    }

    public function getFinancialSummary($dateFrom = null, $dateTo = null, $filters = [], $skipGrowth = false)
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
                    SUM(B.final_price) as total_revenue,
                    COUNT(B.id) as total_bookings,
                    AVG(B.final_price) as avg_booking_value
                FROM bookings B 
                LEFT JOIN tours T ON B.tour_id = T.id
                $whereClause 
                AND B.status IN ('completed', 'paid', 'hoan_tat', 'da_thanh_toan', 'da_coc')";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $revenueData = $stmt->fetch();

        $totalRevenue = $revenueData['total_revenue'] ?? 0;

        $revenueGrowth = 0;
        if (!$skipGrowth) {
            $previousPeriodData = $this->getPreviousPeriodData($dateFrom, $dateTo, $filters);
            $revenueGrowth = $this->calculateGrowth($totalRevenue, $previousPeriodData['total_revenue']);
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_estimated_expense' => 0,
            'total_actual_expense' => 0,
            'estimated_profit' => $totalRevenue,
            'estimated_profit_margin' => 100,
            'actual_profit' => $totalRevenue,
            'actual_profit_margin' => 100,
            'total_bookings' => $revenueData['total_bookings'] ?? 0,
            'avg_booking_value' => $revenueData['avg_booking_value'] ?? 0,
            'revenue_growth' => $revenueGrowth,
            'expense_growth' => 0,
            'profit_growth' => $revenueGrowth
        ];
    }

    public function getActualExpenses($tourId, $dateFrom, $dateTo)
    {
        return 0; // Đã loại bỏ báo cáo chi phí theo Option 1A
    }

    public function getTotalExpenses($dateFrom, $dateTo, $tourId = null)
    {
        return 0;
    }

    public function getTourFinancials($dateFrom = null, $dateTo = null, $filters = [])
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
                    COUNT(B.id) as booking_count,
                    COALESCE(SUM(B.final_price), 0) as revenue
                FROM tours T
                LEFT JOIN tour_categories TC ON T.category_id = TC.id
                LEFT JOIN bookings B ON T.id = B.tour_id
                {$whereClause}
                AND B.status IN ('completed', 'paid', 'hoan_tat', 'da_thanh_toan', 'da_coc')
                GROUP BY T.id, T.name, TC.name
                HAVING revenue > 0
                ORDER BY revenue DESC
                LIMIT 10";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $tours = $stmt->fetchAll();

        foreach ($tours as &$tour) {
            $tour['estimated_expense'] = 0;
            $tour['actual_expense'] = 0;
            $tour['profit'] = $tour['revenue'];
            $tour['profit_margin'] = 100;
            $tour['variance'] = 0;
        }

        return $tours;
    }

    public function getMonthlyData($year = null, $filters = [])
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

        $sql = "SELECT 
                    MONTH(B.booking_date) as month,
                    COALESCE(SUM(B.final_price), 0) as revenue,
                    COALESCE(COUNT(B.id), 0) as bookings
                FROM bookings B 
                LEFT JOIN tours T ON B.tour_id = T.id
                {$whereClause} 
                AND B.status IN ('completed', 'paid', 'hoan_tat', 'da_thanh_toan', 'da_coc')
                GROUP BY MONTH(B.booking_date)
                ORDER BY month
                LIMIT 12";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $revenueData = $stmt->fetchAll();

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenue = 0;
            $bookings = 0;

            foreach ($revenueData as $data) {
                if ($data['month'] == $month) {
                    $revenue = $data['revenue'];
                    $bookings = $data['bookings'];
                    break;
                }
            }

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue' => $revenue,
                'estimated_expense' => 0,
                'actual_expense' => 0,
                'profit' => $revenue,
                'profit_margin' => 100,
                'bookings' => $bookings
            ];
        }

        return $monthlyData;
    }

    private function getPreviousPeriodData($dateFrom, $dateTo, $filters = [])
    {
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1;
        $prevDateTo = date('Y-m-d', strtotime($dateFrom . ' -1 day'));
        $prevDateFrom = date('Y-m-d', strtotime($prevDateTo . ' -' . ($days - 1) . ' days'));
        return $this->getFinancialSummary($prevDateFrom, $prevDateTo, $filters, true);
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }
}
