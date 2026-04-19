<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Calculate stats (you may need to pass these from controller)
$totalTours = $guide['total_tours'] ?? 0;
$experienceYears = $guide['experience_years'] ?? 0;
$rating = $guide['rating'] ?? 4.5;
?>

<<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=guides" class="text-decoration-none text-muted">Quản lý Guide</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hồ sơ HDV</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=guides/edit&id=<?= $guide['id'] ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                <i class="ph ph-pencil-simple"></i> Chỉnh sửa hồ sơ
            </a>
        </div>
    </div>

    <!-- Simplified Monochrome Stats Section -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 rounded-4 transition-all h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Tổng số Tour</p>
                        <h5 class="fw-bold text-dark mb-0"><?= number_format($totalTours) ?></h5>
                    </div>
                    <div class="text-primary opacity-25">
                        <i class="ph-bold ph-airplane-tilt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 rounded-4 transition-all h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Kinh nghiệm</p>
                        <h5 class="fw-bold text-dark mb-0"><?= $experienceYears ?> năm</h5>
                    </div>
                    <div class="text-primary opacity-25">
                        <i class="ph-bold ph-calendar-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 rounded-4 transition-all h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Đánh giá trung bình</p>
                        <h5 class="fw-bold text-dark mb-0"><?= number_format($rating, 1) ?> <span class="text-muted fs-6 small">/ 5</span></h5>
                    </div>
                    <div class="text-warning opacity-25">
                        <i class="ph-bold ph-star fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm p-3 rounded-4 transition-all h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <?php 
                    $isActive = ($guide['status'] ?? 'active') === 'active';
                    $statusLabel = $isActive ? 'Hoạt động' : 'Tạm nghỉ';
                    ?>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Trạng thái vận hành</p>
                        <h5 class="fw-bold <?= $isActive ? 'text-success' : 'text-danger' ?> mb-0"><?= $statusLabel ?></h5>
                    </div>
                    <div class="<?= $isActive ? 'text-success' : 'text-danger' ?> opacity-25">
                        <i class="ph-bold ph-shield-check fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white py-2 border-bottom border-light">
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="ph-fill ph-briefcase text-primary"></i> Thông tin chuyên môn
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-2 bg-light bg-opacity-50 rounded-4 border border-light-subtle">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem;">Loại Guide</label>
                                <div class="fw-bold text-dark fs-6">
                                    <?php
                                    $typeMap = ['domestic' => 'Nội địa', 'international' => 'Quốc tế', 'specialized' => 'Chuyên môn'];
                                    echo $typeMap[$guide['guide_type'] ?? 'domestic'] ?? 'Nội địa';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light bg-opacity-50 rounded-4 border border-light-subtle">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem;">Chuyên môn chính</label>
                                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($guide['specialization'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light bg-opacity-50 rounded-4 border border-light-subtle">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem;">Ngôn ngữ sử dụng</label>
                                <div class="fw-bold text-primary fs-6"><?= htmlspecialchars($guide['languages'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-2 bg-light bg-opacity-50 rounded-4 border border-light-subtle">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block" style="font-size: 0.65rem;">Tình trạng sức khỏe</label>
                                <div class="d-flex align-items-center gap-2 pt-1">
                                    <?php
                                    $healthStatus = $guide['health_status'] ?? 'Tốt';
                                    $healthColor = $healthStatus === 'Tốt' ? 'success' : ($healthStatus === 'Khá' ? 'info' : 'warning');
                                    ?>
                                    <span class="badge bg-<?= $healthColor ?>-subtle text-<?= $healthColor ?> rounded-pill px-2 py-1 fw-bold" style="font-size: 0.65rem;">
                                        <i class="ph-fill ph-heart-pulse me-1"></i> <?= htmlspecialchars($healthStatus) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($guide['notes'])): ?>
                        <div class="mt-3 pt-3 border-top">
                            <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="font-size: 0.65rem;">Ghi chú chuyên môn</label>
                            <p class="text-muted mb-0 small lh-base italic"><?= nl2br(htmlspecialchars($guide['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Profile Avatar Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <?php if (!empty($guide['avatar'])): ?>
                        <img src="<?= htmlspecialchars($guide['avatar']) ?>" class="rounded-circle shadow-sm border border-4 border-white" style="width: 120px; height: 120px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center border" style="width: 120px; height: 120px;">
                            <i class="ph ph-user-tie text-muted opacity-50 fs-1"></i>
                        </div>
                    <?php endif; ?>
                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 18px; height: 18px;"></div>
                </div>
                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($guide['full_name']) ?></h5>
                <p class="text-muted small mb-3">Hướng dẫn viên <?= htmlspecialchars(($guide['guide_type'] ?? 'domestic') === 'domestic' ? 'Nội địa' : 'Quốc tế') ?></p>
                
                <div class="d-flex justify-content-center align-items-center gap-1 p-2 bg-light rounded-pill mx-auto" style="width: fit-content;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="ph-fill ph-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>" style="font-size: 0.8rem;"></i>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="ph-fill ph-address-book text-primary"></i> Liên hệ
                    </h6>
                </div>
                <div class="card-body p-4 d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                            <i class="ph ph-envelope"></i>
                        </div>
                        <div class="overflow-hidden">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.6rem; text-transform: uppercase;">Email cá nhân</small>
                            <span class="text-dark fw-bold text-truncate d-block small"><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                            <i class="ph ph-phone"></i>
                        </div>
                        <div class="overflow-hidden">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.6rem; text-transform: uppercase;">Số điện thoại</small>
                            <span class="text-dark fw-bold text-truncate d-block small"><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .italic { font-style: italic; }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
     overflow: hidden;
        text-overflow: ellipsis;
    }
    .italic { font-style: italic; }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>