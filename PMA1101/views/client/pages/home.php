<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- 1. Hero Banner Component -->
<section class="home-hero position-relative vh-100 d-flex align-items-center">
    <div class="hero-bg position-absolute w-100 h-100 top-0 start-0">
        <!-- Using a high quality placeholder for travel -->
        <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=2021&auto=format&fit=crop" class="w-100 h-100 object-fit-cover" alt="Travel Background" style="filter: brightness(0.7)">
    </div>
    <div class="container position-relative text-center" style="z-index: 2;">
        <h1 class="display-3 fw-bolder text-white mb-3" data-aos="fade-up" style="text-shadow: 2px 2px 15px rgba(0,0,0,0.5);">Khám Phá Thế Giới Cùng AgileTravel</h1>
        <p class="lead text-white-50 mb-5 fw-medium" data-aos="fade-up" data-aos-delay="100">Đồng hành cùng bạn trên mọi hành trình - Trải nghiệm đẳng cấp, giá trị đích thực.</p>
        
        <!-- Search Bar -->
        <div class="search-box bg-white rounded-pill shadow-lg mx-auto overflow-hidden p-2" style="max-width: 950px; border: 1px solid rgba(0,0,0,0.05);" data-aos="fade-up" data-aos-delay="200">
            <form action="<?= BASE_URL ?>" method="GET" class="row g-0 align-items-center">
                <input type="hidden" name="action" value="tours">
                
                <!-- Destination -->
                <div class="col-md-4">
                    <div class="px-4 py-2 border-end-md">
                        <label class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <i class="ph-fill ph-map-pin text-primary" style="font-size: 0.9rem;"></i> ĐIỂM ĐẾN
                        </label>
                        <input type="text" name="q" class="form-control border-0 shadow-none p-0 fs-5 fw-bold text-dark placeholder-muted bg-transparent" placeholder="Bạn muốn đi đâu?" style="height: auto;">
                    </div>
                </div>

                <!-- Category -->
                <div class="col-md-3">
                    <div class="px-4 py-2 border-end-md position-relative custom-dropdown" id="dropdown-category">
                        <label class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <i class="ph-fill ph-calendar-blank text-primary" style="font-size: 0.9rem;"></i> LOẠI HÌNH
                        </label>
                        <div class="dropdown-trigger cursor-pointer d-flex align-items-center justify-content-between">
                            <span class="current-value fs-5 fw-bold text-dark">Tất cả</span>
                            <i class="ph-bold ph-caret-down text-muted" style="font-size: 0.75rem;"></i>
                        </div>
                        <input type="hidden" name="category" value="">
                        <div class="dropdown-menu-custom shadow-lg border-0 rounded-4 overflow-hidden">
                            <div class="dropdown-item-custom py-2 px-3 fw-medium selected" data-value="">Tất cả</div>
                            <?php foreach($categories as $cat): ?>
                                <div class="dropdown-item-custom py-2 px-3 fw-medium" data-value="<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Duration -->
                <div class="col-md-3">
                    <div class="px-4 py-2 position-relative custom-dropdown" id="dropdown-duration">
                        <label class="text-uppercase text-muted fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <i class="ph-fill ph-clock text-primary" style="font-size: 0.9rem;"></i> THỜI LƯỢNG
                        </label>
                        <div class="dropdown-trigger cursor-pointer d-flex align-items-center justify-content-between">
                            <span class="current-value fs-5 fw-bold text-dark">Bất kỳ</span>
                            <i class="ph-bold ph-caret-down text-muted" style="font-size: 0.75rem;"></i>
                        </div>
                        <input type="hidden" name="duration" value="">
                        <div class="dropdown-menu-custom shadow-lg border-0 rounded-4 overflow-hidden">
                            <div class="dropdown-item-custom py-2 px-3 fw-medium selected" data-value="">Bất kỳ</div>
                            <div class="dropdown-item-custom py-2 px-3 fw-medium" data-value="1-3">1 - 3 ngày</div>
                            <div class="dropdown-item-custom py-2 px-3 fw-medium" data-value="4-7">4 - 7 ngày</div>
                            <div class="dropdown-item-custom py-2 px-3 fw-medium" data-value="over-7">Trên 7 ngày</div>
                        </div>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-md-2 p-1">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill d-flex align-items-center justify-content-center hover-lift" style="height: 56px; background: #008C72; border: none;">
                        <i class="ph-bold ph-magnifying-glass fs-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <a href="#discover" class="position-absolute bottom-0 start-50 translate-middle-x mb-4 text-white text-decoration-none text-center" style="z-index: 2;" data-aos="fade-down" data-aos-delay="500" data-aos-iteration="infinite">
        <span class="d-block small text-uppercase tracking-tight mb-2">Khám phá</span>
        <i class="ph ph-caret-down fs-4 animate-bounce"></i>
    </a>
