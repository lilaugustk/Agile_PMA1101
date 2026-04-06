<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$departure = $data['departure'] ?? [];
$resources = $data['resources'] ?? [];
$suppliers = $data['suppliers'] ?? [];
$totalRevenue = $data['totalRevenue'] ?? 0;

$serviceTypes = [
    'hotel' => 'Khách sạn / Chỗ ở',
    'bus' => 'Vận chuyển / Xe',
    'restaurant' => 'Ăn uống / Nhà hàng',
    'guide' => 'Hướng dẫn viên',
    'entrance_ticket' => 'Vé tham quan',
    'insurance' => 'Bảo hiểm',
    'other' => 'Khác'
];
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours/departures">Vận hành đoàn</a></li>
                    <li class="breadcrumb-item active">Gán tài nguyên & NCC</li>
                </ol>
            </nav>
            <h4 class="fw-bold mt-2">Vận hành đoàn: <?= date('d/m/Y', strtotime($departure['departure_date'])) ?></h4>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours/departures" class="btn btn-sm btn-light border d-flex align-items-center gap-2">
                <i class="ph ph-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Departure Summary -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-4">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="ph ph-airplane-tilt-fill fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold"><?= htmlspecialchars($departure['tour_name'] ?? 'N/A') ?></h5>
                                <p class="mb-0 text-white-50 small">Khởi hành: <?= date('d/m/Y', strtotime($departure['departure_date'])) ?> | Trạng thái: <?= strtoupper($departure['status']) ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="text-center">
                                <span class="text-white-50 small d-block">Số lượng khách</span>
                                <span class="fw-bold fs-5"><?= $departure['booked_seats'] ?></span>
                            </div>
                            <div class="text-center">
                                <span class="text-white-50 small d-block">Doanh thu tạm tính</span>
                                <span class="fw-bold fs-5"><?= number_format($totalRevenue, 0, ',', '.') ?> ₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="ph ph-truck text-primary"></i> Danh sách Tài nguyên & Nhà cung cấp
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary-subtle text-primary border-primary-subtle d-flex align-items-center gap-2" id="btn-add-resource">
                        <i class="ph ph-plus-circle"></i> Thêm chi phí / NCC
                    </button>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL_ADMIN ?>&action=tours/save-departure-resources" method="POST" id="resource-form">
                        <input type="hidden" name="departure_id" value="<?= $departure['id'] ?>">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="resource-table">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th style="width: 25%;">Nhà cung cấp</th>
                                        <th style="width: 20%;">Loại dịch vụ</th>
                                        <th style="width: 10%;" class="text-center">Số lượng</th>
                                        <th style="width: 15%;" class="text-end">Đơn giá</th>
                                        <th style="width: 15%;" class="text-end">Thành tiền</th>
                                        <th style="width: 10%;" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($resources)): ?>
                                        <?php foreach ($resources as $index => $res): ?>
                                            <tr class="resource-row">
                                                <td>
                                                    <select name="resources[<?= $index ?>][supplier_id]" class="form-select form-select-sm shadow-none supplier-select" required>
                                                        <option value="">Chọn NCC...</option>
                                                        <?php foreach ($suppliers as $s): ?>
                                                            <option value="<?= $s['id'] ?>" <?= $res['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($s['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="resources[<?= $index ?>][service_type]" class="form-select form-select-sm shadow-none">
                                                        <?php foreach ($serviceTypes as $val => $label): ?>
                                                            <option value="<?= $val ?>" <?= $res['service_type'] == $val ? 'selected' : '' ?>><?= $label ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="resources[<?= $index ?>][quantity]" class="form-control form-control-sm text-center quantity-input" value="<?= $res['quantity'] ?>" min="1">
                                                </td>
                                                <td>
                                                    <input type="number" name="resources[<?= $index ?>][unit_price]" class="form-control form-control-sm text-end price-input" value="<?= (int)$res['unit_price'] ?>">
                                                </td>
                                                <td class="text-end fw-bold text-dark total-amount">
                                                    <?= number_format($res['total_amount'], 0, ',', '.') ?> ₫
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-light border text-danger btn-remove-row">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light fw-bold">
                                        <td colspan="4" class="text-end">TỔNG CHI PHÍ ĐOÀN</td>
                                        <td class="text-end text-danger" id="final-total">0 ₫</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-4 pt-4 border-top d-flex justify-content-end gap-3">
                            <span class="text-muted d-flex align-items-center gap-2"><i class="ph ph-info"></i> Chi phí sẽ được cập nhật vào báo cáo P&L đoàn.</span>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <i class="ph ph-floppy-disk"></i> Lưu thay đổi & Chốt đoàn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<template id="resource-row-template">
    <tr class="resource-row">
        <td>
            <select name="resources[INDEX][supplier_id]" class="form-select form-select-sm shadow-none" required>
                <option value="">Chọn NCC...</option>
                <?php foreach ($suppliers as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <select name="resources[INDEX][service_type]" class="form-select form-select-sm shadow-none">
                <?php foreach ($serviceTypes as $val => $label): ?>
                    <option value="<?= $val ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" name="resources[INDEX][quantity]" class="form-control form-control-sm text-center quantity-input" value="1" min="1">
        </td>
        <td>
            <input type="number" name="resources[INDEX][unit_price]" class="form-control form-control-sm text-end price-input" value="0">
        </td>
        <td class="text-end fw-bold text-dark total-amount">0 ₫</td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-light border text-danger btn-remove-row">
                <i class="ph ph-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#resource-table tbody');
    const btnAdd = document.querySelector('#btn-add-resource');
    const template = document.querySelector('#resource-row-template').innerHTML;
    let index = <?= !empty($resources) ? count($resources) : 0 ?>;

    function formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(num) + ' ₫';
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.resource-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const rowTotal = qty * price;
            total += rowTotal;
            row.querySelector('.total-amount').textContent = formatNumber(rowTotal);
        });
        document.getElementById('final-total').textContent = formatNumber(total);
    }

    btnAdd.addEventListener('click', () => {
        const row = template.replace(/INDEX/g, index++);
        tableBody.insertAdjacentHTML('beforeend', row);
        calculateTotal();
    });

    tableBody.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            e.target.closest('tr').remove();
            calculateTotal();
        }
    });

    tableBody.addEventListener('input', (e) => {
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
            calculateTotal();
        }
    });

    calculateTotal();
});
</script>
