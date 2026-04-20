<?php

class Promotion extends BaseModel {
    
    public function getAllPromotions() {
        $sql = "SELECT * FROM promotions WHERE status = 'active' ORDER BY created_at DESC";
        return $this->query($sql);
    }

    public function getPromotionById($promotionId) {
        $sql = "SELECT * FROM promotions WHERE id = ?";
        return $this->query($sql, [$promotionId]);
    }

    public function getPromotionByCode($code) {
        $sql = "SELECT * FROM promotions 
                WHERE code = ? AND status = 'active' 
                AND (end_date IS NULL OR end_date >= CURDATE())
                AND (max_uses IS NULL OR used_count < max_uses)";
        return $this->query($sql, [$code]);
    }

    public function createPromotion($name, $code, $discountType, $discountValue, $startDate = null, $endDate = null, $maxUses = null, $minAmount = 0) {
        $sql = "INSERT INTO promotions (name, code, description, discount_type, discount_value, start_date, end_date, max_uses, min_amount, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
        return $this->execute($sql, [$name, $code, null, $discountType, $discountValue, $startDate, $endDate, $maxUses, $minAmount]);
    }

    public function updatePromotion($promotionId, $name, $code, $discountType, $discountValue, $startDate, $endDate, $maxUses, $minAmount, $status) {
        $sql = "UPDATE promotions 
                SET name = ?, code = ?, discount_type = ?, discount_value = ?, 
                    start_date = ?, end_date = ?, max_uses = ?, min_amount = ?, status = ?, updated_at = NOW() 
                WHERE id = ?";
        return $this->execute($sql, [$name, $code, $discountType, $discountValue, $startDate, $endDate, $maxUses, $minAmount, $status, $promotionId]);
    }

    public function deletePromotion($promotionId) {
        $sql = "DELETE FROM promotions WHERE id = ?";
        return $this->execute($sql, [$promotionId]);
    }

    public function usePromotion($promotionId) {
        $sql = "UPDATE promotions SET used_count = used_count + 1 WHERE id = ?";
        return $this->execute($sql, [$promotionId]);
    }

    public function calculateDiscount($promotionId, $amount) {
        $promo = $this->getPromotionById($promotionId);
        if (!$promo) return 0;

        if ($promo[0]['discount_type'] === 'percent') {
            return ($amount * $promo[0]['discount_value']) / 100;
        } else {
            return $promo[0]['discount_value'];
        }
    }
}
