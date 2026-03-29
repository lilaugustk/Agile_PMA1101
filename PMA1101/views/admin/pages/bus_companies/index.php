<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Nhà Xe</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=bus-companies/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Nhà Xe Mới
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Nhà Xe</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['total'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-bus"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đang Hoạt Động</p>
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Số Xe</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['total_vehicles'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                    <i class="ph ph-truck"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đánh Giá TB</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['avg_rating'] ?? 5, 1) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                    <i class="ph ph-star"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-3">
        <div class="p-2 px-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                <i class="ph ph-funnel text-muted"></i> Bộ Lọc Tìm Kiếm
            </h6>
            <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="resetFilters()" style="font-size: 0.75rem;">
                <i class="ph ph-arrow-counter-clockwise"></i> Reset
            </button>
        </div>
        <div class="p-2 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form id="bus-company-filters" onsubmit="return false;">
                <div class="row g-2">
                    <div class="col-12 col-md-5">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Tìm kiếm</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="keyword" placeholder="Tên nhà xe, mã, SĐT, email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Đánh giá tối thiểu</label>
                        <select class="form-select form-select-sm" id="rating_min">
                            <option value="">Tất cả</option>
                            <option value="4.5">4.5+ ⭐</option>
                            <option value="4.0">4.0+ ⭐</option>
                            <option value="3.5">3.5+ ⭐</option>
                            <option value="3.0">3.0+ ⭐</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="filterBusCompanies()" style="height: 31px;">
                            <i class="ph ph-magnifying-glass me-1"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bus Companies List -->
    <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
        <div class="p-3 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                <i class="ph-fill ph-list-bullets text-primary"></i> Danh sách Nhà Xe
            </h6>
            <div class="count-info badge bg-light text-muted border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                <?= count($busCompanies) ?> nhà xe
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (!empty($busCompanies)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 0.75rem; width: 60px;">ID</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.75rem;">MÃ NX</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.75rem;">TÊN NHÀ XE</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.75rem;">LIÊN HỆ</th>
                                <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">SỐ XE</th>
                                <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">TRẠNG THÁI</th>
                                <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">ĐÁNH GIÁ</th>
                                <th class="pe-4 py-3 text-muted fw-bold text-end" style="font-size: 0.75rem;">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($busCompanies as $index => $company): ?>
                                <tr class="bus-company-row">
                                    <td class="ps-4 fw-bold text-muted">#<?= $index + 1 ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border-0"><?= htmlspecialchars($company['company_code']) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 32px; height: 32px;">
                                                <i class="ph ph-building fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($company['company_name']) ?></div>
                                                <?php if (!empty($company['contact_person'])): ?>
                                                    <div class="small text-muted"><?= htmlspecialchars($company['contact_person']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php if (!empty($company['phone'])): ?>
                                                <div class="d-flex align-items-center gap-1 text-dark fw-medium">
                                                    <i class="ph ph-phone text-success"></i> <?= htmlspecialchars($company['phone']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($company['email'])): ?>
                                                <div class="text-muted d-flex align-items-center gap-1">
                                                    <i class="ph ph-envelope"></i> <?= htmlspecialchars($company['email']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-info-subtle text-info" style="font-size: 0.7rem;">
                                            <?= number_format($company['total_vehicles'] ?? 0) ?> xe
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $statusConfig = [
                                            'active' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'label' => 'Hoạt động', 'icon' => 'ph-check-circle'],
                                            'inactive' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'label' => 'Ngừng', 'icon' => 'ph-pause-circle']
                                        ];
                                        $config = $statusConfig[$company['status'] ?? 'active'] ?? $statusConfig['inactive'];
                                        ?>
                                        <span class="badge rounded-pill <?= $config['bg'] ?> <?= $config['text'] ?> px-2 py-1" style="font-size: 0.7rem;">
                                            <i class="ph-fill <?= $config['icon'] ?> me-1" style="font-size: 0.5rem;"></i>
                                            <?= $config['label'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <i class="ph-fill ph-star text-warning" style="font-size: 0.85rem;"></i>
                                            <span class="fw-bold text-dark small"><?= number_format($company['rating'] ?? 5, 1) ?></span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= BASE_URL_ADMIN . '&action=bus-companies/detail&id=' . $company['id'] ?>" class="btn btn-sm bg-white text-info border shadow-sm" title="Xem"><i class="ph ph-eye"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=bus-companies/edit&id=' . $company['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Sửa"><i class="ph ph-pencil-simple"></i></a>
                                            <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm" onclick="deleteBusCompany(<?= $company['id'] ?>, '<?= htmlspecialchars($company['company_name']) ?>')" title="Xóa"><i class="ph ph-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center p-5">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="ph ph-bus text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có nhà xe nào</h5>
                    <p class="text-muted">Bắt đầu bằng cách thêm nhà xe đầu tiên vào hệ thống.</p>
                    <a href="<?= BASE_URL_ADMIN . '&action=bus-companies/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                        <i class="ph ph-plus-circle me-1"></i> Thêm Nhà Xe Mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Form xóa nhà xe -->
<form id="deleteForm" method="POST" action="<?= BASE_URL_ADMIN ?>&action=bus-companies/delete" style="display: none;">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Client-side filtering
    function filterBusCompanies() {
        const keyword = document.getElementById('keyword').value.toLowerCase();
        const ratingMin = parseFloat(document.getElementById('rating_min').value) || 0;
        const rows = document.querySelectorAll('.bus-company-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const ratingElement = row.querySelector('.rating-value');
            const rating = ratingElement ? parseFloat(ratingElement.textContent) : 0;

            const matchesKeyword = !keyword || text.includes(keyword);
            const matchesRating = rating >= ratingMin;

            if (matchesKeyword && matchesRating) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.querySelector('.count-info').textContent = visibleCount + ' nhà xe';
    }

    function resetFilters() {
        document.getElementById('keyword').value = '';
        document.getElementById('rating_min').value = '';
        filterBusCompanies();
    }

    // Enter key support
    document.getElementById('keyword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBusCompanies();
        }
    });

    function deleteBusCompany(id, name) {
        if (confirm('Bạn có chắc muốn xóa nhà xe "' + name + '"?\nLưu ý: Các booking và tour assignment liên quan sẽ bị ảnh hưởng.')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>