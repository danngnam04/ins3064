<?php
include "connection.php"; // kết nối database LaptopShop
?>

<html lang="en">
<head>
    <title>Laptop Shop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">

    <div class="col-lg-4">
        <h2>Laptop Form</h2>
        <form action="" name="form1" method="post">
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" class="form-control" id="brand" placeholder="Enter Brand" name="brand">
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" class="form-control" id="model" placeholder="Enter Model" name="model">
            </div>
            <div class="form-group">
                <label for="processor">Processor:</label>
                <input type="text" class="form-control" id="processor" placeholder="Enter Processor" name="processor">
            </div>
            <div class="form-group">
                <label for="ram">RAM:</label>
                <input type="text" class="form-control" id="ram" placeholder="Enter RAM" name="ram">
            </div>
            <div class="form-group">
                <label for="storage">Storage:</label>
                <input type="text" class="form-control" id="storage" placeholder="Enter Storage" name="storage">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" placeholder="Enter Price" name="price">
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" placeholder="Enter Stock" name="stock">
            </div>

            <button type="submit" name="insert" class="btn btn-default">Insert</button>
            <button type="submit" name="update" class="btn btn-default">Update</button>
            <button type="submit" name="delete" class="btn btn-default">Delete</button>
        </form>
    </div>

    <div class="col-lg-12">
        <h2>Laptop List</h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Processor</th>
                <th>RAM</th>
                <th>Storage</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($link)) {
                $res = mysqli_query($link, "SELECT * FROM laptops");
            }
            while($row = mysqli_fetch_array($res)) {
                echo "<tr>";
                echo "<td>". $row["id"] ."</td>";
                echo "<td>". $row["brand"] ."</td>";
                echo "<td>". $row["model"] ."</td>";
                echo "<td>". $row["processor"] ."</td>";
                echo "<td>". $row["ram"] ."</td>";
                echo "<td>". $row["storage"] ."</td>";
                echo "<td>". $row["price"] ."</td>";
                echo "<td>". $row["stock"] ."</td>";
                echo "<td><a href='edit.php?id=". $row["id"] ."'><button type='button' class='btn btn-success'>Edit</button></a></td>";
                echo "<td><a href='delete.php?id=". $row["id"] ."'><button type='button' class='btn btn-danger'>Delete</button></a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

<?php
// Insert
if(isset($_POST["insert"])) {
    mysqli_query($link,"INSERT INTO laptops VALUES (NULL,'$_POST[brand]','$_POST[model]','$_POST[processor]','$_POST[ram]','$_POST[storage]','$_POST[price]','$_POST[stock]')");
    echo "<script>window.location.href=window.location.href;</script>";
}

// Delete (xóa theo Brand + Model)
if(isset($_POST["delete"])) {
    mysqli_query($link,"DELETE FROM laptops WHERE brand='$_POST[brand]' AND model='$_POST[model]'");
    echo "<script>window.location.href=window.location.href;</script>";
}

// Update (cập nhật theo Brand + Model)
if(isset($_POST["update"])) {
    mysqli_query($link,"UPDATE laptops SET processor='$_POST[processor]', ram='$_POST[ram]', storage='$_POST[storage]', price='$_POST[price]', stock='$_POST[stock]' WHERE brand='$_POST[brand]' AND model='$_POST[model]'");
    echo "<script>window.location.href=window.location.href;</script>";
}
?>
</body>
</html>
