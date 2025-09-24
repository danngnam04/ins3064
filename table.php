<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <h1>This is my first PHP file</h1>
    <table border="1">
      <?php
      for ($i = 0; $i < 5; $i++) {
        echo "<tr>";
        echo "<td>$i</td>";
        echo "</tr>";
      }
      ?>
    </table>
</body>
</html>
