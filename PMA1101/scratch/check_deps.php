<?php
require_once 'models/BaseModel.php';
require_once 'models/TourDeparture.php';

$tourId = 52;
$pdo = BaseModel::getPdo();
$stmt = $pdo->prepare("SELECT * FROM tour_departures WHERE tour_id = :tid AND departure_date LIKE '2026-05-%'");
$stmt->execute(['tid' => $tourId]);
$deps = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Departures for Tour $tourId in May 2026:\n";
if (empty($deps)) {
    echo "NONE FOUND\n";
} else {
    foreach ($deps as $d) {
        echo "- {$d['departure_date']}: {$d['price_adult']} VND (Status: {$d['status']})\n";
    }
}
