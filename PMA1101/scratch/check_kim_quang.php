<?php
require 'configs/env.php';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

$stmt = $pdo->prepare("SELECT * FROM users WHERE full_name = ?");
$stmt->execute(['Kim Quang']);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "SEARCH RESULT:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
