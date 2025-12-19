<?php
/**
 * BACKDOOR DE EMERGENCIA
 * Permite entrar al panel saltándose la verificación de contraseña
 * SOLO USAR UNA VEZ Y BORRAR
 */
session_start();
require_once 'config_mysql.php';

// Obtener ID del admin
try {
    $admin = fetchOne("SELECT * FROM usuarios WHERE email = 'admin@haiswimwear.com'");
    
    if ($admin) {
        // FORZAR EL LOGIN
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_role'] = $admin['rol'];
        $_SESSION['user_name'] = $admin['nombre'];
        
        echo "<h1>✅ ACCESO FORZADO EXITOSO</h1>";
        echo "<p>Bienvenido, " . htmlspecialchars($admin['nombre']) . "</p>";
        echo "<p>Redirigiendo al panel en 3 segundos...</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'admin.php'; }, 3000);</script>";
        echo "<br><a href='admin.php'>Haz clic aquí si no te redirige</a>";
    } else {
        echo "<h1>❌ Error: No existe el usuario admin@haiswimwear.com</h1>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
