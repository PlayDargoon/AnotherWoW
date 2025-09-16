<?php
// src/models/News.php
class News {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->ensureTableExists();
    }
    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            author VARCHAR(64) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->pdo->exec($sql);

        // Проверка наличия столбца author
        $columns = $this->pdo->query("SHOW COLUMNS FROM news LIKE 'author'")->fetchAll();
        if (empty($columns)) {
            $this->pdo->exec("ALTER TABLE news ADD COLUMN author VARCHAR(64) NOT NULL DEFAULT 'Администрация'");
        }
    }
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM news ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($title, $content, $author) {
        $stmt = $this->pdo->prepare("INSERT INTO news (title, content, author) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $content, $author]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
