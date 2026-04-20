<?php

class BookingController {

    public function createBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                $_SESSION['error'] = "Vui lòng đăng nhập để đặt tour!";
                header("Location: index.php?action=login");
                exit;
            }

            $tourId = intval($_POST['tour_id'] ?? 0);
            $quantity = intval($_POST['quantity'] ?? 1);

            // Validation
            if (!$tourId || $quantity <= 0) {
                $_SESSION['error'] = "Thông tin đặt tour không hợp lệ!";
                header("back");
                exit;
            }

            $tour = new Tour();
            $tourData = $tour->getTourById($tourId);

            if (!$tourData) {
                $_SESSION['error'] = "Tour không tồn tại!";
                header("Location: index.php?action=tours");
                exit;
            }

            // Check available slots
            if ($tourData[0]['current_slots'] + $quantity > $tourData[0]['max_slots']) {
                $_SESSION['error'] = "Số chỗ không đủ! Chỉ còn " . ($tourData[0]['max_slots'] - $tourData[0]['current_slots']) . " chỗ.";
                header("back");
                exit;
            }

            $price = $tourData[0]['discount_price'] ?? $tourData[0]['price'];
            $totalPrice = $price * $quantity;

            $booking = new Booking();
            $bookingId = $booking->createBooking($_SESSION['user']['id'], $tourId, $quantity, $totalPrice);

            if ($bookingId) {
                // Update tour slots
                $tour->updateTourSlots($tourId, $quantity);
                
                $_SESSION['success'] = "Đặt tour thành công! Chuyển đến trang thanh toán...";
                header("Location: index.php?action=checkout&booking_id=" . $bookingId);
            } else {
                $_SESSION['error'] = "Lỗi khi đặt tour! Vui lòng thử lại.";
                header("back");
            }
            exit;
        }
    }

    public function myBookings() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $booking = new Booking();
        $bookings = $booking->getBookingsByUser($_SESSION['user']['id']);
        require 'views/user/booking/my-bookings.php';
    }

    public function bookingDetail() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $bookingId = $_GET['id'] ?? null;
        
        if (!$bookingId) {
            $_SESSION['error'] = "Đơn không hợp lệ!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        $booking = new Booking();
        $bookingData = $booking->getBookingById($bookingId);

        if (!$bookingData || $bookingData[0]['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Bạn không có quyền xem đơn này!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        $payment = new Payment();
        $payments = $payment->getPaymentsByBooking($bookingId);

        require 'views/user/booking/detail.php';
    }

    public function cancelBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?action=login");
                exit;
            }

            $bookingId = $_POST['booking_id'] ?? null;
            $booking = new Booking();
            $bookingData = $booking->getBookingById($bookingId);

            if (!$bookingData || $bookingData[0]['user_id'] != $_SESSION['user']['id']) {
                $_SESSION['error'] = "Không có quyền hủy đơn này!";
                header("Location: index.php?action=myBookings");
                exit;
            }

            if ($bookingData[0]['status'] === 'completed' || $bookingData[0]['status'] === 'cancelled') {
                $_SESSION['error'] = "Không thể hủy đơn này!";
                header("Location: index.php?action=myBookings");
                exit;
            }

            if ($booking->updateBookingStatus($bookingId, 'cancelled')) {
                // Refund slots
                $tour = new Tour();
                $tour->updateTourSlots($bookingData[0]['tour_id'], -$bookingData[0]['quantity']);

                $_SESSION['success'] = "Hủy đơn thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi hủy đơn!";
            }

            header("Location: index.php?action=myBookings");
            exit;
        }
    }

    public function bookingForm() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $tourId = $_GET['id'] ?? null;
        
        if (!$tourId) {
            $_SESSION['error'] = "Tour không hợp lệ!";
            header("Location: index.php?action=tours");
            exit;
        }

        $tour = new Tour();
        $tourData = $tour->getTourById($tourId);

        if (!$tourData) {
            $_SESSION['error'] = "Tour không tồn tại!";
            header("Location: index.php?action=tours");
            exit;
        }

        require 'views/user/booking/form.php';
    }

    public function confirmBooking() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $bookingId = $_GET['id'] ?? null;
        $booking = new Booking();
        $bookingData = $booking->getBookingById($bookingId);

        if (!$bookingData || $bookingData[0]['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Không có quyền xác nhận đơn này!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        if ($booking->updateBookingStatus($bookingId, 'confirmed')) {
            $_SESSION['success'] = "Xác nhận đơn thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xác nhận!";
        }

        header("Location: index.php?action=bookingDetail&id=" . $bookingId);
        exit;
    }
}
