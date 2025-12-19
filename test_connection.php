<?php
/**
 * Script de prueba de conexión a Supabase
 * Ejecuta este archivo para verificar que la conexión funciona
 */

require_once 'config_supabase.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - Supabase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #10b981;
            background: #d1fae5;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .error {
            color: #ef4444;
            background: #fee2e2;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info {
            background: #dbeafe;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        h1 {
            color: #333;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔌 Test de Conexión a Supabase</h1>
        
        <?php
        echo '<div class="info">';
        echo '<strong>Configuración actual:</strong><br>';
        echo 'Host: <code>' . SUPABASE_HOST . '</code><br>';
        echo 'Base de datos: <code>' . SUPABASE_DB . '</code><br>';
        echo 'Usuario: <code>' . SUPABASE_USER . '</code><br>';
        echo 'Puerto: <code>' . SUPABASE_PORT . '</code><br>';
        echo '</div>';

        // Probar conexión
        $test = testConnection();
        
        if ($test['success']) {
            echo '<div class="success">';
            echo '✅ <strong>' . $test['message'] . '</strong><br>';
            echo 'Versión PostgreSQL: ' . ($test['version'] ?? 'N/A');
            echo '</div>';
            
            // Probar consultas básicas
            echo '<h2>Pruebas de Consultas</h2>';
            
            // Verificar si existen las tablas
            $tables = fetchAll("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
            
            if (count($tables) > 0) {
                echo '<div class="success">';
                echo '✅ <strong>Tablas encontradas:</strong> ' . count($tables) . '<br>';
                echo '<ul>';
                foreach ($tables as $table) {
                    echo '<li>' . $table['table_name'] . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '⚠️ No se encontraron tablas. Necesitas importar el schema SQL.';
                echo '</div>';
            }
            
            // Probar inserción y lectura
            try {
                $configTest = fetchOne("SELECT valor FROM configuracion WHERE clave = 'nombre_sitio'");
                if ($configTest) {
                    echo '<div class="success">';
                    echo '✅ Configuración encontrada: <code>nombre_sitio = ' . $configTest['valor'] . '</code>';
                    echo '</div>';
                }
            } catch(Exception $e) {
                echo '<div class="error">';
                echo '⚠️ Error al leer configuración: ' . $e->getMessage();
                echo '</div>';
            }
            
        } else {
            echo '<div class="error">';
            echo '❌ <strong>' . $test['message'] . '</strong><br>';
            echo '<br><strong>Posibles soluciones:</strong><br>';
            echo '1. Verifica que las credenciales en <code>config_supabase.php</code> sean correctas<br>';
            echo '2. Asegúrate de que tu proyecto Supabase esté activo<br>';
            echo '3. Verifica que la IP desde la que te conectas esté permitida en Supabase<br>';
            echo '4. Revisa que el puerto 5432 esté abierto';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <strong>📝 Notas:</strong><br>
            • Si ves errores, verifica tu archivo <code>config_supabase.php</code><br>
            • Asegúrate de haber importado el schema SQL en Supabase<br>
            • Puedes encontrar tus credenciales en: Supabase Dashboard > Settings > Database
        </div>
    </div>
</body>
</html>

