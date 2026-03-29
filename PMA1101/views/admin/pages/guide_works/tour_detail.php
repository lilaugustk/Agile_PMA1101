<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Defensive check for $tour
if (empty($tour)) {
    echo "<main class='content'><div class='alert alert-danger'>Không tìm thấy thông tin tour.</div></main>";
    include_once PATH_VIEW_ADMIN . 'default/footer.php';
    exit;
}

// Prepare passenger type labels
$passengerTypeLabels = [
  'adult' => 'Người lớn',
  'child' => 'Trẻ em',
  'infant' => 'Em bé'
];

// Prepare status labels and colors
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
?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=guide/schedule" class="text-muted text-decoration-none">Lịch làm việc</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết tour</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 mt-1" style="font-size: 1.25rem; letter-spacing: -0.5px;"><?= htmlspecialchars($tour['name'] ?? 'N/A') ?></h4>
            <div class="text-muted small mt-1">
                <i class="ph ph-calendar-blank me-1"></i> <?= htmlspecialchars($assignment['start_date'] ?? 'N/A') ?> - <?= htmlspecialchars($assignment['end_date'] ?? 'N/A') ?>
            </div>
        </div>
        <div class="d-flex gap-2">
            <?php if (!empty($allCustomers)): ?>
                <a href="<?= BASE_URL_ADMIN . '&action=bookings/print-group-list&id=' . (is_array($bookings) && isset($bookings[0]['id']) ? $bookings[0]['id'] : '') ?>"
                  class="btn btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);"
                  target="_blank">
                  <i class="ph ph-printer" style="font-size: 1.1rem;"></i> In danh sách
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL_ADMIN . '&action=guide/schedule' ?>" class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 border shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-arrow-left" style="font-size: 1.1rem;"></i> Quay lại
            </a>
        </div>
    </div>

      <!-- Stats Cards -->
      <?php if (!empty($allCustomers)): ?>
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="card card-premium border-0 shadow-sm">
              <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="ph-fill ph-users fs-4"></i>
                  </div>
                  <div>
                    <h6 class="text-muted mb-0 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Tổng khách</h6>
                    <h4 class="mb-0 fw-bold" id="stat-total"><?= $stats['total'] ?? 0 ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-premium border-0 shadow-sm">
              <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="ph-fill ph-check-circle fs-4"></i>
                  </div>
                  <div>
                    <h6 class="text-muted mb-0 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Đã đến</h6>
                    <h4 class="mb-0 fw-bold" id="stat-checked-in"><?= $stats['checked_in'] ?? 0 ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-premium border-0 shadow-sm">
              <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="ph-fill ph-clock fs-4"></i>
                  </div>
                  <div>
                    <h6 class="text-muted mb-0 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Chưa đến</h6>
                    <h4 class="mb-0 fw-bold" id="stat-not-arrived"><?= $stats['not_arrived'] ?? 0 ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-premium border-0 shadow-sm">
              <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="ph-fill ph-x-circle fs-4"></i>
                  </div>
                  <div>
                    <h6 class="text-muted mb-0 small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Vắng mặt</h6>
                    <h4 class="mb-0 fw-bold" id="stat-absent"><?= $stats['absent'] ?? 0 ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Tour Info Card -->
      <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark"><i class="ph ph-info me-2 text-primary"></i> Thông tin tour</h6>
        </div>
        <div class="card-body p-4">
          <div class="row g-4">
            <div class="col-md-6 border-end">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded p-2 text-primary"><i class="ph ph-tag"></i></div>
                        <div>
                            <div class="text-muted small fw-medium">Danh mục</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars(is_array($tour) ? ($tour['category_name'] ?? 'N/A') : 'N/A') ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded p-2 text-primary"><i class="ph ph-buildings"></i></div>
                        <div>
                            <div class="text-muted small fw-medium">Nhà cung cấp</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars(is_array($tour) ? ($tour['supplier_name'] ?? 'N/A') : 'N/A') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ps-md-4">
              <?php if (!empty($assignment)): ?>
                <div class="mb-4">
                  <label class="form-label text-muted small fw-medium d-block">Trạng thái vận hành</label>
                  <div class="d-flex gap-2">
                    <select class="form-select form-select-sm shadow-none" id="tour-status-select" style="max-width: 200px; border-radius: 8px;">
                      <?php
                      $currentStatus = $assignment['status'] ?? 'pending';
                      $statuses = [
                        'pending' => 'Chưa bắt đầu',
                        'active' => 'Đang diễn ra',
                        'completed' => 'Hoàn thành'
                      ];
                      foreach ($statuses as $value => $label):
                      ?>
                        <option value="<?= $value ?>" <?= $currentStatus === $value ? 'selected' : '' ?>>
                          <?= $label ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-2" id="btn-update-status"
                      data-assignment-id="<?= is_array($assignment) ? ($assignment['id'] ?? '') : '' ?>" style="border-radius: 8px;">
                      <i class="ph ph-floppy-disk"></i> Lưu
                    </button>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div class="bg-light rounded p-2 text-primary"><i class="ph ph-steering-wheel"></i></div>
                    <div>
                        <div class="text-muted small fw-medium">Tài xế</div>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($assignment['driver_name'] ?? 'Chưa phân công') ?></div>
                    </div>
                </div>
              <?php else: ?>
                <div class="alert bg-warning-subtle text-warning border-0 d-flex align-items-center gap-3 p-3 mb-0" style="border-radius: 12px;">
                  <i class="ph-fill ph-warning-circle fs-3"></i>
                  <div class="small fw-medium">Chưa có phân công HDV cho tour này.</div>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <?php if (!empty($tour['description'])): ?>
            <div class="mt-4 pt-4 border-top">
              <h6 class="fw-bold text-dark pb-2"><i class="ph ph-article me-2"></i>Mô tả chi tiết</h6>
              <p class="text-muted mb-0 small lh-lg"><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Customer List with Check-in -->
      <?php if (!empty($allCustomers)): ?>
        <div class="card card-premium border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="fw-bold mb-0 text-dark">
                <i class="ph ph-users me-2 text-primary"></i> Danh sách khách (<?= count($allCustomers) ?>)
              </h6>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm d-flex align-items-center gap-2 px-3 shadow-sm" id="btn-checkin-all" style="border-radius: 8px;">
                  <i class="ph ph-check-square"></i> Check-in đã chọn
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 px-3 shadow-sm" id="btn-select-all" style="border-radius: 8px;">
                  <i class="ph ph-squares-four"></i> Chọn tất cả
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase;">
                  <tr>
                    <th width="40" class="ps-4">
                      <input type="checkbox" class="form-check-input shadow-none" id="checkbox-all" style="cursor: pointer;">
                    </th>
                    <th width="40">STT</th>
                    <th>Thông tin khách</th>
                    <th>Booking / Loại</th>
                    <th>Ghi chú đặc biệt</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thời gian</th>
                    <th class="pe-4 text-end">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allCustomers as $index => $customer): ?>
                    <tr data-customer-id="<?= $customer['id'] ?>">
                      <td class="ps-4">
                        <input type="checkbox" class="form-check-input customer-checkbox shadow-none" value="<?= $customer['id'] ?>" style="cursor: pointer;">
                      </td>
                      <td class="text-muted"><?= $index + 1 ?></td>
                      <td>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($customer['full_name'] ?? '') ?></div>
                        <div class="text-muted d-flex align-items-center gap-2 mt-1" style="font-size: 0.75rem;">
                          <?php if (!empty($customer['phone'])): ?>
                            <span><i class="ph ph-phone me-1"></i><?= htmlspecialchars($customer['phone']) ?></span>
                          <?php endif; ?>
                          <?php if ($customer['is_foc']): ?>
                            <span class="badge bg-info-subtle text-info px-2 py-0" style="font-size: 0.65rem;">FOC</span>
                          <?php endif; ?>
                          <?php if (!empty($customer['is_main'])): ?>
                            <span class="badge bg-primary-subtle text-primary px-2 py-0" style="font-size: 0.65rem;">Người đặt</span>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td>
                        <div class="mb-1"><small class="text-dark fw-medium">#<?= htmlspecialchars($customer['booking_code'] ?? 'N/A') ?></small></div>
                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size: 0.65rem;">
                          <?= $passengerTypeLabels[$customer['passenger_type']] ?? $customer['passenger_type'] ?>
                        </span>
                      </td>
                      <td>
                        <?php if (!empty($customer['special_request'])): ?>
                          <div class="text-warning d-flex align-items-start gap-1" style="max-width: 200px; font-size: 0.75rem;">
                            <i class="ph-fill ph-warning-circle flex-shrink-0 mt-1"></i>
                            <span class="lh-sm"><?= htmlspecialchars($customer['special_request']) ?></span>
                          </div>
                        <?php else: ?>
                          <span class="text-muted small">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <?php
                            $cStatus = $customer['checkin_status'] ?? 'not_arrived';
                            $cBadgeBg = [
                                'not_arrived' => 'bg-warning-subtle',
                                'checked_in' => 'bg-success-subtle',
                                'absent' => 'bg-danger-subtle'
                            ];
                            $cBadgeText = [
                                'not_arrived' => 'text-warning',
                                'checked_in' => 'text-success',
                                'absent' => 'text-danger'
                            ];
                        ?>
                        <span class="badge rounded-pill <?= $cBadgeBg[$cStatus] ?> <?= $cBadgeText[$cStatus] ?> px-3 py-1 fw-bold status-badge" style="font-size: 0.65rem;">
                          <?= $statusLabels[$cStatus] ?>
                        </span>
                      </td>
                      <td class="text-center checkin-time">
                        <?php if (!empty($customer['checkin_time'])): ?>
                          <div class="text-muted d-flex flex-column" style="font-size: 0.7rem;">
                              <span><?= date('H:i', strtotime($customer['checkin_time'])) ?></span>
                              <span><?= date('d/m/Y', strtotime($customer['checkin_time'])) ?></span>
                          </div>
                        <?php else: ?>
                          <span class="text-muted small">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="pe-4 text-end">
                        <div class="btn-group btn-group-sm rounded shadow-sm border overflow-hidden" style="border-radius: 8px !important;">
                          <button type="button" class="btn btn-light text-success btn-checkin" data-customer-id="<?= $customer['id'] ?>" data-status="checked_in" title="Đã đến" style="border-right-color: #efefef !important;">
                            <i class="ph-bold ph-check"></i>
                          </button>
                          <button type="button" class="btn btn-light text-danger btn-checkin" data-customer-id="<?= $customer['id'] ?>" data-status="absent" title="Vắng mặt" style="border-right-color: #efefef !important;">
                            <i class="ph-bold ph-x"></i>
                          </button>
                          <button type="button" class="btn btn-light text-muted btn-checkin" data-customer-id="<?= $customer['id'] ?>" data-status="not_arrived" title="Reset">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Itinerary -->
      <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark"><i class="ph ph-map-trifold me-2 text-primary"></i> Lịch trình tour chi tiết</h6>
        </div>
        <div class="card-body p-4">
          <?php if (empty($itineraries)): ?>
            <div class="text-center py-4 bg-light rounded" style="border: 1px dashed #e2e8f0; border-radius: var(--radius-md) !important;">
                <i class="ph ph-map-pin-line text-muted fs-2 mb-2"></i>
                <p class="text-muted mb-0 small">Chưa có lịch trình chi tiết cho tour này.</p>
            </div>
          <?php else: ?>
            <div class="row g-4">
                <?php foreach ($itineraries as $day): ?>
                  <div class="col-12">
                    <div class="d-flex gap-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-4 border-white shadow-sm" style="width: 48px; height: 48px; min-width: 48px; z-index: 2;">
                                <span class="fw-bold small">N<?= htmlspecialchars($day['day_number'] ?? '0') ?></span>
                            </div>
                            <div class="flex-grow-1 border-start border-2 border-light translate-x-50 mt-n1" style="width: 2px;"></div>
                        </div>
                        <div class="flex-grow-1 bg-light bg-opacity-50 p-4 rounded-4 shadow-none border hover-shadow-sm transition" style="border-radius: 16px !important;">
                          <div class="d-flex justify-content-between align-items-start mb-2">
                              <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($day['day_label'] ?? 'Hoạt động') ?></h6>
                              <span class="badge bg-white text-primary border px-3 py-1 shadow-sm"><i class="ph ph-clock me-1"></i><?= htmlspecialchars($day['time_start'] ?? '--:--') ?> - <?= htmlspecialchars($day['time_end'] ?? '--:--') ?></span>
                          </div>
                          <div class="text-dark small mb-3 fw-medium"><?= nl2br(htmlspecialchars($day['activities'] ?? '')) ?></div>
                          <?php if (!empty($day['description'])): ?>
                            <div class="text-muted small lh-lg" style="font-size: 0.8rem;"><?= nl2br(htmlspecialchars($day['description'])) ?></div>
                          <?php endif; ?>
                        </div>
                    </div>
                  </div>
                <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

