<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<style>
/* ── Stepper ─────────────────────────────── */
.booking-stepper {
    position: relative;
    z-index: 1;
}
.booking-stepper::before {
    content: '';
    position: absolute;
    top: 20px; left: 0;
    width: 100%; height: 2px;
    background: linear-gradient(90deg, #198754 33%, #0d6efd 33%, #0d6efd 66%, #dee2e6 66%);
    z-index: -1;
}
.booking-step {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.9rem;
    margin: 0 auto 8px;
    transition: all 0.3s ease;
}
.booking-step.completed { background: #198754; color: #fff; border: 2px solid #198754; }
.booking-step.active    { background: #0d6efd; color: #fff; border: 2px solid #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,.2); }
.booking-step.pending   { background: #fff; color: #adb5bd; border: 2px solid #dee2e6; }
.step-label { font-size: 0.78rem; color: #6c757d; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.step-label.active { color: #0d6efd; }
.step-label.completed { color: #198754; }

/* ── Form ─────────────────────────────────── */
.qty-btn {
    width: 36px; height: 36px;
    border-radius: 50%; border: 2px solid #dee2e6;
    background: #fff; cursor: pointer; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.qty-btn:hover { border-color: #0d6efd; color: #0d6efd; background: #e7f1ff; }
.qty-val { font-size: 1.2rem; font-weight: 700; min-width: 40px; text-align: center; }

/* ── Summary Card ─────────────────────────── */
.summary-card { border-radius: 20px; overflow: hidden; border: none; }
.summary-header {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    padding: 22px 20px 32px;
    position: relative;
}
.summary-header::after {
    content: ''; position: absolute;
    bottom: -1px; left: 0; right: 0; height: 20px;
    background: #fff; border-radius: 20px 20px 0 0;
}
.price-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
.availability-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fff3cd; color: #856404;
    border-radius: 8px; padding: 6px 12px; font-size: .82rem; font-weight: 600;
}
</style>

<div class="container" style="padding-top: 40px; padding-bottom: 60px;">

    <!-- ── Stepper ───────────────────────────────── -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between text-center booking-stepper">
                <div>
                    <div class="booking-step completed"><i class="fas fa-check"></i></div>
                    <div class="step-label completed">Chọn Tour</div>
                </div>
                <div>
                    <div class="booking-step active">2</div>
                    <div class="step-label active">Thông Tin</div>
                </div>
                <div>
                    <div class="booking-step pending">3</div>
                    <div class="step-label">Thanh Toán</div>
                </div>
                <div>
                    <div class="booking-step pending">4</div>
                    <div class="step-label">Hoàn Tất</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- ── Left: Form ───────────────────────── -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">

                    <h3 class="fw-bold mb-1 text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>Thông Tin Đặt Tour
                    </h3>
                    <p class="text-muted mb-4 small">Điền thông tin người đại diện đặt tour</p>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger border-0 bg-danger-subtle rounded-3 d-flex align-items-start gap-2 mb-4">
                        <i class="fas fa-exclamation-circle mt-1 text-danger"></i>
                        <div><?= $error ?></div>
                    </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>?action=booking-store" method="POST" id="bookingForm">
                        <input type="hidden" name="tour_id"      value="<?= $tour['id'] ?>">
                        <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">

                        <!-- Thông tin liên hệ -->
                        <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-3">
                            Thông Tin Liên Hệ
                        </h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light border-0" id="full_name" name="full_name" placeholder="Họ và tên" required
                                           value="<?= (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'customer') ? htmlspecialchars($_SESSION['user']['full_name'] ?? '') : '' ?>">
                                    <label for="full_name">Họ và tên <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control bg-light border-0" id="phone" name="phone" placeholder="Số điện thoại" required
                                           value="<?= (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'customer') ? htmlspecialchars($_SESSION['user']['phone'] ?? '') : '' ?>">
                                    <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control bg-light border-0" id="email" name="email" placeholder="Email" required
                                           value="<?= (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'customer') ? htmlspecialchars($_SESSION['user']['email'] ?? '') : '' ?>">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light border-0" id="address" name="address" placeholder="Địa chỉ"
                                           value="<?= (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'customer') ? htmlspecialchars($_SESSION['user']['address'] ?? '') : '' ?>">
                                    <label for="address">Địa chỉ</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control bg-light border-0" id="note" name="note" placeholder="Ghi chú" style="height:90px"></textarea>
                                    <label for="note">Ghi chú (ăn kiêng, dị ứng, yêu cầu đặc biệt...)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Số lượng khách -->
                        <?php
                        $availableSeats = $departure['max_seats'] - ($departure['booked_seats'] ?? 0);
                        $priceAdult = $departure['price_adult'] > 0 ? $departure['price_adult'] : $tour['base_price'];
                        $priceChild = $departure['price_child'] > 0 ? $departure['price_child'] : round($priceAdult * 0.7);
                        ?>
                        <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-3">
                            Số Lượng Khách
                        </h5>

                        <div class="mb-2">
                            <span class="availability-badge">
                                <i class="fas fa-chair"></i>
                                Còn <strong><?= $availableSeats ?></strong> chỗ trống
                            </span>
                        </div>

                        <div class="bg-light rounded-4 p-4 mb-5">
                            <!-- Người lớn -->
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div>
                                    <p class="fw-bold mb-0">Người lớn</p>
                                    <small class="text-muted">Từ 12 tuổi trở lên</small>
                                </div>
                                <div class="text-end me-4">
                                    <span class="text-primary fw-bold"><?= number_format($priceAdult, 0, ',', '.') ?>đ</span>
                                    <small class="text-muted d-block">/người</small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="qty-btn" onclick="updateQty('adults', -1, 1, <?= $availableSeats ?>)">
                                        <i class="fas fa-minus small"></i>
                                    </button>
                                    <span class="qty-val" id="adults-val">1</span>
                                    <input type="hidden" name="adults" id="adults" value="1">
                                    <button type="button" class="qty-btn" onclick="updateQty('adults', 1, 1, <?= $availableSeats ?>)">
                                        <i class="fas fa-plus small"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Trẻ em -->
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div>
                                    <p class="fw-bold mb-0">Trẻ em</p>
                                    <small class="text-muted">Từ 5 – 11 tuổi</small>
                                </div>
                                <div class="text-end me-4">
                                    <span class="text-primary fw-bold"><?= number_format($priceChild, 0, ',', '.') ?>đ</span>
                                    <small class="text-muted d-block">/người</small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="qty-btn" onclick="updateQty('children', -1, 0, <?= $availableSeats ?>)">
                                        <i class="fas fa-minus small"></i>
                                    </button>
                                    <span class="qty-val" id="children-val">0</span>
                                    <input type="hidden" name="children" id="children" value="0">
                                    <button type="button" class="qty-btn" onclick="updateQty('children', 1, 0, <?= $availableSeats ?>)">
                                        <i class="fas fa-plus small"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Em bé -->
                            <div class="d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <p class="fw-bold mb-0">Em bé</p>
                                    <small class="text-muted">Dưới 5 tuổi – <span class="text-success fw-bold">Miễn phí</span></small>
                                </div>
                                <div class="text-end me-4">
                                    <span class="text-success fw-bold">0đ</span>
                                    <small class="text-muted d-block">/người</small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="qty-btn" onclick="updateQty('infants', -1, 0, 99)">
                                        <i class="fas fa-minus small"></i>
                                    </button>
                                    <span class="qty-val" id="infants-val">0</span>
                                    <input type="hidden" name="infants" id="infants" value="0">
                                    <button type="button" class="qty-btn" onclick="updateQty('infants', 1, 0, 99)">
                                        <i class="fas fa-plus small"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cảnh báo chưa đăng nhập -->
                        <?php if (!isset($_SESSION['user'])): ?>
                        <div class="alert alert-info border-0 bg-info-subtle small mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <a href="<?= BASE_URL ?>?action=login" class="fw-bold">Đăng nhập</a> để xem lịch sử đặt tour và nhận ưu đãi thành viên.
                        </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="text-decoration-none text-muted fw-bold">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                Tiếp tục <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── Right: Order Summary ──────────────── -->
        <div class="col-lg-4">
            <div class="card summary-card shadow-lg sticky-top" style="top: 90px;">
                <div class="summary-header">
                    <h5 class="mb-0 fw-bold text-white"><i class="fas fa-receipt me-2"></i>Tóm Tắt Đặt Tour</h5>
                </div>
                <div class="card-body p-4 pt-3">
                    <!-- Tour info -->
                    <div class="d-flex mb-4">
                        <?php
                        $thumb = 'https://via.placeholder.com/80';
                        $gallery = $this->tourModel->getRelatedData('tour_gallery_images', $tour['id']);
                        if (!empty($gallery)) $thumb = BASE_ASSETS_UPLOADS . $gallery[0]['image_url'];
                        ?>
                        <img src="<?= $thumb ?>" class="rounded-3 object-fit-cover shadow-sm flex-shrink-0"
                             style="width:75px;height:75px;" alt="<?= htmlspecialchars($tour['name']) ?>">
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1" style="line-height:1.3"><?= htmlspecialchars($tour['name']) ?></h6>
                            <span class="badge bg-light text-secondary border small">
                                <i class="far fa-clock me-1"></i><?= $tour['duration_days'] ?? 'N/A' ?> Ngày
                            </span>
                        </div>
                    </div>

                    <!-- Chuyến đi -->
                    <div class="bg-light rounded-3 p-3 mb-4 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Ngày khởi hành</span>
                            <span class="fw-bold"><?= date('d/m/Y', strtotime($departure['departure_date'])) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Mã Tour</span>
                            <span class="fw-bold text-primary">TOUR-<?= $tour['id'] ?></span>
                        </div>
                    </div>

                    <hr class="opacity-10">

                    <!-- Giá chi tiết -->
                    <div class="mb-3 small" id="price-breakdown">
                        <div class="price-row">
                            <span class="text-muted">Người lớn × <span id="sum-adults">1</span></span>
                            <span class="fw-bold" id="sum-adults-price">—</span>
                        </div>
                        <div class="price-row">
                            <span class="text-muted">Trẻ em × <span id="sum-children">0</span></span>
                            <span class="fw-bold" id="sum-children-price">0đ</span>
                        </div>
                        <div class="price-row">
                            <span class="text-muted">Em bé × <span id="sum-infants">0</span></span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top border-2">
                        <span class="fw-bold text-muted">Tổng cộng</span>
                        <span class="h4 fw-bold text-primary mb-0" id="grand-total">—</span>
                    </div>

                    <div class="mt-3 small text-center">
                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>Đã bao gồm thuế & phí dịch vụ</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const PRICE_ADULT  = <?= $priceAdult ?>;
const PRICE_CHILD  = <?= $priceChild ?>;
const MAX_SEATS    = <?= $availableSeats ?>;

function updateQty(field, delta, minVal, maxSeats) {
    const hiddenInput = document.getElementById(field);
    const display     = document.getElementById(field + '-val');
    let current = parseInt(hiddenInput.value) || 0;
    current = Math.max(minVal, current + delta);

    // Kiểm tra tổng chỗ (adults + children <= max_seats)
    const adults   = parseInt(document.getElementById('adults').value)   || 0;
    const children = parseInt(document.getElementById('children').value) || 0;
    const total    = (field === 'adults'   ? current : adults)
                   + (field === 'children' ? current : children);

    if (field !== 'infants' && total > MAX_SEATS) {
        showSeatWarning();
        return;
    }

    hiddenInput.value = current;
    display.textContent = current;
    updateSummary();
}

function updateSummary() {
    const adults   = parseInt(document.getElementById('adults').value)   || 0;
    const children = parseInt(document.getElementById('children').value) || 0;
    const infants  = parseInt(document.getElementById('infants').value)  || 0;

    document.getElementById('sum-adults').textContent   = adults;
    document.getElementById('sum-children').textContent = children;
    document.getElementById('sum-infants').textContent  = infants;

    const totalAdults   = adults   * PRICE_ADULT;
    const totalChildren = children * PRICE_CHILD;
    const grandTotal    = totalAdults + totalChildren;

    document.getElementById('sum-adults-price').textContent   = fmt(totalAdults);
    document.getElementById('sum-children-price').textContent = fmt(totalChildren);
    document.getElementById('grand-total').textContent        = fmt(grandTotal);
}

function fmt(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

function showSeatWarning() {
    const el = document.createElement('div');
    el.className = 'alert alert-warning border-0 py-2 px-3 small position-fixed bottom-0 end-0 m-3 shadow';
    el.style.zIndex = 9999;
    el.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Đã đạt giới hạn chỗ trống!';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
}

document.addEventListener('DOMContentLoaded', updateSummary);
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
