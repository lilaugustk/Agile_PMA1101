<?php
class DashboardController
{
    protected $model;
    protected $tourModel;
    protected $bookingModel;
    protected $userModel;
    protected $transactionModel;
    protected $guideModel;

    public function __construct()
    {
        $this->model = new Booking();
        $this->tourModel = new Tour();
        $this->bookingModel = new Booking();
        $this->userModel = new UserModel();
        $this->transactionModel = new Transaction();
        $this->guideModel = new Guide();
    }

    public function index()
    {
        // Kiểm tra quyền: chỉ admin và hdv được truy cập
        check_role(['admin', 'guide']);

        // Redirect guide về trang schedule
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole === 'guide') {
            header('Location: ' . BASE_URL_ADMIN . '&action=guide/schedule');
            exit;
        }

        $currentMonth = date('m');
        $currentYear = date('Y');
        $today = date('Y-m-d');
        $lastMonth = date('m', strtotime('first day of last month'));
        $lastMonthYear = date('Y', strtotime('first day of last month'));

        // Lấy dữ liệu tổng quan
        $monthlyRevenue = $this->bookingModel->getMonthlyRevenue($currentMonth, $currentYear);
        $lastMonthRevenue = $this->bookingModel->getMonthlyRevenue($lastMonth, $lastMonthYear);
        $lastMonthName = $this->getMonthName($lastMonth);
        $currentMonthName = $this->getMonthName($currentMonth);
        $newBookings = $this->bookingModel->getNewBookingsThisMonth($currentMonth, $currentYear);
        $ongoingTours = $this->tourModel->getOngoingTours();
        $newCustomers = $this->userModel->getNewCustomersThisMonth($currentMonth, $currentYear);

        $data = [
            // Thống kê chính
            'monthlyRevenue' => $monthlyRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'lastMonthName' => $lastMonthName,
            'currentMonthName' => $currentMonthName,
            'ongoingTours' => $ongoingTours,
            'newBookings' => $newBookings,
            'newCustomers' => $newCustomers,
            'totalGuides' => $this->guideModel->getTotalActiveGuides(),

            // Dữ liệu biểu đồ
            'revenueData' => $this->getRevenueDataByPeriod('6m'),
            'bookingStatusData' => $this->bookingModel->getBookingStatusStats(),
            'topTours' => $this->tourModel->getTopToursByRevenue(5),
            'tourCategories' => $this->tourModel->getTourCategoriesStats(),

            // Danh sách
            'pendingBookings' => $this->bookingModel->getRecentPendingBookings(5),
            'upcomingTours' => $this->tourModel->getUpcomingTours(5),
            'recentTransactions' => $this->transactionModel->getRecentTransactions(5),
            'availableGuides' => $this->guideModel->getAvailableGuides() ?? [],

            // Thông tin thời gian
            'today' => $today
        ];

