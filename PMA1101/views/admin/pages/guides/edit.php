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
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=guides" class="text-muted text-decoration-none">Quản lý HDV</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa HDV</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 mt-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">Chỉnh sửa hướng dẫn viên</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=guides" class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 border shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-x" style="font-size: 1.1rem;"></i> Hủy bỏ
            </a>
            <a href="<?= BASE_URL_ADMIN ?>&action=guides/detail&id=<?= $guide['id'] ?>" class="btn btn-outline-info d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-eye" style="font-size: 1.1rem;"></i> Xem chi tiết
            </a>
            <button type="submit" form="guide-edit-form" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-floppy-disk" style="font-size: 1.1rem;"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-warning-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-success-subtle text-success border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-check-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Progress Steps -->
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-center align-items-center gap-4 progress-steps-modern">
                <div class="step active d-flex align-items-center gap-2" data-step="1" style="cursor: pointer;">
                    <div class="step-icon rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">1</div>
                    <span class="step-text fw-bold small">Thông tin cơ bản</span>
                </div>
                <div class="step-line bg-light flex-grow-0" style="width: 40px; height: 2px;"></div>
                <div class="step d-flex align-items-center gap-2 text-muted" data-step="2" style="cursor: pointer;">
                    <div class="step-icon rounded-circle d-flex align-items-center justify-content-center fw-bold bg-light" style="width: 32px; height: 32px; font-size: 0.85rem;">2</div>
                    <span class="step-text fw-medium small">Chuyên môn</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .progress-steps-modern .step.active .step-icon { background: var(--primary-color); color: white; }
        .progress-steps-modern .step.completed .step-icon { background: var(--success-color); color: white; }
        .progress-steps-modern .step.completed .step-icon::after { content: '\eab1'; font-family: "Phosphor"; }
    </style>

        <!-- Guide Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=guides/update" enctype="multipart/form-data" id="guide-edit-form">
            <input type="hidden" name="id" value="<?= $guide['id'] ?>">

            <div class="row g-4">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step-1">
                        <div class="card card-premium border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="ph ph-user me-2 text-primary"></i> Thông tin cơ bản
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Họ và tên <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm shadow-none">
                                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-user-circle"></i></span>
                                            <input type="text" class="form-control border-start-0 ps-0 shadow-none" id="full_name" name="full_name"
                                                value="<?= htmlspecialchars($guide['full_name'] ?? '') ?>" required placeholder="VD: Nguyễn Văn A" style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm shadow-none">
                                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-envelope-simple"></i></span>
                                            <input type="email" class="form-control border-start-0 ps-0 shadow-none" id="email" name="email"
                                                value="<?= htmlspecialchars($guide['email'] ?? '') ?>" required placeholder="email@example.com" style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Số điện thoại <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm shadow-none">
                                            <span class="input-group-text bg-light border-end-0"><i class="ph ph-phone"></i></span>
                                            <input type="tel" class="form-control border-start-0 ps-0 shadow-none" id="phone" name="phone"
                                                value="<?= htmlspecialchars($guide['phone'] ?? '') ?>" required placeholder="090 123 4567" style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div class="card card-premium border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="ph ph-image me-2 text-success"></i> Ảnh đại diện
                                </h6>
                            </div>
                            <div class="card-body p-4 text-center">
                                <div class="main-image-upload">
                                    <?php if (!empty($guide['avatar'])): ?>
                                        <div class="main-image-preview" id="avatarPreview">
                                            <div class="position-relative d-inline-block">
                                                <img src="<?= htmlspecialchars($guide['avatar']) ?>" alt="Avatar" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px; border: 4px solid white;">
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle shadow-sm" onclick="removeAvatar()" style="width: 28px; height: 28px; padding: 0;">
                                                    <i class="ph-bold ph-x"></i>
                                                </button>
                                            </div>
                                            <p class="text-muted small mt-2">Click vào dấu X để xóa và tải ảnh mới</p>
                                        </div>
                                        <div class="upload-area p-5 border-2 border-dashed rounded-4 bg-light bg-opacity-50 transition" id="avatarDropZone" onclick="document.getElementById('avatar').click()" style="display: none; cursor: pointer; border-style: dashed !important;">
                                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                                <i class="ph ph-cloud-arrow-up fs-2"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Tải ảnh mới</h6>
                                            <p class="text-muted small mb-0">Hoặc kéo thả vào đây</p>
                                            <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
                                        </div>
                                    <?php else: ?>
                                        <div class="upload-area p-5 border-2 border-dashed rounded-4 bg-light bg-opacity-50 transition" id="avatarDropZone" onclick="document.getElementById('avatar').click()" style="cursor: pointer; border-style: dashed !important;">
                                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                                <i class="ph ph-cloud-arrow-up fs-2"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Tải ảnh lên hoặc kéo thả</h6>
                                            <p class="text-muted small mb-0">JPG, PNG, GIF. Tối đa 5MB</p>
                                            <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
                                        </div>
                                        <div class="main-image-preview mt-3" id="avatarPreview" style="display: none;">
                                            <div class="position-relative d-inline-block">
                                                <img src="" alt="Avatar Preview" class="img-fluid rounded-4 shadow-sm" style="max-height: 200px; border: 4px solid white;">
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle shadow-sm" onclick="removeAvatar()" style="width: 28px; height: 28px; padding: 0;">
                                                    <i class="ph-bold ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Professional Information -->
                    <div class="form-step" id="step-2" style="display: none;">
                        <div class="card card-premium border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="ph ph-briefcase me-2 text-warning"></i> Thông tin chuyên môn
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Loại hướng dẫn viên</label>
                                        <select class="form-select form-select-sm shadow-none" id="guide_type" name="guide_type" style="border-radius: 8px;">
                                            <option value="domestic" <?= ($guide['guide_type'] ?? '') == 'domestic' ? 'selected' : '' ?>>Nội địa</option>
                                            <option value="international" <?= ($guide['guide_type'] ?? '') == 'international' ? 'selected' : '' ?>>Quốc tế</option>
                                            <option value="specialized" <?= ($guide['guide_type'] ?? '') == 'specialized' ? 'selected' : '' ?>>Chuyên môn</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Chuyên môn</label>
                                        <input type="text" class="form-control form-control-sm shadow-none" id="specialization" name="specialization"
                                            value="<?= htmlspecialchars($guide['specialization'] ?? '') ?>" placeholder="VD: Khách Âu Mỹ..." style="border-radius: 8px;">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Ngôn ngữ</label>
                                        <input type="text" class="form-control form-control-sm shadow-none" id="languages" name="languages"
                                            value="<?= htmlspecialchars($guide['languages'] ?? '') ?>" placeholder="VD: Tiếng Anh, Tiếng Pháp..." style="border-radius: 8px;">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Kinh nghiệm (năm)</label>
                                        <input type="number" class="form-control form-control-sm shadow-none" id="experience_years" name="experience_years"
                                            value="<?= $guide['experience_years'] ?? 0 ?>" min="0" style="border-radius: 8px;">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Sức khỏe</label>
                                        <select class="form-select form-select-sm shadow-none" id="health_status" name="health_status" style="border-radius: 8px;">
                                            <option value="">-- Chọn --</option>
                                            <option value="Tốt" <?= ($guide['health_status'] ?? '') == 'Tốt' ? 'selected' : '' ?>>Tốt</option>
                                            <option value="Khá" <?= ($guide['health_status'] ?? '') == 'Khá' ? 'selected' : '' ?>>Khá</option>
                                            <option value="Trung bình" <?= ($guide['health_status'] ?? '') == 'Trung bình' ? 'selected' : '' ?>>Trung bình</option>
                                            <option value="Cần theo dõi" <?= ($guide['health_status'] ?? '') == 'Cần theo dõi' ? 'selected' : '' ?>>Cần theo dõi</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Ghi chú bổ sung</label>
                                        <textarea class="form-control shadow-none" id="notes" name="notes" style="height: 100px; border-radius: 8px;" placeholder="Thông tin khác..."><?= htmlspecialchars($guide['notes'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card card-premium border-0 shadow-sm sticky-top" style="top: 1.5rem; z-index: 10;">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">Thao tác</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" form="guide-edit-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm" style="border-radius: 10px;">
                                    <i class="ph ph-floppy-disk fs-5"></i> <span class="fw-bold">Lưu thay đổi</span>
                                </button>
                                <a href="<?= BASE_URL_ADMIN ?>&action=guides/detail&id=<?= $guide['id'] ?>" class="btn btn-outline-info d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm" style="border-radius: 10px;">
                                    <i class="ph ph-eye fs-5"></i> Xem hồ sơ
                                </a>
                                <a href="<?= BASE_URL_ADMIN ?>&action=guides" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-sm" style="border-radius: 10px;">
                                    <i class="ph ph-arrow-left fs-5"></i> Quay lại
                                </a>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 px-3 shadow-none border" onclick="previousStep()" id="prev-btn" style="display: none; border-radius: 8px;">
                                    <i class="ph ph-caret-left"></i> Bước trước
                                </button>
                                <div class="flex-grow-1"></div>
                                <button type="button" class="btn btn-sm btn-dark d-flex align-items-center gap-2 px-3 shadow-sm" onclick="nextStep()" id="next-btn" style="border-radius: 8px;">
                                    Tiếp theo <i class="ph ph-caret-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let currentStep = 1;
    const totalSteps = 2;

    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupAvatarUpload();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();
    }

    function setupAvatarUpload() {
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarDropZone').style.display = 'none';
                    const preview = document.getElementById('avatarPreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function removeAvatar() {
        document.getElementById('avatar').value = '';
        document.getElementById('avatarDropZone').style.display = 'flex';
        document.getElementById('avatarPreview').style.display = 'none';
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
            const stepIcon = step.querySelector('.step-icon');
            
            if (stepNum === currentStep) {
                step.classList.add('active');
                step.classList.remove('text-muted');
                if (stepIcon) stepIcon.innerHTML = stepNum;
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
                step.classList.remove('text-muted');
                if (stepIcon) stepIcon.innerHTML = '<i class="ph ph-check"></i>';
            } else {
                step.classList.add('text-muted');
                if (stepIcon) stepIcon.innerHTML = stepNum;
            }
        });

        document.querySelectorAll('.form-step').forEach(step => {
            step.style.display = 'none';
            step.classList.remove('active');
        });
        const currentStepEl = document.getElementById(`step-${currentStep}`);
        if (currentStepEl) {
            currentStepEl.style.display = 'block';
            currentStepEl.classList.add('active');
        }
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');

        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                return false;
            }
        }
        return true;
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>