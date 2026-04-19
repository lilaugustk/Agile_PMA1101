<?php
require 'configs/env.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

$bookingId = 378;
$stmt = $pdo->prepare("SELECT * FROM booking_customers WHERE booking_id = :bid");
$stmt->execute(['bid' => $bookingId]);
$companions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "COMPANIONS FOR BOOKING $bookingId:\n";
foreach ($companions as $c) {
    echo "ID: {$c['id']}, Name: {$c['full_name']}, UserID: {$c['user_id']}, Phone: '{$c['phone']}', Email: '{$c['email']}', IDCard: '{$c['id_card']}', Birth: '{$c['birth_date']}'\n";
}
