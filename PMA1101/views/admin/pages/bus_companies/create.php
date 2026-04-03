<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);
?>

<main class="content">
    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=bus-companies/store" id="bus-company-form">
        <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bus-companies" class="text-muted text-decoration-none">Quản lý Nhà Xe</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm Nhà Xe Mới</li>
                    </ol>
                </nav>
                <h4 class="fw-bold mt-2 mb-0">Đăng ký Nhà Xe Mới</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN ?>&action=bus-companies" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2" style="border-radius: var(--radius-md);">
                    <i class="ph ph-x" style="font-size: 1.1rem;"></i> Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                    <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Lưu hồ sơ
                </button>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible shadow-sm border-0 mb-4" role="alert" style="border-radius: var(--radius-md); border-left: 4px solid #dc3545 !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="ph-fill ph-warning-circle fs-5"></i>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Thông tin cơ bản -->
                <div class="card card-premium mb-4">
                    <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                        <i class="ph ph-buildings text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Hồ sơ pháp nhân</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control <?= isset($errors['company_code']) ? 'is-invalid' : '' ?>"
                                        id="company_code" name="company_code" required
                                        value="<?= htmlspecialchars($old['company_code'] ?? '') ?>" placeholder=" ">
                                    <label for="company_code">Mã hiệu nhà xe <span class="text-danger">*</span></label>
                                    <?php if (isset($errors['company_code'])): ?>
                                        <div class="invalid-feedback"><?= $errors['company_code'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control <?= isset($errors['company_name']) ? 'is-invalid' : '' ?>"
                                        id="company_name" name="company_name" required
                                        value="<?= htmlspecialchars($old['company_name'] ?? '') ?>" placeholder=" ">
                                    <label for="company_name">Tên đơn vị vận tải <span class="text-danger">*</span></label>
                                    <?php if (isset($errors['company_name'])): ?>
                                        <div class="invalid-feedback"><?= $errors['company_name'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="contact_person" name="contact_person"
                                        value="<?= htmlspecialchars($old['contact_person'] ?? '') ?>" placeholder=" ">
                                    <label for="contact_person">Người đại diện liên hệ</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                        id="phone" name="phone"
                                        value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder=" ">
                                    <label for="phone">Số điện thoại liên lạc</label>
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?= $errors['phone'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        id="email" name="email"
                                        value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder=" ">
                                    <label for="email">Địa chỉ Email</label>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="business_license" name="business_license"
                                        value="<?= htmlspecialchars($old['business_license'] ?? '') ?>" placeholder=" ">
                                    <label for="business_license">Số GPKD / Mã số thuế</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating mb-0">
                                    <textarea class="form-control" id="address" name="address" style="height: 100px" placeholder=" "><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                                    <label for="address">Địa chỉ trụ sở đăng ký</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin xe -->
                <div class="card card-premium">
                    <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                        <i class="ph ph-truck text-success fs-5"></i>
                        <h6 class="fw-bold mb-0">Thông tin đội xe</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating mb-3 text-center">
                                    <input type="number" class="form-control text-center fw-bold" id="total_vehicles" name="total_vehicles" min="0"
                                        value="<?= htmlspecialchars($old['total_vehicles'] ?? '0') ?>" placeholder=" ">
                                    <label for="total_vehicles">Tổng số lượng xe</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="vehicle_brand" name="vehicle_brand"
                                        value="<?= htmlspecialchars($old['vehicle_brand'] ?? '') ?>" placeholder=" ">
                                    <label for="vehicle_brand">Dòng xe chủ lực</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control fw-bold" id="rating" name="rating" min="0" max="5" step="0.1"
                                        value="<?= htmlspecialchars($old['rating'] ?? '5.0') ?>" placeholder=" ">
                                    <label for="rating">Đánh giá ban đầu (0-5)</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="notes" name="notes" style="height: 120px" placeholder=" "><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                                    <label for="notes">Ghi chú & Nhận định ban đầu</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-premium mb-4">
                    <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                        <i class="ph ph-activity text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Khởi tạo trạng thái</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="form-floating mb-3">
                            <select class="form-select fw-bold" id="status" name="status">
                                <option value="active" <?= ($old['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                                <option value="inactive" <?= ($old['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Ngừng hoạt động</option>
                            </select>
                            <label for="status">Trạng thái hợp tác</label>
                        </div>
                        <div class="mt-3 small text-muted text-start p-2 bg-light rounded-3 border-start border-primary border-4">
                            <i class="ph ph-info me-1"></i> Mặc định hồ sơ mới sẽ ở trạng thái **Đang hoạt động**.
                        </div>
                    </div>
                </div>

                <div class="card card-premium sticky-top" style="top: 100px;">
                    <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                        <i class="ph ph-shield-check text-success fs-5"></i>
                        <h6 class="fw-bold mb-0">Xác nhận đăng ký</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-4 text-center">Vui lòng kiểm tra kỹ các thông tin bắt buộc trước khi lưu vào hệ thống.</p>
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="ph ph-check-circle fs-5"></i> Hoàn tất đăng ký
                            </button>
                            <a href="<?= BASE_URL_ADMIN ?>&action=bus-companies" class="btn btn-outline-secondary py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="ph ph-arrow-u-up-left fs-5"></i> Trở về danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>