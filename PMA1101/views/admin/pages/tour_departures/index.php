<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// $departures hiện tại được truyền từ Controller là mảng kết quả
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none">Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vận hành đoàn</li>
                </ol>
            </nav>
            <h4 class="fw-bold mt-2 mb-0" style="font-size: 1.25rem; letter-spacing: -0.5px;">Quản lý Lịch khởi hành & Vận hành</h4>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card-premium mb-4">
        <div class="p-2 px-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;"><i class="ph ph-funnel text-muted"></i> Bộ Lọc Vận Hành</h6>
        </div>
        <div class="p-3 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form method="GET" action="<?= BASE_URL_ADMIN ?>">
                <input type="hidden" name="action" value="tours/departures">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Tìm theo tên tour</label>
                        <div class="position-relative">
                            <i class="ph ph-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%);"></i>
                            <input type="text" class="form-control form-control-sm ps-4" name="keyword" 
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" placeholder="Nhập tên tour...">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Trạng thái</label>
                        <select class="form-select form-select-sm" name="status">
                            <option value="">Tất cả</option>
                            <option value="open" <?= (($_GET['status'] ?? '') == 'open') ? 'selected' : '' ?>>Đang mở (Open)</option>
                            <option value="guaranteed" <?= (($_GET['status'] ?? '') == 'guaranteed') ? 'selected' : '' ?>>Chắc chắn khởi hành</option>
                            <option value="closed" <?= (($_GET['status'] ?? '') == 'closed') ? 'selected' : '' ?>>Đã đóng (Closed)</option>
                            <option value="cancelled" <?= (($_GET['status'] ?? '') == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Từ ngày đi</label>
                        <input type="date" class="form-control form-control-sm" name="date_from" value="<?= $_GET['date_from'] ?? '' ?>">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Đến ngày đi</label>
                        <input type="date" class="form-control form-control-sm" name="date_to" value="<?= $_GET['date_to'] ?? '' ?>">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="ph ph-magnifying-glass"></i> Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium border-0 shadow-sm overflow-hidden">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-fill ph-calendar-check text-primary"></i>
                <h6 class="fw-bold mb-0">Danh sách Chuyến đi</h6>
                <div class="d-flex align-items-center gap-2 ms-2">
                    <span class="badge bg-light text-muted border rounded-pill"><?= number_format($pagination['total']) ?> bản ghi</span>
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours/sync-departures" class="btn btn-xs btn-outline-info d-flex align-items-center gap-1 py-1 rounded-pill" title="Đồng bộ số chỗ thực tế">
                        <i class="ph ph-arrows-counter-clockwise"></i> Sync
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive bg-white">
            <style>
                .badge { font-weight: 500; }
                .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
                
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
            </style>
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-light bg-opacity-50">
                        <th class="ps-4 py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Thông tin Tour</th>
                        <th class="py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Khởi hành</th>
                        <th class="py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Trạng thái</th>
                        <th class="py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Lấp đầy</th>
                        <th class="text-end py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Doanh thu</th>
                        <th class="text-end pe-4 py-3 border-0" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (!empty($departures)): ?>
                        <?php foreach ($departures as $dep): ?>
                            <tr class="transition-all hover-bg-light">
                                <td class="ps-4 py-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark fs-6 mb-1"><?= htmlspecialchars($dep['tour_name']) ?></span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-muted border-0 fw-normal rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                <i class="ph ph-hash me-1"></i>CODE: <?= str_pad($dep['id'], 5, '0', STR_PAD_LEFT) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-inline-flex align-items-center gap-2 px-2 py-1 bg-primary-subtle bg-opacity-50 rounded-3 border border-primary border-opacity-10">
                                        <i class="ph-fill ph-calendar-blank text-primary" style="font-size: 1rem;"></i>
                                        <span class="fw-bold text-primary small"><?= date('d/m/Y', strtotime($dep['departure_date'])) ?></span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <?php 
                                    $statusMap = [
                                        'open' => ['class' => 'success', 'label' => 'Đang mở', 'icon' => 'ph-circle-wavy-check'],
                                        'guaranteed' => ['class' => 'primary', 'label' => 'Chắc chắn', 'icon' => 'ph-shield-check'],
                                        'closed' => ['class' => 'secondary', 'label' => 'Đã đóng', 'icon' => 'ph-lock'],
                                        'cancelled' => ['class' => 'danger', 'label' => 'Đã hủy', 'icon' => 'ph-x-circle']
                                    ];
                                    $s = $statusMap[$dep['status']] ?? ['class' => 'light', 'label' => strtoupper($dep['status']), 'icon' => 'ph-info'];
                                    ?>
                                    <div class="d-flex align-items-center gap-1 text-<?= $s['class'] ?> fw-bold small">
                                        <i class="ph-fill <?= $s['icon'] ?>"></i>
                                        <span class="text-uppercase" style="letter-spacing: 0.3px; font-size: 0.7rem;"><?= $s['label'] ?></span>
                                    </div>
                                </td>
                                <td class="py-3" style="min-width: 140px;">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark" style="font-size: 0.8rem;"><?= $dep['booked_seats'] ?> <span class="text-muted fw-normal">/ <?= $dep['max_seats'] ?></span></span>
                                            <?php $perc = ($dep['booked_seats'] / max(1, $dep['max_seats'])) * 100; ?>
                                            <span class="badge bg-<?= $perc > 90 ? 'danger' : ($perc > 60 ? 'warning' : 'success') ?>-subtle text-<?= $perc > 90 ? 'danger' : ($perc > 60 ? 'warning' : 'success') ?> rounded-pill" style="font-size: 0.65rem;"><?= round($perc) ?>%</span>
                                        </div>
                                        <div class="progress <?= $perc > 100 ? 'overbooked-pulse' : '' ?>" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                                            <div class="progress-bar rounded-pill shadow-none <?= $perc > 100 ? 'bg-danger' : ($perc > 90 ? 'bg-warning' : 'bg-success') ?> <?= $perc > 80 ? 'progress-bar-striped progress-bar-animated' : '' ?>" 
                                                 style="width: <?= min(100, $perc) ?>%"></div>
                                        </div>
                                        <?php if ($perc > 100): ?>
                                            <div class="d-flex align-items-center gap-1 mt-1 text-danger fw-bold" style="font-size: 0.65rem;">
                                                <i class="ph-fill ph-warning-octagon"></i> VƯỢT QUÁ GIỚI HẠN
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-end py-3">
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="fw-bold text-dark fs-6"><?= number_format($dep['revenue'] ?? 0, 0, ',', '.') ?> <small class="fw-medium">₫</small></span>
                                        <small class="text-muted" style="font-size: 0.65rem;">Tổng doanh thu</small>
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="dropdown">
                                        <button class="btn btn-modern btn-xs btn-light border dropdown-toggle px-3 py-2 shadow-sm d-flex align-items-center gap-2 ms-auto" type="button" data-bs-toggle="dropdown" style="border-radius: 10px;">
                                            <i class="ph-fill ph-gear"></i> Vận hành
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" style="border-radius: 16px; min-width: 220px;">
                                            <li class="px-2 py-1 text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Quản lý chuyến đi</li>
                                            <li><a class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center gap-3" href="<?= BASE_URL_ADMIN ?>&action=tours/departure-resources&id=<?= $dep['id'] ?>">
                                                <div class="icon-box bg-primary-subtle text-primary rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ph ph-truck"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold small">Tài nguyên & NCC</span>
                                                    <small class="text-muted extra-small">Gán nhà xe, HDV, NCC</small>
                                                </div>
                                            </a></li>
                                            <li><a class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center gap-3" href="<?= BASE_URL_ADMIN ?>&action=bookings&departure_id=<?= $dep['id'] ?>">
                                                <div class="icon-box bg-info-subtle text-info rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ph ph-users"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold small">Danh sách khách</span>
                                                    <small class="text-muted extra-small">Xem/In danh sách đoàn</small>
                                                </div>
                                            </a></li>
                                            <li><hr class="dropdown-divider mx-2"></li>
                                            <li><a class="dropdown-item py-2 px-3 rounded-3 d-flex align-items-center gap-3 text-danger" href="#">
                                                <div class="icon-box bg-danger-subtle text-danger rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ph ph-x-circle"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold small">Hủy chuyến</span>
                                                    <small class="text-muted extra-small">Dừng vận hành tour này</small>
                                                </div>
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">
                            <i class="ph ph-magnifying-glass fs-1 d-block mb-3 opacity-25"></i>
                            Không tìm thấy lịch khởi hành phù hợp.
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="px-4 py-3 border-top bg-light-subtle d-flex justify-content-between align-items-center">
                <div class="text-muted small">Trang <strong><?= $pagination['page'] ?></strong> / <strong><?= $pagination['total_pages'] ?></strong></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0 gap-1">
                        <?php 
                        $query = $_GET;
                        for ($i = 1; $i <= $pagination['total_pages']; $i++): 
                            if ($i > 5 && $i < $pagination['total_pages']) { if ($i == 6) echo '<li class="page-item disabled"><span class="page-link border-0">...</span></li>'; continue; }
                            $query['page'] = $i;
                        ?>
                            <li class="page-item <?= ($i == $pagination['page']) ? 'active' : '' ?>">
                                <a class="page-link border-0 shadow-sm rounded-2 text-center" style="width: 32px;" 
                                   href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
