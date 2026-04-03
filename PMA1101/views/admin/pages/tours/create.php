<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Load data for form
$categories = $categories ?? [];
$policies = $policies ?? [];
?>
<main class="content tour-create-page">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none"><i class="ph ph-map-pin me-1"></i> Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tạo Tour Mới</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn btn-light border shadow-sm px-3 py-2">
                <i class="ph ph-x me-1"></i> Hủy bỏ
            </a>
            <button type="submit" form="tour-form" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm">
                <i class="ph-fill ph-floppy-disk"></i> Lưu Tour
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-warning-circle fs-5"></i>
            <div><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
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
    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/store" enctype="multipart/form-data" id="tour-form">
        <!-- Hidden inputs for dynamic data -->
        <input type="hidden" name="tour_itinerary" id="tour_itinerary" value="[]">
        <input type="hidden" name="tour_pricing_options" id="tour_pricing_options" value="[]">
        <input type="hidden" name="tour_partners" id="tour_partners" value="[]">

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
                                    <input type="text" name="name" id="tour-name" class="form-control form-control-lg" required placeholder="Ví dụ: Tour Đà Nẵng - Hội An 3N2Đ...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-medium">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-medium">Giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="base_price" id="base_price" class="form-control" required min="0" step="1" placeholder="0">
                                        <span class="input-group-text bg-light text-muted fw-medium">VNĐ</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-medium">Mô tả chi tiết</label>
                                    <textarea name="description" id="description" class="form-control" style="height: 150px" placeholder="Viết vài dòng hấp dẫn về tour..."></textarea>
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
                                <label class="form-label text-muted fw-medium">Ảnh đại diện</label>
                                <div class="main-image-upload border rounded bg-light p-4 text-center cursor-pointer hover-bg-light" style="border-style: dashed !important; border-width: 2px !important; border-color: var(--border-color) !important;">
                                    <div class="upload-area" id="mainImageDropZone">
                                        <i class="ph-fill ph-cloud-arrow-up text-primary mb-2" style="font-size: 3rem;"></i>
                                        <p class="mb-1 fw-medium">Kéo thả hoặc click để chọn ảnh đại diện</p>
                                        <span class="text-muted small">JPG, PNG, WEBP. Tối đa 5MB</span>
                                        <input type="file" name="main_image" id="main_image" accept="image/*" class="d-none">
                                    </div>
                                    <div class="main-image-preview position-relative mx-auto mt-3" id="mainImagePreview" style="display: none; max-width: 300px;">
                                        <img src="" alt="Main Image Preview" class="img-fluid rounded shadow-sm w-100 object-fit-cover" style="height: 200px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="removeMainImage()">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label class="form-label text-muted fw-medium">Thư viện ảnh</label>
                                <div class="gallery-upload-zone border rounded bg-light p-4 text-center cursor-pointer hover-bg-light" style="border-style: dashed !important; border-width: 2px !important; border-color: var(--border-color) !important;">
                                    <div class="upload-area" id="galleryDropZone" onclick="document.getElementById('gallery_images').click()">
                                        <i class="ph-fill ph-images text-primary mb-2" style="font-size: 3rem;"></i>
                                        <p class="mb-1 fw-medium">Kéo thả hoặc click để chọn nhiều ảnh</p>
                                        <span class="text-muted small">JPG, PNG, WEBP. Tối đa 5MB mỗi ảnh</span>
                                        <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*" class="d-none">
                                    </div>
                                    <div class="gallery-preview-grid mt-4 d-flex flex-wrap gap-2 justify-content-center" id="galleryPreview">
                                        <!-- Gallery previews will appear here -->
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
                            <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm" onclick="addItineraryDay()">
                                <i class="ph ph-plus"></i> Thêm ngày
                            </button>
                        </div>
                        <div class="p-4">
                            <div id="itinerary-list" class="itinerary-list d-flex flex-column gap-3">
                                <!-- Itinerary items will be added here dynamically -->
                            </div>
                            <div class="text-center text-muted py-5 bg-light rounded" id="itinerary-empty" style="border: 2px dashed var(--border-color);">
                                <i class="ph-fill ph-calendar-blank text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                <h6 class="fw-bold text-dark">Chưa có lịch trình nào</h6>
                                <p class="small">Thêm các ngày tương ứng cho lộ trình.</p>
                                <button type="button" class="btn btn-outline-primary" onclick="addItineraryDay()">
                                    <i class="ph ph-plus me-1"></i> Thêm ngày đầu tiên
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Final Details -->
                <div class="form-step d-none" id="step-4">
                    <!-- Partners -->
                    <div class="card-premium border-0 shadow-sm bg-white mb-4">
                        <div class="p-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-info d-flex align-items-center gap-2">
                                <i class="ph-fill ph-handshake"></i> Đối tác dịch vụ
                            </h6>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="supplier-select" style="width: 250px;">
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php if (!empty($suppliers)): ?>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?= $supplier['id'] ?>"
                                                data-name="<?= htmlspecialchars($supplier['name']) ?>"
                                                data-type="<?= $supplier['type'] ?? '' ?>"
                                                data-contact="<?= htmlspecialchars($supplier['phone'] ?? $supplier['email'] ?? '') ?>">
                                                <?= htmlspecialchars($supplier['name']) ?> (<?= strtoupper($supplier['type'] ?? '') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="addPartnerFromSupplier()">
                                    <i class="ph ph-plus"></i> Thêm
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <!-- Partner Services List -->
                            <div id="partners-list" class="partners-list d-flex flex-column gap-3">
                                <!-- Partners will be added here dynamically -->
                            </div>
                            <div class="text-center text-muted py-5 bg-light rounded" id="partners-empty" style="border: 2px dashed var(--border-color);">
                                <i class="ph-fill ph-users text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                <h6 class="fw-bold text-dark">Chưa có đối tác (xe, khách sạn...)</h6>
                                <p class="small">Liệt kê các đối tác vệ tinh.</p>
                                <button type="button" class="btn btn-outline-primary" onclick="addPartner()">
                                    <i class="ph ph-plus me-1"></i> Thêm đối tác
                                </button>
                            </div>
                        </div>
                    </div>

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
                                                <input class="form-check-input mt-1" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>" id="policy_<?= $policy['id'] ?>" style="width: 1.25rem; height: 1.25rem;">
                                                <label class="form-check-label fw-bold ms-2 text-dark" for="policy_<?= $policy['id'] ?>">
                                                    <?= htmlspecialchars($policy['name']) ?>
                                                </label>
                                                <div class="small text-muted mt-2 ms-4"><?= htmlspecialchars($policy['description']) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4 bg-light rounded">
                                    <i class="ph-fill ph-shield-warning text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                    <p class="mb-0">Hệ thống chưa có chính sách nào được cấu hình</p>
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
                                <i class="ph ph-floppy-disk"></i> Lưu nháp
                            </button>
                            <button type="submit" form="tour-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 shadow-sm py-2">
                                <i class="ph-fill ph-check-circle"></i> Tạo Tour Ngay
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
                        <span class="fw-medium">Đã lưu tự động lúc <span class="fw-bold">--:--</span></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<style>
/* CSS for Form Steps since original tours.css was deleted */
.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 10%;
    right: 10%;
    height: 3px;
    background: var(--bg-hover);
    z-index: 1;
}
.step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    transition: all 0.3s;
}
.step .step-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    border: 3px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    background: var(--bg-body);
    transition: all 0.3s;
}
.step.active .step-icon, .step.completed .step-icon {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
    box-shadow: 0 0 0 4px var(--primary-subtle);
}
.step.active .step-label {
    color: var(--primary);
    font-weight: 600;
}
.step.completed .step-label {
    color: var(--text-main);
    font-weight: 500;
}