</section>


<!-- 3. Tour Nổi Bật (Featured Tours) -->
<section class="py-5 py-lg-6">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <h6 class="text-primary fw-bold text-uppercase tracking-tight">Xu Hướng</h6>
                <h2 class="display-6 fw-bold text-dark mb-0">Tour Nổi Bật Nhất</h2>
            </div>
            <div data-aos="fade-left">
                <a href="<?= BASE_URL ?>?action=tours" class="btn btn-outline-primary rounded-pill px-4 hover-lift d-none d-md-inline-flex align-items-center gap-2">
                    Xem Tất Cả <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($featuredTours)): ?>
                <?php foreach($featuredTours as $index => $tour): ?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <div class="tour-card card border-0 shadow-soft h-100 rounded-4 overflow-hidden position-relative hover-lift transition-all group">
                        
                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 m-3 z-2 d-flex flex-column gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium shadow-sm"><?= htmlspecialchars($tour['category_name'] ?? 'Mới') ?></span>
                            <?php if ($tour['base_price'] > 5000000): ?>
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium shadow-sm"><i class="ph-fill ph-fire me-1"></i>Hot</span>
                            <?php endif; ?>
                        </div>

                        <!-- Image Section -->
                        <div class="tour-img-wrapper position-relative overflow-hidden" style="height: 250px;">
                            <?php $imgUrl = !empty($tour['main_image']) ? BASE_ASSETS_UPLOADS . $tour['main_image'] : 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=600&auto=format&fit=crop'; ?>
                            <img src="<?= $imgUrl ?>" class="w-100 h-100 object-fit-cover transition-all" alt="<?= htmlspecialchars($tour['name']) ?>">
                            <div class="img-overlay position-absolute w-100 h-100 top-0 start-0 bg-dark opacity-0 transition-all p-3 d-flex align-items-end justify-content-center">
                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-light rounded-pill px-4 text-primary fw-bold shadow-sm translate-y-lg transition-all view-details-btn">Xem Chi Tiết</a>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center text-muted small mb-2 gap-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold"><?= htmlspecialchars($tour['category_name'] ?? 'Tour') ?></span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3 line-clamp-2" style="min-height: 48px;">
                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="text-dark text-decoration-none hover-text-primary transition-all"><?= htmlspecialchars($tour['name']) ?></a>
                            </h5>
                            
                            <div class="mb-3 d-flex align-items-center gap-1 text-warning fs-6">
                                <i class="ph-fill ph-star"></i>
                                <span class="text-dark fw-bold"><?= number_format($tour['avg_rating'] ?: 5.0, 1) ?></span>
                                <span class="text-muted small ms-1">(<?= $tour['review_count'] ?> đánh giá)</span>
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div class="price-box">
                                    <span class="text-muted small d-block mb-1">Chỉ từ</span>
                                    <span class="fs-4 fw-bolder text-primary"><?= number_format($tour['base_price'], 0, ',', '.') ?>đ</span>
                                </div>
                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-primary rounded-circle hover-lift text-center" style="width: 45px; height: 45px; line-height: 33px;">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Hiện chưa có tour nào nổi bật.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5 d-block d-md-none">
            <a href="<?= BASE_URL ?>?action=tours" class="btn btn-outline-primary rounded-pill px-5">Xem Tất Cả Tour</a>
        </div>
    </div>
