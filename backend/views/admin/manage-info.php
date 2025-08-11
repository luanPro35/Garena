<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/UserController.php';

// Kiểm tra đăng nhập và quyền admin
$user = getCurrentUser();
if (!$user || ($user['role'] !== 'admin' && $user['username'] !== 'quangluan0305')) {
    header('Location: ' . BASE_URL . '/frontend/index.php');
    exit;
}

// Khởi tạo controller
$userController = new UserController();

// Lấy danh sách người dùng
$usersResponse = $userController->getAllUsers();
$users = $usersResponse['data'] ?? [];

// Tiêu đề trang
$pageTitle = 'Quản Lý Thông Tin Người Dùng';

// Bao gồm header
include_once __DIR__ . '/../header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu Quản Trị</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Bảng Điều Khiển</a>
                    <a href="manage-info.php" class="list-group-item list-group-item-action active">Quản Lý Thông Tin</a>
                    <a href="games.php" class="list-group-item list-group-item-action">Quản Lý Game</a>
                    <a href="transactions.php" class="list-group-item list-group-item-action">Quản Lý Giao Dịch</a>
                    <a href="settings.php" class="list-group-item list-group-item-action">Cài Đặt Hệ Thống</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Danh sách người dùng -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Danh Sách Người Dùng</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                    <div class="alert alert-info">Chưa có người dùng nào.</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email</th>
                                    <th>Vai Trò</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><?php echo htmlspecialchars($u['role']); ?></td>
                                    <td><?php echo htmlspecialchars($u['status']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($u['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Bao gồm footer
include_once __DIR__ . '/../footer.php';
?>
