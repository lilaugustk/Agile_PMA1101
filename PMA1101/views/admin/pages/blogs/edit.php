<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=blogs" class="text-decoration-none text-muted">Quản lý bài viết</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa bài viết</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 text-dark mt-1" style="letter-spacing: -0.5px;">Chỉnh sửa bài viết</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=blogs" class="btn btn-light border bg-white shadow-sm d-flex align-items-center gap-2 px-3">
                <i class="ph ph-x-circle"></i> Hủy bỏ
            </a>
            <button type="submit" form="blogForm" class="btn btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                <i class="ph ph-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <form action="<?= BASE_URL_ADMIN ?>&action=blogs/update" method="POST" id="blogForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $blog['id'] ?>">
        <input type="hidden" name="old_published_at" value="<?= $blog['published_at'] ?>">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-premium border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Tiêu đề bài viết</label>
                            <input type="text" name="title" class="form-control form-control-lg border shadow-none bg-light-subtle" placeholder="Nhập tiêu đề hấp dẫn..." value="<?= htmlspecialchars($blog['title']) ?>" required style="font-size: 1.25rem; font-weight: 600; border-radius: 12px;">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Tóm tắt nội dung</label>
                            <textarea name="summary" class="form-control border shadow-none bg-light-subtle" rows="3" placeholder="Mô tả ngắn gọn về bài viết..." style="border-radius: 12px;"><?= htmlspecialchars($blog['summary']) ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control border shadow-none bg-light-subtle" rows="18" placeholder="Viết nội dung tại đây..." style="border-radius: 12px;"><?= htmlspecialchars($blog['content']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-premium border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i class="ph-fill ph-gear-six text-primary"></i> Cấu hình bài viết
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Chuyên mục</label>
                            <select name="category" class="form-select border shadow-none bg-light-subtle" style="border-radius: 10px;">
                                <option value="Kinh nghiệm du lịch" <?= $blog['category'] == 'Kinh nghiệm du lịch' ? 'selected' : '' ?>>Kinh nghiệm du lịch</option>
                                <option value="Tin tức công ty" <?= $blog['category'] == 'Tin tức công ty' ? 'selected' : '' ?>>Tin tức công ty</option>
                                <option value="Khuyến mãi" <?= $blog['category'] == 'Khuyến mãi' ? 'selected' : '' ?>>Khuyến mãi</option>
                                <option value="Cẩm nang" <?= $blog['category'] == 'Cẩm nang' ? 'selected' : '' ?>>Cẩm nang</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Trạng thái</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer transition-all hover-bg-light">
                                    <input type="radio" name="status" value="published" <?= $blog['status'] == 'published' ? 'checked' : '' ?> class="form-check-input mt-0">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Công khai</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Hiện thị lên website</div>
                                    </div>
                                </label>
                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer transition-all hover-bg-light">
                                    <input type="radio" name="status" value="draft" <?= $blog['status'] == 'draft' ? 'checked' : '' ?> class="form-check-input mt-0">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Bản nháp</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Chỉ xem được trong admin</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Ảnh bìa (Thumbnail)</label>
                            <div class="thumbnail-preview border rounded-3 d-flex align-items-center justify-content-center bg-light mb-3 overflow-hidden position-relative" style="height: 200px; border-style: dashed !important; border-width: 2px !important;">
                                <?php if ($blog['thumbnail']): ?>
                                    <img id="imgPreview" src="<?= BASE_URL . $blog['thumbnail'] ?>" alt="Preview" class="w-100 h-100 object-fit-cover">
                                    <div id="uploadPlaceholder" class="text-center p-3 text-muted d-none">
                                        <i class="ph ph-image-square display-4 mb-2"></i>
                                        <p class="small mb-0">Chọn ảnh mới</p>
                                    </div>
                                <?php else: ?>
                                    <img id="imgPreview" src="#" alt="Preview" class="d-none w-100 h-100 object-fit-cover">
                                    <div id="uploadPlaceholder" class="text-center p-3 text-muted">
                                        <i class="ph ph-image-square display-4 mb-2"></i>
                                        <p class="small mb-0">Chưa có ảnh nào</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="form-control border shadow-none bg-light-subtle" accept="image/*" style="border-radius: 10px;">
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.75rem;">Để trống nếu không muốn thay đổi ảnh.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
    document.getElementById('thumbnailInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imgPreview');
                const placeholder = document.getElementById('uploadPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
