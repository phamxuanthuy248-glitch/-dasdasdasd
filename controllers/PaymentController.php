<?php

class PaymentController {

    public function checkout() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $bookingId = $_GET['booking_id'] ?? null;
        
        if (!$bookingId) {
            $_SESSION['error'] = "Đơn không hợp lệ!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        $booking = new Booking();
        $bookingData = $booking->getBookingById($bookingId);

        if (!$bookingData || $bookingData[0]['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Đơn không hợp lệ!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        $promotion = new Promotion();
        $promotions = $promotion->getAllPromotions();

        require 'views/user/payment/checkout.php';
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?action=login");
                exit;
            }

            $bookingId = $_POST['booking_id'] ?? null;
            $paymentMethod = $_POST['payment_method'] ?? null;
            $promoCode = $_POST['promo_code'] ?? null;

            // Validation
            $validMethods = ['vnpay', 'momo', 'paypal', 'credit_card', 'bank_transfer', 'cash'];
            if (!in_array($paymentMethod, $validMethods)) {
                $_SESSION['error'] = "Phương thức thanh toán không hợp lệ!";
                header("back");
                exit;
            }

            $booking = new Booking();
            $bookingData = $booking->getBookingById($bookingId);

            if (!$bookingData || $bookingData[0]['user_id'] != $_SESSION['user']['id']) {
                $_SESSION['error'] = "Đơn không hợp lệ!";
                header("Location: index.php?action=myBookings");
                exit;
            }

            // Calculate discount if promo code provided
            $totalPrice = $bookingData[0]['total_price'];
            if ($promoCode) {
                $promotion = new Promotion();
                $promoData = $promotion->getPromotionByCode($promoCode);
                
                if ($promoData && $totalPrice >= $promoData[0]['min_amount']) {
                    $discount = $promotion->calculateDiscount($promoData[0]['id'], $totalPrice);
                    $totalPrice -= $discount;
                    $promotion->usePromotion($promoData[0]['id']);
                }
            }

            $transactionId = 'TXN' . date('YmdHis') . rand(10000, 99999);
            $payment = new Payment();
            $paymentId = $payment->createPayment($bookingId, $_SESSION['user']['id'], $totalPrice, $paymentMethod, $transactionId);

            if (!$paymentId) {
                $_SESSION['error'] = "Lỗi khi tạo giao dịch!";
                header("back");
                exit;
            }

            // Redirect to payment gateway based on method
            switch ($paymentMethod) {
                case 'vnpay':
                    $this->processVNPay($paymentId, $totalPrice, $transactionId);
                    break;
                case 'momo':
                    $this->processMomo($paymentId, $totalPrice, $transactionId);
                    break;
                case 'paypal':
                    $this->processPayPal($paymentId, $totalPrice, $transactionId);
                    break;
                case 'cash':
                case 'bank_transfer':
                    // Mark as pending, waiting for manual confirmation
                    $payment->updatePaymentStatus($paymentId, 'pending');
                    $booking->updateBookingStatus($bookingId, 'confirmed');
                    $_SESSION['success'] = "Đơn đã được xác nhận. Vui lòng chuyển tiền theo hướng dẫn.";
                    header("Location: index.php?action=bookingDetail&id=" . $bookingId);
                    break;
                default:
                    $payment->updatePaymentStatus($paymentId, 'success');
                    $booking->updateBookingStatus($bookingId, 'confirmed');
                    $booking->updatePaymentStatus($bookingId, 'paid');
                    $_SESSION['success'] = "Thanh toán thành công!";
                    header("Location: index.php?action=bookingDetail&id=" . $bookingId);
            }
            exit;
        }
    }

    public function validatePromoCode() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? null;
            $amount = floatval($_POST['amount'] ?? 0);

            $promotion = new Promotion();
            $promoData = $promotion->getPromotionByCode($code);

            if (!$promoData) {
                echo json_encode(['valid' => false, 'message' => 'Mã không hợp lệ!']);
                exit;
            }

            if ($amount < $promoData[0]['min_amount']) {
                echo json_encode([
                    'valid' => false, 
                    'message' => 'Giá trị đơn hàng tối thiểu ' . number_format($promoData[0]['min_amount'], 0, ',', '.') . 'đ'
                ]);
                exit;
            }