<?php if (!empty($allCustomers)): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Select all checkbox
      document.getElementById('checkbox-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.customer-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
      });

      // Select all button
      document.getElementById('btn-select-all')?.addEventListener('click', function() {
        const checkboxAll = document.getElementById('checkbox-all');
        if (checkboxAll) {
          checkboxAll.checked = !checkboxAll.checked;
          checkboxAll.dispatchEvent(new Event('change'));
        }
      });

      // Single check-in buttons
      document.querySelectorAll('.btn-checkin').forEach(btn => {
        btn.addEventListener('click', function() {
          const customerId = this.dataset.customerId;
          const status = this.dataset.status;
          updateCheckinStatus(customerId, status);
        });
      });

      // Bulk check-in
      document.getElementById('btn-checkin-all')?.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.customer-checkbox:checked'))
          .map(cb => cb.value);

        if (selectedIds.length === 0) {
          alert('Vui lòng chọn ít nhất một khách');
          return;
        }

        if (!confirm(`Xác nhận check-in ${selectedIds.length} khách?`)) {
          return;
        }

        bulkCheckin(selectedIds, 'checked_in');
      });

      // Update single customer check-in status
      function updateCheckinStatus(customerId, status) {
        const formData = new FormData();
        formData.append('customer_id', customerId);
        formData.append('status', status);

        fetch('<?= BASE_URL_ADMIN ?>&action=bookings/update-checkin', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              updateCustomerRow(customerId, status, data.timestamp);
              updateStats();
              showToast('success', data.message);
            } else {
              showToast('error', data.message || 'Cập nhật thất bại');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Lỗi kết nối');
          });
      }

      // Bulk check-in
      function bulkCheckin(customerIds, status) {
        const formData = new FormData();
        customerIds.forEach(id => formData.append('customer_ids[]', id));
        formData.append('status', status);

        fetch('<?= BASE_URL_ADMIN ?>&action=bookings/bulk-checkin', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              location.reload();
            } else {
              showToast('error', data.message || 'Cập nhật thất bại');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Lỗi kết nối');
          });
      }

      // Update customer row UI
      function updateCustomerRow(customerId, status, timestamp) {
        const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
        if (!row) return;

        const statusBadge = row.querySelector('.status-badge');
        const timeCell = row.querySelector('.checkin-time');

        const statusLabels = {
          'not_arrived': 'Chưa đến',
          'checked_in': 'Đã đến',
          'absent': 'Vắng mặt'
        };

        const statusColors = {
          'not_arrived': 'warning',
          'checked_in': 'success',
          'absent': 'danger'
        };

        if (statusBadge) {
          statusBadge.className = `badge bg-${statusColors[status]} status-badge`;
          statusBadge.textContent = statusLabels[status];
        }

        if (timeCell && timestamp) {
          timeCell.innerHTML = `<small>${timestamp}</small>`;
        }
      }

      // Update stats
      function updateStats() {
        location.reload();
      }

      // Toast notification
      function showToast(type, message) {
        const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${toastClass} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      `;

        const container = document.querySelector('.toast-container') || createToastContainer();
        container.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => toast.remove());
      }

      function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1080';
        document.body.appendChild(container);
        return container;
      }

      // Handle tour status update
      const btnUpdateStatus = document.getElementById('btn-update-status');
      if (btnUpdateStatus) {
        btnUpdateStatus.addEventListener('click', function() {
          const assignmentId = this.dataset.assignmentId;
          const statusSelect = document.getElementById('tour-status-select');
          const newStatus = statusSelect.value;

          // Validate assignment ID
          if (!assignmentId || assignmentId === '') {
            showToast('error', 'Không tìm thấy thông tin phân công. Vui lòng thử lại.');
            return;
          }

          if (!confirm('Bạn có chắc muốn cập nhật trạng thái tour?')) {
            return;
          }

          // Disable button
          this.disabled = true;
          this.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Đang lưu...';

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
                showToast('success', data.message);
                // Reload page after 1 second to show updated status
                setTimeout(() => location.reload(), 1000);
              } else {
                showToast('error', data.message);
                this.disabled = false;
                this.innerHTML = '<i class="ph ph-floppy-disk"></i> Lưu';
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showToast('error', 'Lỗi: ' + error.message);
              this.disabled = false;
              this.innerHTML = '<i class="ph ph-floppy-disk"></i> Lưu';
            });
        });
      }
    });
  </script>
<?php endif; ?>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>