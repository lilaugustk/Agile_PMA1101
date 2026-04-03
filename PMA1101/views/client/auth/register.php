<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<div class="container-fluid p-0">
    <div class="row g-0 auth-wrapper min-vh-100 flex-row-reverse">
        <!-- Split Layout Image (Reversed for Register) -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2073&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Travel Banner">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
            <div class="position-absolute top-50 start-50 translate-middle text-center w-75 z-2" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-white mb-3 tracking-tight">Gia Nhập Cộng Đồng Bay Xa</h2>
                <p class="lead text-white-50">Hàng ngàn ưu đãi du lịch đặc quyền đang chờ đón bạn.</p>
            </div>
        </div>

        <!-- Form Layout -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white py-5">
            <div class="auth-form-container w-100 px-4 px-md-5" style="max-width: 550px;" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="ph-fill ph-user-plus fs-1"></i>
                    </div>
                    <h2 class="fw-bold text-dark tracking-tight">Tạo Tài Khoản Mới</h2>
                    <p class="text-muted">Chỉ mất 1 phút để trở thành một phần của AgileTravel</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 p-3 mb-4 d-flex align-items-center">
                        <i class="ph-fill ph-warning-circle fs-4 me-2"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?action=register-submit" method="POST" id="registerForm">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control focus-ring-primary bg-light border-0" id="full_name" name="full_name" placeholder="Họ và tên" required>
                        <label for="full_name" class="text-muted"><i class="ph-fill ph-user me-1"></i> Họ và tên</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control focus-ring-primary bg-light border-0" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email" class="text-muted"><i class="ph-fill ph-envelope-simple me-1"></i> Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control focus-ring-primary bg-light border-0" id="phone" name="phone" placeholder="Số điện thoại" required>
                        <label for="phone" class="text-muted"><i class="ph-fill ph-phone me-1"></i> Số điện thoại</label>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control focus-ring-primary bg-light border-0" id="password" name="password" placeholder="Mật khẩu" required minlength="6">
                                <label for="password" class="text-muted"><i class="ph-fill ph-lock-key me-1"></i> Mật khẩu</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control focus-ring-primary bg-light border-0" id="password_confirm" name="password_confirm" placeholder="Xác nhận MK" required minlength="6">
                                <label for="password_confirm" class="text-muted"><i class="ph-fill ph-shield-check me-1"></i> Xác nhận MK</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <div class="form-check custom-checkbox d-inline-block text-start">
                            <input class="form-check-input focus-ring-primary" type="checkbox" value="" id="terms" required>
                            <label class="form-check-label text-muted small" for="terms">Tôi đồng ý với <a href="#" class="text-primary hover-text-dark">Điều khoản & Chính sách bảo mật</a></label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold hover-lift mb-3 shadow-sm">
                        Đăng Ký Tài Khoản <i class="ph-bold ph-paper-plane-right ms-1"></i>
                    </button>

                    <p class="text-center text-muted mt-4 mb-0">
                        Đã có tài khoản? <a href="<?= BASE_URL ?>?action=login" class="text-primary text-decoration-none fw-bold hover-text-dark transition-all">Đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple password match validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const pwd = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirm').value;
        if(pwd !== confirm) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp! Vui lòng kiểm tra lại.');
        }
    });
</script>

<style>
    header, footer { display: none !important; } /* Hide layout components in auth view */
    .navbar-spacer { display: none !important; }
</style>

<?php // include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>