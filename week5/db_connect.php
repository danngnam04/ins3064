<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'myapp';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // ném Exception khi lỗi
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch dạng mảng kết hợp
    PDO::ATTR_EMULATE_PREPARES   => false,                  // dùng prepared statement thật
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // ... code xử lý truy vấn ở đây ...
} catch (PDOException $e) {
    http_response_code(500);
    // Không echo lỗi thật ra ngoài để tránh lộ thông tin
    die('Database connection failed');
}
