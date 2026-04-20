<?php

// ===== START SESSION =====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===== REQUIRE CONTROLLERS =====
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/TourController.php';
require_once __DIR__ . '/../controllers/BookingController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/ReviewController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/UserProfileController.php';

// ===== AUTO ROUTING =====
$action = $_GET['action'] ?? (isset($_SESSION['user']) ? 'home' : 'login');

switch ($action) {

    // ===== AUTH ROUTES =====
    case 'register':
        $auth = new AuthController();
        $auth->register();
        break;

    case 'handleRegister':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new AuthController();
            $auth->handleRegister();
        }
        break;

    case 'login':
        $auth = new AuthController();
        $auth->login();
        break;

    case 'handleLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new AuthController();
            $auth->handleLogin();
        }
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    // ===== HOME ROUTES =====
    case 'home':
        $home = new HomeController();
        $home->index();
        break;

    // ===== TOUR ROUTES =====
    case 'tours':
        $tour = new TourController();
        $tour->index();
        break;

    case 'tourDetail':
        $tour = new TourController();
        $tour->detail();
        break;

    case 'searchTours':
        $tour = new TourController();
        $tour->search();
        break;

    // ===== BOOKING ROUTES =====
    case 'bookingForm':
        $booking = new BookingController();
        $booking->bookingForm();
        break;

    case 'booking':
        $booking = new BookingController();
        $booking->createBooking();
        break;

    case 'myBookings':
        $booking = new BookingController();
        $booking->myBookings();
        break;

    case 'bookingDetail':
        $booking = new BookingController();
        $booking->bookingDetail();
        break;

    case 'confirmBooking':
        $booking = new BookingController();
        $booking->confirmBooking();
        break;

    case 'cancelBooking':
        $booking = new BookingController();
        $booking->cancelBooking();
        break;

    // ===== PAYMENT ROUTES =====
    case 'checkout':
        $payment = new PaymentController();
        $payment->checkout();
        break;

    case 'processPayment':
        $payment = new PaymentController();
        $payment->processPayment();
        break;

    case 'validatePromoCode':
        $payment = new PaymentController();
        $payment->validatePromoCode();
        break;

    case 'paymentStatus':
        $payment = new PaymentController();
        $payment->paymentStatus();
        break;

    case 'vnpayReturn':
        $payment = new PaymentController();
        $payment->vnpayReturn();
        break;

    case 'momoReturn':
        $payment = new PaymentController();
        $payment->momoReturn();
        break;

    case 'paypalReturn':
        $payment = new PaymentController();
        $payment->paypalReturn();
        break;

    // ===== REVIEW ROUTES =====
    case 'addReview':
        $review = new ReviewController();
        $review->addReview();
        break;

    case 'editReview':
        $review = new ReviewController();
        $review->editReview();
        break;

    case 'deleteReview':
        $review = new ReviewController();
        $review->deleteReview();
        break;

    case 'markHelpful':
        $review = new ReviewController();
        $review->markHelpful();
        break;

    // ===== USER PROFILE ROUTES =====
    case 'viewProfile':
        $profile = new UserProfileController();
        $profile->viewProfile();
        break;

    case 'editProfile':
        $profile = new UserProfileController();
        $profile->editProfile();
        break;

    case 'updateProfile':
        $profile = new UserProfileController();
        $profile->updateProfile();
        break;

    case 'changePassword':
        $profile = new UserProfileController();
        $profile->changePassword();
        break;

    case 'myReviews':
        $profile = new UserProfileController();
        $profile->myReviews();
        break;

    case 'myPayments':
        $profile = new UserProfileController();
        $profile->myPayments();
        break;

    case 'downloadInvoice':
        $profile = new UserProfileController();
        $profile->downloadInvoice();
        break;

    // ===== ADMIN DASHBOARD ROUTES =====
    case 'dashboard':
        $admin = new AdminController();
        $admin->dashboard();
        break;

    // ===== ADMIN TOUR MANAGEMENT ROUTES =====
    case 'manageTours':
        $admin = new AdminController();
        $admin->manageTours();
        break;

    case 'addTourForm':
        $admin = new AdminController();
        $admin->addTourForm();
        break;

    case 'editTourForm':
        $tourId = $_GET['id'] ?? null;
        $admin = new AdminController();
        $admin->editTourForm($tourId);
        break;

    case 'saveTour':
        $admin = new AdminController();
        $admin->saveTour();
        break;

    case 'deleteTour':
        $admin = new AdminController();
        $admin->deleteTour();
        break;

    // ===== ADMIN BOOKING MANAGEMENT ROUTES =====
    case 'manageBookings':
        $admin = new AdminController();
        $admin->manageBookings();
        break;

    case 'confirmBookingAdmin':
        $admin = new AdminController();
        $admin->confirmBooking();
        break;

    case 'cancelBookingAdmin':
        $admin = new AdminController();
        $admin->cancelBookingAdmin();
        break;

    case 'completeBooking':
        $admin = new AdminController();
        $admin->completeBooking();
        break;

    // ===== ADMIN PAYMENT MANAGEMENT ROUTES =====
    case 'managePayments':
        $admin = new AdminController();
        $admin->managePayments();
        break;

    case 'paymentDetail':
        $admin = new AdminController();
        $admin->paymentDetail();
        break;

    // ===== ADMIN CUSTOMER MANAGEMENT ROUTES =====
    case 'manageCustomers':
        $admin = new AdminController();
        $admin->manageCustomers();
        break;

    case 'blockCustomer':
        $admin = new AdminController();
        $admin->blockCustomer();
        break;

    case 'unblockCustomer':
        $admin = new AdminController();
        $admin->unblockCustomer();
        break;

    case 'customerDetail':
        $admin = new AdminController();
        $admin->customerDetail();
        break;

    // ===== ADMIN REVIEW MANAGEMENT ROUTES =====
    case 'manageReviews':
        $admin = new AdminController();
        $admin->manageReviews();
        break;

    case 'approveReview':
        $admin = new AdminController();
        $admin->approveReview();
        break;

    case 'rejectReview':
        $admin = new AdminController();
        $admin->rejectReview();
        break;

    case 'deleteReviewAdmin':
        $admin = new AdminController();
        $admin->deleteReviewAdmin();
        break;

    // ===== ADMIN CATEGORY MANAGEMENT ROUTES =====
    case 'manageCategories':
        $admin = new AdminController();
        $admin->manageCategories();
        break;

    case 'saveCategory':
        $admin = new AdminController();
        $admin->saveCategory();
        break;

    case 'deleteCategory':
        $admin = new AdminController();
        $admin->deleteCategory();
        break;

    // ===== ADMIN PROMOTION MANAGEMENT ROUTES =====
    case 'managePromotions':
        $admin = new AdminController();
        $admin->managePromotions();
        break;

    case 'savePromotion':
        $admin = new AdminController();
        $admin->savePromotion();
        break;

    case 'deletePromotion':
        $admin = new AdminController();
        $admin->deletePromotion();
        break;

    // ===== DEFAULT =====
    default:
        echo "404 Not Found";
        break;
}
