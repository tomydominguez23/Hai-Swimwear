<?php
/**
 * Test simple de conexión a la API
 */

header('Content-Type: application/json');

// Simular respuesta de la API
$response = [
    'success' => true,
    'message' => 'API funcionando',
    'data' => [
        'test' => 'OK',
        'php_version' => phpversion()
    ]
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>

