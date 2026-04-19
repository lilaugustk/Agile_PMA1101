<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
?>

<main class="content user-create-page">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=users" class="text-muted text-decoration-none">Thành viên</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Thêm mới</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Tạo tài khoản khách hàng</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center gap-2 px-4 py-2 border shadow-sm transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
            <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-primary transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600;">
                <i class="ph-bold ph-check-circle"></i> Lưu thông tin
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4 animate__animated animate__shakeX" style="border-radius: 16px;">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">
                <i class="ph-bold ph-warning"></i>
            </div>
            <div class="fw-medium"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=users/store" id="user-form">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Profile Section -->
                <div class="card card-premium border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                <i class="ph-bold ph-identification-card fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Thông tin hồ sơ</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Visual Identity / Avatar Placeholder -->
                        <div class="d-flex align-items-center gap-4 mb-4 pb-2">
                            <div class="position-relative">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border-2 border-dashed border-primary border-opacity-25" style="width: 80px; height: 80px;">
                                    <i class="ph ph-user fs-1 text-muted"></i>
                                </div>
                                <div class="position-absolute bottom-0 end-0 bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; margin-right: -4px; margin-bottom: -4px;">
                                    <i class="ph-fill ph-camera text-primary small"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Ảnh đại diện</h6>
                                <p class="text-muted small mb-0">Hệ thống sẽ tự động tạo avatar từ tên gọi nếu bỏ trống</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-12">
                                <div class="form-floating form-floating-premium">
                                    <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Nguyen Van A">
                                    <label for="full_name"><i class="ph ph-user-circle me-1"></i> Họ và tên khách hàng <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <!-- Email & Phone -->
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com">
                                    <label for="email"><i class="ph ph-envelope me-1"></i> Địa chỉ Email <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="090...">
                                    <label for="phone"><i class="ph ph-phone me-1"></i> Số điện thoại</label>
                                </div>
                            </div>

                            <input type="hidden" name="role" value="customer">
                        </div>
                    </div>
                </div>

                <!-- Credential Section -->
                <div class="card card-premium border-0 shadow-sm mb-4">
                    <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                                <i class="ph-bold ph-key fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Bảo mật tài khoản</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                                    <label for="password"><i class="ph ph-lock me-1"></i> Mật khẩu <span class="text-danger">*</span></label>
                                </div>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 4px; border-radius: 10px;">
                                        <div id="password-strength-bar" class="progress-bar bg-muted" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <span id="password-strength-text" class="text-muted" style="font-size: 0.75rem;">Yếu</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required placeholder="••••••••">
                                    <label for="password_confirm"><i class="ph ph-shield-check me-1"></i> Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex gap-3">
                                <div class="bg-white shadow-sm p-2 rounded-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <i class="ph-fill ph-info text-info"></i>
                                </div>
                                <p class="small text-secondary mb-0 line-height-15">
                                    Hệ thống sử dụng cơ chế bảo mật đa tầng. Vui lòng nhắc nhở khách hàng <strong>thay đổi mật khẩu</strong> định kỳ hoặc sau lần đăng nhập đầu tiên để đảm bảo an toàn tối đa cho tài khoản.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 1.5rem; z-index: 10;">
                    <!-- Action Card -->
                    <div class="card card-premium border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">Trạng thái tài khoản</h6>
                            
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                    <span class="fw-600 text-dark small">Tự động kích hoạt</span>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3 shadow-primary transition-all hover-translate-y" style="border-radius: 14px; font-weight: 700;">
                                    <i class="ph-fill ph-user-plus fs-5"></i> HOÀN TẤT ĐĂNG KÝ
                                </button>
                                <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-sm" style="border-radius: 12px; font-weight: 600; color: #64748b;">
                                    Hủy bỏ thao tác
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Guidelines Card -->
                    <div class="card card-premium border-0 shadow-sm" style="background: #fdfdfd; border: 1px solid #e2e8f0 !important; border-radius: 16px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                                <i class="ph-bold ph-lightbulb text-warning fs-5"></i> Quy tắc hệ thống
                            </h6>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 mt-0.5">
                                        <i class="ph-bold ph-fingerprint text-primary"></i>
                                    </div>
                                    <div class="small text-secondary" style="line-height: 1.5;">
                                        Mỗi địa chỉ Email chỉ được đăng ký cho <span class="fw-bold text-dark">một tài khoản duy nhất</span>.
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 mt-0.5">
                                        <i class="ph-bold ph-shield-star text-primary"></i>
                                    </div>
                                    <div class="small text-secondary" style="line-height: 1.5;">
                                        Mật khẩu lý tưởng nên có từ <span class="fw-bold text-dark">8 ký tự trở lên</span>, bao gồm cả chữ và số.
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 mt-0.5">
                                        <i class="ph-bold ph-bell-ringing text-primary"></i>
                                    </div>
                                    <div class="small text-secondary" style="line-height: 1.5;">
                                        Khách hàng sẽ nhận được <span class="fw-bold text-dark">thông báo tự động</span> sau khi tài khoản được tạo.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<!-- CSS for modern effects -->
<style>
    .form-floating-premium {
        position: relative;
    }
    .form-floating-premium .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
        height: auto;
        min-height: 58px;
        font-weight: 600;
        color: #1e293b;
        background-color: #fcfcfc;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s ease-in-out;
    }
    .form-floating-premium .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
        background-color: #fff;
    }
    .form-floating-premium label {
        padding-left: 1.2rem;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        opacity: 0.8;
        background-color: transparent !important;
        height: auto !important;
        padding-top: 1rem !important;
    }
    .form-floating-premium .form-control:focus ~ label,
    .form-floating-premium .form-control:not(:placeholder-shown) ~ label {
        color: var(--primary-color);
        transform: scale(0.85) translateY(-0.85rem) translateX(0.15rem);
        opacity: 1;
    }
    
    .hover-translate-y:hover {
        transform: translateY(-2px);
    }
    
    .line-height-15 {
        line-height: 1.55;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordLogic();
        setupVisualInteractions();
    });

    function setupPasswordLogic() {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const bar = document.getElementById('password-strength-bar');
        const text = document.getElementById('password-strength-text');

        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 6) strength += 30;
            if (val.length >= 8) strength += 20;
            if (/[0-9]/.test(val)) strength += 25;
            if (/[A-Z]/.test(val)) strength += 25;

            bar.style.width = strength + '%';
            if (strength < 40) {
                bar.className = 'progress-bar bg-danger';
                text.textContent = 'Yếu';
                text.className = 'text-danger small';
            } else if (strength < 80) {
                bar.className = 'progress-bar bg-warning';
                text.textContent = 'Trung bình';
                text.className = 'text-warning small';
            } else {
                bar.className = 'progress-bar bg-success';
                text.textContent = 'Mạnh';
                text.className = 'text-success small';
            }
        });

        passwordConfirm.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.classList.add('is-invalid');
                this.style.borderColor = '#ef4444';
            } else {
                this.classList.remove('is-invalid');
                this.style.borderColor = '#e2e8f0';
            }
        });
    }

    function setupVisualInteractions() {
        // Simple input focus effects beyond CSS if needed
        const controls = document.querySelectorAll('.form-control');
        controls.forEach(control => {
            control.addEventListener('focus', function() {
                const card = this.closest('.card-premium');
                if (card) card.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)';
            });
            control.addEventListener('blur', function() {
                const card = this.closest('.card-premium');
                if (card) card.style.boxShadow = 'none';
            });
        });
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>