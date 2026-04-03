<?php
// Get tour ID from URL
$tourId = $_GET['id'] ?? null;

if (!$tourId) {
    header('Location: ' . BASE_URL_ADMIN . '&action=tours');
    exit;
}

// Process images from controller data
$mainImage = null;
$galleryImages = [];

if (!empty($allImages)) {
    foreach ($allImages as $img) {
        if (!empty($img['main'])) {
            $mainImage = [
                'id' => $img['id'],
                'image_url' => $img['path']
            ];
        } else {
            $galleryImages[] = [
                'id' => $img['id'],
                'image_url' => $img['path']
            ];
        }
    }
}

// Ensure variables exist (set by controller)
$categories = $categories ?? [];
$itinerarySchedule = $itinerarySchedule ?? [];
$pricingOptions = $pricingOptions ?? [];
$partnerServices = $partnerServices ?? [];
$policies = $policies ?? [];
$assignedPolicyIds = $assignedPolicyIds ?? [];
$versions = $versions ?? [];
?>

<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="content tour-edit-page">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none"><i class="ph ph-map-pin me-1"></i> Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Tour</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn btn-light border shadow-sm px-3 py-2">
                <i class="ph ph-x me-1"></i> Hủy bỏ
            </a>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tourId ?>" class="btn btn-info text-white shadow-sm px-3 py-2 d-flex align-items-center gap-1">
                <i class="ph-fill ph-eye"></i> Xem chi tiết
            </a>
            <button type="submit" form="tour-edit-form" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                <i class="ph-fill ph-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-warning-circle fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['error']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-check-circle fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['success']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Progress Steps -->
    <div class="card-premium mb-4 bg-white p-3 shadow-sm border-0 d-flex justify-content-center">
        <div class="progress-steps d-flex justify-content-between align-items-center position-relative" style="max-width: 800px; width: 100%;">
            <div class="step active" data-step="1">
                <div class="step-icon"><i class="ph-fill ph-info"></i></div>
                <div class="step-label">Cơ bản</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-icon"><i class="ph-fill ph-image"></i></div>
                <div class="step-label">Hình ảnh</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-icon"><i class="ph-fill ph-map-trifold"></i></div>
                <div class="step-label">Lịch trình</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-icon"><i class="ph-fill ph-check-circle"></i></div>
                <div class="step-label">Hoàn tất</div>
            </div>
        </div>
    </div>

    <!-- Tour Form -->
    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/update&id=<?= $tourId ?>" enctype="multipart/form-data" id="tour-edit-form">
        <!-- Hidden inputs for dynamic data -->
        <input type="hidden" name="id" value="<?= $tourId ?>">
        <input type="hidden" name="tour_itinerary" id="tour_itinerary">
        <input type="hidden" name="tour_pricing_options" id="tour_pricing_options">
        <input type="hidden" name="tour_partners" id="tour_partners">
        <input type="hidden" name="tour_versions" id="tour_versions">

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Step 1: Basic Information -->
                <div class="form-step active" id="step-1">
                    <div class="card-premium border-0 shadow-sm bg-white">
                        <div class="p-3 px-4 border-bottom border-light">
                            <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                                <i class="ph-fill ph-info"></i> Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label text-muted fw-medium">Tên Tour <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="tour-name" class="form-control form-control-lg" required placeholder="Ví dụ: Tour Đà Nẵng..." value="<?= htmlspecialchars($tour['name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-medium">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == ($tour['category_id'] ?? '')) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-medium">Giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="base_price" id="base_price" class="form-control" required min="0" step="1" placeholder="0" value="<?= $tour['base_price'] ?? 0 ?>">
                                        <span class="input-group-text bg-light fw-medium text-muted">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-medium">Mô tả chi tiết</label>
                                    <textarea name="description" id="description" class="form-control" style="height: 150px" placeholder="Viết vài dòng giới thiệu về tour..."><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Images -->
                <div class="form-step d-none" id="step-2">
                    <div class="card-premium border-0 shadow-sm bg-white">
                        <div class="p-3 px-4 border-bottom border-light">
                            <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                                <i class="ph-fill ph-image"></i> Hình ảnh Tour
                            </h6>
                        </div>
                        <div class="p-4">
                            <!-- Main Image -->
                            <div class="mb-4">
                                <label class="form-label fw-medium text-muted">Ảnh đại diện</label>
                                <div class="main-image-upload border rounded bg-light p-4 text-center hover-bg-light position-relative" style="border-style: dashed !important; border-width: 2px !important; border-color: var(--border-color) !important;">
                                    <?php if (!empty($mainImage)): ?>
                                        <div class="main-image-preview mx-auto" id="mainImagePreview" style="max-width: 300px;">
                                            <img src="<?= BASE_ASSETS_UPLOADS . $mainImage['image_url'] ?>" alt="Main Image" class="img-fluid rounded shadow-sm w-100 object-fit-cover" style="height: 200px;">
                                            <input type="hidden" name="existing_main_image" value="<?= $mainImage['id'] ?>">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="removeMainImage()">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="upload-area cursor-pointer" id="mainImageDropZone" onclick="document.getElementById('main_image').click()" style="<?= !empty($mainImage) ? 'display: none;' : '' ?>">
                                        <i class="ph-fill ph-cloud-arrow-up text-primary mb-2" style="font-size: 3rem;"></i>
                                        <p class="mb-1 fw-bold">Kéo thả hoặc click để chọn ảnh đại diện</p>
                                        <span class="text-muted small">JPG, PNG, WEBP. Tối đa 5MB</span>
                                        <input type="file" name="main_image" id="main_image" accept="image/*" class="d-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label class="form-label fw-medium text-muted">Thư viện ảnh</label>
                                <div class="gallery-upload-zone border rounded bg-light p-4 text-center hover-bg-light" style="border-style: dashed !important; border-width: 2px !important; border-color: var(--border-color) !important;">
                                    <div class="upload-area cursor-pointer" id="galleryDropZone" onclick="document.getElementById('gallery_images').click()">
                                        <i class="ph-fill ph-images text-primary mb-2" style="font-size: 3rem;"></i>
                                        <p class="mb-1 fw-bold">Kéo thả hoặc click để chọn thêm ảnh</p>
                                        <span class="text-muted small">JPG, PNG, WEBP. Tối đa 5MB mỗi ảnh</span>
                                        <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*" class="d-none">
                                    </div>

                                    <!-- Existing Gallery -->
                                    <div class="gallery-preview-grid mt-4 d-flex flex-wrap gap-2 justify-content-center" id="galleryPreview">
                                        <?php if (!empty($galleryImages)): ?>
                                            <?php foreach ($galleryImages as $img): ?>
                                                <div class="gallery-preview-item position-relative border rounded shadow-sm">
                                                    <img src="<?= BASE_ASSETS_UPLOADS . $img['image_url'] ?>" alt="Gallery Image" class="object-fit-cover rounded" style="width: 100px; height: 100px;">
                                                    <input type="hidden" name="existing_gallery[]" value="<?= $img['id'] ?>">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center;" onclick="removeExistingImage(<?= $img['id'] ?>, this)">
                                                        <i class="ph ph-trash" style="font-size: 0.8rem"></i>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Itinerary -->
                <div class="form-step d-none" id="step-3">
                    <div class="card-premium border-0 shadow-sm bg-white">
                        <div class="p-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-warning d-flex align-items-center gap-2">
                                <i class="ph-fill ph-map-trifold"></i> Lịch trình Tour
                            </h6>
                            <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="addItineraryDay()">
                                <i class="ph ph-plus me-1"></i> Thêm ngày
                            </button>
                        </div>
                        <div class="p-4">
                            <div id="itinerary-list" class="itinerary-list d-flex flex-column gap-3">
                                <?php if (!empty($itinerarySchedule)): ?>
                                    <?php foreach ($itinerarySchedule as $index => $itinerary): ?>
                                        <div class="itinerary-item border border-light bg-light rounded p-4 position-relative" data-index="<?= $index ?>">
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="removeItineraryItem(this)" style="width: 32px; height: 32px; padding:0;">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </div>
                                            <h6 class="mb-3 fw-bold text-primary">Ngày <span class="day-number"><?= $itinerary['day_number'] ?></span>: <?= htmlspecialchars($itinerary['day_label']) ?></h6>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-medium text-muted small">Tiêu đề</label>
                                                    <input type="text" class="form-control itinerary-title" value="<?= htmlspecialchars($itinerary['title'] ?? '') ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-medium text-muted small">Mô tả chi tiết</label>
                                                    <textarea class="form-control itinerary-description" style="height: 100px"><?= htmlspecialchars($itinerary['activities']) ?></textarea>
                                                </div>
                                                <?php if (!empty($itinerary['image_url'])): ?>
                                                    <div class="col-12">
                                                        <img src="<?= BASE_ASSETS_UPLOADS . $itinerary['image_url'] ?>" alt="Itinerary" class="object-fit-cover rounded border shadow-sm" style="max-width: 200px; height: 150px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="text-center text-muted py-5 bg-light rounded mt-3" id="itinerary-empty" style="border: 2px dashed var(--border-color); <?= !empty($itinerarySchedule) ? 'display: none;' : '' ?>">
                                <i class="ph-fill ph-calendar-blank text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                <h6 class="fw-bold text-dark">Chưa có lịch trình nào</h6>
                                <p class="small">Lên kế hoạch các ngày cụ thể cho tour.</p>
                                <button type="button" class="btn btn-outline-primary shadow-sm" onclick="addItineraryDay()">
                                    <i class="ph ph-plus me-1"></i> Thêm ngày đầu tiên
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Final Details -->
                <div class="form-step d-none" id="step-4">
                    <!-- Partners -->

                    <!-- Policies -->
                    <div class="card-premium border-0 shadow-sm bg-white">
                        <div class="p-3 px-4 border-bottom border-light">
                            <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                                <i class="ph-fill ph-shield-check"></i> Chính sách áp dụng
                            </h6>
                        </div>
                        <div class="p-4">
                            <?php if (!empty($policies)): ?>
                                <div class="row g-3">
                                    <?php foreach ($policies as $policy): ?>
                                        <div class="col-md-6">
                                            <div class="form-check border border-light rounded p-3 bg-light hover-bg-white transition-all h-100">
                                                <input class="form-check-input mt-1" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>" id="policy_<?= $policy['id'] ?>"
                                                    <?= in_array($policy['id'], $assignedPolicyIds ?? []) ? 'checked' : '' ?> style="width: 1.25rem; height: 1.25rem;">
                                                <label class="form-check-label fw-bold ms-2 text-dark" for="policy_<?= $policy['id'] ?>">
                                                    <?= htmlspecialchars($policy['name']) ?>
                                                </label>
                                                <div class="small text-muted mt-2 ms-4"><?= htmlspecialchars($policy['description'] ?? '') ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 bg-light rounded text-muted">
                                    <i class="ph-fill ph-shield-warning text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                    <p class="mb-0">Hệ thống chưa có chính sách nào</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Form Actions -->
                <div class="card-premium border-0 shadow-sm bg-white mb-4 sticky-top" style="top: 20px;">
                    <div class="p-3 px-4 border-bottom border-light bg-primary-subtle text-primary" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2"><i class="ph-fill ph-gear"></i> Bảng điều khiển</h6>
                    </div>
                    <div class="p-4">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2" onclick="saveDraft()" id="save-draft-btn">
                                <i class="ph ph-floppy-disk"></i> Lưu thay đổi nháp
                            </button>
                            <button type="submit" form="tour-edit-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 shadow-sm py-2">
                                <i class="ph-fill ph-check-circle"></i> Xác nhận & Cập nhật
                            </button>
                        </div>
                        <hr class="my-4 border-light">
                        <div class="d-flex justify-content-between gap-2">
                            <button type="button" class="btn btn-light border shadow-sm w-50 justify-content-center fw-medium" onclick="previousStep()" id="prev-btn" style="display: none;">
                                <i class="ph ph-caret-left me-1"></i> Quay lại
                            </button>
                            <button type="button" class="btn btn-dark shadow-sm w-50 justify-content-center fw-medium" onclick="nextStep()" id="next-btn">
                                Tiếp theo <i class="ph ph-caret-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Auto-save Status -->
                <div class="card-premium border-0 shadow-sm bg-white" id="autoSaveIndicator" style="opacity: 0.5;">
                    <div class="p-3 d-flex align-items-center gap-2 text-success">
                        <i class="ph-fill ph-check-circle fs-5"></i>
                        <span class="fw-medium">Thay đổi được tự động ghi nhận tại <span class="fw-bold">--:--</span></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<style>
/* Same CSS as create from Phase 4 Redesign */
.progress-steps::before {
    content: ''; position: absolute; top: 50%; transform: translateY(-50%); left: 10%; right: 10%; height: 3px; background: var(--bg-hover); z-index: 1;
}
.step {
    position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; color: var(--text-muted); transition: all 0.3s;
}
.step .step-icon {
    width: 48px; height: 48px; border-radius: 50%; background: white; border: 3px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; transition: all 0.3s;
}
.step.active .step-icon, .step.completed .step-icon {
    border-color: var(--primary); background: var(--primary); color: white; box-shadow: 0 0 0 4px var(--primary-subtle);
}
.step.active .step-label { color: var(--primary); font-weight: 600; }
.step.completed .step-label { color: var(--text-main); font-weight: 500; }
.cursor-pointer { cursor: pointer; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }
.hover-bg-white:hover { background-color: #ffffff !important; box-shadow: var(--shadow-sm); }
.transition-all { transition: all 0.2s ease-in-out; }
.form-step { display: none; }
.form-step.active { display: block; animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.gallery-preview-item { position: relative; }
.gallery-preview-item img { object-fit: cover; }
.gallery-item-actions { position: absolute; top: 4px; right: 4px; }
</style>

<!-- Templates -->
<template id="itinerary-template">
    <div class="itinerary-item border border-light bg-light rounded p-4 position-relative">
        <div class="position-absolute top-0 end-0 p-2">
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="removeItineraryItem(this)" style="width: 32px; height: 32px; padding:0;">
                <i class="ph ph-trash"></i>
            </button>
        </div>
        <h6 class="mb-3 fw-bold text-primary">Ngày <span class="day-number"></span></h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-medium text-muted small">Tiêu đề</label>
                <input type="text" class="form-control itinerary-title" placeholder="Ví dụ: Bay tới Nha Trang..." required>
            </div>
            <div class="col-12">
                <label class="form-label fw-medium text-muted small">Mô tả chi tiết</label>
                <textarea class="form-control itinerary-description" style="height: 100px"></textarea>
            </div>
        </div>
    </div>
</template>


<script>
    let currentStep = 1;
    const totalSteps = 4;
    let itineraryCount = <?= count($itinerarySchedule ?? []) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        updateStepDisplay();
        updateNavigationButtons();
        
        // Auto Save Mocking
        let autoSaveTimer;
        document.getElementById('tour-edit-form').addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(saveDraft, 30000);
        });

        // Setup Main Image Upload interaction
        document.getElementById('main_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('mainImageDropZone').style.display = 'none';
                    let preview = document.getElementById('mainImagePreview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'mainImagePreview';
                        preview.className = 'main-image-preview mx-auto';
                        preview.style.maxWidth = '300px';
                        document.querySelector('.main-image-upload').appendChild(preview);
                    }
                    preview.style.display = 'block';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="img-fluid rounded shadow-sm w-100 object-fit-cover" style="height: 200px;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="removeMainImage()">
                            <i class="ph ph-trash"></i>
                        </button>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });

        // Setup Gallery Upload interaction
        document.getElementById('gallery_images')?.addEventListener('change', function(e) {
            let preview = document.getElementById('galleryPreview');
            Array.from(e.target.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const item = document.createElement('div');
                        item.className = 'gallery-preview-item position-relative border rounded shadow-sm';
                        item.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="object-fit-cover rounded" style="width: 100px; height: 100px;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center;" onclick="this.parentElement.remove()">
                            <i class="ph ph-trash" style="font-size: 0.8rem"></i>
                        </button>
                    `;
                        preview.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    });

    function saveDraft() {
        updateItineraryData();
        const indicator = document.getElementById('autoSaveIndicator');
        indicator.style.opacity = '1';
        indicator.querySelector('.fw-bold').innerText = new Date().toLocaleTimeString();
        showToast('Đã lưu nháp tự động', 'success');
    }

    function removeMainImage() {
        if (confirm('Bạn có chắc muốn xóa ảnh đại diện này?')) {
            const preview = document.getElementById('mainImagePreview');
            const dropzone = document.getElementById('mainImageDropZone');
            if (preview) {
                preview.style.display = 'none';
                if (!preview.querySelector('input[name="existing_main_image"]')) {
                    preview.remove();
                }
            }
            dropzone.style.display = 'block';
            const fileInput = document.getElementById('main_image');
            if (fileInput) fileInput.value = '';

            const existingInput = document.querySelector('input[name="existing_main_image"]');
            if (existingInput) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_main_image';
                input.value = '1';
                document.getElementById('tour-edit-form').appendChild(input);
                existingInput.remove();
            }
        }
    }

    function removeExistingImage(imageId, btnElement) {
        if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_images[]';
            input.value = imageId;
            document.getElementById('tour-edit-form').appendChild(input);
            btnElement.closest('.gallery-preview-item').remove();
        }
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                updateNavigationButtons();
            }
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
            updateNavigationButtons();
        }
    }

    function updateStepDisplay() {
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
            const stepNum = parseInt(step.dataset.step);
            if (stepNum === currentStep) step.classList.add('active');
            else if (stepNum < currentStep) step.classList.add('completed');
        });

        document.querySelectorAll('.form-step').forEach(step => step.classList.add('d-none'));
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        
        const current = document.getElementById(`step-${currentStep}`);
        current.classList.remove('d-none');
        current.classList.add('active');
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        prevBtn.style.display = currentStep === 1 ? 'none' : 'flex';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'flex';
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                showToast('Vui lòng điền đầy đủ thông tin', 'danger');
                return false;
            }
        }
        return true;
    }

    function showToast(message, type = 'success') {
        const div = document.createElement('div');
        div.style.cssText = 'position:fixed; top:20px; right:20px; z-index:9999';
        div.innerHTML = `<div class="alert alert-${type} shadow-lg py-3 px-4 fw-bold rounded-pill border-0 d-flex align-items-center"><i class="ph-fill ph-check-circle me-2 fs-5"></i>${message}</div>`;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }

    // Dynamic Tracking Functions
    function addItineraryDay() {
        itineraryCount++;
        const template = document.getElementById('itinerary-template');
        const clone = template.content.cloneNode(true);
        clone.querySelector('.day-number').textContent = itineraryCount;
        document.getElementById('itinerary-list').appendChild(clone);
        document.getElementById('itinerary-empty').style.display = 'none';
        updateItineraryData();
    }
    function removeItineraryItem(btn) {
        btn.closest('.itinerary-item').remove();
        updateItineraryDayNumbers();
        updateItineraryData();
    }
    function updateItineraryDayNumbers() {
        const items = document.querySelectorAll('.itinerary-item');
        items.forEach((item, index) => item.querySelector('.day-number').textContent = index + 1);
        itineraryCount = items.length;
        if (itineraryCount === 0) document.getElementById('itinerary-empty').style.display = 'block';
    }
    function updateItineraryData() {
        const arr = [];
        document.querySelectorAll('.itinerary-item').forEach((item, index) => {
            arr.push({
                day_number: index + 1,
                day_label: `Ngày ${index + 1}`,
                title: item.querySelector('.itinerary-title').value,
                activities: item.querySelector('.itinerary-description').value
            });
        });
        document.getElementById('tour_itinerary').value = JSON.stringify(arr);
    }

    document.getElementById('tour-edit-form').addEventListener('submit', function(e) {
        updateItineraryData();
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>