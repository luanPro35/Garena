<?php
/**
 * Home Template
 * Hiển thị trang chủ với slider và danh sách game
 */

// Lấy danh sách game
$games = $gameController->getAllGames();
?>

<div class="container">
    <div class="banner-slider">
        <div class="slider-container" id="slider-container">
            <div class="slide">
                <img src="../frontend/images/FF_2129.jpg" alt="Free Fire">
            </div>
            <div class="slide">
                <img src="../frontend/images/lq_pc_11012023.png" alt="Call of Duty">
            </div>
            <div class="slide">
                <img src="../frontend/images/sp_pc_1032022.jpg" alt="FIFA Online 4">
            </div>
            <div class="slide">
                <img src="../frontend/images/sp_pc_15092022.jpg" alt="FIFA Online 4">
            </div>
        </div>
        <button class="slider-nav prev-btn" id="prev-btn">&lt;</button>
        <button class="slider-nav next-btn" id="next-btn">&gt;</button>
    </div>

    <h2 class="section-title">Chọn game để nạp</h2>
    
    <div class="game-section">
        <div class="game-grid">
            <?php foreach ($games as $game): ?>
                <div class="game-item">
                    <div class="game-icon">
                        <img src="../frontend/<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
                    </div>
                    <div class="game-name"><?php echo htmlspecialchars($game['name']); ?></div>
                    <?php if (isset($game['promotion']) && $game['promotion']): ?>
                        <a href="#" class="download-btn">Khuyến mãi</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>