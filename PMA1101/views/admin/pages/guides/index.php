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
                    <li class="breadcrumb-item active" aria-current="page">Quản lý HDV</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=guides/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm HDV Mới
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
        <section class="stats-section">
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                        <div>
                            <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng HDV</p>
                            <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(count($guides)) ?></h3>
                        </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                            <i class="ph ph-user-tie"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                        <div>
                            <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đang Hoạt Động</p>
                            <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                                <?php
                                $activeCount = 0;
                                foreach ($guides as $g) {
                                    if (($g['status'] ?? 'active') === 'active') $activeCount++;
                                }
                                echo number_format($activeCount);
                                ?>
                            </h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                            <i class="ph ph-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                        <div>
                            <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Kinh Nghiệm TB (năm)</p>
                            <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                                <?php
                                $totalExp = 0;
                                foreach ($guides as $g) {
                                    $totalExp += ($g['experience_years'] ?? 0);
                                }
                                $avgExp = count($guides) > 0 ? $totalExp / count($guides) : 0;
                                echo number_format($avgExp, 1);
                                ?>
                            </h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                            <i class="ph ph-briefcase"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                        <div>
                            <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đánh Giá TB</p>
                            <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                                <?php
                                $totalRating = 0;
                                foreach ($guides as $g) {
                                    $totalRating += ($g['rating'] ?? 5);
                                }
                                $avgRating = count($guides) > 0 ? $totalRating / count($guides) : 5;
                                echo number_format($avgRating, 1);
                                ?>
                            </h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                            <i class="ph ph-star"></i>
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
                <form id="guide-filters" method="GET" action="<?= BASE_URL_ADMIN . '&action=guides' ?>">
                    <input type="hidden" name="action" value="guides">
                    <div class="row g-2">
                        <div class="col-12 col-md-5">
                            <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Tìm kiếm</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" name="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" placeholder="Tên, email, SĐT...">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Đánh giá tối thiểu</label>
                            <select class="form-select form-select-sm" name="rating_min">
                                <option value="">Tất cả</option>
                                <option value="4" <?= (($_GET['rating_min'] ?? '') == '4') ? 'selected' : '' ?>>≥ 4 sao</option>
                                <option value="3" <?= (($_GET['rating_min'] ?? '') == '3') ? 'selected' : '' ?>>≥ 3 sao</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100" style="height: 31px;">
                                <i class="ph ph-magnifying-glass me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guides List -->
        <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
            <div class="p-3 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="ph-fill ph-list-bullets text-primary"></i> Danh sách Hướng Dẫn Viên
                </h6>
                <div class="count-info badge bg-light text-muted border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                    <?= count($guides) ?> HDV
                </div>
            </div>

            <div class="card-body p-0">
                <?php if (!empty($guides)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 0.75rem;">ẢNH</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.75rem;">HỌ VÀ TÊN</th>
                                    <th class="py-3 text-muted fw-bold" style="font-size: 0.75rem;">LIÊN HỆ</th>
                                    <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">NGÔN NGỮ</th>
                                    <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">KINH NGHIỆM</th>
                                    <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.75rem;">ĐÁNH GIÁ</th>
                                    <th class="pe-4 py-3 text-muted fw-bold text-end" style="font-size: 0.75rem;">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($guides as $guide): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <?php if (!empty($guide['avatar'])): ?>
                                                <img src="<?= htmlspecialchars($guide['avatar']) ?>" alt="Avatar" class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #fff;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; border: 2px solid #fff;">
                                                    <i class="ph ph-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($guide['full_name'] ?? 'N/A') ?></div>
                                            <div class="small text-muted">ID: #<?= str_pad($guide['id'], 3, '0', STR_PAD_LEFT) ?></div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="d-flex align-items-center gap-1 text-dark fw-medium">
                                                    <i class="ph ph-envelope text-primary"></i> <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
                                                </div>
                                                <div class="text-muted d-flex align-items-center gap-1">
                                                    <i class="ph ph-phone text-success"></i> <?= htmlspecialchars($guide['phone'] ?? 'N/A') ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-info-subtle text-info px-2 py-1" style="font-size: 0.7rem;">
                                                <i class="ph ph-translate me-1"></i> <?= htmlspecialchars($guide['languages'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold text-dark small"><?= htmlspecialchars($guide['experience_years'] ?? 0) ?> năm</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <i class="ph-fill ph-star text-warning" style="font-size: 0.85rem;"></i>
                                                <span class="fw-bold text-dark small"><?= number_format($guide['rating'] ?? 5, 1) ?></span>
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="<?= BASE_URL_ADMIN . '&action=guides/detail&id=' . $guide['id'] ?>" class="btn btn-sm bg-white text-info border shadow-sm" title="Chi tiết"><i class="ph ph-eye"></i></a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=guides/edit&id=' . $guide['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Sửa"><i class="ph ph-pencil-simple"></i></a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=guides/delete&id=' . $guide['id'] ?>" class="btn btn-sm bg-white text-danger border shadow-sm" onclick="return confirm('Xóa hướng dẫn viên này?')" title="Xóa"><i class="ph ph-trash"></i></a>
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
                            <i class="ph ph-user-tie text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Chưa có hướng dẫn viên nào</h5>
                        <p class="text-muted">Bắt đầu bằng cách thêm hướng dẫn viên đầu tiên vào hệ thống.</p>
                        <a href="<?= BASE_URL_ADMIN . '&action=guides/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                            <i class="ph ph-plus-circle me-1"></i> Thêm HDV Mới
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add Enter key handler to keyword input
        const keywordInput = document.querySelector('#guide-filters [name="keyword"]');
        if (keywordInput) {
            keywordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterGuides();
                }
            });
        }
    });

    function filterGuides() {
        const keyword = document.querySelector('[name="keyword"]').value.toLowerCase();
        const ratingMin = parseFloat(document.querySelector('[name="rating_min"]').value) || 0;

        const tbody = document.querySelector('.table-modern tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Filter rows
        let visibleCount = 0;
        rows.forEach(row => {
            // Get row data
            const guideName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const contactInfo = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            // Get rating
            const ratingElement = row.querySelector('.rating-value');
            const guideRating = ratingElement ? parseFloat(ratingElement.textContent) : 0;

            // Check filters
            let show = true;

            // Filter by keyword (search in name, email, phone)
            if (keyword && !guideName.includes(keyword) && !contactInfo.includes(keyword)) {
                show = false;
            }

            // Filter by rating
            if (ratingMin > 0 && guideRating < ratingMin) {
                show = false;
            }

            // Show/hide row
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Update count
        const countElement = document.querySelector('.count-info');
        if (countElement) {
            countElement.textContent = visibleCount + ' HDV';
        }
    }

    function resetFilters() {
        document.getElementById('guide-filters').reset();
        filterGuides();
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>