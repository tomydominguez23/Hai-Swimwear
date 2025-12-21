#!/usr/bin/env php
<?php
/**
 * Script de diagnóstico para imágenes de productos
 */

// Intentar cargar configuración
$configLoaded = false;
$configPaths = [
    __DIR__ . '/config_supabase.php',
    __DIR__ . '/config_postgresql.php',
    __DIR__ . '/config_mysql.php',
    __DIR__ . '/config.php'
];

foreach ($configPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $configLoaded = true;
        echo "✓ Configuración cargada: " . basename($path) . "\n\n";
        break;
    }
}

if (!$configLoaded) {
    die("❌ Error: No se encontró archivo de configuración\n");
}

echo "=== DIAGNÓSTICO DE IMÁGENES DE PRODUCTOS ===\n\n";

try {
    // 1. Verificar productos existentes
    echo "1. PRODUCTOS EN LA BASE DE DATOS:\n";
    echo str_repeat("-", 50) . "\n";
    
    $productos = fetchAll("SELECT id, nombre, slug, precio, precio_anterior, stock, estado FROM productos ORDER BY id ASC");
    
    if (empty($productos)) {
        echo "❌ No hay productos en la base de datos\n\n";
    } else {
        foreach ($productos as $producto) {
            echo "ID: {$producto['id']}\n";
            echo "  Nombre: {$producto['nombre']}\n";
            echo "  Slug: {$producto['slug']}\n";
            echo "  Precio: \${$producto['precio']}\n";
            echo "  Stock: {$producto['stock']}\n";
            echo "  Estado: {$producto['estado']}\n";
            
            // Verificar imágenes del producto
            $imagenes = fetchAll(
                "SELECT id, url, orden, es_principal, alt_text FROM producto_imagenes WHERE producto_id = $1 ORDER BY es_principal DESC, orden ASC",
                [$producto['id']]
            );
            
            if (empty($imagenes)) {
                echo "  ⚠️  Imágenes: NINGUNA\n";
            } else {
                echo "  ✓ Imágenes: " . count($imagenes) . "\n";
                foreach ($imagenes as $img) {
                    $principal = $img['es_principal'] ? '★ PRINCIPAL' : '';
                    echo "    - {$img['url']} {$principal}\n";
                }
            }
            
            // Verificar si existe archivo HTML
            $htmlFile = __DIR__ . '/productos/' . ($producto['slug'] ?: 'sin-slug') . '.html';
            if (file_exists($htmlFile)) {
                echo "  ✓ Archivo HTML: productos/{$producto['slug']}.html (existe)\n";
            } else {
                echo "  ❌ Archivo HTML: productos/{$producto['slug']}.html (NO existe)\n";
            }
            
            echo "\n";
        }
    }
    
    // 2. Verificar directorio de uploads
    echo "\n2. DIRECTORIO DE UPLOADS:\n";
    echo str_repeat("-", 50) . "\n";
    
    $uploadsDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadsDir)) {
        echo "❌ El directorio /uploads/ NO existe\n";
        echo "   Creando directorio...\n";
        mkdir($uploadsDir, 0755, true);
        echo "   ✓ Directorio creado\n";
    } else {
        echo "✓ El directorio /uploads/ existe\n";
        
        // Listar archivos en uploads
        $files = array_diff(scandir($uploadsDir), ['.', '..']);
        $imageFiles = array_filter($files, function($file) use ($uploadsDir) {
            return is_file($uploadsDir . $file) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
        });
        
        if (empty($imageFiles)) {
            echo "⚠️  No hay imágenes en /uploads/\n";
        } else {
            echo "✓ Imágenes encontradas: " . count($imageFiles) . "\n";
            foreach ($imageFiles as $file) {
                echo "  - $file\n";
            }
        }
    }
    
    // 3. Verificar archivos HTML de productos
    echo "\n3. ARCHIVOS HTML DE PRODUCTOS:\n";
    echo str_repeat("-", 50) . "\n";
    
    $productosDir = __DIR__ . '/productos/';
    if (!is_dir($productosDir)) {
        echo "❌ El directorio /productos/ NO existe\n";
        echo "   Creando directorio...\n";
        mkdir($productosDir, 0755, true);
        echo "   ✓ Directorio creado\n";
    } else {
        echo "✓ El directorio /productos/ existe\n";
        
        $htmlFiles = array_diff(scandir($productosDir), ['.', '..']);
        $htmlFiles = array_filter($htmlFiles, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'html';
        });
        
        if (empty($htmlFiles)) {
            echo "⚠️  No hay archivos HTML en /productos/\n";
        } else {
            echo "✓ Archivos HTML encontrados: " . count($htmlFiles) . "\n";
            foreach ($htmlFiles as $file) {
                echo "  - $file\n";
            }
        }
    }
    
    // 4. Resumen y recomendaciones
    echo "\n4. RESUMEN Y RECOMENDACIONES:\n";
    echo str_repeat("-", 50) . "\n";
    
    $productosSinImagenes = 0;
    $productosSinHTML = 0;
    
    foreach ($productos as $producto) {
        $imagenes = fetchAll(
            "SELECT id FROM producto_imagenes WHERE producto_id = $1",
            [$producto['id']]
        );
        
        if (empty($imagenes)) {
            $productosSinImagenes++;
        }
        
        $htmlFile = __DIR__ . '/productos/' . ($producto['slug'] ?: 'sin-slug') . '.html';
        if (!file_exists($htmlFile)) {
            $productosSinHTML++;
        }
    }
    
    if ($productosSinImagenes > 0) {
        echo "⚠️  {$productosSinImagenes} producto(s) sin imágenes\n";
        echo "   → Sube imágenes desde el panel de administración\n";
        echo "   → O edita el producto y asigna una URL de imagen\n\n";
    }
    
    if ($productosSinHTML > 0) {
        echo "⚠️  {$productosSinHTML} producto(s) sin archivo HTML\n";
        echo "   → Ejecuta: regenerar_paginas_productos.php\n\n";
    }
    
    if ($productosSinImagenes === 0 && $productosSinHTML === 0 && !empty($productos)) {
        echo "✅ ¡Todo está configurado correctamente!\n";
    }
    
    if (empty($productos)) {
        echo "ℹ️  No hay productos. Crea productos desde el panel de administración.\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
?>
