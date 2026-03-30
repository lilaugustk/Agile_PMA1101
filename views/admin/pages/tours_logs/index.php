<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nhật ký Tour</li>
                </ol>
            </nav>
        </div>
    </div>

        <!-- Tours Grid -->
        <div class="row g-4">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-premium h-100 border-0 shadow-sm overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; letter-spacing: -0.3px;">
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </h5>
                                    <span class="badge bg-primary-subtle text-primary border-0">
                                        #<?= str_pad($tour['id'], 3, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </div>
                                
                                <div class="tour-stats mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--primary-subtle); margin-right: 12px;">
                                            <i class="ph ph-article" style="font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block fw-semibold" style="font-size: 0.7rem; text-transform: uppercase;">Tổng số nhật ký</small>
                                            <span class="fw-bold text-dark" style="font-size: 1.1rem;"><?= $tour['log_count'] ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--info-subtle); margin-right: 12px;">
                                            <i class="ph ph-calendar-blank" style="font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block fw-semibold" style="font-size: 0.7rem; text-transform: uppercase;">Cập nhật lần cuối</small>
                                            <span class="fw-medium text-dark" style="font-size: 0.9rem;">
                                                <?= $tour['last_log_date'] ? date('d/m/Y', strtotime($tour['last_log_date'])) : 'Chưa có' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="<?= BASE_URL_ADMIN . '&action=tours_logs/tour_detail&id=' . $tour['id'] ?>" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ph ph-eye"></i> Xem chi tiết nhật ký
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center p-5">
                        <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                            <i class="ph ph-magnifying-glass text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Chưa có tour nào</h5>
                        <p class="text-muted">Hiện tại chưa có tour nào để ghi nhật ký.</p>
                    </div>
                </div>
            <?php endif; ?>
    </div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>