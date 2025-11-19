<?php
/**
 * Página para ver los contactos guardados en archivos planos
 */

require_once 'includes/file_manager.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Archivos de Contactos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .file-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            max-height: 500px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>
    
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold text-primary">
                    <i class="bi bi-file-earmark-text me-2"></i>Archivos de Contactos
                </h1>
                <p class="text-muted">Visualiza los contactos guardados en archivos planos (CSV y JSON)</p>
            </div>
        </div>

        <div class="row">
            <!-- Archivo CSV -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-filetype-csv me-2"></i>Archivo CSV
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $csvFile = CONTACTOS_FILE_CSV;
                        if (file_exists($csvFile)) {
                            $csvContent = file_get_contents($csvFile);
                            $size = filesize($csvFile);
                            echo '<p class="text-muted small mb-2">';
                            echo '<i class="bi bi-info-circle me-1"></i>';
                            echo 'Ubicación: <code>' . $csvFile . '</code><br>';
                            echo 'Tamaño: ' . number_format($size / 1024, 2) . ' KB';
                            echo '</p>';
                            echo '<div class="file-content">';
                            echo '<pre>' . htmlspecialchars($csvContent) . '</pre>';
                            echo '</div>';
                            echo '<div class="mt-3">';
                            echo '<a href="data/contactos.csv" class="btn btn-success btn-sm" download>';
                            echo '<i class="bi bi-download me-1"></i>Descargar CSV';
                            echo '</a>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">';
                            echo '<i class="bi bi-info-circle me-2"></i>El archivo CSV aún no existe. Se creará automáticamente al agregar el primer contacto.';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Archivo JSON -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-filetype-json me-2"></i>Archivo JSON
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $jsonFile = CONTACTOS_FILE_JSON;
                        if (file_exists($jsonFile)) {
                            $jsonContent = file_get_contents($jsonFile);
                            $size = filesize($jsonFile);
                            $contactos = json_decode($jsonContent, true);
                            $total = is_array($contactos) ? count($contactos) : 0;
                            
                            echo '<p class="text-muted small mb-2">';
                            echo '<i class="bi bi-info-circle me-1"></i>';
                            echo 'Ubicación: <code>' . $jsonFile . '</code><br>';
                            echo 'Tamaño: ' . number_format($size / 1024, 2) . ' KB<br>';
                            echo 'Total de contactos: <strong>' . $total . '</strong>';
                            echo '</p>';
                            echo '<div class="file-content">';
                            echo '<pre>' . htmlspecialchars($jsonContent) . '</pre>';
                            echo '</div>';
                            echo '<div class="mt-3">';
                            echo '<a href="data/contactos.json" class="btn btn-primary btn-sm" download>';
                            echo '<i class="bi bi-download me-1"></i>Descargar JSON';
                            echo '</a>';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-info">';
                            echo '<i class="bi bi-info-circle me-2"></i>El archivo JSON aún no existe. Se creará automáticamente al agregar el primer contacto.';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Información
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>Los contactos se guardan automáticamente en dos formatos:</p>
                        <ul>
                            <li><strong>CSV (Comma Separated Values):</strong> Formato de texto plano separado por comas, fácil de abrir en Excel o cualquier editor de texto.</li>
                            <li><strong>JSON (JavaScript Object Notation):</strong> Formato estructurado que mantiene toda la información del contacto de forma organizada.</li>
                        </ul>
                        <p class="mb-0">
                            <strong>Ubicación de los archivos:</strong> <code>data/contactos.csv</code> y <code>data/contactos.json</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <a href="index.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Volver a la Agenda
                </a>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

