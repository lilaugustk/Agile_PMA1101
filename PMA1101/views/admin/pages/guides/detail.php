<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Calculate stats (you may need to pass these from controller)
$totalTours = $guide['total_tours'] ?? 0;
$experienceYears = $guide['experience_years'] ?? 0;
$rating = $guide['rating'] ?? 4.5;
?>

<main class="dashboard-content">
    <div class="container-fluid p-0">
        <!-- Modern Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=guides" class="text-decoration-none text-muted">Quản lý Guide</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-dark mb-0">Hồ sơ Hướng dẫn viên</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN ?>&action=guides" class="btn btn-light border btn-xs d-flex align-items-center gap-2 px-3 py-2 rounded-3 hover-lift shadow-sm">
                    <i class="ph ph-arrow-left"></i> Quay lại
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=guides/edit&id=<?= $guide['id'] ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-primary hover-lift">
                    <i class="ph ph-pencil-simple"></i> Chỉnh sửa hồ sơ
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white hover-lift transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="ph-fill ph-airplane-tilt fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-semibold mb-0">Tổng số Tour</p>
                            <h4 class="fw-bold text-dark mb-0"><?= number_format($totalTours) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white hover-lift transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape bg-success-subtle text-success rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="ph-fill ph-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-semibold mb-0">Kinh nghiệm</p>
                            <h4 class="fw-bold text-dark mb-0"><?= $experienceYears ?> năm</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white hover-lift transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-shape bg-warning-subtle text-warning rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="ph-fill ph-star fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-semibold mb-0">Đánh giá TB</p>
                            <h4 class="fw-bold text-dark mb-0"><?= number_format($rating, 1) ?> <span class="text-muted fs-6">/ 5</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white hover-lift transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <?php 
                        $isActive = ($guide['status'] ?? 'active') === 'active';
                        $statusColor = $isActive ? 'success' : 'danger';
                        $statusLabel = $isActive ? 'Hoạt động' : 'Tạm nghỉ';
                        ?>
                        <div class="icon-shape bg-<?= $statusColor ?>-subtle text-<?= $statusColor ?> rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="ph-fill ph-shield-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-semibold mb-0">Trạng thái</p>
                            <h4 class="fw-bold text-<?= $statusColor ?> mb-0"><?= $statusLabel ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Left Side: Professional Information -->
            <div class="col-lg-8">
                <!-- Specialized Information Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                             <i class="ph ph-briefcase text-primary shadow-sm" style="font-size: 1.25rem;"></i>
                             Thông tin chuyên môn
                        </h5>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-10">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-4 border border-light-subtle shadow-inner h-100 transition-all hover-lift">
                                    <label class="text-muted small fw-semibold d-block mb-1">Loại Guide</label>
                                    <div class="fw-bold text-dark fs-5">
                                        <?php
                                        $typeMap = [
                                            'domestic' => 'Nội địa',
                                            'international' => 'Quốc tế',
                                            'specialized' => 'Chuyên môn'
                                        ];
                                        echo $typeMap[$guide['guide_type'] ?? 'domestic'] ?? 'Nội địa';
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-4 border border-light-subtle shadow-inner h-100 transition-all hover-lift">
                                    <label class="text-muted small fw-semibold d-block mb-1">Chuyên môn</label>
                                    <div class="fw-bold text-dark fs-5"><?= htmlspecialchars($guide['specialization'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-4 border border-light-subtle shadow-inner h-100 transition-all hover-lift">
                                    <label class="text-muted small fw-semibold d-block mb-1">Ngôn ngữ sử dụng</label>
                                    <div class="fw-bold text-primary fs-5"><?= htmlspecialchars($guide['languages'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-4 border border-light-subtle shadow-inner h-100 transition-all hover-lift">
                                    <label class="text-muted small fw-semibold d-block mb-1">Tình trạng sức khỏe</label>
                                    <div class="d-flex align-items-center gap-2 pt-1">
                                        <?php
                                        $healthStatus = $guide['health_status'] ?? 'Tốt';
                                        $healthColor = $healthStatus === 'Tốt' ? 'success' : ($healthStatus === 'Khá' ? 'info' : 'warning');
                                        ?>
                                        <span class="badge bg-<?= $healthColor ?>-subtle text-<?= $healthColor ?> rounded-pill px-3 py-2 fw-semibold border border-<?= $healthColor ?> border-opacity-10 shadow-sm">
                                            <i class="ph ph-heart-pulse me-1"></i> <?= htmlspecialchars($healthStatus) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                <?php if (!empty($guide['notes'])): ?>
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="ph ph-chat-centered-text text-warning shadow-sm" style="font-size: 1.25rem;"></i>
                                Ghi chú chuyên môn
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted mb-0 lh-lg italic"><?= nl2br(htmlspecialchars($guide['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Side: Profile & Contact -->
            <div class="col-lg-4">
                <!-- Profile Avatar Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4 text-center pb-5 bg-white">
                        <div class="position-relative d-inline-block mb-4">
                            <?php if (!empty($guide['avatar'])): ?>
                                <img src="<?= htmlspecialchars($guide['avatar']) ?>"
                                    alt="Avatar"
                                    class="rounded-circle shadow-lg border border-4 border-white"
                                    style="width: 140px; height: 140px; object-fit: cover;">
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-lg border border-4 border-white"
                                    style="width: 140px; height: 140px; background: #f3f4f6;">
                                    <i class="ph ph-user-tie text-muted" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white shadow-sm" style="width: 20px; height: 20px;"></div>
                        </div>
                        <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($guide['full_name']) ?></h4>
                        <p class="text-primary fw-medium small text-uppercase mb-3 tracking-wider">Hướng dẫn viên <?= htmlspecialchars(($guide['guide_type'] ?? 'domestic') === 'domestic' ? 'Nội địa' : 'Quốc tế') ?></p>
                        
                        <div class="rating d-flex justify-content-center align-items-center gap-1 mb-0 p-2 bg-light rounded-pill mx-auto" style="width: fit-content;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="ph-fill ph-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>" style="font-size: 0.9rem;"></i>
                            <?php endfor; ?>
                            <span class="text-dark fw-bold ms-1 small"><?= number_format($rating, 1) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="ph ph-address-book text-success shadow-sm" style="font-size: 1.25rem;"></i>
                            Thông tin liên hệ
                        </h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 bg-light bg-opacity-50 rounded-4 border border-light-subtle transition-all hover-lift">
                            <div class="icon-shape bg-primary text-white rounded-pill d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <i class="ph ph-envelope"></i>
                            </div>
                            <div class="overflow-hidden">
                                <small class="text-muted d-block fw-semibold" style="font-size: 0.7rem; text-transform: uppercase;">Email cá nhân</small>
                                <span class="text-dark fw-bold text-truncate d-block small"><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light bg-opacity-50 rounded-4 border border-light-subtle transition-all hover-lift">
                            <div class="icon-shape bg-success text-white rounded-pill d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <i class="ph ph-phone"></i>
                            </div>
                            <div class="overflow-hidden">
                                <small class="text-muted d-block fw-semibold" style="font-size: 0.7rem; text-transform: uppercase;">Số điện thoại</small>
                                <span class="text-dark fw-bold text-truncate d-block small"><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .shadow-inner {
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .italic { font-style: italic; }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>