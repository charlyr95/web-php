<?php
/**
 * Backend para gestión de categorías
 * Maneja las operaciones CRUD de categorías
 */

// Configuración de headers para CORS y JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir clases necesarias
include_once '../class/autoload.php';

// Función para enviar respuesta JSON
function sendResponse($success, $message, $data = null, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
}

try {
    // Inicializar conexión a base de datos
    $database = new Database();
    $categoria = new Categorias();
    
    // Obtener método HTTP y datos
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($method) {
        case 'GET':
            handleGet();
            break;
            
        case 'POST':
            handlePost($categoria, $input);
            break;
            
        case 'PUT':
            handlePut($categoria, $input);
            break;
            
        case 'DELETE':
            handleDelete($categoria, $input);
            break;
            
        default:
            sendResponse(false, 'Método no permitido', null, 405);
    }
    
} catch (Exception $e) {
    sendResponse(false, 'Error interno del servidor: ' . $e->getMessage(), null, 500);
}

/**
 * Manejar peticiones GET
 */
function handleGet() {
    global $categoria;
    
    try {
        // Si se proporciona un ID, obtener categoría específica
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if (!$id) {
                sendResponse(false, 'ID inválido', null, 400);
            }
            
            if ($categoria->obtenerPorId($id)) {
                sendResponse(true, 'Categoría obtenida exitosamente', [
                    'id' => $categoria->getId(),
                    'nombre' => $categoria->getNombre()
                ]);
            } else {
                sendResponse(false, 'Categoría no encontrada', null, 404);
            }
        }
        // Si se proporciona un término de búsqueda
        else if (isset($_GET['buscar'])) {
            $termino = trim($_GET['buscar']);
            if (empty($termino)) {
                sendResponse(false, 'Término de búsqueda vacío', null, 400);
            }
            
            $categorias = $categoria->buscarPorNombre($termino);
            sendResponse(true, 'Búsqueda completada', $categorias);
        }
        // Listar todas las categorías
        else {
            $categorias = $categoria->listarTodas();
            sendResponse(true, 'Categorías obtenidas exitosamente', $categorias);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al obtener categorías: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones POST (crear nueva categoría)
 */
function handlePost($categoria, $input) {
    try {
        // Validar datos de entrada
        if (empty($input['nombre'])) {
            sendResponse(false, 'El nombre de la categoría es requerido', null, 400);
        }
        
        $nombre = trim($input['nombre']);
        
        // Validar longitud del nombre
        if (strlen($nombre) < 2) {
            sendResponse(false, 'El nombre debe tener al menos 2 caracteres', null, 400);
        }
        
        if (strlen($nombre) > 100) {
            sendResponse(false, 'El nombre no puede exceder 100 caracteres', null, 400);
        }
        
        // Validar que el nombre sea único
        if (!$categoria->validarNombreUnico($nombre)) {
            sendResponse(false, 'Ya existe una categoría con ese nombre', null, 409);
        }
        
        // Establecer nombre y guardar
        $categoria->setNombre($nombre);
        $id = $categoria->guardar();
        
        if ($id) {
            sendResponse(true, 'Categoría creada exitosamente', [
                'id' => $id,
                'nombre' => $nombre
            ], 201);
        } else {
            sendResponse(false, 'Error al crear la categoría', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al crear categoría: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones PUT (actualizar categoría)
 */
function handlePut($categoria, $input) {
    try {
        // Validar ID
        if (empty($input['id'])) {
            sendResponse(false, 'ID de categoría requerido', null, 400);
        }
        
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            sendResponse(false, 'ID inválido', null, 400);
        }
        
        // Validar nombre
        if (empty($input['nombre'])) {
            sendResponse(false, 'El nombre de la categoría es requerido', null, 400);
        }
        
        $nombre = trim($input['nombre']);
        
        // Validar longitud del nombre
        if (strlen($nombre) < 2) {
            sendResponse(false, 'El nombre debe tener al menos 2 caracteres', null, 400);
        }
        
        if (strlen($nombre) > 100) {
            sendResponse(false, 'El nombre no puede exceder 100 caracteres', null, 400);
        }
        
        // Verificar que la categoría existe
        if (!$categoria->obtenerPorId($id)) {
            sendResponse(false, 'Categoría no encontrada', null, 404);
        }
        
        // Validar que el nombre sea único (excluyendo la categoría actual)
        if (!$categoria->validarNombreUnico($nombre, $id)) {
            sendResponse(false, 'Ya existe otra categoría con ese nombre', null, 409);
        }
        
        // Actualizar categoría
        $categoria->setNombre($nombre);
        
        if ($categoria->guardar()) {
            sendResponse(true, 'Categoría actualizada exitosamente', [
                'id' => $id,
                'nombre' => $nombre
            ]);
        } else {
            sendResponse(false, 'Error al actualizar la categoría', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al actualizar categoría: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones DELETE (eliminar categoría)
 */
function handleDelete($categoria, $input) {
    try {
        // Validar ID
        if (empty($input['id'])) {
            sendResponse(false, 'ID de categoría requerido', null, 400);
        }
        
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            sendResponse(false, 'ID inválido', null, 400);
        }
        
        // Verificar que la categoría existe
        if (!$categoria->obtenerPorId($id)) {
            sendResponse(false, 'Categoría no encontrada', null, 404);
        }
        
        // Eliminar categoría
        if ($categoria->eliminar()) {
            sendResponse(true, 'Categoría eliminada exitosamente');
        } else {
            sendResponse(false, 'Error al eliminar la categoría', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al eliminar categoría: ' . $e->getMessage(), null, 500);
    }
}
?>