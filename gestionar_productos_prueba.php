<?php
/**
 * Script para gestionar productos de prueba
 * Hai Swimwear
 */

// Cargar configuración
require_once 'config_mysql.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener acción
$action = $_GET['action'] ?? 'list';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos de Prueba - Hai Swimwear</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #000;
        }
        
        .description {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-info {
            background: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #1976d2;
        }
        
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }
        
        .alert-warning {
            background: #fff3e0;
            color: #e65100;
            border-left: 4px solid #ff9800;
        }
        
        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .products-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }
        
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 14px;
        }
        
        .products-table tr:hover {
            background: #f8f9fa;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .product-image-placeholder {
            width: 60px;
            height: 60px;
            background: #e0e0e0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-activo {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .badge-inactivo {
            background: #ffebee;
            color: #c62828;
        }
        
        .badge-agotado {
            background: #fff3e0;
            color: #e65100;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #000;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .actions-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .checkbox-cell {
            text-align: center;
            width: 40px;
        }
        
        .checkbox-cell input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin.php" class="back-link">← Volver al Panel de Administración</a>
        
        <h1>🧹 Gestionar Productos de Prueba</h1>
        <p class="description">Aquí puedes ver todos los productos y eliminar los de prueba</p>
        
        <?php
        if ($action === 'delete' && isset($_POST['product_ids'])) {
            $productIds = $_POST['product_ids'];
            $deletedCount = 0;
            $errors = [];
            
            foreach ($productIds as $id) {
                // Primero eliminar las imágenes del producto
                $imagenes = fetchAll("SELECT url FROM producto_imagenes WHERE producto_id = ?", [$id]);
                foreach ($imagenes as $imagen) {
                    $filePath = __DIR__ . '/' . $imagen['url'];
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
                
                // Eliminar registros de imágenes en BD
                executeQuery("DELETE FROM producto_imagenes WHERE producto_id = ?", [$id]);
                
                // Obtener slug del producto para eliminar página HTML
                $producto = fetchOne("SELECT slug FROM productos WHERE id = ?", [$id]);
                if ($producto && $producto['slug']) {
                    $htmlPath = __DIR__ . '/productos/' . $producto['slug'] . '.html';
                    if (file_exists($htmlPath)) {
                        @unlink($htmlPath);
                    }
                }
                
                // Eliminar producto
                $result = executeQuery("DELETE FROM productos WHERE id = ?", [$id]);
                if ($result) {
                    $deletedCount++;
                } else {
                    $errors[] = "Error al eliminar producto ID: $id";
                }
            }
            
            if ($deletedCount > 0) {
                echo '<div class="alert alert-success">';
                echo "✅ Se eliminaron <strong>$deletedCount</strong> producto(s) correctamente.";
                echo '</div>';
            }
            
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                echo "❌ Algunos productos no se pudieron eliminar:<br>";
                foreach ($errors as $error) {
                    echo "- $error<br>";
                }
                echo '</div>';
            }
        }
        
        // Obtener todos los productos
        try {
            $productos = fetchAll("
                SELECT p.*, c.nombre as categoria_nombre,
                       (SELECT COUNT(*) FROM producto_imagenes WHERE producto_id = p.id) as num_imagenes
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.fecha_creacion DESC
            ");
            
            if (empty($productos)) {
                echo '<div class="empty-state">';
                echo '<div class="empty-state-icon">📦</div>';
                echo '<h2>No hay productos</h2>';
                echo '<p>No se encontraron productos en la base de datos.</p>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info">';
                echo "📊 Se encontraron <strong>" . count($productos) . "</strong> producto(s) en total.";
                echo '</div>';
                
                echo '<form method="POST" action="?action=delete" onsubmit="return confirm(\'¿Estás seguro de que quieres eliminar los productos seleccionados?\')">';
                echo '<div class="actions-group">';
                echo '<button type="submit" class="btn btn-danger">🗑️ Eliminar Seleccionados</button>';
                echo '<button type="button" class="btn btn-warning" onclick="selectAll()">☑️ Seleccionar Todos</button>';
                echo '<button type="button" class="btn btn-primary" onclick="deselectAll()">☐ Deseleccionar Todos</button>';
                echo '</div>';
                
                echo '<table class="products-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th class="checkbox-cell"><input type="checkbox" id="select-all" onchange="toggleAll(this)"></th>';
                echo '<th>Imagen</th>';
                echo '<th>Nombre</th>';
                echo '<th>SKU</th>';
                echo '<th>Categoría</th>';
                echo '<th>Precio</th>';
                echo '<th>Stock</th>';
                echo '<th>Estado</th>';
                echo '<th>Imágenes</th>';
                echo '<th>Fecha</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                foreach ($productos as $producto) {
                    // Obtener imagen principal
                    $imagenPrincipal = fetchOne("SELECT url FROM producto_imagenes WHERE producto_id = ? AND es_principal = 1 LIMIT 1", [$producto['id']]);
                    if (!$imagenPrincipal) {
                        $imagenPrincipal = fetchOne("SELECT url FROM producto_imagenes WHERE producto_id = ? ORDER BY orden ASC LIMIT 1", [$producto['id']]);
                    }
                    
                    echo '<tr>';
                    echo '<td class="checkbox-cell"><input type="checkbox" name="product_ids[]" value="' . $producto['id'] . '" class="product-checkbox"></td>';
                    echo '<td>';
                    if ($imagenPrincipal) {
                        echo '<img src="' . htmlspecialchars($imagenPrincipal['url']) . '" alt="' . htmlspecialchars($producto['nombre']) . '" class="product-image">';
                    } else {
                        echo '<div class="product-image-placeholder">Sin imagen</div>';
                    }
                    echo '</td>';
                    echo '<td>' . htmlspecialchars($producto['nombre']) . '</td>';
                    echo '<td>' . htmlspecialchars($producto['sku'] ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') . '</td>';
                    echo '<td>$' . number_format($producto['precio'], 0, ',', '.') . '</td>';
                    echo '<td>' . $producto['stock'] . '</td>';
                    echo '<td>';
                    $estadoClass = 'badge-' . $producto['estado'];
                    echo '<span class="badge ' . $estadoClass . '">' . ucfirst($producto['estado']) . '</span>';
                    echo '</td>';
                    echo '<td>' . $producto['num_imagenes'] . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($producto['fecha_creacion'])) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</form>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '❌ Error al cargar productos: ' . htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #dee2e6;">
            <h2 style="font-size: 20px; margin-bottom: 15px;">💡 Opciones Adicionales</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <div style="padding: 20px; background: #f8f9fa; border-radius: 6px;">
                    <h3 style="font-size: 16px; margin-bottom: 10px;">🧪 Eliminar TODOS los productos</h3>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Elimina todos los productos de la base de datos (útil para empezar desde cero)</p>
                    <form method="POST" action="?action=delete_all" onsubmit="return confirm('⚠️ ¿ESTÁS SEGURO? Esta acción eliminará TODOS los productos y NO se puede deshacer.')">
                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar TODOS</button>
                    </form>
                </div>
                
                <div style="padding: 20px; background: #f8f9fa; border-radius: 6px;">
                    <h3 style="font-size: 16px; margin-bottom: 10px;">📝 Agregar campo "creado_por"</h3>
                    <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Añade un campo para identificar quién creó cada producto (recomendado)</p>
                    <form method="POST" action="?action=add_user_field" onsubmit="return confirm('¿Añadir campo creado_por a la tabla productos?')">
                        <button type="submit" class="btn btn-success btn-sm">✨ Añadir Campo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleAll(checkbox) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }
        
        function selectAll() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('select-all').checked = true;
        }
        
        function deselectAll() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('select-all').checked = false;
        }
    </script>
