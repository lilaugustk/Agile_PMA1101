<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$statusLabels = [
    'not_arrived' => 'Chưa đến',
    'checked_in' => 'Đã đến',
    'absent' => 'Vắng mặt'
];
$statusColors = [
    'not_arrived' => 'warning',
    'checked_in' => 'success',
    'absent' => 'danger'
];
$passengerTypeLabels = [
    'adult' => 'Người lớn',
    'child' => 'Trẻ em',
    'infant' => 'Em bé'
];
?>

<main class="dashboard">
    <div class="dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours/departures" class="text-decoration-none text-muted">Vận hành đoàn</a></li>
                        <li class="breadcrumb-item active">Điểm danh đoàn</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-light border" target="_blank" href="<?= BASE_URL_ADMIN ?>&action=bookings/print-group-list&departure_id=<?= (int)($booking['departure_id'] ?? 0) ?>">In danh sách</a>
                <a class="btn btn-outline-secondary" href="<?= BASE_URL_ADMIN ?>&action=tours/departures">Quay lại</a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Tổng khách</div><div class="fw-bold fs-4" id="stat-total"><?= (int)$stats['total'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Đã đến</div><div class="fw-bold fs-4 text-success" id="stat-checked-in"><?= (int)$stats['checked_in'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Chưa đến</div><div class="fw-bold fs-4 text-warning" id="stat-not-arrived"><?= (int)$stats['not_arrived'] ?></div></div></div></div>
            <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Vắng mặt</div><div class="fw-bold fs-4 text-danger" id="stat-absent"><?= (int)$stats['absent'] ?></div></div></div></div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Booking</th>
                            <th>Loại khách</th>
                            <th>Trạng thái</th>
                            <th>Thời gian điểm danh</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $idx => $customer): ?>
                            <?php
                            $status = $customer['checkin_status'] ?? 'not_arrived';
                            $customerKey = !empty($customer['id']) ? $customer['id'] : ('main_' . ($customer['booking_code'] ?? 0));
                            ?>
                            <tr data-customer-id="<?= htmlspecialchars($customerKey) ?>">
                                <td><?= $idx + 1 ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($customer['full_name'] ?? '') ?></td>
                                <td>#<?= htmlspecialchars($customer['booking_code'] ?? '') ?></td>
                                <td><?= htmlspecialchars($passengerTypeLabels[$customer['passenger_type'] ?? 'adult'] ?? 'Người lớn') ?></td>
                                <td><span class="badge bg-<?= $statusColors[$status] ?> status-badge"><?= $statusLabels[$status] ?></span></td>
                                <td class="checkin-time-cell">
                                    <?php if (!empty($customer['checkin_time']) && $status === 'checked_in'): ?>
                                        <span class="small text-muted"><?= date('H:i d/m/Y', strtotime($customer['checkin_time'])) ?></span>
                                    <?php else: ?>
                                        <span class="small text-muted">--</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-success btn-checkin" data-customer-id="<?= htmlspecialchars($customerKey) ?>" data-status="checked_in">Đã đến</button>
                                        <button type="button" class="btn btn-outline-danger btn-checkin" data-customer-id="<?= htmlspecialchars($customerKey) ?>" data-status="absent">Vắng</button>
                                        <button type="button" class="btn btn-outline-secondary btn-checkin" data-customer-id="<?= htmlspecialchars($customerKey) ?>" data-status="not_arrived">Reset</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-checkin').forEach(btn => {
        btn.addEventListener('click', function () {
            const customerId = this.dataset.customerId;
            const status = this.dataset.status;
            const formData = new FormData();
            formData.append('customer_id', customerId);
            formData.append('status', status);

            fetch('<?= BASE_URL_ADMIN ?>&action=bookings/update-checkin', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Cập nhật thất bại');
                        return;
                    }

                    const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
                    const badge = row ? row.querySelector('.status-badge') : null;
                    if (badge) {
                        const map = {
                            checked_in: { cls: 'success', text: 'Đã đến' },
                            absent: { cls: 'danger', text: 'Vắng mặt' },
                            not_arrived: { cls: 'warning', text: 'Chưa đến' }
                        };
                        badge.className = `badge bg-${map[status].cls} status-badge`;
                        badge.textContent = map[status].text;
                    }
                    const timeCell = row ? row.querySelector('.checkin-time-cell') : null;
                    if (timeCell) {
                        const ts = data.timestamp ? data.timestamp : '--';
                        timeCell.innerHTML = `<span class="small text-muted">${ts}</span>`;
                    }
                    location.reload();
                })
                .catch(() => alert('Lỗi kết nối'));
        });
    });
});
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
