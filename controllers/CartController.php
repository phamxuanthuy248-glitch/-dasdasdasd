<?php

namespace App\Controllers;

use App\Models\BaseModel;

class CartController extends BaseModel {
    public function addToCart($itemId, $quantity) {
        // Add item to cart logic
    }

    public function getCart() {
        // Get cart items logic
    }

    public function updateQuantity($itemId, $quantity) {
        // Update item quantity in cart logic
    }

    public function removeFromCart($itemId) {
        // Remove item from cart logic
    }

    public function clearCart() {
        // Clear all items from cart logic
    }

    public function getCartTotal() {
        // Calculate total price of cart items logic
    }

    public function viewCart() {
        // View cart items logic
    }
}