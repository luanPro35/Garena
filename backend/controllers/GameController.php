<?php
/**
 * GameController
 * Xử lý các chức năng liên quan đến game như hiển thị danh sách, tìm kiếm
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Game.php';

class GameController {
    
    /**
     * Lấy danh sách tất cả các game
     * @return array Danh sách game
     */
    public function getAllGames() {
        // Lấy danh sách game từ model
        $games = Game::getAll();
        
        // Chuyển đổi đối tượng game thành mảng
        $gameArray = [];
        foreach ($games as $game) {
            $gameArray[] = $game->toArray();
        }
        
        return $gameArray;
    }
    
    /**
     * Tìm kiếm game theo từ khóa
     * @param string $searchQuery Từ khóa tìm kiếm
     * @return array Danh sách game phù hợp với từ khóa
     */
    public function searchGames($searchQuery) {
        // Kiểm tra từ khóa tìm kiếm
        if (empty($searchQuery)) {
            return [];
        }
        
        // Tìm kiếm game từ model
        $games = Game::search($searchQuery);
        
        // Chuyển đổi đối tượng game thành mảng
        $gameArray = [];
        foreach ($games as $game) {
            $gameArray[] = $game->toArray();
        }
        
        return $gameArray;
    }
    
    /**
     * Lấy thông tin chi tiết của game theo ID
     * @param int $gameId ID của game
     * @return array|null Thông tin chi tiết của game hoặc null nếu không tìm thấy
     */
    public function getGameById($gameId) {
        // Tìm game theo ID
        $game = Game::findById($gameId);
        
        // Trả về thông tin chi tiết nếu tìm thấy
        if ($game) {
            return $game->toArray();
        }
        
        return null;
    }
    
    /**
     * Lấy danh sách game nổi bật
     * @param int $limit Số lượng game muốn lấy
     * @return array Danh sách game nổi bật
     */
    public function getFeaturedGames($limit = 5) {
        // Trong thực tế, bạn sẽ lấy danh sách game nổi bật từ cơ sở dữ liệu
        // Ví dụ: $sql = "SELECT * FROM games WHERE featured = 1 ORDER BY position ASC LIMIT $limit";
        
        // Giả lập lấy danh sách game nổi bật
        $allGames = $this->getAllGames();
        
        // Lấy $limit game đầu tiên
        return array_slice($allGames, 0, $limit);
    }
    
    /**
     * Lấy danh sách game theo thể loại
     * @param string $genre Thể loại game
     * @return array Danh sách game thuộc thể loại
     */
    public function getGamesByGenre($genre) {
        // Trong thực tế, bạn sẽ lấy danh sách game theo thể loại từ cơ sở dữ liệu
        // Ví dụ: $sql = "SELECT * FROM games WHERE genre = ? ORDER BY position ASC";
        
        // Giả lập lấy danh sách game theo thể loại
        $allGames = $this->getAllGames();
        $filteredGames = [];
        
        foreach ($allGames as $game) {
            if (isset($game['genre']) && strtolower($game['genre']) === strtolower($genre)) {
                $filteredGames[] = $game;
            }
        }
        
        return $filteredGames;
    }
}