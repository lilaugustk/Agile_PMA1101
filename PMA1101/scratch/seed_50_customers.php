<?php
require_once 'configs/env.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== BAT DAU SEEDING 50 KHACH HANG THUC TE ===\n";

    // 1. Data Generators
    $surnames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Phan', 'Vũ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
    $middles_male = ['Văn', 'Hữu', 'Đức', 'Minh', 'Quang', 'Anh', 'Mạnh', 'Trọng', 'Đình'];
    $middles_female = ['Thị', 'Ngọc', 'Diệu', 'Thanh', 'Phương', 'Bích', 'Kiều', 'Hồng'];
    $names_male = ['Hùng', 'Nam', 'Cường', 'Dũng', 'Thành', 'Long', 'Tuấn', 'Kiên', 'Sơn', 'Hải', 'Việt', 'Quân'];
    $names_female = ['Lan', 'Hương', 'Hoa', 'Mai', 'Linh', 'Trang', 'Phương', 'Thủy', 'Hạnh', 'Oanh', 'Ngọc', 'An'];
    
    $provinces = ['Hà Nội', 'TP. HCM', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'Quảng Ninh', 'Nghệ An', 'Thanh Hóa', 'Khánh Hòa', 'Lâm Đồng', 'Quảng Nam', 'Vũng Tàu'];
    $districts = ['Quận 1', 'Quận 3', 'Quận Cầu Giấy', 'Quận Ba Đình', 'Quận Ninh Kiều', 'Quận Hải Châu', 'Huyện Thanh Trì', 'Thanh Xuân'];
    $streets = ['Lê Lợi', 'Nguyễn Huệ', 'Lê Duẩn', 'Trần Hưng Đạo', 'Hai Bà Trưng', 'Lý Tự Trọng', 'Hoàng Văn Thụ', 'Cách Mạng Tháng 8'];

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

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Clear existing customers if any to avoid confusion (Optional, based on user's 'rebuild' goal)
    // $pdo->exec("DELETE FROM users WHERE role = 'customer'");

    for ($i = 0; $i < 50; $i++) {
        $isMale = rand(0, 1) == 1;
        $fullName = genName($isMale);
        $phone = genPhone();
        $email = strtolower(str_replace(' ', '', $fullName)) . rand(1, 999) . "@gmail.com";
        $password = password_hash('123', PASSWORD_DEFAULT);

        // 1. Insert into 'users' (Slim Auth)
        $sqlUser = "INSERT INTO users (full_name, email, phone, role, password_hash) VALUES (?, ?, ?, 'customer', ?)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([$fullName, $email, $phone, $password]);
        $userId = $pdo->lastInsertId();

        // 2. Insert into 'customer_profiles' (Master Data)
        $gender = $isMale ? 'Nam' : 'Nữ';
        $birthDate = date('Y-m-d', strtotime('-' . rand(18, 65) . ' years -' . rand(1, 365) . ' days'));
        $idCard = genID();
        $address = (rand(1, 400)) . " Đường " . $streets[array_rand($streets)] . ", " . $districts[array_rand($districts)] . ", " . $provinces[array_rand($provinces)];
        $passengerType = 'adult';
        $specialRequest = (rand(0, 10) > 8) ? "Cần hỗ trợ đặc biệt, dị ứng hàu" : "";

        $sqlProfile = "INSERT INTO customer_profiles (user_id, full_name, email, phone, gender, birth_date, id_card, address, passenger_type, special_request) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtProfile = $pdo->prepare($sqlProfile);
        $stmtProfile->execute([$userId, $fullName, $email, $phone, $gender, $birthDate, $idCard, $address, $passengerType, $specialRequest]);

        if (($i + 1) % 10 == 0) echo " - Da tao " . ($i + 1) . " khach hang...\n";
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "\n=== SEEDING THANH CONG! ===\n";
    echo "Da tao 50 khach hang voi day du thong tin dang nhap va ho so chi tiet.\n";

} catch (Exception $e) {
    echo "LOI: " . $e->getMessage() . "\n";
}
