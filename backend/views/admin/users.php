// Lấy danh sách người dùng
try {
    $usersResponse = $userController->getAllUsers();
    $users = $usersResponse['data'] ?? [];
} catch (Exception $e) {
    $users = [];
}