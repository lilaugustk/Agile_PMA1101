<?php
require_once 'models/User.php';
require_once 'models/Booking.php';
require_once 'models/CustomerProfileModel.php';

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
        $userId = $_SESSION['user']['user_id'];
        $userModel = new User();
        $user = $userModel->getById($userId);
        
        $profileModel = new CustomerProfileModel();
        $profile = $profileModel->getByUserId($userId);
        
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

        $resUser = $userModel->updateUser($userId, $data);

        // Update detailed profile
        $profileModel = new CustomerProfileModel();
        $profileData = [
            'full_name'  => $fullName,
            'phone'      => $phone,
            'gender'     => $_POST['gender'] ?? null,
            'birth_date' => $_POST['birth_date'] ?? null,
            'id_card'    => $_POST['id_card'] ?? null,
            'address'    => $_POST['address'] ?? null,
        ];
        $resProfile = $profileModel->upsertProfile($userId, $profileData);

        if ($resUser || $resProfile) {
            // Update session
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['success'] = 'Cập nhật thông tin thành công!';
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại hoặc không có thay đổi nào.';
        }

        header('Location: ' . BASE_URL . '?action=profile');
        exit;
    }

    public function bookings()
    {
        $bookingModel = new Booking();
        $userId = $_SESSION['user']['user_id'];
        $bookings = $bookingModel->getByCustomerId($userId);

        // Ưu tiên hiển thị đơn có ngày đặt gần nhất trước.
        usort($bookings, function ($a, $b) {
            $aTime = strtotime($a['booking_date'] ?? '1970-01-01 00:00:00');
            $bTime = strtotime($b['booking_date'] ?? '1970-01-01 00:00:00');
            return $bTime <=> $aTime;
        });

        require_once PATH_VIEW_CLIENT . 'account/bookings.php';
    }
}
