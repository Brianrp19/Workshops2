<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "taller_php2";
    private $conn;

    public function conectar() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Error en la conexión: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}
?>
