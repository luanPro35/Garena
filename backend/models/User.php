<?php
/**
 * User Model
 * Định nghĩa cấu trúc và các phương thức xử lý dữ liệu người dùng
 */

require_once __DIR__ . '/../config.php';

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $fullName;
    private $avatar;
    private $balance;
    private $role;
    private $status;
    private $createdAt;
    private $updatedAt;
    
    /**
     * Khởi tạo đối tượng User
     */
    public function __construct($id = null, $username = null, $email = null, $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->balance = 0.00;
        $this->role = 'user';
        $this->status = 'active';
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getFullName() { return $this->fullName; }
    public function getAvatar() { return $this->avatar; }
    public function getBalance() { return $this->balance; }
    public function getRole() { return $this->role; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt; }
    
    // Setters
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setFullName($fullName) { $this->fullName = $fullName; }
    public function setAvatar($avatar) { $this->avatar = $avatar; }
    public function setBalance($balance) { $this->balance = $balance; }
    public function setRole($role) { $this->role = $role; }
    public function setStatus($status) { $this->status = $status; }
    
    /**
     * Mã hóa mật khẩu
     */
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Kiểm tra mật khẩu
     * Hỗ trợ di chuyển từ mật khẩu văn bản thuần sang mật khẩu băm
     */
    public function verifyPassword($password) {
        // Nếu mật khẩu được lưu trữ không phải là một chuỗi băm hợp lệ,
        // có thể đó là mật khẩu văn bản thuần từ hệ thống cũ.
        if (password_needs_rehash($this->password, PASSWORD_DEFAULT)) {
            // So sánh trực tiếp với mật khẩu văn bản thuần
            if ($password === $this->password) {
                // Mật khẩu khớp. Băm mật khẩu và cập nhật vào cơ sở dữ liệu.
                $this->setPassword($password);
                $this->save();
                return true;
            }
        }
        
        // Nếu không, sử dụng phương thức xác minh tiêu chuẩn.
        return password_verify($password, $this->password);
    }
    
    /**
     * Lưu người dùng vào cơ sở dữ liệu
     */
    public function save() {
        $conn = connectDB();
        
        if ($this->id) {
            // Cập nhật người dùng hiện có
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, full_name = ?, avatar = ?, balance = ?, role = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssdssi", $this->username, $this->email, $this->password, $this->fullName, $this->avatar, $this->balance, $this->role, $this->status, $this->id);
        } else {
            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, avatar, balance, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssds", $this->username, $this->email, $this->password, $this->fullName, $this->avatar, $this->balance, $this->role, $this->status);
        }
        
        $result = $stmt->execute();
        
        if ($result && !$this->id) {
            $this->id = $conn->insert_id;
        }
        
        $stmt->close();
        $conn->close();
        
        return $result;
    }
    
    /**
     * Tìm người dùng theo ID
     */
    public static function findById($id) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $user = new User($userData['id'], $userData['username'], $userData['email'], $userData['password']);
            $user->fullName = $userData['full_name'];
            $user->avatar = $userData['avatar'];
            $user->balance = $userData['balance'];
            $user->role = $userData['role'];
            $user->status = $userData['status'];
            $user->createdAt = $userData['created_at'];
            $user->updatedAt = $userData['updated_at'];
            
            $stmt->close();
            $conn->close();
            
            return $user;
        }
        
        $stmt->close();
        $conn->close();
        
        return null;
    }
    
    /**
     * Tìm người dùng theo tên đăng nhập
     */
    public static function findByUsername($username) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $user = new User($userData['id'], $userData['username'], $userData['email'], $userData['password']);
            $user->fullName = $userData['full_name'];
            $user->avatar = $userData['avatar'];
            $user->balance = $userData['balance'];
            $user->role = $userData['role'];
            $user->status = $userData['status'];
            $user->createdAt = $userData['created_at'];
            $user->updatedAt = $userData['updated_at'];
            
            $stmt->close();
            $conn->close();
            
            return $user;
        }
        
        $stmt->close();
        $conn->close();
        
        return null;
    }
    
    /**
     * Tìm người dùng theo email
     */
    public static function findByEmail($email) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $user = new User($userData['id'], $userData['username'], $userData['email'], $userData['password']);
            $user->fullName = $userData['full_name'];
            $user->avatar = $userData['avatar'];
            $user->balance = $userData['balance'];
            $user->role = $userData['role'];
            $user->status = $userData['status'];
            $user->createdAt = $userData['created_at'];
            $user->updatedAt = $userData['updated_at'];
            
            $stmt->close();
            $conn->close();
            
            return $user;
        }
        
        $stmt->close();
        $conn->close();
        
        return null;
    }
    
    /**
     * Chuyển đối tượng thành mảng
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->fullName,
            'avatar' => $this->avatar,
            'balance' => $this->balance,
            'role' => $this->role,
            'status' => $this->status,
            'created_at' => $this->createdAt
        ];
    }
}
