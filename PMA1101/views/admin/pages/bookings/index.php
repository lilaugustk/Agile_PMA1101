<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$userRole = $_SESSION['user']['role'] ?? 'customer';
$isAdmin = $userRole === 'admin';
$isGuide = $userRole === 'guide';
?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Booking</li>
                </ol>
            </nav>
        </div>
        <?php if ($isAdmin): ?>
            <div>
                <a href="<?= BASE_URL_ADMIN . '&action=bookings/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                    <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Tạo Booking Mới
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-success-subtle text-success border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-check-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-warning-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Booking</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['total'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-ticket"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Chờ Thanh Toán</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['soft_pending'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                    <i class="ph ph-hourglass-medium"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đã Cọc</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['deposited'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                    <i class="ph ph-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Hoàn Tất</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['completed'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                    <i class="ph ph-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card-premium mb-3">
        <div class="p-2 px-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                <i class="ph ph-funnel text-muted"></i> Bộ Lọc Tìm Kiếm
            </h6>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="resetFilters()" style="font-size: 0.75rem;">
                    <i class="ph ph-arrow-counter-clockwise"></i> Reset
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="toggleAdvancedFilters()" style="font-size: 0.75rem;">
                    <i class="ph ph-sliders"></i> Nâng Cao
                </button>
            </div>
        </div>

        <div class="p-2 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form id="booking-filters" method="GET" action="<?= BASE_URL_ADMIN ?>">
                <input type="hidden" name="mode" value="admin">
                <input type="hidden" name="action" value="bookings">

                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Tìm kiếm</label>
                        <div class="position-relative">
                            <i class="ph ph-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem;"></i>
                            <input type="text" class="form-control form-control-sm ps-4 border-light-subtle shadow-sm" name="keyword" 
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" 
                                placeholder="Mã BK, tên KH, tour..." style="border-radius: 8px; min-height: 38px;">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Loại Tour</label>
                        <select class="form-select form-select-sm border-light-subtle shadow-sm" name="category_id" style="border-radius: 8px; min-height: 38px;">
                            <option value="">Tất cả</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (($_GET['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Trạng thái</label>
                        <select class="form-select form-select-sm border-light-subtle shadow-sm" name="status" style="border-radius: 8px; min-height: 38px;">
                            <option value="">Tất cả</option>
                            <option value="pending" <?= (($_GET['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Chờ Thanh Toán</option>
                            <option value="cho_xac_nhan" <?= (($_GET['status'] ?? '') == 'cho_xac_nhan') ? 'selected' : '' ?>>Chờ Xác Nhận</option>
                            <option value="da_coc" <?= (($_GET['status'] ?? '') == 'da_coc') ? 'selected' : '' ?>>Đã Cọc</option>
                            <option value="hoan_tat" <?= (($_GET['status'] ?? '') == 'hoan_tat') ? 'selected' : '' ?>>Hoàn Tất</option>
                            <option value="da_huy" <?= (($_GET['status'] ?? '') == 'da_huy') ? 'selected' : '' ?>>Đã Hủy</option>
                            <option value="expired" <?= (($_GET['status'] ?? '') == 'expired') ? 'selected' : '' ?>>Hết Hạn</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; height: 38px;">
                            <i class="ph-fill ph-magnifying-glass"></i> Tìm kiếm
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters (Hidden by default) -->
                <div class="row g-3 mt-1 advanced-filters" style="display: <?= (!empty($_GET['price_min']) || !empty($_GET['price_max']) || !empty($_GET['date_from']) || !empty($_GET['date_to'])) ? 'flex' : 'none' ?>;">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá từ (VNĐ)</label>
                        <input type="number" class="form-control form-control-sm border-light-subtle shadow-sm" name="price_min"
                            value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>" placeholder="0" style="border-radius: 8px; min-height: 38px;">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá đến (VNĐ)</label>
                        <input type="number" class="form-control form-control-sm border-light-subtle shadow-sm" name="price_max"
                            value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>" placeholder="Không giới hạn" style="border-radius: 8px; min-height: 38px;">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Từ ngày</label>
                        <input type="date" class="form-control form-control-sm border-light-subtle shadow-sm" name="date_from"
                            value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" style="border-radius: 8px; min-height: 38px;" />
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Đến ngày</label>
                        <input type="date" class="form-control form-control-sm border-light-subtle shadow-sm" name="date_to"
                            value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" style="border-radius: 8px; min-height: 38px;" />
                    </div>
                </div>

                <div class="row g-3 mt-1 align-items-end">
                    <div class="col-12 col-lg-12 d-flex gap-3">
                        <div class="flex-grow-1">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Sắp xếp theo</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="sort_by" style="border-radius: 8px; min-height: 38px;">
                                <option value="booking_date" <?= (($_GET['sort_by'] ?? '') == 'booking_date') ? 'selected' : '' ?>>Ngày đặt</option>
                                <option value="total_price" <?= (($_GET['sort_by'] ?? '') == 'total_price') ? 'selected' : '' ?>>Giá trị đơn hàng</option>
                                <option value="customer" <?= (($_GET['sort_by'] ?? '') == 'customer') ? 'selected' : '' ?>>Tên khách hàng</option>
                                <option value="tour" <?= (($_GET['sort_by'] ?? '') == 'tour') ? 'selected' : '' ?>>Tên tour</option>
                            </select>
                        </div>
                        <div style="width: 130px;">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Thứ tự</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="sort_dir" style="border-radius: 8px; min-height: 38px;">
                                <option value="DESC" <?= (($_GET['sort_dir'] ?? '') == 'DESC') ? 'selected' : '' ?>>Giảm dần</option>
                                <option value="ASC" <?= (($_GET['sort_dir'] ?? '') == 'ASC') ? 'selected' : '' ?>>Tăng dần</option>
                            </select>
                        </div>
                        <div style="width: 100px;">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Hiển thị</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="per_page" style="border-radius: 8px; min-height: 38px;">
                                <option value="15" <?= (($_GET['per_page'] ?? '') == '15') ? 'selected' : '' ?>>15</option>
                                <option value="30" <?= (($_GET['per_page'] ?? '') == '30') ? 'selected' : '' ?>>30</option>
                                <option value="50" <?= (($_GET['per_page'] ?? '') == '50') ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= (($_GET['per_page'] ?? '') == '100') ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-fill ph-ticket text-primary"></i>
                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Danh sách Booking</h6>
                <span class="badge bg-light text-muted border ms-2 rounded-pill" style="font-size: 0.7rem;"><?= number_format($pagination['total']) ?> kết quả</span>
            </div>
            
        </div>
        <div class="table-responsive bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($bookings)) : ?>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="22%">Khách hàng</th>
                            <th width="22%">Tour</th>
                            <th width="11%">Ngày đặt</th>
                            <th width="17%">Tổng tiền</th>
                            <th width="13%">Trạng thái</th>
                            <th width="10%" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stt = ($pagination['page'] - 1) * $pagination['per_page'] + 1; 
                        foreach ($bookings as $booking) : 
                        ?>
                            <tr>
                                <td class="text-center fw-medium text-muted"><?= $stt++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= mb_substr(trim($booking['customer_name'] ?: ($booking['contact_name'] ?: 'X')), 0, 1) ?>
                                        </div>
                                        <div class="d-flex flex-column overflow-hidden">
                                            <span class="fw-medium text-dark text-nowrap"><?= htmlspecialchars($booking['customer_name'] ?: ($booking['contact_name'] ?: 'Khách vãng lai')) ?></span>
                                            <?php if (empty($booking['customer_id'])): ?><span class="extra-small text-muted mb-0 text-nowrap">Guest Booking</span><?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ph-fill ph-map-pin text-muted"></i>
                                        <span class="fw-medium text-truncate" style="max-width: 200px; display: inline-block;" title="<?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?>">
                                            <?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 0.9rem;"><i class="ph ph-calendar-blank me-1"></i><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-nowrap" style="color: var(--text-main);"><?= number_format($booking['final_price'] ?? 0, 0, ',', '.') ?> ₫</span>
                                </td>
                                <td>
                                    <?php
                                    $statusMap = [
                                        'pending' => ['text' => 'Chờ Thanh Toán', 'class' => 'warning'],
                                        'cho_xac_nhan' => ['text' => 'Chờ Xác Nhận', 'class' => 'warning'],
                                        'da_coc' => ['text' => 'Đã Cọc', 'class' => 'info'],
                                        'confirmed' => ['text' => 'Đã Xác Nhận', 'class' => 'info'],
                                        'hoan_tat' => ['text' => 'Hoàn Tất', 'class' => 'success'],
                                        'completed' => ['text' => 'Hoàn Tất', 'class' => 'success'],
                                        'paid' => ['text' => 'Đã Thanh Toán', 'class' => 'success'],
                                        'da_huy' => ['text' => 'Đã Hủy', 'class' => 'danger'],
                                        'cancelled' => ['text' => 'Đã Hủy', 'class' => 'danger'],
                                        'expired' => ['text' => 'Hết Hạn', 'class' => 'secondary']
                                    ];
                                    $curr = $statusMap[$booking['status']] ?? ['text' => 'Không Rõ', 'class' => 'secondary'];
                                    ?>
                                    <span class="badge badge-soft bg-<?= $curr['class'] ?>-subtle text-<?= $curr['class'] ?>">
                                        <?= $curr['text'] ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= BASE_URL_ADMIN . '&action=bookings/detail&id=' . $booking['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Chi tiết">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL_ADMIN . '&action=bookings/invoice&id=' . $booking['id'] ?>" target="_blank" class="btn btn-sm bg-white text-info border shadow-sm" title="In Hóa Đơn">
                                            <i class="ph ph-printer"></i>
                                        </a>
                                        <?php if ($isAdmin): ?>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bookings/edit&id=' . $booking['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm delete-booking" data-id="<?= $booking['id'] ?>" data-name="<?= htmlspecialchars($booking['customer_name'] ?: ($booking['contact_name'] ?: 'Khách hàng')) ?>" title="Xóa">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="text-center p-5">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="ph-fill ph-calendar-x text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có booking nào</h5>
                    <p class="text-muted">Bắt đầu tạo booking đầu tiên cho khách hàng của bạn.</p>
                    <a href="<?= BASE_URL_ADMIN . '&action=bookings/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                        <i class="ph ph-plus me-1"></i> Tạo Booking Mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="px-4 py-3 border-top bg-light-subtle d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Hiển thị từ <strong><?= ($pagination['page'] - 1) * $pagination['per_page'] + 1 ?></strong> 
                    đến <strong><?= min($pagination['page'] * $pagination['per_page'], $pagination['total']) ?></strong> 
                    trong tổng số <strong><?= number_format($pagination['total']) ?></strong> kết quả
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0 gap-1">
                        <?php 
                        $query = $_GET;
                        ?>
                        
                        <!-- Previous Page -->
                        <li class="page-item <?= ($pagination['page'] <= 1) ? 'disabled' : '' ?>">
                            <?php $query['page'] = $pagination['page'] - 1; ?>
                            <a class="page-link border-0 shadow-sm rounded-2 d-flex align-items-center justify-content-center" 
                               href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>" style="width: 32px; height: 32px;">
                                <i class="ph ph-caret-left"></i>
                            </a>
                        </li>

                        <?php 
                        $start = max(1, $pagination['page'] - 2);
                        $end = min($pagination['total_pages'], $pagination['page'] + 2);
                        
                        if ($start > 1): ?>
                            <li class="page-item">
                                <?php $query['page'] = 1; ?>
                                <a class="page-link border-0 shadow-sm rounded-2 d-flex align-items-center justify-content-center" 
                                   href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>" style="width: 32px; height: 32px;">1</a>
                            </li>
                            <?php if ($start > 2): ?>
                                <li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= ($i == $pagination['page']) ? 'active' : '' ?>">
                                <?php $query['page'] = $i; ?>
                                <a class="page-link border-0 shadow-sm rounded-2 d-flex align-items-center justify-content-center" 
                                   href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>" 
                                   style="width: 32px; height: 32px; <?= ($i == $pagination['page']) ? 'background: var(--primary-color) !important; color: white !important;' : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($end < $pagination['total_pages']): ?>
                            <?php if ($end < $pagination['total_pages'] - 1): ?>
                                <li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>
                            <?php endif; ?>
                            <li class="page-item">
                                <?php $query['page'] = $pagination['total_pages']; ?>
                                <a class="page-link border-0 shadow-sm rounded-2 d-flex align-items-center justify-content-center" 
                                   href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>" style="width: 32px; height: 32px;"><?= $pagination['total_pages'] ?></a>
                            </li>
                        <?php endif; ?>

                        <!-- Next Page -->
                        <li class="page-item <?= ($pagination['page'] >= $pagination['total_pages']) ? 'disabled' : '' ?>">
                            <?php $query['page'] = $pagination['page'] + 1; ?>
                            <a class="page-link border-0 shadow-sm rounded-2 d-flex align-items-center justify-content-center" 
                               href="<?= BASE_URL_ADMIN . '&' . http_build_query($query) ?>" style="width: 32px; height: 32px;">
                                <i class="ph ph-caret-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-5 px-4">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger" style="width: 72px; height: 72px;">
                    <i class="ph ph-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-3">Xác nhận xóa booking</h5>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn xóa booking của "<strong id="delete-booking-name" class="text-dark"></strong>"?<br> Hành động này tĩnh không thể hoàn tác.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="delete-form" method="POST" class="m-0">
                        <input type="hidden" name="id" id="delete-booking-id">
                        <button type="submit" class="btn btn-danger px-4">Xóa Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete booking functionality
        document.querySelectorAll('.delete-booking').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.dataset.id;
                const bookingName = this.dataset.name;

                document.getElementById('delete-booking-id').value = bookingId;
                document.getElementById('delete-booking-name').textContent = bookingName;

                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
        });

        // Handle delete form submission
        document.getElementById('delete-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const bookingId = document.getElementById('delete-booking-id').value;
            window.location.href = '<?= BASE_URL_ADMIN ?>&action=bookings/delete&id=' + bookingId;
        });
    });

    function resetFilters() {
        window.location.href = '<?= BASE_URL_ADMIN ?>&action=bookings';
    }

    function toggleAdvancedFilters() {
        const advancedSection = document.querySelector('.advanced-filters');
        if (advancedSection.style.display === 'none') {
            advancedSection.style.display = 'flex';
        } else {
            advancedSection.style.display = 'none';
        }
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>