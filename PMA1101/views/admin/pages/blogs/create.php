<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard blogs-page">
    <div class="dashboard-container px-4 py-4">
        <header class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=blogs" class="text-decoration-none small text-muted d-flex align-items-center gap-1 mb-2">
                    <i class="ph ph-arrow-left"></i> Quay lại danh sách
                </a>
                <h4 class="fw-bold mb-0">Thêm bài viết mới</h4>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" form="blogForm" name="status" value="draft" class="btn btn-outline-secondary rounded-pill px-4">
                    Lưu bản nháp
                </button>
                <button type="submit" form="blogForm" name="status" value="published" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    Công khai ngay
                </button>
            </div>
        </header>

        <form action="<?= BASE_URL_ADMIN ?>&action=blogs/store" method="POST" id="blogForm" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tiêu đề bài viết</label>
                            <input type="text" name="title" class="form-control form-control-lg border-0 bg-light shadow-none" placeholder="Nhập tiêu đề hấp dẫn..." required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tóm tắt ngắn</label>
                            <textarea name="summary" class="form-control border-0 bg-light shadow-none" rows="3" placeholder="Tóm tắt nội dung bài viết..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control border-0 bg-light shadow-none" rows="15" placeholder="Viết nội dung tại đây..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Sidebar: Settings -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h6 class="fw-bold mb-3">Cài đặt bài viết</h6>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Chuyên mục</label>
                            <select name="category" class="form-select border-0 bg-light shadow-none">
                                <option value="Kinh nghiệm du lịch">Kinh nghiệm du lịch</option>
                                <option value="Tin tức công ty">Tin tức công ty</option>
                                <option value="Khuyến mãi">Khuyến mãi</option>
                                <option value="Cẩm nang">Cẩm nang</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Ảnh bìa (Thumbnail)</label>
                            <div class="thumbnail-preview border rounded-3 d-flex align-items-center justify-content-center bg-light mb-2 overflow-hidden" style="height: 150px;">
                                <i class="ph ph-image text-muted display-4"></i>
                            </div>
                            <input type="file" name="thumbnail" class="form-control form-control-sm border-0 bg-light shadow-none" accept="image/*">
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
