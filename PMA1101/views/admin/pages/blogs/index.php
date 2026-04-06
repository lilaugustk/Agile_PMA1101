<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard blogs-page">
    <div class="dashboard-container px-4 py-4">
        <header class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0">Quản lý Tin tức & Blog</h4>
                <p class="text-muted small mb-0">Quản lý các bài viết kinh nghiệm du lịch và tin tức công ty.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN ?>&action=blogs/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="ph ph-plus-circle me-2"></i>Thêm bài viết mới
            </a>
        </header>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tiêu đề</th>
                            <th>Chuyên mục</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th>Lượt xem</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($blogs)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có bài viết nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($blogs as $blog): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($blog['thumbnail']): ?>
                                                <img src="<?= BASE_URL . $blog['thumbnail'] ?>" class="rounded-3 shadow-sm" style="width: 60px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 60px; height: 40px;">
                                                    <i class="ph ph-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold fs-6"><?= htmlspecialchars($blog['title']) ?></div>
                                                <div class="text-muted small">slug: <?= $blog['slug'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= $blog['category'] ?: 'Chưa phân loại' ?></span></td>
                                    <td>
                                        <?php if ($blog['status'] == 'published'): ?>
                                            <span class="badge bg-success-subtle text-success px-3 rounded-pill border border-success-subtle">
                                                <i class="ph-fill ph-check-circle me-1"></i>Công khai
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning px-3 rounded-pill border border-warning-subtle">
                                                <i class="ph-fill ph-pencil me-1"></i>Bản nháp
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?= $blog['published_at'] ? date('d/m/Y H:i', strtotime($blog['published_at'])) : '---' ?>
                                    </td>
                                    <td><i class="ph ph-eye me-1"></i><?= $blog['view_count'] ?></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded-3">
                                            <a href="<?= BASE_URL_ADMIN ?>&action=blogs/edit&id=<?= $blog['id'] ?>" class="btn btn-sm btn-white border px-3" title="Chỉnh sửa">
                                                <i class="ph ph-note-pencil"></i>
                                            </a>
                                            <form action="<?= BASE_URL_ADMIN ?>&action=blogs/delete" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết này?')">
                                                <input type="hidden" name="id" value="<?= $blog['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-white border px-3 text-danger" title="Xóa">
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

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
