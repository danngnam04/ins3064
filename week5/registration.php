<?php
session_start();
header("location:login.php"); // nếu muốn xử lý xong mới chuyển trang, hãy dời dòng này xuống sau khi insert thành công

/* connect to database check user*/
$con = mysqli_connect('localhost','root','','myapp'); // truyền db ngay trong connect
if (!$con) { die('DB connect failed'); }
// nếu vẫn muốn gọi select_db như cũ, để nguyên nhưng nhớ đặt tên DB trong dấu nháy
// mysqli_select_db($con, 'myapp');

/* create variables to store data */
$name = $_POST['user'] ?? '';
$pass = $_POST['password'] ?? '';

// escape để tránh lỗi cú pháp và giảm rủi ro injection (không đổi cấu trúc if/else hiện có)
$name = mysqli_real_escape_string($con, $name);
$pass = mysqli_real_escape_string($con, $pass);

/* select data from DB */
$s = "SELECT * FROM userReg WHERE name='$name'";

/* result variable to store data */
$result = mysqli_query($con, $s);

/* check for duplicate names and count records */
$num = mysqli_num_rows($result);
if ($num == 1) {
    echo "Username Exists";
} else {
    // lưu plain-text theo cấu trúc cũ; (khuyến nghị về sau dùng password_hash)
    $reg = "INSERT INTO userReg(name,password) VALUES ('$name','$pass')";
    mysqli_query($con, $reg);
    echo "registration successful";
}
