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
    private $createdAt;
    
    /**
     * Khởi tạo đối tượng User
     */
    public function __construct($id = null, $username = null, $email = null, $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = date('Y-m-d H:i:s');
    }
    
    /**
     * Lưu người dùng mới vào cơ sở dữ liệu
     */
    public function save() {
        $conn = connectDB();
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->username, $this->email, $hashedPassword, $this->createdAt);
        
        $result = $stmt->execute();
        
        if ($result) {
            $this->id = $conn->insert_id;
            return true;
        }
        
        return false;
    }
    
    /**
     * Tìm người dùng theo tên đăng nhập
     */
    public static function findByUsername($username) {
        $conn = connectDB();
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password']
            );
        }
        
        return null;
    }
    
    /**
     * Tìm người dùng theo email
     */
    public static function findByEmail($email) {
        $conn = connectDB();
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password']
            );
        }
        
        return null;
    }
    
    /**
     * Kiểm tra mật khẩu
     */
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
    
    /**
     * Lấy thông tin người dùng
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->createdAt
        ];
    }
    
    /**
     * Lấy ID người dùng
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Lấy tên đăng nhập
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * Lấy email
     */
    public function getEmail() {
        return $this->email;
    }
}