        // Load view
        require_once PATH_VIEW_ADMIN . 'default/header.php';
        require_once PATH_VIEW_ADMIN . 'default/sidebar.php';
        require_once PATH_VIEW_ADMIN . 'dashboard.php';
        require_once PATH_VIEW_ADMIN . 'default/footer.php';
    }

    private function getRevenueDataByPeriod($period = '6m', $customParams = [])
    {
        $revenueData = [];
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');

        if ($period === '6m') {
            for ($i = 5; $i >= 0; $i--) {
                $month = $currentMonth - $i;
                $year = $currentYear;
                if ($month <= 0) {
                    $month += 12;
                    $year -= 1;
                }
                $revenue = $this->bookingModel->getMonthlyRevenue($month, $year);
                $revenueData[] = [
                    'label' => $this->getMonthName($month) . ' ' . $year,
                    'revenue' => $revenue
                ];
            }
        } elseif ($period === '12m') {
            for ($i = 11; $i >= 0; $i--) {
                $month = $currentMonth - $i;
                $year = $currentYear;
                if ($month <= 0) {
                    $month += 12;
                    $year -= 1;
                }
                $revenue = $this->bookingModel->getMonthlyRevenue($month, $year);
                $revenueData[] = [
                    'label' => $this->getMonthName($month) . ' ' . $year,
                    'revenue' => $revenue
                ];
            }
        } elseif ($period === 'cur_year') {
            for ($month = 1; $month <= $currentMonth; $month++) {
                $revenue = $this->bookingModel->getMonthlyRevenue($month, $currentYear);
                $revenueData[] = [
                    'label' => $this->getMonthName($month) . ' ' . $currentYear,
                    'revenue' => $revenue
                ];
            }
        } elseif ($period === 'last_year') {
            $lastYear = $currentYear - 1;
            for ($month = 1; $month <= 12; $month++) {
                $revenue = $this->bookingModel->getMonthlyRevenue($month, $lastYear);
                $revenueData[] = [
                    'label' => $this->getMonthName($month) . ' ' . $lastYear,
                    'revenue' => $revenue
                ];
            }
        } elseif ($period === 'custom' && !empty($customParams)) {
            $mFrom = (int)$customParams['month_from'];
            $yFrom = (int)$customParams['year_from'];
            $mTo = (int)$customParams['month_to'];
            $yTo = (int)$customParams['year_to'];

            $start = new DateTime("$yFrom-$mFrom-01");
            $end = new DateTime("$yTo-$mTo-01");
            
            // Loop through months between start and end
            $interval = new DateInterval('P1M');
            $periodObj = new DatePeriod($start, $interval, $end->modify('+1 day'));

            foreach ($periodObj as $dt) {
                $m = (int)$dt->format('m');
                $y = (int)$dt->format('Y');
                $revenue = $this->bookingModel->getMonthlyRevenue($m, $y);
                $revenueData[] = [
                    'label' => $this->getMonthName($m) . ' ' . $y,
                    'revenue' => $revenue
                ];
                
                // Tránh lặp vô tận/quá nhiều (giới hạn 36 tháng)
                if (count($revenueData) >= 36) break;
            }
        }

        return $revenueData;
    }

    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Tháng 1',
            'Tháng 2',
            'Tháng 3',
            'Tháng 4',
            'Tháng 5',
            'Tháng 6',
            'Tháng 7',
            'Tháng 8',
            'Tháng 9',
            'Tháng 10',
            'Tháng 11',
            'Tháng 12'
        ];
        return $months[(int)$monthNumber] ?? '';
    }

    public function getChartData()
    {
        header('Content-Type: application/json');
        
        $period = $_GET['period'] ?? '6m';
        $customParams = [
            'month_from' => $_GET['month_from'] ?? null,
            'year_from' => $_GET['year_from'] ?? null,
            'month_to' => $_GET['month_to'] ?? null,
            'year_to' => $_GET['year_to'] ?? null,
        ];
        
        $revenueData = $this->getRevenueDataByPeriod($period, $customParams);

        $data = [
            'success' => true,
            'periodLabel' => $this->getPeriodLabel($period, $customParams),
            'revenueData' => $revenueData,
            'bookingStatus' => $this->bookingModel->getBookingStatusStats(),
            'tourCategories' => $this->tourModel->getTourCategoriesStats()
        ];

        echo json_encode($data);
        exit;
    }

    private function getPeriodLabel($period, $params) {
        if ($period === '6m') return '6 Tháng gần nhất';
        if ($period === '12m') return '12 Tháng gần nhất';
        if ($period === 'cur_year') return 'Năm hiện tại (' . date('Y') . ')';
        if ($period === 'last_year') return 'Năm trước (' . (date('Y') - 1) . ')';
        if ($period === 'custom') {
            return "Từ T{$params['month_from']}/{$params['year_from']} đến T{$params['month_to']}/{$params['year_to']}";
        }
        return '';
    }
}
