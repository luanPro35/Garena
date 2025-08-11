<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/GameController.php';

// Kiểm tra đăng nhập và quyền admin
$user = getCurrentUser();
if (!$user || $user['role'] !== 'admin') {
    redirectWithMessage(BASE_URL . '/frontend/index.php', 'Bạn không có quyền truy cập trang này', 'error');
    exit;
}

// Khởi tạo controller
$gameController = new GameController();

// Lấy danh sách game
try {
    $games = $gameController->getAllGames();
} catch (Exception $e) {
    $games = [];
}

// Tiêu đề trang
$pageTitle = 'Quản Lý Game';

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
                    <a href="users.php" class="list-group-item list-group-item-action">Quản Lý Người Dùng</a>
                    <a href="games.php" class="list-group-item list-group-item-action active">Quản Lý Game</a>
                    <a href="transactions.php" class="list-group-item list-group-item-action">Quản Lý Giao Dịch</a>
                    <a href="promotions.php" class="list-group-item list-group-item-action">Quản Lý Khuyến Mãi</a>
                    <a href="settings.php" class="list-group-item list-group-item-action">Cài Đặt Hệ Thống</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Danh sách game -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh Sách Game</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addGameModal">
                        <i class="bi bi-plus-circle"></i> Thêm Game
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($games)): ?>
                    <div class="alert alert-info">Chưa có game nào.</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình Ảnh</th>
                                    <th>Tên Game</th>
                                    <th>Thể Loại</th>
                                    <th>Giá</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($games as $game): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($game['id'] ?? ''); ?></td>
                                    <td>
                                        <?php if (isset($game['image']) && !empty($game['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['name'] ?? ''); ?>" width="50">
                                        <?php else: ?>
                                        <span class="text-muted">Không có ảnh</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($game['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($game['genre'] ?? ''); ?></td>
                                    <td><?php echo number_format($game['price'] ?? 0); ?> VND</td>
                                    <td>
                                        <?php if (isset($game['status']) && $game['status'] == 'active'): ?>
                                            <span class="badge bg-success">Hoạt Động</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editGame(<?php echo $game['id']; ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteGame(<?php echo $game['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
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

<!-- Modal Thêm Game -->
<div class="modal fade" id="addGameModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGameModalLabel">Thêm Game Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addGameForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Game</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Thể Loại</label>
                        <input type="text" class="form-control" id="genre" name="genre" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình Ảnh URL</label>
                        <input type="text" class="form-control" id="image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô Tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveGame()">Lưu</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Các hàm JavaScript để xử lý thêm, sửa, xóa game
    function editGame(gameId) {
        // Xử lý sửa game
        alert('Chức năng đang được phát triển');
    }
    
    function deleteGame(gameId) {
        // Xử lý xóa game
        if (confirm('Bạn có chắc chắn muốn xóa game này?')) {
            alert('Chức năng đang được phát triển');
        }
    }
    
    function saveGame() {
        // Xử lý lưu game mới
        alert('Chức năng đang được phát triển');
        $('#addGameModal').modal('hide');
    }
</script>

<?php
// Bao gồm footer
include_once __DIR__ . '/../footer.php';
?>