<?php
require_once 'frontend/includes/header.php';
?>

<main>
    <div class="container">
        <div class="slider">
            <div class="slides">
                <img src="frontend/img/lq_pc_11012023.png" alt="Banner 4" id="lastClone">
                <img src="frontend/img/FF_2129.jpg" alt="Banner 1">
                <img src="frontend/img/sp_pc_1032022.jpg" alt="Banner 2">
                <img src="frontend/img/sp_pc_15092022.jpg" alt="Banner 3">
                <img src="frontend/img/lq_pc_11012023.png" alt="Banner 4">
                <img src="frontend/img/FF_2129.jpg" alt="Banner 1" id="firstClone">
            </div>
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
        <h2>Chọn game để nạp</h2>
        <div class="game-list">
            <div class="game-item">
                <a href="frontend/nap-so.php">
                    <img src="frontend/img/icon_so.png" alt="FC Online (VN)">
                    <span>FC Online (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="frontend/nap-fc.php">
                    <img src="frontend/img/icon_fc.png" alt="FC Online M (VN)">
                    <span>FC Online (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="frontend/nap-fcm.php">
                    <img src="frontend/img/icon_fc_on.png" alt="FC Online (VN)">
                    <span>FC Online M (VN)</span>
                </a>
            </div>
            <div class="game-item">
                <a href="frontend/nap-lq.php">
                    <img src="frontend/img/icon_lq.png" alt="Liên Quân Mobile">
                    <span>Liên Quân Mobile</span>
                </a>
            </div>
            <div class="game-item">
                <a href="frontend/nap-ff.php">
                    <img src="frontend/img/icon_ff.png" alt="Free Fire">
                    <span>Free Fire</span>
                </a>
            </div>
            <div class="game-item">
                <a href="frontend/nap-ctth.php">
                    <img src="frontend/img/icon_ko.png" alt="Cái Thế Tranh Hùng">
                    <span>Cái Thế Tranh Hùng</span>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'frontend/includes/footer.php';
?>
