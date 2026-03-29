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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Chờ Xác Nhận</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['pending'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                    <i class="ph ph-clock-countdown"></i>
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
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;"><i class="ph ph-funnel text-muted"></i> Bộ Lọc Tìm Kiếm</h6>
            <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="resetFilters()" style="font-size: 0.75rem;">
                <i class="ph ph-arrow-counter-clockwise"></i> Reset
            </button>
        </div>
        <div class="p-2 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form id="booking-filters" method="GET" action="<?= BASE_URL_ADMIN . '&action=bookings' ?>">
                <input type="hidden" name="action" value="bookings">
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Từ khóa</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" name="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" placeholder="Mã BK, tên KH, tour...">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Trạng thái</label>
                        <select class="form-select form-select-sm" name="status">
                            <option value="">Tất cả</option>
                            <option value="cho_xac_nhan" <?= (($_GET['status'] ?? '') == 'cho_xac_nhan') ? 'selected' : '' ?>>Chờ Xác Nhận</option>
                            <option value="da_coc" <?= (($_GET['status'] ?? '') == 'da_coc') ? 'selected' : '' ?>>Đã Cọc</option>
                            <option value="hoan_tat" <?= (($_GET['status'] ?? '') == 'hoan_tat') ? 'selected' : '' ?>>Hoàn Tất</option>
                            <option value="da_huy" <?= (($_GET['status'] ?? '') == 'da_huy') ? 'selected' : '' ?>>Đã Hủy</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Từ ngày</label>
                        <input type="date" class="form-control form-control-sm" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Đến ngày</label>
                        <input type="date" class="form-control form-control-sm" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                        <div class="flex-grow-1">
                            <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Sắp xếp</label>
                            <select class="form-select form-select-sm" name="sort_by">
                                <option value="booking_date">Ngày đặt</option>
                                <option value="total_price">Tổng tiền</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm px-3" style="height: 31px;">Lọc</button>
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
                <span class="badge bg-light text-muted border ms-2 rounded-pill" style="font-size: 0.7rem;"><?= count($bookings) ?> kết quả</span>
            </div>
            
        </div>
        <div class="table-responsive bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($bookings)) : ?>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="20%">Khách hàng</th>
                            <th width="25%">Tour</th>
                            <th width="12%">Ngày đặt</th>
                            <th width="15%">Tổng tiền</th>
                            <th width="13%">Trạng thái</th>
                            <th width="10%" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; foreach ($bookings as $booking) : ?>
                            <tr>
                                <td class="text-center fw-medium text-muted"><?= $stt++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= mb_substr(trim($booking['customer_name'] ?? 'A'), 0, 1) ?>
                                        </div>
                                        <span class="fw-medium text-dark"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></span>
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
                                    <span class="fw-bold" style="color: var(--text-main);"><?= number_format($booking['final_price'] ?? 0, 0, ',', '.') ?> ₫</span>
                                </td>
                                <td>
                                    <?php
                                    $statusText = 'Chờ Xác Nhận';
                                    $statusClass = 'warning';
                                    if ($booking['status'] === 'hoan_tat') {
                                        $statusText = 'Hoàn Tất';
                                        $statusClass = 'success';
                                    } elseif ($booking['status'] === 'da_coc') {
                                        $statusText = 'Đã Cọc';
                                        $statusClass = 'info';
                                    } elseif ($booking['status'] === 'da_huy') {
                                        $statusText = 'Đã Hủy';
                                        $statusClass = 'danger';
                                    }
                                    ?>
                                    <span class="badge badge-soft bg-<?= $statusClass ?>-subtle text-<?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= BASE_URL_ADMIN . '&action=bookings/detail&id=' . $booking['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Chi tiết">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <?php if ($isAdmin): ?>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bookings/edit&id=' . $booking['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm delete-booking" data-id="<?= $booking['id'] ?>" data-name="<?= htmlspecialchars($booking['customer_name']) ?>" title="Xóa">
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
        document.getElementById('booking-filters').reset();
        document.getElementById('booking-filters').submit();
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>