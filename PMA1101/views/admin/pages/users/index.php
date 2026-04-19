<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$currentUserRole = $_SESSION['user']['role'] ?? 'customer';
?>

<main class="content user-index-page">
    <!-- Clean Page Header -->
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Quản lý Thành viên</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=users/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm transition-all hover-translate-y" style="border-radius: 10px; font-weight: 600;">
                <i class="ph-bold ph-plus"></i> Thêm thành viên
            </a>
        </div>
    </div>

    <!-- Clean Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-success-subtle text-success border-0 d-flex align-items-center gap-3 p-3 mb-4 rounded-4 animate__animated animate__fadeIn">
            <i class="ph-fill ph-check-circle fs-4"></i>
            <div class="fw-medium small"><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>



    <!-- Optimized Filters Card -->
    <div class="card card-premium border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
        <div class="p-4 bg-white">
            <form id="user-filters" onsubmit="return false;" class="row g-3">
                <div class="col-md-12">
                    <div class="position-relative">
                        <i class="ph ph-magnifying-glass position-absolute text-muted" style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 1.1rem; z-index: 10;"></i>
                        <input type="text" name="search" class="form-control ps-5 border-light-subtle shadow-none bg-light" 
                            placeholder="Tìm kiếm theo tên, email hoặc số điện thoại..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            style="border-radius: 14px; min-height: 52px; font-weight: 500; font-size: 0.95rem; border: 1px solid #e5e7eb;">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Synchronized Users Table Card -->
    <div class="card card-premium border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="ph-bold ph-list-bullets text-primary"></i> Danh sách thành viên
            </h6>
            <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                <?= count($users) ?> bản ghi
            </span>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                            <tr>
                                <th class="ps-4 py-3 border-0">Thành viên</th>
                                <th class="py-3 border-0">Thông tin liên hệ</th>
                                <th class="py-3 border-0">Ngày gia nhập</th>
                                <th class="text-end pe-4 py-3 border-0">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=4f46e5&color=fff&size=40&font-size=0.4&rounded=true&bold=true" 
                                                 class="rounded-circle shadow-sm border border-2 border-white" alt="<?= htmlspecialchars($user['full_name']) ?>" style="width: 42px; height: 42px;">
                                            <div>
                                                <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($user['full_name']) ?></div>
                                                <div class="text-muted small">UID: #<?= $user['user_id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-primary small mb-1"><i class="ph ph-envelope me-1"></i> <?= htmlspecialchars($user['email']) ?></span>
                                            <span class="text-secondary small"><i class="ph ph-phone me-1"></i> <?= htmlspecialchars($user['phone'] ?? '---') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark fw-500 small"><?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= date('H:i', strtotime($user['created_at'])) ?></div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=users/detail&id=<?= $user['user_id'] ?>" class="btn btn-sm btn-icon shadow-sm border-0 bg-light text-primary" title="Xem chi tiết">
                                                <i class="ph-bold ph-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=users/edit&id=<?= $user['user_id'] ?>" class="btn btn-sm btn-icon shadow-sm border-0 bg-light text-dark" title="Sửa thông tin">
                                                <i class="ph-bold ph-pencil-simple"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-light-subtle">
                    <div class="bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="ph-bold ph-users-three text-muted fs-2 opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Chưa có khách hàng nào</h5>
                    <p class="text-muted small mx-auto" style="max-width: 300px;">Bắt đầu xây dựng danh sách của bạn bằng cách thêm thành viên đầu tiên.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
    .hover-translate-y:hover {
        transform: translateY(-2px);
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-icon:hover {
        transform: translateY(-2px);
        background: #fff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    .fw-500 { font-weight: 500; }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize client-side filtering
        const searchInput = document.querySelector('[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                const rows = document.querySelectorAll('.table tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>