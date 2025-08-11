<?php
/**
 * UserController
 * Xử lý các chức năng liên quan đến người dùng như đăng nhập, đăng ký, đăng xuất
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    
    /**
     * Xử lý đăng nhập người dùng
     */
    public function login($username, $password) {
        // Chuẩn bị phản hồi
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Kiểm tra dữ liệu đầu vào
        if (empty($username) || empty($password)) {
            $response['message'] = 'Vui lòng nhập tên đăng nhập và mật khẩu';
            return $response;
        }
        
        // Tìm người dùng theo tên đăng nhập
        $user = User::findByUsername($username);
        
        if ($user && $user->verifyPassword($password)) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['is_logged_in'] = true;
            
            $response['success'] = true;
            $response['message'] = 'Đăng nhập thành công';
            $response['data'] = [
                'username' => $user->getUsername(),
                'redirect' => '../frontend/index.php'
            ];
        } else {
            // Đăng nhập thất bại
            $response['message'] = 'Tên đăng nhập hoặc mật khẩu không đúng';
        }
        
        return $response;
    }
    
    /**
     * Xử lý đăng ký người dùng mới
     */
    public function register($username, $email, $password) {
        // Chuẩn bị phản hồi
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Tạo người dùng mới
        $user = new User(null, $username, $email, $password);
        
        // Lưu vào cơ sở dữ liệu
        if ($user->save()) {
            $response['success'] = true;
            $response['message'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
            $response['data'] = [
                'redirect' => '../frontend/login.php'
            ];
        } else {
            $response['message'] = 'Đăng ký thất bại. Vui lòng thử lại sau.';
        }
        
        return $response;
    }
    
    /**
     * Xử lý đăng xuất người dùng
     */
    public function logout() {
        // Xóa tất cả dữ liệu session
        session_unset();
        session_destroy();
        
        return [
            'success' => true,
            'message' => 'Đăng xuất thành công',
            'data' => [
                'redirect' => '../frontend/index.php'
            ]
        ];
    }
    
    /**
     * Lấy thông tin người dùng theo ID
     */
    public function getUserById($userId) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa (cho form đăng ký)
     */
    public function isUsernameExists($username) {
        // Sử dụng phương thức findByUsername từ model User
        $user = User::findByUsername($username);
        return $user !== null;
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa (cho form đăng ký)
     */
    public function isEmailExists($email) {
        // Sử dụng phương thức findByEmail từ model User
        $user = User::findByEmail($email);
        return $user !== null;
    }
    
    /**
     * Cập nhật thông tin người dùng
     */
    public function updateProfile($userId, $data) {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        $conn = connectDB();
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $data['username'], $data['email'], $userId);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Cập nhật thông tin thành công';
            $response['data'] = $this->getUserById($userId);
        } else {
            $response['message'] = 'Cập nhật thông tin thất bại';
        }
        
        return $response;
    }
    
    /**
     * Đổi mật khẩu
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];
        
        // Lấy thông tin người dùng
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu hiện tại
            if (password_verify($currentPassword, $userData['password'])) {
                // Cập nhật mật khẩu mới
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $hashedPassword, $userId);
                
                if ($updateStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Đổi mật khẩu thành công';
                } else {
                    $response['message'] = 'Đổi mật khẩu thất bại';
                }
            } else {
                $response['message'] = 'Mật khẩu hiện tại không đúng';
            }
        } else {
            $response['message'] = 'Không tìm thấy thông tin người dùng';
        }
        
        return $response;
    }
}