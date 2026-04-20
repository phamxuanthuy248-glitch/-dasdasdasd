<?php

class Itinerary extends BaseModel {
    
    public function getItinerariesByTour($tourId) {
        $sql = "SELECT * FROM itineraries WHERE tour_id = ? ORDER BY day ASC";
        return $this->query($sql, [$tourId]);
    }

    public function getItineraryById($itineraryId) {
        $sql = "SELECT * FROM itineraries WHERE id = ?";
        return $this->query($sql, [$itineraryId]);
    }

    public function createItinerary($tourId, $day, $title, $description, $image = null) {
        $sql = "INSERT INTO itineraries (tour_id, day, title, description, image) 
                VALUES (?, ?, ?, ?, ?)";
        return $this->execute($sql, [$tourId, $day, $title, $description, $image]);
    }

    public function updateItinerary($itineraryId, $day, $title, $description, $image = null) {
        $sql = "UPDATE itineraries SET day = ?, title = ?, description = ?, image = ? WHERE id = ?";
        return $this->execute($sql, [$day, $title, $description, $image, $itineraryId]);
    }

    public function deleteItinerary($itineraryId) {
        $sql = "DELETE FROM itineraries WHERE id = ?";
        return $this->execute($sql, [$itineraryId]);
    }

    public function deleteItinerariesByTour($tourId) {
        $sql = "DELETE FROM itineraries WHERE tour_id = ?";
        return $this->execute($sql, [$tourId]);
    }
}
