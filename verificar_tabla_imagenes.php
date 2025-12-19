<?php
/**
 * Script para verificar y crear la tabla producto_imagenes
 */

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
        echo "✓ Configuración cargada: " . basename($path) . "\n";
        break;
    }
}

if (!$configLoaded) {
    die("❌ Error: No se encontró archivo de configuración\n");
}

echo "\n=== VERIFICANDO TABLA producto_imagenes ===\n\n";

try {
    // Verificar si la tabla existe
    if (isset($isPostgres) && $isPostgres) {
        echo "Usando PostgreSQL/Supabase\n\n";
        
        // Verificar tabla
        $result = fetchOne("SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_name = 'producto_imagenes'
        ) as exists");
        
        if ($result && $result['exists']) {
            echo "✓ La tabla producto_imagenes existe\n";
            
            // Contar registros
            $count = fetchOne("SELECT COUNT(*) as total FROM producto_imagenes");
            echo "  Total de imágenes: " . ($count['total'] ?? 0) . "\n\n";
            
            // Mostrar estructura
            echo "Estructura de la tabla:\n";
            $columns = fetchAll("
                SELECT column_name, data_type, is_nullable 
                FROM information_schema.columns 
                WHERE table_name = 'producto_imagenes'
                ORDER BY ordinal_position
            ");
            
            foreach ($columns as $col) {
                echo "  - {$col['column_name']} ({$col['data_type']}) " . 
                     ($col['is_nullable'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
            }
            
        } else {
            echo "❌ La tabla producto_imagenes NO existe\n";
            echo "Creando tabla...\n\n";
            
            // Crear tabla para PostgreSQL
            $sql = "CREATE TABLE IF NOT EXISTS producto_imagenes (
                id SERIAL PRIMARY KEY,
                producto_id INTEGER NOT NULL,
                url VARCHAR(255) NOT NULL,
                orden INTEGER DEFAULT 0,
                es_principal BOOLEAN DEFAULT false,
                alt_text VARCHAR(255),
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
            )";
            
            executeQuery($sql);
            
            // Crear índices
            executeQuery("CREATE INDEX IF NOT EXISTS idx_producto ON producto_imagenes(producto_id)");
            executeQuery("CREATE INDEX IF NOT EXISTS idx_principal ON producto_imagenes(es_principal)");
            
            echo "✓ Tabla creada exitosamente\n";
        }
        
    } else {
        echo "Usando MySQL\n\n";
        
        // Verificar tabla
        $result = fetchOne("SHOW TABLES LIKE 'producto_imagenes'");
        
        if ($result) {
            echo "✓ La tabla producto_imagenes existe\n";
            
            // Contar registros
            $count = fetchOne("SELECT COUNT(*) as total FROM producto_imagenes");
            echo "  Total de imágenes: " . ($count['total'] ?? 0) . "\n\n";
            
            // Mostrar estructura
            echo "Estructura de la tabla:\n";
            $columns = fetchAll("DESCRIBE producto_imagenes");
            
            foreach ($columns as $col) {
                echo "  - {$col['Field']} ({$col['Type']}) " . 
                     ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
            }
            
        } else {
            echo "❌ La tabla producto_imagenes NO existe\n";
            echo "Creando tabla...\n\n";
            
            // Crear tabla para MySQL
            $sql = "CREATE TABLE IF NOT EXISTS producto_imagenes (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            executeQuery($sql);
            
            echo "✓ Tabla creada exitosamente\n";
        }
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    
    // Verificar productos existentes sin imágenes
    echo "\n=== PRODUCTOS SIN IMÁGENES ===\n\n";
    
    if (isset($isPostgres) && $isPostgres) {
        $productosSinImagenes = fetchAll("
            SELECT p.id, p.nombre, p.estado
            FROM productos p
            LEFT JOIN producto_imagenes pi ON p.id = pi.producto_id
            WHERE pi.id IS NULL
            ORDER BY p.fecha_creacion DESC
        ");
    } else {
        $productosSinImagenes = fetchAll("
            SELECT p.id, p.nombre, p.estado
            FROM productos p
            LEFT JOIN producto_imagenes pi ON p.id = pi.producto_id
            WHERE pi.id IS NULL
            ORDER BY p.fecha_creacion DESC
        ");
    }
    
    if (empty($productosSinImagenes)) {
        echo "✓ Todos los productos tienen imágenes (o no hay productos)\n";
    } else {
        echo "⚠️  Productos sin imágenes:\n";
        foreach ($productosSinImagenes as $prod) {
            echo "  - ID: {$prod['id']} | {$prod['nombre']} | Estado: {$prod['estado']}\n";
        }
        echo "\nTIP: Edita estos productos en el panel admin para agregar imágenes\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✓ Script finalizado\n";
?>
