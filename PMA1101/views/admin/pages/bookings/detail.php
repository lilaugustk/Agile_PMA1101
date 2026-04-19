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
    'da_thanh_toan' => ['text' => 'Đã thanh toán', 'class' => 'success', 'icon' => 'wallet'],
    'dang_dien_ra' => ['text' => 'Đang diễn ra', 'class' => 'primary', 'icon' => 'spinner-gap'],
    'hoan_tat' => ['text' => 'Hoàn tất', 'class' => 'success', 'icon' => 'check-circle'],
    'da_huy' => ['text' => 'Đã hủy', 'class' => 'danger', 'icon' => 'times-circle'],
    'pending' => ['text' => 'Chờ thanh toán', 'class' => 'warning', 'icon' => 'hourglass-medium'],
    'expired' => ['text' => 'Hết hạn', 'class' => 'secondary', 'icon' => 'calendar-x']
];

$statusKey = $booking['operational_status'] ?? $booking['status'];
$currentStatus = $statusMap[$statusKey] ?? ['text' => 'Unknown', 'class' => 'secondary', 'icon' => 'question'];

// Check edit permission - Chỉ admin mới được edit
$userRole = $_SESSION['user']['role'] ?? 'customer';
$isCompleted = (($booking['status'] ?? '') === 'hoan_tat');
$canEdit = ($userRole === 'admin' && !$isCompleted);
?>

