<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=bus-companies" class="text-muted text-decoration-none">Quản lý Nhà Xe</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết Nhà Xe</li>
                </ol>
            </nav>
            <h4 class="fw-bold mt-2 mb-0">Hồ sơ Nhà Xe: <?= htmlspecialchars($busCompany['company_name']) ?></h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=bus-companies" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2" style="border-radius: var(--radius-md);">
                <i class="ph ph-arrow-left" style="font-size: 1.1rem;"></i> Quay lại
            </a>
            <a href="<?= BASE_URL_ADMIN ?>&action=bus-companies/edit&id=<?= $busCompany['id'] ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-pencil-simple" style="font-size: 1.1rem;"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="card card-premium mb-4">
                <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                    <i class="ph ph-info-circle text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0">Thông tin cơ bản</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Mã nhà xe</label>
                                <div class="bg-light px-3 py-2 rounded-3 border fw-bold text-primary"><?= htmlspecialchars($busCompany['company_code']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Tên nhà xe</label>
                                <div class="fs-5 fw-bold text-dark"><?= htmlspecialchars($busCompany['company_name']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Người liên hệ</label>
                                <div class="d-flex align-items-center gap-2 text-dark">
                                    <i class="ph ph-user-circle fs-5 text-muted"></i>
                                    <?= htmlspecialchars($busCompany['contact_person'] ?? 'Chưa cập nhật') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Số điện thoại</label>
                                <div class="d-flex align-items-center gap-2 text-dark">
                                    <i class="ph ph-phone-call fs-5 text-success"></i>
                                    <?= !empty($busCompany['phone']) ? htmlspecialchars($busCompany['phone']) : 'Chưa cập nhật' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Email</label>
                                <div class="d-flex align-items-center gap-2 text-dark">
                                    <i class="ph ph-envelope fs-5 text-info"></i>
                                    <?= !empty($busCompany['email']) ? htmlspecialchars($busCompany['email']) : 'Chưa cập nhật' ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Giấy phép kinh doanh</label>
                                <div class="d-flex align-items-center gap-2 text-dark">
                                    <i class="ph ph-cardholder fs-5 text-muted"></i>
                                    <?= htmlspecialchars($busCompany['business_license'] ?? 'N/A') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 0.5px;">Địa chỉ trụ sở</label>
                                <div class="d-flex align-items-start gap-2 text-dark p-3 bg-light rounded-3 border shadow-none">
                                    <i class="ph ph-map-pin fs-5 text-danger pt-1"></i>
                                    <span><?= !empty($busCompany['address']) ? htmlspecialchars($busCompany['address']) : 'Chưa cập nhật' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-premium">
                <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                    <i class="ph ph-bus text-success fs-5"></i>
                    <h6 class="fw-bold mb-0">Năng lực phương tiện</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="info-group text-center p-3 bg-light rounded-3 border">
                                <label class="text-muted small fw-bold text-uppercase mb-1 d-block">Tổng số xe</label>
                                <h3 class="fw-bold text-primary mb-0"><?= number_format($busCompany['total_vehicles'] ?? 0) ?></h3>
                                <small class="text-muted">phương tiện</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Dòng xe chủ yếu</label>
                                <div class="badge bg-success-subtle text-success px-3 py-2 rounded-pill shadow-none" style="font-size: 0.85rem;">
                                    <i class="ph ph-tag me-1"></i> <?= htmlspecialchars($busCompany['vehicle_brand'] ?? 'Chưa cập nhật') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-group">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Đánh giá chung</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <?php
                                        $rating = $busCompany['rating'] ?? 5;
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="<?= $i <= floor($rating) ? 'ph-fill ph-star text-warning' : 'ph ph-star text-muted opacity-50' ?>" style="font-size: 1.1rem;"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="fw-bold fs-5 text-dark"><?= number_format($rating, 1) ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($busCompany['notes'])): ?>
                            <div class="col-12">
                                <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Ghi chú quản lý</label>
                                <div class="p-3 border rounded-3 bg-white" style="border-style: dashed !important; border-color: #ddd !important;">
                                    <?= nl2br(htmlspecialchars($busCompany['notes'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card card-premium mb-4">
                <div class="p-3 border-bottom border-light bg-white d-flex align-items-center gap-2">
                    <i class="ph ph-toggle-left text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0">Trạng thái hệ thống</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <?php
                    $statusClass = ($busCompany['status'] == 'active') ? 'success' : 'secondary';
                    $statusText = ($busCompany['status'] == 'active') ? 'Đang hoạt động' : 'Ngừng hoạt động';
                    $statusIcon = ($busCompany['status'] == 'active') ? 'check-circle' : 'minus-circle';
                    ?>
                    <div class="badge bg-<?= $statusClass ?>-subtle text-<?= $statusClass ?> fs-6 px-4 py-3 rounded-pill w-100 shadow-none border border-<?= $statusClass ?>-subtle mb-4">
                        <i class="ph ph-<?= $statusIcon ?> me-2"></i>
                        <span class="fw-bold"><?= $statusText ?></span>
                    </div>

                    <div class="text-start border-top pt-4">
                        <div class="info-group mb-3">
                            <label class="text-muted small fw-bold d-block mb-1">Ngày tham gia</label>
                            <span class="text-dark small"><i class="ph ph-calendar-plus me-1 text-muted"></i> <?= date('d/m/Y H:i', strtotime($busCompany['created_at'])) ?></span>
                        </div>
                        <div class="info-group">
                            <label class="text-muted small fw-bold d-block mb-1">Cập nhật lần cuối</label>
                            <span class="text-dark small"><i class="ph ph-clock-counter-clockwise me-1 text-muted"></i> <?= date('d/m/Y H:i', strtotime($busCompany['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card card-premium border-danger-subtle bg-danger-subtle bg-opacity-10">
                <div class="p-3 border-bottom border-danger-subtle d-flex align-items-center gap-2">
                    <i class="ph ph-warning-octagon text-danger fs-5"></i>
                    <h6 class="fw-bold mb-0 text-danger">Khu vực nguy hiểm</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-4">Việc xóa nhà xe có thể gây ra lỗi khi truy xuất lịch sử tour và booking liên quan.</p>
                    <button type="button" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2" onclick="deleteBusCompany()">
                        <i class="ph ph-trash"></i> Xóa House xe
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<form id="deleteForm" method="POST" action="<?= BASE_URL_ADMIN ?>&action=bus-companies/delete" style="display: none;">
    <input type="hidden" name="id" value="<?= $busCompany['id'] ?>">
</form>

<script>
    function deleteBusCompany() {
        if (confirm('Bạn có chắc muốn xóa nhà xe "<?= htmlspecialchars($busCompany['company_name']) ?>"?\nLưu ý: Các booking và tour assignment liên quan sẽ bị ảnh hưởng.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
mentById('deleteForm').submit();
        }
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>