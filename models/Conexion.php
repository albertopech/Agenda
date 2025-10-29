<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $base_datos = "BD_Agenda";
    
    private function __construct() {
        $this->conn = mysqli_connect(
            $this->host, 
            $this->usuario, 
            $this->password, 
            $this->base_datos
        );
        
        if (!$this->conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        
        mysqli_set_charset($this->conn, "utf8");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Evitar clonación del singleton
    private function __clone() {}
    
    // Evitar deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}
?>