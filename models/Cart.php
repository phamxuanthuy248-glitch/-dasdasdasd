<?php

class Cart {
    private $items = [];

    public function addToCart($item, $quantity) {
        if (array_key_exists($item, $this->items)) {
            $this->items[$item] += $quantity;
        } else {
            $this->items[$item] = $quantity;
        }
    }

    public function removeFromCart($item) {
        if (array_key_exists($item, $this->items)) {
            unset($this->items[$item]);
        }
    }

    public function getCartItems() {
        return $this->items;
    }

    public function clearCart() {
        $this->items = [];
    }

    public function updateQuantity($item, $quantity) {
        if (array_key_exists($item, $this->items)) {
            $this->items[$item] = $quantity;
        }
    }
}
