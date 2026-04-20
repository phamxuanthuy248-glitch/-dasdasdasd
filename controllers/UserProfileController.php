<?php

class UserProfileController {

    public function editProfile() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $user = new User();
        $userData = $user->getUserById($_SESSION['user']['id']);
        require 'views/user/profile/edit.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?action=login");
                exit;
            }

            $userId = $_SESSION['user']['id'];
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $avatar = null;

            // Validation
            if (empty($email)) {
                $_SESSION['error'] = "Email không được để trống!";
                header("back");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Email không hợp lệ!";
                header("back");
                exit;
            }

            $user = new User();

            // Upload avatar nếu có
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
                if (!$avatar) {
                    $_SESSION['error'] = "Lỗi upload avatar!";
                    header("back");
                    exit;
                }
            }

            if ($user->updateProfile($userId, $email, $phone, $address, $avatar)) {
                $_SESSION['user']['email'] = $email;
                $_SESSION['success'] = "Cập nhật hồ sơ thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi cập nhật hồ sơ!";
            }

            header("Location: index.php?action=editProfile");
            exit;
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?action=login");
                exit;
            }

            $oldPassword = $_POST['old_password'] ?? null;
            $newPassword = $_POST['new_password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;

            // Validation
            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("back");
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Mật khẩu mới không trùng khớp!";
                header("back");
                exit;
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Mật khẩu phải ít nhất 6 ký tự!";
                header("back");
                exit;
            }

            $user = new User();
            if ($user->changePassword($_SESSION['user']['id'], $oldPassword, $newPassword)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "Mật khẩu cũ không chính xác!";
            }

            header("Location: index.php?action=editProfile");
            exit;
        }
    }

    public function viewProfile() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $user = new User();
        $userData = $user->getUserById($_SESSION['user']['id']);
        
        $booking = new Booking();
        $userBookings = $booking->getBookingsByUser($_SESSION['user']['id']);
        
        require 'views/user/profile/view.php';
    }

    public function myReviews() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $review = new Review();
        $userReviews = $review->getReviewsByUser($_SESSION['user']['id']);
        
        require 'views/user/profile/my-reviews.php';
    }

    public function myPayments() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $payment = new Payment();
        $userPayments = $payment->getPaymentsByUser($_SESSION['user']['id']);
        
        require 'views/user/profile/my-payments.php';
    }

    public function downloadInvoice() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $paymentId = $_GET['id'] ?? null;
        $payment = new Payment();
        $paymentData = $payment->getPaymentById($paymentId);

        if (!$paymentData || $paymentData[0]['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Không có quyền tải hóa đơn này!";
            header("back");
            exit;
        }

        // TODO: Generate PDF invoice
        // For now, just show a message
        $_SESSION['info'] = "Tính năng tải hóa đơn đang được phát triển!";
        header("back");
        exit;
    }

    private function uploadAvatar($file) {
        $uploadDir = 'assets/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = "Chỉ hỗ trợ file ảnh (JPG, PNG, GIF)!";
            return null;
        }

        // Check file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File quá lớn (tối đa 2MB)!";
            return null;
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName;
        }
        return null;
    }
}
