<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=users" class="text-muted text-decoration-none">Quản lý User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm User mới</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 mt-1" style="font-size: 1.25rem; letter-spacing: -0.5px;">Thêm người dùng</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 border shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-x" style="font-size: 1.1rem;"></i> Hủy bỏ
            </a>
            <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-floppy-disk" style="font-size: 1.1rem;"></i> Lưu người dùng
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
                    <span class="step-text fw-medium small">Mật khẩu</span>
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
    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=users/store" id="user-form">
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
                                        <input type="text" class="form-control border-start-0 ps-0 shadow-none" id="full_name" name="full_name" required placeholder="VD: Nguyễn Văn A" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-envelope-simple"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0 shadow-none" id="email" name="email" required placeholder="email@example.com" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Số điện thoại</label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-phone"></i></span>
                                        <input type="tel" class="form-control border-start-0 ps-0 shadow-none" id="phone" name="phone" placeholder="090 123 4567" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>

                                <!-- Hidden role field - automatically set to customer -->
                                <input type="hidden" name="role" value="customer">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Password -->
                <div class="form-step" id="step-2" style="display: none;">
                    <div class="card card-premium border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="ph ph-key me-2 text-warning"></i> Cài đặt mật khẩu
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-lock"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0 shadow-none" id="password" name="password" required placeholder="••••••••" minlength="6" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Tối thiểu 6 ký tự để đảm bảo an toàn.</p>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm shadow-none">
                                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-lock-key"></i></span>
                                        <input type="password" class="form-control border-start-0 ps-0 shadow-none" id="password_confirm" name="password_confirm" required placeholder="••••••••" minlength="6" style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>
                            </div>

                            <div class="alert bg-blue-subtle border-0 d-flex align-items-start gap-3 p-3 mt-4" style="border-radius: 12px; background: #eef2ff;">
                                <i class="ph-fill ph-info text-primary fs-4"></i>
                                <div class="small text-dark fw-medium">
                                    Người dùng có thể chủ động thay đổi mật khẩu sau khi đăng nhập lần đầu vào hệ thống.
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
                                <i class="ph ph-floppy-disk fs-5"></i> <span class="fw-bold">Tạo người dùng</span>
                            </button>
                            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-sm" style="border-radius: 10px;">
                                <i class="ph ph-x fs-5"></i> Hủy
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
                            <h6 class="fw-bold text-dark small mb-2"><i class="ph ph-lightbulb me-1"></i> Hướng dẫn</h6>
                            <ul class="list-unstyled mb-0 small text-muted d-flex flex-column gap-2">
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <i class="ph ph-check-circle text-success fs-5 mt-0.5"></i> Điền đầy đủ thông tin bắt buộc
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <i class="ph ph-check-circle text-success fs-5 mt-0.5"></i> Email phải là duy nhất
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <i class="ph ph-check-circle text-success fs-5 mt-0.5"></i> Mật khẩu tối thiểu 6 ký tự
                                </li>
                            </ul>
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
        setupPasswordValidation();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();
    }

    function setupPasswordValidation() {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');

        passwordConfirm.addEventListener('input', function() {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Mật khẩu không khớp');
            } else {
                passwordConfirm.setCustomValidity('');
            }
        });
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