<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$supplierDebts = $data['supplierDebts'] ?? [];
$customerDebts = $data['customerDebts'] ?? [];

$totalSupplierDebt = array_sum(array_column($supplierDebts, 'debt'));
$totalCustomerDebt = array_sum(array_map(function($d) { return $d['final_price'] - $d['paid_amount']; }, $customerDebts));
?>

<main class="content">
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
        <div class="col-12 col-md-6">
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
        <div class="col-12 col-md-6">
            <div class="card-premium p-4 d-flex align-items-center justify-content-between stat-card-premium stat-danger shadow-hover">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="ph-fill ph-trend-down"></i>
                    </div>
                    <div>
                        <span class="stat-label">Tổng nợ phải trả (Nhà cung cấp)</span>
                        <h3 class="stat-value text-danger"><?= number_format($totalSupplierDebt, 0, ',', '.') ?> ₫</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs Card -->
    <div class="card-premium border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white p-0 border-bottom">
            <ul class="nav nav-tabs nav-fill border-0" id="debtTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier-debt" type="button" role="tab">
                        <i class="ph ph-handshake fs-5"></i> Nợ Phải trả (Nhà cung cấp)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-debt" type="button" role="tab">
                        <i class="ph ph-users fs-5"></i> Nợ Phải thu (Khách hàng)
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="debtTabsContent">
                <!-- Supplier Debt Tab -->
                <div class="tab-pane fade show active" id="supplier-debt" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Nhà cung cấp</th>
                                    <th class="text-end">Tổng giá dịch vụ</th>
                                    <th class="text-end">Đã thanh toán</th>
                                    <th class="text-end">Còn nợ</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($supplierDebts)): ?>
                                    <?php foreach ($supplierDebts as $d): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($d['supplier_name']) ?></div>
                                                <small class="text-muted">ID: #<?= $d['id'] ?></small>
                                            </td>
                                            <td class="text-end fw-semibold"><?= number_format($d['total_payable'], 0, ',', '.') ?> ₫</td>
                                            <td class="text-end text-success"><?= number_format($d['total_paid'], 0, ',', '.') ?> ₫</td>
                                            <td class="text-end fw-bold text-danger"><?= number_format($d['debt'], 0, ',', '.') ?> ₫</td>
                                            <td class="text-center">
                                                <span class="badge-premium badge-da_huy px-3">Đang nợ</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-premium-sm btn-light border px-3">
                                                    <i class="ph ph-eye"></i> Chi tiết
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="ph ph-detective fs-1 text-muted opacity-25"></i>
                                            </div>
                                            <h6 class="text-muted fw-normal">Hiện tại không có khoản nợ nhà cung cấp nào.</h6>
                                            <p class="small text-muted mb-0">Hệ thống sẽ hiển thị khi bạn có chi phí logistics chưa thanh toán.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Customer Debt Tab -->
                <div class="tab-pane fade" id="customer-debt" role="tabpanel">
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
                                        <?php $remaining = $c['final_price'] - $c['paid_amount']; ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($c['customer_name']) ?></div>
                                                <small class="text-muted">ID Booking: <span class="fw-medium">#<?= $c['id'] ?></span></small>
                                            </td>
                                            <td>
                                                <div class="text-truncate fw-medium" style="max-width: 220px;"><?= htmlspecialchars($c['tour_name']) ?></div>
                                                <small class="text-primary d-flex align-items-center gap-1">
                                                    <i class="ph ph-calendar"></i> <?= date('d/m/Y', strtotime($c['departure_date'])) ?>
                                                </small>
                                            </td>
                                            <td class="text-end fw-bold text-dark"><?= number_format($c['final_price'], 0, ',', '.') ?> ₫</td>
                                            <td class="text-end text-success"><?= number_format($c['paid_amount'], 0, ',', '.') ?> ₫</td>
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
    </div>
</main>

<style>
    /* Custom spacing and overrides for debt module */
    .nav-tabs .nav-link {
        color: #64748b;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary);
        background: #fff;
        border-bottom-color: var(--primary) !important;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        background: #f8fafc;
        border-color: transparent;
    }
    .stat-label { font-size: 0.8rem; }
    .stat-value { font-size: 1.6rem; letter-spacing: -0.5px; }
    
    @media print {
        .sidebar, .navbar-header, .btn-outline-primary { display: none !important; }
        .content { margin-left: 0 !important; padding: 0 !important; }
        .card-premium { border: 1px solid #ddd !important; box-shadow: none !important; }
    }
</style>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
