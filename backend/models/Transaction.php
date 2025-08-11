<?php
/**
 * Transaction Model
 * Định nghĩa cấu trúc và các phương thức xử lý dữ liệu giao dịch
 */

require_once __DIR__ . '/../config.php';

class Transaction {
    private $id;
    private $userId;
    private $gameId;
    private $amount;
    private $paymentMethod;
    private $transactionType;
    private $status;
    private $transactionCode;
    private $cardCode;
    private $serialNumber;
    private $gameType;
    private $createdAt;
    private $updatedAt;
    
    /**
     * Khởi tạo đối tượng Transaction
     */
    public function __construct($userId = null, $amount = null, $paymentMethod = null, $transactionType = 'deposit') {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->transactionType = $transactionType;
        $this->status = 'pending';
        $this->transactionCode = $this->generateTransactionCode();
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getGameId() { return $this->gameId; }
    public function getAmount() { return $this->amount; }
    public function getPaymentMethod() { return $this->paymentMethod; }
    public function getTransactionType() { return $this->transactionType; }
    public function getStatus() { return $this->status; }
    public function getTransactionCode() { return $this->transactionCode; }
    public function getCreatedAt() { return $this->createdAt; }
    
    // Setters
    public function setUserId($userId) { $this->userId = $userId; }
    public function setGameId($gameId) { $this->gameId = $gameId; }
    public function setAmount($amount) { $this->amount = $amount; }
    public function setPaymentMethod($paymentMethod) { $this->paymentMethod = $paymentMethod; }
    public function setTransactionType($transactionType) { $this->transactionType = $transactionType; }
    public function setStatus($status) { $this->status = $status; }
    
    /**
     * Lưu thông tin thẻ cào
     */
    public function setCardInfo($cardCode, $serialNumber, $gameType = '') {
        $this->cardCode = $cardCode;
        $this->serialNumber = $serialNumber;
        $this->gameType = $gameType;
    }
    
    /**
     * Tạo mã giao dịch ngẫu nhiên
     */
    private function generateTransactionCode() {
        return 'TRX' . time() . rand(1000, 9999);
    }
    
    /**
     * Lưu giao dịch vào cơ sở dữ liệu
     */
    public function save() {
        error_log("Starting Transaction::save() method");
        $conn = connectDB();
        
        if (!$conn) {
            error_log("Database connection failed in Transaction::save()");
            return false;
        }
        error_log("Database connection successful");
        
        try {
            if ($this->id) {
                // Cập nhật giao dịch hiện có
                $stmt = $conn->prepare("UPDATE transactions SET user_id = ?, game_id = ?, amount = ?, payment_method = ?, transaction_type = ?, status = ? WHERE id = ?");
                $stmt->bind_param("iidsssi", $this->userId, $this->gameId, $this->amount, $this->paymentMethod, $this->transactionType, $this->status, $this->id);
            } else {
                // Thêm giao dịch mới
                // Debug thông tin giao dịch
                error_log("Transaction data: userId={$this->userId}, gameId={$this->gameId}, amount={$this->amount}, paymentMethod={$this->paymentMethod}, transactionType={$this->transactionType}, status={$this->status}, cardCode={$this->cardCode}, serialNumber={$this->serialNumber}, gameType={$this->gameType}");
                
                if ($this->gameId) {
                    $query = "INSERT INTO transactions (user_id, game_id, amount, payment_method, transaction_type, status, transaction_code, card_code, serial_number, game_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    error_log("SQL query with gameId: $query");
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        error_log("Prepare statement failed: " . $conn->error);
                        return false;
                    }
                    $stmt->bind_param("iidsssssss", $this->userId, $this->gameId, $this->amount, $this->paymentMethod, $this->transactionType, $this->status, $this->transactionCode, $this->cardCode, $this->serialNumber, $this->gameType);
                } else {
                    $query = "INSERT INTO transactions (user_id, amount, payment_method, transaction_type, status, transaction_code, card_code, serial_number, game_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    error_log("SQL query without gameId: $query");
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        error_log("Prepare statement failed: " . $conn->error);
                        return false;
                    }
                    $stmt->bind_param("idsssssss", $this->userId, $this->amount, $this->paymentMethod, $this->transactionType, $this->status, $this->transactionCode, $this->cardCode, $this->serialNumber, $this->gameType);
                }
            }
            
            error_log("Executing SQL statement...");
            $result = $stmt->execute();
            
            if (!$result) {
                // Ghi log lỗi SQL
                error_log("SQL Error: " . $stmt->error);
                return false;
            }
            
            error_log("SQL executed successfully. Result: " . ($result ? 'true' : 'false'));
            
            if (!$this->id && $result) {
                $this->id = $conn->insert_id;
            }
            
            $stmt->close();
            $conn->close();
            
            return $result;
        } catch (Exception $e) {
            // Ghi log lỗi ngoại lệ
            error_log("Exception in Transaction::save(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hoàn thành giao dịch và cập nhật số dư người dùng
     */
    public function complete() {
        // Cập nhật trạng thái giao dịch
        $this->status = 'completed';
        $this->save();
        
        // Chỉ cập nhật số dư nếu có userId hợp lệ
        if ($this->userId > 0) {
            $user = User::findById($this->userId);
            if ($user) {
                $currentBalance = $user->getBalance();
                
                if ($this->transactionType == 'deposit') {
                    $user->setBalance($currentBalance + $this->amount);
                } elseif ($this->transactionType == 'purchase') {
                    $user->setBalance($currentBalance - $this->amount);
                } elseif ($this->transactionType == 'refund') {
                    $user->setBalance($currentBalance + $this->amount);
                }
                
                return $user->save();
            }
        }
        
        return true; // Trả về true cho giao dịch ẩn danh
    }
    
    /**
     * Tìm giao dịch theo ID
     */
    public static function findById($id) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $transactionData = $result->fetch_assoc();
            $transaction = new Transaction($transactionData['user_id'], $transactionData['amount'], $transactionData['payment_method'], $transactionData['transaction_type']);
            $transaction->id = $transactionData['id'];
            $transaction->gameId = $transactionData['game_id'];
            $transaction->status = $transactionData['status'];
            $transaction->transactionCode = $transactionData['transaction_code'];
            $transaction->createdAt = $transactionData['created_at'];
            $transaction->updatedAt = $transactionData['updated_at'];
            
            $stmt->close();
            $conn->close();
            
            return $transaction;
        }
        
        $stmt->close();
        $conn->close();
        
        return null;
    }
    
