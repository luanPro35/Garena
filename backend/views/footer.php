<?php
/**
 * Footer Template
 * Phần cuối của trang web, bao gồm footer và các script
 */
?>

    <!-- Modal Đăng nhập -->
    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Đăng nhập</h2>
            <form id="login-form" action="../frontend/login.php" method="post">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Đăng nhập</button>
                </div>
                <div class="form-links">
                    <a href="#">Quên mật khẩu?</a>
                    <a href="#" id="register-link">Đăng ký tài khoản mới</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Đăng ký -->
    <div id="register-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Đăng ký tài khoản</h2>
            <form id="register-form" action="../frontend/register.php" method="post">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="reg-username">Tên đăng nhập:</label>
                    <input type="text" id="reg-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Email:</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Mật khẩu:</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="reg-confirm-password">Xác nhận mật khẩu:</label>
                    <input type="password" id="reg-confirm-password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Đăng ký</button>
                </div>
                <div class="form-links">
                    <a href="#" id="login-link">Đã có tài khoản? Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="../frontend/images/garena-logo.png" alt="Garena Logo" width="120">
                </div>
                <div class="footer-links">
                    <h3>Liên kết nhanh</h3>
                    <ul>
                        <li><a href="../frontend/index.php">Trang chủ</a></li>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>Liên hệ</h3>
                    <p>Email: support@garena.vn</p>
                    <p>Hotline: 1900 1234</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Garena. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script src="../frontend/js/main.js"></script>
</body>

</html>