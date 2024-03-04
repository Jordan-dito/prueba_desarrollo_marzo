<?php
class ConexionBD {
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $base_de_datos = "gestion_asistencia";
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        try {
            // Crear una nueva conexión PDO
            $this->conexion = new PDO("mysql:host={$this->host};dbname={$this->base_de_datos}", $this->usuario, $this->contrasena);

            // Establecer el modo de error de PDO a excepción
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Establecer el juego de caracteres a UTF-8
            $this->conexion->exec("SET NAMES utf8");

            // Mensaje de conexión exitosa
            //echo "Conexión exitosa a la base de datos";
        } catch(PDOException $error) {
            // En caso de error en la conexión, mostrar un mensaje de error y finalizar la ejecución del script
            die("Error de conexión: " . $error->getMessage());
        }
    }

    // Método para obtener la conexión PDO
    public function obtenerConexion() {
        return $this->conexion;
    }
}

date_default_timezone_set("America/Guayaquil");
//echo date_default_timezone_get();
date_default_timezone_get();
?>
