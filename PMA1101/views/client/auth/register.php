<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<style>
    /* Ẩn Navbar và Footer mặc định */
    #mainNav, .premium-footer, .navbar-spacer, .scroll-top-btn { 
        display: none !important; 
    }
    
    body {
        background-color: #f0f2f5;
        overflow-x: hidden;
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: stretch;
        flex-direction: row-reverse; /* Đảo ngược để ảnh bên phải, form bên trái */
    }

    /* Phía ảnh nền */
    .auth-bg-section {
        flex: 1;
        position: relative;
        background-image: url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        display: none;
    }

    @media (min-width: 992px) {
        .auth-bg-section {
            display: block;
        }
    }

    .auth-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%);
        z-index: 1;
    }

    .auth-bg-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        color: white;
        text-align: right;
    }

    /* Phía Form */
    .auth-form-section {
        width: 100%;
        max-width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: white;
    }

    @media (min-width: 992px) {
        .auth-form-section {
            width: 550px;
            max-width: 550px;
        }
    }

    .auth-card {
        width: 100%;
        max-width: 450px;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        background-color: #f8f9fa;
        transition: all 0.2s;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(0, 140, 114, 0.1);
    }

    .btn-auth {
        padding: 0.8rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 800;
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Animation */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="auth-page">
    <!-- Phía Ảnh nền -->
    <div class="auth-bg-section">
        <div class="auth-bg-overlay"></div>
        <div class="auth-bg-content">
            <h1 class="display-5 fw-bold text-white mb-4" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Mở cửa thế giới cùng chúng tôi</h1>
            <p class="lead opacity-85">Gia nhập cộng đồng hơn 10,000 khách du lịch thông thái và nhận ngay các ưu đãi đặc quyền.</p>
            <div class="mt-5 d-flex flex-column align-items-end gap-3 text-white-50">
                <div class="d-flex align-items-center gap-2">
                    <span>Cập nhật xu hướng du lịch mới nhất</span>
                    <i class="ph ph-trend-up fs-4"></i>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span>Tích lũy điểm thưởng khi đặt tour</span>
                    <i class="ph ph-gift fs-4"></i>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span>Ưu tiên hỗ trợ từ chuyên gia</span>
                    <i class="ph ph-headset fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Phía Form đăng ký -->
    <div class="auth-form-section">
        <div class="auth-card fade-in-up">
            <a href="<?= BASE_URL ?>" class="brand-logo">
                <i class="ph-fill ph-paper-plane-tilt"></i>
                <span>AgileTravel</span>
            </a>

            <div class="mb-4">
                <h2 class="fw-bold text-dark">Tạo tài khoản mới</h2>
                <p class="text-muted">Chỉ mất vài giây để bắt đầu hành trình của bạn.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 rounded-3 small py-2 mb-4">
                    <i class="ph-bold ph-warning-circle me-2"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>?action=register-submit" method="POST" id="registerForm">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Họ và tên</label>
                    <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-dark">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-dark">Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control" placeholder="0123 456 789" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Xác nhận</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small text-muted" for="terms">
                        Tôi đồng ý với các <a href="#" class="text-primary text-decoration-none">điều khoản và chính sách</a>.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-auth w-100 mb-4 shadow-none">
                    Đăng Ký Tài Khoản
                </button>

                <div class="text-center">
                    <p class="small text-muted">
                        Đã có tài khoản? 
                        <a href="<?= BASE_URL ?>?action=login" class="text-primary fw-bold text-decoration-none">Đăng nhập</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Kiểm tra mật khẩu khớp trực quan
    const form = document.getElementById('registerForm');
    const pwd = document.getElementById('password');
    const confirm = document.getElementById('password_confirm');

    form.addEventListener('submit', function(e) {
        if (pwd.value !== confirm.value) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp! Vui lòng kiểm tra lại.');
            confirm.classList.add('is-invalid');
        }
    });

    [pwd, confirm].forEach(el => {
        el.addEventListener('input', () => {
            confirm.classList.remove('is-invalid');
        });
    });
</script>

<?php // include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>