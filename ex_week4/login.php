<?php
include("db_connect.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Kiểm tra username hoặc email đã tồn tại chưa
    $check_user = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($link, $check_user);

    if (mysqli_num_rows($result) > 0) {
        echo "Username or Email already exists!";
    } else {
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($link, $query)) {
            echo "Registration successful!";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="" method="post">
        <label>Username</label><br>
        <input type="text" name="username" required><br>

        <label>Email</label><br>
        <input type="email" name="email" required><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>
