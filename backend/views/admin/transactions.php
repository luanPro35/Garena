<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/TransactionController.php';

// Kiểm tra đăng nhập và quyền admin
$user = getCurrentUser();
if (!$user || $user['role'] !== 'admin') {
    redirectWithMessage(BASE_URL . '/frontend/index.php', 'Bạn không có quyền truy cập trang này', 'error');
    exit;
}

// Khởi tạo controller
$transactionController = new TransactionController();

// Lấy danh sách giao dịch
try {
    $transactions = $transactionController->getRecentTransactions(50);
    if (!is_array($transactions)) {
        $transactions = [];
    }
} catch (Exception $e) {
    $transactions = [];
}

// Tiêu đề trang
$pageTitle = 'Quản Lý Giao Dịch';

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
                    <a href="games.php" class="list-group-item list-group-item-action">Quản Lý Game</a>
                    <a href="transactions.php" class="list-group-item list-group-item-action active">Quản Lý Giao Dịch</a>
                    <a href="promotions.php" class="list-group-item list-group-item-action">Quản Lý Khuyến Mãi</a>
                    <a href="settings.php" class="list-group-item list-group-item-action">Cài Đặt Hệ Thống</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Danh sách giao dịch -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh Sách Giao Dịch</h5>
                    <div>
                        <button class="btn btn-light btn-sm" onclick="exportTransactions()">
                            <i class="bi bi-download"></i> Xuất Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filterType">
                                <option value="">Tất Cả Loại</option>
                                <option value="deposit">Nạp Tiền</option>
                                <option value="purchase">Mua Game</option>
                                <option value="refund">Hoàn Tiền</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tất Cả Trạng Thái</option>
                                <option value="completed">Hoàn Thành</option>
                                <option value="pending">Đang Xử Lý</option>
                                <option value="failed">Thất Bại</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchQuery" placeholder="Tìm kiếm...">
                                <button class="btn btn-primary" type="button" onclick="searchTransactions()">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" onclick="resetFilters()">
                                <i class="bi bi-x-circle"></i> Đặt Lại
                            </button>
                        </div>
                    </div>
                    
                    <?php if (empty($transactions)): ?>
                    <div class="alert alert-info">Chưa có giao dịch nào.</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã Giao Dịch</th>
                                    <th>Người Dùng</th>
                                    <th>Loại</th>
                                    <th>Số Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Thời Gian</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['transaction_code'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['username'] ?? ''); ?></td>
                                    <td>
                                        <?php if (isset($transaction['transaction_type']) && $transaction['transaction_type'] == 'deposit'): ?>
                                            <span class="badge bg-success">Nạp Tiền</span>
                                        <?php elseif (isset($transaction['transaction_type']) && $transaction['transaction_type'] == 'purchase'): ?>
                                            <span class="badge bg-primary">Mua Game</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Hoàn Tiền</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo number_format($transaction['amount'] ?? 0); ?> VND</td>
                                    <td>
                                        <?php if (isset($transaction['status']) && $transaction['status'] == 'completed'): ?>
                                            <span class="badge bg-success">Hoàn Thành</span>
                                        <?php elseif (isset($transaction['status']) && $transaction['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Đang Xử Lý</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Thất Bại</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo isset($transaction['created_at']) ? date('d/m/Y H:i', strtotime($transaction['created_at'])) : ''; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewTransaction('<?php echo $transaction['transaction_code'] ?? ''; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if (isset($transaction['status']) && $transaction['status'] == 'pending'): ?>
                                        <button class="btn btn-sm btn-success" onclick="approveTransaction('<?php echo $transaction['transaction_code'] ?? ''; ?>')">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectTransaction('<?php echo $transaction['transaction_code'] ?? ''; ?>')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        <?php endif; ?>
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

<script>
    // Các hàm JavaScript để xử lý giao dịch
    function viewTransaction(transactionCode) {
        // Xử lý xem chi tiết giao dịch
        alert('Xem chi tiết giao dịch: ' + transactionCode);
    }
    
    function approveTransaction(transactionCode) {
        // Xử lý duyệt giao dịch
        if (confirm('Bạn có chắc chắn muốn duyệt giao dịch này?')) {
            alert('Chức năng đang được phát triển');
        }
    }
    
    function rejectTransaction(transactionCode) {
        // Xử lý từ chối giao dịch
        if (confirm('Bạn có chắc chắn muốn từ chối giao dịch này?')) {
            alert('Chức năng đang được phát triển');
        }
    }
    
    function exportTransactions() {
        // Xử lý xuất Excel
        alert('Chức năng đang được phát triển');
    }
    
    function searchTransactions() {
        // Xử lý tìm kiếm giao dịch
        alert('Chức năng đang được phát triển');
    }
    
    function resetFilters() {
        // Đặt lại bộ lọc
        document.getElementById('filterType').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('searchQuery').value = '';
        alert('Đã đặt lại bộ lọc');
    }
</script>

<?php
// Bao gồm footer
include_once __DIR__ . '/../footer.php';
?>