.cursor-pointer { cursor: pointer; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }
.hover-bg-white:hover { background-color: #ffffff !important; box-shadow: var(--shadow-sm); }
.transition-all { transition: all 0.2s ease-in-out; }
.form-step { display: none; }
.form-step.active { display: block; animation: fadeIn 0.3s ease-in-out; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.gallery-preview-grid img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; box-shadow: var(--shadow-sm); }
</style>

<!-- Templates -->
<template id="itinerary-template">
    <div class="itinerary-item border border-light rounded bg-light p-4 position-relative">
        <div class="position-absolute top-0 end-0 p-2">
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="removeItineraryItem(this)" style="width: 32px; height: 32px; padding:0;">
                <i class="ph ph-trash"></i>
            </button>
        </div>
        <h6 class="mb-3 fw-bold text-primary">Ngày <span class="day-number"></span></h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label text-muted fw-medium small">Tiêu đề</label>
                <input type="text" class="form-control itinerary-title" placeholder="VD: Khởi hành đi Đà Nẵng" required>
            </div>
            <div class="col-12">
                <label class="form-label text-muted fw-medium small">Mô tả chi tiết</label>
                <textarea class="form-control itinerary-description" style="height: 100px" placeholder="Lịch trình cụ thể..."></textarea>
            </div>
        </div>
    </div>
</template>

<template id="partner-template">
    <div class="partner-item border border-light rounded bg-light p-4 position-relative">
        <div class="position-absolute top-0 end-0 p-2">
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="removePartnerItem(this)" style="width: 32px; height: 32px; padding:0;">
                <i class="ph ph-trash"></i>
            </button>
        </div>
        <h6 class="mb-3 fw-bold text-info">Đối tác dịch vụ</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-muted fw-medium small">Loại dịch vụ</label>
                <select class="form-select partner-service-type" required>
                    <option value="">-- Loại dịch vụ --</option>
                    <option value="hotel">Khách sạn</option>
                    <option value="transport">Vận chuyển</option>
                    <option value="restaurant">Nhà hàng</option>
                    <option value="guide">Hướng dẫn viên</option>
                    <option value="other">Khác</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted fw-medium small">Tên đối tác</label>
                <input type="text" class="form-control partner-name" placeholder="Ví dụ: Khách sạn Mường Thanh..." required>
            </div>
            <div class="col-12">
                <label class="form-label text-muted fw-medium small">Thông tin liên hệ</label>
                <input type="text" class="form-control partner-contact" placeholder="SĐT, Email..." required>
            </div>
        </div>
    </div>
</template>

<script>
    // Include the original behavior logic but adapted to Bootstrap 5 + generic DOM
    let currentStep = 1;
    const totalSteps = 4;
    let itineraryCount = 0;
    let partnerCount = 0;

    document.addEventListener('DOMContentLoaded', function() {
        updateStepDisplay();
        updateNavigationButtons();

        // Setup Main Image Upload interaction
        document.getElementById('mainImageDropZone').addEventListener('click', () => {
             document.getElementById('main_image').click();
        });
        document.getElementById('main_image').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('mainImageDropZone').style.display = 'none';
                    document.getElementById('mainImagePreview').style.display = 'block';
                    document.getElementById('mainImagePreview').querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Setup Gallery Upload interaction
        document.getElementById('gallery_images').addEventListener('change', function(e) {
            const preview = document.getElementById('galleryPreview');
            preview.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    });

    function removeMainImage() {
        document.getElementById('main_image').value = '';
        document.getElementById('mainImageDropZone').style.display = 'block';
        document.getElementById('mainImagePreview').style.display = 'none';
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
        prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'block';
        }
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                showToast('Vui lòng điền đầy đủ thông tin bắt buộc', 'danger');
                return false;
            }
        }
        return true;
    }

    function showToast(message, type = 'success') {
        const div = document.createElement('div');
        div.style.cssText = 'position:fixed; top:20px; right:20px; z-index:9999';
        div.innerHTML = `<div class="alert alert-${type} shadow-lg py-3 px-4 fw-bold rounded-pill text-white" style="background-color: var(--bs-${type}); border: none;"><i class="ph-fill ph-warning-circle me-2"></i>${message}</div>`;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }

    function saveDraft() {
        updateItineraryData();
        updatePartnerData();
        showToast('Đã lưu bản nháp lúc ' + new Date().toLocaleTimeString(), 'success');
        document.getElementById('autoSaveIndicator').style.opacity = '1';
        document.querySelector('#autoSaveIndicator .fw-bold').textContent = new Date().toLocaleTimeString();
    }

    // Dynamic Lists Logic
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
                description: item.querySelector('.itinerary-description').value
            });
        });
        document.getElementById('tour_itinerary').value = JSON.stringify(arr);
    }

    function addPartner() {
        partnerCount++;
        const template = document.getElementById('partner-template');
        document.getElementById('partners-list').appendChild(template.content.cloneNode(true));
        document.getElementById('partners-empty').style.display = 'none';
        updatePartnerData();
    }
    function removePartnerItem(btn) {
        btn.closest('.partner-item').remove();
        partnerCount--;
        if (partnerCount === 0) document.getElementById('partners-empty').style.display = 'block';
        updatePartnerData();
    }
    function addPartnerFromSupplier() {
        const sel = document.getElementById('supplier-select');
        const opt = sel.options[sel.selectedIndex];
        if(!opt.value) return;
        addPartner();
        const items = document.querySelectorAll('.partner-item');
        const last = items[items.length - 1];
        last.querySelector('.partner-service-type').value = opt.dataset.type === 'transport' ? 'transport' : (opt.dataset.type === 'hotel' ? 'hotel' : 'restaurant');
        last.querySelector('.partner-name').value = opt.dataset.name;
        last.querySelector('.partner-contact').value = opt.dataset.contact;
        updatePartnerData();
    }
    function updatePartnerData() {
        const arr = [];
        document.querySelectorAll('.partner-item').forEach(item => {
            arr.push({
                service_type: item.querySelector('.partner-service-type').value,
                name: item.querySelector('.partner-name').value,
                contact: item.querySelector('.partner-contact').value
            });
        });
        document.getElementById('tour_partners').value = JSON.stringify(arr);
    }

    document.getElementById('tour-form').addEventListener('submit', function(e) {
        updateItineraryData();
        updatePartnerData();
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>