<?php require_once __DIR__ . '/../../backend/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nạp Thẻ - Cổng thanh toán Garena</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        const BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/">
                    <img src="<?php echo BASE_URL; ?>/frontend/img/garena-logo.png" alt="Garena">
                </a>
            </div>
            <div class="user-menu">
                <a href="#">Chăm sóc khách hàng</a>
                <a href="#" class="login-btn" id="login-btn">
                    <i class="fa-solid fa-user"></i>
                    <span>Đăng nhập</span>
                </a>
            </div>
        </div>
    </header>

    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Đăng nhập</h2>
            <div class="login-options">
                <a href="#" class="login-option" id="garena-login-btn">
                    <img src="<?php echo BASE_URL; ?>/frontend/img/garena.jpg" alt="Garena">
                    <span>Garena</span>
                </a>
                <a href="#" class="login-option facebook" id="facebook-login-btn">
                    <i class="fab fa-facebook-square"></i>
                    <span>Facebook</span>
                </a>
            </div>

            <form id="garena-login-form" class="login-form" style="display: none;">
                <h3>Đăng nhập Garena</h3>
                <input type="text" placeholder="Tên tài khoản hoặc email" required>
                <input type="password" placeholder="Mật khẩu" required>
                <button type="submit" class="submit-btn">Đăng nhập</button>
            </form>

            <form id="facebook-login-form" class="login-form" style="display: none;">
                <h3>Đăng nhập Facebook</h3>
                <input type="email" placeholder="Email hoặc số điện thoại" required>
                <input type="password" placeholder="Mật khẩu" required>
                <button type="submit" class="submit-btn">Đăng nhập</button>
            </form>
        </div>
    </div>
