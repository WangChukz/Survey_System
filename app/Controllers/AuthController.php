<?php

namespace App\Controllers;

use Core\Controller;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập Admin
     */
    public function showLogin(): void
    {
        // Nếu đã đăng nhập, chuyển hướng về admin dashboard
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            $this->redirect(BASE_URL . '/admin');
        }

        $this->render('admin/login', [
            'maxWidth' => 'max-w-md',
            'error' => $_SESSION['login_error'] ?? null
        ], 'layout'); // Dùng layout gốc để căn giữa màn hình, không hiện sidebar admin

        // Xóa thông báo lỗi sau khi hiển thị
        unset($_SESSION['login_error']);
    }

    /**
     * Xử lý đăng nhập
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Lấy thông tin Admin từ biến môi trường (.env)
            $adminUser = getenv('ADMIN_USER') ?: 'admin';
            $adminPass = getenv('ADMIN_PASS') ?: '123456'; 

            if ($username === $adminUser && $password === $adminPass) {
                // Đăng nhập thành công
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                
                $this->redirect(BASE_URL . '/admin');
            } else {
                // Đăng nhập thất bại
                $_SESSION['login_error'] = 'Tài khoản hoặc mật khẩu không chính xác.';
                $this->redirect(BASE_URL . '/admin/login');
            }
        }
    }

    /**
     * Đăng xuất
     */
    public function logout(): void
    {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_username']);
        
        $this->redirect(BASE_URL . '/admin/login');
    }
}
