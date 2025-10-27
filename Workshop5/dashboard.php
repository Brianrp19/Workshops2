<?php
session_start();
if (empty($_SESSION['user'])) {
  header('Location: index.php'); // o /pages/login.php
  exit();
}
$user = $_SESSION['user'];
?>
<h1>Bienvenido <?php echo htmlspecialchars($user['name'].' '.$user['lastname']); ?></h1>
<a href="logout.php">Logout</a>

<nav class="nav">
  <?php if ($user['role'] === 'Administrador') { ?>
    <li class="nav-item"><a class="nav-link active" href="#">Users</a></li>
  <?php } ?>
  <li class="nav-item"><a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Arboles</a></li>
</nav>
