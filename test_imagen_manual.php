<?php
/**
 * Script de prueba: Insertar imagen de prueba en la base de datos
 */

// Cargar configuración
require_once __DIR__ . '/config_mysql.php';

echo "<h2>Test: Insertar Imagen de Prueba</h2>";

try {
    // 1. Verificar si hay productos
    $productos = fetchAll("SELECT id, nombre FROM productos LIMIT 5");
    
    echo "<h3>Productos existentes:</h3>";
    if (empty($productos)) {
        echo "<p style='color: red;'>❌ No hay productos en la base de datos. Crea un producto primero.</p>";
        exit;
    }
    
    echo "<ul>";
    foreach ($productos as $p) {
        echo "<li>ID: {$p['id']} - {$p['nombre']}</li>";
    }
    echo "</ul>";
    
    // 2. Tomar el primer producto
    $productoId = $productos[0]['id'];
    echo "<h3>Usando producto ID: $productoId</h3>";
    
    // 3. Insertar imagen de prueba (URL de ejemplo)
    $urlImagen = "https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80";
    
    $sql = "INSERT INTO producto_imagenes (producto_id, url, alt_text, es_principal, orden) 
            VALUES (?, ?, ?, ?, ?)";
    
    $params = [
        $productoId,
        $urlImagen,
        "Imagen de prueba",
        1, // es_principal
        0  // orden
    ];
    
    executeQuery($sql, $params);
    
    echo "<p style='color: green;'>✓ Imagen de prueba insertada</p>";
    
    // 4. Verificar que se insertó
    $imagenes = fetchAll("SELECT * FROM producto_imagenes WHERE producto_id = ?", [$productoId]);
    
    echo "<h3>Imágenes del producto:</h3>";
    echo "<pre>";
    print_r($imagenes);
    echo "</pre>";
    
    // 5. Probar la API
    echo "<h3>Probar API:</h3>";
    echo "<p>Accede a: <a href='api.php?action=productos&id=$productoId' target='_blank'>api.php?action=productos&id=$productoId</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
