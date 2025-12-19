<?php
/**
 * Script de prueba simple para verificar que PHP funciona
 */

// Probar que PHP funciona
echo json_encode([
    'success' => true,
    'message' => 'PHP está funcionando correctamente',
    'php_version' => phpversion(),
    'timestamp' => date('Y-m-d H:i:s')
], JSON_UNESCAPED_UNICODE);

?>

