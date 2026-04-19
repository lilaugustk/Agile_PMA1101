<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$customerDebts = $data['customerDebts'] ?? [];
$totalCustomerDebt = array_sum(array_map(function($d) { return ($d['final_price'] ?? 0) - ($d['paid_amount'] ?? 0); }, $customerDebts));
?>

<main class="dashboard">
    <div class="dashboard-container">
    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=reports" class="text-muted text-decoration-none">Báo cáo</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Công nợ</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                <i class="ph ph-printer" style="font-size: 1.1rem;"></i> Xuất Báo Cáo
            </button>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card-premium p-4 d-flex align-items-center justify-content-between stat-card-premium stat-success shadow-hover">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="ph-fill ph-trend-up"></i>
                    </div>
                    <div>
                        <span class="stat-label">Tổng nợ phải thu (Khách hàng)</span>
                        <h3 class="stat-value text-success"><?= number_format($totalCustomerDebt, 0, ',', '.') ?> ₫</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card-premium border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white p-3 border-bottom d-flex align-items-center gap-2">
            <i class="ph ph-users-three fs-5 text-primary"></i>
            <h6 class="fw-bold mb-0">Danh sách Nợ Phải thu (Khách hàng)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">Khách hàng / Booking</th>
                            <th>Tour / Ngày đi</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-end">Đã đóng</th>
                            <th class="text-end">Còn thiếu</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customerDebts)): ?>
                            <?php foreach ($customerDebts as $c): ?>
                                <?php $remaining = ($c['final_price'] ?? 0) - ($c['paid_amount'] ?? 0); ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($c['customer_name'] ?? 'N/A') ?></div>
                                        <small class="text-muted">ID Booking: <span class="fw-medium">#<?= $c['id'] ?></span></small>
                                    </td>
                                    <td>
                                        <div class="text-truncate fw-medium" style="max-width: 250px;"><?= htmlspecialchars($c['tour_name'] ?? 'N/A') ?></div>
                                        <small class="text-primary d-flex align-items-center gap-1">
                                            <i class="ph ph-calendar"></i> <?= date('d/m/Y', strtotime($c['departure_date'] ?? 'now')) ?>
                                        </small>
                                    </td>
                                    <td class="text-end fw-bold text-dark"><?= number_format($c['final_price'] ?? 0, 0, ',', '.') ?> ₫</td>
                                    <td class="text-end text-success"><?= number_format($c['paid_amount'] ?? 0, 0, ',', '.') ?> ₫</td>
                                    <td class="text-end fw-bold text-warning"><?= number_format($remaining, 0, ',', '.') ?> ₫</td>
                                    <td class="text-end pe-4">
                                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=<?= $c['id'] ?>" class="btn btn-premium-sm btn-primary px-3">
                                            <i class="ph ph-wallet"></i> Thu tiền
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="ph ph-check-circle fs-1 text-success opacity-25"></i>
                                    </div>
                                    <h6 class="text-muted fw-normal">Tất cả khách hàng đã đóng đủ tiền.</h6>
                                    <p class="small text-muted mb-0">Tuyệt vời! Không có công nợ quá hạn từ phía khách hàng.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</main>

<style>
    .stat-label { font-size: 0.8rem; }
    .stat-value { font-size: 1.6rem; letter-spacing: -0.5px; }
    
    @media print {
        .sidebar, .navbar-header, .btn-outline-primary { display: none !important; }
        .dashboard { margin-left: 0 !important; padding: 0 !important; }
        .dashboard-container { padding: 0 !important; }
        .card-premium { border: 1px solid #ddd !important; box-shadow: none !important; }
    }
</style>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
