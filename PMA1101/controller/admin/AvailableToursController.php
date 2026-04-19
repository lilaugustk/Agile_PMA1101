<?php

class AvailableToursController
{
    protected $tourAssignmentModel;

    public function __construct()
    {
        $this->tourAssignmentModel = new TourAssignment();
    }

    /**
     * Hiển thị trang Tour Khả Dụng
     */
    public function index()
    {
        // Chỉ HDV và Admin mới được xem
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $focusDepartureId = isset($_GET['departure_id']) ? (int)$_GET['departure_id'] : 0;
        if (!in_array($userRole, ['guide', 'admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location:' . BASE_URL_ADMIN);
            exit;
        }

        if ($userRole === 'admin') {
            // Admin xem toàn bộ departures có booking để phân công/đổi HDV
            $availableTours = $this->tourAssignmentModel->getDepartureAssignmentsForAdmin($focusDepartureId > 0 ? $focusDepartureId : null);
        } else {
            // HDV vẫn giữ danh sách tour khả dụng cũ
            $availableTours = $this->tourAssignmentModel->getAvailableTours();
        }

        // Nếu là admin, lấy danh sách HDV để phân công
        $guides = [];
        if ($userRole === 'admin') {
            $guideModel = new Guide();
            $guides = $guideModel->getAll();
        }

        include_once PATH_VIEW_ADMIN . 'pages/available_tours/index.php';
    }

    /**
     * Admin phân công HDV cho tour (AJAX endpoint)
     */
    public function assignGuide()
    {
        header('Content-Type: application/json');

        // Chỉ admin mới được phân công
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        if ($userRole !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']);
            exit;
        }

        $guideId = $_POST['guide_id'] ?? null;
        $tourId = (int)($_POST['tour_id'] ?? 0);
        $departureId = (int)($_POST['departure_id'] ?? 0);
        $departureDate = $_POST['departure_date'] ?? null;

        if (!$guideId || !$tourId || !$departureId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin HDV, tour hoặc lịch khởi hành']);
            exit;
        }

        try {
            // Nếu không có departure_date thì lấy theo departure_id
            if (!$departureDate) {
                $departureModel = new TourDeparture();
                $departure = $departureModel->findById($departureId);
                $departureDate = $departure['departure_date'] ?? null;
            }

            if (!$departureDate) {
                echo json_encode(['success' => false, 'message' => 'Không xác định được ngày khởi hành']);
                exit;
            }

            // Validate departure date
            if (strtotime($departureDate) < strtotime(date('Y-m-d'))) {
                echo json_encode(['success' => false, 'message' => 'Ngày khởi hành không được trong quá khứ']);
                exit;
            }

            // Nếu đoàn đã có assignment active thì cập nhật HDV (đổi phân công)
            $existing = $this->tourAssignmentModel->getActiveAssignmentByDeparture($departureId);

            $assignmentEndDate = $this->calculateAssignmentEndDate($tourId, $departureDate);

            // Tránh phân công trùng lịch trong cùng ngày
            $excludeAssignmentId = $existing['id'] ?? null;
            if ($this->tourAssignmentModel->guideHasAssignmentOnDate($guideId, $departureDate, $excludeAssignmentId)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'HDV đã có tour khác trong ngày ' . date('d/m/Y', strtotime($departureDate))
                ]);
                exit;
            }

            // Chặn nhận nhiều tour bị chồng khoảng thời gian diễn ra
            $overlap = $this->tourAssignmentModel->getGuideOverlappingAssignment(
                $guideId,
                $departureDate,
                $assignmentEndDate,
                $excludeAssignmentId
            );
            if ($overlap) {
                $conflictStart = !empty($overlap['start_date']) ? date('d/m/Y', strtotime($overlap['start_date'])) : 'N/A';
                $conflictEndRaw = $overlap['end_date'] ?: $overlap['start_date'];
                $conflictEnd = !empty($conflictEndRaw) ? date('d/m/Y', strtotime($conflictEndRaw)) : $conflictStart;
                echo json_encode([
                    'success' => false,
                    'message' => 'HDV đang phụ trách tour "' . ($overlap['tour_name'] ?? ('#' . ($overlap['tour_id'] ?? ''))) . '" từ ' . $conflictStart . ' đến ' . $conflictEnd . '. Không thể phân công tour chồng thời gian.'
                ]);
                exit;
            }

            if ($existing) {
                if ((int)$existing['guide_id'] === (int)$guideId) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đoàn này đã được phân công cho HDV đã chọn'
                    ]);
                    exit;
                }

                $updated = $this->tourAssignmentModel->update([
                    'guide_id' => $guideId,
                    'tour_id' => $tourId,
                    'start_date' => $departureDate,
                    'end_date' => $assignmentEndDate,
                    'status' => 'active'
                ], 'id = :id', ['id' => $existing['id']]);

                if ($updated) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đổi phân công HDV thành công'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật phân công']);
                }
            } else {
                $created = $this->tourAssignmentModel->insert([
                    'guide_id' => $guideId,
                    'tour_id' => $tourId,
                    'start_date' => $departureDate,
                    'end_date' => $assignmentEndDate,
                    'status' => 'active'
                ]);

                if (!$created) {
                    echo json_encode(['success' => false, 'message' => 'Không thể tạo phân công']);
                    exit;
                }
                echo json_encode([
                    'success' => true,
                    'message' => 'Phân công HDV thành công'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * HDV nhận tour (AJAX endpoint)
     */
    public function claimTour()
    {
        header('Content-Type: application/json');

        // Kiểm tra quyền
        $userRole = $_SESSION['user']['role'] ?? 'customer';
        $userId = $_SESSION['user']['user_id'] ?? null;

        if (!in_array($userRole, ['guide', 'admin'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền nhận tour']);
            exit;
        }

        // Get guide ID
        require_once 'models/Guide.php';
        $guideModel = new Guide();
        $guide = $guideModel->getByUserId($userId);

        if (!$guide) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy hồ sơ HDV tương ứng. Vui lòng liên hệ quản trị.']);
            exit;
        }

        $guideId = $guide['id'];
        $tourId = $_POST['tour_id'] ?? null;
        $departureId = $_POST['departure_id'] ?? null;
        $departureDate = $_POST['departure_date'] ?? null;

        if (!$tourId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin tour']);
            exit;
        }

        try {
            // Kiểm tra tour đã có HDV chưa
            if ($this->tourAssignmentModel->tourHasGuide($tourId)) {
                echo json_encode(['success' => false, 'message' => 'Tour này đã có HDV khác nhận rồi']);
                exit;
            }

            // Kiểm tra HDV đã nhận tour này chưa
            if ($this->tourAssignmentModel->isGuideAssignedToTour($guideId, $tourId)) {
                echo json_encode(['success' => false, 'message' => 'Bạn đã nhận tour này rồi']);
                exit;
            }

            // Tính tổng số khách của tour
            $bookingModel = new Booking();
            $sql = "SELECT 
                    COUNT(DISTINCT b.id) as booking_count,
                    COUNT(DISTINCT b.id) + COALESCE(SUM(bc_count.total), 0) as total_customers
                FROM bookings b
                LEFT JOIN (
                    SELECT booking_id, COUNT(*) as total 
                    FROM booking_customers 
                    GROUP BY booking_id
                ) bc_count ON b.id = bc_count.booking_id
                WHERE b.tour_id = :tour_id
                AND b.status IN ('da_coc', 'da_thanh_toan')";

            $pdo = $bookingModel->getPDO();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['tour_id' => $tourId]);
            $tourStats = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalCustomers = $tourStats['total_customers'] ?? 0;

            // Validate 15-30 người
            if ($totalCustomers < 15) {
                echo json_encode(['success' => false, 'message' => 'Tour chưa đủ 15 người. Hiện tại: ' . $totalCustomers . ' người']);
                exit;
            }

            if ($totalCustomers > 30) {
                echo json_encode(['success' => false, 'message' => 'Tour quá đông (>30 người). Cần chia nhóm. Hiện tại: ' . $totalCustomers . ' người']);
                exit;
            }

            // Lấy ngày khởi hành
            if (!$departureDate) {
                if ($departureId) {
                    // Lấy từ tour_departures
                    $departureModel = new TourDeparture();
                    $departure = $departureModel->findById($departureId);
                    $startDate = $departure['departure_date'] ?? date('Y-m-d');
                } else {
                    // Lấy ngày khởi hành sớm nhất từ bookings
                    $sql = "SELECT MIN(COALESCE(departure_date, DATE(booking_date))) as start_date 
                        FROM bookings 
                        WHERE tour_id = :tour_id 
                        AND status IN ('da_coc', 'da_thanh_toan')";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['tour_id' => $tourId]);
                    $dateInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                    $startDate = $dateInfo['start_date'] ?? date('Y-m-d');
                }
            } else {
                $startDate = $departureDate;
            }

            $assignmentEndDate = $this->calculateAssignmentEndDate($tourId, $startDate);
            $overlap = $this->tourAssignmentModel->getGuideOverlappingAssignment($guideId, $startDate, $assignmentEndDate);
            if ($overlap) {
                $conflictStart = !empty($overlap['start_date']) ? date('d/m/Y', strtotime($overlap['start_date'])) : 'N/A';
                $conflictEndRaw = $overlap['end_date'] ?: $overlap['start_date'];
                $conflictEnd = !empty($conflictEndRaw) ? date('d/m/Y', strtotime($conflictEndRaw)) : $conflictStart;
                echo json_encode([
                    'success' => false,
                    'message' => 'Bạn đã có tour "' . ($overlap['tour_name'] ?? ('#' . ($overlap['tour_id'] ?? ''))) . '" từ ' . $conflictStart . ' đến ' . $conflictEnd . '. Mỗi HDV chỉ nhận 1 tour trong thời gian diễn ra.'
                ]);
                exit;
            }

            // Gán tour cho HDV
            $assignmentData = [
                'guide_id' => $guideId,
                'tour_id' => $tourId,
                'start_date' => $startDate,
                'end_date' => $assignmentEndDate,
                'status' => 'active'
            ];

            $result = $this->tourAssignmentModel->insert($assignmentData);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => "Nhận tour thành công! Tổng {$totalCustomers} khách."
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể nhận tour']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    private function calculateAssignmentEndDate($tourId, $startDate): string
    {
        $startDate = date('Y-m-d', strtotime($startDate));
        $tourModel = new Tour();
        $tour = $tourModel->findById($tourId);
        $durationDays = (int)($tour['duration_days'] ?? 0);
        if ($durationDays < 1) {
            try {
                $pdo = BaseModel::getPdo();
                $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM tour_itineraries WHERE tour_id = :tour_id");
                $stmt->execute(['tour_id' => $tourId]);
                $durationDays = (int)($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] ?? 0);
            } catch (Throwable $e) {
                $durationDays = 0;
            }
        }
        if ($durationDays < 1) {
            $durationDays = 1;
        }
        return date('Y-m-d', strtotime($startDate . ' +' . ($durationDays - 1) . ' days'));
    }
}
