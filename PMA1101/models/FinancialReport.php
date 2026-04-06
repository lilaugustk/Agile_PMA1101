<?php
require_once 'SupplierCost.php';

/**
 * Financial Report Model - Báo cáo tài chính
 */
class FinancialReport extends BaseModel
{
    protected $table = 'bookings';
    private $supplierCost;

    public function __construct()
    {
        parent::__construct();
        $this->supplierCost = new SupplierCost();
    }

    public function getFinancialSummary($dateFrom = null, $dateTo = null, $filters = [], $skipGrowth = false)
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

        // Doanh thu từ bookings đã hoàn thành
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

        // Chi phí dự toán từ nhà cung cấp (BSA)
        $costData = $this->supplierCost->getTotalCosts($dateFrom, $dateTo, $filters);
        $totalEstimatedExpense = $costData['total_costs'] ?? 0;

        // Chi phí thực tế từ Tour Logs và Departure Resources
        $totalActualExpense = 0;
        if (!empty($filters['tour_id'])) {
            $totalActualExpense = $this->getActualExpenses($filters['tour_id'], $dateFrom, $dateTo);
        } else {
            // Nếu không lọc theo tour, tính tổng toàn bộ
            $sqlActual = "SELECT 
                            (SELECT COALESCE(SUM(actual_cost), 0) FROM tour_logs 
                             WHERE date BETWEEN :d1 AND :d2) + 
                            (SELECT COALESCE(SUM(dr.total_amount), 0) FROM departure_resources dr
                             JOIN tour_departures td ON dr.departure_id = td.id
                             WHERE td.departure_date BETWEEN :d1 AND :d2) as total";
            $stmt = self::$pdo->prepare($sqlActual);
            $stmt->execute([':d1' => $dateFrom, ':d2' => $dateTo]);
            $result = $stmt->fetch();
            $totalActualExpense = $result['total'] ?? 0;
        }

        // Tính toán lợi nhuận và các chỉ số
        $totalRevenue = $revenueData['total_revenue'] ?? 0;
        
        // Lợi nhuận dự toán
        $estimatedProfit = $totalRevenue - $totalEstimatedExpense;
        $estimatedProfitMargin = $totalRevenue > 0 ? ($estimatedProfit / $totalRevenue) * 100 : 0;
        
        // Lợi nhuận thực tế
        $actualProfit = $totalRevenue - $totalActualExpense;
        $actualProfitMargin = $totalRevenue > 0 ? ($actualProfit / $totalRevenue) * 100 : 0;

        // Lấy dữ liệu kỳ trước để tính growth (chỉ khi không skip)
        $revenueGrowth = 0;
        $expenseGrowth = 0;
        $profitGrowth = 0;

