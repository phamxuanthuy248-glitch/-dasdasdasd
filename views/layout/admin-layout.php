name=views/layout/admin-layout.php
<?php
// Admin Layout Wrapper
define('IS_ADMIN_PAGE', true);
require_once __DIR__ . '/header.php';
?>

<style>
    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .admin-wrapper .sidebar {
        flex: 0 0 250px;
        overflow-y: auto;
    }

    .admin-wrapper .main-content {
        flex: 1;
        overflow-y: auto;
        padding: 30px 20px;
    }

    @media (max-width: 768px) {
        .admin-wrapper {
            flex-direction: column;
        }

        .admin-wrapper .sidebar {
            flex: 0 0 auto;
            position: fixed;
            left: -250px;
            top: 0;
            width: 250px;
            height: 100%;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .admin-wrapper .main-content {
            width: 100%;
        }

        .mobile-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 500;
            width: 50px;
            height: 50px;
        }
    }
</style>

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Mobile Toggle Button -->
        <button class="btn btn-primary d-md-none mobile-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- PAGE CONTENT -->
        <div class="container-fluid">
            <?php echo $content ?? ''; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
