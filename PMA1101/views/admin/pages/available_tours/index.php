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
                    <li class="breadcrumb-item active" aria-current="page">Tour Khả Dụng</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-success-subtle text-success border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-check-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-warning-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
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
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold text-dark mb-0 title-clamp" style="font-size: 1.1rem; line-height: 1.4;">
                                    <?= htmlspecialchars($tour['tour_name'] ?? '') ?>
                                </h5>
                            </div>

                            <p class="text-muted small mb-4 description-clamp" style="line-height: 1.6;">
                                <?= htmlspecialchars(substr($tour['description'] ?? 'Không có mô tả', 0, 100)) ?>
                                <?php if (strlen($tour['description'] ?? '') > 100): ?>...<?php endif; ?>
                            </p>

                            <div class="vstack gap-2 mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded" style="width: 24px; height: 24px;">
                                        <i class="ph-fill ph-calendar-blank" style="font-size: 0.85rem;"></i>
                                    </div>
                                    <span class="text-muted small">Khởi hành:</span>
                                    <span class="fw-semibold text-dark small">
                                        <?php if ($tour['departure_date']): ?>
                                            <?= date('d/m/Y', strtotime($tour['departure_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-warning">Chưa có lịch</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <?= $statusBadge ?>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <div class="badge bg-primary-subtle text-primary border-0 fw-bold d-flex align-items-center gap-1.5 py-1.5 px-3" style="border-radius: 8px;">
                                    <i class="ph-fill ph-users"></i>
                                    <?= $totalCustomers ?> người
                                </div>
                                <div class="badge bg-info-subtle text-info border-0 fw-bold d-flex align-items-center gap-1.5 py-1.5 px-3" style="border-radius: 8px;">
                                    <i class="ph-fill ph-calendar-check"></i>
                                    <?= $tour['booking_count'] ?> bkg
                                </div>
                                <div class="badge bg-success-subtle text-success border-0 fw-bold d-flex align-items-center gap-1.5 py-1.5 px-3" style="border-radius: 8px;">
                                    <i class="ph-fill ph-currency-circle-dollar"></i>
                                    <?= number_format($tour['total_booking_price'] ?? 0, 0, ',', '.') ?>đ
                                </div>
                            </div>

                            <!-- Version Breakdown -->
                            <?php if (!empty($tour['version_breakdown']) && count($tour['version_breakdown']) > 0): ?>
                                <div class="mt-auto">
                                    <?php $uniqueId = $tour['tour_id'] . '-' . str_replace('-', '', $tour['departure_date']); ?>
                                    <button class="btn btn-sm btn-light border w-100 d-flex align-items-center justify-content-center gap-2 mb-3 py-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#version-detail-<?= $uniqueId ?>"
                                        style="font-size: 0.8rem; border-radius: 8px;">
                                        <i class="ph ph-caret-down"></i>
                                        Chi tiết phiên bản
                                    </button>

                                    <div class="collapse" id="version-detail-<?= $uniqueId ?>">
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
                                    <!-- Admin: Guide Assignment -->
                                    <div class="p-3 bg-light rounded-3 mb-3 border border-light">
                                        <?php $uniqueId = $tour['tour_id'] . '-' . str_replace('-', '', $tour['departure_date']); ?>
                                        <label class="form-label small text-muted fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Phân công Hướng dẫn viên</label>
                                        <select class="form-select form-select-sm border-0 shadow-sm mb-3" id="guide-select-<?= $uniqueId ?>" style="min-height: 38px; border-radius: 8px;">
                                            <option value="">-- Chọn HDV --</option>
                                            <?php foreach ($guides as $guide): ?>
                                                <option value="<?= $guide['id'] ?>">
                                                    <?= htmlspecialchars($guide['full_name']) ?>
                                                    <?= !empty($guide['languages']) ? "({$guide['languages']})" : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-success w-100 admin-assign-guide-btn d-flex align-items-center justify-content-center gap-2 shadow-sm py-2"
                                            data-unique-id="<?= $uniqueId ?>"
                                            data-tour-id="<?= $tour['tour_id'] ?>"
                                            data-departure-id="<?= $tour['departure_id'] ?? '' ?>"
                                            data-departure-date="<?= $tour['departure_date'] ?>"
                                            data-tour-name="<?= htmlspecialchars($tour['tour_name']) ?>"
                                            style="border-radius: 8px;">
                                            <i class="ph-fill ph-user-check"></i> Phân công ngay
                                        </button>
                                    </div>
                                <?php elseif ($isEligible): ?>
                                    <!-- HDV: Claim Tour Button -->
                                    <button class="btn btn-primary w-100 claim-tour-btn d-flex align-items-center justify-content-center gap-2 shadow-sm py-2"
                                        data-tour-id="<?= $tour['tour_id'] ?>"
                                        data-departure-id="<?= $tour['departure_id'] ?? '' ?>"
                                        data-departure-date="<?= $tour['departure_date'] ?>"
                                        data-tour-name="<?= htmlspecialchars($tour['tour_name']) ?>"
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
                <p class="text-muted mx-auto" style="max-width: 400px;">Hiện tại tất cả tour đã có HDV phụ trách. Vui lòng quay lại sau hoặc kiểm tra các tour khác.</p>
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
                    fetch('<?= BASE_URL_ADMIN ?>&action=available-tours/claim-tour', {
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

        // Handle departure date selection - auto show guide section
        document.querySelectorAll('[id^="departure-date-"]').forEach(select => {
            select.addEventListener('change', function() {
                const tourId = this.id.replace('departure-date-', '');
                const selectedDate = this.value;

                if (selectedDate) {
                    // Format date for display
                    const formattedDate = new Date(selectedDate).toLocaleDateString('vi-VN');

                    // Show confirmed date
                    const confirmedDateEl = document.getElementById(`confirmed-date-${tourId}`);
                    if (confirmedDateEl) {
                        confirmedDateEl.textContent = formattedDate;
                    }

                    // Show guide selection section
                    const guideSection = document.getElementById(`guide-section-${tourId}`);
                    if (guideSection) {
                        guideSection.style.display = 'block';
                    }
                } else {
                    // Hide guide selection if no date selected
                    const guideSection = document.getElementById(`guide-section-${tourId}`);
                    if (guideSection) {
                        guideSection.style.display = 'none';
                    }
                }
            });
        });

        // Handle edit date buttons
        document.querySelectorAll('.edit-date-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tourId = this.dataset.tourId;

                // Hide guide section
                document.getElementById(`guide-section-${tourId}`).style.display = 'none';

                // Show date select and confirm button again
                const dateSelectDiv = document.querySelector(`#departure-date-${tourId}`).closest('.mb-3');
                const confirmBtn = document.querySelector(`.confirm-date-btn[data-tour-id="${tourId}"]`);

                dateSelectDiv.style.display = 'block';
                confirmBtn.style.display = 'block';

                // Reset guide selection
                document.getElementById(`guide-select-${tourId}`).value = '';
            });
        });

        // Handle admin assign guide buttons
        document.querySelectorAll('.admin-assign-guide-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const uniqueId = this.dataset.uniqueId;
                const tourId = this.dataset.tourId;
                const tourName = this.dataset.tourName;
                const guideSelect = document.getElementById(`guide-select-${uniqueId}`);
                const guideId = guideSelect.value;

                if (!guideId) {
                    alert('⚠️ Vui lòng chọn HDV trước khi phân công!');
                    guideSelect.focus();
                    return;
                }

                const guideName = guideSelect.options[guideSelect.selectedIndex].text;

                if (confirm(`Phân công HDV "${guideName}" cho tour "${tourName}"?`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="ph ph-spinner fa-spin me-2"></i>Đang phân công...';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=available-tours/assign-guide', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `tour_id=${tourId}&guide_id=${guideId}&departure_id=${this.dataset.departureId || ''}&departure_date=${this.dataset.departureDate}`
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
                            alert('❌ Có lỗi xảy ra khi phân công HDV!');
                            this.disabled = false;
                            this.innerHTML = '<i class="ph-fill ph-user-check me-2"></i>Phân công ngay';
                        });
                }
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>