<?php
require_once 'includes/header.php';
?>

<main>
    <div class="container">
        <div class="slider">
            <div class="slides">
                <img src="img/lq_pc_11012023.png" alt="Banner 4" id="lastClone">
                <img src="img/FF_2129.jpg" alt="Banner 1">
                <img src="img/sp_pc_1032022.jpg" alt="Banner 2">
                <img src="img/sp_pc_15092022.jpg" alt="Banner 3">
                <img src="img/lq_pc_11012023.png" alt="Banner 4">
                <img src="img/FF_2129.jpg" alt="Banner 1" id="firstClone">
            </div>
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
        <h2>Chọn game để nạp</h2>
        <div class="game-list">
            <div class="game-item">
                <a href="nap-so.php">
                    <img src="img/icon_so.png" alt="FC Online (VN)">
                    <span>FC Online (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="nap-fc.php">
                    <img src="img/icon_fc.png" alt="FC Online M (VN)">
                    <span>FC Online (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="nap-fcm.php">
                    <img src="img/icon_fc_on.png" alt="FC Online (VN)">
                    <span>FC Online M (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="nap-lq.php">
                    <img src="img/icon_lq.png" alt="Liên Quân Mobile">
                    <span>Liên Quân Mobile</span>
                </a>
            </div>
            <div class="game-item">
                <a href="nap-ff.php">
                    <img src="img/icon_ff.png" alt="Free Fire">
                    <span>Free Fire</span>
                </a>
            </div>
            <div class="game-item">
                <a href="nap-ctth.php">
                    <img src="img/icon_ko.png" alt="Cái Thế Tranh Hùng">
                    <span>Cái Thế Tranh Hùng</span>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
?>
