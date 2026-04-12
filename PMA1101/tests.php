<?php require 'configs/env.php'; $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD); $stmt = $pdo->query('SHOW TABLES'); print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
