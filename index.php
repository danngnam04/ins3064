<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
  $name = "Dang Nam";
  $age = 21;
  $course = array("Java", "C", "PHP");
  echo  "Name: " . $name . ", age: " . $age . "<br/>3rd course is: " . $course[2];
  
  $x = $_GET["x"];
  $y = $_GET["y"];

  echo "x + y =" . ($x + $y) . "<br>";
  echo "x == y" . ($x==$y) . "<br>";
  
  ?>
</body>
</html>