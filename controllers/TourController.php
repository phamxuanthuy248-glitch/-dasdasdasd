<?php

class TourController extends BaseModel {

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
        $sql = "SELECT * FROM tours WHERE category_id = ? AND id != ? AND status = 'active' LIMIT ?";
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

    // ===== CREATE/UPDATE/DELETE METHODS =====
    public function createTour($name, $description, $destination, $price, $discountPrice, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image = null) {
        $discountPercent = 0;
        if ($discountPrice && $discountPrice > 0) {
            $discountPercent = (($price - $discountPrice) / $price) * 100;
        }

        $sql = "INSERT INTO tours (name, description, destination, price, discount_price, discount_percent, duration, start_date, end_date, max_slots, category_id, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
        return $this->execute($sql, [$name, $description, $destination, $price, $discountPrice, $discountPercent, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image]);
    }

    public function updateTour($id, $name, $description, $destination, $price, $discountPrice, $duration, $startDate, $endDate, $maxSlots, $categoryId, $image = null, $status = 'active') {
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

    public function updateTourSlots($tourId, $quantity) {
        $sql = "UPDATE tours SET current_slots = current_slots + ? WHERE id = ?";
        return $this->execute($sql, [$quantity, $tourId]);
    }

    public function addServiceToTour($tourId, $serviceId) {
        $sql = "INSERT IGNORE INTO tour_services (tour_id, service_id) VALUES (?, ?)";
        return $this->execute($sql, [$tourId, $serviceId]);
    }

    public function removeServiceFromTour($tourId, $serviceId) {
        $sql = "DELETE FROM tour_services WHERE tour_id = ? AND service_id = ?";
        return $this->execute($sql, [$tourId, $serviceId]);
    }

    // ===== VIEW METHODS =====
    public function index() {
        $categoryId = $_GET['category'] ?? null;
        $minPrice = $_GET['minPrice'] ?? null;
        $maxPrice = $_GET['maxPrice'] ?? null;
        $destination = $_GET['destination'] ?? null;

        if ($categoryId || $minPrice || $maxPrice || $destination) {
            $tours = $this->filterTours($categoryId, $minPrice, $maxPrice, $destination);
        } else {
            $tours = $this->getAllTours();
        }

        $category = new Category();
        $categories = $category->getAllCategories();

        require 'views/user/tours/list.php';
    }

    public function detail() {
        $tourId = $_GET['id'] ?? null;
        
        if (!$tourId) {
            header("Location: index.php?action=tours");
            exit;
        }

        $tour = $this->getTourById($tourId);
        if (!$tour) {
            header("Location: index.php?action=tours");
            exit;
        }

        $itinerary = new Itinerary();
        $itineraries = $itinerary->getItinerariesByTour($tourId);

        $services = $this->getTourServices($tourId);

        $review = new Review();
        $reviews = $review->getReviewsByTour($tourId);

        $relatedTours = $this->getRelatedTours($tourId, 4);

        require 'views/user/tours/detail.php';
    }

    public function search() {
        $query = $_GET['q'] ?? null;
        $tours = [];

        if ($query) {
            $tours = $this->searchTours($query);
        }

        $category = new Category();
        $categories = $category->getAllCategories();

        require 'views/user/tours/search.php';
    }
}
