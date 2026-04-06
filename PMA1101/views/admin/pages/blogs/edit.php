<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard blogs-page">
    <div class="dashboard-container px-4 py-4">
        <header class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=blogs" class="text-decoration-none small text-muted d-flex align-items-center gap-1 mb-2">
                    <i class="ph ph-arrow-left"></i> Quay lại danh sách
                </a>
                <h4 class="fw-bold mb-0">Chỉnh sửa bài viết</h4>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" form="blogForm" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    Cập nhật bài viết
                </button>
            </div>
        </header>

        <form action="<?= BASE_URL_ADMIN ?>&action=blogs/update" method="POST" id="blogForm" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $blog['id'] ?>">
            <input type="hidden" name="old_published_at" value="<?= $blog['published_at'] ?>">

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tiêu đề bài viết</label>
                            <input type="text" name="title" class="form-control form-control-lg border-0 bg-light shadow-none" placeholder="Nhập tiêu đề hấp dẫn..." value="<?= htmlspecialchars($blog['title']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tóm tắt ngắn</label>
                            <textarea name="summary" class="form-control border-0 bg-light shadow-none" rows="3" placeholder="Tóm tắt nội dung bài viết..."><?= htmlspecialchars($blog['summary']) ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control border-0 bg-light shadow-none" rows="15" placeholder="Viết nội dung tại đây..."><?= htmlspecialchars($blog['content']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Sidebar: Settings -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h6 class="fw-bold mb-3">Cài đặt bài viết</h6>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Trạng thái</label>
                            <select name="status" class="form-select border-0 bg-light shadow-none">
                                <option value="draft" <?= $blog['status'] == 'draft' ? 'selected' : '' ?>>Bản nháp</option>
                                <option value="published" <?= $blog['status'] == 'published' ? 'selected' : '' ?>>Công khai</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Chuyên mục</label>
                            <select name="category" class="form-select border-0 bg-light shadow-none">
                                <option value="Kinh nghiệm du lịch" <?= $blog['category'] == 'Kinh nghiệm du lịch' ? 'selected' : '' ?>>Kinh nghiệm du lịch</option>
                                <option value="Tin tức công ty" <?= $blog['category'] == 'Tin tức công ty' ? 'selected' : '' ?>>Tin tức công ty</option>
                                <option value="Khuyến mãi" <?= $blog['category'] == 'Khuyến mãi' ? 'selected' : '' ?>>Khuyến mãi</option>
                                <option value="Cẩm nang" <?= $blog['category'] == 'Cẩm nang' ? 'selected' : '' ?>>Cẩm nang</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Ảnh bìa (Thumbnail)</label>
                            <div class="thumbnail-preview border rounded-3 d-flex align-items-center justify-content-center bg-light mb-2 overflow-hidden" style="height: 150px;">
                                <?php if ($blog['thumbnail']): ?>
                                    <img src="<?= BASE_URL . $blog['thumbnail'] ?>" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                <?php else: ?>
                                    <i class="ph ph-image text-muted display-4"></i>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="thumbnail" class="form-control form-control-sm border-0 bg-light shadow-none" accept="image/*">
                            <small class="text-muted mt-2 d-block">Để trống nếu không muốn thay đổi ảnh.</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<style>
    .form-control:focus, .form-select:focus { background-color: #f0f2f5; }
</style>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
