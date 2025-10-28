<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php'); exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$user = authenticate($username, $password);

if (!$user) {
  // Podría ser usuario inexistente, inactivo o password mala.
  // Para diferenciar "inactivo", consulta rápido:
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT status FROM users WHERE username=? LIMIT 1");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close(); $conn->close();

  if ($row && $row['status'] !== 'active') {
    header('Location: index.php?m=inactive'); exit;
  }
  header('Location: index.php?m=bad'); exit;
}

// Login OK
$_SESSION['user'] = $user;
// Puedes mostrar "login exitoso" en el index si prefieres, o ir directo al dashboard
header('Location: dashboard.php');
