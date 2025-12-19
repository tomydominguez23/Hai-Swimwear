<?php
/**
 * Script de Configuración Interactiva
 * Este script te ayudará a configurar la conexión a Supabase
 */

// Verificar si ya existe el archivo de configuración
$configFile = __DIR__ . '/config_supabase.php';
$configExists = file_exists($configFile);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Conexión - Supabase</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .label-hint {
            font-weight: 400;
            color: #999;
            font-size: 12px;
            margin-top: 4px;
        }
        input[type="text"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn:hover {
            background: #333;
        }
        .btn-secondary {
            background: #6b7280;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .info-box {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .info-box h3 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .info-box p {
            color: #1e3a8a;
            font-size: 13px;
            line-height: 1.6;
        }
        .info-box ol {
            margin-left: 20px;
            margin-top: 10px;
        }
        .info-box li {
            margin-bottom: 5px;
        }
        .success {
            background: #d1fae5;
            border-left-color: #10b981;
            color: #065f46;
        }
        .error {
            background: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }
        .warning {
            background: #fef3c7;
            border-left-color: #f59e0b;
            color: #92400e;
        }
        .test-result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Configuración de Supabase</h1>
        <p class="subtitle">Hai Swimwear - Configuración de Base de Datos</p>

        <?php if ($configExists): ?>
            <div class="info-box warning">
                <h3>⚠️ Archivo de configuración existente</h3>
                <p>Ya existe un archivo <code>config_supabase.php</code>. Si continúas, se sobrescribirá.</p>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <h3>📋 ¿Dónde encontrar estos datos?</h3>
            <ol>
                <li>Ve a tu proyecto en <a href="https://app.supabase.com" target="_blank">Supabase Dashboard</a></li>
                <li>Haz clic en <strong>Settings</strong> (Configuración)</li>
                <li>Selecciona <strong>Database</strong></li>
                <li>En la sección <strong>Connection string</strong> o <strong>Connection info</strong> encontrarás:</li>
                <ul style="margin-left: 20px; margin-top: 5px;">
                    <li><strong>Host:</strong> db.xxxxxxxxxxxxx.supabase.co</li>
                    <li><strong>Database:</strong> postgres (generalmente)</li>
                    <li><strong>User:</strong> postgres (generalmente)</li>
                    <li><strong>Password:</strong> Tu contraseña (la que configuraste)</li>
                    <li><strong>Port:</strong> 5432</li>
                </ul>
            </ol>
        </div>

        <?php
        // Procesar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host = trim($_POST['host'] ?? '');
            $db = trim($_POST['db'] ?? 'postgres');
            $user = trim($_POST['user'] ?? 'postgres');
            $pass = trim($_POST['pass'] ?? '');
            $port = trim($_POST['port'] ?? '5432');

            if (empty($host) || empty($pass)) {
                echo '<div class="info-box error"><h3>❌ Error</h3><p>Por favor, completa al menos el Host y la Contraseña.</p></div>';
            } else {
                // Crear archivo de configuración
                $configContent = generateConfigFile($host, $db, $user, $pass, $port);
                
                if (file_put_contents($configFile, $configContent)) {
                    echo '<div class="info-box success"><h3>✅ Configuración guardada</h3><p>El archivo <code>config_supabase.php</code> se ha creado correctamente.</p></div>';
                    
                    // Probar conexión
                    echo '<div class="test-result">';
                    testConnection($host, $db, $user, $pass, $port);
                    echo '</div>';
                } else {
                    echo '<div class="info-box error"><h3>❌ Error</h3><p>No se pudo crear el archivo. Verifica los permisos de la carpeta.</p></div>';
                }
            }
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="host">Host de Supabase *</label>
                <input type="text" id="host" name="host" 
                       value="<?php echo htmlspecialchars($_POST['host'] ?? ''); ?>" 
                       placeholder="db.xxxxxxxxxxxxx.supabase.co" required>
                <span class="label-hint">Formato: db.xxxxxxxxxxxxx.supabase.co</span>
            </div>

            <div class="form-group">
                <label for="db">Nombre de la Base de Datos</label>
                <input type="text" id="db" name="db" 
                       value="<?php echo htmlspecialchars($_POST['db'] ?? 'postgres'); ?>" 
                       placeholder="postgres">
                <span class="label-hint">Generalmente es 'postgres'</span>
            </div>

            <div class="form-group">
                <label for="user">Usuario</label>
                <input type="text" id="user" name="user" 
                       value="<?php echo htmlspecialchars($_POST['user'] ?? 'postgres'); ?>" 
                       placeholder="postgres">
                <span class="label-hint">Generalmente es 'postgres'</span>
            </div>

            <div class="form-group">
                <label for="pass">Contraseña *</label>
                <div class="password-toggle">
                    <input type="password" id="pass" name="pass" 
                           value="<?php echo htmlspecialchars($_POST['pass'] ?? ''); ?>" 
                           placeholder="Tu contraseña de Supabase" required>
                    <button type="button" onclick="togglePassword()">👁️</button>
                </div>
                <span class="label-hint">La contraseña que configuraste al crear el proyecto</span>
            </div>

            <div class="form-group">
                <label for="port">Puerto</label>
                <input type="number" id="port" name="port" 
                       value="<?php echo htmlspecialchars($_POST['port'] ?? '5432'); ?>" 
                       placeholder="5432">
                <span class="label-hint">Generalmente es 5432</span>
            </div>

            <button type="submit" class="btn">💾 Guardar Configuración</button>
            
            <?php if ($configExists): ?>
                <a href="test_connection.php" class="btn btn-secondary" style="text-decoration: none; display: block; text-align: center; margin-top: 10px;">
                    🧪 Probar Conexión Actual
                </a>
            <?php endif; ?>
        </form>

        <div class="info-box" style="margin-top: 30px;">
            <h3>🔒 Seguridad</h3>
            <p>El archivo <code>config_supabase.php</code> contiene información sensible. Asegúrate de:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>No subirlo a repositorios públicos (Git)</li>
                <li>Mantenerlo fuera del directorio web público si es posible</li>
                <li>Usar permisos restrictivos (chmod 600)</li>
            </ul>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('pass');
            const button = event.target;
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

<?php

function generateConfigFile($host, $db, $user, $pass, $port) {
    $template = file_get_contents(__DIR__ . '/config_supabase.php.example');
    
    // Reemplazar los valores
    $config = str_replace("define('SUPABASE_HOST', 'db.xxxxxxxxxxxxx.supabase.co');", "define('SUPABASE_HOST', '" . addslashes($host) . "');", $template);
    $config = str_replace("define('SUPABASE_DB', 'postgres');", "define('SUPABASE_DB', '" . addslashes($db) . "');", $config);
    $config = str_replace("define('SUPABASE_USER', 'postgres');", "define('SUPABASE_USER', '" . addslashes($user) . "');", $config);
    $config = str_replace("define('SUPABASE_PASS', 'tu_contraseña_aqui');", "define('SUPABASE_PASS', '" . addslashes($pass) . "');", $config);
    $config = str_replace("define('SUPABASE_PORT', '5432');", "define('SUPABASE_PORT', '" . addslashes($port) . "');", $config);
    
    // Si el ejemplo no tiene el resto del código, usar el template completo
    if (strpos($config, 'class Database') === false) {
        $fullTemplate = file_get_contents(__DIR__ . '/config_supabase.php');
        if ($fullTemplate) {
            // Reemplazar solo las constantes
            $config = preg_replace("/define\('SUPABASE_HOST',\s*'[^']*'\);/", "define('SUPABASE_HOST', '" . addslashes($host) . "');", $fullTemplate);
            $config = preg_replace("/define\('SUPABASE_DB',\s*'[^']*'\);/", "define('SUPABASE_DB', '" . addslashes($db) . "');", $config);
            $config = preg_replace("/define\('SUPABASE_USER',\s*'[^']*'\);/", "define('SUPABASE_USER', '" . addslashes($user) . "');", $config);
            $config = preg_replace("/define\('SUPABASE_PASS',\s*'[^']*'\);/", "define('SUPABASE_PASS', '" . addslashes($pass) . "');", $config);
            $config = preg_replace("/define\('SUPABASE_PORT',\s*'[^']*'\);/", "define('SUPABASE_PORT', '" . addslashes($port) . "');", $config);
        }
    }
    
    return $config;
}

function testConnection($host, $db, $user, $pass, $port) {
    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        $version = $pdo->query("SELECT version()")->fetchColumn();
        
        echo '<div class="info-box success">';
        echo '<h3>✅ Conexión Exitosa</h3>';
        echo '<p><strong>Versión PostgreSQL:</strong> ' . htmlspecialchars(substr($version, 0, 50)) . '...</p>';
        
        // Verificar tablas
        $tables = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name")->fetchAll(PDO::FETCH_COLUMN);
        if (count($tables) > 0) {
            echo '<p><strong>Tablas encontradas:</strong> ' . count($tables) . '</p>';
            echo '<p style="font-size: 12px; margin-top: 5px;">' . implode(', ', array_slice($tables, 0, 5)) . (count($tables) > 5 ? '...' : '') . '</p>';
        } else {
            echo '<p style="color: #f59e0b;"><strong>⚠️ No se encontraron tablas.</strong> Necesitas importar el schema SQL.</p>';
        }
        
        echo '</div>';
        
    } catch (PDOException $e) {
        echo '<div class="info-box error">';
        echo '<h3>❌ Error de Conexión</h3>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p style="font-size: 12px; margin-top: 10px;"><strong>Posibles soluciones:</strong></p>';
        echo '<ul style="margin-left: 20px; font-size: 12px;">';
        echo '<li>Verifica que el host sea correcto</li>';
        echo '<li>Verifica que la contraseña sea correcta</li>';
        echo '<li>Asegúrate de que tu proyecto Supabase esté activo</li>';
        echo '<li>Verifica que no haya restricciones de red/IP</li>';
        echo '</ul>';
        echo '</div>';
    }
}

?>

