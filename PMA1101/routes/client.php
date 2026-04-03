<?php
$action = $_GET['action'] ?? '/';

switch ($action) {
    case '/':
        require_once 'controller/client/TourController.php';
        $controller = new ClientTourController();
        $controller->home();
        break;
        
    case 'tours':
        require_once 'controller/client/TourController.php';
        $controller = new ClientTourController();
        $controller->index();
        break;
        
    case 'login':
        require_once 'controller/client/AuthController.php';
        $controller = new ClientAuthController();
        $controller->showLogin();
        break;

    case 'login-submit':
        require_once 'controller/client/AuthController.php';
        $controller = new ClientAuthController();
        $controller->login();
        break;

    case 'register':
        require_once 'controller/client/AuthController.php';
        $controller = new ClientAuthController();
        $controller->showRegister();
        break;

    case 'register-submit':
        require_once 'controller/client/AuthController.php';
        $controller = new ClientAuthController();
        $controller->register();
        break;

    case 'logout':
        require_once 'controller/client/AuthController.php';
        $controller = new ClientAuthController();
        $controller->logout();
        break;

    case 'profile':
        require_once 'controller/client/AccountController.php';
        $controller = new ClientAccountController();
        $controller->profile();
        break;

    case 'profile-update':
        require_once 'controller/client/AccountController.php';
        $controller = new ClientAccountController();
        $controller->updateProfile();
        break;

    case 'my-bookings':
        require_once 'controller/client/AccountController.php';
        $controller = new ClientAccountController();
        $controller->bookings();
        break;

    case 'about':
        require_once 'controller/client/PageController.php';
        $controller = new ClientPageController();
        $controller->about();
        break;

    case 'contact':
        require_once 'controller/client/PageController.php';
        $controller = new ClientPageController();
        $controller->contact();
        break;

    case 'contact-submit':
        require_once 'controller/client/PageController.php';
        $controller = new ClientPageController();
        $controller->contactSubmit();
        break;

    case 'tour-detail':
        require_once 'controller/client/TourController.php';
        $controller = new ClientTourController();
        $controller->detail();
        break;

    case 'booking-create':
        require_once 'controller/client/BookingController.php';
        $controller = new ClientBookingController();
        $controller->create();
        break;
        
    case 'booking-store':
        require_once 'controller/client/BookingController.php';
        $controller = new ClientBookingController();
        $controller->store();
        break;

    case 'booking-payment':
        require_once 'controller/client/BookingController.php';
        $controller = new ClientBookingController();
        $controller->payment();
        break;
        
    case 'booking-success':
        require_once 'controller/client/BookingController.php';
        $controller = new ClientBookingController();
        $controller->success();
        break;
        
    default:
        // 404 placeholder
        echo "<h1>404 Not Found</h1>";
        break;
}
