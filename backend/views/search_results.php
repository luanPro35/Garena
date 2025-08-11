<?php
/**
 * Search Results Template
 * Hiển thị kết quả tìm kiếm game
 */

// Lấy kết quả tìm kiếm từ controller
$searchResults = $searchResponse['data'] ?? [];
$searchMessage = $searchResponse['message'] ?? 'Không có kết quả tìm kiếm';
$searchSuccess = $searchResponse['success'] ?? false;
$searchQuery = htmlspecialchars($query);
?>

<div class="container">
    <div class="search-header">
        <h2 class="section-title">Kết quả tìm kiếm cho "<?php echo $searchQuery; ?>"</h2>
        <p class="search-message"><?php echo $searchMessage; ?></p>
    </div>
    
    <?php if ($searchSuccess && !empty($searchResults)): ?>
        <div class="game-section">
            <div class="game-grid">
                <?php foreach ($searchResults as $game): ?>
                    <div class="game-item">
                        <div class="game-icon">
                            <img src="../frontend/<?php echo $game['image']; ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
                        </div>
                        <div class="game-name"><?php echo htmlspecialchars($game['name']); ?></div>
                        <div class="game-description"><?php echo htmlspecialchars($game['description']); ?></div>
                        <?php if (isset($game['promotion']) && $game['promotion']): ?>
                            <a href="#" class="download-btn">Khuyến mãi</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="no-results">
            <p>Không tìm thấy kết quả nào phù hợp với từ khóa "<?php echo $searchQuery; ?>".</p>
            <p>Vui lòng thử lại với từ khóa khác hoặc xem <a href="index.php">danh sách game</a> của chúng tôi.</p>
        </div>
    <?php endif; ?>
</div>