<?php
/**
 * Script para generar nuevo hash de contraseña
 * Sube este archivo y ejecútalo una vez para arreglar el acceso
 */

require_once 'config_mysql.php';

$email = 'admin@haiswimwear.com';
$password = 'admin123'; // Contraseña deseada
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<h1>Restablecer Contraseña Admin</h1>";
echo "<p>Email: $email</p>";
echo "<p>Password: $password</p>";
echo "<p>Hash generado: $hash</p>";

try {
    $sql = "UPDATE usuarios SET password = ?, rol = 'super_admin', activo = 1 WHERE email = ?";
    $stmt = executeQuery($sql, [$hash, $email]);
    
    if ($stmt && $stmt->rowCount() > 0) {
        echo "<h2 style='color:green'>✅ Contraseña actualizada correctamente</h2>";
    } else {
        // Si no actualizó nada, intentamos insertar el usuario
        $sqlInsert = "INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (?, ?, ?, 'super_admin', 1)";
        $id = insertAndGetId($sqlInsert, ['Administrador', $email, $hash]);
        
        if ($id) {
            echo "<h2 style='color:green'>✅ Usuario creado correctamente</h2>";
        } else {
            echo "<h2 style='color:orange'>⚠️ No se actualizó ningún registro (¿tal vez ya tenía esa contraseña?)</h2>";
            
            // Verificar si el usuario existe
            $user = fetchOne("SELECT * FROM usuarios WHERE email = ?", [$email]);
            if ($user) {
                echo "<p>El usuario existe en la base de datos.</p>";
                echo "<p>Hash en DB: " . $user['password'] . "</p>";
                echo "<p>Hash nuevo: " . $hash . "</p>";
            } else {
                echo "<p style='color:red'>❌ El usuario no existe en la base de datos.</p>";
            }
        }
    }
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
