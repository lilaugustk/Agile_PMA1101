<?php
require 'configs/database.php';
$db = new Database();
$stmt = $db->getConnection()->query('DESCRIBE bookings');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' (' . $row['Type'] . ")\n";
}
echo "--- COMPANIONS SCHEMA ---\n";
$stmt = $db->getConnection()->query('DESCRIBE booking_customers');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' (' . $row['Type'] . ")\n";
}
