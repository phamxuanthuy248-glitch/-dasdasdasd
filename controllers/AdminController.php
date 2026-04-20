<?php

class AdminController {

    public function __construct() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=home");
            exit;
        }
    }

    // ===== DASHBOARD =====
    public function dashboard() {
        $tour = new Tour();
        $booking = new Booking();
        $payment = new Payment();
        $user = new User();

        $totalTours = $tour->countTours();
        $totalBookings = $booking->countBookings();
        $totalUsers = $user->countUsers();
        $totalRevenue = $payment->getTotalRevenue();

        $recentBookings = $booking->getAllBookings(5, 0);
        $recentPayments = $payment->getAllPayments(5, 0);

        require 'views/admin/dashboard.php';
    }

    // ===== TOURS MANAGEMENT =====
    public function manageTours() {
        $tour = new Tour();
        $tours = $tour->getAllTours();
        require 'views/admin/tours/list.php';
    }

    public function addTourForm() {
        $category = new Category();
        $categories = $category->getAllCategories();
        $tourData = null;
        require 'views/admin/tours/form.php';
    }

    public function editTourForm($tourId) {
        $tour = new Tour();
        $category = new Category();
        $tourData = $tour->getTourById($tourId);
        $categories = $category->getAllCategories();

        if (!$tourData) {
            $_SESSION['error'] = "Tour không tồn tại!";
            header("Location: index.php?action=manageTours");
            exit;
        }

        require 'views/admin/tours/form.php';
    }

    public function saveTour() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tourId = $_POST['tour_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $destination = trim($_POST['destination'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $discountPrice = floatval($_POST['discount_price'] ?? 0);
            $duration = intval($_POST['duration'] ?? 0);
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;
            $maxSlots = intval($_POST['max_slots'] ?? 0);
            $categoryId = intval($_POST['category_id'] ?? 0);
            $image = null;

            // Validation
            if (empty($name) || empty($destination) || $price <= 0) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("back");
                exit;
            }

            $tour = new Tour();

            // Upload hình ảnh
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploadTourImage($_FILES['image']);
                if (!$image) {
                    $_SESSION['error'] = "Lỗi upload hình ảnh!";
                    header("back");
                    exit;
                }
            }

            if ($tourId) {
                // Update
                $success = $tour->updateTour($tourId, $name, $description, $destination, $price, $discountPrice, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image);
                $_SESSION['success'] = $success ? "Cập nhật tour thành công!" : "Lỗi khi cập nhật!";
            } else {
                // Create
                $tourId = $tour->createTour($name, $description, $destination, $price, $discountPrice, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image);
                $_SESSION['success'] = $tourId ? "Thêm tour thành công!" : "Lỗi khi thêm tour!";
            }

            header("Location: index.php?action=manageTours");
            exit;
        }
    }

    public function deleteTour() {
        $tourId = $_GET['id'] ?? null;
        $tour = new Tour();
        if ($tour->deleteTour($tourId)) {
            $_SESSION['success'] = "Xóa tour thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa tour!";
        }
        header("Location: index.php?action=manageTours");
        exit;
    }

    // ===== BOOKINGS MANAGEMENT =====
    public function manageBookings() {
        $booking = new Booking();
        $bookings = $booking->getAllBookings(20, 0);
        require 'views/admin/bookings/list.php';
    }

    public function confirmBooking() {
        $bookingId = $_GET['id'] ?? null;
        $booking = new Booking();
        if ($booking->updateBookingStatus($bookingId, 'confirmed')) {
            $_SESSION['success'] = "Xác nhận đơn thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xác nhận!";
        }
        header("Location: index.php?action=manageBookings");
        exit;
    }

    public function cancelBookingAdmin() {
        $bookingId = $_GET['id'] ?? null;
        $booking = new Booking();
        if ($booking->updateBookingStatus($bookingId, 'cancelled')) {
            $_SESSION['success'] = "Hủy đơn thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi hủy!";
        }
        header("Location: index.php?action=manageBookings");
        exit;
    }

    public function completeBooking() {
        $bookingId = $_GET['id'] ?? null;
        $booking = new Booking();
        if ($booking->updateBookingStatus($bookingId, 'completed')) {
            $_SESSION['success'] = "Hoàn thành đơn thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi hoàn thành!";
        }
        header("Location: index.php?action=manageBookings");
        exit;
    }

    // ===== PAYMENTS MANAGEMENT =====
    public function managePayments() {
        $payment = new Payment();
        $payments = $payment->getAllPayments(20, 0);
        require 'views/admin/payments/list.php';
    }

    public function paymentDetail() {
        $paymentId = $_GET['id'] ?? null;
        $payment = new Payment();
        $paymentData = $payment->getPaymentById($paymentId);
        
        if (!$paymentData) {
            $_SESSION['error'] = "Giao dịch không tồn tại!";
            header("Location: index.php?action=managePayments");
            exit;
        }

        require 'views/admin/payments/detail.php';
    }

    // ===== CUSTOMERS MANAGEMENT =====
    public function manageCustomers() {
        $user = new User();
        $users = $user->getAllUsers();
        require 'views/admin/customers/list.php';
    }

    public function blockCustomer() {
        $userId = $_GET['id'] ?? null;
        $user = new User();
        if ($user->blockUser($userId)) {
            $_SESSION['success'] = "Khoá khách hàng thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi khoá!";
        }
        header("Location: index.php?action=manageCustomers");
        exit;
    }

    public function unblockCustomer() {
        $userId = $_GET['id'] ?? null;
        $user = new User();
        if ($user->unblockUser($userId)) {
            $_SESSION['success'] = "Mở khoá khách hàng thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi mở khoá!";
        }
        header("Location: index.php?action=manageCustomers");
        exit;
    }

    public function customerDetail() {
        $userId = $_GET['id'] ?? null;
        $user = new User();
        $booking = new Booking();
        
        $userData = $user->getUserById($userId);
        if (!$userData) {
            $_SESSION['error'] = "Khách hàng không tồn tại!";
            header("Location: index.php?action=manageCustomers");
            exit;
        }

        $userBookings = $booking->getBookingsByUser($userId);
        require 'views/admin/customers/detail.php';
    }

    // ===== REVIEWS MANAGEMENT =====
    public function manageReviews() {
        $review = new Review();
        $reviews = $review->getPendingReviews();
        require 'views/admin/reviews/list.php';
    }

    public function approveReview() {
        $reviewId = $_GET['id'] ?? null;
        $review = new Review();
        if ($review->updateReviewStatus($reviewId, 'approved')) {
            $_SESSION['success'] = "Phê duyệt đánh giá thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi phê duyệt!";
        }
        header("Location: index.php?action=manageReviews");
        exit;
    }

    public function rejectReview() {
        $reviewId = $_GET['id'] ?? null;
        $review = new Review();
        if ($review->updateReviewStatus($reviewId, 'rejected')) {
            $_SESSION['success'] = "Từ chối đánh giá thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi từ chối!";
        }
        header("Location: index.php?action=manageReviews");
        exit;
    }

    public function deleteReviewAdmin() {
        $reviewId = $_GET['id'] ?? null;
        $review = new Review();
        if ($review->deleteReview($reviewId)) {
            $_SESSION['success'] = "Xóa đánh giá thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa!";
        }
        header("Location: index.php?action=manageReviews");
        exit;
    }

    // ===== CATEGORIES MANAGEMENT =====
    public function manageCategories() {
        $category = new Category();
        $categories = $category->getAllCategories();
        require 'views/admin/categories/list.php';
    }

    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon = trim($_POST['icon'] ?? '');

            if (empty($name)) {
                $_SESSION['error'] = "Vui lòng nhập tên loại tour!";
                header("back");
                exit;
            }

            $category = new Category();

            if ($categoryId) {
                $success = $category->updateCategory($categoryId, $name, $description, $icon);
                $_SESSION['success'] = $success ? "Cập nhật loại tour thành công!" : "Lỗi!";
            } else {
                $success = $category->createCategory($name, $description, $icon);
                $_SESSION['success'] = $success ? "Thêm loại tour thành công!" : "Lỗi!";
            }

            header("Location: index.php?action=manageCategories");
            exit;
        }
    }

    public function deleteCategory() {
        $categoryId = $_GET['id'] ?? null;
        $category = new Category();
        if ($category->deleteCategory($categoryId)) {
            $_SESSION['success'] = "Xóa loại tour thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa!";
        }
        header("Location: index.php?action=manageCategories");
        exit;
    }

    // ===== PROMOTIONS MANAGEMENT =====
    public function managePromotions() {
        $promotion = new Promotion();
        $promotions = $promotion->getAllPromotions();
        require 'views/admin/promotions/list.php';
    }

    public function savePromotion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $promotionId = $_POST['promotion_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $code = trim(strtoupper($_POST['code'] ?? ''));
            $discountType = $_POST['discount_type'] ?? 'percent';
            $discountValue = floatval($_POST['discount_value'] ?? 0);
            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;
            $maxUses = intval($_POST['max_uses'] ?? 0);
            $minAmount = floatval($_POST['min_amount'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            if (empty($name) || empty($code) || $discountValue <= 0) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("back");
                exit;
            }

            $promotion = new Promotion();

            if ($promotionId) {
                $success = $promotion->updatePromotion($promotionId, $name, $code, $discountType, $discountValue, $startDate, $endDate, $maxUses, $minAmount, $status);
                $_SESSION['success'] = $success ? "Cập nhật khuyến mãi thành công!" : "Lỗi!";
            } else {
                $success = $promotion->createPromotion($name, $code, $discountType, $discountValue, $startDate, $endDate, $maxUses, $minAmount);
                $_SESSION['success'] = $success ? "Thêm khuyến mãi thành công!" : "Lỗi!";
            }

            header("Location: index.php?action=managePromotions");
            exit;
        }
    }

    public function deletePromotion() {
        $promotionId = $_GET['id'] ?? null;
        $promotion = new Promotion();
        if ($promotion->deletePromotion($promotionId)) {
            $_SESSION['success'] = "Xóa khuyến mãi thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa!";
        }
        header("Location: index.php?action=managePromotions");
        exit;
    }

    // ===== HELPER FUNCTIONS =====
    private function uploadTourImage($file) {
        $uploadDir = 'assets/uploads/tours/';
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

        // Check file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = "File quá lớn (tối đa 5MB)!";
            return null;
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName;
        }
        return null;
    }
}
