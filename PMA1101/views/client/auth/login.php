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
    }

    /* Phía ảnh nền */
    .auth-bg-section {
        flex: 1;
        position: relative;
        background-image: url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=2073&auto=format&fit=crop');
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
            width: 480px;
            max-width: 480px;
        }
    }

    .auth-card {
        width: 100%;
        max-width: 400px;
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

    .social-btn {
        border: 1px solid #e1e5eb;
        border-radius: 10px;
        padding: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #4b5563;
        font-weight: 500;
        transition: all 0.2s;
    }

    .social-btn:hover {
        background-color: #f8f9fa;
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
    <!-- Phía Ảnh nền (Chỉ hiện trên desktop) -->
    <div class="auth-bg-section">
        <div class="auth-bg-overlay"></div>
        <div class="auth-bg-content">
            <h1 class="display-5 fw-bold text-white mb-4" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Khám phá thế giới cùng AgileTravel</h1>
            <p class="lead opacity-75">Đăng nhập để quản lý các chuyến đi của bạn và nhận những ưu đãi đặc quyền cho thành viên.</p>
            <div class="mt-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2">
                        <i class="ph-bold ph-check text-white"></i>
                    </div>
                    <span>Hơn 500+ Tour cao cấp toàn cầu</span>
                </div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2">
                        <i class="ph-bold ph-check text-white"></i>
                    </div>
                    <span>Thanh toán an toàn, bảo mật</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2">
                        <i class="ph-bold ph-check text-white"></i>
                    </div>
                    <span>Hỗ trợ khách hàng 24/7</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Phía Form đăng nhập -->
    <div class="auth-form-section">
        <div class="auth-card fade-in-up">
            <a href="<?= BASE_URL ?>" class="brand-logo">
                <i class="ph-fill ph-paper-plane-tilt"></i>
                <span>AgileTravel</span>
            </a>

            <div class="mb-4">
                <h2 class="fw-bold text-dark">Mừng bạn trở lại!</h2>
                <p class="text-muted">Nhập thông tin để tiếp tục khám phá.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 rounded-3 small py-2 mb-4">
                    <i class="ph-bold ph-warning-circle me-2"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success border-0 rounded-3 small py-2 mb-4">
                    <i class="ph-bold ph-check-circle me-2"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>?action=login-submit" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label small fw-bold text-dark">Mật khẩu</label>
                        <a href="#" class="small text-primary text-decoration-none">Quên mật khẩu?</a>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small text-muted" for="remember">
                        Duy trì đăng nhập
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-auth w-100 mb-3 shadow-none">
                    Đăng Nhập
                </button>


                <div class="text-center">
                    <p class="small text-muted">
                        Chưa có tài khoản? 
                        <a href="<?= BASE_URL ?>?action=register" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Nút quay lại trang chủ góc nhỏ cho Mobile -->
<a href="<?= BASE_URL ?>" class="position-absolute d-lg-none top-0 end-0 m-3 text-dark">
    <i class="ph-bold ph-x fs-3"></i>
</a>

<?php // include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>

