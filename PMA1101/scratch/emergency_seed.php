<?php
require_once 'configs/env.php';
require_once 'models/BaseModel.php';
require_once 'models/TourDeparture.php';

// Init DB
new BaseModel();

$tourId = 52;
$departureModel = new TourDeparture();

// Get existing tour price
$pdo = BaseModel::getPdo();
$stmt = $pdo->prepare("SELECT base_price FROM tours WHERE id = :id");
$stmt->execute(['id' => $tourId]);
$basePrice = $stmt->fetchColumn();

echo "Seeding Tour $tourId for May 2026...\n";

$days = [5, 12, 19, 26]; // Tuesdays in May 2026
foreach ($days as $day) {
    $date = "2026-05-" . str_pad($day, 2, '0', STR_PAD_LEFT);
    
    // Check if exists
    $check = $pdo->prepare("SELECT id FROM tour_departures WHERE tour_id = :tid AND departure_date = :d");
    $check->execute(['tid' => $tourId, 'd' => $date]);
    if (!$check->fetch()) {
        $departureModel->insert([
            'tour_id' => $tourId,
            'departure_date' => $date,
            'price_adult' => $basePrice,
            'price_child' => round($basePrice * 0.7),
            'max_seats' => 40,
            'booked_seats' => rand(0, 10),
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        echo "Created departure for $date\n";
    } else {
        echo "Departure for $date already exists\n";
    }
}
echo "DONE! Please refresh the page.";
