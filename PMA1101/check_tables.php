<?php
require 'configs/env.php';
require 'models/BaseModel.php';
$pdo = BaseModel::getPdo();
if (!$pdo) {
    new BaseModel();
    $pdo = BaseModel::getPdo();
}
$stmt = $pdo->query('SHOW TABLES');
while($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo $row[0] . PHP_EOL;
}
