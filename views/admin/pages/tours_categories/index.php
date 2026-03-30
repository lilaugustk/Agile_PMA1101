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
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Danh mục Tour</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours_categories/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Danh Mục
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Danh Mục</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(count($categories ?? [])) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle); border-color: var(--primary-subtle) !important;">
                    <i class="ph ph-folder"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Tour</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(array_sum(array_column($categories ?? [], 'tour_count'))) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle); border-color: var(--success-subtle) !important;">
                    <i class="ph ph-projector-screen-chart"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Có Tour</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(count(array_filter($categories ?? [], fn($c) => $c['tour_count'] > 0))) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle); border-color: var(--warning-subtle) !important;">
                    <i class="ph ph-folder-notched-plus"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Trống</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(count(array_filter($categories ?? [], fn($c) => $c['tour_count'] == 0))) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle); border-color: var(--info-subtle) !important;">
                    <i class="ph ph-folder-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
            <div class="p-3 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="ph-fill ph-list-bullets text-primary"></i> Danh sách danh mục
                </h6>
            </div>
            <?php if (!empty($categories)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 0.8rem; width: 60px;">ID</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.8rem;">DANH MỤC</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.8rem;">MÔ TẢ</th>
                                <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.8rem;">TOUR</th>
                                <th class="py-3 text-muted fw-bold text-center" style="font-size: 0.8rem;">GIÁ TB</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 0.8rem;">NGÀY TẠO</th>
                                <th class="pe-4 py-3 text-muted fw-bold text-end" style="font-size: 0.8rem;">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $category['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded" style="width: 40px; height: 40px;">
                                                <i class="<?= !empty($category['icon']) ? htmlspecialchars($category['icon']) : 'ph ph-folder' ?> fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($category['name']) ?></div>
                                                <div class="small text-muted font-monospace"><?= htmlspecialchars($category['slug'] ?? '') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small" style="max-width: 250px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= htmlspecialchars($category['description']) ?>">
                                            <?= htmlspecialchars($category['description'] ?: 'Chưa có mô tả') ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?= ($category['tour_count'] ?? 0) > 0 ? 'bg-success-subtle text-success' : 'bg-light text-muted border' ?>" style="font-size: 0.75rem;">
                                            <?= $category['tour_count'] ?? 0 ?> tour
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-primary">
                                            <?= number_format($category['avg_price'] ?? 0, 0, ',', '.') ?>đ
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <i class="ph ph-calendar-blank me-1"></i>
                                            <?= date('d/m/Y', strtotime($category['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=tours&category_id=<?= $category['id'] ?>" class="btn btn-sm bg-white text-info border shadow-sm" title="Xem Tour"><i class="ph ph-eye"></i></a>
                                            <button type="button" class="btn btn-sm bg-white text-primary border shadow-sm edit-category" data-id="<?= $category['id'] ?>" data-name="<?= htmlspecialchars($category['name']) ?>" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                                            <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm delete-category" data-id="<?= $category['id'] ?>" data-name="<?= htmlspecialchars($category['name']) ?>" data-tour-count="<?= $category['tour_count'] ?? 0 ?>" title="Xóa"><i class="ph ph-trash"></i></button>
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
                        <i class="ph ph-folder-open text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có danh mục nào</h5>
                    <p class="text-muted">Bắt đầu bằng cách tạo danh mục đầu tiên để tổ chức tours tốt hơn.</p>
                    <a href="<?= BASE_URL_ADMIN . '&action=tours_categories/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                        <i class="ph ph-plus-circle me-1"></i> Thêm Danh Mục Mới
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
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-5 px-4">
                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger-subtle text-danger mb-4" style="width: 72px; height: 72px;">
                    <i class="ph-fill ph-warning-circle" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-3">Xóa danh mục?</h5>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn xóa danh mục "<strong id="categoryName" class="text-dark"></strong>"?</p>
                
                <div id="tourCountWarning" class="alert bg-warning-subtle text-warning border-0 d-flex align-items-center gap-3 p-3 mb-4 text-start" style="border-radius: 12px; display: none !important;">
                    <i class="ph-fill ph-warning fs-4"></i>
                    <div class="small fw-medium">
                        Danh mục này đang có <span id="tourCount"></span> tour. Bạn cần chuyển các tour này sang danh mục khác trước khi xóa.
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Hủy bỏ</button>
                    <form id="deleteForm" method="POST" class="m-0">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger px-4 shadow-sm" id="confirmDeleteBtn" style="border-radius: 10px;">Xóa Danh Mục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
    /* Table Section Refinements moved to card-premium */

    .table thead th {
        background: #fdfdfd;
        border-bottom: 1px solid #f1f3f5;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8faff !important;
    }

    .table td {
        padding: 16px 8px;
        border-bottom: 1px solid #f1f3f5;
    }

    /* Badge Styles */
    .bg-success-subtle {
        background-color: #d1fae5 !important;
    }
    .text-success {
        color: #059669 !important;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Empty State */
    .empty-state-modern {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state-icon {
        font-size: 80px;
        color: var(--tours-text-muted, #adb5bd);
        margin-bottom: 24px;
    }

    .empty-state-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        margin-bottom: 12px;
    }

    .empty-state-description {
        color: var(--tours-text-secondary, #6c757d);
        font-size: 16px;
        margin-bottom: 24px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Modal Styles */
    .delete-warning {
        text-align: center;
    }

    .delete-warning i {
        font-size: 3rem;
        color: #ffc107;
        margin-bottom: 20px;
    }

    /* Responsive - Sync with tours */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 2rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete category functionality
        var deleteButtons = document.querySelectorAll('.delete-category');
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        var categoryName = document.getElementById('categoryName');
        var tourCount = document.getElementById('tourCount');
        var tourCountWarning = document.getElementById('tourCountWarning');
        var deleteForm = document.getElementById('deleteForm');
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var categoryId = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var tourCountValue = this.getAttribute('data-tour-count');

                categoryName.textContent = name;
                tourCount.textContent = tourCountValue;

                if (tourCountValue > 0) {
                    tourCountWarning.style.display = 'block';
                    confirmDeleteBtn.disabled = true;
                    confirmDeleteBtn.classList.add('disabled');
                } else {
                    tourCountWarning.style.display = 'none';
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.classList.remove('disabled');
                }

                deleteForm.action = '<?= BASE_URL_ADMIN ?>&action=tours_categories/delete&id=' + categoryId;
                deleteModal.show();
            });
        });

        // Edit category functionality
        var editButtons = document.querySelectorAll('.edit-category');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var categoryId = this.getAttribute('data-id');
                window.location.href = '<?= BASE_URL_ADMIN ?>&action=tours_categories/edit&id=' + categoryId;
            });
        });
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>