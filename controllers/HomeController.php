<?php
class HomeController {

    public function index() {
        require 'views/user/home.php';
    }

    public function dashboard() {
        require 'views/admin/dashboard.php';
    }
}