<?php
require_once 'configs/database.php';
$pdo = get_pdo();
$stmt = $pdo->query("DESCRIBE tours");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . "\n";
}
unlink(__FILE__);
