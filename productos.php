<?php
/**
 * API para obtener productos - Página Principal
 * Hai Swimwear
 */

header('Content-Type: application/json');
require_once '../database/config_supabase.php';

// Permitir CORS si es necesario
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Obtener productos
            $categoria = $_GET['categoria'] ?? null;
            $estado = $_GET['estado'] ?? 'activo';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $buscar = $_GET['buscar'] ?? null;
            
            $sql = "SELECT p.*, c.nombre as categoria_nombre, c.slug as categoria_slug 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    WHERE 1=1 AND (p.es_prueba = 0 OR p.es_prueba IS NULL)";
            
            $params = [];
            $paramCount = 0;
            
            if ($estado) {
                $paramCount++;
                $sql .= " AND p.estado = $" . $paramCount;
                $params[] = $estado;
            }
            
            if ($categoria) {
                $paramCount++;
                $sql .= " AND (c.slug = $" . $paramCount . " OR c.nombre = $" . $paramCount . ")";
                $params[] = $categoria;
            }
            
            if ($buscar) {
                $paramCount++;
                $sql .= " AND (p.nombre ILIKE $" . $paramCount . " OR p.descripcion_corta ILIKE $" . $paramCount . ")";
                $params[] = '%' . $buscar . '%';
            }
            
            $sql .= " ORDER BY p.producto_destacado DESC, p.fecha_creacion DESC";
            
            if ($limit) {
                $paramCount++;
                $sql .= " LIMIT $" . $paramCount;
                $params[] = $limit;
                
                if ($offset > 0) {
                    $paramCount++;
                    $sql .= " OFFSET $" . $paramCount;
                    $params[] = $offset;
                }
            }
            
            $productos = fetchAll($sql, $params);
            
            // Obtener imágenes para cada producto
            foreach ($productos as &$producto) {
                $imagenes = fetchAll(
                    "SELECT url, alt_text, es_principal FROM producto_imagenes WHERE producto_id = $1 ORDER BY es_principal DESC, orden ASC",
                    [$producto['id']]
                );
                $producto['imagenes'] = $imagenes;
                $producto['imagen_principal'] = !empty($imagenes) ? $imagenes[0]['url'] : null;
            }
            
            jsonResponse(true, 'Productos obtenidos', $productos);
            break;
            
        case 'POST':
            // Solo para admin (requiere autenticación)
            requireAuth();
            requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data['nombre'] || !$data['precio']) {
                jsonResponse(false, 'Nombre y precio son requeridos', null);
            }
            
            $sql = "INSERT INTO productos (nombre, sku, slug, categoria_id, precio, precio_anterior, stock, descripcion_corta, estado, producto_destacado) 
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) RETURNING id";
            
            $slug = slugify($data['nombre']);
            $params = [
                $data['nombre'],
                $data['sku'] ?? null,
                $slug,
                $data['categoria_id'] ?? null,
                $data['precio'],
                $data['precio_anterior'] ?? null,
                $data['stock'] ?? 0,
                $data['descripcion_corta'] ?? null,
                $data['estado'] ?? 'activo',
                $data['producto_destacado'] ?? false
            ];
            
            $id = insertAndGetId($sql, $params);
            
            if ($id) {
                jsonResponse(true, 'Producto creado', ['id' => $id]);
            } else {
                jsonResponse(false, 'Error al crear producto', null);
            }
            break;
            
        default:
            http_response_code(405);
            jsonResponse(false, 'Método no permitido', null);
    }
} catch (Exception $e) {
    error_log("Error en productos.php: " . $e->getMessage());
    http_response_code(500);
    jsonResponse(false, 'Error del servidor', ['error' => $e->getMessage()]);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

?>

