<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<?php
// Config ngân hàng
$bankId      = 'MB';
$accountNo   = '0986951086';
$accountName = 'Kim Van Kien';
$amount      = $booking['final_price'];
$bookingCode = 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
$content     = $bookingCode . ' THANH TOAN';
$qrUrl       = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.jpg?amount={$amount}&addInfo=" . urlencode($content) . "&accountName=" . urlencode($accountName);
?>

<style>
/* ── Stepper ─────────────────────────────── */
.booking-stepper { position: relative; z-index: 1; }
.booking-stepper::before {
    content: ''; position: absolute;
    top: 20px; left: 0; width: 100%; height: 2px;
    background: linear-gradient(90deg, #198754 66%, #dee2e6 66%);
    z-index: -1;
}
.booking-step {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; margin: 0 auto 8px; transition: all .3s;
}
.booking-step.completed { background: #198754; color:#fff; border: 2px solid #198754; }
.booking-step.active    { background: #0d6efd; color:#fff; border: 2px solid #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,.2); }
.booking-step.pending   { background: #fff; color: #adb5bd; border: 2px solid #dee2e6; }
.step-label { font-size: .78rem; color: #6c757d; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.step-label.active    { color: #0d6efd; }
.step-label.completed { color: #198754; }

/* ── Countdown ───────────────────────────── */
.countdown-bar {
    background: linear-gradient(135deg, #fff3cd, #ffe69c);
    border: 1px solid #ffc107; border-radius: 12px;
    padding: 12px 20px;
}
.countdown-digits { font-size: 1.6rem; font-weight: 800; color: #856404; font-variant-numeric: tabular-nums; letter-spacing: 2px; }
.countdown-bar.urgent .countdown-digits { color: #dc3545; animation: pulse .8s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }

/* ── Payment Info ────────────────────────── */
.bank-item {
    display: flex; align-items: center; gap: 14px;
    background: #f8f9fa; border-radius: 12px; padding: 14px;
}
.bank-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: #fff; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
}
.copy-btn {
    margin-left: auto; background: none; border: none;
    color: #0d6efd; cursor: pointer; font-size: .9rem;
    padding: 4px 8px; border-radius: 6px; transition: background .2s;
}
.copy-btn:hover { background: #e7f1ff; }

/* ── QR ──────────────────────────────────── */
.qr-wrapper {
    border: 2px dashed #dee2e6; border-radius: 16px;
    padding: 16px; display: inline-block;
    transition: border-color .3s;
}
.qr-wrapper:hover { border-color: #0d6efd; }

/* ── Order Summary ───────────────────────── */
.order-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: .9rem; }

/* ── Upload Zone ─────────────────────────── */
.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f8f9fa;
}
.upload-zone:hover {
    border-color: #0d6efd;
    background: #f0f7ff;
}
.upload-zone i {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 10px;
}
.upload-zone.has-file {
    border-color: #198754;
    background: #f0fff4;
}
.upload-zone.has-file i { color: #198754; }
</style>

<div class="container" style="padding-top: 40px; padding-bottom: 60px;">

    <!-- Stepper -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between text-center booking-stepper">
                <div><div class="booking-step completed"><i class="fas fa-check"></i></div><div class="step-label completed">Chọn Tour</div></div>
                <div><div class="booking-step completed"><i class="fas fa-check"></i></div><div class="step-label completed">Thông Tin</div></div>
                <div><div class="booking-step active">3</div><div class="step-label active">Thanh Toán</div></div>
                <div><div class="booking-step pending">4</div><div class="step-label">Hoàn Tất</div></div>
            </div>
        </div>
    </div>

    <!-- Countdown Timer -->
    <?php if ($secondsLeft > 0): ?>
    <div class="row mb-4">
        <div class="col-lg-10 mx-auto">
            <div class="countdown-bar d-flex align-items-center justify-content-between flex-wrap gap-3" id="countdownBar">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-clock fa-lg text-warning"></i>
                    <div>
                        <p class="mb-0 fw-bold text-dark">Giữ chỗ tạm thời</p>
                        <small class="text-muted">Vui lòng thanh toán trong thời gian này, chỗ sẽ tự động giải phóng khi hết hạn</small>
                    </div>
                </div>
                <div class="countdown-digits" id="countdownDisplay">--:--</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">

        <!-- ─── Left: Thông tin chuyển khoản ─── -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 pb-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-qrcode me-2"></i>Thanh Toán Chuyển Khoản
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Quét mã QR hoặc chuyển khoản theo thông tin dưới đây</p>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center g-4">

                        <!-- QR Code -->
                        <div class="col-md-5 text-center">
                            <div class="qr-wrapper">
                                <img src="<?= $qrUrl ?>" alt="QR Payment" class="img-fluid rounded" style="max-width:180px;">
                            </div>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fas fa-mobile-alt me-1"></i>Quét bằng app ngân hàng
                            </p>
                        </div>

                        <!-- Bank Info -->
                        <div class="col-md-7 d-flex flex-column gap-3">
                            <div class="bank-item">
                                <div class="bank-icon text-primary"><i class="fas fa-university"></i></div>
                                <div>
                                    <small class="text-muted d-block">Ngân hàng</small>
                                    <span class="fw-bold">MB Bank (Quân Đội)</span>
                                </div>
                            </div>

                            <div class="bank-item">
                                <div class="bank-icon text-primary"><i class="fas fa-credit-card"></i></div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">Số tài khoản</small>
                                    <span class="fw-bold fs-5" id="accNum"><?= $accountNo ?></span>
                                </div>
                                <button class="copy-btn" onclick="copyText('accNum', this)" title="Sao chép">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>

                            <div class="bank-item">
                                <div class="bank-icon text-primary"><i class="fas fa-user"></i></div>
                                <div>
                                    <small class="text-muted d-block">Chủ tài khoản</small>
                                    <span class="fw-bold"><?= strtoupper($accountName) ?></span>
                                </div>
                            </div>

                            <div class="bank-item" style="background: #fff3cd; border: 1px solid #ffc107;">
                                <div class="bank-icon text-warning"><i class="fas fa-comment-dots"></i></div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">Nội dung chuyển khoản <strong class="text-danger">*bắt buộc</strong></small>
                                    <span class="fw-bold text-danger fs-6" id="transContent"><?= $bookingCode ?> THANH TOAN</span>
                                </div>
                                <button class="copy-btn text-warning" onclick="copyText('transContent', this)" title="Sao chép">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Button -->
                    <div class="border-top pt-4 mt-4">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            Để xác nhận nhanh hơn, vui lòng tải lên ảnh chụp màn hình chuyển khoản thành công.
                        </p>
                        
                        <form action="<?= BASE_URL ?>?action=booking-confirm" method="POST" id="confirmForm" enctype="multipart/form-data">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            
                            <div class="mb-4">
                                <label for="payment_proof" class="upload-zone w-100 mb-0" id="uploadZone">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="fw-bold text-dark">Tải ảnh minh chứng</div>
                                    <p class="small text-muted mb-0" id="fileName">Nhấn để chọn ảnh hoặc kéo thả vào đây</p>
                                    <input type="file" name="payment_proof" id="payment_proof" class="d-none" accept="image/*" onchange="handleFileSelect(this)">
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm"
                                    id="confirmBtn">
                                <i class="fas fa-check-circle me-2"></i>Tôi Đã Chuyển Khoản
                            </button>
                        </form>
                        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary w-100 mt-2 rounded-pill border-0">
                            <i class="fas fa-arrow-left me-2"></i>Quay về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Right: Chi tiết đơn hàng ─── -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 pb-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-receipt me-2"></i>Chi Tiết Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">

                    <!-- Tổng tiền nổi bật -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <p class="text-muted small mb-1">Số tiền cần thanh toán</p>
                        <h2 class="text-primary fw-bold mb-0">
                            <?= number_format($booking['final_price'], 0, ',', '.') ?>đ
                        </h2>
                    </div>

                    <!-- Chi tiết -->
                    <div class="order-item">
                        <span class="text-muted">Mã đặt tour</span>
                        <span class="fw-bold text-primary"><?= $bookingCode ?></span>
                    </div>
                    <div class="order-item">
                        <span class="text-muted">Tour</span>
                        <span class="fw-bold text-end" style="max-width:55%"><?= htmlspecialchars($booking['tour_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="order-item">
                        <span class="text-muted">Ngày khởi hành</span>
                        <span class="fw-bold"><?= date('d/m/Y', strtotime($booking['departure_date'])) ?></span>
                    </div>
                    <div class="order-item">
                        <span class="text-muted">Người đặt</span>
                        <span class="fw-bold"><?= htmlspecialchars($booking['contact_name'] ?? '') ?></span>
                    </div>
                    <?php if ($booking['adults'] > 0): ?>
                    <div class="order-item">
                        <span class="text-muted">Người lớn</span>
                        <span class="fw-bold"><?= $booking['adults'] ?> người</span>
                    </div>
                    <?php endif; ?>
                    <?php if ($booking['children'] > 0): ?>
                    <div class="order-item">
                        <span class="text-muted">Trẻ em</span>
                        <span class="fw-bold"><?= $booking['children'] ?> người</span>
                    </div>
                    <?php endif; ?>
                    <?php if ($booking['infants'] > 0): ?>
                    <div class="order-item">
                        <span class="text-muted">Em bé</span>
                        <span class="fw-bold"><?= $booking['infants'] ?> người</span>
                    </div>
                    <?php endif; ?>
                    <div class="order-item">
                        <span class="text-muted">Ngày đặt</span>
                        <span class="fw-bold"><?= date('d/m/Y H:i', strtotime($booking['booking_date'])) ?></span>
                    </div>
                    <div class="order-item border-top pt-3 mt-1">
                        <span class="text-muted">Trạng thái</span>
                        <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                    </div>

                    <!-- Cảnh báo -->
                    <div class="alert alert-warning border-0 bg-warning-subtle mt-4 small rounded-3">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Chỗ được giữ tối đa <strong>30 phút</strong>. Nếu không nhận được thanh toán, đơn hàng sẽ tự động hủy.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// ── Copy to clipboard ──────────────────────
function copyText(elementId, btn) {
    const text = document.getElementById(elementId).innerText.trim();
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => btn.innerHTML = original, 2000);
    });
}

// ── Countdown Timer ────────────────────────
<?php if ($secondsLeft > 0): ?>
let secondsLeft = <?= $secondsLeft ?>;
const display   = document.getElementById('countdownDisplay');
const bar       = document.getElementById('countdownBar');

function updateCountdown() {
    if (secondsLeft <= 0) {
        display.textContent = '00:00';
        bar.classList.add('urgent');
        display.closest('.countdown-bar').innerHTML = `
            <div class="d-flex align-items-center gap-3 text-danger">
                <i class="fas fa-times-circle fa-lg"></i>
                <div>
                    <p class="mb-0 fw-bold">Đã hết thời gian giữ chỗ!</p>
                    <small>Chỗ của bạn đã được giải phóng. <a href="<?= BASE_URL ?>">Đặt tour lại</a></small>
                </div>
            </div>`;
        return;
    }

    const m = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
    const s = String(secondsLeft % 60).padStart(2, '0');
    display.textContent = `${m}:${s}`;

    if (secondsLeft <= 120) bar.classList.add('urgent');
    secondsLeft--;
}

updateCountdown();
setInterval(updateCountdown, 1000);
<?php endif; ?>
// ── File Upload Handling ──────────────────
function handleFileSelect(input) {
    const zone = document.getElementById('uploadZone');
    const fileNameDisplay = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        zone.classList.add('has-file');
        fileNameDisplay.textContent = 'Đã chọn: ' + input.files[0].name;
    } else {
        zone.classList.remove('has-file');
        fileNameDisplay.textContent = 'Nhấn để chọn ảnh hoặc kéo thả vào đây';
    }
}

// ── Confirm Form ──────────────────────────
document.getElementById('confirmForm').addEventListener('submit', function(e) {
    if (!document.getElementById('payment_proof').files.length) {
        if (!confirm('Bạn chưa tải ảnh minh chứng chuyển khoản. Bạn có chắc chắn muốn xác nhận đã thanh toán không?')) {
            e.preventDefault();
        }
    }
});
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
