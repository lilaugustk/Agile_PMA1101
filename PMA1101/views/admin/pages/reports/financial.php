<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Get data from controller
$financialData = $data['financialData'] ?? [];
$tourFinancials = $data['tourFinancials'] ?? [];
$filters = $data['filters'] ?? [];
$filterOptions = $data['filterOptions'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=reports" class="text-decoration-none text-muted">Báo Cáo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tài Chính</li>
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
        <div class="card-body p-3">
            <form action="" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="mode" value="admin">
                <input type="hidden" name="action" value="reports/financial">
                
                <div class="col-12 col-md-3 col-xl-2">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Từ ngày</label>
                    <div class="input-group input-group-sm shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-calendar"></i></span>
                        <input type="date" name="date_from" class="form-control border-start-0 ps-0" value="<?= $filters['date_from'] ?? date('Y-m-01') ?>" style="border-radius: 0 8px 8px 0;">
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xl-2">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Đến ngày</label>
                    <div class="input-group input-group-sm shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-calendar"></i></span>
                        <input type="date" name="date_to" class="form-control border-start-0 ps-0" value="<?= $filters['date_to'] ?? date('Y-m-d') ?>" style="border-radius: 0 8px 8px 0;">
                    </div>
                </div>

                 <div class="col-12 col-md-4 col-xl-6">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Phân tích theo Tour</label>
                    <div class="input-group input-group-sm shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-airplane-tilt"></i></span>
                        <select name="tour_id" class="form-select no-choices border-start-0 ps-0 shadow-none">
                            <option value="">Tất cả các Tour trong hệ thống</option>
                            <?php if (!empty($filterOptions['tours'])): ?>
                                <?php foreach ($filterOptions['tours'] as $tour): ?>
                                    <option value="<?= $tour['id'] ?>" <?= ($filters['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-2 col-xl-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1 shadow-sm d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; min-height: 38px;">
                        <i class="ph ph-funnel"></i> Lọc dữ liệu
                    </button>
                    <button type="button" onclick="resetFilters()" class="btn btn-sm btn-light border shadow-sm px-3 d-flex align-items-center justify-content-center" title="Đặt lại" style="border-radius: 8px; min-height: 38px;">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Revenue -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--success-subtle);">
                        <i class="ph ph-currency-circle-dollar" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-success-subtle text-success" style="font-size: 0.7rem;">+<?= $financialData['total_bookings'] ?? 0 ?> bookings</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Doanh Thu</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= number_format($financialData['total_revenue'] ?? 0, 0, ',', '.') ?> <span style="font-size: 0.8rem; font-weight: 500;">₫</span></h3>
                </div>
            </div>
        </div>

        <!-- Total Estimated Expense -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--warning-subtle);">
                        <i class="ph ph-hash" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-warning-subtle text-warning" style="font-size: 0.7rem;">Dự toán (BSA)</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Chi Dự Kiến</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= number_format($financialData['total_estimated_expense'] ?? 0, 0, ',', '.') ?> <span style="font-size: 0.8rem; font-weight: 500;">₫</span></h3>
                </div>
            </div>
        </div>

        <!-- Total Actual Expense -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-danger border border-danger-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--danger-subtle);">
                        <i class="ph ph-receipt" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 0.7rem;">Thực tế (Logs)</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tổng Chi Thực Tế</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= number_format($financialData['total_actual_expense'] ?? 0, 0, ',', '.') ?> <span style="font-size: 0.8rem; font-weight: 500;">₫</span></h3>
                </div>
            </div>
        </div>

        <!-- Actual Profit -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-premium p-3 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--primary-subtle);">
                        <i class="ph ph-trend-up" style="font-size: 1rem;"></i>
                    </div>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Lợi Nhuận Thực</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= number_format($financialData['actual_profit'] ?? 0, 0, ',', '.') ?> <span style="font-size: 0.8rem; font-weight: 500;">₫</span></h3>
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
                    <i class="ph-fill ph-chart-bar text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Doanh Thu & Chi Phí Theo Tháng</h5>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="monthlyFinancialChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Pie Chart -->
        <div class="col-12 col-lg-4">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom border-light py-3 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">Lợi Nhuận Top 5 Tours</h5>
                </div>
                <div class="card-body">
                    <div style="height: 350px;" class="d-flex align-items-center justify-content-center">
                        <canvas id="tourProfitChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Row -->
    <div class="card card-premium border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom border-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="ph-fill ph-list-numbers text-primary"></i> Chi Tiết Tài Chính Theo Tour
            </h5>
            <span class="badge bg-light text-muted border fw-medium" style="font-size: 0.75rem;"><?= count($tourFinancials) ?> tours</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="border-0 ps-3">#</th>
                            <th class="border-0">Tên Tour</th>
                            <th class="border-0 text-end pe-3">Doanh Thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tourFinancials)): ?>
                            <?php foreach ($tourFinancials as $index => $tour): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted" style="font-size: 0.8rem;"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($tour['tour_name']) ?>">
                                            <?= htmlspecialchars($tour['tour_name']) ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($tour['category_name'] ?? 'N/A') ?></div>
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-success"><?= number_format($tour['revenue'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ph ph-folder-open fa-3x mb-3"></i>
                                        <p>Chưa có dữ liệu phù hợp với bộ lọc.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($tourFinancials)): ?>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="2" class="text-end ps-3">TỔNG CỘNG</td>
                                <td class="text-end pe-3 text-success"><?= number_format(array_sum(array_column($tourFinancials, 'revenue')), 0, ',', '.') ?> ₫</td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SaaS Chart Settings
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = "#64748b";
        Chart.defaults.scale.grid.color = "#f1f5f9";

        // Re-use data from PHP
        const monthlyLabels = <?= json_encode($data['monthlyLabels'] ?? []) ?>;
        const monthlyRevenue = <?= json_encode($data['monthlyRevenue'] ?? []) ?>;
        const monthlyEstimatedExpense = <?= json_encode($data['monthlyEstimatedExpense'] ?? []) ?>;
        const monthlyActualExpense = <?= json_encode($data['monthlyActualExpense'] ?? []) ?>;
        const monthlyProfit = <?= json_encode($data['monthlyProfit'] ?? []) ?>;

        const tourNames = <?= json_encode($data['tourNames'] ?? []) ?>;
        const tourProfits = <?= json_encode($data['tourProfits'] ?? []) ?>;

        // Monthly Financial Chart
        const monthlyCtx = document.getElementById('monthlyFinancialChart');
        if (monthlyCtx && monthlyLabels.length > 0) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Doanh Thu',
                        data: monthlyRevenue,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderWidth: 0,
                        borderRadius: 4,
                        barThickness: 8
                    }, {
                        label: 'Chi dự toán (BSA)',
                        data: monthlyEstimatedExpense,
                        backgroundColor: 'rgba(245, 158, 11, 0.5)',
                        borderWidth: 0,
                        borderRadius: 4, 
                        barThickness: 8
                    }, {
                        label: 'Chi thực tế (Logs)',
                        data: monthlyActualExpense,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderWidth: 0,
                        borderRadius: 4, 
                        barThickness: 8
                    }, {
                        label: 'Lợi Nhuận Thực',
                        data: monthlyProfit,
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderWidth: 0,
                        borderRadius: 4, 
                        barThickness: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'top', 
                            labels: { 
                                usePointStyle: true, 
                                pointStyle: 'circle', 
                                padding: 25,
                                font: { size: 11, weight: '600' }
                            } 
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' +
                                        new Intl.NumberFormat('vi-VN').format(context.raw) + ' ₫';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            border: { display: false, dash: [4, 4] },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(0) + 'tr';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                    return value;
                                },
                                font: { size: 10 }
                            }
                        },
                        x: { border: { display: false }, grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

        // Tour Profit Pie Chart
        const profitCtx = document.getElementById('tourProfitChart');
        if (profitCtx && tourNames.length > 0) {
            new Chart(profitCtx, {
                type: 'doughnut',
                data: {
                    labels: tourNames,
                    datasets: [{
                        data: tourProfits,
                        backgroundColor: ['#4361ee', '#10b981', '#f59e0b', '#ef4444', '#7209b7', '#4cc9f0'],
                        borderWidth: 4,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                usePointStyle: true, 
                                pointStyle: 'circle', 
                                padding: 20, 
                                font: { size: 11, weight: '500' } 
                            } 
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const value = new Intl.NumberFormat('vi-VN').format(context.raw) + ' ₫';
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1) + '%';
                                    return value + ' (' + percentage + ')';
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });

    function resetFilters() {
        window.location.href = '<?= BASE_URL_ADMIN ?>&action=reports/financial';
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>