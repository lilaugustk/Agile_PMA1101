<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
$userRole = $_SESSION['user']['role'] ?? 'customer';
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tour Khả Dụng</li>
                </ol>
            </nav>
        </div>
    </div>
        <style>
            .available-tours-page .table th {
                font-size: 0.72rem;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: #64748b;
                font-weight: 700;
                padding: 1rem 0.75rem;
                border-bottom: 2px solid #f1f5f9;
            }
            .available-tours-page .table td {
                padding: 1.25rem 0.75rem;
                vertical-align: middle;
            }
            .badge { font-weight: 600; font-size: 0.7rem; }
            .btn-xs { padding: 0.35rem 0.75rem; font-size: 0.75rem; border-radius: 8px; font-weight: 600; }
            
            /* Animation cho trạng thái vượt quá số lượng */
            @keyframes pulse-red-border {
                0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
                70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
                100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
            }
            .overbooked-pulse {
                animation: pulse-red-border 2s infinite;
                border: 1px solid rgba(220, 53, 69, 0.5);
            }
            
            .card-premium {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.04);
                border: 1px solid rgba(0,0,0,0.05);
                overflow: hidden;
            }
            
            .tour-info-box {
                max-width: 300px;
            }
            
            .revenue-box {
                min-width: 120px;
            }
            
            .guide-select-wrapper {
                min-width: 200px;
            }
        </style>

        <div class="card-premium">
            <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                <div class="d-flex align-items-center gap-2">
                    <i class="ph-fill ph-calendar-check text-primary"></i>
                    <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Danh sách Tour Đang Khả Dụng</h6>
                    <span class="badge bg-light text-muted border ms-2 rounded-pill" style="font-size: 0.7rem;"><?= count($availableTours) ?> Tour</span>
                </div>
            </div>
            <div class="table-responsive bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4">Thông tin Tour</th>
                            <th>Khởi hành & Lấp đầy</th>
                            <th>Doanh thu</th>
                            <th>Đang phụ trách</th>
                            <th class="pe-4">Vận hành</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($availableTours as $row): ?>
                            <?php
                            $uniqueId = 'dep-' . (int)$row['departure_id'];
                            $hasGuide = !empty($row['assigned_guide_id']);
                            $bookedSeats = (int)($row['booked_seats_live'] ?? $row['booked_seats'] ?? 0);
                            $maxSeats = (int)($row['max_seats'] ?? 0);
                            $perc = ($maxSeats > 0) ? ($bookedSeats / $maxSeats) * 100 : 0;
                            $isFocusedDeparture = isset($_GET['departure_id']) && (int)$_GET['departure_id'] === (int)$row['departure_id'];
                            ?>
                            <tr class="<?= $isFocusedDeparture ? 'bg-primary bg-opacity-10' : '' ?> transition-all">
                                <td class="ps-4">
                                    <div class="tour-info-box">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem; line-height: 1.4;"><?= htmlspecialchars($row['tour_name'] ?? '') ?></div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-muted border px-2">#<?= (int)$row['departure_id'] ?></span>
                                            <small class="text-muted" style="font-size: 0.65rem;">Mã đoàn khởi hành</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2" style="max-width: 200px;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-1 text-primary fw-bold" style="font-size: 0.8rem;">
                                                <i class="ph-fill ph-calendar-blank"></i>
                                                <?= date('d/m/Y', strtotime($row['departure_date'])) ?>
                                            </div>
                                            <span class="badge bg-<?= $perc > 90 ? 'danger' : ($perc > 60 ? 'warning' : 'success') ?>-subtle text-<?= $perc > 90 ? 'danger' : ($perc > 60 ? 'warning' : 'success') ?> rounded-pill" style="font-size: 0.65rem;"><?= round($perc) ?>%</span>
                                        </div>
                                        <div class="progress <?= $perc > 100 ? 'overbooked-pulse' : '' ?>" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                                            <div class="progress-bar rounded-pill shadow-none <?= $perc > 100 ? 'bg-danger' : ($perc > 90 ? 'bg-warning' : 'bg-success') ?> <?= $perc > 80 ? 'progress-bar-striped progress-bar-animated' : '' ?>" 
                                                 style="width: <?= min(100, $perc) ?>%"></div>
                                        </div>
                                        <small class="text-muted fw-medium" style="font-size: 0.7rem;"><?= $bookedSeats ?> / <?= $maxSeats ?> chỗ đã đặt</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="revenue-box d-flex flex-column">
                                        <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= number_format((float)($row['total_booking_price'] ?? 0), 0, ',', '.') ?> <small class="fw-medium">₫</small></span>
                                        <small class="text-muted" style="font-size: 0.62rem;">Tổng doanh thu bộ phận</small>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($hasGuide): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.7rem;">
                                                <?= strtoupper(substr($row['assigned_guide_name'], 0, 1)) ?>
                                            </div>
                                            <span class="fw-semibold text-success small"><?= htmlspecialchars($row['assigned_guide_name']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary py-1.5 px-3 rounded-pill">
                                            <i class="ph ph-user-minus me-1"></i> Chưa phân công
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="guide-select-wrapper position-relative">
                                            <select
                                                class="form-select form-select-sm auto-assign-guide-select shadow-sm border-light-subtle"
                                                id="guide-select-<?= $uniqueId ?>"
                                                data-unique-id="<?= $uniqueId ?>"
                                                data-tour-id="<?= (int)$row['tour_id'] ?>"
                                                data-departure-id="<?= (int)$row['departure_id'] ?>"
                                                data-departure-date="<?= htmlspecialchars($row['departure_date']) ?>"
                                                data-tour-name="<?= htmlspecialchars($row['tour_name']) ?>"
                                                data-prev-value="<?= (int)($row['assigned_guide_id'] ?? 0) ?>"
                                                style="font-size: 0.75rem; border-radius: 8px;">
                                                <option value="">-- Chọn hướng dẫn viên --</option>
                                                <?php foreach ($guides as $guide): ?>
                                                    <option value="<?= (int)$guide['id'] ?>" <?= ((int)$guide['id'] === (int)($row['assigned_guide_id'] ?? 0)) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($guide['full_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="spinner-border spinner-border-sm text-primary position-absolute d-none saving-indicator" 
                                                 id="saving-<?= $uniqueId ?>" role="status" style="right: 35px; top: 10px;">
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-xs btn-primary d-flex align-items-center gap-2" href="<?= BASE_URL_ADMIN ?>&action=bookings/group-checkin&departure_id=<?= (int)$row['departure_id'] ?>">
                                                <i class="ph-bold ph-users-three"></i> Điểm danh đoàn
                                            </a>
                                            <?php if ($hasGuide): ?>
                                                <span class="d-flex align-items-center gap-1 text-success fw-bold" style="font-size: 0.65rem;">
                                                    <i class="ph-fill ph-check-circle"></i> Sẵn sàng
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.auto-assign-guide-select').forEach(select => {
            select.addEventListener('change', function() {
                const uniqueId = this.dataset.uniqueId;
                const guideId = this.value;
                if (!guideId) {
                    return;
                }

                const payload = new URLSearchParams({
                    tour_id: this.dataset.tourId,
                    departure_id: this.dataset.departureId,
                    departure_date: this.dataset.departureDate,
                    guide_id: guideId
                });

                const savingIndicator = document.getElementById(`saving-${uniqueId}`);
                const previousValue = this.dataset.prevValue || '';

                this.disabled = true;
                if (savingIndicator) savingIndicator.classList.remove('d-none');

                fetch('<?= BASE_URL_ADMIN ?>&action=available-tours/assign-guide', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: payload.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message || (data.success ? 'Thành công' : 'Có lỗi xảy ra'));
                        if (data.success) {
                            this.dataset.prevValue = guideId;
                            window.location.reload();
                            return;
                        }
                        this.value = previousValue;
                        this.disabled = false;
                        if (savingIndicator) savingIndicator.classList.add('d-none');
                    })
                    .catch(() => {
                        alert('Không thể gửi yêu cầu, vui lòng thử lại');
                        this.value = previousValue;
                        this.disabled = false;
                        if (savingIndicator) savingIndicator.classList.add('d-none');
                    });
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>