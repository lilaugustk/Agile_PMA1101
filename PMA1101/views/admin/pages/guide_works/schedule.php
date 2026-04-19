<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="dashboard">
    <div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=guide/schedule_all" class="text-muted text-decoration-none">Lịch làm việc</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lịch của tôi</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN ?>&action=guide/schedule_all" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-users" style="font-size: 1.1rem;"></i> Xem tất cả Guide
            </a>
        </div>
    </div>

    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="get" class="row g-2 align-items-end" action="<?= BASE_URL_ADMIN ?>">
                <input type="hidden" name="action" value="guide/schedule">
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.75rem; color: #64748b;">Lọc theo Tour</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-map-pin"></i></span>
                        <select name="tour_id" class="form-select border-start-0 ps-0 shadow-none">
                            <option value="">-- Tất cả Tour --</option>
                            <?php foreach ($tours as $t): ?>
                                <option value="<?= htmlspecialchars($t['id']) ?>" <?= (!empty($filters['tour_id']) && $filters['tour_id'] == $t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.75rem; color: #64748b;">Trạng thái</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-toggle-left"></i></span>
                        <select name="status" class="form-select border-start-0 ps-0 shadow-none">
                            <option value="">-- Tất cả --</option>
                            <option value="assigned" <?= (!empty($filters['status']) && $filters['status'] == 'assigned') ? 'selected' : '' ?>>Chưa bắt đầu</option>
                            <option value="in_progress" <?= (!empty($filters['status']) && $filters['status'] == 'in_progress') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (!empty($filters['status']) && $filters['status'] == 'completed') ? 'selected' : '' ?>>Hoàn thành</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold mb-1" style="font-size: 0.75rem; color: #64748b;">Tìm kiếm nhanh</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" class="form-control border-start-0 ps-0 shadow-none" placeholder="Tên tour, tài xế...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-2 h-100 py-2 shadow-sm" style="border-radius: 8px;">
                        <i class="ph ph-funnel"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-premium border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0 text-dark"><i class="ph ph-list-bullets me-2 text-primary"></i> Danh sách phân công của tôi</h6>
        </div>
        <div class="card-body p-0">
            <?php if (empty($assignments)): ?>
                <div class="text-center py-5">
                    <img src="assets/admin/img/empty-box.png" alt="No data" class="mb-3" style="width: 80px; opacity: 0.5;">
                    <p class="text-muted small">Bạn chưa có tour nào được phân công trong khoảng thời gian này.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="bg-light text-muted fw-semibold" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.4px;">
                            <tr>
                                <th class="ps-4 border-0 py-3">Tour / Thời gian</th>
                                <th class="border-0 py-3 text-center">Trạng thái</th>
                                <th class="border-0 py-3">Tài xế / Liên hệ</th>
                                <th class="pe-4 border-0 py-3 text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $a): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($a['tour_name'] ?? '') ?></div>
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <span><i class="ph ph-calendar me-1"></i><?= htmlspecialchars($a['start_date'] ?? '') ?> - <?= htmlspecialchars($a['end_date'] ?? '') ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <?php
                                        $status = $a['status'] ?? 'pending';
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning', 'label' => 'Chưa bắt đầu'],
                                            'assigned' => ['bg' => 'bg-info-subtle', 'text' => 'text-info', 'label' => 'Đã phân công'],
                                            'in_progress' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'label' => 'Đang diễn ra'],
                                            'completed' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'label' => 'Hoàn thành']
                                        ];
                                        $config = $statusConfig[$status] ?? ['bg' => 'bg-light', 'text' => 'text-muted', 'label' => $status];
                                        ?>
                                        <span class="badge rounded-pill <?= $config['bg'] ?> <?= $config['text'] ?> px-3 py-1 fw-bold" style="font-size: 0.7rem;">
                                            <?= $config['label'] ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted small">
                                        <?php if (!empty($a['driver_name'])): ?>
                                            <div class="text-dark fw-medium mb-1"><i class="ph ph-user me-1"></i><?= htmlspecialchars($a['driver_name'] ?? '') ?></div>
                                            <div class="small"><i class="ph ph-phone me-1"></i><?= htmlspecialchars($a['driver_phone'] ?? 'Chưa cập nhật') ?></div>
                                        <?php else: ?>
                                            <span class="fst-italic">Chưa có thông tin tài xế</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=guide/tourDetail&id=<?= $a['tour_id'] ?>&guide_id=<?= $_SESSION['guide_id'] ?? $a['guide_id'] ?>" 
                                               class="btn btn-icon-sm btn-light-primary" title="Chi tiết">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                            <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                                                <button class="btn btn-icon-sm btn-light-danger remove-assignment-btn"
                                                    data-assignment-id="<?= $a['id'] ?>"
                                                    data-tour-name="<?= htmlspecialchars($a['tour_name'] ?? '') ?>"
                                                    title="Xóa phân công">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle remove assignment buttons
        document.querySelectorAll('.remove-assignment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const assignmentId = this.dataset.assignmentId;
                const tourName = this.dataset.tourName;

                if (confirm(`Bạn có chắc muốn xóa phân công tour "${tourName}"?`)) {
                    // Disable button và hiển thị loading
                    this.disabled = true;
                    this.innerHTML = '<i class="ph ph-spinner ph-spin"></i>';

                    // Send AJAX request
                    fetch('<?= BASE_URL_ADMIN ?>&action=guides/remove-assignment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `assignment_id=${assignmentId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert('❌ ' + data.message);
                                // Re-enable button
                                this.disabled = false;
                                this.innerHTML = '<i class="ph ph-trash"></i>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra! Vui lòng thử lại.');
                            // Re-enable button
                            this.disabled = false;
                            this.innerHTML = '<i class="ph ph-trash"></i>';
                        });
                }
            });
        });
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>