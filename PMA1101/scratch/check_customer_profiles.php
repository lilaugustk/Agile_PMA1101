<?php
require_once 'configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    
    echo "--- CUSTOMER_PROFILES SCHEMA ---\n";
    $stmt = $pdo->query("DESCRIBE customer_profiles");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
