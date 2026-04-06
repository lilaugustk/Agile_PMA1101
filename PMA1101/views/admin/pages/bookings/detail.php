<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Helper for price formatting
function formatPrice($price)
{
    if ($price >= 1000000000) {
        return round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
    } elseif ($price >= 1000000) {
        return round($price / 1000000, 1) . ' tr';
    } else {
        return number_format($price, 0, ',', '.') . 'đ';
    }
}

// Status mapping
$statusMap = [
    'cho_xac_nhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning', 'icon' => 'clock'],
    'da_coc' => ['text' => 'Đã cọc', 'class' => 'info', 'icon' => 'credit-card'],
    'hoan_tat' => ['text' => 'Hoàn tất', 'class' => 'success', 'icon' => 'check-circle'],
    'da_huy' => ['text' => 'Đã hủy', 'class' => 'danger', 'icon' => 'times-circle'],
    'pending' => ['text' => 'Chờ thanh toán', 'class' => 'warning', 'icon' => 'hourglass-medium'],
    'expired' => ['text' => 'Hết hạn', 'class' => 'secondary', 'icon' => 'calendar-x']
];

$currentStatus = $statusMap[$booking['status']] ?? ['text' => 'Unknown', 'class' => 'secondary', 'icon' => 'question'];

// Check edit permission - Chỉ admin mới được edit
$userRole = $_SESSION['user']['role'] ?? 'customer';
$canEdit = ($userRole === 'admin');
?>

