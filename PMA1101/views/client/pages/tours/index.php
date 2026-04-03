<?php include_once PATH_VIEW_CLIENT . 'default/header.php'; ?>

<!-- Breadcrumb Banner -->
<div class="bg-primary pt-5 pb-5 mt-4" style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);">
    <div class="container mt-5 text-center">
        <h1 class="text-white fw-bold display-5 mb-3" data-aos="fade-up">Khám Phá Cùng AgileTravel</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none">Trang Chủ</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Danh Sách Tour</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top transition-all" style="top: 100px;" data-aos="fade-right">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="ph-bold ph-faders me-2 text-primary"></i>Lọc Tác Vụ</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>" method="GET" id="filterForm">
                        <input type="hidden" name="action" value="tours">
                        
                        <!-- Search -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ph-bold ph-magnifying-glass"></i></span>
                                <input type="text" name="q" class="form-control bg-light border-start-0 ps-0 focus-ring-primary" placeholder="Tên tour..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Danh Mục</label>
                            <?php foreach ($categories as $cat): ?>
                            <div class="form-check custom-checkbox mb-2">
                                <input class="form-check-input" type="radio" name="category" value="<?= htmlspecialchars($cat['slug']) ?>" id="cat_<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['slug']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label d-flex justify-content-between" for="cat_<?= $cat['id'] ?>">
                                    <span><?= htmlspecialchars($cat['name']) ?></span>
                                    <span class="badge bg-light text-secondary rounded-pill"><?= $cat['tour_count'] ?></span>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Duration -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Thời Gian</label>
                            <select name="duration" class="form-select border-1 focus-ring-primary" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                <option value="1-3" <?= (isset($_GET['duration']) && $_GET['duration'] == '1-3') ? 'selected' : '' ?>>1 - 3 ngày</option>
                                <option value="4-7" <?= (isset($_GET['duration']) && $_GET['duration'] == '4-7') ? 'selected' : '' ?>>4 - 7 ngày</option>
                                <option value="over-7" <?= (isset($_GET['duration']) && $_GET['duration'] == 'over-7') ? 'selected' : '' ?>>Trên 7 ngày</option>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Khoảng Giá</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control focus-ring-primary text-center" placeholder="Từ" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control focus-ring-primary text-center" placeholder="Đến" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <hr class="text-secondary opacity-25 my-4">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold hover-lift">Áp Dụng Lọc</button>
                            <a href="<?= BASE_URL ?>?action=tours" class="btn btn-light fw-bold text-muted">Xóa Bộ Lọc</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Top Bar -->
            <div class="bg-white p-3 rounded-4 shadow-sm mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center" data-aos="fade-up">
                <p class="mb-3 mb-md-0 fw-medium text-muted">Tìm thấy <span class="text-primary fw-bold"><?= count($tours) ?></span> tour phù hợp</p>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-bold text-nowrap">Sắp Xếp:</span>
                    <form action="<?= BASE_URL ?>" method="GET" id="sortForm" class="d-inline-flex m-0">
                        <?php foreach ($_GET as $key => $value): if ($key !== 'sort'): ?>
                            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                        <?php endif; endforeach; ?>
                        
                        <select name="sort" class="form-select form-select-sm border-0 bg-light focus-ring-primary fw-medium" onchange="document.getElementById('sortForm').submit()" style="width: auto;">
                            <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                            <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Giá: Thấp đến Cao</option>
                            <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Giá: Cao đến Thấp</option>
                            <option value="popular" <?= (isset($_GET['sort']) && $_GET['sort'] == 'popular') ? 'selected' : '' ?>>Phổ biến nhất</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Tour Grid -->
            <div class="row g-4">
                <?php if (!empty($tours)): ?>
                    <?php foreach($tours as $index => $tour): ?>
                    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                        <div class="tour-card card border-0 shadow-soft h-100 rounded-4 overflow-hidden position-relative hover-lift transition-all">
                            
                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3 z-2 d-flex flex-column gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium shadow-sm"><?= htmlspecialchars($tour['category_name'] ?? 'Mới') ?></span>
                                <?php if ($tour['base_price'] > 5000000): ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium shadow-sm"><i class="ph-fill ph-fire me-1"></i>Hot</span>
                                <?php endif; ?>
                            </div>

                            <!-- Image Section -->
                            <div class="tour-img-wrapper position-relative overflow-hidden" style="height: 220px;">
                                <?php $imgUrl = !empty($tour['main_image']) ? BASE_ASSETS_UPLOADS . $tour['main_image'] : 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=600&auto=format&fit=crop'; ?>
                                <img src="<?= $imgUrl ?>" class="w-100 h-100 object-fit-cover transition-all" alt="<?= htmlspecialchars($tour['name']) ?>">
                                <div class="img-overlay position-absolute w-100 h-100 top-0 start-0 bg-dark opacity-0 transition-all p-3 d-flex align-items-end justify-content-center">
                                    <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-light rounded-pill px-4 text-primary fw-bold shadow-sm translate-y-lg transition-all view-details-btn">Xem Chi Tiết</a>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                                    <span class="d-flex align-items-center gap-1"><i class="ph-fill ph-clock text-primary"></i> <?= $tour['duration_days'] ?? 'N/A' ?> Ngày</span>
                                    <span class="d-flex align-items-center gap-1"><i class="ph-fill ph-users text-primary"></i> <?= $tour['max_participants'] ?? 'N/A' ?> Chỗ</span>
                                </div>
                                
                                <h5 class="card-title fw-bold mb-3 line-clamp-2" style="min-height: 48px;">
                                    <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="text-dark text-decoration-none hover-text-primary transition-all"><?= htmlspecialchars($tour['name']) ?></a>
                                </h5>
                                
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div class="price-box">
                                        <span class="fs-5 fw-bolder text-primary d-block"><?= number_format($tour['base_price'], 0, ',', '.') ?>đ</span>
                                    </div>
                                    <a href="<?= BASE_URL ?>?action=tour-detail&id=<?= $tour['id'] ?>" class="btn btn-primary rounded-circle hover-lift text-center" style="width: 40px; height: 40px; line-height: 28px;">
                                        <i class="ph-bold ph-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 py-5 text-center" data-aos="fade-up">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="150" class="opacity-50 mb-4" alt="No Data">
                        <h4 class="text-muted fw-bold">Không tìm thấy tour nào!</h4>
                        <p class="text-muted">Vui lòng thử điều chỉnh lại bộ lọc tìm kiếm của bạn.</p>
                        <a href="<?= BASE_URL ?>?action=tours" class="btn btn-primary rounded-pill px-4 mt-2">Xem Tất Cả Tour</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination (Placeholder for UI) -->
            <?php if (count($tours) > 12): ?>
            <nav class="mt-5" data-aos="fade-up">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link border-0 shadow-sm rounded-circle me-2 d-flex align-items-center justify-content-center text-muted" style="width:40px; height:40px" href="#"><i class="ph-bold ph-caret-left"></i></a></li>
                    <li class="page-item active"><a class="page-link border-0 shadow-sm rounded-circle mx-1 d-flex align-items-center justify-content-center" style="width:40px; height:40px" href="#">1</a></li>
                    <li class="page-item"><a class="page-link border-0 shadow-sm rounded-circle mx-1 d-flex align-items-center justify-content-center text-dark" style="width:40px; height:40px" href="#">2</a></li>
                    <li class="page-item"><a class="page-link border-0 shadow-sm rounded-circle ms-2 d-flex align-items-center justify-content-center text-dark" style="width:40px; height:40px" href="#"><i class="ph-bold ph-caret-right"></i></a></li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Custom Checkbox styles */
    .custom-checkbox .form-check-input {
        cursor: pointer;
    }
    .custom-checkbox .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
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
</style>

<?php include_once PATH_VIEW_CLIENT . 'default/footer.php'; ?>
