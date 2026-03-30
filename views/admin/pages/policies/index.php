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
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Chính sách</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=policies/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Chính sách Mới
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Chính sách</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format(count($policies)) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-shield-check"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tour Áp dụng</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                        <?php 
                        $totalTours = array_sum(array_column($policies, 'tour_count'));
                        echo number_format($totalTours);
                        ?>
                    </h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                    <i class="ph ph-map-trifold"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-premium">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-fill ph-list-bullets text-primary"></i>
                <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Danh sách Chính sách</h6>
                <span class="badge bg-light text-muted border ms-2 rounded-pill" style="font-size: 0.7rem;"><?= count($policies) ?> kết quả</span>
            </div>
        </div>
        <div class="table-responsive bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($policies)) : ?>
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="30%">Tên Chính sách</th>
                            <th width="40%">Mô tả</th>
                            <th width="15%">Tour Áp dụng</th>
                            <th width="10%" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; foreach ($policies as $policy) : ?>
                            <tr>
                                <td class="text-center fw-medium text-muted"><?= $stt++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($policy['name']) ?></div>
                                    <div class="text-muted small"><code><?= htmlspecialchars($policy['slug']) ?></code></div>
                                </td>
                                <td>
                                    <div class="text-muted small text-truncate" style="max-width: 400px;" title="<?= htmlspecialchars($policy['description'] ?? '') ?>">
                                        <?= nl2br(htmlspecialchars($policy['description'] ?? '')) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="ph ph-map-pin me-1"></i><?= $policy['tour_count'] ?> tour
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= BASE_URL_ADMIN . '&action=policies/edit&id=' . $policy['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa">
                                            <i class="ph ph-pencil-simple"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm delete-policy" 
                                                data-id="<?= $policy['id'] ?>" 
                                                data-name="<?= htmlspecialchars($policy['name']) ?>"
                                                data-tour-count="<?= $policy['tour_count'] ?>"
                                                title="Xóa">
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
                        <i class="ph-fill ph-shield-slash text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có chính sách nào</h5>
                    <p class="text-muted">Bắt đầu tạo chính sách đầu tiên cho tour của bạn.</p>
                    <a href="<?= BASE_URL_ADMIN . '&action=policies/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm">
                        <i class="ph ph-plus me-1"></i> Thêm Chính sách Mới
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
                <h5 class="fw-bold mb-3">Xác nhận xóa chính sách</h5>
                <p class="text-muted mb-3">Bạn có chắc chắn muốn xóa chính sách "<strong id="delete-policy-name" class="text-dark"></strong>"?</p>
                
                <div id="tour-count-warning" class="alert bg-warning-subtle text-warning border-0 small mb-4 py-2" style="display: none; border-radius: 8px;">
                    <i class="ph ph-info me-1"></i>
                    <span id="tour-count-text"></span>
                </div>
                
                <p class="text-muted small mb-4">Hành động này không thể hoàn tác.</p>
                
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-danger px-4" id="confirm-delete">Xác nhận xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModalEl = document.getElementById('deleteModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const deletePolicyName = document.getElementById('delete-policy-name');
        const tourCountWarning = document.getElementById('tour-count-warning');
        const tourCountText = document.getElementById('tour-count-text');
        const confirmDeleteBtn = document.getElementById('confirm-delete');

        document.querySelectorAll('.delete-policy').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const tourCount = parseInt(this.dataset.tourCount);

                deletePolicyName.textContent = name;

                if (tourCount > 0) {
                    tourCountWarning.style.display = 'block';
                    tourCountText.textContent = `Chính sách này đang được sử dụng bởi ${tourCount} tour và không thể xóa.`;
                    confirmDeleteBtn.disabled = true;
                    confirmDeleteBtn.classList.add('opacity-50');
                } else {
                    tourCountWarning.style.display = 'none';
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.classList.remove('opacity-50');
                    confirmDeleteBtn.onclick = function() {
                        window.location.href = '<?= BASE_URL_ADMIN ?>&action=policies/delete&id=' + id;
                    };
                }

                deleteModal.show();
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>