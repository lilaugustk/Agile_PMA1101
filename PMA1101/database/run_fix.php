<?php
require_once __DIR__ . '/../configs/env.php';
require_once __DIR__ . '/../models/BaseModel.php';

// Khởi tạo BaseModel để kích hoạt kết nối CSDL (hàm __construct sẽ tạo PDO)
new BaseModel();
$pdo = BaseModel::getPdo();

try {
    $sqlFile = __DIR__ . '/fix_missing_tables.sql';
    if (!file_exists($sqlFile)) {
        die("Lỗi: Không tìm thấy file SQL tại $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Thực thi SQL
    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>SQL Hotfix 7.5 Success!</h2>";
    echo "<p>Bảng <b>departure_resources</b> và <b>pages</b> đã được tạo thành công.</p>";
    echo "<p><a href='../../?action=about'>Quay lại kiểm tra trang About</a></p>";
} catch (Exception $e) {
    echo "<h2 style='color: red;'>SQL Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
