<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$feedbackStats = $data['feedbackStats'] ?? [];
$feedbacks = $data['feedbacks'] ?? [];
$topRatedTours = $data['topRatedTours'] ?? [];
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
                    <li class="breadcrumb-item active" aria-current="page">Phản Hồi</li>
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

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Avg Rating -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--warning-subtle);">
                        <i class="ph-fill ph-star" style="font-size: 1rem;"></i>
                    </div>
                    <?php if (isset($feedbackStats['rating_growth'])): ?>
                        <?php $isPositive = $feedbackStats['rating_growth'] >= 0; ?>
                        <span class="badge rounded-pill <?= $isPositive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>" style="font-size: 0.7rem;">
                            <i class="ph ph-trend-<?= $isPositive ? 'up' : 'down' ?> me-1"></i> <?= number_format(abs($feedbackStats['rating_growth']), 1) ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Đánh Giá TB</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($feedbackStats['avg_rating'] ?? 0, 1) ?><span class="text-muted ms-1" style="font-size: 0.9rem; font-weight: 500;">/ 5.0</span></h3>
                </div>
            </div>
        </div>

        <!-- Total Feedback -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--info-subtle);">
                        <i class="ph ph-chat-centered-text" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-info-subtle text-info" style="font-size: 0.7rem;"><?= number_format($feedbackStats['feedback_rate'] ?? 0, 1) ?>% rate</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Phản Hồi</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($feedbackStats['total_feedbacks'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Positive Feedback -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--success-subtle);">
                        <i class="ph ph-smiley" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 0.7rem;"><?= number_format($feedbackStats['positive_rate'] ?? 0, 1) ?>%</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tích Cực</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($feedbackStats['positive_feedbacks'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Negative Feedback -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-danger border border-danger-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--danger-subtle);">
                        <i class="ph ph-smiley-sad" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 0.7rem;"><?= number_format($feedbackStats['negative_rate'] ?? 0, 1) ?>%</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tiêu Cực</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($feedbackStats['negative_feedbacks'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="card card-premium border-0 shadow-sm h-100">
                 <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-chart-bar text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Phân Bổ Đánh Giá</h5>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="ratingDistChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-chart-pie text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Loại Phản Hồi</h5>
                </div>
                <div class="card-body">
                     <div style="height: 320px;" class="d-flex align-items-center justify-content-center">
                        <canvas id="feedbackTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Rated Tours -->
    <div class="card card-premium border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom border-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="ph-fill ph-award text-warning"></i> Top Tours Được Đánh Giá Cao Nhất
            </h5>
            <span class="badge bg-light text-muted border fw-medium" style="font-size: 0.75rem;">Premium Metrics</span>
        </div>
         <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3 border-0">#</th>
                            <th class="border-0">Tên Tour</th>
                            <th class="border-0 text-center">Số Đánh Giá</th>
                            <th class="border-0 text-end pe-3">Rating TB</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topRatedTours)): ?>
                            <?php foreach ($topRatedTours as $index => $tour): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted" style="font-size: 0.8rem;"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 350px;" title="<?= htmlspecialchars($tour['tour_name']) ?>">
                                            <i class="ph ph-airplane-tilt text-muted me-2"></i>
                                            <?= htmlspecialchars($tour['tour_name']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center fw-medium"><?= number_format($tour['feedback_count']) ?></td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <div class="d-flex align-items-center text-warning" style="font-size: 0.85rem;">
                                                <?php
                                                $rating = $tour['avg_rating'] ?? 0;
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= floor($rating)) echo '<i class="ph-fill ph-star"></i>';
                                                    elseif ($i - 0.5 <= $rating) echo '<i class="ph-fill ph-star-half"></i>';
                                                    else echo '<i class="ph ph-star text-muted opacity-25"></i>';
                                                }
                                                ?>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning fw-bold" style="font-size: 0.8rem; min-width: 35px;"><?= number_format($rating, 1) ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">Chưa có dữ liệu</td></tr>
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

    <?php if (isset($data['ratingDistribution'])): ?>
    new Chart(document.getElementById('ratingDistChart'), {
        type: 'bar',
        data: {
            labels: ['5 Sao', '4 Sao', '3 Sao', '2 Sao', '1 Sao'],
            datasets: [{
                label: 'Số lượng',
                data: <?= json_encode($data['ratingDistribution'] ?? [0,0,0,0,0]) ?>,
                backgroundColor: ['#10b981', '#2563eb', '#f59e0b', '#ef4444', '#991b1b'],
                borderRadius: 4,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8 }
            },
            scales: { 
                y: { beginAtZero: true, border: { display: false, dash: [4, 4] } },
                x: { border: { display: false }, grid: { display: false } }
            }
        }
    });
    <?php endif; ?>

    <?php if (isset($data['feedbackTypeLabels'])): ?>
    new Chart(document.getElementById('feedbackTypeChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['feedbackTypeLabels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['feedbackTypeCounts'] ?? []) ?>,
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b'],
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
