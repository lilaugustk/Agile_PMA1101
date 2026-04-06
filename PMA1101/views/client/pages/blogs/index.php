<?php
include_once PATH_VIEW_CLIENT . 'default/header.php';
$blogs = $data['blogs'] ?? [];
$latestBlogs = $data['latestBlogs'] ?? [];
?>

<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">Cẩm nang du lịch & Tin tức</h1>
            <p class="text-muted lead">Khám phá những điểm đến hấp dẫn, kinh nghiệm du lịch hữu ích và những tin tức mới nhất từ hệ thống của chúng tôi.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <?php if (!empty($blogs)): ?>
                <div class="row g-4">
                    <?php foreach ($blogs as $blog): ?>
                        <div class="col-md-6">
                            <article class="card h-100 border-0 shadow-sm overflow-hidden tour-card-hover">
                                <a href="?action=blog-detail&slug=<?= $blog['slug'] ?>" class="text-decoration-none">
                                    <div class="position-relative" style="height: 220px;">
                                        <img src="<?= htmlspecialchars($blog['thumbnail'] ?? 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80') ?>" 
                                             class="w-100 h-100 object-fit-cover" 
                                             alt="<?= htmlspecialchars($blog['title']) ?>">
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.75rem;">Tin tức</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center gap-2 mb-3 text-muted" style="font-size: 0.85rem;">
                                            <i class="ph ph-calendar"></i> <?= date('d/m/Y', strtotime($blog['published_at'])) ?>
                                            <span class="mx-1">•</span>
                                            <i class="ph ph-user"></i> Admin
                                        </div>
                                        <h2 class="h5 fw-bold text-dark mb-3 line-clamp-2"><?= htmlspecialchars($blog['title']) ?></h2>
                                        <p class="text-muted small mb-0 line-clamp-3">
                                            <?= htmlspecialchars($blog['short_description'] ?? '') ?>
                                        </p>
                                    </div>
                                </a>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="ph ph-article-ny-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Chưa có bài viết nào được đăng tải.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <!-- Recent Posts -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                             <i class="ph ph-bolt-fill text-primary"></i> Bài viết mới nhất
                        </h5>
                        <div class="d-flex flex-column gap-4">
                            <?php foreach ($latestBlogs as $lb): ?>
                                <a href="?action=blog-detail&slug=<?= $lb['slug'] ?>" class="d-flex align-items-start gap-3 text-decoration-none group">
                                    <img src="<?= htmlspecialchars($lb['thumbnail'] ?? 'https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&q=80') ?>" 
                                         class="rounded object-fit-cover" 
                                         style="width: 80px; height: 60px;" alt="">
                                    <div>
                                        <h6 class="text-dark fw-bold mb-1 line-clamp-2" style="font-size: 0.9rem; line-height: 1.4;"><?= htmlspecialchars($lb['title']) ?></h6>
                                        <div class="text-muted" style="font-size: 0.75rem;"><i class="ph ph-calendar me-1"></i><?= date('d/m/Y', strtotime($lb['published_at'])) ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <h5 class="fw-bold mb-3">Nhận tin khuyến mãi</h5>
                        <p class="small mb-4 text-white-50">Đăng ký nhận bản tin để nhận được các ưu đãi tour du lịch sớm nhất từ chúng tôi.</p>
                        <form class="position-relative">
                            <input type="email" class="form-control border-0 rounded-pill bg-white px-4 py-2" placeholder="Email của bạn...">
                            <button class="btn btn-dark position-absolute top-0 end-0 rounded-circle m-1" style="width: 32px; height: 32px; padding: 0;">
                                <i class="ph ph-paper-plane-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.tour-card-hover {
    transition: all 0.3s ease;
}
.tour-card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.12) !important;
}
</style>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
