<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$roleMap = [
    'customer' => ['text' => 'Khách hàng', 'class' => 'primary', 'icon' => 'ph-user-circle', 'color' => '#4f46e5'],
    'admin' => ['text' => 'Quản trị viên', 'class' => 'danger', 'icon' => 'ph-shield-star', 'color' => '#ef4444'],
    'guide' => ['text' => 'Hướng dẫn viên', 'class' => 'indigo', 'icon' => 'ph-identification-card', 'color' => '#6366f1']
];
$roleInfo = $roleMap[$user['role']] ?? ['text' => 'Người dùng', 'class' => 'secondary', 'icon' => 'ph-user', 'color' => '#64748b'];

$joinDate = strtotime($user['created_at']);
?>

<main class="content user-detail-page">
    <!-- Simplified Light Hero Header -->
    <div class="card border-0 shadow-sm mb-4 rounded-4" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-md-auto text-center mb-4 mb-md-0">
                    <div class="position-relative d-inline-block">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=4f46e5&color=fff&size=200&font-size=0.4&rounded=true&bold=true" 
                             class="rounded-circle shadow-sm border border-4 border-light" 
                             alt="<?= htmlspecialchars($user['full_name']) ?>" 
                             style="width: 140px; height: 140px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle p-2" style="margin-bottom: 8px; margin-right: 8px;"></span>
                    </div>
                </div>
                <div class="col-md ps-md-4">
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                        <h1 class="h2 fw-bold text-dark mb-0" style="letter-spacing: -1px;"><?= htmlspecialchars($user['full_name']) ?></h1>
                        <span class="badge rounded-pill px-3 py-1 bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem;">
                            <i class="<?= $roleInfo['icon'] ?> me-1"></i> <?= $roleInfo['text'] ?>
                        </span>
                    </div>
                    <p class="text-secondary mb-4 small"><i class="ph ph-envelope-simple me-1"></i> <?= htmlspecialchars($user['email']) ?></p>
                    
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL_ADMIN ?>&action=users/edit&id=<?= $user['user_id'] ?>" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-primary transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600;">
                            <i class="ph-bold ph-note-pencil"></i> Chỉnh sửa
                        </a>
                        <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center gap-2 px-4 py-2 border shadow-none transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600; color: #64748b;">
                            <i class="ph ph-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white border-bottom border-light py-4 px-4">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ph-bold ph-identification-card text-primary fs-4"></i> Hồ sơ chi tiết
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-0">
                        <div class="col-12 py-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center border-bottom border-light">
                            <span class="text-secondary fw-500 small mb-1 mb-sm-0">Mã định danh User</span>
                            <span class="fw-bold text-dark opacity-75">#<?= $user['user_id'] ?></span>
                        </div>
                        <div class="col-12 py-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center border-bottom border-light">
                            <span class="text-secondary fw-500 small mb-1 mb-sm-0">Họ và tên đầy đủ</span>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($user['full_name']) ?></span>
                        </div>
                        <div class="col-12 py-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center border-bottom border-light">
                            <span class="text-secondary fw-500 small mb-1 mb-sm-0">Địa chỉ Email</span>
                            <span class="fw-bold text-primary"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="col-12 py-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center border-bottom border-light">
                            <span class="text-secondary fw-500 small mb-1 mb-sm-0">Số điện thoại</span>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></span>
                        </div>
                        <div class="col-12 py-3 d-flex flex-sm-row flex-column justify-content-between align-items-sm-center">
                            <span class="text-secondary fw-500 small mb-1 mb-sm-0">Ngày tạo tài khoản</span>
                            <span class="fw-bold text-dark opacity-75"><?= date('H:i d/m/Y', $joinDate) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions Column -->
        <div class="col-lg-4">
            <!-- Simplified Action Card -->
            <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-4 pb-1 border-bottom border-light">Quản lý nâng cao</h6>
                    <div class="d-grid gap-3">
                        <?php if ($user['role'] === 'customer'): ?>
                            <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&customer_id=<?= $user['user_id'] ?>" class="btn btn-dark d-flex align-items-center justify-content-center gap-2 py-3 shadow-none transition-all hover-translate-y" style="border-radius: 12px; font-weight: 700;">
                                <i class="ph-bold ph-calendar-plus fs-5"></i> TẠO BOOKING MỚI
                            </a>
                        <?php endif; ?>
                        <a href="mailto:<?= $user['email'] ?>" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-none" style="border-radius: 12px; font-weight: 600; color: #475569;">
                            <i class="ph ph-paper-plane-tilt"></i> Gửi thông báo
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
    .hover-translate-y:hover {
        transform: translateY(-3px);
    }
    .fw-500 { font-weight: 500; }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>