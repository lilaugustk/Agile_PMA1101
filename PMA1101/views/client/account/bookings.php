<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>
<?php
$displayName = $_SESSION['user']['full_name'] ?? 'Khách hàng';
$displayEmail = $_SESSION['user']['email'] ?? '';

$statusMap = [
    'pending' => ['class' => 'warning', 'label' => 'Chờ thanh toán', 'icon' => 'ph-clock'],
    'cho_xac_nhan' => ['class' => 'warning', 'label' => 'Chờ xác nhận', 'icon' => 'ph-hourglass'],
    'da_coc' => ['class' => 'info', 'label' => 'Đã cọc', 'icon' => 'ph-credit-card'],
    'da_thanh_toan' => ['class' => 'success', 'label' => 'Đã thanh toán', 'icon' => 'ph-check-circle'],
    'dang_dien_ra' => ['class' => 'primary', 'label' => 'Đang diễn ra', 'icon' => 'ph-airplane-takeoff'],
    'hoan_tat' => ['class' => 'success', 'label' => 'Hoàn tất', 'icon' => 'ph-flag-checkered'],
    'da_huy' => ['class' => 'danger', 'label' => 'Đã hủy', 'icon' => 'ph-x-circle'],
    'expired' => ['class' => 'secondary', 'label' => 'Hết hạn', 'icon' => 'ph-timer']
];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    :root {
        --sapphire-blue: #2563eb;
        --sapphire-gradient: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        --sapphire-soft: #f8faff;
        --sapphire-slate: #1e293b;
        --sapphire-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.1);
        --primary-color: #2563eb;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f6f8fc;
    }

    /* Dashboard Header */
    .profile-hero {
        background: var(--sapphire-gradient);
        padding: 80px 0 100px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Sidebar */
    .profile-nav-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        box-shadow: var(--sapphire-shadow);
        overflow: hidden;
        margin-top: -60px;
    }

    .nav-user-info {
        padding: 30px;
        background: #f8faff;
        border-bottom: 1.5px solid #edf2f7;
    }

    .nav-link-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 25px;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .nav-link-item i {
        font-size: 1.4rem;
    }

    .nav-link-item:hover {
        background: #f8faff;
        color: var(--sapphire-blue);
    }

    .nav-link-item.active {
        background: #eff6ff;
        color: var(--sapphire-blue);
        border-left-color: var(--sapphire-blue);
    }

    .nav-link-item.logout {
        color: #ef4444;
    }

    /* Content Card */
    .content-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        box-shadow: var(--sapphire-shadow);
        margin-top: -60px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card-title-box {
        padding: 2.5rem 2.5rem 1.5rem;
        border-bottom: 1.5px solid #edf2f7;
    }

    /* Modern Table */
    .sapphire-table thead th {
        background: #f8faff;
        border-bottom: 2px solid #edf2f7;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1.2rem 1.5rem;
    }

    .sapphire-table tbody td {
        padding: 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 500;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-sm {
        width: 36px; height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        transition: all 0.2s;
    }

    .btn-action-sm:hover {
        background: var(--sapphire-blue);
        color: #fff;
    }

    .badge-premium {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(5px);
        color: #fff;
        font-weight: 700;
        padding: 8px 15px;
        border-radius: 100px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,0.2);
    }
</style>

<div class="profile-hero animate__animated animate__fadeIn">
    <div class="container">
        <div class="d-flex align-items-center gap-4 text-white">
            <div class="position-relative">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($displayName) ?>&background=fff&color=2563eb&size=100&bold=true" 
                     class="rounded-circle shadow-lg border border-4 border-white border-opacity-20" 
                     width="90" height="90" alt="Avatar">
                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width:18px; height:18px"></span>
            </div>
            <div>
                <span class="badge-premium mb-2 d-inline-block">Dữ liệu chuyến đi</span>
                <h1 class="h2 fw-800 mb-0">Lịch sử đặt tour</h1>
                <p class="mb-0 opacity-75 small">Xem lại tất cả các hành trình bạn đã từng tham gia.</p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-4 col-xl-3">
            <div class="profile-nav-card animate__animated animate__fadeInLeft">
                <div class="nav-user-info">
                    <div class="fw-800 text-dark mb-1 text-truncate">Quản lý tài khoản</div>
                    <div class="small text-muted">Mã KH: #USER-<?= $_SESSION['user']['id'] ?? '???' ?></div>
                </div>
                <div class="py-2">
                    <a href="<?= BASE_URL ?>?action=profile" class="nav-link-item">
                        <i class="ph-bold ph-user-circle"></i> Thông tin tài khoản
                    </a>
                    <a href="<?= BASE_URL ?>?action=my-bookings" class="nav-link-item active">
                        <i class="ph-bold ph-suitcase-rolling"></i> Chuyến đi của tôi
                    </a>
                    <hr class="mx-3 opacity-5">
                    <a href="<?= BASE_URL ?>?action=logout" class="nav-link-item logout">
                        <i class="ph-bold ph-sign-out"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9">
            <div class="content-card animate__animated animate__fadeInRight">
                <div class="card-title-box d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-800 text-dark mb-1">Danh sách hành trình</h4>
                        <p class="text-muted mb-0 small">Theo dõi trạng thái và chi tiết các dịch vụ đã đặt.</p>
                    </div>
                </div>
                
                <div class="p-0">
                    <?php if (empty($bookings)): ?>
                        <div class="text-center py-5">
                            <i class="ph ph-suitcase-rolling text-primary opacity-20 mb-3" style="font-size: 5rem;"></i>
                            <h5 class="fw-800 text-dark">Chưa có hành trình nào</h5>
                            <p class="text-muted small">Mọi chuyến đi tuyệt vời đều bắt đầu bằng một cú click!</p>
                            <a href="<?= BASE_URL ?>?action=tours" class="btn btn-primary rounded-pill px-4 fw-800 py-3 mt-3">
                                Khám phá các Tour ngay
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table sapphire-table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Tour & Khởi hành</th>
                                        <th class="text-end">Tiền thanh toán</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <?php
                                            $displayStatus = $booking['operational_status'] ?? ($booking['status'] ?? '');
                                            $status = $statusMap[$displayStatus] ?? ['class' => 'secondary', 'label' => ($displayStatus ?: 'Không rõ'), 'icon' => 'ph-info'];
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-800 text-primary">#BK<?= str_pad((string)$booking['id'], 6, '0', STR_PAD_LEFT) ?></div>
                                                <div class="x-small text-muted mt-1"><?= date('H:i d/m/Y', strtotime($booking['booking_date'] ?? 'now')) ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-800 text-dark text-truncate" style="max-width: 250px;"><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></div>
                                                <div class="small text-muted d-flex align-items-center gap-1">
                                                    <i class="ph ph-calendar"></i>
                                                    <?= isset($booking['departure_date']) ? date('d/m/Y', strtotime($booking['departure_date'])) : '--' ?>
                                                </div>
                                            </td>
                                            <td class="text-end fw-800 text-dark fs-5">
                                                <?= number_format((float)($booking['final_price'] ?? 0), 0, ',', '.') ?>đ
                                            </td>
                                            <td class="text-center">
                                                <span class="status-badge bg-<?= $status['class'] ?>-subtle text-<?= $status['class'] ?>">
                                                    <i class="ph-bold <?= $status['icon'] ?>"></i>
                                                    <?= $status['label'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= (int)($booking['tour_id'] ?? 0) ?>" 
                                                   class="btn-action-sm shadow-sm" title="Xem chi tiết tour">
                                                    <i class="ph-bold ph-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
