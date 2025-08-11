<?php

/**
 * Header Template
 * Phần đầu của trang web, bao gồm thẻ head và phần header
 */

require_once __DIR__ . '/../config.php';

// Lấy thông tin người dùng hiện tại
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Garena - Nền tảng game hàng đầu'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/css/style.css">
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/frontend/index.php"><img src="<?php echo BASE_URL; ?>/frontend/images/garena-logo.png" alt="Garena Logo"></a>
            </div>
            <div class="nav-menu">
                <form class="search-form" action="<?php echo BASE_URL; ?>/frontend/search.php" method="get">
                    <input type="text" name="query" placeholder="Tìm kiếm game...">
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
            <div class="user-menu">
                <span>Chăm sóc khách hàng</span>
                <?php if (isLoggedIn()): ?>
                    <div class="user-dropdown">
                        <button class="user-button">
                            <img src="<?php echo BASE_URL; ?>/frontend/images/user-icon.svg" alt="User" class="user-icon">
                            <?php echo htmlspecialchars($currentUser['username']); ?>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo BASE_URL; ?>/frontend/profile.php">Tài khoản</a>
                            <a href="<?php echo BASE_URL; ?>/frontend/login.php?action=logout">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button id="login-button" class="login-button">
                        <img src="<?php echo BASE_URL; ?>/frontend/images/user-icon.svg" alt="User" class="user-icon"> Đăng nhập
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </header>
