<?php
require_once 'models/Page.php';

class ClientPageController
{
    public function about()
    {
        $pageModel = new Page();
        $pageData = $pageModel->getBySlug('about');
        require_once PATH_VIEW_CLIENT . 'pages/about.php';
    }

    public function contact()
    {
        $pageModel = new Page();
        $pageData = $pageModel->getBySlug('contact');
        require_once PATH_VIEW_CLIENT . 'pages/contact.php';
    }

    public function contactSubmit()
    {
        // Logic to handle contact form submission
        $_SESSION['success'] = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';
        header('Location: ' . BASE_URL . '?action=contact');
        exit;
    }
}
