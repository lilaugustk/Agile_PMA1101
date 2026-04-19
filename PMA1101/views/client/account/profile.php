<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>
<?php
$displayName = $user['full_name'] ?? ($_SESSION['user']['full_name'] ?? 'Khách hàng');
$displayEmail = $user['email'] ?? ($_SESSION['user']['email'] ?? '');
$displayPhone = $user['phone'] ?? ($_SESSION['user']['phone'] ?? '');

$gender = $profile['gender'] ?? '';
$birthDate = $profile['birth_date'] ?? '';
$idCard = $profile['id_card'] ?? '';
$address = $profile['address'] ?? '';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    :root {
        --sapphire-blue: #2563eb;
        --sapphire-gradient: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        --sapphire-soft: #f8faff;
        --sapphire-slate: #1e293b;
        --sapphire-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.1);
        --primary-color: #2563eb;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f6f8fc;
    }

    /* Dashboard Header */
    .profile-hero {
        background: var(--sapphire-gradient);
        padding: 80px 0 100px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Glassmorphism Sidebar */
    .profile-nav-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        box-shadow: var(--sapphire-shadow);
        overflow: hidden;
        margin-top: -60px;
    }

    .nav-user-info {
        padding: 30px;
        background: #f8faff;
        border-bottom: 1.5px solid #edf2f7;
    }

    .nav-link-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 25px;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .nav-link-item i {
        font-size: 1.4rem;
    }

    .nav-link-item:hover {
        background: #f8faff;
        color: var(--sapphire-blue);
    }

    .nav-link-item.active {
        background: #eff6ff;
        color: var(--sapphire-blue);
        border-left-color: var(--sapphire-blue);
    }

    .nav-link-item.logout {
        color: #ef4444;
    }

    /* Content Cards */
    .content-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        box-shadow: var(--sapphire-shadow);
        margin-top: -60px;
        margin-bottom: 30px;
    }

    .card-title-box {
        padding: 2.5rem 2.5rem 1.5rem;
        border-bottom: 1.5px solid #edf2f7;
    }

    /* Sapphire Floating Labels */
    .cust-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .cust-floating .form-control {
        height: 64px;
        padding: 1.2rem 1rem 0.5rem;
        border: 2px solid #edf2f7;
        border-radius: 16px;
        font-weight: 600;
        color: var(--sapphire-slate);
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cust-floating label {
        position: absolute;
        top: 50%; left: 1rem;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        font-weight: 500;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
    }

    .cust-floating .form-control:focus,
    .cust-floating .form-control:not(:placeholder-shown) {
        border-color: var(--sapphire-blue);
        padding-top: 1.8rem;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.05);
    }

    .cust-floating .form-control:focus + label,
    .cust-floating .form-control:not(:placeholder-shown) + label {
        top: 0.8rem;
        font-size: 0.75rem;
        color: var(--sapphire-blue);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-sapphire {
        background: var(--sapphire-gradient);
        color: #fff;
        padding: 18px 35px;
        border-radius: 16px;
        font-weight: 800;
        border: none;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    .btn-sapphire:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
        color: #fff;
    }

    .badge-premium {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(5px);
        color: #fff;
        font-weight: 700;
        padding: 8px 15px;
        border-radius: 100px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .alert-sapphire {
        border: none;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

<div class="profile-hero animate__animated animate__fadeIn">
    <div class="container">
        <div class="d-flex align-items-center gap-4 text-white">
            <div class="position-relative">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($displayName) ?>&background=fff&color=2563eb&size=100&bold=true" 
                     class="rounded-circle shadow-lg border border-4 border-white border-opacity-20" 
                     width="90" height="90" alt="Avatar">
                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width:18px; height:18px"></span>
            </div>
            <div>
                <span class="badge-premium mb-2 d-inline-block">Thành viên Agile Travel</span>
                <h1 class="h2 fw-800 mb-0">Chào bạn, <?= htmlspecialchars($displayName) ?>!</h1>
                <p class="mb-0 opacity-75 small"><i class="ph ph-envelope me-1"></i><?= htmlspecialchars($displayEmail) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-4 col-xl-3">
            <div class="profile-nav-card animate__animated animate__fadeInLeft">
                <div class="nav-user-info">
                    <div class="fw-800 text-dark mb-1 text-truncate">Quản lý tài khoản</div>
                    <div class="small text-muted">Mã KH: #USER-<?= $_SESSION['user']['id'] ?? '???' ?></div>
                </div>
                <div class="py-2">
                    <a href="<?= BASE_URL ?>?action=profile" class="nav-link-item active">
                        <i class="ph-bold ph-user-circle"></i> Thông tin tài khoản
                    </a>
                    <a href="<?= BASE_URL ?>?action=my-bookings" class="nav-link-item">
                        <i class="ph-bold ph-suitcase-rolling"></i> Chuyến đi của tôi
                    </a>
                    <hr class="mx-3 opacity-5">
                    <a href="<?= BASE_URL ?>?action=logout" class="nav-link-item logout">
                        <i class="ph-bold ph-sign-out"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9">
            <div class="content-card animate__animated animate__fadeInRight">
                <div class="card-title-box">
                    <h4 class="fw-800 text-dark mb-1">Cập nhật hồ sơ</h4>
                    <p class="text-muted mb-0 small">Thông tin cá nhân được bảo mật và chỉ dùng để liên hệ đặt tour.</p>
                </div>
                
                <div class="p-4 p-md-5">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-sapphire mb-4 animate__animated animate__headShake">
                            <i class="ph-fill ph-warning-circle fs-4"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-sapphire mb-4 animate__animated animate__fadeIn">
                            <i class="ph-fill ph-check-circle fs-4"></i> <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>?action=profile-update" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="text" class="form-control" name="full_name" id="full_name" value="<?= htmlspecialchars($displayName) ?>" placeholder=" " required>
                                    <label><i class="ph ph-user me-2"></i>Họ và tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="email" class="form-control" style="background:#f8faff" value="<?= htmlspecialchars($displayEmail) ?>" placeholder=" " readonly disabled>
                                    <label><i class="ph ph-envelope me-2"></i>Email (Không thể thay đổi)</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="cust-floating">
                                    <input type="tel" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($displayPhone) ?>" 
                                           placeholder=" " pattern="0\d{9}" title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0">
                                    <label><i class="ph ph-phone me-2"></i>Số điện thoại di động</label>
                                </div>
                            </div>

                            <div class="col-12 pt-3 pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                                        <i class="ph-bold ph-identification-card text-primary fs-5"></i>
                                    </div>
                                    <h6 class="fw-800 mb-0">Hồ sơ chi tiết</h6>
                                </div>
                                <hr class="my-3 opacity-5">
                            </div>

                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="" <?= empty($gender) ? 'selected' : '' ?>>Chọn giới tính</option>
                                        <option value="Nam" <?= $gender == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Nữ" <?= $gender == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                        <option value="Khác" <?= $gender == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                    </select>
                                    <label><i class="ph ph-gender-intersex me-2"></i>Giới tính</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="date" class="form-control" name="birth_date" id="birth_date" value="<?= htmlspecialchars($birthDate) ?>" placeholder=" ">
                                    <label><i class="ph ph-calendar-bold me-2"></i>Ngày sinh</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="text" class="form-control" name="id_card" id="id_card" value="<?= htmlspecialchars($idCard) ?>" 
                                           placeholder=" " pattern="\d{9,12}" title="Số CCCD thường gồm 9 hoặc 12 chữ số">
                                    <label><i class="ph ph-identification-badge me-2"></i>Số CCCD/Hộ chiếu</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="text" class="form-control" name="address" id="address" value="<?= htmlspecialchars($address) ?>" placeholder=" ">
                                    <label><i class="ph ph-map-pin me-2"></i>Địa chỉ liên hệ</label>
                                </div>
                            </div>

                            <div class="col-12 pt-4 pb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                                        <i class="ph-bold ph-key text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-800 mb-0">Đổi mật khẩu</h5>
                                        <p class="text-muted small mb-0">Giữ an toàn cho tài khoản của bạn</p>
                                    </div>
                                </div>
                                <hr class="my-4 opacity-5">
                            </div>

                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="password" class="form-control" name="password" id="password" minlength="6" placeholder=" ">
                                    <label><i class="ph ph-lock me-2"></i>Mật khẩu mới</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="password" class="form-control" name="password_confirm" id="confirm" placeholder=" ">
                                    <label><i class="ph ph-lock-key me-2"></i>Xác nhận mật khẩu</label>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-sapphire w-100 w-md-auto">
                                    <i class="ph-bold ph-check-square-offset me-2"></i>Cập nhật thông tin
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
