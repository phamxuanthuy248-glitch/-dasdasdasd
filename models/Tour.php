<?php

class Tour extends BaseModel {
    
    // ===== GET METHODS =====
    public function getAllTours() {
        $sql = "SELECT * FROM tours WHERE status = 'active' ORDER BY created_at DESC";
        return $this->query($sql);
    }

    public function getTourById($id) {
        $sql = "SELECT t.*, c.name as category_name 
                FROM tours t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.id = ?";
        return $this->query($sql, [$id]);
    }

    public function searchTours($keyword) {
        $sql = "SELECT * FROM tours 
                WHERE status = 'active' 
                AND (name LIKE ? OR description LIKE ? OR destination LIKE ?) 
                ORDER BY created_at DESC";
        $keyword = "%$keyword%";
        return $this->query($sql, [$keyword, $keyword, $keyword]);
    }

    public function filterTours($categoryId = null, $minPrice = null, $maxPrice = null, $destination = null) {
        $sql = "SELECT * FROM tours WHERE status = 'active'";
        $params = [];

        if ($categoryId) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($minPrice !== null) {
            $sql .= " AND (COALESCE(discount_price, price) >= ?)";
            $params[] = $minPrice;
        }
        
        if ($maxPrice !== null) {
            $sql .= " AND (COALESCE(discount_price, price) <= ?)";
            $params[] = $maxPrice;
        }
        
        if ($destination) {
            $sql .= " AND destination LIKE ?";
            $params[] = "%$destination%";
        }

        $sql .= " ORDER BY rating DESC, created_at DESC";
        return $this->query($sql, $params);
    }

    public function getToursByCategory($categoryId) {
        $sql = "SELECT * FROM tours WHERE category_id = ? AND status = 'active' ORDER BY rating DESC";
        return $this->query($sql, [$categoryId]);
    }

    public function getFeaturedTours($limit = 6) {
        $sql = "SELECT * FROM tours WHERE status = 'active' ORDER BY rating DESC, review_count DESC LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    public function getRelatedTours($tourId, $limit = 4) {
        $tour = $this->getTourById($tourId);
        if (!$tour) return [];

        $categoryId = $tour[0]['category_id'];
        $sql = "SELECT * FROM tours WHERE category_id = ? AND id != ? AND status = 'active' ORDER BY rating DESC LIMIT ?";
        return $this->query($sql, [$categoryId, $tourId, $limit]);
    }

    public function getTourServices($tourId) {
        $sql = "SELECT s.* FROM services s 
                JOIN tour_services ts ON s.id = ts.service_id 
                WHERE ts.tour_id = ?";
        return $this->query($sql, [$tourId]);
    }

    public function getTourRating($tourId) {
        $sql = "SELECT AVG(rating) as rating, COUNT(*) as count FROM reviews WHERE tour_id = ? AND status = 'approved'";
        $result = $this->query($sql, [$tourId]);
        return $result[0] ?? ['rating' => 0, 'count' => 0];
    }

