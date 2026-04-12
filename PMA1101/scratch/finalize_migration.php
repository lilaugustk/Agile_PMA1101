<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=pro1014', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Dropping obsolete tables...\n";
    $pdo->exec("DROP TABLE IF EXISTS tour_partner_services");
    $pdo->exec("DROP TABLE IF EXISTS version_dynamic_pricing");
    $pdo->exec("DROP TABLE IF EXISTS tour_pricing_options");

    echo "Cleaning up tour_vehicles table...\n";
    // Check if the foreign key exists before dropping
    $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'tour_vehicles' AND COLUMN_NAME = 'bus_company_id' AND TABLE_SCHEMA = 'pro1014' AND REFERENCED_TABLE_NAME IS NOT NULL");
    $fk = $stmt->fetch();
    if ($fk) {
        $pdo->exec("ALTER TABLE tour_vehicles DROP FOREIGN KEY " . $fk['CONSTRAINT_NAME']);
        echo "Dropped foreign key: " . $fk['CONSTRAINT_NAME'] . "\n";
    }

    // Check if column exists before dropping
    $stmt = $pdo->query("SHOW COLUMNS FROM tour_vehicles LIKE 'bus_company_id'");
    if ($stmt->fetch()) {
        $pdo->exec("ALTER TABLE tour_vehicles DROP COLUMN bus_company_id");
        echo "Dropped column: bus_company_id\n";
    }

    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
