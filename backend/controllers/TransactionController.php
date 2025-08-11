<?php
/**
 * TransactionController
 * Xử lý các chức năng liên quan đến giao dịch như nạp tiền, mua game
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Game.php';

class TransactionController {
    
    /**
     * Xử lý nạp tiền vào tài khoản
     */
    public function deposit($userId, $amount, $paymentMethod) {
        // Chuẩn bị phản hồi
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Kiểm tra dữ liệu đầu vào
        if (empty($userId) || empty($amount) || empty($paymentMethod)) {
            $response['message'] = 'Vui lòng nhập đầy đủ thông tin';
            return $response;
        }
        
        // Kiểm tra người dùng tồn tại
        $user = User::findById($userId);
        if (!$user) {
            $response['message'] = 'Người dùng không tồn tại';
            return $response;
        }
        
        // Tạo giao dịch mới
        $transaction = new Transaction($userId, $amount, $paymentMethod, 'deposit');
        $result = $transaction->save();
        
        if ($result) {
            // Trong thực tế, bạn sẽ tích hợp với cổng thanh toán ở đây
            // Sau khi thanh toán thành công, bạn sẽ gọi phương thức complete()
            
            // Giả lập thanh toán thành công
            $transaction->complete();
            
            $response['success'] = true;
            $response['message'] = 'Nạp tiền thành công';
            $response['data'] = [
                'transaction_code' => $transaction->getTransactionCode(),
                'amount' => $transaction->getAmount(),
                'new_balance' => $user->getBalance()
            ];
        } else {
            $response['message'] = 'Có lỗi xảy ra khi xử lý giao dịch';
        }
        
        return $response;
    }
    
    /**
     * Xử lý nạp thẻ cào
     */
    public function depositCard($userId, $cardCode, $serialNumber, $amount, $gameType) {
        // Ghi log dữ liệu đầu vào
        error_log("depositCard called with: userId=$userId, cardCode=$cardCode, serialNumber=$serialNumber, amount=$amount, gameType=$gameType");
        
        // Chuẩn bị phản hồi
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Kiểm tra dữ liệu đầu vào
        if (empty($cardCode) || empty($serialNumber) || empty($amount)) {
            $response['message'] = 'Vui lòng nhập đầy đủ thông tin thẻ';
            error_log("depositCard validation failed: missing required fields");
            return $response;
        }
        
        // Tạo giao dịch mới cho người dùng ẩn danh (userId = 0)
        $transaction = new Transaction(0, $amount, 'card', 'deposit');
        // Lưu thông tin thẻ cào và đặt trạng thái hoàn thành
        $transaction->setCardInfo($cardCode, $serialNumber, $gameType);
        $transaction->setStatus('completed');
        
        error_log("depositCard: About to save transaction");
        $result = $transaction->save();
        error_log("depositCard: Transaction save result: " . ($result ? 'success' : 'failed'));
        
        if ($result) {
            // Tính toán số kim cương/sò nhận được dựa trên mệnh giá thẻ
            $virtualCurrency = $this->calculateVirtualCurrency($amount, $gameType);
            
            $response['success'] = true;
            $response['message'] = 'Nạp thẻ thành công';
            $response['data'] = [
                'transaction_code' => $transaction->getTransactionCode(),
                'amount' => $transaction->getAmount(),
                'virtual_currency' => $virtualCurrency,
                'game_type' => $gameType
            ];
        } else {
            $response['message'] = 'Có lỗi xảy ra khi xử lý giao dịch';
        }
        
        return $response;
    }
    
    /**
     * Tính toán số lượng tiền ảo (kim cương, sò, quân huy) dựa trên mệnh giá thẻ
     */
    private function calculateVirtualCurrency($amount, $gameType) {
        $result = [
            'type' => '',
            'amount' => 0
        ];
        
        switch ($gameType) {
            case 'lq': // Liên Quân
                $result['type'] = 'Quân Huy';
                if ($amount == 20000) {
                    $result['amount'] = 0; // Không hỗ trợ
                } elseif ($amount == 50000) {
                    $result['amount'] = 204;
                } elseif ($amount == 100000) {
                    $result['amount'] = 408 * 2; // Khuyến mãi 100%
                } elseif ($amount == 200000) {
                    $result['amount'] = 816 * 2; // Khuyến mãi 100%
                } elseif ($amount == 500000) {
                    $result['amount'] = 2040 * 2; // Khuyến mãi 100%
                } elseif ($amount == 1000000) {
                    $result['amount'] = 4180 * 2; // Khuyến mãi 100%
                }
                break;
            case 'ff': // Free Fire
                $result['type'] = 'Kim Cương';
                if ($amount == 20000) {
                    $result['amount'] = 230;
                } elseif ($amount == 50000) {
                    $result['amount'] = 580;
                } elseif ($amount == 100000) {
                    $result['amount'] = 1160 * 2; // Khuyến mãi 100%
                } elseif ($amount == 200000) {
                    $result['amount'] = 2330 * 2; // Khuyến mãi 100%
                } elseif ($amount == 500000) {
                    $result['amount'] = 5830 * 2; // Khuyến mãi 100%
                } elseif ($amount == 1000000) {
                    $result['amount'] = 11660 * 2; // Khuyến mãi 100%
                }
                break;
            default: // Mặc định
                $result['type'] = 'Sò';
                $result['amount'] = $amount / 100; // 1 VND = 0.01 Sò
                if ($amount >= 100000) {
                    $result['amount'] *= 2; // Khuyến mãi 100%
                }
        }
        
        return $result;
    }
    
    /**
     * Xử lý mua game hoặc nạp tiền vào game
     */
    public function purchase($userId, $gameId, $amount) {
        // Chuẩn bị phản hồi
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Kiểm tra dữ liệu đầu vào
        if (empty($userId) || empty($gameId) || empty($amount)) {
            $response['message'] = 'Vui lòng nhập đầy đủ thông tin';
            return $response;
        }
        
        // Kiểm tra người dùng tồn tại
        $user = User::findById($userId);
        if (!$user) {
            $response['message'] = 'Người dùng không tồn tại';
            return $response;
        }
        
        // Kiểm tra game tồn tại
        $game = Game::findById($gameId);
        if (!$game) {
            $response['message'] = 'Game không tồn tại';
            return $response;
        }
        
        // Kiểm tra số dư
        if ($user->getBalance() < $amount) {
            $response['message'] = 'Số dư không đủ';
            return $response;
        }
        
        // Tạo giao dịch mới
        $transaction = new Transaction($userId, $amount, 'balance', 'purchase');
        $transaction->setGameId($gameId);
        $result = $transaction->save();
        
        if ($result) {
            // Hoàn thành giao dịch và cập nhật số dư
            $transaction->complete();
            
            $response['success'] = true;
            $response['message'] = 'Giao dịch thành công';
            $response['data'] = [
                'transaction_code' => $transaction->getTransactionCode(),
                'amount' => $transaction->getAmount(),
                'new_balance' => $user->getBalance()
            ];
        } else {
            $response['message'] = 'Có lỗi xảy ra khi xử lý giao dịch';
        }
        
        return $response;
    }
    
    /**
     * Lấy lịch sử giao dịch của người dùng
     */
    public function getTransactionHistory($userId) {
        // Kiểm tra người dùng tồn tại
        $user = User::findById($userId);
        if (!$user) {
            return [];
        }
        
        // Lấy danh sách giao dịch
        $transactions = Transaction::getByUserId($userId);
        
        // Chuyển đổi đối tượng thành mảng
        $transactionArray = [];
        foreach ($transactions as $transaction) {
            $transactionData = $transaction->toArray();
            
            // Thêm thông tin game nếu có
            if ($transaction->getGameId()) {
                $game = Game::findById($transaction->getGameId());
                if ($game) {
                    $transactionData['game'] = [
                        'id' => $game->getId(),
                        'name' => $game->getName(),
                        'image' => $game->getImage()
                    ];
                }
            }
            
            $transactionArray[] = $transactionData;
        }
        
        return $transactionArray;
    }
    
    /**
     * Kiểm tra trạng thái giao dịch
     */
    public function checkTransactionStatus($transactionCode) {
        // Tìm giao dịch theo mã
        $transaction = Transaction::findByTransactionCode($transactionCode);
        
        if ($transaction) {
            return [
                'success' => true,
                'data' => [
                    'transaction_code' => $transaction->getTransactionCode(),
                    'amount' => $transaction->getAmount(),
                    'status' => $transaction->getStatus(),
                    'created_at' => $transaction->getCreatedAt()
                ]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Không tìm thấy giao dịch'
        ];
    }

    /**
     * Lấy các giao dịch gần đây
     */
    public function getRecentTransactions($limit = 10) {
        return Transaction::getRecent($limit);
    }

    /**
     * Lấy doanh thu hàng tháng
     */
    public function getMonthlyRevenue() {
        $revenueData = Transaction::getMonthlyRevenue();
        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = 'T' . $i;
            $data[] = $revenueData[$i] ?? 0;
        }

        return [
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Doanh Thu (VND)',
                        'data' => $data,
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ]
        ];
    }
}
