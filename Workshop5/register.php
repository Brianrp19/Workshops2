<?php
session_start();
if (!empty($_SESSION['user'])) {
  header('Location: dashboard.php'); exit;
}
$msg = $_GET['m'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Registro</title>
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body class="container">
  <h1>Registro de usuario</h1>
  <?php if ($msg === 'ok'): ?><div class="alert alert-success">Usuario creado, ya podés iniciar sesión.</div><?php endif; ?>
  <?php if ($msg === 'dup'): ?><div class="alert alert-warning">El username ya existe.</div><?php endif; ?>
  <?php if ($msg === 'err'): ?><div class="alert alert-danger">No se pudo crear el usuario.</div><?php endif; ?>

  <form action="register_process.php" method="post" class="form-inline">
    <input class="form-control" type="text"     name="username" placeholder="Username" required>
    <input class="form-control" type="text"     name="name"     placeholder="Nombre"   required>
    <input class="form-control" type="text"     name="lastname" placeholder="Apellido" required>
    <input class="form-control" type="password" name="password" placeholder="Contraseña" required>
    <select class="form-control" name="role">
      <option value="Usuario">Usuario</option>
      <option value="Administrador">Administrador</option>
    </select>
    <button class="btn btn-primary" type="submit">Crear</button>
  </form>

  <p class="mt-3"><a href="index.php">Volver al Login</a></p>
</body>
</html>
