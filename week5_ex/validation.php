<?php
session_start();

/* connect to database check user*/
$con=mysqli_connect('localhost','root');
mysqli_select_db($con,'LoginReg');

/* create variables to store data */
$name =$_POST['user'];
$pass =$_POST['password'];

/* select data from DB */
$s="select * from userReg where name='$name'&& password='$pass'";

/* result variable to store data */
$result = mysqli_query($con,$s);

/* check for duplicate names and count records */
$num =mysqli_num_rows($result);
if($num==1){
  /* Storing the username and session */
    $_SESSION['username'] =$name;
    header('location:home.php');
}else{
    header('location:login.php');
}
