<?php
class ClientTourController
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tour();
    }

    public function home()
    {
        // 1. Fetch featured/hot tours (limit 6)
        $pdo = BaseModel::getPdo();
        $stmt = $pdo->prepare("
            SELECT t.*, tc.name as category_name 
            FROM tours t 
            LEFT JOIN tour_categories tc ON t.category_id = tc.id 
            WHERE t.status = 'active'
            ORDER BY t.id DESC 
            LIMIT 6
        ");
        $stmt->execute();
        $featuredTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch main image for each tour
        $imageModel = new TourImage();
        foreach ($featuredTours as &$t) {
            $imgs = $imageModel->getByTourId($t['id']);
            $t['main_image'] = !empty($imgs) ? $imgs[0]['image_url'] : '';
            foreach ($imgs as $img) {
                if (!empty($img['main_img'])) {
                    $t['main_image'] = $img['image_url'];
                    break;
                }
            }
        }

        // 2. Fetch categories with tour count
        $stmtCat = $pdo->prepare("
            SELECT tc.id, tc.name, tc.slug, tc.icon, (SELECT COUNT(*) FROM tours WHERE category_id = tc.id) as tour_count
            FROM tour_categories tc
        ");
        $stmtCat->execute();
        $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        // 3. Fetch upcoming tours with availability (limit 6)
        // Tours having a departure >= today, status 'open' and available seats > 0
        $stmtUpcoming = $pdo->prepare("
            SELECT 
                t.id, t.name, t.base_price,
                tc.name as category_name,
                td.departure_date,
                td.max_seats,
                td.booked_seats,
                (td.max_seats - td.booked_seats) as available_seats,
                (SELECT image_url FROM tour_gallery_images WHERE tour_id = t.id AND main_img = 1 LIMIT 1) as main_image,
                (SELECT AVG(rating) FROM tour_feedbacks WHERE tour_id = t.id) as avg_rating,
                (SELECT COUNT(*) FROM tour_feedbacks WHERE tour_id = t.id) as review_count
            FROM tours t
            JOIN tour_departures td ON t.id = td.tour_id
            INNER JOIN (
                SELECT tour_id, MIN(departure_date) as soonest_date
                FROM tour_departures
                WHERE departure_date >= CURDATE() AND status = 'open' AND max_seats > booked_seats
                GROUP BY tour_id
            ) min_td ON td.tour_id = min_td.tour_id AND td.departure_date = min_td.soonest_date
            LEFT JOIN tour_categories tc ON t.category_id = tc.id
            WHERE t.status = 'active'
            ORDER BY td.departure_date ASC
            LIMIT 6
        ");
        $stmtUpcoming->execute();
        $upcomingTours = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

        // 4. Fetch latest blogs (limit 3)
        $blogModel = new Blog();
        $latestBlogs = $blogModel->getLatest(3);

        // 4. Stats for 'Why choose us' counter
        $stats = [
            'total_tours' => $this->model->count('status = "active"'),
            'total_customers' => 1500, // Placeholder
            'years_experience' => 10
        ];

        require_once PATH_VIEW_CLIENT . 'pages/home.php';
    }

    public function index()
    {
        $pdo = BaseModel::getPdo();
        
        // Build base query
        $query = "
            SELECT t.*, tc.name as category_name 
            FROM tours t 
            LEFT JOIN tour_categories tc ON t.category_id = tc.id 
            WHERE t.status = 'active'
        ";
        $params = [];

        // Apply filters
        // 1. Keyword search
        if (!empty($_GET['q'])) {
            $query .= " AND t.name LIKE :q";
            $params['q'] = '%' . $_GET['q'] . '%';
        }

        // 2. Category filter
        if (!empty($_GET['category'])) {
            $query .= " AND tc.slug = :category";
            $params['category'] = $_GET['category'];
        }

        // 3. Price range filter
        if (!empty($_GET['min_price'])) {
            $query .= " AND t.base_price >= :min_price";
            $params['min_price'] = $_GET['min_price'];
        }
        if (!empty($_GET['max_price'])) {
            $query .= " AND t.base_price <= :max_price";
            $params['max_price'] = $_GET['max_price'];
        }

        // 4. Duration filter
        if (!empty($_GET['duration'])) {
            if ($_GET['duration'] == '1-3') {
                $query .= " AND t.duration_days BETWEEN 1 AND 3";
            } elseif ($_GET['duration'] == '4-7') {
                $query .= " AND t.duration_days BETWEEN 4 AND 7";
            } elseif ($_GET['duration'] == 'over-7') {
                $query .= " AND t.duration_days > 7";
            }
        }

        // Apply Sorting
        $sort = $_GET['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY t.base_price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY t.base_price DESC";
                break;
            case 'popular':
                $query .= " ORDER BY t.max_participants DESC"; // Placeholder for popularity
                break;
            case 'newest':
            default:
                $query .= " ORDER BY t.id DESC";
                break;
        }

        // Execute query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch Main Images
        $imageModel = new TourImage();
        foreach ($tours as &$t) {
            $imgs = $imageModel->getByTourId($t['id']);
            $t['main_image'] = !empty($imgs) ? $imgs[0]['image_url'] : '';
            foreach ($imgs as $img) {
                if (!empty($img['main_img'])) {
                    $t['main_image'] = $img['image_url'];
                    break;
                }
            }
        }

        // Fetch Categories for Sidebar
        $stmtCat = $pdo->prepare("
            SELECT tc.id, tc.name, tc.slug, (SELECT COUNT(*) FROM tours WHERE category_id = tc.id AND status='active') as tour_count
            FROM tour_categories tc
        ");
        $stmtCat->execute();
        $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

        require_once PATH_VIEW_CLIENT . 'pages/tours/index.php';
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL);
            exit;
        }

        // Fetch tour details using the same logic as Admin but for display
        $pdo = BaseModel::getPdo();
        $stmt = $pdo->prepare("
            SELECT t.*, tc.name as category_name
            FROM tours t 
            LEFT JOIN tour_categories tc ON t.category_id = tc.id 
            WHERE t.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $tour = $stmt->fetch();

        if (!$tour) {
            // Tour not found or not active
            header('Location: ' . BASE_URL);
            exit;
        }

        // Fetch related data
        // Model Tour Pricing đã bị loại bỏ

        $itineraryModel = new TourItinerary();
        $itinerarySchedule = $itineraryModel->select('*', 'tour_id = :tid', ['tid' => $id], 'day_number ASC');

        $imageModel = new TourImage();
        $images = $imageModel->getByTourId($id);

        $policyAssignmentModel = new TourPolicyAssignment();
        $assignedPolicies = $policyAssignmentModel->getByTourId($id);
        
        $policyModel = new TourPolicy();
        $policies = [];
        foreach ($assignedPolicies as $ap) {
            $p = $policyModel->findById($ap['policy_id']);
            if ($p) {
                $policies[] = $p;
            }
        }

        // Departures
        $departureModel = new TourDeparture();
        $departures = $departureModel->select('*', 'tour_id = :tid', ['tid' => $id], 'departure_date ASC');

        // Reviews and Rating stats (US51, 52)
        $reviewModel = new TourReview();
        $reviews = $reviewModel->getApprovedByTour($id);
        $reviewStats = $reviewModel->getStatsByTour($id);

        // Check for success message from booking
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        
        $errorReview = $_SESSION['error_review'] ?? null;
        $successReview = $_SESSION['success_review'] ?? null;
        unset($_SESSION['error_review'], $_SESSION['success_review']);

        require_once PATH_VIEW_CLIENT . 'pages/tours/detail.php';
    }

    /**
     * Gửi đánh giá tour (US51)
     */
    public function submitReview()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $tourId = $_POST['tour_id'] ?? null;
        $rating = (int)($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!$tourId || empty($comment) || empty($fullName)) {
            $_SESSION['error_review'] = 'Vui lòng điền đầy đủ thông tin và nội dung đánh giá.';
            header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId . '#reviews');
            exit;
        }

        $reviewModel = new TourReview();
        $data = [
            'tour_id' => $tourId,
            'user_id' => $_SESSION['user']['user_id'] ?? null,
            'full_name' => $fullName,
            'email' => $email,
            'rating' => $rating,
            'comment' => $comment,
            'status' => 'pending' // Chờ duyệt
        ];

        if ($reviewModel->insert($data)) {
            $_SESSION['success_review'] = 'Cảm ơn bạn! Đánh giá của bạn đã được gửi và đang chờ quản trị viên duyệt.';
        } else {
            $_SESSION['error_review'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }

        header('Location: ' . BASE_URL . '?action=tour-detail&id=' . $tourId . '#reviews');
        exit;
    }
}
