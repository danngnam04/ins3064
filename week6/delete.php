<?php
include "connection.php";

$id = $_GET["id"] ?? 0;

// When the form is submitted with "yes", delete and redirect
if (isset($_POST["confirm"]) && $_POST["confirm"] === "yes") {
    mysqli_query($link, "DELETE FROM table1 WHERE id=$id");
    header("Location: index.php");
    exit;
}

// When the form is submitted with "no", just go back
if (isset($_POST["confirm"]) && $_POST["confirm"] === "no") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirm-box {
            background: #fff;
            padding: 25px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        button {
            margin: 10px;
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }
        .yes { background-color: #dc3545; color: white; }
        .no  { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="confirm-box">
        <h3>Are you sure you want to delete this record?</h3>
        <form method="post">
            <button type="submit" name="confirm" value="yes" class="yes">Yes</button>
            <button type="submit" name="confirm" value="no" class="no">No</button>
        </form>
    </div>
</body>
</html>
