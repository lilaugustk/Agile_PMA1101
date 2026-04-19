<?php
require_once 'configs/env.php';
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    
    echo "--- TOURS COUNT ---\n";
    $tours = $pdo->query("SELECT id, name, base_price FROM tours WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
    echo "Total active tours: " . count($tours) . "\n";
    
    echo "\n--- TOUR_DEPARTURES SCHEMA ---\n";
    $schema = $pdo->query("DESCRIBE tour_departures")->fetchAll(PDO::FETCH_ASSOC);
    print_r($schema);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