        if (!$skipGrowth) {
            $previousPeriodData = $this->getPreviousPeriodData($dateFrom, $dateTo, $filters);
            $revenueGrowth = $this->calculateGrowth($totalRevenue, $previousPeriodData['total_revenue']);
            $expenseGrowth = $this->calculateGrowth($totalActualExpense, $previousPeriodData['total_actual_expense']);
            $profitGrowth = $this->calculateGrowth($actualProfit, $previousPeriodData['actual_profit']);
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_estimated_expense' => $totalEstimatedExpense,
            'total_actual_expense' => $totalActualExpense,
            'estimated_profit' => $estimatedProfit,
            'estimated_profit_margin' => $estimatedProfitMargin,
            'actual_profit' => $actualProfit,
            'actual_profit_margin' => $actualProfitMargin,
            'total_bookings' => $revenueData['total_bookings'] ?? 0,
            'avg_booking_value' => $revenueData['avg_booking_value'] ?? 0,
            'cost_count' => $costData['cost_count'] ?? 0,
            'avg_cost' => $costData['avg_cost'] ?? 0,
            'revenue_growth' => $revenueGrowth,
            'expense_growth' => $expenseGrowth,
            'profit_growth' => $profitGrowth
        ];
    }

    /**
     * Lấy chi phí thực tế từ bảng tour_logs và departure_resources
     */
    public function getActualExpenses($tourId, $dateFrom, $dateTo)
    {
        // Phối hợp chi phí từ tour_logs và departure_resources
        $sql = "SELECT 
                    (SELECT COALESCE(SUM(actual_cost), 0) FROM tour_logs 
                     WHERE tour_id = :tid AND date BETWEEN :d1 AND :d2) + 
                    (SELECT COALESCE(SUM(dr.total_amount), 0) FROM departure_resources dr
                     JOIN tour_departures td ON dr.departure_id = td.id
                     WHERE td.tour_id = :tid AND td.departure_date BETWEEN :d1 AND :d2) as total";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([':tid' => $tourId, ':d1' => $dateFrom, ':d2' => $dateTo]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Lấy chi phí tổng hợp (dựa trên booking_suppliers_assignment)
     */
    public function getTotalExpenses($dateFrom, $dateTo, $tourId = null)
    {
        $whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
        $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];

        if ($tourId) {
            $whereConditions[] = "B.tour_id = :tour_id";
            $params[':tour_id'] = $tourId;
        }

        $whereClause = "WHERE " . implode(' AND ', $whereConditions);

        // Chi phí thực tế từ booking_suppliers_assignment
        $sql = "SELECT 
                    COALESCE(SUM(BSA.quantity * BSA.price), 0) as total_expense
                FROM booking_suppliers_assignment BSA
                LEFT JOIN bookings B ON BSA.booking_id = B.id 
                {$whereClause} 
                AND B.status IN ('completed', 'paid', 'da_coc')";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_expense'] ?? 0;
    }

    /**
     * Lấy báo cáo chi tiết theo từng tour
     */
    public function getTourFinancials($dateFrom = null, $dateTo = null, $filters = [])
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

        // Lấy chi phí thực tế cho từng tour
        foreach ($tours as &$tour) {
            $tourFilters = array_merge($filters, ['tour_id' => $tour['tour_id']]);
            $costData = $this->supplierCost->getTotalCosts($dateFrom, $dateTo, $tourFilters);

            $tour['estimated_expense'] = $costData['total_costs'] ?? 0;
            $tour['actual_expense'] = $this->getActualExpenses($tour['tour_id'], $dateFrom, $dateTo);
            
            $tour['profit'] = $tour['revenue'] - $tour['actual_expense'];
            $tour['profit_margin'] = $tour['revenue'] > 0 ? ($tour['profit'] / $tour['revenue']) * 100 : 0;
            $tour['variance'] = $tour['estimated_expense'] - $tour['actual_expense'];
        }

        // Sắp xếp lại theo lợi nhuận
        usort($tours, function ($a, $b) {
            return $b['profit'] - $a['profit'];
        });

        return $tours;
    }

    /**
     * Lấy dữ liệu theo tháng cho biểu đồ
     */
    public function getMonthlyData($year = null, $filters = [])
    {
        $year = $year ?? date('Y');

        $whereConditions = ["YEAR(B.booking_date) = :year"];
        $params = [':year' => $year];

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

        // Lấy chi phí dự kiến từ BSA theo tháng
        $costFilters = $filters;
        $costFilters['year'] = $year;
        $estimatedExpenseData = $this->supplierCost->getMonthlyCosts($year, $costFilters);

        // Chi phí thực tế tổng hợp (Tour Logs + Departure Resources)
        $sqlActual = "SELECT month, SUM(total_actual) as total_actual FROM (
                        SELECT MONTH(d.departure_date) as month, 
                            (SELECT COALESCE(SUM(actual_cost), 0) FROM tour_logs l
                             JOIN tour_departures d2 ON l.tour_id = d2.tour_id 
                             WHERE MONTH(d2.departure_date) = MONTH(d.departure_date) AND YEAR(d2.departure_date) = :year) + 
                            (SELECT COALESCE(SUM(total_amount), 0) FROM departure_resources r
                             JOIN tour_departures d3 ON r.departure_id = d3.id
                             WHERE MONTH(d3.departure_date) = MONTH(d.departure_date) AND YEAR(d3.departure_date) = :year) as total_actual
                        FROM tour_departures d
                        WHERE YEAR(d.departure_date) = :year
                      ) subquery 
                      GROUP BY month";
        $stmtActual = self::$pdo->prepare($sqlActual);
        $stmtActual->execute([':year' => $year]);
        $actualExpenseData = $stmtActual->fetchAll();

        // Gộp dữ liệu
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenue = 0;
            $estExpense = 0;
            $actExpense = 0;
            $bookings = 0;

            foreach ($revenueData as $data) {
                if ($data['month'] == $month) {
                    $revenue = $data['revenue'];
                    $bookings = $data['bookings'];
                    break;
                }
            }

            foreach ($estimatedExpenseData as $data) {
                if ($data['month'] == $month) {
                    $estExpense = $data['total_costs'];
                    break;
                }
            }

            foreach ($actualExpenseData as $data) {
                if ($data['month'] == $month) {
                    $actExpense = $data['total_actual'];
                    break;
                }
            }

            $profit = $revenue - $actExpense;
            $profitMargin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue' => $revenue,
                'estimated_expense' => $estExpense,
                'actual_expense' => $actExpense,
                'profit' => $profit,
                'profit_margin' => $profitMargin,
                'bookings' => $bookings
            ];
        }

        return $monthlyData;
    }

    /**
     * Lấy dữ liệu kỳ trước để so sánh
     */
    private function getPreviousPeriodData($dateFrom, $dateTo, $filters = [])
    {
        // Tính khoảng thời gian kỳ trước
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1;
        $prevDateTo = date('Y-m-d', strtotime($dateFrom . ' -1 day'));
        $prevDateFrom = date('Y-m-d', strtotime($prevDateTo . ' -' . ($days - 1) . ' days'));

        // Skip growth calculation để tránh vòng lặp vô hạn
        return $this->getFinancialSummary($prevDateFrom, $prevDateTo, $filters, true);
    }

    /**
     * Tính toán tỷ lệ tăng trưởng
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }
}
