<?php
require_once __DIR__ . '/../configs/env.php';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    echo "Connected to database: " . DB_NAME . "\n";

    // 0. Disable FK Checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 1. Drop Tables
    $tablesToDrop = [
        'booking_suppliers_assignment',
        'tour_version_prices',
        'tour_versions',
        'supplier_contracts',
        'supplier_costs',
        'supplier_feedbacks',
        'suppliers',
        'bus_companies'
    ];

    foreach ($tablesToDrop as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table` CASCADE");
            echo "Dropped table: $table\n";
        } catch (Exception $e) {
            echo "Error dropping table $table: " . $e->getMessage() . "\n";
        }
    }

    // 2. Drop Columns from bookings
    $columnsToDropBookings = ['version_id', 'bus_company_id'];
    foreach ($columnsToDropBookings as $col) {
        try {
            // Check if column exists
            $check = $pdo->query("SHOW COLUMNS FROM `bookings` LIKE '$col'");
            if ($check->rowCount() > 0) {
                $pdo->exec("ALTER TABLE `bookings` DROP COLUMN `$col` ");
                echo "Dropped column $col from bookings\n";
            }
        } catch (Exception $e) {
            echo "Error dropping column $col from bookings: " . $e->getMessage() . "\n";
        }
    }

    // 3. Drop Columns from tours
    try {
        $check = $pdo->query("SHOW COLUMNS FROM `tours` LIKE 'supplier_id'");
        if ($check->rowCount() > 0) {
            $pdo->exec("ALTER TABLE `tours` DROP COLUMN `supplier_id` ");
            echo "Dropped column supplier_id from tours\n";
        }
    } catch (Exception $e) {
        echo "Error dropping column supplier_id from tours: " . $e->getMessage() . "\n";
    }

    // 4. Enable FK Checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\nDatabase migration completed successfully.\n";

} catch (PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
