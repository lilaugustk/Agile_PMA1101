<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$conversionData = $data['conversionData'] ?? [];
$topTours = $data['topTours'] ?? [];
$sourceConversion = $data['sourceConversion'] ?? [];
$filters = $data['filters'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=reports" class="text-decoration-none text-muted">Báo Cáo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chuyển Đổi</li>
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

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Inquiries -->
        <div class="col-12 col-sm-6">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--info-subtle);">
                        <i class="ph ph-eye" style="font-size: 1rem;"></i>
                    </div>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Inquiry</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($conversionData['total_inquiries'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <!-- Overall Rate -->
        <div class="col-12 col-sm-6">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--primary-subtle);">
                        <i class="ph ph-percent" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-primary-subtle text-primary" style="font-size: 0.7rem;">Overall Conversion</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tỷ Lệ Tổng Thể</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($conversionData['conversion_rates']['overall'] ?? 0, 1) ?>%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Funnel Chart -->
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
            <i class="ph-fill ph-funnel text-primary"></i>
            <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Phễu Chuyển Đổi</h5>
        </div>
        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="funnelChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Converting Tours -->
    <div class="card card-premium border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom border-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="ph-fill ph-rocket text-primary"></i> Top Tours Theo Tỷ Lệ Chuyển Đổi
            </h5>
            <span class="badge bg-light text-muted border fw-medium" style="font-size: 0.75rem;">SaaS Performance</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3 border-0">Thứ hạng</th>
                            <th class="border-0">Tên Tour</th>
                            <th class="border-0 text-center">Liên hệ</th>
                            <th class="border-0 text-center">Đặt tour</th>
                            <th class="border-0 text-center">Thanh toán</th>
                            <th class="border-0 text-end pe-3">Tỷ Lệ Chuyển Đổi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topTours)): ?>
                            <?php foreach (array_slice($topTours, 0, 10) as $index => $tour): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted" style="font-size: 0.8rem;"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 320px;" title="<?= htmlspecialchars($tour['tour_name']) ?>">
                                            <i class="ph ph-compass text-muted me-2"></i>
                                            <?= htmlspecialchars($tour['tour_name']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center fw-medium"><?= number_format($tour['inquiries'] ?? 0) ?></td>
                                    <td class="text-center text-muted"><?= number_format($tour['bookings'] ?? 0) ?></td>
                                    <td class="text-center text-muted"><?= number_format($tour['payments'] ?? 0) ?></td>
                                    <td class="text-end pe-3">
                                        <?php $rate = $tour['conversion_rate'] ?? 0; ?>
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <div class="progress shadow-none" style="height: 6px; width: 60px; background-color: #f1f5f9;">
                                                <div class="progress-bar bg-<?= $rate >= 50 ? 'success' : ($rate >= 25 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $rate ?>%"></div>
                                            </div>
                                            <span class="badge bg-<?= $rate >= 50 ? 'success' : ($rate >= 25 ? 'warning' : 'danger') ?>-subtle text-<?= $rate >= 50 ? 'success' : ($rate >= 25 ? 'warning' : 'danger') ?> fw-bold" style="min-width: 50px;">
                                                <?= number_format($rate, 1) ?>%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

    new Chart(document.getElementById('funnelChart'), {
        type: 'bar',
        data: {
            labels: ['Liên hệ', 'Đặt tour', 'Thanh toán', 'Hoàn tất'],
            datasets: [{
                label: 'Số lượng',
                data: [
                    <?= $conversionData['total_inquiries'] ?? 0 ?>,
                    <?= $conversionData['total_bookings'] ?? 0 ?>,
                    <?= $conversionData['total_payments'] ?? 0 ?>,
                    <?= $conversionData['total_completed'] ?? 0 ?>
                ],
                backgroundColor: [
                    'rgba(37, 99, 235, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(99, 102, 241, 0.8)'
                ],
                borderColor: [
                    '#2563eb',
                    '#f59e0b',
                    '#10b981',
                    '#6366f1'
                ],
                borderWidth: 0,
                borderRadius: 6,
                barThickness: 32
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8 }
            },
            scales: { 
                x: { beginAtZero: true, border: { display: false, dash: [4, 4] } },
                y: { border: { display: false }, grid: { display: false } }
            }
        }
    });
});
</script>
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
