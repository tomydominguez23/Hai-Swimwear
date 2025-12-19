<?php
/**
 * API REST para el Panel de Administración
 * Hai Swimwear
 */

// Configurar headers primero
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Manejar errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en la respuesta JSON
ini_set('log_errors', 1);

// Prioridad: MySQL > Supabase > PostgreSQL
$configLoaded = false;

// Definir posibles rutas de configuración
$configPaths = [
    'mysql' => [
        __DIR__ . '/config_mysql.php', 
        __DIR__ . '/../database/config_mysql.php'
    ],
    'supabase' => [
        __DIR__ . '/config_supabase.php', 
        __DIR__ . '/../database/config_supabase.php'
    ],
    'postgresql' => [
        __DIR__ . '/config_postgresql.php', 
        __DIR__ . '/../database/config_postgresql.php'
    ],
    'default' => [
        __DIR__ . '/config.php', 
        __DIR__ . '/../database/config.php'
    ]
];

// Intentar cargar configuración MySQL
foreach ($configPaths['mysql'] as $path) {
    if (file_exists($path)) {
        try {
            require_once $path;
            $isPostgres = false;
            $configLoaded = true;
            break;
        } catch (Exception $e) {
            error_log("Error cargando config_mysql.php: " . $e->getMessage());
        }
    }
}

if (!$configLoaded) {
    // Intentar cargar configuración Supabase
    foreach ($configPaths['supabase'] as $path) {
        if (file_exists($path)) {
            try {
                require_once $path;
                $isPostgres = true;
                $configLoaded = true;
                break;
            } catch (Exception $e) {
                error_log("Error cargando config_supabase.php: " . $e->getMessage());
            }
        }
    }
}

if (!$configLoaded) {
    // Intentar cargar configuración PostgreSQL
    foreach ($configPaths['postgresql'] as $path) {
        if (file_exists($path)) {
            try {
                require_once $path;
                $isPostgres = true;
                $configLoaded = true;
                break;
            } catch (Exception $e) {
                error_log("Error cargando config_postgresql.php: " . $e->getMessage());
            }
        }
    }
}

if (!$configLoaded) {
    // Intentar cargar configuración por defecto
    foreach ($configPaths['default'] as $path) {
        if (file_exists($path)) {
            try {
                require_once $path;
                $isPostgres = false;
                $configLoaded = true;
                break;
            } catch (Exception $e) {
                error_log("Error cargando config.php: " . $e->getMessage());
            }
        }
    }
}

