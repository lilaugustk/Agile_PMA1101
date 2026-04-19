<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- Hero Section -->
<div class="tour-hero position-relative">
    <?php 
    $heroImage = !empty($images) ? BASE_ASSETS_UPLOADS . $images[0]['image_url'] : 'https://via.placeholder.com/1920x600';
    // Find main image if set
    foreach($images as $img) {
        if (!empty($img['main_img'])) {
            $heroImage = BASE_ASSETS_UPLOADS . $img['image_url'];
            break;
        }
    }
    ?>
    <img src="<?= $heroImage ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-100 h-100 object-fit-cover">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.8));"></div>
    <div class="position-absolute top-50 start-50 translate-middle text-center w-75 hero-content">
        <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill text-uppercase letter-spacing-1"><?= htmlspecialchars($tour['category_name'] ?? 'General') ?></span>
        <h1 class="display-4 fw-bold mb-3 text-white" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8);"><?= htmlspecialchars($tour['name']) ?></h1>
        <?php if (!empty($tour['subtitle'])): ?>
            <p class="lead text-white opacity-100" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.8);"><?= htmlspecialchars($tour['subtitle']) ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container my-5" style="margin-top: -60px; position: relative; z-index: 10;">
    <div class="row">
        <!-- Left Content -->
        <div class="col-lg-8">
            <!-- Description -->
            <?php if (!empty(trim((string)($tour['description'] ?? '')))): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Giới thiệu tour</h3>
                <div class="bg-white p-4 rounded shadow-soft">
                    <div class="tour-description text-justify">
                        <?= nl2br((string)($tour['description'] ?? '')) ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Gallery -->
            <?php if (!empty($images)): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Thư viện ảnh</h3>
                <div class="row g-3">
                    <?php foreach(array_slice($images, 0, 6) as $img): ?>
                        <div class="col-md-4 col-6">
                            <img src="<?= BASE_ASSETS_UPLOADS . $img['image_url'] ?>" class="gallery-img w-100 shadow-sm" alt="Tour Image">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Itinerary Timeline -->
            <?php if (!empty($itinerarySchedule)): ?>
            <!-- Itinerary Accordion -->
            <section class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <h3 class="text-primary fw-bold mb-0"><i class="fas fa-map-marked-alt me-2"></i>Lịch trình chi tiết</h3>
                    <span class="badge bg-light text-primary ms-3 border"><?= count($itinerarySchedule) ?> Ngày</span>
                </div>
                
                <div class="accordion" id="itineraryAccordion">
                    <?php foreach($itinerarySchedule as $index => $item): ?>
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading<?= $index ?>">
                                <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> fw-bold py-3 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                                    <span class="badge bg-primary me-3 rounded-pill px-3 py-2">Ngày <?= $item['day_number'] ?></span>
                                    <span><?= htmlspecialchars($item['title'] ?? $item['day_label']) ?></span>
                                </button>
                            </h2>
                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#itineraryAccordion">
                                <div class="accordion-body bg-light p-4">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 d-none d-md-block">
                                            <i class="fas fa-route fa-2x text-primary opacity-50"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-md-4">
                                            <div class="text-muted text-justify">
                                                <?php 
                                                $content = trim($item['description'] ?? $item['activities'] ?? '');
                                                if ($content === '') {
                                                    echo '<div class="alert alert-light border border-dashed text-center text-muted mb-0"><i class="fas fa-pencil-alt me-2"></i>Nội dung chi tiết đang được cập nhật...</div>';
                                                } else {
                                                    echo nl2br($content);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            
            <style>
                .accordion-button:not(.collapsed) {
                    color: #0d6efd;
                    background-color: #fff;
                    box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
                }
                .accordion-button:focus {
                    box-shadow: none;
                    border-color: rgba(0,0,0,.125);
                }
                .accordion-button::after {
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
                }

                /* Capacity Styling */
                .day-cell.is-full {
                    background-color: #f1f5f9 !important;
                    color: #94a3b8 !important;
                    cursor: not-allowed !important;
                    pointer-events: none;
                    opacity: 0.8;
                }
                .full-label {
                    font-size: 10px;
                    color: #ef4444;
                    font-weight: bold;
                    margin-top: 2px;
                    text-transform: uppercase;
                }
                .hot-label {
                    font-size: 9px;
                    background: #fee2e2;
                    color: #dc2626;
                    padding: 1px 4px;
                    border-radius: 4px;
                    font-weight: 600;
                    margin-top: 2px;
                    border: 1px solid #fecaca;
                }
                .day-cell.disabled-too-soon {
                    cursor: not-allowed;
                    opacity: 0.6;
                    background: #f8fafc;
                }

                /* Sidebar Selected Date Modern Style */
                #sidebarSelectedDate {
                    background-color: #e6f4f1 !important;
                    border: 1px solid #c2e5de !important;
                    border-radius: 12px !important;
                    padding: 1rem !important;
                    color: #2d4a43 !important;
                }
                .selected-date-title {
                    font-size: 1.1rem;
                    font-weight: 800;
                    margin-bottom: 0.25rem;
                }
                .selected-date-price {
                    font-size: 1rem;
                    color: #059669;
                    font-weight: 700;
                }
                .selected-date-seats {
                    font-size: 0.85rem;
                    color: #4b5563;
                    margin-top: 0.5rem;
                    align-items: center;
                    gap: 6px;
                }

            </style>
            
            <?php endif; ?>
            
            <!-- Calendar Section -->
            <?php if (!empty($departures)): ?>
            <section id="tour-calendar-section" class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="mb-0 text-sapphire fw-bold">Chọn ngày khởi hành</h3>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <span class="rounded-circle bg-success" style="width:8px; height:8px;"></span> Còn chỗ
                        </div>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <span class="rounded-circle bg-danger" style="width:8px; height:8px;"></span> Sắp hết/Hết
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
                    <!-- Calendar Control -->
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button type="button" class="nav-btn shadow-sm" id="prevMonthBtn">
                                <i class="ph-bold ph-caret-left"></i>
                            </button>
                            <h5 class="month-label mb-0" id="currentMonthLabel">THÁNG -- / ----</h5>
                            <button type="button" class="nav-btn shadow-sm" id="nextMonthBtn">
                                <i class="ph-bold ph-caret-right"></i>
                            </button>
                        </div>
                        
                        <div class="calendar-grid" id="calendarGrid">
                            <!-- Generated via JS -->
                        </div>
                    </div>

                </div>
            </section>

            <input type="hidden" id="departureSelect" name="departure_id" value="">

            <style>
                .bg-sapphire-light { background-color: #f0f7ff; }
                .bg-primary-gradient { background: linear-gradient(135deg, #0d6efd 0%, #0056d2 100%); }
                .calendar-container { background: #fff; border-radius: 12px; overflow: hidden; }
                .calendar-header { padding: 15px 0; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
                .month-label { font-size: 1.1rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
                .nav-btn {
                    width: 38px; height: 38px; border-radius: 10px; border: 1.5px solid #e2e8f0;
                    background: #fff; color: #64748b; transition: all 0.2s; display: flex; align-items: center; justify-content: center;
                }
                .nav-btn:hover:not(:disabled) { background: #f8fafc; border-color: #0d6efd; color: #0d6efd; transform: translateY(-2px); }
                .nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }

                .calendar-grid {
                    display: grid; grid-template-columns: repeat(7, 1fr);
                    background-color: #f1f5f9; gap: 1px; border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden;
                    width: 100%;
                }
                .weekday-header {
                    background-color: #f8fafc; padding: 15px 0; text-align: center;
                    font-weight: 800; font-size: 0.75rem; color: #64748b; text-transform: uppercase;
                }
                .day-cell {
                    min-height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center;
                    padding: 8px 4px; background-color: #fff; position: relative; transition: all 0.2s;
                    border: 1px solid transparent;
                }
                .day-num { font-weight: 700; color: #94a3b8; font-size: 1.05rem; }
                
                .day-cell.has-departure { 
                    cursor: pointer; 
                    background-color: #fcfdfe;
                }
                .day-cell.has-departure:hover { background-color: #f8faff; z-index: 1; border-color: #0d6efd !important; }
                .day-cell.has-departure .day-num { color: #1e293b; }

                /* Status Borders */
                .day-cell.border-status-success { border: 1.5px solid rgba(25, 135, 84, 0.3) !important; }
                .day-cell.border-status-danger { border: 1.5px solid rgba(220, 53, 69, 0.3) !important; }
                .day-cell.border-status-gray { border: 1.5px solid rgba(100, 116, 139, 0.2) !important; }

                .day-cell.selected { background-color: #eff6ff !important; border-color: #0d6efd !important; z-index: 2; border-width: 2px !important; }
                .day-cell.selected .day-num { color: #0d6efd; font-weight: 800; }

                .occ-label { font-size: 0.65rem; color: #64748b; font-weight: 700; margin-top: 2px; }

                .hot-label {
                    font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;
                    text-transform: uppercase; margin-top: 6px; letter-spacing: 0.3px;
                }
                .bg-danger.hot-label { background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; }
                .bg-secondary.hot-label { background: #475569; color: #ffffff; border: 1px solid #334155; }
                
                .day-cell.disabled-too-soon { background-color: #fcfcfc; cursor: not-allowed; }
                .day-cell.disabled-too-soon .day-num, .day-cell.disabled-too-soon .day-price { opacity: 0.4; }
                .day-cell.is-full { background-color: #fff1f2; }
                .inactive-pad { background-color: #f8fafc; opacity: 0.5; }
            </style>

            <script>
                // Pass PHP data to JS
                const departures = <?= json_encode(array_map(function($d) use ($tour) {
                    return [
                        'id' => $d['id'],
                        'date' => substr($d['departure_date'], 0, 10),
                        'price' => $d['price_adult'] > 0 ? (float)$d['price_adult'] : (float)$tour['base_price'],
                        'max_seats' => (int)$d['max_seats'],
                        'booked_seats' => (int)($d['booked_seats'] ?? 0),
                        'available_seats' => (int)$d['max_seats'] - (int)($d['booked_seats'] ?? 0)
                    ];
                }, $departures)) ?>;
                
                let currentMonth = new Date();
                currentMonth.setDate(1); 
                const now = new Date();
                now.setHours(0,0,0,0);

                document.addEventListener('DOMContentLoaded', function() {
                    const futureDeps = departures.filter(d => new Date(d.date) >= now);
                    if (futureDeps.length > 0) {
                        const firstDate = new Date(futureDeps[0].date);
                        currentMonth = new Date(firstDate.getFullYear(), firstDate.getMonth(), 1);
                    }
                    renderCalendar(currentMonth);
                });

                function renderCalendar(date) {
                    const gridEl = document.getElementById('calendarGrid');
                    const labelEl = document.getElementById('currentMonthLabel');
                    if (!gridEl || !labelEl) return;

                    const year = date.getFullYear();
                    const month = date.getMonth();
                    const monthNames = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
                    labelEl.innerText = `${monthNames[month]} / ${year}`;
                    
                    gridEl.innerHTML = '';
                    ["CN","T2","T3","T4","T5","T6","T7"].forEach((day, i) => {
                        const h = document.createElement('div');
                        h.className = 'weekday-header';
                        if (i === 0 || i === 6) h.classList.add('text-danger');
                        h.innerText = day;
                        gridEl.appendChild(h);
                    });

                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    for (let i = 0; i < firstDay; i++) {
                        const p = document.createElement('div');
                        p.className = 'day-cell inactive-pad';
                        gridEl.appendChild(p);
                    }

                    const selectedId = document.getElementById('departureSelect').value;
                    const minBookDate = new Date();
                    minBookDate.setDate(minBookDate.getDate() + 7);

                    for (let day = 1; day <= daysInMonth; day++) {
                        const dStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const div = document.createElement('div');
                        div.className = 'day-cell';
                        const num = document.createElement('span');
                        num.className = 'day-num';
                        num.innerText = day;
                        div.appendChild(num);

                        const departure = departures.find(d => d.date === dStr);
                        if (departure) {
                            const isFull = departure.available_seats <= 0;
                            const isTooSoon = new Date(dStr) < minBookDate;
                            const isPast = new Date(dStr) < now;

                            if (isPast) {
                                div.classList.add('inactive-pad');
                            } else if (isFull) {
                                div.classList.add('is-full', 'border-status-danger');
                                div.title = "Tour này đã đủ số lượng khách. Vui lòng chọn ngày khác.";
                                const lbl = document.createElement('div');
                                lbl.className = 'hot-label bg-danger';
                                lbl.innerText = 'Hết chỗ';
                                div.appendChild(lbl);
                            } else if (isTooSoon) {
                                div.classList.add('disabled-too-soon', 'border-status-gray');
                                div.title = "Hệ thống chỉ nhận đặt tour trực tuyến tối thiểu 7 ngày trước khi khởi hành. Vui lòng liên hệ Hotline để được hỗ trợ đặt gấp.";
                                const lbl = document.createElement('div');
                                lbl.className = 'hot-label bg-secondary';
                                lbl.innerText = 'Đóng nhận';
                                div.appendChild(lbl);
                            } else {
                                div.classList.add('has-departure');
                                if (selectedId == departure.id) div.classList.add('selected');

                                // Quy tắc viền: Còn ít (<=5) -> Đỏ, Còn nhiều -> Xanh
                                if (departure.available_seats <= 5) {
                                    div.classList.add('border-status-danger');
                                } else {
                                    div.classList.add('border-status-success');
                                }

                                // Show occupancy
                                const occ = document.createElement('div');
                                occ.className = 'occ-label';
                                occ.innerHTML = `<i class="ph ph-users me-1 small"></i>${departure.booked_seats}/${departure.max_seats}`;
                                div.appendChild(occ);

                                if (departure.available_seats <= 5) {
                                    const hot = document.createElement('div');
                                    hot.className = 'hot-label bg-danger';
                                    hot.innerText = 'Sắp hết';
                                    div.appendChild(hot);
                                }
                                div.onclick = () => selectDeparture(departure, div);
                            }
                        }
                        gridEl.appendChild(div);
                    }
                    document.getElementById('prevMonthBtn').disabled = (date.getFullYear() === now.getFullYear() && date.getMonth() === now.getMonth());
                }

                function selectDeparture(departure, element) {
                    document.getElementById('departureSelect').value = departure.id;
                    document.querySelectorAll('.day-cell').forEach(el => el.classList.remove('selected'));
                    element.classList.add('selected');

                    const d = new Date(departure.date);
                    const formattedDate = `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;

                    let statusText = 'Còn chỗ';
                    let badgeClass = 'bg-success';
                    if (departure.available_seats <= 5) {
                        statusText = 'Sắp hết';
                        badgeClass = 'bg-warning text-dark';
                    }

                    // Cập nhật giá tour trong sidebar
                    const priceDisplay = document.getElementById('main-tour-price');
                    if (priceDisplay) {
                        priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(departure.price);
                        priceDisplay.style.color = '#ef4444';
                        setTimeout(() => { priceDisplay.style.color = ''; }, 500);
                    }
                    
                    // Cập nhật thẻ tóm tắt Sidebar (Sapphire Blue Style)
                    const sidebarPreview = document.getElementById('sidebarSelectedDate');
                    const sidebarText = document.getElementById('sidebarDateText');
                    if (sidebarPreview && sidebarText) {
                        sidebarText.innerHTML = `
                            <div class="p-3 bg-sapphire-soft rounded-4 mb-3 border-0 shadow-sm animate__animated animate__fadeIn">
                                <div class="small text-primary fw-bold uppercase ls-1 mb-1" style="font-size: 0.65rem;">NGÀY KHỞI HÀNH ĐÃ CHỌN</div>
                                <div class="fw-bold text-dark fs-5 mb-2">${formattedDate}</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="badge ${badgeClass} rounded-pill px-2 py-1">${statusText}</span>
                                    <span class="small text-muted fw-bold"><i class="ph ph-users me-1"></i> ${departure.booked_seats}/${departure.max_seats}</span>
                                </div>
                            </div>
                        `;
                        sidebarPreview.classList.remove('d-none');
                    }
                    
                    const btnBooking = document.getElementById('btn-submit-booking');
                    if (btnBooking) btnBooking.disabled = false;
                }

                function formatCompactPrice(price) {
                    if (price >= 1000000) return (price / 1000000).toFixed(1).replace(/\.0$/, '') + 'tr';
                    if (price >= 1000) return (price / 1000).toFixed(0) + 'k';
                    return price;
                }

                document.getElementById('prevMonthBtn').onclick = () => {
                    currentMonth.setMonth(currentMonth.getMonth() - 1);
                    renderCalendar(currentMonth);
                };
                document.getElementById('nextMonthBtn').onclick = () => {
                    currentMonth.setMonth(currentMonth.getMonth() + 1);
                    renderCalendar(currentMonth);
                };
            </script>
            <?php endif; ?>
            
            <!-- Policies -->
            <?php if (!empty($policies)): ?>
            <section class="mb-5">
                <h3 class="mb-4 text-primary">Chính sách & Điều khoản</h3>
                <div class="row g-4">
                    <?php foreach($policies as $policy): ?>
                        <div class="col-12">
                            <div class="policy-card p-4 rounded h-100">
                                <h5 class="card-title text-secondary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i><?= htmlspecialchars($policy['name']) ?>
                                </h5>
                                <div class="card-text text-muted">
                                    <?= nl2br($policy['description']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <!-- Right Stick Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <div class="booking-card card border-0 shadow-premium mb-4 overflow-hidden rounded-4">
                    <div class="card-header text-center bg-white border-0 pt-4 pb-2">
                        <p class="text-muted mb-1 text-uppercase small fw-bold ls-1 opacity-75">Giá trọn gói chỉ từ</p>
                        <div class="booking-price text-sapphire d-flex align-items-baseline justify-content-center gap-1">
                            <span class="fs-1 fw-black" id="main-tour-price"><?= number_format($tour['base_price'], 0, ',', '.') ?></span>
                            <span class="fs-5 fw-bold">đ</span>
                        </div>
                    </div>
                    <div class="card-body p-4 booking-form pt-0">
                        <!-- Selected Departure Preview (Sapphire Styled) -->
                        <div id="sidebarSelectedDate" class="d-none">
                            <div id="sidebarDateText"></div>
                        </div>

                        <div class="mb-4 text-center">
                            <button class="btn btn-premium-outline w-100 rounded-pill mb-2 transition-all" onclick="document.getElementById('tour-calendar-section').scrollIntoView({behavior: 'smooth', block: 'center'})">
                                <i class="ph ph-calendar-blank me-2"></i>Xem lịch & chọn ngày
                            </button>
                        </div>
                        
                        <div class="d-grid">
                            <button class="btn btn-primary-gradient text-white btn-lg rounded-pill shadow-sapphire py-3 fw-bold transition-all" type="submit" id="btn-submit-booking" onclick="bookNow()" disabled>
                                <i class="ph ph-paper-plane-tilt me-2 fs-5"></i>ĐẶT TOUR NGAY
                            </button>
                        </div>
                    </div>
                </div>

                <style>
                    .bg-sapphire-soft { background-color: #f0f7ff; border: 1px solid #d0e7ff; }
                    .text-sapphire-dark { color: #0056d2; }
                    .shadow-premium { box-shadow: 0 20px 40px rgba(0,0,0,0.06) !important; }
                    .text-sapphire { color: #1e293b; }
                    .fw-black { font-weight: 900; }
                    .btn-premium-outline { border: 1.5px solid #dee2e6; color: #475569; font-weight: 600; padding: 0.6rem; }
                    .btn-premium-outline:hover { border-color: #0d6efd; color: #0d6efd; background: #f8faff; }
                    .btn-primary-gradient { background: linear-gradient(135deg, #0d6efd 0%, #0056d2 100%); border: none; letter-spacing: 0.5px; }
                    .btn-primary-gradient:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3); }
                    .btn-primary-gradient:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
                    .shadow-sapphire { box-shadow: 0 8px 16px rgba(13, 110, 253, 0.2); }
                    .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
                </style>

                <!-- Share & QR Code Card -->
                <div class="card shadow-premium border-0 rounded-4 overflow-hidden share-card">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-share-alt me-2 text-primary"></i>Chia sẻ Tour này</h6>
                        <div id="tour-qr-code" class="d-flex justify-content-center mb-3 p-2 bg-light rounded-3" style="min-height: 140px;">
                            <!-- QR Code will be rendered here -->
                        </div>
                        <p class="small text-muted mb-3">Quét mã QR để xem trên điện thoại hoặc chia sẻ nhanh cho bạn bè</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm rounded-pill" onclick="copyTourUrl()">
                                <i class="fas fa-link me-1"></i> Sao chép liên kết
                            </button>
                        </div>
                    </div>
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        new QRCode(document.getElementById("tour-qr-code"), {
                            text: window.location.href,
                            width: 140,
                            height: 140,
                            colorDark : "#1e293b",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    });

                    function copyTourUrl() {
                        navigator.clipboard.writeText(window.location.href).then(function() {
                            alert('Đã sao chép liên kết tour!');
                        }, function(err) {
                            console.error('Không thể sao chép: ', err);
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Script for bookNow (Moved out/kept global) -->
<script>
    function bookNow() {
        try {
            const departureSelect = document.getElementById('departureSelect');
            if (!departureSelect || !departureSelect.value) {
                alert('Vui lòng chọn ngày khởi hành từ lịch!');
                document.getElementById('tour-calendar-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            const tourId = <?= $tour['id'] ?>;
            const departureId = departureSelect.value;
            window.location.href = `<?= BASE_URL ?>?action=booking-create&tour_id=${tourId}&departure_id=${departureId}`;
        } catch (e) {
            console.error(e);
            alert('Có lỗi xảy ra: ' + e.message);
        }
    }
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
