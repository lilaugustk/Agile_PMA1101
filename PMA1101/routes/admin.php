<?php


// Include controllers
require_once 'controller/admin/AuthorController.php';
require_once 'controller/admin/DashboardController.php';
require_once 'controller/admin/TourController.php';
require_once 'controller/admin/BookingController.php';
require_once 'controller/admin/GuideController.php';
require_once 'controller/admin/ReportController.php';
require_once 'controller/admin/PolicyController.php';
require_once 'controller/admin/TourCategoryController.php';
require_once 'controller/admin/ItineraryController.php';
require_once 'controller/admin/TourLogController.php';
require_once 'controller/admin/TourAssignmentController.php';
require_once 'controller/admin/GuideWorkController.php';
require_once 'controller/admin/TourVehicleController.php';
require_once 'controller/admin/AvailableToursController.php'; // Add missing controller
require_once 'controller/admin/AdminBlogController.php';
require_once 'controller/admin/AdminTourReviewController.php';

require_once 'controller/admin/UserController.php';

$action = $_GET['action'] ?? '/';

match ($action) {
    // Dashboard
    '/'                                     => (new DashboardController)->index(),

    // Auth
    'login'                                 => (new AuthorController)->login(),
    'loginProcess'                          => (new AuthorController)->loginProcess(),
    'logout'                                => (new AuthorController)->logout(),
    'account'                               => (new AuthorController)->accountInfo(),
    'account/update-profile'                => (new AuthorController)->updateProfile(), // AJAX
    'account/change-password'               => (new AuthorController)->changePassword(), // AJAX
    'account/update-avatar'                 => (new AuthorController)->updateAvatar(), // AJAX

    // Tours Management 
    'tours'                                 => (new TourController)->index(),
    'tours/create'                          => (new TourController)->create(),
    'tours/store'                           => (new TourController)->store(),
    'tours/edit'                            => (new TourController)->edit(),
    'tours/update'                          => (new TourController)->update(),
    'tours/clone'                           => (new TourController)->cloneTour(),
    'tours/delete'                          => (new TourController)->delete(),
    'tours/detail'                          => (new TourController)->detail(),
    'tours/toggle-status'                   => (new TourController)->toggleStatus(),
    'tours/toggle-featured'                 => (new TourController)->toggleFeatured(),
    'tours/bulk-update-status'              => (new TourController)->bulkUpdateStatus(),
    'tours/bulk-delete'                     => (new TourController)->bulkDelete(),
    'tours/search'                          => (new TourController)->search(),
    'tours/by-status'                       => (new TourController)->getByStatus(),
    'tours/add-departure'                   => (new TourController)->addDeparture(),
    'tours/departures'                       => (new TourController)->listDepartures(),
    'tours/sync-departures'                 => (new TourController)->syncDepartures(),
    'tours/departure-resources'             => (new TourController)->manageDepartureResources(),
    'tours/save-departure-resources'         => (new TourController)->saveDepartureResources(),

    // Tour Category
    'tours_categories'                      => (new TourCategoryController)->index(),
    'tours_categories/create'               => (new TourCategoryController)->create(),
    'tours_categories/store'                => (new TourCategoryController)->store(),
    'tours_categories/edit'                 => (new TourCategoryController)->edit(),
    'tours_categories/update'               => (new TourCategoryController)->update(),
    'tours_categories/delete'               => (new TourCategoryController)->delete(),



    'tours/itineraries'                     => (new ItineraryController)->index(),
    'tours/itineraries/create'              => (new ItineraryController)->create(),
    'tours/itineraries/store'               => (new ItineraryController)->store(),
    'tours/itineraries/delete'              => (new ItineraryController)->delete(),


    // Tour Logs
    'tours_logs'                            => (new TourLogController)->index(),
    'tours_logs/create'                     => (new TourLogController)->create(),
    'tours_logs/store'                      => (new TourLogController)->store(),
    'tours_logs/edit'                       => (new TourLogController)->edit(),
    'tours_logs/update'                     => (new TourLogController)->update(),
    'tours_logs/delete'                     => (new TourLogController)->delete(),
    'tours_logs/detail'                     => (new TourLogController)->detail(),
    'tours_logs/tour_detail'                => (new TourLogController)->tourDetail(),
    'tours_logs/checkin'                    => (new TourLogController)->checkin(),
    'tours_logs/toggleCheckin'               => (new TourLogController)->toggleCheckin(), // AJAX
    'tours_logs/mark_request_handled'       => (new TourLogController)->markRequestHandled(), // AJAX

    // Bookings
    'bookings'                              => (new BookingController)->index(),
    'bookings/create'                       => (new BookingController)->create(),
    'bookings/store'                        => (new BookingController)->store(),
    'bookings/edit'                         => (new BookingController)->edit(),
    'bookings/update'                       => (new BookingController)->update(),
    'bookings/delete'                       => (new BookingController)->delete(),
    'bookings/detail'                       => (new BookingController)->detail(),
    'bookings/update-status'                => (new BookingController)->updateStatus(), // AJAX endpoint
    'bookings/add-companion'                => (new BookingController)->addCompanion(),
    'bookings/update-companion'             => (new BookingController)->updateCompanion(),
    'bookings/delete-companion'             => (new BookingController)->deleteCompanion(),
    'bookings/get-departures'               => (new BookingController)->getDeparturesByTour(), // AJAX endpoint
    'bookings/checkin'                      => (new BookingController)->checkin(),
    'bookings/update-checkin'               => (new BookingController)->updateCheckin(), // AJAX endpoint
    'bookings/bulk-checkin'                 => (new BookingController)->bulkCheckin(), // AJAX endpoint
    'bookings/print-group-list'             => (new BookingController)->printGroupList(),
    'bookings/allocate-rooms'               => (new BookingController)->allocateRooms(),
    'bookings/save-room-allocation'         => (new BookingController)->saveRoomAllocation(), // AJAX



    // Guides
    'guides'                                => (new GuideController)->index(),
    'guides/create'                         => (new GuideController)->create(),
    'guides/store'                          => (new GuideController)->store(),
    'guides/detail'                         => (new GuideController)->detail(),
    'guides/edit'                           => (new GuideController)->edit(),
    'guides/update'                         => (new GuideController)->update(),
    'guides/delete'                         => (new GuideController)->delete(),

    // Tour Assignments (Guide-Tour management)
    'guides/tour-assignments'               => (new TourAssignmentController)->index(),
    'guides/assign-tour'                    => (new TourAssignmentController)->assign(),
    'guides/remove-tour'                    => (new TourAssignmentController)->remove(),
    'guides/get-tours'                      => (new TourAssignmentController)->getGuideTours(), // AJAX

    // Guides Work
    'guide/schedule'                        => (new GuideWorkController)->schedule(),
    'guide/tourDetail'                      => (new GuideWorkController)->tourDetail(),
    'guide/cancelAssignment'                => (new GuideWorkController)->cancelAssignment(), // AJAX
    'guide/updateStatus'                    => (new GuideWorkController)->updateStatus(), // AJAX

    // Available Tours (moved from guides/)
    'available-tours'                       => (new AvailableToursController)->index(),
    'available-tours/assign-guide'          => (new AvailableToursController)->assignGuide(), // AJAX
    'available-tours/claim-tour'            => (new AvailableToursController)->claimTour(), // AJAX

    // Legacy routes for backward compatibility (will be removed later)
    'guides/available-tours'                => (new AvailableToursController)->index(),
    'guides/admin-assign-guide'             => (new AvailableToursController)->assignGuide(), // AJAX
    'guides/claim-tour'                     => (new AvailableToursController)->claimTour(), // AJAX

    'guides/tour-bookings'                  => (new TourAssignmentController)->tourBookings(),
    'guides/accept-booking'                 => (new TourAssignmentController)->acceptBooking(), // AJAX
    'guides/remove-assignment'              => (new TourAssignmentController)->removeAssignmentByAdmin(), // AJAX


    // Users
    'users'                                 => (new UserController)->index(),
    'users/create'                          => (new UserController)->create(),
    'users/store'                           => (new UserController)->store(),
    'users/edit'                            => (new UserController)->edit(),
    'users/update'                          => (new UserController)->update(),
    'users/delete'                          => (new UserController)->delete(),
    'users/detail'                          => (new UserController)->detail(),

    // Blogs
    'blogs'                                 => (new AdminBlogController)->index(),
    'blogs/create'                          => (new AdminBlogController)->create(),
    'blogs/store'                           => (new AdminBlogController)->store(),
    'blogs/edit'                            => (new AdminBlogController)->edit(),
    'blogs/update'                          => (new AdminBlogController)->update(),
    'blogs/delete'                          => (new AdminBlogController)->delete(),

    // Reviews
    'tour_reviews'                          => (new AdminTourReviewController)->index(),
    'tour_reviews/updateStatus'              => (new AdminTourReviewController)->updateStatus(), // AJAX
    'tour_reviews/delete'                   => (new AdminTourReviewController)->delete(),

    // Reports
    'reports'              => (new ReportController)->index(),
    'reports/financial'    => (new ReportController)->financial(),
    'reports/bookings'     => (new ReportController)->bookings(),
    'reports/feedback'     => (new ReportController)->feedback(),
    'reports/debt'         => (new ReportController)->debt(),

    'reports/dashboard'    => (new ReportController)->dashboard(),

    // Chính sách 
    'policies'             => (new PolicyController)->index(),
    'policies/create'             => (new PolicyController)->create(),
    'policies/store'             => (new PolicyController)->store(),
    'policies/edit'             => (new PolicyController)->edit(),
    'policies/update'             => (new PolicyController)->update(),
    'policies/delete'             => (new PolicyController)->delete(),


    // Quản lý xe
    'tour_vehicles'             => (new TourVehicleController)->index(),
    'tour_vehicles/create'      => (new TourVehicleController)->create(),
    'tour_vehicles/store'       => (new TourVehicleController)->store(),
    'tour_vehicles/edit'        => (new TourVehicleController)->edit(),
    'tour_vehicles/update'      => (new TourVehicleController)->update(),
    'tour_vehicles/delete'      => (new TourVehicleController)->delete(),
};
