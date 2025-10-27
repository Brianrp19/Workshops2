<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: register.php'); exit;
}

$username = trim($_POST['username'] ?? '');
$name     = trim($_POST['name'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? 'Usuario';

if ($username === '' || $name === '' || $lastname === '' || $password === '') {
  header('Location: register.php?m=err'); exit;
}

$conn = getConnection();

// ¿username ya existe?
$chk = $conn->prepare("SELECT 1 FROM users WHERE username=? LIMIT 1");
$chk->bind_param('s', $username);
$chk->execute();
$exists = $chk->get_result()->fetch_row();
$chk->close();

if ($exists) {
  $conn->close();
  header('Location: register.php?m=dup'); exit;
}

// crear (status=active por defecto, password MD5 para compatibilidad)
$stmt = $conn->prepare(
  "INSERT INTO users (username,password,name,lastname,role,status)
   VALUES (?, MD5(?), ?, ?, ?, 'active')"
);
$stmt->bind_param('sssss', $username, $password, $name, $lastname, $role);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

header('Location: register.php?m=' . ($ok ? 'ok' : 'err'));
