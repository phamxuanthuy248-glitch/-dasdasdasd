<?php

class Payment extends BaseModel {
    
    public function createPayment($bookingId, $userId, $amount, $paymentMethod, $transactionId = null) {
        $sql = "INSERT INTO payments (booking_id, user_id, transaction_id, amount, payment_method, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        return $this->execute($sql, [$bookingId, $userId, $transactionId, $amount, $paymentMethod]);
    }

    public function getPaymentById($paymentId) {
        $sql = "SELECT * FROM payments WHERE id = ?";
        return $this->query($sql, [$paymentId]);
    }

    public function getPaymentByTransactionId($transactionId) {
        $sql = "SELECT * FROM payments WHERE transaction_id = ?";
        return $this->query($sql, [$transactionId]);
    }

    public function getPaymentsByBooking($bookingId) {
        $sql = "SELECT * FROM payments WHERE booking_id = ? ORDER BY created_at DESC";
        return $this->query($sql, [$bookingId]);
    }

    public function getPaymentsByUser($userId) {
        $sql = "SELECT p.*, b.booking_code, t.name as tour_name 
                FROM payments p 
                JOIN bookings b ON p.booking_id = b.id 
                JOIN tours t ON b.tour_id = t.id 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC";
        return $this->query($sql, [$userId]);
    }

    public function updatePaymentStatus($paymentId, $status) {
        $sql = "UPDATE payments SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$status, $paymentId]);
    }

    public function getAllPayments($limit = 10, $offset = 0) {
        $sql = "SELECT p.*, b.booking_code, u.username, t.name as tour_name 
                FROM payments p 
                JOIN bookings b ON p.booking_id = b.id 
                JOIN users u ON p.user_id = u.id 
                JOIN tours t ON b.tour_id = t.id 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        return $this->query($sql, [$limit, $offset]);
    }

    public function getPaymentsByStatus($status) {
        $sql = "SELECT p.*, b.booking_code, u.username 
                FROM payments p 
                JOIN bookings b ON p.booking_id = b.id 
                JOIN users u ON p.user_id = u.id 
                WHERE p.status = ? 
                ORDER BY p.created_at DESC";
        return $this->query($sql, [$status]);
    }

    public function getTotalRevenue() {
        $sql = "SELECT SUM(amount) as total FROM payments WHERE status = 'success'";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function countPayments() {
        $sql = "SELECT COUNT(*) as total FROM payments";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function deletePayment($paymentId) {
        $sql = "DELETE FROM payments WHERE id = ?";
        return $this->execute($sql, [$paymentId]);
    }
}
