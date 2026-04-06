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
                <span class="badge bg-light text-muted border ms-2 rounded-pill"><?= number_format($pagination['total']) ?> bản ghi</span>
            </div>
        </div>
        <div class="table-responsive bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Thông tin Tour</th>
                        <th class="py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Khởi hành</th>
                        <th class="py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Trạng thái</th>
                        <th class="text-center py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Lấp đầy</th>
                        <th class="text-end py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Doanh thu</th>
                        <th class="text-end pe-4 py-3" style="font-size: 0.75rem; text-transform: uppercase; color: #64748b;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($departures)): ?>
                        <?php foreach ($departures as $dep): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark mb-1"><?= htmlspecialchars($dep['tour_name']) ?></div>
                                    <div class="d-flex align-items-center gap-2 small text-muted">
                                        <i class="ph ph-hash"></i> CODE: <?= str_pad($dep['id'], 5, '0', STR_PAD_LEFT) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary p-2 rounded-2">
                                            <i class="ph ph-calendar-blank"></i>
                                        </div>
                                        <div class="fw-medium"><?= date('d/m/Y', strtotime($dep['departure_date'])) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $statusMap = [
                                        'open' => ['class' => 'success', 'label' => 'ĐANG MỞ'],
                                        'guaranteed' => ['class' => 'primary', 'label' => 'CHẮC CHẮN'],
                                        'closed' => ['class' => 'secondary', 'label' => 'ĐÃ ĐÓNG'],
                                        'cancelled' => ['class' => 'danger', 'label' => 'ĐÃ HỦY']
                                    ];
                                    $s = $statusMap[$dep['status']] ?? ['class' => 'light', 'label' => strtoupper($dep['status'])];
                                    ?>
                                    <span class="badge bg-<?= $s['class'] ?>-subtle text-<?= $s['class'] ?> fw-bold" style="font-size: 0.65rem; padding: 0.4rem 0.6rem;">
                                        <?= $s['label'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <div class="small fw-bold text-dark"><?= $dep['booked_seats'] ?> / <?= $dep['max_seats'] ?></div>
                                        <?php $perc = ($dep['booked_seats'] / max(1, $dep['max_seats'])) * 100; ?>
                                        <div class="progress w-100" style="height: 6px; max-width: 80px; border-radius: 10px;">
                                            <div class="progress-bar bg-<?= $perc > 80 ? 'danger' : ($perc > 50 ? 'primary' : 'success') ?>" 
                                                 style="width: <?= min(100, $perc) ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    <?= number_format($dep['revenue'] ?? 0, 0, ',', '.') ?> ₫
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Vận hành
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; font-size: 0.85rem;">
                                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="<?= BASE_URL_ADMIN ?>&action=tours/departure-resources&id=<?= $dep['id'] ?>">
                                                <i class="ph ph-truck text-primary"></i> Gán Tài nguyên & NCC
                                            </a></li>
                                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="<?= BASE_URL_ADMIN ?>&action=bookings&departure_id=<?= $dep['id'] ?>">
                                                <i class="ph ph-users text-info"></i> Danh sách hành khách
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-danger" href="#">
                                                <i class="ph ph-x-circle"></i> Hủy chuyến
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy lịch khởi hành phù hợp.</td></tr>
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
