<?php
// Get current user session
$currentUser = $_SESSION['user'] ?? null;
$isAdmin = $currentUser && $currentUser['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>Hệ Thống Quản Lý Tour Du Lịch</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #0066cc;
            --secondary-color: #00d4ff;
            --danger-color: #ff4757;
            --success-color: #2ed573;
            --warning-color: #ffa502;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0047a3 100%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s ease;
            margin: 0 5px;
        }
        
        .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .nav-link.active {
            color: var(--secondary-color) !important;
            border-bottom: 2px solid var(--secondary-color);
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: background-color 0.3s ease;
        }
        
        .user-avatar:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        .dropdown-menu {
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert {
            margin-top: 20px;
            animation: slideDown 0.3s ease;
        }
        
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,.5%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php?action=home">
            <i class="fas fa-plane"></i> Tour Du Lịch
        </a>
        
        <!-- Toggle Button (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Public Navigation -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=home">
                        <i class="fas fa-home"></i> Trang Chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=tours">
                        <i class="fas fa-map"></i> Tour
                    </a>
                </li>
                
                <!-- User Menu (Logged In) -->
                <?php if ($currentUser): ?>
                    <?php if ($isAdmin): ?>
                        <!-- Admin Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Quản Lý
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminMenu">
                                <li><h6 class="dropdown-header">Quản Lý Hệ Thống</h6></li>
                                <li><a class="dropdown-item" href="index.php?action=dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Nội Dung</h6></li>
                                <li><a class="dropdown-item" href="index.php?action=manageTours"><i class="fas fa-map"></i> Tour</a></li>
                                <li><a class="dropdown-item" href="index.php?action=manageCategories"><i class="fas fa-list"></i> Loại Tour</a></li>
                                <li><a class="dropdown-item" href="index.php?action=managePromotions"><i class="fas fa-tag"></i> Khuyến Mãi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Đơn Hàng & Thanh Toán</h6></li>
                                <li><a class="dropdown-item" href="index.php?action=manageBookings"><i class="fas fa-clipboard-list"></i> Booking</a></li>
                                <li><a class="dropdown-item" href="index.php?action=managePayments"><i class="fas fa-credit-card"></i> Thanh Toán</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Quản Lý</h6></li>
                                <li><a class="dropdown-item" href="index.php?action=manageCustomers"><i class="fas fa-users"></i> Khách Hàng</a></li>
                                <li><a class="dropdown-item" href="index.php?action=manageReviews"><i class="fas fa-star"></i> Đánh Giá</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-menu" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="ms-2"><?php echo htmlspecialchars($currentUser['username']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><h6 class="dropdown-header"><?php echo htmlspecialchars($currentUser['email']); ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (!$isAdmin): ?>
                                <li><a class="dropdown-item" href="index.php?action=viewProfile"><i class="fas fa-user-circle"></i> Hồ Sơ</a></li>
                                <li><a class="dropdown-item" href="index.php?action=myBookings"><i class="fas fa-list"></i> Đơn Của Tôi</a></li>
                                <li><a class="dropdown-item" href="index.php?action=myReviews"><i class="fas fa-star"></i> Đánh Giá</a></li>
                                <li><a class="dropdown-item" href="index.php?action=myPayments"><i class="fas fa-wallet"></i> Thanh Toán</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="index.php?action=editProfile"><i class="fas fa-edit"></i> Chỉnh Sửa Hồ Sơ</a></li>
                            <li><a class="dropdown-item" href="index.php?action=changePassword"><i class="fas fa-key"></i> Đổi Mật Khẩu</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Auth Links (Not Logged In) -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=login">
                            <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light btn-sm ms-2" href="index.php?action=register">
                            <i class="fas fa-user-plus"></i> Đăng Ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ALERTS (Session Messages) -->
<div class="container-fluid mt-3">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($_SESSION['info']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['warning']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['warning']); ?>
    <?php endif; ?>
</div>
