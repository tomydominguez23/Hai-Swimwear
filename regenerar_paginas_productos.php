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
        
        // Obtener imágenes del producto - Usar placeholders correctos según el tipo de BD
        // Detectar si estamos usando PostgreSQL/Supabase
        $isPostgres = defined('SUPABASE_HOST') || defined('POSTGRES_HOST');
        
        if ($isPostgres) {
            $imagenes = fetchAll(
                "SELECT url, alt_text FROM producto_imagenes WHERE producto_id = $1 ORDER BY es_principal DESC, orden ASC",
                [$producto['id']]
            );
        } else {
            $imagenes = fetchAll(
                "SELECT url, alt_text FROM producto_imagenes WHERE producto_id = ? ORDER BY es_principal DESC, orden ASC",
                [$producto['id']]
            );
        }
        
        // Generar slug
        $slug = slugify($producto['nombre']);
        
        // Procesar imágenes
        $processedImages = [];
        if (!empty($imagenes)) {
            foreach ($imagenes as $img) {
                $imgUrl = $img['url'];
                if (strpos($imgUrl, 'http') !== 0) {
                    $imgUrl = '../' . $imgUrl;
                }
                $processedImages[] = [
                    'url' => $imgUrl,
                    'alt' => $img['alt_text'] ?? $producto['nombre']
                ];
            }
            echo "<p>✓ " . count($processedImages) . " imágenes encontradas</p>";
        } else {
            // Placeholder
            $processedImages[] = [
                'url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'alt' => $producto['nombre']
            ];
            echo "<p>⚠️ Sin imágenes - usando placeholder</p>";
        }
        
        // Calcular descuento
        $descuentoHTML = '';
        $precioHTML = '';
        
        if ($producto['precio_anterior'] && $producto['precio_anterior'] > $producto['precio']) {
            $porcentaje = round((($producto['precio_anterior'] - $producto['precio']) / $producto['precio_anterior']) * 100);
            $precioHTML = '<span class="price-current">$' . number_format($producto['precio'], 0, ',', '.') . '</span>';
            $precioHTML .= '<span class="price-original">$' . number_format($producto['precio_anterior'], 0, ',', '.') . '</span>';
            $descuentoHTML = '<span class="discount-badge">-' . $porcentaje . '%</span>';
        } else {
            $precioHTML = '<span class="price-current">$' . number_format($producto['precio'], 0, ',', '.') . '</span>';
        }
        
        // Generar HTML
        $html = generarHTMLProducto($producto, $slug, $processedImages, $precioHTML, $descuentoHTML);
        
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

