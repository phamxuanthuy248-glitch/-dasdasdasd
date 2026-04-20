<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = $_GET['action'] ?? 'dashboard';
?>

<!-- Admin Sidebar -->
<aside class="sidebar bg-dark p-0" style="height: 100vh; position: fixed; left: 0; top: 70px; width: 260px; overflow-y: auto; z-index: 999;">
    
    <!-- Sidebar Header -->
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <h5 class="text-white mb-0 d-flex align-items-center">
            <i class="fas fa-cog me-2 text-info"></i>
            <span>Admin Panel</span>
        </h5>
    </div>

    <!-- Sidebar Menu -->
    <nav class="sidebar-menu p-3">
        
        <!-- Dashboard -->
        <a href="<?php echo BASE_URL; ?>?action=dashboard" 
           class="sidebar-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- ===== QUẢN LÝ TOUR ===== -->
        <div class="sidebar-group">
            <a href="#tourMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse" 
               aria-expanded="<?php echo strpos($current_page, 'Tour') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-map-location-dot"></i>
                <span>Quản Lý Tour</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Tour') !== false ? 'show' : ''; ?>" id="tourMenu">
                <a href="<?php echo BASE_URL; ?>?action=manageTours" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'manageTours' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Danh Sách Tours</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=addTourForm" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'addTourForm' ? 'active' : ''; ?>">
                    <i class="fas fa-plus"></i>
                    <span>Thêm Tour Mới</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageCategories" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'manageCategories' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Danh Mục Tours</span>
                </a>
            </div>
        </div>

        <!-- ===== QUẢN LÝ ĐƠN ĐẶT ===== -->
        <div class="sidebar-group">
            <a href="#bookingMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse"
               aria-expanded="<?php echo strpos($current_page, 'Booking') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-ticket"></i>
                <span>Quản Lý Đơn Đặt</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Booking') !== false ? 'show' : ''; ?>" id="bookingMenu">
                <a href="<?php echo BASE_URL; ?>?action=manageBookings" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'manageBookings' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Danh Sách Đơn</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageBookings&status=pending" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-hourglass"></i>
                    <span>Chờ Xác Nhận</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageBookings&status=confirmed" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-check-circle"></i>
                    <span>Đã Xác Nhận</span>
                </a>
            </div>
        </div>

        <!-- ===== QUẢN LÝ THANH TOÁN ===== -->
        <div class="sidebar-group">
            <a href="#paymentMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse"
               aria-expanded="<?php echo strpos($current_page, 'Payment') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-credit-card"></i>
                <span>Quản Lý Thanh Toán</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Payment') !== false ? 'show' : ''; ?>" id="paymentMenu">
                <a href="<?php echo BASE_URL; ?>?action=managePayments" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'managePayments' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Lịch Sử Thanh Toán</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=managePayments&status=success" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-check"></i>
                    <span>Thành Công</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=managePayments&status=failed" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-times"></i>
                    <span>Thất Bại</span>
                </a>
            </div>
        </div>

        <!-- ===== QUẢN LÝ KHÁCH HÀNG ===== -->
        <div class="sidebar-group">
            <a href="#customerMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse"
               aria-expanded="<?php echo strpos($current_page, 'Customer') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-users"></i>
                <span>Quản Lý Khách Hàng</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Customer') !== false ? 'show' : ''; ?>" id="customerMenu">
                <a href="<?php echo BASE_URL; ?>?action=manageCustomers" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'manageCustomers' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Danh Sách Khách</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageCustomers&status=active" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-user-check"></i>
                    <span>Đang Hoạt Động</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageCustomers&status=blocked" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-user-slash"></i>
                    <span>Bị Chặn</span>
                </a>
            </div>
        </div>

        <!-- ===== QUẢN LÝ ĐÁNH GIÁ ===== -->
        <div class="sidebar-group">
            <a href="#reviewMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse"
               aria-expanded="<?php echo strpos($current_page, 'Review') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-star"></i>
                <span>Quản Lý Đánh Giá</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Review') !== false ? 'show' : ''; ?>" id="reviewMenu">
                <a href="<?php echo BASE_URL; ?>?action=manageReviews" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'manageReviews' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Danh Sách Đánh Giá</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageReviews&status=pending" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-hourglass"></i>
                    <span>Chờ Duyệt</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=manageReviews&status=approved" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-check-circle"></i>
                    <span>Đã Duyệt</span>
                </a>
            </div>
        </div>

        <!-- ===== QUẢN LÝ KHUYẾN MÃI ===== -->
        <div class="sidebar-group">
            <a href="#promotionMenu" class="sidebar-item dropdown-toggle" data-bs-toggle="collapse"
               aria-expanded="<?php echo strpos($current_page, 'Promotion') !== false ? 'true' : 'false'; ?>">
                <i class="fas fa-gift"></i>
                <span>Quản Lý Khuyến Mãi</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse <?php echo strpos($current_page, 'Promotion') !== false ? 'show' : ''; ?>" id="promotionMenu">
                <a href="<?php echo BASE_URL; ?>?action=managePromotions" 
                   class="sidebar-item ps-5 <?php echo $current_page === 'managePromotions' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span>Danh Sách Khuyến Mãi</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?action=savePromotion" 
                   class="sidebar-item ps-5">
                    <i class="fas fa-plus"></i>
                    <span>Thêm Khuyến Mãi</span>
                </a>
            </div>
        </div>

        <hr class="bg-secondary my-3">

        <!-- Settings -->
        <a href="#" class="sidebar-item">
            <i class="fas fa-sliders-h"></i>
            <span>Cài Đặt</span>
        </a>

        <!-- Reports -->
        <a href="#" class="sidebar-item">
            <i class="fas fa-chart-bar"></i>
            <span>Báo Cáo</span>
        </a>

    </nav>

</aside>

<!-- Styling for Sidebar -->
<style>
    .sidebar {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        box-shadow: 2px 0 8px rgba(0,0,0,0.15);
        scrollbar-width: thin;
        scrollbar-color: #0066cc #1a1a2e;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #1a1a2e;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #0066cc;
        border-radius: 3px;
    }

    .sidebar-header {
        border-bottom: 2px solid #0066cc;
    }

    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #adb5bd;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        gap: 10px;
        font-size: 0.95rem;
    }

    .sidebar-item i {
        width: 20px;
        text-align: center;
    }

    .sidebar-item:hover {
        color: white;
        background-color: rgba(0, 102, 204, 0.1);
        padding-left: 20px;
    }

    .sidebar-item.active {
        background-color: #0066cc;
        color: white;
        font-weight: 600;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .sidebar-item.active i {
        color: #00d4ff;
    }

    .sidebar-group {
        margin-bottom: 5px;
    }

    .sidebar-item.dropdown-toggle::after {
        content: '';
    }

    .sidebar-item.dropdown-toggle .fa-chevron-down {
        transition: transform 0.3s ease;
    }

    .sidebar-item.dropdown-toggle[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }

    /* Main content adjustment */
    main {
        margin-left: 260px;
        transition: margin-left 0.3s ease;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 0;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .sidebar.show {
            width: 260px;
        }

        main {
            margin-left: 0;
        }

        .sidebar-item {
            padding: 15px 20px;
            font-size: 1rem;
        }
    }
</style>
