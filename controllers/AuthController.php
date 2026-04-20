<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function handleRegister() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $userModel = new User();
        $userModel->create($name, $email, $password);

        header("Location: index.php?action=login");
        exit;
    }

    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function handleLogin() {

        // ✅ đảm bảo có session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            // ✅ lưu session
            $_SESSION['user'] = $user;

            // ✅ phân quyền
            if ($user['role'] === 'admin') {
                header("Location: index.php?action=dashboard");
            } else {
                header("Location: index.php?action=home");
            }
            exit;

        } else {
            // ❌ KHÔNG echo trực tiếp
            $_SESSION['error'] = "Sai email hoặc mật khẩu!";
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function logout() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }
}