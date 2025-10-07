<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $_POST['nombre'];
    $apellido  = $_POST['apellido'];
    $correo    = $_POST['correo'];
    $telefono  = $_POST['telefono'];
    $provincia = $_POST['provincia'];

 
    $sql = "INSERT INTO usuarios (nombre, apellido, correo, telefono, provincia_id) 
            VALUES ('$nombre', '$apellido', '$correo', '$telefono', $provincia)";

    if ($conn->query($sql) === TRUE) {
     
        header("Location: login.php?username=" . urlencode($nombre));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
