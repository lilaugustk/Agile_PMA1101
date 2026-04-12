<?php
require_once 'configs/env.php';
require_once 'models/BaseModel.php';
require_once 'models/Booking.php';

// Initialize a model to trigger constructor and set up PDO
$booking = new Booking();

$pdo = BaseModel::getPdo();
if ($pdo === null) {
    die("PDO is null\n");
}
$sql = "SELECT status, COUNT(*) as count FROM bookings GROUP BY status";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results, JSON_PRETTY_PRINT);
