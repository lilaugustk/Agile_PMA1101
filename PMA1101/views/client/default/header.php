<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Travel - Đặt Tour Cao Cấp</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- FontAwesome (Legacy fallback if needed) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_ASSETS_CLIENT ?>css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top premium-navbar transition-all" id="mainNav">
        <div class="container container-navbar">
            <a class="navbar-brand d-flex align-items-center fw-bold text-primary gap-2" href="<?= BASE_URL ?>">
                <i class="ph-fill ph-paper-plane-tilt fs-2"></i>
                <span class="fs-4 tracking-tight">AgileTravel</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="ph ph-list fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == '/') || !isset($_GET['action']) ? 'active' : '' ?>" href="<?= BASE_URL ?>">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && strpos($_GET['action'], 'tour') !== false) && !isset($_GET['id']) ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=tours">Khám Phá Tour</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'about') ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=about">Về Chúng Tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'contact') ? 'active' : '' ?>" href="<?= BASE_URL ?>?action=contact">Liên Hệ</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 auth-buttons">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="dropdown">
                            <a class="nav-link d-flex align-items-center gap-2 fw-medium text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= !empty($_SESSION['user']['avatar']) ? BASE_ASSETS_UPLOADS . $_SESSION['user']['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user']['full_name'] ?? 'User') . '&background=008C72&color=fff' ?>" alt="Avatar" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                <span class="d-none d-lg-block"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></span>
                                <i class="ph ph-caret-down fs-7 ms-1 text-muted"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>?action=account-profile">
                                    <i class="ph ph-user me-2 text-muted"></i>Tài khoản của tôi
                                </a></li>
                                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>?action=account-bookings">
                                    <i class="ph ph-calendar-check me-2 text-muted"></i>Lịch sử đặt tour
                                </a></li>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>?mode=admin">
                                    <i class="ph ph-lock-key me-2 text-muted"></i>Trang Quản trị
                                </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>?action=logout">
                                    <i class="ph ph-sign-out me-2"></i>Đăng xuất
                                </a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>?action=login" class="text-decoration-none fw-medium text-dark login-link hover-text-primary px-2 transition-all">Đăng Nhập</a>
                        <a href="<?= BASE_URL ?>?action=register" class="btn btn-primary rounded-pill px-4 btn-nav transition-all hover-lift">
                            Đăng Ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Spacer to prevent content overlap due to fixed navbar -->
    <div class="navbar-spacer" style="height: 80px;"></div>