<?php

/**
 * API Endpoints
 * Định nghĩa các endpoint API cho hệ thống
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/GameController.php';
require_once __DIR__ . '/../controllers/TransactionController.php';

// Thiết lập header cho CORS và JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Xử lý OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Lấy phương thức và đường dẫn
$method = $_SERVER['REQUEST_METHOD'];

// Xử lý PATH_INFO an toàn hơn cho các endpoint khác
$path_info = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '';
$path_info = parse_url($path_info, PHP_URL_PATH);
$api_prefix = '/backend/api';
if (strpos($path_info, $api_prefix) === 0) {
    $path_info = substr($path_info, strlen($api_prefix));
}
$request = explode('/', trim($path_info, '/'));

// Endpoint đầu tiên (ví dụ: users, games, transactions)
$endpoint = $request[0] ?? '';

// ID nếu có (ví dụ: /users/1)
$id = $request[1] ?? null;

// Action nếu có (ví dụ: /users/someaction)
$action = $id;

// Chuẩn bị phản hồi
$response = [
    'success' => false,
    'message' => 'Endpoint không hợp lệ',
    'data' => null
];

// Xử lý endpoint users
if ($endpoint === 'users') {
    $userController = new UserController();

    if ($method === 'POST') {
        // Lấy dữ liệu từ request body
        $data = json_decode(file_get_contents('php://input'), true);

        if ($action === 'login') {
            // Đăng nhập
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';
            $response = $userController->login($username, $password);
        } elseif ($action === 'register') {
            // Đăng ký
            $username = $data['username'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $fullName = $data['full_name'] ?? '';
            $response = $userController->register($username, $email, $password, $fullName);
        } elseif ($action === 'logout') {
            // Đăng xuất
            $response = $userController->logout();
        } else {
            // Tạo người dùng mới
            $username = $data['username'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';
            $fullName = $data['full_name'] ?? '';
            $response = [
                'success' => false,
                'message' => 'Method not implemented',
                'data' => null
            ];
        }
    } elseif ($method === 'GET') {
        if ($id === 'current') {
            // Get current authenticated user info from session/token
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            $response = [
                'success' => false,
                'message' => 'Method not implemented',
                'data' => null
            ];
        } elseif (is_numeric($id)) {
            // Lấy thông tin người dùng theo ID
            $response = [
                'success' => false,
                'message' => 'Method not implemented',
                'data' => null
            ];
        } else {
            // Lấy danh sách người dùng
            $response = [
                'success' => false,
                'message' => 'Method not implemented',
                'data' => null
            ];
        }
    } elseif ($method === 'PUT' && is_numeric($id)) {
        // Cập nhật thông tin người dùng
        $data = json_decode(file_get_contents('php://input'), true);
        $response = [
            'success' => false,
            'message' => 'Method not implemented',
            'data' => null
        ];
    } elseif ($method === 'DELETE' && is_numeric($id)) {
        // Xóa người dùng
        $response = [
            'success' => false,
            'message' => 'Method not implemented',
            'data' => null
        ];
    }
}

// Xử lý endpoint games
elseif ($endpoint === 'games') {
    $gameController = new GameController();

    if ($method === 'GET') {
        if (is_numeric($id)) {
            // Lấy thông tin game theo ID
            $response = [
                'success' => true,
                'data' => $gameController->getGameById($id)
            ];
        } elseif ($action === 'featured') {
            // Lấy danh sách game nổi bật
            $limit = $_GET['limit'] ?? 5;
            $response = [
                'success' => true,
                'data' => $gameController->getFeaturedGames($limit)
            ];
        } elseif ($action === 'search') {
            // Tìm kiếm game
            $query = $_GET['q'] ?? '';
            $response = [
                'success' => true,
                'data' => $gameController->searchGames($query)
            ];
        } elseif ($action === 'genre') {
            // Lấy danh sách game theo thể loại
            $genre = $_GET['type'] ?? '';
            $response = [
                'success' => true,
                'data' => $gameController->getGamesByGenre($genre)
            ];
        } else {
            // Lấy danh sách tất cả game
            $response = [
                'success' => true,
                'data' => $gameController->getAllGames()
            ];
        }
    }
}

// Xử lý endpoint transactions
elseif ($endpoint === 'transactions') {
    // Debug thông tin request
    error_log("Transactions endpoint called with method: $method");
    error_log("Path info: $path_info");
    error_log("Endpoint: $endpoint, Action: $action");
    
    $transactionController = new TransactionController();

    if ($method === 'POST') {
        // Lấy dữ liệu từ request body
        $raw_data = file_get_contents('php://input');
        error_log("Raw request data: $raw_data");
        $data = json_decode($raw_data, true);
        error_log("Decoded data: " . print_r($data, true));

        if ($action === 'deposit-card') {
            // Nạp thẻ cào
            $cardCode = $data['card_code'] ?? '';
            $serialNumber = $data['serial_number'] ?? '';
            $amount = $data['amount'] ?? 0;
            $gameType = $data['game_type'] ?? '';
            $response = $transactionController->depositCard(0, $cardCode, $serialNumber, $amount, $gameType);
        } elseif ($action === 'deposit') {
            // Nạp tiền
            $userId = $data['user_id'] ?? '';
            $amount = $data['amount'] ?? 0;
            $paymentMethod = $data['payment_method'] ?? '';
            $response = $transactionController->deposit($userId, $amount, $paymentMethod);
        } elseif ($action === 'purchase') {
            // Mua game hoặc nạp tiền vào game
            $userId = $data['user_id'] ?? '';
            $gameId = $data['game_id'] ?? '';
            $amount = $data['amount'] ?? 0;
            $response = $transactionController->purchase($userId, $gameId, $amount);
        } elseif ($action === 'check') {
            // Kiểm tra trạng thái giao dịch
            $transactionCode = $data['transaction_code'] ?? '';
            $response = $transactionController->checkTransactionStatus($transactionCode);
        }
    } elseif ($method === 'GET') {
        if ($action === 'history') {
            // Lấy lịch sử giao dịch
            $userId = $_GET['user_id'] ?? '';
            $response = [
                'success' => true,
                'data' => $transactionController->getTransactionHistory($userId)
            ];
        } elseif ($action === 'check') {
            // Kiểm tra trạng thái giao dịch
            $transactionCode = $_GET['code'] ?? '';
            $response = $transactionController->checkTransactionStatus($transactionCode);
        } elseif ($action === 'revenue') {
            // Lấy doanh thu hàng tháng
            $response = $transactionController->getMonthlyRevenue();
        }
    }
}

// Trả về phản hồi dạng JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
