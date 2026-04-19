<?php
require_once 'configs/env.php';
require_once 'models/BaseModel.php';
require_once 'models/TourDeparture.php';

new BaseModel();
$tourId = 52;
$pdo = BaseModel::getPdo();

$stmt = $pdo->prepare("SELECT id, departure_date, status FROM tour_departures WHERE tour_id = :tid AND departure_date LIKE '2026-05-%'");
$stmt->execute(['tid' => $tourId]);
$deps = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "May 2026 Status Check for Tour $tourId:\n";
foreach ($deps as $d) {
    echo "- ID: {$d['id']}, Date: {$d['departure_date']}, Status: '{$d['status']}'\n";
    
    if ($d['status'] !== 'open') {
        $update = $pdo->prepare("UPDATE tour_departures SET status = 'open' WHERE id = :id");
        $update->execute(['id' => $d['id']]);
        echo "  [FIXED] Updated to 'open'\n";
    }
}
echo "DONE! Please refresh.";
