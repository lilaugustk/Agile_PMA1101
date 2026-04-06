<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard room-allocation-page">
    <div class="dashboard-container px-4 py-4">
        <header class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=bookings/detail&id=<?= $booking['id'] ?>" class="text-decoration-none small text-muted d-flex align-items-center gap-1 mb-2">
                    <i class="ph ph-arrow-left"></i> Quay lại chi tiết booking
                </a>
                <h4 class="fw-bold mb-0">Phân bổ phòng cho đoàn</h4>
                <p class="text-muted small mb-0">Booking #<?= $booking['id'] ?> - <?= htmlspecialchars($booking['tour_name']) ?></p>
            </div>
            <button type="button" id="saveAllocation" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="ph ph-floppy-disk me-2"></i>Lưu phân bổ
            </button>
        </header>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0">Danh sách khách hàng</h6>
                            <div class="d-flex gap-2 text-muted small">
                                <span class="d-flex align-items-center gap-1"><i class="ph ph-users"></i> <?= count($customers) ?> khách</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Họ tên</th>
                                    <th>Loại khách</th>
                                    <th>Số phòng</th>
                                    <th>Yêu cầu (Room Type)</th>
                                    <th class="pe-4">Hành động nhanh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($customer['full_name']) ?></div>
                                            <div class="text-muted small"><?= $customer['gender'] ? ($customer['gender'] == 'male' ? 'Nam' : 'Nữ') : '---' ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?= ucfirst($customer['passenger_type']) ?></span>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="form-control form-control-sm border-0 bg-light room-input" 
                                                   data-id="<?= $customer['id'] ?>" 
                                                   value="<?= htmlspecialchars($customer['room_number'] ?? '') ?>" 
                                                   placeholder="VD: 101, 102...">
                                        </td>
                                        <td>
                                            <div class="text-muted small">
                                                <i class="ph ph-bed me-1"></i><?= $customer['room_type'] ?: '---' ?>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            <button type="button" class="btn btn-sm btn-light border copy-down" title="Sao chép xuống">
                                                <i class="ph ph-arrow-down"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy room number to the next row (useful for families)
    const copyBtns = document.querySelectorAll('.copy-down');
    copyBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const inputs = document.querySelectorAll('.room-input');
            if (inputs[index] && inputs[index+1]) {
                inputs[index+1].value = inputs[index].value;
            }
        });
    });

    // Save allocation
    const saveBtn = document.getElementById('saveAllocation');
    saveBtn.addEventListener('click', function() {
        const inputs = document.querySelectorAll('.room-input');
        const allocations = {};
        inputs.forEach(input => {
            allocations[input.dataset.id] = input.value;
        });

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Đang lưu...';

        const formData = new FormData();
        for (const [id, room] of Object.entries(allocations)) {
            formData.append(`allocations[${id}]`, room);
        }

        fetch('<?= BASE_URL_ADMIN ?>&action=bookings/saveRoomAllocation', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Lưu phân bổ phòng thành công!');
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="ph ph-floppy-disk me-2"></i>Lưu phân bổ';
        });
    });
});
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
