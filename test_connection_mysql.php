<?php
/**
 * Script de prueba de conexión a MySQL
 * Hai Swimwear
 */

require_once '../database/config_mysql.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión MySQL - Hai Swimwear</title>
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
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #bee5eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-ok {
            background: #28a745;
            color: white;
        }
        .status-error {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test de Conexión MySQL</h1>
        
        <?php
        try {
            $result = testConnection();
            
            if ($result['success']) {
                echo '<div class="success">';
                echo '<h2>✅ Conexión exitosa a MySQL</h2>';
                echo '<p><strong>Versión:</strong> ' . htmlspecialchars($result['version']) . '</p>';
                echo '</div>';
                
                // Probar consultas
                echo '<div class="info">';
                echo '<h3>📊 Pruebas de Consultas</h3>';
                
                // Contar tablas
                $tables = fetchAll("SHOW TABLES");
                $tableCount = count($tables);
                echo '<p><strong>Tablas encontradas:</strong> ' . $tableCount . '</p>';
                
                if ($tableCount > 0) {
                    echo '<table>';
                    echo '<tr><th>Tabla</th><th>Estado</th></tr>';
                    foreach ($tables as $table) {
                        $tableName = array_values($table)[0];
                        $count = fetchOne("SELECT COUNT(*) as count FROM `$tableName`");
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($tableName) . '</td>';
                        echo '<td><span class="status status-ok">OK (' . $count['count'] . ' registros)</span></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
                
                // Verificar usuario admin
                $admin = fetchOne("SELECT * FROM usuarios WHERE email = ?", ['admin@haiswimwear.com']);
                if ($admin) {
                    echo '<div class="success">';
                    echo '<h3>✅ Usuario Administrador</h3>';
                    echo '<p><strong>Email:</strong> ' . htmlspecialchars($admin['email']) . '</p>';
                    echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($admin['nombre']) . '</p>';
                    echo '<p><strong>Rol:</strong> ' . htmlspecialchars($admin['rol']) . '</p>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h3>⚠️ Usuario Administrador no encontrado</h3>';
                    echo '<p>Ejecuta el SQL completo para crear el usuario admin.</p>';
                    echo '</div>';
                }
                
                // Verificar categorías
                $categorias = fetchAll("SELECT * FROM categorias");
                if (count($categorias) > 0) {
                    echo '<div class="success">';
                    echo '<h3>✅ Categorías</h3>';
                    echo '<p>Se encontraron ' . count($categorias) . ' categorías.</p>';
                    echo '</div>';
                }
                
                echo '</div>';
                
            } else {
                echo '<div class="error">';
                echo '<h2>❌ Error de Conexión</h2>';
                echo '<p>' . htmlspecialchars($result['message']) . '</p>';
                echo '</div>';
                
                echo '<div class="info">';
                echo '<h3>🔧 Verifica:</h3>';
                echo '<ul>';
                echo '<li>Que MySQL esté corriendo (XAMPP, WAMP, etc.)</li>';
                echo '<li>Las credenciales en <code>database/config_mysql.php</code></li>';
                echo '<li>Que la base de datos <code>hai_swimwear</code> exista</li>';
                echo '</ul>';
                echo '</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h3>📝 Información de Configuración</h3>
            <table>
                <tr>
                    <th>Parámetro</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Host</td>
                    <td><?php echo htmlspecialchars(DB_HOST); ?></td>
                </tr>
                <tr>
                    <td>Base de Datos</td>
                    <td><?php echo htmlspecialchars(DB_NAME); ?></td>
                </tr>
                <tr>
                    <td>Usuario</td>
                    <td><?php echo htmlspecialchars(DB_USER); ?></td>
                </tr>
                <tr>
                    <td>Puerto</td>
                    <td><?php echo htmlspecialchars(DB_PORT); ?></td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><a href="login.php">→ Ir al Panel de Administración</a></p>
            <p><a href="../index.html">→ Ir al Frontend</a></p>
        </div>
    </div>
</body>
</html>



