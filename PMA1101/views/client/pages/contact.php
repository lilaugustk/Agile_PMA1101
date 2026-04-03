<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Breadcrumb -->
<div class="bg-primary pt-5 mt-4 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50 z-1" style="background-image: url('https://images.unsplash.com/photo-1524850011238-e3d235c161c2?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center; filter: brightness(0.4);"></div>
    <div class="container py-5 text-center position-relative z-2">
        <h1 class="text-white fw-bold display-5 mb-3" data-aos="fade-up"><?= !empty($pageData['title']) ? htmlspecialchars($pageData['title']) : 'Liên Hệ Với Chúng Tôi' ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none hover-text-white transition-all">Trang Chủ</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Liên Hệ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Info -->
        <div class="col-lg-5" data-aos="fade-right">
            <h6 class="text-primary fw-bold text-uppercase tracking-tight mb-2">Tư Vấn Miễn Phí</h6>
            <h2 class="display-6 fw-bold text-dark mb-4">Chúng Tôi Luôn Ở Đây Để Lắng Nghe Bạn</h2>
            <p class="text-muted mb-5 text-justify">Đội ngũ AgileTravel luôn sẵn sàng giải đáp mọi thắc mắc của bạn 24/7. Hãy liên hệ với chúng tôi qua các kênh dưới đây hoặc để lại lời nhắn.</p>
            
            <?php if(!empty($pageData) && !empty($pageData['content'])): ?>
                <div class="mb-5 page-content">
                    <?= $pageData['content'] ?>
                </div>
            <?php else: ?>
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4 shadow-sm hover-lift transition-all">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm me-4" style="width: 60px; height: 60px;">
                        <i class="ph-fill ph-map-pin fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Văn Phòng Trụ Sở</h6>
                        <span class="text-muted small">Tòa nhà FPT Polytechnic, số 1 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4 shadow-sm hover-lift transition-all">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm me-4" style="width: 60px; height: 60px;">
                        <i class="ph-fill ph-phone fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Hotline Phục Vụ</h6>
                        <span class="text-muted small">1900 1234 (Tổng đài vé) <br> 090 123 4567 (CSKH 24/7)</span>
                    </div>
                </div>

                <div class="d-flex align-items-center p-3 bg-light rounded-4 shadow-sm hover-lift transition-all">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm me-4" style="width: 60px; height: 60px;">
                        <i class="ph-fill ph-envelope-simple fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Địa Chỉ Email</h6>
                        <span class="text-muted small">support@agiletravel.vn <br> booking@agiletravel.vn</span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Social Links -->
            <div class="mt-5">
                <h6 class="fw-bold mb-3">Theo dõi chúng tôi trên</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width: 45px; height: 45px;"><i class="ph-bold ph-facebook-logo fs-5"></i></a>
                    <a href="#" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width: 45px; height: 45px;"><i class="ph-bold ph-instagram-logo fs-5"></i></a>
                    <a href="#" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width: 45px; height: 45px;"><i class="ph-bold ph-youtube-logo fs-5"></i></a>
                    <a href="#" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center hover-lift" style="width: 45px; height: 45px;"><i class="ph-bold ph-tiktok-logo fs-5"></i></a>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="col-lg-7" data-aos="fade-left">
            <div class="card border-0 shadow-soft rounded-4 p-4 p-md-5">
                <h4 class="fw-bold mb-4 font-outfit text-dark tracking-tight">Gửi Lời Nhắn Cho Chúng Tôi</h4>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success border-0 bg-success-subtle text-success rounded-3 p-3 mb-4 d-flex align-items-center">
                        <i class="ph-fill ph-check-circle fs-4 me-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>?action=contact-submit" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control focus-ring-primary bg-light border-0" id="name" name="name" placeholder="Họ và tên" required>
                                <label for="name" class="text-muted">Họ và tên</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control focus-ring-primary bg-light border-0" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email" class="text-muted">Email của bạn</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control focus-ring-primary bg-light border-0" id="phone" name="phone" placeholder="Số điện thoại" required>
                                <label for="phone" class="text-muted">Số điện thoại</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control focus-ring-primary bg-light border-0" id="subject" name="subject" placeholder="Tiêu đề" required>
                                <label for="subject" class="text-muted">Chủ đề cần tư vấn</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control focus-ring-primary bg-light border-0" id="message" name="message" placeholder="Nội dung" style="height: 150px" required></textarea>
                                <label for="message" class="text-muted">Nội dung chi tiết...</label>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm hover-lift d-flex align-items-center gap-2 ms-auto">
                                <i class="ph-bold ph-paper-plane-right"></i> Gửi Yêu Cầu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Embed Map Placeholder -->
<div class="container-fluid px-0 mt-5 pt-5" data-aos="fade-up">
    <!-- FPT Polytechnic Map Frame Address -->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863855881404!2d105.74459841540237!3d21.038132792835425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313454b991d80fd5%3A0x53cefc99d6b0bf6f!2sFPT%20Polytechnic%20Hanoi!5e0!3m2!1sen!2s!4v1655225026786!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
