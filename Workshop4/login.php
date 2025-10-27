<?php
$username = isset($_GET['username']) ? $_GET['username'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>
  <h2>Login</h2>
  <label>Usuario:</label><br>
  <input type="text" value="<?= htmlspecialchars($username) ?>" readonly><br><br>
</body>
</html>
