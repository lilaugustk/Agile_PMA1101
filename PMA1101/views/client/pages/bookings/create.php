<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- 
    SAPPHIRE REBUILD: Booking Creation Page
    Version: 2.0 (High-Fidelity)
-->

<style>
/* ── Design System Tokens ────────────────── */
:root {
    --sapphire-primary: #0d6efd;
    --sapphire-gradient: linear-gradient(135deg, #0d6efd 0%, #0052cc 100%);
    --sapphire-soft: #f0f7ff;
    --sapphire-slate: #1e293b;
    --sapphire-text-muted: #64748b;
    --sapphire-border: #e2e8f0;
    --sapphire-shadow: 0 12px 40px rgba(0,0,0,0.06);
    --sapphire-shadow-sm: 0 4px 12px rgba(0,0,0,0.03);
}

body { 
    background-color: #f8fafc; 
    font-family: 'Inter', system-ui, -apple-system, sans-serif; 
    color: var(--sapphire-slate);
}

/* ── Typography & Helpers ────────────────── */
.text-sapphire { color: var(--sapphire-primary); }
.bg-sapphire-soft { background-color: var(--sapphire-soft); }
.fw-800 { font-weight: 800; }
.ls-1 { letter-spacing: 0.5px; }
.shadow-premium { box-shadow: var(--sapphire-shadow); }
.rounded-4 { border-radius: 1rem !important; }

/* ── Stepper (Sapphire Style) ────────────── */
.stepper-container { position: relative; margin-top: 20px; }
.stepper-progress {
    position: absolute; top: 22px; left: 10%; right: 10%;
    height: 3px; background: #e2e8f0; z-index: 0;
}
.stepper-progress-active {
    position: absolute; top: 0; left: 0; height: 100%;
    width: 33.33%; background: var(--sapphire-primary);
    transition: width 0.4s ease;
}
.step-item { position: relative; z-index: 1; flex: 1; text-align: center; }
.step-circle {
    width: 44px; height: 44px; border-radius: 50%;
    background: #fff; border: 2px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 800; color: #94a3b8; font-size: 0.95rem;
}
.step-item.active .step-circle {
    background: var(--sapphire-gradient); border-color: transparent;
    color: #fff; box-shadow: 0 0 0 6px rgba(13, 110, 253, 0.1);
}
.step-item.completed .step-circle {
    background: #198754; border-color: transparent; color: #fff;
}
.step-label {
    font-size: 0.65rem; font-weight: 800; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 1px;
}
.step-item.active .step-label { color: var(--sapphire-primary); }

/* ── Floating Inputs ──────────────────────── */
.cust-floating { position: relative; margin-bottom: 20px; }
.cust-floating .form-control, .cust-floating .form-select {
    height: 62px; background: #fff; border: 1.5px solid #e2e8f0;
    border-radius: 14px; padding: 1.5rem 1rem 0.6rem;
    font-weight: 600; color: var(--sapphire-slate);
    transition: all 0.2s;
}
.cust-floating select { padding-top: 1.5rem !important; }
.cust-floating label {
    position: absolute; top: 12px; left: 14px;
    font-size: 0.72rem; font-weight: 800; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 0.5px;
    pointer-events: none; transition: all 0.2s;
    line-height:1; display:flex; align-items:center;
}
.cust-floating .form-control:focus {
    border-color: var(--sapphire-primary);
    box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.08);
    background: #fff;
}

