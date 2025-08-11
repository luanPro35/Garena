<?php
require_once 'includes/header.php';
?>

<main>
    <div class="container">
        <div class="nap-lq-container">
            <h1>Liên Quân Mobile</h1>
            <div class="alert">
                <p>Nạp quân huy Liên Quân Mobile nhận ngay skin Violet Thứ Nguyên Vệ Thần khi nạp thẻ lần đầu trong ngày với thẻ có mệnh giá từ 500.000đ trở lên.</p>
                <p>LƯU Ý: Mỗi tài khoản chỉ được hưởng ưu đãi một lần</p>
            </div>

            <h3>Chọn cách đăng nhập</h3>
            <div class="login-methods">
                <button id="uid-login-btn-page" class="login-method-btn">UID</button>
                <button id="garena-login-btn-page" class="login-method-btn">Garena</button>
                <button id="facebook-login-btn-page" class="login-method-btn">Facebook</button>
            </div>

            <form id="uid-login-form-page" class="login-form" style="display: none;">
                <h3>Đăng nhập bằng UID</h3>
                <input type="text" placeholder="Nhập UID tài khoản Liên Quân Mobile" required>
            </form>

            <form id="garena-login-form-page" class="login-form" style="display: none;">
                <h3>Đăng nhập Garena</h3>
                <input type="text" placeholder="Tên tài khoản hoặc email" required>
                <input type="password" placeholder="Mật khẩu" required>
            </form>

            <form id="facebook-login-form-page" class="login-form">
                <h3>Đăng nhập Facebook</h3>
                <input type="email" placeholder="Email hoặc số điện thoại" required>
                <input type="password" placeholder="Mật khẩu" required>
            </form>

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
                        <li>Quân huy x 204</li>
                        <li>Quân huy x 408 (+ 408)</li>
                        <li>Quân huy x 816 (+ 816)</li>
                        <li>Quân huy x 2 040 (+ 2 040)</li>
                        <li>Quân huy x 4 180 (+ 4 180)</li>
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