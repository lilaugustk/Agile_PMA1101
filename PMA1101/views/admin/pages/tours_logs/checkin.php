<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=tours_logs/tour_detail&id=<?= $tour['id'] ?>" class="text-decoration-none small text-muted d-flex align-items-center gap-1 mb-2">
                    <i class="ph ph-arrow-left"></i> Quay lại nhật ký
                </a>
                <h4 class="fw-bold mb-0">Điểm danh đoàn</h4>
                <p class="text-muted small mb-0"><?= htmlspecialchars($tour['name']) ?></p>
            </div>
            <div class="bg-primary-subtle text-primary px-3 py-2 rounded-3 text-center">
                <div class="fw-bold h5 mb-0" id="checked-count">0/<?= count($customers) ?></div>
                <div style="font-size: 10px; text-transform: uppercase; font-weight: 700;">Đã đến</div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="ph ph-magnifying-glass"></i></span>
                    <input type="text" id="memberSearch" class="form-control border-0 bg-light" placeholder="Tìm tên khách hàng...">
                </div>
            </div>
        </div>

        <!-- Passenger List -->
        <div class="list-group list-group-flush shadow-sm rounded-4 overflow-hidden border">
            <?php if (empty($customers)): ?>
                <div class="list-group-item text-center py-5 text-muted">
                    <i class="ph ph-users-three display-4 opacity-25 d-block mb-3"></i>
                    <p>Chưa có danh sách khách hàng cho tour này.</p>
                </div>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                    <div class="list-group-item p-3 member-item" data-name="<?= strtolower(htmlspecialchars($customer['full_name'])) ?>">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                    <?= strtoupper(substr($customer['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold mb-0"><?= htmlspecialchars($customer['full_name']) ?></div>
                                    <div class="text-muted" style="font-size: 12px;">
                                        <span class="badge bg-light text-dark border me-1"><?= ucfirst($customer['passenger_type']) ?></span>
                                        Booking #<?= $customer['booking_id'] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input checkin-toggle" type="checkbox" role="switch" 
                                       style="width: 50px; height: 26px; cursor: pointer;"
                                       data-id="<?= $customer['id'] ?>"
                                       <?= $customer['is_checked_in'] ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <?php if ($customer['special_request']): ?>
                            <div class="mt-2 p-2 bg-warning-subtle rounded-3 small border border-warning-subtle">
                                <i class="ph ph-warning-circle text-warning me-1"></i>
                                <strong>Lưu ý:</strong> <?= htmlspecialchars($customer['special_request']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
    .avatar-sm { font-size: 14px; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; }
    .member-item { transition: background-color 0.2s; }
    .member-item:active { background-color: #f8f9fa; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checks = document.querySelectorAll('.checkin-toggle');
    const searchInput = document.getElementById('memberSearch');
    const checkedCountEl = document.getElementById('checked-count');
    const totalCount = <?= count($customers) ?>;

    function updateCounter() {
        const checked = document.querySelectorAll('.checkin-toggle:checked').length;
        checkedCountEl.textContent = `${checked}/${totalCount}`;
    }

    updateCounter();

    // Toggle checkin
    checks.forEach(check => {
        check.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);

            fetch('<?= BASE_URL_ADMIN ?>&action=tours_logs/toggleCheckin', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCounter();
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể cập nhật'));
                    this.checked = !this.checked;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối server');
                this.checked = !this.checked;
            });
        });
    });

    // Search
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.member-item').forEach(item => {
            const name = item.dataset.name;
            if (name.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
