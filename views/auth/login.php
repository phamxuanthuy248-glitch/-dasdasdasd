<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            height: 100vh;
        }

        .login-box {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<div class="container">
    <div class="login-box">

        <h3 class="text-center mb-4">🔐 Đăng nhập</h3>

        <!-- FORM -->
        <form method="POST" action="index.php?action=handleLogin">

            <!-- EMAIL -->
            <div class="mb-3">
                <label>Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Nhập email"
                    required
                >
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label>Mật khẩu</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Nhập mật khẩu"
                    required
                >
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn btn-primary w-100">
                Đăng nhập
            </button>

        </form>

        <!-- REGISTER -->
        <div class="text-center mt-3">
            <small>
                Chưa có tài khoản? 
                <a href="index.php?action=register">Đăng ký</a>
            </small>
        </div>

    </div>
</div>

</body>
</html>