</body>
</html>

<?php
// Procesar acciones adicionales
if ($action === 'delete_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Eliminar todas las imágenes físicas
        $imagenes = fetchAll("SELECT url FROM producto_imagenes");
        foreach ($imagenes as $imagen) {
            $filePath = __DIR__ . '/' . $imagen['url'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        
        // Eliminar todas las páginas HTML de productos
        $productos = fetchAll("SELECT slug FROM productos WHERE slug IS NOT NULL");
        foreach ($productos as $producto) {
            $htmlPath = __DIR__ . '/productos/' . $producto['slug'] . '.html';
            if (file_exists($htmlPath)) {
                @unlink($htmlPath);
            }
        }
        
        // Eliminar todos los registros
        executeQuery("DELETE FROM producto_imagenes");
        executeQuery("DELETE FROM productos");
        
        echo '<script>alert("✅ Se eliminaron TODOS los productos correctamente."); window.location.href = "gestionar_productos_prueba.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("❌ Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "gestionar_productos_prueba.php";</script>';
    }
    exit;
}

if ($action === 'add_user_field' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verificar si el campo ya existe
        $result = fetchOne("SHOW COLUMNS FROM productos LIKE 'creado_por'");
        
        if (!$result) {
            // Añadir el campo
            executeQuery("ALTER TABLE productos ADD COLUMN creado_por INT NULL AFTER estado");
            executeQuery("ALTER TABLE productos ADD COLUMN es_prueba TINYINT(1) DEFAULT 0 AFTER creado_por");
            
            echo '<script>alert("✅ Campo creado_por añadido correctamente."); window.location.href = "gestionar_productos_prueba.php";</script>';
        } else {
            echo '<script>alert("⚠️ El campo creado_por ya existe."); window.location.href = "gestionar_productos_prueba.php";</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("❌ Error: ' . addslashes($e->getMessage()) . '"); window.location.href = "gestionar_productos_prueba.php";</script>';
    }
    exit;
}
?>
