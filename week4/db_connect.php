<?php
$link = mysqli_connect("localhost", "root", "", "LoginReg");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
