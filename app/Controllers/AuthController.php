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

        // Kiểm tra xem có đang bị khóa không
        $error = $_SESSION['login_error'] ?? null;
        if (isset($_SESSION['lockout_until']) && time() < $_SESSION['lockout_until']) {
            $remaining = ceil(($_SESSION['lockout_until'] - time()) / 60);
            $error = "Tài khoản tạm thời bị khóa. Vui lòng thử lại sau $remaining phút.";
        }

        $this->render('admin/login', [
            'maxWidth' => 'max-w-md',
            'error' => $error
        ], 'layout');

        // Xóa thông báo lỗi sau khi hiển thị (trừ khi đang bị khóa)
        if (!isset($_SESSION['lockout_until']) || time() >= $_SESSION['lockout_until']) {
            unset($_SESSION['login_error']);
        }
    }

    /**
     * Xử lý đăng nhập
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem có đang bị khóa không
            if (isset($_SESSION['lockout_until']) && time() < $_SESSION['lockout_until']) {
                $this->redirect(BASE_URL . '/admin/login');
                return;
            }

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $adminUser = getenv('ADMIN_USER') ?: 'admin';
            $adminPassHash = getenv('ADMIN_PASS') ?: '';

            if ($username === $adminUser && password_verify($password, $adminPassHash)) {
                // Đăng nhập thành công -> Reset số lần sai
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_until']);

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                
                $this->redirect(BASE_URL . '/admin');
            } else {
                // Đăng nhập thất bại -> Tăng số lần sai
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['lockout_until'] = time() + 300; // Khóa 5 phút
                    $_SESSION['login_error'] = 'Bạn đã nhập sai quá 5 lần. Tài khoản bị khóa trong 5 phút.';
                } else {
                    $remaining = 5 - $_SESSION['login_attempts'];
                    $_SESSION['login_error'] = "Tài khoản hoặc mật khẩu không chính xác. Còn $remaining lần thử.";
                }
                
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
