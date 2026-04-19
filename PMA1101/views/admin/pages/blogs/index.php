<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý bài viết</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 text-dark mt-1" style="letter-spacing: -0.5px;">Quản lý Tin tức & Blog</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=blogs/create" class="btn btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                <i class="ph ph-plus-circle"></i> Thêm bài viết mới
            </a>
        </div>
    </div>

    <div class="card card-premium shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="ph-fill ph-newspaper text-primary"></i> Danh sách bài viết
                </h6>
                <div class="text-muted small"><?= count($blogs) ?> bài viết trong hệ thống</div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 border-0">Bài viết</th>
                            <th class="border-0">Chuyên mục</th>
                            <th class="border-0">Trạng thái</th>
                            <th class="border-0">Phát hành</th>
                            <th class="border-0">Lượt xem</th>
                            <th class="text-end pe-4 border-0">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($blogs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ph ph-article-ny-times display-4 mb-3"></i>
                                        <p>Chưa có bài viết nào được tạo.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="position-relative">
                                                <?php if ($blog['thumbnail']): ?>
                                                    <img src="<?= BASE_URL . $blog['thumbnail'] ?>" class="rounded shadow-sm" style="width: 80px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 80px; height: 50px;">
                                                        <i class="ph ph-image text-muted opacity-50"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6" style="max-width: 300px; line-height: 1.2;"><?= htmlspecialchars($blog['title']) ?></div>
                                                <div class="text-muted small mt-1">
                                                    <a href="<?= BASE_URL ?>?action=blog-detail&slug=<?= $blog['slug'] ?>" target="_blank" class="text-decoration-none text-primary">
                                                        <i class="ph ph-link me-1"></i>/<?= $blog['slug'] ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark border px-3 fw-medium">
                                            <?= $blog['category'] ?: 'Chưa phân loại' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($blog['status'] == 'published'): ?>
                                            <span class="badge-premium badge-active shadow-none" style="font-size: 0.75rem;">
                                                <i class="ph-fill ph-check-circle me-1"></i> Công khai
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-premium badge-pending shadow-none" style="font-size: 0.75rem;">
                                                <i class="ph-fill ph-pencil-line me-1"></i> Bản nháp
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <div class="d-flex flex-column">
                                            <span><?= $blog['published_at'] ? date('d/m/Y', strtotime($blog['published_at'])) : '---' ?></span>
                                            <span style="font-size: 0.7rem;"><?= $blog['published_at'] ? date('H:i', strtotime($blog['published_at'])) : '' ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1 text-muted">
                                            <i class="ph ph-eye"></i> <?= number_format($blog['view_count']) ?>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=blogs/edit&id=<?= $blog['id'] ?>" class="btn btn-sm btn-light border bg-white shadow-sm" title="Chỉnh sửa">
                                                <i class="ph ph-note-pencil"></i>
                                            </a>
                                            <form action="<?= BASE_URL_ADMIN ?>&action=blogs/delete" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết này? Thao tác này sẽ chuyển bài viết vào thùng rác.')">
                                                <input type="hidden" name="id" value="<?= $blog['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-light border bg-white shadow-sm text-danger" title="Xóa">
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

<style>
    .badge-premium {
        white-space: nowrap !important;
        display: inline-flex;
        align-items: center;
    }
</style>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
