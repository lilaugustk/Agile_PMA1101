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
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none"><i class="ph ph-map-pin me-1"></i> Quản lý Tour</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="text-muted text-decoration-none"><i class="ph ph-stack me-1"></i> Phiên bản</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Chỉnh sửa' : 'Thêm mới' ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-light border px-4 py-2 shadow-sm fw-medium d-flex align-items-center gap-2" style="border-radius: 10px;">
                <i class="ph ph-arrow-left"></i> Quay lại
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
                    <!-- Basic Information & Description -->
                    <div class="col-12 col-lg-7">
                        <div class="card-premium border-0 shadow-sm bg-white mb-4">
                            <div class="p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-info"></i> Thông tin phiên bản
                                </h6>
                            </div>
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <label for="name" class="form-label text-muted fw-medium small mb-2">Tên phiên bản <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 text-muted"><i class="ph ph-tag"></i></span>
                                            <input type="text" class="form-control bg-light border-0 py-2 <?= isset($formErrors['name']) ? 'is-invalid' : '' ?>" 
                                                id="name" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" 
                                                placeholder="Ví dụ: Mùa xuân, Gói VIP..." required>
                                        </div>
                                        <?php if (isset($formErrors['name'])): ?>
                                            <div class="invalid-feedback d-block small mt-1 ps-2"><?= $formErrors['name'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status" class="form-label text-muted fw-medium small mb-2">Trạng thái</label>
                                        <select class="form-select bg-light border-0 py-2" id="status" name="status">
                                            <option value="active" <?= ($formData['status'] ?? '') === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                                            <option value="inactive" <?= ($formData['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tạm ẩn</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label text-muted fw-medium small mb-2">Mô tả chi tiết</label>
                                        <div class="position-relative">
                                            <textarea class="form-control bg-light border-0 p-3" id="description" name="description" rows="8" 
                                                placeholder="Nhập ghi chú chi tiết cho phiên bản này..." style="resize: none;"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                            <div class="position-absolute bottom-0 end-0 p-2 text-muted small opacity-50" id="descCounterContainer">
                                                <span id="descCounter">0</span>/1000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Pricing & Preview -->
                    <div class="col-12 col-lg-5">
                        <!-- Pricing Section -->
                        <div class="card-premium border-0 shadow-sm bg-white mb-4 overflow-hidden">
                            <div class="p-3 px-4 border-bottom border-light bg-light bg-opacity-50">
                                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-currency-circle-dollar text-warning"></i> Giá phiên bản (%)
                                </h6>
                            </div>
                            <div class="p-4">
                                <div class="alert alert-primary py-2 px-3 mb-4 border-0 bg-primary-subtle text-primary d-flex align-items-start gap-2" style="font-size: 0.8rem;">
                                    <i class="ph-fill ph-info fs-6 mt-1"></i>
                                    <div>Nhập % thay đổi (+/-). VD: +10 tăng giá, -5 giảm giá so với gốc.</div>
                                </div>
 
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label text-muted small mb-1">Người lớn (%)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0 text-muted ps-2 pe-1"><i class="ph ph-user"></i></span>
                                            <input type="number" class="form-control bg-light border-0 py-2" name="adult_percent" value="<?= htmlspecialchars($prices['adult_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted small mb-1">Trẻ em (%)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0 text-muted ps-2 pe-1"><i class="ph ph-baby"></i></span>
                                            <input type="number" class="form-control bg-light border-0 py-2" name="child_percent" value="<?= htmlspecialchars($prices['child_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted small mb-1">Em bé (%)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0 text-muted ps-2 pe-1"><i class="ph ph-baby-carriage"></i></span>
                                            <input type="number" class="form-control bg-light border-0 py-2" name="infant_percent" value="<?= htmlspecialchars($prices['infant_percent'] ?? 0) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted small mb-1">Tỷ lệ Trẻ em (%)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0 text-muted ps-2 pe-1"><i class="ph ph-percent"></i></span>
                                            <input type="number" class="form-control bg-light border-0 py-2" name="child_base_percent" value="<?= htmlspecialchars($prices['child_base_percent'] ?? 75) ?>" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted small mb-1">Tỷ lệ Em bé (%)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0 text-muted ps-2 pe-1"><i class="ph ph-percent"></i></span>
                                            <input type="number" class="form-control bg-light border-0 py-2" name="infant_base_percent" value="<?= htmlspecialchars($prices['infant_base_percent'] ?? 50) ?>" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        <!-- Preview Card -->
                        <div class="card-premium border-0 shadow-sm bg-white overflow-hidden border-start border-primary border-4">
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0 text-dark" id="preview-name"><?= htmlspecialchars($formData['name'] ?? 'Tên phiên bản') ?></h5>
                                    <div id="preview-status">
                                        <span class="badge rounded-pill px-3 py-1 fw-bold <?= ($formData['status'] ?? 'inactive') === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>" style="font-size: 0.7rem;">
                                            <i class="ph-fill ph-circle me-1"></i> <?= ($formData['status'] ?? 'inactive') === 'active' ? 'Hoạt động' : 'Tạm ẩn' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="preview-content bg-light p-3 rounded" style="min-height: 120px;">
                                    <p class="text-muted small mb-0" id="preview-description" style="line-height: 1.6;">
                                        <?= !empty($formData['description']) ? nl2br(htmlspecialchars($formData['description'])) : 'Chưa có mô tả chi tiết cho phiên bản này.' ?>
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                    <span><i class="ph ph-calendar me-1"></i> Cập nhật: <?= date('d/m/Y') ?></span>
                                    <span class="fw-bold text-primary">#<?= str_pad($version['id'] ?? '0', 4, '0', STR_PAD_LEFT) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-light px-4 py-2 fw-medium" style="border-radius: 10px;">
                        <i class="ph ph-x me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold d-flex align-items-center gap-2" id="submitBtn" style="border-radius: 10px;">
                        <i class="ph-fill ph-check-circle"></i> <?= $isEdit ? 'Lưu thay đổi' : 'Tạo phiên bản' ?>
                    </button>
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
            previewStatus.innerHTML = `
                <span class="badge rounded-pill px-3 py-1 fw-bold ${status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}" style="font-size: 0.7rem;">
                    <i class="ph-fill ph-circle me-1"></i> ${status === 'active' ? 'Hoạt động' : 'Tạm ẩn'}
                </span>
            `;
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
            submitBtn.innerHTML = '<i class="ph ph-spinner fs-5 ph-spin"></i> Đang lưu...';
        });

        updatePreview();
        updateCounter();
    });
</script>