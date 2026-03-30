<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$bookingStats = $data['bookingStats'] ?? [];
$bookings = $data['bookings'] ?? [];
$topTours = $data['topTours'] ?? [];
$filters = $data['filters'] ?? [];
$tours = $data['tours'] ?? [];
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=reports" class="text-decoration-none text-muted">Báo Cáo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm bg-white text-muted border shadow-sm d-flex align-items-center gap-2" onclick="window.location.reload()">
                <i class="ph ph-arrows-clockwise"></i> Làm mới
            </button>
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 shadow-sm" onclick="window.print()">
                <i class="ph ph-printer"></i> In báo cáo
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="mode" value="admin">
                <input type="hidden" name="action" value="reports/bookings">
                
                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Từ ngày</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-calendar text-muted"></i></span>
                        <input type="date" name="date_from" class="form-control border-start-0 ps-0" value="<?= $filters['date_from'] ?? date('Y-m-01') ?>">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Đến ngày</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="ph ph-calendar text-muted"></i></span>
                        <input type="date" name="date_to" class="form-control border-start-0 ps-0" value="<?= $filters['date_to'] ?? date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Trạng thái</label>
                    <select name="status" class="form-select form-select-sm shadow-none">
                        <option value="">Tất cả</option>
                        <option value="cho_xac_nhan" <?= ($filters['status'] ?? '') == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="da_coc" <?= ($filters['status'] ?? '') == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                        <option value="hoan_tat" <?= ($filters['status'] ?? '') == 'hoan_tat' ? 'selected' : '' ?>>Hoàn tất</option>
                        <option value="da_huy" <?= ($filters['status'] ?? '') == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1 text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase;">Tour</label>
                    <select name="tour_id" class="form-select form-select-sm shadow-none">
                        <option value="">Tất cả Tour</option>
                        <?php if (!empty($tours)): ?>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?= $tour['id'] ?>" <?= ($filters['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tour['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ph ph-funnel"></i> Lọc
                    </button>
                    <a href="<?= BASE_URL_ADMIN ?>&action=reports/bookings" class="btn btn-sm btn-light border shadow-sm px-3">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Booking -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--primary-subtle);">
                        <i class="ph ph-shopping-cart-simple" style="font-size: 1rem;"></i>
                    </div>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Booking</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($bookingStats['total_bookings'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Successful -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--success-subtle);">
                        <i class="ph ph-check-circle" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 0.7rem;"><?= number_format($bookingStats['success_rate'] ?? 0, 1) ?>% Rate</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Hoàn Tất</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($bookingStats['successful_bookings'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Cancelled -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-danger border border-danger-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--danger-subtle);">
                        <i class="ph ph-x-circle" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 0.7rem;"><?= number_format($bookingStats['cancellation_rate'] ?? 0, 1) ?>% Cancelled</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Đã Hủy</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($bookingStats['cancelled_bookings'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--info-subtle);">
                        <i class="ph ph-users" style="font-size: 1rem;"></i>
                    </div>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Khách Hàng</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($bookingStats['total_customers'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Monthly Chart -->
        <div class="col-12 col-lg-8">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-chart-line text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Booking Theo Tháng</h5>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="monthlyBookingsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Source Pie Chart -->
        <div class="col-12 col-lg-4">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Nguồn Booking</h5>
                </div>
                <div class="card-body">
                    <div style="height: 320px;" class="d-flex align-items-center justify-content-center">
                        <canvas id="sourceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Tours Table -->
    <div class="card card-premium border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom border-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="ph-fill ph-medal text-warning"></i> Top Tours Được Đặt Nhiều Nhất
            </h5>
            <span class="badge bg-light text-muted border fw-medium" style="font-size: 0.75rem;">Premium Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3 border-0">Thứ hạng</th>
                            <th class="border-0">Tên Tour</th>
                            <th class="border-0 text-end">Số Booking</th>
                            <th class="border-0 text-end">Tổng Khách</th>
                            <th class="border-0 text-end pe-3">Doanh Thu Dự Kiến</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topTours)): ?>
                            <?php foreach ($topTours as $index => $tour): ?>
                                <tr>
                                    <td class="ps-3">
                                        <?php if($index < 3): ?>
                                            <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'bronze') ?>-subtle text-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'bronze') ?> rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                                                <?= $index + 1 ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted ms-2 fw-medium"><?= $index + 1 ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 320px;" title="<?= htmlspecialchars($tour['tour_name']) ?>">
                                            <i class="ph ph-airplane text-muted me-2"></i>
                                            <?= htmlspecialchars($tour['tour_name']) ?>
                                        </div>
                                    </td>
                                    <td class="text-end fw-medium"><?= number_format($tour['booking_count']) ?></td>
                                    <td class="text-end text-muted"><?= number_format($tour['total_passengers'] ?? 0) ?> khách</td>
                                    <td class="text-end pe-3 fw-bold text-success"><?= number_format($tour['total_revenue'] ?? 0, 0, ',', '.') ?> <span style="font-weight: 400; font-size: 0.8rem;">₫</span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SaaS Chart Settings
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = "#64748b";
    Chart.defaults.scale.grid.color = "#f1f5f9";

    <?php if (isset($data['monthlyBookings'])): ?>
    const ctxMonthly = document.getElementById('monthlyBookingsChart').getContext('2d');
    
    // Gradients
    let gradPrimary = ctxMonthly.createLinearGradient(0, 0, 0, 320);
    gradPrimary.addColorStop(0, 'rgba(37, 99, 235, 0.1)');
    gradPrimary.addColorStop(1, 'rgba(37, 99, 235, 0)');

    let gradSuccess = ctxMonthly.createLinearGradient(0, 0, 0, 320);
    gradSuccess.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
    gradSuccess.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: <?= json_encode($data['monthlyLabels'] ?? []) ?>,
            datasets: [{
                label: 'Tổng Booking',
                data: <?= json_encode($data['monthlyBookings'] ?? []) ?>,
                borderColor: '#2563eb',
                backgroundColor: gradPrimary,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHitRadius: 20
            }, {
                label: 'Hoàn Tất',
                data: <?= json_encode($data['monthlySuccessfulBookings'] ?? []) ?>,
                borderColor: '#10b981',
                backgroundColor: gradSuccess,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHitRadius: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20 } },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8 }
            },
            scales: { 
                y: { beginAtZero: true, border: { display: false, dash: [4, 4] } },
                x: { border: { display: false }, grid: { display: false } }
            }
        }
    });
    <?php endif; ?>

    <?php if (isset($data['sourceNames'])): ?>
    new Chart(document.getElementById('sourceChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['sourceNames'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['sourceCounts'] ?? []) ?>,
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#6366f1'],
                borderWidth: 4,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 15, font: { size: 10 } } },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8 }
            },
            cutout: '70%'
        }
    });
    <?php endif; ?>
});
</script>
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
