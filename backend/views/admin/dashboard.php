<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../controllers/GameController.php';
require_once __DIR__ . '/../../controllers/TransactionController.php';

// Kiểm tra đăng nhập và quyền admin
$user = getCurrentUser();
if (!$user || $user['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/frontend/index.php');
    exit;
}

// Khởi tạo controllers
$userController = new UserController();
$gameController = new GameController();
$transactionController = new TransactionController();

// Lấy thống kê
$totalUsers = $userController->getTotalUsers();
$allGames = $gameController->getAllGames();
$totalGames = count($allGames);
$recentTransactions = $transactionController->getRecentTransactions(10);

// Đếm giao dịch hôm nay
$todayTransactions = 0;
foreach ($recentTransactions as $transaction) {
    if (date('Y-m-d', strtotime($transaction['created_at'])) === date('Y-m-d')) {
        $todayTransactions++;
    }
}

// Tiêu đề trang
$pageTitle = 'Bảng Điều Khiển Admin';

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
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">Bảng Điều Khiển</a>
                    <a href="users.php" class="list-group-item list-group-item-action">Quản Lý Người Dùng</a>
                    <a href="games.php" class="list-group-item list-group-item-action">Quản Lý Game</a>
                    <a href="transactions.php" class="list-group-item list-group-item-action">Quản Lý Giao Dịch</a>
                    <a href="settings.php" class="list-group-item list-group-item-action">Cài Đặt Hệ Thống</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Người Dùng</h5>
                            <h2 class="display-4"><?php echo $totalUsers; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Game</h5>
                            <h2 class="display-4"><?php echo $totalGames; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Giao Dịch Hôm Nay</h5>
                            <h2 class="display-4"><?php echo $todayTransactions; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Giao dịch gần đây -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Giao Dịch Gần Đây</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentTransactions)): ?>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTransactions as $transaction): ?>
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
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Biểu đồ thống kê -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thống Kê Doanh Thu</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Lấy dữ liệu doanh thu từ API
    fetch('/backend/api/transactions/revenue')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Dữ liệu biểu đồ
                var ctx = document.getElementById('revenueChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.data.labels,
                        datasets: data.data.datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Lỗi khi lấy dữ liệu doanh thu:', error);
            // Dữ liệu mẫu nếu API lỗi
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                    datasets: [{
                        label: 'Doanh Thu (VND)',
                        data: [12000000, 19000000, 3000000, 5000000, 2000000, 3000000, 20000000, 33000000, 15000000, 23000000, 45000000, 50000000],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
</script>

<?php
// Bao gồm footer
include_once __DIR__ . '/../footer.php';
?>