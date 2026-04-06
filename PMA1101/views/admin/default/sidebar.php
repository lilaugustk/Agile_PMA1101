<?php
// Hàm helper để kiểm tra active
function isActive($action)
{
    $currentAction = $_GET['action'] ?? '';
    return ($currentAction == $action) ? 'active' : '';
}

// Hàm kiểm tra menu cha có active không (kiểm tra các menu con)
function isParentActive($actions)
{
    $currentAction = $_GET['action'] ?? '';

    if (!is_array($actions)) {
        $actions = [$actions];
    }

    foreach ($actions as $action) {
        if ($currentAction === $action) {
            return 'active'; 
        }
        if (strpos($currentAction, $action . '/') === 0) {
            return 'active';
        }
    }

    return '';
}

// Hàm tương tự cho collapse
function isCollapseShow($actions) {
    return isParentActive($actions) === 'active' ? 'show' : '';
}

$currentAction = $_GET['action'] ?? '';
$userRole = $_SESSION['user']['role'] ?? 'customer';
$isAdmin = $userRole === 'admin';
$isGuide = $userRole === 'guide';
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="ph-fill ph-paper-plane-tilt me-3 text-primary"></i> TravelAdmin
    </div>
    <ul class="sidebar-menu overflow-y-auto" style="overflow-x: hidden;">
        <?php if ($isAdmin): ?>
            <li class="menu-item">
                <a class="menu-link <?= $currentAction === 'dashboard' || empty($currentAction) ? 'active' : '' ?>" href="<?= BASE_URL_ADMIN ?>&action=/">
                    <i class="ph ph-squares-four"></i> Tổng quan
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive(['tours', 'policies', 'suppliers', 'tours_categories', 'tours_versions', 'tours_history']) ?>"
                    href="#" data-bs-toggle="collapse" data-bs-target="#tourMenu"
                    aria-expanded="<?= isCollapseShow(['tours', 'policies', 'suppliers', 'tours_categories', 'tours_versions', 'tours_history']) ? 'true' : 'false' ?>">
                    <div class="d-flex align-items-center gap-2"><i class="ph ph-map-trifold"></i> Tour</div>
                    <i class="ph ph-caret-down" style="font-size: 0.9rem;"></i>
                </a>
                <div class="collapse <?= isCollapseShow(['tours', 'policies', 'suppliers', 'tours_categories', 'tours_versions', 'tours_history']) ?>" id="tourMenu">
                    <ul class="sidebar-menu pb-0 pt-2 ps-3 m-0" style="border-left: 1px solid var(--border-light); margin-left: 0.8rem !important; padding-right: 0;">
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('tours') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours">
                                <i class="ph ph-list" style="font-size: 1.1rem;"></i> Danh sách Tour
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('tours_categories') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_categories">
                                <i class="ph ph-folders" style="font-size: 1.1rem;"></i> Danh mục Tour
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('tours_versions') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_versions">
                                <i class="ph ph-git-branch" style="font-size: 1.1rem;"></i> Phiên bản Tour
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('policies') ?>" href="<?= BASE_URL_ADMIN ?>&action=policies">
                                <i class="ph ph-file-text" style="font-size: 1.1rem;"></i> Chính sách
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('suppliers') ?>" href="<?= BASE_URL_ADMIN ?>&action=suppliers">
                                <i class="ph ph-handshake" style="font-size: 1.1rem;"></i> Nhà cung cấp
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('tours/departures') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours/departures">
                                <i class="ph ph-truck" style="font-size: 1.1rem;"></i> Vận hành đoàn
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <li class="menu-item">
            <a class="menu-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive(['bookings', 'guides/available-tours']) ?>"
                href="#" data-bs-toggle="collapse" data-bs-target="#bookingMenu"
                aria-expanded="<?= (isCollapseShow('bookings') || isCollapseShow('guides/available-tours')) ? 'true' : 'false' ?>">
                <div class="d-flex align-items-center gap-2"><i class="ph ph-calendar-check"></i> Booking</div>
                <i class="ph ph-caret-down" style="font-size: 0.9rem;"></i>
            </a>
            <div class="collapse <?= (isCollapseShow('bookings') || isCollapseShow('guides/available-tours')) ? 'show' : '' ?>" id="bookingMenu">
                <ul class="sidebar-menu pb-0 pt-2 ps-3 m-0" style="border-left: 1px solid var(--border-light); margin-left: 0.8rem !important; padding-right: 0;">
                    <li class="menu-item">
                        <a class="menu-link py-2 <?= isActive('bookings') ?>" href="<?= BASE_URL_ADMIN ?>&action=bookings">
                            <i class="ph ph-list-numbers" style="font-size: 1.1rem;"></i> Quản lý Booking
                        </a>
                    </li>
                    <?php if (in_array($_SESSION['user']['role'] ?? '', ['guide', 'admin'])): ?>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('available-tours') ?>" href="<?= BASE_URL_ADMIN ?>&action=available-tours">
                                <i class="ph ph-ticket" style="font-size: 1.1rem;"></i> Tour Khả Dụng
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>

        <?php if ($isAdmin): ?>
            <li class="menu-item">
                <a class="menu-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive(['guides', 'bus-companies']) ?>"
                    href="#" data-bs-toggle="collapse" data-bs-target="#staffMenu"
                    aria-expanded="<?= isCollapseShow(['guides', 'bus-companies']) ? 'true' : 'false' ?>">
                    <div class="d-flex align-items-center gap-2"><i class="ph ph-users-three"></i> Nhân Sự</div>
                    <i class="ph ph-caret-down" style="font-size: 0.9rem;"></i>
                </a>
                <div class="collapse <?= isCollapseShow(['guides', 'bus-companies']) ?>" id="staffMenu">
                    <ul class="sidebar-menu pb-0 pt-2 ps-3 m-0" style="border-left: 1px solid var(--border-light); margin-left: 0.8rem !important; padding-right: 0;">
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('guides') ?>" href="<?= BASE_URL_ADMIN ?>&action=guides">
                                <i class="ph ph-user-focus" style="font-size: 1.1rem;"></i> Quản lý Guide
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('bus-companies') ?>" href="<?= BASE_URL_ADMIN ?>&action=bus-companies">
                                <i class="ph ph-bus" style="font-size: 1.1rem;"></i> Quản lý Nhà Xe
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-item">
                <a class="menu-link <?= isActive('users') ?>" href="<?= BASE_URL_ADMIN ?>&action=users">
                    <i class="ph ph-user"></i> Tài khoản
                </a>
            </li>
        <?php endif; ?>

        <li class="menu-item">
            <a class="menu-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive(['tours_logs', 'guide/schedule']) ?>"
                href="#" data-bs-toggle="collapse" data-bs-target="#workMenu"
                aria-expanded="<?= isCollapseShow(['tours_logs', 'guide/schedule']) ? 'true' : 'false' ?>">
                <div class="d-flex align-items-center gap-2"><i class="ph ph-briefcase"></i> Công việc</div>
                <i class="ph ph-caret-down" style="font-size: 0.9rem;"></i>
            </a>
            <div class="collapse <?= isCollapseShow(['tours_logs', 'guide/schedule']) ?>" id="workMenu">
                <ul class="sidebar-menu pb-0 pt-2 ps-3 m-0" style="border-left: 1px solid var(--border-light); margin-left: 0.8rem !important; padding-right: 0;">
                    <li class="menu-item">
                        <a class="menu-link py-2 <?= isActive('tours_logs') ?>" href="<?= BASE_URL_ADMIN ?>&action=tours_logs">
                            <i class="ph ph-book-open" style="font-size: 1.1rem;"></i> Nhật ký Tour
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link py-2 <?= isActive('guide/schedule') ?>" href="<?= BASE_URL_ADMIN ?>&action=guide/schedule">
                            <i class="ph ph-calendar-blank" style="font-size: 1.1rem;"></i> Lịch làm việc
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <?php if ($isAdmin): ?>
            <li class="menu-item" style="margin-top: 24px;">
                <span style="padding: 0 16px; font-size: 0.75rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px;">Thống kê</span>
            </li>
            
            <li class="menu-item">
                <a class="menu-link dropdown-toggles d-flex justify-content-between align-items-center <?= isParentActive('reports') ?>"
                    href="#" data-bs-toggle="collapse" data-bs-target="#reportsMenu"
                    aria-expanded="<?= isCollapseShow('reports') ? 'true' : 'false' ?>">
                    <div class="d-flex align-items-center gap-2"><i class="ph ph-chart-line-up"></i> Báo cáo</div>
                    <i class="ph ph-caret-down" style="font-size: 0.9rem;"></i>
                </a>
                <div class="collapse <?= isCollapseShow('reports') ?>" id="reportsMenu">
                    <ul class="sidebar-menu pb-0 pt-2 ps-3 m-0" style="border-left: 1px solid var(--border-light); margin-left: 0.8rem !important; padding-right: 0;">
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('reports/financial') ?>" href="<?= BASE_URL_ADMIN ?>&action=reports/financial">
                                <i class="ph ph-currency-circle-dollar" style="font-size: 1.1rem;"></i> BC Tài chính
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('reports/bookings') ?>" href="<?= BASE_URL_ADMIN ?>&action=reports/bookings">
                                <i class="ph ph-chart-pie" style="font-size: 1.1rem;"></i> BC Booking
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('reports/feedback') ?>" href="<?= BASE_URL_ADMIN ?>&action=reports/feedback">
                                <i class="ph ph-chat-teardrop-dots" style="font-size: 1.1rem;"></i> BC Phản hồi
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link py-2 <?= isActive('reports/debt') ?>" href="<?= BASE_URL_ADMIN ?>&action=reports/debt">
                                <i class="ph ph-handshake" style="font-size: 1.1rem;"></i> BC Công nợ
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</aside>