function generarHTMLProducto($producto, $slug, $imagenes, $precioHTML, $descuentoHTML) {
    $stock = intval($producto['stock']);
    $thumbnailsHTML = '';
    $mainImage = $imagenes[0]['url'];
    
    // Generar thumbnails
    if (count($imagenes) > 1) {
        foreach ($imagenes as $index => $img) {
            $activeClass = $index === 0 ? 'active' : '';
            $thumbnailsHTML .= "
                <div class=\"thumbnail {$activeClass}\" onclick=\"changeImage(this, '{$img['url']}')\">
                    <img src=\"{$img['url']}\" alt=\"Thumbnail {$index}\">
                </div>";
        }
    } else {
         $thumbnailsHTML .= "
            <div class=\"thumbnail active\" onclick=\"changeImage(this, '{$mainImage}')\">
                <img src=\"{$mainImage}\" alt=\"Thumbnail\">
            </div>";
    }
    
    // Pre-calculate dynamic values for HEREDOC
    $availabilityClass = ($stock > 0 ? 'available' : 'out-of-stock');
    $availabilityText = ($stock > 0 ? "Disponible ({$stock} unidades)" : 'Agotado');
    $skuHTML = ($producto['sku'] ? "<div class=\"spec-item\"><span class=\"spec-label\">SKU</span><span class=\"spec-value\">{$producto['sku']}</span></div>" : '');
    
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
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        
        .product-detail-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
        }
        
        .breadcrumb {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: #000;
        }
        
        .product-detail-grid {
            display: grid;
            grid-template-columns: 60% 40%;
            gap: 60px;
            margin-bottom: 80px;
        }
        
        /* Gallery Styles */
        .product-gallery {
            display: flex;
            gap: 20px;
        }
        
        .thumbnails-col {
            width: 100px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .thumbnail {
            width: 100%;
            aspect-ratio: 3/4;
            cursor: pointer;
            border: 1px solid transparent;
            transition: border-color 0.2s;
        }
        
        .thumbnail.active {
            border-color: #000;
        }
        
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .main-image-col {
            flex: 1;
            aspect-ratio: 3/4;
            background: #f8f8f8;
            overflow: hidden;
            position: relative;
        }
        
        .main-image-col img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Product Info Styles */
        .product-info {
            padding-top: 20px;
        }
        
        .brand-name {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        
        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 500;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .product-price-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .price-current {
            font-size: 28px;
            font-weight: 600;
        }
        
        .price-original {
            font-size: 18px;
            color: #999;
            text-decoration: line-through;
        }
        
        .discount-badge {
            background: #e63946;
            color: white;
            padding: 4px 8px;
            border-radius: 0;
            font-size: 14px;
            font-weight: 600;
        }
        
        .availability {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .available .dot { background: #4CAF50; }
        .out-of-stock .dot { background: #F44336; }
        
        /* Actions */
        .product-actions {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-add-cart {
            background: #25D366; /* WhatsApp Green */
            color: white;
            border: none;
            width: 100%;
            padding: 18px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.3s;
            text-align: center;
        }
        
        .btn-add-cart:hover {
            background: #1da851;
        }
        
        .specs-section {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 30px;
        }
        
        .specs-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f9f9f9;
            font-size: 14px;
        }
        
        .spec-label { color: #666; }
        .spec-value { font-weight: 500; }
        
        @media (max-width: 992px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .product-gallery {
                flex-direction: column-reverse;
            }
            .thumbnails-col {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
            }
            .thumbnail {
                width: 80px;
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
                </div>
            </div>
        </div>
    </nav>

    <div class="product-detail-container">
        <div class="breadcrumb">
            <a href="../index.html">Home</a> / <a href="../productos.html">Productos</a> / <span>{$producto['nombre']}</span>
        </div>
        
        <div class="product-detail-grid">
            <div class="product-gallery">
                <div class="thumbnails-col">
                    {$thumbnailsHTML}
                </div>
                <div class="main-image-col">
                    <img id="mainImage" src="{$mainImage}" alt="{$producto['nombre']}" onerror="this.src='https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'">
                </div>
            </div>
            
            <div class="product-info">
                <div class="brand-name">HAI SWIMWEAR</div>
                <h1 class="product-title">{$producto['nombre']}</h1>
                
                <div class="product-price-section">
                    {$precioHTML}
                    {$descuentoHTML}
                </div>
                
                <div class="availability {$availabilityClass}">
                    <span class="dot"></span>
                    <span>{$availabilityText}</span>
                </div>
                
                <div class="product-actions">
                    <button class="btn-add-cart" onclick="contactWhatsApp()">
                        CONSULTAR DISPONIBILIDAD
                    </button>
                </div>
                
                <div class="specs-section">
                    <h3 class="specs-title">Especificaciones</h3>
                    {$skuHTML}
                    <div class="spec-item">
                        <span class="spec-label">Descripción</span>
                    </div>
                    <div style="margin-top: 10px; color: #666; line-height: 1.6;">
                        {$producto['descripcion_corta']}
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
                    <p>Trajes de baño diseñados especialmente para mujeres con busto grande.</p>
                </div>
                <div class="footer-section">
                    <h4>Información</h4>
                    <ul>
                        <li><a href="../index.html#tallas">Guía de Tallas</a></li>
                        <li><a href="../index.html#envios">Envíos y Devoluciones</a></li>
                        <li><a href="../index.html#contacto">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Hai Swimwear. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function changeImage(element, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

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
