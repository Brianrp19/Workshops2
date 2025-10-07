<?php
include("conexion.php");


$result = $conn->query("SELECT id, nombre FROM provincias");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form action="insertar.php" method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Apellido:</label><br>
        <input type="text" name="apellido" required><br><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" required><br><br>

        <label>Provincia:</label><br>
        <select name="provincia" required>
            <option value="">--Seleccione una provincia--</option>
            <?php while($row = $result->fetch_assoc()) { ?>
                <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
            <?php } ?>
        </select><br><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
