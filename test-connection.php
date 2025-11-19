<?php
/**
 * Script de prueba de conexión a la base de datos
 * Abre este archivo en tu navegador para verificar la conexión
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - Agenda de Contactos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .test-card {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card test-card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-database-check me-2"></i>Test de Conexión a Base de Datos</h3>
            </div>
            <div class="card-body">
                <?php
                require_once 'config/database.php';
                
                echo '<div class="mb-3">';
                echo '<h5>Configuración Actual:</h5>';
                echo '<ul class="list-group mb-3">';
                echo '<li class="list-group-item"><strong>Host:</strong> ' . DB_HOST . '</li>';
                echo '<li class="list-group-item"><strong>Usuario:</strong> ' . DB_USER . '</li>';
                echo '<li class="list-group-item"><strong>Contraseña:</strong> ' . (DB_PASS ? '***' : '(vacía)') . '</li>';
                echo '<li class="list-group-item"><strong>Base de Datos:</strong> ' . DB_NAME . '</li>';
                echo '<li class="list-group-item"><strong>Puerto:</strong> ' . DB_PORT . '</li>';
                echo '</ul>';
                echo '</div>';
                
                echo '<hr>';
                
                // Test 1: Conexión al servidor MySQL
                echo '<h5>Test 1: Conexión al Servidor MySQL</h5>';
                try {
                    $testConn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
                    
                    if ($testConn->connect_error) {
                        echo '<div class="alert alert-danger">';
                        echo '<i class="bi bi-x-circle me-2"></i><strong>Error:</strong> ' . $testConn->connect_error;
                        echo '</div>';
                        echo '<div class="alert alert-info">';
                        echo '<strong>Posibles soluciones:</strong><ul>';
                        echo '<li>Verifica que MySQL/MariaDB esté corriendo</li>';
                        echo '<li>Verifica que el usuario y contraseña sean correctos</li>';
                        echo '<li>Verifica que el host sea correcto (localhost o 127.0.0.1)</li>';
                        echo '<li>Si usas XAMPP, asegúrate de que el módulo MySQL esté iniciado</li>';
                        echo '</ul></div>';
                    } else {
                        echo '<div class="alert alert-success">';
                        echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> Conexión al servidor MySQL establecida correctamente';
                        echo '</div>';
                        echo '<p><strong>Versión de MySQL:</strong> ' . $testConn->server_info . '</p>';
                        $testConn->close();
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="bi bi-x-circle me-2"></i><strong>Excepción:</strong> ' . $e->getMessage();
                    echo '</div>';
                }
                
                echo '<hr>';
                
                // Test 2: Crear/Seleccionar base de datos
                echo '<h5>Test 2: Base de Datos</h5>';
                try {
                    $testConn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
                    
                    if (!$testConn->connect_error) {
                        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                        if ($testConn->query($sql)) {
                            echo '<div class="alert alert-success">';
                            echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> Base de datos "' . DB_NAME . '" creada o ya existe';
                            echo '</div>';
                            
                            if ($testConn->select_db(DB_NAME)) {
                                echo '<div class="alert alert-success">';
                                echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> Base de datos seleccionada correctamente';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-danger">';
                                echo '<i class="bi bi-x-circle me-2"></i><strong>Error:</strong> No se pudo seleccionar la base de datos';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="bi bi-x-circle me-2"></i><strong>Error:</strong> ' . $testConn->error;
                            echo '</div>';
                        }
                        $testConn->close();
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="bi bi-x-circle me-2"></i><strong>Excepción:</strong> ' . $e->getMessage();
                    echo '</div>';
                }
                
                echo '<hr>';
                
                // Test 3: Conexión completa usando getConnection()
                echo '<h5>Test 3: Conexión Completa (getConnection)</h5>';
                try {
                    $conn = getConnection();
                    echo '<div class="alert alert-success">';
                    echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> Conexión completa establecida correctamente';
                    echo '</div>';
                    
                    // Verificar si la tabla existe
                    $result = $conn->query("SHOW TABLES LIKE 'contactos'");
                    if ($result && $result->num_rows > 0) {
                        echo '<div class="alert alert-success">';
                        echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> La tabla "contactos" existe';
                        echo '</div>';
                        
                        // Contar registros
                        $countResult = $conn->query("SELECT COUNT(*) as total FROM contactos");
                        if ($countResult) {
                            $row = $countResult->fetch_assoc();
                            echo '<p><strong>Total de contactos:</strong> ' . $row['total'] . '</p>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">';
                        echo '<i class="bi bi-exclamation-triangle me-2"></i><strong>Advertencia:</strong> La tabla "contactos" no existe, pero se creará automáticamente';
                        echo '</div>';
                    }
                    
                    $conn->close();
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="bi bi-x-circle me-2"></i><strong>Error:</strong> ' . $e->getMessage();
                    echo '</div>';
                }
                
                echo '<hr>';
                
                // Test 4: Verificar extensión mysqli
                echo '<h5>Test 4: Extensiones PHP</h5>';
                if (extension_loaded('mysqli')) {
                    echo '<div class="alert alert-success">';
                    echo '<i class="bi bi-check-circle me-2"></i><strong>Éxito:</strong> Extensión mysqli está cargada';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="bi bi-x-circle me-2"></i><strong>Error:</strong> Extensión mysqli NO está cargada';
                    echo '</div>';
                    echo '<div class="alert alert-info">';
                    echo 'Necesitas habilitar la extensión mysqli en tu php.ini';
                    echo '</div>';
                }
                
                echo '<p><strong>Versión de PHP:</strong> ' . phpversion() . '</p>';
                ?>
                
                <hr>
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la Agenda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

