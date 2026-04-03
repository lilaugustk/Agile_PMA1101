<?php
require_once 'models/User.php';
require_once 'models/Booking.php';

class ClientAccountController
{
    public function __construct()
    {
        // Must be logged in
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?action=login');
            exit;
        }
    }

    public function profile()
    {
        $userModel = new User();
        $user = $userModel->getById($_SESSION['user']['user_id']);
        
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);
        
        require_once PATH_VIEW_CLIENT . 'account/profile.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=profile');
            exit;
        }

        $userId = $_SESSION['user']['user_id'];
        $fullName = $_POST['full_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        
        if (empty($fullName)) {
            $_SESSION['error'] = 'Họ tên không được để trống!';
            header('Location: ' . BASE_URL . '?action=profile');
            exit;
        }

        $userModel = new User();
        $data = [
            'full_name' => $fullName,
            'phone' => $phone
        ];

        // Only update password if provided
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                header('Location: ' . BASE_URL . '?action=profile');
                exit;
            }
            $data['password'] = $_POST['password'];
        }

        if ($userModel->updateUser($userId, $data)) {
            // Update session
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['success'] = 'Cập nhật thông tin thành công!';
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại, vui lòng thử lại sau.';
        }

        header('Location: ' . BASE_URL . '?action=profile');
        exit;
    }

    public function bookings()
    {
        $bookingModel = new Booking();
        $userId = $_SESSION['user']['user_id'];
        $bookings = $bookingModel->getByCustomerId($userId);

        require_once PATH_VIEW_CLIENT . 'account/bookings.php';
    }
}
