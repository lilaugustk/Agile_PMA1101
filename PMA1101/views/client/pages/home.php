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
        <div class="search-box bg-white p-3 p-md-4 rounded-4 shadow-lg mx-auto" style="max-width: 900px;" data-aos="fade-up" data-aos-delay="200">
            <form action="<?= BASE_URL ?>" method="GET" class="row g-3 align-items-center">
                <input type="hidden" name="action" value="tours">
                <div class="col-md-4">
                    <div class="input-group-custom text-start border-end pe-3">
                        <label class="text-uppercase text-muted small fw-bold mb-1 d-block"><i class="ph-fill ph-map-pin me-1 text-primary"></i> Điểm Đến</label>
                        <input type="text" name="q" class="form-control border-0 shadow-none p-0 fs-5 fw-medium bg-transparent" placeholder="Bạn muốn đi đâu?">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group-custom text-start border-end pe-3 ps-md-3">
                        <label class="text-uppercase text-muted small fw-bold mb-1 d-block"><i class="ph-fill ph-calendar-blank me-1 text-primary"></i> Loại hình</label>
                        <select name="category" class="form-select border-0 shadow-none p-0 fs-5 fw-medium bg-transparent cursor-pointer">
                            <option value="">Tất cả</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group-custom text-start pe-3 ps-md-3">
                        <label class="text-uppercase text-muted small fw-bold mb-1 d-block"><i class="ph-fill ph-clock me-1 text-primary"></i> Thời lượng</label>
                        <select name="duration" class="form-select border-0 shadow-none p-0 fs-5 fw-medium bg-transparent cursor-pointer">
                            <option value="">Bất kỳ</option>
                            <option value="1-3">1 - 3 ngày</option>
                            <option value="4-7">4 - 7 ngày</option>
                            <option value="over-7">Trên 7 ngày</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 mt-4 mt-md-0 d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 py-3 hover-lift">
                        <i class="ph-bold ph-magnifying-glass fs-5"></i>
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

<!-- 2. Danh Mục Các Loại Tour -->
<section id="discover" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-primary fw-bold text-uppercase tracking-tight">Danh Mục Tour</h6>
            <h2 class="display-6 fw-bold text-dark">Lựa Chọn Theo Sở Thích</h2>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            $icons = ['ph-island', 'ph-mountains', 'ph-buildings', 'ph-camera', 'ph-sun-dim', 'ph-airplane-tilt'];
            foreach(array_slice($categories, 0, 6) as $index => $cat): 
                $icon = $icons[$index % count($icons)];
            ?>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <a href="<?= BASE_URL ?>?action=tours&category=<?= htmlspecialchars($cat['slug']) ?>" class="category-card text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100 p-4 transition-all hover-lift rounded-4">
                        <div class="icon-circle bg-primary-subtle text-primary mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="<?= $icon ?> fs-1"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($cat['name']) ?></h6>
                        <span class="text-muted small"><?= $cat['tour_count'] ?> tours</span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
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
                                <span class="d-flex align-items-center gap-1"><i class="ph-fill ph-clock text-primary"></i> <?= $tour['duration_days'] ?? 'N/A' ?> Ngày</span>
                                <span class="d-flex align-items-center gap-1"><i class="ph-fill ph-users text-primary"></i> Lên đến <?= $tour['max_participants'] ?? 'N/A' ?></span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3 line-clamp-2" style="min-height: 48px;">
                                <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="text-dark text-decoration-none hover-text-primary transition-all"><?= htmlspecialchars($tour['name']) ?></a>
                            </h5>
                            
                            <!-- Rating (Placeholder) -->
                            <div class="mb-3 d-flex align-items-center gap-1 text-warning fs-6">
                                <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star-half"></i>
                                <span class="text-muted small ms-1">(128 đánh giá)</span>
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

<!-- 4. Vì Sao Chọn Chúng Tôi -->
<section class="py-5 position-relative" style="background-color: #008C72; color: white;">
    <!-- Background Pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container py-5 position-relative z-1">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <h6 class="text-white-50 fw-bold text-uppercase tracking-tight">Cam Kết Của Chúng Tôi</h6>
                <h2 class="display-5 fw-bold mb-4">Vì Sao Khách Hàng Chọn AgileTravel?</h2>
                <p class="lead text-white-50 mb-4 text-justify">
                    Hơn một thập kỷ hoạt động, chúng tôi tự hào là người bạn đồng hành tin cậy, mang đến hành trình trọn vẹn và an tâm tuyệt đối cho hàng triệu khách hàng.
                </p>
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <h2 class="display-4 fw-bolder mb-0 counter"><?= $stats['total_tours'] ?></h2>
                        <span class="text-white-50 text-uppercase small fw-bold">Tour Đa Dạng</span>
                    </div>
                    <div class="col-6">
                        <h2 class="display-4 fw-bolder mb-0"><span class="counter"><?= $stats['total_customers'] ?></span>+</h2>
                        <span class="text-white-50 text-uppercase small fw-bold">Khách Hàng/Năm</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="row g-4">
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-white text-dark p-4 rounded-4 h-100 shadow-lg hover-lift transition-all">
                            <i class="ph-fill ph-shield-check text-primary fs-1 mb-3"></i>
                            <h5 class="fw-bold mb-2">An Toàn Tuyệt Đối</h5>
                            <p class="text-muted small mb-0">Bảo hiểm du lịch trọn gói cho 100% chuyến đi.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-white text-dark p-4 rounded-4 h-100 shadow-lg hover-lift transition-all mt-md-4">
                            <i class="ph-fill ph-wallet text-primary fs-1 mb-3"></i>
                            <h5 class="fw-bold mb-2">Giá Tốt Nhất</h5>
                            <p class="text-muted small mb-0">Cam kết không phát sinh ẩn phí, hoàn tiền nếu tìm được giá tốt hơn.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="bg-white text-dark p-4 rounded-4 h-100 shadow-lg hover-lift transition-all mt-md-n4">
                            <i class="ph-fill ph-headset text-primary fs-1 mb-3"></i>
                            <h5 class="fw-bold mb-2">Hỗ Trợ 24/7</h5>
                            <p class="text-muted small mb-0">Đội ngũ CSKH chuyên nghiệp luôn sẵn sàng mọi lúc.</p>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="bg-white text-dark p-4 rounded-4 h-100 shadow-lg hover-lift transition-all">
                            <i class="ph-fill ph-star text-primary fs-1 mb-3"></i>
                            <h5 class="fw-bold mb-2">Chất Lượng Cao</h5>
                            <p class="text-muted small mb-0">Lưu trú 4-5 sao, dịch vụ ăn uống và di chuyển đẳng cấp.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Testimonials (Swiper Carousel) -->
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
    .hover-text-primary:hover {
        color: var(--primary-color) !important;
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
    });
</script>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
