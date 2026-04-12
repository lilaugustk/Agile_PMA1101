<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=pro1014', 'root', '');
    $stmt = $pdo->query('SHOW CREATE TABLE tour_vehicles');
    echo $stmt->fetch()[1];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
