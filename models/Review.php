<?php

class Review extends BaseModel {
    
    public function createReview($tourId, $userId, $bookingId, $rating, $comment, $image = null) {
        $sql = "INSERT INTO reviews (tour_id, user_id, booking_id, rating, comment, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        return $this->execute($sql, [$tourId, $userId, $bookingId, $rating, $comment, $image]);
    }

    public function getReviewsByTour($tourId) {
        $sql = "SELECT r.*, u.username, u.avatar 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.tour_id = ? AND r.status = 'approved' 
                ORDER BY r.created_at DESC";
        return $this->query($sql, [$tourId]);
    }

    public function getReviewsByUser($userId) {
        $sql = "SELECT r.*, t.name as tour_name, t.image 
                FROM reviews r 
                JOIN tours t ON r.tour_id = t.id 
                WHERE r.user_id = ? 
                ORDER BY r.created_at DESC";
        return $this->query($sql, [$userId]);
    }

    public function getReviewById($reviewId) {
        $sql = "SELECT r.*, u.username, t.name as tour_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN tours t ON r.tour_id = t.id 
                WHERE r.id = ?";
        return $this->query($sql, [$reviewId]);
    }

    public function getAllReviews($limit = 10, $offset = 0) {
        $sql = "SELECT r.*, u.username, t.name as tour_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN tours t ON r.tour_id = t.id 
                ORDER BY r.created_at DESC 
                LIMIT ? OFFSET ?";
        return $this->query($sql, [$limit, $offset]);
    }

    public function getPendingReviews() {
        $sql = "SELECT r.*, u.username, t.name as tour_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN tours t ON r.tour_id = t.id 
                WHERE r.status = 'pending' 
                ORDER BY r.created_at ASC";
        return $this->query($sql);
    }

    public function updateReviewStatus($reviewId, $status) {
        $sql = "UPDATE reviews SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$status, $reviewId]);
    }

    public function updateReview($reviewId, $rating, $comment, $image = null) {
        $sql = "UPDATE reviews SET rating = ?, comment = ?, image = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$rating, $comment, $image, $reviewId]);
    }

    public function deleteReview($reviewId) {
        $sql = "DELETE FROM reviews WHERE id = ?";
        return $this->execute($sql, [$reviewId]);
    }

    public function getAverageRating($tourId) {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM reviews 
                WHERE tour_id = ? AND status = 'approved'";
        return $this->query($sql, [$tourId]);
    }

    public function countReviewsByTour($tourId) {
        $sql = "SELECT COUNT(*) as total FROM reviews WHERE tour_id = ? AND status = 'approved'";
        $result = $this->query($sql, [$tourId]);
        return $result[0]['total'] ?? 0;
    }

    public function addHelpful($reviewId) {
        $sql = "UPDATE reviews SET helpful_count = helpful_count + 1 WHERE id = ?";
        return $this->execute($sql, [$reviewId]);
    }
}