</section>

<!-- Section: Chuyến đi sắp khởi hành (Upcoming Tours) -->
<?php if (!empty($upcomingTours)): ?>
<section class="py-5 py-lg-6 bg-light-subtle">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <h6 class="text-primary fw-bold text-uppercase tracking-tight">Cơ Hội Cuối Cùng</h6>
                <h2 class="display-6 fw-bold text-dark mb-0">Hành Trình Sắp Khởi Hành</h2>
            </div>
            <div data-aos="fade-left">
                <a href="<?= BASE_URL ?>?action=tours" class="text-primary fw-bold text-decoration-none d-flex align-items-center gap-2">
                    Khám Phá Thêm <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($upcomingTours as $index => $tour): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="card border-0 shadow-soft h-100 rounded-4 overflow-hidden hover-reveal transition-all">
                    <!-- Image Wrapper -->
                    <div class="position-relative overflow-hidden" style="height: 220px;">
                        <?php $imgUrl = !empty($tour['main_image']) ? BASE_ASSETS_UPLOADS . $tour['main_image'] : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop'; ?>
                        <img src="<?= $imgUrl ?>" class="w-100 h-100 object-fit-cover transition-all" alt="<?= htmlspecialchars($tour['name']) ?>">
                        
                        <!-- Badges -->
                        <div class="position-absolute top-0 end-0 m-3 z-2">
                            <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm d-flex align-items-center gap-1">
                                <i class="ph-fill ph-calendar-blank"></i> <?= date('d/m', strtotime($tour['departure_date'])) ?>
                            </span>
                        </div>
                        
                        <div class="position-absolute bottom-0 start-0 m-3 z-2">
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium shadow-sm">
                                Chỉ còn <?= $tour['available_seats'] ?> chỗ
                            </span>
                        </div>

                        <div class="img-overlay-dark position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center opacity-0 transition-all">
                            <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-primary rounded-pill px-4 fw-bold">Đặt Ngay</a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="card-body p-4">
                        <div class="text-primary small fw-bold text-uppercase mb-2 lh-1"><?= htmlspecialchars($tour['category_name'] ?? 'Tour') ?></div>
                        <h5 class="card-title fw-bold mb-3 line-clamp-1">
                            <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($tour['name']) ?></a>
                        </h5>
                        
                        <div class="d-flex align-items-center gap-3 text-muted small mb-4">
                            <!-- Metadata removed as requested -->
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="price">
                                <span class="text-muted small d-block">Giá chỉ từ</span>
                                <span class="fs-5 fw-bold text-dark"><?= number_format($tour['base_price'], 0, ',', '.') ?>đ</span>
                            </div>
                            <div class="rating text-warning small">
                                <i class="ph-fill ph-star"></i>
                                <span class="text-dark fw-bold"><?= number_format($tour['avg_rating'] ?: 5.0, 1) ?></span>
                                <span class="text-muted">(<?= $tour['review_count'] ?>)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 4. Vì Sao Chọn Chúng Tôi -->
