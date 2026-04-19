<?php
require_once 'configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== BAT DAU SEEDING LICH KHOI HANH TUONG LAI (2026) ===\n";

    // 1. Lay tat ca tour active
    $stmtTours = $pdo->query("SELECT id, name, base_price FROM tours WHERE status = 'active'");
    $tours = $stmtTours->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tours)) {
        die("LOI: Khong tim thay tour nao de seed.\n");
    }

    $totalInserted = 0;
    
    // 2. Thoi gian: Tu ngay hom nay den het nam 2026
    $startDate = new DateTime('2026-04-19'); // Ngay hien tai trong he thong
    $endDate = new DateTime('2026-12-31');
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    foreach ($tours as $tour) {
        echo " - Dang tao lich cho Tour: " . $tour['name'] . "...\n";
        
        $currentDate = clone $startDate;
        
        // Loop qua cac thang
        while ($currentDate <= $endDate) {
            $year = $currentDate->format('Y');
            $month = $currentDate->format('m');
            
            // Xac dinh so ngay khoi hanh trong thang (10 - 15)
            $numDays = rand(10, 15);
            $daysInMonth = $currentDate->format('t');
            
            // Chon cac ngay ngau nhien duy nhat
            $selectedDays = [];
            $startDay = (int)$currentDate->format('d');
            
            // Neu la thang hien tai, chi lay cac ngay tu hom nay tro di
            $rangeStart = ($month == $startDate->format('m') && $year == $startDate->format('Y')) ? $startDay : 1;
            
            if ($rangeStart >= $daysInMonth) {
                 $currentDate->modify('first day of next month');
                 continue;
            }

            for ($i = 0; $i < $numDays; $i++) {
                $d = rand($rangeStart, $daysInMonth);
                $selectedDays[] = $d;
            }
            $selectedDays = array_unique($selectedDays);
            sort($selectedDays);

            foreach ($selectedDays as $day) {
                $departureDate = sprintf("%04d-%02d-%02d", $year, $month, $day);
                
                // Gia mac dinh
                $priceAdult = $tour['base_price'];
                $priceChild = $priceAdult * 0.75;
                $priceInfant = $priceAdult * 0.25;
                
                $sql = "INSERT INTO tour_departures (tour_id, departure_date, max_seats, booked_seats, price_adult, price_child, price_infant, status, operational_status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $tour['id'],
                    $departureDate,
                    rand(30, 45), // max_seats
                    0,            // booked_seats
                    $priceAdult,
                    $priceChild,
                    $priceInfant,
                    'open',
                    'open'
                ]);
                $totalInserted++;
            }
            
            $currentDate->modify('first day of next month');
        }
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\n=== SEEDING LICH KHOI HANH THANH CONG! ===\n";
    echo "Tong so lich khoi hanh da tao: $totalInserted\n";

} catch (Exception $e) {
    echo "LOI: " . $e->getMessage() . "\n";
}
