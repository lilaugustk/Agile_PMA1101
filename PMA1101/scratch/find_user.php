<?php
require 'configs/env.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

$stmt = $pdo->prepare("SELECT * FROM users WHERE full_name = :name AND role = 'customer'");
$stmt->execute(['name' => 'Kim Quang']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "USER Kim Quang: " . json_encode($user) . "\n";
