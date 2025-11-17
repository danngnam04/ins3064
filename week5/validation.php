<?php
session_start();

/* connect to database check user */
$con = mysqli_connect('localhost', 'root', '', 'myapp'); // sửa tham số: thêm '' cho mật khẩu và truyền 'myapp' là tên DB
if (!$con) { die('DB connect failed'); }
// Nếu vẫn muốn gọi select_db: mysqli_select_db($con, 'myapp');

/* create variables to store data */
$name = $_POST['user'] ?? '';
$pass = $_POST['password'] ?? '';

/* escape input để tránh lỗi cú pháp và giảm rủi ro injection */
$name = mysqli_real_escape_string($con, $name);
$pass = mysqli_real_escape_string($con, $pass);

/* select data from DB */
$s = "SELECT * FROM userReg WHERE name='$name' && password='$pass'";

/* result variable to store data */
$result = mysqli_query($con, $s);

/* check for duplicate names and count records */
$num = $result ? mysqli_num_rows($result) : 0;
if ($num == 1) {
    /* Storing the username and session */
    $_SESSION['username'] = $name;
    header('Location: home.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