<main class="dashboard booking-detail-page">
    <div class="dashboard-container">
        <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="text-muted text-decoration-none">Quản lý Booking</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết #<?= $booking['id'] ?></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-sm bg-white text-muted border shadow-sm px-3 d-flex align-items-center gap-2" style="border-radius: 8px;">
                    <i class="ph ph-arrow-left"></i> Quay lại
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=bookings/exportInvoice&id=<?= $booking['id'] ?>" target="_blank" class="btn btn-sm bg-white text-info border shadow-sm px-3 d-flex align-items-center gap-2" style="border-radius: 8px;">
                    <i class="ph ph-printer"></i> Hóa đơn
                </a>
                <?php if ($canEdit): ?>
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings/edit&id=<?= $booking['id'] ?>" class="btn btn-sm btn-primary shadow-sm px-3 d-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="ph ph-pencil-simple"></i> Chỉnh sửa
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success border-0 shadow-sm mb-3"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger border-0 shadow-sm mb-3"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-premium border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Tổng tiền đơn đặt</div>
                        <div class="fw-bold text-dark mt-1"><?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?> ₫</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-premium border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Trạng thái hiện tại</div>
                        <div class="mt-1">
                            <div class="d-flex align-items-center gap-2">
                                <span id="status-badge" class="badge bg-<?= $currentStatus['class'] ?>-subtle text-<?= $currentStatus['class'] ?> fw-bold px-3 py-1 rounded-pill">
                                    <?= $currentStatus['text'] ?>
                                </span>
                                <?php if ($canEdit): ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 px-2 py-1 lh-1" type="button" data-bs-toggle="dropdown" aria-label="Đổi trạng thái">
                                        ⋮
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item status-change-btn" href="#" data-status="cho_xac_nhan" data-booking-id="<?= $booking['id'] ?>">Chờ xác nhận</a></li>
                                        <li><a class="dropdown-item status-change-btn" href="#" data-status="da_coc" data-booking-id="<?= $booking['id'] ?>">Đã cọc</a></li>
                                        <li><a class="dropdown-item status-change-btn" href="#" data-status="da_thanh_toan" data-booking-id="<?= $booking['id'] ?>">Đã thanh toán</a></li>
                                        <li><a class="dropdown-item status-change-btn" href="#" data-status="hoan_tat" data-booking-id="<?= $booking['id'] ?>">Hoàn tất</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item status-change-btn" href="#" data-status="pending" data-booking-id="<?= $booking['id'] ?>">Chờ thanh toán</a></li>
                                        <li><a class="dropdown-item status-change-btn text-danger" href="#" data-status="da_huy" data-booking-id="<?= $booking['id'] ?>">Hủy đơn</a></li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-premium border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Ngày xác lập</div>
                        <div class="fw-bold text-dark mt-1"><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-premium border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">Tổng số khách</div>
                        <div class="fw-bold text-dark mt-1"><?= (int)$booking['adults'] + (int)$booking['children'] + (int)$booking['infants'] ?> khách</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($booking['payment_proof'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-premium border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-success"><i class="ph ph-image me-2"></i>Minh chứng thanh toán</h6>
                            <a href="<?= BASE_URL . htmlspecialchars($booking['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-light border px-3" style="border-radius: 8px;">
                                <i class="ph ph-magnifying-glass-plus me-1"></i>Xem ảnh lớn
                            </a>
                        </div>
                        <div class="card-body p-4 text-center bg-light-subtle">
                            <img src="<?= BASE_URL . htmlspecialchars($booking['payment_proof']) ?>" 
                                 alt="Payment Proof" 
                                 class="img-fluid rounded shadow-sm border" 
                                 style="max-height: 400px; cursor: pointer;"
                                 onclick="window.open(this.src)">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-12">
                <div class="card card-premium border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-primary"><i class="ph ph-users-three me-2"></i>Danh sách hành khách (<?= count($companions) + 1 ?>)</h6>
                        <?php if ($canEdit): ?>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-primary" data-bs-toggle="modal" data-bs-target="#companionModal" id="addNewCompanionBtn">
                                <i class="ph ph-user-plus me-1"></i>Thêm hành khách
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4">
                        <!-- Representative Card -->
                        <div class="border rounded-4 p-4 mb-4 bg-light-subtle shadow-sm">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-muted text-uppercase fw-bold extra-small ls-1 mb-1">Người đại diện</div>
                                    <div class="fw-bold text-dark fs-5"><?= htmlspecialchars($booking['customer_name'] ?: 'Khách vãng lai') ?></div>
                                    <div class="text-muted small mt-2 d-flex flex-wrap gap-4">
                                        <span><i class="ph ph-envelope me-2"></i><?= htmlspecialchars($booking['customer_email'] ?: 'N/A') ?></span>
                                        <span><i class="ph ph-phone me-2"></i><?= htmlspecialchars($booking['customer_phone'] ?: 'N/A') ?></span>
                                        <?php if (!empty($booking['contact_address'])): ?>
                                            <span><i class="ph ph-map-pin me-2"></i><?= htmlspecialchars($booking['contact_address']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-3 py-1">Lead Passenger</span>
                            </div>
                        </div>

                        <?php if (!empty($companions)): ?>
                            <div class="row g-3">
                                <?php
                                $typeLabels = [
                                    'adult' => ['text' => 'Người lớn', 'class' => 'primary', 'icon' => 'user'],
                                    'child' => ['text' => 'Trẻ em', 'class' => 'info', 'icon' => 'child'],
                                    'infant' => ['text' => 'Em bé', 'class' => 'danger', 'icon' => 'baby']
                                ];
                                ?>
                                <?php foreach ($companions as $index => $companion): ?>
                                    <?php
                                    $resolvedName = trim((string)($companion['full_name'] ?? ''));
                                    if ($resolvedName === '') $resolvedName = 'Hành khách ' . ($index + 1);
                                    
                                    $typeKey = $companion['passenger_type'] ?? 'adult';
                                    $typeConfig = $typeLabels[$typeKey] ?? $typeLabels['adult'];
                                    
                                    $isMale = ($companion['gender'] ?? '') === 'Nam' || ($companion['gender'] ?? '') === 'male';
                                    $isChild = in_array($typeKey, ['child', 'infant']);
                                    $avatarClass = $isChild ? 'avatar-child' : ($isMale ? 'avatar-male' : 'avatar-female');
                                    $avatarIcon = $isChild ? 'ph-fill ' . ($typeKey === 'infant' ? 'ph-baby' : 'ph-child') : 'ph-fill ph-user';
                                    ?>
                                    <div class="col-md-6">
                                        <div class="companion-card-modern rounded-4 p-3 h-100 shadow-sm border position-relative overflow-hidden" style="background: white;">
                                            <?php if (!empty($companion['is_foc'])): ?>
                                                <div class="foc-ribbon">MIỄN PHÍ</div>
                                            <?php endif; ?>

                                            <!-- Header: Avatar + Primary Info -->
                                            <div class="d-flex gap-3 align-items-center mb-3">
                                                <div class="passenger-avatar <?= $avatarClass ?>">
                                                    <i class="<?= $avatarIcon ?>"></i>
                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <div class="fw-bold text-dark text-truncate pe-2" style="font-size: 1.05rem;" title="<?= htmlspecialchars($resolvedName) ?>">
                                                            <?= htmlspecialchars($resolvedName) ?>
                                                        </div>
                                                        
                                                        <?php if ($canEdit): ?>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-light border-0 p-1 lh-1 rounded-circle" type="button" data-bs-toggle="dropdown">
                                                                    <i class="ph-bold ph-dots-three-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                                    <li>
                                                                        <a class="dropdown-item edit-companion-btn" href="javascript:void(0)" 
                                                                           data-companion-id="<?= $companion['id'] ?>"
                                                                           data-companion='<?= json_encode([
                                                                               'name' => $companion['full_name'],
                                                                               'gender' => $companion['gender'],
                                                                               'birth_date' => $companion['birth_date'],
                                                                               'phone' => $companion['phone'],
                                                                               'email' => $companion['email'],
                                                                               'address' => $companion['address'],
                                                                               'id_card' => $companion['id_card'],
                                                                               'passenger_type' => $companion['passenger_type'],
                                                                               'is_foc' => $companion['is_foc'],
                                                                               'special_request' => $companion['special_request']
                                                                           ], JSON_HEX_APOS) ?>'>
                                                                            <i class="ph ph-pencil-simple me-2"></i>Sửa
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger delete-companion-btn" href="javascript:void(0)"
                                                                           data-companion-id="<?= $companion['id'] ?>"
                                                                           data-booking-id="<?= $booking['id'] ?>">
                                                                            <i class="ph ph-trash me-2"></i>Xóa
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-<?= $typeConfig['class'] ?>-subtle text-<?= $typeConfig['class'] ?> border-0 rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                            <i class="ph ph-<?= $typeConfig['icon'] ?> me-1"></i><?= $typeConfig['text'] ?>
                                                        </span>
                                                        <span class="badge bg-light text-dark border-0 rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                            <?= htmlspecialchars($companion['gender'] ?? 'N/A') ?>
                                                        </span>
                                                        <?php if(!empty($companion['birth_date'])): ?>
                                                            <span class="badge bg-light text-dark border-0 rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                                <i class="ph ph-cake me-1"></i><?= date('d/m/Y', strtotime($companion['birth_date'])) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details: Vertically Aligned Below Avatar -->
                                            <div class="meta-list mb-2">
                                                <?php if(!empty($companion['id_card'])): ?>
                                                    <div class="meta-item"><i class="ph ph-identification-card"></i>CCCD: <?= htmlspecialchars($companion['id_card']) ?></div>
                                                <?php endif; ?>
                                                <?php if(!empty($companion['phone'])): ?>
                                                    <div class="meta-item"><i class="ph ph-phone"></i><?= htmlspecialchars($companion['phone']) ?></div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if(!empty($companion['special_request'])): ?>
                                                <div class="special-request-note d-flex justify-content-between align-items-center">
                                                    <span class="small text-muted text-truncate me-2">
                                                        <i class="ph ph-warning-circle me-1"></i><?= htmlspecialchars($companion['special_request']) ?>
                                                    </span>
                                                    <?php if ($canEdit): ?>
                                                        <button class="btn p-0 border-0 bg-transparent text-primary edit-special-request-btn"
                                                            data-companion-id="<?= $companion['id'] ?>"
                                                            data-booking-id="<?= $booking['id'] ?>"
                                                            data-current-request="<?= htmlspecialchars($companion['special_request'] ?? '') ?>">
                                                            <i class="ph ph-pencil-simple"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif ($canEdit): ?>
                                                <button class="btn btn-link p-0 text-primary extra-small text-decoration-none edit-special-request-btn"
                                                    data-companion-id="<?= $companion['id'] ?>"
                                                    data-booking-id="<?= $booking['id'] ?>"
                                                    data-current-request="">
                                                    <i class="ph ph-plus me-1"></i>Ghi chú khách
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5 border rounded-4 bg-light-subtle">
                                <i class="ph ph-users fs-1 opacity-25 mb-2 d-block"></i>
                                Chưa có khách đi kèm
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($booking['notes'])): ?>
                    <div class="card card-premium border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h6 class="fw-bold mb-0 text-warning"><i class="ph ph-note me-2"></i>Ghi chú điều hành</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-muted small"><?= nl2br(htmlspecialchars($booking['notes'])) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Companion Modal -->
    <div class="modal fade" id="companionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-primary text-white py-3 px-4">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="companionModalTitle">
                        <i class="ph-fill ph-user-plus"></i> Thêm Khách Đi Kèm
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light-subtle">
                    <form id="companionForm">
                        <input type="hidden" id="companion-id" name="companion_id">
                        <input type="hidden" id="companion-booking-id" name="booking_id" value="<?= $booking['id'] ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light shadow-sm" id="companion-name" name="name" placeholder="Võ Văn A..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Giới tính</label>
                                <select class="form-select border-light shadow-sm" id="companion-gender" name="gender">
                                    <option value="">Chọn</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Ngày sinh</label>
                                <input type="date" class="form-control border-light shadow-sm" id="companion-birth-date" name="birth_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Điện thoại</label>
                                <input type="tel" class="form-control border-light shadow-sm" id="companion-phone" name="phone" placeholder="0901...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">CMND/Hộ chiếu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light shadow-sm" id="companion-id-card" name="id_card" placeholder="Số định danh..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Loại khách <span class="text-danger">*</span></label>
                                <select class="form-select border-light shadow-sm" id="companion-passenger-type" name="passenger_type" required>
                                    <option value="adult" selected>Người lớn</option>
                                    <option value="child">Trẻ em</option>
                                    <option value="infant">Em bé</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark small text-uppercase">Email</label>
                                <input type="email" class="form-control border-light shadow-sm" id="companion-email" name="email" placeholder="example@mail.com">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark small text-uppercase">Địa chỉ thường trú</label>
                                <textarea class="form-control border-light shadow-sm" id="companion-address" name="address" style="height: 60px;" placeholder="Nhập địa chỉ đầy đủ..."></textarea>
                            </div>
                             <div class="col-md-6 d-flex align-items-end mb-3">
                                 <div class="form-check form-switch p-3 bg-white border border-light rounded-3 shadow-sm w-100">
                                     <input class="form-check-input ms-0 me-2" type="checkbox" id="companion-is-foc" name="is_foc">
                                     <label class="form-check-label fw-bold text-dark small text-uppercase" for="companion-is-foc">Hành khách Miễn Phí (FOC)</label>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <label class="form-label fw-bold text-dark small text-uppercase">Yêu cầu đặc biệt</label>
                                 <textarea class="form-control border-light shadow-sm" id="companion-special-request" name="special_request" rows="3" placeholder="Dị ứng, ăn chay..."></textarea>
                             </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-white border-0 py-3 px-4 shadow-top">
                    <button type="button" class="btn btn-light fw-bold px-4 rounded-pill" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary fw-bold px-4 rounded-pill shadow-primary" id="saveCompanionBtn">
                        <i class="ph-fill ph-floppy-disk me-1"></i>Lưu thông tin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Request Modal -->
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
    // Helper functions moved to TOP to avoid hoisting issues
    window.applyDateMask = function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 8) value = value.slice(0, 8);
        
        let formatted = '';
        if (value.length > 0) {
            formatted = value.substring(0, 2);
            if (value.length > 2) {
                formatted += '/' + value.substring(2, 4);
                if (value.length > 4) {
                    formatted += '/' + value.substring(4, 8);
                }
            }
        }
        e.target.value = formatted;
    };

    window.formatDateToVN = function(dateStr) {
        if (!dateStr) return '';
        if (dateStr.includes('/')) return dateStr;
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    };

    // Special Request Update
    document.addEventListener('DOMContentLoaded', function() {
        const specialRequestModalEl = document.getElementById('specialRequestModal');
        const specialRequestModal = new bootstrap.Modal(specialRequestModalEl);

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
                        // Show success message
                        alert(data.message);
                        specialRequestModal.hide();
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật');
                });
        });

        // Apply masking to birth date input
        const birthDateInput = document.getElementById('companion-birth-date');
        if (birthDateInput) {
            birthDateInput.addEventListener('input', window.applyDateMask);
        }
    });

    // Companion Management
    document.addEventListener('DOMContentLoaded', function() {
        const companionModalEl = document.getElementById('companionModal');
        const companionModal = new bootstrap.Modal(companionModalEl, {
            backdrop: true,
            keyboard: true
        });

        // Add new companion button
        const addNewBtn = document.getElementById('addNewCompanionBtn');
        if (addNewBtn) {
            addNewBtn.addEventListener('click', function() {
                // Reset form for new entry
                document.getElementById('companionForm').reset();
                document.getElementById('companion-id').value = '';
                document.getElementById('companionModalTitle').innerHTML = '<i class="ph-fill ph-user-plus"></i> Thêm Khách Đi Kèm';
            });
        }

        // Close button listeners
        const closeButtons = companionModalEl.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                companionModal.hide();
            });
        });

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
                document.getElementById('companion-birth-date').value = (data.birth_date || '');
                document.getElementById('companion-phone').value = data.phone || '';
                document.getElementById('companion-email').value = data.email || '';
                document.getElementById('companion-address').value = data.address || '';
                document.getElementById('companion-id-card').value = data.id_card || '';
                document.getElementById('companion-passenger-type').value = data.passenger_type || 'adult';
                document.getElementById('companion-special-request').value = data.special_request || '';
                document.getElementById('companion-is-foc').checked = data.is_foc == 1;

                // Show modal
                companionModal.show();
            });
        });

        // Delete companion buttons
        document.querySelectorAll('.delete-companion-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const companionId = this.dataset.companionId;
                const bookingId = this.dataset.bookingId;
                
                if (!confirm(`Bạn có chắc muốn xóa hành khách này?`)) {
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

                const formData = new URLSearchParams({
                    booking_id: bookingId,
                    name: name,
                    gender: document.getElementById('companion-gender').value,
                    birth_date: document.getElementById('companion-birth-date').value,
                    phone: document.getElementById('companion-phone').value,
                    email: document.getElementById('companion-email').value,
                    address: document.getElementById('companion-address').value,
                    id_card: document.getElementById('companion-id-card').value,
                    passenger_type: passengerType,
                    is_foc: document.getElementById('companion-is-foc').checked ? '1' : '0',
                    special_request: document.getElementById('companion-special-request').value
                });

                const url = '<?= BASE_URL_ADMIN ?>&action=bookings/updateCompanion';

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
                        companionModal.hide();
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi lưu thông tin');
                });
            });
        }
    });

    // Status Management
    document.querySelectorAll('.status-change-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const status = this.dataset.status;
            const bookingId = this.dataset.bookingId;

            if (confirm('Bạn có chắc muốn đổi trạng thái đơn đặt này?')) {
                fetch('<?= BASE_URL_ADMIN ?>&action=bookings/updateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        booking_id: bookingId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });
</script>

<style>
    .passenger-badge {
        padding: 10px 16px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        min-width: 90px;
    }
    .ls-1 { letter-spacing: 0.5px; }
    .extra-small { font-size: 0.65rem; }
    
    /* Modern Passenger Cards */
    .companion-card-modern {
        transition: all 0.2s ease;
        border: 1px solid #f1f5f9;
        background: #fff;
    }
    .companion-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-color: var(--bs-primary-border-subtle);
    }
    .passenger-avatar {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }
    .avatar-male { background: #e0f2fe; color: #0ea5e9; }
    .avatar-female { background: #fff1f2; color: #f43f5e; }
    .avatar-child { background: #f5f3ff; color: #8b5cf6; }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 4px;
    }
    .meta-item i { font-size: 1.1rem; color: #94a3b8; }
    
    .special-request-note {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px 14px 10px 18px;
        font-style: italic;
        margin-top: 12px;
        position: relative;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .special-request-note::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: #cbd5e1;
    }

    .foc-ribbon {
        position: absolute;
        top: 10px;
        right: -30px;
        background: #ef4444;
        color: white;
        padding: 2px 40px;
        font-size: 0.65rem;
        font-weight: bold;
        transform: rotate(45deg);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
