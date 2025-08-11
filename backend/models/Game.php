<?php
/**
 * Game Model
 * Định nghĩa cấu trúc và các phương thức xử lý dữ liệu game
 */

require_once __DIR__ . '/../config.php';

class Game {
    private $id;
    private $name;
    private $image;
    private $description;
    private $promotion;
    private $systemRequirements;
    private $screenshots;
    private $releaseDate;
    private $developer;
    private $publisher;
    private $genre;
    private $rating;
    private $downloadCount;
    
    /**
     * Khởi tạo đối tượng Game
     */
    public function __construct($id = null, $name = null, $image = null, $description = null, $promotion = false) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->promotion = $promotion;
        
        // Khởi tạo thông tin chi tiết mặc định
        $this->systemRequirements = [
            'minimum' => [
                'os' => 'Windows 7 64-bit',
                'processor' => 'Intel Core i3-2100 / AMD FX-6300',
                'memory' => '4 GB RAM',
                'graphics' => 'NVIDIA GeForce GTX 660 2GB / AMD Radeon HD 7850 2GB',
                'storage' => '30 GB available space'
            ],
            'recommended' => [
                'os' => 'Windows 10 64-bit',
                'processor' => 'Intel Core i5-6600K / AMD Ryzen 5 1600',
                'memory' => '8 GB RAM',
                'graphics' => 'NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB',
                'storage' => '30 GB available space'
            ]
        ];
        
        if ($id) {
            $this->loadGameDetails();
        }
    }
    
    /**
     * Tải thông tin chi tiết của game từ cơ sở dữ liệu
     */
    private function loadGameDetails() {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $gameData = $result->fetch_assoc();
            $this->name = $gameData['name'];
            $this->image = $gameData['image'];
            $this->description = $gameData['description'];
            $this->promotion = $gameData['promotion'] == 1;
            $this->releaseDate = $gameData['release_date'];
            $this->developer = $gameData['developer'];
            $this->publisher = $gameData['publisher'];
            $this->genre = $gameData['genre'];
            $this->rating = $gameData['rating'];
            $this->downloadCount = $gameData['download_count'];
            
            // Tải screenshots
            $screenshotStmt = $conn->prepare("SELECT url FROM game_screenshots WHERE game_id = ?");
            $screenshotStmt->bind_param("i", $this->id);
            $screenshotStmt->execute();
            $screenshotResult = $screenshotStmt->get_result();
            
            $this->screenshots = [];
            while ($screenshot = $screenshotResult->fetch_assoc()) {
                $this->screenshots[] = $screenshot['url'];
            }
            
            // Tải system requirements
            $reqStmt = $conn->prepare("SELECT * FROM game_requirements WHERE game_id = ?");
            $reqStmt->bind_param("i", $this->id);
            $reqStmt->execute();
            $reqResult = $reqStmt->get_result();
            
            if ($reqResult->num_rows > 0) {
                $reqData = $reqResult->fetch_assoc();
                $this->systemRequirements = [
                    'minimum' => [
                        'os' => $reqData['min_os'],
                        'processor' => $reqData['min_processor'],
                        'memory' => $reqData['min_memory'],
                        'graphics' => $reqData['min_graphics'],
                        'storage' => $reqData['min_storage']
                    ],
                    'recommended' => [
                        'os' => $reqData['rec_os'],
                        'processor' => $reqData['rec_processor'],
                        'memory' => $reqData['rec_memory'],
                        'graphics' => $reqData['rec_graphics'],
                        'storage' => $reqData['rec_storage']
                    ]
                ];
            }
        }
    }
    
    /**
     * Lấy tất cả game từ cơ sở dữ liệu
     */
    public static function getAll() {
        $conn = connectDB();
        $result = $conn->query("SELECT * FROM games WHERE active = 1 ORDER BY position ASC");
        
        $games = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $games[] = new Game(
                    $row['id'],
                    $row['name'],
                    $row['image'],
                    $row['description'],
                    $row['promotion'] == 1
                );
            }
        }
        
        return $games;
    }
    
    /**
     * Tìm game theo ID
     */
    public static function findById($id) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $gameData = $result->fetch_assoc();
            return new Game(
                $gameData['id'],
                $gameData['name'],
                $gameData['image'],
                $gameData['description'],
                $gameData['promotion'] == 1
            );
        }
        
        return null;
    }
    
    /**
     * Tìm kiếm game theo từ khóa
     */
    public static function search($keyword) {
        $conn = connectDB();
        $keyword = "%$keyword%";
        
        $stmt = $conn->prepare("SELECT * FROM games WHERE name LIKE ? OR description LIKE ? ORDER BY position ASC");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $games = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $games[] = new Game(
                    $row['id'],
                    $row['name'],
                    $row['image'],
                    $row['description'],
                    $row['promotion'] == 1
                );
            }
        }
        
        return $games;
    }
    
    /**
     * Lấy thông tin game dưới dạng mảng
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'description' => $this->description,
            'promotion' => $this->promotion,
            'systemRequirements' => $this->systemRequirements,
            'screenshots' => $this->screenshots,
            'releaseDate' => $this->releaseDate,
            'developer' => $this->developer,
            'publisher' => $this->publisher,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'downloadCount' => $this->downloadCount
        ];
    }
    
    /**
     * Lấy ID game
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Lấy tên game
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Lấy đường dẫn hình ảnh
     */
    public function getImage() {
        return $this->image;
    }
    
    /**
     * Lấy mô tả game
     */
    public function getDescription() {
        return $this->description;
    }
}