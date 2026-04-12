<?php
require_once 'configs/env.php';
require_once 'models/BaseModel.php';
require_once 'models/TourDeparture.php';

$departureModel = new TourDeparture();
$pdo = BaseModel::getPdo();

// Query to find discrepancies between td.booked_seats and actual sum of passengers
$sql = "SELECT 
            td.id, 
            t.name as tour_name,
            td.departure_date,
            td.max_seats, 
            td.booked_seats as cached_count,
            (SELECT SUM(adults + children) FROM bookings WHERE departure_id = td.id AND status NOT IN ('cancelled', 'da_huy', 'expired')) as real_count
        FROM tour_departures td
        JOIN tours t ON td.tour_id = t.id
        HAVING cached_count != real_count OR real_count > max_seats";

$stmt = $pdo->query($sql);
$discrepancies = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($discrepancies, JSON_PRETTY_PRINT);
