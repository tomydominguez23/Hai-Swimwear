<?php
/**
 * Script para regenerar las páginas de productos existentes
 * con el formato actualizado (imágenes y header correctos)
 */

// Cargar configuración - Intentar con diferentes configuraciones
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
        break;
    }
}

if (!$configLoaded) {
    die("Error: No se pudo cargar ningún archivo de configuración.");
}

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Regenerar Páginas</title></head><body>";
echo "<h1>🔄 Regenerando Páginas de Productos</h1>";

try {
    // Obtener todos los productos
    $productos = fetchAll("SELECT * FROM productos ORDER BY id ASC");
    
    if (empty($productos)) {
        echo "<p style='color: orange;'>⚠️ No hay productos para regenerar.</p>";
        echo "</body></html>";
        exit;
    }
    
    echo "<p>Productos encontrados: " . count($productos) . "</p>";
    echo "<hr>";
    
    $regenerados = 0;
    $errores = 0;
    
    foreach ($productos as $producto) {
        echo "<div style='padding: 10px; margin: 10px 0; background: #f5f5f5; border-left: 4px solid #4CAF50;'>";
        echo "<h3>Producto: {$producto['nombre']} (ID: {$producto['id']})</h3>";
        
        // Obtener imágenes del producto
        $imagenes = fetchAll(
            "SELECT url, alt_text FROM producto_imagenes WHERE producto_id = ? ORDER BY es_principal DESC, orden ASC",
            [$producto['id']]
        );
        
        // Generar slug
        $slug = slugify($producto['nombre']);
        
        // Determinar URL de imagen
        $imagenUrl = 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        if (!empty($imagenes)) {
            // Si la URL ya es absoluta (http/https), usarla directamente
            if (strpos($imagenes[0]['url'], 'http') === 0) {
                $imagenUrl = $imagenes[0]['url'];
            } else {
                // Si es relativa, agregar ../ para navegar desde /productos/
                $imagenUrl = '../' . $imagenes[0]['url'];
            }
            echo "<p>✓ Imagen encontrada: {$imagenes[0]['url']}</p>";
        } else {
            echo "<p>⚠️ Sin imágenes - usando placeholder</p>";
        }
        
        // Calcular descuento
        $descuento = '';
        if ($producto['precio_anterior'] && $producto['precio_anterior'] > $producto['precio']) {
            $porcentaje = round((($producto['precio_anterior'] - $producto['precio']) / $producto['precio_anterior']) * 100);
            $descuento = "<span class=\"product-price-original\">$" . number_format($producto['precio_anterior'], 0, ',', '.') . "</span>
                    <span class=\"product-discount\">-{$porcentaje}%</span>";
        }
        
        // Generar HTML
        $html = generarHTMLProducto($producto, $slug, $imagenUrl, $descuento);
        
        // Guardar archivo
        $dirProductos = __DIR__ . '/productos';
        if (!file_exists($dirProductos)) {
            mkdir($dirProductos, 0755, true);
        }
        
        $archivo = $dirProductos . '/' . $slug . '.html';
        
        if (file_put_contents($archivo, $html)) {
            echo "<p style='color: green;'>✓ Página generada: <a href='productos/{$slug}.html' target='_blank'>productos/{$slug}.html</a></p>";
            $regenerados++;
        } else {
            echo "<p style='color: red;'>✗ Error al guardar archivo</p>";
            $errores++;
        }
        
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<h2>Resumen:</h2>";
    echo "<p>✓ Páginas regeneradas: <strong>{$regenerados}</strong></p>";
    echo "<p>✗ Errores: <strong>{$errores}</strong></p>";
    
    if ($regenerados > 0) {
        echo "<p style='color: green; font-size: 18px;'>🎉 ¡Listo! Ahora todas las páginas tienen el header correcto y las imágenes reales.</p>";
        echo "<p><a href='productos.html'>Ver página de productos →</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "</body></html>";

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

function generarHTMLProducto($producto, $slug, $imagenUrl, $descuento) {
    $precio = number_format($producto['precio'], 0, ',', '.');
    $stock = intval($producto['stock']);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$producto['nombre']} - Hai Swimwear</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 40px 20px;
        }
        
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }
        
        .product-images {
            position: sticky;
            top: 100px;
        }
        
        .main-product-image {
            width: 100%;
            aspect-ratio: 1;
            background: #f8f8f8;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .main-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px 0;
        }
        
        .product-category {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .product-title {
            font-size: 36px;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
        }
        
        .product-price-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .product-price {
            font-size: 32px;
            font-weight: 700;
            color: #000;
        }
        
        .product-price-original {
            font-size: 24px;
            color: #999;
            text-decoration: line-through;
        }
        
        .product-discount {
            background: #e63946;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .product-description {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }
        
        .product-stock {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
        }
        
        .stock-indicator.low {
            background: #f59e0b;
        }
        
        .stock-indicator.out {
            background: #ef4444;
        }
        
        .product-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .btn-add-cart {
            flex: 1;
            background: #000;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn-add-cart:hover {
            background: #333;
        }
        
        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn-whatsapp:hover {
            background: #1da851;
        }
        
        .product-specs {
            border-top: 1px solid #eee;
            padding-top: 30px;
        }
        
        .spec-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .spec-label {
            flex: 0 0 150px;
            font-weight: 600;
            color: #333;
        }
        
        .spec-value {
            flex: 1;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .product-images {
                position: relative;
                top: 0;
            }
            
            .product-title {
                font-size: 28px;
            }
            
            .product-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Top Info Bar -->
    <div class="top-info-bar">
        <div class="container">
            <div class="info-left">
                <span>PAGA EN 12 CUOTAS SIN INTERÉS</span>
            </div>
            <div class="info-center">
                <span>Envío RM a $3.490 y REGIONES a $6.390</span>
            </div>
            <div class="info-right">
                <span>RETIRO EN TIENDA GRATIS</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="nav-left">
                <div class="logo">
                    <a href="../index.html" style="text-decoration: none; color: inherit;">
                        <h1 class="logo-text">Hai Swimwear</h1>
                    </a>
                </div>
            </div>
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="../index.html#inicio">Inicio</a></li>
                    <li><a href="../productos.html">Productos</a></li>
                    <li><a href="../index.html#colecciones">Colecciones</a></li>
                    <li><a href="../index.html#tallas">Guía de Tallas</a></li>
                    <li><a href="../index.html#contacto">Contacto</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <div class="search-bar">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" placeholder="¿Qué buscas?" class="search-input">
                </div>
                <div class="nav-icons">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <a href="../login.php" class="nav-icon-link" title="Panel de Administración">
                        <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 2v2M15 2v2M9 20v2M15 20v2M5 2h14a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <div class="product-detail-container">
        <div class="breadcrumb">
            <a href="../index.html">Home</a> / <a href="../productos.html">Productos</a> / <span>{$producto['nombre']}</span>
        </div>
        
        <div class="product-detail-grid">
            <div class="product-images">
                <div class="main-product-image">
                    <img src="{$imagenUrl}" alt="{$producto['nombre']}" onerror="this.src='https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                </div>
            </div>
            
            <div class="product-info">
                <div class="product-category">Hai Swimwear</div>
                <h1 class="product-title">{$producto['nombre']}</h1>
                
                <div class="product-price-section">
                    <span class="product-price">\${$precio}</span>
                    {$descuento}
                </div>
                
                {$producto['descripcion_corta'] ? "<div class=\"product-description\"><p>{$producto['descripcion_corta']}</p></div>" : ''}
                
                <div class="product-stock">
                    <span class="stock-indicator " . ($stock === 0 ? 'out' : ($stock < 10 ? 'low' : '')) . "\"></span>
                    <span>" . ($stock === 0 ? 'Agotado' : ($stock < 10 ? "Solo quedan {$stock} unidades" : 'Disponible')) . "</span>
                </div>
                
                <div class="product-actions">
                    <button class="btn-add-cart" " . ($stock === 0 ? 'disabled' : '') . ">
                        " . ($stock === 0 ? 'AGOTADO' : 'AGREGAR AL CARRITO') . "
                    </button>
                    <button class=\"btn-whatsapp\" onclick=\"contactWhatsApp()\">
                        CONSULTAR
                    </button>
                </div>
                
                <div class="product-specs">
                    <h3 style="margin-bottom: 20px; font-size: 20px;">Especificaciones</h3>
                    " . ($producto['sku'] ? "<div class=\"spec-row\"><span class=\"spec-label\">SKU:</span><span class=\"spec-value\">{$producto['sku']}</span></div>" : '') . "
                    " . ($producto['dimensiones'] ? "<div class=\"spec-row\"><span class=\"spec-label\">Dimensiones:</span><span class=\"spec-value\">{$producto['dimensiones']}</span></div>" : '') . "
                    " . ($producto['peso'] ? "<div class=\"spec-row\"><span class=\"spec-label\">Peso:</span><span class=\"spec-value\">{$producto['peso']} kg</span></div>" : '') . "
                    <div class=\"spec-row\">
                        <span class=\"spec-label\">Stock:</span>
                        <span class=\"spec-value\">{$stock} unidades</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-logo">Hai Swimwear</h3>
                    <p>Trajes de baño diseñados especialmente para mujeres con busto grande. Comodidad, estilo y soporte perfecto.</p>
                </div>
                <div class="footer-section">
                    <h4>Información</h4>
                    <ul>
                        <li><a href="../index.html#tallas">Guía de Tallas</a></li>
                        <li><a href="../index.html#envios">Envíos y Devoluciones</a></li>
                        <li><a href="../index.html#preguntas">Preguntas Frecuentes</a></li>
                        <li><a href="../index.html#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#terminos">Términos y Condiciones</a></li>
                        <li><a href="#privacidad">Política de Privacidad</a></li>
                        <li><a href="#cambios">Política de Cambios</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram">Instagram</a>
                        <a href="#" aria-label="Facebook">Facebook</a>
                        <a href="#" aria-label="TikTok">TikTok</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Hai Swimwear. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function contactWhatsApp() {
            const message = encodeURIComponent('Hola! Estoy interesado/a en el producto: {$producto['nombre']}');
            window.open('https://wa.me/56912345678?text=' + message, '_blank');
        }
    </script>
</body>
</html>
HTML;
}
?>
