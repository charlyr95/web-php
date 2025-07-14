<?php
/**
 * Controller para Lista de Categorías
 * Obtiene y muestra todas las categorías del sistema
 */

// Configuración de headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, DELETE, OPTIONS');
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
    // Inicializar categorías
    $categoria = new Categorias();
    
    // Obtener método HTTP
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            handleGet($categoria);
            break;
            
        case 'DELETE':
            handleDelete($categoria);
            break;
            
        default:
            sendResponse(false, 'Método no permitido', null, 405);
    }
    
} catch (Exception $e) {
    sendResponse(false, 'Error interno del servidor: ' . $e->getMessage(), null, 500);
}

/**
 * Manejar peticiones GET - Obtener lista de categorías
 */
function handleGet($categoria) {
    try {
        // Obtener parámetros de consulta
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 10;
        
        if (!empty($buscar)) {
            // Búsqueda por nombre
            $categorias = $categoria->buscarPorNombre($buscar);
            $totalCategorias = count($categorias);
            
            // Aplicar paginación manualmente para búsquedas
            $offset = ($page - 1) * $limit;
            $categorias = array_slice($categorias, $offset, $limit);
        } else {
            // Obtener todas las categorías
            $todasCategorias = $categoria->listarTodas();
            $totalCategorias = count($todasCategorias);
            
            // Aplicar paginación
            $offset = ($page - 1) * $limit;
            $categorias = array_slice($todasCategorias, $offset, $limit);
        }
        
        // Calcular información de paginación
        $totalPages = ceil($totalCategorias / $limit);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;
        
        // Preparar respuesta
        $response = [
            'categorias' => $categorias,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalCategorias,
                'items_per_page' => $limit,
                'has_next' => $hasNext,
                'has_prev' => $hasPrev
            ]
        ];
        
        sendResponse(true, 'Categorías obtenidas exitosamente', $response);
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al obtener categorías: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones DELETE - Eliminar categoría
 */
function handleDelete($categoria) {
    try {
        // Obtener ID desde query string
        $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
        
        if (!$id) {
            sendResponse(false, 'ID de categoría inválido', null, 400);
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