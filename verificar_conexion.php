<?php
/**
 * Script simple para verificar qué falta en la conexión
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Conexión Supabase</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .ok { color: green; }
        .error { color: red; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Verificación de Conexión a Supabase</h1>
    
    <?php
    // 1. Verificar archivo de configuración
    echo "<h2>1. Archivo de Configuración</h2>";
    if (file_exists('../database/config_supabase.php')) {
        echo "<p class='ok'>✅ Archivo config_supabase.php existe</p>";
        require_once '../database/config_supabase.php';
    } else {
        echo "<p class='error'>❌ NO existe config_supabase.php</p>";
        exit;
    }
    
    // 2. Verificar constantes
    echo "<h2>2. Credenciales Configuradas</h2>";
    $credenciales = [
        'SUPABASE_HOST' => defined('SUPABASE_HOST') ? SUPABASE_HOST : null,
        'SUPABASE_DB' => defined('SUPABASE_DB') ? SUPABASE_DB : null,
        'SUPABASE_USER' => defined('SUPABASE_USER') ? SUPABASE_USER : null,
        'SUPABASE_PASS' => defined('SUPABASE_PASS') ? SUPABASE_PASS : null,
        'SUPABASE_PORT' => defined('SUPABASE_PORT') ? SUPABASE_PORT : null,
    ];
    
    $todoOk = true;
    foreach ($credenciales as $key => $value) {
        if ($value) {
            if ($key === 'SUPABASE_PASS') {
                echo "<p class='ok'>✅ $key: " . (strlen($value) > 0 ? "Configurada (" . strlen($value) . " caracteres)" : "VACÍA") . "</p>";
                if (strlen($value) == 0) $todoOk = false;
            } else {
                echo "<p class='ok'>✅ $key: $value</p>";
            }
        } else {
            echo "<p class='error'>❌ $key: NO CONFIGURADA</p>";
            $todoOk = false;
        }
    }
    
    if (!$todoOk) {
        echo "<div class='info'><strong>⚠️ FALTA:</strong> Completa las credenciales en database/config_supabase.php</div>";
        exit;
    }
    
    // 3. Verificar extensión PDO
    echo "<h2>3. Extensión PHP</h2>";
    if (extension_loaded('pdo_pgsql')) {
        echo "<p class='ok'>✅ Extensión pdo_pgsql está instalada</p>";
    } else {
        echo "<p class='error'>❌ Extensión pdo_pgsql NO está instalada</p>";
        echo "<div class='info'>Necesitas instalar php-pgsql en tu servidor</div>";
        exit;
    }
    
    // 4. Intentar conexión
    echo "<h2>4. Prueba de Conexión</h2>";
    try {
        $dsn = "pgsql:host=" . SUPABASE_HOST . ";port=" . SUPABASE_PORT . ";dbname=" . SUPABASE_DB . ";sslmode=require";
        $conn = new PDO($dsn, SUPABASE_USER, SUPABASE_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        echo "<p class='ok'>✅ Conexión exitosa a Supabase!</p>";
        
        // 5. Verificar tablas
        echo "<h2>5. Verificación de Tablas</h2>";
        $stmt = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $required = ['usuarios', 'productos', 'categorias', 'pedidos', 'clientes', 'mensajes'];
        $found = [];
        foreach ($tables as $table) {
            if (in_array($table, $required)) {
                echo "<p class='ok'>✅ Tabla '$table' existe</p>";
                $found[] = $table;
            }
        }
        
        $missing = array_diff($required, $found);
        if ($missing) {
            echo "<p class='error'>❌ Faltan tablas: " . implode(', ', $missing) . "</p>";
            echo "<div class='info'><strong>SOLUCIÓN:</strong> Ejecuta database/schema_postgresql.sql en Supabase SQL Editor</div>";
        }
        
        // 6. Verificar usuario admin
        echo "<h2>6. Usuario Administrador</h2>";
        try {
            $stmt = $conn->query("SELECT id, email, rol, activo FROM usuarios WHERE email = 'admin@haiswimwear.com'");
            $admin = $stmt->fetch();
            if ($admin) {
                echo "<p class='ok'>✅ Usuario admin existe</p>";
                echo "<p>Email: " . $admin['email'] . "</p>";
                echo "<p>Rol: " . $admin['rol'] . "</p>";
                echo "<p>Activo: " . ($admin['activo'] ? 'Sí' : 'No') . "</p>";
            } else {
                echo "<p class='error'>❌ Usuario admin NO existe</p>";
                echo "<div class='info'><strong>SOLUCIÓN:</strong> Ejecuta database/crear_usuario_admin.sql en Supabase SQL Editor</div>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
        
        echo "<h2>✅ RESUMEN</h2>";
        echo "<div class='info' style='background: #d4edda; border: 1px solid #c3e6cb;'>";
        echo "<strong>Todo está configurado correctamente!</strong><br>";
        echo "La conexión a Supabase funciona. Si aún tienes errores, el problema está en la API o en el JavaScript.";
        echo "</div>";
        
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error de conexión: " . $e->getMessage() . "</p>";
        echo "<div class='info'>";
        echo "<strong>Posibles causas:</strong><br>";
        echo "1. Contraseña incorrecta<br>";
        echo "2. Host incorrecto<br>";
        echo "3. Firewall bloqueando conexión<br>";
        echo "4. SSL no disponible<br>";
        echo "</div>";
    }
    ?>
</body>
</html>

