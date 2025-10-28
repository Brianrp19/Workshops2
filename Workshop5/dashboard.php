<?php
session_start();
if (empty($_SESSION['user'])) { header('Location: index.php'); exit; }
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Dashboard</title>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body class="container">
  <h1>Bienvenido <?=htmlspecialchars($user['name'].' '.$user['lastname'])?></h1>
  <p>Rol: <strong><?=htmlspecialchars($user['role'])?></strong></p>
  <p>Último login: <?=htmlspecialchars($user['last_login_datetime'] ?? '—')?></p>

  <nav class="nav">
    <?php if ($user['role'] === 'Administrador') { ?>
      <li class="nav-item"><a class="nav-link active" href="#">Users</a></li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Arboles</a></li>
  </nav>

  <p><a href="logout.php" class="btn btn-default">Logout</a></p>
</body>
</html>