    /**
     * Tìm giao dịch theo mã giao dịch
     */
    public static function findByTransactionCode($code) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE transaction_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $transactionData = $result->fetch_assoc();
            $transaction = new Transaction($transactionData['user_id'], $transactionData['amount'], $transactionData['payment_method'], $transactionData['transaction_type']);
            $transaction->id = $transactionData['id'];
            $transaction->gameId = $transactionData['game_id'];
            $transaction->status = $transactionData['status'];
            $transaction->transactionCode = $transactionData['transaction_code'];
            $transaction->createdAt = $transactionData['created_at'];
            $transaction->updatedAt = $transactionData['updated_at'];
            
            $stmt->close();
            $conn->close();
            
            return $transaction;
        }
        
        $stmt->close();
        $conn->close();
        
        return null;
    }
    
    /**
     * Lấy lịch sử giao dịch của người dùng
     */
    public static function getByUserId($userId) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        
        while ($row = $result->fetch_assoc()) {
            $transaction = new Transaction($row['user_id'], $row['amount'], $row['payment_method'], $row['transaction_type']);
            $transaction->id = $row['id'];
            $transaction->gameId = $row['game_id'];
            $transaction->status = $row['status'];
            $transaction->transactionCode = $row['transaction_code'];
            $transaction->createdAt = $row['created_at'];
            $transaction->updatedAt = $row['updated_at'];
            
            $transactions[] = $transaction;
        }
        
        $stmt->close();
        $conn->close();
        
        return $transactions;
    }
    
    /**
     * Chuyển đối tượng thành mảng
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'game_id' => $this->gameId,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'transaction_type' => $this->transactionType,
            'status' => $this->status,
            'transaction_code' => $this->transactionCode,
            'created_at' => $this->createdAt
        ];
    }

    /**
     * Lấy các giao dịch gần đây
     */
    public static function getRecent($limit = 10) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT t.*, u.username FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        
        $stmt->close();
        $conn->close();
        
        return $transactions;
    }

    /**
     * Lấy doanh thu hàng tháng
     */
    public static function getMonthlyRevenue() {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT MONTH(created_at) as month, SUM(amount) as total FROM transactions WHERE status = 'completed' AND transaction_type = 'deposit' GROUP BY MONTH(created_at)");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $revenueData = [];
        while ($row = $result->fetch_assoc()) {
            $revenueData[$row['month']] = (float)$row['total'];
        }
        
        $stmt->close();
        $conn->close();
        
        return $revenueData;
    }
}
