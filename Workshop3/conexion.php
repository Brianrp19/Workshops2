<?php
$host = "localhost";
$user = "root";      // Tu usuario de MySQL
$pass = "";          // Tu contraseña de MySQL
$db   = "taller_php2"; // Nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
