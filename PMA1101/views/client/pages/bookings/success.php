<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<style>
.success-hero {
    background: linear-gradient(135deg, #198754 0%, #0d6efd 100%);
    border-radius: 0 0 50% 50% / 0 0 30px 30px;
    padding: 60px 20px 80px;
    margin-bottom: -40px;
}
.success-icon-wrap {
    width: 90px; height: 90px;
    background: rgba(255,255,255,.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    animation: popIn .5s cubic-bezier(.175,.885,.32,1.275) both;
}
@keyframes popIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.success-card {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}
.detail-item {
    display: flex; justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #e9ecef;
    font-size: .9rem;
}
.detail-item:last-child { border-bottom: none; }
.step-check {
    width: 28px; height: 28px; border-radius: 50%;
    background: #d1e7dd; color: #198754;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700;
    flex-shrink: 0;
}
</style>

<!-- Success Hero -->
<div class="success-hero text-center text-white">
    <div class="success-icon-wrap">
        <i class="fas fa-check fa-3x text-white"></i>
    </div>
    <h1 class="fw-bold mb-2">Đặt Tour Thành Công!</h1>
    <p class="opacity-75 mb-0 lead">Yêu cầu của bạn đã được ghi nhận</p>
</div>

<div class="container" style="padding-bottom: 60px;">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Booking Code Card -->
            <div class="card success-card shadow-lg mb-4" style="margin-top: 60px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-5">
                        <p class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-2">Mã đặt tour của bạn</p>
                        <div class="d-inline-flex align-items-center gap-2 bg-light rounded-pill px-4 py-2">
                            <i class="fas fa-ticket-alt text-primary fa-lg"></i>
                            <span class="h3 fw-bold text-primary mb-0"><?= htmlspecialchars($bookingCode) ?></span>
                        </div>
                        <p class="text-muted small mt-2">Lưu mã này để kiểm tra trạng thái đặt tour</p>
                    </div>

                    <?php if ($booking): ?>
                    <!-- Chi tiết booking -->
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Chi Tiết Đơn Hàng</h6>
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="detail-item">
                            <span class="text-muted">Tour</span>
                            <span class="fw-bold text-end" style="max-width:60%"><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="text-muted">Ngày khởi hành</span>
                            <span class="fw-bold"><?= date('d/m/Y', strtotime($booking['departure_date'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="text-muted">Người đặt</span>
                            <span class="fw-bold"><?= htmlspecialchars($booking['contact_name'] ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="text-muted">Email liên hệ</span>
                            <span class="fw-bold"><?= htmlspecialchars($booking['contact_email'] ?? '') ?></span>
                        </div>
                        <?php $totalPax = ($booking['adults'] ?? 0) + ($booking['children'] ?? 0) + ($booking['infants'] ?? 0); ?>
                        <div class="detail-item">
                            <span class="text-muted">Số hành khách</span>
                            <span class="fw-bold"><?= $booking['adults'] ?? 0 ?> NL · <?= $booking['children'] ?? 0 ?> TE · <?= $booking['infants'] ?? 0 ?> EB</span>
                        </div>
                        <div class="detail-item">
                            <span class="text-muted">Tổng thanh toán</span>
                            <span class="fw-bold text-primary fs-5"><?= number_format($booking['final_price'], 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="detail-item">
                            <span class="text-muted">Trạng thái</span>
                            <span class="badge bg-info text-dark">Chờ xác nhận</span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Các bước tiếp theo -->
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Các Bước Tiếp Theo</h6>
                    <div class="d-flex flex-column gap-3 mb-5">
                        <div class="d-flex align-items-start gap-3">
                            <div class="step-check">1</div>
                            <p class="mb-0 small"><strong>Nhân viên tư vấn sẽ liên hệ</strong> xác nhận thông tin trong vòng 24h, qua số điện thoại hoặc email bạn đã cung cấp.</p>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="step-check">2</div>
                            <p class="mb-0 small"><strong>Xác nhận và chuẩn bị hành lý</strong> theo hướng dẫn. Bạn có thể gọi số hotline nếu có bất kỳ câu hỏi nào.</p>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <div class="step-check">3</div>
                            <p class="mb-0 small"><strong>Khởi hành</strong> đúng thời gian và địa điểm được thông báo. Chúc bạn có một chuyến đi tuyệt vời!</p>
                        </div>
                    </div>

                    <!-- CTAs -->
                    <div class="d-grid gap-2">
                        <?php if (isset($_SESSION['last_invoice_url'])): ?>
                        <a href="<?= $_SESSION['last_invoice_url'] ?>" target="_blank" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm mb-2">
                            <i class="fas fa-file-invoice me-2"></i>Tải Hóa Đơn (PDF/HTML)
                        </a>
                        <?php unset($_SESSION['last_invoice_url']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user'])): ?>
                        <a href="<?= BASE_URL ?>?action=my-bookings" class="btn btn-primary btn-lg rounded-pill fw-bold">
                            <i class="fas fa-list-alt me-2"></i>Xem Lịch Sử Đặt Tour
                        </a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>?action=tours" class="btn btn-outline-primary btn-lg rounded-pill">
                            <i class="fas fa-compass me-2"></i>Tiếp Tục Khám Phá Tour
                        </a>
                        <a href="<?= BASE_URL ?>" class="btn btn-light btn-lg rounded-pill text-muted">
                            <i class="fas fa-home me-2"></i>Về Trang Chủ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Share / Contact -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <p class="text-muted small mb-3">Cần hỗ trợ? Liên hệ chúng tôi</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="tel:0986951086" class="btn btn-outline-success rounded-pill btn-sm px-4">
                            <i class="fas fa-phone me-1"></i> 0986 951 086
                        </a>
                        <a href="mailto:support@viettravel.com" class="btn btn-outline-primary rounded-pill btn-sm px-4">
                            <i class="fas fa-envelope me-1"></i> Email hỗ trợ
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