/* ── Qty Selectors ────────────────────────── */
.qty-box {
    display: flex; align-items: center; gap: 15px;
    background: #f8fafc; padding: 8px 12px; border-radius: 16px;
    border: 1.5px solid #e2e8f0;
}
.btn-qty {
    width: 32px; height: 32px; border-radius: 10px; border: none;
    background: #fff; color: var(--sapphire-slate); font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.btn-qty:hover:not(:disabled) { background: var(--sapphire-primary); color: #fff; transform: translateY(-1px); }
.btn-qty:disabled { opacity: 0.3; cursor: not-allowed; }
.qty-display { font-size: 1.1rem; font-weight: 800; min-width: 25px; text-align: center; }

/* ── Passenger Card ───────────────────────── */
.pass-card {
    background: #fff; border: 1.5px solid #f1f5f9; border-radius: 20px;
    margin-bottom: 24px; overflow: hidden; transition: all 0.3s;
}
.pass-card:hover { border-color: #e2e8f0; box-shadow: var(--sapphire-shadow); }
.pass-header {
    background: #f8fafc; padding: 15px 24px; border-bottom: 1.5px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.pass-title { font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

/* ── Sidebar Receipt ──────────────────────── */
.receipt-card {
    background: #fff; border: none; border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}
.receipt-header {
    padding: 24px 25px 15px; border-radius: 24px 24px 0 0;
    color: var(--sapphire-slate);
    border-bottom: 1.5px solid #f1f5f9;
}
.receipt-tour-img {
    width: 100%; height: 180px; object-fit: cover;
    border-radius: 16px; margin-bottom: 20px;
}
.price-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 0; border-bottom: 1px dashed #e2e8f0;
}
.price-row:last-child { border-bottom: none; }
</style>

<div class="container py-5 mt-4">

    <!-- ── Progression Stepper ──────────────────── -->
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="stepper-container">
                <div class="stepper-progress">
                    <div class="stepper-progress-active"></div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="step-item completed">
                        <div class="step-circle"><i class="ph-bold ph-check"></i></div>
                        <div class="step-label">Chọn Tour</div>
                    </div>
                    <div class="step-item active">
                        <div class="step-circle">2</div>
                        <div class="step-label">Thông Tin</div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">3</div>
                        <div class="step-label">Thanh Toán</div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">4</div>
                        <div class="step-label">Hoàn Tất</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        
        <!-- ── Left Column: Form Fields ────────── -->
        <div class="col-lg-8 animate__animated animate__fadeIn">
            
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-sapphire-soft rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                    <i class="ph-fill ph-clipboard-text fs-3 text-sapphire"></i>
                </div>
                <div>
                    <h2 class="fw-800 mb-0">Thông Tin Đặt Tour</h2>
                    <p class="text-muted small mb-0">Vui lòng điền chính xác thông tin để chúng tôi phục vụ bạn tốt nhất</p>
                </div>
            </div>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                <i class="ph-fill ph-warning-circle fs-4"></i>
                <div class="fw-bold small"><?= $error ?></div>
            </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>?action=booking-store" method="POST" id="bookingForm">
                <input type="hidden" name="tour_id"      value="<?= $tour['id'] ?>">
                <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">

                <!-- SECTION 1: CONTACT -->
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                                <span class="bg-sapphire-soft text-sapphire rounded-circle d-flex align-items-center justify-content-center" style="width:30px; height:30px; font-size:0.8rem">1</span>
                                THÔNG TIN LIÊN HỆ
                            </h5>
                            <span class="badge bg-light text-muted fw-bold">BẮT BUỘC *</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder=" " required oninput="syncLeadPassenger()"
                                           value="<?= isset($userProfile['full_name']) ? htmlspecialchars($userProfile['full_name']) : ((isset($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['full_name']) : '') ?>">
                                    <label><i class="ph ph-user-focus me-2"></i>Họ tên người đặt *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cust-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder=" " required oninput="syncLeadPassenger()"
                                           value="<?= isset($userProfile['phone']) ? htmlspecialchars($userProfile['phone']) : ((isset($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['phone']) : '') ?>">
                                    <label><i class="ph ph-phone-call me-2"></i>Số điện thoại *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cust-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder=" " required oninput="syncLeadPassenger()"
                                           value="<?= isset($userProfile['email']) ? htmlspecialchars($userProfile['email']) : ((isset($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['email']) : '') ?>">
                                    <label><i class="ph ph-envelope-simple me-2"></i>Email nhận vé *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cust-floating">
                                    <input type="text" class="form-control" id="contact_address" name="contact_address" placeholder=" " oninput="syncLeadPassenger()"
                                           value="<?= isset($userProfile['address']) ? htmlspecialchars($userProfile['address']) : '' ?>">
                                    <label><i class="ph ph-map-pin-line me-2"></i>Địa chỉ thường trú</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cust-floating">
                                    <textarea class="form-control" id="note" name="note" placeholder=" " style="height: 100px"></textarea>
                                    <label><i class="ph ph-pencil-line me-2"></i>Ghi chú hoặc yêu cầu đặc biệt</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: PARTY SIZE -->
                <?php
                $availableSeats = $departure['max_seats'] - ($departure['booked_seats'] ?? 0);
                $priceAdult = $departure['price_adult'] > 0 ? $departure['price_adult'] : $tour['base_price'];
                $priceChild = $departure['price_child'] > 0 ? $departure['price_child'] : round($priceAdult * 0.7);
                ?>
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                                <span class="bg-sapphire-soft text-sapphire rounded-circle d-flex align-items-center justify-content-center" style="width:30px; height:30px; font-size:0.8rem">2</span>
                                SỐ LƯỢNG HÀNH KHÁCH
                            </h5>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">
                                <i class="ph-bold ph-armchair me-1"></i>Còn <?= $availableSeats ?> chỗ
                            </span>
                        </div>

                        <div class="row g-4">
                            <!-- Adults -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded-4 d-flex flex-column align-items-center text-center bg-light-subtle">
                                    <div class="fw-800 text-dark mb-1">Người lớn</div>
                                    <div class="small text-muted mb-3">Từ 12 tuổi</div>
                                    <div class="qty-box">
                                        <button type="button" class="btn-qty" onclick="changeQty('adults', -1, 1)"><i class="ph-bold ph-minus"></i></button>
                                        <span class="qty-display" id="adults-val">1</span>
                                        <input type="hidden" name="adults" id="adults" value="1">
                                        <button type="button" class="btn-qty" onclick="changeQty('adults', 1, 1)"><i class="ph-bold ph-plus"></i></button>
                                    </div>
                                    <div class="mt-3 text-sapphire fw-bold"><?= number_format($priceAdult, 0, ',', '.') ?>đ</div>
                                </div>
                            </div>
                            <!-- Children -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded-4 d-flex flex-column align-items-center text-center">
                                    <div class="fw-800 text-dark mb-1">Trẻ em</div>
                                    <div class="small text-muted mb-3">Trẻ từ 5 - 11 tuổi</div>
                                    <div class="qty-box">
                                        <button type="button" class="btn-qty" onclick="changeQty('children', -1, 0)"><i class="ph-bold ph-minus"></i></button>
                                        <span class="qty-display" id="children-val">0</span>
                                        <input type="hidden" name="children" id="children" value="0">
                                        <button type="button" class="btn-qty" onclick="changeQty('children', 1, 0)"><i class="ph-bold ph-plus"></i></button>
                                    </div>
                                    <div class="mt-3 text-sapphire fw-bold"><?= number_format($priceChild, 0, ',', '.') ?>đ</div>
                                </div>
                            </div>
                            <!-- Infants -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded-4 d-flex flex-column align-items-center text-center">
                                    <div class="fw-800 text-dark mb-1">Em bé</div>
                                    <div class="small text-muted mb-3">Dưới 5 tuổi</div>
                                    <div class="qty-box">
                                        <button type="button" class="btn-qty" onclick="changeQty('infants', -1, 0)"><i class="ph-bold ph-minus"></i></button>
                                        <span class="qty-display" id="infants-val">0</span>
                                        <input type="hidden" name="infants" id="infants" value="0">
                                        <button type="button" class="btn-qty" onclick="changeQty('infants', 1, 0)"><i class="ph-bold ph-plus"></i></button>
                                    </div>
                                    <div class="mt-3 text-success fw-bold">Miễn phí</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: PASSENGERS -->
                <div class="mb-5">
                    <h5 class="fw-800 mb-4 d-flex align-items-center gap-2">
                        <span class="bg-sapphire-soft text-sapphire rounded-circle d-flex align-items-center justify-content-center" style="width:30px; height:30px; font-size:0.8rem">3</span>
                        THÔNG TIN CHI TIẾT ĐOÀN KHÁCH
                    </h5>
                    <div id="passenger-container">
                        <!-- Rendered via JS -->
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-4 bg-white rounded-4 shadow-sm">
                    <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-light px-4 py-2 rounded-pill fw-bold text-muted">
                        <i class="ph ph-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-800 shadow-premium" style="background: var(--sapphire-gradient); border:none">
                        TIẾP TỤC THANH TOÁN <i class="ph-bold ph-arrow-right ms-2"></i>
                    </button>
                </div>

            </form>
        </div>

        <!-- ── Right Column: Sidebar ─────────────── -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 90px;">
                <div class="receipt-card animate__animated animate__fadeInRight">
                    <div class="receipt-header">
                        <h5 class="mb-0 fw-800"><i class="ph-fill ph-receipt me-2"></i>Tóm Tắt Đơn Tour</h5>
                    </div>
                    <div class="p-4">
                        <?php
                        $thumb = 'https://via.placeholder.com/400x200';
                        $gallery = $this->tourModel->getRelatedData('tour_gallery_images', $tour['id']);
                        if (!empty($gallery)) $thumb = BASE_ASSETS_UPLOADS . $gallery[0]['image_url'];
                        ?>
                        <img src="<?= $thumb ?>" class="receipt-tour-img shadow-sm" alt="Tour Thumbnail">
                        
                        <h6 class="fw-800 text-dark mb-3" style="line-height:1.4"><?= htmlspecialchars($tour['name']) ?></h6>
                        
                        <div class="bg-light p-3 rounded-4 mb-4 small border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted fw-bold">Ngày đi</span>
                                <span class="fw-800"><?= date('d/m/Y', strtotime($departure['departure_date'])) ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted fw-bold">Mã Tour</span>
                                <span class="fw-800 text-sapphire">TOUR-<?= $tour['id'] ?></span>
                            </div>
                        </div>

                        <div id="price-details" class="mb-4">
                            <!-- Calculated via JS -->
                        </div>

                        <div class="pt-4 border-top border-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted fw-800 small uppercase ls-1">Tổng tiền thanh toán</span>
                                <div class="text-end">
                                    <div class="h3 mb-0 fw-800 text-primary" id="final-total">0đ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-4 bg-sapphire-soft border border-primary border-opacity-10 d-flex align-items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-shield-check fs-2 text-primary"></i>
                    <div class="small">
                        <div class="fw-800 text-dark">Thanh toán an toàn</div>
                        <div class="text-muted">Giao dịch của bạn luôn được bảo mật và mã hóa 256-bit</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const DATA = {
    priceAdult: <?= (int)($priceAdult ?? 0) ?>,
    priceChild: <?= (int)($priceChild ?? 0) ?>,
    maxSeats: <?= (int)($availableSeats ?? 0) ?>,
    profile: <?= json_encode($userProfile ?: (object)[]) ?>
};

function fmt(n) { return new Intl.NumberFormat('vi-VN').format(n) + 'đ'; }

/**
 * JS ENGINE: Real-time update logic
 */
function changeQty(field, delta, min) {
    const input = document.getElementById(field);
    const display = document.getElementById(field + '-val');
    if (!input || !display) return;

    let val = parseInt(input.value || 0) + delta;
    if (val < min) return;
    
    // Check occupancy for Adults + Children
    const curA = parseInt(document.getElementById('adults').value || 0);
    const curC = parseInt(document.getElementById('children').value || 0);
    const totalOccupancy = (field === 'infants') ? (curA + curC) : (field === 'adults' ? val + curC : curA + val);

    if (delta > 0 && field !== 'infants' && totalOccupancy > DATA.maxSeats) {
        alert("Rất tiếc, chuyến đi này chỉ còn " + DATA.maxSeats + " chỗ trống.");
        return;
    }

    input.value = val;
    display.innerText = val;
    
    updateAll();
}

function updateAll() {
    renderPassengerForms();
    calculatePrice();
}

function calculatePrice() {
    const a = parseInt(document.getElementById('adults').value || 0);
    const c = parseInt(document.getElementById('children').value || 0);
    const i = parseInt(document.getElementById('infants').value || 0);
    
    const sumA = a * DATA.priceAdult;
    const sumC = c * DATA.priceChild;
    const total = sumA + sumC;

    const html = `
        <div class="price-row">
            <span class="text-muted fw-bold small">Người lớn × ${a}</span>
            <span class="fw-800">${fmt(sumA)}</span>
        </div>
        <div class="price-row">
            <span class="text-muted fw-bold small">Trẻ em × ${c}</span>
            <span class="fw-800">${fmt(sumC)}</span>
        </div>
        <div class="price-row">
            <span class="text-muted fw-bold small">Em bé × ${i}</span>
            <span class="text-success fw-800 small uppercase">Miễn phí</span>
        </div>
    `;
    const detailsContainer = document.getElementById('price-details');
    const totalDisplay = document.getElementById('final-total');
    
    if (detailsContainer) detailsContainer.innerHTML = html;
    if (totalDisplay) totalDisplay.innerText = fmt(total);
}

function renderPassengerForms() {
    const container = document.getElementById('passenger-container');
    if (!container) return;

    const a = parseInt(document.getElementById('adults').value || 0);
    const c = parseInt(document.getElementById('children').value || 0);
    const i = parseInt(document.getElementById('infants').value || 0);
    
    const saved = {};
    container.querySelectorAll('input, select').forEach(el => {
        const id = el.id || el.name + '_' + (el.dataset.idx || '');
        saved[id] = el.value;
    });

    let html = '';
    let idx = 1;
    for(let k=0; k<a; k++) html += buildCard(idx++, 'adult');
    for(let k=0; k<c; k++) html += buildCard(idx++, 'child');
    for(let k=0; k<i; k++) html += buildCard(idx++, 'infant');
    
    container.innerHTML = html;
    
    container.querySelectorAll('input, select').forEach(el => {
        const id = el.id || el.name + '_' + (el.dataset.idx || '');
        if (saved[id]) el.value = saved[id];
        else if (DATA.profile && el.dataset.idx == "1") {
            // Auto-fill lead passenger from profile if empty
            if (el.name === 'companion_name[]' && DATA.profile.full_name) el.value = DATA.profile.full_name;
            if (el.name === 'companion_phone[]' && DATA.profile.phone) el.value = DATA.profile.phone;
            if (el.name === 'companion_gender[]' && DATA.profile.gender) el.value = DATA.profile.gender;
            if (el.name === 'companion_birth_date[]' && DATA.profile.birth_date) {
                // Convert YYYY-MM-DD to DD/MM/YYYY for the mask
                const d = DATA.profile.birth_date.split('-');
                if (d.length === 3) el.value = `${d[2]}/${d[1]}/${d[0]}`;
            }
            if (el.name === 'companion_id_card[]' && DATA.profile.id_card) el.value = DATA.profile.id_card;
            if (el.name === 'companion_email[]' && DATA.profile.email) el.value = DATA.profile.email;
            if (el.name === 'companion_address[]' && DATA.profile.address) el.value = DATA.profile.address;
        }
    });

    syncLeadPassenger();
    
    document.querySelectorAll('.birth-mask').forEach(m => m.oninput = applyMask);
}

function buildCard(idx, type) {
    const isAdult = type === 'adult';
    const label = type === 'adult' ? 'Người lớn' : (type === 'child' ? 'Trẻ em' : 'Em bé');
    const colorClass = type === 'adult' ? 'text-primary' : (type === 'child' ? 'text-success' : 'text-info');
    const isLead = idx === 1;

    return `
        <div class="pass-card animate__animated animate__fadeInUp">
            <div class="pass-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-circle" style="width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center">${idx}</span>
                    <span class="pass-title">Hành khách: <span class="${colorClass}">${label}</span></span>
                </div>
                ${isLead ? '<span class="badge bg-primary text-white small rounded-pill px-3">Người đại diện</span>' : ''}
            </div>
            <div class="p-4">
                <input type="hidden" name="companion_passenger_type[]" value="${type}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="cust-floating">
                            <input type="text" class="form-control" name="companion_name[]" data-idx="${idx}" placeholder=" " required ${isLead?'id="lead_name_link"':''}>
                            <label><i class="ph ph-user me-2"></i>Họ và tên *</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="cust-floating">
                            <select class="form-select" name="companion_gender[]" data-idx="${idx}">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                            <label><i class="ph ph-gender-intersex me-2"></i>Giới tính</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="cust-floating">
                            <input type="text" class="form-control birth-mask" name="companion_birth_date[]" data-idx="${idx}" placeholder=" " required maxlength="10">
                            <label><i class="ph ph-calendar me-2"></i>Ngày sinh *</label>
                        </div>
                    </div>

                    ${isAdult ? `
                    <div class="col-md-4">
                        <div class="cust-floating">
                            <input type="tel" class="form-control" name="companion_phone[]" data-idx="${idx}" placeholder=" " ${isLead?'id="lead_phone_link"':''}>
                            <label><i class="ph ph-phone me-2"></i>Số điện thoại</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cust-floating">
                            <input type="email" class="form-control" name="companion_email[]" data-idx="${idx}" placeholder=" " ${isLead?'id="lead_email_link"':''}>
                            <label><i class="ph ph-envelope me-2"></i>Email</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="cust-floating">
                            <input type="text" class="form-control" name="companion_id_card[]" data-idx="${idx}" placeholder=" ">
                            <label><i class="ph ph-identification-card me-2"></i>CCCD / Hộ chiếu</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="cust-floating">
                            <input type="text" class="form-control" name="companion_address[]" data-idx="${idx}" placeholder=" " ${isLead?'id="lead_address_link"':''}>
                            <label><i class="ph ph-map-pin me-2"></i>Địa chỉ thường trú</label>
                        </div>
                    </div>
                    ` : ''}

                    <div class="col-12">
                        <div class="cust-floating">
                            <input type="text" class="form-control" name="companion_note[]" data-idx="${idx}" placeholder=" ">
                            <label><i class="ph ph-note me-2"></i>Yêu cầu đặc biệt (Ghế ngồi, dị ứng thức ăn...)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function syncLeadPassenger() {
    const fName = document.getElementById('full_name');
    const fPhone = document.getElementById('phone');
    const fEmail = document.getElementById('email');
    const fAddr = document.getElementById('contact_address');
    
    const lName = document.getElementById('lead_name_link');
    const lPhone = document.getElementById('lead_phone_link');
    const lEmail = document.getElementById('lead_email_link');
    const lAddr = document.getElementById('lead_address_link');
    
    if (fName && lName) lName.value = fName.value;
    if (fPhone && lPhone) lPhone.value = fPhone.value;
    if (fEmail && lEmail) lEmail.value = fEmail.value;
    if (fAddr && lAddr) lAddr.value = fAddr.value;
}

function applyMask(e) {
    let v = e.target.value.replace(/\D/g,'');
    if (v.length > 8) v = v.substring(0,8);
    let f = '';
    if (v.length > 0) f = v.substring(0,2);
    if (v.length > 2) f += '/' + v.substring(2,4);
    if (v.length > 4) f += '/' + v.substring(4,8);
    e.target.value = f;
}

/**
 * Toast notification
 */
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} shadow-lg position-fixed bottom-0 end-0 m-4 px-4 py-3 rounded-4 transition-all`;
    toast.style.zIndex = 10000;
    toast.style.minWidth = '300px';
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    updateAll();
});
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
