<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour = $tour ?? null;
$allImages = $allImages ?? [];
$pricingOptions = $pricingOptions ?? [];
$itinerarySchedule = $itinerarySchedule ?? [];
$partnerServices = $partnerServices ?? [];
$departures = $departures ?? [];
$policies = $policies ?? [];

// Helper for price formatting
function formatPrice($price)
{
    if ($price >= 1000000000) {
        return round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
    } elseif ($price >= 1000000) {
        return round($price / 1000000, 1) . ' tr';
    } else {
        return number_format($price, 0, ',', '.') . 'đ';
    }
}

// Find main image from $allImages
$mainImage = BASE_URL . 'assets/admin/image/no-image.png';
foreach ($allImages as $img) {
    if (!empty($img['main'])) {
        $mainImage = $img['url'] ?? BASE_URL . 'assets/admin/image/no-image.png';
        break;
    }
}

// Prepare gallery URLs for lightbox
$galleryUrls = [];
foreach ($allImages as $img) {
    $url = $img['url'] ?? '';
    if ($url) {
        $galleryUrls[] = $url;
    }
}
if (empty($galleryUrls)) {
    $galleryUrls[] = $mainImage;
}
?>

<main class="content">
    <div class="d-flex justify-content-between align-items-end mb-4 pb-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=/" class="text-decoration-none text-muted"><i class="ph ph-house me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL_ADMIN ?>&action=tours" class="text-decoration-none text-muted">Quản lý Tour</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết Tour</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= $tour['id'] ?>" class="btn btn-sm bg-white text-muted border shadow-sm d-flex align-items-center gap-2">
                <i class="ph ph-note-pencil"></i> Chỉnh sửa
            </a>
            <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&tour_id=<?= $tour['id'] ?>" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 shadow-sm">
                <i class="ph ph-calendar-plus"></i> Tạo Booking
            </a>
        </div>
    </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Price Card -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá cơ bản</p>
                        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= formatPrice($tour['base_price'] ?? 0) ?></h3>
                    </div>
                    <div class="d-flex align-items-center justify-content-center text-primary border border-primary-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--primary-subtle);">
                        <i class="ph ph-currency-circle-dollar"></i>
                    </div>
                </div>
            </div>

            <!-- Departures Card -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Lịch khởi hành</p>
                        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= count($departures) ?> <span style="font-size: 0.9rem; font-weight: 500;">ngày</span></h3>
                    </div>
                    <div class="d-flex align-items-center justify-content-center text-success border border-success-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--success-subtle);">
                        <i class="ph ph-calendar-blank"></i>
                    </div>
                </div>
            </div>

            <!-- Duration Card -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Thời lượng</p>
                        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;"><?= count($itinerarySchedule) ?> <span style="font-size: 0.9rem; font-weight: 500;">ngày</span></h3>
                    </div>
                    <div class="d-flex align-items-center justify-content-center text-warning border border-warning-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--warning-subtle);">
                        <i class="ph ph-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Rating Card -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card-premium p-3 d-flex align-items-center justify-content-between card-stat">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Đánh giá</p>
                        <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.5rem; letter-spacing: -0.5px;">4.5 <i class="ph-fill ph-star text-warning" style="font-size: 1rem;"></i></h3>
                    </div>
                    <div class="d-flex align-items-center justify-content-center text-info border border-info-subtle" style="width: 32px; height: 32px; border-radius: 8px; font-size: 1rem; background: var(--info-subtle);">
                        <i class="ph ph-star"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Description Card -->
                <div class="card card-premium mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ph-fill ph-file-text text-primary"></i>
                            Mô tả chi tiết
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tour['description'])): ?>
                            <div class="description-content text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($tour['description'])) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="ph ph-file-x fa-3x mb-3 op-2"></i>
                                <h6>Chưa có mô tả</h6>
                                <p class="mb-0 small">Mô tả chi tiết cho tour này chưa được cập nhật.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Itinerary Card -->
                <div class="card card-premium mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ph-fill ph-map-trifold text-success"></i>
                            Lịch trình Tour
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($itinerarySchedule)): ?>
                            <div class="itinerary-timeline position-relative ps-5 ms-2" style="border-left: 2px dashed #cbd5e1;">
                                <?php foreach ($itinerarySchedule as $index => $item): ?>
                                    <div class="timeline-item position-relative mb-4 pb-2">
                                        <div class="timeline-dot position-absolute bg-success shadow-sm rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; left: -63px; top: -2px; z-index: 1; font-size: 0.8rem; font-weight: bold; border: 4px solid #fff;">
                                            <?= $index + 1 ?>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 1rem;">
                                                    <?= htmlspecialchars($item['title'] ?? 'Lịch trình ngày ' . ($index + 1)) ?>
                                                </h6>
                                                <button class="btn btn-xs p-0 text-muted itinerary-toggle-btn shadow-none border-0" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#itinerary-collapse-<?= $index ?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="itinerary-collapse-<?= $index ?>">
                                                    <i class="ph ph-caret-down fs-5"></i>
                                                </button>
                                            </div>
                                            <div class="collapse" id="itinerary-collapse-<?= $index ?>">
                                                <div class="itinerary-description-wrapper pt-2">
                                                    <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6; text-align: justify;">
                                                        <?= nl2br(htmlspecialchars($item['description'] ?? '')) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="ph ph-map-pin fa-3x mb-3 op-2"></i>
                                <h6>Chưa có lịch trình</h6>
                                <p class="mb-0 small">Lịch trình chi tiết cho tour này chưa được cập nhật.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Departures Card -->
                <div class="card card-premium mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ph-fill ph-calendar text-info"></i>
                            Lịch khởi hành
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($departures)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <tr>
                                            <th class="ps-3 border-0">Ngày khởi hành</th>
                                            <th class="border-0">Trạng thái</th>
                                            <th class="border-0 text-end pe-3">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($departures as $departure): ?>
                                            <tr>
                                                <td class="ps-3 fw-semibold text-dark">
                                                    <i class="ph ph-calendar-check text-muted me-2"></i>
                                                    <?= date('d/m/Y', strtotime($departure['departure_date'])) ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $departure['status'] ?? 'unknown';
                                                    $badgeClass = $status === 'open' ? 'success' : ($status === 'full' ? 'danger' : 'warning');
                                                    ?>
                                                    <span class="badge bg-<?= $badgeClass ?>-subtle text-<?= $badgeClass ?> fw-bold">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end pe-3">
                                                    <button class="btn btn-sm btn-light border p-1 rounded d-inline-flex align-items-center justify-content-center" title="Chi tiết">
                                                        <i class="ph ph-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="ph ph-calendar-x fa-3x mb-3 op-2"></i>
                                <h6>Chưa có lịch khởi hành</h6>
                                <p class="mb-0 small">Hiện chưa có lịch khởi hành nào cho tour này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tour Policies Card -->
                <div class="card card-premium mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ph-fill ph-shield-check text-success"></i>
                            Chính sách áp dụng
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($policies)): ?>
                            <div class="row g-3">
                                <?php foreach ($policies as $policy): ?>
                                    <div class="col-md-6">
                                        <div class="border border-light-subtle rounded p-3 h-100 bg-light-subtle shadow-sm transition-all hover-translate-y">
                                            <h6 class="mb-2 text-primary fw-bold d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                                <i class="ph ph-check-circle"></i>
                                                <?= htmlspecialchars($policy['name']) ?>
                                            </h6>
                                            <?php if (!empty($policy['description'])): ?>
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($policy['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="ph ph-shield-slash fa-3x mb-3 op-2"></i>
                                <h6>Chưa có chính sách</h6>
                                <p class="mb-0 small">Tour này chưa được gán chính sách nào.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gallery Card -->
                <div class="card card-premium mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                                <i class="ph-fill ph-images text-primary"></i>
                                Thư viện ảnh
                                <span class="badge bg-light text-muted border fw-medium ms-1" style="font-size: 0.7rem;"><?= count($galleryUrls) ?> ảnh</span>
                            </h5>
                            <?php if (count($galleryUrls) > 6): ?>
                                <button class="btn btn-xs bg-white text-primary border shadow-sm rounded-pill px-3 d-flex align-items-center gap-2" style="font-size: 0.75rem;" onclick="openLightbox(0)">
                                    <i class="ph ph-magnifying-glass-plus"></i> Xem tất cả
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($galleryUrls)): ?>
                            <div class="row g-3">
                                <?php
                                $displayImages = array_slice($galleryUrls, 0, 6);
                                foreach ($displayImages as $index => $url):
                                    $isLast = ($index === 5 && count($galleryUrls) > 6);
                                ?>
                                    <div class="col-6 col-md-4">
                                        <div class="gallery-item-wrapper position-relative overflow-hidden rounded shadow-sm border" style="cursor: pointer; aspect-ratio: 4/3;" onclick="openLightbox(<?= $index ?>)">
                                            <img src="<?= $url ?>" alt="Tour Gallery Image <?= $index + 1 ?>" class="img-fluid w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                                            <div class="gallery-overlay d-flex align-items-center justify-content-center">
                                                <div class="text-white text-center">
                                                    <i class="ph ph-magnifying-glass-plus mb-1" style="font-size: 1.5rem;"></i>
                                                    <div class="small fw-medium">Xem ảnh</div>
                                                </div>
                                            </div>
                                            <?php if ($isLast): ?>
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-60 d-flex align-items-center justify-content-center" style="z-index: 2;">
                                                    <div class="text-center text-white">
                                                        <span class="h4 fw-bold mb-0 d-block">+<?= count($galleryUrls) - 6 ?></span>
                                                        <span class="small fw-medium">Xem thêm</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5 rounded bg-light-subtle border border-dashed">
                                <i class="ph ph-images-square fa-4x mb-3 op-2"></i>
                                <h6 class="fw-bold">Thư viện trống</h6>
                                <p class="text-muted mb-4 small">Chưa có hình ảnh nào được tải lên cho tour này.</p>
                                <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= $tour['id'] ?>#gallery" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill">
                                    <i class="ph ph-plus-circle me-1"></i> Thêm hình ảnh
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <!-- Main Image Widget -->
                <div class="card card-premium mb-4 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white border-bottom border-light py-3">
                        <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ph-fill ph-image text-primary"></i>
                            Ảnh đại diện
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="main-image-container gallery-item-wrapper position-relative" style="cursor: pointer; aspect-ratio: 4/3;" onclick="openMainImageLightbox(this)">
                            <img src="<?= $mainImage ?>" alt="Tour Main Image" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                            <div class="gallery-overlay d-flex align-items-center justify-content-center">
                                <div class="text-white text-center">
                                    <i class="ph ph-magnifying-glass-plus mb-1" style="font-size: 1.5rem;"></i>
                                    <div class="small fw-medium">Xem ảnh</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Card -->
                <?php if (!empty($tour['supplier_id'])): ?>
                    <div class="card card-premium mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom border-light py-3">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2" style="font-size: 1rem;">
                                <i class="ph-fill ph-buildings text-info"></i>
                                Nhà cung cấp
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $supplierModel = new Supplier();
                            $supplier = $supplierModel->find('*', 'id = :id', ['id' => $tour['supplier_id']]);
                            ?>
                            <?php if ($supplier): ?>
                                <div class="supplier-info">
                                    <h6 class="mb-3 text-primary fw-bold d-flex align-items-center gap-2">
                                        <i class="ph-fill ph-hand-shake"></i>
                                        <?= htmlspecialchars($supplier['name']) ?>
                                    </h6>

                                    <div class="vstack gap-2" style="font-size: 0.9rem;">
                                        <?php if (!empty($supplier['type'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ph ph-tag text-muted"></i>
                                                <span class="text-muted">Loại:</span>
                                                <span class="badge bg-info-subtle text-info fw-bold"><?= htmlspecialchars($supplier['type']) ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($supplier['contact_person'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ph ph-user text-muted"></i>
                                                <span class="text-muted">Liên hệ:</span>
                                                <span class="text-dark fw-medium"><?= htmlspecialchars($supplier['contact_person']) ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($supplier['phone'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ph ph-phone text-muted"></i>
                                                <span class="text-muted">SĐT:</span>
                                                <a href="tel:<?= $supplier['phone'] ?>" class="text-primary text-decoration-none fw-medium"><?= htmlspecialchars($supplier['phone']) ?></a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($supplier['email'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ph ph-envelope text-muted"></i>
                                                <span class="text-muted">Email:</span>
                                                <a href="mailto:<?= $supplier['email'] ?>" class="text-primary text-decoration-none fw-medium"><?= htmlspecialchars($supplier['email']) ?></a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($supplier['address'])): ?>
                                            <div class="d-flex align-items-start gap-2 pt-1 border-top border-light mt-1">
                                                <i class="ph ph-map-pin text-muted mt-1"></i>
                                                <div>
                                                    <div class="text-muted small">Địa chỉ:</div>
                                                    <span class="text-dark" style="font-size: 0.85rem;"><?= htmlspecialchars($supplier['address']) ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="ph ph-warning-circle fa-2x mb-2 op-3"></i>
                                    <p class="mb-0 small">Không tìm thấy thông tin</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div> <!-- /row -->

        <!-- Lightbox Modal -->
        <div id="galleryLightbox" class="lightbox-overlay">
            <div class="lightbox-content">
                <button class="lightbox-close" onclick="closeLightbox()"><i class="ph ph-x"></i></button>

                <div class="lightbox-main">
                    <button class="lightbox-nav prev" onclick="moveSlide(-1)"><i class="ph ph-caret-left"></i></button>
                    <img id="lightboxImage" src="" alt="Gallery Image">
                    <button class="lightbox-nav next" onclick="moveSlide(1)"><i class="ph ph-caret-right"></i></button>
                </div>

                <div class="lightbox-caption">
                    <span id="currentIndex" class="fw-bold text-white">1</span> / <span id="totalImages">0</span>
                </div>

                <!-- Thumbnails Strip -->
                <div class="lightbox-thumbnails">
                    <div class="thumbnails-track" id="thumbnailsTrack">
                        <!-- Thumbnails injected via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /content -->
</main>

<style>
    /* Premium Detail Page Styles */
    .content {
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .card-premium {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .hover-translate-y:hover {
        transform: translateY(-3px);
    }

    /* Timeline Styles */
    .itinerary-timeline .timeline-item:last-child {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Gallery Styles */
    .gallery-item-wrapper {
        transition: transform 0.3s ease;
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .gallery-item-wrapper:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-item-wrapper:hover img {
        transform: scale(1.08);
    }

    /* Lightbox Styles */
    .lightbox-overlay {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(8px);
    }

    .lightbox-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .lightbox-main {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 75%;
        padding: 0 60px;
    }

    #lightboxImage {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transition: opacity 0.3s ease;
    }

    .lightbox-close {
        position: absolute;
        top: 30px;
        right: 30px;
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        font-size: 24px;
        cursor: pointer;
        z-index: 10001;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .lightbox-close:hover {
        background: #ef4444;
        color: #fff;
        transform: scale(1.1) rotate(90deg);
    }

    /* Itinerary Collapse Styles */
    .itinerary-toggle-btn {
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 4px;
        border-radius: 4px;
        color: #94a3b8;
    }
    .itinerary-toggle-btn:hover {
        background-color: #f1f5f9;
        color: var(--primary-color);
    }
    .itinerary-toggle-btn i {
        transition: transform 0.3s ease;
    }
    .itinerary-toggle-btn[aria-expanded="false"] i {
        transform: rotate(180deg);
    }
    .itinerary-toggle-btn:focus, .itinerary-toggle-btn:active {
        text-decoration: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .itinerary-description-wrapper {
        border-left: 2px solid #f1f5f9;
        padding-left: 15px;
        margin-left: 5px;
    }

    .lightbox-nav {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
    }

    .lightbox-nav:hover {
        background: var(--primary-color);
        transform: scale(1.1);
    }

    .lightbox-nav.prev { left: 30px; }
    .lightbox-nav.next { right: 30px; }

    .lightbox-caption {
        color: #94a3b8;
        margin-top: 20px;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    .lightbox-thumbnails {
        height: 100px;
        width: 100%;
        margin-top: 30px;
        overflow-x: auto;
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }

    .thumbnails-track {
        display: flex;
        gap: 12px;
        padding: 0 40px;
    }

    .lightbox-thumb {
        height: 70px;
        width: 100px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        opacity: 0.4;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .lightbox-thumb.active {
        opacity: 1;
        border-color: var(--primary-color);
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }

    .lightbox-thumb:hover {
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .lightbox-main { padding: 0 20px; }
        .lightbox-nav { width: 40px; height: 40px; font-size: 18px; }
        .lightbox-thumbnails { display: none; }
    }
</style>

                <script>
                    // Prepare Gallery Data
                    const galleryImages = <?= json_encode($galleryUrls) ?>;

                    let currentImageIndex = 0;

                    function openLightbox(index) {
                        if (galleryImages.length === 0) return;
                        currentImageIndex = index;

                        const lightbox = document.getElementById('galleryLightbox');
                        const totalImages = document.getElementById('totalImages');
                        const thumbnailsTrack = document.getElementById('thumbnailsTrack');

                        // Render Thumbnails
                        thumbnailsTrack.innerHTML = '';
                        galleryImages.forEach((src, idx) => {
                            const thumb = document.createElement('img');
                            thumb.src = src;
                            thumb.className = `lightbox-thumb ${idx === index ? 'active' : ''}`;
                            thumb.onclick = () => showImage(idx);
                            thumbnailsTrack.appendChild(thumb);
                        });

                        totalImages.innerText = galleryImages.length;
                        showImage(index);

                        lightbox.style.display = 'block';
                        document.body.style.overflow = 'hidden'; // Prevent background scrolling

                        // Keyboard Nav
                        document.addEventListener('keydown', handleKeyboardNav);
                    }

                    function closeLightbox() {
                        const lightbox = document.getElementById('galleryLightbox');
                        if (lightbox) lightbox.style.display = 'none';
                        document.body.style.overflow = '';
                        document.removeEventListener('keydown', handleKeyboardNav);
                    }

                    function showImage(index) {
                        if (index >= galleryImages.length) index = 0;
                        if (index < 0) index = galleryImages.length - 1;

                        currentImageIndex = index;

                        const imgEnd = document.getElementById('lightboxImage');
                        const currentIndexEl = document.getElementById('currentIndex');

                        // Update Image
                        imgEnd.style.opacity = '0';
                        setTimeout(() => {
                            imgEnd.src = galleryImages[index];
                            imgEnd.style.opacity = '1';
                        }, 200);

                        // Update Counter
                        currentIndexEl.innerText = index + 1;

                        // Update Thumbnails
                        document.querySelectorAll('.lightbox-thumb').forEach((thumb, idx) => {
                            if (idx === index) {
                                thumb.classList.add('active');
                                thumb.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'nearest',
                                    inline: 'center'
                                });
                            } else {
                                thumb.classList.remove('active');
                            }
                        });
                    }

                    function moveSlide(step) {
                        showImage(currentImageIndex + step);
                    }

                    function handleKeyboardNav(e) {
                        if (e.key === 'Escape') closeLightbox();
                        if (e.key === 'ArrowRight') moveSlide(1);
                        if (e.key === 'ArrowLeft') moveSlide(-1);
                    }

                    // Close on click outside
                    const lightboxEl = document.getElementById('galleryLightbox');
                    if (lightboxEl) {
                        lightboxEl.addEventListener('click', (e) => {
                            if (e.target.id === 'galleryLightbox' || e.target.classList.contains('lightbox-content')) {
                                closeLightbox();
                            }
                        });
                    }

                    function openMainImageLightbox(element) {
                        const img = element.querySelector('img');
                        if (!img) return;

                        const src = img.getAttribute('src');

                        // Check against galleryImages variable defined above
                        if (!galleryImages || !galleryImages.length) {
                            console.warn('No gallery data available');
                            return;
                        }

                        // Try to find index
                        let index = -1;

                        for (let i = 0; i < galleryImages.length; i++) {
                            // Compare src with gallery image URL. 
                            // Using includes to be safe about relative/absolute path differences
                            if (src.includes(galleryImages[i]) || galleryImages[i].includes(src)) {
                                index = i;
                                break;
                            }
                        }

                        if (index !== -1) {
                            openLightbox(index);
                        } else {
                            // Fallback: If main image is not in gallery list, just open the first gallery image
                            // This allows user to enter the lightbox view.
                            openLightbox(0);
                        }
                    }

                    function showAllImages() {
                        const galleryItems = document.querySelectorAll('.gallery-item-wrapper');
                        galleryItems.forEach(item => item.style.display = 'block');
                    }
                </script>

                <?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>