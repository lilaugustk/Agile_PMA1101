<?php
$data = $data ?? [];
extract($data);
?>
<!-- Main Content -->
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item active" aria-current="page"><i class="ph ph-house me-1"></i> Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm bg-white text-muted border shadow-sm d-flex align-items-center gap-2" onclick="window.location.reload()">
                <i class="ph ph-arrows-clockwise"></i> Làm mới
            </button>
            <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                <i class="ph ph-plus-circle"></i> Quản lý Booking
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-5">
        <!-- Revenue Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-premium p-3 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <?php
                    $revDiff = $monthlyRevenue - $lastMonthRevenue;
                    $revPercent = $lastMonthRevenue > 0 ? ($revDiff / $lastMonthRevenue) * 100 : ($monthlyRevenue > 0 ? 100 : 0);
                    $isPositive = $revDiff >= 0;
                    ?>
                    <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--primary-subtle);">
                        <i class="ph ph-currency-circle-dollar" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill <?= $isPositive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>" style="font-size: 0.7rem;">
                        <i class="ph ph-trend-<?= $isPositive ? 'up' : 'down' ?> me-1"></i> <?= number_format(abs($revPercent), 1) ?>%
                    </span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Doanh thu tháng này</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($monthlyRevenue) ?> <span style="font-size: 0.9rem; font-weight: 500;">₫</span></h3>
                </div>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-premium p-3 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--success-subtle);">
                        <i class="ph ph-shopping-cart-simple" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-primary-subtle text-primary" style="font-size: 0.7rem;">THÁNG <?= $currentMonthName ?></span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Booking mới</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($newBookings) ?></h3>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-premium p-3 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--info-subtle);">
                        <i class="ph ph-users" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-info-subtle text-info" style="font-size: 0.7rem;">+<?= $newCustomers ?> mới</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Khách hàng mới</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($newCustomers) ?></h3>
                </div>
            </div>
        </div>

        <!-- Tours Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-premium p-3 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle rounded-circle" style="width: 32px; height: 32px; background: var(--warning-subtle);">
                        <i class="ph ph-airplane-tilt" style="font-size: 1rem;"></i>
                    </div>
                    <span class="badge rounded-pill bg-danger-subtle text-danger" style="font-size: 0.7rem;">LIVE</span>
                </div>
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Tour đang khởi hành</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($ongoingTours) ?></h3>
                </div>
            </div>
        </div>
    </div> <!-- /row -->

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-xl-8">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="letter-spacing: -0.3px; font-size: 1.1rem;">
                        <i class="ph-fill ph-chart-line-up text-primary"></i> Doanh thu 6 tháng gần nhất
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-xs btn-light border text-muted px-2 py-1" type="button" style="font-size: 0.75rem;">
                            <i class="ph ph-calendar-blank me-1"></i> 6 Tháng
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <div style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card card-premium border-0 shadow-sm h-100">
                <div class="p-4 border-bottom border-light bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="letter-spacing: -0.3px; font-size: 1.1rem;">
                        <i class="ph-fill ph-chart-pie-slice text-primary"></i> Trạng thái Booking
                    </h5>
                </div>
                <div class="p-4 d-flex justify-content-center align-items-center" style="height: 320px;">
                    <div style="height: 280px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Style overrides for charts to match SaaS vibe
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = "#64748b";
        Chart.defaults.scale.grid.color = "#f1f5f9";

        // Revenue Data
        const revenueData = <?= json_encode($revenueData ?? []) ?>;
        const revLabels = revenueData.map(d => d.month);
        const revValues = revenueData.map(d => d.revenue);

        // Render Revenue Chart
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient for primary line chart
        let gradient = ctxRev.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)'); // var(--primary)
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: revValues,
                    borderColor: '#2563eb', // var(--primary)
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointRadius: 0,
                    pointHitRadius: 20,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND',
                                    maximumFractionDigits: 0
                                }).format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false, dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(0) + 'tr';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                return value;
                            },
                            padding: 10,
                            font: { size: 11 }
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: { padding: 10, font: { size: 11 } }
                    }
                }
            }
        });

        // Booking Status Data
        const statusStats = <?= json_encode($bookingStatusData['stats'] ?? []) ?>;
        const statusLabels = statusStats.map(d => d.status);
        const statusValues = statusStats.map(d => d.count);

        // Render Status Pie Chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#2563eb', // primary
                        '#10b981', // success
                        '#f59e0b', // warning
                        '#0ea5e9', // info
                        '#6366f1', // indigo
                        '#ef4444'  // danger
                    ],
                    borderWidth: 4,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>