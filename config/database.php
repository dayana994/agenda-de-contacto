<?php
/**
 * Configuración de la base de datos
 * 
 * IMPORTANTE: Ajusta estos valores según tu configuración de MySQL
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agenda_contactos');
define('DB_PORT', 3306); // Puerto por defecto de MySQL

/**
 * Función para obtener conexión a la base de datos
 */
function getConnection() {
    // Primero intentar conectar sin especificar la base de datos
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
    
    // Verificar conexión al servidor
    if ($conn->connect_error) {
        throw new Exception("Error de conexión al servidor MySQL: " . $conn->connect_error . 
                          "<br>Verifica que MySQL esté corriendo y las credenciales sean correctas.");
    }
    
    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($sql)) {
        throw new Exception("Error al crear la base de datos: " . $conn->error);
    }
    
    // Seleccionar la base de datos
    if (!$conn->select_db(DB_NAME)) {
        throw new Exception("Error al seleccionar la base de datos: " . $conn->error);
    }
    
    // Configurar charset
    $conn->set_charset("utf8mb4");
    
    // Crear la tabla si no existe
    crearTablaSiNoExiste($conn);
    
    return $conn;
}

/**
 * Crear la tabla de contactos si no existe
 */
function crearTablaSiNoExiste($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS contactos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        apellido VARCHAR(100) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        email VARCHAR(150) NOT NULL,
        direccion TEXT,
        fecha_nacimiento DATE,
        categoria VARCHAR(50) DEFAULT 'Personal',
        notas TEXT,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_nombre (nombre),
        INDEX idx_email (email),
        INDEX idx_categoria (categoria)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$conn->query($sql)) {
        throw new Exception("Error al crear la tabla: " . $conn->error);
    }
}
?>

