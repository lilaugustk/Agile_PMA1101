<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

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

        <!-- Progress Stepper Modern -->
        <div class="progress-stepper-container mb-5">
            <div class="progress-stepper">
                <div class="stepper-item active" data-step="1" onclick="goToStep(1)">
                    <div class="stepper-dot">1</div>
                    <span class="stepper-label">Thông tin cơ bản</span>
                </div>
                <div class="stepper-item" data-step="2" onclick="goToStep(2)">
                    <div class="stepper-dot">2</div>
                    <span class="stepper-label">Phân công & Lần đầu</span>
                </div>
                <div class="stepper-item" data-step="3" onclick="goToStep(3)">
                    <div class="stepper-dot">3</div>
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
                max-width: 900px;
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
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bookings/update" id="booking-edit-form">
            <input type="hidden" name="id" value="<?= $booking['id'] ?>">

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="form-step active" id="step-1">
                        <!-- Customer Information -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-user-circle"></i> Thông tin khách hàng & Liên hệ
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="row g-3">
                                    <div class="col-12 mb-2">
                                        <div class="input-group">
                                            <div class="form-floating flex-grow-1">
                                                <select class="form-select" id="customer_id" name="customer_id">
                                                    <option value="">-- Khách vãng lai (không tài khoản) --</option>
                                                    <?php if (!empty($customers)): ?>
                                                        <?php foreach ($customers as $c): ?>
                                                            <option value="<?= htmlspecialchars($c['user_id']) ?>"
                                                                <?= $c['user_id'] == $booking['customer_id'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['email']) ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <label for="customer_id">Liên kết tài khoản khách hàng</label>
                                            </div>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=users/create" target="_blank" class="btn btn-outline-primary d-flex align-items-center px-3" title="Tạo khách hàng mới">
                                                <i class="ph-fill ph-user-plus fs-5"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                                value="<?= htmlspecialchars($booking['contact_name'] ?? '') ?>" placeholder=" " required>
                                            <label for="contact_name">Họ tên người liên hệ <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                                value="<?= htmlspecialchars($booking['contact_phone'] ?? '') ?>" placeholder=" " required>
                                            <label for="contact_phone">Số điện thoại <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                value="<?= htmlspecialchars($booking['contact_email'] ?? '') ?>" placeholder=" " required>
                                            <label for="contact_email">Email liên hệ <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="contact_address" name="contact_address" 
                                                value="<?= htmlspecialchars($booking['contact_address'] ?? '') ?>" placeholder=" ">
                                            <label for="contact_address">Địa chỉ khách</label>
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
                                                        <option value="<?= htmlspecialchars($t['id']) ?>"
                                                            data-price="<?= htmlspecialchars($t['base_price']) ?>"
                                                            <?= $t['id'] == $booking['tour_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($t['name']) ?> - <?= number_format($t['base_price'], 0, ',', '.') ?> ₫
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="tour_id">Tour lựa chọn <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="version_id" name="version_id">
                                                <option value="">-- Chọn phiên bản --</option>
                                                <?php if (!empty($versions)): ?>
                                                    <?php foreach ($versions as $v): ?>
                                                        <option value="<?= htmlspecialchars($v['id']) ?>"
                                                            data-price-adult="<?= htmlspecialchars($v['price_adult'] ?? 0) ?>"
                                                            <?= (isset($booking['version_id']) && $v['id'] == $booking['version_id']) ? 'selected' : '' ?>>
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
                                        <input type="hidden" id="booking_date" name="booking_date" value="<?= $booking['booking_date'] ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="total_price" name="total_price"
                                                value="<?= $booking['total_price'] ?>" min="0" step="1000" placeholder=" " required>
                                            <label for="total_price">Tổng tiền đặt (VNĐ) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>Chờ thanh toán (Hold)</option>
                                                <option value="cho_xac_nhan" <?= $booking['status'] == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                                <option value="da_coc" <?= $booking['status'] == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                                                <option value="hoan_tat" <?= $booking['status'] == 'hoan_tat' ? 'selected' : '' ?>>Hoàn tất</option>
                                                <option value="da_huy" <?= $booking['status'] == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                                                <option value="expired" <?= $booking['status'] == 'expired' ? 'selected' : '' ?>>Hết hạn</option>
                                            </select>
                                            <label for="status">Trạng thái Booking <span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <!-- Passenger Counts -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="adults" name="adults"
                                                value="<?= $booking['adults'] ?>" min="1" required>
                                            <label for="adults">Người lớn <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="children" name="children"
                                                value="<?= $booking['children'] ?>" min="0">
                                            <label for="children">Trẻ em</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="infants" name="infants"
                                                value="<?= $booking['infants'] ?>" min="0">
                                            <label for="infants">Em bé</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Staff & Suppliers -->
                    <div class="form-step" id="step-2">
                        <!-- Supplier Management -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-info d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-buildings"></i> Quản lý Nhà cung cấp
                                </h6>
                                <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="addSupplierRow()">
                                    <i class="ph ph-plus me-1"></i> Thêm supplier
                                </button>
                            </div>
                            <div class="card-body-premium p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="suppliers-table">
                                        <thead>
                                            <tr>
                                                <th width="20%">Loại dịch vụ</th>
                                                <th width="30%">Nhà cung cấp</th>
                                                <th width="10%">S.Lượng</th>
                                                <th width="15%">Giá (VNĐ)</th>
                                                <th width="20%">Ghi chú</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="suppliers-tbody">
                                            <?php if (!empty($bookingSuppliers)): ?>
                                                <?php foreach ($bookingSuppliers as $index => $bs): ?>
                                                    <tr>
                                                        <td>
                                                            <select name="suppliers[<?= $index ?>][service_type]" class="form-select form-select-sm" required>
                                                                <option value="tour_operator" <?= $bs['service_type'] == 'tour_operator' ? 'selected' : '' ?>>Tour Operator</option>
                                                                <option value="hotel" <?= $bs['service_type'] == 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                                                                <option value="transport" <?= $bs['service_type'] == 'transport' ? 'selected' : '' ?>>Vận chuyển</option>
                                                                <option value="restaurant" <?= $bs['service_type'] == 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                                                                <option value="guide" <?= $bs['service_type'] == 'guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                                                <option value="other" <?= $bs['service_type'] == 'other' ? 'selected' : '' ?>>Khác</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="suppliers[<?= $index ?>][supplier_id]" class="form-select form-select-sm" required>
                                                                <?php foreach ($suppliers as $s): ?>
                                                                    <option value="<?= $s['id'] ?>" <?= $bs['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($s['name']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="suppliers[<?= $index ?>][quantity]"
                                                                value="<?= $bs['quantity'] ?>" class="form-control form-control-sm text-center" min="1" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="suppliers[<?= $index ?>][price]"
                                                                value="<?= $bs['price'] ?>" class="form-control form-control-sm text-end" min="0" step="10000">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="suppliers[<?= $index ?>][notes]"
                                                                value="<?= htmlspecialchars($bs['notes'] ?? '') ?>" class="form-control form-control-sm" placeholder="...">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-icon-only-sm text-danger" onclick="removeSupplierRow(this)">
                                                                <i class="ph-bold ph-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (empty($bookingSuppliers)): ?>
                                    <div class="text-center text-muted py-4 border rounded-3 border-dashed mt-2" id="empty-suppliers-message">
                                        <i class="ph-bold ph-building-office fa-2x mb-2 text-light"></i>
                                        <p class="small mb-0">Chưa có nhà cung cấp nào được gán</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Logistics & Operations -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white">
                            <div class="card-header-premium p-3 px-4 border-bottom border-light">
                                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                                    <i class="ph-fill ph-bus"></i> Phân công & Ghi chú nội bộ
                                </h6>
                            </div>
                            <div class="card-body-premium p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating text-dark">
                                            <select class="form-select fw-medium" id="bus_company_id" name="bus_company_id">
                                                <option value="">-- Chưa phân công --</option>
                                                <?php if (!empty($busCompanies)): ?>
                                                    <?php foreach ($busCompanies as $company): ?>
                                                        <option value="<?= $company['id'] ?>"
                                                            <?= ($company['id'] == ($booking['bus_company_id'] ?? '')) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($company['company_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="bus_company_id" class="text-muted small">Nhà xe đối tác</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control fw-medium" id="notes" name="notes" style="height: 120px" placeholder=" "><?= htmlspecialchars($booking['notes'] ?? '') ?></textarea>
                                            <label for="notes" class="text-muted small">Yêu cầu đặc biệt cho điều hành</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step" id="step-3">
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
                                        <h6 class="text-muted small fw-bold text-uppercase mb-3 letter-spacing-05">Thông tin cơ bản</h6>
                                        <div class="summary-grid row g-3">
                                            <div class="col-sm-6 summary-item">
                                                <span class="label text-muted small d-block">Mã đơn hàng</span>
                                                <span class="value fw-bold">#<?= $booking['id'] ?></span>
                                            </div>
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
                                                <span class="label text-muted small d-block">Trạng thái</span>
                                                <span class="value fw-bold" id="summary-status">--</span>
                                            </div>
                                            <div class="col-sm-6 summary-item">
                                                <span class="label text-muted small d-block">Nhà xe</span>
                                                <span class="value fw-bold" id="summary-bus-company">Chưa phân công</span>
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

                <!-- Sidebar Actions -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 90px; z-index: 100;">
                        <!-- Action Card -->
                        <div class="card-premium mb-4 border-0 shadow-sm bg-white overflow-hidden">
                            <div class="p-4">
                                <h6 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                                    <i class="ph-duotone ph-lightning text-warning fs-5"></i> Thao tác nhanh
                                </h6>
                                
                                <div class="d-grid gap-3 mb-4">
                                    <button type="submit" form="booking-edit-form" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3 fw-bold shadow-primary rounded-3 border-0">
                                        <i class="ph-fill ph-floppy-disk fs-5"></i>
                                        Cập nhật Booking
                                    </button>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings" class="btn btn-light shadow-none w-50 justify-content-center py-2 rounded-3 text-muted border-0 bg-light">
                                            <i class="ph ph-x me-1"></i> Hủy bỏ
                                        </a>
                                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=<?= $booking['id'] ?>" class="btn btn-info shadow-none text-white w-50 justify-content-center py-2 rounded-3">
                                            <i class="ph-fill ph-eye me-1"></i> Chi tiết
                                        </a>
                                    </div>
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
                                    <i class="ph-fill ph-receipt text-primary"></i> Tóm tắt đơn
                                </h6>
                                <span class="badge bg-primary-subtle text-primary border-0">#<?= $booking['id'] ?></span>
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
                                        <span class="small fw-bold text-muted text-uppercase letter-spacing-1">Tổng cộng:</span>
                                        <div class="text-end">
                                            <div class="text-danger fs-3 fw-800 line-height-1" id="quick-price">0 ₫</div>
                                            <small class="text-muted ultra-small">Đã bao gồm VAT & Phí</small>
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
    // Booking Edit JavaScript (Premium Modernized)
    let currentStep = 1;
    const totalSteps = 3;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupEventListeners();
        updateSummary(); // Initial summary update
    });

    function initializeForm() {
        updateStepDisplay();
        updateNavigationButtons();

        // Auto-select "Bình thường" version (ID=10) if no version is selected
        const versionSelect = document.getElementById('version_id');
        if (versionSelect && !versionSelect.value) {
            const defaultOption = versionSelect.querySelector('option[value="10"]');
            if (defaultOption) {
                versionSelect.value = '10';
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

            const departureSelect = document.getElementById('departure_id');
            departureSelect.innerHTML = '<option value="">-- Chọn ngày khởi hành --</option>';
            document.getElementById('departure-info').textContent = '';
            document.getElementById('booking_date').value = '';

            if (tourId) {
                fetchDepartures(tourId);
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
                const availableSeats = selectedOption.getAttribute('data-seats');
                const maxSeats = selectedOption.getAttribute('data-max-seats');

                document.getElementById('booking_date').value = departureDate;

                document.getElementById('departure-info').innerHTML =
                    `<i class="ph-bold ph-info me-1"></i>Còn ${availableSeats}/${maxSeats} chỗ trống`;

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
                    document.getElementById('total_price').value = priceAdult;
                } else {
                    const tourSelect = document.getElementById('tour_id');
                    const tourPrice = tourSelect.options[tourSelect.selectedIndex].getAttribute('data-price');
                    if (tourPrice) {
                        document.getElementById('total_price').value = tourPrice;
                    }
                }
                updateSummary();
            });
        }

        ['customer_id', 'tour_id', 'version_id', 'departure_id', 'status', 'total_price', 'bus_company_id'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', updateSummary);
            }
        });

        const tourId = document.getElementById('tour_id').value;
        if (tourId) {
            fetchDepartures(tourId, '<?= $booking['booking_date'] ?? '' ?>');
        }
    }

    function fetchDepartures(tourId, selectedDate = null) {
        const departureSelect = document.getElementById('departure_id');
        const infoDiv = document.getElementById('departure-info');

        infoDiv.innerHTML = '<i class="ph-bold ph-spinner-gap ph-spin me-1"></i>Đang tải lịch...';

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
                        option.setAttribute('data-seats', dep.available_seats);
                        option.setAttribute('data-max-seats', dep.max_seats);

                        if (dep.version_name) option.textContent += ` (${dep.version_name})`;
                        if (selectedDate && dep.departure_date === selectedDate) {
                            option.selected = true;
                            setTimeout(() => {
                                departureSelect.dispatchEvent(new Event('change'));
                            }, 100);
                        }
                        departureSelect.appendChild(option);
                    });
                    infoDiv.innerHTML = `<i class="ph-bold ph-check-circle text-success me-1"></i>Tìm thấy ${data.departures.length} lịch`;
                } else {
                    infoDiv.innerHTML = '<i class="ph-bold ph-warning text-warning me-1"></i>Không có lịch khởi hành';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                infoDiv.innerHTML = '<i class="ph-bold ph-x-circle text-danger me-1"></i>Lỗi tải dữ liệu';
            });
    }

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
            // If going forward, check validation
            for (let i = currentStep; i < step; i++) {
                if (!validateSpecificStep(i)) return;
            }
        }
        currentStep = step;
        updateStepDisplay();
        updateNavigationButtons();
        updateSummary();
    }

    function validateSpecificStep(stepNum) {
        const stepElement = document.getElementById(`step-${stepNum}`);
        const requiredFields = stepElement.querySelectorAll('[required]');

        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                // Simple modern toast / alert could be better, but keeping alert for now
                alert('Vui lòng hoàn thành các trường bắt buộc ở bước ' + stepNum);
                return false;
            }
        }
        return true;
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

        if (prevBtn) prevBtn.style.display = currentStep === 1 ? 'none' : 'flex';
        if (nextBtn) nextBtn.style.display = currentStep === totalSteps ? 'none' : 'flex';
    }

    function validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');

        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Vui lòng hoàn thành các trường bắt buộc');
                return false;
            }
        }
        return true;
    }

    function updateSummary() {
        const customerSelect = document.getElementById('customer_id');
        const tourSelect = document.getElementById('tour_id');
        const dateInput = document.getElementById('booking_date');
        const statusSelect = document.getElementById('status');
        const priceInput = document.getElementById('total_price');
        const busCompanySelect = document.getElementById('bus_company_id');

        const customerText = customerSelect.options[customerSelect.selectedIndex]?.text || '--';
        const tourText = tourSelect.options[tourSelect.selectedIndex]?.text || '--';
        const busCompanyText = busCompanySelect?.options[busCompanySelect.selectedIndex]?.text || 'Chưa phân công';
        const price = priceInput.value || '0';
        const statusValue = statusSelect.value;
        const statusText = statusSelect.options[statusSelect.selectedIndex]?.text || '--';

        // Quick summary
        if (document.getElementById('quick-customer')) document.getElementById('quick-customer').textContent = customerText.split('(')[0].trim();
        if (document.getElementById('quick-tour')) document.getElementById('quick-tour').textContent = tourText.split('-')[0].trim();
        if (document.getElementById('quick-price')) document.getElementById('quick-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
        
        const quickStatusBadge = document.getElementById('quick-status-badge');
        if (quickStatusBadge) {
            quickStatusBadge.textContent = statusText;
            quickStatusBadge.className = `badge-premium badge-${statusValue} small`;
        }

        // Final summary
        if (document.getElementById('summary-customer')) document.getElementById('summary-customer').textContent = customerText;
        if (document.getElementById('summary-tour')) document.getElementById('summary-tour').textContent = tourText;
        if (document.getElementById('summary-date')) document.getElementById('summary-date').textContent = dateInput.value || '--';
        if (document.getElementById('summary-status')) document.getElementById('summary-status').textContent = statusText;
        if (document.getElementById('summary-bus-company')) document.getElementById('summary-bus-company').textContent = busCompanyText;
        if (document.getElementById('summary-price')) document.getElementById('summary-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
    }

    // Supplier Row Management
    let supplierIndex = <?= count($bookingSuppliers ?? []) ?>;

    function addSupplierRow() {
        const tbody = document.getElementById('suppliers-tbody');
        const emptyMessage = document.getElementById('empty-suppliers-message');
        if (emptyMessage) emptyMessage.remove();

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="suppliers[${supplierIndex}][service_type]" class="form-select form-select-sm" required>
                    <option value="tour_operator">Tour Operator</option>
                    <option value="hotel">Khách sạn</option>
                    <option value="transport">Vận chuyển</option>
                    <option value="restaurant">Nhà hàng</option>
                    <option value="guide">Hướng dẫn viên</option>
                    <option value="other">Khác</option>
                </select>
            </td>
            <td>
                <select name="suppliers[${supplierIndex}][supplier_id]" class="form-select form-select-sm" required>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="number" name="suppliers[${supplierIndex}][quantity]" value="1" class="form-control form-control-sm text-center" min="1" required>
            </td>
            <td>
                <input type="number" name="suppliers[${supplierIndex}][price]" value="0" class="form-control form-control-sm text-end" min="0" step="10000">
            </td>
            <td>
                <input type="text" name="suppliers[${supplierIndex}][notes]" class="form-control form-control-sm" placeholder="...">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-icon-only-sm text-danger" onclick="removeSupplierRow(this)">
                    <i class="ph-bold ph-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        supplierIndex++;
    }

    function removeSupplierRow(btn) {
        const row = btn.closest('tr');
        row.remove();
        const tbody = document.getElementById('suppliers-tbody');
        if (tbody.children.length === 0) {
            tbody.parentElement.parentElement.insertAdjacentHTML('afterend', `
                <div class="text-center text-muted py-4 border rounded-3 border-dashed mt-2" id="empty-suppliers-message">
                    <i class="ph-bold ph-building-office fa-2x mb-2 text-light"></i>
                    <p class="small mb-0">Chưa có nhà cung cấp nào được gán</p>
                </div>
            `);
        }
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>