<?php

/**
 * Cấu hình hệ thống
 * File này chứa các cấu hình cơ bản cho hệ thống
 */

// Cấu hình cơ sở dữ liệu
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456789');
define('DB_NAME', 'garena_db');

// Cấu hình đường dẫn
define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', '/Garena');

// Cấu hình session
session_start();

// Hàm kết nối cơ sở dữ liệu
function connectDB()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Đặt charset
    $conn->set_charset("utf8");

    return $conn;
}

// Hàm lấy thông tin người dùng hiện tại
function getCurrentUser()
{
    if (isset($_SESSION['user_id'])) {
        // Truy vấn thông tin người dùng từ cơ sở dữ liệu
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user['is_logged_in'] = true;
            return $user;
        }

        // Nếu không tìm thấy người dùng, xóa session
        session_unset();
        session_destroy();
    }

    return null;
}

// Hàm kiểm tra đăng nhập
function isLoggedIn()
{
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

// Hàm chuyển hướng với thông báo
function redirectWithMessage($url, $message, $type = 'success')
{
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];

    header("Location: $url");
    exit;
}
