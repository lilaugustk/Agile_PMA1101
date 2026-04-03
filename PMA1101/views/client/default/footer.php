    <!-- Footer -->
    <footer class="premium-footer bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container mt-4">
            <div class="row g-4">
                <!-- Column 1: Brand & About -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <a href="<?= BASE_URL ?>" class="d-flex align-items-center text-white text-decoration-none mb-3 gap-2">
                        <i class="ph-fill ph-paper-plane-tilt fs-1 text-primary"></i>
                        <span class="fs-3 fw-bold tracking-tight">AgileTravel</span>
                    </a>
                    <p class="text-white-50 mb-4 pe-lg-4 text-justify">
                        Chúng tôi cam kết mang đến những trải nghiệm du lịch tuyệt vời nhất với dịch vụ cao cấp, lịch trình độc đáo và sự hỗ trợ tận tâm 24/7.
                    </p>
                    <div class="social-links d-flex gap-2">
                        <a href="#" class="btn btn-outline-light rounded-circle social-btn"><i class="ph-fill ph-facebook-logo"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle social-btn"><i class="ph-fill ph-instagram-logo"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle social-btn"><i class="ph-fill ph-youtube-logo"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle social-btn"><i class="ph-fill ph-tiktok-logo"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white fw-bold mb-4 position-relative footer-heading">Khám Phá</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="<?= BASE_URL ?>?action=tours" class="text-white-50 text-decoration-none transition-all hover-text-white"><i class="ph ph-caret-right me-2 text-primary"></i>Tất Cả Tour</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>?action=tours&category=hot" class="text-white-50 text-decoration-none transition-all hover-text-white"><i class="ph ph-caret-right me-2 text-primary"></i>Tour Khuyến Mãi</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>?action=tours&category=domestic" class="text-white-50 text-decoration-none transition-all hover-text-white"><i class="ph ph-caret-right me-2 text-primary"></i>Tour Trong Nước</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>?action=tours&category=international" class="text-white-50 text-decoration-none transition-all hover-text-white"><i class="ph ph-caret-right me-2 text-primary"></i>Tour Quốc Tế</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>?action=about" class="text-white-50 text-decoration-none transition-all hover-text-white"><i class="ph ph-caret-right me-2 text-primary"></i>Về Chúng Tôi</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white fw-bold mb-4 position-relative footer-heading">Liên Hệ</h5>
                    <ul class="list-unstyled footer-contact">
                        <li class="d-flex mb-3 align-items-start gap-3">
                            <i class="ph-fill ph-map-pin text-primary fs-4 mt-1"></i>
                            <span class="text-white-50">Tòa nhà Agile, Đường Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</span>
                        </li>
                        <li class="d-flex mb-3 align-items-center gap-3">
                            <i class="ph-fill ph-phone-call text-primary fs-4"></i>
                            <span class="text-white-50">1900 1080 (Hỗ trợ 24/7)</span>
                        </li>
                        <li class="d-flex mb-3 align-items-center gap-3">
                            <i class="ph-fill ph-envelope-simple text-primary fs-4"></i>
                            <span class="text-white-50">support@agiletravel.com</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white fw-bold mb-4 position-relative footer-heading">Đăng Ký Nhận Tin</h5>
                    <p class="text-white-50 mb-3">Nhận ngay ưu đãi 10% cho lần đặt tour đầu tiên khi đăng ký nhận bản tin từ chúng tôi.</p>
                    <form class="newsletter-form mt-3 position-relative">
                        <input type="email" class="form-control bg-dark border-secondary text-white rounded-pill pe-5 py-2 focus-ring-primary" placeholder="Email của bạn..." required>
                        <button type="submit" class="btn btn-primary rounded-circle position-absolute top-0 end-0 h-100 p-0 text-center" style="width: 42px;">
                            <i class="ph-bold ph-paper-plane-right"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row align-items-center border-top border-secondary mt-5 pt-4">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0 pt-2">
                    <p class="text-white-50 small mb-0">&copy; <?= date('Y') ?> AgileTravel. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Bo_cong_thuong.svg/1024px-Bo_cong_thuong.svg.png" alt="Bộ Công Thương" height="35" class="opacity-75 hover-opacity-100 transition-all">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg scroll-top-btn transition-all" id="scrollTopBtn" style="width: 45px; height: 45px; opacity: 0; pointer-events: none; z-index: 1000;" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="ph-bold ph-caret-up"></i>
    </button>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Init AOS
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Navbar Scroll Effect
        const navbar = document.getElementById('mainNav');
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow', 'bg-white');
                navbar.classList.remove('py-2');
                
                // Show scroll top btn
                scrollTopBtn.style.opacity = '1';
                scrollTopBtn.style.pointerEvents = 'auto';
                scrollTopBtn.style.transform = 'translateY(0)';
            } else {
                navbar.classList.remove('shadow', 'bg-white');
                navbar.classList.add('py-2');
                
                // Hide scroll top btn
                scrollTopBtn.style.opacity = '0';
                scrollTopBtn.style.pointerEvents = 'none';
                scrollTopBtn.style.transform = 'translateY(20px)';
            }
        });

        // Trigger scroll event on load
        window.dispatchEvent(new Event('scroll'));
    </script>
</body>
</html>