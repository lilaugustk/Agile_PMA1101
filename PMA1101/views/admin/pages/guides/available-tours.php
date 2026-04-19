<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard">
    <div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tour Khả Dụng</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-modern alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="alert-content">
                <i class="ph-fill ph-check-circle alert-icon"></i>
                <span><?= $_SESSION['success'] ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-modern alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="alert-content">
                <i class="ph-fill ph-warning-circle alert-icon"></i>
                <span><?= $_SESSION['error'] ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($availableTours)): ?>
        <div class="row g-4">
            <?php foreach ($availableTours as $tour): ?>
                <?php
                $totalCustomers = $tour['total_customers'] ?? 0;
                $isEligible = ($totalCustomers >= 15 && $totalCustomers <= 30);

                // Xác định badge trạng thái
                if ($totalCustomers < 15) {
                    $statusBadge = '<span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1.5"><i class="ph-fill ph-warning me-1"></i>Chưa đủ khách</span>';
                } elseif ($totalCustomers > 30) {
                    $statusBadge = '<span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1.5"><i class="ph-fill ph-users-four me-1"></i>Quá đông</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success-subtle text-success fw-bold px-2 py-1.5"><i class="ph-fill ph-check-circle me-1"></i>Đủ điều kiện</span>';
                }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-premium h-100 border-0 shadow-sm overflow-hidden d-flex flex-column">
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold text-dark mb-0 title-clamp" style="font-size: 1.15rem; line-height: 1.4; letter-spacing: -0.01em;">
                                    <?= htmlspecialchars($tour['name']) ?>
                                </h5>
                            </div>

                            <p class="text-muted small mb-4 description-clamp" style="line-height: 1.6; opacity: 0.8;">
                                <?= htmlspecialchars(substr($tour['description'] ?? 'Không có mô tả', 0, 120)) ?>
                                <?php if (strlen($tour['description'] ?? '') > 120): ?>...<?php endif; ?>
                            </p>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-light text-primary rounded-circle" style="width: 32px; height: 32px;">
                                    <i class="ph-fill ph-calendar-blank" style="font-size: 1rem;"></i>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Khởi hành</div>
                                    <div class="fw-bold text-dark">
                                        <?php if ($tour['departure_date']): ?>
                                            <?= date('d/m/Y', strtotime($tour['departure_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-warning">Chưa có lịch</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                                <?php if (isset($tour['available_seats'])): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded" style="width: 24px; height: 24px;">
                                            <i class="ph-fill ph-users" style="font-size: 0.85rem;"></i>
                                        </div>
                                        <span class="text-muted small">Chỗ trống:</span>
                                        <span class="fw-semibold text-dark small"><?= $tour['available_seats'] ?>/<?= $tour['max_seats'] ?> chỗ</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <?= $statusBadge ?>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-4" style="background: #f8fafc; padding: 12px; border-radius: 12px;">
                                <div class="d-flex align-items-center gap-1.5 fw-bold text-primary" style="font-size: 0.85rem;">
                                    <i class="ph-fill ph-users fs-5"></i>
                                    <?= $totalCustomers ?> người
                                </div>
                                <div class="vr mx-1 opacity-25"></div>
                                <div class="d-flex align-items-center gap-1.5 fw-bold text-info" style="font-size: 0.85rem;">
                                    <i class="ph-fill ph-calendar-check fs-5"></i>
                                    <?= $tour['booking_count'] ?> bkg
                                </div>
                                <div class="vr mx-1 opacity-25"></div>
                                <div class="d-flex align-items-center gap-1.5 fw-bold text-success" style="font-size: 0.85rem;">
                                    <i class="ph-fill ph-currency-circle-dollar fs-5"></i>
                                    <?= number_format($tour['base_price'], 0, ',', '.') ?>đ
                                </div>
                            </div>

                            <!-- Version Breakdown -->
                            <?php if (!empty($tour['version_breakdown']) && count($tour['version_breakdown']) > 0): ?>
                                <div class="mt-auto">
                                    <button class="btn btn-sm btn-light border w-100 d-flex align-items-center justify-content-center gap-2 mb-3 py-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#version-detail-<?= $tour['id'] ?>"
                                        style="font-size: 0.8rem; border-radius: 8px;">
                                        <i class="ph ph-caret-down"></i>
                                        Chi tiết phiên bản
                                    </button>

                                    <div class="collapse" id="version-detail-<?= $tour['id'] ?>">
                                        <div class="p-3 bg-light rounded-3 mb-3 border border-light">
                                            <?php foreach ($tour['version_breakdown'] as $index => $version): ?>
                                                <div class="d-flex justify-content-between align-items-center <?= $index > 0 ? 'mt-2 pt-2 border-top border-light' : '' ?>">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="ph ph-tag text-muted" style="font-size: 0.8rem;"></i>
                                                        <span class="small fw-medium text-dark"><?= htmlspecialchars($version['version_name'] ?: 'Mặc định') ?></span>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-white text-info border small px-2"><?= $version['customer_count'] ?> người</span>
                                                        <span class="badge bg-white text-muted border small px-2"><?= $version['booking_count'] ?> bkg</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <?php
                                $userRole = $_SESSION['user']['role'] ?? 'customer';
                                if ($userRole === 'admin' && !empty($guides)):
                                ?>
                                    <!-- Admin: Guide Assignment Dropdown -->
                                    <div class="p-3 bg-light rounded-4 mb-3 border border-light">
                                        <label class="form-label small text-muted fw-bold text-uppercase mb-2 px-1" style="font-size: 0.65rem; letter-spacing: 1px;">Phân công Guide</label>
                                        <select class="form-select border-0 shadow-sm mb-3 bg-white" id="guide-select-<?= $tour['id'] ?>" style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                            <option value="">-- Chọn Guide --</option>
                                            <?php foreach ($guides as $guide): ?>
                                                <option value="<?= $guide['id'] ?>">
                                                    <?= htmlspecialchars($guide['full_name']) ?>
                                                    <?= !empty($guide['languages']) ? "({$guide['languages']})" : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-success w-100 admin-assign-guide-btn d-flex align-items-center justify-content-center gap-2 shadow-sm py-2 fw-bold"
                                            data-tour-id="<?= $tour['id'] ?>"
                                            data-departure-id="<?= $tour['departure_id'] ?>"
                                            data-tour-name="<?= htmlspecialchars($tour['name']) ?>"
                                            style="height: 48px; border-radius: 12px;">
                                            <i class="ph-fill ph-user-check fs-5"></i> Phân công ngay
                                        </button>
                                    </div>
                                <?php elseif ($isEligible): ?>
                                    <!-- HDV: Claim Tour Button -->
                                    <button class="btn btn-primary w-100 claim-tour-btn d-flex align-items-center justify-content-center gap-2 shadow-sm py-2"
                                        data-tour-id="<?= $tour['id'] ?>"
                                        data-departure-id="<?= $tour['departure_id'] ?>"
                                        data-tour-name="<?= htmlspecialchars($tour['name']) ?>"
                                        data-total-customers="<?= $totalCustomers ?>"
                                        style="border-radius: 8px;">
                                        <i class="ph-fill ph-hand-pointing"></i> Nhận Tour này
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100 border-0 d-flex align-items-center justify-content-center gap-2 py-2" disabled style="border-radius: 8px; opacity: 0.6;">
                                        <i class="ph ph-prohibit"></i> Không đủ điều kiện
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card card-premium border-0 shadow-sm">
            <div class="card-body p-5 text-center">
                <div class="d-flex align-items-center justify-content-center bg-light rounded-circle mb-4 mx-auto" style="width: 80px; height: 80px;">
                    <i class="ph ph-map-pin-line text-muted" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark">Không có tour khả dụng</h5>
                <p class="text-muted mx-auto" style="max-width: 400px;">Hiện tại tất cả tour đã có Guide phụ trách. Vui lòng quay lại sau hoặc kiểm tra các tour khác.</p>
                <a href="<?= BASE_URL_ADMIN ?>&action=/" class="btn btn-light border px-4 mt-2">Quay lại Dashboard</a>
            </div>
        </div>
    <?php endif; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle claim tour buttons (for HDV)
        document.querySelectorAll('.claim-tour-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                const tourName = this.dataset.tourName;
                const totalCustomers = this.dataset.totalCustomers;

                if (confirm(`Bạn có chắc muốn nhận tour "${tourName}"?\nTổng số khách: ${totalCustomers} người`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="ph ph-spinner fa-spin me-2"></i>Đang xử lý...';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=guides/claim-tour', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `tour_id=${tourId}&departure_id=${this.dataset.departureId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('✅ ' + data.message);
                                window.location.reload();
                            } else {
                                alert('❌ ' + data.message);
                                // Re-enable button
                                this.disabled = false;
                                this.innerHTML = '<i class="ph-fill ph-hand-pointing me-2"></i>Nhận Tour';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra! Vui lòng thử lại.');
                            // Re-enable button
                            this.disabled = false;
                            this.innerHTML = '<i class="ph-fill ph-hand-pointing me-2"></i>Nhận Tour';
                        });
                }
            });
        });

        // Handle admin assign guide buttons
        document.querySelectorAll('.admin-assign-guide-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;
                const tourName = this.dataset.tourName;
                const guideSelect = document.getElementById(`guide-select-${tourId}`);
                const guideId = guideSelect.value;

                if (!guideId) {
                    alert('⚠️ Vui lòng chọn Guide trước khi phân công!');
                    guideSelect.focus();
                    return;
                }

                const guideName = guideSelect.options[guideSelect.selectedIndex].text;

                if (confirm(`Phân công Guide "${guideName}" cho tour "${tourName}"?`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="ph ph-spinner fa-spin me-2"></i>Đang phân công...';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=guides/admin-assign-guide', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `tour_id=${tourId}&guide_id=${guideId}&departure_id=${this.dataset.departureId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('✅ ' + data.message);
                                window.location.reload();
                            } else {
                                alert('❌ ' + data.message);
                                this.disabled = false;
                                this.innerHTML = '<i class="ph-fill ph-user-check me-2"></i>Phân công ngay';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('❌ Có lỗi xảy ra khi phân công Guide!');
                            this.disabled = false;
                            this.innerHTML = '<i class="ph-fill ph-user-check me-2"></i>Phân công ngay';
                        });
                }
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>