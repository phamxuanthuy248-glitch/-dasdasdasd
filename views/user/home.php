<?php
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>

    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">🌍 Tour Du Lịch</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">
                        Xin chào, <b><?= $user['name'] ?? 'User' ?></b>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white ms-2" href="index.php?action=logout">
                        Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== CONTENT ===== -->
<div class="container mt-4">

    <!-- WELCOME -->
    <div class="alert alert-success">
        <h4>🎉 Chào mừng bạn đến với Website Du Lịch!</h4>
        <p>Bạn đã đăng nhập thành công.</p>
    </div>

    <!-- USER INFO -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            Thông tin tài khoản
        </div>
        <div class="card-body">
            <p><b>Tên:</b> <?= $user['name'] ?></p>
            <p><b>Email:</b> <?= $user['email'] ?></p>
            <p><b>Vai trò:</b> <?= $user['role'] ?></p>
        </div>
    </div>

    <!-- TOUR LIST DEMO -->
    <div class="card">
        <div class="card-header bg-warning">
            🌴 Danh sách tour nổi bật
        </div>
        <div class="card-body">

            <div class="row">

                <!-- TOUR 1 -->
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="https://picsum.photos/300/200" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Tour Đà Nẵng</h5>
                            <p class="card-text">3 ngày 2 đêm</p>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>

                <!-- TOUR 2 -->
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="https://picsum.photos/300/201" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Tour Phú Quốc</h5>
                            <p class="card-text">4 ngày 3 đêm</p>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>

                <!-- TOUR 3 -->
                <div class="col-md-4">
                    <div class="card mb-3">
                        <img src="https://picsum.photos/300/202" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Tour Đà Lạt</h5>
                            <p class="card-text">2 ngày 1 đêm</p>
                            <button class="btn btn-primary">Xem chi tiết</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- ===== FOOTER ===== -->
<footer class="bg-dark text-white text-center p-3 mt-4">
    © 2026 - Website Tour Du Lịch
</footer>

</body>
</html>