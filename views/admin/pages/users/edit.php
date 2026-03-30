<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
$currentUserId = $_SESSION['user']['user_id'] ?? null;
$isOwnProfile = ($user['user_id'] == $currentUserId);
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=users" class="text-muted text-decoration-none">Quản lý User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa người dùng</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 mt-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">Chỉnh sửa người dùng</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 border shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-x" style="font-size: 1.1rem;"></i> Hủy bỏ
            </a>
            <button type="submit" form="user-form" class="btn btn-dark d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
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
                    <span class="step-text fw-medium small">Đổi mật khẩu</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .progress-steps-modern .step.active .step-icon { background: var(--primary-color); color: white; }
        .progress-steps-modern .step.completed .step-icon { background: var(--success-color); color: white; }
        .progress-steps-modern .step.completed .step-icon::after { content: '\eab1'; font-family: "Phosphor"; }
    </style>

    <!-- User Form -->
    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=users/update" id="user-form">
        <input type="hidden" name="id" value="<?= $user['user_id'] ?>">

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
                                        <input type="text" class="form-control border-start-0 ps-0 shadow-none" id="full_name" name="full_name" required placeholder="VD: Nguyễn Văn A" value="<?= htmlspecialchars($user['full_name']) ?>" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-envelope-simple"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0 shadow-none" id="email" name="email" required placeholder="email@example.com" value="<?= htmlspecialchars($user['email']) ?>" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Số điện thoại</label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-phone"></i></span>
                                        <input type="tel" class="form-control border-start-0 ps-0 shadow-none" id="phone" name="phone" placeholder="090 123 4567" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Vai trò</label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-shield-check"></i></span>
                                        <select class="form-select border-start-0 ps-0 shadow-none" id="role" name="role" required disabled style="border-radius: 0 8px 8px 0; background-color: #f8f9fa;">
                                            <option value="customer" selected>Khách hàng (Mặc định)</option>
                                        </select>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0"><i class="ph ph-info me-1"></i> Vai trò người dùng này được cố định trong hệ thống.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Password Update -->
                <div class="form-step" id="step-2" style="display: none;">
                    <div class="card card-premium border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="ph ph-key me-2 text-warning"></i> Đổi mật khẩu
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert bg-blue-subtle border-0 d-flex align-items-start gap-3 p-3 mb-4" style="border-radius: 12px; background: #eef2ff;">
                                <i class="ph-fill ph-info text-primary fs-4"></i>
                                <div class="small text-dark fw-medium">
                                    Chỉ điền thông tin bên dưới nếu bạn muốn thay đổi mật khẩu hiện tại. Để trống nếu muốn giữ nguyên.
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Mật khẩu mới</label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0 shadow-none" id="password" name="password" placeholder="••••••••" minlength="6" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Tối thiểu 6 ký tự.</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Xác nhận mật khẩu</label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-lock-key"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0 shadow-none" id="password_confirm" name="password_confirm" placeholder="••••••••" minlength="6" style="border-radius: 0 8px 8px 0;">
                                    </div>
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
                            <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm" style="border-radius: 10px;">
                                <i class="ph ph-floppy-disk fs-5"></i> <span class="fw-bold">Cập nhật User</span>
                            </button>
                            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-sm" style="border-radius: 10px;">
                                <i class="ph ph-arrow-left fs-5"></i> Quay lại
                            </a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mb-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 px-3 shadow-none border" onclick="previousStep()" id="prev-btn" style="display: none; border-radius: 8px;">
                                <i class="ph ph-caret-left"></i> Bước trước
                            </button>
                            <div class="flex-grow-1"></div>
                            <button type="button" class="btn btn-sm btn-dark d-flex align-items-center gap-2 px-3 shadow-sm" onclick="nextStep()" id="next-btn" style="border-radius: 8px;">
                                Tiếp theo <i class="ph ph-caret-right"></i>
                            </button>
                        </div>

                        <div class="bg-light p-3 rounded-3">
                            <h6 class="fw-bold text-dark small mb-3"><i class="ph ph-info me-1"></i> Thông tin hệ thống</h6>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">ID User:</span>
                                    <span class="badge bg-white text-dark border fw-bold">#<?= $user['user_id'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Ngày tạo:</span>
                                    <span class="fw-medium small"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                                </div>
                                <?php if (!empty($user['updated_at'])): ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Cập nhật:</span>
                                        <span class="fw-medium small"><?= date('d/m/Y', strtotime($user['updated_at'])) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</main>

    let currentStep = 1;
    const totalSteps = 2;

    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupPasswordValidation();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();
    }

    function setupPasswordValidation() {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');

        if (password && passwordConfirm) {
            passwordConfirm.addEventListener('input', function() {
                if (password.value && password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Mật khẩu không khớp');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
            });

            password.addEventListener('input', function() {
                if (this.value && passwordConfirm.value && this.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Mật khẩu không khớp');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
            });
        }
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
            updateNavigationButtons();
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
        if (prevBtn) prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        if (nextBtn) nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
    }

<style>
    .info-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>