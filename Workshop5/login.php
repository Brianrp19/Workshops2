<?php
  session_start();
  if (isset($_SESSION['user']) && $_SESSION['user']) {
    // Si ya hay sesión, redirige al dashboard
    header('Location: dashboard.php');
    exit;
  }

  // Mostrar mensajes de error o éxito
  $message = "";
  if (!empty($_REQUEST['status'])) {
    switch($_REQUEST['status']) {
      case 'login':
        $message = '⚠️ Usuario no existe o datos incorrectos.';
        break;
      case 'inactive':
        $message = '🚫 Tu usuario está inactivo.';
        break;
      case 'error':
        $message = '❌ Ocurrió un error al procesar el inicio de sesión.';
        break;
      case 'logout':
        $message = '👋 Sesión cerrada correctamente.';
        break;
    }
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Workshop 5</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <style>
    body {
      background: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: white;
      border-radius: 10px;
      padding: 40px;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .msg { margin-bottom: 15px; }
  </style>
</head>

<body>
  <div class="login-container">
    <h1 class="text-center mb-4">User Login</h1>

    <?php if ($message): ?>
      <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="form-inline" role="form" style="display: flex; flex-direction: column; gap: 10px;">
      <div class="form-group">
        <input type="text" class="form-control" name="username" placeholder="Your username" required style="width:100%;">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Your password" required style="width:100%;">
      </div>

      <div class="text-center mt-3" style="display:flex; justify-content:center; gap:10px;">
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="register.php" class="btn btn-success">Registrarme</a>
      </div>
    </form>
  </div>
</body>
</html>
