<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$isEdit = isset($category);
$pageTitle = $isEdit ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục Mới';
$submitAction = $isEdit ? 'tours_categories/update' : 'tours_categories/store';

// Old input and errors
$oldInput = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);

// Form data
$formData = $isEdit ? array_merge($category, $oldInput) : $oldInput;
?>

<main class="content">
    <div class="container-fluid p-0">
        <!-- Header & Breadcrumbs -->
        <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted">
                                <i class="ph ph-house me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories" class="text-decoration-none text-muted">
                                Quản lý Danh mục
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle ?></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories" class="btn btn-sm btn-white border shadow-sm d-flex align-items-center gap-2 px-3 py-2">
                    <i class="ph ph-x"></i> Hủy bỏ
                </a>
                <button type="submit" form="category-form" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm">
                    <i class="ph ph-floppy-disk"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu Danh mục' ?>
                </button>
            </div>
        </div>

        <!-- Category Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=<?= $submitAction ?>" id="category-form" novalidate>
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <?php endif; ?>

            <div class="row g-4 justify-content-center">
                <!-- Main Info Column -->
                <div class="col-lg-10">
                    <div class="card card-premium border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom border-light py-3">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                                <i class="ph-fill ph-info text-primary"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-floating input-group-premium">
                                        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                               id="name" name="name" placeholder=" "
                                               value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
                                        <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                                        <div class="input-icon-right">
                                            <i class="ph ph-tag"></i>
                                        </div>
                                        <?php if (isset($errors['name'])): ?>
                                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6">
                                    <div class="form-floating input-group-premium">
                                        <input type="text" class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>" 
                                               id="slug" name="slug" placeholder=" "
                                               value="<?= htmlspecialchars($formData['slug'] ?? '') ?>">
                                        <label for="slug">Slug (URL thân thiện)</label>
                                        <div class="input-icon-right">
                                            <i class="ph ph-link"></i>
                                        </div>
                                        <?php if (isset($errors['slug'])): ?>
                                            <div class="invalid-feedback"><?= $errors['slug'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-text small mt-2">
                                        <i class="ph ph-info me-1"></i> Để trống để tự động tạo từ tên danh mục.
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="form-floating custom-textarea-wrapper">
                                        <textarea class="form-control" id="description" name="description" 
                                                  placeholder=" " style="height: 180px;"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                        <label for="description">Mô tả danh mục</label>
                                        <div class="textarea-counter">
                                            <span id="char-count">0</span> / 500
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Actions -->
            <div class="form-actions-sticky mt-2 p-4 card card-premium border-0 shadow-lg d-flex flex-row justify-content-between align-items-center col-lg-10 mx-auto">
                <div class="text-muted d-none d-md-block small fw-medium">
                    <i class="ph ph-info me-1"></i> Kiểm tra kỹ thông tin trước khi hoàn tất.
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold" onclick="resetForm()">
                        <i class="ph ph-arrows-counter-clockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold">
                        <i class="ph ph-check-circle me-1"></i> <?= $isEdit ? 'Cập nhật ngay' : 'Tạo danh mục mới' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<style>
    /* Premium Style Enhancements */
    .content {
        background-color: #f8fafc;
        padding: 2.5rem 2rem;
    }

    .card-premium {
        border-radius: var(--radius-lg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-premium:hover {
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08) !important;
    }

    /* Floating Labels Styling */
    .form-floating > .form-control {
        border-radius: var(--radius-md);
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
        height: calc(3.8rem + 2px);
        border: 1px solid var(--border-light);
        box-shadow: none;
    }

    .form-floating > .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-subtle);
    }

    .form-floating > label {
        padding: 1.1rem;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.95rem;
    }

    /* Input with Icon on Right */
    .input-group-premium {
        position: relative;
    }

    .input-icon-right {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.3rem;
        pointer-events: none;
        z-index: 5;
    }

    .form-control:focus ~ .input-icon-right {
        color: var(--primary);
    }

    /* Textarea Customization */
    .custom-textarea-wrapper .form-control {
        height: auto;
        min-height: 151px;
        padding-top: 2rem;
    }

    .textarea-counter {
        position: absolute;
        bottom: 12px;
        right: 16px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        background: rgba(255, 255, 255, 0.9);
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        pointer-events: none;
    }

    /* Sticky Bottom Bar */
    .form-actions-sticky {
        position: sticky;
        bottom: 1.5rem;
        z-index: 1000;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.85) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 -10px 40px -10px rgba(0, 0, 0, 0.05) !important;
    }

    /* Responsive Tweaks */
    @media (max-width: 991px) {
        .content { padding: 1.5rem 1rem; }
        .form-actions-sticky {
            flex-direction: column !important;
            gap: 1.5rem;
            align-items: stretch !important;
            position: relative;
            bottom: 0;
            margin-top: 2rem;
        }
        .form-actions-sticky button { width: 100%; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descTextarea = document.getElementById('description');
    const charCountEl = document.getElementById('char-count');

    if (descTextarea && charCountEl) {
        const updateCounter = () => {
            const length = descTextarea.value.length;
            charCountEl.textContent = length;
            
            if (length > 500) {
                charCountEl.style.color = 'var(--danger)';
                charCountEl.classList.add('fw-bold');
                descTextarea.classList.add('is-invalid');
            } else {
                charCountEl.style.color = '';
                charCountEl.classList.remove('fw-bold');
                descTextarea.classList.remove('is-invalid');
            }
        };

        descTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Auto-generate slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput && !<?= $isEdit ? 'true' : 'false' ?>) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                const slug = generateSlug(this.value);
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        slugInput.addEventListener('input', function() {
            if (this.value) this.dataset.autoGenerated = 'false';
        });
    }

    function generateSlug(text) {
        const vietnameseMap = {
            'à': 'a', 'á': 'a', 'ạ': 'a', 'ả': 'a', 'ã': 'a', 'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ậ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ă': 'a', 'ằ': 'a', 'ắ': 'a', 'ặ': 'a', 'ẳ': 'a', 'ẵ': 'a',
            'è': 'e', 'é': 'e', 'ẹ': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ê': 'e', 'ề': 'e', 'ế': 'e', 'ệ': 'e', 'ể': 'e', 'ễ': 'e',
            'ì': 'i', 'í': 'i', 'ị': 'i', 'ỉ': 'i', 'ĩ': 'i',
            'ò': 'o', 'ó': 'o', 'ọ': 'o', 'ỏ': 'o', 'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ộ': 'o', 'ổ': 'o', 'ỗ': 'o', 'ơ': 'o', 'ờ': 'o', 'ớ': 'o', 'ợ': 'o', 'ở': 'o', 'ỡ': 'o',
            'ù': 'u', 'ú': 'u', 'ụ': 'u', 'ủ': 'u', 'ũ': 'u', 'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ự': 'u', 'ử': 'u', 'ữ': 'u',
            'ỳ': 'y', 'ý': 'y', 'ỵ': 'y', 'ỷ': 'y', 'ỹ': 'y', 'đ': 'd'
        };
        let slug = text.toLowerCase();
        for (let char in vietnameseMap) {
            slug = slug.replace(new RegExp(char, 'g'), vietnameseMap[char]);
        }
        return slug.replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }
});

function resetForm() {
    if (confirm('Bạn có chắc muốn làm mới toàn bộ form? Mọi thay đổi sẽ bị mất.')) {
        document.getElementById('category-form').reset();
        document.getElementById('char-count').textContent = '0';
    }
}
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>