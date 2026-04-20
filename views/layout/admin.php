name=views/layout/sidebar.php
<?php
// Admin Sidebar Navigation
$currentUser = $_SESSION['user'] ?? null;
$isAdmin = $currentUser && $currentUser['role'] === 'admin';

// Get current action for active menu highlighting
$currentAction = $_GET['action'] ?? 'dashboard';
?>

<style>
    .sidebar {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
        min-height: 100vh;
        padding-top: 20px;
        position: sticky;
        top: 0;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 2px solid #00d4ff;
        margin-bottom: 20px;
    }

    .sidebar-header h5 {
        color: #00d4ff;
        margin: 0;
        font-weight: bold;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 0;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #adb5bd;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar-menu a:hover {
        background-color: rgba(0, 212, 255, 0.1);
        color: #00d4ff;
        border-left-color: #00d4ff;
        padding-left: 25px;
    }

    .sidebar-menu a.active {
        background-color: rgba(0, 212, 255, 0.2);
        color: #00d4ff;
        border-left-color: #00d4ff;
        font-weight: 600;
    }

    .sidebar-menu i {
        width: 25px;
        text-align: center;
        margin-right: 12px;
    }

    .sidebar-section-title {
        padding: 15px 20px 8px 20px;
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 0.5px;
        margin-top: 20px;
        margin-bottom: 5px;
    }

    .sidebar-submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: none;
        background-color: rgba(0, 0, 0, 0.1);
    }

    .sidebar-submenu.show {
        display: block;
    }

    .sidebar-submenu a {
        padding-left: 50px;
        font-size: 0.95rem;
    }

    .sidebar-submenu a:hover,
    .sidebar-submenu a.active {
        background-color: rgba(0, 212, 255, 0.15);
        color: #00d4ff;
    }

    .user-info {
        padding: 15px 20px;
        background-color: rgba(0, 212, 255, 0.1);
        margin: 20px 15px;
        border-radius: 8px;
        border-left: 3px solid #00d4ff;
    }

    .user-info p {
        margin: 0;
        color: #adb5bd;
        font-size: 0.9rem;
    }

    .user-info strong {
        color: #00d4ff;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -250px;
            width: 250px;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }
    }
</style>

<?php if ($isAdmin): ?>
<!-- SIDEBAR -->
<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <h5><i class="fas fa-tachometer-alt"></i> Admin Panel</h5>
    </div>

    <!-- User Info -->
    <div class="user-info">
        <p><strong><?php echo htmlspecialchars($currentUser['username'] ?? 'Admin'); ?></strong></p>
        <p><?php echo htmlspecialchars($currentUser['email'] ?? 'admin@tour.com'); ?></p>
    </div>

    <!-- Menu Items -->
    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="index.php?action=dashboard" 
               class="<?php echo $currentAction === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>

        <!-- Tour Management -->
        <li class="sidebar-section-title">QUẢN LÝ NỘI DUNG</li>
        <li>
            <a href="index.php?action=manageTours" 
               class="<?php echo in_array($currentAction, ['manageTours', 'addTourForm', 'editTourForm']) ? 'active' : ''; ?}"
               onclick="toggleSubmenu(event, 'tourMenu')">
                <i class="fas fa-map"></i> Tours
                <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="sidebar-submenu" id="tourMenu">
                <li>
                    <a href="index.php?action=manageTours" 
                       class="<?php echo $currentAction === 'manageTours' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Danh Sách Tours
                    </a>
                </li>
                <li>
                    <a href="index.php?action=addTourForm">
                        <i class="fas fa-plus"></i> Thêm Tour Mới
                    </a>
                </li>
            </ul>
        </li>

        <!-- Categories -->
        <li>
            <a href="index.php?action=manageCategories"
               class="<?php echo $currentAction === 'manageCategories' ? 'active' : ''; ?>">
                <i class="fas fa-list"></i> Loại Tour
            </a>
        </li>

        <!-- Promotions -->
        <li>
            <a href="index.php?action=managePromotions"
               class="<?php echo $currentAction === 'managePromotions' ? 'active' : ''; ?>">
                <i class="fas fa-tag"></i> Khuyến Mãi
            </a>
        </li>

        <!-- Booking & Payment -->
        <li class="sidebar-section-title">ĐƠN HÀNG & THANH TOÁN</li>
        <li>
            <a href="index.php?action=manageBookings"
               class="<?php echo $currentAction === 'manageBookings' ? 'active' : ''; ?}"
               onclick="toggleSubmenu(event, 'bookingMenu')">
                <i class="fas fa-clipboard-list"></i> Booking
                <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="sidebar-submenu" id="bookingMenu">
                <li>
                    <a href="index.php?action=manageBookings">
                        <i class="fas fa-list"></i> Danh Sách Booking
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="index.php?action=managePayments"
               class="<?php echo $currentAction === 'managePayments' ? 'active' : ''; ?>">
                <i class="fas fa-credit-card"></i> Thanh Toán
            </a>
        </li>

        <!-- User Management -->
        <li class="sidebar-section-title">QUẢN LÝ NGƯỜI DÙNG</li>
        <li>
            <a href="index.php?action=manageCustomers"
               class="<?php echo $currentAction === 'manageCustomers' ? 'active' : ''; ?}"
               onclick="toggleSubmenu(event, 'customerMenu')">
                <i class="fas fa-users"></i> Khách Hàng
                <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="sidebar-submenu" id="customerMenu">
                <li>
                    <a href="index.php?action=manageCustomers">
                        <i class="fas fa-list"></i> Danh Sách Khách
                    </a>
                </li>
            </ul>
        </li>

        <!-- Reviews -->
        <li>
            <a href="index.php?action=manageReviews"
               class="<?php echo $currentAction === 'manageReviews' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Đánh Giá
            </a>
        </li>

        <!-- Reports -->
        <li class="sidebar-section-title">BÁO CÁO & THỐNG KÊ</li>
        <li>
            <a href="index.php?action=reports" style="opacity: 0.6;">
                <i class="fas fa-chart-bar"></i> Báo Cáo
            </a>
        </li>

        <!-- Settings -->
        <li class="sidebar-section-title">CẤU HÌNH</li>
        <li>
            <a href="index.php?action=settings" style="opacity: 0.6;">
                <i class="fas fa-cog"></i> Cài Đặt
            </a>
        </li>
    </ul>
</div>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSubmenu(event, menuId) {
        event.preventDefault();
        const submenu = document.getElementById(menuId);
        if (submenu) {
            submenu.classList.toggle('show');
        }
    }

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    // Show submenu if current page is in submenu
    document.querySelectorAll('.sidebar-submenu a').forEach(link => {
        if (link.classList.contains('active')) {
            link.parentElement.parentElement.classList.add('show');
        }
    });
</script>
<?php endif; ?>
