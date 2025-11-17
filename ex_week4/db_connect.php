<?php
$link = mysqli_connect("localhost", "root", "", "login_demo");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
