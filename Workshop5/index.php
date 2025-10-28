<?php
session_start();
if (!empty($_SESSION['user'])) {
  header('Location: dashboard.php'); exit;
}
$message = '';
if (!empty($_GET['m'])) {
  $map = [
    'bad'      => '⚠️ Usuario o contraseña incorrectos.',
    'inactive' => '🚫 Tu usuario está inactivo.',
    'bye'      => '👋 Sesión cerrada.',
    'ok'       => '✅ Login exitoso.'  // la mostraremos al volver del login
  ];
  $message = $map[$_GET['m']] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Workshop 5</title>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <style>
    body{background:#f4f6f8;display:flex;justify-content:center;align-items:center;height:100vh;}
    .card{background:#fff;border-radius:10px;padding:32px;max-width:420px;width:100%;box-shadow:0 2px 10px rgba(0,0,0,.1);}
  </style>
</head>
<body>
  <div class="card">
    <h2 class="text-center">User Login</h2>
    <?php if ($message): ?>
      <div class="alert alert-info text-center" style="margin-top:10px;"><?=$message?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" style="margin-top:16px;">
      <div class="form-group">
        <input class="form-control" type="text" name="username" placeholder="Your username" required>
      </div>
      <div class="form-group">
        <input class="form-control" type="password" name="password" placeholder="Your password" required>
      </div>
      <div class="text-center">
        <button class="btn btn-primary" type="submit">Login</button>
        <a class="btn btn-success" href="register.php">Registrarme</a>
      </div>
    </form>
  </div>
</body>
</html>
