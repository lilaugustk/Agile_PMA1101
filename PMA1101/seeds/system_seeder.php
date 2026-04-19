Bảng<?php
require_once dirname(__DIR__) . '/configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== BAT DAU SEEDING DU LIEU THUC TE ===\n";

    // 1. Data Generators
    $surnames = ['Nguyen', 'Tran', 'Le', 'Pham', 'Hoang', 'Phan', 'Vu', 'Dang', 'Bui', 'Do', 'Ho', 'Ngo', 'Duong', 'Ly'];
    $middles_male = ['Van', 'Huu', 'Duc', 'Minh', 'Quang', 'Anh', 'Manh', 'Trong', 'Dinh'];
    $middles_female = ['Thi', 'Ngoc', 'Dieu', 'Thanh', 'Phuong', 'Bich', 'Kieu', 'Hong'];
    $names_male = ['Hung', 'Nam', 'Cuong', 'Dung', 'Thanh', 'Long', 'Tuan', 'Kien', 'Son', 'Hai', 'Viet', 'Quan'];
    $names_female = ['Lan', 'Huong', 'Hoa', 'Mai', 'Linh', 'Trang', 'Phuong', 'Thuy', 'Hanh', 'Oanh', 'Ngoc', 'An'];
    
    $provinces = ['Ha Noi', 'TP. HCM', 'Da Nang', 'Hai Phong', 'Can Tho', 'Quang Ninh', 'Nghe An', 'Thanh Hoa', 'Khanh Hoa', 'Lam Dong', 'Quang Nam', 'Vung Tau'];
    $streets = ['Le Loi', 'Nguyen Hue', 'Le Duan', 'Tran Hung Dao', 'Hai Ba Trung', 'Ly Tu Trong', 'Hoang Van Thu', 'Cach Mang Thang 8'];

    function genName($isMale = true) {
        global $surnames, $middles_male, $middles_female, $names_male, $names_female;
        $s = $surnames[array_rand($surnames)];
        $m = $isMale ? $middles_male[array_rand($middles_male)] : $middles_female[array_rand($middles_female)];
        $n = $isMale ? $names_male[array_rand($names_male)] : $names_female[array_rand($names_female)];
        return "$s $m $n";
    }

    function genPhone() {
        $prefixes = ['090', '091', '098', '033', '034', '077', '088'];
        return $prefixes[array_rand($prefixes)] . rand(1000000, 9999999);
    }

    function genID() {
        return '0' . rand(30, 99) . rand(100, 999) . rand(100000, 999999);
    }

    // 2. Lay danh sach Tour active
    $stmtTours = $pdo->query("SELECT id, name, base_price FROM tours WHERE status = 'active'");
    $tours = $stmtTours->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tours)) {
        die("Loi: Khong tim thay tour nao de seed. Vui long tao tour truoc.\n");
    }

    // 3. Main Loop: Tu 2024-01 den 2026-04
    $currentDate = new DateTime('2024-01-01');
    $endDate = new DateTime('2026-04-19');
    $interval = new DateInterval('P1M'); // Tang moi thang

    $totalDepartures = 0;
    $totalBookings = 0;
    $totalCustomers = 0;

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    while ($currentDate <= $endDate) {
        $yearMonth = $currentDate->format('Y-m');
        echo " - Dang seed du lieu cho thang: $yearMonth...\n";

        // Chon 2-4 tour ngau nhien moi thang de co lich khoi hanh
        $monthlyTours = array_rand($tours, min(count($tours), rand(2, 4)));
        if (!is_array($monthlyTours)) $monthlyTours = [$monthlyTours];

        foreach ($monthlyTours as $tourIdx) {
            $tour = $tours[$tourIdx];
            $departureDate = clone $currentDate;
            $departureDate->modify('+' . rand(5, 25) . ' days'); // Ngay khoi hanh ngau nhien trong thang
            
            // Tao Tour Departure
            $sqlDep = "INSERT INTO tour_departures (tour_id, departure_date, max_seats, booked_seats, status) VALUES (?, ?, ?, ?, ?)";
            $stmtDep = $pdo->prepare($sqlDep);
            $maxSeats = rand(20, 45);
            $depStatus = ($departureDate < new DateTime()) ? 'completed' : 'open';
            $stmtDep->execute([$tour['id'], $departureDate->format('Y-m-d'), $maxSeats, 0, $depStatus]);
            $departureId = $pdo->lastInsertId();
            $totalDepartures++;

            // Tao 3-7 Booking cho moi doan
            $numBookings = rand(3, 7);
            $currentSeats = 0;

            for ($i = 0; $i < $numBookings; $i++) {
                if ($currentSeats >= $maxSeats) break;

                $adults = rand(1, 4);
                $children = (rand(0, 100) > 70) ? rand(1, 2) : 0;
                if ($currentSeats + $adults + $children > $maxSeats) break;

                $bookingDate = clone $departureDate;
                $bookingDate->modify('-' . rand(10, 40) . ' days');
                
                $pricePerAdult = $tour['base_price'] ?: 5000000;
                $totalPrice = ($adults * $pricePerAdult) + ($children * $pricePerAdult * 0.75);
                
                $contactIsMale = rand(0, 1) == 1;
                $contactName = genName($contactIsMale);
                $contactPhone = genPhone();
                $contactEmail = strtolower(str_replace(' ', '', $contactName)) . rand(1, 99) . "@gmail.com";
                $contactAddress = rand(1, 400) . " " . $streets[array_rand($streets)] . ", " . $provinces[array_rand($provinces)];

                // 1. Insert Booking
                $sqlBook = "INSERT INTO bookings (tour_id, departure_id, booking_date, original_price, final_price, total_price, status, contact_name, contact_phone, contact_email, contact_address, adults, children, infants, created_by, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtBook = $pdo->prepare($sqlBook);
                $stmtBook->execute([
                    $tour['id'], $departureId, $bookingDate->format('Y-m-d H:i:s'), 
                    $totalPrice, $totalPrice, $totalPrice, 'hoan_tat', $contactName, $contactPhone, 
                    $contactEmail, $contactAddress, $adults, $children, 0, 1, $bookingDate->format('Y-m-d H:i:s')
                ]);
                $bookingId = $pdo->lastInsertId();
                $totalBookings++;

                // 2. Insert Customers
                for ($j = 0; $j < ($adults + $children); $j++) {
                    $isAdult = ($j < $adults);
                    $isMale = rand(0, 1) == 1;
                    $cusName = ($j == 0) ? $contactName : genName($isMale);
                    $cusPhone = ($j == 0) ? $contactPhone : genPhone();
                    
                    $birthDate = new DateTime();
                    if ($isAdult) {
                        $birthDate->modify('-' . rand(20, 60) . ' years');
                    } else {
                        $birthDate->modify('-' . rand(3, 12) . ' years');
                    }

                    $sqlCus = "INSERT INTO booking_customers (booking_id, full_name, gender, birth_date, phone, id_card, passenger_type, payment_status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtCus = $pdo->prepare($sqlCus);
                    $stmtCus->execute([
                        $bookingId, $cusName, ($isMale ? 'Nam' : 'Nu'), 
                        $birthDate->format('Y-m-d'), $cusPhone, genID(), 
                        ($isAdult ? 'adult' : 'child'), 'paid'
                    ]);
                    $totalCustomers++;
                }

                // 3. Insert Transaction (Full pay)
                $sqlTrans = "INSERT INTO transactions (booking_id, amount, method, type, date) VALUES (?, ?, ?, ?, ?)";
                $stmtTrans = $pdo->prepare($sqlTrans);
                $stmtTrans->execute([$bookingId, $totalPrice, 'transfer', 'payment', $bookingDate->modify('+2 hours')->format('Y-m-d H:i:s')]);

                $currentSeats += ($adults + $children);
            }

            // Cap nhat so ghe da dat cho Departure
            $pdo->exec("UPDATE tour_departures SET booked_seats = $currentSeats WHERE id = $departureId");
        }

        $currentDate->add($interval);
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\n=== SEEDING THANH CONG! ===\n";
    echo "So doan khoi hanh: $totalDepartures\n";
    echo "So booking: $totalBookings\n";
    echo "So hanh khach: $totalCustomers\n";

} catch (Exception $e) {
    echo "LOI: " . $e->getMessage() . "\n";
}
