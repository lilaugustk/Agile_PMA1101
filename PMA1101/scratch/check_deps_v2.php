<?php
require_once 'config/config.php';
require_once 'models/BaseModel.php';
require_once 'models/TourDeparture.php';

// Khởi tạo PDO
new BaseModel(); 

$tourId = 52;
$departureModel = new TourDeparture();
$deps = $departureModel->getByTourId($tourId);

echo "Total Departures for Tour $tourId: " . count($deps) . "\n";
$mayCount = 0;
foreach ($deps as $d) {
    if (strpos($d['departure_date'], '2026-05') === 0) {
        echo "- {$d['departure_date']}: {$d['price_adult']} VND\n";
        $mayCount++;
    }
}

if ($mayCount === 0) {
    echo "NO DEPARTURES FOUND FOR MAY 2026 IN DATABASE.\n";
}
