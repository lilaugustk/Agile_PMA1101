<?php
require_once 'models/User.php';

class ClientAuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        // If already logged in, redirect
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);
        
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ email và mật khẩu!';
            header('Location: ' . BASE_URL . '?action=login');
            exit;
        }

        $user = $this->userModel->getByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];
            
            $_SESSION['success'] = 'Đăng nhập thành công!';
            
            // Redirect based on role or back to previous page
            if ($user['role'] === 'admin') {
                header('Location: ' . BASE_URL . '?mode=admin');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit;
        } else {
            $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác!';
            header('Location: ' . BASE_URL . '?action=login');
            exit;
        }
    }

    public function showRegister()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=register');
            exit;
        }

        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validation
        if (empty($fullName) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc!';
            header('Location: ' . BASE_URL . '?action=register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: ' . BASE_URL . '?action=register');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Email này đã được sử dụng!';
            header('Location: ' . BASE_URL . '?action=register');
            exit;
        }

        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'customer' // default role for frontend registration
        ];

        try {
            $userId = $this->userModel->create($data);
            if ($userId) {
                $_SESSION['success'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.';
                header('Location: ' . BASE_URL . '?action=login');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
                header('Location: ' . BASE_URL . '?action=register');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '?action=register');
            exit;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: ' . BASE_URL . '?action=login');
        exit;
    }
}
