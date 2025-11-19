<?php
/**
 * Gestor de archivos planos para contactos
 * Guarda los contactos en formato CSV y JSON
 */

define('CONTACTOS_FILE_CSV', __DIR__ . '/../data/contactos.csv');
define('CONTACTOS_FILE_JSON', __DIR__ . '/../data/contactos.json');

/**
 * Crear directorio de datos si no existe
 */
function crearDirectorioDatos() {
    $dir = dirname(CONTACTOS_FILE_CSV);
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

/**
 * Guardar contacto en archivo CSV
 */
function guardarContactoCSV($contacto) {
    crearDirectorioDatos();
    
    // Leer todos los contactos existentes desde JSON para regenerar CSV completo
    $contactos = leerContactosJSON();
    
    // Si el contacto ya existe, actualizarlo; si no, agregarlo
    $id = $contacto['id'] ?? null;
    $encontrado = false;
    
    if ($id) {
        foreach ($contactos as $key => $c) {
            if (isset($c['id']) && $c['id'] == $id) {
                $contactos[$key] = $contacto;
                $encontrado = true;
                break;
            }
        }
    }
    
    if (!$encontrado) {
        $contactos[] = $contacto;
    }
    
    // Regenerar archivo CSV completo
    $archivo = fopen(CONTACTOS_FILE_CSV, 'w');
    
    if ($archivo === false) {
        return false;
    }
    
    // Escribir encabezados
    $encabezados = [
        'ID',
        'Nombre',
        'Apellido',
        'Teléfono',
        'Email',
        'Dirección',
        'Fecha Nacimiento',
        'Categoría',
        'Notas',
        'Fecha Creación',
        'Fecha Actualización'
    ];
    fputcsv($archivo, $encabezados);
    
    // Escribir todos los contactos
    foreach ($contactos as $c) {
        $datos = [
            $c['id'] ?? '',
            $c['nombre'] ?? '',
            $c['apellido'] ?? '',
            $c['telefono'] ?? '',
            $c['email'] ?? '',
            $c['direccion'] ?? '',
            $c['fecha_nacimiento'] ?? '',
            $c['categoria'] ?? 'Personal',
            $c['notas'] ?? '',
            $c['fecha_creacion'] ?? date('Y-m-d H:i:s'),
            $c['fecha_actualizacion'] ?? date('Y-m-d H:i:s')
        ];
        fputcsv($archivo, $datos);
    }
    
    fclose($archivo);
    
    return true;
}

/**
 * Guardar contacto en archivo JSON
 */
function guardarContactoJSON($contacto) {
    crearDirectorioDatos();
    
    $contactos = [];
    
    // Leer contactos existentes
    if (file_exists(CONTACTOS_FILE_JSON)) {
        $contenido = file_get_contents(CONTACTOS_FILE_JSON);
        $contactos = json_decode($contenido, true) ?: [];
    }
    
    // Agregar o actualizar contacto
    $id = $contacto['id'] ?? null;
    
    if ($id) {
        // Actualizar contacto existente
        $encontrado = false;
        foreach ($contactos as $key => $c) {
            if (isset($c['id']) && $c['id'] == $id) {
                $contactos[$key] = $contacto;
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $contactos[] = $contacto;
        }
    } else {
        // Nuevo contacto
        $contacto['id'] = count($contactos) + 1;
        $contacto['fecha_creacion'] = date('Y-m-d H:i:s');
        $contacto['fecha_actualizacion'] = date('Y-m-d H:i:s');
        $contactos[] = $contacto;
    }
    
    // Guardar en archivo
    $json = json_encode($contactos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(CONTACTOS_FILE_JSON, $json) !== false;
}

/**
 * Guardar contacto en ambos formatos (CSV y JSON)
 */
function guardarContactoArchivo($contacto) {
    // Primero guardar en JSON
    $resultadoJSON = guardarContactoJSON($contacto);
    // Luego regenerar CSV completo desde JSON
    $resultadoCSV = guardarContactoCSV($contacto);
    
    return $resultadoCSV && $resultadoJSON;
}

/**
 * Leer contactos desde archivo JSON
 */
function leerContactosJSON() {
    if (!file_exists(CONTACTOS_FILE_JSON)) {
        return [];
    }
    
    $contenido = file_get_contents(CONTACTOS_FILE_JSON);
    $contactos = json_decode($contenido, true);
    
    return $contactos ?: [];
}

/**
 * Leer contactos desde archivo CSV
 */
function leerContactosCSV() {
    if (!file_exists(CONTACTOS_FILE_CSV)) {
        return [];
    }
    
    $contactos = [];
    $archivo = fopen(CONTACTOS_FILE_CSV, 'r');
    
    if ($archivo === false) {
        return [];
    }
    
    // Leer encabezados
    $encabezados = fgetcsv($archivo);
    
    if ($encabezados === false) {
        fclose($archivo);
        return [];
    }
    
    // Leer datos
    while (($fila = fgetcsv($archivo)) !== false) {
        if (count($fila) == count($encabezados)) {
            $contacto = array_combine($encabezados, $fila);
            $contactos[] = $contacto;
        }
    }
    
    fclose($archivo);
    return $contactos;
}

/**
 * Eliminar contacto de archivo JSON
 */
function eliminarContactoArchivo($id) {
    if (!file_exists(CONTACTOS_FILE_JSON)) {
        return false;
    }
    
    $contactos = leerContactosJSON();
    $contactos = array_filter($contactos, function($contacto) use ($id) {
        return !isset($contacto['id']) || $contacto['id'] != $id;
    });
    
    $contactos = array_values($contactos);
    $json = json_encode($contactos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $resultadoJSON = file_put_contents(CONTACTOS_FILE_JSON, $json) !== false;
    
    // Regenerar CSV sin el contacto eliminado
    if ($resultadoJSON) {
        // Regenerar CSV completo
        $archivo = fopen(CONTACTOS_FILE_CSV, 'w');
        if ($archivo !== false) {
            $encabezados = [
                'ID', 'Nombre', 'Apellido', 'Teléfono', 'Email',
                'Dirección', 'Fecha Nacimiento', 'Categoría', 'Notas',
                'Fecha Creación', 'Fecha Actualización'
            ];
            fputcsv($archivo, $encabezados);
            
            foreach ($contactos as $c) {
                $datos = [
                    $c['id'] ?? '', $c['nombre'] ?? '', $c['apellido'] ?? '',
                    $c['telefono'] ?? '', $c['email'] ?? '', $c['direccion'] ?? '',
                    $c['fecha_nacimiento'] ?? '', $c['categoria'] ?? 'Personal',
                    $c['notas'] ?? '', $c['fecha_creacion'] ?? date('Y-m-d H:i:s'),
                    $c['fecha_actualizacion'] ?? date('Y-m-d H:i:s')
                ];
                fputcsv($archivo, $datos);
            }
            fclose($archivo);
        }
    }
    
    return $resultadoJSON;
}

/**
 * Actualizar contacto en archivo JSON
 */
function actualizarContactoArchivo($contacto) {
    return guardarContactoJSON($contacto);
}

?>

