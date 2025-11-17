<?php
$db_host = 'localhost';
$db_user = 'root'; // Sửa nếu cần
$db_pass = '';     // Sửa nếu cần
$db_name = 'ins3064'; // Tên CSDL bạn tạo trong Laragon

// Tạo kết nối
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Đặt charset thành UTF-8
mysqli_set_charset($conn, "utf8");