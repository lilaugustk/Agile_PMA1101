<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
?>

<main class="content user-index-page">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý User</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=users/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-user-plus" style="font-size: 1.1rem;"></i> Thêm User Mới
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-success-subtle text-success border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-check-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-danger-subtle text-danger border-0 d-flex align-items-center gap-3 p-3 mb-4" style="border-radius: 12px;">
            <i class="ph-fill ph-warning-circle fs-4"></i>
            <div class="small fw-medium"><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Khách hàng</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['customers'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-users"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">User Mới (Tháng)</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">+<?= $stats['new_users'] ?? 0 ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                    <i class="ph ph-user-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card-premium mb-3">
        <div class="p-2 px-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;"><i class="ph ph-funnel text-muted"></i> Bộ Lọc Tìm Kiếm</h6>
            <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="resetFilters()" style="font-size: 0.75rem;">
                <i class="ph ph-arrow-counter-clockwise"></i> Reset
            </button>
        </div>
        <div class="p-2 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form id="user-filters" onsubmit="return false;" class="row g-2">
                <div class="col-md-6">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Tìm kiếm</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="ph ph-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên hoặc email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Vai trò</label>
                    <select name="role" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="customer">Khách hàng</option>
                        <option value="guide">HDV</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" onclick="filterUsers()" class="btn btn-primary btn-sm w-100">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

        <!-- Users List Section -->
        <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
            <div class="p-3 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                    <i class="ph-fill ph-users text-primary"></i> Danh sách Người dùng
                </h6>
                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                    <?= count($users) ?> thành viên
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($users)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-4 py-3">ID</th>
                                    <th class="py-3">Họ tên</th>
                                    <th class="py-3">Email</th>
                                    <th class="py-3">Điện thoại</th>
                                    <th class="py-3">Vai trò</th>
                                    <th class="py-3">Ngày tạo</th>
                                    <th class="text-end pe-4 py-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="ps-4"><strong>#<?= $user['user_id'] ?></strong></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="ph ph-user text-muted"></i>
                                                </div>
                                                <span class="fw-bold"><?= htmlspecialchars($user['full_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['phone'] ?? '---') ?></td>
                                        <td>
                                            <?php
                                            $roleMap = [
                                                'customer' => ['text' => 'Khách hàng', 'color' => 'success'],
                                                'guide' => ['text' => 'HDV', 'color' => 'info'],
                                                'admin' => ['text' => 'Admin', 'color' => 'danger']
                                            ];
                                            $roleInfo = $roleMap[$user['role']] ?? ['text' => $user['role'], 'color' => 'secondary'];
                                            ?>
                                            <span class="badge bg-<?= $roleInfo['color'] ?>-subtle text-<?= $roleInfo['color'] ?> px-2 py-1" style="font-size: 0.7rem;">
                                                <?= $roleInfo['text'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="<?= BASE_URL_ADMIN ?>&action=users/detail&id=<?= $user['user_id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Chi tiết"><i class="ph ph-eye"></i></a>
                                                <a href="<?= BASE_URL_ADMIN ?>&action=users/edit&id=<?= $user['user_id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa"><i class="ph ph-pencil-simple"></i></a>
                                                <?php
                                                $canDelete = false;
                                                if ($currentUserRole === 'admin') {
                                                    $canDelete = ($user['role'] !== 'admin');
                                                } elseif ($currentUserRole === 'guide') {
                                                    $canDelete = ($user['role'] === 'customer');
                                                }
                                                if ($user['user_id'] == $_SESSION['user']['user_id']) {
                                                    $canDelete = false;
                                                }

                                                if ($canDelete):
                                                ?>
                                                    <button class="btn btn-sm bg-white text-danger border shadow-sm delete-user-btn" data-id="<?= $user['user_id'] ?>" data-name="<?= htmlspecialchars($user['full_name']) ?>" title="Xóa">
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
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ph ph-users-three fa-4x text-muted mb-3 opacity-25"></i>
                        <h5 class="text-muted">Không có người dùng nào</h5>
                        <p class="text-muted small">Bắt đầu bằng cách thêm người dùng đầu tiên vào hệ thống</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger" style="width: 64px; height: 64px;">
                    <i class="ph ph-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold">Xác nhận xóa người dùng</h5>
                <p class="text-muted mb-0">Bạn có chắc chắn muốn xóa user <strong id="delete-user-name" class="text-dark"></strong>?</p>
                <p class="text-danger small mt-2"><i class="ph ph-info me-1"></i> Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete user functionality
    let deleteUserId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteUserId = this.dataset.id;
            document.getElementById('delete-user-name').textContent = this.dataset.name;
            deleteModal.show();
        });
    });

    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (!deleteUserId) return;

        fetch('<?= BASE_URL_ADMIN ?>&action=users/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + deleteUserId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa user');
            });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Client-side filtering functions
    function filterUsers() {
        const searchTerm = document.querySelector('[name="search"]').value.toLowerCase();
        const roleFilter = document.querySelector('[name="role"]').value;

        const tbody = document.querySelector('.table tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        let visibleCount = 0;

        rows.forEach(row => {
            const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const roleBadge = row.querySelector('td:nth-child(5) .badge');

            // Get role value from badge class
            let userRole = '';
            if (roleBadge.classList.contains('bg-success')) userRole = 'customer';
            else if (roleBadge.classList.contains('bg-info')) userRole = 'guide';
            else if (roleBadge.classList.contains('bg-danger')) userRole = 'admin';

            // Filter by search term
            const matchesSearch = !searchTerm ||
                fullName.includes(searchTerm) ||
                email.includes(searchTerm);

            // Filter by role
            const matchesRole = !roleFilter || userRole === roleFilter;

            // Show/hide row
            if (matchesSearch && matchesRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update count
        const countElement = document.querySelector('.card-title');
        if (countElement) {
            countElement.innerHTML = '<i class="fas fa-list me-2"></i>Danh sách User (' + visibleCount + ')';
        }
    }

    function resetFilters() {
        document.getElementById('user-filters').reset();
        filterUsers();
    }

    // Add event listeners to filter inputs
    document.querySelector('[name="search"]').addEventListener('input', filterUsers);
    document.querySelector('[name="role"]').addEventListener('change', filterUsers);
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>