<section class="py-5 py-lg-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #008C72 0%, #006b57 100%); color: white;">
    <!-- Abstract Decorative Elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" style="z-index: 0;">
        <div class="position-absolute rounded-circle" style="width: 400px; height: 400px; top: -200px; left: -200px; background: rgba(255, 255, 255, 0.03);"></div>
        <div class="position-absolute rounded-circle" style="width: 600px; height: 600px; bottom: -300px; right: -200px; background: rgba(255, 255, 255, 0.03);"></div>
        <div class="position-absolute w-100 h-100 top-0 start-0 opacity-5" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 50px 50px;"></div>
    </div>
    
    <div class="container py-5 position-relative z-1">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <div class="pe-lg-5">
                    <h6 class="text-white-50 fw-bold text-uppercase tracking-tight mb-3 d-flex align-items-center gap-2">
                        <span class="bg-white-50" style="width: 30px; height: 2px;"></span> Cam Kết Của Chúng Tôi
                    </h6>
                    <h2 class="display-5 fw-bold mb-4 text-white">Vì Sao Khách Hàng Chọn AgileTravel?</h2>
                    <p class="lead opacity-75 mb-5 text-justify fw-light" style="line-height: 1.8;">
                        Hơn một thập kỷ hoạt động, chúng tôi tự hào là người bạn đồng hành tin cậy, mang đến hành trình trọn vẹn và an tâm tuyệt đối cho hàng triệu khách hàng mỗi năm.
                    </p>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-6">
                            <div class="counter-box p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 shadow-sm">
                                <h2 class="display-4 fw-bolder mb-0 text-white counter"><?= $stats['total_tours'] ?></h2>
                                <span class="text-white-50 text-uppercase small fw-bold tracking-wider">Tour Đa Dạng</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="counter-box p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 shadow-sm">
                                <h2 class="display-4 fw-bolder mb-0 text-white"><span class="counter"><?= $stats['total_customers'] ?></span>+</h2>
                                <span class="text-white-50 text-uppercase small fw-bold tracking-wider">Khách Hàng/Năm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4 box-features">
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-card bg-white p-4 rounded-4 h-100 shadow-lg hover-lift transition-all border border-white border-opacity-20">
                            <div class="icon-box bg-primary-subtle rounded-4 mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                <i class="ph-fill ph-shield-check text-primary fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-3">An Toàn Tuyệt Đối</h5>
                            <p class="text-muted small mb-0 lh-base">Bảo hiểm du lịch trọn gói cho 100% chuyến đi của khách hàng.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-card bg-white p-4 rounded-4 h-100 shadow-lg hover-lift transition-all border border-white border-opacity-20">
                            <div class="icon-box bg-success-subtle rounded-4 mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                <i class="ph-fill ph-wallet text-success fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-3">Giá Tốt Nhất</h5>
                            <p class="text-muted small mb-0 lh-base">Cam kết không phát sinh ẩn phí, hoàn tiền nếu tìm được giá tốt hơn.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-card bg-white p-4 rounded-4 h-100 shadow-lg hover-lift transition-all border border-white border-opacity-20">
                            <div class="icon-box bg-info-subtle rounded-4 mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                <i class="ph-fill ph-headset text-info fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-3">Hỗ Trợ 24/7</h5>
                            <p class="text-muted small mb-0 lh-base">Đội ngũ CSKH chuyên nghiệp luôn sẵn sàng hỗ trợ mọi lúc mọi nơi.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-card bg-white p-4 rounded-4 h-100 shadow-lg hover-lift transition-all border border-white border-opacity-20">
                            <div class="icon-box bg-warning-subtle rounded-4 mb-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                <i class="ph-fill ph-star text-warning fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-3">Chất Lượng Cao</h5>
                            <p class="text-muted small mb-0 lh-base">Lưu trú 4-5 sao, dịch vụ ăn uống và di chuyển sang trọng nhất.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Latest Blogs Section -->
