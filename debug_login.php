<?php
/**
 * Script de diagnóstico de login
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config_mysql.php';

echo "<h1>Diagnóstico de Login</h1>";

$email = 'admin@haiswimwear.com';
$password = 'admin123';

echo "<p>Probando con: $email / $password</p>";

try {
    // 1. Verificar si conecta a la DB
    $db = getDB();
    echo "<p style='color:green'>✅ Conexión a DB exitosa</p>";
    
    // 2. Buscar usuario
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color:green'>✅ Usuario encontrado (ID: " . $user['id'] . ")</p>";
        echo "<p>Hash en DB: " . htmlspecialchars($user['password']) . "</p>";
        
        // 3. Verificar hash
        if (password_verify($password, $user['password'])) {
            echo "<h2 style='color:green'>✅ LA CONTRASEÑA ES CORRECTA</h2>";
            echo "<p>El problema podría estar en las sesiones de PHP.</p>";
            
            // Verificar sesiones
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['test'] = 'funciona';
            echo "<p>Prueba de sesión: " . $_SESSION['test'] . "</p>";
        } else {
            echo "<h2 style='color:red'>❌ LA CONTRASEÑA ES INCORRECTA</h2>";
            echo "<p>Hash generado ahora: " . password_hash($password, PASSWORD_BCRYPT) . "</p>";
            
            // Forzar actualización directa sin hash para probar
            // OJO: Esto es solo para depurar, no seguro para producción
            // $newHash = password_hash($password, PASSWORD_BCRYPT);
            // executeQuery("UPDATE usuarios SET password = ? WHERE id = ?", [$newHash, $user['id']]);
            // echo "<p>Se intentó actualizar la contraseña nuevamente.</p>";
        }
    } else {
        echo "<h2 style='color:red'>❌ Usuario NO encontrado</h2>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>
