name=views/layout/user-layout.php
<?php
// User Layout Wrapper (simpler than admin)
require_once __DIR__ . '/header.php';
?>

<style>
    .user-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .user-wrapper .main-content {
        flex: 1;
        padding: 30px 20px;
    }

    .breadcrumb-section {
        background-color: #f8f9fa;
        padding: 20px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #dee2e6;
    }

    .breadcrumb-section .breadcrumb {
        margin-bottom: 0;
        background-color: transparent;
    }
</style>

<div class="user-wrapper">
    <!-- BREADCRUMB (Optional) -->
    <?php if (!empty($breadcrumbs)): ?>
        <div class="breadcrumb-section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?action=home">Trang Chủ</a></li>
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <?php if (isset($crumb['url'])): ?>
                                <li class="breadcrumb-item"><a href="<?php echo $crumb['url']; ?>"><?php echo htmlspecialchars($crumb['label']); ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active"><?php echo htmlspecialchars($crumb['label']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            </div>
        </div>
    <?php endif; ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container">
            <?php echo $content ?? ''; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
