<?php

class Booking extends BaseModel {
    
    public function createBooking($userId, $tourId, $quantity, $totalPrice) {
        $bookingCode = 'BK' . date('YmdHis') . rand(1000, 9999);
        $sql = "INSERT INTO bookings (booking_code, user_id, tour_id, quantity, total_price, status, payment_status) 
                VALUES (?, ?, ?, ?, ?, 'pending', 'unpaid')";
        return $this->execute($sql, [$bookingCode, $userId, $tourId, $quantity, $totalPrice]);
    }

    public function getBookingsByUser($userId) {
        $sql = "SELECT b.*, t.name as tour_name, t.image, t.destination 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                WHERE b.user_id = ? 
                ORDER BY b.created_at DESC";
        return $this->query($sql, [$userId]);
    }

    public function getBookingById($bookingId) {
        $sql = "SELECT b.*, t.name as tour_name, t.image, t.destination, u.email, u.phone 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                JOIN users u ON b.user_id = u.id 
                WHERE b.id = ?";
        return $this->query($sql, [$bookingId]);
    }

    public function updateBookingStatus($bookingId, $status) {
        $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$status, $bookingId]);
    }

    public function updatePaymentStatus($bookingId, $paymentStatus) {
        $sql = "UPDATE bookings SET payment_status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$paymentStatus, $bookingId]);
    }

    public function getAllBookings($limit = 10, $offset = 0) {
        $sql = "SELECT b.*, t.name as tour_name, u.username, u.email 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC 
                LIMIT ? OFFSET ?";
        return $this->query($sql, [$limit, $offset]);
    }

    public function getBookingsByStatus($status) {
        $sql = "SELECT b.*, t.name as tour_name, u.username 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.id 
                JOIN users u ON b.user_id = u.id 
                WHERE b.status = ? 
                ORDER BY b.created_at DESC";
        return $this->query($sql, [$status]);
    }

    public function deleteBooking($bookingId) {
        $sql = "DELETE FROM bookings WHERE id = ?";
        return $this->execute($sql, [$bookingId]);
    }

    public function countBookings() {
        $sql = "SELECT COUNT(*) as total FROM bookings";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }
}
