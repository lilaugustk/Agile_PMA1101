<?php
if (!isset($isAjax)) {
    include_once PATH_VIEW_ADMIN . 'default/header.php';
    include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
}
?>

<?php if (!isset($isAjax)) : ?>
<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-muted text-decoration-none"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Tour</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours/create' ?>" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" style="border-radius: var(--radius-md);">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Thêm Tour Mới
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-check-circle fs-5"></i>
            <div><?= $_SESSION['success'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert" style="border-radius: var(--radius-md);">
            <i class="ph-fill ph-warning-circle fs-5"></i>
            <div><?= $_SESSION['error'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tổng Tour</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['total'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                    <i class="ph ph-map-trifold"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đang Hoạt Động</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['active'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                    <i class="ph ph-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Tour Nổi Bật</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['featured'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                    <i class="ph ph-star"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                <div>
                    <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Đang Diễn Ra</p>
                    <h3 class="fw-bold mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= number_format($stats['ongoing'] ?? 0) ?></h3>
                </div>
                <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                    <i class="ph ph-airplane-in-flight"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card-premium mb-3">
        <div class="p-2 px-3 border-bottom border-light d-flex justify-content-between align-items-center bg-white" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                <i class="ph ph-funnel text-muted"></i> Bộ Lọc Tìm Kiếm
            </h6>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="resetFilters()" style="font-size: 0.75rem;">
                    <i class="ph ph-arrow-counter-clockwise"></i> Reset
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 py-1" onclick="toggleAdvancedFilters()" style="font-size: 0.75rem;">
                    <i class="ph ph-sliders"></i> Nâng Cao
                </button>
            </div>
        </div>

        <div class="p-2 bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <form id="tour-filters" method="GET" action="<?= BASE_URL_ADMIN ?>">
                <input type="hidden" name="mode" value="admin">
                <input type="hidden" name="action" value="tours">

                <div class="row g-2">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Tìm kiếm</label>
                        <div class="position-relative">
                            <i class="ph ph-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem;"></i>
                            <input type="text" class="form-control form-control-sm ps-4 border-light-subtle shadow-sm" name="keyword" 
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" 
                                placeholder="Tên tour, mô tả..." style="border-radius: 8px; min-height: 38px;">
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Loại Tour</label>
                        <select class="form-select form-select-sm border-light-subtle shadow-sm" name="category_id" style="border-radius: 8px; min-height: 38px;">
                            <option value="">Tất cả</option>
                            <?php foreach ($categories ?? [] as $category): ?>
                                <option value="<?= $category['id'] ?>"
                                    <?= (($_GET['category_id'] ?? '') == $category['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Trạng thái</label>
                        <select class="form-select form-select-sm border-light-subtle shadow-sm" name="status" style="border-radius: 8px; min-height: 38px;">
                            <option value="">Tất cả</option>
                            <option value="active" <?= (($_GET['status'] ?? '') == 'active') ? 'selected' : '' ?>>Đang hoạt động</option>
                            <option value="inactive" <?= (($_GET['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Tạm ẩn</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Đánh giá</label>
                        <select class="form-select form-select-sm border-light-subtle shadow-sm" name="rating_min" style="border-radius: 8px; min-height: 38px;">
                            <option value="">Tất cả</option>
                            <option value="1" <?= (($_GET['rating_min'] ?? '') == '1') ? 'selected' : '' ?>>≥ 1 sao</option>
                            <option value="2" <?= (($_GET['rating_min'] ?? '') == '2') ? 'selected' : '' ?>>≥ 2 sao</option>
                            <option value="3" <?= (($_GET['rating_min'] ?? '') == '3') ? 'selected' : '' ?>>≥ 3 sao</option>
                            <option value="4" <?= (($_GET['rating_min'] ?? '') == '4') ? 'selected' : '' ?>>≥ 4 sao</option>
                            <option value="5" <?= (($_GET['rating_min'] ?? '') == '5') ? 'selected' : '' ?>>≥ 5 sao</option>
                        </select>
                    </div>
                </div>

                <!-- Advanced Filters (Hidden by default) -->
                <div class="row g-3 mt-1 advanced-filters" style="display: none;">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá từ (VNĐ)</label>
                        <input type="number" class="form-control form-control-sm border-light-subtle shadow-sm" name="price_min"
                            value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>" placeholder="0" style="border-radius: 8px; min-height: 38px;">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá đến (VNĐ)</label>
                        <input type="number" class="form-control form-control-sm border-light-subtle shadow-sm" name="price_max"
                            value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>" placeholder="Không giới hạn" style="border-radius: 8px; min-height: 38px;">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Từ ngày</label>
                        <input type="date" class="form-control form-control-sm border-light-subtle shadow-sm" name="date_from"
                            value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" style="border-radius: 8px; min-height: 38px;" />
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Đến ngày</label>
                        <input type="date" class="form-control form-control-sm border-light-subtle shadow-sm" name="date_to"
                            value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" style="border-radius: 8px; min-height: 38px;" />
                    </div>
                </div>

                <div class="row g-3 mt-1 align-items-end">
                    <div class="col-12 col-lg-8 d-flex gap-3">
                        <div class="flex-grow-1">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Sắp xếp theo</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="sort_by" style="border-radius: 8px; min-height: 38px;">
                                <option value="">Mặc định</option>
                                <option value="name" <?= (($_GET['sort_by'] ?? '') == 'name') ? 'selected' : '' ?>>Tên tour</option>
                                <option value="price" <?= (($_GET['sort_by'] ?? '') == 'price') ? 'selected' : '' ?>>Giá</option>
                                <option value="rating" <?= (($_GET['sort_by'] ?? '') == 'rating') ? 'selected' : '' ?>>Đánh giá</option>
                                <option value="created_at" <?= (($_GET['sort_by'] ?? '') == 'created_at') ? 'selected' : '' ?>>Ngày tạo</option>
                            </select>
                        </div>
                        <div style="width: 130px;">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Thứ tự</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="sort_dir" style="border-radius: 8px; min-height: 38px;">
                                <option value="DESC" <?= (($_GET['sort_dir'] ?? '') == 'DESC') ? 'selected' : '' ?>>Giảm dần</option>
                                <option value="ASC" <?= (($_GET['sort_dir'] ?? '') == 'ASC') ? 'selected' : '' ?>>Tăng dần</option>
                            </select>
                        </div>
                        <div style="width: 100px;">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Hiển thị</label>
                            <select class="form-select form-select-sm border-light-subtle shadow-sm" name="per_page" style="border-radius: 8px; min-height: 38px;">
                                <option value="12" <?= (($_GET['per_page'] ?? '') == '12') ? 'selected' : '' ?>>12</option>
                                <option value="24" <?= (($_GET['per_page'] ?? '') == '24') ? 'selected' : '' ?>>24</option>
                                <option value="48" <?= (($_GET['per_page'] ?? '') == '48') ? 'selected' : '' ?>>48</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm d-flex align-items-center gap-2" style="border-radius: 8px; height: 35px;">
                            <i class="ph-fill ph-magnifying-glass"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>


    <!-- Tours Grid Section -->
    <div class="card-premium min-vh-100">
        <div class="p-3 px-4 border-bottom border-light bg-white d-flex flex-wrap justify-content-between align-items-center" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
            <div class="d-flex align-items-center gap-3">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="ph-fill ph-list-bullets text-primary"></i> 
                    Danh sách Tour
                </h6>
                <span class="count-info badge bg-light text-muted border px-2 py-1 rounded-pill">
                    <?= $pagination['total'] ?? 0 ?> tour • Trang <?= $pagination['page'] ?? 1 ?>/<?= max(1, $pagination['total_pages'] ?? 1) ?>
                </span>
            </div>
            
            <div class="d-flex gap-1 bg-light p-1 rounded-pill">
                <button class="btn btn-sm view-btn active rounded-circle border-0 text-muted" data-view="grid" title="Grid view" style="width: 32px; height: 32px; padding: 0;">
                    <i class="ph-fill ph-grid-four"></i>
                </button>
                <button class="btn btn-sm view-btn rounded-circle border-0 text-muted" data-view="list" title="List view" style="width: 32px; height: 32px; padding: 0;">
                    <i class="ph-fill ph-list"></i>
                </button>
            </div>
        </div>

        <div class="p-4 bg-white" id="tour-list-container" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
            <?php if (!empty($tours)) : ?>
                <div class="row g-4 tours-grid">
                    <?php foreach ($tours as $tour) : ?>
                        <?php
                        $mainImage = $tour['main_image'] ?? null;
                        $galleryImages = [];
                        if (!empty($tour['gallery_images'])) {
                            $galleryImages = array_values(array_filter(array_map('trim', explode(',', $tour['gallery_images']))));
                        }
                        if (empty($galleryImages) && !empty($mainImage)) $galleryImages = [$mainImage];
                        if (!empty($mainImage) && !empty($galleryImages) && !in_array($mainImage, $galleryImages)) array_unshift($galleryImages, $mainImage);
                        if (empty($mainImage) && !empty($galleryImages)) $mainImage = $galleryImages[0];
                        
                        $thumbs = array_slice($galleryImages, 1);
                        $totalImages = count($galleryImages);
                        
                        $galleryUrls = [];
                        if (!empty($galleryImages)) {
                            foreach ($galleryImages as $g) {
                                if (!empty($g)) $galleryUrls[] = BASE_ASSETS_UPLOADS . $g;
                            }
                        }
                        if (empty($galleryUrls) && !empty($tour['main_image'])) {
                            $galleryUrls = [BASE_ASSETS_UPLOADS . $tour['main_image']];
                        }
                        ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xxl-3 tour-card-modern" data-id="<?= $tour['id'] ?>" data-gallery='<?= htmlspecialchars(json_encode($galleryUrls), ENT_QUOTES) ?>'>
                            <div class="card h-100 border border-light shadow-sm d-flex flex-column overflow-hidden" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                                
                                <!-- Media Container to group image and thumbnails in list view -->
                                <div class="card-media-wrapper d-flex flex-column">
                                    <!-- Image Header -->
                                    <div class="position-relative bg-light main-img-wrapper" style="padding-top: 66.66%;">
                                    <!-- Background Img -->
                                    <?php 
                                        $mainUrl = $mainImage ? BASE_ASSETS_UPLOADS . $mainImage : BASE_URL . 'assets/admin/image/no-image.png';
                                    ?>
                                    <img src="<?= $mainUrl ?>" class="main-img position-absolute top-0 start-0 w-100 h-100 object-fit-cover" data-index="0" alt="<?= htmlspecialchars($tour['name']) ?>" style="cursor: pointer;">
                                    
                                    <!-- Overlays -->
                                    <div class="position-absolute top-0 start-0 w-100 p-3 d-flex justify-content-between align-items-start pointer-events-none" style="background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);">
                                        <div class="d-flex flex-column gap-2 pointer-events-auto">
                                            <?php if ($tour['featured'] ?? 0): ?>
                                                <span class="badge bg-warning text-dark"><i class="ph-fill ph-star me-1"></i> Nổi bật</span>
                                            <?php endif; ?>
                                            <span class="badge badge-status <?= ($tour['status'] ?? 'active') === 'active' ? 'bg-success' : 'bg-secondary' ?> text-white">
                                                <?= ($tour['status'] ?? 'active') === 'active' ? 'Hoạt động' : 'Tạm ẩn' ?>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2 text-white">
                                            <span class="badge category-badge bg-primary px-2 py-1"><?= htmlspecialchars($tour['category_name'] ?? 'Chung') ?></span>
                                            <div class="badge bg-dark bg-opacity-75 d-flex align-items-center gap-1">
                                                <i class="ph ph-images"></i> <?= $totalImages ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Thumbnails Strip -->
                                <?php if (!empty($thumbs) && count($thumbs) > 0): ?>
                                    <div class="d-flex gap-1 p-2 bg-light border-bottom border-light overflow-auto" style="min-height: 48px;">
                                        <?php 
                                        $maxThumbs = 4;
                                        $thumbsToShow = array_slice($thumbs, 0, $maxThumbs);
                                        foreach ($thumbsToShow as $i => $timg): 
                                            $turl = !empty($timg) ? BASE_ASSETS_UPLOADS . $timg : BASE_URL . 'assets/admin/image/no-image.png';
                                        ?>
                                            <div class="thumbnail-item rounded" style="width: 40px; height: 32px; overflow: hidden; cursor:pointer;" onclick="this.closest('.tour-card-modern').querySelector('.main-img').src = '<?= $turl ?>';">
                                                <img src="<?= $turl ?>" class="w-100 h-100 object-fit-cover" alt="thumb">
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if(count($thumbs) > $maxThumbs): ?>
                                            <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 32px; font-size: 0.75rem;">
                                                +<?= count($thumbs) - $maxThumbs ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                </div> <!-- End card-media-wrapper -->

                                <!-- Content -->
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <h5 class="tour-title fw-bold fs-6 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= htmlspecialchars($tour['name']) ?>">
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </h5>
                                    
                                    <div class="mt-auto pt-2 d-flex justify-content-between align-items-center border-top border-light">
                                        <div class="text-warning small d-flex align-items-center gap-1">
                                            <i class="ph-fill ph-star"></i>
                                            <span class="rating-value fw-bold text-dark"><?= number_format($tour['avg_rating'] ?? 0, 1) ?></span>
                                            <span class="text-muted" style="font-size: 0.75rem;">(<?= number_format($tour['booking_count'] ?? 0) ?>)</span>
                                        </div>
                                        <div class="price-value fw-bold text-primary" style="font-size: 1.1rem;">
                                            <?= number_format($tour['base_price'] ?? 0, 0, ',', '.') ?>đ
                                        </div>
                                    </div>
                                    
                                    <!-- Actions & Toggles Bar -->
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3 p-2 bg-light rounded" style="font-size: 0.85rem;">
                                        <!-- Toggles -->
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input toggle-status" type="checkbox" role="switch" data-id="<?= $tour['id'] ?>" <?= ($tour['status'] ?? 'active') === 'active' ? 'checked' : '' ?>>
                                                <label class="form-check-label text-muted toggle-label">
                                                    <span class="toggle-text d-none"><?= ($tour['status'] ?? 'active') === 'active' ? 'Hoạt động' : 'Tạm ẩn' ?></span>
                                                    Trạng thái
                                                </label>
                                            </div>
                                            <div class="form-check mb-0 ms-2">
                                                <input class="form-check-input toggle-featured" type="checkbox" data-id="<?= $tour['id'] ?>" <?= ($tour['featured'] ?? 0) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-warning"><i class="ph-fill ph-star"></i> Nổi Bật</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours/detail&id=' . $tour['id'] ?>" class="btn btn-sm bg-white text-primary border shadow-sm" title="Chi tiết"><i class="ph ph-eye"></i></a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm" title="Sửa"><i class="ph ph-pencil-simple"></i></a>
                                            <button type="button" class="btn btn-sm bg-white text-info border shadow-sm btn-qr" data-id="<?= $tour['id'] ?>" data-name="<?= htmlspecialchars($tour['name']) ?>" title="Lấy Link & QR"><i class="ph ph-qr-code"></i></button>
                                            <button type="button" class="btn btn-sm bg-white text-danger border shadow-sm delete-tour" data-id="<?= $tour['id'] ?>" data-name="<?= htmlspecialchars($tour['name']) ?>" title="Xóa"><i class="ph ph-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center p-5">
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="ph-fill ph-magnifying-glass text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Không tìm thấy tour phù hợp</h5>
                    <p class="text-muted">Thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                    <button class="btn btn-primary mt-2 px-4 shadow-sm" onclick="resetFilters()">
                        <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset bộ lọc
                    </button>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (($pagination['total_pages'] ?? 1) > 1) : ?>
            <?php
            $filterParams = array_filter([
                'keyword' => $_GET['keyword'] ?? '',
                'category_id' => $_GET['category_id'] ?? '',
                'status' => $_GET['status'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? '',
                'price_min' => $_GET['price_min'] ?? '',
                'price_max' => $_GET['price_max'] ?? '',
                'rating_min' => $_GET['rating_min'] ?? '',
                'per_page' => $pagination['per_page'] ?? null,
                'sort_by' => $_GET['sort_by'] ?? null,
                'sort_dir' => $_GET['sort_dir'] ?? null,
            ], function ($value) { return $value !== null && $value !== ''; });

            $queryStringBase = !empty($filterParams) ? '&' . http_build_query($filterParams) : '';
            ?>
            <div class="p-4 border-top border-light d-flex justify-content-center bg-white" style="border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                <nav>
                    <ul class="pagination mb-0">
                        <?php
                        $currentPage = $pagination['page'];
                        $totalPages = $pagination['total_pages'];
                        $prevPage = max(1, $currentPage - 1);
                        $nextPage = min($totalPages, $currentPage + 1);
                        ?>
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link shadow-sm" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $prevPage . $queryStringBase ?>"><i class="ph ph-caret-left"></i></a>
                        </li>
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        if ($startPage > 1) : ?>
                            <li class="page-item"><a class="page-link shadow-sm" href="<?= BASE_URL_ADMIN . '&action=tours&page=1' . $queryStringBase ?>">1</a></li>
                            <?php if ($startPage > 2) : ?><li class="page-item disabled"><span class="page-link bg-light border-0">...</span></li><?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($page = $startPage; $page <= $endPage; $page++) : ?>
                            <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                                <a class="page-link shadow-sm" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $page . $queryStringBase ?>"><?= $page ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($endPage < $totalPages) : ?>
                            <?php if ($endPage < $totalPages - 1) : ?><li class="page-item disabled"><span class="page-link bg-light border-0">...</span></li><?php endif; ?>
                            <li class="page-item"><a class="page-link shadow-sm" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $totalPages . $queryStringBase ?>"><?= $totalPages ?></a></li>
                        <?php endif; ?>
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link shadow-sm" href="<?= BASE_URL_ADMIN . '&action=tours&page=' . $nextPage . $queryStringBase ?>"><i class="ph ph-caret-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Style fixes specific to Tour Grid */
.pointer-events-none { pointer-events: none; }
.pointer-events-auto { pointer-events: auto; }
.tour-card-modern:hover .card {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md) !important;
    border-color: var(--primary) !important;
}
.view-btn.active {
    background-color: var(--primary) !important;
    color: white !important;
}
.tours-grid.list-view {
    display: flex;
    flex-direction: column;
}
.tours-grid.list-view .tour-card-modern {
    width: 100%;
    max-width: 100%;
}
.tours-grid.list-view .tour-card-modern .card {
    flex-direction: row !important;
}
.tours-grid.list-view .tour-card-modern .card > .card-media-wrapper {
    width: 300px;
    min-width: 300px;
    flex-shrink: 0;
    border-right: 1px solid var(--border-light);
}
.tours-grid.list-view .tour-card-modern .card > .card-media-wrapper .main-img-wrapper {
    padding-top: 0 !important;
    min-height: 180px;
    flex-grow: 1;
}
.tours-grid.list-view .tour-card-modern .card > .card-media-wrapper img.main-img {
    height: 100%;
}
</style>

<!-- Add all modals from original -->
<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="ph ph-qr-code me-2"></i> Mã QR & Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-4">
                <h6 id="qr-tour-name" class="mb-3 fw-bold text-primary"></h6>
                <div class="qr-code-wrapper mb-4 p-3 border rounded border-light d-inline-block bg-white shadow-sm">
                    <div id="qrcode"></div>
                </div>
                <div class="input-group mb-3 shadow-sm rounded overflow-hidden">
                    <span class="input-group-text bg-light border-0"><i class="ph ph-link"></i></span>
                    <input type="text" class="form-control border-0 bg-light" id="tour-link" readonly>
                    <button class="btn btn-primary px-3" type="button" id="copy-link-btn">
                        <i class="ph ph-copy"></i> Copy
                    </button>
                </div>
                <div class="alert alert-success d-none py-2 border-0" id="copy-success-alert">
                    <small><i class="ph-fill ph-check-circle me-1"></i> Đã sao chép liên kết!</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-5 px-4">
                <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-danger-subtle text-danger mb-4" style="width: 72px; height: 72px;">
                    <i class="ph-fill ph-warning-circle" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold mb-3">Xóa tour này?</h5>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn xóa tour "<strong id="delete-tour-name" class="text-dark"></strong>"?<br> Hành động này không thể hoàn tác.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="delete-form" method="POST" class="m-0">
                        <input type="hidden" name="id" id="delete-tour-id">
                        <button type="submit" class="btn btn-danger px-4 shadow-sm">Xóa Tour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Toggle status
        document.querySelectorAll('.toggle-status').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const tourId = this.dataset.id;
                const newStatus = this.checked ? 'active' : 'inactive';

                fetch('<?= BASE_URL_ADMIN ?>&action=tours/toggle-status', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `_method=PATCH&id=${tourId}`
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const tourCard = this.closest('.tour-card-modern');
                        const statusBadge = tourCard.querySelector('.badge-status');
                        statusBadge.className = `badge badge-status text-white ${newStatus === 'active' ? 'bg-success' : 'bg-secondary'}`;
                        statusBadge.textContent = newStatus === 'active' ? 'Hoạt động' : 'Tạm ẩn';
                        
                        const toggleText = this.closest('.toggle-label').querySelector('.toggle-text');
                        if(toggleText) toggleText.textContent = newStatus === 'active' ? 'Hoạt động' : 'Tạm ẩn';
                    } else {
                        this.checked = !this.checked;
                        showToast('Có lỗi xảy ra', 'error');
                    }
                }).catch(err => {
                    this.checked = !this.checked;
                    showToast('Có lỗi xảy ra', 'error');
                });
            });
        });

        // Toggle featured
        document.querySelectorAll('.toggle-featured').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const tourId = this.dataset.id;
                fetch('<?= BASE_URL_ADMIN ?>&action=tours/toggle-featured', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `_method=PATCH&id=${tourId}`
                }).then(res=>res.json()).then(data => {
                    if(!data.success) { this.checked = !this.checked; showToast('Lỗi !', 'error'); }
                }).catch(()=>{ this.checked=!this.checked; showToast('Lỗi !', 'error'); });
            });
        });

        // Delete Form Setup
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.querySelectorAll('.delete-tour').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('delete-tour-id').value = this.dataset.id;
                document.getElementById('delete-tour-name').textContent = this.dataset.name;
                document.getElementById('delete-form').action = '<?= BASE_URL_ADMIN ?>&action=tours/delete&id=' + this.dataset.id;
                deleteModal.show();
            });
        });


        // Display View Toggle
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active', 'bg-primary', 'text-white'));
                this.classList.add('active', 'bg-primary', 'text-white');
                const view = this.dataset.view;
                const container = document.querySelector('.tours-grid');
                if (view === 'list') container.classList.add('list-view');
                else container.classList.remove('list-view');
            });
        });
        
        // Initial set of list/grid
        document.querySelector('.view-btn.active').classList.add('bg-primary', 'text-white');
    });

    function toggleAdvancedFilters() {
        const adv = document.querySelector('.advanced-filters');
        if (adv.style.display === 'none') adv.style.display = 'flex';
        else adv.style.display = 'none';
    }

    function resetFilters() {
        window.location.href = '<?= BASE_URL_ADMIN ?>&action=tours';
    }

    function showToast(message, type = 'success') {
        const div = document.createElement('div');
        div.style.cssText = 'position:fixed; top:20px; right:20px; z-index:9999';
        div.innerHTML = `<div class="alert alert-${type === 'success' ? 'success' : 'danger'} shadow-sm"><i class="ph-fill ph-${type==='success'?'check':'warning'}-circle me-2"></i>${message}</div>`;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrModal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrcode');
        const tourLinkInput = document.getElementById('tour-link');
        const copyAlert = document.getElementById('copy-success-alert');
        let qrcodeObj = null;

        document.querySelectorAll('.btn-qr').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const tourId = this.dataset.id;
                const publicUrl = `<?= BASE_URL ?>?action=tour-detail&id=${tourId}`;
                document.getElementById('qr-tour-name').textContent = this.dataset.name;
                tourLinkInput.value = publicUrl;
                copyAlert.classList.add('d-none');
                
                qrContainer.innerHTML = '';
                new QRCode(qrContainer, { text: publicUrl, width: 180, height: 180, colorDark: "#000", colorLight: "#fff", correctLevel: QRCode.CorrectLevel.H });
                new bootstrap.Modal(qrModal).show();
            });
        });

        document.getElementById('copy-link-btn').addEventListener('click', function() {
            tourLinkInput.select();
            navigator.clipboard.writeText(tourLinkInput.value).then(() => {
                copyAlert.classList.remove('d-none');
                setTimeout(() => copyAlert.classList.add('d-none'), 2000);
            });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- Filter JavaScript bindings (client-side filters) from original file but using standard query selector --><?php if (!isset($isAjax)) : ?>
    </div>
</main>
<?php endif; ?>

<?php if (isset($isAjax)) exit; ?>
<script>
// AJAX Search Implementation
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('tour-filters');
    const targetArea = document.querySelector('.card-premium.min-vh-100');

    if (filterForm && targetArea) {
        const handleSearch = async (e) => {
            if (e) e.preventDefault();
            
            // Show loading state
            targetArea.style.opacity = '0.5';
            targetArea.style.pointerEvents = 'none';
            
            try {
                // Construct URL robustly using URLSearchParams
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                params.set('ajax', '1');

                // Construct base URL from action attribute
                const formAction = filterForm.getAttribute('action') || window.location.href;
                const baseUrl = formAction.split('?')[0];
                const url = new URL(baseUrl, window.location.origin);
                
                // Merge existing params from formAction (e.g., mode, action)
                if (formAction.includes('?')) {
                    const existingParams = new URLSearchParams(formAction.split('?')[1]);
                    existingParams.forEach((v, k) => url.searchParams.set(k, v));
                }
                
                // Apply params from form inputs (overwrites defaults)
                params.forEach((v, k) => {
                    if (v !== '') url.searchParams.set(k, v);
                    else url.searchParams.delete(k); // Clean up empty params
                });
                url.searchParams.set('ajax', '1');

                console.log('Fetching from:', url.toString());

                const response = await fetch(url.toString());
                const html = await response.text();
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('.card-premium.min-vh-100');
                
                if (newContent) {
                    targetArea.innerHTML = newContent.innerHTML;
                    
                    // Update URL for browser history (without ajax=1)
                    const historyUrl = new URL(url.toString());
                    historyUrl.searchParams.delete('ajax');
                    window.history.pushState({path: historyUrl.toString()}, '', historyUrl.toString());
                    
                    // Re-bind all dynamic events
                    bindDynamicEvents(targetArea);
                }
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                targetArea.style.opacity = '1';
                targetArea.style.pointerEvents = 'auto';
            }
        };

        filterForm.addEventListener('submit', handleSearch);
        filterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => handleSearch());
        });

        let timeout = null;
        filterForm.querySelector('input[name="keyword"]').addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(handleSearch, 500);
        });

        function bindDynamicEvents(container) {
            container.querySelectorAll('.toggle-status').forEach(t => t.addEventListener('change', handleStatusToggle));
            container.querySelectorAll('.toggle-featured').forEach(t => t.addEventListener('change', handleFeaturedToggle));
            container.querySelectorAll('.delete-tour').forEach(b => b.addEventListener('click', handleDeleteClick));
            container.querySelectorAll('.view-btn').forEach(b => b.addEventListener('click', handleViewToggle));
            container.querySelectorAll('.btn-qr').forEach(b => b.addEventListener('click', handleQRClick));
        }
        
        // Use existing global handlers or define them here for the dynamic scope
        function handleStatusToggle() {
            const tourId = this.dataset.id;
            const newStatus = this.checked ? 'active' : 'inactive';
            fetch('<?= BASE_URL_ADMIN ?>&action=tours/toggle-status', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `_method=PATCH&id=${tourId}`
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    const tourCard = this.closest('.tour-card-modern');
                    const statusBadge = tourCard.querySelector('.badge-status');
                    if(statusBadge) {
                        statusBadge.className = `badge badge-status text-white ${newStatus === 'active' ? 'bg-success' : 'bg-secondary'}`;
                        statusBadge.textContent = newStatus === 'active' ? 'Hoạt động' : 'Tạm ẩn';
                    }
                } else {
                    this.checked = !this.checked;
                }
            });
        }

        function handleFeaturedToggle() {
            const tourId = this.dataset.id;
            fetch('<?= BASE_URL_ADMIN ?>&action=tours/toggle-featured', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `_method=PATCH&id=${tourId}`
            }).then(res=>res.json()).then(data => {
                if(!data.success) this.checked = !this.checked;
            });
        }

        function handleDeleteClick() {
            document.getElementById('delete-tour-id').value = this.dataset.id;
            document.getElementById('delete-tour-name').textContent = this.dataset.name;
            document.getElementById('delete-form').action = '<?= BASE_URL_ADMIN ?>&action=tours/delete&id=' + this.dataset.id;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function handleViewToggle() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active', 'bg-primary', 'text-white'));
            this.classList.add('active', 'bg-primary', 'text-white');
            const view = this.dataset.view;
            const container = document.querySelector('.tours-grid');
            if (container) {
                if (view === 'list') container.classList.add('list-view');
                else container.classList.remove('list-view');
            }
        }

        function handleQRClick() {
            const tourId = this.dataset.id;
            const publicUrl = `<?= BASE_URL ?>?action=tour-detail&id=${tourId}`;
            document.getElementById('qr-tour-name').textContent = this.dataset.name;
            document.getElementById('tour-link').value = publicUrl;
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            new QRCode(qrContainer, { text: publicUrl, width: 180, height: 180 });
            new bootstrap.Modal(document.getElementById('qrModal')).show();
        }
    }
});
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>