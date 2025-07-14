<?php
/**
 * Backend para gestión de productos
 * Maneja las operaciones CRUD de productos
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
    $producto = new Productos();
    $categoria = new Categorias();
    
    // Obtener método HTTP y datos
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($method) {
        case 'GET':
            handleGet($producto, $categoria);
            break;
            
        case 'POST':
            handlePost($producto, $input);
            break;
            
        case 'PUT':
            handlePut($producto, $input);
            break;
            
        case 'DELETE':
            handleDelete($producto, $input);
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
function handleGet($producto, $categoria) {
    try {
        // Si se solicitan las categorías para el select
        if (isset($_GET['categorias'])) {
            $categorias = $categoria->listarTodas();
            sendResponse(true, 'Categorías obtenidas exitosamente', $categorias);
        }
        // Si se proporciona un ID, obtener producto específico
        else if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if (!$id) {
                sendResponse(false, 'ID inválido', null, 400);
            }
            
            if ($producto->obtenerPorId($id)) {
                $data = [
                    'id' => $producto->getId(),
                    'nombre' => $producto->getNombre(),
                    'precio' => $producto->getPrecio(),
                    'categoria_id' => $producto->getCategoriaId(),
                    'descripcion' => $producto->getDescripcion(),
                    'imagen' => $producto->getImagen()
                ];
                sendResponse(true, 'Producto obtenido exitosamente', $data);
            } else {
                sendResponse(false, 'Producto no encontrado', null, 404);
            }
        }
        // Si se proporciona un término de búsqueda
        else if (isset($_GET['buscar'])) {
            $termino = trim($_GET['buscar']);
            if (empty($termino)) {
                sendResponse(false, 'Término de búsqueda vacío', null, 400);
            }
            
            $productos = $producto->buscarPorNombre($termino);
            sendResponse(true, 'Búsqueda completada', $productos);
        }
        // Listar todos los productos
        else {
            $productos = $producto->listarTodos();
            sendResponse(true, 'Productos obtenidos exitosamente', $productos);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al obtener datos: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones POST (crear nuevo producto)
 */
function handlePost($producto, $input) {
    try {
        // Validar datos de entrada
        if (empty($input['nombre'])) {
            sendResponse(false, 'El nombre del producto es requerido', null, 400);
        }
        
        if (empty($input['precio']) || !is_numeric($input['precio'])) {
            sendResponse(false, 'El precio es requerido y debe ser numérico', null, 400);
        }
        
        if (empty($input['categoria_id']) || !is_numeric($input['categoria_id'])) {
            sendResponse(false, 'La categoría es requerida', null, 400);
        }
        
        $nombre = trim($input['nombre']);
        $precio = floatval($input['precio']);
        $categoriaId = intval($input['categoria_id']);
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';
        $imagen = isset($input['imagen']) ? trim($input['imagen']) : '';
        
        // Validar longitud del nombre
        if (strlen($nombre) < 2) {
            sendResponse(false, 'El nombre debe tener al menos 2 caracteres', null, 400);
        }
        
        if (strlen($nombre) > 100) {
            sendResponse(false, 'El nombre no puede exceder 100 caracteres', null, 400);
        }
        
        // Validar precio positivo
        if ($precio <= 0) {
            sendResponse(false, 'El precio debe ser mayor a 0', null, 400);
        }
        
        // Verificar que la categoría existe
        $categoria = new Categorias();
        if (!$categoria->obtenerPorId($categoriaId)) {
            sendResponse(false, 'La categoría seleccionada no existe', null, 400);
        }
        
        // Establecer datos del producto y guardar
        $producto->setNombre($nombre);
        $producto->setPrecio($precio);
        $producto->setCategoriaId($categoriaId);
        $producto->setDescripcion($descripcion);
        $producto->setImagen($imagen);
        
        $id = $producto->guardar();
        
        if ($id) {
            sendResponse(true, 'Producto creado exitosamente', [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'categoria_id' => $categoriaId,
                'descripcion' => $descripcion,
                'imagen' => $imagen
            ], 201);
        } else {
            sendResponse(false, 'Error al crear el producto', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al crear producto: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones PUT (actualizar producto)
 */
function handlePut($producto, $input) {
    try {
        // Validar ID
        if (empty($input['id'])) {
            sendResponse(false, 'ID de producto requerido', null, 400);
        }
        
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            sendResponse(false, 'ID inválido', null, 400);
        }
        
        // Validar datos similares al POST
        if (empty($input['nombre'])) {
            sendResponse(false, 'El nombre del producto es requerido', null, 400);
        }
        
        if (empty($input['precio']) || !is_numeric($input['precio'])) {
            sendResponse(false, 'El precio es requerido y debe ser numérico', null, 400);
        }
        
        if (empty($input['categoria_id']) || !is_numeric($input['categoria_id'])) {
            sendResponse(false, 'La categoría es requerida', null, 400);
        }
        
        $nombre = trim($input['nombre']);
        $precio = floatval($input['precio']);
        $categoriaId = intval($input['categoria_id']);
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';
        $imagen = isset($input['imagen']) ? trim($input['imagen']) : '';
        
        // Verificar que el producto existe
        if (!$producto->obtenerPorId($id)) {
            sendResponse(false, 'Producto no encontrado', null, 404);
        }
        
        // Actualizar producto
        $producto->setNombre($nombre);
        $producto->setPrecio($precio);
        $producto->setCategoriaId($categoriaId);
        $producto->setDescripcion($descripcion);
        $producto->setImagen($imagen);
        
        if ($producto->guardar()) {
            sendResponse(true, 'Producto actualizado exitosamente', [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'categoria_id' => $categoriaId,
                'descripcion' => $descripcion,
                'imagen' => $imagen
            ]);
        } else {
            sendResponse(false, 'Error al actualizar el producto', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al actualizar producto: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones DELETE (eliminar producto)
 */
function handleDelete($producto, $input) {
    try {
        // Validar ID
        if (empty($input['id'])) {
            sendResponse(false, 'ID de producto requerido', null, 400);
        }
        
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            sendResponse(false, 'ID inválido', null, 400);
        }
        
        // Verificar que el producto existe
        if (!$producto->obtenerPorId($id)) {
            sendResponse(false, 'Producto no encontrado', null, 404);
        }
        
        // Eliminar producto
        if ($producto->eliminar()) {
            sendResponse(true, 'Producto eliminado exitosamente');
        } else {
            sendResponse(false, 'Error al eliminar el producto', null, 500);
        }
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al eliminar producto: ' . $e->getMessage(), null, 500);
    }
}
?>