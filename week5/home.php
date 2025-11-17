<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:login.php');
}
?>

<html lang='en'>
<head>
    <title>Home page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


</head>
<body>
<div class="container">

    <a class="float-right" href="logout.php">LOGOUT</a>
    <h1>Welcome <? echo $_SESSION['username']; ?> </h1>

</div>



</body>