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
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none">Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Phiên bản Tour</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours_versions/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Phiên Bản
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
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Phiên Bản</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= count($versions ?? []) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle); border-color: var(--primary-subtle) !important;">
                    <i class="ph ph-git-branch"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đang Hoạt Động</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= count(array_filter($versions ?? [], fn($v) => ($v['status'] ?? '') === 'active')) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle); border-color: var(--success-subtle) !important;">
                    <i class="ph ph-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tạm Dừng</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= count(array_filter($versions ?? [], fn($v) => ($v['status'] ?? '') === 'inactive')) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle); border-color: var(--warning-subtle) !important;">
                    <i class="ph ph-pause-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Mới Nhất</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.1rem; letter-spacing: -0.2px;"><?= !empty($versions) ? date('d/m/Y', strtotime($versions[0]['created_at'])) : '---' ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle); border-color: var(--info-subtle) !important;">
                    <i class="ph ph-calendar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Versions List -->
    <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
            <div class="p-3 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="ph-fill ph-list-bullets text-primary"></i> Danh sách phiên bản
                </h6>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn btn-sm btn-light border d-flex align-items-center gap-1 py-1 px-3 shadow-none" style="font-size: 0.75rem; border-radius: 8px;">
                        <i class="ph ph-arrow-left"></i> Quay lại Tours
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($versions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 80px;">ID</th>
                                    <th class="py-3">Tên phiên bản</th>
                                    <th class="py-3">Mô tả</th>
                                    <th class="py-3">Ngày tạo</th>
                                    <th class="py-3">Trạng thái</th>
                                    <th class="text-end pe-4 py-3" style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($versions as $version): ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-medium">#<?= str_pad($version['id'] ?? '0', 3, '0', STR_PAD_LEFT) ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($version['name'] ?? '') ?></div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($version['description'] ?? '---') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <i class="ph ph-calendar text-primary"></i>
                                                <?= !empty($version['created_at']) ? date('d/m/Y', strtotime($version['created_at'])) : '---' ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-2 py-1 <?= ($version['status'] ?? '') === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>" style="font-size: 0.7rem; font-weight: 600;">
                                                <i class="ph-fill ph-circle me-1" style="font-size: 0.5rem;"></i>
                                                <?= ($version['status'] ?? '') === 'active' ? 'Hoạt động' : 'Tạm dừng' ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm bg-white text-primary border shadow-sm" onclick="editVersion(<?= $version['id'] ?>)" title="Sửa">
                                                    <i class="ph ph-pencil-simple"></i>
                                                </button>
                                                <button class="btn btn-sm bg-white <?= ($version['status'] ?? '') === 'active' ? 'text-warning' : 'text-success' ?> border shadow-sm" onclick="toggleVersionStatus(<?= $version['id'] ?>, '<?= $version['status'] ?? '' ?>')" title="<?= ($version['status'] ?? '') === 'active' ? 'Tạm dừng' : 'Kích hoạt' ?>">
                                                    <i class="ph ph-<?= ($version['status'] ?? '') === 'active' ? 'pause' : 'play' ?>"></i>
                                                </button>
                                                <button class="btn btn-sm bg-white text-danger border shadow-sm" onclick="deleteVersion(<?= $version['id'] ?>, '<?= htmlspecialchars($version['name'] ?? '') ?>')" title="Xóa">
                                                    <i class="ph ph-trash"></i>
                                                </button>
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
                            <i class="ph ph-git-branch text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Chưa có phiên bản nào</h5>
                        <p class="text-muted">Bắt đầu tạo phiên bản tour đầu tiên để quản lý.</p>
                        <a href="<?= BASE_URL_ADMIN . '&action=tours_versions/create' ?>" class="btn btn-primary mt-2 px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="ph ph-plus-circle me-1"></i> Tạo phiên bản mới
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-4 px-4">
                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger-subtle text-danger mb-4" style="width: 72px; height: 72px;">
                    <i class="ph-fill ph-warning-circle" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Xóa phiên bản này?</h5>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn xóa phiên bản "<strong id="deleteVersionName" class="text-dark"></strong>"?<br> Hành động này không thể hoàn tác.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="deleteForm" method="POST" class="m-0">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger px-4 shadow-sm">Xóa Phiên Bản</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Table Section Refinements moved to card-premium */
    .bg-success-subtle { background-color: #d1fae5 !important; }
    .bg-secondary-subtle { background-color: #f1f5f9 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete version functionality
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        var deleteForm = document.getElementById('deleteForm');
        var deleteVersionName = document.getElementById('deleteVersionName');

        window.deleteVersion = function(id, name) {
            deleteVersionName.textContent = name;
            deleteForm.action = '<?= BASE_URL_ADMIN ?>&action=tours_versions/delete&id=' + id;
            deleteModal.show();
        };

        // Edit version
        window.editVersion = function(id) {
            window.location.href = '<?= BASE_URL_ADMIN ?>&action=tours_versions/edit&id=' + id;
        };

        // Toggle version status
        window.toggleVersionStatus = function(id, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const actionText = newStatus === 'active' ? 'Kích hoạt' : 'Tạm dừng';

            if (confirm(`Bạn có chắc muốn ${actionText.toLowerCase()} phiên bản này?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL_ADMIN ?>&action=tours_versions/toggle-status';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;

                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = newStatus;

                form.appendChild(methodInput);
                form.appendChild(idInput);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            }
        };
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>