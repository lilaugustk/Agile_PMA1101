<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="dashboard booking-edit-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header mb-4">
            <div class="header-content d-flex justify-content-between align-items-end">
                <div class="header-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 0.8rem; letter-spacing: 0.02em;">
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none d-flex align-items-center gap-1"><i class="ph-fill ph-house"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="text-muted text-decoration-none d-flex align-items-center gap-1"><i class="ph-fill ph-calendar-check"></i> Quản lý Booking</a></li>
                            <li class="breadcrumb-item active text-primary fw-600" aria-current="page">Chỉnh sửa #<?= $booking['id'] ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="header-right d-flex gap-2">
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=<?= $booking['id'] ?>" class="btn btn-light border-0 shadow-sm px-3 py-2 bg-white d-flex align-items-center gap-1">
                        <i class="ph ph-eye me-1"></i> Xem chi tiết
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-light border-0 shadow-sm px-3 py-2 bg-white">
                        <i class="ph ph-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" form="booking-edit-form" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-primary">
                        <i class="ph-fill ph-floppy-disk"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="ph-bold ph-warning-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="ph-bold ph-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <style>
            .card-header-premium {
                background: linear-gradient(to right, rgba(248, 250, 252, 0.5), transparent);
            }
            .form-label {
                font-size: 0.8rem;
                font-weight: 600;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.025em;
                margin-bottom: 0.5rem;
            }
            .form-control, .form-select {
                height: 48px !important;
                background-color: #fff !important;
                border: 1.5px solid #e2e8f0 !important;
                border-radius: 10px !important;
                font-weight: 500 !important;
                color: #1e293b !important;
                padding: 0.6rem 1rem !important;
                transition: all 0.2s ease;
            }
            .form-control:focus, .form-select:focus {
                border-color: #4f46e5 !important;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
                background-color: #fff !important;
            }
            .calendar-container {
                border: 1px solid #f1f5f9;
                border-radius: 12px;
                padding: 15px;
                background: #fff;
            }
            .calendar-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 15px;
            }
            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
            }
            .calendar-weekday {
                text-align: center;
                font-weight: 700;
                font-size: 0.7rem;
                color: #94a3b8;
                padding-bottom: 5px;
                text-transform: uppercase;
            }
            .day-cell {
                min-height: 50px;
                border: 1px solid #f1f5f9;
                border-radius: 8px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: default;
                transition: all 0.2s;
                position: relative;
            }
            .day-cell.available {
                cursor: pointer;
            }
            .day-cell.available:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            .day-cell.selected {
                border-color: var(--primary) !important;
                background-color: var(--primary-subtle) !important;
                box-shadow: 0 0 0 2px var(--primary) !important;
            }
            .day-cell.selected .day-num {
                color: var(--primary) !important;
                font-weight: 800;
            }
            .month-nav-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: 1px solid #e2e8f0;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #64748b;
                transition: all 0.2s;
            }
            .month-nav-btn:hover {
                background: #f8fafc;
                color: var(--primary);
                border-color: var(--primary);
            }
            .form-select-premium {
                cursor: pointer;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 256 256'%3E%3Cpath d='M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z'%3E%3C/path%3E%3C/svg%3E");
                background-position: right 1rem center;
                background-size: 1.25rem;
            }
            .companion-card-premium {
                border: 1px solid #eef2f6;
                transition: all 0.3s ease;
                background: #fff;
            }
            .companion-card-premium:hover {
                border-color: var(--primary-subtle);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
                transform: translateY(-2px);
            }
        </style>

        <!-- Booking Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/update" id="booking-edit-form">
            <input type="hidden" name="id" value="<?= $booking['id'] ?>">

            <div class="row">
                <div class="col-12">
                    <!-- Hidden Guest Counts -->
                    <input type="hidden" id="adults" name="adults" value="<?= $booking['adults'] ?? 1 ?>">
                    <input type="hidden" id="children" name="children" value="<?= $booking['children'] ?? 0 ?>">
                    <input type="hidden" id="infants" name="infants" value="<?= $booking['infants'] ?? 0 ?>">
                    <input type="hidden" id="total_price" name="total_price" value="<?= $booking['total_price'] ?? 0 ?>">

                    <!-- Tour Information -->
                    <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                        <div class="card-header-premium p-3 px-4 border-bottom border-light">
                            <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                                <i class="ph-fill ph-map-pin"></i> Chi tiết Tour &amp; Giá
                            </h6>
                        </div>
                        <div class="card-body-premium p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Chọn Tour du lịch <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-premium" id="tour_id" name="tour_id" required>
                                        <option value="">-- Click để chọn tour --</option>
                                        <?php if (!empty($tours)): ?>
                                            <?php foreach ($tours as $t): ?>
                                                <option value="<?= htmlspecialchars($t['id']) ?>"
                                                    data-price="<?= htmlspecialchars($t['base_price']) ?>"
                                                    <?= $t['id'] == $booking['tour_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Trạng thái xử lý <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="cho_xac_nhan" <?= $booking['status'] == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                        <option value="da_coc" <?= $booking['status'] == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                                        <option value="da_thanh_toan" <?= $booking['status'] == 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
                                        <option value="hoan_tat" <?= $booking['status'] == 'hoan_tat' ? 'selected' : '' ?>>Hoàn tất</option>
                                        <option value="da_huy" <?= $booking['status'] == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                                        <option value="expired" <?= $booking['status'] == 'expired' ? 'selected' : '' ?>>Hết hạn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label text-primary fw-bold mb-3 small text-uppercase letter-spacing-05">Chọn lịch khởi hành <span class="text-danger">*</span></label>

                                <div id="calendar-wrapper" style="display: none;">
                                    <div class="calendar-container shadow-sm border-light mx-auto" style="max-width: 800px;">
                                        <div class="calendar-header">
                                            <button type="button" class="month-nav-btn" onclick="changeMonth(-1)">
                                                <i class="ph ph-caret-left"></i>
                                            </button>
                                            <h6 class="fw-bold mb-0 text-dark" id="calendar-month-title">THÁNG --/----</h6>
                                            <button type="button" class="month-nav-btn" onclick="changeMonth(1)">
                                                <i class="ph ph-caret-right"></i>
                                            </button>
                                        </div>
                                        <div class="calendar-grid-header border-bottom pb-2 mb-2">
                                            <div class="calendar-grid">
                                                <div class="calendar-weekday">T2</div>
                                                <div class="calendar-weekday">T3</div>
                                                <div class="calendar-weekday">T4</div>
                                                <div class="calendar-weekday">T5</div>
                                                <div class="calendar-weekday">T6</div>
                                                <div class="calendar-weekday text-danger">T7</div>
                                                <div class="calendar-weekday text-danger">CN</div>
                                            </div>
                                        </div>
                                        <div id="calendar-grid" class="calendar-grid">
                                            <!-- Days injected by JS -->
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-center gap-4 mt-3 px-1">
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <div style="width: 12px; height: 12px; border-radius: 3px; background: #dcfce7; border: 1px solid #22c55e;"></div> Còn chỗ
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <div style="width: 12px; height: 12px; border-radius: 3px; background: #fee2e2; border: 1px solid #ef4444;"></div> Hết chỗ
                                        </div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <div style="width: 12px; height: 12px; border-radius: 3px; background: #e0e7ff; border: 1px solid #4f46e5;"></div> Đang chọn
                                        </div>
                                    </div>

                                    <div class="mt-4 p-3 rounded-4 bg-primary bg-opacity-10 border-0 d-flex align-items-center justify-content-between mx-auto shadow-sm" id="selected-date-info" style="display: none !important; max-width: 800px;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-primary" style="width: 44px; height: 44px;">
                                                <i class="ph-fill ph-calendar-check fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="small text-primary fw-bold mb-0 uppercase letter-spacing-1" style="font-size: 0.7rem;">Ngày đã chọn</div>
                                                <div class="fw-bold text-dark fs-5" id="display-selected-date">--/--/----</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="small text-primary fw-bold mb-0 uppercase letter-spacing-1" style="font-size: 0.7rem;">Chỗ trống</div>
                                            <div class="fw-bold text-primary fs-5" id="display-available-seats">--/-- khách</div>
                                            <div id="display-status-badge" class="badge rounded-pill px-3 py-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="calendar-placeholder" class="text-center py-5 border border-dashed rounded-4 bg-light bg-opacity-50" style="display: none;">
                                    <i class="ph ph-calendar-blank fs-1 text-muted opacity-25 mb-2"></i>
                                    <p class="text-muted small px-4">Vui lòng chọn <strong>Tour</strong> phía trên để xem lịch khởi hành khả dụng.</p>
                                </div>

                                <input type="hidden" id="departure_id" name="departure_id" value="<?= $booking['departure_id'] ?? '' ?>">
                                <input type="hidden" id="booking_date" name="booking_date" value="<?= !empty($booking['booking_date']) ? substr($booking['booking_date'], 0, 10) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Companions Information -->
                    <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                        <div class="card-header-premium p-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-info d-flex align-items-center gap-2">
                                <i class="ph-fill ph-users-three"></i> Danh sách khách đoàn đi cùng
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1 border-0 fw-bold px-3 py-2 rounded-pill bg-info bg-opacity-10" onclick="addCompanion()">
                                <i class="ph-bold ph-plus"></i> Thêm khách
                            </button>
                        </div>
                        <div class="card-body-premium p-4">
                            <div id="companions-container"></div>
                            <div id="companions-empty" class="text-center py-5 text-muted border border-dashed rounded-4 bg-light bg-opacity-50">
                                <div class="bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                    <i class="ph-duotone ph-user-list fs-1 text-info opacity-75"></i>
                                </div>
                                <p class="small fw-bold text-dark mb-1">Chưa có khách đoàn nào được thêm</p>
                                <p class="text-muted small px-5">Nhấn <strong>"Thêm khách"</strong> phía trên nếu người đại diện đặt cho một đoàn đông người.</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="booking_customer_id" name="customer_id" value="<?= htmlspecialchars($booking['customer_id'] ?? '') ?>">
                    <input type="hidden" id="contact_name" name="contact_name" value="<?= htmlspecialchars($booking['contact_name'] ?? '') ?>">
                    <input type="hidden" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($booking['contact_phone'] ?? '') ?>">
                    <input type="hidden" id="contact_email" name="contact_email" value="<?= htmlspecialchars($booking['contact_email'] ?? '') ?>">
                    <input type="hidden" id="contact_address" name="contact_address" value="<?= htmlspecialchars($booking['contact_address'] ?? '') ?>">
                    <input type="hidden" id="notes" name="notes" value="<?= htmlspecialchars($booking['notes'] ?? '') ?>">

                    <!-- Footer Actions -->
                    <div class="footer-actions-container mt-5 pt-4 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="quick-summary-bar p-3 px-4 rounded-4 bg-white shadow-sm border d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="ph-fill ph-receipt fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small fw-bold text-uppercase letter-spacing-1" style="font-size: 0.65rem;">Tổng tiền</div>
                                            <div class="text-danger fw-800 fs-4 mb-0" id="quick-price"><?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?> ₫</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="d-flex flex-column flex-sm-row justify-content-end align-items-center gap-3">
                                    <button type="submit" form="booking-edit-form" class="btn btn-primary px-4 py-2 rounded-pill shadow-primary fw-bold d-flex align-items-center justify-content-center gap-2" style="white-space: nowrap;">
                                        <i class="ph-fill ph-floppy-disk"></i> Lưu thay đổi
                                    </button>
                                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-link text-muted text-decoration-none fw-600 px-3 py-2 d-flex align-items-center gap-1" style="white-space: nowrap;">
                                        <i class="ph ph-x fs-5"></i> Hủy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    const PRE_BOOKING_DATE = '<?= !empty($booking['booking_date']) ? substr($booking['booking_date'], 0, 10) : '' ?>';
    const PRE_TOUR_ID = '<?= $booking['tour_id'] ?? '' ?>';
    const PRE_CUSTOMER_ID = '<?= $booking['customer_id'] ?? '' ?>';
    const PRE_CONTACT_NAME = '<?= htmlspecialchars($booking['contact_name'] ?? '', ENT_QUOTES) ?>';
    const PRE_CONTACT_PHONE = '<?= htmlspecialchars($booking['contact_phone'] ?? '', ENT_QUOTES) ?>';
    const PRE_CONTACT_EMAIL = '<?= htmlspecialchars($booking['contact_email'] ?? '', ENT_QUOTES) ?>';
    const PRE_CONTACT_ADDRESS = '<?= htmlspecialchars($booking['contact_address'] ?? '', ENT_QUOTES) ?>';
    const PRE_CONTACT_NOTES = '<?= htmlspecialchars($booking['notes'] ?? '', ENT_QUOTES) ?>';
    const PRE_COMPANIONS = <?= json_encode($companions ?? []) ?>;
    const customersData = <?= json_encode($customers ?? []) ?>;

    let departureLookupData = {};
    let currentCalDate = PRE_BOOKING_DATE ? new Date(PRE_BOOKING_DATE) : new Date();
    let currentAvailableSeats = 999;
    let companionIndex = 0;

    // Helper functions moved to TOP to avoid hoisting issues
    window.applyDateMask = function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 8) value = value.slice(0, 8);
        
        let formatted = '';
        if (value.length > 0) {
            formatted = value.substring(0, 2);
            if (value.length > 2) {
                formatted += '/' + value.substring(2, 4);
                if (value.length > 4) {
                    formatted += '/' + value.substring(4, 8);
                }
            }
        }
        e.target.value = formatted;
    };

    window.formatDateToVN = function(dateStr) {
        if (!dateStr) return '';
        if (dateStr.includes('/')) return dateStr; 
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    };

    document.addEventListener('DOMContentLoaded', function() {
        setupEventListeners();

        if (window.initChoices) {
            window.initChoices(document);
        }

        // Apply mask to any pre-existing date inputs (like representative card)
        document.querySelectorAll('input[placeholder="dd/mm/yyyy"]').forEach(input => {
            input.addEventListener('input', window.applyDateMask);
        });

        setTimeout(() => {
            // Nạp người đại diện (khách đầu tiên trong danh sách)
            const rep = (PRE_COMPANIONS && PRE_COMPANIONS.length > 0) ? PRE_COMPANIONS[0] : {};
            // Tìm thông tin từ customersData nếu PRE_CUSTOMER_ID tồn tại và rep còn thiếu thông tin
            let customerInfo = {};
            if (PRE_CUSTOMER_ID && typeof customersData !== 'undefined') {
                customerInfo = customersData.find(c => String(c.user_id) === String(PRE_CUSTOMER_ID)) || {};
            }

            addCompanion(true, {
                customer_id: PRE_CUSTOMER_ID,
                full_name: rep.full_name || customerInfo.full_name || PRE_CONTACT_NAME,
                phone: rep.phone || customerInfo.phone || PRE_CONTACT_PHONE,
                email: rep.email || customerInfo.email || PRE_CONTACT_EMAIL,
                address: rep.address || customerInfo.address || PRE_CONTACT_ADDRESS,
                id_card: rep.id_card || customerInfo.id_card || '',
                birth_date: rep.birth_date || customerInfo.birth_date || '', 
                gender: rep.gender || customerInfo.gender || 'male',
                passenger_type: rep.passenger_type || 'adult',
                is_foc: rep.is_foc || 0,
                special_request: rep.special_request || PRE_CONTACT_NOTES
            });

            // Nạp các khách đi cùng còn lại
            if (PRE_COMPANIONS && PRE_COMPANIONS.length > 1) {
                PRE_COMPANIONS.slice(1).forEach(companion => {
                    let cInfo = {};
                    if (companion.user_id && typeof customersData !== 'undefined') {
                        cInfo = customersData.find(c => String(c.user_id) === String(companion.user_id)) || {};
                    } else if (companion.full_name && typeof customersData !== 'undefined') {
                        // Tìm theo tên nếu thiếu ID (best effort)
                        cInfo = customersData.find(c => c.full_name && c.full_name.toLowerCase() === companion.full_name.toLowerCase()) || {};
                    }

                    addCompanion(false, {
                        full_name: companion.full_name || cInfo.full_name || '',
                        passenger_type: companion.passenger_type || 'adult',
                        gender: companion.gender || cInfo.gender || 'male',
                        // SMART LOOKUP: Only use specific companion or matching user info
                        phone: companion.phone || cInfo.phone || '',
                        email: companion.email || cInfo.email || '',
                        address: companion.address || cInfo.address || '',
                        id_card: companion.id_card || cInfo.id_card || '',
                        birth_date: companion.birth_date || cInfo.birth_date || '',
                        user_id: companion.user_id || '',
                        is_foc: companion.is_foc || 0,
                        special_request: companion.special_request || ''
                    });
                });
            }
            updateSummary();
        }, 100);



        if (PRE_TOUR_ID) {
            fetchDepartures(PRE_TOUR_ID, PRE_BOOKING_DATE);
        } else {
            document.getElementById('calendar-placeholder').style.display = 'block';
        }
    });

    function setupEventListeners() {
        document.getElementById('tour_id').addEventListener('change', function() {
            const tourId = this.value;
            document.getElementById('departure_id').value = '';
            document.getElementById('booking_date').value = '';
            document.getElementById('display-selected-date').textContent = '--/--/----';
            document.getElementById('selected-date-info').style.setProperty('display', 'none', 'important');

            if (tourId) {
                fetchDepartures(tourId);
                document.getElementById('calendar-placeholder').style.display = 'none';
                document.getElementById('calendar-wrapper').style.display = 'block';
            } else {
                document.getElementById('calendar-placeholder').style.display = 'block';
                document.getElementById('calendar-wrapper').style.display = 'none';
            }
            updateSummary();
        });

        ['status', 'total_price'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', updateSummary);
            }
        });

        document.getElementById('companions-container').addEventListener('change', (e) => {
            if (e.target.name === 'companion_passenger_type[]') {
                updateSummary();
            }

            if (e.target.classList.contains('guest-account-select')) {
                const userId = e.target.value;
                const customer = customersData.find(c => c.user_id == userId);
                const card = e.target.closest('.companion-card-premium');
                
                if (customer && card) {
                    const fieldMap = {
                        'input[name="companion_name[]"]': customer.full_name,
                        'input[name="companion_email[]"]': customer.email,
                        'input[name="companion_phone[]"]': customer.phone,
                        'textarea[name="companion_address[]"]': customer.address,
                        'input[name="companion_id_card[]"]': customer.id_card,
                        'input[name="companion_birth_date[]"]': customer.birth_date,
                    };

                    for (const [selector, value] of Object.entries(fieldMap)) {
                        const el = card.querySelector(selector);
                        if (el) el.value = value || '';
                    }

                    const genderSelect = card.querySelector('select[name="companion_gender[]"]');
                    if (genderSelect) {
                        genderSelect.value = customer.gender || 'male';
                        if (genderSelect.choices) genderSelect.choices.setChoiceByValue(customer.gender || 'male');
                    }

                    if (card.id === 'representative-row') {
                        const contactFields = {
                            'contact_name': customer.full_name,
                            'contact_phone': customer.phone,
                            'contact_email': customer.email,
                            'contact_address': customer.address
                        };
                        for (const [id, val] of Object.entries(contactFields)) {
                            const el = document.getElementById(id);
                            if (el) el.value = val || '';
                        }
                        // Đồng bộ ID vào trường ẩn customer_id
                        const cid = document.getElementById('booking_customer_id');
                        if (cid) cid.value = customer.user_id || '';
                    }
                    
                    // Apply masking to updated date field
                    card.querySelectorAll('input[placeholder="dd/mm/yyyy"]').forEach(input => {
                        input.addEventListener('input', window.applyDateMask);
                    });
                }
                updateSummary();
            }
        });
    }

    function fetchDepartures(tourId, preSelectDate = null) {
        const wrapper = document.getElementById('calendar-wrapper');
        const placeholder = document.getElementById('calendar-placeholder');

        fetch(`<?= BASE_URL_ADMIN ?>&action=bookings/get-departures&tour_id=${tourId}&include_id=<?= $booking['departure_id'] ?? '' ?>`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    departureLookupData = {};
                    data.departures.forEach(dep => {
                        departureLookupData[dep.departure_date] = dep;
                    });
                    placeholder.style.display = 'none';
                    wrapper.style.display = 'block';

                    if (preSelectDate) {
                        currentCalDate = new Date(preSelectDate);
                    }
                    renderCalendar();

                    if (preSelectDate && departureLookupData[preSelectDate]) {
                        const dep = departureLookupData[preSelectDate];
                        selectDate(preSelectDate, dep.id, dep.available_seats, dep.max_seats);
                    }
                }
            })
            .catch(err => console.error('Error fetching departures:', err));
    }

    function renderCalendar() {
        const grid = document.getElementById('calendar-grid');
        const monthTitle = document.getElementById('calendar-month-title');
        if (!grid || !monthTitle) return;

        grid.innerHTML = '';
        const year = currentCalDate.getFullYear();
        const month = currentCalDate.getMonth();
        const monthNames = ["THÁNG 1", "THÁNG 2", "THÁNG 3", "THÁNG 4", "THÁNG 5", "THÁNG 6", "THÁNG 7", "THÁNG 8", "THÁNG 9", "THÁNG 10", "THÁNG 11", "THÁNG 12"];
        monthTitle.innerHTML = `${monthNames[month]}/${year}`;

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        let startDay = firstDay.getDay();
        startDay = startDay === 0 ? 6 : startDay - 1;

        for (let i = 0; i < startDay; i++) {
            grid.innerHTML += '<div class="day-cell opacity-25"></div>';
        }

        const selectedDate = document.getElementById('booking_date').value;
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const d = String(day).padStart(2, '0');
            const m = String(month + 1).padStart(2, '0');
            const dateStr = `${year}-${m}-${d}`;
            const departure = departureLookupData[dateStr];

            let classes = 'day-cell';
            let content = `<span class="day-num fw-bold">${day}</span>`;
            let onClick = '';

            if (departure) {
                const isFull = departure.available_seats <= 0;
                const occupied = departure.max_seats - departure.available_seats;
                classes += ' available';

                if (selectedDate === dateStr) {
                    classes += ' selected';
                } else if (isFull) {
                    classes += ' bg-danger-subtle border-danger text-danger';
                } else {
                    classes += ' bg-success-subtle border-success text-success';
                }

                onClick = `onclick="selectDate('${dateStr}', '${departure.id}', ${departure.available_seats}, ${departure.max_seats})"`;
                content += `<div class="small fw-bold d-flex align-items-center justify-content-center gap-1" style="font-size: 0.65rem; opacity: 0.8;">
                    <i class="ph ph-users" style="font-size: 0.75rem;"></i> ${occupied}/${departure.max_seats}
                </div>`;
            }

            grid.innerHTML += `<div class="${classes} p-2 text-center" ${onClick} style="min-height: 60px;">${content}</div>`;
        }
    }

    function changeMonth(offset) {
        currentCalDate.setMonth(currentCalDate.getMonth() + offset);
        renderCalendar();
    }

    function selectDate(dateStr, depId, availableSeats, maxSeats) {
        document.getElementById('booking_date').value = dateStr;
        document.getElementById('departure_id').value = depId;

        const bookedSeats = maxSeats - availableSeats;
        document.getElementById('display-available-seats').textContent = `${bookedSeats}/${maxSeats} khách`;

        let statusText = 'Còn chỗ';
        let statusClass = 'bg-success';
        if (availableSeats <= 0) {
            statusText = 'Hết chỗ';
            statusClass = 'bg-danger';
        } else if (availableSeats <= 5) {
            statusText = 'Sắp hết';
            statusClass = 'bg-warning text-dark';
        }

        const statusBadge = document.getElementById('display-status-badge');
        statusBadge.textContent = statusText;
        statusBadge.className = `badge ${statusClass} rounded-pill px-3 py-2`;

        const parts = dateStr.split('-');
        document.getElementById('display-selected-date').textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
        document.getElementById('selected-date-info').style.setProperty('display', 'flex', 'important');

        currentAvailableSeats = availableSeats;
        renderCalendar();
        updateSummary();
    }

    function updateSummary() {
        const passengerTypes = document.querySelectorAll('select[name="companion_passenger_type[]"]');
        let adults = 0;
        let children = 0;
        let infants = 0;

        passengerTypes.forEach(select => {
            if (select.value === 'adult') adults++;
            else if (select.value === 'child') children++;
            else if (select.value === 'infant') infants++;
        });

        document.getElementById('adults').value = adults;
        document.getElementById('children').value = children;
        document.getElementById('infants').value = infants;

        const dateValue = document.getElementById('booking_date').value;
        const priceInput = document.getElementById('total_price');
        let calculatedPrice = 0;

        if (dateValue && departureLookupData[dateValue]) {
            const dep = departureLookupData[dateValue];
            calculatedPrice = (adults * dep.price_adult) + (children * dep.price_child) + (infants * dep.price_infant);
        } else {
            const tourSelect = document.getElementById('tour_id');
            const basePrice = parseFloat(tourSelect.options[tourSelect.selectedIndex]?.getAttribute('data-price')) || 0;
            calculatedPrice = adults * basePrice;
        }

        priceInput.value = calculatedPrice;
        const quickPrice = document.getElementById('quick-price');
        if (quickPrice) {
            quickPrice.textContent = new Intl.NumberFormat('vi-VN').format(calculatedPrice) + ' ₫';
        }
    }

    // Companion Management
    function addCompanion(isRepresentative = false, data = {}) {
        const container = document.getElementById('companions-container');
        const emptyState = document.getElementById('companions-empty');
        
        const currentCount = document.querySelectorAll('.companion-card-premium').length;
        if (!isRepresentative && currentCount >= currentAvailableSeats) {
            Swal.fire({
                icon: 'error',
                title: 'Hết chỗ trống',
                text: `Ngày này chỉ còn ${currentAvailableSeats} chỗ trống. Bạn không thể thêm khách vượt quá số lượng này.`,
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        
        const id = isRepresentative ? 'representative-row' : `companion-${companionIndex}`;
        const title = isRepresentative ? 'Thông tin Người đại diện (Chủ đoàn)' : 'Thông tin khách đi cùng';
        const themeClass = isRepresentative ? 'border-primary bg-primary bg-opacity-10' : 'border shadow-sm';
        const icon = isRepresentative ? 'ph ph-user-circle-plus' : 'ph ph-user';
        
        let customerOptions = '<option value="">-- Chọn khách hàng sẵn có --</option>';
        const selectedId = isRepresentative ? (data.customer_id || '') : (data.user_id || '');
        
        if (typeof customersData !== 'undefined') {
            customersData.forEach(c => {
                const isSelected = String(c.user_id) === String(selectedId) ? 'selected' : '';
                customerOptions += `<option value="${c.user_id}" ${isSelected}>${c.full_name} (${c.phone || ''})</option>`;
            });
        }

        const deleteButtonHtml = isRepresentative ? '' : `
            <button type="button" class="btn btn-sm btn-light border position-absolute top-0 end-0 m-3 text-danger shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0; z-index: 10;" onclick="removeCompanion('${id}')">
                <i class="ph-bold ph-trash fs-6"></i>
            </button>
        `;
        
        const html = `
            <div class="companion-card-premium rounded-4 p-4 mb-4 position-relative ${themeClass} transition-all" id="${id}">
                ${deleteButtonHtml}
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="${icon} fs-6"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark small text-uppercase letter-spacing-05">${title}</h6>
                    </div>
                    <div class="d-flex align-items-center mb-0">
                        <input type="hidden" name="companion_is_foc[]" value="${data.is_foc == 1 ? '1' : '0'}">
                        <input type="hidden" name="companion_create_account[]" value="0">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <div class="passenger-type-banner mb-3 p-2 rounded-3 d-flex align-items-center justify-content-between px-3 ${data.passenger_type === 'infant' ? 'bg-danger-subtle' : (data.passenger_type === 'child' ? 'bg-info-subtle' : 'bg-primary-subtle')}">
                            <div class="banner-label d-flex align-items-center gap-2">
                                <i class="ph-fill ${data.passenger_type === 'infant' ? 'ph-baby text-danger' : (data.passenger_type === 'child' ? 'ph-child text-info' : 'ph-user text-primary')} fs-5"></i>
                                <span class="fw-bold small text-uppercase ${data.passenger_type === 'infant' ? 'text-danger' : (data.passenger_type === 'child' ? 'text-info' : 'text-primary')}">
                                    ${data.passenger_type === 'infant' ? 'EM BÉ (Dưới 2 tuổi)' : (data.passenger_type === 'child' ? 'TRẺ EM (2-11 tuổi)' : 'NGƯỜI LỚN (Từ 12 tuổi)')}
                                </span>
                            </div>
                            <div style="width: 150px;">
                                <select class="form-select form-select-sm border-0 bg-white" name="companion_passenger_type[]" onchange="window.updatePassengerCard(this)">
                                    <option value="adult" ${data.passenger_type === 'adult' ? 'selected' : ''}>Người lớn</option>
                                    <option value="child" ${data.passenger_type === 'child' ? 'selected' : ''}>Trẻ em</option>
                                    <option value="infant" ${data.passenger_type === 'infant' ? 'selected' : ''}>Em bé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small text-muted text-uppercase fw-bold">Hồ sơ khách sẵn có</label>
                        <select class="form-select bg-light border-0 guest-account-select" name="companion_user_id[]">
                            ${customerOptions}
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small text-muted text-uppercase fw-bold">Họ và tên khách <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-white" name="companion_name[]" value="${data.full_name || ''}" placeholder="Nhập đầy đủ họ tên..." required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Giới tính</label>
                        <select class="form-select bg-white" name="companion_gender[]">
                            <option value="male" ${data.gender === 'male' ? 'selected' : ''}>Nam</option>
                            <option value="female" ${data.gender === 'female' ? 'selected' : ''}>Nữ</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold">Ngày sinh</label>
                        <input type="date" class="form-control bg-white" name="companion_birth_date[]" value="${data.birth_date || ''}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted text-uppercase fw-bold">Số CCCD/Passport</label>
                        <input type="text" class="form-control bg-white" name="companion_id_card[]" value="${data.id_card || ''}" placeholder="Số định danh...">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold">Số điện thoại</label>
                        <input type="text" class="form-control bg-white" name="companion_phone[]" value="${data.phone || ''}" placeholder="SĐT liên hệ...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold">Địa chỉ Email</label>
                        <input type="email" class="form-control bg-white" name="companion_email[]" value="${data.email || ''}" placeholder="Email nhận thông báo...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold">Địa chỉ</label>
                        <input type="text" class="form-control bg-white" name="companion_address[]" value="${data.address || ''}" placeholder="Địa chỉ thường trú...">
                    </div>

                    <div class="col-12">
                        <label class="form-label small text-muted text-uppercase fw-bold">Ghi chú đặc biệt cho khách này</label>
                        <textarea class="form-control bg-white" name="companion_special_request[]" placeholder="Dị ứng thực phẩm, bệnh lý, yêu cầu đặc biệt..." style="height: 60px;">${data.special_request || ''}</textarea>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        
        if (!isRepresentative) companionIndex++;

        updateSummary();
    }

    window.updatePassengerCard = function(select) {
        const card = select.closest('.companion-card-premium');
        const banner = card.querySelector('.passenger-type-banner');
        const label = banner.querySelector('.banner-label');
        const type = select.value;
        
        banner.className = `passenger-type-banner mb-3 p-2 rounded-3 d-flex align-items-center justify-content-between px-3 ${type === 'infant' ? 'bg-danger-subtle' : (type === 'child' ? 'bg-info-subtle' : 'bg-primary-subtle')}`;
        
        const icon = type === 'infant' ? 'ph-baby text-danger' : (type === 'child' ? 'ph-child text-info' : 'ph-user text-primary');
        const text = type === 'infant' ? 'EM BÉ (Dưới 2 tuổi)' : (type === 'child' ? 'TRẺ EM (2-11 tuổi)' : 'NGƯỜI LỚN (Từ 12 tuổi)');
        const textClass = type === 'infant' ? 'text-danger' : (type === 'child' ? 'text-info' : 'text-primary');
        
        label.innerHTML = `
            <i class="ph-fill ${icon} fs-5"></i>
            <span class="fw-bold small text-uppercase ${textClass}">${text}</span>
        `;
        
        updateSummary();
    };

    function removeCompanion(id) {
        const item = document.getElementById(id);
        if (!item) return;

        item.style.opacity = '0';
        item.style.transform = 'translateX(20px)';
        setTimeout(() => {
            item.remove();
            const container = document.getElementById('companions-container');
            const emptyState = document.getElementById('companions-empty');
            if (container && container.children.length === 0 && emptyState) {
                emptyState.style.display = 'block';
            }
            updateSummary();
        }, 200);
    }

    window.addCompanion = addCompanion;
    window.removeCompanion = removeCompanion;
    window.changeMonth = changeMonth;
    window.selectDate = selectDate;
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>