    public function countTours() {
        $sql = "SELECT COUNT(*) as total FROM tours WHERE status = 'active'";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function getToursByPriceRange($minPrice, $maxPrice) {
        $sql = "SELECT * FROM tours 
                WHERE status = 'active' 
                AND (COALESCE(discount_price, price) BETWEEN ? AND ?)
                ORDER BY price ASC";
        return $this->query($sql, [$minPrice, $maxPrice]);
    }

    public function getToursByDateRange($startDate, $endDate) {
        $sql = "SELECT * FROM tours 
                WHERE status = 'active' 
                AND start_date >= ? AND end_date <= ?
                ORDER BY start_date ASC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    public function getToursWithAvailableSlots() {
        $sql = "SELECT * FROM tours 
                WHERE status = 'active' 
                AND current_slots < max_slots
                ORDER BY created_at DESC";
        return $this->query($sql);
    }

    // ===== CREATE/UPDATE/DELETE METHODS =====
    public function createTour($name, $description, $destination, $price, $discountPrice = null, $duration = null, $startDate = null, $endDate = null, $maxSlots = 0, $categoryId = null, $image = null) {
        $discountPercent = 0;
        if ($discountPrice && $discountPrice > 0) {
            $discountPercent = (($price - $discountPrice) / $price) * 100;
        }

        $sql = "INSERT INTO tours (name, description, destination, price, discount_price, discount_percent, duration, start_date, end_date, max_slots, category_id, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
        return $this->execute($sql, [$name, $description, $destination, $price, $discountPrice, $discountPercent, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image]);
    }

    public function updateTour($id, $name, $description, $destination, $price, $discountPrice = null, $duration = null, $startDate = null, $endDate = null, $maxSlots = 0, $categoryId = null, $image = null, $status = 'active') {
        $discountPercent = 0;
        if ($discountPrice && $discountPrice > 0) {
            $discountPercent = (($price - $discountPrice) / $price) * 100;
        }

        $sql = "UPDATE tours 
                SET name = ?, description = ?, destination = ?, price = ?, discount_price = ?, discount_percent = ?, 
                    duration = ?, start_date = ?, end_date = ?, max_slots = ?, category_id = ?, status = ?, updated_at = NOW()";
        
        $params = [$name, $description, $destination, $price, $discountPrice, $discountPercent, $duration, $startDate, $endDate, $maxSlots, $categoryId, $status];

        if ($image) {
            $sql .= ", image = ?";
            $params[] = $image;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        return $this->execute($sql, $params);
    }

    public function deleteTour($id) {
        $sql = "UPDATE tours SET status = 'inactive', updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    public function permanentlyDeleteTour($id) {
        $sql = "DELETE FROM tours WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    public function updateTourSlots($tourId, $quantity) {
        $sql = "UPDATE tours SET current_slots = current_slots + ? WHERE id = ?";
        return $this->execute($sql, [$quantity, $tourId]);
    }

    public function updateTourStatus($tourId, $status) {
        $sql = "UPDATE tours SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$status, $tourId]);
    }

    // ===== SERVICE METHODS =====
    public function addServiceToTour($tourId, $serviceId) {
        $sql = "INSERT IGNORE INTO tour_services (tour_id, service_id) VALUES (?, ?)";
        return $this->execute($sql, [$tourId, $serviceId]);
    }

    public function removeServiceFromTour($tourId, $serviceId) {
        $sql = "DELETE FROM tour_services WHERE tour_id = ? AND service_id = ?";
        return $this->execute($sql, [$tourId, $serviceId]);
    }

    public function removeAllServicesFromTour($tourId) {
        $sql = "DELETE FROM tour_services WHERE tour_id = ?";
        return $this->execute($sql, [$tourId]);
    }

    // ===== RATING & REVIEW METHODS =====
    public function updateTourRating($tourId) {
        $ratingData = $this->getTourRating($tourId);
        $sql = "UPDATE tours SET rating = ?, review_count = ? WHERE id = ?";
        return $this->execute($sql, [$ratingData['rating'] ?? 0, $ratingData['count'] ?? 0, $tourId]);
    }

    public function getTopRatedTours($limit = 5) {
        $sql = "SELECT * FROM tours WHERE status = 'active' ORDER BY rating DESC LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    // ===== UTILITY METHODS =====
    public function getTourPrice($tourId) {
        $tour = $this->getTourById($tourId);
        if ($tour) {
            return $tour[0]['discount_price'] ?? $tour[0]['price'];
        }
        return 0;
    }

    public function isTourAvailable($tourId) {
        $tour = $this->getTourById($tourId);
        if ($tour) {
            return $tour[0]['current_slots'] < $tour[0]['max_slots'];
        }
        return false;
    }

    public function getTourAvailableSlots($tourId) {
        $tour = $this->getTourById($tourId);
        if ($tour) {
            return $tour[0]['max_slots'] - $tour[0]['current_slots'];
        }
        return 0;
    }

    public function searchToursByAllFields($keyword, $categoryId = null, $minPrice = null, $maxPrice = null) {
        $sql = "SELECT * FROM tours WHERE status = 'active'";
        $params = [];

        if ($keyword) {
            $sql .= " AND (name LIKE ? OR description LIKE ? OR destination LIKE ?)";
            $keyword = "%$keyword%";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        if ($categoryId) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }

        if ($minPrice !== null) {
            $sql .= " AND (COALESCE(discount_price, price) >= ?)";
            $params[] = $minPrice;
        }

        if ($maxPrice !== null) {
            $sql .= " AND (COALESCE(discount_price, price) <= ?)";
            $params[] = $maxPrice;
        }

        $sql .= " ORDER BY rating DESC, created_at DESC";
        return $this->query($sql, $params);
    }

    public function getDurationRange() {
        $sql = "SELECT MIN(duration) as min_duration, MAX(duration) as max_duration FROM tours WHERE status = 'active'";
        return $this->query($sql);
    }

    public function getPriceRange() {
        $sql = "SELECT MIN(COALESCE(discount_price, price)) as min_price, MAX(COALESCE(discount_price, price)) as max_price FROM tours WHERE status = 'active'";
        return $this->query($sql);
    }
}
