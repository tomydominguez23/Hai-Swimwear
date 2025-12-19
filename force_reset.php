<?php
/**
 * Script DE RESCATE para forzar actualización de contraseña
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config_mysql.php';

echo "<h1>🛠️ Rescate de Contraseña</h1>";

$email = 'admin@haiswimwear.com';
$password = 'admin123';
$newHash = password_hash($password, PASSWORD_DEFAULT); // Usar DEFAULT para máxima compatibilidad

try {
    $db = getDB();
    
    echo "<p>Intentando actualizar contraseña para: <strong>$email</strong></p>";
    echo "<p>Nueva contraseña plana: <strong>$password</strong></p>";
    echo "<p>Nuevo Hash generado: <small>$newHash</small></p>";
    
    $sql = "UPDATE usuarios SET password = ? WHERE email = ?";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$newHash, $email]);
    
    if ($result) {
        echo "<h2 style='color:green'>✅ ¡ACTUALIZACIÓN FORZADA EXITOSA!</h2>";
        echo "<p>La base de datos confirmó el cambio.</p>";
        
        // Verificación inmediata
        $check = fetchOne("SELECT password FROM usuarios WHERE email = ?", [$email]);
        echo "<p>Hash leído de DB ahora: <small>" . $check['password'] . "</small></p>";
        
        if (password_verify($password, $check['password'])) {
            echo "<h2 style='color:blue'>🎉 VERIFICACIÓN DE PHP: ¡AHORA SÍ COINCIDEN!</h2>";
            echo "<p><a href='login.php' style='font-size:20px; font-weight:bold'>👉 HAZ CLIC AQUÍ PARA INICIAR SESIÓN</a></p>";
        } else {
            echo "<h2 style='color:red'>⚠️ ALERTA: PHP sigue sin verificar el hash. Versión PHP: " . phpversion() . "</h2>";
        }
        
    } else {
        echo "<h2 style='color:red'>❌ Falló la actualización SQL</h2>";
        print_r($stmt->errorInfo());
    }
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ Error Fatal: " . $e->getMessage() . "</h2>";
}
?>
