-- Base de datos para Agenda de Contactos
CREATE DATABASE IF NOT EXISTS agenda_contactos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE agenda_contactos;

-- Tabla de contactos
CREATE TABLE IF NOT EXISTS contactos (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo (opcional)
INSERT INTO contactos (nombre, apellido, telefono, email, direccion, fecha_nacimiento, categoria, notas) VALUES
('Juan', 'Pérez', '0987654321', 'juan.perez@email.com', 'Av. Principal 123', '1990-05-15', 'Personal', 'Amigo de la universidad'),
('María', 'González', '0998765432', 'maria.gonzalez@email.com', 'Calle Secundaria 456', '1985-08-20', 'Trabajo', 'Compañera de trabajo'),
('Carlos', 'Rodríguez', '0976543210', 'carlos.rodriguez@email.com', 'Boulevard Norte 789', '1992-11-10', 'Familia', 'Primo');

