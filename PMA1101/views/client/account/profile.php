<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Breadcrumb -->
<div class="bg-light pt-5 mt-4">
    <div class="container py-4">
        <h2 class="fw-bold text-dark mb-2">Tài Khoản Của Tôi</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hồ sơ cá nhân</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 sticky-top transition-all" style="top: 100px;">
                <div class="bg-primary p-4 text-center text-white">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=random" class="rounded-circle border border-3 border-white shadow-sm mb-3" width="90" alt="Avatar">
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($user['full_name']) ?></h5>
                    <span class="small text-white-50"><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= BASE_URL ?>?action=profile" class="list-group-item list-group-item-action py-3 px-4 active fw-medium d-flex align-items-center">
                        <i class="ph-bold ph-identification-card fs-5 me-2"></i> Hồ Sơ Cá Nhân
                    </a>
                    <a href="<?= BASE_URL ?>?action=my-bookings" class="list-group-item list-group-item-action py-3 px-4 fw-medium text-muted d-flex align-items-center transition-all hover-text-primary">
                        <i class="ph-bold ph-suitcase-rolling fs-5 me-2"></i> Lịch Sử Đặt Tour
                    </a>
                    <a href="<?= BASE_URL ?>?action=logout" class="list-group-item list-group-item-action py-3 px-4 fw-medium text-danger d-flex align-items-center transition-all">
                        <i class="ph-bold ph-sign-out fs-5 me-2"></i> Đăng Xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h4 class="fw-bold mb-4 font-outfit text-dark tracking-tight">Thông Tin Cá Nhân</h4>
                <hr class="text-secondary opacity-25 mb-4">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle text-danger rounded-3 p-3 mb-4 d-flex align-items-center">
                        <i class="ph-fill ph-warning-circle fs-4 me-2"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success border-0 bg-success-subtle text-success rounded-3 p-3 mb-4 d-flex align-items-center">
                        <i class="ph-fill ph-check-circle fs-4 me-2"></i> <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?action=profile-update" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="ph-fill ph-user"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 focus-ring-primary ps-0" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="ph-fill ph-envelope-simple"></i></span>
                                <input type="email" class="form-control bg-light border-start-0 ps-0 text-muted" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>
                            <div class="form-text small">Email không thể thay đổi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="ph-fill ph-phone"></i></span>
                                <input type="tel" class="form-control bg-light border-start-0 focus-ring-primary ps-0" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <h5 class="fw-bold mb-3 font-outfit text-dark">Đổi Mật Khẩu</h5>
                            <p class="text-muted small">Bỏ trống nếu không muốn thay đổi mật khẩu hiện tại.</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="ph-fill ph-lock-key"></i></span>
                                <input type="password" class="form-control bg-light border-start-0 focus-ring-primary ps-0" name="password" minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="ph-fill ph-shield-check"></i></span>
                                <input type="password" class="form-control bg-light border-start-0 focus-ring-primary ps-0" name="password_confirm">
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm hover-lift d-flex align-items-center gap-2 m-auto ms-md-auto me-md-0">
                                <i class="ph-bold ph-floppy-disk"></i> Lưu Thay Đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