<main class="dashboard booking-detail-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header mb-4">
            <div class="header-content d-flex justify-content-between align-items-center">
                <div class="header-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph-fill ph-house me-1"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="text-muted text-decoration-none"><i class="ph-fill ph-calendar-check me-1"></i> Quản lý Booking</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết #<?= $booking['id'] ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="header-right d-flex gap-2">
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-light border shadow-sm px-3 py-2">
                        <i class="ph ph-arrow-left me-1"></i> Quay lại
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings/exportInvoice&id=<?= $booking['id'] ?>" target="_blank" class="btn btn-outline-info d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                        <i class="ph ph-printer"></i> In Hóa Đơn
                    </a>
                    <?php if ($canEdit): ?>
                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings/edit&id=<?= $booking['id'] ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                            <i class="ph-fill ph-pencil-simple"></i> Chỉnh sửa
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="ph-bold ph-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="ph-bold ph-warning-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <section class="stats-section mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="stat-card-premium border-0 shadow-sm bg-white stat-primary">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="ph-fill ph-currency-circle-dollar"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label text-muted small fw-bold">Tổng tiền đơn đặt</span>
                            <h3 class="stat-value text-dark fw-800 mb-0"><?= formatPrice($booking['total_price'] ?? 0) ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card-premium border-0 shadow-sm bg-white stat-<?= $currentStatus['class'] ?>">
                        <div class="stat-icon bg-<?= $currentStatus['class'] ?> bg-opacity-10 text-<?= $currentStatus['class'] ?>">
                            <i class="ph-fill ph-pulse"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label text-muted small fw-bold">Trạng thái hiện tại</span>
                            <h3 class="stat-value text-dark fw-800 mb-0"><?= $currentStatus['text'] ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card-premium border-0 shadow-sm bg-white stat-info">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="ph-fill ph-calendar-blank"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label text-muted small fw-bold">Ngày xác lập</span>
                            <h3 class="stat-value text-dark fw-800 mb-0"><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card-premium border-0 shadow-sm bg-white stat-success">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="ph-fill ph-users-three"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label text-muted small fw-bold">Tổng số khách</span>
                            <h3 class="stat-value text-dark fw-800 mb-0"><?= (int)$booking['adults'] + (int)$booking['children'] + (int)$booking['infants'] ?> khách</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Booking Information Card -->
                <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                    <div class="card-header-premium p-3 px-4 border-bottom border-light">
                        <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                            <i class="ph-fill ph-info"></i> Thông tin đơn đặt
                        </h6>
                    </div>
                    <div class="card-body-premium p-4">
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small fw-bold d-block mb-1">Mã tham chiếu</label>
                                <div class="fw-bold text-dark">#<?= htmlspecialchars($booking['id']) ?></div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small fw-bold d-block mb-1">Thời điểm khởi tạo</label>
                                <div class="text-dark"><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small fw-bold d-block mb-1">Giá trị giao dịch</label>
                                <div class="text-danger fw-800 fs-5"><?= number_format($booking['total_price'], 0, ',', '.') ?> ₫</div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="text-muted small fw-bold d-block mb-1 text-uppercase">Trạng thái vận hành</label>
                                <div class="d-flex align-items-center mt-1">
                                    <span id="status-badge" class="badge-premium badge-<?= $currentStatus['class'] ?> shadow-sm px-3 py-2 rounded-pill" data-status="<?= $booking['status'] ?>" style="font-size: 0.75rem;">
                                        <i class="ph-fill ph-circle me-1" style="font-size: 6px;"></i>
                                        <?= $currentStatus['text'] ?>
                                    </span>
                                    <?php if ($canEdit): ?>
                                        <div class="dropdown ms-2">
                                            <button class="btn btn-sm btn-light border-0 p-0 fs-4 bg-transparent text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ph-bold ph-dots-three-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                                <li><a class="dropdown-item py-2 status-change-btn" href="#" data-status="cho_xac_nhan" data-booking-id="<?= $booking['id'] ?>"><i class="ph ph-clock me-2"></i> Chờ xác nhận</a></li>
                                                <li><a class="dropdown-item py-2 status-change-btn" href="#" data-status="da_coc" data-booking-id="<?= $booking['id'] ?>"><i class="ph ph-credit-card me-2"></i> Đã cọc</a></li>
                                                <li><a class="dropdown-item py-2 status-change-btn" href="#" data-status="hoan_tat" data-booking-id="<?= $booking['id'] ?>"><i class="ph ph-check-circle me-2"></i> Hoàn tất</a></li>
                                                <li><hr class="dropdown-divider opacity-50"></li>
                                                <li><a class="dropdown-item py-2 status-change-btn" href="#" data-status="pending" data-booking-id="<?= $booking['id'] ?>"><i class="ph ph-hourglass-medium me-2"></i> Chờ thanh toán</a></li>
                                                <li><a class="dropdown-item py-2 status-change-btn text-danger" href="#" data-status="da_huy" data-booking-id="<?= $booking['id'] ?>"><i class="ph ph-x-circle me-2"></i> Hủy đơn</a></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($booking['status'] === 'pending' && !empty($booking['expires_at'])): ?>
                                <div class="col-12 mt-3">
                                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 p-2 mb-0 rounded-3 small text-warning-emphasis d-flex align-items-center gap-2">
                                        <i class="ph-fill ph-timer fs-5"></i>
                                        <span>Giữ chỗ sẽ hết hạn vào: <strong><?= date('H:i d/m/Y', strtotime($booking['expires_at'])) ?></strong></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Guests List Card -->
                <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden">
                    <div class="card-header-premium p-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center bg-white">
                        <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                            <i class="ph-fill ph-users-three"></i> Danh sách hành khách (<?= count($companions) + 1 ?>)
                        </h6>
                        <?php if ($canEdit): ?>
                            <button type="button" class="btn btn-sm btn-primary-light d-flex align-items-center gap-1 px-3 shadow-none border" id="add-companion-btn" data-booking-id="<?= $booking['id'] ?>">
                                <i class="ph-bold ph-plus"></i> Thêm khách
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body-premium p-4">
                        <!-- Main Customer -->
                        <div class="guest-card-premium border rounded-4 p-4 mb-4 position-relative overflow-hidden" 
                             style="background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.05), transparent);">
                            <div class="position-absolute top-0 end-0 p-3">
                                <span class="badge bg-primary px-3 py-2 rounded-pill small" style="letter-spacing: 0.5px;">Hành khách chính</span>
                            </div>
                            
                            <div class="d-flex align-items-start gap-4">
                                <div class="guest-avatar-large bg-primary text-white fs-1 d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 72px; height: 72px; min-width: 72px;">
                                    <i class="ph-fill ph-user-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="border-bottom pb-2 mb-3">
                                        <h4 class="fw-800 text-dark mb-1">
                                            <?= htmlspecialchars($booking['contact_name'] ?: ($booking['customer_name'] ?: 'Khách vãng lai')) ?>
                                        </h4>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="text-muted small d-flex align-items-center gap-1">
                                                <i class="ph ph-envelope"></i>
                                                <?= htmlspecialchars($booking['contact_email'] ?: ($booking['customer_email'] ?: 'N/A')) ?>
                                            </span>
                                            <span class="text-muted small d-flex align-items-center gap-1">
                                                <i class="ph ph-phone"></i>
                                                <?= htmlspecialchars($booking['contact_phone'] ?: ($booking['customer_phone'] ?: 'N/A')) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($booking['contact_address'])): ?>
                                            <div class="text-muted small mt-1">
                                                <i class="ph ph-map-pin"></i> <?= htmlspecialchars($booking['contact_address']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="passenger-badge text-center">
                                            <div class="fw-bold text-dark fs-5"><?= $booking['adults'] ?></div>
                                            <div class="extra-small text-muted text-uppercase">Người lớn</div>
                                        </div>
                                        <div class="passenger-badge text-center">
                                            <div class="fw-bold text-dark fs-5"><?= $booking['children'] ?></div>
                                            <div class="extra-small text-muted text-uppercase">Trẻ em</div>
                                        </div>
                                        <div class="passenger-badge text-center">
                                            <div class="fw-bold text-dark fs-5"><?= $booking['infants'] ?></div>
                                            <div class="extra-small text-muted text-uppercase">Em bé</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Companions -->
                        <?php if (!empty($companions)): ?>
                            <div class="row g-3">
                                <?php foreach ($companions as $index => $companion): ?>
                                    <div class="col-md-6">
                                        <div class="guest-card-premium p-3 border rounded-3 bg-light bg-opacity-25 h-100 position-relative hover-shadow-sm transition-all">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="guest-avatar-sm bg-white text-dark me-2 d-flex align-items-center justify-content-center border shadow-sm" style="width: 32px; height: 32px; border-radius: 8px;">
                                                        <i class="ph-fill ph-user"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-muted extra-small mb-0 fw-bold">Khách #<?= $index + 1 ?></p>
                                                        <h6 class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($companion['full_name']) ?></h6>
                                                    </div>
                                                </div>
                                                <?php if ($canEdit): ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light border-0 p-1 bg-transparent text-muted" type="button" data-bs-toggle="dropdown">
                                                            <i class="ph-bold ph-dots-three"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                            <li><a class="dropdown-item py-2 small edit-companion-btn" href="#" 
                                                                data-companion-id="<?= $companion['id'] ?>"
                                                                data-companion='<?= json_encode([
                                                                                    "name" => $companion["full_name"] ?? "",
                                                                                    "gender" => $companion["gender"] ?? "",
                                                                                    "birth_date" => $companion["birth_date"] ?? "",
                                                                                    "phone" => $companion["phone"] ?? "",
                                                                                    "id_card" => $companion["id_card"] ?? "",
                                                                                    "room_type" => $companion["room_type"] ?? "",
                                                                                    "passenger_type" => $companion["passenger_type"] ?? "adult",
                                                                                    "special_request" => $companion["special_request"] ?? ""
                                                                                ]) ?>'>
                                                                <i class="ph ph-pencil-simple me-2"></i>Sửa</a></li>
                                                            <li><a class="dropdown-item py-2 small text-danger delete-companion-btn" href="#" 
                                                                data-companion-id="<?= $companion['id'] ?>" data-booking-id="<?= $booking['id'] ?>">
                                                                <i class="ph ph-trash me-2"></i>Xóa</a></li>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="row g-2 border-top pt-2 mt-1">
                                                <div class="col-4">
                                                    <div class="info-mini">
                                                        <small class="text-muted extra-small fw-bold">Giới tính</small>
                                                        <p class="mb-0 small text-dark"><?= htmlspecialchars($companion['gender'] ?? 'N/A') ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="info-mini">
                                                        <small class="text-muted extra-small fw-bold">Phòng</small>
                                                        <p class="mb-0 small text-dark"><?= htmlspecialchars($companion['room_type'] ?? 'N/A') ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="info-mini">
                                                        <small class="text-muted extra-small fw-bold">Loại</small>
                                                        <p class="mb-0 small text-dark text-capitalize"><?= htmlspecialchars($companion['passenger_type'] ?? 'adult') ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="p-2 rounded-2 bg-white border border-dashed text-muted extra-small d-flex justify-content-between align-items-center">
                                                        <div class="text-truncate" style="max-width: 80%;">
                                                            <i class="ph ph-note-pencil me-1"></i>
                                                            <?= !empty($companion['special_request']) ? htmlspecialchars($companion['special_request']) : 'Không có yêu cầu' ?>
                                                        </div>
                                                        <?php if ($canEdit): ?>
                                                            <button class="btn-text-only p-0 border-0 bg-transparent text-primary edit-special-request-btn"
                                                                data-companion-id="<?= $companion['id'] ?>"
                                                                data-booking-id="<?= $booking['id'] ?>"
                                                                data-current-request="<?= htmlspecialchars($companion['special_request'] ?? '') ?>">
                                                                <i class="ph-bold ph-pencil-simple"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5 border rounded-4 border-dashed bg-light bg-opacity-25">
                                <i class="ph-fill ph-user-plus fs-1 mb-2 opacity-25"></i>
                                <p class="small mb-0 fw-medium">Chưa ghi nhận khách đi cùng cho đơn đặt này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Calculator Card -->
                <?php if ($canEdit && !empty($booking['version_id'])): ?>
                    <?php
                    $bookingCustomerModel = new BookingCustomer();
                    $calculation = $bookingCustomerModel->calculateTotalPrice($booking['id'], $booking['tour_id'], $booking['version_id']);
                    $breakdown = $calculation['breakdown'];
                    ?>
                    <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden rounded-4">
                        <div class="p-3 px-4 bg-warning bg-opacity-10 border-bottom border-warning border-opacity-25">
                            <h6 class="fw-bold mb-0 text-warning-emphasis small d-flex align-items-center gap-2">
                                <i class="ph-fill ph-calculator"></i> TỔNG HỢP CHI PHÍ ƯỚC TÍNH
                            </h6>
                        </div>
                        <div class="card-body-premium p-4">
                            <div class="price-breakdown d-grid gap-3">
                                <?php foreach ($breakdown as $type => $data): ?>
                                    <?php if ($data['count'] > 0): ?>
                                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom border-dashed">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-light p-1 rounded-2 text-muted">
                                                    <i class="ph-fill ph-<?= $type == 'adults' ? 'user' : ($type == 'children' ? 'child' : 'baby') ?>"></i>
                                                </div>
                                                <span class="text-muted small fw-medium"><?= $type == 'adults' ? 'Người lớn' : ($type == 'children' ? 'Trẻ em' : 'Em bé') ?> (×<?= $data['count'] ?>)</span>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-dark small fw-bold"><?= number_format($data['subtotal']) ?> ₫</div>
                                                <div class="extra-small text-muted"><?= number_format($data['price']) ?> ₫/khách</div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <div class="total-recalc-box mt-4 p-4 bg-danger bg-opacity-10 rounded-4 border border-danger border-opacity-10 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-danger extra-small fw-bold text-uppercase mb-1 letter-spacing-1">Ước tính lại tổng thu</p>
                                    <h3 class="mb-0 text-danger fw-800"><?= number_format($calculation['total']) ?> ₫</h3>
                                </div>
                                <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=<?= $booking['id'] ?>">
                                    <input type="hidden" name="update_price" value="1">
                                    <input type="hidden" name="calculated_total" value="<?= $calculation['total'] ?>">
                                    <button type="submit" class="btn btn-danger-premium rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2" onclick="return confirm('Cập nhật tổng tiền đơn hàng thành <?= number_format($calculation['total']) ?> ₫?')">
                                        <i class="ph-fill ph-arrows-counter-clockwise"></i>
                                        Cập nhật
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notes Card -->
                <?php if (!empty($booking['notes'])): ?>
                    <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden rounded-4">
                        <div class="p-3 px-4 bg-light border-bottom">
                            <h6 class="fw-bold mb-0 text-muted small d-flex align-items-center gap-2">
                                <i class="ph-fill ph-note"></i> GHI CHÚ ĐIỀU HÀNH
                            </h6>
                        </div>
                        <div class="card-body-premium p-4">
                            <div class="p-3 bg-light bg-opacity-50 rounded-3 border-start border-4 border-warning small text-muted lh-base">
                                <?= nl2br(htmlspecialchars($booking['notes'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <!-- Tour Profile Card -->
                <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden rounded-4">
                    <div class="p-3 px-4 bg-light bg-opacity-50 border-bottom">
                        <h6 class="fw-bold mb-0 text-success small d-flex align-items-center gap-2">
                            <i class="ph-fill ph-path"></i> THÔNG TIN TOUR
                        </h6>
                    </div>
                    <div class="p-4">
                        <div class="tour-small-card mb-4 p-3 bg-light rounded-3">
                            <h6 class="fw-bold text-dark mb-2"><?= htmlspecialchars($booking['tour_name'] ?? 'Chưa cập nhật') ?></h6>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="ph-fill ph-tag me-2 text-success"></i>
                                <span>Giá niêm yết: <strong><?= number_format($booking['tour_base_price'] ?? 0) ?> ₫</strong></span>
                            </div>
                        </div>
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tour['id'] ?>" class="btn btn-outline-success w-100 py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-medium">
                            <i class="ph-bold ph-eye"></i> Chi tiết sản phẩm
                        </a>
                    </div>
                </div>

                <!-- Assignment Card -->
                <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden rounded-4">
                    <div class="p-3 px-4 bg-light bg-opacity-50 border-bottom">
                        <h6 class="fw-bold mb-0 text-info small d-flex align-items-center gap-2">
                            <i class="ph-fill ph-users-four"></i> ĐIỀU HÀNH & VẬN TẢI
                        </h6>
                    </div>
                    <div class="p-4">
                        <div class="assignment-list d-grid gap-4">
                            <div class="assignment-item">
                                <p class="text-muted extra-small fw-bold text-uppercase mb-2 letter-spacing-1">Hướng dẫn viên chỉ định</p>
                                <div class="d-flex align-items-center p-2 rounded-3 border bg-light-subtle">
                                    <div class="avatar-circle-sm bg-white shadow-sm text-info me-3 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="ph-fill ph-identification-card fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark small"><?= htmlspecialchars($booking['guide_name'] ?? 'Chưa phân công') ?></span>
                                </div>
                            </div>
                            <div class="assignment-item">
                                <p class="text-muted extra-small fw-bold text-uppercase mb-2 letter-spacing-1">Nhà xe / Đơn vị vận chuyển</p>
                                <div class="d-flex align-items-center p-2 rounded-3 border bg-light-subtle">
                                    <div class="avatar-circle-sm bg-white shadow-sm text-primary me-3 d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="ph-fill ph-bus fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark small"><?= htmlspecialchars($booking['bus_company_name'] ?? 'Chưa có thông tin') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suppliers Assignment Details -->
                <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden rounded-4">
                    <div class="p-3 px-4 bg-light bg-opacity-50 border-bottom">
                        <h6 class="fw-bold mb-0 text-warning small d-flex align-items-center gap-2">
                            <i class="ph-fill ph-buildings"></i> CUNG ỨNG DỊCH VỤ
                        </h6>
                    </div>
                    <div class="p-4">
                        <?php
                        $bsaModel = new BookingSupplierAssignment();
                        $bookingSuppliers = $bsaModel->getByBookingId($booking['id']);
                        ?>

                        <?php if (!empty($bookingSuppliers)): ?>
                            <div class="supplier-compact-list">
                                <?php foreach ($bookingSuppliers as $bs): ?>
                                    <div class="supplier-compact-item p-2 border-bottom mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold small text-truncate" style="max-width: 70%;"><?= htmlspecialchars($bs['supplier_name']) ?></span>
                                            <span class="badge bg-light text-dark extra-small"><?= htmlspecialchars($bs['service_type']) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1 extra-small text-muted">
                                            <span>Số lượng: <?= $bs['quantity'] ?></span>
                                            <span class="text-primary"><?= number_format($bs['price']) ?> ₫</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="alert alert-light border mt-3 mb-0 p-2 text-center">
                                <small class="text-muted">Tổng chi phí:</small>
                                <strong class="ms-1 text-danger"><?= number_format($bsaModel->getTotalCostByBookingId($booking['id'])) ?> ₫</strong>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted">
                                <i class="ph-bold ph-building-office text-light fa-2x mb-2"></i>
                                <p class="small mb-0">Không có dữ liệu supplier</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- Companion Modal -->
    <div class="modal fade" id="companionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companionModalTitle">Thêm Khách Đi Kèm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="companionForm">
                        <input type="hidden" id="companion-id" name="companion_id">
                        <input type="hidden" id="companion-booking-id" name="booking_id" value="<?= $booking['id'] ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="companion-name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giới tính</label>
                                <select class="form-select" id="companion-gender" name="gender">
                                    <option value="">Chọn</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" class="form-control" id="companion-birth-date" name="birth_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Điện thoại</label>
                                <input type="tel" class="form-control" id="companion-phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">CMND/Hộ chiếu</label>
                                <input type="text" class="form-control" id="companion-id-card" name="id_card">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại phòng</label>
                                <select class="form-select" id="companion-room-type" name="room_type">
                                    <option value="">Chọn loại phòng</option>
                                    <option value="đơn">Phòng đơn</option>
                                    <option value="đôi">Phòng đôi</option>
                                    <option value="ghép">Ghép phòng</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại khách <span class="text-danger">*</span></label>
                                <select class="form-select" id="companion-passenger-type" name="passenger_type" required>
                                    <option value="">Chọn loại khách</option>
                                    <option value="adult" selected>Người lớn</option>
                                    <option value="child">Trẻ em</option>
                                    <option value="infant">Em bé</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                                <textarea class="form-control" id="companion-special-request" name="special_request" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveCompanionBtn">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="specialRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Yêu Cầu Đặc Biệt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="specialRequestForm">
                        <input type="hidden" id="sr-companion-id">
                        <input type="hidden" id="sr-booking-id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                            <textarea class="form-control" id="sr-special-request" rows="4"
                                placeholder="Ví dụ: Ăn chay, dị ứng hải sản, cần xe lăn..."></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Ghi chú các yêu cầu đặc biệt của khách để phục vụ tốt hơn
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveSpecialRequestBtn">
                        <i class="fas fa-save me-1"></i>Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Special Request Update
    document.addEventListener('DOMContentLoaded', function() {
        const specialRequestModal = new bootstrap.Modal(document.getElementById('specialRequestModal'));

        // Open modal
        document.querySelectorAll('.edit-special-request-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companionId = this.dataset.companionId;
                const bookingId = this.dataset.bookingId;
                const currentRequest = this.dataset.currentRequest;

                document.getElementById('sr-companion-id').value = companionId;
                document.getElementById('sr-booking-id').value = bookingId;
                document.getElementById('sr-special-request').value = currentRequest;

                specialRequestModal.show();
            });
        });

        // Save special request
        document.getElementById('saveSpecialRequestBtn').addEventListener('click', function() {
            const companionId = document.getElementById('sr-companion-id').value;
            const bookingId = document.getElementById('sr-booking-id').value;
            const specialRequest = document.getElementById('sr-special-request').value;

            // AJAX call
            fetch('<?= BASE_URL_ADMIN ?>&action=bookings/updateSpecialRequest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        companion_id: companionId,
                        booking_id: bookingId,
                        special_request: specialRequest
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const displayElement = document.getElementById('special-request-' + companionId);
                        if (displayElement) {
                            displayElement.innerHTML = specialRequest || '<span class="text-muted">Chưa có</span>';
                        }

                        // Update button data
                        const btn = document.querySelector(`[data-companion-id="${companionId}"]`);
                        if (btn) {
                            btn.dataset.currentRequest = specialRequest;
                        }

                        // Show success message
                        alert(data.message);
                        specialRequestModal.hide();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật');
                });
        });
    });
