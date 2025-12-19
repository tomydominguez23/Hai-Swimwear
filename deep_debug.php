<?php
/**
 * Script de DIAGNÓSTICO PROFUNDO DE AUTENTICACIÓN
 * Hai Swimwear
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'config_mysql.php';

echo "<h1>🔍 Diagnóstico Profundo de Autenticación</h1>";
echo "<p>Versión PHP: " . phpversion() . "</p>";
echo "<p>Host DB: " . DB_HOST . "</p>";

// 1. Verificar sesión
echo "<h2>1. Estado de Sesión</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "<p>Cookie de sesión: " . (isset($_COOKIE[session_name()]) ? 'Presente' : 'Ausente') . "</p>";

// 2. Probar conexión y usuario
echo "<h2>2. Verificación de Usuario en DB</h2>";
$email = 'admin@haiswimwear.com';
$pass = 'admin123';

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<p style='color:green'>✅ Usuario encontrado.</p>";
        echo "<ul>";
        echo "<li>ID: " . $user['id'] . "</li>";
        echo "<li>Email: " . $user['email'] . "</li>";
        echo "<li>Rol: " . $user['rol'] . "</li>";
        echo "<li>Hash almacenado: " . $user['password'] . "</li>";
        echo "</ul>";

        // 3. Probar verificación de contraseña
        echo "<h2>3. Verificación de Contraseña</h2>";
        if (password_verify($pass, $user['password'])) {
            echo "<p style='color:green; font-weight:bold; font-size:18px;'>✅ password_verify() DICE QUE SÍ COINCIDEN</p>";
            echo "<p>Si el login falla, el problema es al guardar la sesión o redireccionar.</p>";
        } else {
            echo "<p style='color:red; font-weight:bold; font-size:18px;'>❌ password_verify() DICE QUE NO COINCIDEN</p>";
            echo "<p>Probando generar nuevo hash localmente...</p>";
            $newHash = password_hash($pass, PASSWORD_DEFAULT);
            echo "<p>Hash generado ahora mismo: $newHash</p>";
            
            // Intento de actualización directa
            echo "<h3>4. Intento de Reparación Automática</h3>";
            $upd = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            if ($upd->execute([$newHash, $user['id']])) {
                echo "<p style='color:blue'>🛠️ Se actualizó la contraseña con el nuevo hash generado por este servidor.</p>";
                echo "<p>Prueba verificar de nuevo recargando esta página.</p>";
            } else {
                echo "<p style='color:red'>❌ Error al intentar actualizar.</p>";
            }
        }
    } else {
        echo "<p style='color:red'>❌ El usuario '$email' no existe en la tabla 'usuarios'.</p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red'>Error crítico: " . $e->getMessage() . "</p>";
}
?>
