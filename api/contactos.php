<?php
/**
 * API REST para gestión de contactos
 */

header('Content-Type: application/json; charset=utf-8');

// Manejar errores de conexión de forma más amigable
try {
    // Intentar cargar desde diferentes rutas posibles
    if (file_exists(__DIR__ . '/../config/database.php')) {
        require_once __DIR__ . '/../config/database.php';
    } elseif (file_exists(__DIR__ . '/config/database.php')) {
        require_once __DIR__ . '/config/database.php';
    } else {
        throw new Exception('No se encontró el archivo de configuración de base de datos');
    }
    
    // Cargar gestor de archivos planos
    if (file_exists(__DIR__ . '/../includes/file_manager.php')) {
        require_once __DIR__ . '/../includes/file_manager.php';
    } elseif (file_exists(__DIR__ . '/includes/file_manager.php')) {
        require_once __DIR__ . '/includes/file_manager.php';
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    $conn = getConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Error de conexión: ' . $e->getMessage(),
        'debug' => 'Verifica la configuración en config/database.php'
    ]);
    exit;
}

// Obtener el ID si existe
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($method) {
    case 'GET':
        if ($id > 0) {
            // Obtener un contacto específico
            getContacto($conn, $id);
        } else {
            // Obtener todos los contactos
            getContactos($conn);
        }
        break;
        
    case 'POST':
        crearContacto($conn);
        break;
        
    case 'PUT':
        actualizarContacto($conn, $id);
        break;
        
    case 'DELETE':
        eliminarContacto($conn, $id);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

/**
 * Obtener todos los contactos
 */
function getContactos($conn) {
    $sql = "SELECT * FROM contactos ORDER BY nombre ASC";
    $result = $conn->query($sql);
    
    $contactos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contactos[] = $row;
        }
    }
    
    echo json_encode(['success' => true, 'data' => $contactos]);
}

/**
 * Obtener un contacto por ID
 */
function getContacto($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM contactos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $contacto = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $contacto]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Contacto no encontrado']);
    }
    $stmt->close();
}

/**
 * Crear un nuevo contacto
 */
function crearContacto($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['nombre']) || !isset($data['apellido']) || !isset($data['telefono']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        return;
    }
    
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $telefono = $data['telefono'];
    $email = $data['email'];
    $direccion = $data['direccion'] ?? '';
    $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
    $categoria = $data['categoria'] ?? 'Personal';
    $notas = $data['notas'] ?? '';
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Email inválido']);
        return;
    }
    
    $stmt = $conn->prepare("INSERT INTO contactos (nombre, apellido, telefono, email, direccion, fecha_nacimiento, categoria, notas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nombre, $apellido, $telefono, $email, $direccion, $fecha_nacimiento, $categoria, $notas);
    
    if ($stmt->execute()) {
        $id = $conn->insert_id;
        
        // Obtener el contacto completo para guardarlo en archivo
        $stmt2 = $conn->prepare("SELECT * FROM contactos WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $contactoCompleto = $result->fetch_assoc();
        $stmt2->close();
        
        // Guardar también en archivo plano (CSV y JSON)
        if (function_exists('guardarContactoArchivo')) {
            guardarContactoArchivo($contactoCompleto);
        }
        
        echo json_encode(['success' => true, 'message' => 'Contacto creado exitosamente (guardado en BD y archivo)', 'id' => $id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al crear contacto: ' . $stmt->error]);
    }
    $stmt->close();
}

/**
 * Actualizar un contacto
 */
function actualizarContacto($conn, $id) {
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['nombre']) || !isset($data['apellido']) || !isset($data['telefono']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        return;
    }
    
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $telefono = $data['telefono'];
    $email = $data['email'];
    $direccion = $data['direccion'] ?? '';
    $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
    $categoria = $data['categoria'] ?? 'Personal';
    $notas = $data['notas'] ?? '';
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Email inválido']);
        return;
    }
    
    $stmt = $conn->prepare("UPDATE contactos SET nombre = ?, apellido = ?, telefono = ?, email = ?, direccion = ?, fecha_nacimiento = ?, categoria = ?, notas = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $telefono, $email, $direccion, $fecha_nacimiento, $categoria, $notas, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Obtener el contacto actualizado para guardarlo en archivo
            $stmt2 = $conn->prepare("SELECT * FROM contactos WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $contactoCompleto = $result->fetch_assoc();
            $stmt2->close();
            
            // Actualizar también en archivo plano
            if (function_exists('actualizarContactoArchivo')) {
                actualizarContactoArchivo($contactoCompleto);
            }
            
            echo json_encode(['success' => true, 'message' => 'Contacto actualizado exitosamente (actualizado en BD y archivo)']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Contacto no encontrado']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al actualizar contacto: ' . $stmt->error]);
    }
    $stmt->close();
}

/**
 * Eliminar un contacto
 */
function eliminarContacto($conn, $id) {
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM contactos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Eliminar también del archivo plano
            if (function_exists('eliminarContactoArchivo')) {
                eliminarContactoArchivo($id);
            }
            
            echo json_encode(['success' => true, 'message' => 'Contacto eliminado exitosamente (eliminado de BD y archivo)']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Contacto no encontrado']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al eliminar contacto: ' . $stmt->error]);
    }
    $stmt->close();
}

$conn->close();
?>

