<?php
if (!isset($isAjax)) {
    include_once PATH_VIEW_ADMIN . 'default/header.php';
    include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
}
?>

<?php if (!isset($isAjax)) : ?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-muted text-decoration-none">Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng rác</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours' ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-arrow-left" style="font-size: 1.1rem;"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-check-circle fs-5"></i>
            <div><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-warning-circle fs-5"></i>
            <div><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Tours Grid Section -->
    <div class="card-premium min-vh-100">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex flex-wrap justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <div class="d-flex align-items-center gap-3">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 text-danger">
                    <i class="ph-fill ph-trash"></i> 
                    Thùng rác (Tours đã xóa)
                </h6>
                <span class="count-info badge bg-light text-muted border px-2 py-1 rounded-pill">
                    <?= $pagination['total'] ?? 0 ?> tour trong thùng rác
                </span>
            </div>
        </div>

        <div class="p-4 bg-white" id="tour-list-container" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($tours)) : ?>
                <div class="row g-4 tours-grid">
                    <?php foreach ($tours as $tour) : ?>
                        <?php
                        $mainImage = $tour['main_image'] ?? null;
                        $mainUrl = $mainImage ? BASE_ASSETS_UPLOADS . $mainImage : BASE_URL . 'assets/admin/image/no-image.png';
                        ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xxl-3 tour-card-modern" data-id="<?= $tour['id'] ?>">
                            <div class="card h-100 border border-light shadow-sm d-flex flex-column overflow-hidden" style="border-radius: 16px; opacity: 0.85;">
                                
                                <!-- Image Header -->
                                <div class="position-relative bg-light main-img-wrapper" style="padding-top: 60%;">
                                    <img src="<?= $mainUrl ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover grayscale" alt="<?= htmlspecialchars($tour['name']) ?>">
                                    <div class="position-absolute top-0 start-0 w-100 p-2">
                                        <span class="badge bg-danger">Đã xóa</span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <h5 class="tour-title fw-bold fs-6 mb-2 text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </h5>
                                    
                                    <div class="mt-auto pt-2 d-flex justify-content-between align-items-center border-top border-light">
                                        <div class="small text-muted">
                                            Xóa lúc: <?= date('d/m/Y H:i', strtotime($tour['deleted_at'])) ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <a href="<?= BASE_URL_ADMIN . '&action=tours/restore&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1" style="font-size: 0.75rem; white-space: nowrap;" onclick="return confirm('Khôi phục tour này?')">
                                            <i class="ph ph-arrow-counter-clockwise"></i> Khôi phục
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-force-delete d-flex align-items-center gap-1" data-id="<?= $tour['id'] ?>" data-name="<?= htmlspecialchars($tour['name']) ?>" style="font-size: 0.75rem; white-space: nowrap;">
                                            <i class="ph ph-x-circle"></i> Xóa vĩnh viễn
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center p-5">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="ph ph-trash text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Thùng rác trống</h5>
                    <p class="text-muted">Các tour bị xóa sẽ xuất hiện ở đây.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.grayscale { filter: grayscale(100%); }
.tour-card-modern:hover { transform: scale(1.02); transition: 0.2s; }
</style>

<!-- Force Delete Modal -->
<div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-5 px-4">
                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger text-white mb-4" style="width: 72px; height: 72px;">
                    <i class="ph ph-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-3 text-danger">XÓA VĨNH VIỄN?</h5>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn xóa vĩnh viễn tour "<strong id="force-delete-tour-name" class="text-dark"></strong>"?<br> Hành động này <b>KHÔNG THỂ</b> hoàn tác và sẽ xóa toàn bộ dữ liệu liên quan.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="force-delete-form" method="POST" class="m-0">
                        <input type="hidden" name="id" id="force-delete-tour-id">
                        <button type="submit" class="btn btn-danger px-4 shadow-sm">Xóa Vĩnh Viễn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forceDeleteModal = new bootstrap.Modal(document.getElementById('forceDeleteModal'));
    
    // Sử dụng Event Delegation để xử lý cả các nút được tải qua AJAX
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-force-delete');
        if (btn) {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            document.getElementById('force-delete-tour-name').textContent = name;
            document.getElementById('force-delete-form').action = '<?= BASE_URL_ADMIN ?>&action=tours/force-delete&id=' + id;
            forceDeleteModal.show();
        }
    });
});
</script>
<?php endif; ?>

<?php if (!isset($isAjax)): ?>
    <?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
<?php endif; ?>