if (!$configLoaded) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al cargar configuración de base de datos',
        'error' => 'No se pudo cargar ningún archivo de configuración',
        'debug' => [
            'mysql_exists' => file_exists('../database/config_mysql.php'),
            'supabase_exists' => file_exists('../database/config_supabase.php'),
            'postgresql_exists' => file_exists('../database/config_postgresql.php'),
            'config_exists' => file_exists('../database/config.php')
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener método y acción
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Verificar autenticación (solo para acciones que lo requieren)
// Permitir algunas acciones sin autenticación para debugging
$publicActions = ['test']; // Acciones públicas
if (!in_array($action, $publicActions)) {
    if (!function_exists('isAuthenticated') || !isAuthenticated()) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'No autenticado. Por favor, inicia sesión.',
            'action' => $action
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Router básico
try {
    switch ($action) {
        case 'test':
            echo json_encode([
                'success' => true,
                'message' => 'API funcionando correctamente',
                'php_version' => phpversion(),
                'method' => $method,
                'action' => $action
            ], JSON_UNESCAPED_UNICODE);
            exit;
            
        case 'productos':
            handleProductos($method);
            break;
        case 'pedidos':
            handlePedidos($method);
            break;
        case 'clientes':
            handleClientes($method);
            break;
        case 'mensajes':
            handleMensajes($method);
            break;
        case 'cotizaciones':
            handleCotizaciones($method);
            break;
        case 'imagenes':
            handleImagenes($method);
            break;
        case 'categorias':
            handleCategorias($method);
            break;
        case 'stats':
            handleStats();
            break;
        case 'create_product_page':
            handleCreateProductPage($method);
            break;
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida',
                'action' => $action,
                'available_actions' => ['test', 'productos', 'pedidos', 'clientes', 'mensajes', 'cotizaciones', 'imagenes', 'stats', 'create_product_page']
            ], JSON_UNESCAPED_UNICODE);
            exit;
    }
} catch (Exception $e) {
    error_log("Error en API: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Handlers

function handleProductos($method) {
    global $isPostgres;
    
    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            if ($id) {
                if ($isPostgres) {
                    $sql = "SELECT p.*, c.nombre as categoria_nombre 
                            FROM productos p 
                            LEFT JOIN categorias c ON p.categoria_id = c.id 
                            WHERE p.id = $1";
                } else {
                    $sql = "SELECT p.*, c.nombre as categoria_nombre 
                            FROM productos p 
                            LEFT JOIN categorias c ON p.categoria_id = c.id 
                            WHERE p.id = ?";
                }
                $producto = fetchOne($sql, [$id]);
                jsonResponse(true, 'Producto obtenido', $producto);
            } else {
                $productos = fetchAll("SELECT p.*, c.nombre as categoria_nombre 
                                      FROM productos p 
                                      LEFT JOIN categorias c ON p.categoria_id = c.id 
                                      ORDER BY p.fecha_creacion DESC");
                jsonResponse(true, 'Productos obtenidos', $productos);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                jsonResponse(false, 'Datos inválidos', null);
            }
            
            // Validar campos requeridos
            if (empty($data['nombre']) || empty($data['precio'])) {
                jsonResponse(false, 'Nombre y precio son requeridos', null);
            }
            
            if ($isPostgres) {
                $sql = "INSERT INTO productos (nombre, sku, slug, categoria_id, precio, precio_anterior, stock, descripcion_corta, dimensiones, peso, estado, producto_destacado) 
                        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12) RETURNING id";
            } else {
                $sql = "INSERT INTO productos (nombre, sku, slug, categoria_id, precio, precio_anterior, stock, descripcion_corta, dimensiones, peso, estado, producto_destacado) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            }
            
            $slug = slugify($data['nombre']);
            $params = [
                $data['nombre'],
                $data['sku'] ?? null,
                $slug,
                $data['categoria_id'] ?? null,
                floatval($data['precio']),
                isset($data['precio_anterior']) ? floatval($data['precio_anterior']) : null,
                isset($data['stock']) ? intval($data['stock']) : 0,
                $data['descripcion_corta'] ?? null,
                $data['dimensiones'] ?? null,
                isset($data['peso']) ? floatval($data['peso']) : null,
                $data['estado'] ?? 'activo',
                isset($data['producto_destacado']) && $data['producto_destacado'] ? true : false
            ];
            
            $id = insertAndGetId($sql, $params);
            if ($id) {
                jsonResponse(true, 'Producto creado exitosamente', ['id' => $id]);
            } else {
                jsonResponse(false, 'Error al crear producto', null);
            }
            break;
            
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            if ($isPostgres) {
                $sql = "UPDATE productos SET nombre = $1, sku = $2, categoria_id = $3, precio = $4, stock = $5, estado = $6 WHERE id = $7";
            } else {
                $sql = "UPDATE productos SET nombre = ?, sku = ?, categoria_id = ?, precio = ?, stock = ?, estado = ? WHERE id = ?";
            }
            $params = [
                $data['nombre'],
                $data['sku'] ?? null,
                $data['categoria_id'] ?? null,
                $data['precio'],
                $data['stock'],
                $data['estado'],
                $id
            ];
            executeQuery($sql, $params);
            jsonResponse(true, 'Producto actualizado', null);
            break;
            
        case 'DELETE':
            $id = $_GET['id'];
            $sql = $isPostgres ? "DELETE FROM productos WHERE id = $1" : "DELETE FROM productos WHERE id = ?";
            executeQuery($sql, [$id]);
            jsonResponse(true, 'Producto eliminado', null);
            break;
    }
}

function handlePedidos($method) {
    global $isPostgres;
    
    switch ($method) {
        case 'GET':
            $pedidos = fetchAll("SELECT p.*, c.nombre as cliente_nombre 
                                FROM pedidos p 
                                LEFT JOIN clientes c ON p.cliente_id = c.id 
                                ORDER BY p.fecha_pedido DESC");
            jsonResponse(true, 'Pedidos obtenidos', $pedidos);
            break;
            
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = $isPostgres 
                ? "UPDATE pedidos SET estado_pedido = $1, estado_pago = $2 WHERE id = $3"
                : "UPDATE pedidos SET estado_pedido = ?, estado_pago = ? WHERE id = ?";
            executeQuery($sql, [$data['estado_pedido'], $data['estado_pago'], $data['id']]);
            jsonResponse(true, 'Pedido actualizado', null);
            break;
    }
}

function handleClientes($method) {
    switch ($method) {
        case 'GET':
            $clientes = fetchAll("SELECT * FROM clientes ORDER BY fecha_registro DESC");
            jsonResponse(true, 'Clientes obtenidos', $clientes);
            break;
    }
}

function handleMensajes($method) {
    global $isPostgres;
    
    switch ($method) {
        case 'GET':
            $mensajes = fetchAll("SELECT * FROM mensajes ORDER BY fecha_creacion DESC");
            jsonResponse(true, 'Mensajes obtenidos', $mensajes);
            break;
            
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = $isPostgres
                ? "UPDATE mensajes SET estado = $1, leido = true WHERE id = $2"
                : "UPDATE mensajes SET estado = ?, leido = 1 WHERE id = ?";
            executeQuery($sql, [$data['estado'], $data['id']]);
            jsonResponse(true, 'Mensaje actualizado', null);
            break;
    }
}

function handleCotizaciones($method) {
    switch ($method) {
        case 'GET':
            $cotizaciones = fetchAll("SELECT * FROM cotizaciones ORDER BY fecha_creacion DESC");
            jsonResponse(true, 'Cotizaciones obtenidas', $cotizaciones);
            break;
    }
}

function handleCategorias($method) {
    global $isPostgres;
    
    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            if ($id) {
                if ($isPostgres) {
                    $sql = "SELECT * FROM categorias WHERE id = $1";
                } else {
                    $sql = "SELECT * FROM categorias WHERE id = ?";
                }
                $categoria = fetchOne($sql, [$id]);
                jsonResponse(true, 'Categoría obtenida', $categoria);
            } else {
                $categorias = fetchAll("SELECT * FROM categorias ORDER BY orden ASC, nombre ASC");
                jsonResponse(true, 'Categorías obtenidas', $categorias);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($isPostgres) {
                $sql = "INSERT INTO categorias (nombre, slug, descripcion, orden) 
                        VALUES ($1, $2, $3, $4) RETURNING id";
            } else {
                $sql = "INSERT INTO categorias (nombre, slug, descripcion, orden) 
                        VALUES (?, ?, ?, ?)";
            }
            $slug = slugify($data['nombre']);
            $id = insertAndGetId($sql, [
                $data['nombre'],
                $slug,
                $data['descripcion'] ?? null,
                $data['orden'] ?? 0
            ]);
            jsonResponse(true, 'Categoría creada', ['id' => $id]);
            break;
    }
}

function handleImagenes($method) {
    global $isPostgres;
    
    switch ($method) {
        case 'GET':
            $tipo = $_GET['tipo'] ?? null;
            if ($isPostgres) {
                if ($tipo) {
                    $sql = "SELECT id, url, tipo, titulo, descripcion, ubicacion, fecha_creacion 
                            FROM imagenes_web 
                            WHERE tipo = $1 
                            ORDER BY fecha_creacion DESC";
                    $params = [$tipo];
                } else {
                    $sql = "SELECT id, url, tipo, titulo, descripcion, ubicacion, fecha_creacion 
                            FROM imagenes_web 
                            ORDER BY fecha_creacion DESC";
                    $params = [];
                }
            } else {
                $sql = "SELECT id, url, tipo, titulo, descripcion, ubicacion, fecha_creacion FROM imagenes_web";
                $params = [];
                if ($tipo) {
                    $sql .= " WHERE tipo = ?";
                    $params[] = $tipo;
                }
                $sql .= " ORDER BY fecha_creacion DESC";
            }
            $imagenes = fetchAll($sql, $params);
            // Agregar prefijo a las URLs si no lo tienen
            foreach ($imagenes as &$img) {
                if (!empty($img['url']) && strpos($img['url'], 'http') !== 0 && strpos($img['url'], '/') !== 0) {
                    $img['url'] = '../' . $img['url'];
                }
            }
            jsonResponse(true, 'Imágenes obtenidas', $imagenes);
            break;
            
        case 'POST':
            // Manejar subida de imágenes
            if (!isset($_FILES['imagen'])) {
                jsonResponse(false, 'No se recibió ninguna imagen', null);
            }
            
            // Crear directorio de uploads si no existe
            $uploadDir = '../uploads/';
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    jsonResponse(false, 'Error al crear directorio de uploads', null);
                }
            }
            
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileType = $_FILES['imagen']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                jsonResponse(false, 'Tipo de archivo no permitido. Solo se permiten: JPEG, PNG, WebP, GIF', null);
            }
            
            // Validar tamaño (máximo 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($_FILES['imagen']['size'] > $maxSize) {
                jsonResponse(false, 'La imagen es demasiado grande. Máximo 5MB', null);
            }
            
            // Generar nombre único
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            
            // Mover archivo
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
                // Obtener datos del formulario
                $tipo = $_POST['tipo'] ?? 'galeria';
                $titulo = $_POST['titulo'] ?? pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME);
                $descripcion = $_POST['descripcion'] ?? null;
                $altText = $_POST['alt_text'] ?? null;
                
                // Guardar en base de datos
                if ($isPostgres) {
                    $sql = "INSERT INTO imagenes_web (url, tipo, titulo, descripcion, alt_text) 
                            VALUES ($1, $2, $3, $4, $5) RETURNING id";
                } else {
                    $sql = "INSERT INTO imagenes_web (url, tipo, titulo, descripcion, alt_text) 
                            VALUES (?, ?, ?, ?, ?)";
                }
                
                $url = 'uploads/' . $fileName;
                $id = insertAndGetId($sql, [$url, $tipo, $titulo, $descripcion, $altText]);
                
                if ($id) {
                    jsonResponse(true, 'Imagen subida exitosamente', [
                        'id' => $id,
                        'url' => $url,
                        'tipo' => $tipo,
                        'titulo' => $titulo
                    ]);
                } else {
                    // Si falla la inserción, eliminar el archivo
                    unlink($filePath);
                    jsonResponse(false, 'Error al guardar imagen en la base de datos', null);
                }
            } else {
                jsonResponse(false, 'Error al mover el archivo. Verifica permisos del directorio uploads/', null);
            }
            break;
    }
}