<section class="py-5 py-lg-6">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <h6 class="text-primary fw-bold text-uppercase tracking-tight">Blog & Tin Tức</h6>
                <h2 class="display-6 fw-bold text-dark mb-0">Bài Viết Mới Nhất</h2>
            </div>
            <div data-aos="fade-left">
                <a href="<?= BASE_URL ?>?action=blogs" class="btn btn-link text-decoration-none fw-bold text-primary p-0 d-flex align-items-center gap-2">
                    Xem Tất Cả <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($latestBlogs)): ?>
                <?php foreach ($latestBlogs as $index => $blog): ?>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <article class="card h-100 border-0 shadow-sm overflow-hidden tour-card-hover rounded-4">
                        <a href="<?= BASE_URL ?>?action=blog-detail&slug=<?= $blog['slug'] ?>" class="text-decoration-none">
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                <img src="<?= htmlspecialchars($blog['thumbnail'] ?? 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&q=80') ?>" 
                                     class="w-100 h-100 object-fit-cover transition-all blog-card-img" 
                                     alt="<?= htmlspecialchars($blog['title']) ?>">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-medium shadow-sm" style="font-size: 0.75rem;">Kiến thức</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-2 mb-3 text-muted small">
                                    <i class="ph ph-calendar"></i> <?= !empty($blog['published_at']) ? date('d/m/Y', strtotime($blog['published_at'])) : date('d/m/Y') ?>
                                    <span class="mx-1 opacity-50">•</span>
                                    <i class="ph ph-user"></i> Admin
                                </div>
                                <h5 class="fw-bold text-dark mb-3 line-clamp-2" style="min-height: 52px; line-height: 1.4; transition: var(--transition-base);">
                                    <?= htmlspecialchars($blog['title']) ?>
                                </h5>
                                <p class="text-muted small mb-0 line-clamp-2 opacity-75">
                                    <?= htmlspecialchars($blog['short_description'] ?? '') ?>
                                </p>
                            </div>
                        </a>
                    </article>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5 bg-light rounded-4">
                    <p class="text-muted mb-0">Hiện chưa có bài viết nào được đăng tải.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 6. Testimonials (Swiper Carousel) -->
<section class="py-5 py-lg-6 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-primary fw-bold text-uppercase tracking-tight">Đánh Giá</h6>
            <h2 class="display-6 fw-bold text-dark">Khách Hàng Nói Gì Về Chúng Tôi?</h2>
        </div>

        <div class="swiper testimonialSwiper" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper mb-5">
                <!-- Testimonial 1 -->
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-100 bg-white">
                        <ul class="list-unstyled d-flex text-warning mb-3 gap-1">
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                        </ul>
                        <p class="fst-italic text-muted mb-4 fs-5 text-justify">"Trải nghiệm tour Phú Quốc 4 ngày 3 đêm thật sự tuyệt vời. Hướng dẫn viên rất nhiệt tình, khách sạn và đồ ăn đều trên cả mong đợi."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Lan+Anh&background=random" class="rounded-circle me-3" width="50" alt="Avatar">
                            <div>
                                <h6 class="fw-bold mb-0">Chị Lan Anh</h6>
                                <span class="small text-muted">Khách hàng tháng 8/2023</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-100 bg-white">
                        <ul class="list-unstyled d-flex text-warning mb-3 gap-1">
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                        </ul>
                        <p class="fst-italic text-muted mb-4 fs-5 text-justify">"Gia đình tôi đã đi tour Nhật Bản mùa hoa anh đào. Mọi thứ được chuẩn bị chu đáo từ Visa đến lịch trình. Cảm ơn AgileTravel nhiều!"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Quoc+Tuan&background=random" class="rounded-circle me-3" width="50" alt="Avatar">
                            <div>
                                <h6 class="fw-bold mb-0">Anh Quốc Tuấn</h6>
                                <span class="small text-muted">Khách hàng tháng 4/2023</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 3 -->
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 h-100 bg-white">
                        <ul class="list-unstyled d-flex text-warning mb-3 gap-1">
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star"></i></li>
                            <li><i class="ph-fill ph-star-half"></i></li>
                        </ul>
                        <p class="fst-italic text-muted mb-4 fs-5 text-justify">"Giá tour rất hợp lý so với chất lượng dịch vụ nhận được. Rất ấn tượng với quy trình hỗ trợ chăm sóc 24/7 của các bạn."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Minh+Tam&background=random" class="rounded-circle me-3" width="50" alt="Avatar">
                            <div>
                                <h6 class="fw-bold mb-0">Cô Minh Tâm</h6>
                                <span class="small text-muted">Khách hàng tháng 11/2023</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination position-relative"></div>
        </div>
    </div>
