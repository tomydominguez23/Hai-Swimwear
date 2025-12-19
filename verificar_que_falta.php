<?php
/**
 * Script para verificar EXACTAMENTE qué falta de la base de datos
 * Abre este archivo en tu navegador
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verificar qué Falta - Base de Datos</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
        .error-box { background: #ffebee; padding: 15px; margin: 10px 0; border-left: 4px solid #f44336; }
        .success-box { background: #e8f5e9; padding: 15px; margin: 10px 0; border-left: 4px solid #4caf50; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        code { background: #f5f5f5; padding: 2px 5px; }
    </style>
</head>
<body>
    <h1>🔍 Verificación de Base de Datos - Supabase</h1>
    <p>Este script verificará EXACTAMENTE qué falta para completar la vinculación.</p>
    
    <?php
    // 1. Verificar archivo de configuración
    echo "<h2>1. Archivo de Configuración</h2>";
    if (file_exists('../database/config_supabase.php')) {
        echo "<p class='ok'>✅ Archivo config_supabase.php existe</p>";
        require_once '../database/config_supabase.php';
    } else {
        echo "<div class='error-box'>";
        echo "<p class='error'>❌ NO existe config_supabase.php</p>";
        echo "<p>Necesitas crear este archivo con tus credenciales de Supabase.</p>";
        echo "</div>";
        exit;
    }
    
    // 2. Verificar credenciales
    echo "<h2>2. Credenciales de Conexión</h2>";
    $credenciales = [
        'SUPABASE_HOST' => defined('SUPABASE_HOST') ? SUPABASE_HOST : null,
        'SUPABASE_DB' => defined('SUPABASE_DB') ? SUPABASE_DB : null,
        'SUPABASE_USER' => defined('SUPABASE_USER') ? SUPABASE_USER : null,
        'SUPABASE_PASS' => defined('SUPABASE_PASS') ? SUPABASE_PASS : null,
        'SUPABASE_PORT' => defined('SUPABASE_PORT') ? SUPABASE_PORT : null,
    ];
    
    $credencialesOk = true;
    foreach ($credenciales as $key => $value) {
        if ($value && strlen($value) > 0) {
            if ($key === 'SUPABASE_PASS') {
                echo "<p class='ok'>✅ $key: Configurada (" . strlen($value) . " caracteres)</p>";
            } else {
                echo "<p class='ok'>✅ $key: <code>$value</code></p>";
            }
        } else {
            echo "<p class='error'>❌ $key: NO CONFIGURADA</p>";
            $credencialesOk = false;
        }
    }
    
    if (!$credencialesOk) {
        echo "<div class='error-box'>";
        echo "<h3>⚠️ FALTA: Credenciales de Conexión</h3>";
        echo "<p>Necesitas completar estas credenciales en <code>database/config_supabase.php</code>:</p>";
        echo "<ul>";
        foreach ($credenciales as $key => $value) {
            if (!$value || strlen($value) == 0) {
                echo "<li><strong>$key</strong> - Obtén este valor en Supabase Dashboard > Settings > Database</li>";
            }
        }
        echo "</ul>";
        echo "</div>";
        exit;
    }
    
    // 3. Verificar extensión PHP
    echo "<h2>3. Extensión PHP PostgreSQL</h2>";
    if (extension_loaded('pdo_pgsql')) {
        echo "<p class='ok'>✅ Extensión pdo_pgsql está instalada</p>";
    } else {
        echo "<div class='error-box'>";
        echo "<p class='error'>❌ Extensión pdo_pgsql NO está instalada</p>";
        echo "<h3>SOLUCIÓN:</h3>";
        echo "<p>1. Abre <code>php.ini</code> en tu servidor</p>";
        echo "<p>2. Busca la línea: <code>;extension=pdo_pgsql</code></p>";
        echo "<p>3. Quita el punto y coma: <code>extension=pdo_pgsql</code></p>";
        echo "<p>4. Reinicia Apache</p>";
        echo "</div>";
        exit;
    }
    
    // 4. Intentar conexión
    echo "<h2>4. Prueba de Conexión a Supabase</h2>";
    try {
        $dsn = "pgsql:host=" . SUPABASE_HOST . 
               ";port=" . SUPABASE_PORT . 
               ";dbname=" . SUPABASE_DB . 
               ";sslmode=require";
        
        $conn = new PDO($dsn, SUPABASE_USER, SUPABASE_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        echo "<div class='success-box'>";
        echo "<p class='ok'>✅ Conexión exitosa a Supabase!</p>";
        echo "</div>";
        
        // 5. Verificar tablas
        echo "<h2>5. Verificación de Tablas</h2>";
        $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $requiredTables = [
            'usuarios' => 'Usuarios del sistema',
            'productos' => 'Productos',
            'categorias' => 'Categorías de productos',
            'pedidos' => 'Pedidos',
            'clientes' => 'Clientes',
            'mensajes' => 'Mensajes de contacto',
            'cotizaciones' => 'Cotizaciones',
            'imagenes_web' => 'Imágenes del sitio',
            'configuracion' => 'Configuración del sistema'
        ];
        
        $foundTables = [];
        $missingTables = [];
        
        foreach ($requiredTables as $table => $desc) {
            if (in_array($table, $tables)) {
                echo "<p class='ok'>✅ Tabla <code>$table</code> existe ($desc)</p>";
                $foundTables[] = $table;
            } else {
                echo "<p class='error'>❌ Tabla <code>$table</code> NO existe ($desc)</p>";
                $missingTables[] = $table;
            }
        }
        
        if ($missingTables) {
            echo "<div class='error-box'>";
            echo "<h3>⚠️ FALTAN TABLAS</h3>";
            echo "<p>Necesitas ejecutar el schema SQL en Supabase:</p>";
            echo "<ol>";
            echo "<li>Ve a <a href='https://app.supabase.com' target='_blank'>Supabase Dashboard</a></li>";
            echo "<li>Selecciona tu proyecto</li>";
            echo "<li>Ve a <strong>SQL Editor</strong></li>";
            echo "<li>Abre el archivo: <code>database/schema_postgresql.sql</code></li>";
            echo "<li>Copia TODO el contenido y pégalo en el SQL Editor</li>";
            echo "<li>Haz clic en <strong>Run</strong> o presiona Ctrl+Enter</li>";
            echo "</ol>";
            echo "<p><strong>Tablas faltantes:</strong> " . implode(', ', $missingTables) . "</p>";
            echo "</div>";
        }
        
        // 6. Verificar extensión pgcrypto
        echo "<h2>6. Extensión pgcrypto (para contraseñas)</h2>";
        try {
            $stmt = $conn->query("SELECT * FROM pg_extension WHERE extname = 'pgcrypto'");
            $pgcrypto = $stmt->fetch();
            if ($pgcrypto) {
                echo "<p class='ok'>✅ Extensión pgcrypto está habilitada</p>";
            } else {
                echo "<div class='warning-box' style='background: #fff3e0; border-left: 4px solid #ff9800; padding: 15px; margin: 10px 0;'>";
                echo "<p class='warning'>⚠️ Extensión pgcrypto NO está habilitada</p>";
                echo "<p><strong>SOLUCIÓN:</strong> Ejecuta en Supabase SQL Editor:</p>";
                echo "<code>CREATE EXTENSION IF NOT EXISTS pgcrypto;</code>";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ No se pudo verificar pgcrypto: " . $e->getMessage() . "</p>";
        }
        
        // 7. Verificar usuario admin
        echo "<h2>7. Usuario Administrador</h2>";
        try {
            $stmt = $conn->query("SELECT id, nombre, email, rol, activo FROM usuarios WHERE email = 'admin@haiswimwear.com'");
            $admin = $stmt->fetch();
            if ($admin) {
                echo "<div class='success-box'>";
                echo "<p class='ok'>✅ Usuario admin existe</p>";
                echo "<ul>";
                echo "<li>ID: " . $admin['id'] . "</li>";
                echo "<li>Nombre: " . htmlspecialchars($admin['nombre']) . "</li>";
                echo "<li>Email: " . htmlspecialchars($admin['email']) . "</li>";
                echo "<li>Rol: " . htmlspecialchars($admin['rol']) . "</li>";
                echo "<li>Activo: " . ($admin['activo'] ? 'Sí ✅' : 'No ❌') . "</li>";
                echo "</ul>";
                echo "</div>";
                
                // Verificar si tiene password
                $stmt2 = $conn->query("SELECT password FROM usuarios WHERE email = 'admin@haiswimwear.com'");
                $passData = $stmt2->fetch();
                if ($passData && !empty($passData['password'])) {
                    echo "<p class='ok'>✅ El usuario tiene contraseña configurada</p>";
                } else {
                    echo "<div class='error-box'>";
                    echo "<p class='error'>❌ El usuario NO tiene contraseña</p>";
                    echo "<p><strong>SOLUCIÓN:</strong> Ejecuta en Supabase SQL Editor el archivo: <code>database/crear_usuario_admin.sql</code></p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='error-box'>";
                echo "<p class='error'>❌ Usuario admin NO existe</p>";
                echo "<h3>SOLUCIÓN:</h3>";
                echo "<ol>";
                echo "<li>Ve a Supabase > SQL Editor</li>";
                echo "<li>Ejecuta el contenido de: <code>database/crear_usuario_admin.sql</code></li>";
                echo "<li>O ejecuta este SQL:</li>";
                echo "</ol>";
                echo "<pre style='background: #f5f5f5; padding: 10px;'>";
                echo "CREATE EXTENSION IF NOT EXISTS pgcrypto;\n\n";
                echo "INSERT INTO usuarios (nombre, email, password, rol, activo)\n";
                echo "VALUES (\n";
                echo "    'Administrador',\n";
                echo "    'admin@haiswimwear.com',\n";
                echo "    crypt('admin123', gen_salt('bf')),\n";
                echo "    'super_admin',\n";
                echo "    true\n";
                echo ")\n";
                echo "ON CONFLICT (email) DO UPDATE SET\n";
                echo "    password = crypt('admin123', gen_salt('bf')),\n";
                echo "    rol = 'super_admin',\n";
                echo "    activo = true;\n";
                echo "</pre>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='error-box'>";
            echo "<p class='error'>❌ Error al verificar usuario: " . $e->getMessage() . "</p>";
            echo "<p>Probablemente la tabla <code>usuarios</code> no existe. Ejecuta el schema SQL primero.</p>";
            echo "</div>";
        }
        
        // 8. Verificar categorías
        echo "<h2>8. Categorías Iniciales</h2>";
        try {
            $stmt = $conn->query("SELECT COUNT(*) as total FROM categorias");
            $catCount = $stmt->fetch();
            if ($catCount && $catCount['total'] > 0) {
                echo "<p class='ok'>✅ Existen " . $catCount['total'] . " categorías</p>";
            } else {
                echo "<div class='warning-box' style='background: #fff3e0; border-left: 4px solid #ff9800; padding: 15px; margin: 10px 0;'>";
                echo "<p class='warning'>⚠️ No hay categorías</p>";
                echo "<p>Ejecuta en Supabase SQL Editor:</p>";
                echo "<pre style='background: #f5f5f5; padding: 10px;'>";
                echo "INSERT INTO categorias (nombre, slug, descripcion, orden)\n";
                echo "VALUES\n";
                echo "    ('Bikini', 'bikini', 'Bikinis de dos piezas', 1),\n";
                echo "    ('Traje de Baño', 'traje-bano', 'Trajes de baño enteros', 2),\n";
                echo "    ('Bikini Entero', 'bikini-entero', 'Bikinis de una pieza', 3),\n";
                echo "    ('Accesorios', 'accesorios', 'Accesorios de playa', 4)\n";
                echo "ON CONFLICT (slug) DO NOTHING;\n";
                echo "</pre>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "<p class='warning'>⚠️ No se pudo verificar categorías (la tabla puede no existir)</p>";
        }
        
        // RESUMEN FINAL
        echo "<h2>📊 RESUMEN</h2>";
        if (empty($missingTables) && $admin) {
            echo "<div class='success-box'>";
            echo "<h3>✅ ¡TODO ESTÁ CONFIGURADO CORRECTAMENTE!</h3>";
            echo "<p>La base de datos está lista para usar. Si aún tienes errores, el problema está en:</p>";
            echo "<ul>";
            echo "<li>La API no está respondiendo (verifica que uses <code>http://localhost</code>)</li>";
            echo "<li>No has iniciado sesión (ve a <code>login.php</code> primero)</li>";
            echo "<li>El servidor PHP no está corriendo</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='error-box'>";
            echo "<h3>⚠️ FALTA CONFIGURAR:</h3>";
            echo "<ul>";
            if (!empty($missingTables)) {
                echo "<li><strong>Tablas:</strong> Ejecuta <code>database/schema_postgresql.sql</code> en Supabase</li>";
            }
            if (!$admin) {
                echo "<li><strong>Usuario Admin:</strong> Ejecuta <code>database/crear_usuario_admin.sql</code> en Supabase</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        
    } catch (PDOException $e) {
        echo "<div class='error-box'>";
        echo "<h3>❌ ERROR DE CONEXIÓN</h3>";
        echo "<p class='error'>" . $e->getMessage() . "</p>";
        echo "<h3>Posibles causas:</h3>";
        echo "<ul>";
        echo "<li><strong>Contraseña incorrecta:</strong> Verifica que <code>SUPABASE_PASS</code> sea la contraseña de la base de datos (NO la API Key)</li>";
        echo "<li><strong>Host incorrecto:</strong> Verifica que <code>SUPABASE_HOST</code> sea correcto</li>";
        echo "<li><strong>Firewall:</strong> Tu servidor puede estar bloqueando la conexión</li>";
        echo "<li><strong>SSL:</strong> Supabase requiere SSL, verifica que tu PHP lo soporte</li>";
        echo "</ul>";
        echo "<h3>Verifica en Supabase:</h3>";
        echo "<ol>";
        echo "<li>Ve a <a href='https://app.supabase.com' target='_blank'>Supabase Dashboard</a></li>";
        echo "<li>Settings > Database</li>";
        echo "<li>Verifica el <strong>Host</strong> y la <strong>Database password</strong></li>";
        echo "</ol>";
        echo "</div>";
    }
    ?>
    
    <hr>
    <h2>📝 Información que Necesito</h2>
    <div class="info">
        <p>Si después de seguir todas las soluciones aún hay problemas, comparte:</p>
        <ol>
            <li>¿Qué pruebas pasaron? (marca con ✅)</li>
            <li>¿Qué pruebas fallaron? (marca con ❌)</li>
            <li>El mensaje de error exacto (si hay alguno)</li>
        </ol>
    </div>
</body>
</html>

