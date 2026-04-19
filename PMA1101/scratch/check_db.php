<?php
include 'configs/env.php';
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- TABLES ---\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    
    echo "\n--- USERS SCHEMA ---\n";
    $usersSchema = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
    print_r($usersSchema);

    echo "\n--- BOOKING_CUSTOMERS SCHEMA ---\n";
    $bcSchema = $pdo->query("DESCRIBE booking_customers")->fetchAll(PDO::FETCH_ASSOC);
    print_r($bcSchema);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