</script>

<script>
    // Companion Management
    document.addEventListener('DOMContentLoaded', function() {
        const companionModalEl = document.getElementById('companionModal');
        const companionModal = new bootstrap.Modal(companionModalEl, {
            backdrop: true, // Allow closing by clicking backdrop
            keyboard: true // Allow closing with ESC key
        });

        // Close button listeners (X button and Cancel button)
        const closeButtons = companionModalEl.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                companionModal.hide();
            });
        });

        // Add companion button
        const addBtn = document.getElementById('add-companion-btn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                // Reset form for adding
                document.getElementById('companionModalTitle').textContent = 'Thêm Khách Đi Kèm';
                document.getElementById('companionForm').reset();
                document.getElementById('companion-id').value = '';
                companionModal.show();
            });
        }

        // Edit companion buttons
        document.querySelectorAll('.edit-companion-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companionId = this.dataset.companionId;
                const data = JSON.parse(this.dataset.companion);

                // Set modal title
                document.getElementById('companionModalTitle').textContent = 'Chỉnh Sửa Khách Đi Kèm';

                // Set companion ID for update
                document.getElementById('companion-id').value = companionId;

                // Populate form with companion data
                document.getElementById('companion-name').value = data.name || '';
                document.getElementById('companion-gender').value = data.gender || '';
                document.getElementById('companion-birth-date').value = data.birth_date || '';
                document.getElementById('companion-phone').value = data.phone || '';
                document.getElementById('companion-id-card').value = data.id_card || '';
                document.getElementById('companion-room-type').value = data.room_type || '';
                document.getElementById('companion-passenger-type').value = data.passenger_type || 'adult';
                document.getElementById('companion-special-request').value = data.special_request || '';

                // Show modal
                companionModal.show();
            });
        });

        // Delete companion buttons
        document.querySelectorAll('.delete-companion-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companionId = this.dataset.companionId;
                const bookingId = this.dataset.bookingId;
                const card = this.closest('.guest-item');
                const name = card.querySelector('h6').textContent.trim();

                if (!confirm(`Bạn có chắc muốn xóa khách "${name}"?`)) {
                    return;
                }

                // Delete via AJAX
                fetch('<?= BASE_URL_ADMIN ?>&action=bookings/deleteCompanion', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            companion_id: companionId,
                            booking_id: bookingId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa khách');
                    });
            });
        });

        // Save companion button
        const saveBtn = document.getElementById('saveCompanionBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const companionId = document.getElementById('companion-id').value;
                const bookingId = document.getElementById('companion-booking-id').value;
                const name = document.getElementById('companion-name').value.trim();
                const passengerType = document.getElementById('companion-passenger-type').value;

                if (!name) {
                    alert('Vui lòng nhập họ tên khách');
                    return;
                }

                if (!passengerType) {
                    alert('Vui lòng chọn loại khách');
                    return;
                }

                const formData = new URLSearchParams({
                    booking_id: bookingId,
                    name: name,
                    gender: document.getElementById('companion-gender').value,
                    birth_date: document.getElementById('companion-birth-date').value,
                    phone: document.getElementById('companion-phone').value,
                    id_card: document.getElementById('companion-id-card').value,
                    room_type: document.getElementById('companion-room-type').value,
                    passenger_type: passengerType,
                    special_request: document.getElementById('companion-special-request').value
                });

                const url = companionId ?
                    '<?= BASE_URL_ADMIN ?>&action=bookings/updateCompanion' :
                    '<?= BASE_URL_ADMIN ?>&action=bookings/addCompanion';

                if (companionId) {
                    formData.append('companion_id', companionId);
                }

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Don't show alert here, page reload will show session message
                            companionModal.hide();
                            location.reload();
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Even if JSON parse fails, the update likely succeeded
                        // Just reload the page to show updated data
                        location.reload();
                    });
            });
        }
    });
</script>

<style>
    .info-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }

    .guest-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #0d6efd;
    }

    .guest-header {
        display: flex;
        align-items: center;
    }

    .guest-details {
        font-size: 0.875rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
    }

    .staff-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
    }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>