</section>

<!-- Additional Custom CSS specifically for Home -->
<style>
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
    .tour-card .img-overlay {
        opacity: 0;
        background: linear-gradient(to top, rgba(0,140,114,0.8), transparent);
    }
    .tour-card:hover .img-overlay {
        opacity: 1 !important;
    }
    .tour-card .view-details-btn {
        transform: translateY(20px);
    }
    .tour-card:hover .view-details-btn {
        transform: translateY(0);
        opacity: 1;
    }
    .tour-card:hover img {
        transform: scale(1.1);
    }

    /* Upcoming Tours Section Styles */
    .hover-reveal .img-overlay-dark {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(2px);
    }
    .hover-reveal:hover .img-overlay-dark {
        opacity: 1 !important;
    }
    .hover-reveal:hover img {
        transform: scale(1.08);
    }
    .hover-reveal:hover {
        border-color: var(--primary-color) !important;
    }
    .bg-light-subtle {
        background-color: #f8fafc;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hover-text-primary:hover {
        color: var(--primary-color) !important;
    }

    /* Custom Dropdown Styling */
    .dropdown-menu-custom {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: white;
        z-index: 1000;
        display: none;
        margin-top: 15px;
        transform: translateY(10px);
        transition: all 0.2s ease;
        opacity: 0;
        visibility: hidden;
    }
    .custom-dropdown.active .dropdown-menu-custom {
        display: block;
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    .dropdown-item-custom {
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
        color: #4b5563;
    }
    .dropdown-item-custom:hover {
        background-color: #f3f4f6;
        color: #008C72;
        padding-left: 20px !important;
    }
    .dropdown-item-custom.selected {
        background-color: #f3f4f6;
        color: #008C72;
        font-weight: 600;
    }
    .dropdown-trigger i {
        transition: transform 0.2s ease;
    }
    .custom-dropdown.active .dropdown-trigger i {
        transform: rotate(180deg);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        const options = { threshold: 0.5 };
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalVal = parseInt(target.innerText) || 0;
                    let currentVal = 0;
                    const increment = Math.max(1, Math.floor(finalVal / 50));
                    
                    const updateCounter = setInterval(() => {
                        currentVal += increment;
                        if (currentVal >= finalVal) {
                            target.innerText = finalVal;
                            clearInterval(updateCounter);
                        } else {
                            target.innerText = currentVal;
                        }
                    }, 30);
                    observer.unobserve(target);
                }
            });
        }, options);
        
        counters.forEach(counter => observer.observe(counter));

        // Swiper Init
        const swiper = new Swiper('.testimonialSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });

        // Custom Dropdown Logic
        const dropdowns = document.querySelectorAll('.custom-dropdown');
        
        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu-custom');
            const hiddenInput = dropdown.querySelector('input[type="hidden"]');
            const items = dropdown.querySelectorAll('.dropdown-item-custom');
            const currentValueText = dropdown.querySelector('.current-value');
            
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                // Close other dropdowns
                dropdowns.forEach(d => {
                    if (d !== dropdown) d.classList.remove('active');
                });
                dropdown.classList.toggle('active');
            });
            
            items.forEach(item => {
                item.addEventListener('click', function() {
                    const val = this.dataset.value;
                    const text = this.innerText;
                    
                    hiddenInput.value = val;
                    currentValueText.innerText = text;
                    
                    // Update classes
                    items.forEach(i => i.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    dropdown.classList.remove('active');
                });
            });
        });
        
        // Click outside to close
        document.addEventListener('click', function() {
            dropdowns.forEach(d => d.classList.remove('active'));
        });
    });
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
