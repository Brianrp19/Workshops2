<?php
include("conexion.php");


class User {
    private $nombre, $apellido, $correo, $telefono, $provincia;

    public function __construct($nombre, $apellido, $correo, $telefono, $provincia) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->provincia = $provincia;
    }

    public function guardar($conn) {
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, telefono, provincia_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $this->nombre, $this->apellido, $this->correo, $this->telefono, $this->provincia);
        return $stmt->execute();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();
    $conn = $db->conectar();

    $user = new User($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'], $_POST['provincia']);
    
    if ($user->guardar($conn)) {
        header("Location: login.php?username=" . urlencode($_POST['nombre']));
        exit();
    } else {
        echo "Error al guardar el usuario.";
    }

    $conn->close();
}
?>
