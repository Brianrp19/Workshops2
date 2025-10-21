<?php
$host = "localhost";
$user = "root";      
$pass = "";         
$db   = "taller_php2"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
