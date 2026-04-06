<?php
/**
 * TOURS MANAGEMENT SYSTEM - MEGA SEEDER
 * Purpose: Fill the system with large amount of logical data to simulate long-term operation.
 */

require_once __DIR__ . '/../configs/env.php';
require_once __DIR__ . '/../models/BaseModel.php';

// Khởi tạo connection
new BaseModel();
$pdo = BaseModel::getPdo();

// Tăng giới hạn thời gian cho script lớn
set_time_limit(600);
ini_set('memory_limit', '256M');

echo "<h2>🚀 Bắt đầu quá trình Mega Seeding...</h2>";

try {
    // 1. LẤY DANH SÁCH ID HIỆN CÓ
    $categoryIds = $pdo->query("SELECT id FROM tour_categories")->fetchAll(PDO::FETCH_COLUMN);
    $supplierIds = $pdo->query("SELECT id FROM suppliers")->fetchAll(PDO::FETCH_COLUMN);
    $versionIds = $pdo->query("SELECT id FROM tour_versions")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($categoryIds)) die("Lỗi: Cần có danh mục Tour trước khi chạy Seeder.");
    if (empty($supplierIds)) die("Lỗi: Cần có nhà cung cấp trước khi chạy Seeder.");

    // 2. TẠO NGƯỜI DÙNG (CUSTOMERS & GUIDES)
    echo "<li>Đang tạo người dùng mẫu...</li>";
    $passHash = password_hash('123456', PASSWORD_DEFAULT);
    $roles = ['customer', 'guide', 'customer', 'customer']; // Tỉ lệ khách hàng cao hơn
    
    for ($i = 1; $i <= 50; $i++) {
        $role = $roles[array_rand($roles)];
        $sqlUser = "INSERT IGNORE INTO users (full_name, email, phone, role, password_hash) 
                    VALUES (:name, :email, :phone, :role, :pass)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':name' => "Người dùng Mẫu $i",
            ':email' => "user$i@example.com",
            ':phone' => "090" . str_pad($i, 7, '0', STR_PAD_LEFT),
            ':role' => $role,
            ':pass' => $passHash
        ]);
        
        // Nếu là guide, tạo profile guide
        if ($role == 'guide') {
            $lastId = $pdo->lastInsertId();
            if ($lastId) {
                $pdo->exec("INSERT IGNORE INTO guides (user_id, languages, experience_years, rating, health_status) 
                            VALUES ($lastId, 'Tiếng Việt, Tiếng Anh', ".rand(1,10).", 5, 'Tốt')");
            }
        }
    }
    $customerIds = $pdo->query("SELECT user_id FROM users WHERE role = 'customer'")->fetchAll(PDO::FETCH_COLUMN);
    $guideIds = $pdo->query("SELECT user_id FROM users WHERE role = 'guide'")->fetchAll(PDO::FETCH_COLUMN);

    // 3. TẠO TOURS (NẾU CHƯA ĐỦ)
    echo "<li>Đang bổ sung danh sách Tour...</li>";
    $tourNames = [
        "Khám phá Hà Giang - Mùa hoa tam giác mạch",
        "Hàn Quốc: Seoul - Nami - Everland (Cao cấp)",
        "Du lịch Thái Lan: Bangkok - Pattaya 5N4Đ",
        "Đà Lạt - Thành phố mộng mơ",
        "Tây Nguyên: Buôn Ma Thuột - Pleiku",
        "Phú Quốc - Đảo ngọc thiên đường",
        "Châu Âu: Pháp - Thụy Sỹ - Ý (11 Ngày)",
        "Trung Quốc: Phượng Hoàng Cổ Trấn - Trương Gia Giới",
        "Quy Nhơn - Tuy Hòa: Xứ nẫu thân thương",
        "Singapore - Malaysia: Hành trình 2 quốc gia"
    ];

    foreach ($tourNames as $name) {
        $sqlTour = "INSERT IGNORE INTO tours (name, category_id, base_price, status, featured) 
                    VALUES (:name, :cat, :price, 'active', :fav)";
        $stmtTour = $pdo->prepare($sqlTour);
        $stmtTour->execute([
            ':name' => $name,
            ':cat' => $categoryIds[array_rand($categoryIds)],
            ':price' => rand(50, 400) * 100000,
            ':fav' => rand(0, 1)
        ]);
    }
    $tourIds = $pdo->query("SELECT id FROM tours")->fetchAll(PDO::FETCH_COLUMN);

    // 4. TẠO LỊCH KHỞI HÀNH (DEPARTURES)
    echo "<li>Đang tạo lịch khởi hành (Từ 2024 đến 2026)...</li>";
    
    $startDate = "2024-01-01";
    $endDate = "2026-12-31";
    
    foreach ($tourIds as $tId) {
        // Mỗi tour tạo khoảng 30-40 lịch khởi hành rải đều
        for ($j = 0; $j < 35; $j++) {
            $randomDays = rand(0, 1000); // Khoảng 3 năm
            $depDate = date('Y-m-d', strtotime("$startDate +$randomDays days"));
            
            $sqlDep = "INSERT IGNORE INTO tour_departures (tour_id, departure_date, max_seats, booked_seats, price_adult, status) 
                       VALUES (:tour, :date, :max, 0, (SELECT base_price FROM tours WHERE id = :tour), 'open')";
            $stmtDep = $pdo->prepare($sqlDep);
            $stmtDep->execute([
                ':tour' => $tId,
                ':date' => $depDate,
                ':max' => rand(25, 45)
            ]);
        }
    }
    $departureIds = $pdo->query("SELECT id FROM tour_departures")->fetchAll(PDO::FETCH_COLUMN);

    // 5. TẠO BOOKINGS & PASSENGERS
    echo "<li>Đang tạo hàng nghìn Booking mẫu rải đều các năm...</li>";
    $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    $adminId = 1;

    foreach ($departureIds as $dId) {
        $depInfo = $pdo->query("SELECT d.*, t.base_price FROM tour_departures d JOIN tours t ON d.tour_id = t.id WHERE d.id = $dId")->fetch();
        if (!$depInfo) continue;

        // Mỗi departure tạo 3-8 bookings để dữ liệu dày đặc
        $numBookings = rand(3, 8);
        for ($k = 0; $k < $numBookings; $k++) {
            $custId = $customerIds[array_rand($customerIds)];
            $adults = rand(1, 5);
            $children = rand(0, 3);
            $price = $depInfo['price_adult'] ?: $depInfo['base_price'];
            $total = ($adults + $children * 0.75) * $price;
            
            // Logic trạng thái theo thời gian
            if ($depInfo['departure_date'] < date('Y-m-d')) {
                $status = (rand(0, 10) > 1) ? 'completed' : 'cancelled';
            } else {
                $status = $statuses[array_rand(['pending', 'confirmed', 'confirmed'])];
            }

            $sqlBooking = "INSERT INTO bookings (tour_id, departure_id, customer_id, adults, children, final_price, total_price, status, created_by, booking_date) 
                           VALUES (:tour, :dep, :cust, :a, :c, :f, :t, :st, :by, :bdate)";
            $stmtBooking = $pdo->prepare($sqlBooking);
            $stmtBooking->execute([
                ':tour' => $depInfo['tour_id'],
                ':dep' => $dId,
                ':cust' => $custId,
                ':a' => $adults,
                ':c' => $children,
                ':f' => $total,
                ':t' => $total,
                ':st' => $status,
                ':by' => $adminId,
                ':bdate' => date('Y-m-d H:i:s', strtotime($depInfo['departure_date'] . " - " . rand(10, 60) . " days"))
            ]);
            
            $bookingId = $pdo->lastInsertId();
            
            // Tạo hành khách nhanh
            $pdo->exec("INSERT INTO booking_customers (booking_id, full_name, passenger_type) 
                        VALUES ($bookingId, 'Khách chính $bookingId', 'adult')");
            
            if ($status != 'cancelled') {
                $pdo->exec("UPDATE tour_departures SET booked_seats = booked_seats + $adults + $children WHERE id = $dId");
            }
        }
    }

    // 6. TẠO CHI PHÍ VÀ CÔNG NỢ (RESOURCES)
    echo "<li>Đang tạo dữ liệu chi phí & công nợ...</li>";
    foreach ($departureIds as $dId) {
        // Chỉ tạo chi phí cho tour đã/đang khởi hành hoặc sắp khởi hành
        $depDate = $pdo->query("SELECT departure_date FROM tour_departures WHERE id = $dId")->fetchColumn();
        if ($depDate < date('Y-m-d', strtotime('+30 days'))) {
            // Mỗi tour tạo 2 nhà cung cấp (1 khách sạn, 1 xe)
            $selectedSuppliers = array_rand(array_flip($supplierIds), 2);
            foreach ($selectedSuppliers as $sId) {
                $totalAmt = rand(5, 20) * 1000000;
                $paidAmt = (rand(0, 10) > 7) ? $totalAmt : rand(0, $totalAmt); // 30% là chưa trả hết nợ
                
                $sqlRes = "INSERT INTO departure_resources (departure_id, supplier_id, service_type, quantity, unit_price, total_amount, paid_amount, payment_status) 
                           VALUES (:dep, :sup, :type, :qty, :unit, :total, :paid, :st)";
                $stmtRes = $pdo->prepare($sqlRes);
                $stmtRes->execute([
                    ':dep' => $dId,
                    ':sup' => $sId,
                    ':type' => ($sId % 2 == 0 ? "Hotel" : "Transport"),
                    ':qty' => 1,
                    ':unit' => $totalAmt,
                    ':total' => $totalAmt,
                    ':paid' => $paidAmt,
                    ':st' => ($totalAmt == $paidAmt) ? 'paid' : 'unpaid'
                ]);
            }
        }
    }

    // 7. THÔNG BÁO & ĐÁNH GIÁ & BLOGS
    echo "<li>Đang tạo thông báo mẫu & đánh giá khách hàng...</li>";
    for ($n = 0; $n < 30; $n++) {
        $pdo->exec("INSERT INTO notifications (user_id, message, is_read) VALUES (1, 'Hệ thống ghi nhận hoạt động mới $n', ".(rand(0,1)).")");
    }
    
    foreach ($tourIds as $tId) {
        $pdo->exec("INSERT INTO tour_reviews (tour_id, full_name, rating, comment, status) 
                    VALUES ($tId, 'Khách hàng Ẩn Danh', ".rand(4, 5).", 'Chuyến đi tuyệt vời, dịch vụ rất tốt!', 'approved')");
    }

    echo "<li>Đang tạo bài viết blogs...</li>";
    $blogTitles = [
        "10 Địa điểm không thể bỏ qua khi đến Sapa",
        "Kinh nghiệm săn mây Đà Lạt cực đỉnh",
        "Nên đi du lịch Nhật Bản mùa nào đẹp nhất?",
        "Top 5 resort sang chảnh nhất Phú Quốc",
        "Hành trình xuyên Việt: Những cung đường rực rỡ"
    ];
    foreach ($blogTitles as $bt) {
        $slug = strtolower(str_replace(' ', '-', $bt));
        $pdo->exec("INSERT IGNORE INTO blogs (title, slug, summary, content, status) 
                    VALUES ('$bt', '$slug', 'Tóm tắt bài viết $bt', 'Nội dung chi tiết bài viết $bt...', 'published')");
    }

    echo "<h2 style='color: green;'>✅ Hoàn thành Mega Seeding!</h2>";
    echo "<p>Hệ thống hiện đã sở hữu hàng trăm Booking, Tour và dữ liệu công nợ chân thực.</p>";
    echo "<p><a href='/Agile/PMA1101/index.php?mode=admin'>Vào Dashboard kiểm tra kết quả ngay</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Lỗi Mega Seeding</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
