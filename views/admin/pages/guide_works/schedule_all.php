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
                    <li class="breadcrumb-item active" aria-current="page">Phân công công việc</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);" onclick="location.reload()">
                <i class="ph ph-arrows-clockwise" style="font-size: 1.1rem;"></i> Làm mới
            </button>
        </div>
    </div>

    <?php foreach ($guideAssignments as $group): ?>
        <div class="card card-premium border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="ph-fill ph-user-gear fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 1rem; color: #1e293b;"><?= htmlspecialchars($group['guide']['full_name'] ?? 'N/A') ?></h6>
                            <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.8rem;">
                                <span><i class="ph ph-envelope me-1"></i><?= htmlspecialchars($group['guide']['email'] ?? 'N/A') ?></span>
                                <span class="text-gray-300">•</span>
                                <span><i class="ph ph-phone me-1"></i><?= htmlspecialchars($group['guide']['phone'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (empty($group['assignments'])): ?>
                    <div class="text-center py-4 bg-light rounded" style="border: 1px dashed #e2e8f0; border-radius: var(--radius-md) !important;">
                        <i class="ph ph-calendar-blank text-muted fs-2 mb-2"></i>
                        <p class="text-muted mb-0 small">Chưa có tour nào được phân công cho hướng dẫn viên này.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="bg-light text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-4 border-0 py-3">Tour</th>
                                    <th class="border-0 py-3">Thời gian</th>
                                    <th class="border-0 py-3 text-center">Trạng thái</th>
                                    <th class="pe-4 border-0 py-3 text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($group['assignments'] as $a): ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($a['tour_name'] ?? '') ?></div>
                                            <div class="text-muted small">Mã: #<?= str_pad($a['tour_id'] ?? '0', 4, '0', STR_PAD_LEFT) ?></div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-medium small"><i class="ph ph-calendar-check me-1 text-primary"></i><?= htmlspecialchars($a['start_date'] ?? '') ?></span>
                                                <span class="text-muted small"><i class="ph ph-calendar-x me-1 text-danger"></i><?= htmlspecialchars($a['end_date'] ?? '') ?></span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <?php
                                            $status = $a['status'] ?? 'pending';
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning', 'label' => 'Chưa bắt đầu', 'icon' => 'ph-clock'],
                                                'active' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'label' => 'Đang diễn ra', 'icon' => 'ph-play-circle'],
                                                'completed' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'label' => 'Hoàn thành', 'icon' => 'ph-check-circle']
                                            ];
                                            $config = $statusConfig[$status] ?? ['bg' => 'bg-light', 'text' => 'text-muted', 'label' => $status, 'icon' => 'ph-info'];
                                            ?>
                                            <span class="badge rounded-pill <?= $config['bg'] ?> <?= $config['text'] ?> px-3 py-1 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                                <i class="ph-fill <?= $config['icon'] ?>"></i>
                                                <?= $config['label'] ?>
                                            </span>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="<?= BASE_URL_ADMIN ?>&action=guide/tourDetail&id=<?= $a['tour_id'] ?>&guide_id=<?= $group['guide']['id'] ?>" 
                                                   class="btn btn-icon-sm btn-light-primary" title="Chi tiết">
                                                    <i class="ph ph-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN ?>&action=tour_vehicles&assignment_id=<?= $a['id'] ?>" 
                                                   class="btn btn-icon-sm btn-light-warning" title="Phân công xe">
                                                    <i class="ph ph-bus"></i>
                                                </a>
                                                <button class="btn btn-icon-sm btn-light-danger remove-assignment-btn"
                                                    data-assignment-id="<?= $a['id'] ?>"
                                                    data-tour-name="<?= htmlspecialchars($a['tour_name'] ?? '') ?>"
                                                    data-guide-name="<?= htmlspecialchars($group['guide']['full_name'] ?? '') ?>"
                                                    title="Xóa phân công">
                                                    <i class="ph ph-trash"></i>
                                                </button>
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
    <?php endforeach; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle status change
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const assignmentId = this.dataset.assignmentId;
                const newStatus = this.value;
                const originalValue = this.querySelector('option[selected]')?.value || 'pending';

                if (!confirm('Bạn có chắc muốn cập nhật trạng thái?')) {
                    this.value = originalValue;
                    return;
                }

                // Disable select
                this.disabled = true;

                fetch('<?= BASE_URL_ADMIN ?>&action=guide/updateStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `assignment_id=${assignmentId}&status=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ ' + data.message);
                            location.reload();
                        } else {
                            alert('❌ ' + data.message);
                            this.value = originalValue;
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật trạng thái');
                        this.value = originalValue;
                        this.disabled = false;
                    });
            });
        });

        // Handle remove assignment buttons
        document.querySelectorAll('.remove-assignment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const assignmentId = this.dataset.assignmentId;
                const tourName = this.dataset.tourName;
                const guideName = this.dataset.guideName;

                if (confirm(`Bạn có chắc muốn xóa phân công tour "${tourName}" của HDV "${guideName}"?`)) {
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