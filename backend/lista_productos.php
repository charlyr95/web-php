<?php
/**
 * Controller para Lista de Productos
 * Obtiene y muestra todos los productos del sistema con información de categorías
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
    // Inicializar productos y categorías
    $producto = new Productos();
    $categoria = new Categorias();
    
    // Obtener método HTTP
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            handleGet($producto, $categoria);
            break;
            
        case 'DELETE':
            handleDelete($producto);
            break;
            
        default:
            sendResponse(false, 'Método no permitido', null, 405);
    }
    
} catch (Exception $e) {
    sendResponse(false, 'Error interno del servidor: ' . $e->getMessage(), null, 500);
}

/**
 * Manejar peticiones GET - Obtener lista de productos
 */
function handleGet($producto, $categoria) {
    try {
        // Obtener parámetros de consulta
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        $categoriaId = isset($_GET['categoria_id']) ? filter_var($_GET['categoria_id'], FILTER_VALIDATE_INT) : null;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 10;
        $orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'nombre';
        $orderDir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';
        
        // Validar campo de ordenamiento
        $validOrderFields = ['nombre', 'precio', 'id'];
        if (!in_array($orderBy, $validOrderFields)) {
            $orderBy = 'nombre';
        }
        
        $productos = [];
        $totalProductos = 0;
        
        if (!empty($buscar)) {
            // Búsqueda por nombre
            $productos = $producto->buscarPorNombre($buscar);
        } else if ($categoriaId) {
            // Filtrar por categoría
            $productos = $producto->obtenerPorCategoria($categoriaId);
        } else {
            // Obtener todos los productos
            $productos = $producto->listarTodos();
        }
        
        // Enriquecer productos con información de categoría
        $productosEnriquecidos = [];
        foreach ($productos as $prod) {
            $prodData = $prod;
            
            // Obtener nombre de categoría
            if ($prod['categoria_id']) {
                if ($categoria->obtenerPorId($prod['categoria_id'])) {
                    $prodData['categoria_nombre'] = $categoria->getNombre();
                } else {
                    $prodData['categoria_nombre'] = 'Sin categoría';
                }
            } else {
                $prodData['categoria_nombre'] = 'Sin categoría';
            }
            
            // Formatear precio
            $prodData['precio_formateado'] = number_format($prod['precio'], 2, '.', ',');
            
            // Formatear imagen (si existe)
            if (!empty($prod['imagen'])) {
                $prodData['imagen_url'] = $prod['imagen'];
                $prodData['tiene_imagen'] = true;
            } else {
                $prodData['imagen_url'] = null;
                $prodData['tiene_imagen'] = false;
            }
            
            $productosEnriquecidos[] = $prodData;
        }
        
        // Ordenar productos
        usort($productosEnriquecidos, function($a, $b) use ($orderBy, $orderDir) {
            $valueA = $a[$orderBy];
            $valueB = $b[$orderBy];
            
            if ($orderBy === 'precio') {
                $valueA = floatval($valueA);
                $valueB = floatval($valueB);
            }
            
            $result = $valueA <=> $valueB;
            return $orderDir === 'desc' ? -$result : $result;
        });
        
        $totalProductos = count($productosEnriquecidos);
        
        // Aplicar paginación
        $offset = ($page - 1) * $limit;
        $productosPaginados = array_slice($productosEnriquecidos, $offset, $limit);
        
        // Calcular información de paginación
        $totalPages = ceil($totalProductos / $limit);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;
        
        // Obtener lista de categorías para filtros
        $categorias = $categoria->listarTodas();
        
        // Preparar respuesta
        $response = [
            'productos' => $productosPaginados,
            'categorias' => $categorias,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalProductos,
                'items_per_page' => $limit,
                'has_next' => $hasNext,
                'has_prev' => $hasPrev
            ],
            'filters' => [
                'buscar' => $buscar,
                'categoria_id' => $categoriaId,
                'order_by' => $orderBy,
                'order_dir' => $orderDir
            ]
        ];
        
        sendResponse(true, 'Productos obtenidos exitosamente', $response);
        
    } catch (Exception $e) {
        sendResponse(false, 'Error al obtener productos: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Manejar peticiones DELETE - Eliminar producto
 */
function handleDelete($producto) {
    try {
        // Obtener ID desde query string
        $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
        
        if (!$id) {
            sendResponse(false, 'ID de producto inválido', null, 400);
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

/**
 * Función auxiliar para formatear respuesta de productos
 */
function formatearProducto($producto, $categoriaNombre = null) {
    return [
        'id' => $producto['id'],
        'nombre' => $producto['nombre'],
        'precio' => floatval($producto['precio']),
        'precio_formateado' => number_format($producto['precio'], 2, '.', ','),
        'categoria_id' => $producto['categoria_id'],
        'categoria_nombre' => $categoriaNombre ?? 'Sin categoría',
        'descripcion' => $producto['descripcion'] ?? '',
        'imagen' => $producto['imagen'] ?? null,
        'tiene_imagen' => !empty($producto['imagen'])
    ];
}
?>