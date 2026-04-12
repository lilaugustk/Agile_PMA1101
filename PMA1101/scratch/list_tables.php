<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=pro1014', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    while($row = $stmt->fetch()) {
        echo $row[0] . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