            $discount = $promotion->calculateDiscount($promoData[0]['id'], $amount);
            echo json_encode([
                'valid' => true,
                'message' => 'Mã hợp lệ!',
                'discount' => $discount,
                'discountType' => $promoData[0]['discount_type']
            ]);
            exit;
        }
    }

    // ===== PAYMENT GATEWAY METHODS =====
    private function processVNPay($paymentId, $amount, $transactionId) {
        // TODO: Integrate with VNPay API
        // This is a placeholder for VNPay integration
        $_SESSION['info'] = "VNPay integration coming soon";
        header("Location: index.php?action=myBookings");
    }

    private function processMomo($paymentId, $amount, $transactionId) {
        // TODO: Integrate with Momo API
        // This is a placeholder for Momo integration
        $_SESSION['info'] = "Momo integration coming soon";
        header("Location: index.php?action=myBookings");
    }

    private function processPayPal($paymentId, $amount, $transactionId) {
        // TODO: Integrate with PayPal API
        // This is a placeholder for PayPal integration
        $_SESSION['info'] = "PayPal integration coming soon";
        header("Location: index.php?action=myBookings");
    }

    public function vnpayReturn() {
        // Handle VNPay callback
        session_start();
        
        $transactionId = $_GET['vnp_TransactionNo'] ?? null;
        $responseCode = $_GET['vnp_ResponseCode'] ?? null;

        $payment = new Payment();
        $paymentData = $payment->getPaymentByTransactionId($transactionId);

        if (!$paymentData) {
            $_SESSION['error'] = "Giao dịch không tồn tại!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        if ($responseCode === '00') {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'success');
            
            $booking = new Booking();
            $booking->updateBookingStatus($paymentData[0]['booking_id'], 'confirmed');
            $booking->updatePaymentStatus($paymentData[0]['booking_id'], 'paid');
            
            $_SESSION['success'] = "Thanh toán VNPay thành công!";
        } else {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'failed');
            $_SESSION['error'] = "Thanh toán VNPay thất bại!";
        }

        header("Location: index.php?action=bookingDetail&id=" . $paymentData[0]['booking_id']);
        exit;
    }

    public function momoReturn() {
        // Handle Momo callback
        session_start();
        
        $transactionId = $_GET['transactionId'] ?? null;
        $resultCode = $_GET['resultCode'] ?? null;

        $payment = new Payment();
        $paymentData = $payment->getPaymentByTransactionId($transactionId);

        if (!$paymentData) {
            $_SESSION['error'] = "Giao dịch không tồn tại!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        if ($resultCode === '0') {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'success');
            
            $booking = new Booking();
            $booking->updateBookingStatus($paymentData[0]['booking_id'], 'confirmed');
            $booking->updatePaymentStatus($paymentData[0]['booking_id'], 'paid');
            
            $_SESSION['success'] = "Thanh toán Momo thành công!";
        } else {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'failed');
            $_SESSION['error'] = "Thanh toán Momo thất bại!";
        }

        header("Location: index.php?action=bookingDetail&id=" . $paymentData[0]['booking_id']);
        exit;
    }

    public function paypalReturn() {
        // Handle PayPal callback
        session_start();
        
        $transactionId = $_GET['tx'] ?? null;
        $status = $_GET['st'] ?? null;

        $payment = new Payment();
        $paymentData = $payment->getPaymentByTransactionId($transactionId);

        if (!$paymentData) {
            $_SESSION['error'] = "Giao dịch không tồn tại!";
            header("Location: index.php?action=myBookings");
            exit;
        }

        if ($status === 'Completed') {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'success');
            
            $booking = new Booking();
            $booking->updateBookingStatus($paymentData[0]['booking_id'], 'confirmed');
            $booking->updatePaymentStatus($paymentData[0]['booking_id'], 'paid');
            
            $_SESSION['success'] = "Thanh toán PayPal thành công!";
        } else {
            $payment->updatePaymentStatus($paymentData[0]['id'], 'failed');
            $_SESSION['error'] = "Thanh toán PayPal thất bại!";
        }

        header("Location: index.php?action=bookingDetail&id=" . $paymentData[0]['booking_id']);
        exit;
    }

    public function paymentStatus() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $paymentId = $_GET['id'] ?? null;
        $payment = new Payment();
        $paymentData = $payment->getPaymentById($paymentId);

        if (!$paymentData || $paymentData[0]['user_id'] != $_SESSION['user']['id']) {
            echo json_encode(['status' => 'error', 'message' => 'Không có quyền!']);
            exit;
        }

        echo json_encode([
            'status' => 'success',
            'payment_status' => $paymentData[0]['status'],
            'amount' => $paymentData[0]['amount'],
            'method' => $paymentData[0]['payment_method'],
            'date' => $paymentData[0]['payment_date']
        ]);
        exit;
    }
}
