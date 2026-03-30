<?php
$version = $version ?? null;
$isEdit = isset($version);
$formAction = $isEdit
    ? "?mode=admin&action=tours_versions/update&id={$version['id']}"
    : "?mode=admin&action=tours_versions/store";
$title = $isEdit ? 'Chỉnh sửa phiên bản' : 'Thêm phiên bản mới';

// Get old input or use version data
$formData = $_SESSION['old_input'] ?? ($version ?? []);
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);
?>

<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="text-muted text-decoration-none">Phiên bản Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Chỉnh sửa' : 'Thêm mới' ?></li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 mt-1" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= $title ?></h4>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-arrow-left" style="font-size: 1.1rem;"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 mb-4" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-check-circle fs-5"></i>
            <div><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 mb-4" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-warning-circle fs-5"></i>
            <div><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="POST" action="<?= $formAction ?>" id="versionForm">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $version['id'] ?>">
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-12 col-lg-7">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="ph ph-info text-primary"></i> Thông tin cơ bản
                            </h6>

                            <div class="row g-3 px-3">
                                <div class="col-12 col-md-8">
                                    <label for="name" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">
                                        Tên phiên bản <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-sm mb-1 shadow-sm border rounded" style="border-radius: var(--radius-md) !important;">
                                        <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="ph ph-tag"></i></span>
                                        <input type="text"
                                            class="form-control border-0 py-2 <?= isset($formErrors['name']) ? 'is-invalid' : '' ?>"
                                            id="name"
                                            name="name"
                                            value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                            placeholder="Ví dụ: Mùa hè 2023, Giáng sinh 2023..."
                                            required
                                            style="font-size: 0.9rem;">
                                    </div>
                                    <?php if (isset($formErrors['name'])): ?>
                                        <div class="invalid-feedback d-block ps-2" style="font-size: 0.75rem;">
                                            <i class="ph ph-warning-circle me-1"></i> <?= $formErrors['name'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="status" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">
                                        Trạng thái
                                    </label>
                                    <div class="input-group input-group-sm mb-1 shadow-sm border rounded" style="border-radius: var(--radius-md) !important;">
                                        <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="ph ph-toggle-left"></i></span>
                                        <select class="form-select border-0 py-2" id="status" name="status" style="font-size: 0.9rem;">
                                            <option value="active" <?= ($formData['status'] ?? '') === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                                            <option value="inactive" <?= ($formData['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tạm ẩn</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="ph ph-text-align-left text-primary"></i> Mô tả chi tiết
                            </h6>
                            <div class="px-3">
                                <div class="mb-2">
                                    <label for="description" class="form-label fw-semibold text-dark mb-1" style="font-size: 0.85rem;">Mô tả phiên bản</label>
                                    <div class="shadow-sm border rounded overflow-hidden" style="border-radius: var(--radius-md) !important;">
                                        <textarea class="form-control border-0 p-3"
                                            id="description"
                                            name="description"
                                            rows="6"
                                            placeholder="Nhập mô tả chi tiết về phiên bản này..."
                                            style="font-size: 0.9rem; resize: none;"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                        <div class="bg-light px-3 py-1 border-top text-end" style="font-size: 0.75rem; color: #64748b;">
                                            <span id="descCounter">0</span> / 1000 ký tự
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Pricing & Preview -->
                    <div class="col-12 col-lg-5">
                        <!-- Pricing Section -->
                        <div class="card bg-light border-0 mb-4" style="border-radius: var(--radius-md);">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="ph ph-currency-circle-dollar text-primary"></i> Giá theo phiên bản (%)
                                </h6>
                                <div class="alert alert-info py-2 px-3 mb-3 border-0 bg-info-subtle text-info d-flex align-items-start gap-2" style="font-size: 0.8rem; border-radius: 8px;">
                                    <i class="ph-fill ph-info fs-6 mt-1"></i>
                                    <div><strong>Lưu ý:</strong> Nhập % tăng/giảm so với giá gốc. Ví dụ: +20, -15.</div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Người lớn (%)</label>
                                        <div class="input-group input-group-sm shadow-sm border rounded" style="border-radius: 8px !important;">
                                            <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="ph ph-users"></i></span>
                                            <input type="number" class="form-control border-0 py-2" name="adult_percent" value="<?= htmlspecialchars($prices['adult_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Trẻ em (%)</label>
                                        <div class="input-group input-group-sm shadow-sm border rounded" style="border-radius: 8px !important;">
                                            <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="ph ph-baby"></i></span>
                                            <input type="number" class="form-control border-0 py-2" name="child_percent" value="<?= htmlspecialchars($prices['child_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Em bé (%)</label>
                                        <div class="input-group input-group-sm shadow-sm border rounded" style="border-radius: 8px !important;">
                                            <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="ph ph-baby-carriage"></i></span>
                                            <input type="number" class="form-control border-0 py-2" name="infant_percent" value="<?= htmlspecialchars($prices['infant_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Tỷ lệ Trẻ em (%)</label>
                                        <div class="input-group input-group-sm shadow-sm border rounded" style="border-radius: 8px !important;">
                                            <span class="input-group-text bg-white border-0 text-muted ps-2 pe-1"><i class="ph ph-percent"></i></span>
                                            <input type="number" class="form-control border-0 py-2" name="child_base_percent" value="<?= htmlspecialchars($prices['child_base_percent'] ?? 75) ?>" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="ph ph-eye text-primary"></i> Xem trước
                            </h6>
                            <div class="p-3 border shadow-sm bg-white" style="border-radius: var(--radius-md); border-left: 4px solid var(--primary) !important;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0 text-dark" id="preview-name" style="font-size: 1.1rem;"><?= htmlspecialchars($formData['name'] ?? 'Tên phiên bản') ?></h5>
                                    <span class="badge rounded-pill px-2 py-1" id="preview-status" style="font-size: 0.65rem; font-weight: 600;">
                                        <?= ($formData['status'] ?? 'inactive') === 'active' ? 'Hoạt động' : 'Tạm ẩn' ?>
                                    </span>
                                </div>
                                <p class="text-muted small mb-0" id="preview-description" style="line-height: 1.5; min-height: 60px;">
                                    <?= !empty($formData['description']) ? nl2br(htmlspecialchars($formData['description'])) : 'Chưa có mô tả chi tiết cho phiên bản này.' ?>
                                </p>
                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                    <span><i class="ph ph-calendar-blank me-1"></i> <?= date('d/m/Y') ?></span>
                                    <span class="fw-medium text-primary">#<?= str_pad($version['id'] ?? '0', 4, '0', STR_PAD_LEFT) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-light px-4" style="border-radius: 8px;">Hủy bỏ</a>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm d-flex align-items-center gap-2" id="submitBtn" style="border-radius: 8px;">
                                            </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('versionForm');
        const nameInput = document.getElementById('name');
        const statusSelect = document.getElementById('status');
        const descriptionTextarea = document.getElementById('description');
        const submitBtn = document.getElementById('submitBtn');
        const previewName = document.getElementById('preview-name');
        const previewStatus = document.getElementById('preview-status');
        const previewDescription = document.getElementById('preview-description');
        const descCounter = document.getElementById('descCounter');

        function updateCounter() {
            const length = descriptionTextarea.value.length;
            descCounter.textContent = length;
            descCounter.style.color = length > 1000 ? '#ef4444' : (length > 800 ? '#f59e0b' : '#64748b');
        }

        function updatePreview() {
            previewName.textContent = nameInput.value || 'Tên phiên bản';
            const status = statusSelect.value;
            previewStatus.innerHTML = status === 'active' ? 
                '<i class="ph-fill ph-check-circle me-1"></i> Hoạt động' : 
                '<i class="ph-fill ph-circle me-1"></i> Tạm ẩn';
            previewStatus.className = 'badge rounded-pill px-2 py-1 ' + 
                (status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary');
            const description = descriptionTextarea.value;
            previewDescription.innerHTML = description ? 
                description.replace(/\n/g, '<br>') : 'Chưa có mô tả chi tiết cho phiên bản này.';
        }

        nameInput.addEventListener('input', updatePreview);
        statusSelect.addEventListener('change', updatePreview);
        descriptionTextarea.addEventListener('input', () => {
            updatePreview();
            updateCounter();
        });

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ph ph-spinner fs-5 ph-spin"></i> Đang xử lý...';
        });

        updatePreview();
        updateCounter();
    });
</script>
});

        // Clear changes flag after successful submission
        form.addEventListener('submit', () => {
            hasChanges = false;
        });
    });
</script>