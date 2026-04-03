<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Breadcrumb -->
<div class="bg-light pt-5 mt-4">
    <div class="container py-4">
        <h2 class="fw-bold text-dark mb-2">Tài Khoản Của Tôi</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lịch sử đặt tour</li>
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
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['full_name']) ?>&background=random" class="rounded-circle border border-3 border-white shadow-sm mb-3" width="90" alt="Avatar">
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h5>
                    <span class="small text-white-50"><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= BASE_URL ?>?action=profile" class="list-group-item list-group-item-action py-3 px-4 fw-medium text-muted d-flex align-items-center transition-all hover-text-primary">
                        <i class="ph-bold ph-identification-card fs-5 me-2"></i> Hồ Sơ Cá Nhân
                    </a>
                    <a href="<?= BASE_URL ?>?action=my-bookings" class="list-group-item list-group-item-action py-3 px-4 active fw-medium d-flex align-items-center">
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
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h4 class="fw-bold mb-0 font-outfit text-dark tracking-tight">Danh Sách Chuyến Đi</h4>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-medium border border-primary-subtle">
                        Tổng cộng: <?= count($bookings) ?> chuyến
                    </span>
                </div>
                <hr class="text-secondary opacity-25 mb-4">
                
                <?php if (empty($bookings)): ?>
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="ph-duotone ph-ticket text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-dark fw-bold mb-2">Bạn chưa đặt chuyến đi nào</h4>
                        <p class="text-muted mb-4">Cùng AgileTravel khám phá những địa điểm mới ngay thôi!</p>
                        <a href="<?= BASE_URL ?>?action=tours" class="btn btn-primary rounded-pill px-4 fw-bold hover-lift shadow-sm">
                            Xem Các Tour Đang Mở
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($bookings as $booking): 
                            // Determine status styles
                            $statusMap = [
                                'cho_xac_nhan' => ['badge' => 'bg-warning-subtle text-warning border-warning border', 'icon' => 'ph-hourglass', 'label' => 'Chờ xác nhận'],
                                'da_coc' => ['badge' => 'bg-info-subtle text-info border-info border', 'icon' => 'ph-piggy-bank', 'label' => 'Đã cọc'],
                                'da_thanh_toan' => ['badge' => 'bg-success-subtle text-success border-success border', 'icon' => 'ph-check-circle', 'label' => 'Đã thanh toán'],
                                'hoan_tat' => ['badge' => 'bg-success text-white', 'icon' => 'ph-flag-checkered', 'label' => 'Hoàn tất'],
                                'da_huy' => ['badge' => 'bg-danger-subtle text-danger border-danger border', 'icon' => 'ph-x-circle', 'label' => 'Đã hủy']
                            ];
                            $statusStyle = $statusMap[$booking['status']] ?? ['badge' => 'bg-secondary', 'icon' => 'ph-dots-three', 'label' => $booking['status']];
                        ?>
                        <div class="col-12" data-aos="fade-up">
                            <div class="card border-0 rounded-4 overflow-hidden" style="box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
                                <div class="card-header bg-light border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-medium">
                                        <i class="ph-bold ph-hash"></i> Mã Phiếu: <strong>BKG-<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                                    </span>
                                    <span class="badge rounded-pill px-3 py-2 <?= $statusStyle['badge'] ?>">
                                        <i class="ph-fill <?= $statusStyle['icon'] ?> me-1"></i> <?= $statusStyle['label'] ?>
                                    </span>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-7 mb-3 mb-md-0">
                                            <h5 class="fw-bold mb-2 font-outfit text-dark line-clamp-2">
                                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $booking['tour_id'] ?>" class="text-decoration-none text-dark hover-text-primary transition-all">
                                                    <?= htmlspecialchars($booking['tour_name']) ?>
                                                </a>
                                            </h5>
                                            <div class="d-flexflex-wrap gap-3 text-muted small mt-3">
                                                <div class="d-flex align-items-center gap-1 mb-2">
                                                    <i class="ph-fill ph-calendar-check text-primary"></i> Đặt ngày: <?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-md-end border-md-start ps-md-4">
                                            <p class="text-muted small mb-1 text-uppercase fw-bold">Tổng tiền</p>
                                            <h3 class="fw-bold text-primary mb-3"><?= number_format($booking['final_price'], 0, ',', '.') ?>đ</h3>
                                            
                                            <!-- Trạng thái hoặc Nút hành động tương ứng -->
                                           <?php if (in_array($booking['status'], ['cho_xac_nhan', 'da_coc'])): ?>
                                                <p class="text-info small mb-0 fw-medium fst-italic"><i class="ph-fill ph-info"></i> Nhân viên sẽ sớm liên hệ để xác nhận khởi hành với bạn.</p>
                                           <?php elseif ($booking['status'] == 'hoan_tat'): ?>
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="ph-fill ph-star me-1"></i> Đánh giá ngay</button>
                                           <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
