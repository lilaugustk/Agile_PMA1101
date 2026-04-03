<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard booking-create-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header mb-4">
            <div class="header-content d-flex justify-content-between align-items-end">
                <div class="header-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="font-size: 0.8rem; letter-spacing: 0.02em;">
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none d-flex align-items-center gap-1"><i class="ph-fill ph-house"></i> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="text-muted text-decoration-none d-flex align-items-center gap-1"><i class="ph-fill ph-calendar-check"></i> Quản lý Booking</a></li>
                            <li class="breadcrumb-item active text-primary fw-600" aria-current="page">Tạo mới</li>
                        </ol>
                    </nav>
                </div>
                <div class="header-right d-flex gap-2">
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-light border-0 shadow-sm px-3 py-2 bg-white">
                        <i class="ph ph-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" form="booking-form" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-primary">
                        <i class="ph-fill ph-floppy-disk"></i> Lưu Booking
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

        <!-- Progress Stepper Modern -->
        <div class="progress-stepper-container mb-5">
            <div class="progress-stepper">
                <div class="stepper-item active" data-step="1" onclick="goToStep(1)">
                    <div class="stepper-dot">1</div>
                    <span class="stepper-label">Thông tin Booking</span>
                </div>
                <div class="stepper-item" data-step="2" onclick="goToStep(2)">
                    <div class="stepper-dot">2</div>
                    <span class="stepper-label">Xác nhận đơn</span>
                </div>
            </div>
        </div>

        <style>
            .form-step {
                display: none;
                animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .form-step.active {
                display: block;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .shadow-primary {
                box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39) !important;
            }
            .progress-stepper-container {
                max-width: 600px;
                margin: 0 auto;
            }
            .card-header-premium {
                background: linear-gradient(to right, rgba(248, 250, 252, 0.5), transparent);
            }
            .form-floating > label {
                font-weight: 500;
                color: var(--text-muted);
            }
            .form-floating > .form-control:focus ~ label,
            .form-floating > .form-control:not(:placeholder-shown) ~ label,
            .form-floating > .form-select ~ label {
                color: var(--primary);
                font-weight: 600;
            }
        </style>

        <!-- Booking Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/store" id="booking-form">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Step 1: Booking Information -->
                    <div class="form-step active" id="step-1">
                        <!-- Customer Information -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-user-circle"></i> Thông tin khách hàng
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="form-floating flex-grow-1">
                                                <select class="form-select" id="customer_id" name="customer_id" required>
                                                    <option value="">-- Chọn khách hàng --</option>
                                                    <?php if (!empty($customers)): ?>
                                                        <?php foreach ($customers as $c): ?>
                                                            <option value="<?= htmlspecialchars($c['user_id']) ?>">
                                                                <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <label for="customer_id">Khách hàng chính <span class="text-danger">*</span></label>
                                            </div>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=users/create" target="_blank" class="btn btn-outline-primary d-flex align-items-center px-3" title="Tạo khách hàng mới">
                                                <i class="ph-fill ph-user-plus fs-5"></i>
                                            </a>
                                        </div>
                                        <div class="mt-2 text-muted small px-1">
                                            <i class="ph ph-info me-1"></i>Chọn khách hàng hoặc click "+" để thêm mới người dùng.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Information -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-map-pin"></i> Chi tiết Tour & Giá
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select text-dark fw-medium" id="tour_id" name="tour_id" required>
                                                <option value="">-- Chọn tour --</option>
                                                <?php if (!empty($tours)): ?>
                                                    <?php foreach ($tours as $t): ?>
                                                        <option value="<?= htmlspecialchars($t['id']) ?>" data-price="<?= htmlspecialchars($t['base_price']) ?>">
                                                            <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="tour_id">Tour lựa chọn <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select" id="version_id" name="version_id">
                                                <option value="">-- Chọn phiên bản --</option>
                                                <?php if (!empty($versions)): ?>
                                                    <?php foreach ($versions as $v): ?>
                                                        <option value="<?= htmlspecialchars($v['id']) ?>"
                                                            data-price-adult="<?= htmlspecialchars($v['price_adult'] ?? 0) ?>">
                                                            <?= htmlspecialchars($v['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="version_id">Phiên bản / Sự kiện</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="departure_id" name="departure_id" required>
                                                <option value="">-- Chọn ngày khởi hành --</option>
                                            </select>
                                            <label for="departure_id">Lịch khởi hành <span class="text-danger">*</span></label>
                                        </div>
                                        <small class="text-muted d-block mt-2 px-1" id="departure-info"></small>
                                        <input type="hidden" id="booking_date" name="booking_date">
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="total_price" name="total_price" min="0" step="1000" placeholder=" " required>
                                            <label for="total_price">Tổng tiền đặt (VNĐ) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="">-- Trạng thái --</option>
                                                <option value="cho_xac_nhan">Chờ xác nhận</option>
                                                <option value="da_coc">Đã cọc</option>
                                                <option value="hoan_tat">Hoàn tất</option>
                                                <option value="da_huy">Đã hủy</option>
                                            </select>
                                            <label for="status">Trạng thái Booking <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-warning d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-note-pencil"></i> Ghi chú & Yêu cầu
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="form-floating">
                                    <textarea class="form-control fw-medium" id="notes" name="notes" style="height: 120px" placeholder=" "></textarea>
                                    <label for="notes" class="text-muted small">Yêu cầu đặc biệt từ khách hàng</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Confirmation -->
                    <div class="form-step" id="step-2">
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-check-circle"></i> Xác nhận đơn hàng
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="alert-modern alert-info mb-4 py-3 border-0 bg-primary-subtle text-primary rounded-3 d-flex align-items-center gap-3">
                                    <i class="ph-fill ph-info fs-4"></i>
                                    <span class="small fw-medium">Vui lòng kiểm tra kỹ các thông tin trước khi hoàn tất lưu trữ hệ thống.</span>
                                </div>

                                <div class="booking-summary-premium">
                                    <div class="summary-section mb-4">
                                        <h6 class="text-muted small fw-bold text-uppercase mb-3 letter-spacing-05">Thông tin tóm lược</h6>
                                        <div class="summary-grid row g-3">
                                            <div class="col-sm-6 summary-item">
                                                <span class="label text-muted small d-block">Ngày khởi hành</span>
                                                <span class="value fw-bold" id="summary-date">--</span>
                                            </div>
                                            <div class="col-12 summary-item">
                                                <span class="label text-muted small d-block">Khách hàng chính</span>
                                                <span class="value fw-bold" id="summary-customer">--</span>
                                            </div>
                                            <div class="col-12 summary-item">
                                                <span class="label text-muted small d-block">Tour đăng ký</span>
                                                <span class="value fw-bold" id="summary-tour">--</span>
                                            </div>
                                            <div class="col-sm-6 summary-item">
                                                <span class="label text-muted small d-block">Trạng thái ban đầu</span>
                                                <span class="value fw-bold" id="summary-status">--</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="summary-total-box bg-danger-subtle p-4 rounded-3 d-flex justify-content-between align-items-center border border-danger border-opacity-10 mt-5">
                                        <span class="fw-bold text-danger text-uppercase small">Tổng cộng thanh toán</span>
                                        <span class="text-danger fw-800 fs-3" id="summary-price">0 ₫</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 90px; z-index: 100;">
                        <!-- Action Card -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden">
                            <div class="p-4">
                                <h6 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                                    <i class="ph-duotone ph-lightning text-warning fs-5"></i> Thao tác nhanh
                                </h6>
                                
                                <div class="d-grid gap-3 mb-4">
                                    <button type="submit" form="booking-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3 fw-bold shadow-primary rounded-3 border-0">
                                        <i class="ph-fill ph-plus-circle fs-5"></i>
                                        Tạo Booking Mới
                                    </button>
                                    
                                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-light shadow-none w-100 justify-content-center py-2 rounded-3 text-muted border-0 bg-light">
                                        <i class="ph ph-x me-1"></i> Hủy bỏ
                                    </a>
                                </div>

                                <div class="step-navigation-box border-top pt-4 mt-2 d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-link text-decoration-none text-muted p-0 d-flex align-items-center gap-2 fw-600" onclick="previousStep()" id="prev-btn" style="display: none;">
                                        <i class="ph-bold ph-arrow-left"></i> Quay lại
                                    </button>
                                    <button type="button" class="btn btn-dark d-flex align-items-center gap-2 px-4 py-2 rounded-pill shadow-sm ms-auto fw-600" onclick="nextStep()" id="next-btn">
                                        Tiếp tục <i class="ph-bold ph-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Recap Card -->
                        <div class="card-premium border-0 shadow-sm bg-white overflow-hidden rounded-4">
                            <div class="p-3 px-4 bg-light bg-opacity-50 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-muted small d-flex align-items-center gap-2 text-uppercase letter-spacing-1">
                                    <i class="ph-fill ph-receipt text-primary"></i> Tóm tắt mới
                                </h6>
                            </div>
                            <div class="p-4">
                                <div class="recap-list mb-4">
                                    <div class="recap-item d-flex justify-content-between mb-3">
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <i class="ph ph-navigation-arrow text-primary"></i> Sản phẩm:
                                        </div>
                                        <span id="quick-tour" class="text-end small fw-bold text-dark text-truncate ms-2" style="max-width: 150px;">--</span>
                                    </div>
                                    <div class="recap-item d-flex justify-content-between mb-3">
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <i class="ph ph-user-focus text-primary"></i> Khách hàng:
                                        </div>
                                        <span id="quick-customer" class="text-end small fw-bold text-dark text-truncate ms-2" style="max-width: 150px;">--</span>
                                    </div>
                                    <div class="recap-item d-flex justify-content-between">
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <i class="ph ph-shield-check text-primary"></i> Trạng thái:
                                        </div>
                                        <span id="quick-status-badge" class="badge-premium small">--</span>
                                    </div>
                                </div>
                                <div class="pt-4 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small fw-bold text-muted text-uppercase letter-spacing-1">Dự tính:</span>
                                        <div class="text-end">
                                            <div class="text-danger fs-3 fw-800 line-height-1" id="quick-price">0 ₫</div>
                                        </div>
                                    </div>
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
    // Booking Creation JavaScript
    let currentStep = 1;
    const totalSteps = 2;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupEventListeners();
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();

        // Auto-select "Bình thường" version (ID=10) if no version is selected
        const versionSelect = document.getElementById('version_id');
        if (versionSelect && !versionSelect.value) {
            // Try to find and select version with ID=10
            const defaultOption = versionSelect.querySelector('option[value="10"]');
            if (defaultOption) {
                versionSelect.value = '10';
                // Trigger change event to update price
                versionSelect.dispatchEvent(new Event('change'));
            }
        }
    }

    function setupEventListeners() {
        // Auto-update price when tour is selected
        document.getElementById('tour_id').addEventListener('change', function() {
            const tourId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');

            // Reset departure selection
            const departureSelect = document.getElementById('departure_id');
            departureSelect.innerHTML = '<option value="">-- Chọn ngày khởi hành --</option>';
            document.getElementById('departure-info').textContent = '';
            document.getElementById('booking_date').value = '';

            if (tourId) {
                // Fetch departures for this tour
                fetchDepartures(tourId);

                // Reset version selection when tour changes
                const versionSelect = document.getElementById('version_id');
                if (versionSelect) {
                    versionSelect.value = '';
                }
                document.getElementById('total_price').value = price;
                updateSummary();
            }
        });

        // Handle departure selection
        document.getElementById('departure_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const departureDate = selectedOption.getAttribute('data-date');
                const priceAdult = selectedOption.getAttribute('data-price-adult');
                const availableSeats = selectedOption.getAttribute('data-seats');
                const maxSeats = selectedOption.getAttribute('data-max-seats');

                // Update hidden booking_date field
                document.getElementById('booking_date').value = departureDate;

                // Disable automatic price update - keep tour base price
                // if (priceAdult) {
                //     document.getElementById('total_price').value = priceAdult;
                // }

                // Show departure info
                document.getElementById('departure-info').innerHTML =
                    `<i class="fas fa-info-circle me-1"></i>Còn ${availableSeats}/${maxSeats} chỗ trống`;

                updateSummary();
            } else {
                document.getElementById('booking_date').value = '';
                document.getElementById('departure-info').textContent = '';
            }
        });
        // Auto-update price when version is selected
        const versionSelect = document.getElementById('version_id');
        if (versionSelect) {
            versionSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const priceAdult = selectedOption.getAttribute('data-price-adult');

                if (priceAdult && priceAdult > 0) {
                    // Nếu có chọn version → dùng giá của version
                    document.getElementById('total_price').value = priceAdult;
                } else {
                    // Nếu không chọn version → quay về giá gốc của tour
                    const tourSelect = document.getElementById('tour_id');
                    const tourPrice = tourSelect.options[tourSelect.selectedIndex].getAttribute('data-price');
                    if (tourPrice) {
                        document.getElementById('total_price').value = tourPrice;
                    }
                }
                updateSummary();
            });
        }
        // Update summary on field changes
        ['customer_id', 'tour_id', 'version_id', 'departure_id', 'status', 'total_price'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', updateSummary);
            }
        });
    }

    // Fetch departures for a tour
    function fetchDepartures(tourId) {
        const departureSelect = document.getElementById('departure_id');
        const infoDiv = document.getElementById('departure-info');

        // Show loading
        infoDiv.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang tải lịch khởi hành...';

        fetch(`<?= BASE_URL_ADMIN ?>&action=bookings/get-departures&tour_id=${tourId}`)
            .then(response => response.json())
            .then(data => {
                infoDiv.textContent = '';

                if (data.success && data.departures && data.departures.length > 0) {
                    data.departures.forEach(dep => {
                        const option = document.createElement('option');
                        option.value = dep.id;
                        option.textContent = dep.formatted_date;
                        option.setAttribute('data-date', dep.departure_date);
                        option.setAttribute('data-price-adult', dep.price_adult);
                        option.setAttribute('data-price-child', dep.price_child);
                        option.setAttribute('data-seats', dep.available_seats);
                        option.setAttribute('data-max-seats', dep.max_seats);

                        if (dep.version_name) {
                            option.textContent += ` (${dep.version_name})`;
                        }

                        departureSelect.appendChild(option);
                    });

                    infoDiv.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i>Tìm thấy ${data.departures.length} lịch khởi hành`;
                } else {
                    infoDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-warning me-1"></i>Không có lịch khởi hành nào';
                }
            })
            .catch(error => {
                console.error('Error fetching departures:', error);
                infoDiv.innerHTML = '<i class="fas fa-exclamation-circle text-danger me-1"></i>Lỗi khi tải lịch khởi hành';
            });
    }

    // Step Navigation
    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                updateNavigationButtons();
                updateSummary();
            }
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
            updateNavigationButtons();
        }
    }

    function goToStep(step) {
        if (step > currentStep) {
            if (!validateCurrentStep()) return;
        }
        currentStep = step;
        updateStepDisplay();
        updateNavigationButtons();
        updateSummary();
    }

    function updateStepDisplay() {
        // Update progress stepper (Premium style)
        document.querySelectorAll('.stepper-item').forEach(step => {
            step.classList.remove('active', 'completed');
            const stepNum = parseInt(step.dataset.step);
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
                const dot = step.querySelector('.stepper-dot');
                if (dot) dot.innerHTML = '<i class="ph-bold ph-check"></i>';
            } else {
                const dot = step.querySelector('.stepper-dot');
                if (dot) dot.innerHTML = stepNum;
            }
        });

        // Update form sections with fade effect
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        const current = document.getElementById(`step-${currentStep}`);
        current.classList.add('active');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
        nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
    }

    // Validation
    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');

        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                return false;
            }
        }

        return true;
    }

    // Update Summary
    function updateSummary() {
        const customerSelect = document.getElementById('customer_id');
        const tourSelect = document.getElementById('tour_id');
        const dateInput = document.getElementById('booking_date');
        const statusSelect = document.getElementById('status');
        const priceInput = document.getElementById('total_price');

        const customerText = customerSelect.options[customerSelect.selectedIndex]?.text || '--';
        const tourText = tourSelect.options[tourSelect.selectedIndex]?.text || '--';
        const price = priceInput.value || '0';
        const statusValue = statusSelect.value;
        const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || '--';

        // Update quick summary
        if (document.getElementById('quick-customer')) document.getElementById('quick-customer').textContent = customerText.split('(')[0].trim();
        if (document.getElementById('quick-tour')) document.getElementById('quick-tour').textContent = tourText.split('-')[0].trim();
        if (document.getElementById('quick-price')) document.getElementById('quick-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
        
        const quickStatusBadge = document.getElementById('quick-status-badge');
        if (quickStatusBadge) {
            quickStatusBadge.textContent = statusText;
            quickStatusBadge.className = `badge-premium badge-${statusValue} small`;
        }

        // Update confirmation summary
        if (document.getElementById('summary-customer')) document.getElementById('summary-customer').textContent = customerText;
        if (document.getElementById('summary-tour')) document.getElementById('summary-tour').textContent = tourText;
        if (document.getElementById('summary-date')) document.getElementById('summary-date').textContent = dateInput.value || '--';
        if (document.getElementById('summary-status')) document.getElementById('summary-status').textContent = statusText;
        if (document.getElementById('summary-price')) document.getElementById('summary-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>