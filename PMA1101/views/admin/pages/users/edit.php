<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
$currentUserId = $_SESSION['user']['user_id'] ?? null;
?>

<main class="content user-edit-page">
    <!-- Synchronized Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=users" class="text-muted text-decoration-none">Thành viên</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Chỉnh sửa hồ sơ</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center gap-2 px-4 py-2 border shadow-sm transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600;">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
            <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-primary transition-all hover-translate-y" style="border-radius: 12px; font-weight: 600;">
                <i class="ph-bold ph-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4 rounded-4 animate__animated animate__shakeX">
            <i class="ph-fill ph-warning-circle fs-4"></i>
            <div class="fw-medium small"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=users/update" id="user-form">
        <input type="hidden" name="id" value="<?= $user['user_id'] ?>">
        
        <div class="row g-4">
            <!-- Main Content Column -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card card-premium border-0 shadow-sm mb-4 rounded-4 overflow-hidden" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-identification-card text-primary fs-4"></i> Thông tin cơ bản
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-4 mb-4 pb-2">
                            <div class="position-relative">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=4f46e5&color=fff&size=100&font-size=0.4&rounded=true&bold=true" 
                                     class="rounded-circle shadow-sm border border-4 border-light" alt="avatar" style="width: 80px; height: 80px;">
                                <div class="position-absolute bottom-0 end-0 bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border: 2px solid #fff;">
                                    <i class="ph-fill ph-camera text-primary small"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($user['full_name']) ?></h6>
                                <p class="text-muted small mb-0">ID User: #<?= $user['user_id'] ?> • Thành viên từ <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-floating form-floating-premium">
                                    <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Nguyen Van A" value="<?= htmlspecialchars($user['full_name']) ?>">
                                    <label for="full_name"><i class="ph ph-user-circle me-1"></i> Họ và tên khách hàng <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com" value="<?= htmlspecialchars($user['email']) ?>">
                                    <label for="email"><i class="ph ph-envelope me-1"></i> Địa chỉ Email <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="090..." value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                    <label for="phone"><i class="ph ph-phone me-1"></i> Số điện thoại</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="card card-premium border-0 shadow-sm mb-4 rounded-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
                    <div class="card-header bg-white border-bottom border-light py-4 px-4">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="ph-bold ph-lock-key-open text-warning fs-4"></i> Bảo mật tài khoản
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••">
                                    <label for="password"><i class="ph ph-lock me-1"></i> Mật khẩu mới</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-premium">
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="••••••••">
                                    <label for="password_confirm"><i class="ph ph-shield-check me-1"></i> Xác nhận mật khẩu</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 rounded-4 bg-light border border-light-subtle">
                            <div class="d-flex gap-3">
                                <div class="bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <i class="ph-fill ph-info text-primary"></i>
                                </div>
                                <p class="small text-secondary mb-0 line-height-15">
                                    Chỉ điền mật khẩu nếu bạn muốn thay đổi truy cập cho người dùng này. Để đảm bảo an toàn, mật khẩu mới nên có từ <strong>8 ký tự trở lên</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 1.5rem; z-index: 10;">
                    <div class="card card-premium border-0 shadow-sm mb-4 rounded-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
                        <div class="card-body p-4 text-center">
                            <h6 class="fw-bold text-dark mb-4 border-bottom pb-3">Phân quyền hệ thống</h6>
                            
                            <div class="bg-primary bg-opacity-10 text-primary py-3 rounded-4 mb-4">
                                <i class="ph-bold ph-user-circle fs-1 mb-2"></i>
                                <div class="fw-bold text-uppercase" style="letter-spacing: 1px;">Khách hàng</div>
                            </div>
                            
                            <input type="hidden" name="role" value="<?= $user['role'] ?>">
                            
                            <div class="d-grid gap-2">
                                <button type="submit" form="user-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3 shadow-primary transition-all hover-translate-y" style="border-radius: 14px; font-weight: 700;">
                                    <i class="ph-bold ph-check-circle fs-5"></i> CẬP NHẬT NGAY
                                </button>
                                <a href="<?= BASE_URL_ADMIN ?>&action=users" class="btn btn-light d-flex align-items-center justify-content-center gap-2 py-2 border shadow-none" style="border-radius: 12px; font-weight: 600; color: #64748b;">
                                    Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card card-premium border-0 shadow-sm rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0 !important;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="ph-bold ph-shield-check text-success me-1"></i> Lưu ý an toàn</h6>
                            <ul class="ps-3 mb-0 text-secondary small d-flex flex-column gap-2 opacity-75">
                                <li>Email phải là <strong>duy nhất</strong> trong hệ thống.</li>
                                <li>Dữ liệu cá nhân tuân thủ chính sách bảo mật SaaS.</li>
                                <li>Mọi hành động cập nhật đều được ghi lại lịch sử.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<style>
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
    .line-height-15 { line-height: 1.55; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple password match logic
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        
        if(password && passwordConfirm) {
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
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>