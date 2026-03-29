<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Nhà Cung Cấp</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=suppliers/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Nhà Cung Cấp Mới
            </a>
        </div>
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Nhà Cung Cấp</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['total'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-handshake"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đánh Giá Tốt</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['active'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                    <i class="ph ph-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đối Tác Ưu Tiên</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['high_rated'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                    <i class="ph ph-star"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đánh Giá TB</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['avg_rating'] ?? 0, 1) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                    <i class="ph ph-chart-line-up"></i>
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
            <form id="supplier-filters" method="GET" action="<?= BASE_URL_ADMIN . '&action=suppliers' ?>">
                <input type="hidden" name="action" value="suppliers">
                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Tìm kiếm</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" name="keyword"
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
                                placeholder="Tên, liên hệ, SĐT, email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Loại</label>
                        <select class="form-select form-select-sm" name="type">
                            <option value="">Tất cả</option>
                            <?php foreach ($types ?? [] as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>"
                                    <?= (($_GET['type'] ?? '') == $t) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(ucfirst($t)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Đánh giá</label>
                        <select class="form-select form-select-sm" name="rating_min">
                            <option value="">Tất cả</option>
                            <option value="1" <?= (($_GET['rating_min'] ?? '') == '1') ? 'selected' : '' ?>>≥ 1 sao</option>
                            <option value="2" <?= (($_GET['rating_min'] ?? '') == '2') ? 'selected' : '' ?>>≥ 2 sao</option>
                            <option value="3" <?= (($_GET['rating_min'] ?? '') == '3') ? 'selected' : '' ?>>≥ 3 sao</option>
                            <option value="4" <?= (($_GET['rating_min'] ?? '') == '4') ? 'selected' : '' ?>>≥ 4 sao</option>
                            <option value="5" <?= (($_GET['rating_min'] ?? '') == '5') ? 'selected' : '' ?>>5 sao</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex align-items-end gap-2">
                        <button type="button" onclick="filterSuppliers()" class="btn btn-primary btn-sm flex-grow-1" style="height: 31px;">Lọc kết quả</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-fill ph-list-bullets text-primary"></i>
                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Danh sách Nhà Cung Cấp</h6>
                <span class="badge bg-light text-muted border ms-2 rounded-pill count-info" style="font-size: 0.7rem;"><?= count($suppliers) ?> kết quả</span>
            </div>
        </div>
        <div class="table-responsive bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($suppliers)) : ?>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="20%">Tên Nhà Cung Cấp</th>
                            <th width="15%">Loại</th>
                            <th width="20%">Liên Hệ</th>
                            <th width="15%">Thông Tin</th>
                            <th width="15%">Đánh Giá</th>
                            <th width="10%" class="text-end">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $index => $supplier) : ?>
                            <tr>
                                <td class="text-center fw-medium text-muted"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($supplier['name']) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="ph ph-tag me-1"></i><?= htmlspecialchars(ucfirst($supplier['type'] ?? '-')) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark small fw-medium"><?= htmlspecialchars($supplier['contact_person'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <?php if (!empty($supplier['phone'])): ?>
                                            <div><i class="ph ph-phone me-1"></i><?= htmlspecialchars($supplier['phone']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($supplier['email'])): ?>
                                            <div><i class="ph ph-envelope me-1"></i><?= htmlspecialchars($supplier['email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($supplier['rating'])): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="text-warning d-flex">
                                                <?php
                                                $rating = $supplier['rating'];
                                                $fullStars = floor($rating);
                                                for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="ph-fill ph-star fs-6 <?= $i <= $fullStars ? '' : 'text-muted opacity-25' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="fw-bold rating-value" style="font-size: 0.85rem;"><?= number_format($rating, 1) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= BASE_URL_ADMIN . '&action=suppliers/detail&id=' . $supplier['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Xem">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL_ADMIN . '&action=suppliers/edit&id=' . $supplier['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa">
                                            <i class="ph ph-pencil-simple"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm" onclick="showDeleteModal(<?= $supplier['id'] ?>, '<?= htmlspecialchars($supplier['name']) ?>')" title="Xóa">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="text-center p-5">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="ph-fill ph-handshake text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có nhà cung cấp nào</h5>
                    <p class="text-muted">Bắt đầu thêm nhà cung cấp đầu tiên của bạn.</p>
                    <a href="<?= BASE_URL_ADMIN . '&action=suppliers/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                        <i class="ph ph-plus me-1"></i> Thêm Nhà Cung Cấp Mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modern Delete Modal -->
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
                <h5 class="fw-bold mb-3">Xóa Nhà Cung Cấp?</h5>
                <p class="text-muted mb-4">Bạn có chắc muốn xóa "<strong id="delete-supplier-name" class="text-dark"></strong>"?<br>Lưu ý: Các dữ liệu liên quan có thể bị ảnh hưởng.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="delete-form" method="POST" action="<?= BASE_URL_ADMIN ?>&action=suppliers/delete" class="m-0">
                        <input type="hidden" name="id" id="delete-supplier-id">
                        <button type="submit" class="btn btn-danger px-4">Xác nhận xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterSuppliers() {
        const keyword = document.querySelector('[name="keyword"]').value.toLowerCase();
        const type = document.querySelector('[name="type"]').value.toLowerCase();
        const ratingMin = parseFloat(document.querySelector('[name="rating_min"]').value) || 0;

        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        let visibleCount = 0;

        rows.forEach(row => {
            const supplierName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const supplierType = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const contactInfo = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const phoneEmail = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const ratingElement = row.querySelector('.rating-value');
            const supplierRating = ratingElement ? parseFloat(ratingElement.textContent) : 0;

            let show = true;
            if (keyword && !supplierName.includes(keyword) && !contactInfo.includes(keyword) && !phoneEmail.includes(keyword)) show = false;
            if (type && !supplierType.includes(type)) show = false;
            if (ratingMin > 0 && supplierRating < ratingMin) show = false;

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        const countElement = document.querySelector('.count-info');
        if (countElement) countElement.textContent = visibleCount + ' kết quả';
    }

    function resetFilters() {
        document.getElementById('supplier-filters').reset();
        filterSuppliers();
    }

    function showDeleteModal(id, name) {
        document.getElementById('delete-supplier-id').value = id;
        document.getElementById('delete-supplier-name').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>