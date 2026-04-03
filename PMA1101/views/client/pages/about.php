<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Breadcrumb -->
<div class="bg-primary pt-5 mt-4 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50 z-1" style="background-image: url('https://images.unsplash.com/photo-1516939884455-1445c8652f83?q=80&w=2000&auto=format&fit=crop'); background-size: cover; background-position: center; filter: brightness(0.4);"></div>
    <div class="container py-5 text-center position-relative z-2">
        <h1 class="text-white fw-bold display-5 mb-3" data-aos="fade-up"><?= !empty($pageData['title']) ? htmlspecialchars($pageData['title']) : 'Về Chúng Tôi' ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none hover-text-white transition-all">Trang Chủ</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Giới Thiệu</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <?php if(!empty($pageData) && !empty($pageData['content'])): ?>
        <div class="page-content" data-aos="fade-up">
            <?= $pageData['content'] ?> <!-- Render HTML from DB -->
        </div>
    <?php else: ?>
        <div class="row align-items-center g-5 py-5 mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h6 class="text-primary fw-bold text-uppercase tracking-tight mb-2">Câu Chuyện Của AgileTravel</h6>
                <h2 class="display-6 fw-bold text-dark mb-4">Mở Ra Chân Trời Mới Cho Mỗi Chuyến Đi Của Bạn</h2>
                <p class="lead text-muted mb-4 text-justify">
                    Được thành lập từ năm 2012, AgileTravel khởi đầu từ đam mê xê dịch của một nhóm bạn trẻ. Chúng tôi tin rằng mỗi chuyến đi không chỉ là những bức ảnh đẹp, mà là hành trình khám phá thế giới và thấu hiểu bản thân.
                </p>
                <div class="row g-4 mt-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3">
                                <i class="ph-bold ph-globe-hemisphere-east fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">50+ Quốc Gia</h5>
                        </div>
                        <p class="text-muted small">Mạng lưới đối tác toàn cầu mang lại mức giá tốt nhất.</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3">
                                <i class="ph-bold ph-users fs-4"></i>
                            </div>
                            <h5 class="fw-bold mb-0">1Tr+ Khách Hàng</h5>
                        </div>
                        <p class="text-muted small">Luôn nhận được đánh giá 5 sao về chất lượng phục vụ.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative p-0 p-md-4">
                    <img src="https://images.unsplash.com/photo-1528543606781-2f6e6857f318?q=80&w=1530&auto=format&fit=crop" class="img-fluid rounded-4 shadow-lg w-100" alt="About Us">
                    <!-- Floating box -->
                    <div class="position-absolute bottom-0 start-0 bg-white p-4 rounded-4 shadow-lg m-4 translate-y-lg-50 d-none d-md-block">
                        <h2 class="text-primary fw-bolder mb-0 display-5">10+</h2>
                        <span class="fw-bold text-uppercase text-muted">Năm Kinh Nghiệm</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="text-secondary opacity-25 my-5">

        <!-- Team Section -->
        <div class="text-center mb-5" data-aos="fade-up">
            <h6 class="text-primary fw-bold text-uppercase tracking-tight">Đội Ngũ Của Chúng Tôi</h6>
            <h2 class="display-6 fw-bold text-dark">Những Người Truyền Cảm Hứng</h2>
        </div>
        
        <div class="row g-4 justify-content-center text-center">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 bg-transparent">
                    <img src="https://ui-avatars.com/api/?name=Dang+Son&background=random" class="rounded-circle shadow-sm mx-auto mb-3" width="150" alt="CEO">
                    <h5 class="fw-bold mb-1">Anh Đặng Sơn</h5>
                    <span class="text-primary small fw-medium">Founder & CEO</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 bg-transparent">
                    <img src="https://ui-avatars.com/api/?name=Thu+Ha&background=random" class="rounded-circle shadow-sm mx-auto mb-3" width="150" alt="Manager">
                    <h5 class="fw-bold mb-1">Chị Thu Hà</h5>
                    <span class="text-primary small fw-medium">Operation Manager</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 bg-transparent">
                    <img src="https://ui-avatars.com/api/?name=Minh+Tri&background=random" class="rounded-circle shadow-sm mx-auto mb-3" width="150" alt="Guide">
                    <h5 class="fw-bold mb-1">Anh Minh Trí</h5>
                    <span class="text-primary small fw-medium">Head of Tour Guides</span>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 bg-transparent">
                    <img src="https://ui-avatars.com/api/?name=Quynh+Anh&background=random" class="rounded-circle shadow-sm mx-auto mb-3" width="150" alt="Support">
                    <h5 class="fw-bold mb-1">Chị Quỳnh Anh</h5>
                    <span class="text-primary small fw-medium">Customer Support Lead</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .page-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
    .page-content p { color: #6c757d; font-size: 1.1rem; line-height: 1.8; text-align: justify; }
</style>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