function handleStats() {
    global $isPostgres;
    
    try {
        $leidoCondition = $isPostgres ? "leido = false" : "leido = 0";
        $monthCondition = $isPostgres 
            ? "EXTRACT(MONTH FROM fecha_pedido) = EXTRACT(MONTH FROM CURRENT_DATE()) AND EXTRACT(YEAR FROM fecha_pedido) = EXTRACT(YEAR FROM CURRENT_DATE())"
            : "MONTH(fecha_pedido) = MONTH(CURRENT_DATE()) AND YEAR(fecha_pedido) = YEAR(CURRENT_DATE())";
        
        // Obtener estadísticas de productos
        $totalProductos = fetchOne("SELECT COUNT(*) as total FROM productos");
        $productosActivos = fetchOne("SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'");
        $productosAgotados = fetchOne("SELECT COUNT(*) as total FROM productos WHERE stock = 0");
        $productosBajoStock = fetchOne("SELECT COUNT(*) as total FROM productos WHERE stock > 0 AND stock < 10");
        
        // Obtener estadísticas de pedidos
        $totalPedidos = fetchOne("SELECT COUNT(*) as total FROM pedidos");
        $pedidosActivos = fetchOne("SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido NOT IN ('completado', 'cancelado')");
        $pedidosNuevos = fetchOne("SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'nuevo'");
        $pedidosProceso = fetchOne("SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'en_proceso'");
        $pedidosPendientes = fetchOne("SELECT COUNT(*) as total FROM pedidos WHERE estado_pedido = 'pendiente'");
        
        // Obtener estadísticas de clientes
        $clientesRegistrados = fetchOne("SELECT COUNT(*) as total FROM clientes");
        
        // Obtener estadísticas de mensajes
        $totalMensajes = fetchOne("SELECT COUNT(*) as total FROM mensajes");
        $mensajesNuevos = fetchOne("SELECT COUNT(*) as total FROM mensajes WHERE " . $leidoCondition);
        $mensajesPendientes = fetchOne("SELECT COUNT(*) as total FROM mensajes WHERE estado = 'pendiente'");
        $mensajesRespondidos = fetchOne("SELECT COUNT(*) as total FROM mensajes WHERE estado = 'respondido'");
        
        // Obtener estadísticas de cotizaciones
        $totalCotizaciones = fetchOne("SELECT COUNT(*) as total FROM cotizaciones");
        $cotizacionesPendientes = fetchOne("SELECT COUNT(*) as total FROM cotizaciones WHERE estado = 'pendiente'");
        
        // Obtener ventas del mes
        $ventasMes = fetchOne("SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE estado_pedido = 'completado' AND " . $monthCondition);
        
        $stats = [
            'total_productos' => (int)($totalProductos['total'] ?? 0),
            'productos_activos' => (int)($productosActivos['total'] ?? 0),
            'productos_agotados' => (int)($productosAgotados['total'] ?? 0),
            'productos_bajo_stock' => (int)($productosBajoStock['total'] ?? 0),
            'total_pedidos' => (int)($totalPedidos['total'] ?? 0),
            'pedidos_activos' => (int)($pedidosActivos['total'] ?? 0),
            'pedidos_nuevos' => (int)($pedidosNuevos['total'] ?? 0),
            'pedidos_proceso' => (int)($pedidosProceso['total'] ?? 0),
            'pedidos_pendientes' => (int)($pedidosPendientes['total'] ?? 0),
            'clientes_registrados' => (int)($clientesRegistrados['total'] ?? 0),
            'total_mensajes' => (int)($totalMensajes['total'] ?? 0),
            'mensajes_nuevos' => (int)($mensajesNuevos['total'] ?? 0),
            'mensajes_pendientes' => (int)($mensajesPendientes['total'] ?? 0),
            'mensajes_respondidos' => (int)($mensajesRespondidos['total'] ?? 0),
            'total_cotizaciones' => (int)($totalCotizaciones['total'] ?? 0),
            'cotizaciones_pendientes' => (int)($cotizacionesPendientes['total'] ?? 0),
            'ventas_mes' => (float)($ventasMes['total'] ?? 0)
        ];
        
        jsonResponse(true, 'Estadísticas obtenidas', $stats);
    } catch (Exception $e) {
        error_log("Error en handleStats: " . $e->getMessage());
        jsonResponse(false, 'Error al obtener estadísticas: ' . $e->getMessage(), null);
    }
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

function handleCreateProductPage($method) {
    if ($method !== 'POST') {
        http_response_code(405);
        jsonResponse(false, 'Método no permitido', null);
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['product_id']) || !isset($data['slug']) || !isset($data['html_content'])) {
        jsonResponse(false, 'Datos incompletos', null);
    }
    
    // Crear directorio de productos si no existe
    $productsDir = __DIR__ . '/productos';
    if (!file_exists($productsDir)) {
        if (!mkdir($productsDir, 0755, true)) {
            jsonResponse(false, 'Error al crear directorio de productos', null);
        }
    }
    
    // Crear archivo HTML
    $filename = $productsDir . '/' . $data['slug'] . '.html';
    
    if (file_put_contents($filename, $data['html_content'])) {
        jsonResponse(true, 'Página de producto creada exitosamente', [
            'url' => 'productos/' . $data['slug'] . '.html',
            'slug' => $data['slug'],
            'product_id' => $data['product_id']
        ]);
    } else {
        jsonResponse(false, 'Error al crear archivo de página', null);
    }
}

?>

