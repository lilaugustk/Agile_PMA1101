<?php
include_once PATH_VIEW_CLIENT . 'default/header.php';
$blog = $data['blog'] ?? [];
$latestBlogs = $data['latestBlogs'] ?? [];
?>

<div class="blog-detail-hero overflow-hidden position-relative" style="margin-top: 80px; height: 500px;">
    <?php if (!empty($blog['thumbnail'])): ?>
        <img src="<?= htmlspecialchars($blog['thumbnail']) ?>" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="filter: brightness(0.4);" alt="">
    <?php endif; ?>
    <div class="container h-100 position-relative z-1 d-flex flex-column justify-content-center text-white">
        <div class="row">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-4" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="?action=/" class="text-white text-decoration-none">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="?action=blogs" class="text-white text-decoration-none">Cẩm nang</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Chi tiết</li>
                    </ol>
                </nav>
                <div class="badge bg-primary rounded-pill px-3 py-2 mb-4">Tin tức du lịch</div>
                <h1 class="display-4 fw-bold mb-4 shadow-sm" style="line-height: 1.2;"><?= htmlspecialchars($blog['title']) ?></h1>
                <div class="d-flex align-items-center gap-4 text-white-50" style="font-size: 0.95rem;">
                    <span class="d-flex align-items-center gap-2"><i class="ph ph-calendar"></i> <?= date('d/m/Y', strtotime($blog['published_at'])) ?></span>
                    <span class="d-flex align-items-center gap-2"><i class="ph ph-user"></i> Đăng bởi Admin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 mt-n5 position-relative z-2">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-body p-5">
                    <!-- Article Content -->
                    <div class="blog-content fs-5 text-dark" style="line-height: 1.8;">
                        <?= $blog['content'] // Assuming HTML content from Admin CKEditor ?>
                    </div>

                    <!-- Tags / Meta -->
                    <div class="border-top mt-5 pt-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="share-box d-flex align-items-center gap-3">
                            <span class="fw-bold small text-muted text-uppercase">Chia sẻ bài viết:</span>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-light border rounded-circle" style="width: 36px; height: 36px; padding: 0; line-height: 36px;"><i class="ph ph-facebook-logo"></i></a>
                                <a href="#" class="btn btn-sm btn-light border rounded-circle" style="width: 36px; height: 36px; padding: 0; line-height: 36px;"><i class="ph ph-twitter-logo"></i></a>
                                <a href="#" class="btn btn-sm btn-light border rounded-circle" style="width: 36px; height: 36px; padding: 0; line-height: 36px;"><i class="ph ph-linkedin-logo"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comment Section Placeholder -->
            <div class="card border-0 shadow-sm rounded-4 p-5 mb-5">
                <h4 class="fw-bold mb-4">Bình luận</h4>
                <p class="text-muted small mb-0">Tính năng bình luận hiện đang được bảo trì. Vui lòng quay lại sau.</p>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <!-- Related Posts -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                             <i class="ph ph-sparkle-fill text-warning"></i> Bài viết liên quan
                        </h5>
                        <div class="d-flex flex-column gap-4">
                            <?php foreach ($latestBlogs as $lb): ?>
                                <?php if ($lb['id'] == $blog['id']) continue; ?>
                                <a href="?action=blog-detail&slug=<?= $lb['slug'] ?>" class="d-flex align-items-start gap-3 text-decoration-none group">
                                    <img src="<?= htmlspecialchars($lb['thumbnail'] ?? 'https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&q=80') ?>" 
                                         class="rounded-3 object-fit-cover" 
                                         style="width: 90px; height: 70px;" alt="">
                                    <div>
                                        <h6 class="text-dark fw-bold mb-1 line-clamp-2" style="font-size: 0.95rem; line-height: 1.4;"><?= htmlspecialchars($lb['title']) ?></h6>
                                        <div class="text-muted" style="font-size: 0.8rem;"><i class="ph ph-calendar me-1"></i><?= date('d/m/Y', strtotime($lb['published_at'])) ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Banner -->
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden text-center p-4">
                   <div class="py-4 px-2">
                        <i class="ph ph-airplane text-primary fa-3x mb-3"></i>
                        <h5 class="fw-bold mb-3">Sẵn sàng cho chuyến du lịch tiếp theo?</h5>
                        <p class="small text-white-50 mb-4">Hàng trăm tour hấp dẫn đang chờ đón bạn khám phá.</p>
                        <a href="?action=tours" class="btn btn-primary rounded-pill px-4 py-2 w-100">Xem danh sách Tour</a>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 24px 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.blog-content h2, .blog-content h3 {
    font-weight: 700;
    margin-top: 40px;
    margin-bottom: 20px;
}
.blog-content p {
    margin-bottom: 24px;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
