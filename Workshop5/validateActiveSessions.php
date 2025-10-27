<?php
// validateActiveSessions.php
// Uso desde consola: php validateActiveSessions.php 24

require_once __DIR__ . '/functions.php'; // asegúrate que functions.php esté en la misma carpeta

if (PHP_SAPI !== 'cli') {
  echo "⚠️ Este script debe ejecutarse desde la línea de comandos (no desde el navegador).\n";
  exit(1);
}

// Lee las horas desde el argumento del comando
$hours = isset($argv[1]) ? (int)$argv[1] : 0;
if ($hours <= 0) {
  echo "Uso: php validateActiveSessions.php <horas>\n";
  echo "Ejemplo: php validateActiveSessions.php 24\n";
  exit(1);
}

$conn = getConnection();

$sql = "UPDATE users
        SET status = 'inactive'
        WHERE status = 'active'
          AND last_login_datetime IS NOT NULL
          AND TIMESTAMPDIFF(HOUR, last_login_datetime, NOW()) > ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $hours);
$stmt->execute();

echo "✅ Usuarios marcados como 'inactive' después de $hours horas: " . $stmt->affected_rows . "\n";

$stmt->close();
$conn->close();
