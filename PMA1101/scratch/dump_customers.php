<?php
require 'configs/env.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

$stmt = $pdo->query("SELECT user_id, full_name, phone, email, id_card FROM users LIMIT 20");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "CUSTOMERS DUMP:\n";
foreach ($customers as $c) {
    echo "ID: {$c['user_id']}, Name: {$c['full_name']}, Phone: '{$c['phone']}', Email: '{$c['email']}', IDCard: '{$c['id_card']}'\n";
}
