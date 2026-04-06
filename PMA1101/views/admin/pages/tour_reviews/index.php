<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard reviews-page">
    <div class="dashboard-container px-4 py-4">
        <header class="mb-4">
            <h4 class="fw-bold mb-0">Quản lý Đánh giá & Phản hồi</h4>
            <p class="text-muted small mb-0">Duyệt hoặc ẩn các đánh giá từ khách hàng sau khi đi tour.</p>
        </header>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-muted small fw-bold text-uppercase">
                        <tr>
                            <th class="ps-4">Khách hàng</th>
                            <th>Tour</th>
                            <th>Đánh giá</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có đánh giá nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                                <tr id="review-row-<?= $review['id'] ?>">
                                    <td class="ps-4">
                                        <div class="fw-bold"><?= htmlspecialchars($review['full_name']) ?></div>
                                        <div class="text-muted small"><?= $review['email'] ?></div>
                                    </td>
                                    <td class="small" style="max-width: 200px;"><?= htmlspecialchars($review['tour_name']) ?></td>
                                    <td>
                                        <div class="text-warning small d-flex gap-1">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="small text-muted" style="max-width: 300px; white-space: normal;">
                                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                    </td>
                                    <td>
                                        <div class="status-badge" id="status-<?= $review['id'] ?>">
                                            <?php if ($review['status'] == 'approved'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Hiển thị</span>
                                            <?php elseif ($review['status'] == 'rejected'): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Bị ẩn</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Chờ duyệt</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-none">
                                            <?php if ($review['status'] != 'approved'): ?>
                                                <button type="button" class="btn btn-sm btn-white border update-status" data-id="<?= $review['id'] ?>" data-status="approved" title="Duyệt">
                                                    <i class="ph ph-check-circle text-success"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($review['status'] != 'rejected'): ?>
                                                <button type="button" class="btn btn-sm btn-white border update-status" data-id="<?= $review['id'] ?>" data-status="rejected" title="Ẩn">
                                                    <i class="ph ph-prohibit text-danger"></i>
                                                </button>
                                            <?php endif; ?>
                                            <form action="<?= BASE_URL_ADMIN ?>&action=tour_reviews/delete" method="POST" class="d-inline" onsubmit="return confirm('Xóa vĩnh viễn đánh giá này?')">
                                                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-white border text-muted" title="Xóa">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusBtns = document.querySelectorAll('.update-status');
    statusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const status = this.dataset.status;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);

            fetch('<?= BASE_URL_ADMIN ?>&action=tour_reviews/updateStatus', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        });
    });
});
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
