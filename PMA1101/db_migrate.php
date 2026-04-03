<?php
require_once __DIR__ . '/configs/env.php';
try {
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS pages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(50) UNIQUE,
        title VARCHAR(255),
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    $sql2 = "INSERT IGNORE INTO pages (slug, title, content) VALUES
    ('about', 'Về Chúng Tôi', 'Nội dung giới thiệu AgileTravel...'),
    ('contact', 'Thông tin liên hệ', 'Nội dung thông tin liên hệ AgileTravel...')
    ";
    $pdo->exec($sql2);
    echo "Migration successful\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
