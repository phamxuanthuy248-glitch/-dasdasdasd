<?php

class Category extends BaseModel {
    
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->query($sql);
    }

    public function getCategoryById($categoryId) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->query($sql, [$categoryId]);
    }

    public function createCategory($name, $description = null, $icon = null) {
        $sql = "INSERT INTO categories (name, description, icon) VALUES (?, ?, ?)";
        return $this->execute($sql, [$name, $description, $icon]);
    }

    public function updateCategory($categoryId, $name, $description = null, $icon = null) {
        $sql = "UPDATE categories SET name = ?, description = ?, icon = ? WHERE id = ?";
        return $this->execute($sql, [$name, $description, $icon, $categoryId]);
    }

    public function deleteCategory($categoryId) {
        $sql = "DELETE FROM categories WHERE id = ?";
        return $this->execute($sql, [$categoryId]);
    }

    public function getToursByCategory($categoryId) {
        $sql = "SELECT t.* FROM tours t 
                WHERE t.category_id = ? AND t.status = 'active' 
                ORDER BY t.created_at DESC";
        return $this->query($sql, [$categoryId]);
    }

    public function countCategories() {
        $sql = "SELECT COUNT(*) as total FROM categories";
        $result = $this->query($sql);
        return $result[0]['total'] ?? 0;
    }
}
