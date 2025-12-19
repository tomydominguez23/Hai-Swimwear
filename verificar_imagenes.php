<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Imágenes - Hai Swimwear</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; border-bottom: 3px solid #000; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; margin-bottom: 15px; font-size: 20px; }
        .status { padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; color: #333; }
        tr:hover { background: #f8f9fa; }
        .btn { display: inline-block; padding: 10px 20px; background: #000; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; cursor: pointer; border: none; }
        .btn:hover { background: #333; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verificación de Imágenes de Productos</h1>

<?php
// Cargar configuración
$configLoaded = false;
$configPaths = [
    __DIR__ . '/config_mysql.php',
    __DIR__ . '/config_supabase.php',
    __DIR__ . '/config_postgresql.php',
    __DIR__ . '/config.php'
];

foreach ($configPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $configLoaded = true;
        echo "<div class='status success'>✓ Configuración cargada: " . basename($path) . "</div>";
        break;
    }
}

if (!$configLoaded) {
    die("<div class='status error'>❌ Error: No se encontró archivo de configuración</div></div></body></html>");
}

try {
    // 1. Verificar si la tabla existe
    echo "<h2>1. Verificación de Tabla</h2>";
    
    $tablaExiste = false;
    if (isset($isPostgres) && $isPostgres) {
        echo "<div class='status info'>Usando PostgreSQL/Supabase</div>";
        $result = fetchOne("SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'producto_imagenes'
        ) as exists");
        $tablaExiste = $result && $result['exists'];
    } else {
        echo "<div class='status info'>Usando MySQL</div>";
        $result = fetchOne("SHOW TABLES LIKE 'producto_imagenes'");
        $tablaExiste = !empty($result);
    }
    
    if ($tablaExiste) {
        echo "<div class='status success'>✓ La tabla <code>producto_imagenes</code> existe</div>";
        
        // Contar imágenes
        $count = fetchOne("SELECT COUNT(*) as total FROM producto_imagenes");
        $totalImagenes = $count['total'] ?? 0;
        echo "<div class='status info'>Total de imágenes en la base de datos: <strong>{$totalImagenes}</strong></div>";
        
    } else {
        echo "<div class='status error'>❌ La tabla <code>producto_imagenes</code> NO existe</div>";
        echo "<div class='status warning'>⚠️  Necesitas crear la tabla. Ejecuta el archivo <code>schema.sql</code> en tu base de datos.</div>";
        
        echo "<h3>SQL para crear la tabla:</h3>";
        if (isset($isPostgres) && $isPostgres) {
            echo "<pre>CREATE TABLE producto_imagenes (
    id SERIAL PRIMARY KEY,
    producto_id INTEGER NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INTEGER DEFAULT 0,
    es_principal BOOLEAN DEFAULT false,
    alt_text VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE INDEX idx_producto ON producto_imagenes(producto_id);
CREATE INDEX idx_principal ON producto_imagenes(es_principal);</pre>";
        } else {
            echo "<pre>CREATE TABLE producto_imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0,
    es_principal TINYINT(1) DEFAULT 0,
    alt_text VARCHAR(255) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    INDEX idx_producto (producto_id),
    INDEX idx_principal (es_principal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</pre>";
        }
        
        echo "<button class='btn' onclick='crearTabla()'>Crear Tabla Automáticamente</button>";
    }
    
    // 2. Verificar productos
    echo "<h2>2. Productos Existentes</h2>";
    
    $productos = fetchAll("SELECT p.id, p.nombre, p.precio, p.estado, p.fecha_creacion 
                          FROM productos p 
                          ORDER BY p.fecha_creacion DESC 
                          LIMIT 20");
    
    if (empty($productos)) {
        echo "<div class='status warning'>⚠️  No hay productos en la base de datos</div>";
    } else {
        echo "<div class='status success'>✓ Encontrados " . count($productos) . " productos</div>";
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Imagen</th><th>Nombre</th><th>Precio</th><th>Estado</th><th>Imágenes</th><th>Fecha</th></tr>";
        
        foreach ($productos as $prod) {
            // Obtener imágenes del producto
            if ($tablaExiste) {
                if (isset($isPostgres) && $isPostgres) {
                    $imagenes = fetchAll("SELECT * FROM producto_imagenes WHERE producto_id = $1 ORDER BY es_principal DESC, orden ASC", [$prod['id']]);
                } else {
                    $imagenes = fetchAll("SELECT * FROM producto_imagenes WHERE producto_id = ? ORDER BY es_principal DESC, orden ASC", [$prod['id']]);
                }
            } else {
                $imagenes = [];
            }
            
            $numImagenes = count($imagenes);
            $imagenPrincipal = !empty($imagenes) ? $imagenes[0]['url'] : null;
            
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>";
            if ($imagenPrincipal) {
                echo "<img src='{$imagenPrincipal}' class='product-image' alt='{$prod['nombre']}' onerror='this.src=\"data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27100%27 height=%27100%27%3E%3Crect fill=%27%23ddd%27 width=%27100%27 height=%27100%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 dominant-baseline=%27middle%27 text-anchor=%27middle%27 fill=%27%23999%27%3ESin img%3C/text%3E%3C/svg%3E\"'>";
            } else {
                echo "<span style='color: #999;'>Sin imagen</span>";
            }
            echo "</td>";
            echo "<td><strong>{$prod['nombre']}</strong></td>";
            echo "<td>$" . number_format($prod['precio'], 0, ',', '.') . "</td>";
            echo "<td><span style='color: " . ($prod['estado'] == 'activo' ? 'green' : 'orange') . ";'>●</span> {$prod['estado']}</td>";
            echo "<td>" . ($numImagenes > 0 ? "✓ {$numImagenes} imagen(es)" : "<span style='color: red;'>❌ Sin imágenes</span>") . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($prod['fecha_creacion'])) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    // 3. Verificar imágenes huérfanas
    if ($tablaExiste && $totalImagenes > 0) {
        echo "<h2>3. Imágenes en la Base de Datos</h2>";
        
        $imagenes = fetchAll("SELECT pi.*, p.nombre as producto_nombre 
                             FROM producto_imagenes pi 
                             LEFT JOIN productos p ON pi.producto_id = p.id 
                             ORDER BY pi.fecha_creacion DESC 
                             LIMIT 20");
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Preview</th><th>Producto</th><th>URL</th><th>Principal</th><th>Orden</th><th>Fecha</th></tr>";
        
        foreach ($imagenes as $img) {
            echo "<tr>";
            echo "<td>{$img['id']}</td>";
            echo "<td><img src='{$img['url']}' class='product-image' alt='{$img['alt_text']}' onerror='this.style.border=\"2px solid red\"'></td>";
            echo "<td>" . ($img['producto_nombre'] ?? '<span style="color:red;">Producto eliminado</span>') . "</td>";
            echo "<td><code>" . substr($img['url'], 0, 50) . (strlen($img['url']) > 50 ? '...' : '') . "</code></td>";
            echo "<td>" . ($img['es_principal'] ? '⭐ Sí' : 'No') . "</td>";
            echo "<td>{$img['orden']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($img['fecha_creacion'])) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    // 4. Probar API
    echo "<h2>4. Prueba de API</h2>";
    echo "<div class='status info'>
        <strong>Endpoint:</strong> <code>api.php?action=productos&estado=activo</code><br>
        <button class='btn' onclick='probarAPI()'>Probar API Ahora</button>
    </div>";
    echo "<div id='apiResult' style='margin-top: 10px;'></div>";
    
    // 5. Verificar directorio uploads
    echo "<h2>5. Verificación de Directorio</h2>";
    
    $uploadDir = __DIR__ . '/uploads/productos/';
    if (file_exists($uploadDir)) {
        echo "<div class='status success'>✓ Directorio <code>/uploads/productos/</code> existe</div>";
        
        $files = glob($uploadDir . '*');
        $numFiles = count($files);
        
        if ($numFiles > 0) {
            echo "<div class='status success'>✓ Encontrados {$numFiles} archivo(s) en el directorio</div>";
            echo "<ul>";
            foreach (array_slice($files, 0, 10) as $file) {
                $filename = basename($file);
                $size = filesize($file);
                echo "<li>{$filename} (" . number_format($size/1024, 1) . " KB)</li>";
            }
            if ($numFiles > 10) {
                echo "<li>... y " . ($numFiles - 10) . " archivo(s) más</li>";
            }
            echo "</ul>";
        } else {
            echo "<div class='status warning'>⚠️  El directorio está vacío. No se han subido imágenes aún.</div>";
        }
    } else {
        echo "<div class='status error'>❌ El directorio <code>/uploads/productos/</code> NO existe</div>";
        echo "<div class='status info'>Creando directorio...</div>";
        if (mkdir($uploadDir, 0755, true)) {
            echo "<div class='status success'>✓ Directorio creado exitosamente</div>";
        } else {
            echo "<div class='status error'>❌ No se pudo crear el directorio. Verifica permisos.</div>";
        }
    }
    
    // Resumen y recomendaciones
    echo "<h2>6. Resumen y Recomendaciones</h2>";
    
    if (!$tablaExiste) {
        echo "<div class='status error'><strong>ACCIÓN REQUERIDA:</strong> Crear la tabla <code>producto_imagenes</code></div>";
    } elseif ($totalImagenes == 0 && !empty($productos)) {
        echo "<div class='status warning'><strong>ACCIÓN SUGERIDA:</strong> Tienes productos pero ninguna imagen. Sube imágenes desde el panel admin.</div>";
    } elseif ($totalImagenes > 0) {
        echo "<div class='status success'><strong>✓ TODO OK:</strong> La base de datos tiene la tabla y hay imágenes guardadas.</div>";
    }
    
    echo "<div style='margin-top: 20px;'>
        <a href='admin.php' class='btn'>Ir al Panel Admin</a>
        <a href='productos.html' class='btn btn-secondary'>Ver Página de Productos</a>
        <button class='btn btn-secondary' onclick='location.reload()'>Actualizar</button>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='status error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

    </div>

    <script>
        async function probarAPI() {
            const result = document.getElementById('apiResult');
            result.innerHTML = '<div class="status info">Cargando...</div>';
            
            try {
                const response = await fetch('api.php?action=productos&estado=activo');
                const data = await response.json();
                
                if (data.success) {
                    const productos = data.data || [];
                    let html = `<div class="status success">✓ API funcionando correctamente</div>`;
                    html += `<div class="status info">Productos encontrados: ${productos.length}</div>`;
                    
                    if (productos.length > 0) {
                        html += '<h3>Primeros 3 productos:</h3><pre>';
                        html += JSON.stringify(productos.slice(0, 3), null, 2);
                        html += '</pre>';
                    }
                    
                    result.innerHTML = html;
                } else {
                    result.innerHTML = `<div class="status error">❌ Error: ${data.message}</div>`;
                }
            } catch (error) {
                result.innerHTML = `<div class="status error">❌ Error al conectar con la API: ${error.message}</div>`;
            }
        }

        async function crearTabla() {
            if (!confirm('¿Estás seguro de que quieres crear la tabla producto_imagenes?')) {
                return;
            }
            
            alert('Esta función requiere ejecutar SQL. Por favor, ejecuta el SQL mostrado arriba en tu panel de base de datos (phpMyAdmin, Supabase, etc.)');
        }
    </script>
</body>
</html>
