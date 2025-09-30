<?php
include "connection.php";
$id=$_GET["id"];
$firstname="";
$lastname="";
$email="";
$contact="";

$res=mysqli_query($link,"select * from table1 where id=$id");
while ($row=mysqli_fetch_array($res))
{
    $firstname=$row["firstname"];
    $lastname=$row["lastname"];
    $email=$row["email"];
    $contact=$row["contact"];

}
header("location.index.php");
?>

<html lang="en" xmlns="">
<head>
    <title>User Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <!-- short column display for forms rows -->
    <!--visit https://www.w3schools.com/bootstrap/bootstrap_forms.asp search for forms template and use it.-->
    <div class="col-lg-4">
        <h2>User data form</h2>
        <form action="" name="form1" method="post">
            <div class="form-group">
                <label for="firstname">First name:</label>
                <input type="text" class="form-control" id="firstname" placeholder="Enter first name" name="firstname" value="<?php echo $firstname; ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Last name:</label>
                <input type="text" class="form-control" id="lastname" placeholder="Enter Last name" name="lastname" value="<?php echo $lastname; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" class="form-control" id="contact" placeholder="Enter contact" name="contact" value="<?php echo $contact; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-default">Update</button>

        </form>
    </div>
</div>

</body>

<?php
if(isset($_POST["update"]))
    {
        mysqli_query($link,"update table1 set firstname='$_POST[firstname]',lastname='$_POST[lastname]',email='$_POST[email]',contact='$_POST[contact]' where id=$id");

        ?>
        <script type="text/javascript">
            window.location="index.php";
        </script>
        <?php
    }
?>

</html>